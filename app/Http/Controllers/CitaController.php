<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especialidad;
use App\Models\Medico;
use App\Models\Disponibilidad;
use App\Models\Cita;
use App\Models\User;


class CitaController extends Controller
{
    public function mostrarFormularioReservaPaciente()
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

        $cita = new Cita();
        $cita->paciente_id = auth()->user()->paciente->id;
        $cita->medico_id = $request->medico_id;
        $cita->especialidad_id = $request->especialidad_id;
        $cita->fecha = $request->fecha;
        $cita->hora_inicio = $request->hora_inicio;
        $cita->hora_fin = date('H:i:s', strtotime($request->hora_inicio) + 1800);
        $cita->razon_paciente = $request->razon_paciente;
        $cita->tipo_cita = 'primera_vez';
        $cita->estado = 'pendiente';
        $cita->save();

        return redirect()->route('paciente.citas.resumen')->with('success', 'Cita reservada con éxito.');
    }

    public function mostrarDetalles($id)
    {
        $cita = Cita::with(['medico.user', 'especialidad', 'paciente.user'])->findOrFail($id);
    
        if (auth()->user()->rol === 'Paciente' && $cita->paciente_id !== auth()->user()->paciente->id) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }
    
        if (auth()->user()->rol === 'Medico' && $cita->medico_id !== auth()->user()->medico->id) {
            abort(403, 'No tienes permiso para ver esta cita.');
        }
    
        if (auth()->user()->rol === 'Paciente') {
            return view('paciente.citas.detalles', compact('cita'));
        } elseif (auth()->user()->rol === 'Medico') {
            return view('medico.citas.detalles', compact('cita'));
        }
    
        abort(403, 'No tienes permiso para ver esta cita.');
    }

    public function mostrarResumenPaciente()
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

    public function mostrarResumenMedico()
    {
        $user = auth()->user();

        $citasConfirmadas = [];
        $citasPendientes = [];
        $citasPasadas = [];

        if ($user->rol === 'Medico') {
            $citasConfirmadas = Cita::where('medico_id', $user->medico->id)
                ->where('estado', 'confirmada')
                ->with(['paciente.user', 'especialidad'])
                ->get();

            $citasPendientes = Cita::where('medico_id', $user->medico->id)
                ->where('estado', 'pendiente')
                ->with(['paciente.user', 'especialidad'])
                ->get();

            $citasPasadas = Cita::where('medico_id', $user->medico->id)
                ->whereIn('estado', ['completada', 'cancelada'])
                ->with(['paciente.user', 'especialidad'])
                ->get();
        }

        return view('medico.citas.resumen', compact('citasConfirmadas', 'citasPendientes', 'citasPasadas'));
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

    public function confirmarCita($id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->medico_id !== auth()->user()->medico->id) {
            abort(403, 'No tienes permiso para confirmar esta cita.');
        }

        if ($cita->estado !== 'pendiente') {
            return redirect()->route('medico.citas.detalles', $cita->id)
                ->with('error', 'No se puede confirmar una cita en estado ' . $cita->estado);
        }

        $cita->estado = 'confirmada';
        $cita->save();

        return redirect()->route('medico.citas.resumen')
            ->with('success', 'La cita ha sido confirmada correctamente.');
    }

    public function rechazarCita($id)
    {
        $cita = Cita::findOrFail($id);

        if ($cita->medico_id !== auth()->user()->medico->id) {
            abort(403, 'No tienes permiso para rechazar esta cita.');
        }

        if ($cita->estado !== 'pendiente') {
            return redirect()->route('medico.citas.detalles', $cita->id)
                ->with('error', 'No se puede rechazar una cita en estado ' . $cita->estado);
        }

        $cita->estado = 'cancelada';
        $cita->save();

        return redirect()->route('medico.citas.detalles', $cita->id)
            ->with('success', 'La cita ha sido rechazada correctamente.');
    }

    public function completarCita(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
    
        if ($cita->medico_id !== auth()->user()->medico->id) {
            abort(403, 'No tienes permiso para completar esta cita.');
        }
    
        if ($cita->estado !== 'confirmada') {
            return redirect()->route('medico.citas.detalles', $cita->id)
                ->with('error', 'No se puede completar una cita en estado ' . $cita->estado);
        }
    
        $request->validate([
            'notas_medico' => 'nullable|string|max:1000',
            'imagen_prescripcion' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Máximo 2MB
        ]);
    
        if ($request->hasFile('imagen_prescripcion')) {
            $imagenPath = $request->file('imagen_prescripcion')->store('prescripciones', 'public');
            $cita->imagen_prescripcion = $imagenPath;
        }
    
        $cita->notas_medico = $request->input('notas_medico');
        $cita->estado = 'completada';
        $cita->save();
    
        return redirect()->route('medico.citas.detalles', $cita->id)
            ->with('success', 'La cita ha sido marcada como completada correctamente.');
    }

    public function mostrarFormularioReservaMedico()
    {
        $especialidades = auth()->user()->medico->especialidades;
        return view('medico.citas.reservar', compact('especialidades'));
    }

    public function buscarPacientePorDni($dni)
    {
        $paciente = User::where('dni', $dni)->where('rol', 'Paciente')->first();
        return response()->json($paciente);
    }

    public function obtenerHorasDisponiblesMedico($dni, $fecha)
    {
        $paciente = User::where('dni', $dni)->where('rol', 'Paciente')->first();

        if (!$paciente) {
            return response()->json([]);
        }

        $medicoId = auth()->user()->medico->id;
        $diaSemana = date('w', strtotime($fecha));

        $disponibilidades = Disponibilidad::where('medico_id', $medicoId)
            ->where('dia_semana', $diaSemana)
            ->get();

        $horasOcupadasMedico = Cita::where('medico_id', $medicoId)
            ->where('fecha', $fecha)
            ->pluck('hora_inicio')
            ->toArray();

        $horasOcupadasPaciente = Cita::where('paciente_id', $paciente->paciente->id)
            ->where('fecha', $fecha)
            ->pluck('hora_inicio')
            ->toArray();

        $horasOcupadas = array_merge($horasOcupadasMedico, $horasOcupadasPaciente);

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

    public function procesarReservaMedico(Request $request)
    {
        $request->validate([
            'dni' => 'required|exists:users,dni',
            'especialidad_id' => 'required|exists:especialidades,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'razon_cita' => 'required|string|max:255',
        ]);

        $paciente = User::where('dni', $request->dni)->where('rol', 'Paciente')->first();

        $cita = new Cita();
        $cita->paciente_id = $paciente->paciente->id;
        $cita->medico_id = auth()->user()->medico->id;
        $cita->especialidad_id = $request->especialidad_id;
        $cita->fecha = $request->fecha;
        $cita->hora_inicio = $request->hora_inicio;
        $cita->hora_fin = date('H:i:s', strtotime($request->hora_inicio) + 1800);
        $cita->razon_paciente = $request->razon_cita;
        $cita->tipo_cita = 'seguimiento';
        $cita->estado = 'confirmada';
        $cita->save();

        return redirect()->route('medico.citas.resumen')->with('success', 'Cita reservada con éxito.');
    }
}
