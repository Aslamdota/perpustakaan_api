@extends('layouts.app')

@section('content')
    <h1>Daftar Buku</h1>
    <a href="{{ route('buku.create') }}">Tambah Buku</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($buku as $item)
                <tr>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->penulis }}</td>
                    <td>{{ $item->tahun_terbit }}</td>
                    <td>
                        <a href="{{ route('buku.edit', $item->id) }}">Edit</a> |
                        <form action="{{ route('buku.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus buku ini?')" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
