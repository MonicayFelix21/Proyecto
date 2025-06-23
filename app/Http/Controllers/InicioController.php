<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;
use App\Models\Artista;
use App\Models\Album;

class InicioController extends Controller
{
    /**
     * Muestra la página de inicio con canciones, artistas y álbumes populares.
     */
    public function index()
    {

        $canciones = Cancion::take(12)->get(); 
        $artistas = Artista::take(8)->get();
         $albumes    = Album::popular()->get();

        return view('inicio', compact('canciones', 'artistas', 'albumes'));
    }
}
