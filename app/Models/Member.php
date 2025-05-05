<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'nama', 'email', 'telepon'
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}