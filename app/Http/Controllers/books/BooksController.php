<?php

namespace App\Http\Controllers\books;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
// use App\Models\Category;

class BooksController extends Controller
{
    
    public function viewBooks(){
        $books = Book::with('category')->get();
        $categories = Category::latest()->get();
        return view('buku.index', ['title' => 'Buku Page'], compact('books', 'categories'));
    }

    public function storeBook(Request $request){
        // return $request;
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

        $file = NULL;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image')->store('books', 'public');
        }

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'publication_year' => $request->publication_year,
            'stock' => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'cover_image' => $file
        ]);

        $notification = array(
                'message' => 'Buku Berhasil ditambah',
                'alert-type' => 'success'
            );

        return redirect()->route('view.books')->with($notification);
    }

    public function editBook($id){
        $books = Book::findorFail($id);
        $categories = Category::latest()->get();
        return view('buku.edit', compact('books', 'categories'), ['title' => 'Edit Buku']);
    }

    public function updateBook(Request $request, $id){
        // return $request;
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'required|string|unique:books',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'stock' => 'required|integer|min:0',
            'description' => 'string',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $book = Book::findOrFail($id);
        $book->title = $request->title;
        $book->publisher = $request->publisher;
        $book->isbn = $request->isbn;
        $book->publication_year = $request->publication_year;
        $book->stock = $request->stock;
        $book->description = $request->description;
        $book->category_id = $request->category_id;

        if ($request->hasFile('cover_image')) {

            if ($book->cover_image && Storage::disk('public/' .$book->cover_image)) {
                Storage::delete('public/' .$book->cover_image);
            }

            $file = $request->file('cover_image')->store('books', 'public');
            $book->cover_image = $file;
        }

        $book->save();

         $notification = array(
                'message' => 'Buku Berhasil diedit',
                'alert-type' => 'success'
            );

        return redirect()->route('view.books')->with($notification);
    }

    public function destroyBook($id){
        $book = Book::findOrFail($id);

         // Hapus gambar jika ada
        if ($book->cover_image && Storage::exists('public/' . $book->cover_image)) {
            Storage::delete('public/' . $book->cover_image);
        }

        // Hapus data buku dari database
        $book->delete();

        $notif = array(
                'message' => 'Buku Berhasil dihapus',
                'alert-type' => 'success'
            );
        return redirect()->route('view.books')->with($notif);
    }


}
