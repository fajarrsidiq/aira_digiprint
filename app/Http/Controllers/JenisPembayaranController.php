<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use Illuminate\Http\Request;

class JenisPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $jenisPembayaran = JenisPembayaran::when($search, function ($query, $search) {
                return $query->where('nama_metode', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('jenis_pembayaran.index', compact('jenisPembayaran'));
    }

    public function create()
    {
        return view('jenis_pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:50|unique:jenis_pembayaran',
            'no_rekening' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        JenisPembayaran::create($request->all());
        return redirect()->route('jenispembayaran.index')->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function edit(JenisPembayaran $jenispembayaran)
    {
        return view('jenis_pembayaran.edit', compact('jenispembayaran'));
    }

    public function update(Request $request, JenisPembayaran $jenispembayaran)
    {
        $request->validate([
            'nama_metode' => 'required|string|max:50|unique:jenis_pembayaran,nama_metode,' . $jenispembayaran->id_jenis_pembayaran . ',id_jenis_pembayaran',
            'no_rekening' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        $jenispembayaran->update($request->all());
        return redirect()->route('jenispembayaran.index')->with('success', 'Jenis pembayaran berhasil diperbarui.');
    }

    public function destroy(JenisPembayaran $jenispembayaran)
    {
        if ($jenispembayaran->transaksis()->exists()) {
            return redirect()->route('jenispembayaran.index')
                ->with('error', 'Jenis pembayaran "' . $jenispembayaran->nama_metode . '" tidak bisa dihapus karena memiliki riwayat transaksi!');
        }

        $jenispembayaran->delete();
        return redirect()->route('jenispembayaran.index')->with('success', 'Jenis pembayaran berhasil dihapus.');
    }
}