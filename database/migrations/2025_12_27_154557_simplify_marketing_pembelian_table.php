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
        // Drop foreign key untuk kode_salesman terlebih dahulu
        try {
            Schema::table('marketing_pembelian', function (Blueprint $table) {
                $table->dropForeign(['kode_salesman']);
            });
        } catch (\Exception $e) {
            // Foreign key mungkin sudah tidak ada atau nama berbeda, skip
        }
        
        // Drop index (harus dilakukan sebelum drop column)
        try {
            Schema::table('marketing_pembelian', function (Blueprint $table) {
                $table->dropIndex(['kode_salesman']);
            });
        } catch (\Exception $e) {
            // Index mungkin sudah tidak ada
        }
        
        try {
            Schema::table('marketing_pembelian', function (Blueprint $table) {
                $table->dropIndex(['tanggal_pelunasan']);
            });
        } catch (\Exception $e) {
            // Index mungkin sudah tidak ada
        }
        
        // Drop kolom yang tidak diperlukan
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            $table->dropColumn([
                'kode_salesman',
                'kode_akun_potongan',
                'kode_akun_penyesuaian',
                'potongan_aida',
                'potongan_swan',
                'potongan_stick',
                'potongan_sp',
                'potongan_sambal',
                'potongan',
                'potis_aida',
                'potis_swan',
                'potis_stick',
                'potongan_istimewa',
                'peny_aida',
                'peny_swan',
                'peny_stick',
                'penyesuaian',
                'ppn',
                'jatuh_tempo',
                'routing',
                'signature',
                'tanggal_pelunasan',
                'print',
                'keterangan',
                'status_batal',
                'lock_print'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_pembelian', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->char('kode_salesman', 7)->after('kode_supplier');
            $table->char('kode_akun_potongan', 6)->default('4-2201')->after('kode_akun');
            $table->char('kode_akun_penyesuaian', 6)->default('4-2202')->after('kode_akun_potongan');
            $table->integer('potongan_aida')->default(0)->after('kode_akun_penyesuaian');
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
            $table->date('jatuh_tempo')->nullable()->after('jenis_bayar');
            $table->string('routing')->nullable();
            $table->string('signature')->nullable();
            $table->date('tanggal_pelunasan')->nullable();
            $table->integer('print')->default(0);
            $table->string('keterangan')->nullable();
            $table->char('status_batal', 1)->default('0');
            $table->char('lock_print', 1)->default('0');
            
            // Kembalikan index dan foreign key
            $table->index('kode_salesman');
            $table->index('tanggal_pelunasan');
            $table->foreign('kode_salesman')->references('kode_salesman')->on('salesman')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
};
