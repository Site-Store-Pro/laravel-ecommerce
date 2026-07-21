<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('nav_menus')->cascadeOnDelete();
            $table->unsignedBigInteger('parent_id')->nullable(); // null = top-level
            $table->float('position')->default(0);               // float allows fractional sorting
            $table->text('label');                               // supports HTML/icon markup
            $table->string('item_type', 50)->default('link');    // link|cms_page|home|shop|cart|account|
                                                                 // categories|brands|parent|no_link|
                                                                 // mega_menu|html_submenu|separator|plugin
            $table->text('url')->nullable();                     // for item_type=link
            $table->longText('html_content')->nullable();        // for mega_menu / html_submenu (TinyMCE)
            $table->unsignedInteger('cms_page_id')->nullable();  // for item_type=cms_page
            $table->boolean('is_active')->default(true);
            $table->boolean('open_in_new_tab')->default(false);
            $table->string('visibility', 20)->default('all');    // all|guests_only|auth_only|wholesale_only
            $table->boolean('hide_on_mobile')->default(false);
            $table->boolean('hide_on_desktop')->default(false);
            $table->string('css_class', 255)->nullable();        // extra CSS classes on <li>
            $table->string('aria_label', 255)->nullable();       // accessibility aria-label
            $table->string('plugin_slug', 100)->nullable();      // for item_type=plugin
            $table->timestamps();

            $table->index(['menu_id', 'parent_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_items');
    }
};
