<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cms_page_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('alternate_page_title')->nullable();
            $table->string('translation_status', 20)->default('pending'); // pending|ai_translated|reviewed
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['cms_page_id', 'language_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cms_page_translations');
    }
};
