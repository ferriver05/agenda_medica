@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detalles del Paciente</h1>
            <a href="{{ route('medico.pacientes.resumen') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Regresar
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Datos Clínicos del Paciente</h2>
                
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Nombre:</span>
                        <span class="col-span-2">{{ $paciente->user->name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">DNI:</span>
                        <span class="col-span-2">{{ $paciente->user->dni }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Edad:</span>
                        <span class="col-span-2">
                            {{ \Carbon\Carbon::parse($paciente->user->fecha_nacimiento)->age }} años
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Género:</span>
                        <span class="col-span-2 capitalize">{{ $paciente->user->genero ?? 'No especificado' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Teléfono:</span>
                        <span class="col-span-2">{{ $paciente->user->telefono ?? 'No registrado' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Tipo de sangre:</span>
                        <span class="col-span-2">{{ $paciente->tipo_sangre ?? 'No registrado' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Seguro médico:</span>
                        <span class="col-span-2">{{ $paciente->seguro_medico ?? 'No especificado' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Ocupación:</span>
                        <span class="col-span-2">{{ $paciente->ocupacion ?? 'No especificada' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="font-medium text-gray-600">Contacto emergencia:</span>
                        <span class="col-span-2">{{ $paciente->contacto_emergencia }} ({{ $paciente->telefono_emergencia }})</span>
                    </div>
                </div>
            </div>
        
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-4 border-b pb-2">Antecedentes Médicos</h2>
                
                @if($paciente->historial)
                <div class="space-y-3">
                    <div>
                        <h3 class="font-medium text-gray-600 mb-1">Alergias:</h3>
                        <p>{{ $paciente->historial->alergias ?: 'Ninguna registrada' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-600 mb-1">Enfermedades crónicas:</h3>
                        <p>{{ $paciente->historial->enfermedades_cronicas ?: 'Ninguna registrada' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-600 mb-1">Medicamentos actuales:</h3>
                        <p>{{ $paciente->historial->medicamentos ?: 'Ninguno registrado' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-600 mb-1">Antecedentes familiares:</h3>
                        <p>{{ $paciente->historial->antecedentes_familiares ?: 'No registrados' }}</p>
                    </div>
                    
                    @if($paciente->historial->otras_condiciones)
                    <div>
                        <h3 class="font-medium text-gray-600 mb-1">Otras condiciones:</h3>
                        <p>{{ $paciente->historial->otras_condiciones }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-gray-500 italic">
                    No se ha registrado historial médico para este paciente.
                </div>
                @endif
            </div>
        </div>

        <h2 class="text-xl font-semibold mb-4">Historial de Citas</h2>
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Fecha</th>
                        <th class="px-6 py-3 text-left">Especialidad</th>
                        <th class="px-6 py-3 text-left">Razón</th>
                        <th class="px-6 py-3 text-left">Estado</th>
                        <th class="px-6 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paciente->citas as $cita)
                    <tr class="border-b">
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} a las {{ $cita->hora_inicio }}</td>
                        <td class="px-6 py-4">{{ $cita->especialidad->nombre }}</td>
                        <td class="px-6 py-4">
                            {{ Str::limit($cita->razon_paciente, 50, '...') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded 
                                @if($cita->estado == 'completada') bg-green-100 text-green-800
                                @elseif($cita->estado == 'cancelada') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('medico.citas.detalles', $cita->id) }}" 
                               class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition duration-200">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center">No hay citas registradas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection