<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $pelanggans = Pelanggan::when($search, function ($query, $search) {
                return $query->where('nama_pelanggan', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pelanggan',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
        ], [
            'nama_pelanggan.required' => 'Kolom Nama Lengkap wajib diisi.',
            'username.required' => 'Kolom Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username yang lain.',
            'alamat.required' => 'Kolom Alamat wajib diisi.',
            'no_telpon.required' => 'Kolom No. Telepon wajib diisi.',
            'no_telpon.max' => 'No. Telepon maksimal terdiri dari 15 karakter.',
        ]);

        // Password default
        $defaultPassword = 'pelanggan123'; // bisa diganti

        Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
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
            'nama_pelanggan' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('pelanggan')->ignore($pelanggan->id_pelanggan, 'id_pelanggan')],
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
            'reset_password' => 'nullable|boolean',
        ], [
            'nama_pelanggan.required' => 'Kolom Nama Lengkap wajib diisi.',
            'username.required' => 'Kolom Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username yang lain.',
            'alamat.required' => 'Kolom Alamat wajib diisi.',
            'no_telpon.required' => 'Kolom No. Telepon wajib diisi.',
            'no_telpon.max' => 'No. Telepon maksimal terdiri dari 15 karakter.',
        ]);

        $data = $request->only(['nama_pelanggan', 'username', 'alamat', 'no_telpon']);

        // Cek jika kasir mencentang opsi reset password
        if ($request->boolean('reset_password')) {
            $data['password'] = Hash::make('pelanggan123');
        }

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ($pelanggan->transaksis()->exists()) {
            return redirect()->route('pelanggan.index')
                ->with('error', 'Pelanggan "' . $pelanggan->nama_pelanggan . '" tidak bisa dihapus karena memiliki riwayat transaksi!');
        }
        
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}