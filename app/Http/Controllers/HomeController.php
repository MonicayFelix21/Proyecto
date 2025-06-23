<?php
namespace App\Http\Controllers;

use App\Models\Cancion;
use App\Models\Artista;
use App\Models\Album;

class HomeController extends Controller
{
    public function index()
    {
        $canciones = Cancion::take(12)->get(); 
        $artistas = Artista::take(8)->get();
        $albumes = Album::popular()->get();

        return view('home', compact('canciones', 'artistas', 'albumes'));
    }
}
