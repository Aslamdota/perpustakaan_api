<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function viewUsers(){
        $users = User::latest()->paginate(10);
        return view('users.index', ['title' => 'viewUsers'], compact('users'));
    }

    public function storeUsers(Request $request){
        // return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $avatar = NULL;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('users', 'public');
        }


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $avatar,
        ]);

        $notif = array(
            'message' => 'User Berhasil ditambahkan',
            'alert-type' => 'success'
        );

        return redirect()->route('view.user')->with($notif);
    }

    public function editUsers($id){
        $users = User::findOrFail($id);
        return view('users.edit', compact('users'), ['title' => 'Edit User']);
    }

    public function updateUsers(Request $request, $id){
        // return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:8',
            'role' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $users = User::findOrFail($id);
        $users->name = $request->name;
        // $users->email = $request->email;
        $users->role = $request->role;

        if ($request->hasFile('avatar')) {
            if ($users->avatar) {
                Storage::disk('public')->delete($users->avatar);
            }

            $file = $request->file('avatar')->store('users', 'public');
            $users->avatar = $file;
        }

        $users->save();

        $notif = array(
            'message' => 'User Berhasil diedit',
            'alert-type' => 'success'
        );
        return redirect()->route('view.user')->with($notif);
    }

    public function destroyUsers($id){
        $users = User::findOrFail($id);

        if ($users->avatar) {
            Storage::disk('public')->delete($users->avatar);
        }
        $users->delete();

        $notif = array(
            'message' => 'User Berhasil dihapus',
            'alert-type' => 'success'
        );
        return redirect()->route('view.user')->with($notif);
    }
}
