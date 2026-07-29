<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_labels', function (Blueprint $table) {
            $table->id();
            $table->string('label_key', 120)->unique()->index();
            $table->unsignedSmallInteger('section_id')->default(0)->index();
            $table->unsignedSmallInteger('language_id')->default(0)->index();
            $table->string('file_name', 120)->index();
            $table->string('label_description', 255);
            $table->text('label_default');
            $table->text('label_custom')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            $table->index(['language_id', 'section_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_labels');
    }
};
