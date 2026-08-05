<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_builder_block_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_builder_block_id')->constrained('cms_builder_blocks')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->longText('content_desktop')->nullable();
            $table->longText('content_tablet')->nullable();
            $table->longText('content_mobile')->nullable();
            $table->string('translation_status')->default('ai_translated'); // ai_translated, reviewed
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['cms_builder_block_id', 'language_id'], 'cbb_trans_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_builder_block_translations');
    }
};
