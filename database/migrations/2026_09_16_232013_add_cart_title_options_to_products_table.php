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
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('show_sku_in_cart')->nullable()->default(null)->after('max_qty')
                ->comment('null=inherit global, 1=show, 0=hide');
            $table->tinyInteger('show_variant_in_cart')->nullable()->default(null)->after('show_sku_in_cart')
                ->comment('null=inherit global, 1=show, 0=hide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['show_sku_in_cart', 'show_variant_in_cart']);
        });
    }
};
