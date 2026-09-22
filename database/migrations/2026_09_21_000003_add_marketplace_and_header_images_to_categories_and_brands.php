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
        if (Schema::hasTable('product_categories')) {
            Schema::table('product_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('product_categories', 'amazon_category')) {
                    $table->text('amazon_category')->nullable()->after('description');
                }
                if (!Schema::hasColumn('product_categories', 'ebay_category')) {
                    $table->text('ebay_category')->nullable()->after('amazon_category');
                }
                if (!Schema::hasColumn('product_categories', 'header_image')) {
                    $table->string('header_image', 2048)->nullable()->after('category_image');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_s3')) {
                    $table->tinyInteger('header_image_s3')->default(0)->after('category_image_direct_url');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_cdn_url')) {
                    $table->string('header_image_cdn_url', 500)->nullable()->after('header_image_s3');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_region')) {
                    $table->string('header_image_region', 100)->nullable()->after('header_image_cdn_url');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_bucket_name')) {
                    $table->string('header_image_bucket_name', 255)->nullable()->after('header_image_region');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_access_key_id')) {
                    $table->string('header_image_access_key_id', 255)->nullable()->after('header_image_bucket_name');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_secret_access_key')) {
                    $table->string('header_image_secret_access_key', 500)->nullable()->after('header_image_access_key_id');
                }
                if (!Schema::hasColumn('product_categories', 'header_image_direct_url')) {
                    $table->string('header_image_direct_url', 1000)->nullable()->after('header_image_secret_access_key');
                }
            });
        }

        if (Schema::hasTable('product_brands')) {
            Schema::table('product_brands', function (Blueprint $table) {
                if (!Schema::hasColumn('product_brands', 'header_image')) {
                    $table->string('header_image', 2048)->nullable()->after('brand_icon');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_s3')) {
                    $table->tinyInteger('header_image_s3')->default(0)->after('brand_icon_direct_url');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_cdn_url')) {
                    $table->string('header_image_cdn_url', 500)->nullable()->after('header_image_s3');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_region')) {
                    $table->string('header_image_region', 100)->nullable()->after('header_image_cdn_url');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_bucket_name')) {
                    $table->string('header_image_bucket_name', 255)->nullable()->after('header_image_region');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_access_key_id')) {
                    $table->string('header_image_access_key_id', 255)->nullable()->after('header_image_bucket_name');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_secret_access_key')) {
                    $table->string('header_image_secret_access_key', 500)->nullable()->after('header_image_access_key_id');
                }
                if (!Schema::hasColumn('product_brands', 'header_image_direct_url')) {
                    $table->string('header_image_direct_url', 1000)->nullable()->after('header_image_secret_access_key');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_categories')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $columns = [
                    'amazon_category',
                    'ebay_category',
                    'header_image',
                    'header_image_s3',
                    'header_image_cdn_url',
                    'header_image_region',
                    'header_image_bucket_name',
                    'header_image_access_key_id',
                    'header_image_secret_access_key',
                    'header_image_direct_url',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('product_categories', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('product_brands')) {
            Schema::table('product_brands', function (Blueprint $table) {
                $columns = [
                    'header_image',
                    'header_image_s3',
                    'header_image_cdn_url',
                    'header_image_region',
                    'header_image_bucket_name',
                    'header_image_access_key_id',
                    'header_image_secret_access_key',
                    'header_image_direct_url',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('product_brands', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
