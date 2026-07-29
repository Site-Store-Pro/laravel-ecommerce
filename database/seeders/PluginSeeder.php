<?php

namespace Database\Seeders;

use App\Models\Plugin;
use App\Models\PluginOption;
use App\Models\PluginSetting;
use Illuminate\Database\Seeder;

class PluginSeeder extends Seeder
{
    public function run(): void
    {
        // ── Slideshow Plugin ──────────────────────────────────────────────────
        $slideshowPlugin = Plugin::updateOrCreate(
            ['filename' => 'slideshow_2026'],
            [
                'name'                => 'Slideshow - Swiper Display (2026)',
                'shortcode'           => 'slideshow-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Displays an active slideshow using Swiper.js with full overlay content controls, flexible alignment, and CSS customizations.',
                'usage_instructions'  => '<p>Add the shortcode <strong>[plugin:slideshow-2026]</strong> to any CMS page to display your slideshow. To specify a slideshow by ID use: <strong>[plugin:slideshow-2026 id=2]</strong>. To disable navigation arrows: <strong>[plugin:slideshow-2026 nav=off]</strong>. To disable pagination dots: <strong>[plugin:slideshow-2026 paging=off]</strong>.</p>',
            ]
        );

        PluginOption::where('plugin_id', $slideshowPlugin->id)->delete();

        $defaultCss = ".slideshow-plugin-wrapper { width: 100%; }\n.slideshow-plugin-slide { min-height: 500px; background-size: cover; background-position: center; position: relative; }\n.slideshow-plugin-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; padding: 40px; }\n.slideshow-plugin-content { max-width: 600px; }\n.slideshow-plugin-heading { font-size: 2.5rem; font-weight: 700; color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5); margin-bottom: 0.5rem; }\n.slideshow-plugin-subheading { font-size: 1.2rem; font-weight: 400; color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.4); margin-bottom: 1.5rem; }\n.slideshow-plugin-btn { display: inline-block; padding: 12px 28px; background: #4f46e5; color: #fff; border-radius: 8px; font-weight: 600; text-decoration: none; transition: background 0.2s; }\n.slideshow-plugin-btn:hover { background: #4338ca; }\n@media (max-width: 768px) { .slideshow-plugin-slide { min-height: 300px; } .slideshow-plugin-heading { font-size: 1.5rem; } .slideshow-plugin-subheading { font-size: 1rem; } .slideshow-plugin-overlay { padding: 20px; } }";

        $slideshowOptions = [
            [
                'field_name'          => 'custom_css',
                'field_label'         => 'Custom CSS Overrides',
                'field_type'          => 'textarea',
                'field_editor'        => 'css',
                'field_required'      => 'no',
                'sort_order'          => 10,
                'field_default_value' => '',
            ],
            [
                'field_name'          => 'default_css',
                'field_label'         => 'Default Plugin CSS (Read-Only Reference)',
                'field_type'          => 'text-only',
                'field_required'      => 'no',
                'sort_order'          => 20,
                'field_default_value' => $defaultCss,
            ],
        ];

        foreach ($slideshowOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $slideshowPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $slideshowPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'  => $slideshowPlugin->id,
                    'field_name' => $opt['field_name'],
                    'field_value'=> $opt['field_default_value'],
                ]);
            }
        }

        // ── FedEx Plugin ──────────────────────────────────────────────────────
        $fedexPlugin = Plugin::updateOrCreate(
            ['shortcode' => 'fedex-api'],
            [
                'filename'            => 'fedex_api_2026',
                'name'                => 'Shipping Rates - FedEx REST API (2026)',
                'shortcode'           => 'fedex-api',
                'type'                => 'shipping',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 0,
                'description'         => 'Live FedEx shipping rates via the FedEx REST API v1 for North American and International shipments.',
                'usage_instructions'  => '<p>Enter your FedEx API credentials below. You need a FedEx developer account with REST API credentials (Client ID and Client Secret). Visit <a href="https://developer.fedex.com" target="_blank">developer.fedex.com</a>. Note: This uses FedEx REST API v1 — old SOAP credentials are not compatible.</p>',
            ]
        );

        PluginOption::where('plugin_id', $fedexPlugin->id)->delete();

        $fedexOptions = [
            ['field_name' => 'FedEx_Account',               'field_label' => 'FedEx Account Number',                    'field_type' => 'input',    'field_data_format' => 'string',  'field_required' => 'yes', 'sort_order' => 10,  'field_default_value' => ''],
            ['field_name' => 'FedEx_Access_ID',             'field_label' => 'API Client ID (Key)',                     'field_type' => 'input',    'field_data_format' => 'string',  'field_required' => 'yes', 'sort_order' => 20,  'field_default_value' => ''],
            ['field_name' => 'FedEx_Password',              'field_label' => 'API Client Secret (Password)',            'field_type' => 'input',    'field_data_format' => 'string',  'field_required' => 'yes', 'sort_order' => 30,  'field_default_value' => ''],
            ['field_name' => 'FedEx_markup',                'field_label' => 'Markup/Markdown Value (+/-)',             'field_type' => 'input',    'field_data_format' => 'float',   'field_required' => 'yes', 'sort_order' => 40,  'field_default_value' => '0.00', 'field_help' => 'Enter a numeric value. Use negative for discount. Enter 0.00 for no adjustment.'],
            ['field_name' => 'FedEx_NorthAmerica',          'field_label' => 'Show Rates for North America?',          'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 50,  'field_default_value' => '1'],
            ['field_name' => 'FedEx_International',         'field_label' => 'Show Rates for International?',          'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 60,  'field_default_value' => '1'],
            ['field_name' => 'FedEx_Ground',                'field_label' => 'FedEx Ground',                           'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 70,  'field_default_value' => '1'],
            ['field_name' => 'FedEx_Ground_Home_Delivery',  'field_label' => 'FedEx Ground Home Delivery',             'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 80,  'field_default_value' => '1'],
            ['field_name' => 'FedEx_Express_Saver',         'field_label' => 'FedEx Express Saver',                    'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 90,  'field_default_value' => '1'],
            ['field_name' => 'FedEx_2_Day_Air',             'field_label' => 'FedEx 2 Day Air',                        'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 100, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_2_Day_Air_AM',          'field_label' => 'FedEx 2 Day Air AM',                     'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 110, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_Priority_Overnight',    'field_label' => 'FedEx Priority Overnight',               'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 120, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_Standard_Overnight',    'field_label' => 'FedEx Standard Overnight',               'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 130, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_First_Overnight',       'field_label' => 'FedEx First Overnight',                  'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 140, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_International_Priority','field_label' => 'FedEx International Priority',           'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 150, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_International_Economy', 'field_label' => 'FedEx International Economy',            'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 160, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_International_First',   'field_label' => 'FedEx International First',              'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 170, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_1_Day_Freight',         'field_label' => 'FedEx 1 Day Freight',                    'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 180, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_2_Day_Freight',         'field_label' => 'FedEx 2 Day Freight',                    'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 190, 'field_default_value' => '1'],
            ['field_name' => 'FedEx_3_Day_Freight',         'field_label' => 'FedEx 3 Day Freight',                    'field_type' => 'checkbox', 'field_data_format' => 'integer', 'field_required' => 'no',  'sort_order' => 200, 'field_default_value' => '1'],
        ];

        foreach ($fedexOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $fedexPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $fedexPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'   => $fedexPlugin->id,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ]);
            }
        }

        // ── UPS Plugin ────────────────────────────────────────────────────────
        $upsPlugin = Plugin::updateOrCreate(
            ['filename' => 'ups_api_2026'],
            [
                'name'                 => 'Shipping Rates - UPS REST API (2026)',
                'shortcode'            => 'ups-api',
                'type'                 => 'shipping',
                'author'               => 'Built-in',
                'version'              => '1.0',
                'install_type'         => 1,
                'activation_required'  => 'yes',
                'activation_status'    => 0,
                'description'          => 'Real-time UPS shipping rates via the UPS REST Rating API with OAuth2. Supports all domestic and international UPS services.',
                'activation_instructions' => '<p>Enter your <strong>UPS Client ID</strong> and <strong>Client Secret</strong> from <a href="https://developer.ups.com" target="_blank">developer.ups.com</a> and click Activate. Your UPS Account Number is optional but required for negotiated rates.</p>',
                'activation_success_msg' => 'UPS plugin activated. Configure your services in the Settings tab.',
                'activation_failed_msg'  => 'Could not activate. Please check your UPS Client ID and Client Secret.',
                'usage_instructions'     => '<p>Active UPS shipping rates will automatically appear on the checkout review page alongside other shipping options, sorted by price. No shortcode required for shipping plugins.</p>',
                'help_url'               => 'https://developer.ups.com/api/reference/rating/business-rules',
            ]
        );

        $upsOptions = [
            ['field_name' => 'UPS_Client_ID',     'field_label' => 'UPS Client ID (OAuth2)',       'field_type' => 'input',  'sort_order' => 10, 'field_required' => 'yes', 'field_help' => 'From developer.ups.com → My Apps', 'field_default_value' => ''],
            ['field_name' => 'UPS_Client_Secret', 'field_label' => 'UPS Client Secret (OAuth2)',   'field_type' => 'input',  'sort_order' => 20, 'field_required' => 'yes', 'field_help' => 'Keep confidential', 'field_default_value' => ''],
            ['field_name' => 'UPS_Account_Number','field_label' => 'UPS Account Number',           'field_type' => 'input',  'sort_order' => 30, 'field_required' => 'no',  'field_help' => 'Required for negotiated/discounted rates. Leave blank for retail rates.', 'field_default_value' => ''],
            ['field_name' => 'UPS_From_Zip',      'field_label' => 'Origin ZIP Code',              'field_type' => 'input',  'sort_order' => 40, 'field_required' => 'yes', 'field_help' => 'ZIP code your shipments originate from', 'field_default_value' => ''],
            ['field_name' => 'UPS_From_Country',  'field_label' => 'Origin Country Code',          'field_type' => 'input',  'sort_order' => 50, 'field_required' => 'yes', 'field_help' => '2-letter country code (e.g. US, CA)', 'field_default_value' => 'US'],
            ['field_name' => 'UPS_Markup',        'field_label' => 'Rate Markup / Markdown ($)',   'field_type' => 'input',  'sort_order' => 60, 'field_required' => 'no',  'field_help' => 'Added to every rate. Use negative to discount. e.g. 2.50 or -1.00', 'field_default_value' => '0.00'],
            // Services
            ['field_name' => 'UPS_Ground',                   'field_label' => 'UPS Ground',                      'field_type' => 'checkbox', 'sort_order' => 100, 'field_default_value' => '1'],
            ['field_name' => 'UPS_Ground_Saver',             'field_label' => 'UPS Ground Saver',                'field_type' => 'checkbox', 'sort_order' => 110, 'field_default_value' => '1'],
            ['field_name' => 'UPS_3_Day_Select',             'field_label' => 'UPS 3 Day Select',                'field_type' => 'checkbox', 'sort_order' => 120, 'field_default_value' => '1'],
            ['field_name' => 'UPS_2nd_Day_Air',              'field_label' => 'UPS 2nd Day Air',                 'field_type' => 'checkbox', 'sort_order' => 130, 'field_default_value' => '1'],
            ['field_name' => 'UPS_2nd_Day_Air_AM',           'field_label' => 'UPS 2nd Day Air A.M.',            'field_type' => 'checkbox', 'sort_order' => 140, 'field_default_value' => '0'],
            ['field_name' => 'UPS_Next_Day_Air_Saver',       'field_label' => 'UPS Next Day Air Saver',          'field_type' => 'checkbox', 'sort_order' => 150, 'field_default_value' => '1'],
            ['field_name' => 'UPS_Next_Day_Air',             'field_label' => 'UPS Next Day Air',                'field_type' => 'checkbox', 'sort_order' => 160, 'field_default_value' => '1'],
            ['field_name' => 'UPS_Next_Day_Air_Early',       'field_label' => 'UPS Next Day Air Early',          'field_type' => 'checkbox', 'sort_order' => 170, 'field_default_value' => '0'],
            ['field_name' => 'UPS_International_Economy',    'field_label' => 'UPS Worldwide Economy',           'field_type' => 'checkbox', 'sort_order' => 200, 'field_default_value' => '0'],
            ['field_name' => 'UPS_International_Expedited',  'field_label' => 'UPS Worldwide Expedited',         'field_type' => 'checkbox', 'sort_order' => 210, 'field_default_value' => '0'],
            ['field_name' => 'UPS_Worldwide_Express',        'field_label' => 'UPS Worldwide Express',           'field_type' => 'checkbox', 'sort_order' => 220, 'field_default_value' => '0'],
            ['field_name' => 'UPS_Worldwide_Express_Plus',   'field_label' => 'UPS Worldwide Express Plus',      'field_type' => 'checkbox', 'sort_order' => 230, 'field_default_value' => '0'],
            ['field_name' => 'UPS_Worldwide_Saver',          'field_label' => 'UPS Worldwide Saver',             'field_type' => 'checkbox', 'sort_order' => 240, 'field_default_value' => '0'],
        ];

        foreach ($upsOptions as $opt) {
            PluginOption::updateOrCreate(
                ['plugin_id' => $upsPlugin->id, 'field_name' => $opt['field_name']],
                array_merge(['field_data_format' => 'string', 'field_required' => 'no', 'field_editor' => null, 'field_help' => null, 'field_default_value' => '0'], $opt)
            );
            if (!PluginSetting::where('plugin_id', $upsPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'   => $upsPlugin->id,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ]);
            }
        }

        // ── USPS Plugin ───────────────────────────────────────────────────────
        $uspsPlugin = Plugin::updateOrCreate(
            ['filename' => 'usps_api_2026'],
            [
                'name'                 => 'Shipping Rates - USPS REST API v3 (2026)',
                'shortcode'            => 'usps-api',
                'type'                 => 'shipping',
                'author'               => 'Built-in',
                'version'              => '1.0',
                'install_type'         => 1,
                'activation_required'  => 'yes',
                'activation_status'    => 0,
                'description'          => 'Real-time USPS shipping rates via the new USPS REST API v3 with OAuth2. Replaces the deprecated SOAP Web Tools. Supports Priority Mail, Ground Advantage, First-Class, and international services.',
                'activation_instructions' => '<p>Register at <a href="https://developer.usps.com" target="_blank">developer.usps.com</a>, create an application, and copy your <strong>Client ID</strong> and <strong>Client Secret</strong>. Enter them below and click Activate.</p><p><em>Note: The old USPS Web Tools XML API was deprecated January 2024. This plugin uses the new REST API.</em></p>',
                'activation_success_msg' => 'USPS plugin activated. Enable your desired services in the Settings tab.',
                'activation_failed_msg'  => 'Could not activate. Please verify your USPS Client ID and Client Secret.',
                'usage_instructions'     => '<p>Active USPS shipping rates will automatically appear on the checkout review page alongside other shipping options, sorted by price. No shortcode required for shipping plugins.</p>',
                'help_url'               => 'https://developer.usps.com/api/81',
            ]
        );

        $uspsOptions = [
            ['field_name' => 'USPS_Client_ID',     'field_label' => 'USPS Client ID (OAuth2)',     'field_type' => 'input',  'sort_order' => 10, 'field_required' => 'yes', 'field_help' => 'From developer.usps.com → My Apps', 'field_default_value' => ''],
            ['field_name' => 'USPS_Client_Secret', 'field_label' => 'USPS Client Secret (OAuth2)', 'field_type' => 'input',  'sort_order' => 20, 'field_required' => 'yes', 'field_help' => 'Keep confidential', 'field_default_value' => ''],
            ['field_name' => 'USPS_From_Zip',      'field_label' => 'Origin ZIP Code',             'field_type' => 'input',  'sort_order' => 30, 'field_required' => 'yes', 'field_help' => 'ZIP code your shipments originate from (5 digits, US only)', 'field_default_value' => ''],
            ['field_name' => 'USPS_Markup',        'field_label' => 'Rate Markup / Markdown ($)',  'field_type' => 'input',  'sort_order' => 40, 'field_required' => 'no',  'field_help' => 'Added to every rate. Use negative to discount. e.g. 0.50 or -0.25', 'field_default_value' => '0.00'],
            // Domestic services
            ['field_name' => 'USPS_Priority_Mail',              'field_label' => 'Priority Mail (1-3 days)',               'field_type' => 'checkbox', 'sort_order' => 100, 'field_default_value' => '1'],
            ['field_name' => 'USPS_Priority_Mail_Express',      'field_label' => 'Priority Mail Express (overnight)',       'field_type' => 'checkbox', 'sort_order' => 110, 'field_default_value' => '1'],
            ['field_name' => 'USPS_Ground_Advantage',           'field_label' => 'USPS Ground Advantage (2-5 days)',        'field_type' => 'checkbox', 'sort_order' => 120, 'field_default_value' => '1'],
            ['field_name' => 'USPS_First_Class_Package',        'field_label' => 'First-Class Package Service',             'field_type' => 'checkbox', 'sort_order' => 130, 'field_default_value' => '0'],
            ['field_name' => 'USPS_Parcel_Select',              'field_label' => 'Parcel Select (economy)',                 'field_type' => 'checkbox', 'sort_order' => 140, 'field_default_value' => '0'],
            ['field_name' => 'USPS_Parcel_Select_Lightweight',  'field_label' => 'Parcel Select Lightweight',               'field_type' => 'checkbox', 'sort_order' => 150, 'field_default_value' => '0'],
            ['field_name' => 'USPS_Priority_Mail_Cubic',        'field_label' => 'Priority Mail Cubic',                     'field_type' => 'checkbox', 'sort_order' => 160, 'field_default_value' => '0'],
            // International services
            ['field_name' => 'USPS_Priority_Mail_Express_Intl', 'field_label' => 'Priority Mail Express International',    'field_type' => 'checkbox', 'sort_order' => 200, 'field_default_value' => '0'],
            ['field_name' => 'USPS_Priority_Mail_Intl',         'field_label' => 'Priority Mail International',            'field_type' => 'checkbox', 'sort_order' => 210, 'field_default_value' => '0'],
            ['field_name' => 'USPS_First_Class_Package_Intl',   'field_label' => 'First-Class Package International',      'field_type' => 'checkbox', 'sort_order' => 220, 'field_default_value' => '0'],
        ];

        foreach ($uspsOptions as $opt) {
            PluginOption::updateOrCreate(
                ['plugin_id' => $uspsPlugin->id, 'field_name' => $opt['field_name']],
                array_merge(['field_data_format' => 'string', 'field_required' => 'no', 'field_editor' => null, 'field_help' => null, 'field_default_value' => '0'], $opt)
            );
            if (!PluginSetting::where('plugin_id', $uspsPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'   => $uspsPlugin->id,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ]);
            }
        }

        // ── Featured Items Plugin ─────────────────────────────────────────────
        $featuredPlugin = Plugin::updateOrCreate(
            ['filename' => 'featured_items_2026'],
            [
                'name'                => 'Featured Items Display (2026)',
                'shortcode'           => 'featured-items',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Displays products that have the Featured Item flag enabled. Supports grid, list, and Swiper slider display modes. Shortcode: [plugin:featured-items]',
                'usage_instructions'  => '<p>Add <strong>[plugin:featured-items]</strong> to any CMS page to display your featured products. '
                    . 'Available parameters:</p><ul>'
                    . '<li><strong>display</strong> — <code>grid</code> (default), <code>list</code>, or <code>slider</code></li>'
                    . '<li><strong>max</strong> — maximum number of products to show (default: 12)</li>'
                    . '<li><strong>cols</strong> — grid columns 2–6 (default: 4, grid mode only)</li>'
                    . '<li><strong>sort</strong> — <code>random</code>, <code>newest</code>, or <code>name</code></li>'
                    . '<li><strong>header</strong> — optional section title text</li>'
                    . '<li><strong>slides</strong> — visible slides at desktop (default: 4, slider mode only)</li>'
                    . '<li><strong>nav</strong> — <code>on</code> / <code>off</code> navigation arrows (slider mode)</li>'
                    . '<li><strong>autoplay</strong> — <code>on</code> / <code>off</code> (slider mode)</li>'
                    . '<li><strong>speed</strong> — autoplay delay in ms (default: 4000, slider mode)</li>'
                    . '</ul><p>To mark a product as featured, open the product in Admin → Edit → Advanced Settings and enable the ★ Featured Item toggle, then save.</p>',
            ]
        );

        $featuredDefaultCss = "/* Featured Items 2026 Default Formatting */\n.featured-items-plugin-section {\n    width: 100%;\n}\n.featured-items-card {\n    transition: all 0.3s ease;\n}\n.featured-items-card:hover {\n    transform: translateY(-2px);\n}";

        $featuredOptions = [
            ['field_name' => 'display',       'field_label' => 'Default Display Mode',       'field_type' => 'select',   'field_selections' => 'grid,list,slider', 'sort_order' => 10,  'field_default_value' => 'grid',   'field_help' => 'Can be overridden per shortcode with display=grid|list|slider'],
            ['field_name' => 'max_items',      'field_label' => 'Max Items to Show',          'field_type' => 'input',    'field_data_format' => 'integer', 'sort_order' => 20,  'field_default_value' => '12',     'field_help' => 'Maximum number of featured products to display (1–100)'],
            ['field_name' => 'sort_order',     'field_label' => 'Default Sort Order',         'field_type' => 'select',   'field_selections' => 'random,newest,name', 'sort_order' => 30, 'field_default_value' => 'random', 'field_help' => 'How to sort the displayed products'],
            ['field_name' => 'header_title',   'field_label' => 'Default Section Header',     'field_type' => 'input',    'field_data_format' => 'string',  'sort_order' => 40,  'field_default_value' => '',       'field_help' => 'Optional heading displayed above the product grid. Leave blank for no header.'],
            ['field_name' => 'grid_columns',   'field_label' => 'Grid Columns (default)',      'field_type' => 'select',   'field_selections' => '2,3,4,5,6', 'sort_order' => 50, 'field_default_value' => '4',      'field_help' => 'Number of columns in grid mode (can be overridden with cols= param)'],
            ['field_name' => 'slides_visible', 'field_label' => 'Slider: Visible Slides (Desktop)', 'field_type' => 'input', 'field_data_format' => 'integer', 'sort_order' => 60, 'field_default_value' => '4',   'field_help' => 'How many product cards are visible at once in slider mode on desktop'],
            ['field_name' => 'show_nav',       'field_label' => 'Slider: Show Navigation Arrows', 'field_type' => 'select', 'field_selections' => 'on,off', 'sort_order' => 70, 'field_default_value' => 'on',    'field_help' => 'Show prev/next arrows in slider mode'],
            ['field_name' => 'autoplay',       'field_label' => 'Slider: Autoplay',           'field_type' => 'select',   'field_selections' => 'on,off', 'sort_order' => 80,  'field_default_value' => 'on',     'field_help' => 'Automatically advance slides in slider mode'],
            ['field_name' => 'autoplay_speed', 'field_label' => 'Slider: Autoplay Speed (ms)', 'field_type' => 'input',  'field_data_format' => 'integer', 'sort_order' => 90, 'field_default_value' => '4000',   'field_help' => 'Milliseconds between slide advances (e.g. 4000 = 4 seconds)'],
            ['field_name' => 'custom_css',      'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css',                'sort_order' => 95, 'field_default_value' => ''],
            ['field_name' => 'default_css',     'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 96, 'field_default_value' => $featuredDefaultCss],
        ];

        PluginOption::where('plugin_id', $featuredPlugin->id)->delete();

        foreach ($featuredOptions as $opt) {
            PluginOption::create(array_merge([
                'plugin_id'         => $featuredPlugin->id,
                'field_data_format' => 'string',
                'field_required'    => 'no',
                'field_editor'      => null,
                'field_selections'  => null,
                'field_help'        => null,
                'field_min_value'   => null,
                'field_max_value'   => null,
            ], $opt));

            if (!PluginSetting::where('plugin_id', $featuredPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'   => $featuredPlugin->id,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ]);
            }
        }

        // ── Cross-Sell List Plugin ────────────────────────────────────────────
        $crossSellPlugin = Plugin::updateOrCreate(
            ['filename' => 'cross_sell_list_2026'],
            [
                'name'                => 'Cross-Sell List Display (2026)',
                'shortcode'           => 'cross-sell-list',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Displays the cross-selling products linked to a specific product. Filter by Product ID. Supports grid, list, and Swiper slider display modes. Shortcode: [plugin:cross-sell-list product_id=X]',
                'usage_instructions'  => '<p>Add <strong>[plugin:cross-sell-list product_id=X]</strong> to any CMS page to display the cross-sell products for a given product. '
                    . 'Replace <code>X</code> with the numeric product ID. Available parameters:</p><ul>'
                    . '<li><strong>product_id</strong> — the product whose cross-sells to display (required)</li>'
                    . '<li><strong>display</strong> — <code>grid</code> (default), <code>list</code>, or <code>slider</code></li>'
                    . '<li><strong>max</strong> — maximum number of products to show (default: 12)</li>'
                    . '<li><strong>cols</strong> — grid columns 2–6 (default: 4, grid mode only)</li>'
                    . '<li><strong>sort</strong> — <code>sort_order</code> (default), <code>newest</code>, or <code>name</code></li>'
                    . '<li><strong>header</strong> — optional section title text</li>'
                    . '<li><strong>slides</strong> — visible slides at desktop (default: 4, slider mode only)</li>'
                    . '<li><strong>nav</strong> — <code>on</code> / <code>off</code> navigation arrows (slider mode)</li>'
                    . '<li><strong>autoplay</strong> — <code>on</code> / <code>off</code> (slider mode)</li>'
                    . '<li><strong>speed</strong> — autoplay delay in ms (default: 4000, slider mode)</li>'
                    . '</ul><p>Cross-sells are managed per product under Admin → Edit Product → Cross-Selling. Only items with <em>Display on Item View</em> enabled will appear.</p>',
            ]
        );

        $crossSellDefaultCss = "/* Cross-Sell List 2026 Default Formatting */\n.cross-sell-plugin-section {\n    width: 100%;\n}\n.cross-sell-card {\n    transition: all 0.3s ease;\n}\n.cross-sell-card:hover {\n    transform: translateY(-2px);\n}";

        $crossSellOptions = [
            ['field_name' => 'product_id',     'field_label' => 'Default Product ID',            'field_type' => 'input',    'field_data_format' => 'integer', 'sort_order' => 5,   'field_default_value' => '',      'field_help' => 'Numeric product ID whose cross-sells to display. Can be overridden per shortcode with product_id=X'],
            ['field_name' => 'display',         'field_label' => 'Default Display Mode',          'field_type' => 'select',   'field_selections' => 'grid,list,slider', 'sort_order' => 10,  'field_default_value' => 'grid',  'field_help' => 'Can be overridden per shortcode with display=grid|list|slider'],
            ['field_name' => 'max_items',        'field_label' => 'Max Items to Show',             'field_type' => 'input',    'field_data_format' => 'integer', 'sort_order' => 20,  'field_default_value' => '12',    'field_help' => 'Maximum number of cross-sell products to display (1–100)'],
            ['field_name' => 'sort_order',       'field_label' => 'Default Sort Order',            'field_type' => 'select',   'field_selections' => 'sort_order,newest,name', 'sort_order' => 30, 'field_default_value' => 'sort_order', 'field_help' => 'How to sort the displayed products'],
            ['field_name' => 'header_title',     'field_label' => 'Default Section Header',        'field_type' => 'input',    'field_data_format' => 'string',  'sort_order' => 40,  'field_default_value' => '',      'field_help' => 'Optional heading displayed above the product grid. Leave blank for no header.'],
            ['field_name' => 'grid_columns',     'field_label' => 'Grid Columns (default)',         'field_type' => 'select',   'field_selections' => '2,3,4,5,6', 'sort_order' => 50, 'field_default_value' => '4',     'field_help' => 'Number of columns in grid mode (can be overridden with cols= param)'],
            ['field_name' => 'slides_visible',   'field_label' => 'Slider: Visible Slides (Desktop)', 'field_type' => 'input', 'field_data_format' => 'integer', 'sort_order' => 60, 'field_default_value' => '4',    'field_help' => 'How many product cards are visible at once in slider mode on desktop'],
            ['field_name' => 'show_nav',         'field_label' => 'Slider: Show Navigation Arrows', 'field_type' => 'select', 'field_selections' => 'on,off',   'sort_order' => 70,  'field_default_value' => 'on',    'field_help' => 'Show prev/next arrows in slider mode'],
            ['field_name' => 'autoplay',         'field_label' => 'Slider: Autoplay',              'field_type' => 'select',   'field_selections' => 'on,off',   'sort_order' => 80,  'field_default_value' => 'on',    'field_help' => 'Automatically advance slides in slider mode'],
            ['field_name' => 'autoplay_speed',   'field_label' => 'Slider: Autoplay Speed (ms)',   'field_type' => 'input',    'field_data_format' => 'integer', 'sort_order' => 90,  'field_default_value' => '4000',  'field_help' => 'Milliseconds between slide advances (e.g. 4000 = 4 seconds)'],
            ['field_name' => 'custom_css',      'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css',                'sort_order' => 95, 'field_default_value' => ''],
            ['field_name' => 'default_css',     'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 96, 'field_default_value' => $crossSellDefaultCss],
        ];

        PluginOption::where('plugin_id', $crossSellPlugin->id)->delete();

        foreach ($crossSellOptions as $opt) {
            PluginOption::create(array_merge([
                'plugin_id'         => $crossSellPlugin->id,
                'field_data_format' => 'string',
                'field_required'    => 'no',
                'field_editor'      => null,
                'field_selections'  => null,
                'field_help'        => null,
                'field_min_value'   => null,
                'field_max_value'   => null,
            ], $opt));

            if (!PluginSetting::where('plugin_id', $crossSellPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create([
                    'plugin_id'   => $crossSellPlugin->id,
                    'field_name'  => $opt['field_name'],
                    'field_value' => $opt['field_default_value'],
                ]);
            }
        }

        // ── Brands 2026 Plugin ────────────────────────────────────────────────
        $brandsPlugin = Plugin::updateOrCreate(
            ['filename' => 'brands_2026'],
            [
                'name'                => 'Brands Display (2026)',
                'shortcode'           => 'brands-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Displays brand logos and links to brand SEO slugs in Slider, Grid, or List mode. Shortcode: [plugin:brands-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:brands-2026]</strong> to any page. Options: <code>display=slider|grid|list</code>, <code>max=12</code>, <code>cols=4</code>, <code>header="Featured Brands"</code>.</p>',
            ]
        );

        $brandsDefaultCss = "/* Brands 2026 Default Formatting */\n.brands-plugin-wrapper {\n    width: 100%;\n}\n.brands-plugin-card img {\n    filter: grayscale(100%);\n    transition: filter 0.3s ease;\n}\n.brands-plugin-card:hover img {\n    filter: grayscale(0%);\n}";

        $brandsOptions = [
            ['field_name' => 'display_type', 'field_label' => 'Display Mode', 'field_type' => 'select', 'field_selections' => 'slider,grid,list', 'sort_order' => 10, 'field_default_value' => 'slider'],
            ['field_name' => 'max_brands',   'field_label' => 'Max Brands',   'field_type' => 'input',  'field_data_format' => 'integer', 'sort_order' => 20, 'field_default_value' => '12'],
            ['field_name' => 'columns',      'field_label' => 'Columns',      'field_type' => 'select', 'field_selections' => '2,3,4,5,6',     'sort_order' => 30, 'field_default_value' => '4'],
            ['field_name' => 'header_title', 'field_label' => 'Header Title', 'field_type' => 'input',  'field_data_format' => 'string',  'sort_order' => 40, 'field_default_value' => 'Featured Brands'],
            ['field_name' => 'custom_css',   'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css', 'sort_order' => 50, 'field_default_value' => ''],
            ['field_name' => 'default_css',  'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 55, 'field_default_value' => $brandsDefaultCss],
        ];

        PluginOption::where('plugin_id', $brandsPlugin->id)->delete();
        foreach ($brandsOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $brandsPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $brandsPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $brandsPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Categories 2026 Plugin ───────────────────────────────────────────
        $categoriesPlugin = Plugin::updateOrCreate(
            ['filename' => 'categories_2026'],
            [
                'name'                => 'Top Level Categories Display (2026)',
                'shortcode'           => 'categories-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Displays TOP LEVEL categories only with image, name, and link to category SEO slug. Shortcode: [plugin:categories-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:categories-2026]</strong> to any page. Options: <code>display=grid|slider|list</code>, <code>max=12</code>, <code>cols=4</code>, <code>header="Top Categories"</code>.</p>',
            ]
        );

        $categoriesDefaultCss = "/* Categories 2026 Default Formatting */\n.categories-plugin-wrapper {\n    width: 100%;\n}\n.categories-plugin-card {\n    transition: all 0.3s ease;\n}\n.categories-plugin-card:hover {\n    transform: translateY(-2px);\n}";

        $categoriesOptions = [
            ['field_name' => 'display_type',   'field_label' => 'Display Mode', 'field_type' => 'select', 'field_selections' => 'grid,slider,list', 'sort_order' => 10, 'field_default_value' => 'grid'],
            ['field_name' => 'max_categories', 'field_label' => 'Max Categories', 'field_type' => 'input', 'field_data_format' => 'integer', 'sort_order' => 20, 'field_default_value' => '12'],
            ['field_name' => 'columns',        'field_label' => 'Columns',      'field_type' => 'select', 'field_selections' => '2,3,4,5,6',     'sort_order' => 30, 'field_default_value' => '4'],
            ['field_name' => 'header_title',   'field_label' => 'Header Title', 'field_type' => 'input',  'field_data_format' => 'string',  'sort_order' => 40, 'field_default_value' => 'Top Categories'],
            ['field_name' => 'custom_css',      'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css', 'sort_order' => 50, 'field_default_value' => ''],
            ['field_name' => 'default_css',     'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 55, 'field_default_value' => $categoriesDefaultCss],
        ];

        PluginOption::where('plugin_id', $categoriesPlugin->id)->delete();
        foreach ($categoriesOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $categoriesPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $categoriesPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $categoriesPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Site News Flash 2026 Plugin ──────────────────────────────────────
        $newsflashPlugin = Plugin::updateOrCreate(
            ['filename' => 'newsflash_2026'],
            [
                'name'                => 'Site News Flash Banner (2026)',
                'shortcode'           => 'newsflash-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Renders an announcement news flash ticker banner. Shortcode: [plugin:newsflash-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:newsflash-2026]</strong> to any header or page section. Options: <code>message="Announcement Text"</code>, <code>bg_color="#4f46e5"</code>, <code>text_color="#ffffff"</code>, <code>dismissible=on</code>.</p>',
            ]
        );

        $newsflashDefaultCss = "/* Newsflash 2026 Default Formatting */\n.site-news-flash-ticker {\n    width: 100%;\n    transition: all 0.2s ease;\n}\n.site-news-flash-ticker a {\n    text-decoration: underline;\n}";

        $newsflashOptions = [
            ['field_name' => 'news_message', 'field_label' => 'News Message Text', 'field_type' => 'input', 'field_data_format' => 'string', 'sort_order' => 10, 'field_default_value' => 'Welcome to our store! Enjoy free shipping on orders over $50.'],
            ['field_name' => 'bg_color',     'field_label' => 'Background Color', 'field_type' => 'input', 'field_data_format' => 'string', 'sort_order' => 20, 'field_default_value' => 'transparent'],
            ['field_name' => 'text_color',   'field_label' => 'Text Color',       'field_type' => 'input', 'field_data_format' => 'string', 'sort_order' => 30, 'field_default_value' => ''],
            ['field_name' => 'dismissible',  'field_label' => 'Allow Dismiss',    'field_type' => 'select', 'field_selections' => 'on,off',     'sort_order' => 40, 'field_default_value' => 'off'],
            ['field_name' => 'custom_css',   'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css', 'sort_order' => 50, 'field_default_value' => ''],
            ['field_name' => 'default_css',  'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 55, 'field_default_value' => $newsflashDefaultCss],
        ];

        PluginOption::where('plugin_id', $newsflashPlugin->id)->delete();
        foreach ($newsflashOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $newsflashPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $newsflashPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $newsflashPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Testimonials 2026 Plugin ──────────────────────────────────────────
        $testimonialsPlugin = Plugin::updateOrCreate(
            ['filename' => 'testimonials_2026'],
            [
                'name'                => 'Testimonials Display (2026)',
                'shortcode'           => 'testimonials-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Renders testimonials from the CMS testimonials table in Slider or List view with ratings and quote icons. Shortcode: [plugin:testimonials-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:testimonials-2026]</strong> to any page. Options: <code>display=slider|list</code>, <code>max=6</code>, <code>cols=3</code>, <code>quote_icon=quote-left|none</code>, <code>header="What Our Customers Say"</code>.</p>',
            ]
        );

        $testimonialsDefaultCss = "/* Testimonials 2026 Default Formatting */\n.testimonials-plugin-wrapper {\n    width: 100%;\n}\n.testimonials-card {\n    transition: all 0.3s ease;\n}\n.testimonials-card:hover {\n    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);\n}";

        $testimonialsOptions = [
            ['field_name' => 'display_type', 'field_label' => 'Display Mode', 'field_type' => 'select', 'field_selections' => 'slider,list', 'sort_order' => 10, 'field_default_value' => 'slider'],
            ['field_name' => 'max_items',    'field_label' => 'Max Testimonials', 'field_type' => 'input', 'field_data_format' => 'integer', 'sort_order' => 20, 'field_default_value' => '6'],
            ['field_name' => 'columns',      'field_label' => 'Columns',      'field_type' => 'select', 'field_selections' => '1,2,3,4',        'sort_order' => 30, 'field_default_value' => '3'],
            ['field_name' => 'quote_icon',    'field_label' => 'Quote Icon Style', 'field_type' => 'select', 'field_selections' => 'quote-left,none', 'sort_order' => 40, 'field_default_value' => 'quote-left'],
            ['field_name' => 'header_title', 'field_label' => 'Header Title', 'field_type' => 'input',  'field_data_format' => 'string',  'sort_order' => 50, 'field_default_value' => 'What Our Customers Say'],
            ['field_name' => 'custom_css',   'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css', 'sort_order' => 60, 'field_default_value' => ''],
            ['field_name' => 'default_css',  'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 65, 'field_default_value' => $testimonialsDefaultCss],
        ];

        PluginOption::where('plugin_id', $testimonialsPlugin->id)->delete();
        foreach ($testimonialsOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $testimonialsPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $testimonialsPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $testimonialsPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Social Icons 2026 Plugin ──────────────────────────────────────────
        $socialPlugin = Plugin::updateOrCreate(
            ['filename' => 'social_icons_2026'],
            [
                'name'                => 'Social Media Icons (2026)',
                'shortcode'           => 'social-icons-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Renders social media profile links with optional Font-Awesome 6 CDN integration. Shortcode: [plugin:social-icons-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:social-icons-2026]</strong> to any page or footer block. Options: <code>align=left|center|right</code>, <code>size=sm|md|lg</code>, <code>font_awesome=on|off</code>.</p>',
            ]
        );

        $socialDefaultCss = "/* Social Media Icons 2026 Default Formatting */\n.social-icons-2026-wrapper {\n    display: flex;\n    align-items: center;\n    gap: 0.75rem;\n}\n.social-icon-link {\n    transition: color 0.2s ease, transform 0.2s ease;\n}\n.social-icon-link:hover {\n    transform: translateY(-1px);\n}";

        $socialOptions = [
            ['field_name' => 'icon_style',          'field_label' => 'Icon Style (Shape)',          'field_type' => 'select', 'field_selections' => 'non-circle,circle', 'sort_order' => 5,  'field_default_value' => 'non-circle'],
            ['field_name' => 'alignment',           'field_label' => 'Alignment',                   'field_type' => 'select', 'field_selections' => 'left,center,right', 'sort_order' => 10, 'field_default_value' => 'left'],
            ['field_name' => 'icon_size',           'field_label' => 'Icon Size',                   'field_type' => 'select', 'field_selections' => 'sm,md,lg',          'sort_order' => 20, 'field_default_value' => 'md'],
            ['field_name' => 'use_font_awesome',    'field_label' => 'Font-Awesome CDN',           'field_type' => 'select', 'field_selections' => 'on,off',            'sort_order' => 30, 'field_default_value' => 'on'],
            ['field_name' => 'header_icon_color',   'field_label' => 'Header Icon Color Override',  'field_type' => 'color',  'field_data_format' => 'string',       'sort_order' => 31, 'field_default_value' => '#ffffff'],
            ['field_name' => 'footer_icon_color',   'field_label' => 'Footer Icon Color Override',  'field_type' => 'color',  'field_data_format' => 'string',       'sort_order' => 32, 'field_default_value' => ''],
            ['field_name' => 'icon_color_override', 'field_label' => 'General Icon Color Override', 'field_type' => 'color',  'field_data_format' => 'string',       'sort_order' => 33, 'field_default_value' => ''],
            ['field_name' => 'phone_number',        'field_label' => 'Phone Number / Link',         'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 35, 'field_default_value' => ''],
            ['field_name' => 'email_address',       'field_label' => 'Email Address / Link',        'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 36, 'field_default_value' => ''],
            ['field_name' => 'facebook_url',        'field_label' => 'Facebook URL',                 'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 40, 'field_default_value' => 'https://facebook.com'],
            ['field_name' => 'twitter_url',         'field_label' => 'X / Twitter URL',              'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 50, 'field_default_value' => 'https://twitter.com'],
            ['field_name' => 'instagram_url',       'field_label' => 'Instagram URL',                'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 60, 'field_default_value' => 'https://instagram.com'],
            ['field_name' => 'youtube_url',         'field_label' => 'YouTube URL',                  'field_type' => 'input',  'field_data_format' => 'string',       'sort_order' => 70, 'field_default_value' => 'https://youtube.com'],
            ['field_name' => 'custom_css',          'field_label' => 'Custom CSS Overrides',        'field_type' => 'textarea', 'field_editor' => 'css',                'sort_order' => 80, 'field_default_value' => ''],
            ['field_name' => 'default_css',         'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 85, 'field_default_value' => $socialDefaultCss],
        ];

        PluginOption::where('plugin_id', $socialPlugin->id)->delete();
        foreach ($socialOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $socialPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $socialPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $socialPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Live Search 2026 Plugin ───────────────────────────────────────────
        $searchPlugin = Plugin::updateOrCreate(
            ['filename' => 'live_search_2026'],
            [
                'name'                => 'Live Search Display (2026)',
                'shortcode'           => 'live-search-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Multi-content live search input and results display for active Products, CMS Pages, KB Articles, and Testimonials. Shortcode: [plugin:live-search-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:live-search-2026]</strong> to render a search bar widget. To render search results on a page, use: <strong>[plugin:live-search-2026 mode=results]</strong>. Options: <code>placeholder="..."</code>, <code>button_label="..."</code>, <code>layout=list|grid</code>, <code>custom_css="..."</code>, <code>custom_js="..."</code>.</p>',
            ]
        );

        $searchDefaultCss = "/* Live Search 2026 Default Formatting */
.live-search-2026-wrapper {
    position: relative;
    width: 100%;
}
.live-search-form {
    display: flex;
    align-items: stretch;
    width: 100%;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 0.75rem;
    overflow: hidden;
}
.live-search-form input[type=\"text\"] {
    width: 100%;
    max-width: 250px;
    padding: 0.75rem 1rem;
    border: none;
    outline: none;
    font-size: 0.875rem;
}
.live-search-form button[type=\"submit\"] {
    padding: 0.75rem 1.5rem;
    background-color: #4f46e5;
    color: #ffffff;
    font-weight: 700;
    border: none;
    border-top-left-radius: 0px !important;
    border-bottom-left-radius: 0px !important;
    border-top-right-radius: 0.75rem;
    border-bottom-right-radius: 0.75rem;
    cursor: pointer;
}
.live-search-form button[type=\"submit\"]:hover {
    background-color: #4338ca;
}";

        $searchOptions = [
            ['field_name' => 'default_mode',   'field_label' => 'Default Mode',            'field_type' => 'select',   'field_selections' => 'input,results', 'sort_order' => 10, 'field_default_value' => 'input'],
            ['field_name' => 'placeholder',    'field_label' => 'Input Placeholder Text',  'field_type' => 'input',    'field_data_format' => 'string',       'sort_order' => 20, 'field_default_value' => 'Search products, pages, articles...'],
            ['field_name' => 'button_label',   'field_label' => 'Button Label',            'field_type' => 'input',    'field_data_format' => 'string',       'sort_order' => 30, 'field_default_value' => 'Search'],
            ['field_name' => 'layout',         'field_label' => 'Results Layout Mode',     'field_type' => 'select',   'field_selections' => 'list,grid',     'sort_order' => 40, 'field_default_value' => 'list'],
            ['field_name' => 'custom_css',      'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css',                'sort_order' => 50, 'field_default_value' => ''],
            ['field_name' => 'default_css',     'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 55, 'field_default_value' => $searchDefaultCss],
            ['field_name' => 'custom_js',       'field_label' => 'Custom JS Override Widget','field_type' => 'textarea', 'field_editor' => 'javascript',         'sort_order' => 60, 'field_default_value' => ''],
        ];

        PluginOption::where('plugin_id', $searchPlugin->id)->delete();
        foreach ($searchOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $searchPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $searchPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $searchPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }

        // ── Events Calendar 2026 Plugin ──────────────────────────────────────
        $eventsCalendarPlugin = Plugin::updateOrCreate(
            ['filename' => 'events_calendar_2026'],
            [
                'name'                => 'Events Calendar Display (2026)',
                'shortcode'           => 'events-calendar-2026',
                'type'                => 'display',
                'author'              => 'Built-in',
                'version'             => '1.0',
                'install_type'        => 1,
                'activation_required' => 'no',
                'activation_status'   => 1,
                'description'         => 'Interactive events calendar displaying products marked as events based on start and stop dates. Shortcode: [plugin:events-calendar-2026]',
                'usage_instructions'  => '<p>Add <strong>[plugin:events-calendar-2026]</strong> to any page. Options: <code>layout=month|list|grid</code>, <code>max=50</code>, <code>header="Upcoming Events"</code>, <code>category="slug"</code>.</p>',
            ]
        );

        $eventsDefaultCss = "/* Events Calendar 2026 Formatting */\n.events-calendar-wrapper {\n    width: 100%;\n}";

        $eventsOptions = [
            ['field_name' => 'header_title',   'field_label' => 'Header Title',            'field_type' => 'input',    'field_data_format' => 'string',       'sort_order' => 10, 'field_default_value' => 'Upcoming Events Calendar'],
            ['field_name' => 'default_layout',  'field_label' => 'Default Layout Mode',     'field_type' => 'select',   'field_selections' => 'month,list,grid','sort_order' => 20, 'field_default_value' => 'month'],
            ['field_name' => 'max_events',     'field_label' => 'Max Events to Display',   'field_type' => 'input',    'field_data_format' => 'integer',      'sort_order' => 30, 'field_default_value' => '50'],
            ['field_name' => 'custom_css',      'field_label' => 'Custom CSS Overrides',    'field_type' => 'textarea', 'field_editor' => 'css',                'sort_order' => 40, 'field_default_value' => ''],
            ['field_name' => 'default_css',     'field_label' => 'Default Plugin CSS (Read-Only Reference)', 'field_type' => 'text-only', 'sort_order' => 50, 'field_default_value' => $eventsDefaultCss],
        ];

        PluginOption::where('plugin_id', $eventsCalendarPlugin->id)->delete();
        foreach ($eventsOptions as $opt) {
            PluginOption::create(array_merge(['plugin_id' => $eventsCalendarPlugin->id], $opt));
            if (!PluginSetting::where('plugin_id', $eventsCalendarPlugin->id)->where('field_name', $opt['field_name'])->exists()) {
                PluginSetting::create(['plugin_id' => $eventsCalendarPlugin->id, 'field_name' => $opt['field_name'], 'field_value' => $opt['field_default_value']]);
            }
        }
    }
}
