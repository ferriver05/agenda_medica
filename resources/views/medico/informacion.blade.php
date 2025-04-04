@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6 p-4 max-w-5xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header con degradado -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <h1 class="text-3xl font-bold text-center">Perfil Médico</h1>
            <p class="text-center text-blue-100 mt-2">Información profesional y datos personales</p>
        </div>

        <div class="p-6 md:p-8">
            <!-- Sección de Datos Personales y Profesionales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <!-- Tarjeta Datos Personales -->
                <div class="rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Datos Personales</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 w-1/3">Nombre:</span>
                            <span class="text-gray-800 flex-1">{{ $user->name }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">DNI:</span>
                            <span class="text-gray-800 flex-1">{{ $user->dni }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Nacimiento:</span>
                            <span class="text-gray-800 flex-1">{{ $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Género:</span>
                            <span class="text-gray-800 flex-1 capitalize">{{ $user->genero ?? 'No especificado' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Email:</span>
                            <span class="text-gray-800 flex-1">{{ $user->email }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Teléfono:</span>
                            <span class="text-gray-800 flex-1">{{ $user->telefono ?? 'No registrado' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Dirección:</span>
                            <span class="text-gray-800 flex-1">{{ $user->direccion ?? 'No registrada' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta Datos Profesionales -->
                <div class="rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Datos Profesionales</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 w-1/3">Licencia Médica:</span>
                            <span class="text-gray-800 flex-1">{{ $medico->numero_licencia }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Sala/Número:</span>
                            <span class="text-gray-800 flex-1">{{ $medico->numero_sala }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Especialidades:</span>
                            <div class="text-gray-800 flex-1">
                                @if($especialidades->count() > 0)
                                    <ul class="list-disc pl-5">
                                        @foreach($especialidades as $especialidad)
                                            <li>{{ $especialidad->nombre }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span>No tiene especialidades registradas</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Horario -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Horario de Atención</h2>
                </div>
                
                @if($disponibilidades->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Día</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horario</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($disponibilidades as $disponibilidad)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ [
                                        0 => 'Domingo',
                                        1 => 'Lunes',
                                        2 => 'Martes',
                                        3 => 'Miércoles',
                                        4 => 'Jueves',
                                        5 => 'Viernes',
                                        6 => 'Sábado'
                                    ][$disponibilidad->dia_semana] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($disponibilidad->hora_inicio)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($disponibilidad->hora_fin)->format('h:i A') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No se ha registrado horario de atención</h3>
                    <p class="mt-1 text-gray-500">Por favor configure su disponibilidad en el sistema.</p>
                </div>
                @endif
            </div>

            <!-- Botón de regreso -->
            <div class="flex justify-end">
                <a href="{{ route('medico.dashboard') }}" 
                   class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Regresar al Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection