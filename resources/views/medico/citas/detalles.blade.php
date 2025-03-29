@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Información de la Cita</h2>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-600"><strong>Paciente:</strong> {{ $cita->paciente->user->name }}</p>
                </div>

                <div>
                    <p class="text-gray-600"><strong>Especialidad:</strong> {{ $cita->especialidad->nombre }}</p>
                </div>

                <div>
                    <p class="text-gray-600"><strong>Fecha:</strong> {{ $cita->fecha }}</p>
                    <p class="text-gray-600"><strong>Hora de inicio:</strong> {{ $cita->hora_inicio }}</p>
                    <p class="text-gray-600"><strong>Hora de fin:</strong> {{ $cita->hora_fin }}</p>
                </div>

                <div>
                    <p class="text-gray-600"><strong>Estado:</strong> {{ ucfirst($cita->estado) }}</p>
                </div>

                <div>
                    <p class="text-gray-600"><strong>Razón de la cita:</strong> {{ $cita->razon_paciente }}</p>
                </div>

                @if ($cita->notas_medico)
                    <div>
                        <p class="text-gray-600"><strong>Notas del médico:</strong> {{ $cita->notas_medico }}</p>
                    </div>
                @endif

                @if ($cita->imagen_prescripcion)
                    <div>
                        <p class="text-gray-600"><strong>Prescripción:</strong></p>
                        <img src="{{ asset('storage/' . $cita->imagen_prescripcion) }}" alt="Prescripción"
                            class="mt-2 rounded-lg shadow-md">
                    </div>
                @endif
            </div>

            <div class="mt-6">
                @if ($cita->estado === 'pendiente')
                    <div class="flex flex-wrap gap-4">
                        <form action="{{ route('medico.citas.confirmar', $cita->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" onclick="return confirm('¿Confirmar esta cita?')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Confirmar Cita
                            </button>
                        </form>

                        <form action="{{ route('medico.citas.rechazar', $cita->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" onclick="return confirm('¿Rechazar esta cita?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Rechazar Cita
                            </button>
                        </form>
                    </div>
                @endif

                @if ($cita->estado === 'confirmada')
                    <div class="flex flex-wrap gap-4">
                        <form action="{{ route('medico.citas.completar', $cita->id) }}" method="POST"
                            enctype="multipart/form-data" class="flex-1">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label for="notas_medico" class="block text-sm font-medium text-gray-700">Notas
                                        Médicas</label>
                                    <textarea name="notas_medico" id="notas_medico" rows="3"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notas_medico', $cita->notas_medico) }}</textarea>
                                </div>

                                <div>
                                    <label for="imagen_prescripcion"
                                        class="block text-sm font-medium text-gray-700">Prescripción (Imagen)</label>
                                    <input type="file" name="imagen_prescripcion" id="imagen_prescripcion"
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <button type="submit" onclick="return confirm('¿Marcar esta cita como completada?')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Marcar como Completada
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ url()->previous() ?? route('medico.citas.resumen') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        Regresar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection