<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'fecha',
        'hora_inicio',
        'estado',
        'notas',
        'imagen_prescripcion',
    ];

    // Relacion M a 1 con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relacion M a 1 con Medico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }
}
