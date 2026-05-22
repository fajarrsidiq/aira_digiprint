<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::latest()->paginate(10);
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:pelanggan',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
        ]);

        // Password default
        $defaultPassword = 'pelanggan123'; // bisa diganti

        Pelanggan::create([
            'username' => $request->username,
            'password' => Hash::make($defaultPassword),
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan (password default: ' . $defaultPassword . ')');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('pelanggan')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
            'reset_password' => 'nullable|boolean',
        ]);

        $data = $request->only(['username', 'alamat', 'no_telpon']);
        
        if ($request->boolean('reset_password')) {
            $data['password'] = Hash::make('pelanggan123');
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // Cek apakah pelanggan memiliki transaksi (jika ada relasi, bisa dicegah)
        // if ($pelanggan->transaksi()->count() > 0) { ... }
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}