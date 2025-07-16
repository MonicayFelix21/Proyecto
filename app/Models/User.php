<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'genero',

                // tokens de Spotify:
        'spotify_token',
        'spotify_refresh_token',
        'spotify_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'spotify_token_expires_at' => 'datetime',
        ];
    }

    /**
     * Verifica si el usuario tiene Spotify conectado.
     */
    public function hasSpotifyConnected(): bool
    {
        return !empty($this->spotify_token) && !empty($this->spotify_refresh_token);
    }

    /**
     * Verifica si el token de Spotify ha expirado.
     */
    public function isSpotifyTokenExpired(): bool
    {
        return $this->spotify_token_expires_at && $this->spotify_token_expires_at->isPast();
    }

    /**
     * Verifica si el usuario tiene una cuenta Premium de Spotify.
     * Incluye: Premium Individual, Premium Familiar, Premium Duo, Premium Estudiantes
     */
    public function isSpotifyPremium(): bool
    {
        if (!$this->hasSpotifyConnected()) {
            return false;
        }

        try {
            $spotifyClient = app(\App\Services\SpotifyClient::class);
            $profile = $spotifyClient->getUserProfile();
            
            // Todos los tipos de Premium aparecen como "premium" en la API
            // Esto incluye: Individual, Familiar, Duo, Estudiantes
            return isset($profile['product']) && $profile['product'] === 'premium';
        } catch (\Exception $e) {
            Log::error('Error verificando estado Premium de Spotify: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene el tipo específico de plan Premium si es posible determinarlo.
     */
    public function getSpotifyPlanType(): string
    {
        if (!$this->hasSpotifyConnected()) {
            return 'not_connected';
        }

        try {
            $spotifyClient = app(\App\Services\SpotifyClient::class);
            $profile = $spotifyClient->getUserProfile();
            $product = $profile['product'] ?? 'unknown';
            
            if ($product === 'premium') {
                // Todos los tipos Premium aparecen como "premium"
                // No hay forma de distinguir entre Individual, Familiar, Duo desde la API pública
                return 'premium';
            }
            
            return $product; // 'free', 'open', etc.
        } catch (\Exception $e) {
            Log::error('Error obteniendo tipo de plan Spotify: ' . $e->getMessage());
            return 'error';
        }
    }
}
