<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cancion;

class CancionApiController extends Controller
{
    // Obtener todas las canciones
    public function index()
    {
        return Cancion::all();
    }

    // Crear una nueva canción
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'artista' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'imagen' => 'nullable|string',
            'audio' => 'nullable|string',
            'explicito' => 'boolean',
        ]);

        $cancion = Cancion::create($validated);
        return response()->json($cancion, 201);
    }

    // Ver una canción específica
    public function show($id)
    {
        return Cancion::findOrFail($id);
    }

    // Actualizar una canción
    public function update(Request $request, $id)
    {
        $cancion = Cancion::findOrFail($id);
        $cancion->update($request->all());

        return response()->json($cancion);
    }

    // Eliminar una canción
    public function destroy($id)
    {
        $cancion = Cancion::findOrFail($id);
        $cancion->delete();

        return response()->json(null, 204);
    }
}
