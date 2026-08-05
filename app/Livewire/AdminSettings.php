<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CmsSetting;
use App\Helpers\TimezoneHelper;
use App\Services\HeaderFooterCssManager;
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

    // Favicon Configuration
    public string $favicon_type = '';     // local|s3|custom_s3|cdn|url|svg
    public string $favicon_path = '';
    public string $favicon_cdn_url = '';
    public string $favicon_svg_html = '';
    public string $favicon_s3_bucket = '';
    public string $favicon_s3_key = '';
    public string $favicon_s3_secret = '';
    public string $favicon_s3_region = 'us-east-1';
    public $favicon_upload = null;        // Livewire temporary upload

    // Appearance
    public bool $frontend_dark_mode = false;
    public bool $admin_dark_mode = false;
    public string $theme_primary_color = '';
    public string $theme_hover_color = '';
    public string $theme_text_color = '';
    public string $theme_primary_border_color = '';
    public string $theme_primary_hover_text_color = '';
    public string $theme_border_radius = '0.75rem';
    public string $theme_secondary_bg_color = 'transparent';
    public string $theme_secondary_text_color = '#4f46e5';
    public string $theme_secondary_border_color = '#4f46e5';
    public string $theme_secondary_hover_bg_color = '#4f46e5';
    public string $theme_secondary_hover_text_color = '#ffffff';

    // Go to Top Button
    public string $backtop_bg_color = '';
    public string $backtop_hover_bg_color = '';
    public string $backtop_icon_color = '#ffffff';

    // Shop Catalog View Mode Colors
    public string $shop_view_mode_active_bg = '#4f46e5';
    public string $shop_view_mode_active_text = '#ffffff';
    public string $shop_view_mode_inactive_bg = '#f1f5f9';
    public string $shop_view_mode_inactive_text = '#64748b';

    // Shop Category Filter Pill Colors
    public string $shop_category_pill_bg = '#EEF2FF';
    public string $shop_category_pill_text = '#4338CA';
    public string $shop_category_pill_border = '#C7D2FE';
    public string $shop_category_pill_hover_bg = '#4338CA';
    public string $shop_category_pill_hover_text = '#FFFFFF';
    public string $shop_category_pill_hover_border = '#4338CA';

    // Shop Brand Filter Pill Colors
    public string $shop_brand_pill_bg = '#F5F3FF';
    public string $shop_brand_pill_text = '#6D28D9';
    public string $shop_brand_pill_border = '#DDD6FE';
    public string $shop_brand_pill_hover_bg = '#6D28D9';
    public string $shop_brand_pill_hover_text = '#FFFFFF';
    public string $shop_brand_pill_hover_border = '#6D28D9';

    // Shop Subcategory Filter Pill Colors
    public string $shop_subcat_pill_bg = '#F0FDF4';
    public string $shop_subcat_pill_text = '#15803D';
    public string $shop_subcat_pill_border = '#BBF7D0';
    public string $shop_subcat_pill_hover_bg = '#15803D';
    public string $shop_subcat_pill_hover_text = '#FFFFFF';
    public string $shop_subcat_pill_hover_border = '#15803D';

    // Sitewide Pagination Colors
    public string $pagination_active_bg = '#4f46e5';
    public string $pagination_active_text = '#ffffff';
    public string $pagination_inactive_bg = '#ffffff';
    public string $pagination_inactive_text = '#334155';
    public string $pagination_hover_bg = '#e0e7ff';

    // Site Theme Customization: Background Media
    public string $page_bg_mode = 'default'; // default | color | image | video
    public string $page_bg_color = '';

    // Page Background Image Storage
    public string $page_bg_image_type = 'local';
    public string $page_bg_image_path = '';
    public string $page_bg_image_url = '';
    public string $page_bg_image_s3_cdn_url = '';  // Optional CDN/CloudFront base URL for S3 image
    public string $page_bg_image_s3_bucket = '';
    public string $page_bg_image_s3_key = '';
    public string $page_bg_image_s3_secret = '';
    public string $page_bg_image_s3_region = 'us-east-1';
    public $page_bg_image_upload = null;

    // Page Background Video Storage
    public string $page_bg_video_type = 'local';
    public string $page_bg_video_path = '';
    public string $page_bg_video_url = '';
    public string $page_bg_video_s3_cdn_url = '';  // Optional CDN/CloudFront base URL for S3 video
    public string $page_bg_video_s3_bucket = '';
    public string $page_bg_video_s3_key = '';
    public string $page_bg_video_s3_secret = '';
    public string $page_bg_video_s3_region = 'us-east-1';
    public $page_bg_video_upload = null;

    // Header Navigation Settings
    public bool $top_nav_sticky = true;

    // Overlay Tint
    public string $page_bg_overlay_color = '#000000';
    public string $page_bg_overlay_opacity = '0';

    // Site Theme Customization: Typography
    public string $theme_body_font_family = '';
    public string $theme_body_font_size = '';
    public string $theme_body_font_color = '';
    public string $theme_paragraph_font_family = '';
    public string $theme_paragraph_font_size = '';
    public string $theme_paragraph_font_color = '';
    public string $theme_h1_font_family = '';
    public string $theme_h1_font_size = '';
    public string $theme_h1_font_color = '';
    public string $theme_h2_font_family = '';
    public string $theme_h2_font_size = '';
    public string $theme_h2_font_color = '';
    public string $theme_h3_font_family = '';
    public string $theme_h3_font_size = '';
    public string $theme_h3_font_color = '';
    public string $theme_link_color = '';
    public string $theme_link_hover_color = '';
    public string $theme_link_active_color = '';

    // Site Theme Customization: Content Cards
    public string $theme_content_bg_color = '';
    public string $theme_card_bg_color = '';
    public string $theme_card_border_color = '';
    public string $theme_card_shadow = '';

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
        $this->logo_s3_bucket= $settings['logo_s3_bucket'] ?? '';
        $this->logo_s3_key   = $settings['logo_s3_key'] ?? '';
        $this->logo_s3_secret= $settings['logo_s3_secret'] ?? '';
        $this->logo_s3_region= $settings['logo_s3_region'] ?? 'us-east-1';

        // Favicon Configuration
        $this->favicon_type     = $settings['favicon_type'] ?? '';
        $this->favicon_path     = $settings['favicon_path'] ?? '';
        $this->favicon_cdn_url  = $settings['favicon_cdn_url'] ?? '';
        $this->favicon_svg_html = $settings['favicon_svg_html'] ?? '';
        $this->favicon_s3_bucket= $settings['favicon_s3_bucket'] ?? '';
        $this->favicon_s3_key   = $settings['favicon_s3_key'] ?? '';
        $this->favicon_s3_secret= $settings['favicon_s3_secret'] ?? '';
        $this->favicon_s3_region= $settings['favicon_s3_region'] ?? 'us-east-1';

        // Appearance
        $this->frontend_dark_mode          = (bool) ($settings['frontend_dark_mode'] ?? false);
        $this->admin_dark_mode             = (bool) ($settings['admin_dark_mode'] ?? false);
        $this->theme_primary_color         = $settings['theme_primary_color'] ?? '';
        $this->theme_hover_color           = $settings['theme_hover_color'] ?? '';
        $this->theme_text_color            = $settings['theme_text_color'] ?? '';
        $this->theme_primary_border_color  = $settings['theme_primary_border_color'] ?? '';
        $this->theme_primary_hover_text_color = $settings['theme_primary_hover_text_color'] ?? '';
        $this->theme_border_radius         = $settings['theme_border_radius'] ?? '0.75rem';
        $this->theme_secondary_bg_color    = $settings['theme_secondary_bg_color'] ?? 'transparent';
        $this->theme_secondary_text_color  = $settings['theme_secondary_text_color'] ?? '#1e3a8a';
        $this->theme_secondary_border_color= $settings['theme_secondary_border_color'] ?? '#1e3a8a';
        $this->theme_secondary_hover_bg_color   = $settings['theme_secondary_hover_bg_color'] ?? '#1e3a8a';
        $this->theme_secondary_hover_text_color = $settings['theme_secondary_hover_text_color'] ?? '#ffffff';

        $this->backtop_bg_color            = $settings['backtop_bg_color'] ?? '';
        $this->backtop_hover_bg_color      = $settings['backtop_hover_bg_color'] ?? '';
        $this->backtop_icon_color          = $settings['backtop_icon_color'] ?? '#FFFFFF';

        $this->shop_view_mode_active_bg    = $settings['shop_view_mode_active_bg'] ?? '#e2e8f0';
        $this->shop_view_mode_active_text  = $settings['shop_view_mode_active_text'] ?? '#0f172a';
        $this->shop_view_mode_inactive_bg  = $settings['shop_view_mode_inactive_bg'] ?? '#f8fafc';
        $this->shop_view_mode_inactive_text= $settings['shop_view_mode_inactive_text'] ?? '#64748b';

        $this->shop_category_pill_bg          = $settings['shop_category_pill_bg'] ?? '#EEF2FF';
        $this->shop_category_pill_text        = $settings['shop_category_pill_text'] ?? '#4338CA';
        $this->shop_category_pill_border      = $settings['shop_category_pill_border'] ?? '#C7D2FE';
        $this->shop_category_pill_hover_bg    = $settings['shop_category_pill_hover_bg'] ?? '#4338CA';
        $this->shop_category_pill_hover_text  = $settings['shop_category_pill_hover_text'] ?? '#FFFFFF';
        $this->shop_category_pill_hover_border= $settings['shop_category_pill_hover_border'] ?? '#4338CA';

        $this->shop_brand_pill_bg             = $settings['shop_brand_pill_bg'] ?? '#F5F3FF';
        $this->shop_brand_pill_text           = $settings['shop_brand_pill_text'] ?? '#6D28D9';
        $this->shop_brand_pill_border         = $settings['shop_brand_pill_border'] ?? '#DDD6FE';
        $this->shop_brand_pill_hover_bg       = $settings['shop_brand_pill_hover_bg'] ?? '#6D28D9';
        $this->shop_brand_pill_hover_text     = $settings['shop_brand_pill_hover_text'] ?? '#FFFFFF';
        $this->shop_brand_pill_hover_border   = $settings['shop_brand_pill_hover_border'] ?? '#6D28D9';

        $this->shop_subcat_pill_bg            = $settings['shop_subcat_pill_bg'] ?? '#F0FDF4';
        $this->shop_subcat_pill_text          = $settings['shop_subcat_pill_text'] ?? '#15803D';
        $this->shop_subcat_pill_border        = $settings['shop_subcat_pill_border'] ?? '#BBF7D0';
        $this->shop_subcat_pill_hover_bg      = $settings['shop_subcat_pill_hover_bg'] ?? '#15803D';
        $this->shop_subcat_pill_hover_text    = $settings['shop_subcat_pill_hover_text'] ?? '#FFFFFF';
        $this->shop_subcat_pill_hover_border  = $settings['shop_subcat_pill_hover_border'] ?? '#15803D';

        $this->pagination_active_bg        = $settings['pagination_active_bg'] ?? '#4f46e5';
        $this->pagination_active_text      = $settings['pagination_active_text'] ?? '#ffffff';
        $this->pagination_inactive_bg      = $settings['pagination_inactive_bg'] ?? '#ffffff';
        $this->pagination_inactive_text    = $settings['pagination_inactive_text'] ?? '#334155';
        $this->pagination_hover_bg         = $settings['pagination_hover_bg'] ?? '#e0e7ff';

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

        // Site Theme Customization
        $this->page_bg_mode                = $settings['page_bg_mode'] ?? 'default';
        $this->page_bg_color               = $settings['page_bg_color'] ?? '';
        $this->page_bg_image_type          = $settings['page_bg_image_type'] ?? 'local';
        $this->page_bg_image_path          = $settings['page_bg_image_path'] ?? '';
        $this->page_bg_image_url           = $settings['page_bg_image_url'] ?? '';
        $this->page_bg_image_s3_cdn_url    = $settings['page_bg_image_s3_cdn_url'] ?? '';
        $this->page_bg_image_s3_bucket     = $settings['page_bg_image_s3_bucket'] ?? '';
        $this->page_bg_image_s3_key        = $settings['page_bg_image_s3_key'] ?? '';
        $this->page_bg_image_s3_secret     = $settings['page_bg_image_s3_secret'] ?? '';
        $this->page_bg_image_s3_region     = $settings['page_bg_image_s3_region'] ?? 'us-east-1';

        $this->page_bg_video_type          = $settings['page_bg_video_type'] ?? 'local';
        $this->page_bg_video_path          = $settings['page_bg_video_path'] ?? '';
        $this->page_bg_video_url           = $settings['page_bg_video_url'] ?? '';
        $this->page_bg_video_s3_cdn_url    = $settings['page_bg_video_s3_cdn_url'] ?? '';
        $this->page_bg_video_s3_bucket     = $settings['page_bg_video_s3_bucket'] ?? '';
        $this->page_bg_video_s3_key        = $settings['page_bg_video_s3_key'] ?? '';
        $this->page_bg_video_s3_secret     = $settings['page_bg_video_s3_secret'] ?? '';
        $this->page_bg_video_s3_region     = $settings['page_bg_video_s3_region'] ?? 'us-east-1';

        $this->page_bg_overlay_color       = $settings['page_bg_overlay_color'] ?? '#000000';
        $this->page_bg_overlay_opacity     = $settings['page_bg_overlay_opacity'] ?? '0';

        $this->theme_body_font_family      = $settings['theme_body_font_family'] ?? '';
        $this->theme_body_font_size        = $settings['theme_body_font_size'] ?? '';
        $this->theme_body_font_color       = $settings['theme_body_font_color'] ?? '';
        $this->theme_paragraph_font_family = $settings['theme_paragraph_font_family'] ?? '';
        $this->theme_paragraph_font_size   = $settings['theme_paragraph_font_size'] ?? '';
        $this->theme_paragraph_font_color = $settings['theme_paragraph_font_color'] ?? '';
        $this->theme_h1_font_family        = $settings['theme_h1_font_family'] ?? '';
        $this->theme_h1_font_size          = $settings['theme_h1_font_size'] ?? '';
        $this->theme_h1_font_color         = $settings['theme_h1_font_color'] ?? '';
        $this->theme_h2_font_family        = $settings['theme_h2_font_family'] ?? '';
        $this->theme_h2_font_size          = $settings['theme_h2_font_size'] ?? '';
        $this->theme_h2_font_color         = $settings['theme_h2_font_color'] ?? '';
        $this->theme_h3_font_family        = $settings['theme_h3_font_family'] ?? '';
        $this->theme_h3_font_size          = $settings['theme_h3_font_size'] ?? '';
        $this->theme_h3_font_color         = $settings['theme_h3_font_color'] ?? '';
        $this->theme_link_color            = $settings['theme_link_color'] ?? '';
        $this->theme_link_hover_color      = $settings['theme_link_hover_color'] ?? '';
        $this->theme_link_active_color     = $settings['theme_link_active_color'] ?? '';

        $this->theme_content_bg_color      = $settings['theme_content_bg_color'] ?? '';
        $this->theme_card_bg_color         = $settings['theme_card_bg_color'] ?? '';
        $this->theme_card_border_color     = $settings['theme_card_border_color'] ?? '';
        $this->theme_card_shadow           = $settings['theme_card_shadow'] ?? '';

        $this->top_nav_sticky              = ($settings['top_nav_sticky'] ?? '1') !== '0';
    }

    private function handleFileUpload($fileUpload, string $type, string $targetFolder, ?string $s3Bucket = null, ?string $s3Key = null, ?string $s3Secret = null, ?string $s3Region = null): ?string
    {
        if (!$fileUpload) return null;

        if ($type === 'local') {
            return $fileUpload->store($targetFolder, 'public');
        }

        if ($type === 's3') {
            try {
                return $fileUpload->store($targetFolder, 's3') ?: null;
            } catch (\Throwable $e) {
                \Log::error('S3 default upload error: ' . $e->getMessage());
                $this->dispatch('toast', message: 'S3 (.env) Upload error: ' . $e->getMessage(), type: 'error');
                return null;
            }
        }

        if ($type === 'custom_s3') {
            $bucket = trim($s3Bucket ?? '');
            $region = trim($s3Region ?? '') ?: 'us-east-1';
            $key    = trim($s3Key ?? '') ?: config('filesystems.disks.s3.key');
            $secret = trim($s3Secret ?? '') ?: config('filesystems.disks.s3.secret');

            if (empty($bucket)) {
                $this->dispatch('toast', message: 'Custom S3 Upload failed: Bucket Name is required.', type: 'error');
                return null;
            }

            if (empty($key) || empty($secret)) {
                $this->dispatch('toast', message: 'Custom S3 Upload failed: AWS Access Key ID and Secret Access Key are required.', type: 'error');
                return null;
            }

            $diskName = 'custom_s3_' . md5($bucket . $region . $key);
            \Illuminate\Support\Facades\Storage::forgetDisk($diskName);
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver'                  => 's3',
                    'key'                     => $key,
                    'secret'                  => $secret,
                    'region'                  => $region,
                    'bucket'                  => $bucket,
                    'use_path_style_endpoint' => false,
                ]
            ]);

            try {
                return $fileUpload->store($targetFolder, $diskName) ?: null;
            } catch (\Throwable $e) {
                \Log::error('Custom S3 upload error: ' . $e->getMessage());
                $this->dispatch('toast', message: 'Custom S3 Upload error: ' . $e->getMessage(), type: 'error');
                return null;
            }
        }

        return null;
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
            'favicon_type'        => 'nullable|string|in:local,s3,custom_s3,cdn,url,svg,',
            'favicon_path'        => 'nullable|string|max:500',
            'favicon_cdn_url'     => 'nullable|url|max:500',
            'favicon_svg_html'    => 'nullable|string',
            'favicon_s3_bucket'   => 'nullable|string|max:255',
            'favicon_s3_key'      => 'nullable|string|max:255',
            'favicon_s3_secret'   => 'nullable|string|max:255',
            'favicon_s3_region'   => 'nullable|string|max:100',
            'google_fonts_url'    => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:50',
            'custom_js_loader'    => 'nullable|string',
            'timezone'            => ['required', 'string', \Illuminate\Validation\Rule::in(TimezoneHelper::all())],
            'theme_primary_color'            => 'nullable|string|max:100',
            'theme_hover_color'              => 'nullable|string|max:100',
            'theme_text_color'               => 'nullable|string|max:100',
            'theme_primary_border_color'     => 'nullable|string|max:100',
            'theme_primary_hover_text_color' => 'nullable|string|max:100',
            'theme_link_color'               => 'nullable|string|max:100',
            'theme_link_hover_color'         => 'nullable|string|max:100',
            'theme_link_active_color'        => 'nullable|string|max:100',
            'theme_border_radius'            => 'required|string|max:50',
            'backtop_bg_color'       => 'nullable|string|max:100',
            'backtop_hover_bg_color' => 'nullable|string|max:100',
            'backtop_icon_color'     => 'nullable|string|max:100',
            'enable_reviews'      => 'boolean',
            'third_party_reviews_js' => 'nullable|string',
            'file_icon_pack'      => 'required|string|in:vivid,classic,square',
            'product_image_orientation' => 'required|string|in:16:9,1:1',
            'disable_shop_landing' => 'boolean',
            'enable_advanced_shop_search' => 'boolean',
        ]);

        // Handle logo file upload (local, s3, custom_s3)
        if ($this->logo_upload) {
            $path = $this->handleFileUpload($this->logo_upload, $this->logo_type, 'logos', $this->logo_s3_bucket, $this->logo_s3_key, $this->logo_s3_secret, $this->logo_s3_region);
            if ($path) $this->logo_path = $path;
        }

        // Handle favicon file upload (local, s3, custom_s3)
        if ($this->favicon_upload) {
            $path = $this->handleFileUpload($this->favicon_upload, $this->favicon_type, 'favicons', $this->favicon_s3_bucket, $this->favicon_s3_key, $this->favicon_s3_secret, $this->favicon_s3_region);
            if ($path) $this->favicon_path = $path;
        }

        // Handle page background image upload (local, s3, custom_s3)
        if ($this->page_bg_image_upload) {
            $path = $this->handleFileUpload($this->page_bg_image_upload, $this->page_bg_image_type, 'backgrounds', $this->page_bg_image_s3_bucket, $this->page_bg_image_s3_key, $this->page_bg_image_s3_secret, $this->page_bg_image_s3_region);
            if ($path) $this->page_bg_image_path = $path;
        }

        // Handle page background video upload (local, s3, custom_s3)
        if ($this->page_bg_video_upload) {
            $path = $this->handleFileUpload($this->page_bg_video_upload, $this->page_bg_video_type, 'backgrounds', $this->page_bg_video_s3_bucket, $this->page_bg_video_s3_key, $this->page_bg_video_s3_secret, $this->page_bg_video_s3_region);
            if ($path) $this->page_bg_video_path = $path;
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

            'favicon_type'       => $this->favicon_type,
            'favicon_path'       => trim($this->favicon_path),
            'favicon_cdn_url'    => trim($this->favicon_cdn_url),
            'favicon_svg_html'   => trim($this->favicon_svg_html),
            'favicon_s3_bucket'  => trim($this->favicon_s3_bucket),
            'favicon_s3_key'     => trim($this->favicon_s3_key),
            'favicon_s3_secret'  => trim($this->favicon_s3_secret),
            'favicon_s3_region'  => trim($this->favicon_s3_region) ?: 'us-east-1',

            // Site Theme Customization: Background Media
            'page_bg_mode'            => $this->page_bg_mode,
            'page_bg_color'           => trim($this->page_bg_color),
            'page_bg_image_type'          => $this->page_bg_image_type,
            'page_bg_image_path'          => trim($this->page_bg_image_path),
            'page_bg_image_url'           => trim($this->page_bg_image_url),
            'page_bg_image_s3_cdn_url'    => trim($this->page_bg_image_s3_cdn_url),
            'page_bg_image_s3_bucket'     => trim($this->page_bg_image_s3_bucket),
            'page_bg_image_s3_key'        => trim($this->page_bg_image_s3_key),
            'page_bg_image_s3_secret'     => trim($this->page_bg_image_s3_secret),
            'page_bg_image_s3_region'     => trim($this->page_bg_image_s3_region) ?: 'us-east-1',

            'page_bg_video_type'          => $this->page_bg_video_type,
            'page_bg_video_path'          => trim($this->page_bg_video_path),
            'page_bg_video_url'           => trim($this->page_bg_video_url),
            'page_bg_video_s3_cdn_url'    => trim($this->page_bg_video_s3_cdn_url),
            'page_bg_video_s3_bucket'     => trim($this->page_bg_video_s3_bucket),
            'page_bg_video_s3_key'        => trim($this->page_bg_video_s3_key),
            'page_bg_video_s3_secret'     => trim($this->page_bg_video_s3_secret),
            'page_bg_video_s3_region'     => trim($this->page_bg_video_s3_region) ?: 'us-east-1',

            'page_bg_overlay_color'   => trim($this->page_bg_overlay_color),
            'page_bg_overlay_opacity' => trim($this->page_bg_overlay_opacity),

            // Site Theme Customization: Typography
            'theme_body_font_family'      => trim($this->theme_body_font_family),
            'theme_body_font_size'        => trim($this->theme_body_font_size),
            'theme_body_font_color'       => trim($this->theme_body_font_color),
            'theme_paragraph_font_family' => trim($this->theme_paragraph_font_family),
            'theme_paragraph_font_size'   => trim($this->theme_paragraph_font_size),
            'theme_paragraph_font_color' => trim($this->theme_paragraph_font_color),
            'theme_h1_font_family'        => trim($this->theme_h1_font_family),
            'theme_h1_font_size'          => trim($this->theme_h1_font_size),
            'theme_h1_font_color'         => trim($this->theme_h1_font_color),
            'theme_h2_font_family'        => trim($this->theme_h2_font_family),
            'theme_h2_font_size'          => trim($this->theme_h2_font_size),
            'theme_h2_font_color'         => trim($this->theme_h2_font_color),
            'theme_h3_font_family'        => trim($this->theme_h3_font_family),
            'theme_h3_font_size'          => trim($this->theme_h3_font_size),
            'theme_h3_font_color'         => trim($this->theme_h3_font_color),
            'theme_link_color'            => trim($this->theme_link_color),
            'theme_link_hover_color'      => trim($this->theme_link_hover_color),
            'theme_link_active_color'     => trim($this->theme_link_active_color),

            // Site Theme Customization: Content Cards
            'theme_content_bg_color'      => trim($this->theme_content_bg_color),
            'theme_card_bg_color'         => trim($this->theme_card_bg_color),
            'theme_card_border_color'     => trim($this->theme_card_border_color),
            'theme_card_shadow'           => trim($this->theme_card_shadow),
            'top_nav_sticky'              => $this->top_nav_sticky ? '1' : '0',

            'frontend_dark_mode' => $this->frontend_dark_mode  ? '1' : '0',
            'admin_dark_mode'    => $this->admin_dark_mode      ? '1' : '0',
            'theme_primary_color'            => trim($this->theme_primary_color),
            'theme_hover_color'              => trim($this->theme_hover_color),
            'theme_text_color'               => trim($this->theme_text_color),
            'theme_primary_border_color'     => trim($this->theme_primary_border_color),
            'theme_primary_hover_text_color' => trim($this->theme_primary_hover_text_color),
            'theme_border_radius'            => $this->theme_border_radius,
            'theme_secondary_bg_color'         => $this->theme_secondary_bg_color,
            'theme_secondary_text_color'       => $this->theme_secondary_text_color,
            'theme_secondary_border_color'     => $this->theme_secondary_border_color,
            'theme_secondary_hover_bg_color'   => $this->theme_secondary_hover_bg_color,
            'theme_secondary_hover_text_color' => $this->theme_secondary_hover_text_color,
            'backtop_bg_color'       => trim($this->backtop_bg_color),
            'backtop_hover_bg_color' => trim($this->backtop_hover_bg_color),
            'backtop_icon_color'     => trim($this->backtop_icon_color),
            'shop_view_mode_active_bg'    => trim($this->shop_view_mode_active_bg),
            'shop_view_mode_active_text'  => trim($this->shop_view_mode_active_text),
            'shop_view_mode_inactive_bg'  => trim($this->shop_view_mode_inactive_bg),
            'shop_view_mode_inactive_text'=> trim($this->shop_view_mode_inactive_text),
            'shop_category_pill_bg'          => trim($this->shop_category_pill_bg),
            'shop_category_pill_text'        => trim($this->shop_category_pill_text),
            'shop_category_pill_border'      => trim($this->shop_category_pill_border),
            'shop_category_pill_hover_bg'    => trim($this->shop_category_pill_hover_bg),
            'shop_category_pill_hover_text'  => trim($this->shop_category_pill_hover_text),
            'shop_category_pill_hover_border'=> trim($this->shop_category_pill_hover_border),
            'shop_brand_pill_bg'             => trim($this->shop_brand_pill_bg),
            'shop_brand_pill_text'           => trim($this->shop_brand_pill_text),
            'shop_brand_pill_border'         => trim($this->shop_brand_pill_border),
            'shop_brand_pill_hover_bg'       => trim($this->shop_brand_pill_hover_bg),
            'shop_brand_pill_hover_text'     => trim($this->shop_brand_pill_hover_text),
            'shop_brand_pill_hover_border'   => trim($this->shop_brand_pill_hover_border),
            'shop_subcat_pill_bg'            => trim($this->shop_subcat_pill_bg),
            'shop_subcat_pill_text'          => trim($this->shop_subcat_pill_text),
            'shop_subcat_pill_border'        => trim($this->shop_subcat_pill_border),
            'shop_subcat_pill_hover_bg'      => trim($this->shop_subcat_pill_hover_bg),
            'shop_subcat_pill_hover_text'    => trim($this->shop_subcat_pill_hover_text),
            'shop_subcat_pill_hover_border'  => trim($this->shop_subcat_pill_hover_border),
            'pagination_active_bg'        => trim($this->pagination_active_bg),
            'pagination_active_text'      => trim($this->pagination_active_text),
            'pagination_inactive_bg'      => trim($this->pagination_inactive_bg),
            'pagination_inactive_text'    => trim($this->pagination_inactive_text),
            'pagination_hover_bg'         => trim($this->pagination_hover_bg),
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
        return DB::table('products')->where('is_demo', 1)->exists()
            || DB::table('kb_articles')->where('is_demo', 1)->exists();
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

        // 14. Demo KB Articles
        DB::table('kb_articles')->where('is_demo', 1)->delete();

        // 15. Demo KB Categories
        DB::table('kb_categories')->where('is_demo', 1)->delete();

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

    public function clearFavicon(): void
    {
        CmsSetting::setMany([
            'favicon_type' => '',
            'favicon_path' => '',
            'favicon_cdn_url' => '',
            'favicon_svg_html' => '',
            'favicon_s3_bucket' => '',
            'favicon_s3_key' => '',
            'favicon_s3_secret' => '',
            'favicon_s3_region' => 'us-east-1',
        ]);
        $this->favicon_type = '';
        $this->favicon_path = '';
        $this->favicon_cdn_url = '';
        $this->favicon_svg_html = '';
        $this->favicon_s3_bucket = '';
        $this->favicon_s3_key = '';
        $this->favicon_s3_secret = '';
        $this->favicon_s3_region = 'us-east-1';
        $this->favicon_upload = null;
        Cache::forget('cms_settings_all');
        $this->dispatch('toast', message: 'Favicon cleared — default favicon.ico will be used.', type: 'success');
    }

    public function clearBgImage(): void
    {
        $this->page_bg_image_type      = 'local';
        $this->page_bg_image_path      = '';
        $this->page_bg_image_url       = '';
        $this->page_bg_image_s3_bucket = '';
        $this->page_bg_image_s3_key    = '';
        $this->page_bg_image_s3_secret = '';
        $this->page_bg_image_s3_region = 'us-east-1';
        $this->page_bg_image_upload    = null;

        if ($this->page_bg_mode === 'image') {
            $this->page_bg_mode = 'default';
        }

        CmsSetting::setMany([
            'page_bg_mode'              => $this->page_bg_mode,
            'page_bg_image_type'        => 'local',
            'page_bg_image_path'        => '',
            'page_bg_image_url'         => '',
            'page_bg_image_s3_bucket'   => '',
            'page_bg_image_s3_key'      => '',
            'page_bg_image_s3_secret'   => '',
            'page_bg_image_s3_region'   => 'us-east-1',
        ]);

        HeaderFooterCssManager::clearCompiledCssCache();
        $this->dispatch('toast', message: 'Page background image settings cleared and reset to default.', type: 'success');
    }

    public function clearBgVideo(): void
    {
        $this->page_bg_video_type      = 'local';
        $this->page_bg_video_path      = '';
        $this->page_bg_video_url       = '';
        $this->page_bg_video_s3_bucket = '';
        $this->page_bg_video_s3_key    = '';
        $this->page_bg_video_s3_secret = '';
        $this->page_bg_video_s3_region = 'us-east-1';
        $this->page_bg_video_upload    = null;

        if ($this->page_bg_mode === 'video') {
            $this->page_bg_mode = 'default';
        }

        CmsSetting::setMany([
            'page_bg_mode'              => $this->page_bg_mode,
            'page_bg_video_type'        => 'local',
            'page_bg_video_path'        => '',
            'page_bg_video_url'         => '',
            'page_bg_video_s3_bucket'   => '',
            'page_bg_video_s3_key'      => '',
            'page_bg_video_s3_secret'   => '',
            'page_bg_video_s3_region'   => 'us-east-1',
        ]);

        HeaderFooterCssManager::clearCompiledCssCache();
        $this->dispatch('toast', message: 'Page background video settings cleared and reset to default.', type: 'success');
    }

    public function render()
    {
        return view('livewire.admin-settings', [
            'timezoneGroups' => TimezoneHelper::grouped(),
        ])->layout('layouts.app');
    }
}
