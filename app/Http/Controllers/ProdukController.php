<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Bahan;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::with(['bahan', 'satuan'])->latest()->paginate(10);
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        $bahans = Bahan::all();
        $satuans = Satuan::all();
        return view('produk.create', compact('bahans', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_bahan' => 'required|exists:m_bahan,id_bahan',
            'id_satuan' => 'required|exists:m_satuan,id_satuan',
            'nama_produk' => 'required|string|max:100',
            'ukuran_default' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto_produk');
        if ($request->hasFile('foto_produk')) {
            $path = $request->file('foto_produk')->store('produk', 'public');
            $data['foto_produk'] = $path;
        }

        Produk::create($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $produk)
    {
        $bahans = Bahan::all();
        $satuans = Satuan::all();
        return view('produk.edit', compact('produk', 'bahans', 'satuans'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'id_bahan' => 'required|exists:m_bahan,id_bahan',
            'id_satuan' => 'required|exists:m_satuan,id_satuan',
            'nama_produk' => 'required|string|max:100',
            'ukuran_default' => 'nullable|string|max:50',
            'harga' => 'required|numeric|min:0',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('foto_produk');
        if ($request->hasFile('foto_produk')) {
            if ($produk->foto_produk) {
                Storage::disk('public')->delete($produk->foto_produk);
            }
            $path = $request->file('foto_produk')->store('produk', 'public');
            $data['foto_produk'] = $path;
        }

        $produk->update($data);
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        if ($produk->foto_produk) {
            Storage::disk('public')->delete($produk->foto_produk);
        }
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}