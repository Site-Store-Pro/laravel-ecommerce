<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugin_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plugin_id');
            $table->unsignedBigInteger('language_id');
            $table->string('field_name', 255);
            $table->longText('field_value')->nullable();
            $table->timestamps();

            $table->unique(['plugin_id', 'language_id', 'field_name'], 'pst_unique');
            $table->index(['plugin_id', 'language_id'],                  'pst_plugin_lang_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugin_setting_translations');
    }
};
