<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
        CREATE TRIGGER update_status_lunas_pembelian
        AFTER INSERT ON marketing_pembelian_historibayar
        FOR EACH ROW
        BEGIN
            DECLARE total_bayar DECIMAL(15,2);
            DECLARE total_pembelian DECIMAL(15,2);

            SELECT IFNULL(SUM(jumlah),0) INTO total_bayar
            FROM marketing_pembelian_historibayar
            WHERE no_bukti_pembelian = NEW.no_bukti_pembelian;

            SELECT IFNULL(SUM(subtotal),0) INTO total_pembelian
            FROM marketing_pembelian_detail
            WHERE no_bukti = NEW.no_bukti_pembelian;

            IF (total_bayar >= total_pembelian) THEN
                UPDATE marketing_pembelian SET status = '1' WHERE no_bukti = NEW.no_bukti_pembelian;
            ELSE
                UPDATE marketing_pembelian SET status = '0' WHERE no_bukti = NEW.no_bukti_pembelian;
            END IF;
        END
        ");

        DB::unprepared("
        CREATE TRIGGER update_status_lunas_pembelian_update
        AFTER UPDATE ON marketing_pembelian_historibayar
        FOR EACH ROW
        BEGIN
            DECLARE total_bayar DECIMAL(15,2);
            DECLARE total_pembelian DECIMAL(15,2);

            SELECT IFNULL(SUM(jumlah),0) INTO total_bayar
            FROM marketing_pembelian_historibayar
            WHERE no_bukti_pembelian = NEW.no_bukti_pembelian;

            SELECT IFNULL(SUM(subtotal),0) INTO total_pembelian
            FROM marketing_pembelian_detail
            WHERE no_bukti = NEW.no_bukti_pembelian;

            IF (total_bayar >= total_pembelian) THEN
                UPDATE marketing_pembelian SET status = '1' WHERE no_bukti = NEW.no_bukti_pembelian;
            ELSE
                UPDATE marketing_pembelian SET status = '0' WHERE no_bukti = NEW.no_bukti_pembelian;
            END IF;
        END
        ");

        DB::unprepared("
        CREATE TRIGGER update_status_lunas_pembelian_delete
        AFTER DELETE ON marketing_pembelian_historibayar
        FOR EACH ROW
        BEGIN
            DECLARE total_bayar DECIMAL(15,2);
            DECLARE total_pembelian DECIMAL(15,2);

            SELECT IFNULL(SUM(jumlah),0) INTO total_bayar
            FROM marketing_pembelian_historibayar
            WHERE no_bukti_pembelian = OLD.no_bukti_pembelian;

            SELECT IFNULL(SUM(subtotal),0) INTO total_pembelian
            FROM marketing_pembelian_detail
            WHERE no_bukti = OLD.no_bukti_pembelian;

            IF (total_bayar >= total_pembelian) THEN
                UPDATE marketing_pembelian SET status = '1' WHERE no_bukti = OLD.no_bukti_pembelian;
            ELSE
                UPDATE marketing_pembelian SET status = '0' WHERE no_bukti = OLD.no_bukti_pembelian;
            END IF;
        END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS update_status_lunas_pembelian");
        DB::unprepared("DROP TRIGGER IF EXISTS update_status_lunas_pembelian_update");
        DB::unprepared("DROP TRIGGER IF EXISTS update_status_lunas_pembelian_delete");
    }
};
