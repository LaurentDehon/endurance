<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Services\StravaSyncService;
use App\Services\StravaAuthService;
use App\Jobs\StravaSyncJob;

class UpdateLastLoginAt
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event): void
    {
        $user = $event->user;
        $user->last_login_at = now();
        
        if (request()->ip()) {
            $user->last_ip_address = request()->ip();
        }
        
        if ($user instanceof Model) {
            $user->save();
        }

        // Check if auto sync on login is enabled and user has Strava connected
        if (isset($user->settings['sync_on_login']) && $user->settings['sync_on_login'] && $user->strava_token) {
            // Check if a sync is already in progress or queued
            if (cache()->has("strava_sync_in_progress_{$user->id}") || cache()->has("strava_sync_processing_{$user->id}")) {
                Log::info("Skipping auto-sync for user {$user->id} - sync already in progress");
                return;
            }
            
            // Check if there are already pending sync jobs for this user
            $pendingJobs = \Illuminate\Support\Facades\DB::table('jobs')
                ->where('queue', 'strava-sync')
                ->where('payload', 'like', '%"id";i:' . $user->id . ';%')
                ->count();
                
            if ($pendingJobs > 0) {
                Log::info("Skipping auto-sync for user {$user->id} - {$pendingJobs} jobs already queued");
                return;
            }
            
            // Check if token is valid or can be renewed
            $shouldSync = false;
            
            if ($user->strava_expires_at > now()->timestamp) {
                // Token is still valid
                $shouldSync = true;
            } elseif (isset($user->settings['auto_renew_token']) && $user->settings['auto_renew_token']) {
                // Token expired but auto renewal is enabled, try to renew
                try {
                    $authService = app(StravaAuthService::class);
                    $renewedUser = $authService->refreshUserToken($user);
                    if ($renewedUser && $renewedUser->strava_token) {
                        $shouldSync = true;
                    }
                } catch (\Exception $e) {
                    // Token renewal failed, skip sync
                    Log::warning('Failed to renew Strava token for user ' . $user->id . ' during login sync: ' . $e->getMessage());
                }
            }
            
            if ($shouldSync) {
                // Mark sync as in progress for the polling system
                cache()->put("strava_sync_in_progress_{$user->id}", true, now()->addMinutes(5));
                
                // Dispatch sync job to background
                StravaSyncJob::dispatch($user->id)->onQueue('strava-sync');
                
                // Store a flag to show sync started toast on the next page load
                session()->flash('login_sync_started', true);
                
                Log::info("Auto-sync initiated for user {$user->id} on login");
            }
        }
    }
}
