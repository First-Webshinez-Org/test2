<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustment_lines', function (Blueprint $table) {
            $table->integer('imei_no_line_id')->after('lot_no_line_id')->nullable();
            $table->text('imei_with_warranty')->after('imei_no_line_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustment_lines', function (Blueprint $table) {
            $table->dropColumn('imei_no_line_id');
            $table->dropColumn('imei_with_warranty');
        });
    }
};
