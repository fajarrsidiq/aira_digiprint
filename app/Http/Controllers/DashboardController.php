<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Petugas;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function petugas()
    {
        $user = Auth::guard('petugas')->user();
        $totalPelanggan = Pelanggan::count();
        $totalPetugas = Petugas::count();
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::sum('total_tagihan');
        $totalProduk = Produk::count();
        $totalBahan = Bahan::count();
        $totalSatuan = Satuan::count();

        $chartData = Transaksi::select(DB::raw('DATE(tanggal) as tanggal'), DB::raw('SUM(total_tagihan) as total'))
            ->where('tanggal', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $transaksiTerbaru = Transaksi::with('pelanggan')->orderBy('tanggal', 'desc')->limit(5)->get();

        return view('dashboard.petugas', compact(
            'user', 'totalPelanggan', 'totalPetugas', 'totalTransaksi',
            'totalPendapatan', 'totalProduk', 'totalBahan', 'totalSatuan',
            'chartData', 'transaksiTerbaru'
        ));
    }
}