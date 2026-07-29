<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('cms_testimonials')) {
            Schema::create('cms_testimonials', function (Blueprint $table) {
                $table->id();
                $table->string('author_name');
                $table->string('author_title')->nullable();
                $table->text('content');
                $table->string('avatar_image')->nullable();
                $table->unsignedTinyInteger('rating')->default(5);
                $table->string('company_name')->nullable();
                $table->string('company_link')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_testimonials');
    }
};
