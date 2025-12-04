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
        Schema::create('marketing_pembelian', function (Blueprint $table) {
            $table->char('no_bukti', 13)->primary();
            $table->date('tanggal');
            $table->char('kode_supplier', 13);
            $table->char('kode_salesman', 7);
            $table->char('kode_akun', 6)->default('1-1401');
            $table->char('kode_akun_potongan', 6)->default('4-2201');
            $table->char('kode_akun_penyesuaian', 6)->default('4-2202');
            $table->integer('potongan_aida')->default(0);
            $table->integer('potongan_swan')->default(0);
            $table->integer('potongan_stick')->default(0);
            $table->integer('potongan_sp')->default(0);
            $table->integer('potongan_sambal')->default(0);
            $table->integer('potongan')->default(0);
            $table->integer('potis_aida')->default(0);
            $table->integer('potis_swan')->default(0);
            $table->integer('potis_stick')->default(0);
            $table->integer('potongan_istimewa')->default(0);
            $table->integer('peny_aida')->default(0);
            $table->integer('peny_swan')->default(0);
            $table->integer('peny_stick')->default(0);
            $table->integer('penyesuaian')->default(0);
            $table->integer('ppn')->default(0);
            $table->char('jenis_transaksi', 1);
            $table->char('jenis_bayar', 2);
            $table->date('jatuh_tempo')->nullable();
            $table->char('status', 1)->default('0');
            $table->string('routing')->nullable();
            $table->string('signature')->nullable();
            $table->date('tanggal_pelunasan')->nullable()->index();
            $table->integer('print')->default(0);
            $table->bigInteger('id_user');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->char('status_batal', 1)->default('0');
            $table->char('lock_print', 1)->default('0');
            $table->index('tanggal');
            $table->index('kode_supplier');
            $table->index('kode_salesman');
            $table->foreign('kode_supplier')->references('kode_supplier')->on('supplier')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_pembelian');
    }
};
