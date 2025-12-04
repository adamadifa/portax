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
        Schema::create('marketing_pembelian_detail', function (Blueprint $table) {
            $table->char('no_bukti', 13);
            $table->char('kode_harga', 7);
            $table->integer('harga_dus');
            $table->integer('harga_pack');
            $table->integer('harga_pcs');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->char('status_promosi', 1)->default('0')->index();
            $table->timestamps();
            $table->index('no_bukti');
            $table->index('kode_harga');
            $table->foreign('no_bukti')->references('no_bukti')->on('marketing_pembelian')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_harga')->references('kode_harga')->on('produk_harga')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pembelian_detail');
    }
};
