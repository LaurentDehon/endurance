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
    public $timeout = 300; // 5 minutes timeout
    public $maxExceptions = 3; // Maximum d'exceptions avant échec
    
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

        try {
            Log::info("Starting Strava sync job for user {$user->id}");
            
            // Marquer le début du traitement
            Cache::put("strava_sync_processing_{$user->id}", true, now()->addMinutes(10));
            Log::info("Cache processing flag set for user {$user->id}");
            
            Log::info("About to call syncService->sync() for user {$user->id}");
            $result = $syncService->sync($user);
            Log::info("syncService->sync() completed for user {$user->id}. Result: " . json_encode($result));
            
            if ($result['success']) {
                Log::info("Strava sync completed successfully for user {$user->id}: {$result['message']}");
                
                // Invalider le cache des statistiques si des activités ont été ajoutées
                if (isset($result['count']) && $result['count'] > 0) {
                    Log::info("Invalidating cache for user {$user->id} - {$result['count']} activities added");
                    $this->invalidateStatsCache($user);
                }
                
                // Stocker le résultat dans le cache pour l'interface utilisateur
                Cache::put("strava_sync_result_{$user->id}", [
                    'success' => true,
                    'message' => $result['message'],
                    'count' => $result['count']
                ], now()->addMinutes(10));
                Log::info("Success result cached for user {$user->id}");
                
            } else {
                Log::warning("Strava sync failed for user {$user->id}: " . ($result['message'] ?? 'Unknown error'));
                
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
                        'message' => $result['message'] ?? 'Sync failed'
                    ], now()->addMinutes(10));
                }
                Log::info("Failure result cached for user {$user->id}");
            }
            
        } catch (\Exception $e) {
            Log::error("Strava sync job failed for user {$user->id}: " . $e->getMessage());
            Log::error("Exception trace: " . $e->getTraceAsString());
            
            // Stocker l'erreur dans le cache
            Cache::put("strava_sync_result_{$user->id}", [
                'success' => false,
                'message' => "Sync failed: " . $e->getMessage()
            ], now()->addMinutes(10));
            
            throw $e; // Re-throw pour que Laravel gère les tentatives de retry
            
        } finally {
            // Nettoyer les flags de traitement dans tous les cas
            Log::info("Cleaning up cache flags for user {$user->id}");
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
        
        Log::info("Cache invalidated for user {$userId} stats for year {$currentYear}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Strava sync job permanently failed for user {$this->userId}: " . $exception->getMessage());
        Log::error("Exception trace: " . $exception->getTraceAsString());
        
        // Nettoyer TOUS les flags de cache - utiliser $this->userId au lieu de $this->user
        Cache::forget("strava_sync_in_progress_{$this->userId}");
        Cache::forget("strava_sync_processing_{$this->userId}");
        
        // Stocker l'échec permanent dans le cache
        Cache::put("strava_sync_result_{$this->userId}", [
            'success' => false,
            'message' => 'Sync failed after multiple attempts: ' . $exception->getMessage()
        ], now()->addMinutes(10));
        
        Log::info("Cache flags cleared for failed job - user {$this->userId}");
    }
}