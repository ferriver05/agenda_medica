<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel DBA - Clínica Salud</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@extends('layouts.app')
@section('content')
<body class="bg-gray-100">
    
    <!-- DASHBOARD -->
    <section class="container mx-auto my-16 px-6">
        <h3 class="text-3xl font-bold text-center text-gray-800">Bienvenido, Administrador de Base de Datos</h3>
        <p class="text-center text-gray-600 mt-4">Administra la base de datos de la clínica con herramientas avanzadas.</p>
        <div class="grid md:grid-cols-3 gap-8 mt-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center flex flex-col justify-between h-full">
                <h4 class="text-xl font-bold text-blue-500">Gestión de Usuarios</h4>
                <p class="text-gray-600 mt-2">Administra los accesos y roles de los usuarios.</p>
                <a href="#" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-400 self-center">Acceder</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center flex flex-col justify-between h-full">
                <h4 class="text-xl font-bold text-blue-500">Respaldo y Recuperación</h4>
                <p class="text-gray-600 mt-2">Realiza copias de seguridad y recuperaciones eficientes.</p>
                <a href="#" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-400 self-center">Acceder</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center flex flex-col justify-between h-full">
                <h4 class="text-xl font-bold text-blue-500">Optimización del Sistema</h4>
                <p class="text-gray-600 mt-2">Monitorea y mejora el rendimiento de la base de datos.</p>
                <a href="#" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-400 self-center">Acceder</a>
            </div>
        </div>
    </section>

</body>
@endsection
</html>
