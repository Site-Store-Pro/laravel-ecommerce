<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'stripe_trial_price')) {
                $table->decimal('stripe_trial_price', 10, 2)->default(0.00)->after('stripe_trial_days');
            }
            if (!Schema::hasColumn('product_variants', 'stripe_trial_label')) {
                $table->string('stripe_trial_label')->nullable()->after('stripe_trial_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'stripe_trial_label')) {
                $table->dropColumn('stripe_trial_label');
            }
            if (Schema::hasColumn('product_variants', 'stripe_trial_price')) {
                $table->dropColumn('stripe_trial_price');
            }
        });
    }
};
