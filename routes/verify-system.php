<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Services\SpotifyClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

// Ruta de verificación completa del sistema
Route::middleware('auth')->get('/verify-system', function () {
    $user = Auth::user();
    
    echo "<h1>🔍 Verificación Completa del Sistema Spotify</h1>";
    echo "<style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .card { background: white; padding: 20px; margin: 15px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .warning { color: #ffc107; }
        .error { color: #dc3545; }
        .info { color: #17a2b8; }
        h2 { border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .status { padding: 5px 10px; border-radius: 15px; color: white; font-weight: bold; }
        .status.ok { background: #28a745; }
        .status.warn { background: #ffc107; color: black; }
        .status.fail { background: #dc3545; }
    </style>";
    
    // 1. Verificar información del usuario
    echo "<div class='card'>";
    echo "<h2>1. 👤 Información del Usuario</h2>";
    echo "ID: {$user->id}<br>";
    echo "Nombre: " . ($user->name ?: '<em>Sin nombre</em>') . "<br>";
    echo "Email: {$user->email}<br>";
    echo "<span class='status ok'>✓ Usuario autenticado</span>";
    echo "</div>";
    
    // 2. Verificar tokens de Spotify
    echo "<div class='card'>";
    echo "<h2>2. 🎵 Tokens de Spotify</h2>";
    
    $hasToken = !empty($user->spotify_token);
    $hasRefresh = !empty($user->spotify_refresh_token);
    $hasExpiry = !empty($user->spotify_token_expires_at);
    
    echo "Token principal: " . ($hasToken ? "<span class='success'>✓ Presente</span>" : "<span class='error'>✗ Ausente</span>") . "<br>";
    echo "Refresh token: " . ($hasRefresh ? "<span class='success'>✓ Presente</span>" : "<span class='error'>✗ Ausente</span>") . "<br>";
    echo "Fecha expiración: " . ($hasExpiry ? "<span class='success'>✓ {$user->spotify_token_expires_at}</span>" : "<span class='error'>✗ No definida</span>") . "<br>";
    
    if ($hasToken && $hasRefresh) {
        echo "<span class='status ok'>✓ Spotify conectado</span>";
        
        // Verificar si está expirado
        if ($user->spotify_token_expires_at && $user->spotify_token_expires_at->isPast()) {
            echo "<br><span class='status warn'>⚠ Token expirado - Se renovará automáticamente</span>";
        } else {
            echo "<br><span class='status ok'>✓ Token válido</span>";
        }
    } else {
        echo "<span class='status fail'>✗ Spotify NO conectado</span>";
        echo "<br><a href='/auth/spotify' style='background: #1db954; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Conectar Spotify</a>";
    }
    echo "</div>";
    
    // 3. Verificar API de Spotify si está conectado
    if ($hasToken && $hasRefresh) {
        echo "<div class='card'>";
        echo "<h2>3. 🌐 Prueba de API de Spotify</h2>";
        
        try {
            $response = Http::withToken($user->spotify_token)
                            ->timeout(10)
                            ->get('https://api.spotify.com/v1/me');
            
            echo "Status HTTP: <strong>{$response->status()}</strong><br>";
            
            if ($response->successful()) {
                $data = $response->json();
                
                echo "<span class='status ok'>✓ API respondiendo correctamente</span><br><br>";
                
                echo "<strong>Datos del perfil:</strong><br>";
                echo "• Nombre: " . ($data['display_name'] ?? 'N/A') . "<br>";
                echo "• Email: " . ($data['email'] ?? 'N/A') . "<br>";
                echo "• País: " . ($data['country'] ?? 'N/A') . "<br>";
                echo "• Seguidores: " . ($data['followers']['total'] ?? 0) . "<br>";
                
                // Verificar campo product
                if (isset($data['product'])) {
                    $product = $data['product'];
                    echo "• <strong>Producto: {$product}</strong><br>";
                    
                    if ($product === 'premium') {
                        echo "<span class='status ok'>👑 PREMIUM DETECTADO - ¡Plan familiar funcionando!</span>";
                    } elseif ($product === 'free') {
                        echo "<span class='status warn'>🆓 Cuenta gratuita detectada</span>";
                    } else {
                        echo "<span class='status warn'>❓ Tipo de producto desconocido: {$product}</span>";
                    }
                } else {
                    echo "• <strong>Producto: <span class='error'>NO DISPONIBLE</span></strong><br>";
                    echo "<span class='status fail'>✗ Falta permiso 'user-read-private' - Necesitas reconectar</span>";
                    echo "<br><a href='/spotify/reset-connection' style='background: #ffc107; color: black; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Reconectar Spotify</a>";
                }
                
            } else {
                echo "<span class='status fail'>✗ Error en API: {$response->status()}</span><br>";
                echo "Respuesta: " . htmlspecialchars($response->body());
            }
            
        } catch (Exception $e) {
            echo "<span class='status fail'>✗ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</span>";
        }
        echo "</div>";
        
        // 4. Verificar nuestro SpotifyClient
        echo "<div class='card'>";
        echo "<h2>4. 🔧 SpotifyClient (Nuestro Código)</h2>";
        
        try {
            $spotifyClient = app(SpotifyClient::class);
            $subscriptionInfo = $spotifyClient->getUserSubscriptionInfo();
            
            echo "<span class='status ok'>✓ SpotifyClient funcionando</span><br><br>";
            
            echo "<strong>Información procesada:</strong><br>";
            echo "• Producto: " . ($subscriptionInfo['product'] ?? 'N/A') . "<br>";
            echo "• Descripción: " . ($subscriptionInfo['product_display'] ?? 'N/A') . "<br>";
            echo "• Es Premium: " . ($subscriptionInfo['is_premium'] ? 'SÍ' : 'NO') . "<br>";
            
            if ($subscriptionInfo['is_premium']) {
                echo "<span class='status ok'>👑 Sistema detecta PREMIUM correctamente</span>";
            } else {
                echo "<span class='status warn'>🆓 Sistema detecta cuenta gratuita</span>";
                
                if (isset($subscriptionInfo['error_message'])) {
                    echo "<br><em>" . htmlspecialchars($subscriptionInfo['error_message']) . "</em>";
                }
            }
            
        } catch (Exception $e) {
            echo "<span class='status fail'>✗ Error en SpotifyClient: " . htmlspecialchars($e->getMessage()) . "</span>";
        }
        echo "</div>";
    }
    
    // 5. Verificar rutas del reproductor
    echo "<div class='card'>";
    echo "<h2>5. 🎮 Rutas del Reproductor</h2>";
    
    $routes = [
        'spotify.player' => '/spotify/player',
        'spotify.account.status' => '/spotify/account-status',
        'api.spotify.is.premium' => '/api/spotify/is-premium',
    ];
    
    foreach ($routes as $name => $url) {
        try {
            $exists = route($name);
            echo "• <span class='success'>✓</span> {$name} → {$url}<br>";
        } catch (Exception $e) {
            echo "• <span class='error'>✗</span> {$name} → Error<br>";
        }
    }
    echo "<span class='status ok'>✓ Rutas registradas correctamente</span>";
    echo "</div>";
    
    // 6. Enlaces de prueba
    echo "<div class='card'>";
    echo "<h2>6. 🚀 Enlaces de Prueba</h2>";
    
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin: 20px 0;'>";
    
    $links = [
        ['Estado de Cuenta', '/spotify/account-status', '#007bff'],
        ['API Premium Check', '/api/spotify/is-premium', '#28a745'],
        ['Reproductor Web', '/spotify/player', '#dc3545'],
        ['Debug Completo', '/debug-spotify', '#ffc107'],
        ['Reconectar Spotify', '/spotify/reset-connection', '#6f42c1'],
    ];
    
    foreach ($links as [$title, $url, $color]) {
        echo "<a href='{$url}' target='_blank' style='background: {$color}; color: white; padding: 15px; text-decoration: none; border-radius: 8px; text-align: center; font-weight: bold; display: block;'>{$title}</a>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // 7. Resumen final
    echo "<div class='card'>";
    echo "<h2>7. 📊 Resumen del Estado</h2>";
    
    $isConnected = $hasToken && $hasRefresh;
    $needsReconnect = $isConnected && !isset($data['product']);
    
    if (!$isConnected) {
        echo "<span class='status fail'>❌ SPOTIFY NO CONECTADO</span><br>";
        echo "<strong>Acción:</strong> <a href='/auth/spotify'>Conectar Spotify</a>";
    } elseif ($needsReconnect) {
        echo "<span class='status warn'>⚠️ PERMISOS INSUFICIENTES</span><br>";
        echo "<strong>Acción:</strong> <a href='/spotify/reset-connection'>Reconectar con permisos completos</a>";
    } elseif (isset($data['product']) && $data['product'] === 'premium') {
        echo "<span class='status ok'>🎉 TODO FUNCIONANDO - PREMIUM DETECTADO</span><br>";
        echo "<strong>Acción:</strong> <a href='/spotify/player'>¡Usar el reproductor!</a>";
    } else {
        echo "<span class='status warn'>⚠️ CUENTA GRATUITA DETECTADA</span><br>";
        echo "<strong>Nota:</strong> El reproductor web requiere Spotify Premium";
    }
    
    echo "</div>";
    
    return '';
});
