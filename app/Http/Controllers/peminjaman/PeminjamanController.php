<?php

namespace App\Http\Controllers\peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Loan;
use Illuminate\Support\Facades\Validator;
use App\Models\Finemaster;
use Carbon\Carbon;
use App\Models\Book;
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

    public function returnsIndex()
    {
        return view('pengembalian.returns',  ['title' => 'viewPengembalian']);
    }

    public function returnsHistory()
    {
        return view('pengembalian.history_pengembalian',  ['title' => 'viewPengembalianHistory']);
    }
    

    public function show(Loan $loan)
    {
        // Load the relationships
        $loan->load(['book', 'member']);
        
        return response()->json([
            'book' => [
                'title' => $loan->book->title,
                'code' => $loan->book->code,
                'isbn' => $loan->book->isbn,
                'author' => $loan->book->author
            ],
            'member' => [
                'name' => $loan->member->name,
                'member_id' => $loan->member->member_id,
                'email' => $loan->member->email
            ],
            'loan_date' => $loan->loan_date,
            'due_date' => $loan->due_date,
            'return_date' => $loan->return_date,
            'status' => $loan->status,
            'fine' => $loan->fine,
            'notes' => $loan->noted,
            'id' => $loan->id // Note the field name difference (noted vs notes)
        ]);
    }

    public function returnBook(Request $request, Loan $loan)
    {
        $request->validate([
            'return_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        if ($loan->status === 'returned') {
            return response()->json([
                'status' => 'error',
                'message' => 'Buku ini sudah dikembalikan sebelumnya'
            ], 400);
        }

        $returnDate = Carbon::parse($request->return_date);
        $dueDate = Carbon::parse($loan->due_date);
        
        $loan->return_date = $returnDate;
        $loan->noted = $request->notes;
        
        // Get fine settings
        $fineMaster = FineMaster::where('status', 'active')->first();
        
        if ($returnDate->gt($dueDate)) {
            $daysLate = $returnDate->diffInDays($dueDate);
            $loan->fine = $daysLate * $fineMaster->fine_amount;
            $loan->status = 'overdue';
        } else {
            $loan->fine = 0;
            $loan->status = 'returned';
        }
        
        $loan->save();

        // Increase book stock
        $book = Book::find($loan->book_id);
        $book->stock += $loan->jumlah;
        $book->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Pengembalian buku berhasil dicatat',
            'data' => $loan
        ]);
    }

    public function getLoansForReturn()
    {
        $loans = Loan::with(['book', 'member'])
            ->whereIn('status', ['returned', 'overdue'])
            ->get();

        return datatables()->of($loans)
            ->addIndexColumn()
            ->addColumn('action', function($loan) {
                return '<button class="btn btn-sm btn-primary process-return" data-id="'.$loan->id.'">Proses</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getLoansForReturnHistory(Request $request)
    {
        $query = Loan::with(['book', 'member'])
            ->where('status', '!=', 'pending');

        // Date range filter
        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' - ', $request->date_range);
            $startDate = Carbon::parse($dates[0])->startOfDay();
            $endDate = Carbon::parse($dates[1])->endOfDay();
            
            $query->whereBetween('loan_date', [$startDate, $endDate]);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('book', function($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                      ->orWhere('code', 'like', '%'.$search.'%');
                })
                ->orWhereHas('member', function($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                      ->orWhere('member_id', 'like', '%'.$search.'%');
                });
            });
        }

        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function($loan) {
                return '<button class="btn btn-sm btn-outline-secondary view-detail" data-id="'.$loan->id.'">
                    <i class="bx bx-show"></i>
                </button>';
            })
            ->editColumn('loan_date', function($loan) {
                return $loan->loan_date ? Carbon::parse($loan->loan_date)->format('Y-m-d') : null;
            })
            ->editColumn('due_date', function($loan) {
                return $loan->due_date ? Carbon::parse($loan->due_date)->format('Y-m-d') : null;
            })
            ->editColumn('return_date', function($loan) {
                return $loan->return_date ? Carbon::parse($loan->return_date)->format('Y-m-d') : null;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getLoansForReturnPending()
    {
        $loans = Loan::with(['book', 'member'])
            ->where('status', 'pending')
            ->get();

        return datatables()->of($loans)
            ->addIndexColumn()
            ->addColumn('action', function($loan) {
                return '<button class="btn btn-sm btn-primary process-return" data-id="'.$loan->id.'">Proses</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function getLoansForBorrowing()
    {
        $loans = Loan::with(['book', 'member'])
            ->where('status', 'borrowed')
            ->get();

        return datatables()->of($loans)
            ->addIndexColumn()
            ->addColumn('action', function($loan) {
                return '<button class="btn btn-sm btn-primary process-return" data-id="'.$loan->id.'">Proses</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    
}
