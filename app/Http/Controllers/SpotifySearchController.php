<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpotifyClient;

class SpotifySearchController extends Controller
{
    protected SpotifyClient $spotify;

    public function __construct(SpotifyClient $spotify)
    {
        $this->spotify = $spotify;
    }

    /**
     * Devuelve JSON con tracks, artistas y álbumes que coinciden.
     */
    public function __invoke(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 2) {
            return response()->json([], 200);
        }

        $data = $this->spotify->search($q, 'track,artist,album', 5);

        // Extraemos solo las pistas:
        $tracks = $data['tracks']['items'] ?? [];

        // Mapeamos lo mínimo que necesitamos:
        $results = collect($tracks)->map(fn($t) => [
            'id'       => $t['id'],
            'titulo'   => $t['name'],
            'artista'  => implode(', ', array_column($t['artists'], 'name')),
            'imagen'   => $t['album']['images'][0]['url'] ?? null,
        ])->toArray();

        return response()->json($results);
    }

    /**
     * Devuelve las categorías de Spotify (para explorar)
     */
    public function categorias(Request $request)
    {
        $data = $this->spotify->getCategories();
        return response()->json($data);
    }
}
