<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Listeners\UpdateLastLoginAt;
use Illuminate\Auth\Events\Login;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TestLoginSyncCommand extends Command
{
    protected $signature = 'test:login-sync {user_id=1}';
    protected $description = 'Test the login auto-sync functionality';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }
        
        $this->info("Testing login auto-sync for user: {$user->name} (ID: {$user->id})");
        
        // Vérifier les paramètres Strava
        $this->info("Strava token: " . ($user->strava_token ? 'Present' : 'Missing'));
        $this->info("Token expires at: " . $user->strava_expires_at);
        $this->info("Current timestamp: " . now()->timestamp);
        $this->info("Auto sync on login: " . (isset($user->settings['sync_on_login']) && $user->settings['sync_on_login'] ? 'Enabled' : 'Disabled'));
        
        // Vérifier l'état actuel
        $syncInProgress = Cache::has("strava_sync_in_progress_{$user->id}");
        $syncProcessing = Cache::has("strava_sync_processing_{$user->id}");
        $pendingJobs = DB::table('jobs')
            ->where('queue', 'strava-sync')
            ->where('payload', 'like', '%"id";i:' . $user->id . ';%')
            ->count();
            
        $this->info("Sync in progress: " . ($syncInProgress ? 'Yes' : 'No'));
        $this->info("Sync processing: " . ($syncProcessing ? 'Yes' : 'No'));
        $this->info("Pending jobs: {$pendingJobs}");
        
        if (!$user->strava_token) {
            $this->warn("User doesn't have Strava connected - sync will be skipped");
            return 0;
        }
        
        if (!isset($user->settings['sync_on_login']) || !$user->settings['sync_on_login']) {
            $this->warn("Auto sync on login is disabled - sync will be skipped");
            return 0;
        }
        
        // Simuler l'événement de connexion
        $this->info("Simulating login event...");
        
        $listener = new UpdateLastLoginAt();
        $event = new Login('web', $user, false);
        
        $listener->handle($event);
        
        // Vérifier le résultat
        $this->info("Checking results...");
        
        $syncInProgressAfter = Cache::has("strava_sync_in_progress_{$user->id}");
        $pendingJobsAfter = DB::table('jobs')
            ->where('queue', 'strava-sync')
            ->where('payload', 'like', '%"id";i:' . $user->id . ';%')
            ->count();
            
        $this->info("Sync in progress after: " . ($syncInProgressAfter ? 'Yes' : 'No'));
        $this->info("Pending jobs after: {$pendingJobsAfter}");
        
        if ($pendingJobsAfter > $pendingJobs) {
            $this->info("✅ New sync job was created successfully!");
            
            // Attendre un peu pour voir si le job est traité
            $this->info("Waiting 10 seconds to see if job is processed...");
            sleep(10);
            
            $finalPendingJobs = DB::table('jobs')
                ->where('queue', 'strava-sync')
                ->where('payload', 'like', '%"id";i:' . $user->id . ';%')
                ->count();
                
            $syncResult = Cache::get("strava_sync_result_{$user->id}");
            
            $this->info("Final pending jobs: {$finalPendingJobs}");
            if ($syncResult) {
                $this->info("Sync result: " . json_encode($syncResult));
            } else {
                $this->info("No sync result yet - job may still be processing");
            }
            
        } else {
            $this->warn("No new job was created - this is expected if conditions weren't met");
        }
        
        return 0;
    }
}
