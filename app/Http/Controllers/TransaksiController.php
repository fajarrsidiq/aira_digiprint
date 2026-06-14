<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\JenisPembayaran;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Filesystem\FilesystemAdapter;
use App\Models\Petugas;

class TransaksiController extends Controller
{
   public function index(Request $request)
    {
        // Mengambil nilai pencarian dari input 'search'
        $search = $request->get('search');

        // Membangun query dengan relasi yang dibutuhkan
        $transaksis = Transaksi::with(['pelanggan', 'petugas', 'pembayaran', 'details.produk'])
            ->when($search, function ($query, $search) {
                // Logika pencarian: mencari berdasarkan nomor invoice ATAU nama pelanggan
                return $query->where('no_invoice', 'like', '%' . $search . '%')
                    ->orWhereHas('pelanggan', function ($q) use ($search) {
                        $q->where('nama_pelanggan', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate(10);

        return view('transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $pelanggans = Pelanggan::all();
        $pembayarans = JenisPembayaran::all();
        $produks = Produk::all();

        $desainers = Petugas::where('level', 'Desain')->get();

        // MEMBUAT INVOICE OTOMATIS AMAN (Maksimal 15 Karakter: INV-300526-0001)
        $hariIni = date('dmy'); 
        $prefix = 'INV-' . $hariIni . '-';
        
        $transaksiTerakhir = Transaksi::where('no_invoice', 'like', $prefix . '%')
            ->orderBy('id_transaksi', 'desc')
            ->first();

        if ($transaksiTerakhir) {
            $nomorUrut = substr($transaksiTerakhir->no_invoice, -4);
            $nomorUrutBaru = str_pad((int)$nomorUrut + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nomorUrutBaru = '0001';
        }

        $invoiceOtomatis = $prefix . $nomorUrutBaru;

        return view('transaksi.create', compact('pelanggans', 'pembayarans', 'produks', 'desainers', 'invoiceOtomatis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'diskon' => 'nullable|integer|min:0',
            'total_tagihan' => 'nullable|numeric',
            'id_pembayaran' => 'required|exists:jenis_pembayaran,id_jenis_pembayaran',
            'jumlah_bayar' => 'required|numeric|min:0',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'id_desainer' => 'nullable|exists:petugas,id_petugas',
            'catatan' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'status_pesanan' => 'nullable|string',
        ], [
            'cart.required' => 'Kolom Produk belanjaan wajib diisi.',
            'id_pelanggan.required' => 'Kolom Nama Pelanggan wajib diisi.',
            'id_pelanggan.exists' => 'Nama Pelanggan yang dipilih tidak valid.',
            'id_pembayaran.required' => 'Kolom Opsi Metode Pembayaran wajib diisi.',
            'id_pembayaran.exists' => 'Metode pembayaran yang dipilih tidak valid.',
            'jumlah_bayar.required' => 'Kolom Jumlah Bayar wajib diisi.',
            'jumlah_bayar.numeric' => 'Nilai jumlah bayar harus berupa angka.',
            'jumlah_bayar.min' => 'Jumlah bayar tidak boleh kurang dari 0.',
            'bukti_bayar.image' => 'Bukti pembayaran harus berupa gambar.',
            'bukti_bayar.mimes' => 'Format gambar bukti pembayaran harus jpeg, png, atau jpg.',
            'bukti_bayar.max' => 'Ukuran gambar bukti pembayaran maksimal 2 MB.',
        ]);

        DB::beginTransaction();
        try {
            $cart = json_decode($request->cart, true);
            if (empty($cart)) {
                throw new \Exception('Keranjang belanja kosong');
            }

            $hariIni = date('dmy');
            $prefix = 'INV-' . $hariIni . '-';
            $transaksiTerakhir = Transaksi::where('no_invoice', 'like', $prefix . '%')
                ->orderBy('id_transaksi', 'desc')
                ->first();

            if ($transaksiTerakhir) {
                $nomorUrut = substr($transaksiTerakhir->no_invoice, -4);
                $nomorUrutBaru = str_pad((int)$nomorUrut + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $nomorUrutBaru = '0001';
            }
            
            $noInvoice = $prefix . $nomorUrutBaru;

            $totalTagihan = 0;
            $cartData = [];

            foreach ($cart as $item) {
                $produk = Produk::find($item['produk_id']);
                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan: ' . ($item['produk_id'] ?? 'unknown'));
                }
                
                $ukuran = $item['ukuran'] ?? $produk->ukuran_default;
                $hargaSatuan = $produk->harga;
                
                if ($ukuran && str_contains($ukuran, 'x')) {
                    $parts = explode('x', $ukuran);
                    if (count($parts) == 2) {
                        $lebar = floatval(preg_replace('/[^0-9.]/', '', $parts[0]));
                        $panjang = floatval(preg_replace('/[^0-9.]/', '', $parts[1]));
                        if ($lebar > 0 && $panjang > 0) {
                            $hargaSatuan = $produk->harga * ($lebar * $panjang);
                        }
                    }
                }
                
                $subtotal = $hargaSatuan * $item['qty'];
                $totalTagihan += $subtotal;
                
                $fileDesainPath = null;
                if (!empty($item['file_desain_base64']) && !empty($item['file_desain_name'])) {
                    $fileDesainPath = $this->saveBase64File(
                        $item['file_desain_base64'],
                        $item['file_desain_name'],
                        $item['file_desain_type'] ?? 'image/jpeg'
                    );
                }
                
                $cartData[] = [
                    'id_produk' => $produk->id_produk,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                    'harga_satuan' => $hargaSatuan,
                    'ukuran' => $ukuran,
                    'file_desain' => $fileDesainPath,
                ];
            }
            
            $diskon = $request->diskon ?? 0;
            $totalSetelahDiskon = $totalTagihan - $diskon;
            if ($totalSetelahDiskon < 0) $totalSetelahDiskon = 0;
            
            $buktiBayar = null;
            if ($request->hasFile('bukti_bayar')) {
                $buktiBayar = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
            }
            
            $statusPesanan = $request->status_pesanan ?? 'Diproses';
            
            /** @var Petugas|null $petugas */
            $petugas = Auth::guard('petugas')->user();

            $transaksi = Transaksi::create([
                'no_invoice'      => $noInvoice,
                'id_pelanggan'    => $request->id_pelanggan,
                'id_petugas'      => $petugas?->id_petugas ?? 1,
                'id_desainer'     => $request->id_desainer,
                'id_pembayaran'   => $request->id_pembayaran,
                'tanggal'         => $request->tanggal ?? now(),
                'total_tagihan'   => $totalSetelahDiskon,
                'jumlah_bayar'    => $request->jumlah_bayar,
                'diskon'          => $diskon,
                'bukti_bayar'     => $buktiBayar,
                'catatan'         => $request->catatan,
                'status_pesanan'  => 'Diproses',
            ]);
            
            foreach ($cartData as $detail) {
                DetailTransaksi::create([
                    'id_transaksi'       => $transaksi->id_transaksi,
                    'id_produk'          => $detail['id_produk'],
                    'keterangan_ukuran'  => $detail['ukuran'],
                    'file_desain'        => $detail['file_desain'],
                    'harga'              => $detail['harga_satuan'],
                    'qty'                => $detail['qty'],
                    'subtotal'           => $detail['subtotal'],
                ]);
            }
            
            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan. No Invoice: ' . $noInvoice);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error store transaksi: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    private function saveBase64File($base64, $fileName, $mimeType)
    {
        try {
            $decoded = base64_decode($base64);
            if ($decoded === false) return null;
            
            $newFileName = 'desain/' . date('Y/m/d') . '/' . uniqid() . '_' . $fileName;
            $path = storage_path('app/public/' . $newFileName);
            
            $dir = dirname($path);
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            
            file_put_contents($path, $decoded);
            return $newFileName;
        } catch (\Exception $e) {
            Log::error('Error saving base64 file: ' . $e->getMessage());
            return null;
        }
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'petugas', 'pembayaran', 'details.produk'])->findOrFail($id);
        return view('transaksi.show', compact('transaksi'));
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        
        if ($transaksi->bukti_bayar) {
            Storage::disk('public')->delete($transaksi->bukti_bayar);
        }
        
        foreach ($transaksi->details as $detail) {
            if ($detail->file_desain) {
                Storage::disk('public')->delete($detail->file_desain);
            }
        }
        
        $transaksi->delete();
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    
    //Mengarahkan langsung cetak pada halaman invoice.blade.php Anda
    public function invoice($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan', 
            'pembayaran', 
            'details.produk',
            'petugas'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('transaksi.invoice', compact('transaksi'));
        
        return $pdf->stream('invoice-' . $transaksi->no_invoice . '.pdf');
    }

    //Menampilkan Form Khusus Pelunasan Baru (Mengambil rincian data nota pembelian)
    public function halamanPelunasan($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'pembayaran', 'details.produk', 'petugas'])->findOrFail($id);
        $pembayarans = JenisPembayaran::all();
        return view('transaksi.pelunasan', compact('transaksi', 'pembayarans'));
    }

    //Memproses logika kalkulasi sisa keuangan pelunasan dari form
    public function prosesPelunasan(Request $request, $id)
    {
        $request->validate([
            'bayar_pelunasan' => 'required|numeric|min:1',
            'id_pembayaran' => 'required|exists:jenis_pembayaran,id_jenis_pembayaran',
            'bukti_bayar' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:3072',
        ], [
            'bayar_pelunasan.required' => 'Kolom Bayar Pelunasan wajib diisi.',
            'bayar_pelunasan.numeric' => 'Nilai pelunasan harus berupa angka.',
            'bayar_pelunasan.min' => 'Jumlah pelunasan minimal Rp 1.',
            'id_pembayaran.required' => 'Kolom Opsi Metode Pembayaran wajib diisi.',
            'id_pembayaran.exists' => 'Metode pembayaran yang dipilih tidak valid.',
            'bukti_bayar.file' => 'Bukti pembayaran pelunasan harus berupa file berkas.',
            'bukti_bayar.mimes' => 'Format file bukti pelunasan harus berupa jpeg, png, jpg, atau pdf.',
            'bukti_bayar.max' => 'Ukuran file bukti pelunasan maksimal 3 MB.',
        ]);

        $transaksi = Transaksi::findOrFail($id);

        // Akumulasikan nilai pembayaran pelunasan baru ke field lama
        $transaksi->jumlah_bayar += $request->bayar_pelunasan;
        $transaksi->id_pembayaran = $request->id_pembayaran;

        if ($request->hasFile('bukti_bayar')) {
            if ($transaksi->bukti_bayar) {
                Storage::disk('public')->delete($transaksi->bukti_bayar);
            }
            $transaksi->bukti_bayar = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
        }

        $transaksi->save();

        return redirect()->route('transaksi.index')->with('success', 'Pelunasan sisa transaksi ' . $transaksi->no_invoice . ' berhasil diperbarui!');
    }

    //Menangani unduhan file desain item cetak dari storage link
    public function downloadDesain($id_detail)
    {
        // Cari detail transaksi berdasarkan ID spesifik dari tombol yang diklik
        $detail = \App\Models\DetailTransaksi::findOrFail($id_detail);

        // Cek apakah file benar-benar ada
        if (!$detail->file_desain || !Storage::disk('public')->exists($detail->file_desain)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $detail->file_desain)
);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Diproses,Selesai'
        ]);
        
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status_pesanan = $request->status;
        $transaksi->save();
        
        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
    }
}