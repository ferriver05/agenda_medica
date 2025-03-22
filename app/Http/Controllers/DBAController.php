<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DBAController extends Controller
{
    public function dashboard()
    {
        return view('dba.dashboard');
    }

    public function resumenUsuarios()
    {
        return view('dba.usuarios.resumen');
    }
}
