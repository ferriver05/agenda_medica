<h2 class="text-xl font-semibold mt-6 mb-4">Datos del Paciente</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="tipo_sangre" class="block text-sm font-medium text-gray-700">Tipo de Sangre</label>
        <select name="tipo_sangre" id="tipo_sangre"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="">Seleccionar</option>
            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $tipo)
                <option value="{{ $tipo }}"
                    {{ old('tipo_sangre', $paciente->tipo_sangre) == $tipo ? 'selected' : '' }}>
                    {{ $tipo }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="seguro_medico" class="block text-sm font-medium text-gray-700">Seguro Médico</label>
        <input type="text" name="seguro_medico" id="seguro_medico"
            value="{{ old('seguro_medico', $paciente->seguro_medico) }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="mb-4">
        <label for="ocupacion" class="block text-sm font-medium text-gray-700">Ocupación</label>
        <input type="text" name="ocupacion" id="ocupacion" value="{{ old('ocupacion', $paciente->ocupacion) }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="mb-4">
        <label for="contacto_emergencia" class="block text-sm font-medium text-gray-700">Contacto de Emergencia</label>
        <input type="text" name="contacto_emergencia" id="contacto_emergencia"
            value="{{ old('contacto_emergencia', $paciente->contacto_emergencia) }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>

    <div class="mb-4">
        <label for="telefono_emergencia" class="block text-sm font-medium text-gray-700">Teléfono de Emergencia</label>
        <input type="text" name="telefono_emergencia" id="telefono_emergencia"
            value="{{ old('telefono_emergencia', $paciente->telefono_emergencia) }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
    </div>
</div>

<h3 class="text-lg font-semibold mt-6 mb-2">Historial Médico</h3>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="mb-4">
        <label for="enfermedades_cronicas" class="block text-sm font-medium text-gray-700">Enfermedades Crónicas</label>
        <textarea name="enfermedades_cronicas" id="enfermedades_cronicas"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('enfermedades_cronicas', $paciente->historial->enfermedades_cronicas) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="alergias" class="block text-sm font-medium text-gray-700">Alergias</label>
        <textarea name="alergias" id="alergias"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('alergias', $paciente->historial->alergias) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="cirugias" class="block text-sm font-medium text-gray-700">Cirugías Previas</label>
        <textarea name="cirugias" id="cirugias"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('cirugias', $paciente->historial->cirugias) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="medicamentos" class="block text-sm font-medium text-gray-700">Medicamentos</label>
        <textarea name="medicamentos" id="medicamentos"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('medicamentos', $paciente->historial->medicamentos) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="antecedentes_familiares" class="block text-sm font-medium text-gray-700">Antecedentes
            Familiares</label>
        <textarea name="antecedentes_familiares" id="antecedentes_familiares"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('antecedentes_familiares', $paciente->historial->antecedentes_familiares) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="otras_condiciones" class="block text-sm font-medium text-gray-700">Otras Condiciones</label>
        <textarea name="otras_condiciones" id="otras_condiciones"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('otras_condiciones', $paciente->historial->otras_condiciones ?? '') }}</textarea>
    </div>

    <div class="mb-4">
        <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
        <textarea name="observaciones" id="observaciones"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('observaciones', $paciente->historial->observaciones) }}</textarea>
    </div>
</div>
