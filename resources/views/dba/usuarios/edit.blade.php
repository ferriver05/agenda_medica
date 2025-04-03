@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-7xl">
        <h1 class="text-2xl font-bold mb-6">Editar Usuario: {{ $usuario->name }}</h1>

        <form action="{{ route('dba.usuarios.update', $usuario) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Errores en el formulario</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
            @endif

            <h2 class="text-xl font-semibold mb-4">Datos Generales</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">DNI</label>
                    <p class="mt-1 p-2 bg-gray-100 rounded-md">{{ $usuario->dni }}</p>
                    <input type="hidden" name="dni" value="{{ $usuario->dni }}">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <p class="mt-1 p-2 bg-gray-100 rounded-md">{{ $usuario->rol }}</p>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $usuario->name) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento', $usuario->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('Y-m-d') : '') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        <option value="masculino" {{ old('genero', $usuario->genero) == 'masculino' ? 'selected' : '' }}>
                            Masculino</option>
                        <option value="femenino" {{ old('genero', $usuario->genero) == 'femenino' ? 'selected' : '' }}>
                            Femenino</option>
                        <option value="otro" {{ old('genero', $usuario->genero) == 'otro' ? 'selected' : '' }}>Otro
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" id="direccion"
                        value="{{ old('direccion', $usuario->direccion) }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña (dejar en blanco para
                        no cambiar)</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                        Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                @if ($usuario->rol != 'DBA')
                    <div class="mb-4 flex items-center">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" id="activo" value="1"
                            {{ old('activo', $usuario->activo) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="activo" class="ml-2 block text-sm text-gray-700">Usuario activo</label>
                    </div>
                @endif
            </div>

            <!-- Campos específicos por rol -->
            @if ($usuario->rol === 'Medico')
                @include('dba.usuarios.partials.edit-medico', ['medico' => $usuario->medico])
            @elseif($usuario->rol === 'Paciente')
                @include('dba.usuarios.partials.edit-paciente', ['paciente' => $usuario->paciente])
            @endif

            <div class="mt-6 flex justify-between">
                <a href="{{ route('dba.usuarios.resumen') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
