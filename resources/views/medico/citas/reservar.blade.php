@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-xl">
        <form action="{{ route('medico.procesar.reserva') }}" method="POST"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 my-4">
            @csrf

            <h1 class="text-2xl font-bold mb-4">Reservar Cita</h1>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <label for="dni" class="block text-gray-700 text-sm font-bold mb-2">DNI del Paciente</label>
                <input type="text" name="dni" id="dni" value="{{ $dni_precargado ?? old('dni') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required {{ $dni_precargado ? 'readonly' : '' }}>
            </div>

            <div class="mb-4">
                <label for="nombre_paciente" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Paciente</label>
                <input type="text" id="nombre_paciente"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-200 leading-tight focus:outline-none focus:shadow-outline"
                    readonly value="{{ old('nombre_paciente') }}">
            </div>

            <div class="mb-4">
                <label for="especialidad" class="block text-gray-700 text-sm font-bold mb-2">Especialidad</label>
                <select name="especialidad_id" id="especialidad"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Seleccione una especialidad</option>
                    @foreach ($especialidades as $especialidad)
                        <option value="{{ $especialidad->id }}"
                            {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                            {{ $especialidad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha" class="block text-gray-700 text-sm font-bold mb-2">Fecha</label>
                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
            </div>

            <div class="mb-4">
                <label for="hora" class="block text-gray-700 text-sm font-bold mb-2">Hora</label>
                <select name="hora_inicio" id="hora"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    {{ !old('hora_inicio') ? 'disabled' : '' }} required>
                    <option value="">Seleccione una hora</option>
                    @if (old('hora_inicio'))
                        <option value="{{ old('hora_inicio') }}" selected>{{ old('hora_inicio') }}</option>
                    @endif
                </select>
            </div>

            <div class="mb-4">
                <label for="razon_cita" class="block text-gray-700 text-sm font-bold mb-2">Razón de la cita</label>
                <textarea name="razon_cita" id="razon_cita"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    rows="4" placeholder="Describa la razón de la cita" required>{{ old('razon_cita') }}</textarea>
            </div>

            <div class="flex items-center justify-start space-x-4">
                <button type="button" onclick="window.history.back()"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Regresar
                </button>

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
            @if(isset($dni_precargado) && $dni_precargado)
                $('#dni').val('{{ $dni_precargado }}');
                buscarPaciente('{{ $dni_precargado }}');
            @endif

            @if(old('dni'))
                $('#dni').val('{{ old("dni") }}');
                buscarPaciente('{{ old("dni") }}');
            @endif

            $('#dni').on('input', function() {
                var dni = $(this).val();
                buscarPaciente(dni);
            });

            function buscarPaciente(dni) {
                if (dni.length >= 1) {
                    $.get('/buscar-paciente-por-dni/' + dni, function(data) {
                        if (data) {
                            $('#nombre_paciente').val(data.name);
                            if ($('#fecha').val()) {
                                cargarHorasDisponibles(dni, $('#fecha').val());
                            }
                        } else {
                            resetSelectHora();
                        }
                    }).fail(function() {
                        resetSelectHora();
                    });
                } else {
                    resetSelectHora();
                }
            }

            $('#fecha').change(function() {
                resetSelectHora();
                
                var dni = $('#dni').val();
                var fecha = $(this).val();
                
                if (dni && fecha) {
                    cargarHorasDisponibles(dni, fecha);
                }
            });

            function cargarHorasDisponibles(dni, fecha) {
                $.get('/horas-disponibles-medico/' + dni + '/' + fecha, function(data) {
                    $('#hora').empty().append('<option value="">Seleccione una hora</option>');
                    $.each(data, function(index, hora) {
                        $('#hora').append('<option value="' + hora + '">' + hora + '</option>');
                    });
                    $('#hora').prop('disabled', false);
                    
                    @if(old('hora_inicio'))
                        $('#hora').val('{{ old("hora_inicio") }}');
                    @endif
                }).fail(function() {
                    resetSelectHora();
                });
            }

            function resetSelectHora() {
                $('#hora')
                    .empty()
                    .append('<option value="">Seleccione una hora</option>')
                    .prop('disabled', true);
            }
        });
    </script>
@endsection