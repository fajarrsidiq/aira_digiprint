@extends('layouts.app')
@section('title', 'Edit Bahan')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Bahan</h2>
    <form action="{{ route('bahan.update', $bahan->id_bahan) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label class="block">Nama Bahan</label><input type="text" name="nama_bahan" value="{{ $bahan->nama_bahan }}" class="w-full border p-2 rounded" required></div>
        <div class="flex gap-2"><button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button><a href="{{ route('bahan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a></div>
    </form>
</div>
@endsection