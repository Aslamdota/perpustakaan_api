<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::get();
        return response()->json($category, 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cek_category = Category::where('code', $request->code);

        if($cek_category->count() > 0 ){
            return response()->json(['status' => 2, 'message' => 'kode sudah tersedia'], 201);
        } 

        $category = New Category;
        $category->name = $request->name;
        $category->code = $request->code;
        $category->save();

        if($category)
        {
            return response()->json(['status' => 1, 'message' => 'tambah berhasil'], 201);
        } else {
            return response()->json(['status' => 2, 'message' => 'tambah data gagal'], 203);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
       return response()->json($category, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
