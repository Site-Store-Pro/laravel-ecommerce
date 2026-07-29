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
        Schema::table('nav_menus', function (Blueprint $table) {
            if (!Schema::hasColumn('nav_menus', 'sticky_body_offset')) {
                $table->string('sticky_body_offset')->nullable()->default('0px')->after('sticky');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            if (Schema::hasColumn('nav_menus', 'sticky_body_offset')) {
                $table->dropColumn('sticky_body_offset');
            }
        });
    }
};
