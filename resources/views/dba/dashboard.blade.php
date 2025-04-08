@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Administrativo</h1>
        <p class="text-gray-600">Resumen estadístico completo del sistema</p>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Usuarios</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $usersCount }}</p>
                </div>
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Roles Distribution -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <p class="text-sm font-medium text-gray-500 mb-4">Distribución por Rol</p>
            <div class="space-y-3">
                @foreach($usersByRole as $role => $total)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="capitalize">{{ $role }}</span>
                        <span class="font-medium">{{ $total }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" 
                             style="width: {{ ($total/$usersCount)*100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Active Appointments -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Citas Activas</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ ($appointmentsStatus->firstWhere('estado', 'confirmada')->total ?? 0) + ($appointmentsStatus->firstWhere('estado', 'pendiente')->total ?? 0) }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Citas Finalizadas</p>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ ($appointmentsStatus->firstWhere('estado', 'completada')->total ?? 0) + ($appointmentsStatus->firstWhere('estado', 'cancelada')->total ?? 0) }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-emerald-50 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Active Appointments Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Citas Activas</h3>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    Estado actual
                </span>
            </div>
            <div class="h-64">
                <canvas id="activeAppointmentsChart"></canvas>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-center">
                <span class="inline-block w-3 h-3 rounded-full bg-indigo-500 mr-1"></span>
                Confirmadas: {{ $appointmentsStatus->firstWhere('estado', 'confirmada')->total ?? 0 }}
                <span class="inline-block w-3 h-3 rounded-full bg-amber-500 ml-4 mr-1"></span>
                Pendientes: {{ $appointmentsStatus->firstWhere('estado', 'pendiente')->total ?? 0 }}
            </div>
        </div>

        <!-- Historical Appointments Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Histórico de Citas</h3>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                    Finalizadas
                </span>
            </div>
            <div class="h-64">
                <canvas id="historicalAppointmentsChart"></canvas>
            </div>
            <div class="mt-2 text-sm text-gray-500 text-center">
                <span class="inline-block w-3 h-3 rounded-full bg-emerald-500 mr-1"></span>
                Completadas: {{ $appointmentsStatus->firstWhere('estado', 'completada')->total ?? 0 }}
                <span class="inline-block w-3 h-3 rounded-full bg-rose-500 ml-4 mr-1"></span>
                Canceladas: {{ $appointmentsStatus->firstWhere('estado', 'cancelada')->total ?? 0 }}
            </div>
        </div>
    </div>

    <!-- Bottom Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Specialties -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Especialidades Más Solicitadas</h3>
            <ul class="divide-y divide-gray-200">
                @foreach($topSpecialties as $specialty)
                <li class="py-3 flex justify-between items-center">
                    <div class="flex items-center">
                        <span class="inline-block w-3 h-3 rounded-full bg-blue-500 mr-3"></span>
                        <span class="text-sm font-medium text-gray-700">{{ $specialty->nombre }}</span>
                    </div>
                    <span class="text-sm font-semibold bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                        {{ $specialty->citas_count }} citas
                    </span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Top Doctors -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Médicos con Más Citas</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médico</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Citas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topDoctors as $doctor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-600 font-medium">{{ initials($doctor->user->name) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $doctor->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $doctor->citas_count }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración común para gráficos de barras
    const barChartConfig = {
        type: 'bar',
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { 
                    backgroundColor: '#1F2937',
                    titleFont: { size: 14 },
                    bodyFont: { size: 14 },
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} citas`;
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { drawBorder: false },
                    ticks: { precision: 0 }
                },
                x: { 
                    grid: { display: false },
                    barPercentage: 0.6
                }
            }
        }
    };

    // Gráfico de citas activas
    new Chart(
        document.getElementById('activeAppointmentsChart'),
        {
            ...barChartConfig,
            data: {
                labels: ['Confirmadas', 'Pendientes'],
                datasets: [{
                    data: {!! json_encode([
                        $appointmentsStatus->firstWhere('estado', 'confirmada')->total ?? 0,
                        $appointmentsStatus->firstWhere('estado', 'pendiente')->total ?? 0
                    ]) !!},
                    backgroundColor: ['#6366F1', '#F59E0B'],
                    borderColor: ['#4F46E5', '#D97706'],
                    borderWidth: 1,
                    borderRadius: 8
                }]
            }
        }
    );

    // Gráfico de histórico
    new Chart(
        document.getElementById('historicalAppointmentsChart'),
        {
            ...barChartConfig,
            data: {
                labels: ['Completadas', 'Canceladas'],
                datasets: [{
                    data: {!! json_encode([
                        $appointmentsStatus->firstWhere('estado', 'completada')->total ?? 0,
                        $appointmentsStatus->firstWhere('estado', 'cancelada')->total ?? 0
                    ]) !!},
                    backgroundColor: ['#10B981', '#F43F5E'],
                    borderColor: ['#059669', '#E11D48'],
                    borderWidth: 1,
                    borderRadius: 8
                }]
            }
        }
    );
</script>
@endsection