@extends('layouts.app')
@section('title', 'Dashboard Pelanggan')
@section('content')
<div class="bg-gradient-to-r from-red-600 to-rose-600 rounded-2xl shadow-lg p-6 text-white">
    <h2 class="text-2xl font-bold">Selamat Datang, {{ $user->nama_pelanggan }}!</h2>
    <p class="text-blue-100 mt-1">Selamat datang di CV AIRA Digiprint.</p>
</div>
@endsection