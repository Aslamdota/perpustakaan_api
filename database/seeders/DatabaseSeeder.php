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
<<<<<<< HEAD
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

=======
>>>>>>> 43f8e04b7eed5342d1d278114a152baea46a19a4
        $this->call([
            CategorySeeder::class, // Jalankan CategorySeeder terlebih dahulu
            MemberSeeder::class,
            BookSeeder::class,
            UserSeeder::class,
        ]);
    }
}