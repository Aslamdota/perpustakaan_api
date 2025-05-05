<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();
        return response()->json([
            'status' => 'success',
            'data' => $members
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'telepon' => 'required|string|max:15',
        ]);

        $member = Member::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Member created successfully',
            'data' => $member
        ], 201);
    }

    public function show(Member $member)
    {
        return response()->json([
            'status' => 'success',
            'data' => $member
        ]);
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'telepon' => 'required|string|max:15',
        ]);

        $member->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Member updated successfully',
            'data' => $member
        ]);
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Member deleted successfully'
        ]);
    }
}