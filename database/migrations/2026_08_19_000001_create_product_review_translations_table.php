<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_review_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_review_id')->constrained('product_reviews')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->text('comments')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['product_review_id', 'language_id']);
            $table->index('language_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_review_translations');
    }
};
