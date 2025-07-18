<?php // app/Http/Controllers/InicioController.php

namespace App\Http\Controllers;

use App\Services\SpotifyClient;
use App\Models\Cancion;
use App\Models\Artista;
use App\Models\Album;

class InicioController extends Controller
{
    protected SpotifyClient $spotify;

    public function __construct(SpotifyClient $spotify)
    {
        $this->spotify = $spotify;
    }

    public function index()
    {
        // 1) Datos locales
        $canciones = Cancion::take(12)->get();
        $artistas  = Artista::take(8)->get();
        $albumes   = Album::popular()->get();

        // 2) Datos públicos de Spotify (nuevos lanzamientos)
        try {
            $newReleases = $this->spotify->nuevosLanzamientosPublic(12);

            $albumes = collect($newReleases)->map(function ($a) {
                return (object)[
                    'titulo'  => $a['name'],
                    'artista' => implode(', ', array_column($a['artists'], 'name')),
                    'imagen'  => $a['images'][0]['url'] ?? null,
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Error Spotify public en inicio: '.$e->getMessage());
            // si falla, seguimos con datos locales
        }

        return view('inicio', compact('canciones','artistas','albumes'));
    }
}
