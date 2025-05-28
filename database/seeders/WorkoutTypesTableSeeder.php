<?php

namespace Database\Seeders;

use App\Models\WorkoutType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('workout_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $types = [
            ['name' => 'easy_run', 'color' => 'bg-blue-500', 'icon' => 'fas fa-walking'],
            ['name' => 'long_run', 'color' => 'bg-green-500', 'icon' => 'fas fa-route'],
            ['name' => 'recovery_run', 'color' => 'bg-stone-500', 'icon' => 'fas fa-heart-pulse'],
            ['name' => 'fartlek', 'color' => 'bg-pink-500', 'icon' => 'fas fa-shuffle'],
            ['name' => 'tempo_run', 'color' => 'bg-slate-800', 'icon' => 'fas fa-gauge-high'],
            ['name' => 'hill_repeats', 'color' => 'bg-purple-500', 'icon' => 'fas fa-chart-line'],
            ['name' => 'intervals', 'color' => 'bg-red-500', 'icon' => 'fas fa-stopwatch'],
            ['name' => 'back_to_back', 'color' => 'bg-cyan-600', 'icon' => 'fas fa-arrows-rotate'],
            ['name' => 'race', 'color' => 'bg-red-700', 'icon' => 'fas fa-flag-checkered'],
        ];

        foreach ($types as $type) {
            WorkoutType::create($type);
        }        
    }
}
