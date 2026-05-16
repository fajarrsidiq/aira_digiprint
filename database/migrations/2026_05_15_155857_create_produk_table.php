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
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->unsignedBigInteger('id_bahan');
            $table->unsignedBigInteger('id_satuan');
            $table->string('nama_produk', 100);
            $table->string('ukuran_default', 50)->nullable();
            $table->decimal('harga', 12, 2);
            $table->string('foto_produk')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_bahan')->references('id_bahan')->on('m_bahan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('id_satuan')->references('id_satuan')->on('m_satuan')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
