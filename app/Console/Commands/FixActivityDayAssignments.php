<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Day;
use Illuminate\Console\Command;

class FixActivityDayAssignments extends Command
{
    protected $signature = 'strava:fix-day-assignments';
    protected $description = 'Fix day_id assignments for existing activities';

    public function handle()
    {
        $this->info('Starting to fix activity day assignments...');
        
        $activities = Activity::whereNull('day_id')
            ->whereNotNull('start_date')
            ->get();
            
        $this->info("Found {$activities->count()} activities without day_id");
        
        $updated = 0;
        $bar = $this->output->createProgressBar($activities->count());
        
        foreach ($activities as $activity) {
            try {
                $day = Day::findByDateOrCreate($activity->start_date);
                $activity->day_id = $day->id;
                $activity->save();
                $updated++;
            } catch (\Exception $e) {
                $this->error("Failed to update activity {$activity->id}: " . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully updated {$updated} activities with day_id assignments");
        
        return 0;
    }
}
