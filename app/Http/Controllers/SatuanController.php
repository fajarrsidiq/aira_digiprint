<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $satuans = Satuan::when($search, function ($query, $search) {
                return $query->where('nama_satuan', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_satuan' => 'required|unique:m_satuan']);
        Satuan::create($request->all());
        return redirect()->route('satuan.index')->with('success', 'Satuan ditambahkan');
    }

    public function edit(Satuan $satuan)
    {
        return view('satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Satuan $satuan)
    {
        $request->validate([
            'nama_satuan' => 'required|unique:m_satuan,nama_satuan,' . $satuan->id_satuan . ',id_satuan'
        ]);

        $satuan->update($request->all());

        return redirect()->route('satuan.index')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Satuan $satuan)
    {
        if ($satuan->produks()->exists()) { 
            return redirect()->route('satuan.index')
            ->with('error', 'Satuan "' . $satuan->nama_satuan . '" tidak bisa dihapus karena masih digunakan oleh produk!');
        }
        
        $satuan->delete();
        return redirect()->route('satuan.index')->with('success', 'Satuan dihapus');
    }
}