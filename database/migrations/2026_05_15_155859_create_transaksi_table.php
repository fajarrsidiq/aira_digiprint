<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('no_invoice', 30)->unique();
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->unsignedBigInteger('id_desainer')->nullable();
            $table->unsignedBigInteger('id_pembayaran');
            $table->datetime('tanggal');
            $table->decimal('total_tagihan', 12, 2);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->string('bukti_bayar')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status_pesanan', ['Menunggu Konfirmasi', 'Diproses', 'Selesai', 'Dibatalkan'])->default('Menunggu Konfirmasi');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('id_desainer')->references('id_petugas')->on('petugas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('id_pembayaran')->references('id_jenis_pembayaran')->on('jenis_pembayaran')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
