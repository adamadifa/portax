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
        Schema::create('marketing_pembelian_historibayar', function (Blueprint $table) {
            $table->char('no_bukti', 14)->primary();
            $table->char('no_bukti_pembelian', 13);
            $table->date('tanggal');
            $table->char('jenis_bayar', 2);
            $table->integer('jumlah');
            $table->char('voucher', 1)->default(0);
            $table->string('jenis_voucher', 1)->default(0);
            $table->char('kode_akun', 6)->default('2-1200')->comment('Hutang Dagang');
            $table->bigInteger('id_user')->unsigned();
            $table->timestamps();
            $table->index('tanggal');
            $table->foreign('no_bukti_pembelian')->references('no_bukti')->on('marketing_pembelian')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pembelian_historibayar');
    }
};
