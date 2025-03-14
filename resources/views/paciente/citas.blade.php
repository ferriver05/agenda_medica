@extends('layouts.app')

@section('content')
@php
    $citasConfirmadas = [
        (object) [
            'titulo' => 'Cita con el Dr. Pérez',
            'descripcion' => 'Consulta general',
            'fecha' => '2023-10-15 10:00',
        ],
        (object) [
            'titulo' => 'Revisión de análisis',
            'descripcion' => 'Revisión de resultados de laboratorio',
            'fecha' => '2023-10-20 14:00',
        ],
    ];

    $citasPendientes = [
        (object) [
            'titulo' => 'Cita con el Dr. Gómez',
            'descripcion' => 'Consulta de seguimiento',
            'fecha' => '2023-11-01 09:00',
        ],
    ];

    $citasPasadas = [
        (object) [
            'titulo' => 'Cita con el Dr. Rodríguez',
            'descripcion' => 'Chequeo anual',
            'fecha' => '2023-09-10 11:00',
        ],
        (object) [
            'titulo' => 'Vacunación',
            'descripcion' => 'Aplicación de vacuna contra la influenza',
            'fecha' => '2023-08-25 16:00',
        ],
        (object) [
            'titulo' => 'Consulta de rutina',
            'descripcion' => 'Revisión de presión arterial',
            'fecha' => '2023-07-15 08:30',
        ],
    ];
@endphp

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Mis Citas</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
            <input
                type="text"
                id="filtroTexto"
                placeholder="Buscar cita..."
                class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <select
                id="filtroCriterio"
                class="w-full sm:w-1/4 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="titulo">Título</option>
                <option value="descripcion">Descripción</option>
                <option value="fecha">Fecha</option>
            </select>

            <button
                id="botonFiltrar"
                class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                Filtrar
            </button>
        </div>
    </div>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Confirmadas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($citasConfirmadas as $cita)
                <div class="cita bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700">{{ $cita->titulo }}</h3>
                    <p class="text-gray-600">{{ $cita->descripcion }}</p>
                    <p class="text-gray-500 text-sm">{{ $cita->fecha }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Pendientes de Confirmación</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($citasPendientes as $cita)
                <div class="cita bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700">{{ $cita->titulo }}</h3>
                    <p class="text-gray-600">{{ $cita->descripcion }}</p>
                    <p class="text-gray-500 text-sm">{{ $cita->fecha }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Citas Pasadas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($citasPasadas as $cita)
                <div class="cita bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700">{{ $cita->titulo }}</h3>
                    <p class="text-gray-600">{{ $cita->descripcion }}</p>
                    <p class="text-gray-500 text-sm">{{ $cita->fecha }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection