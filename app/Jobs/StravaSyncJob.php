<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\StravaSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class StravaSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300; // Timeout de 5 minutes
    public $maxExceptions = 3; // Nombre maximum d'exceptions avant échec définitif
    
    public function __construct(
        protected int $userId
    ) {
        $this->onQueue('strava-sync');
    }

    public function handle(StravaSyncService $syncService): void
    {
        Log::info("=== DEBUT JOB SYNC USER {$this->userId} ===");
        
        $user = User::find($this->userId);

        if (!$user) {
            Log::error("User {$this->userId} introuvable lors de la sync Strava.");
            return;
        }

        // Définir la locale de l'utilisateur pour les traductions
        $userLocale = $user->settings['language'] ?? config('app.locale');
        app()->setLocale($userLocale);
        Log::info("Locale set to '{$userLocale}' for user {$this->userId}");

        try {
            Log::info("Début de la synchronisation Strava pour l'utilisateur {$user->id}");
            
            // Marquer le début du traitement pour l'interface utilisateur
            Cache::put("strava_sync_processing_{$user->id}", true, now()->addMinutes(10));
            Log::info("Flag de traitement en cache défini pour l'utilisateur {$user->id}");
            
            Log::info("Appel du service de synchronisation pour l'utilisateur {$user->id}");
            $result = $syncService->sync($user);
            Log::info("Synchronisation terminée pour l'utilisateur {$user->id}. Résultat: " . json_encode($result));
            
            if ($result['success']) {
                Log::info("Synchronisation Strava réussie pour l'utilisateur {$user->id}: {$result['message']}");
                
                // Mettre à jour l'horodatage de dernière synchronisation
                $user->last_sync_at = now();
                $user->save();
                Log::info("Timestamp last_sync_at mis à jour pour l'utilisateur {$user->id}");
                
                // Invalider le cache des statistiques si des activités ont été ajoutées
                if (isset($result['count']) && $result['count'] > 0) {
                    Log::info("Invalidation du cache pour l'utilisateur {$user->id} - {$result['count']} activités ajoutées");
                    $this->invalidateStatsCache($user);
                }
                
                // Stocker le résultat de succès dans le cache pour l'interface utilisateur
                Cache::put("strava_sync_result_{$user->id}", [
                    'success' => true,
                    'message' => $result['message'],
                    'count' => $result['count']
                ], now()->addMinutes(10));
                Log::info("Résultat de succès mis en cache pour l'utilisateur {$user->id}");
                
            } else {
                Log::warning("Échec de la synchronisation Strava pour l'utilisateur {$user->id}: " . ($result['message'] ?? 'Erreur inconnue'));
                
                // Si une redirection est nécessaire, la stocker dans le cache
                if (isset($result['redirect']) && $result['redirect'] === true) {
                    Cache::put("strava_sync_result_{$user->id}", [
                        'success' => false,
                        'redirect' => true,
                        'route' => $result['route'] ?? 'strava.redirect'
                    ], now()->addMinutes(10));
                } else {
                    Cache::put("strava_sync_result_{$user->id}", [
                        'success' => false,
                        'message' => $result['message'] ?? __('strava.sync.failed')
                    ], now()->addMinutes(10));
                }
                Log::info("Résultat d'échec mis en cache pour l'utilisateur {$user->id}");
            }
            
        } catch (\Exception $e) {
            Log::error("Échec du job de synchronisation Strava pour l'utilisateur {$user->id}: " . $e->getMessage());
            Log::error("Trace de l'exception: " . $e->getTraceAsString());
            
            // Stocker l'erreur dans le cache
            Cache::put("strava_sync_result_{$user->id}", [
                'success' => false,
                'message' => __('strava.sync.failed_with_error', ['error' => $e->getMessage()])
            ], now()->addMinutes(10));
            
            throw $e; // Relancer l'exception pour que Laravel gère les tentatives de retry
            
        } finally {
            // Nettoyer les flags de traitement dans tous les cas
            Log::info("Nettoyage des flags de cache pour l'utilisateur {$user->id}");
            Cache::forget("strava_sync_in_progress_{$user->id}");
            Cache::forget("strava_sync_processing_{$user->id}");
            Log::info("=== FIN JOB SYNC USER {$user->id} ===");
        }
    }

    /**
     * Invalide le cache des statistiques après une synchronisation réussie
     */
    private function invalidateStatsCache(User $user): void
    {
        $userId = $user->id;
        $currentYear = now()->year;
        
        // Invalider les caches des statistiques pour l'année courante
        Cache::forget("calendar-activities-{$userId}-{$currentYear}");
        Cache::forget("calendar-month-stats-{$userId}-{$currentYear}");
        Cache::forget("calendar-year-stats-{$userId}-{$currentYear}");
        
        Log::info("Cache des statistiques invalidé pour l'utilisateur {$userId} pour l'année {$currentYear}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Échec définitif du job de synchronisation Strava pour l'utilisateur {$this->userId}: " . $exception->getMessage());
        Log::error("Trace de l'exception: " . $exception->getTraceAsString());
        
        // Obtenir l'utilisateur pour définir sa locale
        $user = User::find($this->userId);
        if ($user) {
            $userLocale = $user->settings['language'] ?? config('app.locale');
            app()->setLocale($userLocale);
        }
        
        // Nettoyer tous les flags de cache
        Cache::forget("strava_sync_in_progress_{$this->userId}");
        Cache::forget("strava_sync_processing_{$this->userId}");
        
        // Stocker l'échec permanent dans le cache
        Cache::put("strava_sync_result_{$this->userId}", [
            'success' => false,
            'message' => __('strava.sync.failed_multiple_attempts', ['error' => $exception->getMessage()])
        ], now()->addMinutes(10));
        
        Log::info("Flags de cache nettoyés pour le job échoué - utilisateur {$this->userId}");
    }
}