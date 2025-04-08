<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidades';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relacion M a M con Medico
    public function medicos()
    {
        return $this->belongsToMany(Medico::class, 'especialidad_medico', 'especialidad_id', 'medico_id');
    }

    // Relacion 1 a M con Medico
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
