<?php

namespace Database\Seeders;

use App\Models\WeekType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeekTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('week_types')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $types = [
            [
                'name' => 'development', 
                'color' => 'bg-blue-600',
                'icon' => 'fa-arrow-trend-up',
                'description' => 'A high-load week designed to improve endurance, speed, or strength'
            ],
            [
                'name' => 'maintain', 
                'color' => 'bg-amber-700',
                'icon' => 'fa-equals',
                'description' => 'A balanced week that maintains fitness without excessive stress'
            ],
            [
                'name' => 'reduced', 
                'color' => 'bg-emerald-400',
                'icon' => 'fa-arrow-down',
                'description' => 'A week with reduced volume to prevent burnout and allow adaptation'
            ],
            [
                'name' => 'recovery', 
                'color' => 'bg-pink-600',
                'icon' => 'fa-heart-pulse',
                'description' => 'A week focused on recovery after an intense training block'
            ],
            [
                'name' => 'taper', 
                'color' => 'bg-fuchsia-600',
                'icon' => 'fa-minimize',
                'description' => 'A progressive reduction in workout volume before a race'
            ],
            [
                'name' => 'race', 
                'color' => 'bg-rose-600',
                'icon' => 'fa-flag-checkered',
                'description' => 'Competition week including the race and recovery'
            ],
        ];

        foreach ($types as $type) {
            WeekType::create($type);
        }
    }
}
