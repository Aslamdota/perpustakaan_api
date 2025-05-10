<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $guarded = [];

    
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function book(){
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }

}
