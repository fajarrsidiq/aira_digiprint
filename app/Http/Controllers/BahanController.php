<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $bahans = Bahan::latest()->paginate(10);
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
        ]);
        $bahan->update($request->all());
        return redirect()->route('bahan.index')->with('success', 'Bahan berhasil diperbarui.');
    }

    public function destroy(Bahan $bahan)
    {
        $bahan->delete();
        return redirect()->route('bahan.index')->with('success', 'Bahan berhasil dihapus.');
    }
}
