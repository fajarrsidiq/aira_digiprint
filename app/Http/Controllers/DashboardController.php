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
        
        // Inisialisasi default
        $data = [
            'user' => $user,
            'transaksiTerbaru' => Transaksi::with('pelanggan')->orderBy('tanggal', 'desc')->limit(5)->get(),
            'chartData' => collect(), // Default koleksi kosong
        ];

        if (in_array($user->level, ['Owner', 'Administrasi'])) {
            $data['totalPelanggan'] = Pelanggan::count();
            $data['totalPetugas']   = Petugas::count();
            $data['totalTransaksi'] = Transaksi::count();
            $data['totalPendapatan'] = Transaksi::sum('total_tagihan');
            $data['totalDiterima']   = Transaksi::sum('jumlah_bayar');
            $data['totalPiutang']    = $data['totalPendapatan'] - $data['totalDiterima'];
            $data['totalProduk']  = Produk::count();
            $data['totalBahan']   = Bahan::count();
            $data['totalSatuan']  = Satuan::count();
            
            // Ambil data chart hanya untuk level berwenang
            $data['chartData'] = Transaksi::select(
                    DB::raw('DATE(tanggal) as tanggal'), 
                    DB::raw('SUM(total_tagihan) as total')
                )
                ->where('tanggal', '>=', now()->subDays(7))
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
        }

        return view('dashboard.petugas', $data);
    }
}