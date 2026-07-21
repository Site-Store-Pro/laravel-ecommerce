<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Stores submitted checkout custom field values as JSON.
            // Keyed by field label for human readability in admin order views.
            $table->json('custom_field_data')->nullable()->after('order_comments');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('custom_field_data');
        });
    }
};
