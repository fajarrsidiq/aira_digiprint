<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::orderBy('level')->paginate(10);
        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:petugas',
            'email' => 'nullable|email|unique:petugas',
            'level' => 'required|in:Administrasi,Desain,Produksi',
            'password' => 'required|min:6|confirmed',
        ]);

        $data = $request->except('password_confirmation');
        $data['password'] = Hash::make($data['password']);
        Petugas::create($data);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Petugas $petugas)
    {
        // Owner tidak bisa diedit (opsional, tapi lebih baik dilarang di controller juga)
        if ($petugas->level === 'Owner') {
            return redirect()->route('petugas.index')->with('error', 'Data Owner tidak dapat diedit.');
        }
        return view('petugas.edit', compact('petugas'));
    }

    public function update(Request $request, Petugas $petugas)
    {
        if ($petugas->level === 'Owner') {
            return redirect()->route('petugas.index')->with('error', 'Data Owner tidak dapat diedit.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => ['required', 'string', 'max:50', Rule::unique('petugas')->ignore($petugas->id_petugas, 'id_petugas')],
            'email' => ['nullable', 'email', Rule::unique('petugas')->ignore($petugas->id_petugas, 'id_petugas')],
            'level' => 'required|in:Administrasi,Desain,Produksi',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->except(['password_confirmation', 'password']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diperbarui.');
    }

    public function destroy(Petugas $petugas)
    {
        if ($petugas->level === 'Owner') {
            return back()->with('error', 'Owner tidak dapat dihapus.');
        }
        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}