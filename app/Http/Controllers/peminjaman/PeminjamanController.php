<?php

namespace App\Http\Controllers\peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function viewPeminjaman(){
        if(request()->ajax()) {
           $borrowings = Loan::where('status', 'pending')->with(['book', 'member', 'staff'])
            ->select('loans.*');

        return DataTables::of($borrowings)
            ->addIndexColumn()
            ->addColumn('book_title', function($row) {
                return $row->book->title;
            })
            ->addColumn('member_name', function($row) {
                return $row->member->name;
            })
            ->addColumn('action', function($row) {
                return $row->id;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }

        return view('peminjaman.index', ['title' => 'viewPeminjaman']);
    }

     public function approveBorrowing($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'due_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
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
        $loan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Book loan rejected',
            'data' => $loan->load(['book', 'member', 'staff'])
        ], 200);
    }
}
