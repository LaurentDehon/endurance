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
    protected $signature = 'strava:clear-sync {user_id? : ID of specific user to clear, or all users if not specified} {--diagnose : Show diagnostic information} {--force-restart : Force restart queue workers}';

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
        if ($this->option('diagnose')) {
            $this->diagnose();
            return 0;
        }
        
        if ($this->option('force-restart')) {
            $this->forceRestart();
            return 0;
        }

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
    
    private function diagnose(): void
    {
        $this->info('🔍 Strava Sync Diagnostic Report');
        $this->line('================================');
        
        // Check jobs
        $jobs = DB::table('jobs')->where('queue', 'strava-sync')->get();
        $this->line("Active jobs in strava-sync queue: " . $jobs->count());
        
        foreach ($jobs as $job) {
            $this->line("Job ID: {$job->id}, Created: " . date('Y-m-d H:i:s', $job->created_at));
            $this->line("Available at: " . date('Y-m-d H:i:s', $job->available_at));
            $this->line("Attempts: {$job->attempts}");
            $this->line("---");
        }
        
        // Check failed jobs
        $failedJobs = DB::table('failed_jobs')->where('queue', 'strava-sync')->count();
        $this->line("Failed jobs: {$failedJobs}");
        
        // Check currently syncing users
        $users = User::whereNotNull('strava_token')->get();
        $activeUsers = 0;
        foreach ($users as $user) {
            $inProgress = Cache::has("strava_sync_in_progress_{$user->id}");
            $processing = Cache::has("strava_sync_processing_{$user->id}");
            if ($inProgress || $processing) {
                $this->line("User {$user->id} ({$user->name}): " . 
                          ($inProgress ? 'IN_PROGRESS ' : '') . 
                          ($processing ? 'PROCESSING' : ''));
                $activeUsers++;
            }
        }
        
        if ($activeUsers === 0) {
            $this->line("No users currently syncing");
        }
        
        // Check queue workers processes
        $this->line("\n🔧 Queue Worker Processes:");
        exec('ps aux | grep "queue:work"', $output);
        $workers = array_filter($output, function($line) {
            return strpos($line, 'php') !== false && strpos($line, 'queue:work') !== false;
        });
        
        if (empty($workers)) {
            $this->error("❌ No queue workers running!");
        } else {
            foreach ($workers as $worker) {
                $this->line($worker);
            }
        }
        
        // Check supervisor status
        $this->line("\n🔧 Supervisor Status:");
        exec('sudo supervisorctl status 2>/dev/null', $output2, $returnCode);
        if ($returnCode === 0) {
            foreach ($output2 as $line) {
                $this->line($line);
            }
        } else {
            $this->error("Cannot check supervisor status");
        }
    }
    
    private function forceRestart(): void
    {
        $this->info('🔄 Force restarting queue workers...');
        
        // Clear all sync caches
        $users = User::all();
        foreach ($users as $user) {
            $this->clearUserSync($user);
        }
        
        // Clear all jobs
        $clearedJobs = DB::table('jobs')->where('queue', 'strava-sync')->delete();
        $this->info("Cleared {$clearedJobs} jobs");
        
        // Clear failed jobs
        $clearedFailedJobs = DB::table('failed_jobs')->where('queue', 'strava-sync')->delete();
        $this->info("Cleared {$clearedFailedJobs} failed jobs");
        
        // Kill existing queue workers
        exec('pkill -f "queue:work"', $output, $returnCode);
        $this->info("Killed existing queue workers");
        
        // Restart queue workers
        $this->call('queue:restart');
        
        // Try to restart supervisor if possible
        exec('sudo supervisorctl restart all 2>/dev/null', $output, $returnCode);
        if ($returnCode === 0) {
            $this->info('✅ Supervisor restarted');
        } else {
            $this->warn('⚠️  Could not restart supervisor');
        }
        
        $this->info('✅ Force restart completed');
    }
    
    private function clearUserSync(User $user): void
    {
        Cache::forget("strava_sync_in_progress_{$user->id}");
        Cache::forget("strava_sync_processing_{$user->id}");
        Cache::forget("strava_sync_result_{$user->id}");
    }
}