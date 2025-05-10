<?php

namespace App\Http\Controllers\peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function viewPeminjaman(){
        $borrowings = Borrowing::where('status', 'pending')->get();
        return view('peminjaman.index', ['title' => 'viewPeminjaman'], compact('borrowings'));
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
