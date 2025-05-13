<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\books\BooksController;
use App\Http\Controllers\books\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\member\MembersController;
use App\Http\Controllers\member\UsersController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FineMasterController;
use App\Http\Controllers\peminjaman\PeminjamanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
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
Route::post('/borrowings/confirm/{id}', [PeminjamanController::class, 'approveBorrowing'])->name('borrowings.confirm')->middleware('auth');
Route::post('/borrowings/reject/{id}', [PeminjamanController::class, 'rejectedBorrowing'])->name('borrowings.reject')->middleware('auth');

Route::get('/viewMembers', [MembersController::class, 'viewMembers'])->name('view.member')->middleware('auth');
Route::post('/storeMembers', [MembersController::class, 'storeMember'])->name('store.member')->middleware('auth');

Route::get('/viewUsers', [UsersController::class, 'viewUsers'])->name('view.user')->middleware('auth');
Route::post('/storeUsers', [UsersController::class, 'storeUsers'])->name('store.user')->middleware('auth');

Route::get('/viewCategory', [CategoryController::class, 'viewCategory'])->name('view.category')->middleware('auth');
Route::post('/addCategory', [CategoryController::class, 'addCategory'])->name('add.category')->middleware('auth');


// Returns routes
Route::get('/loans/{loan}', [PeminjamanController::class, 'show'])->name('loans.show');

Route::get('/returns', [PeminjamanController::class, 'returnsIndex'])->name('returns.index');
Route::get('/returnsHistory', [PeminjamanController::class, 'returnsHistory'])->name('returns.history');

Route::get('/returns/data', [PeminjamanController::class, 'getLoansForReturn'])->name('returns.data');
Route::get('/returns/data_pending', [PeminjamanController::class, 'getLoansForReturnPending'])->name('returns.pending_data');
Route::get('/returns/data_history', [PeminjamanController::class, 'getLoansForReturnHistory'])->name('returns.history_data');

Route::post('/loans/{loan}/return', [PeminjamanController::class, 'returnBook'])->name('loans.return');

// Fine settings routes
Route::get('/fine-settings', [FineMasterController::class, 'getFineSettings'])->name('fine.get');
Route::post('/fine-settings', [FineMasterController::class, 'updateFineSettings'])->name('fine.update');

Route::apiResource('books', BookController::class);
Route::apiResource('members', MemberController::class);