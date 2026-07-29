<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stores a video embed for layout types 3 & 5.
            // Accepts raw <iframe> HTML or a CMS shortcode such as [code-embed:N].
            $table->text('product_video_embed')->nullable()->after('variant_label');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_video_embed');
        });
    }
};
