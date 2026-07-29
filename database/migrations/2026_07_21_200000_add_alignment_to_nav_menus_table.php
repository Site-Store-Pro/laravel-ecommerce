<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->string('alignment', 50)->default('left')->after('show_logo');
        });
    }

    public function down(): void
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->dropColumn('alignment');
        });
    }
};
