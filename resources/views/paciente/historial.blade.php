@extends('layouts.app')
@section('content')
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Buscar Historial de Pacientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <main class="p-8 flex flex-col justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded shadow-md max-w-4xl w-full">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Buscar Historial de Pacientes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700" for="search-dni">Buscar por DNI</label>
                    <input class="w-full p-2 border rounded" id="search-dni" type="text" placeholder="Ingrese DNI"/>
                </div>
                <div class="flex items-end">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded w-full">Buscar</button>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded shadow-md max-w-4xl w-full mt-6">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Datos del Paciente</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700">Nombre del Médico</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Área del médico</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Nombre completo</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Código de seguro social</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Edad</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">DNI</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Sexo</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Número de celular</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Día y Fecha de la cita</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Hora de la cita</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
                <div>
                    <label class="block text-gray-700">Estado de la cita</label>
                    <input class="w-full p-2 border rounded" type="text" readonly/>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700">Información de lo que padece</label>
                <textarea class="w-full p-2 border rounded h-24" readonly></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700">Receta Médica</label>
                <div class="w-full p-2 border rounded flex justify-center">
                    <img id="receta-medica" src="ruta" alt="Receta Médica" class="max-w-xs rounded"/>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700">Notas del Médico</label>
                <textarea class="w-full p-2 border rounded h-24" readonly></textarea>
            </div>
            
            <div class="flex justify-center mt-4">
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded mr-2">Cancelar</button>
            </div>
        </div>
    </main>
    <footer class="bg-blue-500 text-white text-center p-4 mt-8">
        <p>© Derechos reservados. Elaborado por Clase TI-206-CN USAP.</p>
    </footer>
@endsection