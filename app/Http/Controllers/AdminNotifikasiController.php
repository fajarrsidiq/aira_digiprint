<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminNotifikasiController extends Controller
{
    public function index()
    {
        // Menggunakan eager loading 'details.produk' sesuai nama relasi di Model Transaksi Anda
        $pesanans = Transaksi::with(['pelanggan', 'details.produk'])
            ->where('status_pesanan', 'Menunggu Konfirmasi')
            ->get();

        $desainers = Petugas::where('level', 'Desain')->get(); 
        
        return view('notifikasi.index', compact('pesanans', 'desainers'));
    }

    public function proses(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        try {
            if ($request->action == 'terima') {
                $request->validate(['id_desainer' => 'required|exists:petugas,id_petugas']);

                $transaksi->update([
                    'status_pesanan' => 'Diproses',
                    'id_desainer'    => $request->id_desainer,
                    'id_petugas'     => Auth::guard('petugas')->id(), 
                ]);
                return back()->with('success', 'Pesanan berhasil diproses.');
            }

            elseif ($request->action == 'tolak') {
                $transaksi->update([
                    'status_pesanan' => 'Ditolak',
                    'id_petugas'     => Auth::guard('petugas')->id(),
                ]);
                return back()->with('success', 'Pesanan ditolak.');
            }

            return back()->with('error', 'Aksi tidak dikenali.');

        } catch (\Exception $e) {
            Log::error('Error notifikasi: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage()); 
        }
    }
}