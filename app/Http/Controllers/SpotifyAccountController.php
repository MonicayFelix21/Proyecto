<?php

namespace App\Http\Controllers;

use App\Services\SpotifyClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpotifyAccountController extends Controller
{
    protected SpotifyClient $spotifyClient;

    public function __construct(SpotifyClient $spotifyClient)
    {
        $this->spotifyClient = $spotifyClient;
    }

    /**
     * Verifica el estado de la cuenta de Spotify del usuario.
     */
    public function checkAccountStatus()
    {
        $user = Auth::user();

        if (!$user->hasSpotifyConnected()) {
            return response()->json([
                'connected' => false,
                'message' => 'No tienes tu cuenta de Spotify conectada',
            ], 200);
        }

        if ($user->isSpotifyTokenExpired()) {
            try {
                $this->spotifyClient->refreshAccessToken();
            } catch (\Exception $e) {
                return response()->json([
                    'connected' => false,
                    'error' => 'Token expirado y no se pudo renovar',
                    'message' => 'Por favor, vuelve a conectar tu cuenta de Spotify',
                ], 401);
            }
        }

        $subscriptionInfo = $this->spotifyClient->getUserSubscriptionInfo();

        return response()->json([
            'connected' => true,
            'subscription_info' => $subscriptionInfo,
            'is_premium' => $subscriptionInfo['is_premium'],
            'message' => $subscriptionInfo['is_premium'] 
                ? '¡Tienes una cuenta Premium de Spotify!' 
                : 'Tienes una cuenta gratuita de Spotify',
        ]);
    }

    /**
     * Muestra la página de estado de la cuenta.
     */
    public function showAccountStatus()
    {
        return view('spotify.account-status');
    }

    /**
     * Verifica solo si es Premium (método simple).
     */
    public function isPremium()
    {
        $user = Auth::user();
        
        if (!$user->hasSpotifyConnected()) {
            return response()->json([
                'is_premium' => false,
                'message' => 'Cuenta de Spotify no conectada'
            ]);
        }

        $isPremium = $user->isSpotifyPremium();

        return response()->json([
            'is_premium' => $isPremium,
            'message' => $isPremium 
                ? 'Cuenta Premium activa' 
                : 'Cuenta gratuita'
        ]);
    }
}
