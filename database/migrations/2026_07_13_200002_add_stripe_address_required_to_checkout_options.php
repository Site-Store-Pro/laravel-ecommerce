<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_checkout_options', function (Blueprint $table) {
            if (!Schema::hasColumn('order_checkout_options', 'stripe_address_required')) {
                $table->tinyInteger('stripe_address_required')->default(0)->after('wholesale_minimum');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_checkout_options', function (Blueprint $table) {
            if (Schema::hasColumn('order_checkout_options', 'stripe_address_required')) {
                $table->dropColumn('stripe_address_required');
            }
        });
    }
};
