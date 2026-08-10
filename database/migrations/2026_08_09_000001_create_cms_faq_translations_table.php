<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_faq_translations')) {
            Schema::create('cms_faq_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cms_faq_id')->constrained('cms_faqs')->onDelete('cascade');
                $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
                $table->text('question')->nullable();
                $table->longText('answer')->nullable();
                $table->string('translation_status')->default('pending');
                $table->timestamp('translated_at')->nullable();
                $table->timestamps();

                $table->unique(['cms_faq_id', 'language_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_faq_translations');
    }
};
