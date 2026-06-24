@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-red-600 to-rose-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, {{ $user->nama_lengkap }}!</h2>
                <p class="text-red-100 mt-1">Sistem Informasi CV AIRA Digiprint.</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    @if(in_array($user->level, ['Administrasi', 'Owner']))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Total Pelanggan</p><p class="text-3xl font-bold">{{ $totalPelanggan }}</p></div><div class="bg-blue-100 rounded-full p-3"><i class="fas fa-users text-blue-600 text-xl"></i></div></div></div>
                <div class="bg-blue-50 px-5 py-2 text-xs text-blue-600">Pelanggan terdaftar</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Total Petugas</p><p class="text-3xl font-bold">{{ $totalPetugas }}</p></div><div class="bg-green-100 rounded-full p-3"><i class="fas fa-id-card text-green-600 text-xl"></i></div></div></div>
                <div class="bg-green-50 px-5 py-2 text-xs text-green-600">Seluruh karyawan</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Total Produk</p><p class="text-3xl font-bold">{{ $totalProduk }}</p></div><div class="bg-red-100 rounded-full p-3"><i class="fas fa-boxes text-red-600 text-xl"></i></div></div></div>
                <div class="bg-red-50 px-5 py-2 text-xs text-red-600">Produk tersedia</div>
            </div>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <div class="p-5"><div class="flex justify-between"><div><p class="text-gray-500 text-sm">Total Transaksi</p><p class="text-3xl font-bold">{{ $totalTransaksi }}</p></div><div class="bg-yellow-100 rounded-full p-3"><i class="fas fa-receipt text-yellow-600 text-xl"></i></div></div></div>
                <div class="bg-yellow-50 px-5 py-2 text-xs text-yellow-600">Sepanjang waktu</div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div><p class="text-gray-500 text-xs uppercase font-semibold">Total Omzet</p><p class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p></div>
                    <i class="fas fa-shopping-cart text-blue-500 text-xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div><p class="text-gray-500 text-xs uppercase font-semibold">Total Kas Masuk</p><p class="text-xl font-bold text-green-600">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</p></div>
                    <i class="fas fa-money-bill-wave text-green-500 text-xl opacity-50"></i>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-red-500">
                <div class="flex justify-between items-center">
                    <div><p class="text-gray-500 text-xs uppercase font-semibold">Total Piutang</p><p class="text-xl font-bold text-red-600">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p></div>
                    <i class="fas fa-hand-holding-usd text-red-500 text-xl opacity-50"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Grafik Pendapatan 7 Hari Terakhir</h3>
                <canvas id="revenueChart" height="200"></canvas>
            </div>
            <div class="bg-rose-50 rounded-2xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-rose-100 rounded-full p-3"><i class="fas fa-chart-pie text-rose-600 text-2xl"></i></div>
                    <div><p class="text-gray-500 text-sm">Rata-rata per transaksi</p><p class="text-3xl font-bold text-rose-800">Rp {{ number_format($totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0, 0, ',', '.') }}</p></div>
                </div>
            </div>
        </div>
    @endif

    @if($transaksiTerbaru->count())
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Transaksi Terbaru</h3>
            <a href="#" class="text-blue-600 text-sm hover:underline">Lihat Semua</a>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left">No Invoice</th><th class="px-4 py-2 text-left">Pelanggan</th><th class="px-4 py-2 text-right">Total</th><th class="px-4 py-2 text-left">Tanggal</th></tr></thead>
            <tbody>
                @foreach($transaksiTerbaru as $trx)
                <tr class="border-b"><td class="px-4 py-2">{{ $trx->no_invoice }}</td><td class="px-4 py-2">{{ $trx->pelanggan->username ?? '-' }}</td><td class="px-4 py-2 text-right">Rp {{ number_format($trx->total_tagihan,0,',','.') }}</td><td class="px-4 py-2">{{ $trx->tanggal->format('d/m/Y H:i') }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@if($chartData->isNotEmpty())
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('tanggal')->map(fn($d) => date('d/m', strtotime($d)))) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartData->pluck('total')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } } } }
        });
    </script>
    @endpush
@endif
@endsection