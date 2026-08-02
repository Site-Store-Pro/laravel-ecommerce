<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kb_categories') && !Schema::hasColumn('kb_categories', 'is_demo')) {
            Schema::table('kb_categories', function (Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('kb_articles') && !Schema::hasColumn('kb_articles', 'is_demo')) {
            Schema::table('kb_articles', function (Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kb_categories') && Schema::hasColumn('kb_categories', 'is_demo')) {
            Schema::table('kb_categories', function (Blueprint $table) {
                $table->dropColumn('is_demo');
            });
        }

        if (Schema::hasTable('kb_articles') && Schema::hasColumn('kb_articles', 'is_demo')) {
            Schema::table('kb_articles', function (Blueprint $table) {
                $table->dropColumn('is_demo');
            });
        }
    }
};
