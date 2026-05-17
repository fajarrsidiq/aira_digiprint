<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';  
    protected $fillable = [
        'no_invoice', 'id_pelanggan', 'id_petugas', 'id_pembayaran',
        'tanggal', 'total_tagihan', 'jumlah_bayar', 'bukti_bayar', 'status_pesanan'
    ];
}