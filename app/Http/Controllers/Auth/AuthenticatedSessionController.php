<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        // --- PENTING: Bersihkan semua guard sebelum mencoba login baru ---
        Auth::guard('petugas')->logout();
        Auth::guard('pelanggan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 1. Coba login sebagai Petugas
        if (Auth::guard('petugas')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.petugas');
        }

        // 2. Jika gagal, coba login sebagai Pelanggan
        if (Auth::guard('pelanggan')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.pelanggan');
        }

        // 3. Jika keduanya gagal
        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function destroy(Request $request)
    {
        // Cukup panggil logout untuk setiap guard yang Anda miliki
        Auth::guard('petugas')->logout();
        Auth::guard('pelanggan')->logout();
        
        // Ini adalah perintah standar Laravel untuk membersihkan sesi sepenuhnya
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}