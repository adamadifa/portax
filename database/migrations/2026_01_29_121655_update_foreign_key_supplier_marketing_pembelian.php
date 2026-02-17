<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Check if a foreign key exists on a table.
     */
    private function hasForeignKey(string $table, string $keyName): bool
    {
        $foreignKeys = Schema::getForeignKeys($table);
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey['name'] === $keyName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            if ($this->hasForeignKey('marketing_pembelian', 'marketing_pembelian_kode_supplier_foreign')) {
                $table->dropForeign(['kode_supplier']);
            }
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
            if ($this->hasForeignKey('marketing_pembelian', 'marketing_pembelian_kode_supplier_foreign')) {
                $table->dropForeign(['kode_supplier']);
            }
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
