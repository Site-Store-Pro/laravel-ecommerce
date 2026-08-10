<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cms_slide_translations')) {
            Schema::create('cms_slide_translations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cms_slide_id')->constrained('cms_slides')->onDelete('cascade');
                $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
                $table->text('slide_heading')->nullable();
                $table->text('slide_sub_heading')->nullable();
                $table->string('slide_callout_button_label')->nullable();
                $table->string('translation_status', 20)->default('pending');
                $table->timestamp('translated_at')->nullable();
                $table->timestamps();

                $table->unique(['cms_slide_id', 'language_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_slide_translations');
    }
};
