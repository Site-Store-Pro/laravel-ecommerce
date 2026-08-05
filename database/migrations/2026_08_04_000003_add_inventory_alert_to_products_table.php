<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('inventory_alert_id')
                  ->nullable()
                  ->after('hide_inventory_levels')
                  ->comment('FK to product_inventory_alerts; null = use default out-of-stock text');

            $table->foreign('inventory_alert_id')
                  ->references('id')
                  ->on('product_inventory_alerts')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['inventory_alert_id']);
            $table->dropColumn('inventory_alert_id');
        });
    }
};
