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
            if (!DB::table('site_labels')->where('label_key', 'catalog.view_full_details')->exists()) {
                DB::table('site_labels')->insert([
                    'label_key'         => 'catalog.view_full_details',
                    'section_id'        => 6, // Catalog section
                    'file_name'         => 'quick-shop-modal.blade.php',
                    'label_description' => 'View Full Details link label on Quick Shop modal',
                    'label_default'     => 'View Full Details',
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
            DB::table('site_labels')->where('label_key', 'catalog.view_full_details')->delete();
        }
    }
};
