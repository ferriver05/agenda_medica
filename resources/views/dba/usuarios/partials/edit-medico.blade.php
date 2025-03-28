<h2 class="text-xl font-semibold mt-6 mb-4">Datos Médicos</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Número de licencia -->
    <div class="mb-4">
        <label for="numero_licencia" class="block text-sm font-medium text-gray-700">Número de Licencia</label>
        <input type="text" name="numero_licencia" id="numero_licencia" 
               value="{{ old('numero_licencia', $medico->numero_licencia) }}"
               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        @error('numero_licencia')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Número de sala -->
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

<!-- Especialidades -->
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

<!-- Horario de atención (disponibilidades) -->
<div class="mt-6" id="disponibilidades-container">
    <h3 class="text-lg font-semibold mb-2">Horario de Atención</h3>
    
    @foreach(old('disponibilidades', $medico->disponibilidades) as $index => $disponibilidad)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 disponibilidad-item">
            <div>
                <label class="block text-sm font-medium text-gray-700">Día</label>
                <select name="disponibilidades[{{ $index }}][dia_semana]" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @foreach($diasSemana as $key => $dia)
                        <option value="{{ $key }}" 
                            {{ (is_array($disponibilidad) ? $disponibilidad['dia_semana'] : $disponibilidad->dia_semana) == $key ? 'selected' : '' }}>
                            {{ $dia }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                <input type="time" name="disponibilidades[{{ $index }}][hora_inicio]" 
                       value="{{ is_array($disponibilidad) ? $disponibilidad['hora_inicio'] : $disponibilidad->hora_inicio->format('H:i') }}" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora fin</label>
                <input type="time" name="disponibilidades[{{ $index }}][hora_fin]" 
                       value="{{ is_array($disponibilidad) ? $disponibilidad['hora_fin'] : $disponibilidad->hora_fin->format('H:i') }}" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="button" class="remove-disponibilidad px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Eliminar
                </button>
            </div>
        </div>
    @endforeach
    
    <button type="button" id="add-disponibilidad"
            class="mt-4 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
        Agregar Horario
    </button>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('disponibilidades-container');
    const addButton = document.getElementById('add-disponibilidad');
    let index = {{ count(old('disponibilidades', $medico->disponibilidades)) }};
    
    addButton.addEventListener('click', function() {
        const template = `
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 disponibilidad-item">
            <div>
                <label class="block text-sm font-medium text-gray-700">Día</label>
                <select name="disponibilidades[${index}][dia_semana]" 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @foreach($diasSemana as $key => $dia)
                        <option value="{{ $key }}">{{ $dia }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                <input type="time" name="disponibilidades[${index}][hora_inicio]" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Hora fin</label>
                <input type="time" name="disponibilidades[${index}][hora_fin]" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="button" class="remove-disponibilidad px-3 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Eliminar
                </button>
            </div>
        </div>
        `;
        
        container.insertAdjacentHTML('beforeend', template);
        index++;
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-disponibilidad')) {
            e.target.closest('.disponibilidad-item').remove();
        }
    });
});
</script>
@endpush