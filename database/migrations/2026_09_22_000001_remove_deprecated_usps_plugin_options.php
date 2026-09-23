<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $deprecatedFields = [
            'USPS_First_Class_Package',
            'USPS_Parcel_Select_Lightweight',
        ];

        // Delete deprecated options and their saved settings for USPS plugin
        DB::table('plugin_options')
            ->whereIn('field_name', $deprecatedFields)
            ->delete();

        DB::table('plugin_settings')
            ->whereIn('field_name', $deprecatedFields)
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $uspsPlugin = DB::table('plugins')->where('shortcode', 'usps-api')->first();
        if ($uspsPlugin) {
            DB::table('plugin_options')->insertOrIgnore([
                [
                    'plugin_id' => $uspsPlugin->id,
                    'field_name' => 'USPS_First_Class_Package',
                    'field_label' => 'First-Class Package Service',
                    'field_type' => 'checkbox',
                    'field_data_format' => 'string',
                    'field_default_value' => '0',
                    'field_required' => 'no',
                    'sort_order' => 130,
                ],
                [
                    'plugin_id' => $uspsPlugin->id,
                    'field_name' => 'USPS_Parcel_Select_Lightweight',
                    'field_label' => 'Parcel Select Lightweight',
                    'field_type' => 'checkbox',
                    'field_data_format' => 'string',
                    'field_default_value' => '0',
                    'field_required' => 'no',
                    'sort_order' => 150,
                ],
            ]);
        }
    }
};
