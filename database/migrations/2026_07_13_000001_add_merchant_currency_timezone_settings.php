<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add merchant location, currency, and VAT-inclusive columns to shipping_configurations
        if (!Schema::hasColumn('shipping_configurations', 'merchant_country_code')) {
            Schema::table('shipping_configurations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('merchant_country_code', 10)->default('US')->after('origin_country_code');
                $table->string('currency_code', 10)->default('USD')->after('merchant_country_code');
                $table->string('currency_symbol', 10)->default('$')->after('currency_code');
                $table->boolean('vat_inclusive_pricing')->default(false)->after('currency_symbol');
            });
        }

        // Add timezone to cms_settings via upsert
        DB::table('cms_settings')->upsert([
            [
                'key'        => 'timezone',
                'value'      => 'America/New_York',
                'label'      => 'Site Timezone',
                'type'       => 'select',
                'group'      => 'general',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['key'], ['label', 'type', 'group', 'sort_order', 'updated_at']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('shipping_configurations', 'merchant_country_code')) {
            Schema::table('shipping_configurations', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->dropColumn(['merchant_country_code', 'currency_code', 'currency_symbol', 'vat_inclusive_pricing']);
            });
        }

        DB::table('cms_settings')->where('key', 'timezone')->delete();
    }
};
