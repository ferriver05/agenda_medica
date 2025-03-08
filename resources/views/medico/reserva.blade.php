<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido, Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/imagenes/D_reservaimagen.jpeg'); /* Cambia por tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
    <!-- Diseño encabezado -->
@extends('layouts.app')
@section('content')
<body class="bg-gray-100 bg-opacity-90">

    <!-- Contenedor principal -->
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <!-- Contenedor del contenido -->
        <div class="bg-white shadow-2xl rounded-lg p-8 w-full max-w-6xl bg-opacity-95 backdrop-blur-sm">
            <!-- Encabezado con título y botón -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <!-- Título alineado a la izquierda -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4 md:mb-0">Bienvenido, Doctor</h1>

                <!-- Botón "Agendar Cita Nueva" alineado a la derecha y un poco más abajo -->
                <button class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                    Agendar Cita Nueva
                </button>
            </div>

            <!-- Contenedor de citas en cuadrícula -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Ejemplo de cita -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-4 border-black">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cita de (Nombre Paciente)</h3>
                    <p class="text-gray-600 mb-4">Detalles de la cita...</p>
                    <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        Editar
                    </button>
                </div>

                <!-- Repetir para más citas -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-4 border-black">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cita de (Nombre Paciente)</h3>
                    <p class="text-gray-600 mb-4">Detalles de la cita...</p>
                    <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        Editar
                    </button>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow border-4 border-black">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Cita de (Nombre Paciente)</h3>
                    <p class="text-gray-600 mb-4">Detalles de la cita...</p>
                    <button class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        Editar
                    </button>
                </div>

                <!-- Puedes agregar más recuadros de citas aquí -->
            </div>
        </div>
    </div>

</body>
@endsection
</html>
