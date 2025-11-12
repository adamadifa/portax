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
        Schema::create('marketing_sa_piutangsales_detail', function (Blueprint $table) {
            $table->char('kode_saldo_awal', 8);
            $table->char('no_faktur', 13);
            $table->integer('jumlah');
            $table->foreign('kode_saldo_awal')->references('kode_saldo_awal')->on('marketing_sa_piutangsales')->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_sa_piutangsales_detail');
    }
};
