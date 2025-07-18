<?php

namespace App\Http\Controllers;

use App\Models\Album;

class AlbumController extends Controller
{
    public function index()
    {
        // Trae los más populares (o ajusta según tu scope)
        $albumes = Album::popular()->get();

        // Devuelve la vista (crea resources/views/albumes/index.blade.php)
        return view('albumes.index', compact('albumes'));
    }
}
