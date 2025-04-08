@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Panel Médico</h1>
        <p class="text-gray-600">Bienvenido, Dr. {{ auth()->user()->name }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Citas Hoy -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Citas Hoy</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $todayAppointments }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Citas Pendientes -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-amber-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendientes</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $pendingAppointments }}</p>
                </div>
                <div class="p-3 rounded-full bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pacientes Atendidos -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-emerald-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Atendidos (Mes)</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $monthlyCompleted }}</p>
                </div>
                <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Citas Confirmadas -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-indigo-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Confirmadas</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $confirmedAppointments }}</p>
                </div>
                <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Agenda del Día (Solo Confirmadas) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Agenda Hoy (Confirmadas)</h3>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ now()->format('d M Y') }}
                </span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($todayConfirmedAppointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->hora_inicio)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">{{ initials($appointment->paciente->user->name) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->paciente->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $appointment->paciente->user->dni }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $appointment->tipo_cita === 'primera_vez' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $appointment->tipo_cita === 'primera_vez' ? 'Primera Vez' : 'Seguimiento' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $appointment->paciente->telefono_emergencia }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500">
                                No hay citas confirmadas para hoy
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="space-y-6">
            <!-- Actividad Mensual -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Actividad - {{ now()->translatedFormat('F Y') }}</h3>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        {{ now()->format('M') }}
                    </span>
                </div>
                <div class="h-64 mb-4">
                    <canvas id="monthlyChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Citas Completadas</p>
                        <p class="text-xl font-bold text-blue-600">{{ $monthlyCompleted }}</p>
                    </div>
                    <div class="bg-amber-50 p-3 rounded-lg">
                        <p class="text-sm font-medium text-amber-800">Citas Canceladas</p>
                        <p class="text-xl font-bold text-amber-600">{{ $monthlyCancelled }}</p>
                    </div>
                </div>
            </div>

            <!-- Confirmadas vs Pendientes Chart -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Confirmadas vs Pendientes</h3>
                <div class="h-64">
                    <canvas id="confirmedVsPendingChart"></canvas>
                </div>
                <div class="mt-2 text-center text-sm text-gray-500">
                    <span class="inline-block w-3 h-3 rounded-full bg-indigo-500 mr-1"></span>
                    Confirmadas: {{ $confirmedAppointments }}
                    <span class="inline-block w-3 h-3 rounded-full bg-amber-500 ml-4 mr-1"></span>
                    Pendientes: {{ $pendingAppointments }}
                </div>
            </div>
        </div>
    </div>

    <!-- Pacientes Recientes (Solo Completadas) -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pacientes Atendidos Recientemente</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($recentCompletedPatients as $patient)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-600 font-medium">{{ initials($patient->user->name) }}</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $patient->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $patient->user->dni }}</p>
                        <p class="text-xs mt-1">
                            <span class="px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-800">
                                {{ $patient->tipo_sangre ?? 'SN' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="mt-3 text-xs text-gray-500">
                    <p>Última atención: 
                        @php
                            echo $patient->last_completed 
                                ? \Carbon\Carbon::parse($patient->last_completed)->format('d M Y') 
                                : 'Nunca';
                        @endphp
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico Mensual (Solo días)
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyStats->pluck('day')) !!},
            datasets: [{
                label: 'Completadas',
                data: {!! json_encode($monthlyStats->pluck('completed')) !!},
                backgroundColor: '#10B981',
                borderRadius: 4
            }, {
                label: 'Canceladas',
                data: {!! json_encode($monthlyStats->pluck('cancelled')) !!},
                backgroundColor: '#F43F5E',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Días del mes'
                    }
                }
            }
        }
    });

    // Gráfico Confirmadas vs Pendientes
    const confirmedVsPendingCtx = document.getElementById('confirmedVsPendingChart').getContext('2d');
    new Chart(confirmedVsPendingCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmadas', 'Pendientes'],
            datasets: [{
                data: [{{ $confirmedAppointments }}, {{ $pendingAppointments }}],
                backgroundColor: ['#6366F1', '#F59E0B'],
                borderColor: ['#4F46E5', '#D97706'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} citas`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection