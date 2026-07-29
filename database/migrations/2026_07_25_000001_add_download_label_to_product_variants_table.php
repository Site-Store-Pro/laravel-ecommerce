<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_variants', 'download_label')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->string('download_label')->nullable()->after('direct_download_url');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_variants', 'download_label')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->dropColumn('download_label');
            });
        }
    }
};
