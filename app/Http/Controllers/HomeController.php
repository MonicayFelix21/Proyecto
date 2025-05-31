<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;
use App\Models\Artista;

class HomeController extends Controller
{
    public function index()
    {
        $canciones = Cancion::take(12)->get(); // <--- Esta línea falta
        $artistas = Artista::take(8)->get();

        return view('inicio', compact('canciones', 'artistas'));
    }
}
