<?php

namespace App\Http\Controllers\books;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
// use App\Models\Category;

class BooksController extends Controller
{
    
    public function viewBooks(){
        $books = Book::with('category')->get();
        return view('buku.index', ['title' => 'Buku Page'], compact('books'));
    }

    public function storeBook(Request $request){
        return $request;
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'required|string|unique:books',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'publication_year' => $request->publication_year,
            'stock' => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        $notification = array(
                'message' => 'Buku Berhasil ditambah',
                'alert-type' => 'success'
            );

        return redirect()->route('view.books')->with($notification);
    }


}
