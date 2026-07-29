<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Stores a raw URL or a [page:ID] shortcode.
            // When set, overrides the default order confirmation page after checkout.
            $table->string('completion_redirect', 1000)->nullable()->after('checkout_redirect');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('completion_redirect');
        });
    }
};
