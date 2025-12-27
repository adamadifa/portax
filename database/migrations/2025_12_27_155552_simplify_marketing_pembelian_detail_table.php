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
        // Drop foreign key untuk kode_harga terlebih dahulu
        try {
            Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
                $table->dropForeign(['kode_harga']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada
        }
        
        // Drop index
        try {
            Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
                $table->dropIndex(['kode_harga']);
            });
        } catch (\Exception $e) {
            // Index mungkin sudah tidak ada
        }
        
        try {
            Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
                $table->dropIndex(['status_promosi']);
            });
        } catch (\Exception $e) {
            // Index mungkin sudah tidak ada
        }
        
        // Drop kolom yang tidak diperlukan
        Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
            $table->dropColumn([
                'kode_harga',
                'harga_pack',
                'harga_pcs',
                'status_promosi'
            ]);
        });
        
        // Tambahkan kolom kode_produk
        Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
            $table->char('kode_produk', 6)->after('no_bukti');
            $table->index('kode_produk');
            $table->foreign('kode_produk')->references('kode_produk')->on('produk')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key dan index untuk kode_produk
        try {
            Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
                $table->dropForeign(['kode_produk']);
            });
        } catch (\Exception $e) {
            // Skip jika tidak ada
        }
        
        try {
            Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
                $table->dropIndex(['kode_produk']);
            });
        } catch (\Exception $e) {
            // Skip jika tidak ada
        }
        
        // Drop kolom kode_produk
        Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
            $table->dropColumn('kode_produk');
        });
        
        // Kembalikan kolom yang dihapus
        Schema::table('marketing_pembelian_detail', function (Blueprint $table) {
            $table->char('kode_harga', 7)->after('no_bukti');
            $table->integer('harga_pack')->after('harga_dus');
            $table->integer('harga_pcs')->after('harga_pack');
            $table->char('status_promosi', 1)->default('0')->after('subtotal');
            
            // Kembalikan index dan foreign key
            $table->index('kode_harga');
            $table->index('status_promosi');
            $table->foreign('kode_harga')->references('kode_harga')->on('produk_harga')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
};
