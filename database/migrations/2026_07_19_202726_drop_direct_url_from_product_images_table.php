<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the direct_url column from product_images.
     * This column was previously used as a redundant canonical-URL reference
     * but is no longer consumed by any application logic.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (Schema::hasColumn('product_images', 'direct_url')) {
                $table->dropColumn('direct_url');
            }
        });
    }

    /**
     * Reverse the migration by re-adding direct_url as a nullable text column.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_images', 'direct_url')) {
                $table->text('direct_url')->nullable()->after('active');
            }
        });
    }
};
