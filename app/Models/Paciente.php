<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;

    protected $table = 'pacientes';

    protected $fillable = [
        'user_id',
        'tipo_sangre',
        'seguro_medico',
        'ocupacion',
        'contacto_emergencia',
        'telefono_emergencia'
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

    // Relacion 1 a 1 con Historial
    public function historial()
    {
        return $this->hasOne(Historial::class);
    }

    // Relacion 1 a M con Citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function ultimaCita()
    {
        return $this->hasOne(Cita::class)->latestOfMany();
    }
}
