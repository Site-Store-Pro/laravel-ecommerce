<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'opt_in')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('opt_in')->default(false)->after('remember_token');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'opt_in')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('opt_in');
            });
        }
    }
};
