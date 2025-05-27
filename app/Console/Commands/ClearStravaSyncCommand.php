<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClearStravaSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:clear-sync {user_id? : ID of specific user to clear, or all users if not specified}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Strava sync cache and stuck jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found");
                return 1;
            }
            $this->clearUserSync($user);
            $this->info("Cleared sync cache for user: {$user->name}");
        } else {
            // Nettoyer pour tous les utilisateurs
            $users = User::all();
            foreach ($users as $user) {
                $this->clearUserSync($user);
            }
            $this->info("Cleared sync cache for all users ({$users->count()} users)");
        }
        
        // Nettoyer aussi les jobs bloqués dans la queue
        $clearedJobs = DB::table('jobs')->where('queue', 'strava-sync')->delete();
        if ($clearedJobs > 0) {
            $this->info("Cleared {$clearedJobs} stuck jobs from strava-sync queue");
        }
        
        $this->info('✅ All clear!');
        return 0;
    }
    
    private function clearUserSync(User $user): void
    {
        Cache::forget("strava_sync_in_progress_{$user->id}");
        Cache::forget("strava_sync_processing_{$user->id}");
        Cache::forget("strava_sync_result_{$user->id}");
    }
}
