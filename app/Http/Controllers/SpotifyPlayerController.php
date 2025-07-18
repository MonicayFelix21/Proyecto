<?php

namespace App\Http\Controllers;

use App\Services\SpotifyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotifyPlayerController extends Controller
{
    protected SpotifyClient $spotifyClient;

    public function __construct(SpotifyClient $spotifyClient)
    {
        $this->spotifyClient = $spotifyClient;
    }

    /**
     * Muestra la página del reproductor web.
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user->hasSpotifyConnected()) {
            return redirect()->route('spotify.account.status')
                           ->with('error', 'Debes conectar tu cuenta de Spotify para usar el reproductor.');
        }

        if (!$user->isSpotifyPremium()) {
            return redirect()->route('spotify.account.status')
                           ->with('error', 'El reproductor web requiere Spotify Premium.');
        }

        return view('spotify.player', [
            'user' => $user,
            'spotify_token' => $user->spotify_token,
        ]);
    }

    /**
     * Obtiene el estado actual de reproducción.
     */
    public function getCurrentPlayback()
    {
        try {
            $playback = $this->spotifyClient->userRequest('me/player');
            return response()->json($playback);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Reproduce una canción específica.
     */
    public function play(Request $request)
    {
        $trackUri = $request->input('track_uri');
        $deviceId = $request->input('device_id');

        try {
            $body = [];
            if ($trackUri) {
                $body['uris'] = [$trackUri];
            }

            $this->spotifyClient->userRequest("me/player/play" . ($deviceId ? "?device_id=$deviceId" : ""), [], 'PUT', $body);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Pausa la reproducción.
     */
    public function pause(Request $request)
    {
        $deviceId = $request->input('device_id');

        try {
            $this->spotifyClient->userRequest("me/player/pause" . ($deviceId ? "?device_id=$deviceId" : ""), [], 'PUT');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Siguiente canción.
     */
    public function next(Request $request)
    {
        $deviceId = $request->input('device_id');

        try {
            $this->spotifyClient->userRequest("me/player/next" . ($deviceId ? "?device_id=$deviceId" : ""), [], 'POST');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Canción anterior.
     */
    public function previous(Request $request)
    {
        $deviceId = $request->input('device_id');

        try {
            $this->spotifyClient->userRequest("me/player/previous" . ($deviceId ? "?device_id=$deviceId" : ""), [], 'POST');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Ajusta el volumen.
     */
    public function setVolume(Request $request)
    {
        $volume = $request->input('volume');
        $deviceId = $request->input('device_id');

        try {
            $this->spotifyClient->userRequest("me/player/volume?volume_percent=$volume" . ($deviceId ? "&device_id=$deviceId" : ""), [], 'PUT');
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
