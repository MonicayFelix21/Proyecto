<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cancion;

class CancionController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->get('q');

        $canciones = Cancion::where('titulo', 'LIKE', "%{$query}%")
            ->orWhere('artista', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json($canciones);
    }
}
