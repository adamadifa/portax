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
        Schema::create('harga_supplier', function (Blueprint $table) {
            $table->char('kode_produk', 6)->primary();
            $table->integer('harga');
            $table->timestamps();
            
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_supplier');
    }
};
