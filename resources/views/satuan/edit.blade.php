@extends('layouts.app')
@section('title', 'Edit Satuan')
@section('content')
<div class="bg-white p-6 rounded shadow max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Satuan</h2>
    <form action="{{ route('satuan.update', $satuan->id_satuan) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label class="block">Nama Satuan</label><input type="text" name="nama_satuan" value="{{ $satuan->nama_satuan }}" class="w-full border p-2 rounded" required></div>
        <div class="flex gap-2"><button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded">Update</button><a href="{{ route('satuan.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">Batal</a></div>
    </form>
</div>
@endsection