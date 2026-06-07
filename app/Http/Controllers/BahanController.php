<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
   public function index(Request $request)
    {
        $search = $request->get('search');

        $bahans = Bahan::when($search, function ($query, $search) {
                return $query->where('nama_bahan', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('bahan.index', compact('bahans'));
    }

    public function create()
    {
        return view('bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:m_bahan',
        ], [
            'nama_bahan.required' => 'Kolom Nama Bahan wajib diisi.',
            'nama_bahan.string' => 'Nama bahan harus berupa teks.',
            'nama_bahan.max' => 'Nama bahan maksimal terdiri dari 100 karakter.',
            'nama_bahan.unique' => 'Nama bahan sudah terdaftar, silakan gunakan nama yang lain.',
        ]);
        Bahan::create($request->all());
        return redirect()->route('bahan.index')->with('sucess', 'Bahan berhasil ditambahkan.');
    }

    public function edit(Bahan $bahan)
    {
        return view('bahan.edit', compact('bahan'));
    }

    public function update(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:100|unique:m_bahan,nama_bahan,' . $bahan->id_bahan . ',id_bahan',
        ], [
            'nama_bahan.required' => 'Kolom Nama Bahan wajib diisi.',
            'nama_bahan.string' => 'Nama bahan harus berupa teks.',
            'nama_bahan.max' => 'Nama bahan maksimal terdiri dari 100 karakter.',
            'nama_bahan.unique' => 'Nama bahan sudah terdaftar, silakan gunakan nama yang lain.',
        ]);
        $bahan->update($request->all());
        return redirect()->route('bahan.index')->with('success', 'Bahan berhasil diperbarui.');
    }

    public function destroy(Bahan $bahan)
    {
        if ($bahan->produks()->withTrashed()->exists()) {
            return redirect()->route('bahan.index')
                ->with('error', 'Bahan "' . $bahan->nama_bahan . '" tidak bisa dihapus karena masih digunakan oleh produk!');
        }

        $bahan->delete();
        return redirect()->route('bahan.index')->with('success', 'Bahan berhasil dihapus.');
    }
}
