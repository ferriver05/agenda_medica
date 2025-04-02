@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="filtroTexto" placeholder="Buscar paciente..."
                class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" />

            <select id="filtroCriterio"
                class="w-full sm:w-1/4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="">No filtrar</option>
                <option value="dni">DNI</option>
                <option value="nombre">Nombre</option>
                <option value="correo">Correo</option>
                <option value="estado">Estado</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full" id="tablaPacientes">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Sangre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Última Cita</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($pacientes as $paciente)
                <tr class="filaPaciente">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paciente->user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paciente->user->dni }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paciente->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paciente->user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $paciente->tipo_sangre ?? 'No especificado' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if ($paciente->user->activo)
                            <span class="px-2 py-1 bg-green-100 text-green-600 rounded">Activo</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-600 rounded">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $paciente->ultimaCita ? \Carbon\Carbon::parse($paciente->ultimaCita->fecha)->format('d/m/Y') : 'Sin citas' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('medico.pacientes.detalles', $paciente->id) }}" 
                           class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition duration-200">
                            Ver
                        </a>
                        @if($paciente->user->activo)
                            <a href="{{ route('medico.citas.reservar', ['dni' => $paciente->user->dni]) }}" 
                                class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 transition duration-200">
                                Agendar Cita
                            </a>
                        @endif
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
        const filasPacientes = document.querySelectorAll('.filaPaciente');

        function filtrarPacientes() {
            const texto = filtroTexto.value.toLowerCase();
            const criterio = filtroCriterio.value;

            filasPacientes.forEach(fila => {
                const dni = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const nombre = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const correo = fila.querySelector('td:nth-child(4)').textContent.toLowerCase();
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

        filtroTexto.addEventListener('input', filtrarPacientes);
        filtroCriterio.addEventListener('change', filtrarPacientes);
    });
</script>
@endsection