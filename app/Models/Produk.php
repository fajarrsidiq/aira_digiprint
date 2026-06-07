<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_bahan', 'id_satuan', 'nama_produk',
        'ukuran_default', 'harga', 'foto_produk'
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }
}