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
            'role' => 'required|in:petugas,pelanggan',
        ]);

        if ($request->role === 'petugas') {
            Auth::guard('pelanggan')->logout();
        } else {
            Auth::guard('petugas')->logout();
        }

        $guard = $request->role === 'petugas' ? 'petugas' : 'pelanggan';
        $credentials = ['username' => $request->username, 'password' => $request->password];

        if (Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if ($guard === 'petugas') {
                return redirect()->route('dashboard.petugas');
            }
            return redirect()->route('dashboard.pelanggan');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username', 'role');
    }

    public function destroy(Request $request)
    {
        if (Auth::guard('petugas')->check()) Auth::guard('petugas')->logout();
        if (Auth::guard('pelanggan')->check()) Auth::guard('pelanggan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}