<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('site_labels')) {
            if (!DB::table('site_labels')->where('label_key', 'catalog.return_to_search_results')->exists()) {
                DB::table('site_labels')->insert([
                    'label_key'         => 'catalog.return_to_search_results',
                    'section_id'        => 6, // Catalog section
                    'file_name'         => 'product-details.blade.php',
                    'label_description' => 'Return to search results link label on product details page',
                    'label_default'     => 'Return to search results',
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
        if (Schema::hasTable('site_labels')) {
            DB::table('site_labels')->where('label_key', 'catalog.return_to_search_results')->delete();
        }
    }
};
