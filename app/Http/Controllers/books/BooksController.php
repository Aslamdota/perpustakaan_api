<?php

namespace App\Http\Controllers\books;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function viewBooks(){
        return view('buku.index', ['title' => 'Buku Page']);
    }
}
