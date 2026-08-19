<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('order_refunds')) {
            Schema::table('order_refunds', function (Blueprint $table) {
                if (!Schema::hasColumn('order_refunds', 'order_payment_id')) {
                    $table->unsignedBigInteger('order_payment_id')->nullable()->after('order_id');
                    $table->index('order_payment_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_refunds')) {
            Schema::table('order_refunds', function (Blueprint $table) {
                if (Schema::hasColumn('order_refunds', 'order_payment_id')) {
                    $table->dropIndex(['order_payment_id']);
                    $table->dropColumn('order_payment_id');
                }
            });
        }
    }
};
