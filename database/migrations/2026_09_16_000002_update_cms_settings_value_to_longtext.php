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
        if (Schema::hasTable('cms_settings') && Schema::hasColumn('cms_settings', 'value')) {
            Schema::table('cms_settings', function (Blueprint $table) {
                $table->longText('value')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('cms_settings') && Schema::hasColumn('cms_settings', 'value')) {
            Schema::table('cms_settings', function (Blueprint $table) {
                $table->text('value')->nullable()->change();
            });
        }
    }
};
