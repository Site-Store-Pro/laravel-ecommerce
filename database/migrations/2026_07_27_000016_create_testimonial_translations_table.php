<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('testimonial_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->constrained('cms_testimonials')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->text('content')->nullable();
            $table->string('author_title')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['testimonial_id', 'language_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('testimonial_translations');
    }
};
