<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $fillable = [
        'id_bahan', 'id_satuan', 'nama_produk',
        'ukuran_default', 'harga', 'foto_produk'
    ];

    // Relasi ke Bahan
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'id_bahan');
    }

    // Relasi ke Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }
}