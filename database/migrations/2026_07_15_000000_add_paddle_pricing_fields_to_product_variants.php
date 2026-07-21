<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('paddle_price', 10, 2)->nullable()->after('paddle_live_price_id');
            $table->string('paddle_interval', 30)->nullable()->after('paddle_price');
            $table->integer('paddle_frequency')->nullable()->default(1)->after('paddle_interval');
            $table->string('paddle_currency_code', 10)->default('USD')->after('paddle_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['paddle_price', 'paddle_interval', 'paddle_frequency', 'paddle_currency_code']);
        });
    }
};
