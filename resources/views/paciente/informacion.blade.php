@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-6 p-4 max-w-5xl">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header con degradado -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-white">
            <h1 class="text-3xl font-bold text-center">Mi Información Médica</h1>
            <p class="text-center text-blue-100 mt-2">Datos personales y registro de salud</p>
        </div>

        <div class="p-6 md:p-8">
            <!-- Sección de Datos Personales y Médicos -->
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

                <!-- Tarjeta Datos Médicos -->
                <div class="rounded-lg p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800">Datos Médicos</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <span class="font-medium text-gray-600 w-1/3">Tipo Sangre:</span>
                            <span class="text-gray-800 flex-1">{{ $paciente->tipo_sangre ?? 'No registrado' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Seguro Médico:</span>
                            <span class="text-gray-800 flex-1">{{ $paciente->seguro_medico ?? 'No registrado' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Ocupación:</span>
                            <span class="text-gray-800 flex-1">{{ $paciente->ocupacion ?? 'No registrada' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Contacto Emergencia:</span>
                            <span class="text-gray-800 flex-1">{{ $paciente->contacto_emergencia ?? 'No registrado' }}</span>
                        </div>
                        
                        <div class="flex items-start border-t border-gray-100 pt-3">
                            <span class="font-medium text-gray-600 w-1/3">Teléfono Emergencia:</span>
                            <span class="text-gray-800 flex-1">{{ $paciente->telefono_emergencia ?? 'No registrado' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Historial Médico -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Historial Médico</h2>
                </div>
                
                @if($historial)
                <div class="space-y-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-medium text-blue-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Alergias
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->alergias ?: 'Ninguna registrada' }}</p>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="font-medium text-green-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Enfermedades Crónicas
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->enfermedades_cronicas ?: 'Ninguna registrada' }}</p>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <h3 class="font-medium text-yellow-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Medicamentos Actuales
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->medicamentos ?: 'Ninguno registrado' }}</p>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg">
                        <h3 class="font-medium text-red-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cirugías
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->cirugias ?: 'Ninguna registrada' }}</p>
                    </div>
                    
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <h3 class="font-medium text-indigo-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Antecedentes Familiares
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->antecedentes_familiares ?: 'No registrados' }}</p>
                    </div>
                    
                    @if($historial->otras_condiciones)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-medium text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Otras Condiciones
                        </h3>
                        <p class="mt-2 text-gray-700 pl-7">{{ $historial->otras_condiciones }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No se ha registrado historial médico</h3>
                    <p class="mt-1 text-gray-500">Por favor contacte a su médico para completar esta información.</p>
                </div>
                @endif
            </div>

            <!-- Botón de regreso -->
            <div class="flex justify-end">
                <a href="{{ route('paciente.dashboard') }}" 
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