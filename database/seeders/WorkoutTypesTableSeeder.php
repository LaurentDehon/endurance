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
            ['name' => 'easy_run', 'color' => 'bg-blue-500', 'icon' => 'fas fa-running', 'short' => 'E'],
            ['name' => 'long_run', 'color' => 'bg-green-500', 'icon' => 'fas fa-road', 'short' => 'L'],
            ['name' => 'recovery_run', 'color' => 'bg-stone-500', 'icon' => 'fas fa-walking', 'short' => 'R'],
            ['name' => 'fartlek', 'color' => 'bg-pink-500', 'icon' => 'fas fa-random', 'short' => 'F'],
            ['name' => 'tempo_run', 'color' => 'bg-slate-800', 'icon' => 'fas fa-tachometer-alt', 'short' => 'T'],
            ['name' => 'hill_repeats', 'color' => 'bg-purple-500', 'icon' => 'fas fa-mountain', 'short' => 'H'],
            ['name' => 'intervals', 'color' => 'bg-red-500', 'icon' => 'fas fa-people-arrows',  'short' => 'I'],
            ['name' => 'back_to_back', 'color' => 'bg-cyan-600', 'icon' => 'fas fa-bolt', 'short' => 'B'],
            ['name' => 'race', 'color' => 'bg-red-700', 'icon' => 'fas fa-trophy', 'short' => 'R'],
        ];

        foreach ($types as $type) {
            WorkoutType::create($type);
        }        
    }
}
