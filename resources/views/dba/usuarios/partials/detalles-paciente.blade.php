<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-xl font-semibold mb-4">Datos del Paciente</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Información Básica Paciente -->
        <div class="space-y-2">
            <p><span class="font-medium text-gray-600">Tipo de Sangre:</span> {{ $paciente->tipo_sangre ?? 'N/A' }}</p>
            <p><span class="font-medium text-gray-600">Seguro Médico:</span> {{ $paciente->seguro_medico ?? 'N/A' }}</p>
            <p><span class="font-medium text-gray-600">Ocupación:</span> {{ $paciente->ocupacion ?? 'N/A' }}</p>
        </div>

        <!-- Contactos de Emergencia -->
        <div class="space-y-2">
            <p><span class="font-medium text-gray-600">Contacto Emergencia:</span> {{ $paciente->contacto_emergencia }}</p>
            <p><span class="font-medium text-gray-600">Teléfono Emergencia:</span> {{ $paciente->telefono_emergencia }}</p>
        </div>

        <!-- Historial Médico -->
        <div class="md:col-span-2">
            <h3 class="text-lg font-semibold mb-2">Historial Médico</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-lg">
                <div class="space-y-2">
                    <p><span class="font-medium text-gray-600">Enfermedades Crónicas:</span> {{ $paciente->historial->enfermedades_cronicas ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Alergias:</span> {{ $paciente->historial->alergias ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Cirugías Previas:</span> {{ $paciente->historial->cirugias ?? 'N/A' }}</p>
                </div>
                <div class="space-y-2">
                    <p><span class="font-medium text-gray-600">Medicamentos:</span> {{ $paciente->historial->medicamentos ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Antecedentes Familiares:</span> {{ $paciente->historial->antecedentes_familiares ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Otras Condiciones:</span> {{ $paciente->historial->otras_condiciones ?? 'N/A' }}</p>
                </div>
                @if($paciente->historial->observaciones ?? false)
                    <div class="md:col-span-2">
                        <p><span class="font-medium text-gray-600">Observaciones:</span></p>
                        <p class="whitespace-pre-line bg-white p-2 rounded">{{ $paciente->historial->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>