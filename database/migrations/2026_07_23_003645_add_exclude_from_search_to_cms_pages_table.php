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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('cms_pages', 'exclude_from_search')) {
                $table->boolean('exclude_from_search')->default(false)->after('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            if (Schema::hasColumn('cms_pages', 'exclude_from_search')) {
                $table->dropColumn('exclude_from_search');
            }
        });
    }
};
