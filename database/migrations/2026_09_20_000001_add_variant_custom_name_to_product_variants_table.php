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
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                if (!Schema::hasColumn('product_variants', 'variant_custom_name')) {
                    $table->string('variant_custom_name', 255)->nullable()->after('part_number');
                }
                if (!Schema::hasColumn('product_variants', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('variant_custom_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $dropColumns = [];
                if (Schema::hasColumn('product_variants', 'sort_order')) {
                    $dropColumns[] = 'sort_order';
                }
                if (Schema::hasColumn('product_variants', 'variant_custom_name')) {
                    $dropColumns[] = 'variant_custom_name';
                }
                if (!empty($dropColumns)) {
                    $table->dropColumn($dropColumns);
                }
            });
        }
    }
};
