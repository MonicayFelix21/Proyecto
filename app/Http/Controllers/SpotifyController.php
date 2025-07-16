<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class SpotifyController extends Controller
{
    /**
     * Redirige al usuario al flujo de autorización de Spotify.
     */
public function redirectToSpotify()
{
    $query = http_build_query([
        'client_id'     => env('SPOTIFY_CLIENT_ID'),
        'response_type' => 'code',
        'redirect_uri'  => env('SPOTIFY_REDIRECT_URI'),
        'scope'         => implode(' ', [
            'streaming',                    // ← Para Web Playback SDK
            'user-read-email',
            'user-read-private',           // ← Para detectar Premium
            'user-top-read',
            'user-read-playback-state',    // ← Para leer estado de reproducción
            'user-modify-playback-state',  // ← Para controlar reproducción
            'user-read-currently-playing', // ← Para ver canción actual
            'playlist-read-private',       // ← Para acceder a playlists
            'playlist-read-collaborative', // ← Para playlists colaborativas
            'user-library-read',           // ← Permite leer canciones guardadas
            'user-library-modify',         // ← Permite dar/quitar me gusta
        ]),
        'show_dialog'   => 'true',
    ]);

    return redirect("https://accounts.spotify.com/authorize?$query");
}
    /**
     * Maneja el callback de Spotify y obtiene el access token.
     */
public function handleSpotifyCallback(Request $request)
{
    if ($request->has('error')) {
        return redirect()->route('home')
                         ->with('error', 'Acceso a Spotify denegado');
    }

    $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
        'grant_type'    => 'authorization_code',
        'code'          => $request->get('code'),
        'redirect_uri'  => env('SPOTIFY_REDIRECT_URI'),
        'client_id'     => env('SPOTIFY_CLIENT_ID'),
        'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    ]);

    if (! $response->successful()) {
        return redirect()->route('home')
                         ->with('error', 'No se pudo obtener el token de Spotify');
    }

    $data = $response->json();

    // ← Aquí guardas en la base en lugar de en sesión
    $user = Auth::user();
    $user->update([
        'spotify_token'            => $data['access_token'],
        'spotify_refresh_token'    => $data['refresh_token'] ?? $user->spotify_refresh_token,
        'spotify_token_expires_at' => now()->addSeconds($data['expires_in']),
    ]);

    return redirect()->route('home')
                     ->with('success', 'Conectado a Spotify correctamente');
}


    /**
     * (Opcional) Refresca el access token usando el refresh_token guardado.
     */
public function refreshAccessToken()
{
    $user = Auth::user();
    $refreshToken = $user->spotify_refresh_token;

    if (! $refreshToken) {
        abort(401, 'No se encontró refresh token de Spotify');
    }

    $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
        'grant_type'    => 'refresh_token',
        'refresh_token' => $refreshToken,
        'client_id'     => env('SPOTIFY_CLIENT_ID'),
        'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    ]);

    if (! $response->successful()) {
        abort($response->status(), 'Error al refrescar token de Spotify');
    }

    $data = $response->json();

    // Actualizamos en la base:
    $user->update([
        'spotify_token'            => $data['access_token'],
        'spotify_token_expires_at' => now()->addSeconds($data['expires_in']),
        // opcional: si viene refresh_token nuevo, lo actualizamos
        'spotify_refresh_token'    => $data['refresh_token'] ?? $refreshToken,
    ]);

    return back()->with('success', 'Token de Spotify actualizado');
}

    /**
     * Muestra la pantalla de 'Tus me gusta' con las canciones guardadas del usuario.
     */
    public function likedTracks(Request $request)
    {
        $user = Auth::user();
        $client = app(\App\Services\SpotifyClient::class);
        $tracks = $client->userRequest('me/tracks', ['limit' => 50]); // Puedes paginar si quieres más
        return view('me-gusta', [
            'tracks' => $tracks['items'] ?? [],
            'user' => $user,
        ]);
    }
}
