@extends('layouts.app')
@section('content')

    <div class="min-h-screen">
        <div class="py-12 px-4 flex items-center justify-center">
            <div class="w-full max-w-3xl mx-auto">
                <div class="bg-gray-50 rounded-xl card-container overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-500 to-teal-900 p-8 text-center">
                        <i class="fas fa-database text-5xl text-white mb-4"></i>
                        <h1 class="text-2xl font-bold text-white">Bienvenido, Administrador de Bases de Datos</h1>
                        <p class="text-white opacity-90 mt-2">MediSync - Sistema de Gestión Hospitalaria</p>
                    </div>
                    
                    <div class="p-8 bg-white">
                        <div class="mb-8 flex justify-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/1925/1925158.png" alt="Database" 
                                class="w-40 h-40">
                        </div>
                        
                        <div class="mb-8 text-gray-700 space-y-6">
                            <p class="text-center text-lg font-medium text-gray-800">
                                Su rol como DBA es fundamental para el funcionamiento de nuestro sistema de salud.
                            </p>
                            
                            <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                                <p class="mb-4">Como administrador de bases de datos en <span class="font-semibold text-blue-600">MediSync</span>, usted tiene acceso a las siguientes acciones:</p>
                                
                                <ul class="list-disc pl-5 space-y-3">
                                    <li class="flex items-start">
                                        <i class="fas fa-list mt-1 mr-2 text-blue-500"></i>
                                        <span>Visualizar la lista completa de usuarios registrados</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-user-edit mt-1 mr-2 text-blue-500"></i>
                                        <span>Editar la información de los usuarios existentes</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-user-plus mt-1 mr-2 text-blue-500"></i>
                                        <span>Crear nuevos usuarios para el personal médico</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-shield-alt mt-1 mr-2 text-blue-500"></i>
                                        <span>Gestionar permisos básicos del sistema</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-shield-alt mt-1 mr-2 text-blue-500"></i>
                                        <span>Gestionar backups del sistema</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-10 text-center">
                            <a href="/dba/usuarios" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition duration-300 shadow-md">
                                <i class="fas fa-users-cog mr-2"></i>Ir a Gestión de Usuarios
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 text-center text-xs text-gray-500 border-t border-gray-200">
                        <p>Sistema de Gestión Hospitalaria | Último acceso: {{ date('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
