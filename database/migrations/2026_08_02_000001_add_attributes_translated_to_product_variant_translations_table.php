<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variant_translations', function (Blueprint $table) {
            // Stores a flat JSON map of raw attribute key/value → translated string.
            // e.g. {"Color":"Couleur","Blue":"Bleu","Size":"Taille","Large":"Grand"}
            // Both keys and values from the variant's attributes JSON are stored here
            // so the blade can translate them without altering the raw stored data.
            $table->json('attributes_translated')->nullable()->after('personalization_placeholder');
        });
    }

    public function down(): void
    {
        Schema::table('product_variant_translations', function (Blueprint $table) {
            $table->dropColumn('attributes_translated');
        });
    }
};
