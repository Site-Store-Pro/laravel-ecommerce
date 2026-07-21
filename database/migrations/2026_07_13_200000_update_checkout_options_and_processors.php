<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new fields to order_checkout_options
        Schema::table('order_checkout_options', function (Blueprint $table) {
            if (!Schema::hasColumn('order_checkout_options', 'randomize_processor')) {
                $table->integer('randomize_processor')->default(0)->after('tertiary_processor');
            }
            if (!Schema::hasColumn('order_checkout_options', 'paypal_express')) {
                $table->integer('paypal_express')->default(0)->after('randomize_processor');
            }
            if (!Schema::hasColumn('order_checkout_options', 'retail_minimum')) {
                $table->decimal('retail_minimum', 10, 2)->default(0.00)->after('paypal_express');
            }
            if (!Schema::hasColumn('order_checkout_options', 'wholesale_minimum')) {
                $table->decimal('wholesale_minimum', 10, 2)->default(0.00)->after('retail_minimum');
            }
        });

        // Add production field to order_processors
        Schema::table('order_processors', function (Blueprint $table) {
            if (!Schema::hasColumn('order_processors', 'production')) {
                $table->integer('production')->default(0)->after('processor_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_checkout_options', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('order_checkout_options', 'paypal_express')) {
                $cols[] = 'paypal_express';
            }
            if (Schema::hasColumn('order_checkout_options', 'retail_minimum')) {
                $cols[] = 'retail_minimum';
            }
            if (Schema::hasColumn('order_checkout_options', 'wholesale_minimum')) {
                $cols[] = 'wholesale_minimum';
            }
            if (count($cols) > 0) {
                $table->dropColumn($cols);
            }
        });
    }
};
