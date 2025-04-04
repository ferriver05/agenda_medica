<h2 class="text-xl font-semibold mt-6 mb-4">Datos Médicos</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="numero_licencia" class="block text-sm font-medium text-gray-700">Número de Licencia</label>
        <input type="text" name="numero_licencia" id="numero_licencia" 
               value="{{ old('numero_licencia', $medico->numero_licencia) }}"
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        @error('numero_licencia')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="numero_sala" class="block text-sm font-medium text-gray-700">Número de Sala</label>
        <input type="text" name="numero_sala" id="numero_sala" 
               value="{{ old('numero_sala', $medico->numero_sala) }}"
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        @error('numero_sala')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-4">
    <label for="especialidades" class="block text-sm font-medium text-gray-700">Especialidades</label>
    <select name="especialidades[]" id="especialidades" multiple
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        @foreach($especialidades as $especialidad)
            <option value="{{ $especialidad->id }}"
                {{ in_array($especialidad->id, old('especialidades', $medico->especialidades->pluck('id')->toArray())) ? 'selected' : '' }}>
                {{ $especialidad->nombre }}
            </option>
        @endforeach
    </select>
    @error('especialidades')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex justify-center mt-6">
    <div class="w-full md:w-1/2">
        <div id="disponibilidades" class="mb-4">
            <h2 class="text-xl font-semibold mb-4 text-center">Disponibilidades</h2>
            
            @php
                $disponibilidades = old('disponibilidades', $medico->disponibilidades);
                if(empty($disponibilidades)) {
                    $disponibilidades = [['dia_semana' => '1', 'hora_inicio' => '', 'hora_fin' => '']];
                }
            @endphp
            
            @foreach($disponibilidades as $index => $disp)
            <div class="disponibilidad mb-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Día</label>
                        <select name="disponibilidades[{{ $index }}][dia_semana]"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            @foreach($diasSemana as $key => $dia)
                                <option value="{{ $key }}" 
                                    {{ (is_array($disp) ? $disp['dia_semana'] : $disp->dia_semana) == $key ? 'selected' : '' }}>
                                    {{ $dia }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                        <input type="time" step="1800" name="disponibilidades[{{ $index }}][hora_inicio]"
                               value="{{ is_array($disp) ? $disp['hora_inicio'] : $disp->hora_inicio->format('H:i') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora fin</label>
                        <input type="time" step="1800" name="disponibilidades[{{ $index }}][hora_fin]"
                               value="{{ is_array($disp) ? $disp['hora_fin'] : $disp->hora_fin->format('H:i') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <button type="button"
                        class="remove-disponibilidad mt-2 px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Eliminar
                </button>
            </div>
            @endforeach
        </div>
        
        <button type="button" id="add-disponibilidad"
                class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
            Agregar Disponibilidad
        </button>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        const setupTimeValidation = (container) => {
            container.querySelectorAll('input[type="time"]').forEach(input => {
                input.addEventListener('change', function() {
                    validateTimeFormat(this);
                });
            });
        };
    
        if (addButton && disponibilidades) {
            const originalDisponibilidad = disponibilidades.querySelector('.disponibilidad');
            
            setupTimeValidation(disponibilidades);
    
            addButton.addEventListener('click', function() {
                const newDisponibilidad = originalDisponibilidad.cloneNode(true);

                newDisponibilidad.querySelectorAll('input').forEach(input => input.value = '');
                newDisponibilidad.querySelector('select').selectedIndex = 0;

                const newIndex = document.querySelectorAll('.disponibilidad').length;
                newDisponibilidad.querySelectorAll('[name]').forEach(el => {
                    const name = el.getAttribute('name');
                    el.setAttribute('name', name.replace(/\[\d+\]/, `[${newIndex}]`));
                });

                disponibilidades.appendChild(newDisponibilidad);

                setupTimeValidation(newDisponibilidad);
            });
    
            disponibilidades.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-disponibilidad')) {
                    if (document.querySelectorAll('.disponibilidad').length > 1) {
                        event.target.closest('.disponibilidad').remove();

                        document.querySelectorAll('.disponibilidad').forEach((disp, index) => {
                            disp.querySelectorAll('[name]').forEach(el => {
                                const name = el.getAttribute('name');
                                el.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
                            });
                        });
                    }
                }
            });

            document.querySelector('form').addEventListener('submit', function(e) {
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
        }
    });
    </script>
@endsection