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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('personalization_label')->default('Add Gift Wrapping / Personalization');
            $table->string('personalization_details_label')->default('Personalization Details / Gift Message');
            $table->string('personalization_placeholder', 500)->default('Enter names for engraving, personalization details, or a custom gift message here...');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'personalization_label',
                'personalization_details_label',
                'personalization_placeholder',
            ]);
        });
    }
};
