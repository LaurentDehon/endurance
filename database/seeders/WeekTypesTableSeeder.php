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
            ['name' => 'reduced', 'color' => 'bg-emerald-400'],
            ['name' => 'recovery', 'color' => 'bg-pink-600'],
            ['name' => 'development', 'color' => 'bg-blue-600'],
            ['name' => 'maintain', 'color' => 'bg-amber-700'],
            ['name' => 'taper', 'color' => 'bg-fuchsia-600'],
            ['name' => 'race', 'color' => 'bg-rose-600'],
        ];

        foreach ($types as $type) {
            WeekType::create($type);
        }
    }
}
