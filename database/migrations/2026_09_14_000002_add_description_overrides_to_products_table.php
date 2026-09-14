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
            if (!Schema::hasColumn('products', 'search_results_description')) {
                $table->text('search_results_description')->nullable()->after('short_description');
            }
            if (!Schema::hasColumn('products', 'quick_shop_description')) {
                $table->text('quick_shop_description')->nullable()->after('search_results_description');
            }
        });

        Schema::table('product_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('product_translations', 'search_results_description')) {
                $table->text('search_results_description')->nullable()->after('short_description');
            }
            if (!Schema::hasColumn('product_translations', 'quick_shop_description')) {
                $table->text('quick_shop_description')->nullable()->after('search_results_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['search_results_description', 'quick_shop_description']);
        });

        Schema::table('product_translations', function (Blueprint $table) {
            $table->dropColumn(['search_results_description', 'quick_shop_description']);
        });
    }
};
