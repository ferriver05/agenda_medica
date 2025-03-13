<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    protected $table = 'salas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    // Relacion 1 a 1 con Medico
    public function medico()
    {
        return $this->hasOne(Medico::class);
    }
}
