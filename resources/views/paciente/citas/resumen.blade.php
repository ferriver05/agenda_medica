@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Resumen de Citas</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <div class="flex flex-col sm:flex-row gap-4">

                <input type="text" id="filtroTexto" placeholder="Buscar cita..."
                    class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" />

                <select id="filtroCriterio"
                    class="w-full sm:w-1/4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    <option value="">No filtrar</option>
                    <option value="medico">Médico</option>
                    <option value="especialidad">Especialidad</option>
                    <option value="fecha">Fecha</option>
                    <option value="estado">Estado</option>
                </select>

            </div>
        </div>

        <!-- Sección de Citas Confirmadas -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Confirmadas</h2>
            <div id="citasConfirmadas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasConfirmadas as $cita)
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                        <p class="text-gray-600">{{ $cita->especialidad->nombre }}</p>
                        <p class="text-gray-500 text-sm">{{ $cita->fecha }} - {{ $cita->hora_inicio }}</p>
                        <p class="text-gray-600 estado-cita">Estado: {{ ucfirst($cita->estado) }}</p>
                        <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                            class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Detalles
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Sección de Citas Pendientes de Confirmación -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Pendientes de Confirmación</h2>
            <div id="citasPendientes" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasPendientes as $cita)
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                        <p class="text-gray-600">{{ $cita->especialidad->nombre }}</p>
                        <p class="text-gray-500 text-sm">{{ $cita->fecha }} - {{ $cita->hora_inicio }}</p>
                        <p class="text-gray-600 estado-cita">Estado: {{ ucfirst($cita->estado) }}</p>
                        <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                            class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Detalles
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Sección de Citas Pasadas -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Pasadas</h2>
            <div id="citasPasadas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasPasadas as $cita)
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                        <p class="text-gray-600">{{ $cita->especialidad->nombre }}</p>
                        <p class="text-gray-500 text-sm">{{ $cita->fecha }} - {{ $cita->hora_inicio }}</p>
                        <p class="text-gray-600 estado-cita">Estado: {{ ucfirst($cita->estado) }}</p>
                        <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                            class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            Detalles
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filtroTexto = document.getElementById('filtroTexto');
            const filtroCriterio = document.getElementById('filtroCriterio');

            function filtrarCitas() {
                const texto = filtroTexto.value.toLowerCase();
                const criterio = filtroCriterio.value;

                const secciones = [
                    document.getElementById('citasConfirmadas'),
                    document.getElementById('citasPendientes'),
                    document.getElementById('citasPasadas')
                ];

                secciones.forEach(seccion => {
                    const citas = seccion.querySelectorAll('.cita');

                    citas.forEach(cita => {
                        const medicoElement = cita.querySelector('h3');
                        const especialidadElement = cita.querySelector('p.text-gray-600');
                        const fechaElement = cita.querySelector('p.text-gray-500');
                        const estadoElement = cita.querySelector('p.estado-cita');

                        const medico = medicoElement ? medicoElement.textContent.toLowerCase() : '';
                        const especialidad = especialidadElement ? especialidadElement.textContent
                            .toLowerCase() : '';
                        const fecha = fechaElement ? fechaElement.textContent.toLowerCase() : '';
                        const estado = estadoElement ? estadoElement.textContent.toLowerCase()
                            .replace('estado:', '').trim() : '';

                        console.log("Cita:", {
                            medico,
                            especialidad,
                            fecha,
                            estado
                        });

                        let coincide = true;

                        if (criterio && texto) {
                            coincide = false;

                            if (criterio === 'medico' && medico.includes(texto)) {
                                coincide = true;
                            } else if (criterio === 'especialidad' && especialidad.includes(
                                texto)) {
                                coincide = true;
                            } else if (criterio === 'fecha' && fecha.includes(texto)) {
                                coincide = true;
                            } else if (criterio === 'estado' && estado.includes(texto)) {
                                coincide = true;
                            }
                        }

                        if (coincide) {
                            cita.style.display = 'block';
                        } else {
                            cita.style.display = 'none';
                        }
                    });
                });
            }

            filtroTexto.addEventListener('input', filtrarCitas);
            filtroCriterio.addEventListener('change', filtrarCitas);
        });
    </script>
@endsection
