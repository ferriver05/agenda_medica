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
        return view('paciente.citas.reservar', compact('especialidades'));
    }

    public function obtenerMedicosPorEspecialidad($especialidad_id)
    {
        $medicos = Medico::whereHas('especialidades', function($query) use ($especialidad_id) {
            $query->where('especialidad_id', $especialidad_id);
        })
        ->with('user')
        ->get();
    
        return response()->json($medicos);
    }

    public function obtenerHorasDisponibles($medico_id, $fecha)
    {
        $diaSemana = date('w', strtotime($fecha)); // 0 (Domingo) a 6 (Sábado)
        $disponibilidades = Disponibilidad::where('medico_id', $medico_id)
            ->where('dia_semana', $diaSemana)
            ->get();
    
        $horasOcupadas = Cita::where('medico_id', $medico_id)
            ->where('fecha', $fecha)
            ->pluck('hora_inicio')
            ->toArray();
    
        $horasDisponibles = [];
        foreach ($disponibilidades as $disponibilidad) {
            $horaInicio = strtotime($disponibilidad->hora_inicio);
            $horaFin = strtotime($disponibilidad->hora_fin);
    
            while ($horaInicio < $horaFin) {
                $horaFormateada = date('H:i:s', $horaInicio);
    
                if (!in_array($horaFormateada, $horasOcupadas)) {
                    $horasDisponibles[] = date('H:i', $horaInicio);
                }

                $horaInicio = strtotime('+30 minutes', $horaInicio);
            }
        }
    
        return response()->json($horasDisponibles);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->paciente) {
            return redirect()->route('paciente.citas.reservar')->with('error', 'Debes completar tu perfil de paciente antes de reservar una cita.');
        }

        $request->validate([
            'medico_id' => 'required|exists:medicos,id',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addMonth(),
            'hora_inicio' => 'required|date_format:H:i',
            'razon_paciente' => 'required|string|max:255',
        ]);

        $tipoCita = 'primera_vez';
        if (auth()->user()->rol === 'Medico') {
            $tipoCita = 'seguimiento';
        }

        $cita = new Cita();
        $cita->paciente_id = auth()->user()->paciente->id;
        $cita->medico_id = $request->medico_id;
        $cita->especialidad_id = $request->especialidad_id;
        $cita->fecha = $request->fecha;
        $cita->hora_inicio = $request->hora_inicio;
        $cita->hora_fin = date('H:i:s', strtotime($request->hora_inicio) + 1800);
        $cita->razon_paciente = $request->razon_paciente;
        $cita->tipo_cita = $tipoCita;
        $cita->estado = 'pendiente';
        $cita->save();

        return redirect()->route('paciente.citas.reservar')->with('success', 'Cita reservada con éxito.');
    }

    public function mostrarDetalles($id)
    {
        $cita = Cita::with(['medico.user', 'especialidad', 'paciente.user'])->findOrFail($id);
    
        if (auth()->user()->rol === 'Paciente' && $cita->paciente_id !== auth()->user()->paciente->id) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }
        
        return view('paciente.citas.detalles', compact('cita'));
    }

    public function mostrarResumen()
    {
        $user = auth()->user();

        $citasConfirmadas = [];
        $citasPendientes = [];
        $citasPasadas = [];

        if ($user->rol === 'Paciente') {
            $citasConfirmadas = Cita::where('paciente_id', $user->paciente->id)
                ->where('estado', 'confirmada')
                ->with(['medico.user', 'especialidad'])
                ->get();

            $citasPendientes = Cita::where('paciente_id', $user->paciente->id)
                ->where('estado', 'pendiente')
                ->with(['medico.user', 'especialidad'])
                ->get();

            $citasPasadas = Cita::where('paciente_id', $user->paciente->id)
                ->whereIn('estado', ['completada', 'cancelada'])
                ->with(['medico.user', 'especialidad'])
                ->get();
        }

        return view('paciente.citas.resumen', compact('citasConfirmadas', 'citasPendientes', 'citasPasadas'));
    }

    public function cancelarCita($id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->paciente_id !== auth()->user()->paciente->id) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        if (!in_array($cita->estado, ['pendiente', 'confirmada'])) {
            return redirect()->route('paciente.citas.detalles', $cita->id)
                ->with('error', 'No se puede cancelar una cita en estado ' . $cita->estado);
        }

        $cita->estado = 'cancelada';
        $cita->save();

        return redirect()->route('paciente.citas.detalles', $cita->id)
            ->with('success', 'La cita ha sido cancelada correctamente.');
    }
}
