<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Services\SpotifyClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

// Ruta temporal para debug
Route::middleware('auth')->get('/debug-spotify', function () {
    $user = Auth::user();
    
    echo "<h2>🔍 Debug de Spotify - Usuario: {$user->name}</h2>";
    
    echo "<h3>1. Información del Usuario</h3>";
    echo "ID: {$user->id}<br>";
    echo "Nombre: {$user->name}<br>";
    echo "Email: {$user->email}<br>";
    
    echo "<h3>2. Tokens de Spotify</h3>";
    echo "spotify_token: " . ($user->spotify_token ? "✅ Sí (" . substr($user->spotify_token, 0, 20) . "...)" : "❌ No") . "<br>";
    echo "spotify_refresh_token: " . ($user->spotify_refresh_token ? "✅ Sí" : "❌ No") . "<br>";
    echo "spotify_token_expires_at: " . ($user->spotify_token_expires_at ? $user->spotify_token_expires_at->format('Y-m-d H:i:s') : "❌ No") . "<br>";
    
    echo "<h3>3. Estado del Token</h3>";
    if ($user->hasSpotifyConnected()) {
        echo " Spotify conectado<br>";
        
        if ($user->isSpotifyTokenExpired()) {
            echo " Token expirado<br>";
        } else {
            echo " Token válido<br>";
        }
        
        echo "<h3>4. Prueba directa con la API de Spotify</h3>";
        try {
            $response = Http::withToken($user->spotify_token)
                            ->get('https://api.spotify.com/v1/me');
            
            echo "Status HTTP: " . $response->status() . "<br>";
            
            if ($response->successful()) {
                $data = $response->json();
                echo "<pre>";
                echo "Respuesta completa de Spotify:\n";
                print_r($data);
                echo "</pre>";
                
                echo "<h3>5. Análisis del Producto</h3>";
                $product = $data['product'] ?? 'no_definido';
                echo "Producto detectado: <strong>{$product}</strong><br>";
                
                if ($product === 'premium') {
                    echo " <span style='color: green; font-weight: bold;'>DEBERÍA APARECER COMO PREMIUM</span><br>";
                } elseif ($product === 'free') {
                    echo "ℹ<span style='color: orange; font-weight: bold;'>CUENTA GRATUITA DETECTADA</span><br>";
                } else {
                    echo " <span style='color: red; font-weight: bold;'>PRODUCTO DESCONOCIDO: {$product}</span><br>";
                }
                
            } else {
                echo " Error en la respuesta: " . $response->body() . "<br>";
            }
            
        } catch (Exception $e) {
            echo " Error al consultar Spotify: " . $e->getMessage() . "<br>";
        }
        
        echo "<h3>6. Prueba con nuestro SpotifyClient</h3>";
        try {
            $spotifyClient = app(SpotifyClient::class);
            $subscriptionInfo = $spotifyClient->getUserSubscriptionInfo();
            
            echo "<pre>";
            echo "Información de suscripción:\n";
            print_r($subscriptionInfo);
            echo "</pre>";
            
        } catch (Exception $e) {
            echo " Error con SpotifyClient: " . $e->getMessage() . "<br>";
        }
        
    } else {
        echo " Spotify NO conectado<br>";
        echo "Para conectar, ve a: <a href='/auth/spotify'>Conectar Spotify</a><br>";
    }
    
    echo "<hr>";
    echo "<h3>7. Todos los usuarios con Spotify</h3>";
    $usersWithSpotify = User::whereNotNull('spotify_token')->get();
    foreach ($usersWithSpotify as $u) {
        echo "ID: {$u->id} - {$u->name} ({$u->email})<br>";
    }
    
    return '';
});
