<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Member::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required|string|max:15',
            'member_id' => 'required|string',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required',
            'address' => 'required|string|max:255'
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => 'errors',
                'message' => $validasi->errors()
            ], 422);
        }

        $member = new Member();
        $member->name = $request->name;
        $member->member_id = $request->member_id;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->address = $request->address;

        $member->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Members has a created',
            'data' => $member
        ], 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return response()->json([
            'status' => 'success',
            'data' => $member
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validasi = Validator::make($request->all(), [
            'name' => 'required|string|max:15',
            'member_id' => 'required|string',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required',
            'address' => 'required|string|max:255'
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validasi->errors(),
            ], 422);
        }

        if($request->has('name')) $member->name = $request->name;
        // if($request->has('member_id')) $member->member_id = $request->member_id;
        if($request->has('email')) $member->email = $request->email;
        if($request->has('phone')) $member->phone = $request->phone;
        if($request->has('address')) $member->address = $request->address;

        $member->save();

        return response()->json([
            'status' => 'success',
            'message' => 'members has updated success',
            'data' => $member
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'members has destroy'
        ]);
    }

    public function search(Request $request){
        $query = $request->input('query');

        $member = Member::where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $member
        ]);

    }

    public function myProfile($id){
        $member = Member::findOrFail($id);

        if (!$member) {
            return response()->json([
                'status' => 'errors',
                'message' => 'member notfound'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'  => $member
        ]);
    }
}
