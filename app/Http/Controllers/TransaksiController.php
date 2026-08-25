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
use App\Models\Petugas;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $transaksis = Transaksi::with(['pelanggan', 'petugas', 'pembayaran', 'details.produk'])
            ->when($search, function ($query, $search) {
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
        $pembayarans = JenisPembayaran::all();
        $produks = Produk::all();

        $desainers = Petugas::where('level', 'Desain')->get();

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

        $cart = json_decode($request->cart, true);
        if (empty($cart)) {
            return back()->withInput()->with('error', 'Keranjang belanja tidak boleh kosong.');
        }

        // PERHITUNGAN ULANG SUBTOTAL & TOTAL TAGIHAN SERVER-SIDE
        $totalTagihanBackend = 0;
        foreach ($cart as $item) {
            $produk = Produk::find($item['produk_id']);
            if (!$produk) continue;

            $hargaSatuan = $produk->harga;
            $ukuran = $item['ukuran'] ?? $produk->ukuran_default;

            if ($ukuran && str_contains($ukuran, 'x')) {
                $parts = explode('x', $ukuran);
                if (count($parts) == 2) {
                    $panjang = floatval(preg_replace('/[^0-9.]/', '', $parts[0]));
                    $lebar = floatval(preg_replace('/[^0-9.]/', '', $parts[1]));
                    if ($panjang > 0 && $lebar > 0) {
                        $hargaSatuan = $produk->harga * ($panjang * $lebar);
                    }
                }
            }
            $totalTagihanBackend += ($hargaSatuan * $item['qty']);
        }

        $diskon = $request->diskon ?? 0;
        $totalSetelahDiskon = $totalTagihanBackend - $diskon;
        if ($totalSetelahDiskon < 0) $totalSetelahDiskon = 0;

        // VALIDASI DP MINIMAL 50% SISI BACKEND
        $minimalDpBackend = $totalSetelahDiskon * 0.50;
        if ($request->jumlah_bayar < $minimalDpBackend) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memproses transaksi: Nominal bayar kurang dari syarat minimal DP 50% (Rp ' . number_format($minimalDpBackend, 0, ',', '.') . ').');
        }

        DB::beginTransaction();
        try {
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
            
            $buktiBayar = null;
            if ($request->hasFile('bukti_bayar')) {
                $buktiBayar = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
            }
            
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

    public function invoice($id)
    {
        $transaksi = Transaksi::with([
            'pelanggan', 
            'pembayaran', 
            'details.produk',
            'petugas'
        ])->findOrFail($id);

        if ($transaksi->status_pesanan === 'Ditolak') {
            return redirect()->back()->with('error', 'Invoice tidak tersedia untuk pesanan yang ditolak.');
        }

        $pdf = Pdf::loadView('transaksi.invoice', compact('transaksi'));
        
        return $pdf->stream('invoice-' . $transaksi->no_invoice . '.pdf');
    }

    public function halamanPelunasan($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'pembayaran', 'details.produk', 'petugas'])->findOrFail($id);
        $pembayarans = JenisPembayaran::all();
        return view('transaksi.pelunasan', compact('transaksi', 'pembayarans'));
    }

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

    public function downloadDesain($id_detail)
    {
        $detail = \App\Models\DetailTransaksi::findOrFail($id_detail);

        if (!$detail->file_desain || !Storage::disk('public')->exists($detail->file_desain)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $detail->file_desain));
    }

    public function indexProduksi()
    {
        $user = Auth::guard('petugas')->user();

        $transaksis = Transaksi::with(['pelanggan', 'details.produk', 'desainer'])
            ->where('status_pesanan', 'Diproses')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
                            
        return view('transaksi.produksi', compact('transaksis', 'user'));
    }

    public function uploadFinal(Request $request, $id_detail)
    {
        $request->validate(['file_desain_final' => 'required|file|mimes:pdf,cdr,ai,jpg,png|max:10240']);
        
        $detail = \App\Models\DetailTransaksi::findOrFail($id_detail);
        $path = $request->file('file_desain_final')->store('desain_final', 'public');
        
        $detail->update([
            'file_desain_final' => $path,
            'status_desain'     => 'Final'
        ]);

        return back()->with('success', 'Desain final berhasil diunggah.');
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = \App\Models\Transaksi::with('details')->findOrFail($id);

        if ($request->status == 'Selesai') {
            foreach ($transaksi->details as $detail) {
                if ($detail->status_desain !== 'Final') {
                    return back()->with('error', 'Gagal! Semua desain harus berstatus "Final" sebelum menyelesaikan pesanan.');
                }
            }
        }

        $transaksi->update(['status_pesanan' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}