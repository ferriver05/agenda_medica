@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-4 p-4 max-w-5xl">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-center">Mi Información Médica</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <h2 class="text-xl font-semibold border-b pb-2">Datos Personales</h2>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium">Nombre:</span>
                    <span class="col-span-2">{{ $user->name }}</span>
                    
                    <span class="font-medium">DNI:</span>
                    <span class="col-span-2">{{ $user->dni }}</span>
                    
                    <span class="font-medium">Fecha Nacimiento:</span>
                    <span class="col-span-2">{{ $user->fecha_nacimiento ? \Carbon\Carbon::parse($user->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</span>
                    
                    <span class="font-medium">Género:</span>
                    <span class="col-span-2 capitalize">{{ $user->genero ?? 'No especificado' }}</span>
                    
                    <span class="font-medium">Email:</span>
                    <span class="col-span-2">{{ $user->email }}</span>
                    
                    <span class="font-medium">Teléfono:</span>
                    <span class="col-span-2">{{ $user->telefono ?? 'No registrado' }}</span>
                    
                    <span class="font-medium">Dirección:</span>
                    <span class="col-span-2">{{ $user->direccion ?? 'No registrada' }}</span>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-xl font-semibold border-b pb-2">Datos Médicos</h2>
                <div class="grid grid-cols-3 gap-4">
                    <span class="font-medium">Tipo Sangre:</span>
                    <span class="col-span-2">{{ $paciente->tipo_sangre ?? 'No registrado' }}</span>
                    
                    <span class="font-medium">Seguro Médico:</span>
                    <span class="col-span-2">{{ $paciente->seguro_medico ?? 'No registrado' }}</span>
                    
                    <span class="font-medium">Ocupación:</span>
                    <span class="col-span-2">{{ $paciente->ocupacion ?? 'No registrada' }}</span>
                    
                    <span class="font-medium">Contacto Emergencia:</span>
                    <span class="col-span-2">{{ $paciente->contacto_emergencia }}</span>
                    
                    <span class="font-medium">Teléfono Emergencia:</span>
                    <span class="col-span-2">{{ $paciente->telefono_emergencia }}</span>
                </div>
            </div>
        </div>

        <div class="mt-16">
            <h2 class="text-xl font-semibold border-b pb-2 mb-4">Historial Médico</h2>
            
            @if($historial)
            <div class="space-y-4">
                <div>
                    <h3 class="font-medium">Alergias:</h3>
                    <p class="mt-1">{{ $historial->alergias ?: 'Ninguna registrada' }}</p>
                </div>
                
                <div>
                    <h3 class="font-medium">Enfermedades Crónicas:</h3>
                    <p class="mt-1">{{ $historial->enfermedades_cronicas ?: 'Ninguna registrada' }}</p>
                </div>
                
                <div>
                    <h3 class="font-medium">Medicamentos Actuales:</h3>
                    <p class="mt-1">{{ $historial->medicamentos ?: 'Ninguno registrado' }}</p>
                </div>

                <div>
                    <h3 class="font-medium">Cirugías:</h3>
                    <p class="mt-1">{{ $historial->cirugias ?: 'Ninguna registrada' }}</p>
                </div>
                
                <div>
                    <h3 class="font-medium">Antecedentes Familiares:</h3>
                    <p class="mt-1">{{ $historial->antecedentes_familiares ?: 'No registrados' }}</p>
                </div>
                
                @if($historial->otras_condiciones)
                <div>
                    <h3 class="font-medium">Otras Condiciones:</h3>
                    <p class="mt-1">{{ $historial->otras_condiciones }}</p>
                </div>
                @endif
            </div>
            @else
            <p class="text-gray-500 italic">No se ha registrado historial médico.</p>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('paciente.dashboard') }}" 
               class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Regresar
            </a>
        </div>
    </div>
</div>
@endsection