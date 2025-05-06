<?php

namespace App\Http\Controllers\books;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function viewCategory(){
        return view('category.index', ['title' => 'Page Category']);
    }
}
