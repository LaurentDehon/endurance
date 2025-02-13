<?php

namespace Database\Seeders;

use App\Models\WeekType;
use Illuminate\Database\Seeder;

class WeekTypesTableSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Reduced', 'color' => 'cyan-800'],
            ['name' => 'Recovery', 'color' => 'green-800'],
            ['name' => 'Development', 'color' => 'blue-800'],
            ['name' => 'Maintain', 'color' => 'amber-800'],
            ['name' => 'Taper', 'color' => 'purple-800'],
            ['name' => 'Race', 'color' => 'red-800'],
        ];

        foreach ($types as $type) {
            WeekType::create($type);
        }
    }
}
