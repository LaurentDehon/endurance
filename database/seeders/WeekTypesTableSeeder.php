<?php

namespace Database\Seeders;

use App\Models\WeekType;
use Illuminate\Database\Seeder;

class WeekTypesTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Reduced', 'color' => 'bg-cyan-800'],
            ['name' => 'Recovery', 'color' => 'bg-green-800'],
            ['name' => 'Development', 'color' => 'bg-blue-800'],
            ['name' => 'Maintain', 'color' => 'bg-amber-800'],
            ['name' => 'Taper', 'color' => 'bg-purple-800'],
            ['name' => 'Race', 'color' => 'bg-red-800'],
        ];

        foreach ($types as $type) {
            WeekType::create($type);
        }
    }
}
