@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Backups</h1>
            <form action="{{ route('backups.create') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Crear Nuevo Backup
                </button>
            </form>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium">Backups Existentes</h3>
                <span class="text-sm text-gray-500">
                    {{ count($backups) }} {{ Str::plural('backup', count($backups)) }}
                </span>
            </div>

            @if (count($backups) > 0)
                <div class="divide-y divide-gray-200">
                    @foreach ($backups as $backup)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="font-mono text-sm text-gray-900">{{ $backup['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $backup['age'] }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 mt-2 sm:mt-0">
                                    <span class="text-sm text-gray-500">{{ $backup['size'] }}</span>
                                    <span class="text-sm text-gray-500">{{ $backup['date'] }}</span>

                                    <div class="flex gap-2">
                                        <a href="{{ route('backups.download', $backup['name']) }}"
                                            class="text-blue-600 hover:text-blue-900 px-2 py-1 rounded hover:bg-blue-50 transition"
                                            title="Descargar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('backups.delete', $backup['name']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50 transition"
                                                title="Eliminar"
                                                onclick="return confirm('¿Eliminar este backup permanentemente?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    No hay backups disponibles
                </div>
            @endif
        </div>
    </div>
@endsection
