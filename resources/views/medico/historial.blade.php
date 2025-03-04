<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Historial de Consultas del Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Imagen de fondo */
        body {
            background-image: url('/imagenes/Consulta_doctor.webp');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
    <!-- Diseño encabezado -->
@extends('layouts.app2')
@section('content')
<body class="bg-gray-100 bg-opacity-90">
    <main>
        <!-- Contenedor principal -->
        <div class="min-h-screen flex items-center justify-center px-4 py-8">
            <!-- Contenedor del menú y contenido -->
            <div class="bg-white shadow-2xl rounded-lg p-8 w-full max-w-8xl bg-opacity-95"> <!-- Aumentado a max-w-8xl -->
                <!-- Título del menú -->
                <h1 class="text-4xl font-bold text-center mb-8 text-black-800">Historial de Consultas</h1>

                <!-- Menú de opciones -->
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 mb-8">
                    <button onclick="mostrarSeccion('ver-historial')" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 w-full sm:w-auto">
                        Ver Historial de Consultas
                    </button>
                    
                    <button onclick="mostrarSeccion('buscar-historial')" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 w-full sm:w-auto">
                        Buscar Historial de Consultas
                    </button>
                </div>

                <!-- Contenido -->
                <div class="mt-8">
                    <!-- Sección de Ver Historial -->
                    <div id="ver-historial" class="seccion">
                        <h2 class="text-2xl font-semibold mb-6 text-black-800">Historial de Pacientes</h2>
                        <!-- Tabla para mostrar el historial -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200">
                                <thead class="bg-gray-800 text-white">
                                    <tr>
                                        <th class="px-4 py-2 text-center w-1/4">Nombre Completo</th>
                                        <th class="px-4 py-2 text-center w-1/6">Código Seguro Social</th>
                                        <th class="px-4 py-2 text-center w-1/4">Correo</th>
                                        <th class="px-4 py-2 text-center w-1/6">Número de Celular</th>
                                        <th class="px-4 py-2 text-center w-1/6">Ciudad</th>
                                        <th class="px-4 py-2 text-center w-1/6">Fecha de Nacimiento</th>
                                        <th class="px-4 py-2 text-center w-1/6">Edad</th>
                                        <th class="px-4 py-2 text-center w-1/6">DNI</th>
                                        <th class="px-4 py-2 text-center w-1/6">Sexo</th>
                                        <th class="px-4 py-2 text-center w-1/6">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700">
                                    <tr class="hover:bg-gray-50 transition duration-200">
                                        <td class="border px-4 py-2 truncate">Juan Melendez</td>
                                        <td class="border px-4 py-2 truncate">123-45-6789</td>
                                        <td class="border px-4 py-2">juan.melendez@example.com</td>
                                        <td class="border px-4 py-2 truncate">+504 9999-9999</td>
                                        <td class="border px-4 py-2">San Pedro</td>
                                        <td class="border px-4 py-2">2002-04-03</td>
                                        <td class="border px-4 py-2">22</td>
                                        <td class="border px-4 py-2">87654321</td>
                                        <td class="border px-4 py-2 text-center">Masculino</td>
                                        <td class="border px-4 py-2 text-center">
                                            <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red -700 transition duration-300">Eliminar</button>
                                        </td>
                                    </tr>
                                    <!-- Más filas se agregarán dinámicamente desde el backend -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sección de Buscar Historial -->
                    <div id="buscar-historial" class="seccion hidden">
                        <h2 class="text-2xl font-semibold mb-6 text-black-800">Buscar Historial</h2>
                        <div class="flex items-center space-x-4">
                            <label for="busqueda" class="block text-gray-700 font-semibold">Buscar por Nombre</label>
                            <input type="text" id="busqueda" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <button onclick="mostrarResultados()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Buscar</button>
                        </div>
                        <div id="resultados" class="mt-6 hidden">
                            <h3 class="text-xl font-semibold mb-4">Resultados de la Búsqueda</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead class="bg-gray-800 text-white">
                                        <tr>
                                            <th class="px-4 py-2 text-center w-1/4">Nombre Completo</th>
                                            <th class="px-4 py-2 text-center w-1/6">Código Seguro Social</th>
                                            <th class="px-4 py-2 text-center w-1/4">Correo</th>
                                            <th class="px-4 py-2 text-center w-1/6">Número de Celular</th>
                                            <th class="px-4 py-2 text-center w-1/6">Ciudad</th>
                                            <th class="px-4 py-2 text-center w-1/6">Fecha de Nacimiento</th>
                                            <th class="px-4 py-2 text-center w-1/6">Edad</th>
                                            <th class="px-4 py-2 text-center w-1/6">DNI</th>
                                            <th class="px-4 py-2 text-center w-1/6">Sexo</th>
                                            <th class="px-4 py-2 text-center w-1/6">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700" id="resultados-busqueda">
                                        <!-- Aquí se agregarán los resultados de la búsqueda -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Script para manejar la visibilidad de las secciones y mostrar resultados -->
        <script>
            function mostrarSeccion(seccionId) {
                document.querySelectorAll('.seccion').forEach(seccion => {
                    seccion.classList.add('hidden');
                });
                document.getElementById(seccionId).classList.remove('hidden');
            }

            function mostrarResultados() {
                const palabraClave = document.getElementById('busqueda').value;
                const resultados = [
                    { nombre: 'Juan Melendez', codigo: '123-45-6789', correo: 'juan.melendez@example.com', celular: '+504 9999-9999', ciudad: 'San Pedro', fechaNacimiento: '2002-04-03', edad: 22, dni: '87654321', sexo: 'Masculino' },
                ];

                const resultadosFiltrados = resultados.filter(item => item.nombre.toLowerCase().includes(palabraClave.toLowerCase()));
                const tbody = document.getElementById('resultados-busqueda');
                tbody.innerHTML = '';

                resultadosFiltrados.forEach(item => {
                    const row = `<tr>
                                    <td class="border px-4 py-2 truncate">${item.nombre}</td>
                                    <td class="border px-4 py-2 truncate">${item.codigo}</td>
                                    <td class="border px-4 py-2">${item.correo}</td>
                                    <td class="border px-4 py-2 truncate">${item.celular}</td>
                                    <td class="border px-4 py-2">${item.ciudad}</td>
                                    <td class="border px-4 py-2">${item.fechaNacimiento}</td>
                                    <td class="border px-4 py-2">${item.edad}</td>
                                    <td class="border px-4 py-2">${item.dni}</td>
                                    <td class="border px-4 py-2">${item.sexo}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <button class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-300">Eliminar</button>
                                    </td>
                                </tr>`;
                    tbody.innerHTML += row;
                });

                const resultadosDiv = document.getElementById('resultados');
                resultadosDiv.classList.remove('hidden');
            }

            mostrarSeccion('ver-historial');
        </script>
    </main>
</body>
@endsection
</html>