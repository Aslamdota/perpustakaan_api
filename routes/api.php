<?php

use App\Http\Controllers\Api\AuthController as APIAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/login', [APIAuthController::class, 'login'])->name('login');
Route::post('/logout', [APIAuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Book routes
    Route::apiResource('books', BookController::class);
    Route::get('/books/search', [BookController::class, 'search']);
    Route::get('/books/category/{category}', [BookController::class, 'getByCategory']);
    
    // Category routes
    Route::apiResource('categories', CategoryController::class);
    
    // Member routes
    Route::apiResource('members', MemberController::class);
    Route::get('/members/search', [MemberController::class, 'search']);
    
    // Borrowing routes
    Route::apiResource('borrowings', BorrowingController::class);
    Route::put('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook']);
    Route::get('/borrowings/overdue', [BorrowingController::class, 'getOverdue']);
});