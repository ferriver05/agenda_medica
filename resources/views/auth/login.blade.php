<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediSync</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
</head>

<body class="bg-gray-300 h-screen flex items-center justify-center">

    <div class="flex max-w-4xl w-full bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- IMG DOC -->
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('{{ asset('img/medico.jpg') }}');">
        </div>

        <!-- FORM -->
        <div class="w-1/2 p-8">
            <h2 class="text-2xl font-semibold text-center mb-6">Iniciar sesión</h2>

            @if (session('status'))
                <p class="text-green-500 text-sm text-center mb-4">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input type="email" name="email" id="email"
                        class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password"
                        class="mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="w-full py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition duration-300">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
