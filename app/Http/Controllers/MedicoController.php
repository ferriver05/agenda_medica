<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paciente;

class MedicoController extends Controller
{
    public function dashboard()
    {
        return view('medico.dashboard');
    }

    public function pacientes()
    {
        // Obtener el médico autenticado
        $medico = Auth::user()->medico;
        
        // Obtener pacientes que han tenido citas con este médico
        $pacientes = Paciente::whereHas('citas', function($query) use ($medico) {
            $query->where('medico_id', $medico->id);
        })
        ->with(['user', 'ultimaCita' => function($query) use ($medico) {
            $query->where('medico_id', $medico->id)->latest()->limit(1);
        }])
        ->get();
        
        return view('medico.pacientes.resumen', compact('pacientes'));
    }

    public function showPaciente(Paciente $paciente)
    {
        $medicoId = Auth::user()->medico->id;
        
        if (!$paciente->citas()->where('medico_id', $medicoId)->exists()) {
            abort(403, 'No tienes permiso para ver este paciente');
        }
        
        $paciente->load([
            'user',
            'historial',
            'citas' => function($query) use ($medicoId) {
                $query->where('medico_id', $medicoId)
                      ->orderBy('fecha', 'desc')
                      ->orderBy('hora_inicio', 'desc') 
                      ->with('especialidad');
            }
        ]);
        
        return view('medico.pacientes.detalles', compact('paciente'));
    }

    public function mostrarInformacion()
    {
        $user = auth()->user();
        
        $medico = $user->medico()->with([
            'especialidades',
            'disponibilidades'
        ])->first();
        
        if (!$medico) {
            abort(403, 'Acceso denegado. Solo los médicos pueden ver esta información.');
        }
        
        return view('medico.informacion', [
            'user' => $user,
            'medico' => $medico,
            'especialidades' => $medico->especialidades,
            'disponibilidades' => $medico->disponibilidades
        ]);
    }
}
