<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artista extends Model
{
    use HasFactory;

    // Laravel asumirá que el modelo usa la tabla 'artistas'
    // No necesitas definir $table si sigues esta convención

    // Campos que puedes asignar masivamente (ajusta según tu estructura)
    protected $fillable = [
        'nombre',
        'imagen'
    ];
}
