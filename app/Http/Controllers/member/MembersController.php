<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MembersController extends Controller
{
    public function viewMembers(){
        $members = Member::latest()->get();
        return view('member.index', ['title' => 'viewMembers'], compact('members'));
    }

    public function storeMember(Request $request){
        // return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'member_id' => 'required|string|max:255|unique:members',
            'email' => 'required|string|email|max:255|unique:members',
            'Password' => 'required|string|min:8',
            'phone' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $avatar = NULL;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('members', 'public');
        }

        Member::create([
            'name' => $request->name,
            'member_id' => $request->member_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'avatar' => $avatar,
        ]);

        $notif = array(
            'message' => 'Member Berhasil ditambahkan',
            'alert-type' => 'success'
        );

        return redirect()->route('view.member')->with($notif);
    }
}
