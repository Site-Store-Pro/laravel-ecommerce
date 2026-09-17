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
        // 1. Optimize `products` table for catalog filtering, sorting, and joins
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $this->addIndexIfNotExists('products', ['active', 'show_in_results', 'brand_id'], 'idx_products_active_results_brand', $table);
                $this->addIndexIfNotExists('products', ['active', 'show_in_results', 'featured_item'], 'idx_products_active_results_featured', $table);
                $this->addIndexIfNotExists('products', ['active', 'show_in_results', 'created_at'], 'idx_products_active_results_created', $table);
                $this->addIndexIfNotExists('products', ['active', 'show_in_results', 'reviews_rating'], 'idx_products_active_results_rating', $table);
                $this->addIndexIfNotExists('products', ['active', 'show_in_results', 'id'], 'idx_products_active_results_id', $table);
            });

            // Index title with prefix length for text columns in MySQL
            $this->addTextPrefixIndexIfNotExists('products', ['active', 'show_in_results'], 'title', 191, 'idx_products_active_results_title');
        }

        // 2. Optimize `product_variants` table for price calculation, sorting, and filtering
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_variants', ['product_id', 'on_sale', 'sale_price', 'public_price'], 'idx_variants_prod_price_calc', $table);
                $this->addIndexIfNotExists('product_variants', ['product_id', 'public_price'], 'idx_variants_prod_public_price', $table);
                $this->addIndexIfNotExists('product_variants', ['product_id', 'wholesale_price'], 'idx_variants_prod_wholesale_price', $table);
                $this->addIndexIfNotExists('product_variants', ['public_price'], 'idx_variants_public_price', $table);
                $this->addIndexIfNotExists('product_variants', ['sale_price'], 'idx_variants_sale_price', $table);
                $this->addIndexIfNotExists('product_variants', ['on_sale'], 'idx_variants_on_sale', $table);
            });
        }

        // 3. Optimize `product_images` table for fast search and gallery image resolution
        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_images', ['variant_id', 'active', 'search_image'], 'idx_prod_images_var_active_search', $table);
                $this->addIndexIfNotExists('product_images', ['variant_id', 'active'], 'idx_prod_images_var_active', $table);
            });
        }

        // 4. Optimize `products_inventory` / `product_inventories` for stock lookup
        $invTable = Schema::hasTable('products_inventory') ? 'products_inventory' : (Schema::hasTable('product_inventories') ? 'product_inventories' : null);
        if ($invTable) {
            Schema::table($invTable, function (Blueprint $table) use ($invTable) {
                $this->addIndexIfNotExists($invTable, ['variant_id', 'quantity_available'], 'idx_inventory_variant_qty', $table);
            });
        }

        // 5. Optimize `product_categories` for fast hierarchy traversal and menu rendering
        if (Schema::hasTable('product_categories')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_categories', ['parent_id', 'is_visible_in_menu', 'sort_order'], 'idx_categories_parent_menu_sort', $table);
                $this->addIndexIfNotExists('product_categories', ['is_visible_in_menu', 'sort_order', 'name'], 'idx_categories_menu_sort_name', $table);
            });
        }

        // 6. Optimize `product_brands` for menu & filter rendering
        if (Schema::hasTable('product_brands')) {
            Schema::table('product_brands', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_brands', ['is_visible_in_menu', 'sort_order', 'name'], 'idx_brands_menu_sort_name', $table);
                $this->addIndexIfNotExists('product_brands', ['is_visible_in_menu'], 'idx_brands_visible_in_menu', $table);
            });
        }

        // 7. Optimize `product_reviews` for average rating calculations and approved filtering
        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_reviews', ['product_id', 'approved', 'rating'], 'idx_reviews_prod_approved_rating', $table);
            });
        }

        // 8. Optimize `product_categories_assignments` pivot table
        if (Schema::hasTable('product_categories_assignments')) {
            Schema::table('product_categories_assignments', function (Blueprint $table) {
                $this->addIndexIfNotExists('product_categories_assignments', ['category_id', 'product_id'], 'cat_prod_composite_idx', $table);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $this->dropIndexIfExists('products', 'idx_products_active_results_brand', $table);
                $this->dropIndexIfExists('products', 'idx_products_active_results_featured', $table);
                $this->dropIndexIfExists('products', 'idx_products_active_results_title', $table);
                $this->dropIndexIfExists('products', 'idx_products_active_results_created', $table);
                $this->dropIndexIfExists('products', 'idx_products_active_results_rating', $table);
                $this->dropIndexIfExists('products', 'idx_products_active_results_id', $table);
            });
        }

        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $this->dropIndexIfExists('product_variants', 'idx_variants_prod_price_calc', $table);
                $this->dropIndexIfExists('product_variants', 'idx_variants_prod_public_price', $table);
                $this->dropIndexIfExists('product_variants', 'idx_variants_prod_wholesale_price', $table);
                $this->dropIndexIfExists('product_variants', 'idx_variants_public_price', $table);
                $this->dropIndexIfExists('product_variants', 'idx_variants_sale_price', $table);
                $this->dropIndexIfExists('product_variants', 'idx_variants_on_sale', $table);
            });
        }

        if (Schema::hasTable('product_images')) {
            Schema::table('product_images', function (Blueprint $table) {
                $this->dropIndexIfExists('product_images', 'idx_prod_images_var_active_search', $table);
                $this->dropIndexIfExists('product_images', 'idx_prod_images_var_active', $table);
            });
        }

        $invTable = Schema::hasTable('products_inventory') ? 'products_inventory' : (Schema::hasTable('product_inventories') ? 'product_inventories' : null);
        if ($invTable) {
            Schema::table($invTable, function (Blueprint $table) use ($invTable) {
                $this->dropIndexIfExists($invTable, 'idx_inventory_variant_qty', $table);
            });
        }

        if (Schema::hasTable('product_categories')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $this->dropIndexIfExists('product_categories', 'idx_categories_parent_menu_sort', $table);
                $this->dropIndexIfExists('product_categories', 'idx_categories_menu_sort_name', $table);
            });
        }

        if (Schema::hasTable('product_brands')) {
            Schema::table('product_brands', function (Blueprint $table) {
                $this->dropIndexIfExists('product_brands', 'idx_brands_menu_sort_name', $table);
                $this->dropIndexIfExists('product_brands', 'idx_brands_visible_in_menu', $table);
            });
        }

        if (Schema::hasTable('product_reviews')) {
            Schema::table('product_reviews', function (Blueprint $table) {
                $this->dropIndexIfExists('product_reviews', 'idx_reviews_prod_approved_rating', $table);
            });
        }
    }

    /**
     * Safely add an index if it does not already exist.
     */
    private function addIndexIfNotExists(string $tableName, array $columns, string $indexName, Blueprint $table): void
    {
        try {
            $existingIndexes = $this->getTableIndexes($tableName);
            if (!in_array($indexName, $existingIndexes, true)) {
                $table->index($columns, $indexName);
            }
        } catch (\Throwable) {
            // Fallback: Attempt adding directly if introspection fails
        }
    }

    /**
     * Safely add a composite index with a text prefix column for MySQL/MariaDB.
     */
    private function addTextPrefixIndexIfNotExists(string $tableName, array $prefixColumns, string $textCol, int $length, string $indexName): void
    {
        try {
            $existingIndexes = $this->getTableIndexes($tableName);
            if (in_array($indexName, $existingIndexes, true)) {
                return;
            }

            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'mariadb') {
                $colsSql = implode(', ', array_map(fn($c) => "`{$c}`", $prefixColumns));
                $colsSql .= ", `{$textCol}`({$length})";
                DB::statement("CREATE INDEX `{$indexName}` ON `{$tableName}` ({$colsSql})");
            } else {
                Schema::table($tableName, function (Blueprint $table) use ($prefixColumns, $textCol, $indexName) {
                    $allCols = array_merge($prefixColumns, [$textCol]);
                    $table->index($allCols, $indexName);
                });
            }
        } catch (\Throwable) {
            // Fallback gracefully if index already exists
        }
    }

    /**
     * Safely drop an index if it exists.
     */
    private function dropIndexIfExists(string $tableName, string $indexName, Blueprint $table): void
    {
        try {
            $existingIndexes = $this->getTableIndexes($tableName);
            if (in_array($indexName, $existingIndexes, true)) {
                $table->dropIndex($indexName);
            }
        } catch (\Throwable) {
            // Suppress if already dropped
        }
    }

    /**
     * Helper to list existing index names for a table.
     */
    private function getTableIndexes(string $tableName): array
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $indexes = DB::select("PRAGMA index_list('{$tableName}')");
            return array_map(fn($i) => (string) $i->name, $indexes);
        }

        $indexes = DB::select("SHOW INDEX FROM `{$tableName}`");
        return array_unique(array_map(fn($i) => (string) $i->Key_name, $indexes));
    }
};
