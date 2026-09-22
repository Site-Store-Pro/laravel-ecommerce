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
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'hide_gallery_thumbnails')) {
                    $table->tinyInteger('hide_gallery_thumbnails')->default(0)->after('show_variant_selector_thumbnail');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'hide_gallery_thumbnails')) {
                    $table->dropColumn('hide_gallery_thumbnails');
                }
            });
        }
    }
};
