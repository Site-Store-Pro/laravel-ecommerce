<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cms_list_menu_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cms_list_menu_item_id')->constrained('cms_list_menu_items')->cascadeOnDelete();
            $table->foreignId('language_id')->constrained('languages')->cascadeOnDelete();
            $table->string('list_item')->nullable();
            $table->string('translation_status', 20)->default('pending');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();
            $table->unique(['cms_list_menu_item_id', 'language_id'], 'lmi_trans_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cms_list_menu_item_translations');
    }
};
