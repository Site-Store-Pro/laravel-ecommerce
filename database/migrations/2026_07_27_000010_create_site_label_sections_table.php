<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_label_sections', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->primary();
            $table->string('name', 80);
            $table->string('slug', 80)->unique();
            $table->string('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_label_sections');
    }
};
