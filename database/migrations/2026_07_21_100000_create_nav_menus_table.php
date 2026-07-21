<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // admin label, e.g. "Main Menu"
            $table->string('slug', 100)->unique();         // url-safe, used as CSS scope id
            $table->boolean('is_primary')->default(false); // only one can be primary
            $table->boolean('is_active')->default(true);
            $table->string('color_scheme', 50)->default('default'); // preset key
            $table->text('custom_css')->nullable();        // injected in scoped <style> block
            $table->boolean('sticky')->default(true);      // position: sticky
            $table->boolean('show_logo')->default(true);   // render site logo in nav
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nav_menus');
    }
};
