<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\JenisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PelangganTransaksiController extends Controller
{

   public function index()
    {
        $produks = Produk::all();
        $pembayarans = JenisPembayaran::all();

        return view('pelanggan.pesanan', compact('produks', 'pembayarans'));
    }

    public function create()
    {
        $produks = Produk::all();
        $pembayarans = JenisPembayaran::all();
        
        return view('pelanggan.create', compact('produks', 'pembayarans'));
    }

    public function store(Request $request)
    {
        $idPelanggan = Auth::guard('pelanggan')->id();

        $request->validate([
            'cart' => 'required|json',
            'id_pembayaran' => 'required|exists:jenis_pembayaran,id_jenis_pembayaran',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_petugas' => 'nullable',
            'id_desainer' => 'nullable'
        ]);

        DB::beginTransaction();
        try {
            $cart = json_decode($request->cart, true);
            $idPelanggan = Auth::guard('pelanggan')->user()->id_pelanggan;

            $hariIni = date('dmy');
            $prefix = 'INV-' . $hariIni . '-';
            $transaksiTerakhir = Transaksi::where('no_invoice', 'like', $prefix . '%')
                ->orderBy('id_transaksi', 'desc')
                ->first();
            $nomorUrutBaru = $transaksiTerakhir ? str_pad((int)substr($transaksiTerakhir->no_invoice, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';
            $noInvoice = $prefix . $nomorUrutBaru;

            // Hitung Total
            $totalTagihan = 0;
            $cartData = [];

            foreach ($cart as $item) {
                $produk = Produk::find($item['produk_id']);
                $hargaSatuan = $produk->harga;
                
                // Logika Hitung Custom (PxL)
                if (isset($item['ukuran']) && str_contains($item['ukuran'], 'x')) {
                    $parts = explode('x', $item['ukuran']);
                    $hargaSatuan = $produk->harga * (floatval($parts[0]) * floatval($parts[1]));
                }
                
                $subtotal = $hargaSatuan * $item['qty'];
                $totalTagihan += $subtotal;

                // Simpan File Desain
                $uploadPath = null;
                if (!empty($item['file_desain_base64'])) {
                    $uploadPath = $this->saveBase64File($item['file_desain_base64'], $item['file_desain_name']);
                }

                $cartData[] = [
                    'id_produk' => $produk->id_produk,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                    'harga_satuan' => $hargaSatuan,
                    'ukuran' => $item['ukuran'],
                    'upload_desain' => $uploadPath,
                ];
            }

            // Simpan Transaksi
            $transaksi = Transaksi::create([
                'no_invoice'      => $noInvoice,
                'id_pelanggan'    => $idPelanggan,
                'id_petugas'      => null,
                'id_desainer'     => null,
                'id_pembayaran'   => $request->id_pembayaran,
                'total_tagihan'   => $totalTagihan,
                'jumlah_bayar'    => $request->jumlah_bayar,
                'status_pesanan'  => 'Menunggu Konfirmasi',
                'tanggal'         => now(),
                'bukti_bayar'     => $request->hasFile('bukti_bayar')
                    ? $request->file('bukti_bayar')->store('bukti_pembayaran', 'public')
                    : null,
            ]);

            foreach ($cartData as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk'    => $detail['id_produk'],
                    'qty'          => $detail['qty'],
                    'subtotal'     => $detail['subtotal'],
                    'harga'        => $detail['harga_satuan'],
                    'keterangan_ukuran' => $detail['ukuran'],
                    'file_desain'  => $detail['upload_desain'],
                ]);
            }

            DB::commit();
            return redirect()->route('pelanggan.riwayat')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store transaksi pelanggan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
        
    }

    public function riwayat()
    {
        $idPelanggan = Auth::guard('pelanggan')->user()->id_pelanggan;
        
        $transaksis = Transaksi::with(['pembayaran', 'details.produk'])
            ->where('id_pelanggan', $idPelanggan)
            ->latest()
            ->paginate(10);

        return view('pelanggan.riwayat', compact('transaksis'));
    }

    // Helper untuk simpan file Base64
    private function saveBase64File($base64, $fileName)
    {
        $decoded = base64_decode($base64);
        $newFileName = 'desain/' . date('Y/m/d') . '/' . uniqid() . '_' . $fileName;
        Storage::disk('public')->put($newFileName, $decoded);
        return $newFileName;
    }

    // Detail Transaksi
    public function show($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->user()->id_pelanggan;
        $transaksi = Transaksi::with(['details.produk', 'pembayaran'])
            ->where('id_transaksi', $id)
            ->where('id_pelanggan', $idPelanggan)
            ->firstOrFail();
            
        return view('pelanggan.show', compact('transaksi'));
    }

    public function invoice($id)
    {
        $idPelanggan = Auth::guard('pelanggan')->user()->id_pelanggan;

        $transaksi = Transaksi::with(['pelanggan', 'pembayaran', 'details.produk', 'petugas'])
            ->where('id_transaksi', $id)
            ->where('id_pelanggan', $idPelanggan)
            ->firstOrFail();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pelanggan.invoice', compact('transaksi'));
        
        return $pdf->stream('invoice-' . $transaksi->no_invoice . '.pdf');
    }
}