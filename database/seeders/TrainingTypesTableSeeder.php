<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('training_types')->insert([
            [
                'name' => 'Easy Run',
                'color' => 'bg-blue-500', // Bleu
                'icon' => 'fas fa-running', // Icône de course
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intervals',
                'color' => 'bg-red-500', // Rouge
                'icon' => 'fas fa-people-arrows', // Icône de flèches (fractionné)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Long Run',
                'color' => 'bg-green-500', // Vert
                'icon' => 'fas fa-road', // Icône de route
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
    }
}
