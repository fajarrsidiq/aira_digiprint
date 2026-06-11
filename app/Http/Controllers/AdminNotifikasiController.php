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
        $pesanans = Transaksi::where('status_pesanan', 'Menunggu Konfirmasi')->get();
        $desainers = Petugas::where('level', 'Desain')->get(); 
        
        return view('notifikasi.index', compact('pesanans', 'desainers'));
    }

   public function proses(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        try {
            if ($request->action == 'terima') {
                $request->validate([
                    'id_desainer' => 'required|exists:petugas,id_petugas'
                ]);

                $transaksi->update([
                    'status_pesanan' => 'Diproses',
                    'id_desainer'    => $request->id_desainer,
                    'id_petugas'     => Auth::guard('petugas')->id(), 
                ]);
                
                return back()->with('success', 'Pesanan berhasil ditugaskan ke desainer!');
            }

            if ($request->action == 'hapus') {
                $transaksi->delete();
                return back()->with('success', 'Pesanan telah dihapus.');
            }

            return back()->with('error', 'Aksi tidak dikenali.');

        } catch (\Exception $e) {
            Log::error('Gagal memproses notifikasi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
        }
    }
}