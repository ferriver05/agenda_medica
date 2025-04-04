<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Información Médica</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="space-y-4">
            <div>
                <p class="font-medium text-gray-600 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Número de Licencia:
                </p>
                <p class="text-lg">{{ $medico->numero_licencia }}</p>
            </div>

            <div>
                <p class="font-medium text-gray-600">Número de Sala:</p>
                <p class="text-lg">{{ $medico->numero_sala }}</p>
            </div>
        </div>

        <div>
            <p class="font-medium text-gray-600 mb-2">Especialidades:</p>
            @if ($medico->especialidades->count() > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach ($medico->especialidades as $especialidad)
                        <span
                            class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm hover:bg-blue-200 transition cursor-help"
                            title="{{ $especialidad->descripcion ?? 'Sin descripción' }}">
                            {{ $especialidad->nombre }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic">No tiene especialidades registradas</p>
            @endif
        </div>
    </div>

    <h3 class="text-lg font-semibold mb-4">Horario de Atención</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($medico->disponibilidades_organizadas as $dia => $disponibilidades)
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-blue-50 px-4 py-2 border-b">
                    <h4 class="font-medium text-blue-800">{{ $dia }}</h4>
                </div>

                <div class="p-4">
                    @if ($disponibilidades->count() > 0)
                        <ul class="space-y-2">
                            @foreach ($disponibilidades as $disponibilidad)
                                <li class="flex justify-between items-center">
                                    <span class="text-gray-700">
                                        {{ \Carbon\Carbon::parse($disponibilidad->hora_inicio)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($disponibilidad->hora_fin)->format('H:i') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic">No hay horarios este día</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>