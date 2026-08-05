<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── product_brands ────────────────────────────────────────────────────
        Schema::table('product_brands', function (Blueprint $table) {
            // brand_logo_s3 already exists (0=local,1=global s3) — extend to 2=custom
            // Add custom S3 credential fields
            $table->string('brand_logo_cdn_url')->nullable()->after('brand_logo_s3');
            $table->string('brand_logo_region')->nullable()->after('brand_logo_cdn_url');
            $table->string('brand_logo_bucket_name')->nullable()->after('brand_logo_region');
            $table->string('brand_logo_access_key_id')->nullable()->after('brand_logo_bucket_name');
            $table->string('brand_logo_secret_access_key')->nullable()->after('brand_logo_access_key_id');
            // Direct URL option (bypass file upload entirely)
            $table->string('brand_icon_direct_url')->nullable()->after('brand_logo_secret_access_key');
        });

        // ── product_categories ────────────────────────────────────────────────
        Schema::table('product_categories', function (Blueprint $table) {
            // category_image already exists as a varchar path
            // Add S3 mode selector (0=local,1=global s3,2=custom s3)
            $table->unsignedTinyInteger('category_image_s3')->default(0)->after('category_image');
            $table->string('category_image_cdn_url')->nullable()->after('category_image_s3');
            $table->string('category_image_region')->nullable()->after('category_image_cdn_url');
            $table->string('category_image_bucket_name')->nullable()->after('category_image_region');
            $table->string('category_image_access_key_id')->nullable()->after('category_image_bucket_name');
            $table->string('category_image_secret_access_key')->nullable()->after('category_image_access_key_id');
            // Direct URL option (bypass file upload entirely)
            $table->string('category_image_direct_url')->nullable()->after('category_image_secret_access_key');
        });
    }

    public function down(): void
    {
        Schema::table('product_brands', function (Blueprint $table) {
            $table->dropColumn([
                'brand_logo_cdn_url',
                'brand_logo_region',
                'brand_logo_bucket_name',
                'brand_logo_access_key_id',
                'brand_logo_secret_access_key',
                'brand_icon_direct_url',
            ]);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn([
                'category_image_s3',
                'category_image_cdn_url',
                'category_image_region',
                'category_image_bucket_name',
                'category_image_access_key_id',
                'category_image_secret_access_key',
                'category_image_direct_url',
            ]);
        });
    }
};
