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
        Schema::table('product_images', function (Blueprint $table) {
            // Codebase/Database mismatch columns
            if (!Schema::hasColumn('product_images', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('variant_id');
            }
            if (!Schema::hasColumn('product_images', 'main_path')) {
                $table->string('main_path')->nullable()->after('thumbnail_path');
            }
            if (!Schema::hasColumn('product_images', 'zoom_path')) {
                $table->string('zoom_path')->nullable()->after('main_path');
            }
            if (!Schema::hasColumn('product_images', 'image_alt')) {
                $table->string('image_alt')->nullable()->after('zoom_path');
            }
            if (!Schema::hasColumn('product_images', 'search_image')) {
                $table->tinyInteger('search_image')->default(0)->after('sort_order');
            }
            if (!Schema::hasColumn('product_images', 'active')) {
                $table->tinyInteger('active')->default(1)->after('search_image');
            }
            if (Schema::hasColumn('product_images', 'image_url')) {
                $table->text('image_url')->nullable()->change();
            }

            if (!Schema::hasColumn('product_images', 'image_s3_region')) {
                $table->string('image_s3_region')->nullable()->after('active');
            }
            if (!Schema::hasColumn('product_images', 'image_s3_bucket_name')) {
                $table->string('image_s3_bucket_name')->nullable()->after('image_s3_region');
            }
            if (!Schema::hasColumn('product_images', 'image_s3_access_key_id')) {
                $table->string('image_s3_access_key_id')->nullable()->after('image_s3_bucket_name');
            }
            if (!Schema::hasColumn('product_images', 'image_s3_secret_access_key')) {
                $table->string('image_s3_secret_access_key')->nullable()->after('image_s3_access_key_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $columns = [
                'thumbnail_path',
                'main_path',
                'zoom_path',
                'image_alt',
                'search_image',
                'active',
                'image_s3_region',
                'image_s3_bucket_name',
                'image_s3_access_key_id',
                'image_s3_secret_access_key',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('product_images', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

