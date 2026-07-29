<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // When enabled, the product detail page displays a live "Item Total"
            // (unit price × qty) below the Add to Cart button. Off by default.
            $table->tinyInteger('show_item_total')->default(0)->after('featured_item');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('show_item_total');
        });
    }
};
