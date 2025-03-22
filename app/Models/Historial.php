<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;

    protected $table = 'historiales';

    protected $fillable = [
        'paciente_id',
        'enfermedades_cronicas',
        'alergias',
        'cirugias',
        'medicamentos',
        'antecedentes_familiares',
        'otras_condiciones',
        'observaciones',
    ];

    // Relacion 1 a 1 con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}
