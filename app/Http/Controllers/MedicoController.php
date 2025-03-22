<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function dashboard()
    {
        return view('medico.dashboard');
    }

    public function pacientes()
    {
        return view('medico.pacientes');
    }
}
