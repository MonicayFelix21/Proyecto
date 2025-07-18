<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SpotifyClient;
use Illuminate\Console\Command;

class CheckSpotifyPremium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spotify:check-premium {user_id? : ID del usuario a verificar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el estado Premium de Spotify de un usuario';

    protected SpotifyClient $spotifyClient;

    public function __construct(SpotifyClient $spotifyClient)
    {
        parent::__construct();
        $this->spotifyClient = $spotifyClient;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuario con ID {$userId} no encontrado.");
                return 1;
            }
            $this->checkUserPremium($user);
        } else {
            $this->info('Verificando estado Premium de todos los usuarios con Spotify conectado...');
            $users = User::whereNotNull('spotify_token')->get();
            
            if ($users->isEmpty()) {
                $this->warn('No hay usuarios con Spotify conectado.');
                return 0;
            }

            $this->withProgressBar($users, function ($user) {
                $this->checkUserPremium($user, false);
            });
            
            $this->newLine(2);
            $this->info('Verificación completada.');
        }

        return 0;
    }

    private function checkUserPremium(User $user, bool $verbose = true)
    {
        if ($verbose) {
            $this->info("Verificando usuario: {$user->name} (ID: {$user->id})");
        }

        if (!$user->hasSpotifyConnected()) {
            if ($verbose) {
                $this->warn('  ❌ Spotify no conectado');
            }
            return;
        }

        if ($user->isSpotifyTokenExpired()) {
            if ($verbose) {
                $this->warn('  ⚠️  Token expirado, intentando renovar...');
            }
            try {
                $this->spotifyClient->refreshAccessToken();
                if ($verbose) {
                    $this->info('  ✅ Token renovado exitosamente');
                }
            } catch (\Exception $e) {
                if ($verbose) {
                    $this->error('  ❌ No se pudo renovar el token: ' . $e->getMessage());
                }
                return;
            }
        }

        try {
            $subscriptionInfo = $this->spotifyClient->getUserSubscriptionInfo();
            
            if ($verbose) {
                $this->line("  📊 Información de la cuenta:");
                $this->line("     Nombre: " . ($subscriptionInfo['display_name'] ?? 'N/A'));
                $this->line("     Email: " . ($subscriptionInfo['email'] ?? 'N/A'));
                $this->line("     País: " . ($subscriptionInfo['country'] ?? 'N/A'));
                $this->line("     Producto: " . $subscriptionInfo['product']);
                $this->line("     Tipo: " . ($subscriptionInfo['product_display'] ?? 'N/A'));
                $this->line("     Seguidores: " . number_format($subscriptionInfo['followers']));
                
                if ($subscriptionInfo['is_premium']) {
                    $this->info("  👑 PREMIUM - Usuario tiene cuenta Premium activa");
                    $this->line("     ✅ Incluye: Individual, Familiar, Duo o Estudiantes");
                    
                    if (!empty($subscriptionInfo['premium_features'])) {
                        $this->line("     🎵 Características Premium disponibles:");
                        foreach ($subscriptionInfo['premium_features'] as $key => $feature) {
                            $this->line("        • $feature");
                        }
                    }
                } else {
                    $this->warn("  🆓 GRATUITO - Usuario tiene cuenta gratuita");
                }
            }

        } catch (\Exception $e) {
            if ($verbose) {
                $this->error('  ❌ Error verificando estado: ' . $e->getMessage());
            }
        }
    }
}
