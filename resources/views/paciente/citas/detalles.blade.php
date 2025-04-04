@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-4xl">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Información de la Cita</h2>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-600"><strong>Médico:</strong> {{ $cita->medico->user->name }}</p>
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

            <div class="mt-6 flex flex-wrap gap-4">
                @if (in_array($cita->estado, ['pendiente', 'confirmada']))
                    <form action="{{ route('citas.cancelar', $cita->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" onclick="return confirm('¿Estás seguro de que deseas cancelar esta cita?')"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Cancelar Cita
                        </button>
                    </form>
                @endif

                <a href="{{ route('paciente.citas.resumen') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Regresar
                </a>
            </div>
        </div>
    </div>
@endsection
