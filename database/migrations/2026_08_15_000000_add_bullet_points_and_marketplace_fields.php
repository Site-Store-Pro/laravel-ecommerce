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
            $table->string('bullet_point_1')->nullable()->after('long_description');
            $table->string('bullet_point_2')->nullable()->after('bullet_point_1');
            $table->string('bullet_point_3')->nullable()->after('bullet_point_2');
            $table->string('bullet_point_4')->nullable()->after('bullet_point_3');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            // Sale Date Window & Codes
            $table->dateTime('sale_price_start_at')->nullable()->after('sale_price');
            $table->dateTime('sale_price_end_at')->nullable()->after('sale_price_start_at');
            $table->string('upc_code')->nullable()->after('sku');

            // Item Cost & MAP Price
            $table->decimal('item_cost', 10, 2)->nullable()->after('public_price');
            $table->decimal('item_map', 10, 2)->nullable()->after('item_cost');

            // Shipping Dimensions
            $table->decimal('dimension_length', 10, 2)->nullable()->after('weight_type');
            $table->decimal('dimension_width', 10, 2)->nullable()->after('dimension_length');
            $table->decimal('dimension_height', 10, 2)->nullable()->after('dimension_width');
            $table->string('dimension_unit', 20)->default('in')->nullable()->after('dimension_height');

            // Amazon-Specific Fields
            $table->boolean('amazon_product')->default(false)->after('is_event');
            $table->decimal('amazon_price', 10, 2)->nullable()->after('amazon_product');
            $table->string('amazon_asin')->nullable()->after('amazon_price');
            $table->text('amazon_bullet_points')->nullable()->after('amazon_asin');
            $table->string('amazon_item_type')->nullable()->after('amazon_bullet_points');
            $table->string('amazon_condition', 50)->default('New')->nullable()->after('amazon_item_type');

            // eBay-Specific Fields
            $table->boolean('ebay_product')->default(false)->after('amazon_condition');
            $table->decimal('ebay_price', 10, 2)->nullable()->after('ebay_product');
            $table->string('ebay_category_id')->nullable()->after('ebay_price');
            $table->string('ebay_listing_type', 50)->default('Fixed Price')->nullable()->after('ebay_category_id');
            $table->text('ebay_options')->nullable()->after('ebay_listing_type');
            $table->string('ebay_shipping_profile_id')->nullable()->after('ebay_options');
            $table->string('ebay_return_policy_id')->nullable()->after('ebay_shipping_profile_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'bullet_point_1',
                'bullet_point_2',
                'bullet_point_3',
                'bullet_point_4',
            ]);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'sale_price_start_at',
                'sale_price_end_at',
                'upc_code',
                'item_cost',
                'item_map',
                'dimension_length',
                'dimension_width',
                'dimension_height',
                'dimension_unit',
                'amazon_product',
                'amazon_price',
                'amazon_asin',
                'amazon_bullet_points',
                'amazon_item_type',
                'amazon_condition',
                'ebay_product',
                'ebay_price',
                'ebay_category_id',
                'ebay_listing_type',
                'ebay_options',
                'ebay_shipping_profile_id',
                'ebay_return_policy_id',
            ]);
        });
    }
};
