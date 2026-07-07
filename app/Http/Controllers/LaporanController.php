<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'pembayaran'])
                      ->where('status_pesanan', '!=', 'Ditolak'); // Tambahkan baris ini

        if ($request->filled(['tgl_awal', 'tgl_akhir'])) {
            $query->whereBetween('tanggal', [$request->tgl_awal, $request->tgl_akhir]);
        }

        $transaksi = $query->orderBy('tanggal', 'desc')->get();
        
        // Hitung total keseluruhan
        $totalTagihan = $transaksi->sum('total_tagihan');
        $totalBayar = $transaksi->sum('jumlah_bayar');
        $totalKurang = $transaksi->sum(function($trx) {
            return max(0, $trx->total_tagihan - $trx->jumlah_bayar);
        });
        
        // Grouping rekap
        $rekapPembayaran = $transaksi->groupBy(function ($item) {
            return $item->pembayaran ? $item->pembayaran->nama_metode : 'Tanpa Metode';
        })->map(function ($items) {
            return $items->sum('jumlah_bayar');
        });

        return view('laporan.index', compact('transaksi', 'rekapPembayaran', 'totalTagihan', 'totalBayar', 'totalKurang'));
    }

    public function export(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'pembayaran'])
                      ->where('status_pesanan', '!=', 'Ditolak');

        if ($request->filled(['tgl_awal', 'tgl_akhir'])) {
            $query->whereBetween('tanggal', [$request->tgl_awal, $request->tgl_akhir]);
        }

        $transaksi = $query->get();
        
        return Excel::download(new LaporanExport($transaksi), 'laporan_transaksi.xlsx');
    }
}