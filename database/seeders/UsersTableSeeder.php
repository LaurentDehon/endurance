<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email' => 'info@zone2.be',
                'email_verified_at' => now(),
                'password' => Hash::make('3301'),
                'is_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'laurent',
                'email' => 'laurent.dehon@gmail.com',
                'email_verified_at' => null,
                'password' => Hash::make('3301'),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
