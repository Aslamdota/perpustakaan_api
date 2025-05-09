<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReturnBook extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status === 'returned') {
            return response()->json([
                'status' => 'error',
                'message' => 'This book has already been returned'
            ], 400);
        }

        $today = Carbon::today();
        
        $borrowing->return_date = $today;
        
        // Calculate fine if returned late
        if ($today->isAfter($borrowing->due_date)) {
            $daysLate = $today->diffInDays($borrowing->due_date);
            $finePerDay = 1000; // Rp 1.000 per day
            $borrowing->fine = $daysLate * $finePerDay;
            $borrowing->status = 'overdue';
        } else {
            $borrowing->status = 'returned';
        }
        
        $borrowing->save();

        // Increase book stock
        $book = Book::find($borrowing->book_id);
        $book->stock += 1;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
            'data' => $borrowing->load(['book', 'member', 'staff'])
        ]);
    }
}
