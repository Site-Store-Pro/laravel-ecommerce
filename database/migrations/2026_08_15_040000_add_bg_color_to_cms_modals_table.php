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
        if (Schema::hasTable('cms_modals') && !Schema::hasColumn('cms_modals', 'bg_color')) {
            Schema::table('cms_modals', function (Blueprint $table) {
                $table->string('bg_color', 50)->default('#ffffff')->after('max_width');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cms_modals') && Schema::hasColumn('cms_modals', 'bg_color')) {
            Schema::table('cms_modals', function (Blueprint $table) {
                $table->dropColumn('bg_color');
            });
        }
    }
};
