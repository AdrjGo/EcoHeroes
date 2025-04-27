<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Americo Julio',
            'email' => 'americo@example.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
        ]);

        User::create([
            'name' => 'Maria Lopez',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'phone' => '987654321',
        ]);
    }
}