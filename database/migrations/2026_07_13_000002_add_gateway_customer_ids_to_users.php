<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds gateway customer ID columns to the users table so that
 * Stripe and Paddle customer objects can be linked to platform accounts.
 *
 * These are populated automatically by the webhook controllers when
 * Stripe fires customer.created or Paddle fires customer.created events.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->string('stripe_customer_id', 255)->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'paddle_customer_id')) {
                $table->string('paddle_customer_id', 255)->nullable()->after('stripe_customer_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'stripe_customer_id')) {
                $columns[] = 'stripe_customer_id';
            }
            if (Schema::hasColumn('users', 'paddle_customer_id')) {
                $columns[] = 'paddle_customer_id';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
