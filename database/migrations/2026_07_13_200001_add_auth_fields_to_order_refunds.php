<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            $table->text('authorization_code')->nullable()->after('amount');
            $table->text('processor_response')->nullable()->after('authorization_code');
        });
    }

    public function down(): void
    {
        Schema::table('order_refunds', function (Blueprint $table) {
            $table->dropColumn(['authorization_code', 'processor_response']);
        });
    }
};
