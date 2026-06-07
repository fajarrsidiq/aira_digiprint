<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $petugas = Petugas::when($search, function ($query, $search) {
                return $query->where('nama_lengkap', 'like', '%' . $search . '%')
                             ->orWhere('username', 'like', '%' . $search . '%');
            })
            ->orderBy('level')
            ->paginate(10);

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
            'username'     => 'required|string|max:50|unique:petugas,username',
            'email'        => 'nullable|email|unique:petugas,email',
            'level'        => 'required|in:Administrasi,Desain,Produksi',
            'password'     => 'required|min:6|confirmed',
        ], [
            'nama_lengkap.required' => 'Kolom Nama Lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal terdiri dari 100 karakter.',
            'username.required'     => 'Kolom Username wajib diisi.',
            'username.max'          => 'Username maksimal terdiri dari 50 karakter.',
            'username.unique'       => 'Username sudah terdaftar, silakan gunakan username lain.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar, silakan gunakan email lain.',
            'level.required'        => 'Silakan pilih level/jabatan petugas.',
            'level.in'              => 'Level yang dipilih tidak valid.',
            'password.required'     => 'Kolom Password wajib diisi.',
            'password.min'          => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        $data = $request->except('password_confirmation');
        $data['password'] = Hash::make($data['password']);
        Petugas::create($data);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit(Petugas $petugas)
    {
        // Akses Owner diproteksi dari pengeditan langsung demi keamanan sistem
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

        // Pengecekan data unik disinkronkan dengan id_petugas & nama kolom spesifik
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => ['required', 'string', 'max:50', Rule::unique('petugas', 'username')->ignore($petugas->id_petugas, 'id_petugas')],
            'email'        => ['nullable', 'email', Rule::unique('petugas', 'email')->ignore($petugas->id_petugas, 'id_petugas')],
            'level'        => 'required|in:Administrasi,Desain,Produksi',
            'password'     => 'nullable|min:6|confirmed',
        ], [
            'nama_lengkap.required' => 'Kolom Nama Lengkap wajib diisi.',
            'nama_lengkap.max'      => 'Nama lengkap maksimal terdiri dari 100 karakter.',
            'username.required'     => 'Kolom Username wajib diisi.',
            'username.max'          => 'Username maksimal terdiri dari 50 karakter.',
            'username.unique'       => 'Username sudah terdaftar, silakan gunakan username lain.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar, silakan gunakan email lain.',
            'level.required'        => 'Silakan pilih level/jabatan petugas.',
            'level.in'              => 'Level yang dipilih tidak valid.',
            'password.min'          => 'Password baru minimal terdiri dari 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        $data = $request->except(['password_confirmation', 'password']);
        
        // Hanya enkripsi & update password jika kolom diisi oleh admin/user
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

        // Mencegah error Relational Integrity Cascade di database jika petugas sudah bertransaksi
        if ($petugas->transaksis()->exists()) {
            return redirect()->route('petugas.index')
                ->with('error', 'Petugas "' . $petugas->nama_lengkap . '" tidak bisa dihapus karena namanya tercatat dalam riwayat transaksi!');
        }
        
        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}