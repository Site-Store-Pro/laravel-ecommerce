<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Drop legacy language_id from the parent table ─────────────────
        Schema::table('site_labels', function (Blueprint $table) {
            // Drop the composite index before dropping the column
            $table->dropIndex(['language_id', 'section_id']);
            $table->dropIndex(['language_id']);
            $table->dropColumn('language_id');
        });

        // ── 2. Create the child translations table ───────────────────────────
        Schema::create('site_label_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_label_id')->index();
            $table->unsignedBigInteger('language_id')->index();
            $table->text('label_value')->nullable();          // translated text
            $table->string('translation_status', 30)->default('ai_translated'); // ai_translated | reviewed
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['site_label_id', 'language_id'], 'slt_unique');

            $table->foreign('site_label_id')
                  ->references('id')->on('site_labels')
                  ->onDelete('cascade');

            $table->foreign('language_id')
                  ->references('id')->on('languages')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_label_translations');

        Schema::table('site_labels', function (Blueprint $table) {
            $table->unsignedSmallInteger('language_id')->default(0)->index()->after('section_id');
            $table->index(['language_id', 'section_id']);
        });
    }
};
