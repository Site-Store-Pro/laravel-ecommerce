<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds variant_id to shopping_cart_log so that subscription-detection
 * logic in preparePayment() and addToCart() can efficiently look up
 * variant gateway price IDs without parsing item_name strings.
 *
 * Nullable (0 = unknown/legacy rows) for backwards compatibility.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('shopping_cart_log', 'variant_id')) {
            Schema::table('shopping_cart_log', function (Blueprint $table) {
                $table->unsignedBigInteger('variant_id')->default(0)->after('item_downloadable');
                $table->index('variant_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('shopping_cart_log', 'variant_id')) {
            Schema::table('shopping_cart_log', function (Blueprint $table) {
                $table->dropIndex(['variant_id']);
                $table->dropColumn('variant_id');
            });
        }
    }
};
