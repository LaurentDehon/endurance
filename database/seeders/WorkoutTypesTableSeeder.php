<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('workout_types')->insert([
            [
                'name' => 'Easy Run',
                'color' => 'bg-blue-500',
                'icon' => 'fas fa-running',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intervals',
                'color' => 'bg-red-500',
                'icon' => 'fas fa-people-arrows',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Long Run',
                'color' => 'bg-green-500',
                'icon' => 'fas fa-road',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recovery Run',
                'color' => 'bg-teal-500',
                'icon' => 'fas fa-walking',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fartlek',
                'color' => 'bg-pink-500',
                'icon' => 'fas fa-random',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tempo Run',
                'color' => 'bg-yellow-500',
                'icon' => 'fas fa-tachometer-alt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hill Repeats',
                'color' => 'bg-purple-500',
                'icon' => 'fas fa-mountain',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Race',
                'color' => 'bg-indigo-500',
                'icon' => 'fas fa-trophy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
    }
}
