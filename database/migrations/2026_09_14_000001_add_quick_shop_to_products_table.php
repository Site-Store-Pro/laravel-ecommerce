<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'quick_shop_active')) {
                    $table->boolean('quick_shop_active')->default(0)->after('show_variant_selector_thumbnail');
                }
                if (!Schema::hasColumn('products', 'quick_shop_label')) {
                    $table->string('quick_shop_label', 255)->nullable()->after('quick_shop_active');
                }
            });
        }

        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                if (!Schema::hasColumn('product_translations', 'quick_shop_label')) {
                    $table->string('quick_shop_label', 255)->nullable()->after('meta_description');
                }
            });
        }

        // Insert default site label for catalog.quick_shop if missing
        if (Schema::hasTable('site_labels')) {
            $exists = DB::table('site_labels')->where('label_key', 'catalog.quick_shop')->exists();
            if (!$exists) {
                DB::table('site_labels')->insert([
                    'label_key'         => 'catalog.quick_shop',
                    'section_id'        => 6, // Catalog section
                    'file_name'         => 'shop-catalog.blade.php',
                    'label_description' => 'Quick Shop button label',
                    'label_default'     => 'Quick Shop',
                    'label_custom'      => null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'quick_shop_label')) {
                    $table->dropColumn('quick_shop_label');
                }
                if (Schema::hasColumn('products', 'quick_shop_active')) {
                    $table->dropColumn('quick_shop_active');
                }
            });
        }

        if (Schema::hasTable('product_translations')) {
            Schema::table('product_translations', function (Blueprint $table) {
                if (Schema::hasColumn('product_translations', 'quick_shop_label')) {
                    $table->dropColumn('quick_shop_label');
                }
            });
        }

        if (Schema::hasTable('site_labels')) {
            DB::table('site_labels')->where('label_key', 'catalog.quick_shop')->delete();
        }
    }
};
