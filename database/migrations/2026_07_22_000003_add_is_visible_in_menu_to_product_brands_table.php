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
        Schema::table('product_brands', function (Blueprint $table) {
            $table->boolean('is_visible_in_menu')->default(true)->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_brands', function (Blueprint $table) {
            $table->dropColumn('is_visible_in_menu');
        });
    }
};
