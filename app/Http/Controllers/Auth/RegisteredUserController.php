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
            'username' => 'required|string|max:255|unique:pelanggan',
            'password' => 'required|string|min:8|confirmed',
            'alamat' => 'nullable|string',
            'no_telpon' => 'nullable|string|max:15',
        ]);

        $user = Pelanggan::create([
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