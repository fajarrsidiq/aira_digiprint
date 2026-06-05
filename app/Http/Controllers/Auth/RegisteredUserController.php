<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pelanggan',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
        ], [
            'nama_pelanggan.required' => 'Kolom Nama Lengkap wajib diisi.',
            'username.required' => 'Kolom Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar, silakan gunakan username yang lain.',
            'password.required' => 'Kolom Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'alamat.required' => 'Kolom Alamat wajib diisi.',
            'no_telpon.required' => 'Kolom No. Telepon wajib diisi.',
            'no_telpon.max' => 'No. Telepon maksimal terdiri dari 15 karakter.',
        ]);

        $user = Pelanggan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'no_telpon' => $request->no_telpon,
        ]);

        event(new Registered($user));

        Auth::guard('pelanggan')->login($user);

        return redirect()->route('dashboard.pelanggan');
    }
}