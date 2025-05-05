<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run()
    {
        Book::create([
            'title' => 'Laravel for Beginners',
            'author' => 'John Doe',
            'publisher' => 'Tech Books',
            'isbn' => '1234567890',
            'publication_year' => 2023,
            'stock' => 10,
            'description' => 'A beginner-friendly guide to Laravel.',
            'category_id' => 1,
        ]);
    }
}