<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\SpotifyClient;
use App\Models\Cancion;
use App\Models\Artista;
use App\Models\Album;

class HomeController extends Controller
{
    protected SpotifyClient $spotify;

    public function __construct(SpotifyClient $spotify)
    {
        $this->middleware('auth');
        $this->spotify = $spotify;
    }

    public function index()
    {
        // Datos locales fallback
        $canciones = Cancion::take(12)->get();
        $artistas  = Artista::take(8)->get();
        $albumes   = Album::popular()->get();

        $user = Auth::user();
        if ($user->spotify_token) {
            try {
                $tracks = $this->spotify->topTracks(12);
                $arts   = $this->spotify->topArtists(8);
                $rels   = $this->spotify->nuevosLanzamientos(12);

                $canciones = collect($tracks)->map(fn($t) => (object)[
                    'titulo'    => $t['name'],
                    'artista'   => implode(', ', array_column($t['artists'], 'name')),
                    'imagen'    => $t['album']['images'][0]['url'] ?? null,
                    'audio'     => $t['preview_url'] ?? null,
                    'explicito' => $t['explicit'] ?? false,
                    'spotify_uri' => "spotify:track:{$t['id']}",
                    'spotify_id'  => $t['id'],
                ]);
                $artistas = collect($arts)->map(fn($a) => (object)[
                    'nombre' => $a['name'],
                    'imagen' => $a['images'][0]['url'] ?? null,
                ]);
                $albumes = collect($rels)->map(fn($a) => (object)[
                    'titulo'  => $a['name'],
                    'artista' => implode(', ', array_column($a['artists'], 'name')),
                    'imagen'  => $a['images'][0]['url'] ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error('Spotify API error: '.$e->getMessage());
            }
        }

        return view('home', compact('canciones','artistas','albumes'));
    }
}
