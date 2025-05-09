<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnBook;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Book routes
    Route::apiResource('books', BookController::class);
    Route::get('/books/search', [BookController::class, 'search']);
    Route::get('/books/category/{category}', [BookController::class, 'getByCategory']);
    Route::get('/recomendation/{memberId}', [BookController::class, 'getRecomendation']);
    
    // Category routes
    Route::apiResource('categories', CategoryController::class);
    
    // Member routes
    Route::apiResource('members', MemberController::class);

    

    Route::get('/members/search', [MemberController::class, 'search']);
    Route::get('/myProfile/{id}', [MemberController::class, 'myProfile']);
    

    // Borrowing routes
    Route::apiResource('borrowings', BorrowingController::class);

    Route::post('/loans', [LoanController::class, 'loanBook']);
    Route::put('/returns/{borrowing}', [ReturnBook::class, 'returnBook']);
    Route::get('/borrowings/overdue', [BorrowingController::class, 'getOverdue']);
});