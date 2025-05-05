<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->string('isbn');
            $table->year('publication_year');
            $table->integer('stock');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

            // Tambahkan ini jika ada relasi ke tabel kategori
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
