<?php

namespace App\Http\Controllers;
use App\Models\Produk;

class LandingController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        return view('index', compact('produks'));
    }
}
