<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds an is_demo flag to all tables that are populated by DemoStoreSeeder.
 *
 * This allows the admin to detect and purge demo content with one click.
 * The flag defaults to 0 (false) so existing live data is unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'products',
            'product_brands',
            'product_categories',
            'product_variants',
            'product_images',
            'product_cross_selling',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0)->after('updated_at')->comment('1 = seeded by DemoStoreSeeder, eligible for one-click purge');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'products',
            'product_brands',
            'product_categories',
            'product_variants',
            'product_images',
            'product_cross_selling',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('is_demo');
            });
        }
    }
};
