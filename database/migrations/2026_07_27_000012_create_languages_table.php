<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();           // en, es, fr, de, zh, ar
            $table->string('name', 100);                     // English
            $table->string('native_name', 100);              // Español
            $table->string('flag_emoji', 10)->default('🌐'); // 🇺🇸
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_in_switcher')->default(true);
            $table->boolean('rtl')->default(false);
            $table->string('currency_code', 10)->nullable();  // EUR
            $table->string('currency_symbol', 10)->nullable(); // €
            $table->string('currency_position', 10)->default('before'); // before|after
            $table->string('decimal_separator', 5)->default('.');
            $table->string('thousands_separator', 5)->default(',');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed the default English language
        DB::table('languages')->insert([
            'code'          => 'en',
            'name'          => 'English',
            'native_name'   => 'English',
            'flag_emoji'    => '🇺🇸',
            'is_default'    => true,
            'is_active'     => true,
            'show_in_switcher' => true,
            'rtl'           => false,
            'sort_order'    => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('languages');
    }
};
