<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpotifyClient
{
    /**
     * URL base de la API de Spotify.
     *
     * @var string
     */
    protected string $baseUrl = 'https://api.spotify.com/v1/';

    /**
     * URL para obtener tokens (OAuth & Client Credentials).
     *
     * @var string
     */
    protected string $tokenUrl = 'https://accounts.spotify.com/api/token';

    /**
     * Token de aplicación (Client Credentials) y su expiración.
     *
     * @var string|null
     */
    protected ?string $appToken = null;
    protected ?\DateTimeInterface $appTokenExpiresAt = null;

    /**
     * Obtiene o refresca el token de aplicación para endpoints públicos.
     */
    protected function getAppToken(): string
    {
        if ($this->appToken && $this->appTokenExpiresAt > now()) {
            return $this->appToken;
        }

        $response = Http::asForm()
            ->withBasicAuth(env('SPOTIFY_CLIENT_ID'), env('SPOTIFY_CLIENT_SECRET'))
            ->post($this->tokenUrl, ['grant_type' => 'client_credentials']);

        if (! $response->successful()) {
            abort(500, 'No se pudo obtener token de aplicación de Spotify');
        }

        $data = $response->json();
        $this->appToken = $data['access_token'];
        $this->appTokenExpiresAt = now()->addSeconds($data['expires_in'] * 0.9);

        return $this->appToken;
    }

    /**
     * Llama a un endpoint público con Client Credentials.
     */
    protected function publicRequest(string $endpoint, array $query = []): array
    {
        $response = Http::withToken($this->getAppToken())
            ->get($this->baseUrl . $endpoint, $query);

        if (! $response->successful()) {
            abort($response->status(), 'Error Spotify público: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Nuevos lanzamientos públicos.
     */
    public function nuevosLanzamientosPublic(int $limit = 12): array
    {
        $data = $this->publicRequest('browse/new-releases', ['limit' => $limit]);
        return $data['albums']['items'] ?? [];
    }

    /**
     * Playlists destacadas.
     */
    public function featuredPlaylists(int $limit = 1, string $locale = 'es_ES'): array
    {
        $data = $this->publicRequest('browse/featured-playlists', [
            'limit'  => $limit,
            'locale' => $locale,
        ]);
        return $data['playlists']['items'] ?? [];
    }

    /**
     * Pistas de una playlist pública.
     */
    public function playlistTracks(string $playlistId, int $limit = 12): array
    {
        $data = $this->publicRequest("playlists/{$playlistId}/tracks", [
            'limit' => $limit,
        ]);
        return collect($data['items'] ?? [])
            ->pluck('track')
            ->toArray();
    }

    /**
     * Info básica de varios artistas (nombre + imágenes).
     */
    public function publicArtists(array $ids): array
    {
        $artists = [];
        foreach (array_chunk($ids, 50) as $chunk) {
            $data = $this->publicRequest('artists', [
                'ids' => implode(',', $chunk),
            ]);
            $artists = array_merge($artists, $data['artists'] ?? []);
        }
        return $artists;
    }

    /**
     * Recupera el token de un usuario logueado.
     */
    protected function userToken(): string
    {
        $user = auth()->user();
        if (! $user || ! $user->spotify_token) {
            abort(401, 'No autenticado en Spotify');
        }
        if ($user->spotify_token_expires_at && now()->greaterThan($user->spotify_token_expires_at)) {
            // Refrescar token automáticamente
            $refreshToken = $user->spotify_refresh_token;
            if (! $refreshToken) {
                abort(401, 'No se encontró refresh token de Spotify');
            }
            $response = \Http::asForm()->post('https://accounts.spotify.com/api/token', [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id'     => env('SPOTIFY_CLIENT_ID'),
                'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
            ]);
            if (! $response->successful()) {
                abort($response->status(), 'Error al refrescar token de Spotify: ' . $response->body());
            }
            $data = $response->json();
            $user->spotify_token = $data['access_token'];
            $user->spotify_token_expires_at = now()->addSeconds($data['expires_in']);
            if (isset($data['refresh_token'])) {
                $user->spotify_refresh_token = $data['refresh_token'];
            }
            $user->save();
        }
        return $user->spotify_token;
    }

    /**
     * Llama a un endpoint privado con token de usuario.
     */
    public function userRequest(string $endpoint, array $query = [], string $method = 'GET', array $body = []): array
    {
        $httpClient = Http::withToken($this->userToken())->retry(2, 100);
        
        $response = match(strtoupper($method)) {
            'GET' => $httpClient->get($this->baseUrl . $endpoint, $query),
            'POST' => $httpClient->post($this->baseUrl . $endpoint, $body),
            'PUT' => $httpClient->put($this->baseUrl . $endpoint, $body),
            'DELETE' => $httpClient->delete($this->baseUrl . $endpoint),
            default => $httpClient->get($this->baseUrl . $endpoint, $query)
        };

        if (! $response->successful()) {
            abort($response->status(), 'Error Spotify usuario: ' . $response->body());
        }
        
        // Algunos endpoints no devuelven JSON (como play/pause)
        return $response->json() ?: [];
    }

    /**
     * Nuevos lanzamientos privados.
     */
    public function nuevosLanzamientos(int $limit = 12): array
    {
        $data = $this->userRequest('browse/new-releases', ['limit' => $limit]);
        return $data['albums']['items'] ?? [];
    }

    /**
     * Top tracks del usuario.
     */
    public function topTracks(int $limit = 12): array
    {
        $data = $this->userRequest('me/top/tracks', ['limit' => $limit]);
        return $data['items'] ?? [];
    }

    /**
     * Top artists del usuario.
     */
    public function topArtists(int $limit = 8): array
    {
        $data = $this->userRequest('me/top/artists', ['limit' => $limit]);
        return $data['items'] ?? [];
    }

    /**
     * Búsqueda de usuario.
     */
    public function buscar(string $q, string $type = 'track,artist', int $limit = 10): array
    {
        return $this->userRequest('search', compact('q', 'type', 'limit'));
    }

    /**
     * Refresca el token de usuario en BD.
     */
    public function refreshAccessToken(): void
    {
        $user = auth()->user();
        $refreshToken = $user?->spotify_refresh_token;
        if (! $refreshToken) {
            abort(401, 'No hay refresh token');
        }

        $response = Http::asForm()->post($this->tokenUrl, [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id'     => env('SPOTIFY_CLIENT_ID'),
            'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        ]);

        if (! $response->successful()) {
            abort($response->status(), 'Error al refrescar token');
        }

        $data = $response->json();
        $user->update([
            'spotify_token'            => $data['access_token'],
            'spotify_token_expires_at' => now()->addSeconds($data['expires_in']),
        ]);
    }

    /**
     * Busca álbumes, pistas o playlists.
     * Si hay usuario autenticado, usa userRequest; si no, usa publicRequest.
     */
    public function search(string $q, string $types = 'track,artist,album,playlist', int $limit = 10): array
    {
        if (auth()->check() && auth()->user()?->spotify_token) {
            // Usuario autenticado: búsqueda completa
            return $this->userRequest('search', [
                'q'     => $q,
                'type'  => $types,
                'limit' => $limit,
            ]);
        } else {
            // Invitado: búsqueda pública (limitada)
            return $this->publicRequest('search', [
                'q'     => $q,
                'type'  => $types,
                'limit' => $limit,
            ]);
        }
    }

    /**
     * Obtiene el perfil del usuario autenticado.
     */
    public function getUserProfile(): array
    {
        return $this->userRequest('me');
    }

    /**
     * Verifica si el usuario tiene cuenta Premium.
     * Incluye todos los tipos: Individual, Familiar, Duo, Estudiantes
     */
    public function isPremiumUser(): bool
    {
        try {
            $profile = $this->getUserProfile();
            // Todos los planes Premium (Individual, Familiar, Duo, Estudiantes) aparecen como "premium"
            return isset($profile['product']) && $profile['product'] === 'premium';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene información detallada sobre el plan del usuario.
     */
    public function getUserSubscriptionInfo(): array
    {
        try {
            $profile = $this->getUserProfile();
            $product = $profile['product'] ?? null;
            
            // Si no hay campo 'product', significa que falta el scope 'user-read-private'
            if ($product === null) {
                return [
                    'product' => 'scope_missing',
                    'product_display' => 'Permisos insuficientes',
                    'country' => $profile['country'] ?? null,
                    'display_name' => $profile['display_name'] ?? null,
                    'email' => $profile['email'] ?? null,
                    'followers' => $profile['followers']['total'] ?? 0,
                    'is_premium' => false,
                    'premium_features' => [],
                    'error_message' => 'Falta el permiso "user-read-private" para detectar el tipo de cuenta. Reconecta Spotify.',
                ];
            }
            
            $isPremium = $product === 'premium';
            
            return [
                'product' => $product,
                'product_display' => $this->getProductDisplayName($product),
                'country' => $profile['country'] ?? null,
                'display_name' => $profile['display_name'] ?? null,
                'email' => $profile['email'] ?? null,
                'followers' => $profile['followers']['total'] ?? 0,
                'is_premium' => $isPremium,
                'premium_features' => $isPremium ? $this->getPremiumFeatures() : [],
            ];
        } catch (\Exception $e) {
            return [
                'product' => 'error',
                'product_display' => 'Error al obtener información',
                'is_premium' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Convierte el código de producto en un nombre legible.
     */
    private function getProductDisplayName(string $product): string
    {
        return match($product) {
            'premium' => 'Spotify Premium',
            'free' => 'Spotify Gratuito',
            'open' => 'Spotify Abierto',
            'scope_missing' => 'Permisos insuficientes - Reconectar necesario',
            default => ucfirst($product)
        };
    }

    /**
     * Obtiene las características disponibles para usuarios Premium.
     */
    private function getPremiumFeatures(): array
    {
        return [
            'ad_free' => 'Música sin anuncios',
            'offline' => 'Escucha sin conexión',
            'on_demand' => 'Reproduce cualquier canción',
            'unlimited_skips' => 'Saltos ilimitados',
            'high_quality' => 'Calidad de audio superior',
            'group_session' => 'Sesiones grupales',
            'queue_control' => 'Control total de la cola',
        ];
    }

    /**
     * Devuelve las categorías de Spotify para explorar.
     */
    public function getCategories($limit = 50, $locale = 'es_ES', $country = 'MX')
    {
        return $this->publicRequest('browse/categories', [
            'limit' => $limit,
            'locale' => $locale,
            'country' => $country
        ]);
    }
}
