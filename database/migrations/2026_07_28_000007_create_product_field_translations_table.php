<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_field_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_field_id');
            $table->unsignedBigInteger('language_id');
            $table->string('label')->nullable();
            $table->timestamps();

            $table->unique(['product_field_id', 'language_id'], 'pft_field_lang_uq');
            $table->foreign('product_field_id')->references('id')->on('product_fields')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_field_translations');
    }
};
