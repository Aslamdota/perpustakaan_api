<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['book', 'member'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $loans
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'loan_date' => 'required|date',
        ]);

        $loan = Loan::create([
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'loan_date' => $request->loan_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Loan created successfully',
            'data' => $loan,
        ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function loanBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'borrow_date' => 'required|date|date_format:Y-m-d',
            // 'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:borrow_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        // Check if book is available (stock > 0)
        $book = Book::find($request->book_id);
        if ($book->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book is not available for borrowing'
            ], 400);
        }

        // $member = auth()->user()->member_id;

        // Create new borrowing record
        $borrowing = new Borrowing();
        $borrowing->book_id = $request->book_id;
        $borrowing->member_id = $request->member_id;
        $borrowing->borrow_date = $request->borrow_date;
        // $borrowing->due_date = $request->due_date;
        $borrowing->status = 'pending';
        $borrowing->staff_id = auth()->id(); // Current authenticated user (staff/admin)
        $borrowing->save();

        // Decrease book stock
        // $book->stock -= 1;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book borrowed successfully',
            'data' => $borrowing->load(['book', 'member', 'staff'])
        ], 201);
    }

    public function approveBorrowing($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'noted' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $borrowing = Borrowing::find($id);
        if (!$borrowing || $borrowing->status != 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid borrowing request or already processed'
            ], 404);
        }

        // Approve borrowing
        $borrowing->status = 'borrowed';
        $borrowing->due_date = $request->due_date;
        $borrowing->noted = $request->noted;
        $borrowing->save();

        // Decrease book stock
        $book = $borrowing->book;
        $book->stock -= 1;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book borrowing borrowed',
            'data' => $borrowing->load(['book', 'member', 'staff'])
        ], 200);
    }

}
