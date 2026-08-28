<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_form_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_form_id')->constrained('cms_forms')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('submit_button_label')->nullable();
            $table->text('confirmation_message')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['cms_form_id', 'language_id']);
            $table->index('language_id');
        });

        Schema::create('cms_form_field_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_form_field_id')->constrained('cms_form_fields')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->text('instructions')->nullable();
            $table->string('required_error_message')->nullable();
            $table->text('html_above')->nullable();
            $table->json('options')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['cms_form_field_id', 'language_id']);
            $table->index('language_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_form_field_translations');
        Schema::dropIfExists('cms_form_translations');
    }
};
