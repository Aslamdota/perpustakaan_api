<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\due_date_master;
use App\Models\Finemaster;


class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil data loans dengan relasi book dan member
        $loans = Loan::with(['book', 'member'])->get();

        // Debugging
        // dd($loans);

        // Kirim data ke view
        return view('peminjaman.index', ['title' => 'Peminjaman'], compact('loans'));
    }

    public function updateStatus(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved',
        ]);

        $loan->status = $validated['status'];
        $loan->save();

        return response()->json(['success' => true, 'data' => $loan]);
    }

    public function getBorrowing()
    {
        $borrowing = Borrowing::where('status', 'pending')->with(['book', 'member', 'staff'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $borrowing
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $loan = Loan::create([
            'book_id' => $request->book_id,
            'user_id' => auth()->id(),
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Loan request created',
            'loan' => $loan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->status = $request->status; // 'Approved' atau 'Rejected'
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => 'Loan updated',
            'loan' => $loan
        ]);
    }


    public function loanBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'loan_date' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $now = Carbon::now();

        $book = Book::find($request->book_id);
        if ($book->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book is not available for borrowing'
            ], 400);
        }

        
        $loanValidation = Loan::whereMonth('loan_date', $now->month)
        ->whereYear('loan_date', $now->year)
        ->where('book_id', $request->book_id)
        ->where('member_id', $request->member_id)
        ->first();

        if (!$loanValidation || in_array($loanValidation->status, ['returned', 'rejected'])) {

        $duedate = due_date_master::where('status', 'active')
        ->orderBy('due_date', 'desc')
        ->first();

        if ($duedate && $now < $duedate) {
            $loan = new Loan();
            $loan->book_id = $request->book_id;
            $loan->member_id = $request->member_id;
            $loan->loan_date = $duedate->due_date;
            $loan->jumlah = 1;
            $loan->status = 'pending';
            $loan->staff_id = auth()->id();
            $loan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Book loan request created',
                'data' => $loan->load(['book', 'member', 'staff'])
            ], 201);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Loan request failed. Due date has passed.',
            ], 422);
        }

        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already borrowed this book this month.',
            ], 409);
        }
    }

    public function approveBorrowing($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $loan = Loan::find($id);
        if (!$loan || $loan->status != 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid loan request or already processed'
            ], 404);
        }

        $loan->status = 'borrowed';
        $loan->due_date = $request->due_date;
        $loan->save();

        $book = $loan->book;
        $book->stock -= 1;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book loan approved',
            'data' => $loan->load(['book', 'member', 'staff'])
        ], 200);
    }

    public function rejectedBorrowing($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noted' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $loan = Loan::find($id);
        if (!$loan || $loan->status != 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid loan request or already processed'
            ], 404);
        }

        $loan->status = 'rejected';
        $loan->noted = $request->noted;
        $loan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book loan rejected',
            'data' => $loan->load(['book', 'member', 'staff'])
        ], 200);
    }
}
