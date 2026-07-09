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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_produk');
            $table->string('keterangan_ukuran', 100)->nullable();
            $table->string('file_desain')->nullable();
            $table->string('file_desain_final')->nullable();
            $table->enum('status_desain', ['Proses', 'Final'])->default('Proses');
            $table->decimal('harga', 12, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
