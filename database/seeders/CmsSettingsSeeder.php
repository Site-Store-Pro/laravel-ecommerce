<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Appearance
            [
                'key'        => 'frontend_dark_mode',
                'value'      => '0',
                'label'      => 'Frontend Dark Mode',
                'type'       => 'boolean',
                'group'      => 'appearance',
                'sort_order' => 10,
            ],
            [
                'key'        => 'admin_dark_mode',
                'value'      => '0',
                'label'      => 'Admin Dark Mode',
                'type'       => 'boolean',
                'group'      => 'appearance',
                'sort_order' => 20,
            ],

            // Loaders
            [
                'key'        => 'google_fonts_url',
                'value'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
                'label'      => 'Google Fonts URL',
                'type'       => 'text',
                'group'      => 'loaders',
                'sort_order' => 30,
            ],
            [
                'key'        => 'google_analytics_id',
                'value'      => '',
                'label'      => 'Google Analytics ID (e.g. G-XXXXXXXXXX)',
                'type'       => 'text',
                'group'      => 'loaders',
                'sort_order' => 40,
            ],
            [
                'key'        => 'custom_js_loader',
                'value'      => '',
                'label'      => 'Custom JS / Third-Party Scripts',
                'type'       => 'textarea',
                'group'      => 'loaders',
                'sort_order' => 50,
            ],

            // General
            [
                'key'        => 'timezone',
                'value'      => 'America/New_York',
                'label'      => 'Site Timezone',
                'type'       => 'select',
                'group'      => 'general',
                'sort_order' => 10,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('cms_settings')->upsert(
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]),
                ['key'],
                ['value', 'label', 'type', 'group', 'sort_order', 'updated_at']
            );
        }
    }
}
