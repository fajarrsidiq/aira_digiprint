<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';
    protected $fillable = [
        'id_transaksi', 'id_produk', 'keterangan_ukuran',
        'upload_desain', 'qty', 'subtotal'
    ];

    // Relasi ke Transaksi (opsional, jika diperlukan nanti)
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi ke Produk (opsional)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}