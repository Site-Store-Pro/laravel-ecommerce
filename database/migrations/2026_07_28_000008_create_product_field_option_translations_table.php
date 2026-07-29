<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_field_option_translations');
        Schema::create('product_field_option_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_field_option_id');
            $table->unsignedBigInteger('language_id');
            $table->string('option_value')->nullable();
            $table->timestamps();

            $table->unique(['product_field_option_id', 'language_id'], 'pfot_option_lang_uq');
            $table->foreign('product_field_option_id', 'pfot_option_id_fk')->references('id')->on('product_field_options')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_field_option_translations');
    }
};
