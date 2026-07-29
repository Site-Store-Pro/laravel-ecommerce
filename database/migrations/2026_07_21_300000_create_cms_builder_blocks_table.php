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
        Schema::create('cms_builder_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('target_element')->nullable();
            $table->unsignedTinyInteger('type')->default(1); // 1=header container, 2=header element, 3=top bar col, 4=footer row, 5=footer col
            $table->enum('section_type', ['header', 'footer'])->default('header');
            $table->boolean('is_placeholder')->default(false);
            $table->float('sort_desktop')->default(0);
            $table->float('sort_tablet')->default(0);
            $table->float('sort_mobile')->default(0);
            $table->longText('content_desktop')->nullable();
            $table->longText('content_tablet')->nullable();
            $table->longText('content_mobile')->nullable();
            $table->boolean('is_active_desktop')->default(true);
            $table->boolean('is_active_tablet')->default(true);
            $table->boolean('is_active_mobile')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_builder_blocks');
    }
};
