<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Medico extends Model
{
    use HasFactory;

    protected $table = 'medicos';

    protected $fillable = [
        'user_id',
        'numero_licencia',
        'numero_sala'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // Relacion 1 a 1 con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacion M a M con Especialidad
    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'especialidades_medicos', 'medico_id', 'especialidad_id');
    }

    // Relacion 1 a M con Cita
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    // Relacion 1 a M con Disponibilidades
    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidad::class);
    }

    
}
