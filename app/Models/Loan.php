<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
