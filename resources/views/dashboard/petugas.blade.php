@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, {{ $user->nama_lengkap }}!</h2>
                <p class="text-blue-100 mt-1">Berikut ringkasan data CV AIRA Digiprint hari ini.</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full backdrop-blur-sm">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- 4 kartu utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between">
                    <div><p class="text-gray-500 text-sm">Total Pelanggan</p><p class="text-3xl font-bold">{{ $totalPelanggan }}</p></div>
                    <div class="bg-blue-100 rounded-full p-3"><i class="fas fa-users text-blue-600 text-xl"></i></div>
                </div>
            </div>
            <div class="bg-blue-50 px-5 py-2 text-xs text-blue-600">Pelanggan terdaftar</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between">
                    <div><p class="text-gray-500 text-sm">Total Petugas</p><p class="text-3xl font-bold">{{ $totalPetugas }}</p></div>
                    <div class="bg-green-100 rounded-full p-3"><i class="fas fa-id-card text-green-600 text-xl"></i></div>
                </div>
            </div>
            <div class="bg-green-50 px-5 py-2 text-xs text-green-600">Seluruh karyawan</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between">
                    <div><p class="text-gray-500 text-sm">Total Transaksi</p><p class="text-3xl font-bold">{{ $totalTransaksi }}</p></div>
                    <div class="bg-yellow-100 rounded-full p-3"><i class="fas fa-receipt text-yellow-600 text-xl"></i></div>
                </div>
            </div>
            <div class="bg-yellow-50 px-5 py-2 text-xs text-yellow-600">Sepanjang waktu</div>
        </div>
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between">
                    <div><p class="text-gray-500 text-sm">Total Pendapatan</p><p class="text-3xl font-bold">Rp {{ number_format($totalPendapatan,0,',','.') }}</p></div>
                    <div class="bg-purple-100 rounded-full p-3"><i class="fas fa-chart-line text-purple-600 text-xl"></i></div>
                </div>
            </div>
            <div class="bg-purple-50 px-5 py-2 text-xs text-purple-600">Total keseluruhan</div>
        </div>
    </div>

    <!-- 3 kartu master -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-4"><div class="flex justify-between"><div><p class="text-gray-400 text-sm">Total Produk</p><p class="text-2xl font-bold">{{ $totalProduk }}</p></div><i class="fas fa-boxes text-gray-400 text-2xl"></i></div><p class="text-xs text-blue-600 mt-2">Produk tersedia</p></div>
        <div class="bg-white rounded-xl shadow p-4"><div class="flex justify-between"><div><p class="text-gray-400 text-sm">Total Bahan</p><p class="text-2xl font-bold">{{ $totalBahan }}</p></div><i class="fas fa-cubes text-gray-400 text-2xl"></i></div><p class="text-xs text-green-600 mt-2">Jenis bahan baku</p></div>
        <div class="bg-white rounded-xl shadow p-4"><div class="flex justify-between"><div><p class="text-gray-400 text-sm">Total Satuan</p><p class="text-2xl font-bold">{{ $totalSatuan }}</p></div><i class="fas fa-balance-scale text-gray-400 text-2xl"></i></div><p class="text-xs text-purple-600 mt-2">Unit satuan</p></div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Grafik Pendapatan 7 Hari Terakhir</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
        <div class="bg-indigo-50 rounded-2xl shadow p-6">
            <div class="flex items-center gap-4">
                <div class="bg-indigo-100 rounded-full p-3"><i class="fas fa-chart-pie text-indigo-600 text-2xl"></i></div>
                <div><p class="text-gray-500 text-sm">Rata-rata per transaksi</p><p class="text-3xl font-bold text-indigo-800">Rp {{ number_format($totalTransaksi > 0 ? $totalPendapatan / $totalTransaksi : 0, 0, ',', '.') }}</p></div>
            </div>
        </div>
    </div>

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
@endsection