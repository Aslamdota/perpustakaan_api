<?php

namespace App\Http\Controllers\peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Loan;

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

    public function confirm(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->due_date = $request->due_date;
        $borrowing->noted = $request->noted;
        $borrowing->status = 'borrowed';
        $borrowing->save();

        // Kurangi stok buku
        $borrowing->book->decrement('stock', 1);

        $notification = array(
                'message' => 'Peminjaman Berhasil dikonfirmasi',
                'alert-type' => 'success'
            );

        return redirect()->back()->with($notification);
    }

    public function reject(Request $request, $id)
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->noted = $request->noted;
        $borrowing->status = 'rejected';
        $borrowing->save();

         $notification = array(
                'message' => 'Peminjaman Berhasil ditolak',
                'alert-type' => 'error'
            );

        return redirect()->back()->with($notification);
    }
}
