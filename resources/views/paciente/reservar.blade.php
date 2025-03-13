@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-xl">
        <form action="{{ route('procesar.reserva') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 my-4">
            @csrf

            <h1 class="text-2xl font-bold mb-4">Reservar Cita</h1>

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

            <!-- Selección de médico -->
            <div class="mb-4">
                <label for="medico" class="block text-gray-700 text-sm font-bold mb-2">Médico</label>
                <select name="medico_id" id="medico"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    disabled required>
                    <option value="">Seleccione un médico</option>
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

            <!-- Razon del paciente -->
            <div class="mb-4">
                <label for="razon_paciente" class="block text-gray-700 text-sm font-bold mb-2">Razón de la cita</label>
                <textarea name="razon_paciente" id="razon_paciente"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="4" placeholder="Describa la razón de su cita" required></textarea>
            </div>

            <div class="flex items-center justify-start space-x-4">
                <!-- Botón Cancelar -->
                <button type="button" onclick="window.history.back()" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Regresar
                </button>
            
                <!-- Botón Limpiar -->
                <button type="reset" 
                    class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
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
            // Habilitar selección de médico al elegir especialidad
            $('#especialidad').change(function() {
                var especialidadId = $(this).val();

                $('#medico')
                    .empty()
                    .append('<option value="">Seleccione un médico</option>')
                    .prop('disabled', true);

                $('#hora')
                    .empty()
                    .append('<option value="">Seleccione una hora</option>')
                    .prop('disabled', true);

                if (especialidadId) {
                    $.get('/medicos-por-especialidad/' + especialidadId, function(data) {
                        $.each(data, function(index, medico) {
                            $('#medico').append('<option value="' + medico.id + '">' +
                                medico.user.name + '</option>');
                        });
                        $('#medico').prop('disabled', false);
                    });
                }
            });

            // Habilitar selección de fecha al elegir médico
            $('#medico').change(function() {
                var medicoId = $(this).val();
                if (medicoId) {} else {
                    $('#hora').empty().prop('disabled', true); // Deshabilitar hora
                }
            });

            // Cargar horas disponibles al seleccionar fecha
            $('#fecha').change(function() {
                var medicoId = $('#medico').val();
                var fecha = $(this).val();
                if (medicoId && fecha) {
                    $.get('/horas-disponibles/' + medicoId + '/' + fecha, function(data) {
                        $('#hora').empty().append('<option value="">Seleccione una hora</option>');
                        $.each(data, function(index, hora) {
                            $('#hora').append('<option value="' + hora + '">' + hora +
                                '</option>');
                        });
                        $('#hora').prop('disabled', false); // Habilitar el select de horas
                    });
                } else {
                    $('#hora').empty().prop('disabled',
                        true); // Deshabilitar si no hay médico o fecha seleccionada
                }
            });
        });
    </script>
@endsection
