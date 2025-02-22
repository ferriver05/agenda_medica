<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function showClandingForm()
    {
        return view('Clanding');
    }

    public function showDlandingForm()
    {
        return view('Dlanding');
    }

    public function showDBAlandingForm()
    {
        return view('DBAlanding');
    }

    public function showD_historialForm()
    {
        return view('D_historial');
    }

    public function showD_reservaForm()
    {
        return view('D_reserva');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar el rol del usuario y redirigirlo a la página correspondiente
        switch ($user->rol) {
            case 'DBA':
                return redirect()->intended('/DBAlanding');
            case 'medico':
                return redirect()->intended('/Dlanding');
            case 'paciente':
                return redirect()->intended('/Clanding');
            default:
                Auth::logout();
                return back()->withErrors(['email' => 'Rol no autorizado'])->withInput();
        }
    }

    return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
}
      
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
