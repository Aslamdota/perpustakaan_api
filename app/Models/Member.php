<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members'; // pastikan nama tabel sesuai
    // protected $fillable = ['nama', 'alamat', 'no_hp']; // ganti sesuai kolom
    protected $guarded = [];

}

