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
            ['name' => 'Easy Run', 'color' => 'bg-blue-500', 'icon' => 'fas fa-running', 'short' => 'E'],
            ['name' => 'Long Run', 'color' => 'bg-green-500', 'icon' => 'fas fa-road', 'short' => 'L'],
            ['name' => 'Recovery Run', 'color' => 'bg-stone-500', 'icon' => 'fas fa-walking', 'short' => 'R'],
            ['name' => 'Fartlek', 'color' => 'bg-pink-500', 'icon' => 'fas fa-random', 'short' => 'F'],
            ['name' => 'Tempo Run', 'color' => 'bg-slate-800', 'icon' => 'fas fa-tachometer-alt', 'short' => 'T'],
            ['name' => 'Hill Repeats', 'color' => 'bg-purple-500', 'icon' => 'fas fa-mountain', 'short' => 'H'],
            ['name' => 'Intervals', 'color' => 'bg-red-500', 'icon' => 'fas fa-people-arrows',  'short' => 'I'],
            ['name' => 'Back to Back', 'color' => 'bg-cyan-600', 'icon' => 'fas fa-bolt', 'short' => 'B'],
            ['name' => 'Race', 'color' => 'bg-red-700', 'icon' => 'fas fa-trophy', 'short' => 'R'],
        ];

        foreach ($types as $type) {
            WorkoutType::create($type);
        }        
    }
}
