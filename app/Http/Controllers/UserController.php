<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Historial;
use App\Models\Especialidad;
use App\Models\Disponibilidad;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
                'tipo_sangre' => 'nullable|string|max:3',
                'seguro_medico' => 'nullable|string|max:255',
                'ocupacion' => 'nullable|string|max:255',
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

    public function show(User $usuario)
    {
        $usuario->load([
            'medico.especialidades',
            'medico.disponibilidades' => function($query) {
                $query->orderBy('dia_semana')->orderBy('hora_inicio');
            },
            'paciente.historial'
        ]);
    
        if ($usuario->rol === 'Medico' && $usuario->medico) {
            $diasSemana = [
                0 => 'Domingo',
                1 => 'Lunes', 
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado'
            ];
    
            $disponibilidadesPorDia = [];
            foreach ($diasSemana as $key => $dia) {
                $disponibilidadesPorDia[$dia] = $usuario->medico->disponibilidades
                    ->where('dia_semana', $key)
                    ->sortBy('hora_inicio');
            }
    
            $usuario->medico->disponibilidades_organizadas = $disponibilidadesPorDia;
        }
    
        return view('dba.usuarios.detalles', compact('usuario'));
    }

    public function edit(User $usuario)
    {
        // Cargar relaciones según el rol
        $usuario->load([
            'medico.especialidades',
            'medico.disponibilidades' => function($query) {
                $query->orderBy('dia_semana')->orderBy('hora_inicio');
            },
            'paciente.historial'
        ]);
        
        // Si es médico, preparamos datos para el formulario
        if ($usuario->rol === 'Medico') {
            $diasSemana = [
                0 => 'Domingo',
                1 => 'Lunes', 
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado'
            ];
            
            $disponibilidadesPorDia = [];
            foreach ($diasSemana as $key => $dia) {
                $disponibilidadesPorDia[$dia] = $usuario->medico->disponibilidades
                    ->where('dia_semana', $key)
                    ->sortBy('hora_inicio');
            }
            
            $usuario->medico->disponibilidades_organizadas = $disponibilidadesPorDia;
        }
        
        return view('dba.usuarios.edit', [
            'usuario' => $usuario,
            'especialidades' => Especialidad::all(),
            'diasSemana' => $diasSemana ?? null
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        // Validación para datos comunes a todos los usuarios
        $userData = $request->validate([
            'name' => 'required|string|max:255',
            'dni' => 'required|string|max:20|unique:users,dni,'.$usuario->id,
            'email' => 'required|email|unique:users,email,'.$usuario->id,
            'fecha_nacimiento' => 'nullable|date',
            'telefono' => 'nullable|string|max:20',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'direccion' => 'nullable|string|max:255',
            'password' => 'nullable|min:8|confirmed',
            'activo' => 'boolean'
        ]);
        
        // Actualizar contraseña solo si se proporcionó una nueva
        if (!empty($userData['password'])) {
            $userData['password'] = Hash::make($userData['password']);
        } else {
            unset($userData['password']);
        }
        
        // Actualizar datos básicos del usuario
        $usuario->update($userData);
        
        // Actualizar datos específicos según el rol
        switch ($usuario->rol) {
            case 'Paciente':
                $this->updatePacienteData($request, $usuario);
                break;
                
            case 'Medico':
                $this->updateMedicoData($request, $usuario);
                break;
        }
        
        return redirect()->route('dba.usuarios.show', $usuario)
            ->with('success', 'Usuario actualizado correctamente');
    }
    
    protected function updatePacienteData(Request $request, User $usuario)
    {
        $pacienteData = $request->validate([
            'tipo_sangre' => 'nullable|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'seguro_medico' => 'nullable|string|max:255',
            'ocupacion' => 'nullable|string|max:255',
            'contacto_emergencia' => 'required|string|max:255',
            'telefono_emergencia' => 'required|string|max:20',
            'enfermedades_cronicas' => 'nullable|string',
            'alergias' => 'nullable|string',
            'cirugias' => 'nullable|string',
            'medicamentos' => 'nullable|string',
            'antecedentes_familiares' => 'nullable|string',
            'otras_condiciones' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);
        
        $usuario->paciente->update([
            'tipo_sangre' => $pacienteData['tipo_sangre'],
            'seguro_medico' => $pacienteData['seguro_medico'],
            'ocupacion' => $pacienteData['ocupacion'],
            'contacto_emergencia' => $pacienteData['contacto_emergencia'],
            'telefono_emergencia' => $pacienteData['telefono_emergencia']
        ]);
        
        $usuario->paciente->historial->update([
            'enfermedades_cronicas' => $pacienteData['enfermedades_cronicas'],
            'alergias' => $pacienteData['alergias'],
            'cirugias' => $pacienteData['cirugias'],
            'medicamentos' => $pacienteData['medicamentos'],
            'antecedentes_familiares' => $pacienteData['antecedentes_familiares'],
            'otras_condiciones' => $pacienteData['otras_condiciones'],
            'observaciones' => $pacienteData['observaciones']
        ]);
    }
    
    protected function updateMedicoData(Request $request, User $usuario)
    {
        // Validar datos específicos de médico
        $medicoData = $request->validate([
            'numero_licencia' => 'required|string|max:50|unique:medicos,numero_licencia,'.$usuario->medico->id,
            'numero_sala' => 'required|string|max:20|unique:medicos,numero_sala,'.$usuario->medico->id,
            'especialidades' => 'required|array|min:1',
            'especialidades.*' => 'exists:especialidades,id',
            'disponibilidades' => 'required|array|min:1',
            'disponibilidades.*.dia_semana' => 'required|integer|between:0,6',
            'disponibilidades.*.hora_inicio' => 'required|date_format:H:i',
            'disponibilidades.*.hora_fin' => 'required|date_format:H:i|after:disponibilidades.*.hora_inicio'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Obtener especialidades actuales y nuevas
            $currentEspecialidades = $usuario->medico->especialidades->pluck('id')->toArray();
            $newEspecialidades = $medicoData['especialidades'];
            
            // Determinar qué especialidades se están eliminando
            $removedEspecialidades = array_diff($currentEspecialidades, $newEspecialidades);
            
            // Actualizar datos básicos del médico
            $usuario->medico->update([
                'numero_licencia' => $medicoData['numero_licencia'],
                'numero_sala' => $medicoData['numero_sala']
            ]);
            
            // Sincronizar especialidades (relación muchos a muchos)
            $usuario->medico->especialidades()->sync($medicoData['especialidades']);
            
            // Cancelar citas pendientes para especialidades eliminadas
            if (!empty($removedEspecialidades)) {
                DB::table('citas')
                    ->where('medico_id', $usuario->medico->id)
                    ->whereIn('especialidad_id', $removedEspecialidades)
                    ->where('estado', 'pendiente')
                    ->update([
                        'estado' => 'cancelada',
                        'notas_medico' => 'Cita cancelada automaticamente porque el médico ya no atiende esta especialidad.',
                        'updated_at' => now()
                    ]);
            }
            
            // Eliminar todas las disponibilidades existentes y crear las nuevas
            $usuario->medico->disponibilidades()->delete();
            
            foreach ($medicoData['disponibilidades'] as $disponibilidad) {
                $usuario->medico->disponibilidades()->create([
                    'dia_semana' => $disponibilidad['dia_semana'],
                    'hora_inicio' => $disponibilidad['hora_inicio'],
                    'hora_fin' => $disponibilidad['hora_fin'],
                    'activo' => true
                ]);
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Datos del médico actualizados correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar los datos del médico: '.$e->getMessage());
        }
    }
}
