<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\books\BooksController;
use App\Http\Controllers\books\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\member\MembersController;
use App\Http\Controllers\member\UsersController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\peminjaman\PeminjamanController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LoginController::class, 'viewLogin'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/dashboard', [LoginController::class, 'viewDashboard'])->name('dashboard.admin')->middleware('auth');

Route::get('/viewBuku', [BooksController::class, 'viewBooks'])->name('view.books')->middleware('auth');
Route::post('/storeBook', [BooksController::class, 'storeBook'])->name('store.books')->middleware('auth');

// Route::get('/peminjaman', [LoanController::class, 'index'])->name('loans.index');
Route::get('/viewPeminjaman', [PeminjamanController::class, 'viewPeminjaman'])->name('view.peminjaman')->middleware('auth');
Route::post('/borrowings/confirm/{id}', [PeminjamanController::class, 'confirm'])->name('borrowings.confirm')->middleware('auth');
Route::post('/borrowings/reject/{id}', [PeminjamanController::class, 'reject'])->name('borrowings.reject')->middleware('auth');

Route::get('/viewMembers', [MembersController::class, 'viewMembers'])->name('view.member')->middleware('auth');
Route::post('/storeMembers', [MembersController::class, 'storeMember'])->name('store.member')->middleware('auth');

Route::get('/viewUsers', [UsersController::class, 'viewUsers'])->name('view.user')->middleware('auth');
Route::post('/storeUsers', [UsersController::class, 'storeUsers'])->name('store.user')->middleware('auth');

Route::get('/viewCategory', [CategoryController::class, 'viewCategory'])->name('view.category')->middleware('auth');
Route::post('/addCategory', [CategoryController::class, 'addCategory'])->name('add.category')->middleware('auth');

