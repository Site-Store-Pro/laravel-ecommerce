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
        if (Schema::hasTable('cms_modals') && !Schema::hasColumn('cms_modals', 'backdrop_blur')) {
            Schema::table('cms_modals', function (Blueprint $table) {
                $table->boolean('backdrop_blur')->default(true)->after('overlay_dismissible');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cms_modals') && Schema::hasColumn('cms_modals', 'backdrop_blur')) {
            Schema::table('cms_modals', function (Blueprint $table) {
                $table->dropColumn('backdrop_blur');
            });
        }
    }
};
