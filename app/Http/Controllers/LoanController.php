<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\due_date_master;
use App\Models\Member;

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
        $borrowings = Loan::where('status', 'pending')->with(['book', 'member', 'staff'])->get();

        $borrowings = $borrowings->map(function ($loan) {
            $loan->book_title = $loan->book->title ?? null;
            return $loan;
        });

        return response()->json([
            'status' => 'success',
            'data' => $borrowings
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
            'member_id' => 'required|exists:members,member_id',
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

        // Check if member already has an active loan for this book
        $existingLoan = Loan::where('book_id', $request->book_id)
            ->where('member_id', Member::where('member_id', $request->member_id)->first()->id)
            ->whereNotIn('status', ['returned', 'rejected'])
            ->first();

        if ($existingLoan) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already borrowed this book and it has not been returned yet.',
            ], 409);
        }

        // Get active due date settings
        $dueDateSetting = DueDateMaster::where('status', 'active')->first();

        if (!$dueDateSetting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Due date configuration not found'
            ], 400);
        }

        $member = Member::where('member_id', $request->member_id)->first();
        $randomStaff = User::whereIn('role', ['admin', 'karyawan'])->inRandomOrder()->first();

        try {
            $loan = new Loan();
            $loan->book_id = $request->book_id;
            $loan->member_id = $member->id;
            $loan->loan_date = $now; // Set loan date to current time
            $loan->due_date = Carbon::parse($dueDateSetting->due_date); // Set due date from settings
            $loan->jumlah = 1;
            $loan->status = 'pending';
            $loan->staff_id = $randomStaff ? $randomStaff->id : null;
            $loan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Book loan request created successfully',
                'data' => $loan->load(['book', 'member', 'staff'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create loan: ' . $e->getMessage()
            ], 500);
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
