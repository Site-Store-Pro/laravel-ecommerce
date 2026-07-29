<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Customisable label shown above the variant selector on the product
            // detail page when the product has more than one variant.
            // Defaults to 'Select Option:'.
            $table->string('variant_label', 255)
                  ->default('Select Option:')
                  ->after('show_item_total');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('variant_label');
        });
    }
};
