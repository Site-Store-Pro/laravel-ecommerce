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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('order_shipping', 10, 2)->default(0.00)->change();
        });

        // Restore exact decimal shipping amounts on existing orders where the previous INT column
        // rounded the quoted shipping rate (e.g. $6.45 -> 6, $10.80 -> 11) while order_total
        // preserved the exact decimal sum (order_subtotal + order_taxes + order_shipping + order_handling).
        DB::statement('
            UPDATE orders
            SET order_shipping = ROUND(order_total - order_subtotal - order_taxes - order_handling, 2)
            WHERE order_total >= (order_subtotal + order_taxes + order_handling)
              AND ROUND(order_total - order_subtotal - order_taxes - order_handling, 0) = ROUND(order_shipping, 0)
              AND ROUND(order_total - order_subtotal - order_taxes - order_handling, 2) <> order_shipping
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('order_shipping')->default(0)->change();
        });
    }
};
