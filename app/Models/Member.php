<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $gurded = [];

    public function borrowings(){
        return $this->hasMany(Borrowing::class, 'member_id', 'id');
    }
}
