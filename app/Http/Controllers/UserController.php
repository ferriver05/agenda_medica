<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Historial;
use App\Models\Especialidad;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('dba.usuarios.resumen', compact('usuarios'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        return view('dba.usuarios.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|string|max:20|unique:users',
            'name' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'rol' => 'required|in:Paciente,Medico',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        if ($request->rol === 'Paciente') {
            $request->validate([
                'tipo_sangre' => 'required|string|max:3',
                'seguro_medico' => 'required|string|max:255',
                'ocupacion' => 'required|string|max:255',
                'contacto_emergencia' => 'required|string|max:255',
                'telefono_emergencia' => 'required|string|max:20',
                'enfermedades_cronicas' => 'nullable|string|max:255',
                'alergias' => 'nullable|string|max:255',
                'cirugias' => 'nullable|string|max:255',
                'medicamentos' => 'nullable|string|max:255',
                'antecedentes_familiares' => 'nullable|string|max:255',
                'otras_condiciones' => 'nullable|string',
                'observaciones' => 'nullable|string',
            ]);
    
            $user = User::create([
                'dni' => $request->dni,
                'name' => $request->name,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'email' => $request->email,
                'genero' => $request->genero,
                'rol' => $request->rol,
                'password' => bcrypt($request->password),
            ]);
    
            $paciente = Paciente::create([
                'user_id' => $user->id,
                'tipo_sangre' => $request->tipo_sangre,
                'seguro_medico' => $request->seguro_medico,
                'ocupacion' => $request->ocupacion,
                'contacto_emergencia' => $request->contacto_emergencia,
                'telefono_emergencia' => $request->telefono_emergencia,
            ]);
    
            Historial::create([
                'paciente_id' => $paciente->id,
                'enfermedades_cronicas' => $request->enfermedades_cronicas,
                'alergias' => $request->alergias,
                'cirugias' => $request->cirugias,
                'medicamentos' => $request->medicamentos,
                'antecedentes_familiares' => $request->antecedentes_familiares,
                'otras_condiciones' => $request->otras_condiciones,
                'observaciones' => $request->observaciones,
            ]);
        } elseif ($request->rol === 'Medico') {
            $request->validate([
                'numero_licencia' => 'required|string|max:255',
                'numero_sala' => 'required|string|max:255',
                'especialidades' => 'required|array',
                'especialidades.*' => 'exists:especialidades,id',
            ]);
    
            $user = User::create([
                'dni' => $request->dni,
                'name' => $request->name,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'email' => $request->email,
                'genero' => $request->genero,
                'rol' => $request->rol,
                'password' => bcrypt($request->password),
            ]);
    
            $medico = Medico::create([
                'user_id' => $user->id,
                'numero_licencia' => $request->numero_licencia,
                'numero_sala' => $request->numero_sala,
            ]);

            $medico->especialidades()->attach($request->especialidades);

            foreach ($request->dia_semana as $index => $dia) {
                Disponibilidad::create([
                    'medico_id' => $medico->id,
                    'dia_semana' => $dia,
                    'hora_inicio' => $request->hora_inicio[$index],
                    'hora_fin' => $request->hora_fin[$index],
                ]);
            }
        }
    
        return redirect()->route('dba.usuarios.resumen')->with('success', 'Usuario creado exitosamente.');
    }
}
