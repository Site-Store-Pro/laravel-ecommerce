<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_pages_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_pages_category_id')->index();
            $table->unsignedBigInteger('language_id')->index();
            $table->text('name')->nullable();
            $table->string('translation_status', 30)->default('ai_translated');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->foreign('cms_pages_category_id', 'fk_cms_pages_cat_trans_cat_id')
                  ->references('id')->on('cms_pages_categories')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            
            $table->unique(['cms_pages_category_id', 'language_id'], 'uq_cms_pages_cat_trans_cat_lang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_pages_category_translations');
    }
};
