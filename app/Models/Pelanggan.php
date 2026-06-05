<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pelanggan extends Authenticatable
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $fillable = ['username', 'nama_pelanggan', 'password', 'alamat', 'no_telpon'];
    protected $hidden = ['password', 'remember_token'];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }
}