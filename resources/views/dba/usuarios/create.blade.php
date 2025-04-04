@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 max-w-7xl">
        <h1 class="text-2xl font-bold mb-6">Crear Nuevo Usuario</h1>

        <form action="{{ route('dba.usuarios.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <h2 class="text-xl font-semibold mb-4">Datos Generales</h2>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Errores en el formulario</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input required type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="dni" class="block text-sm font-medium text-gray-700">DNI</label>
                    <input required type="text" name="dni" id="dni" value="{{ old('dni') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('dni')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de
                        Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
                        value="{{ old('fecha_nacimiento') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_nacimiento')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('telefono')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar</option>
                        <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                        <option value="otro" {{ old('genero') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('genero')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo</label>
                    <input required type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar
                        Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('direccion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <h2 class="text-xl font-semibold mt-6 mb-4">Tipo de Usuario</h2>
            <div class="mb-4">
                <label for="rol" class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="rol" id="rol"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccionar</option>
                    <option value="Paciente" {{ old('rol') == 'Paciente' ? 'selected' : '' }}>Paciente</option>
                    <option value="Medico" {{ old('rol') == 'Medico' ? 'selected' : '' }}>Médico</option>
                </select>
                @error('rol')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="camposPaciente" class="hidden">
                <h2 class="text-xl font-semibold mt-6 mb-4">Datos del Paciente</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="tipo_sangre" class="block text-sm font-medium text-gray-700">Tipo de Sangre</label>
                        <select name="tipo_sangre" id="tipo_sangre"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar</option>
                            <option value="A+" {{ old('tipo_sangre') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('tipo_sangre') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('tipo_sangre') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('tipo_sangre') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ old('tipo_sangre') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('tipo_sangre') == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ old('tipo_sangre') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('tipo_sangre') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                        @error('tipo_sangre')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="seguro_medico" class="block text-sm font-medium text-gray-700">Seguro Médico</label>
                        <input type="text" name="seguro_medico" id="seguro_medico"
                            value="{{ old('seguro_medico') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('seguro_medico')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ocupacion" class="block text-sm font-medium text-gray-700">Ocupación</label>
                        <input type="text" name="ocupacion" id="ocupacion" value="{{ old('ocupacion') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('ocupacion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="contacto_emergencia" class="block text-sm font-medium text-gray-700">Contacto de
                            Emergencia</label>
                        <input type="text" name="contacto_emergencia" id="contacto_emergencia"
                            value="{{ old('contacto_emergencia') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('contacto_emergencia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="telefono_emergencia" class="block text-sm font-medium text-gray-700">Teléfono de
                            Emergencia</label>
                        <input type="text" name="telefono_emergencia" id="telefono_emergencia"
                            value="{{ old('telefono_emergencia') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('telefono_emergencia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="enfermedades_cronicas" class="block text-sm font-medium text-gray-700">Enfermedades
                            Crónicas</label>
                        <input type="text" name="enfermedades_cronicas" id="enfermedades_cronicas"
                            value="{{ old('enfermedades_cronicas') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('enfermedades_cronicas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="alergias" class="block text-sm font-medium text-gray-700">Alergias</label>
                        <input type="text" name="alergias" id="alergias" value="{{ old('alergias') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('alergias')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="medicamentos" class="block text-sm font-medium text-gray-700">Medicamentos</label>
                        <input type="text" name="medicamentos" id="medicamentos" value="{{ old('medicamentos') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('medicamentos')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="antecedentes_familiares" class="block text-sm font-medium text-gray-700">Antecedentes
                            Familiares</label>
                        <input type="text" name="antecedentes_familiares" id="antecedentes_familiares"
                            value="{{ old('antecedentes_familiares') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('antecedentes_familiares')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="otras_condiciones" class="block text-sm font-medium text-gray-700">Otras
                            Condiciones</label>
                        <textarea name="otras_condiciones" id="otras_condiciones"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('otras_condiciones') }}</textarea>
                        @error('otras_condiciones')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea name="observaciones" id="observaciones"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('observaciones') }}</textarea>
                        @error('observaciones')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div id="camposMedico" class="hidden">
                <h2 class="text-xl font-semibold mt-6 mb-4">Datos del Médico</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label for="numero_licencia" class="block text-sm font-medium text-gray-700">Número de
                            Licencia</label>
                        <input type="text" name="numero_licencia" id="numero_licencia"
                            value="{{ old('numero_licencia') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('numero_licencia')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="numero_sala" class="block text-sm font-medium text-gray-700">Número de Sala</label>
                        <input type="text" name="numero_sala" id="numero_sala" value="{{ old('numero_sala') }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        @error('numero_sala')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="especialidades" class="block text-sm font-medium text-gray-700">Especialidades</label>
                        <select name="especialidades[]" id="especialidades" multiple
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}">{{ $especialidad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('especialidades')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-center">
                    <div class="w-full md:w-1/2">
                        <div id="disponibilidades" class="mb-4">
                            <h2 class="text-xl font-semibold mt-6 mb-4">Disponibilidades</h2>
                            <div class="disponibilidad mb-4">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label for="dia_semana" class="block text-sm font-medium text-gray-700">Día de la semana</label>
                                        <select name="disponibilidades[0][dia_semana]"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="0">Domingo</option>
                                            <option value="1">Lunes</option>
                                            <option value="2">Martes</option>
                                            <option value="3">Miércoles</option>
                                            <option value="4">Jueves</option>
                                            <option value="5">Viernes</option>
                                            <option value="6">Sábado</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="hora_inicio" class="block text-sm font-medium text-gray-700">Hora de inicio</label>
                                        <input type="time" name="disponibilidades[0][hora_inicio]" step="1800"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="hora_fin" class="block text-sm font-medium text-gray-700">Hora de fin</label>
                                        <input type="time" name="disponibilidades[0][hora_fin]" step="1800"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <button type="button"
                                    class="remove-disponibilidad mt-2 px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600">Eliminar</button>
                            </div>
                        </div>
                        <button type="button" id="add-disponibilidad"
                            class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Agregar
                            Disponibilidad</button>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('dba.usuarios.resumen') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Regresar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Guardar
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rolSelect = document.getElementById('rol');
            const camposPaciente = document.getElementById('camposPaciente');
            const camposMedico = document.getElementById('camposMedico');

            function mostrarCampos() {
                if (rolSelect.value === 'Paciente') {
                    camposPaciente.classList.remove('hidden');
                    camposMedico.classList.add('hidden');
                } else if (rolSelect.value === 'Medico') {
                    camposMedico.classList.remove('hidden');
                    camposPaciente.classList.add('hidden');
                } else {
                    camposPaciente.classList.add('hidden');
                    camposMedico.classList.add('hidden');
                }
            }

            rolSelect.addEventListener('change', mostrarCampos);
            mostrarCampos();

            const disponibilidades = document.getElementById('disponibilidades');
            const addButton = document.getElementById('add-disponibilidad');

            const validateTimeFormat = (timeInput) => {
                if (!timeInput.value) return true;

                const minutes = timeInput.value.split(':')[1];
                const isValid = minutes === '00' || minutes === '30';

                if (!isValid) {
                    timeInput.setCustomValidity('La hora debe terminar en :00 o :30');
                    timeInput.reportValidity();
                    return false;
                }

                timeInput.setCustomValidity('');
                return true;
            };

            if (addButton && disponibilidades) {
                const originalDisponibilidad = disponibilidades.querySelector('.disponibilidad');

                addButton.addEventListener('click', function() {
                    const newDisponibilidad = originalDisponibilidad.cloneNode(true);
                    const newIndex = document.querySelectorAll('.disponibilidad').length;

                    newDisponibilidad.querySelectorAll('input').forEach(input => input.value = '');
                    newDisponibilidad.querySelector('select').selectedIndex = 0;

                    newDisponibilidad.querySelectorAll('[name]').forEach(el => {
                        const oldName = el.getAttribute('name');
                        const newName = oldName.replace(/\[\d+\]/, `[${newIndex}]`);
                        el.setAttribute('name', newName);
                    });

                    disponibilidades.appendChild(newDisponibilidad);
                });

                disponibilidades.addEventListener('click', function(event) {
                    if (event.target.classList.contains('remove-disponibilidad')) {
                        if (document.querySelectorAll('.disponibilidad').length > 1) {
                            event.target.closest('.disponibilidad').remove();

                            document.querySelectorAll('.disponibilidad').forEach((disp, index) => {
                                disp.querySelectorAll('[name]').forEach(el => {
                                    const oldName = el.getAttribute('name');
                                    const newName = oldName.replace(/\[\d+\]/, `[${index}]`);
                                    el.setAttribute('name', newName);
                                });
                            });
                        }
                    }
                });
            }

            document.querySelector('form')?.addEventListener('submit', function(e) {
                let isValid = true;

                document.querySelectorAll('input[type="time"]').forEach(input => {
                    if (!validateTimeFormat(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor corrija los horarios. Todos deben terminar en :00 o :30');
                }
            });
        });
    </script>
@endsection
