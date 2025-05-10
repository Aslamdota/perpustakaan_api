<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Api\BorrowingController as ApiBorrowingController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReturnBook;
use Illuminate\Support\Facades\Route;


// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Book routes
    Route::apiResource('books', BookController::class);
    Route::get('/books/latest', [BookController::class, 'latestBooks']);
    Route::get('/books/search', [BookController::class, 'search']);
    Route::get('/books/category/{category}', [BookController::class, 'getByCategory']);
    Route::get('/recomendation/{memberId}', [BookController::class, 'getRecomendation']);
    Route::get('/bestSeller', [BookController::class, 'bestSeller']);

    // Category
    Route::apiResource('categories', CategoryController::class);

    // Member
    Route::apiResource('members', MemberController::class);
    Route::get('/members/search', [MemberController::class, 'search']);
    Route::get('/myProfile/{id}', [MemberController::class, 'myProfile']);

    // Borrowing
    Route::apiResource('borrowings', BorrowingController::class);
    Route::get('/borrowings/overdue', [BorrowingController::class, 'getOverdue']);

    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::put('/borrowings/{id}/approve', [BorrowingController::class, 'approve']);
    Route::put('/borrowings/{id}/reject', [BorrowingController::class, 'reject']);
    Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnBook']);

    // Loan
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'loanBook']);
    Route::post('/loans/{id}', [LoanController::class, 'approveBorrowing']);
    Route::post('/loans/rejected/{id}', [LoanController::class, 'rejectedBorrowing']);
    Route::put('/loans/{id}/status', [LoanController::class, 'updateStatus']);
    Route::get('/getBorrowing', [LoanController::class, 'getBorrowing']);

    // Return
    Route::put('/returns/{borrowing}', [ReturnBook::class, 'returnBook']);
});
