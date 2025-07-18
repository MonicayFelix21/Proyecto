<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SpotifyAdvancedController extends Controller
{
    private function getSpotifyToken()
    {
        $user = Auth::user();
        if (!$user || !$user->spotify_token) {
            throw new \Exception('Token de Spotify no disponible');
        }
        return $user->spotify_token;
    }

    private function makeSpotifyRequest($endpoint, $method = 'GET', $data = null)
    {
        $token = $this->getSpotifyToken();
        
        $request = Http::withToken($token);
        
        switch ($method) {
            case 'POST':
                $response = $request->post("https://api.spotify.com/v1{$endpoint}", $data);
                break;
            case 'PUT':
                $response = $request->put("https://api.spotify.com/v1{$endpoint}", $data);
                break;
            case 'DELETE':
                $response = $request->delete("https://api.spotify.com/v1{$endpoint}", $data);
                break;
            default:
                $response = $request->get("https://api.spotify.com/v1{$endpoint}");
        }

        if (!$response->successful()) {
            throw new \Exception("Error en API de Spotify: " . $response->status());
        }

        return $response->json();
    }

    /**
     * 🎧 Obtener historial de reproducción reciente
     */
    public function getRecentlyPlayed(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $data = $this->makeSpotifyRequest("/me/player/recently-played?limit={$limit}");
            
            $tracks = collect($data['items'])->map(function ($item) {
                $track = $item['track'];
                return [
                    'id' => $track['id'],
                    'name' => $track['name'],
                    'artists' => collect($track['artists'])->pluck('name')->join(', '),
                    'album' => $track['album']['name'],
                    'image' => $track['album']['images'][0]['url'] ?? null,
                    'uri' => $track['uri'],
                    'played_at' => $item['played_at'],
                    'duration_ms' => $track['duration_ms']
                ];
            });

            return response()->json([
                'success' => true,
                'tracks' => $tracks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🎯 Obtener recomendaciones personalizadas
     */
    public function getRecommendations(Request $request)
    {
        try {
            // Obtener parámetros
            $limit = $request->get('limit', 20);
            $seedTracks = $request->get('seed_tracks', '');
            $seedArtists = $request->get('seed_artists', '');
            $seedGenres = $request->get('seed_genres', 'pop,rock');
            
            $params = [
                'limit' => $limit,
                'seed_genres' => $seedGenres
            ];
            
            if ($seedTracks) $params['seed_tracks'] = $seedTracks;
            if ($seedArtists) $params['seed_artists'] = $seedArtists;
            
            $queryString = http_build_query($params);
            $data = $this->makeSpotifyRequest("/recommendations?{$queryString}");
            
            $tracks = collect($data['tracks'])->map(function ($track) {
                return [
                    'id' => $track['id'],
                    'name' => $track['name'],
                    'artists' => collect($track['artists'])->pluck('name')->join(', '),
                    'album' => $track['album']['name'],
                    'image' => $track['album']['images'][0]['url'] ?? null,
                    'uri' => $track['uri'],
                    'duration_ms' => $track['duration_ms'],
                    'popularity' => $track['popularity']
                ];
            });

            return response()->json([
                'success' => true,
                'tracks' => $tracks,
                'seeds' => $data['seeds'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 💝 Obtener canciones guardadas (favoritas)
     */
    public function getSavedTracks(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $offset = $request->get('offset', 0);
            
            $data = $this->makeSpotifyRequest("/me/tracks?limit={$limit}&offset={$offset}");
            
            $tracks = collect($data['items'])->map(function ($item) {
                $track = $item['track'];
                return [
                    'id' => $track['id'],
                    'name' => $track['name'],
                    'artists' => collect($track['artists'])->pluck('name')->join(', '),
                    'album' => $track['album']['name'],
                    'image' => $track['album']['images'][0]['url'] ?? null,
                    'uri' => $track['uri'],
                    'duration_ms' => $track['duration_ms'],
                    'added_at' => $item['added_at']
                ];
            });

            return response()->json([
                'success' => true,
                'tracks' => $tracks,
                'total' => $data['total'],
                'has_more' => ($offset + $limit) < $data['total']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📊 Obtener top artistas y canciones del usuario
     */
    public function getTopItems(Request $request, $type)
    {
        try {
            if (!in_array($type, ['artists', 'tracks'])) {
                return response()->json(['error' => 'Tipo inválido'], 400);
            }

            $timeRange = $request->get('time_range', 'medium_term'); // short_term, medium_term, long_term
            $limit = $request->get('limit', 20);
            
            $data = $this->makeSpotifyRequest("/me/top/{$type}?time_range={$timeRange}&limit={$limit}");
            
            if ($type === 'tracks') {
                $items = collect($data['items'])->map(function ($track) {
                    return [
                        'id' => $track['id'],
                        'name' => $track['name'],
                        'artists' => collect($track['artists'])->pluck('name')->join(', '),
                        'album' => $track['album']['name'],
                        'image' => $track['album']['images'][0]['url'] ?? null,
                        'uri' => $track['uri'],
                        'duration_ms' => $track['duration_ms'],
                        'popularity' => $track['popularity']
                    ];
                });
            } else {
                $items = collect($data['items'])->map(function ($artist) {
                    return [
                        'id' => $artist['id'],
                        'name' => $artist['name'],
                        'genres' => $artist['genres'],
                        'image' => $artist['images'][0]['url'] ?? null,
                        'uri' => $artist['uri'],
                        'popularity' => $artist['popularity'],
                        'followers' => $artist['followers']['total']
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'items' => $items,
                'type' => $type,
                'time_range' => $timeRange
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 📋 Obtener playlists del usuario
     */
    public function getUserPlaylists(Request $request)
    {
        try {
            $limit = $request->get('limit', 20);
            $offset = $request->get('offset', 0);
            
            $data = $this->makeSpotifyRequest("/me/playlists?limit={$limit}&offset={$offset}");
            
            $playlists = collect($data['items'])->map(function ($playlist) {
                return [
                    'id' => $playlist['id'],
                    'name' => $playlist['name'],
                    'description' => $playlist['description'],
                    'image' => $playlist['images'][0]['url'] ?? null,
                    'uri' => $playlist['uri'],
                    'tracks_total' => $playlist['tracks']['total'],
                    'owner' => $playlist['owner']['display_name'],
                    'public' => $playlist['public'],
                    'collaborative' => $playlist['collaborative']
                ];
            });

            return response()->json([
                'success' => true,
                'playlists' => $playlists,
                'total' => $data['total'],
                'has_more' => ($offset + $limit) < $data['total']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ⭐ Guardar/quitar canción de favoritos
     */
    public function toggleSavedTrack(Request $request)
    {
        try {
            $trackId = $request->get('track_id');
            if (!$trackId) {
                return response()->json(['error' => 'track_id requerido'], 400);
            }

            // Verificar si ya está guardada
            $checkData = $this->makeSpotifyRequest("/me/tracks/contains?ids={$trackId}");
            $isSaved = $checkData[0] ?? false;

            if ($isSaved) {
                // Quitar de favoritos
                $this->makeSpotifyRequest("/me/tracks?ids={$trackId}", 'DELETE');
                $action = 'removed';
            } else {
                // Agregar a favoritos
                $this->makeSpotifyRequest("/me/tracks?ids={$trackId}", 'PUT');
                $action = 'added';
            }

            return response()->json([
                'success' => true,
                'action' => $action,
                'is_saved' => !$isSaved
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🎮 Control avanzado de reproducción
     */
    public function setRepeatMode(Request $request)
    {
        try {
            $state = $request->get('state', 'off'); // off, track, context
            $this->makeSpotifyRequest("/me/player/repeat?state={$state}", 'PUT');
            
            return response()->json(['success' => true, 'repeat_state' => $state]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function setShuffle(Request $request)
    {
        try {
            $state = $request->get('state', 'false'); // true, false
            $this->makeSpotifyRequest("/me/player/shuffle?state={$state}", 'PUT');
            
            return response()->json(['success' => true, 'shuffle_state' => $state === 'true']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * 🎵 Obtener cola de reproducción
     */
    public function getQueue()
    {
        try {
            $data = $this->makeSpotifyRequest('/me/player/queue');
            
            $queue = [
                'currently_playing' => null,
                'queue' => []
            ];

            if (isset($data['currently_playing'])) {
                $track = $data['currently_playing'];
                $queue['currently_playing'] = [
                    'id' => $track['id'],
                    'name' => $track['name'],
                    'artists' => collect($track['artists'])->pluck('name')->join(', '),
                    'uri' => $track['uri']
                ];
            }

            if (isset($data['queue'])) {
                $queue['queue'] = collect($data['queue'])->map(function ($track) {
                    return [
                        'id' => $track['id'],
                        'name' => $track['name'],
                        'artists' => collect($track['artists'])->pluck('name')->join(', '),
                        'uri' => $track['uri'],
                        'duration_ms' => $track['duration_ms']
                    ];
                });
            }

            return response()->json(['success' => true, 'queue' => $queue]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
