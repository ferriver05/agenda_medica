<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Disponibilidad;
use App\Models\Cita;


class CitaController extends Controller
{
    public function mostrarFormularioReserva()
    {
        $especialidades = Especialidad::all();
        return view('paciente.reservar', compact('especialidades'));
    }

    // Método para obtener médicos por especialidad (AJAX)
    public function obtenerMedicosPorEspecialidad($especialidad_id)
    {
        $medicos = Medico::whereHas('especialidades', function($query) use ($especialidad_id) {
            $query->where('especialidad_id', $especialidad_id);
        })
        ->with('user') // Cargar la relación 'user'
        ->get();
    
        return response()->json($medicos);
    }

    // Método para obtener horas disponibles por médico y fecha (AJAX)
    public function obtenerHorasDisponibles($medico_id, $fecha)
    {
        // Obtener la disponibilidad del médico para el día de la semana de la fecha seleccionada
        $diaSemana = date('w', strtotime($fecha)); // 0 (Domingo) a 6 (Sábado)
        $disponibilidades = Disponibilidad::where('medico_id', $medico_id)
            ->where('dia_semana', $diaSemana)
            ->get();
    
        // Obtener las horas de inicio de las citas ya reservadas para ese médico en esa fecha
        $horasOcupadas = Cita::where('medico_id', $medico_id)
            ->where('fecha', $fecha)
            ->pluck('hora_inicio')
            ->toArray();
    
        // Calcular las horas disponibles
        $horasDisponibles = [];
        foreach ($disponibilidades as $disponibilidad) {
            $horaInicio = strtotime($disponibilidad->hora_inicio);
            $horaFin = strtotime($disponibilidad->hora_fin);
    
            while ($horaInicio < $horaFin) {
                $horaFormateada = date('H:i:s', $horaInicio); // Formato hh:mm:ss
    
                // Verificar si la hora está ocupada
                if (!in_array($horaFormateada, $horasOcupadas)) {
                    $horasDisponibles[] = date('H:i', $horaInicio); // Devolver en formato hh:mm
                }
    
                // Avanzar 30 minutos
                $horaInicio = strtotime('+30 minutes', $horaInicio);
            }
        }
    
        return response()->json($horasDisponibles);
    }

    // Método para procesar la reserva
    public function store(Request $request)
    {
        if (!auth()->user()->paciente) {
            return redirect()->route('paciente.reservar')->with('error', 'Debes completar tu perfil de paciente antes de reservar una cita.');
        }

        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'fecha' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addMonth(),
            'hora_inicio' => 'required|date_format:H:i',
            'razon_paciente' => 'required|string|max:255',
        ]);

        // Crear la cita
        $cita = new Cita();
        $cita->paciente_id = auth()->user()->paciente->id;
        $cita->medico_id = $request->medico_id;
        $cita->fecha = $request->fecha;
        $cita->hora_inicio = $request->hora_inicio;
        $cita->hora_fin = date('H:i:s', strtotime($request->hora_inicio) + 1800);
        $cita->razon_paciente = $request->razon_paciente;
        $cita->estado = 'pendiente';
        $cita->save();

        return redirect()->route('paciente.reservar')->with('success', 'Cita reservada con éxito.');
    }
}
