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
                if (!Schema::hasColumn('product_categories', 'display_label_in_plugins')) {
                    $table->boolean('display_label_in_plugins')->default(true)->after('is_visible_in_menu');
                }
                if (!Schema::hasColumn('product_categories', 'display_image_in_plugins')) {
                    $table->boolean('display_image_in_plugins')->default(true)->after('display_label_in_plugins');
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
                if (Schema::hasColumn('product_categories', 'display_label_in_plugins')) {
                    $table->dropColumn('display_label_in_plugins');
                }
                if (Schema::hasColumn('product_categories', 'display_image_in_plugins')) {
                    $table->dropColumn('display_image_in_plugins');
                }
            });
        }
    }
};
