<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds two columns that were referenced in the AdminCheckoutProcessors
 * Livewire component but were absent from the consolidated migration:
 *
 *  - order_checkout_options.randomize_processor  (tinyint, default 0)
 *  - order_processors.production                 (tinyint, default 0)
 *
 * Also corrects the seeded name for processor_id = 2 from
 * "PayPal Express Checkout" to "Paddle Billing".
 */
return new class extends Migration
{
    public function up(): void
    {
        // Add randomize_processor to order_checkout_options if not present
        if (!Schema::hasColumn('order_checkout_options', 'randomize_processor')) {
            Schema::table('order_checkout_options', function (Blueprint $table) {
                $table->tinyInteger('randomize_processor')->default(0)->after('tertiary_processor');
            });
        }

        // Add production flag to order_processors if not present
        if (!Schema::hasColumn('order_processors', 'production')) {
            Schema::table('order_processors', function (Blueprint $table) {
                $table->tinyInteger('production')->default(0)->after('processor_name');
            });
        }

        // Correct the PayPal row to Paddle Billing
        DB::table('order_processors')
            ->where('processor_id', 2)
            ->where('processor_name', 'PayPal Express Checkout')
            ->update(['processor_name' => 'Paddle Billing']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('order_checkout_options', 'randomize_processor')) {
            Schema::table('order_checkout_options', function (Blueprint $table) {
                $table->dropColumn('randomize_processor');
            });
        }

        if (Schema::hasColumn('order_processors', 'production')) {
            Schema::table('order_processors', function (Blueprint $table) {
                $table->dropColumn('production');
            });
        }

        // Restore original name on rollback
        DB::table('order_processors')
            ->where('processor_id', 2)
            ->where('processor_name', 'Paddle Billing')
            ->update(['processor_name' => 'PayPal Express Checkout']);
    }
};
