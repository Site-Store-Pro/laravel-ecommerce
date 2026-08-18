<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'paypal_billing_interval')) {
                $table->string('paypal_billing_interval')->default('month')->after('paypal_live_plan_id');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_billing_frequency')) {
                $table->integer('paypal_billing_frequency')->default(1)->after('paypal_billing_interval');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_trial_enabled')) {
                $table->boolean('paypal_trial_enabled')->default(0)->after('paypal_billing_frequency');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_trial_days')) {
                $table->integer('paypal_trial_days')->default(0)->after('paypal_trial_enabled');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_trial_price')) {
                $table->decimal('paypal_trial_price', 10, 2)->default(0.00)->after('paypal_trial_days');
            }
            if (!Schema::hasColumn('product_variants', 'paypal_total_cycles')) {
                $table->integer('paypal_total_cycles')->default(0)->after('paypal_trial_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $columns = [
                'paypal_total_cycles',
                'paypal_trial_price',
                'paypal_trial_days',
                'paypal_trial_enabled',
                'paypal_billing_frequency',
                'paypal_billing_interval',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('product_variants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
