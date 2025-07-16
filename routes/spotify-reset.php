<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Ruta para limpiar tokens de Spotify y forzar nueva autorización
Route::middleware('auth')->get('/spotify/reset-connection', function () {
    $user = Auth::user();
    
    // Limpiar todos los tokens de Spotify
    $user->update([
        'spotify_token' => null,
        'spotify_refresh_token' => null,
        'spotify_token_expires_at' => null,
    ]);
    
    echo "<h2>🔄 Conexión de Spotify Reiniciada</h2>";
    echo "<p>✅ Tokens eliminados correctamente</p>";
    echo "<p>🔗 <a href='/auth/spotify' style='color: #1db954; font-size: 18px; font-weight: bold;'>Conectar Spotify nuevamente con permisos completos</a></p>";
    echo "<br>";
    echo "<p style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
    echo "<strong>Nota:</strong> Ahora se solicitarán los permisos correctos, incluyendo 'user-read-private' ";
    echo "que es necesario para detectar el tipo de cuenta (Premium/Gratuito).";
    echo "</p>";
    
    return '';
});
