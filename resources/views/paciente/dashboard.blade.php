@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Mi Panel de Salud</h1>
        <p class="text-gray-600">Bienvenido, {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Próxima Cita -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Próxima Cita</p>
                    @if($nextAppointment)
                        <p class="mt-1 text-xl font-semibold text-gray-900">
                            Dr. {{ $nextAppointment->medico->user->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($nextAppointment->fecha)->format('d M Y') }} a las 
                            {{ \Carbon\Carbon::parse($nextAppointment->hora_inicio)->format('h:i A') }}
                        </p>
                    @else
                        <p class="mt-1 text-lg font-medium text-gray-500">No tiene citas programadas</p>
                    @endif
                </div>
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Historial Médico -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Citas Realizadas</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $completedAppointments }}</p>
                </div>
                <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Datos de Salud -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tipo de Sangre</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ auth()->user()->paciente->tipo_sangre ?? 'No registrado' }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contacto de Emergencia -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-rose-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Contacto de Emergencia</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900">
                        {{ auth()->user()->paciente->contacto_emergencia ?? 'No registrado' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ auth()->user()->paciente->telefono_emergencia ?? '' }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-rose-50 text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Próximas Citas -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Mis Próximas Citas</h3>
                <a href="{{ route('paciente.citas.reservar') }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                    + Nueva Cita
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($upcomingAppointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->fecha)->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($appointment->hora_inicio)->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    Dr. {{ $appointment->medico->user->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Sala {{ $appointment->medico->numero_sala }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $appointment->especialidad->nombre }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $appointment->estado === 'confirmada' ? 'bg-green-100 text-green-800' : 
                                       ($appointment->estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($appointment->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                No tiene citas programadas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Información de Salud -->
        <div class="space-y-6">
            <!-- Datos Médicos -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mi Información Médica</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Alergias</p>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ auth()->user()->paciente->historial->alergias ?? 'No registradas' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Enfermedades Crónicas</p>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ auth()->user()->paciente->historial->enfermedades_cronicas ?? 'No registradas' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Medicamentos Actuales</p>
                        <p class="text-sm text-gray-900 mt-1">
                            {{ auth()->user()->paciente->historial->medicamentos ?? 'No registrados' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Citas -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Mis Citas (Últimos 6 Meses)</h3>
                <div class="h-64">
                    <canvas id="appointmentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Citas -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Citas Recientes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnóstico</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receta</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($appointmentHistory as $appointment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->fecha)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                Dr. {{ $appointment->medico->user->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $appointment->especialidad->nombre }}
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-gray-900">
                                {{ Str::limit($appointment->notas_medico ?? 'Sin diagnóstico registrado', 50) }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($appointment->imagen_prescripcion)
                            <a href="{{ asset('storage/' . $appointment->imagen_prescripcion) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver receta
                            </a>
                            @else
                            <span class="text-sm text-gray-500">No disponible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                            No tiene citas registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Citas (Últimos 6 meses)
    const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(appointmentsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($appointmentsLastMonths->pluck('month')) !!},
            datasets: [{
                label: 'Citas Completadas',
                data: {!! json_encode($appointmentsLastMonths->pluck('count')) !!},
                backgroundColor: '#3B82F6',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>
@endsection