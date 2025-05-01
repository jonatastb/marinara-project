<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Jonatas Bueno',
            'email' => 'jonatas.bueno@outlook.com',
            'password' => bcrypt('123456'),
        ]);
        User::create([
            'name' => 'Marinara Bueno',
            'email' => 'Marinara.Bueno@rumolog.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
