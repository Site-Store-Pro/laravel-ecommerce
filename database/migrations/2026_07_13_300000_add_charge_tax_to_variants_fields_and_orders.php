<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add charge_tax to product_variants (default 1 = taxable)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->tinyInteger('charge_tax')->default(1)->after('download_item');
        });

        // Add charge_tax to product_fields (default 1 = taxable)
        Schema::table('product_fields', function (Blueprint $table) {
            $table->tinyInteger('charge_tax')->default(1)->after('is_required');
        });

        // Add item_taxable to order_details (preserve tax status on placed orders)
        Schema::table('order_details', function (Blueprint $table) {
            $table->tinyInteger('item_taxable')->default(1)->after('download_item');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('charge_tax');
        });
        Schema::table('product_fields', function (Blueprint $table) {
            $table->dropColumn('charge_tax');
        });
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('item_taxable');
        });
    }
};
