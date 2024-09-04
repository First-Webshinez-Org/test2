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
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('line_charge_id')->after('line_discount_amount')->nullable();
            $table->foreign('line_charge_id')->references('id')->on('charges')->onDelete('cascade');
            $table->enum('line_charge_type', ['fixed', 'percentage'])->nullable()->after('line_charge_id');
            $table->decimal('line_charge_amount', 22, 4)->default(0)->after('line_charge_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_sell_lines', function (Blueprint $table) {
            $table->dropColumn('line_charge_id');
            $table->dropColumn('line_charge_type');
            $table->dropColumn('line_charge_amount');
        });
    }
};
