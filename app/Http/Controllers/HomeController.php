<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        if ($role === 'admin') {
            return view('dashboard.admin');
        } elseif ($role === 'karyawan') {
            return view('dashboard.karyawan');
        } else {
            return view('dashboard.anggota');
        }
    }
}