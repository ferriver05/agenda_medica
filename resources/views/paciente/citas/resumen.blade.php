@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Citas</h1>

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
                
                <a href="{{ route('paciente.citas.reservar') }}" 
                    class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 text-center">
                    Agendar Nueva Cita
                </a>
            </div>
        </div>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Próximas Citas</h2>
            <div id="citasConfirmadas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasConfirmadas as $cita)
                    @php
                        $fechaCita = \Carbon\Carbon::parse($cita->fecha);
                        $horaFin = \Carbon\Carbon::parse($cita->hora_fin);
                        $fechaFinCita = $fechaCita->copy()->setTimeFrom($horaFin);
                        $diasRestantes = now()->diffInDays($fechaCita, false);
                    @endphp
                    
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border-l-4 border-green-500">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Confirmada
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-1 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->especialidad->nombre }}
                        </p>
                        
                        <p class="text-gray-500 text-sm mb-2 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->fecha }}
                            <svg class="h-5 w-5 ml-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->hora_inicio }}
                        </p>

                        <div class="mt-3 flex justify-between items-center">
                            <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                Detalles
                            </a>
                            
                            @if ($diasRestantes <= 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Hoy
                                </span>
                            @elseif($diasRestantes <= 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    Próximamente
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas en Pendientes de Confirmación</h2>
            <div id="citasPendientes" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasPendientes as $cita)
                    @php
                        $fechaCita = \Carbon\Carbon::parse($cita->fecha);
                        $diasRestantes = now()->diffInDays($fechaCita, false);
                    @endphp
                    
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border-l-4 border-yellow-500">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Pendiente
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-1 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->especialidad->nombre }}
                        </p>
                        
                        <p class="text-gray-500 text-sm mb-2 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->fecha }}
                            <svg class="h-5 w-5 ml-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->hora_inicio }}
                        </p>

                        <div class="mt-3 flex justify-between items-center">
                            <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                Detalles
                            </a>
                            
                            @if ($diasRestantes <= 3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    @if ($diasRestantes <= 0) Pronto @else En {{ $diasRestantes }} días @endif
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Historial de Citas</h2>
            <div id="citasPasadas" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($citasPasadas as $cita)
                    <div class="cita bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border-l-4 
                        @if($cita->estado == 'completada') border-blue-500 
                        @elseif($cita->estado == 'cancelada') border-red-500 
                        @else border-gray-500 @endif">
                        
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-gray-700">{{ $cita->medico->user->name }}</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($cita->estado == 'completada') bg-blue-100 text-blue-800 
                                @elseif($cita->estado == 'cancelada') bg-red-100 text-red-800 
                                @else bg-gray-100 text-gray-800 @endif">
                                
                                @if($cita->estado == 'completada')
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($cita->estado == 'cancelada')
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </div>
                        
                        <p class="text-gray-600 mb-1 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->especialidad->nombre }}
                        </p>
                        
                        <p class="text-gray-500 text-sm mb-3 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->fecha }}
                            <svg class="h-5 w-5 ml-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ $cita->hora_inicio }}
                        </p>
                        
                        <a href="{{ route('paciente.citas.detalles', $cita->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            Ver detalles
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
                        const estadoElement = cita.querySelector('span.inline-flex');

                        const medico = medicoElement ? medicoElement.textContent.toLowerCase() : '';
                        const especialidad = especialidadElement ? especialidadElement.textContent.toLowerCase() : '';
                        const fecha = fechaElement ? fechaElement.textContent.toLowerCase() : '';
                        const estado = estadoElement ? estadoElement.textContent.toLowerCase() : '';

                        let coincide = true;

                        if (criterio && texto) {
                            coincide = false;

                            if (criterio === 'medico' && medico.includes(texto)) {
                                coincide = true;
                            } else if (criterio === 'especialidad' && especialidad.includes(texto)) {
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
