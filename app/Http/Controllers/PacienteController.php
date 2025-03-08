<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function dashboard()
    {
        return view('paciente.dashboard');
    }

    public function historial()
    {
        return view('paciente.historial');
    }

    public function reserva()
    {
        return view('paciente.reserva');
    }
}
