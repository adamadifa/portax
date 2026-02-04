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
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            // Drop old foreign key if exists. Try catch block is handled via checking if constraint exists usually, 
            // but here we can just try to drop it or assume it might be different. 
            // Often it is 'marketing_pembelian_kode_supplier_foreign'.
            // However, previous migration 'simplify_marketing_pembelian_table' did not seemingly drop it, 
            // so it might still be referencing 'suppliers' table.
            
             $table->dropForeign(['kode_supplier']);
        });

        Schema::table('marketing_pembelian', function (Blueprint $table) {
             $table->foreign('kode_supplier')
                  ->references('kode_supplier')
                  ->on('supplier_marketing')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            $table->dropForeign(['kode_supplier']);
        });

        Schema::table('marketing_pembelian', function (Blueprint $table) {
             $table->foreign('kode_supplier')
                  ->references('kode_supplier')
                  ->on('supplier')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();
        });
    }
};
