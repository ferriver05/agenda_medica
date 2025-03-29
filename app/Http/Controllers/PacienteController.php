<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function dashboard()
    {
        return view('paciente.dashboard');
    }

    public function mostrarInformacion()
    {
        $user = Auth::user();
        
        // Verificar que el usuario es realmente un paciente
        if ($user->rol !== 'Paciente') {
            abort(403);
        }
    
        $paciente = $user->paciente;
        $historial = $paciente->historial ?? null;
    
        return view('paciente.informacion', compact('user', 'paciente', 'historial'));
    }
}
