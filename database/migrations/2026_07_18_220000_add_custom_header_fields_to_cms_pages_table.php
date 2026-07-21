<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->text('alternate_page_title')->nullable()->after('background_image');
            $table->text('page_title_alignment')->nullable()->after('alternate_page_title');
            $table->text('page_title_css')->nullable()->after('page_title_alignment');
            $table->text('include_slideshow')->nullable()->after('page_title_css');
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropColumn([
                'alternate_page_title',
                'page_title_alignment',
                'page_title_css',
                'include_slideshow',
            ]);
        });
    }
};
