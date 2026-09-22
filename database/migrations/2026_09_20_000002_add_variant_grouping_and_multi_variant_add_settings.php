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
                if (!Schema::hasColumn('product_variants', 'variant_group_name')) {
                    $table->string('variant_group_name', 255)->nullable()->after('variant_custom_name');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'enable_multi_variant_add')) {
                    $table->tinyInteger('enable_multi_variant_add')->default(0)->after('show_variant_selector_thumbnail');
                }
                if (!Schema::hasColumn('products', 'multi_variant_layout')) {
                    $table->string('multi_variant_layout', 20)->default('list')->after('enable_multi_variant_add');
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
                if (Schema::hasColumn('product_variants', 'variant_group_name')) {
                    $table->dropColumn('variant_group_name');
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $dropCols = [];
                if (Schema::hasColumn('products', 'multi_variant_layout')) {
                    $dropCols[] = 'multi_variant_layout';
                }
                if (Schema::hasColumn('products', 'enable_multi_variant_add')) {
                    $dropCols[] = 'enable_multi_variant_add';
                }
                if (!empty($dropCols)) {
                    $table->dropColumn($dropCols);
                }
            });
        }
    }
};
