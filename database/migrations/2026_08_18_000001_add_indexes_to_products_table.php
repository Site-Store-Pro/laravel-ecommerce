<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['active', 'show_in_results'], 'products_active_show_in_results_idx');
            $table->index(['featured_item'], 'products_featured_item_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_show_in_results_idx');
            $table->dropIndex('products_featured_item_idx');
        });
    }
};
