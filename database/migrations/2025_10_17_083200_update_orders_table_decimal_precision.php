<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableDecimalPrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tăng độ dài cho các cột decimal để chứa giá trị lớn hơn
            // decimal(15, 2) có thể chứa tối đa 9,999,999,999,999.99
            $table->decimal('subtotal', 15, 2)->change();
            $table->decimal('tax', 15, 2)->change();
            $table->decimal('shipping', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->change();
            $table->decimal('tax', 10, 2)->change();
            $table->decimal('shipping', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
        });
    }
}
