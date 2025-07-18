<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    // 1) Campos que puedes asignar masivamente
    protected $fillable = [
        'titulo',
        'imagen',
        'artista_id',
        // 'descripcion', 'lanzamiento', etc... según tu tabla
    ];

    // 2) Relación con Artista
    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }

    // 3) Scope para traer los más populares (ajusta 'reproducciones' por tu columna)
    public function scopePopular($query)
    {
        return $query
            ->orderBy('reproducciones', 'desc')
            ->take(10);
    }
}
