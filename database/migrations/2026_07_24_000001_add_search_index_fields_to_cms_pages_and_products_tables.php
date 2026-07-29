<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->longText('cms_search_index')->nullable()->after('meta_description');
            $table->boolean('cms_search_index_locked')->default(false)->after('cms_search_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->longText('product_search_index')->nullable()->after('long_description');
            $table->boolean('product_search_index_locked')->default(false)->after('product_search_index');
        });

        // Add FULLTEXT indexes if running on MySQL or MariaDB
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE cms_pages ADD FULLTEXT INDEX cms_pages_fulltext_search (title, cms_search_index, content)');
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX products_fulltext_search (title, product_search_index, short_description)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            try {
                DB::statement('ALTER TABLE cms_pages DROP INDEX cms_pages_fulltext_search');
            } catch (\Throwable $e) {}

            try {
                DB::statement('ALTER TABLE products DROP INDEX products_fulltext_search');
            } catch (\Throwable $e) {}
        }

        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn(['cms_search_index', 'cms_search_index_locked']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_search_index', 'product_search_index_locked']);
        });
    }
};
