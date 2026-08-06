<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('product_inventory_alert_translations');
        Schema::create('product_inventory_alert_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_inventory_alert_id')->constrained('product_inventory_alerts', 'id', 'fk_pia_trans_alert_id')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages', 'id', 'fk_pia_trans_lang_id')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->string('translation_status', 20)->default('manual');
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            $table->unique(['product_inventory_alert_id', 'language_id'], 'pia_trans_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_inventory_alert_translations');
    }
};
