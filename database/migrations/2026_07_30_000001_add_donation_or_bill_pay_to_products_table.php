<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_donation_or_bill_pay')->default(false)->after('product_video_embed');
            $table->boolean('allow_custom_amount')->default(false)->after('is_donation_or_bill_pay');
            $table->decimal('custom_amount_min', 10, 2)->nullable()->after('allow_custom_amount');
            $table->decimal('custom_amount_max', 10, 2)->nullable()->after('custom_amount_min');
            $table->string('custom_amount_options')->nullable()->after('custom_amount_max');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'is_donation_or_bill_pay',
                'allow_custom_amount',
                'custom_amount_min',
                'custom_amount_max',
                'custom_amount_options',
            ]);
        });
    }
};
