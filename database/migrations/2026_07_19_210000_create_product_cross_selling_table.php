<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_cross_selling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->foreignId('cross_sell_product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();
            $table->float('sort_order')->default(0);
            $table->boolean('display_on_item_view')->default(true);
            $table->boolean('display_on_post_cart')->default(false);
            $table->timestamps();

            // A product can only cross-sell another product once
            $table->unique(['product_id', 'cross_sell_product_id'], 'unique_cross_sell_pair');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_cross_selling');
    }
};
