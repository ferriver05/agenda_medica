@extends('layouts.app')

@section('content')
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Historial De Clinica De Pacientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <main class="p-8 flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded shadow-md max-w-4xl w-full">
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700" for="doctor-name">Nombre del Médico</label>
                        <select class="w-full p-2 border rounded" id="doctor-name">
                            <option>Seleccionar</option>
                            <option>Dr. Juan Pérez</option>
                            <option>Dra. María González</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="doctor-area">Área del médico</label>
                        <select class="w-full p-2 border rounded" id="doctor-area">
                            <option>Seleccionar</option>
                            <option>Cardiología</option>
                            <option>Pediatría</option>
                            <option>Neurología</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="full-name">Nombre completo</label>
                        <input class="w-full p-2 border rounded" id="full-name" type="text"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="social-security">Código de seguro social</label>
                        <input class="w-full p-2 border rounded" id="social-security" type="text"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="age">Edad</label>
                        <input class="w-full p-2 border rounded" id="age" type="text"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="dni">DNI</label>
                        <input class="w-full p-2 border rounded" id="dni" type="text"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="gender">Sexo</label>
                        <select class="w-full p-2 border rounded" id="gender">
                            <option>Seleccionar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="phone-number">Número de celular</label>
                        <input class="w-full p-2 border rounded" id="phone-number" type="text"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="appointment-date">Día y Fecha de la cita</label>
                        <input class="w-full p-2 border rounded" id="appointment-date" type="date"/>
                    </div>
                    <div>
                        <label class="block text-gray-700" for="appointment-time">Hora de la cita</label>
                        <input class="w-full p-2 border rounded" id="appointment-time" type="text"/>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700" for="notes">Información de lo que padece</label>
                    <textarea class="w-full p-2 border rounded h-32" id="notes"></textarea>
                </div>
                <div class="flex justify-center mt-4">
                    <button class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded mr-2">Cancelar</button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-blue-500 text-white text-center p-4 mt-8">
        <p>© Derechos reservados. Elaborado por Clase TI-206-CN USAP.</p>
    </footer>
@endsection