<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_brands', function (Blueprint $table) {
            // Controls whether the brand image/icon is displayed in the mega menu.
            // Defaults to true so existing brands with images keep showing them.
            $table->boolean('show_image')->default(true)->after('is_visible_in_menu');
        });
    }

    public function down(): void
    {
        Schema::table('product_brands', function (Blueprint $table) {
            $table->dropColumn('show_image');
        });
    }
};
