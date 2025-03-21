@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-xl">
        <form action="{{ route('medico.procesar.reserva') }}" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 my-4">
            @csrf

            <h1 class="text-2xl font-bold mb-4">Reservar Cita</h1>

            <!-- Campo de DNI -->
            <div class="mb-4">
                <label for="dni" class="block text-gray-700 text-sm font-bold mb-2">DNI del Paciente</label>
                <input type="text" name="dni" id="dni"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <!-- Nombre del paciente (no editable) -->
            <div class="mb-4">
                <label for="nombre_paciente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Paciente</label>
                <input type="text" id="nombre_paciente"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline"
                    readonly>
            </div>

            <!-- Selección de especialidad -->
            <div class="mb-4">
                <label for="especialidad" class="block text-gray-700 text-sm font-bold mb-2">Especialidad</label>
                <select name="especialidad_id" id="especialidad"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Seleccione una especialidad</option>
                    @foreach ($especialidades as $especialidad)
                        <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Selección de fecha -->
            <div class="mb-4">
                <label for="fecha" class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                <input type="date" name="fecha" id="fecha"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <!-- Selección de hora -->
            <div class="mb-4">
                <label for="hora" class="block text-gray-700 text-sm font-bold mb-2">Hora</label>
                <select name="hora_inicio" id="hora"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    disabled required>
                    <option value="">Seleccione una hora</option>
                </select>
            </div>

            <!-- Razón de la cita -->
            <div class="mb-4">
                <label for="razon_cita" class="block text-gray-700 text-sm font-bold mb-2">Razón de la cita</label>
                <textarea name="razon_cita" id="razon_cita"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="4" placeholder="Describa la razón de la cita" required></textarea>
            </div>

            <div class="flex items-center justify-start space-x-4">
                <!-- Botón Cancelar -->
                <button type="button" onclick="window.history.back()"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Regresar
                </button>

                <!-- Botón Limpiar -->
                <button type="reset" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Limpiar
                </button>

                <!-- Botón Confirmar Reserva -->
                <button type="submit" onclick="return confirm('¿Estás seguro de que quieres reservar esta cita?')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Reservar
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Buscar paciente por DNI
            $('#dni').on('input', function() {
                var dni = $(this).val();

                console.log('DNI ingresado:', dni); // Depuración

                if (dni.length >= 10) { // Suponiendo que el DNI tiene 8 dígitos
                    $.get('/buscar-paciente-por-dni/' + dni, function(data) {
                        console.log('Respuesta del servidor:', data); // Depuración

                        if (data) {
                            $('#nombre_paciente').val(data.name);
                        } else {
                            $('#nombre_paciente').val('');
                            console.log(
                            'No se encontró ningún paciente con ese DNI.'); // Depuración
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus,
                        errorThrown); // Depuración
                    });
                } else {
                    $('#nombre_paciente').val('');
                    console.log('DNI incompleto.'); // Depuración
                }
            });

            // Habilitar selección de hora al seleccionar fecha
            $('#fecha').change(function() {
                var fecha = $(this).val();
                var dni = $('#dni').val();

                console.log('Fecha seleccionada:', fecha); // Depuración
                console.log('DNI del paciente:', dni); // Depuración

                if (fecha && dni) {
                    $.get('/horas-disponibles-medico/' + dni + '/' + fecha, function(data) {
                        console.log('Horas disponibles:', data); // Depuración

                        $('#hora').empty().append('<option value="">Seleccione una hora</option>');
                        $.each(data, function(index, hora) {
                            $('#hora').append('<option value="' + hora + '">' + hora +
                                '</option>');
                        });
                        $('#hora').prop('disabled', false);
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus,
                        errorThrown); // Depuración
                    });
                } else {
                    $('#hora').empty().prop('disabled', true);
                    console.log('Falta DNI o fecha.'); // Depuración
                }
            });
        });
    </script>
@endsection
