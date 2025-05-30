<?php

namespace App\Console\Commands;

use App\Jobs\StravaSyncJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TestStravaSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:test-sync {user_id? : ID of user to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Strava synchronization with queue job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if (!$userId) {
            // Utiliser le premier utilisateur disponible pour le test
            $user = User::first();
            if (!$user) {
                $this->error('No users found in database');
                return 1;
            }
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found");
                return 1;
            }
        }
        
        $this->info("Testing Strava sync for user: {$user->name} (ID: {$user->id})");
        
        // Vérifier si une sync est déjà en cours
        if (Cache::has("strava_sync_in_progress_{$user->id}")) {
            $this->warn('A sync is already in progress for this user');
            return 1;
        }
        
        // Marquer la sync comme en cours
        Cache::put("strava_sync_in_progress_{$user->id}", true, now()->addMinutes(5));
        
        // Nettoyer les anciens résultats
        Cache::forget("strava_sync_result_{$user->id}");
        
        // Lancer le job
        StravaSyncJob::dispatch($user->id);
        
        $this->info('Strava sync job dispatched successfully!');
        $this->info('Monitor the queue worker logs and check cache for results');
        
        // Attendre et vérifier le résultat
        $this->info('Waiting for job completion (max 60 seconds)...');
        
        $attempts = 0;
        $maxAttempts = 60; // 60 secondes max
        
        while ($attempts < $maxAttempts) {
            sleep(1);
            $attempts++;
            
            $result = Cache::get("strava_sync_result_{$user->id}");
            
            if ($result) {
                Cache::forget("strava_sync_result_{$user->id}");
                Cache::forget("strava_sync_in_progress_{$user->id}");
                
                if ($result['success']) {
                    $this->info("✅ Sync completed successfully: {$result['message']}");
                    if (isset($result['count'])) {
                        $this->info("Activities synchronized: {$result['count']}");
                    }
                } else {
                    $this->error("❌ Sync failed: {$result['message']}");
                    if (isset($result['redirect']) && $result['redirect']) {
                        $this->warn("Redirect required to: {$result['route']}");
                    }
                }
                return $result['success'] ? 0 : 1;
            }
            
            if ($attempts % 10 === 0) {
                $this->info("Still waiting... ({$attempts}s)");
            }
        }
        
        $this->warn('Timeout waiting for sync completion');
        return 1;
    }
}
