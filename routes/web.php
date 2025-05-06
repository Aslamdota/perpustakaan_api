<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\books\BooksController;
use App\Http\Controllers\books\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'viewLogin'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/dashboard', [LoginController::class, 'viewDashboard'])->name('dashboard.admin')->middleware('auth');

Route::get('/viewBuku', [BooksController::class, 'viewBooks'])->name('view.books')->middleware('auth');
Route::get('/viewCategory', [CategoryController::class, 'viewCategory'])->name('view.category')->middleware('auth');

