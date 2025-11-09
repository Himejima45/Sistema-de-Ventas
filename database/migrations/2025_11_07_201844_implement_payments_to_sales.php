<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ImplementPaymentsToSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::insert("
            INSERT INTO payments (sale_id, currency_id, cash_usd, cash_bs, change_usd, change_bs, created_at, updated_at)
            SELECT 
                id as sale_id,
                currency_id,
                COALESCE(cash, 0) as cash_usd,
                COALESCE(bs, 0) as cash_bs,
                COALESCE(`change`, 0) as change_usd,
                0 as change_bs,
                created_at,
                updated_at
            FROM sales 
            WHERE currency_id IS NOT NULL 
            AND (cash > 0 OR `change` > 0 OR bs > 0)
        ");

        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['cash', 'change', 'bs']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('cash', 10, 2)->default(0)->after('currency_id');
            $table->decimal('change', 10, 2)->default(0)->after('cash');
            $table->decimal('bs', 10, 2)->default(0)->after('change');
        });

        DB::update("
            UPDATE sales 
            INNER JOIN payments ON sales.id = payments.sale_id
            SET 
                sales.cash = payments.cash_usd,
                sales.change = payments.change_usd,
                sales.bs = payments.cash_bs
        ");

        DB::delete("DELETE FROM payments WHERE sale_id IS NOT NULL");
    }
}