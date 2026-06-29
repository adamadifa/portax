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
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->string('kode_lk')->primary(); // Format: LK-MM-YYYY-CABANG (e.g. LK-06-2026-BDG)
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('kode_cabang')->nullable(); // Nullable for 'Semua Cabang' (ALL)
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_keuangan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lk');
            $table->string('kode_akun');
            $table->double('jumlah');
            $table->timestamps();

            $table->foreign('kode_lk')->references('kode_lk')->on('laporan_keuangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan_detail');
        Schema::dropIfExists('laporan_keuangan');
    }
};
