<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:admin,staff,anggota',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'anggota',
            'avatar' => 'avatar.jpg'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ], 201);
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]
        ]);
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }

    public function UpdateProfil(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|sometimes|string|max:255',
            'email' => 'required|sometimes',
            'avatar' => 'required|sometimes|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        
        if($request->has('name')) $user->name = $request->name;
        if($request->has('email')) $user->email = $request->name;

        if ($request->hasFile('avatar')) {
            if ($user->avatar !== 'avatar.jpg') {
                Storage::delete('avatars/' . $user->avatar);
            }

            $avatarName = time(). '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('avatars', $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User has updated',
            'data' => $user
        ], 200);


    }

    public function updatePassword(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'errors',
                'message' => 'Old password not true'
            ], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password Has updated',
            'data' => $user
        ], 200);


    }

    public function ProfilUser(){
        // $member = Member::findOrFail($id);
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'errors',
                'message' => 'user notfound'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'  => $user
        ]);
    }

}