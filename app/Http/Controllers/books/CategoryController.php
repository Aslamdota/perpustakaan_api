<?php

namespace App\Http\Controllers\books;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function viewCategory(){
        $categories = Category::latest()->get();
        return view('category.index', ['title' => 'Page Category'], compact('categories'));
    }

    public function addCategory(Request $request){
       $request->validate([
            'name' => 'required',
            'code' => 'required|unique:categories,code,'
        ]);

        Category::create([
           'name' => $request->name,
           'code' => $request->code,
        ]);

        return redirect()->route('view.category');
    }
}
