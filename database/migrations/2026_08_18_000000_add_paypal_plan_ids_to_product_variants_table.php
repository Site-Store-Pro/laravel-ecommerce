<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'paypal_sandbox_plan_id')) {
                $table->string('paypal_sandbox_plan_id')->nullable()->after('stripe_trial_label');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_live_plan_id')) {
                $table->string('paypal_live_plan_id')->nullable()->after('paypal_sandbox_plan_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'paypal_live_plan_id')) {
                $table->dropColumn('paypal_live_plan_id');
            }
            if (Schema::hasColumn('product_variants', 'paypal_sandbox_plan_id')) {
                $table->dropColumn('paypal_sandbox_plan_id');
            }
        });
    }
};
