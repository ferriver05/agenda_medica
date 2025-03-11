<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal Paciente</title>
     <!-- IMPORTANTE PARA QUE CARGUE EL TAILWINDCSS -->
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet"> 
     <!--                                            -->
</head>
@extends('layouts.app')
@section('content')
<body class="bg-gray-50"> 

    <!-- CONTENIDO -->
    <main class="py-16 px-4 text-center">
        <h2 class="text-3xl font-semibold text-gray-800">Bienvenido a la Clínica Salud Plus</h2>
        
        <!-- IMG CLINICA -->
        <div class="mt-8">
            <img src="{{ asset('img/clinica.jpg') }}" alt="Clínica" class="w-2xs mx-auto rounded-lg shadow-lg">
        </div>

        <!-- DIV 2DO PARRAFO -->
        <div class="mt-10 flex items-center justify-center max-w-5xl mx-auto p-6">
            <!-- IMG MEDICO -->
            <div class="w-1/2">
                <img src="{{ asset('img/doc text.jpg') }}" alt="Imagen" class="w-full h-auto rounded-lg object-cover">
            </div>
        
            <!-- CONTENIDO -->
            <div class="w-1/2 pl-8">
                <h2 class="text-3xl font-bold text-gray-900">Tu bienestar, <span class="font-extrabold">Nuestra prioridad</span></h2>
                <p class="text-lg text-gray-600 mt-4">
                    En Clínica Salud, nos especializamos en brindarte atención médica integral con profesionales altamente capacitados. 
                    Contamos con tecnología de vanguardia y un equipo comprometido con tu salud y la de tu familia. ¡Agenda tu cita hoy mismo!
                </p>
            </div>
        </div>
    
    </main>
</body>
@endsection
</html>