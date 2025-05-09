<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, // Jalankan CategorySeeder terlebih dahulu
            MemberSeeder::class,
            BookSeeder::class,
        ]);
    }
}