<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_embeds', function (Blueprint $table) {
            $table->id();

            // Admin-facing internal label
            $table->string('name', 255);

            // 0 = YouTube, 1 = Vimeo, 2 = Other HTML
            $table->tinyInteger('embed_type')->default(0)->unsigned();

            // Raw HTML embed code stored verbatim — never processed by TinyMCE
            $table->text('code_snippet')->nullable();

            // Inactive embeds render as an HTML comment rather than an error
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_embeds');
    }
};
