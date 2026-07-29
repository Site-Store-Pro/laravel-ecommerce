<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kb_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kb_category_id')->index();
            $table->unsignedBigInteger('language_id')->index();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->string('translation_status', 30)->default('ai_translated');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->foreign('kb_category_id')->references('id')->on('kb_categories')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            
            $table->unique(['kb_category_id', 'language_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kb_category_translations');
    }
};
