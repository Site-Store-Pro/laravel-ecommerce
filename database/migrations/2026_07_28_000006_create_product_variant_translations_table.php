<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->unsignedBigInteger('language_id');
            $table->string('personalization_label')->nullable();
            $table->string('personalization_details_label')->nullable();
            $table->string('personalization_placeholder')->nullable();
            $table->timestamps();

            $table->unique(['product_variant_id', 'language_id'], 'pvt_variant_lang_uq');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_translations');
    }
};
