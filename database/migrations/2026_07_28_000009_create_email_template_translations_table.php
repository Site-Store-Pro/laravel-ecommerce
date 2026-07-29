<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('email_template_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_template_id')->constrained('email_templates')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('subject')->nullable();
            $table->longText('header_html')->nullable();
            $table->string('salutation')->nullable();
            $table->string('greeting')->nullable();
            $table->longText('body')->nullable();
            $table->string('sign_off')->nullable();
            $table->string('signature')->nullable();
            $table->longText('disclaimer')->nullable();
            $table->string('copyright')->nullable();
            $table->longText('footer_html')->nullable();
            $table->string('translation_status', 20)->default('pending'); // pending|ai_translated|reviewed
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['email_template_id', 'language_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('email_template_translations');
    }
};
