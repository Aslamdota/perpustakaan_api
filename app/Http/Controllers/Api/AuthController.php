<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member || !Hash::check($request->password, $member->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials',
            ], 401);
        }

        // Buat token (jika Member pakai Laravel Sanctum)
        $token = $member->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $member,
        ]);
    }


    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {
        // Hapus semua token pengguna
        $request->user()->tokens()->delete();

        // Logout pengguna
        Auth::logout();

        // Invalidasi sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
