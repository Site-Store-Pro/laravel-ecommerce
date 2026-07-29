<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kb_article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kb_article_id')->constrained('kb_articles')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->longText('article_content')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['kb_article_id', 'language_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('kb_article_translations');
    }
};
