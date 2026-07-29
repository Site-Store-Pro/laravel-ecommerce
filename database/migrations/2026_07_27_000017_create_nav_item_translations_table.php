<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nav_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nav_item_id')->constrained('nav_items')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->text('html_content')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['nav_item_id', 'language_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('nav_item_translations');
    }
};
