<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Historial;
use App\Models\Especialidad;
use App\Models\Disponibilidad;  
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
        // Validación básica para todos los usuarios
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
            // Validación específica para pacientes
            $request->validate([
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
    
            try {
                DB::beginTransaction();
    
                // Crear usuario paciente
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
    
                // Crear registro de paciente
                $paciente = Paciente::create([
                    'user_id' => $user->id,
                    'tipo_sangre' => $request->tipo_sangre,
                    'seguro_medico' => $request->seguro_medico,
                    'ocupacion' => $request->ocupacion,
                    'contacto_emergencia' => $request->contacto_emergencia,
                    'telefono_emergencia' => $request->telefono_emergencia,
                ]);
    
                // Crear historial médico
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
    
                DB::commit();
    
                return redirect()->route('dba.usuarios.resumen')->with('success', 'Paciente creado exitosamente.');
    
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()
                    ->route('dba.usuarios.create')
                    ->with('error', 'Error al crear el paciente: ' . $e->getMessage())
                    ->withInput();
            }
    
        } elseif ($request->rol === 'Medico') {
            // Validación básica para médicos
            $medicoValidator = \Validator::make($request->all(), [
                'numero_licencia' => 'required|string|max:255|unique:medicos',
                'numero_sala' => 'required|string|max:255|unique:medicos',
                'especialidades' => 'required|array|min:1',
                'especialidades.*' => 'exists:especialidades,id',
                'dia_semana' => 'required|array|min:1',
                'dia_semana.*' => 'integer|between:0,6',
                'hora_inicio' => 'required|array|min:1',
                'hora_inicio.*' => 'date_format:H:i',
                'hora_fin' => 'required|array|min:1',
                'hora_fin.*' => 'date_format:H:i|after:hora_inicio.*'
            ]);
    
            if ($medicoValidator->fails()) {
                return redirect()
                    ->route('dba.usuarios.create')
                    ->withErrors($medicoValidator)
                    ->withInput();
            }
    
            // Validación adicional de disponibilidades
            try {
                $this->validateAvailability([
                    'dia_semana' => $request->dia_semana,
                    'hora_inicio' => $request->hora_inicio,
                    'hora_fin' => $request->hora_fin
                ]);
            } catch (ValidationException $e) {
                return redirect()
                    ->route('dba.usuarios.create')
                    ->withErrors($e->validator)
                    ->withInput();
            }
    
            try {
                DB::beginTransaction();
    
                // Crear usuario médico
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
    
                // Crear registro de médico
                $medico = Medico::create([
                    'user_id' => $user->id,
                    'numero_licencia' => $request->numero_licencia,
                    'numero_sala' => $request->numero_sala,
                ]);
    
                // Asignar especialidades
                $medico->especialidades()->attach($request->especialidades);
    
                // Crear disponibilidades
                foreach ($request->dia_semana as $index => $dia) {
                    Disponibilidad::create([
                        'medico_id' => $medico->id,
                        'dia_semana' => $dia,
                        'hora_inicio' => $request->hora_inicio[$index],
                        'hora_fin' => $request->hora_fin[$index],
                    ]);
                }
    
                DB::commit();
    
                return redirect()->route('dba.usuarios.resumen')->with('success', 'Médico creado exitosamente.');
    
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()
                    ->route('dba.usuarios.create')
                    ->with('error', 'Error al crear el médico: ' . $e->getMessage())
                    ->withInput();
            }
        }
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
        // Validar datos básicos primero
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

        // Validación adicional personalizada
        $validator = \Validator::make([], []); // Crear validador vacío

        try {
            // Validar disponibilidades
            $this->validateAvailability($medicoData['disponibilidades']);
        } catch (ValidationException $e) {
            return redirect()
                ->route('dba.usuarios.edit', $usuario)
                ->withErrors($e->validator)
                ->withInput();
        }
    
        try {
            DB::beginTransaction();
    
            // 1. Manejo de especialidades (código existente)
            $currentEspecialidades = $usuario->medico->especialidades->pluck('id')->toArray();
            $newEspecialidades = $medicoData['especialidades'];
            $removedEspecialidades = array_diff($currentEspecialidades, $newEspecialidades);
    
            $usuario->medico->update([
                'numero_licencia' => $medicoData['numero_licencia'],
                'numero_sala' => $medicoData['numero_sala']
            ]);
    
            $usuario->medico->especialidades()->sync($newEspecialidades);
    
            // Cancelar citas de especialidades eliminadas
            if (!empty($removedEspecialidades)) {
                DB::table('citas')
                    ->where('medico_id', $usuario->medico->id)
                    ->whereIn('especialidad_id', $removedEspecialidades)
                    ->where('estado', 'pendiente')
                    ->update([
                        'estado' => 'cancelada',
                        'updated_at' => now(),
                        'notas_medico' => 'Cita cancelada: el médico ya no atiende esta especialidad'
                    ]);
            }
    
            // 2. Preparar nuevos horarios de disponibilidad
            $newAvailability = collect($medicoData['disponibilidades'])->map(function($item) {
                return [
                    'dia_semana' => $item['dia_semana'],
                    'hora_inicio' => $item['hora_inicio'],
                    'hora_fin' => $item['hora_fin']
                ];
            });
    
            // 3. Cancelar citas que quedan fuera de la nueva disponibilidad
            $pendingAppointments = DB::table('citas')
                ->where('medico_id', $usuario->medico->id)
                ->where('estado', 'pendiente')
                ->get();
    
            foreach ($pendingAppointments as $appointment) {
                $appointmentDate = \Carbon\Carbon::parse($appointment->fecha);
                $appointmentDayOfWeek = $appointmentDate->dayOfWeek; // 0 (domingo) a 6 (sábado)
                $appointmentTime = \Carbon\Carbon::parse($appointment->hora_inicio)->format('H:i:s');
    
                // Verificar si la cita está en un día no disponible
                $dayAvailable = $newAvailability->contains('dia_semana', $appointmentDayOfWeek);
    
                if (!$dayAvailable) {
                    DB::table('citas')
                        ->where('id', $appointment->id)
                        ->update([
                            'estado' => 'cancelada',
                            'updated_at' => now(),
                            'notas_medico' => 'Cita cancelada: el médico ya no atiende este día'
                        ]);
                    continue;
                }
    
                // Verificar si la cita está fuera del horario disponible
                $timeAvailable = false;
                $daySlots = $newAvailability->where('dia_semana', $appointmentDayOfWeek);
    
                foreach ($daySlots as $slot) {
                    $start = \Carbon\Carbon::parse($slot['hora_inicio']);
                    $end = \Carbon\Carbon::parse($slot['hora_fin']);
                    $apptTime = \Carbon\Carbon::parse($appointmentTime);
    
                    if ($apptTime->between($start, $end)) {
                        $timeAvailable = true;
                        break;
                    }
                }
    
                if (!$timeAvailable) {
                    DB::table('citas')
                        ->where('id', $appointment->id)
                        ->update([
                            'estado' => 'cancelada',
                            'updated_at' => now(),
                            'notas_medico' => 'Cita cancelada: el médico ya no atiende en este horario'
                        ]);
                }
            }
    
            // 4. Actualizar disponibilidades
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
    
            return redirect()
            ->route('dba.usuarios.show', $usuario)
            ->with('success', 'Datos del médico actualizados correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('dba.usuarios.edit', $usuario)
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    protected function validateAvailability($disponibilidades)
    {
        $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        
        // Verificar que disponibilidades es un array
        if (!is_array($disponibilidades)) {
            throw ValidationException::withMessages([
                'disponibilidades' => 'Formato de disponibilidades inválido'
            ]);
        }
    
        // Reorganizar los datos en una estructura más manejable
        $horariosPorDia = [];
        
        foreach ($disponibilidades as $index => $disp) {
            if (!isset($disp['dia_semana'])) {
                throw ValidationException::withMessages([
                    "disponibilidades.$index.dia_semana" => 'El día de la semana es requerido'
                ]);
            }
            
            if (!isset($disp['hora_inicio'])) {
                throw ValidationException::withMessages([
                    "disponibilidades.$index.hora_inicio" => 'La hora de inicio es requerida'
                ]);
            }
            
            if (!isset($disp['hora_fin'])) {
                throw ValidationException::withMessages([
                    "disponibilidades.$index.hora_fin" => 'La hora de fin es requerida'
                ]);
            }
            
            $dia = $disp['dia_semana'];
            $horariosPorDia[$dia][] = [
                'hora_inicio' => $disp['hora_inicio'],
                'hora_fin' => $disp['hora_fin'],
                'index' => $index
            ];
        }
    
        foreach ($horariosPorDia as $diaNum => $disponibilidadesDia) {
            // Ordenar por hora de inicio
            usort($disponibilidadesDia, function($a, $b) {
                return strcmp($a['hora_inicio'], $b['hora_inicio']);
            });
    
            foreach ($disponibilidadesDia as $i => $disp) {
                // Validar formato de hora
                if (!$this->validateTimeFormat($disp['hora_inicio']) || 
                    !$this->validateTimeFormat($disp['hora_fin'])) {
                    throw ValidationException::withMessages([
                        "disponibilidades.{$disp['index']}.hora_inicio" => 'Las horas deben terminar en :00 o :30',
                        "disponibilidades.{$disp['index']}.hora_fin" => 'Las horas deben terminar en :00 o :30'
                    ]);
                }
    
                // Validar que hora_fin > hora_inicio
                if ($disp['hora_fin'] <= $disp['hora_inicio']) {
                    throw ValidationException::withMessages([
                        "disponibilidades.{$disp['index']}.hora_fin" => "La hora final debe ser mayor que la hora inicial"
                    ]);
                }
    
                // Validar superposición con la siguiente disponibilidad
                if (isset($disponibilidadesDia[$i + 1])) {
                    $nextDisp = $disponibilidadesDia[$i + 1];
                    
                    if ($disp['hora_fin'] > $nextDisp['hora_inicio']) {
                        $diaNombre = $diasSemana[$diaNum];
                        throw ValidationException::withMessages([
                            "disponibilidades.{$disp['index']}.hora_fin" => "Superposición en $diaNombre: {$disp['hora_inicio']}-{$disp['hora_fin']} con {$nextDisp['hora_inicio']}-{$nextDisp['hora_fin']}",
                            "disponibilidades.{$nextDisp['index']}.hora_inicio" => "Superposición en $diaNombre: {$disp['hora_inicio']}-{$disp['hora_fin']} con {$nextDisp['hora_inicio']}-{$nextDisp['hora_fin']}"
                        ]);
                    }
                }
            }
        }
        
        return true;
    }
    
    protected function validateTimeFormat($time)
    {
        // Validar que sea hh:00 o hh:30
        $minutes = date('i', strtotime($time));
        return $minutes === '00' || $minutes === '30';
    }

}
