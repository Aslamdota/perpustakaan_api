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
            'name'       => 'Jane Doe',
            'member_id'       => 'MB09812',
            'email'      => 'jane@example.com',
            'phone'    => '081234567890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Member::create([
            'name'       => 'Jane Smith', // Sesuaikan dengan field migrasi
            'member_id'       => 'MB0ei',
            'email'      => 'janesmith@example.com',
            'phone'    => '084985746',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
