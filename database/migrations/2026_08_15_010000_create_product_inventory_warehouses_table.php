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
        Schema::create('product_inventory_warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_inventory_id')
                ->constrained('products_inventory')
                ->onDelete('cascade');
            $table->foreignId('warehouse_location_id')
                ->constrained('warehouse_locations')
                ->onDelete('cascade');
            $table->integer('stock_level')->default(0);
            $table->timestamps();

            $table->unique(['product_inventory_id', 'warehouse_location_id'], 'piw_inventory_warehouse_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventory_warehouses');
    }
};
