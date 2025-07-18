<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artista;

class ArtistaController extends Controller
{
    public function index()
    {
        $artistas = Artista::paginate(12); // o ->get() si no quieres paginación
        return view('artistas.index', compact('artistas'));
    }
}
