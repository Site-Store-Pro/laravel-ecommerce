<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add is_event flag to product_variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->boolean('is_event')->default(false)->after('subscription');
        });

        // Create product_variant_events (1:1 per variant)
        Schema::create('product_variant_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')
                  ->unique()
                  ->constrained('product_variants')
                  ->cascadeOnDelete();
            $table->datetime('event_start_date');
            $table->datetime('event_end_date')->nullable();
            $table->string('event_label', 255)->default('');
            $table->text('alternate_label')->nullable();
            $table->string('label_background', 50)->nullable()->default('#4f46e5');
            $table->boolean('show_date')->default(true);
            $table->text('event_location')->nullable();
            $table->text('event_description')->nullable();
            $table->float('event_sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_events');

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('is_event');
        });
    }
};
