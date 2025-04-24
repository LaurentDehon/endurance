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
            ['name' => 'Reduced', 'color' => 'bg-emerald-400'], // Ambre plus visible et distinct
            ['name' => 'Recovery', 'color' => 'bg-pink-600'], // Émeraude plus vif et différent du bleu
            ['name' => 'Development', 'color' => 'bg-blue-600'], // Bleu un peu plus clair pour meilleure distinction
            ['name' => 'Maintain', 'color' => 'bg-amber-700'], // Indigo pour un bon contraste avec le bleu
            ['name' => 'Taper', 'color' => 'bg-fuchsia-600'], // Fuchsia plus vif
            ['name' => 'Race', 'color' => 'bg-rose-600'], // Rose plus vif au lieu de rouge
        ];

        foreach ($types as $type) {
            WeekType::create($type);
        }
    }
}
