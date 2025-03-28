@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col sm:flex-row gap-4">

            <input type="text" id="filtroTexto" placeholder="Buscar usuario..."
                class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" />

            <select id="filtroCriterio"
                class="w-full sm:w-1/4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="">No filtrar</option>
                <option value="dni">DNI</option>
                <option value="nombre">Nombre</option>
                <option value="correo">Correo</option>
                <option value="rol">Rol</option>
                <option value="estado">Estado</option>
            </select>

            <a href="{{ route('dba.usuarios.create') }}" 
                class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 text-center">
                Nuevo Usuario
            </a>

        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full" id="tablaUsuarios">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Creación</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($usuarios as $usuario)
                <tr class="filaUsuario">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->dni }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->rol }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($usuario->activo)
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded">Activo</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('dba.usuarios.show', $usuario->id) }}" class="px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition duration-200">
                            Ver
                        </a>
                        <a href="{{ route('dba.usuarios.edit', $usuario->id) }}" class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition duration-200 ml-2">
                            Editar
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filtroTexto = document.getElementById('filtroTexto');
        const filtroCriterio = document.getElementById('filtroCriterio');
        const filasUsuarios = document.querySelectorAll('.filaUsuario');

        function filtrarUsuarios() {
            const texto = filtroTexto.value.toLowerCase();
            const criterio = filtroCriterio.value;

            filasUsuarios.forEach(fila => {
                const dni = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const nombre = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const correo = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const rol = fila.querySelector('td:nth-child(5)').textContent.toLowerCase();
                const estado = fila.querySelector('td:nth-child(6)').textContent.toLowerCase();

                let coincide = true;

                if (criterio && texto) {
                    coincide = false;

                    if (criterio === 'dni' && dni.includes(texto)) {
                        coincide = true;
                    } else if (criterio === 'nombre' && nombre.includes(texto)) {
                        coincide = true;
                    } else if (criterio === 'correo' && correo.includes(texto)) {
                        coincide = true;
                    } else if (criterio === 'rol' && rol.includes(texto)) {
                        coincide = true;
                    } else if (criterio === 'estado' && estado.includes(texto)) {
                        coincide = true;
                    }
                }

                if (coincide) {
                    fila.style.display = 'table-row';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        filtroTexto.addEventListener('input', filtrarUsuarios);
        filtroCriterio.addEventListener('change', filtrarUsuarios);
    });
</script>
@endsection