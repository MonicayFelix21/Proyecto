<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CancionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ArtistaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\SpotifyAuthController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
// Pantalla pública (ahora con datos de Spotify si ya hay token guardado en BD)
Route::get('/inicio', [InicioController::class, 'index'])
     ->name('inicio');

Route::redirect('/', '/inicio');

// Listado de artistas
Route::get('/artistas', [ArtistaController::class, 'index'])
     ->name('artistas.index');

// Login / Registro
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/registro', [RegistroController::class, 'form'])->name('registro');
Route::post('/registro', [RegistroController::class, 'registrar'])->name('registro.submit');

// Alias para compatibilidad con paquetes que buscan la ruta 'register'
Route::get('/register', function () {
    return redirect()->route('registro');
})->name('register');

// AJAX utilities
Route::get('/verificar-email', function (Request $r) {
    $existe = DB::table('users')->where('email', $r->email)->exists();
    return response()->json(['existe' => $existe]);
})->name('verificar.email');
Route::get('/buscar-canciones', [CancionController::class, 'buscar'])
     ->name('canciones.buscar');
Route::get('/buscar-cancion', function (Request $r) {
    $c = \App\Models\Cancion::find($r->id);
    return $c
        ? response()->json($c)
        : response()->json(['error'=>'No encontrada'], 404);
})->name('cancion.unica');

// OAuth Spotify (necesita auth para guardar tokens en BD)
Route::middleware('auth')->group(function () {
    Route::get('/auth/spotify', [SpotifyAuthController::class, 'redirectToSpotify'])
         ->name('spotify.auth');
    Route::get('/auth/spotify/callback', [SpotifyAuthController::class, 'handleCallback'])
         ->name('spotify.callback');
    Route::post('/spotify/refresh', [SpotifyController::class, 'refreshAccessToken'])
         ->name('spotify.refresh');

    // Pantalla privada 
    Route::get('/home', [HomeController::class, 'index'])
         ->name('home');
});

Route::get('/spotify/search', \App\Http\Controllers\SpotifySearchController::class)
     ->name('spotify.search');

// Endpoint para verificar el plan de Spotify del usuario
Route::middleware('auth')->get('/spotify-product', function () {
    // Hacemos la petición a la API de Spotify con el token del usuario
    $response = Http::withToken(Auth::user()->spotify_token)
                    ->get('https://api.spotify.com/v1/me');

    // Mostramos el campo "product" ("premium", "free", etc.)
    dd($response->json('product'));
})->name('spotify.product');

// Rutas para verificar estado de cuenta de Spotify
Route::middleware('auth')->group(function () {
    Route::get('/spotify/account-status', [App\Http\Controllers\SpotifyAccountController::class, 'showAccountStatus'])
         ->name('spotify.account.status');
    
    Route::get('/api/spotify/account-status', [App\Http\Controllers\SpotifyAccountController::class, 'checkAccountStatus'])
         ->name('api.spotify.account.status');
    
    Route::get('/api/spotify/is-premium', [App\Http\Controllers\SpotifyAccountController::class, 'isPremium'])
         ->name('api.spotify.is.premium');

    //  Nuevas APIs avanzadas de Spotify
    Route::prefix('api/spotify')->group(function () {
        // Historial y actividad
        Route::get('/recently-played', [App\Http\Controllers\SpotifyAdvancedController::class, 'getRecentlyPlayed']);
        Route::get('/top/{type}', [App\Http\Controllers\SpotifyAdvancedController::class, 'getTopItems']);
        
        // Recomendaciones
        Route::get('/recommendations', [App\Http\Controllers\SpotifyAdvancedController::class, 'getRecommendations']);
        
        // Biblioteca personal
        Route::get('/saved-tracks', [App\Http\Controllers\SpotifyAdvancedController::class, 'getSavedTracks']);
        Route::post('/toggle-saved-track', [App\Http\Controllers\SpotifyAdvancedController::class, 'toggleSavedTrack']);
        
        // Playlists
        Route::get('/playlists', [App\Http\Controllers\SpotifyAdvancedController::class, 'getUserPlaylists']);
    });

    // Ruta de verificación completa del sistema
    Route::get('/verify-system', function () {
        $user = Auth::user();
        
        echo "<h1>🔍 Verificación Completa del Sistema Spotify</h1>";
        echo "<p><strong>Usuario:</strong> {$user->name} ({$user->email})</p>";
        
        // Verificar conexión Spotify
        $hasSpotify = !empty($user->spotify_token);
        echo "<p><strong>Spotify conectado:</strong> " . ($hasSpotify ? "✅ SÍ" : "❌ NO") . "</p>";
        
        if ($hasSpotify) {
            // Probar API
            try {
                $response = Http::withToken($user->spotify_token)->get('https://api.spotify.com/v1/me');
                $data = $response->json();
                
                echo "<p><strong>API Status:</strong> " . $response->status() . "</p>";
                echo "<p><strong>Producto:</strong> " . ($data['product'] ?? 'NO DISPONIBLE') . "</p>";
                
                if (isset($data['product']) && $data['product'] === 'premium') {
                    echo "<h2 style='color: green;'>🎉 ¡PREMIUM DETECTADO! Todo funciona correctamente.</h2>";
                } elseif (isset($data['product']) && $data['product'] === 'free') {
                    echo "<h2 style='color: orange;'>🆓 Cuenta gratuita detectada</h2>";
                } else {
                    echo "<h2 style='color: red;'>⚠️ Falta permiso 'user-read-private' - Reconectar necesario</h2>";
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'><strong>Error API:</strong> " . $e->getMessage() . "</p>";
            }
        }
        
        echo "<hr>";
        echo "<h3>Enlaces de Prueba:</h3>";
        echo "<a href='/spotify/account-status' style='background: blue; color: white; padding: 10px; margin: 5px; text-decoration: none;'>Estado de Cuenta</a> ";
        echo "<a href='/api/spotify/is-premium' style='background: green; color: white; padding: 10px; margin: 5px; text-decoration: none;'>API Premium</a> ";
        echo "<a href='/spotify/reset-connection' style='background: orange; color: white; padding: 10px; margin: 5px; text-decoration: none;'>Reconectar</a>";
        
        return '';
    })->name('verify.system');
});

// Ruta temporal para debug de Spotify
require __DIR__ . '/debug-spotify.php';

// Ruta para reiniciar conexión de Spotify
require __DIR__ . '/spotify-reset.php';

// Ruta para funcionalidades avanzadas de Spotify
Route::get('/spotify-avanzado', function () {
    return view('spotify-avanzado');
})->middleware('auth')->name('spotify.avanzado');

// Vista de explorar categorías
Route::get('/explorar', function() {
    return view('explorar');
})->name('explorar');

// API para categorías de Spotify
Route::get('/api/spotify/categorias', [\App\Http\Controllers\SpotifySearchController::class, 'categorias']);

// Ruta protegida para 'Tus me gusta'
Route::middleware('auth')->get('/me-gusta', [SpotifyController::class, 'likedTracks'])->name('me-gusta');

// Ruta para obtener el número de canciones guardadas (me gusta) del usuario en Spotify
Route::get('/api/spotify/liked-songs-count', [App\Http\Controllers\SpotifyPlayerController::class, 'getLikedSongsCount'])->middleware('auth');