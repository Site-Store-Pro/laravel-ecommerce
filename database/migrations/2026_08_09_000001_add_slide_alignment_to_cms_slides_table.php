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
        if (Schema::hasTable('cms_slides') && !Schema::hasColumn('cms_slides', 'slide_alignment')) {
            Schema::table('cms_slides', function (Blueprint $table) {
                $table->string('slide_alignment', 50)->nullable()->default('middle-center')->after('slide_heading_css');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cms_slides') && Schema::hasColumn('cms_slides', 'slide_alignment')) {
            Schema::table('cms_slides', function (Blueprint $table) {
                $table->dropColumn('slide_alignment');
            });
        }
    }
};
