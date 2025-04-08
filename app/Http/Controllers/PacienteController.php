<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function mostrarInformacion()
    {
        $user = Auth::user();
    
        $paciente = $user->paciente;
        $historial = $paciente->historial ?? null;
    
        return view('paciente.informacion', compact('user', 'paciente', 'historial'));
    }
}
