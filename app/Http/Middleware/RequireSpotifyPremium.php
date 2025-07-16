<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireSpotifyPremium
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta función.');
        }

        if (!$user->hasSpotifyConnected()) {
            return redirect()->route('spotify.account.status')
                           ->with('error', 'Debes conectar tu cuenta de Spotify para acceder a esta función Premium.');
        }

        if (!$user->isSpotifyPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Se requiere Spotify Premium',
                    'message' => 'Esta función está disponible solo para usuarios con Spotify Premium.',
                    'premium_required' => true
                ], 403);
            }

            return redirect()->route('spotify.account.status')
                           ->with('error', 'Esta función está disponible solo para usuarios con Spotify Premium.');
        }

        return $next($request);
    }
}
