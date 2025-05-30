<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\StravaSyncService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StravaSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300; // 5 minutes timeout
    public $maxExceptions = 3; // Maximum d'exceptions avant échec
    public $user;
    
    public function __construct(
        protected int $userId
    ) {
        $this->onQueue('strava-sync');
    }

    public function handle(StravaSyncService $syncService): void
    {
        $this->user = User::find($this->userId);

            if (!$this->user) {
                Log::error("User {$this->userId} introuvable lors de la sync Strava.");
                return;
            }

        try {
            Log::info("Starting Strava sync job for user {$this->user->id}");
            
            // Marquer le début du traitement
            cache()->put("strava_sync_processing_{$this->user->id}", true, now()->addMinutes(10));
            
            $result = $syncService->sync($this->user);
            
            if ($result['success']) {
                Log::info("Strava sync completed successfully for user {$this->user->id}: {$result['message']}");
                
                // Invalider le cache des statistiques si des activités ont été ajoutées
                if (isset($result['count']) && $result['count'] > 0) {
                    $this->invalidateStatsCache();
                }
                
                // Stocker le résultat dans le cache pour l'interface utilisateur
                cache()->put("strava_sync_result_{$this->user->id}", [
                    'success' => true,
                    'message' => $result['message'],
                    'count' => $result['count']
                ], now()->addMinutes(10));
                
            } else {
                Log::warning("Strava sync failed for user {$this->user->id}: " . ($result['message'] ?? 'Unknown error'));
                
                // Si une redirection est nécessaire, la stocker dans le cache
                if (isset($result['redirect']) && $result['redirect'] === true) {
                    cache()->put("strava_sync_result_{$this->user->id}", [
                        'success' => false,
                        'redirect' => true,
                        'route' => $result['route'] ?? 'strava.redirect'
                    ], now()->addMinutes(10));
                } else {
                    cache()->put("strava_sync_result_{$this->user->id}", [
                        'success' => false,
                        'message' => $result['message'] ?? 'Sync failed'
                    ], now()->addMinutes(10));
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Strava sync job failed for user {$this->user->id}: " . $e->getMessage());
            
            // Stocker l'erreur dans le cache
            cache()->put("strava_sync_result_{$this->user->id}", [
                'success' => false,
                'message' => "Sync failed: " . $e->getMessage()
            ], now()->addMinutes(10));
            
            throw $e; // Re-throw pour que Laravel gère les tentatives de retry
            
        } finally {
            // Nettoyer les flags de traitement dans tous les cas
            cache()->forget("strava_sync_in_progress_{$this->user->id}");
            cache()->forget("strava_sync_processing_{$this->user->id}");
        }
    }

    /**
     * Invalide le cache des statistiques après une synchronisation réussie
     */
    private function invalidateStatsCache(): void
    {
        $userId = $this->user->id;
        $currentYear = now()->year;
        
        // Invalider les caches des statistiques pour l'année courante
        cache()->forget("calendar-activities-{$userId}-{$currentYear}");
        cache()->forget("calendar-month-stats-{$userId}-{$currentYear}");
        cache()->forget("calendar-year-stats-{$userId}-{$currentYear}");
        
        Log::info("Cache invalidated for user {$userId} stats for year {$currentYear}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Strava sync job permanently failed for user {$this->user->id}: " . $exception->getMessage());
        
        // Nettoyer tous les flags de cache
        cache()->forget("strava_sync_in_progress_{$this->user->id}");
        cache()->forget("strava_sync_processing_{$this->user->id}");
        
        // Stocker l'échec permanent dans le cache
        cache()->put("strava_sync_result_{$this->user->id}", [
            'success' => false,
            'message' => 'Sync failed after multiple attempts. Please try again later.'
        ], now()->addMinutes(10));
    }
}
