<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckStatsUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strava:check-stats {user_id? : ID of user to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if month and year stats are properly updated after sync';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if (!$userId) {
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
        
        $this->info("Checking stats for user: {$user->name} (ID: {$user->id})");
        
        $year = now()->year;
        $cacheKeySuffix = $user->id . '-' . $year;
        
        // Vérifier si les caches de stats existent
        $monthStatsKey = 'calendar-month-stats-' . $cacheKeySuffix;
        $yearStatsKey = 'calendar-year-stats-' . $cacheKeySuffix;
        
        $this->info("Checking cache keys:");
        $this->info("Month stats key: {$monthStatsKey}");
        $this->info("Year stats key: {$yearStatsKey}");
        
        $monthStats = Cache::get($monthStatsKey);
        $yearStats = Cache::get($yearStatsKey);
        
        if ($monthStats) {
            $this->info("✅ Month stats cache EXISTS");
            $this->info("Sample month data (January):");
            $janKey = sprintf('%04d-%02d', $year, 1);
            if (isset($monthStats[$janKey])) {
                $jan = $monthStats[$janKey];
                $this->info("  Distance: {$jan['actual']['distance']}km");
                $this->info("  Elevation: {$jan['actual']['elevation']}m");
                $this->info("  Duration: {$jan['actual']['duration']}s");
            }
        } else {
            $this->warn("❌ Month stats cache is EMPTY");
        }
        
        if ($yearStats) {
            $this->info("✅ Year stats cache EXISTS");
            $this->info("Year {$year} stats:");
            $this->info("  Distance: {$yearStats['actual']['distance']}km");
            $this->info("  Elevation: {$yearStats['actual']['elevation']}m");
            $this->info("  Duration: {$yearStats['actual']['duration']}s");
        } else {
            $this->warn("❌ Year stats cache is EMPTY");
        }
        
        // Vérifier les données directement en DB
        $this->info("\nChecking database directly:");
        $totalActivities = \App\Models\Activity::where('user_id', $user->id)
            ->whereYear('start_date', $year)
            ->count();
            
        $totalDistance = \App\Models\Activity::where('user_id', $user->id)
            ->whereYear('start_date', $year)
            ->sum('distance');
            
        $this->info("Total activities in DB for {$year}: {$totalActivities}");
        $this->info("Total distance in DB for {$year}: " . round($totalDistance / 1000, 1) . "km");
        
        return 0;
    }
}
