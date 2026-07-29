<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CmsSetting;
use App\Helpers\TimezoneHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminSettings extends Component
{
    use WithFileUploads;

    // Demo content state
    public bool $confirmingDemoPurge = false;

    // Site Identity
    public string $site_name = '';
    public string $logo_type = '';        // local|s3|cdn|url|svg
    public string $logo_path = '';
    public string $logo_cdn_url = '';
    public string $logo_svg_html = '';
    public string $logo_s3_bucket = '';
    public string $logo_s3_key = '';
    public string $logo_s3_secret = '';
    public string $logo_s3_region = 'us-east-1';
    public $logo_upload = null;           // Livewire temporary upload

    // Appearance
    public bool $frontend_dark_mode = false;
    public bool $admin_dark_mode = false;
    public string $theme_primary_color = '#4f46e5';
    public string $theme_hover_color = '#4338ca';
    public string $theme_text_color = '#ffffff';
    public string $theme_border_radius = '0.75rem';
    public string $theme_secondary_bg_color = 'transparent';
    public string $theme_secondary_text_color = '#4f46e5';
    public string $theme_secondary_border_color = '#4f46e5';
    public string $theme_secondary_hover_bg_color = '#4f46e5';
    public string $theme_secondary_hover_text_color = '#ffffff';

    // Go to Top Button
    public string $backtop_bg_color = '';
    public string $backtop_hover_bg_color = '';

    // Loaders
    public ?string $google_fonts_url = '';
    public ?string $google_analytics_id = '';
    public ?string $custom_js_loader = '';

    // General
    public string $timezone = 'America/New_York';

    // Reviews Settings
    public bool $enable_reviews = false;
    public string $third_party_reviews_js = '';

    // CMS Downloads
    public string $file_icon_pack = 'vivid'; // vivid | classic | square

    // Shop Display
    public string $product_image_orientation = '16:9'; // 16:9 | 1:1
    public bool   $disable_shop_landing = false;
    public bool   $enable_advanced_shop_search = false;

    public function mount(): void
    {
        $settings = CmsSetting::allCached();

        // Site Identity
        $this->site_name     = $settings['site_name'] ?? '';
        $this->logo_type     = $settings['logo_type'] ?? '';
        $this->logo_path     = $settings['logo_path'] ?? '';
        $this->logo_cdn_url  = $settings['logo_cdn_url'] ?? '';
        $this->logo_svg_html = $settings['logo_svg_html'] ?? '';
        $this->logo_s3_bucket  = $settings['logo_s3_bucket'] ?? '';
        $this->logo_s3_key     = $settings['logo_s3_key'] ?? '';
        $this->logo_s3_secret  = $settings['logo_s3_secret'] ?? '';
        $this->logo_s3_region  = $settings['logo_s3_region'] ?? 'us-east-1';

        // Appearance
        $this->frontend_dark_mode          = (bool) ($settings['frontend_dark_mode']  ?? false);
        $this->admin_dark_mode             = (bool) ($settings['admin_dark_mode']      ?? false);
        $this->theme_primary_color         = $settings['theme_primary_color'] ?? '#4f46e5';
        $this->theme_hover_color           = $settings['theme_hover_color']   ?? '#4338ca';
        $this->theme_text_color            = $settings['theme_text_color']    ?? '#ffffff';
        $this->theme_border_radius         = $settings['theme_border_radius'] ?? '0.75rem';
        $this->theme_secondary_bg_color    = $settings['theme_secondary_bg_color']    ?? 'transparent';
        $this->theme_secondary_text_color  = $settings['theme_secondary_text_color']  ?? ($settings['theme_primary_color'] ?? '#4f46e5');
        $this->theme_secondary_border_color = $settings['theme_secondary_border_color'] ?? ($settings['theme_primary_color'] ?? '#4f46e5');
        $this->theme_secondary_hover_bg_color   = $settings['theme_secondary_hover_bg_color']   ?? ($settings['theme_primary_color'] ?? '#4f46e5');
        $this->theme_secondary_hover_text_color = $settings['theme_secondary_hover_text_color'] ?? '#ffffff';

        // Go to Top Button
        $this->backtop_bg_color       = $settings['backtop_bg_color'] ?? '';
        $this->backtop_hover_bg_color = $settings['backtop_hover_bg_color'] ?? '';

        // Loaders
        $this->google_fonts_url    = $settings['google_fonts_url']    ?? '';
        $this->google_analytics_id = $settings['google_analytics_id'] ?? '';
        $this->custom_js_loader    = $settings['custom_js_loader']    ?? '';

        // General
        $this->timezone = $settings['timezone'] ?? 'America/New_York';

        // Reviews
        $this->enable_reviews = (bool) ($settings['enable_reviews'] ?? false);
        $this->third_party_reviews_js = $settings['third_party_reviews_js'] ?? '';

        // CMS Downloads
        $this->file_icon_pack = $settings['file_icon_pack'] ?? 'vivid';

        // Shop Display
        $this->product_image_orientation    = $settings['product_image_orientation'] ?? '16:9';
        $this->disable_shop_landing         = (bool) ($settings['disable_shop_landing'] ?? false);
        $this->enable_advanced_shop_search  = (bool) ($settings['enable_advanced_shop_search'] ?? false);
    }

    public function save(): void
    {
        $this->validate([
            'site_name'           => 'nullable|string|max:255',
            'logo_type'           => 'nullable|string|in:local,s3,custom_s3,cdn,url,svg,',
            'logo_path'           => 'nullable|string|max:500',
            'logo_cdn_url'        => 'nullable|url|max:500',
            'logo_svg_html'       => 'nullable|string',
            'logo_s3_bucket'      => 'nullable|string|max:255',
            'logo_s3_key'         => 'nullable|string|max:255',
            'logo_s3_secret'      => 'nullable|string|max:255',
            'logo_s3_region'      => 'nullable|string|max:100',
            'google_fonts_url'    => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:50',
            'custom_js_loader'    => 'nullable|string',
            'timezone'            => ['required', 'string', \Illuminate\Validation\Rule::in(TimezoneHelper::all())],
            'theme_primary_color' => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'theme_hover_color'   => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'theme_text_color'    => 'required|string|regex:/^#[0-9a-fA-F]{6}$/',
            'theme_border_radius' => 'required|string|max:50',
            'backtop_bg_color'       => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'backtop_hover_bg_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'enable_reviews'      => 'boolean',
            'third_party_reviews_js' => 'nullable|string',
            'file_icon_pack'      => 'required|string|in:vivid,classic,square',
            'product_image_orientation' => 'required|string|in:16:9,1:1',
            'disable_shop_landing' => 'boolean',
            'enable_advanced_shop_search' => 'boolean',
        ]);

        // Handle local logo file upload
        if ($this->logo_upload && $this->logo_type === 'local') {
            $path = $this->logo_upload->store('logos', 'public');
            $this->logo_path = $path;
        }

        CmsSetting::setMany([
            'site_name'          => trim($this->site_name),
            'logo_type'          => $this->logo_type,
            'logo_path'          => trim($this->logo_path),
            'logo_cdn_url'       => trim($this->logo_cdn_url),
            'logo_svg_html'      => trim($this->logo_svg_html),
            'logo_s3_bucket'     => trim($this->logo_s3_bucket),
            'logo_s3_key'        => trim($this->logo_s3_key),
            'logo_s3_secret'     => trim($this->logo_s3_secret),
            'logo_s3_region'     => trim($this->logo_s3_region) ?: 'us-east-1',
            'frontend_dark_mode' => $this->frontend_dark_mode  ? '1' : '0',
            'admin_dark_mode'    => $this->admin_dark_mode      ? '1' : '0',
            'theme_primary_color' => $this->theme_primary_color,
            'theme_hover_color'   => $this->theme_hover_color,
            'theme_text_color'    => $this->theme_text_color,
            'theme_border_radius' => $this->theme_border_radius,
            'theme_secondary_bg_color'         => $this->theme_secondary_bg_color,
            'theme_secondary_text_color'       => $this->theme_secondary_text_color,
            'theme_secondary_border_color'     => $this->theme_secondary_border_color,
            'theme_secondary_hover_bg_color'   => $this->theme_secondary_hover_bg_color,
            'theme_secondary_hover_text_color' => $this->theme_secondary_hover_text_color,
            'backtop_bg_color'       => trim($this->backtop_bg_color),
            'backtop_hover_bg_color' => trim($this->backtop_hover_bg_color),
            'google_fonts_url'   => trim($this->google_fonts_url ?? ''),
            'google_analytics_id'=> trim($this->google_analytics_id ?? ''),
            'custom_js_loader'   => trim($this->custom_js_loader ?? ''),
            'timezone'           => $this->timezone,
            'enable_reviews'     => $this->enable_reviews ? '1' : '0',
            'third_party_reviews_js' => trim($this->third_party_reviews_js),
            'file_icon_pack'     => $this->file_icon_pack,
            'product_image_orientation' => $this->product_image_orientation,
            'disable_shop_landing'      => $this->disable_shop_landing ? '1' : '0',
            'enable_advanced_shop_search' => $this->enable_advanced_shop_search ? '1' : '0',
        ]);

        \App\Services\HeaderFooterCssManager::clearCompiledCssCache();

        // Explicitly clear the cache to ensure updates are visible on the next request
        Cache::forget('cms_settings_all');

        // Apply the new timezone immediately for this request
        if (in_array($this->timezone, \DateTimeZone::listIdentifiers(), true)) {
            config(['app.timezone' => $this->timezone]);
            date_default_timezone_set($this->timezone);
        }

        $this->dispatch('toast', message: 'Settings saved successfully.', type: 'success');
    }

    /**
     * Returns true when the database contains demo-seeded records.
     * The admin "Purge Demo Content" banner is only shown when this is true.
     */
    public function getHasDemoContentProperty(): bool
    {
        return DB::table('products')->where('is_demo', 1)->exists();
    }

    /**
     * Permanently delete all demo-seeded records in correct FK dependency order.
     * Child rows are removed before parent rows to avoid constraint violations.
     */
    public function purgeDemoContent(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Cross-selling records flagged as demo
        DB::table('product_cross_selling')->where('is_demo', 1)->delete();

        // 2. Images for demo variants
        DB::table('product_images')->where('is_demo', 1)->delete();

        // 3. Get all demo variant IDs for cascaded child deletes
        $demoVariantIds = DB::table('product_variants')
            ->where('is_demo', 1)
            ->pluck('id')
            ->toArray();

        if (!empty($demoVariantIds)) {
            // 4. Event rows attached to demo variants
            DB::table('product_variant_events')
                ->whereIn('variant_id', $demoVariantIds)
                ->delete();

            // 5. Inventory rows attached to demo variants
            DB::table('products_inventory')
                ->whereIn('variant_id', $demoVariantIds)
                ->delete();
        }

        // 6. Get all demo product IDs for field/category cascade
        $demoProductIds = DB::table('products')
            ->where('is_demo', 1)
            ->pluck('id')
            ->toArray();

        if (!empty($demoProductIds)) {
            // 7. Product field options (child of product_fields)
            $demoFieldIds = DB::table('product_fields')
                ->whereIn('product_id', $demoProductIds)
                ->pluck('id')
                ->toArray();

            if (!empty($demoFieldIds)) {
                DB::table('product_field_options')
                    ->whereIn('product_field_id', $demoFieldIds)
                    ->delete();
            }

            // 8. Product fields
            DB::table('product_fields')
                ->whereIn('product_id', $demoProductIds)
                ->delete();

            // 9. Category assignments
            DB::table('product_categories_assignments')
                ->whereIn('product_id', $demoProductIds)
                ->delete();
        }

        // 10. Demo variants
        DB::table('product_variants')->where('is_demo', 1)->delete();

        // 11. Demo products
        DB::table('products')->where('is_demo', 1)->delete();

        // 12. Demo brands
        DB::table('product_brands')->where('is_demo', 1)->delete();

        // 13. Demo categories (children first via sort_order desc, then parents)
        DB::table('product_categories')
            ->where('is_demo', 1)
            ->orderByDesc('parent_id') // children (non-null parent_id) first
            ->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->confirmingDemoPurge = false;
        $this->dispatch('toast', message: 'All demo store content has been permanently deleted.', type: 'success');
    }

    public function clearLogo(): void
    {
        CmsSetting::setMany([
            'logo_type' => '',
            'logo_path' => '',
            'logo_cdn_url' => '',
            'logo_svg_html' => '',
        ]);
        $this->logo_type = '';
        $this->logo_path = '';
        $this->logo_cdn_url = '';
        $this->logo_svg_html = '';
        Cache::forget('cms_settings_all');
        $this->dispatch('toast', message: 'Logo cleared — default icon will be shown.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin-settings', [
            'timezoneGroups' => TimezoneHelper::grouped(),
        ])->layout('layouts.app');
    }
}
