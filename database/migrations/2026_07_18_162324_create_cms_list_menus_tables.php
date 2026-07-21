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
        Schema::create('cms_list_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('custom_css')->nullable();
            $table->timestamps();
        });

        Schema::create('cms_list_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_list_menu_id')
                  ->constrained('cms_list_menus')
                  ->onDelete('cascade');
            $table->text('list_item')->nullable();
            $table->double('sort_val')->default(5000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_list_menu_items');
        Schema::dropIfExists('cms_list_menus');
    }
};
