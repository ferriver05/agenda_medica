@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    <h1 class="text-2xl font-bold mb-6">Detalles Completos del Usuario</h1>

    <!-- Datos Generales (users) -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Información Básica</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Columna 1 -->
            <div class="space-y-2">
                <p><span class="font-medium text-gray-600">Nombre:</span> {{ $usuario->name }}</p>
                <p><span class="font-medium text-gray-600">Email:</span> {{ $usuario->email }}</p>
                <p><span class="font-medium text-gray-600">Rol:</span> {{ $usuario->rol }}</p>
                <p><span class="font-medium text-gray-600">DNI:</span> {{ $usuario->dni }}</p>
            </div>
            <!-- Columna 2 -->
            <div class="space-y-2">
                <p><span class="font-medium text-gray-600">Fecha Nacimiento:</span> {{ $usuario->fecha_nacimiento ?? 'N/A' }}</p>
                <p><span class="font-medium text-gray-600">Teléfono:</span> {{ $usuario->telefono ?? 'N/A' }}</p>
                <p><span class="font-medium text-gray-600">Género:</span> {{ $usuario->genero ?? 'N/A' }}</p>
            </div>
            <!-- Columna 3 -->
            <div class="space-y-2">
                <p><span class="font-medium text-gray-600">Dirección:</span> {{ $usuario->direccion ?? 'N/A' }}</p>
                <p><span class="font-medium text-gray-600">Estado:</span> {{ $usuario->activo ? 'Activo' : 'Inactivo' }}</p>
            </div>
        </div>
    </div>

    <!-- Datos Específicos por Rol -->
    @if($usuario->rol === 'Medico')
        @include('dba.usuarios.partials.detalles-medico', ['medico' => $usuario->medico])
    @elseif($usuario->rol === 'Paciente')
        @include('dba.usuarios.partials.detalles-paciente', ['paciente' => $usuario->paciente])
    @endif

    <!-- Botón de regreso -->
    <div class="mt-6">
        <a href="{{ route('dba.usuarios.resumen') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            Volver al listado
        </a>
    </div>
</div>
@endsection