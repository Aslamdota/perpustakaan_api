<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'name'       => 'Jane Doe', // Sesuaikan dengan field migrasi
            'email'      => 'jane@example.com',
            'telepon'    => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
