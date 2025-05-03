{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div>
            <label>Email</label>
            <input type="email" name="email" required autofocus>
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
@endsection
