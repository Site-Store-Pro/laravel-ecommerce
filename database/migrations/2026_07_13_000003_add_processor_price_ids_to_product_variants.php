<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds payment processor price ID fields to product_variants so that
 * individual variants can be linked to pre-configured gateway prices
 * (for subscription billing) or auto-create a Stripe product on checkout.
 *
 * Columns added:
 *   Paddle:
 *     - paddle_sandbox_price_id   — e.g. pri_sandbox_xxxxxxxxx
 *     - paddle_live_price_id      — e.g. pri_xxxxxxxxx
 *
 *   Stripe (subscription only):
 *     - stripe_sandbox_price_id   — e.g. price_test_xxxxxxxxx
 *     - stripe_live_price_id      — e.g. price_xxxxxxxxx
 *     - create_new_stripe_product — 1 = create Product+Price on-the-fly at checkout
 *     - stripe_billing_interval   — month | year | week (used when creating on-the-fly)
 *     - stripe_trial_enabled      — 1 = add a free trial period
 *     - stripe_trial_days         — number of trial days (used when stripe_trial_enabled = 1)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Paddle price IDs
            if (!Schema::hasColumn('product_variants', 'paddle_sandbox_price_id')) {
                $table->string('paddle_sandbox_price_id', 255)->nullable();
            }
            if (!Schema::hasColumn('product_variants', 'paddle_live_price_id')) {
                $table->string('paddle_live_price_id', 255)->nullable();
            }

            // Stripe price IDs + subscription settings
            if (!Schema::hasColumn('product_variants', 'stripe_sandbox_price_id')) {
                $table->string('stripe_sandbox_price_id', 255)->nullable();
            }
            if (!Schema::hasColumn('product_variants', 'stripe_live_price_id')) {
                $table->string('stripe_live_price_id', 255)->nullable();
            }
            if (!Schema::hasColumn('product_variants', 'create_new_stripe_product')) {
                $table->tinyInteger('create_new_stripe_product')->default(0);
            }
            if (!Schema::hasColumn('product_variants', 'stripe_billing_interval')) {
                $table->string('stripe_billing_interval', 10)->default('month');
            }
            if (!Schema::hasColumn('product_variants', 'stripe_trial_enabled')) {
                $table->tinyInteger('stripe_trial_enabled')->default(0);
            }
            if (!Schema::hasColumn('product_variants', 'stripe_trial_days')) {
                $table->integer('stripe_trial_days')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $columns = [
                'paddle_sandbox_price_id',
                'paddle_live_price_id',
                'stripe_sandbox_price_id',
                'stripe_live_price_id',
                'create_new_stripe_product',
                'stripe_billing_interval',
                'stripe_trial_enabled',
                'stripe_trial_days',
            ];
            $existing = array_filter($columns, fn($c) => Schema::hasColumn('product_variants', $c));
            if (!empty($existing)) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
