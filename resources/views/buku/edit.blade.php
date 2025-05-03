@extends('layouts.app')

@section('content')
    <h1>Edit Buku</h1>
    <form action="{{ route('buku.update', $buku->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Judul:</label><br>
        <input type="text" name="judul" value="{{ $buku->judul }}"><br>
        <label>Penulis:</label><br>
        <input type="text" name="penulis" value="{{ $buku->penulis }}"><br>
        <label>Tahun Terbit:</label><br>
        <input type="text" name="tahun_terbit" value="{{ $buku->tahun_terbit }}"><br><br>
        <button type="submit">Update</button>
    </form>
@endsection
