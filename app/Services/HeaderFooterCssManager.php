<?php

namespace App\Services;

use App\Models\CmsSetting;
use Illuminate\Support\Facades\Cache;

class HeaderFooterCssManager
{
    /**
     * List of all CSS Manager configurable root variables with default values.
     */
    public static function getDefaultVariables(): array
    {
        return [
            'primary_accent_color'                           => '#026C80',
            'secondary_accent_color'                         => '#76B5C5',
            'tertiary_accent_color'                          => '#669999',
            'header_background_color'                        => '#F3F3F3',
            'top_nav_container_background'                   => 'transparent',
            'top_nav_menu_background_color'                  => 'transparent',
            'top_nav_menu_desktop_font_color'                => '#000000',
            'top_nav_menu_desktop_font_weight'               => '500',
            'top_nav_menu_desktop_font_size'                 => '1em',
            'top_nav_menu_desktop_tab_hover_background'      => '#76B5C5',
            'top_nav_menu_desktop_tab_hover_label_color'      => '#000000',
            'top_nav_menu_desktop_tab_active_background'     => '#76B5C5',
            'top_nav_menu_desktop_tab_active_label_color'     => '#000000',
            'top_nav_menu_desktop_drop_down_background_color'=> '#FFFFFF',
            'top_nav_menu_desktop_drop_down_list_item_hover' => '#76B5C5',
            'top_nav_menu_desktop_drop_down_list_item_hover_label_color' => '#000000',
            'top_nav_menu_desktop_drop_down_list_item_label_color'       => '#000000',
            'top_nav_menu_mobile_font_color'                 => '#000000',
            'top_nav_menu_mobile_font_size'                  => '1.25rem',
            'top_nav_menu_mobile_font_weight'                => '600',
            'top_nav_menu_mobile_view_list_expand'          => '#76B5C5',
            'top_nav_menu_mobile_view_list_expanded_background' => '#FFFFFF',
            'top_nav_menu_drop_down_menus_font_size'         => '1em',
            'top_nav_sub_menu_drop_downs_font_size'          => '1em',
            'top_nav_menu_borders_radius'                    => '4px',
            'top_nav_menu_borders_color'                     => 'transparent',
            'footer_background_color'                        => '#363538',
            'footer_header_title_color'                      => '#eeeeee',
            'footer_link_color'                              => '#76B5C5',
            'footer_link_hover_color'                        => '#ffffff',
            'footer_text_color'                              => '#cccccc',
            'footer_font_size'                               => '0.9rem',
            'footer_heading_font_size'                       => '1.2rem',
            'header_bg_image_url'                            => '',
            'header_bg_repeat'                               => 'no-repeat',
            'header_bg_size'                                 => 'cover',
            'header_bg_position'                             => 'center center',
            'footer_bg_image_url'                            => '',
            'footer_bg_repeat'                               => 'no-repeat',
            'footer_bg_size'                                 => 'cover',
            'footer_bg_position'                             => 'center center',
            'site_max_width'                                 => '1400px',
            'header_min_height'                              => '75px',
            'header_padding_top'                             => '14px',
            'header_padding_bottom'                          => '14px',
            'nav_inside_main_header'                         => '0',
            'top_nav_sticky'                                 => '1',
            'search_placement_desktop'                       => 'main_header',
            'search_placement_tablet'                        => 'main_header',
            'mobile_search_position'                         => 'top',
            'backtop_bg_color'                               => '',
            'backtop_hover_bg_color'                         => '',
            'backtop_icon_color'                             => '#ffffff',
            'shop_view_mode_active_bg'                       => '#4f46e5',
            'shop_view_mode_active_text'                     => '#ffffff',
            'shop_view_mode_inactive_bg'                     => '#f1f5f9',
            'shop_view_mode_inactive_text'                   => '#64748b',
            'pagination_active_bg'                           => '#4f46e5',
            'pagination_active_text'                         => '#ffffff',
            'pagination_inactive_bg'                         => '#ffffff',
            'pagination_inactive_text'                       => '#334155',
            'pagination_hover_bg'                            => '#e0e7ff',
            'shop_category_pill_bg'                          => '#EEF2FF',
            'shop_category_pill_text'                        => '#4338CA',
            'shop_category_pill_border'                      => '#C7D2FE',
            'shop_category_pill_hover_bg'                    => '#4338CA',
            'shop_category_pill_hover_text'                  => '#FFFFFF',
            'shop_category_pill_hover_border'                => '#4338CA',
            'shop_brand_pill_bg'                             => '#F5F3FF',
            'shop_brand_pill_text'                           => '#6D28D9',
            'shop_brand_pill_border'                         => '#DDD6FE',
            'shop_brand_pill_hover_bg'                       => '#6D28D9',
            'shop_brand_pill_hover_text'                     => '#FFFFFF',
            'shop_brand_pill_hover_border'                   => '#6D28D9',
            'shop_subcat_pill_bg'                            => '#F0FDF4',
            'shop_subcat_pill_text'                          => '#15803D',
            'shop_subcat_pill_border'                        => '#BBF7D0',
            'shop_subcat_pill_hover_bg'                      => '#15803D',
            'shop_subcat_pill_hover_text'                    => '#FFFFFF',
            'shop_subcat_pill_hover_border'                  => '#15803D',
            'header_custom_css'                              => '',
            'footer_custom_css'                              => '',
        ];
    }

    /**
     * Get active variables from cms_settings with defaults.
     */
    public static function getActiveVariables(): array
    {
        $settings = CmsSetting::allCached();
        $defaults = self::getDefaultVariables();
        $active   = [];

        foreach ($defaults as $key => $defaultVal) {
            $val = $settings['css_var_' . $key] ?? $settings[$key] ?? null;
            $active[$key] = (!is_null($val) && $val !== '') ? $val : $defaultVal;
        }

        return $active;
    }

    /**
     * Save updated CSS variables to cms_settings and clear cache.
     */
    public static function saveVariables(array $vars): void
    {
        $payload = [];
        foreach ($vars as $key => $val) {
            $payload['css_var_' . $key] = $val;
        }
        CmsSetting::setMany($payload);
    }

    /**
     * Clear all cached CSS compiled rules and settings.
     */
    public static function clearCompiledCssCache(): void
    {
        Cache::forget('cms_header_footer_compiled_css');
        Cache::forget('cms_header_footer_variables');
        Cache::forget('cms_settings_all');
        Cache::forget('header_footer_compiled_css');
    }

    /**
     * Compile CSS rules and :root variables (alias for generateCompiledCss).
     */
    public static function compileCss(?array $vars = null): string
    {
        return self::generateCompiledCss();
    }

    /**
     * Generate minified CSS output for dynamic site components.
     */
    public static function generateCompiledCss(): string
    {
        $v = self::getActiveVariables();

        $headerBg = !empty($v['header_bg_image_url']) ? "url('" . addslashes($v['header_bg_image_url']) . "')" : "none";
        $footerBg = !empty($v['footer_bg_image_url']) ? "url('" . addslashes($v['footer_bg_image_url']) . "')" : "none";

        $customBacktopBg = $v['backtop_bg_color'] ?? CmsSetting::get('backtop_bg_color', '');
        $customBacktopHover = $v['backtop_hover_bg_color'] ?? CmsSetting::get('backtop_hover_bg_color', '');
        $backtopBgCss = (!empty($customBacktopBg) && preg_match('/^#[0-9a-fA-F]{6}$/', $customBacktopBg)) ? $customBacktopBg : 'var(--primary-accent-color)';
        $backtopHoverBgCss = (!empty($customBacktopHover) && preg_match('/^#[0-9a-fA-F]{6}$/', $customBacktopHover)) ? $customBacktopHover : 'var(--secondary-accent-color)';

        $css = "
:root {
  --primary-accent-color: {$v['primary_accent_color']};
  --secondary-accent-color: {$v['secondary_accent_color']};
  --tertiary-accent-color: {$v['tertiary_accent_color']};
  --header-background-color: {$v['header_background_color']};
  --top-nav-container-background: {$v['top_nav_container_background']};
  --top-nav-menu-background-color: {$v['top_nav_menu_background_color']};
  --top-nav-menu-desktop-font-color: {$v['top_nav_menu_desktop_font_color']};
  --top-nav-menu-desktop-font-weight: {$v['top_nav_menu_desktop_font_weight']};
  --top-nav-menu-desktop-font-size: {$v['top_nav_menu_desktop_font_size']};
  --top-nav-menu-desktop-tab-hover-background: {$v['top_nav_menu_desktop_tab_hover_background']};
  --top-nav-menu-desktop-tab-hover-label-color: {$v['top_nav_menu_desktop_tab_hover_label_color']};
  --top-nav-menu-desktop-tab-active-background: {$v['top_nav_menu_desktop_tab_active_background']};
  --top-nav-menu-desktop-tab-active-label-color: {$v['top_nav_menu_desktop_tab_active_label_color']};
  --top-nav-menu-desktop-drop-down-background-color: {$v['top_nav_menu_desktop_drop_down_background_color']};
  --top-nav-menu-desktop-drop-down-list-item-hover: {$v['top_nav_menu_desktop_drop_down_list_item_hover']};
  --top-nav-menu-desktop-drop-down-list-item-hover-label-color: {$v['top_nav_menu_desktop_drop_down_list_item_hover_label_color']};
  --top-nav-menu-desktop-drop-down-list-item-label-color: {$v['top_nav_menu_desktop_drop_down_list_item_label_color']};
  --top-nav-menu-mobile-font-color: {$v['top_nav_menu_mobile_font_color']};
  --top-nav-menu-mobile-font-size: {$v['top_nav_menu_mobile_font_size']};
  --top-nav-menu-mobile-font-weight: {$v['top_nav_menu_mobile_font_weight']};
  --top-nav-menu-mobile-view-list-expand: {$v['top_nav_menu_mobile_view_list_expand']};
  --top-nav-menu-mobile-view-list-expanded-background: {$v['top_nav_menu_mobile_view_list_expanded_background']};
  --top-nav-menu-drop-down-menus-font-size: {$v['top_nav_menu_drop_down_menus_font_size']};
  --top-nav-sub-menu-drop-downs-font-size: {$v['top_nav_sub_menu_drop_downs_font_size']};
  --top-nav-menu-borders-radius: {$v['top_nav_menu_borders_radius']};
  --top-nav-menu-borders-color: {$v['top_nav_menu_borders_color']};
  --footer-background-color: {$v['footer_background_color']};
  --footer-header-title-color: {$v['footer_header_title_color']};
  --footer-link-color: {$v['footer_link_color']};
  --footer-link-hover-color: {$v['footer_link_hover_color']};
  --footer-text-color: {$v['footer_text_color']};
  --footer-font-size: {$v['footer_font_size']};
  --footer-heading-font-size: {$v['footer_heading_font_size']};
  --header-bg-image: {$headerBg};
  --footer-bg-image: {$footerBg};
  --site-max-width: {$v['site_max_width']};
  --header-min-height: {$v['header_min_height']};
  --header-padding-top: {$v['header_padding_top']};
  --header-padding-bottom: {$v['header_padding_bottom']};
  --shop-view-active-bg: {$v['shop_view_mode_active_bg']};
  --shop-view-active-text: {$v['shop_view_mode_active_text']};
  --shop-view-inactive-bg: {$v['shop_view_mode_inactive_bg']};
  --shop-view-inactive-text: {$v['shop_view_mode_inactive_text']};
  --pagination-active-bg: {$v['pagination_active_bg']};
  --pagination-active-text: {$v['pagination_active_text']};
  --pagination-inactive-bg: {$v['pagination_inactive_bg']};
  --pagination-inactive-text: {$v['pagination_inactive_text']};
  --pagination-hover-bg: {$v['pagination_hover_bg']};
  --shop-category-pill-bg: {$v['shop_category_pill_bg']};
  --shop-category-pill-text: {$v['shop_category_pill_text']};
  --shop-category-pill-border: {$v['shop_category_pill_border']};
  --shop-category-pill-hover-bg: {$v['shop_category_pill_hover_bg']};
  --shop-category-pill-hover-text: {$v['shop_category_pill_hover_text']};
  --shop-category-pill-hover-border: {$v['shop_category_pill_hover_border']};
  --shop-brand-pill-bg: {$v['shop_brand_pill_bg']};
  --shop-brand-pill-text: {$v['shop_brand_pill_text']};
  --shop-brand-pill-border: {$v['shop_brand_pill_border']};
  --shop-brand-pill-hover-bg: {$v['shop_brand_pill_hover_bg']};
  --shop-brand-pill-hover-text: {$v['shop_brand_pill_hover_text']};
  --shop-brand-pill-hover-border: {$v['shop_brand_pill_hover_border']};
  --shop-subcat-pill-bg: {$v['shop_subcat_pill_bg']};
  --shop-subcat-pill-text: {$v['shop_subcat_pill_text']};
  --shop-subcat-pill-border: {$v['shop_subcat_pill_border']};
  --shop-subcat-pill-hover-bg: {$v['shop_subcat_pill_hover_bg']};
  --shop-subcat-pill-hover-text: {$v['shop_subcat_pill_hover_text']};
  --shop-subcat-pill-hover-border: {$v['shop_subcat_pill_hover_border']};
  --backtop-bg-color: {$backtopBgCss};
  --backtop-hover-bg-color: {$backtopHoverBgCss};
  --backtop-icon-color: {$v['backtop_icon_color']};
}

/* Shop Catalog View Mode Buttons */
.btn-view-mode {
    background-color: var(--shop-view-inactive-bg) !important;
    color: var(--shop-view-inactive-text) !important;
    border: 1px solid var(--shop-view-inactive-bg) !important;
    transition: all 0.2s ease !important;
}
.btn-view-mode.active,
.btn-view-mode:hover {
    background-color: var(--shop-view-active-bg) !important;
    color: var(--shop-view-active-text) !important;
    border-color: var(--shop-view-active-bg) !important;
}

/* Shop Category, Brand & Subcategory Filter Pills */
.shop-category-pill {
    background-color: var(--shop-category-pill-bg) !important;
    color: var(--shop-category-pill-text) !important;
    border: 1px solid var(--shop-category-pill-border) !important;
    transition: all 0.2s ease !important;
}
.shop-category-pill:hover {
    background-color: var(--shop-category-pill-hover-bg) !important;
    color: var(--shop-category-pill-hover-text) !important;
    border-color: var(--shop-category-pill-hover-border) !important;
}

.shop-brand-pill {
    background-color: var(--shop-brand-pill-bg) !important;
    color: var(--shop-brand-pill-text) !important;
    border: 1px solid var(--shop-brand-pill-border) !important;
    transition: all 0.2s ease !important;
}
.shop-brand-pill:hover {
    background-color: var(--shop-brand-pill-hover-bg) !important;
    color: var(--shop-brand-pill-hover-text) !important;
    border-color: var(--shop-brand-pill-hover-border) !important;
}

.shop-subcat-pill {
    background-color: var(--shop-subcat-pill-bg) !important;
    color: var(--shop-subcat-pill-text) !important;
    border: 1px solid var(--shop-subcat-pill-border) !important;
    transition: all 0.2s ease !important;
}
.shop-subcat-pill:hover {
    background-color: var(--shop-subcat-pill-hover-bg) !important;
    color: var(--shop-subcat-pill-hover-text) !important;
    border-color: var(--shop-subcat-pill-hover-border) !important;
}

/* Pagination Box Numbers & Navigation */
nav[role='navigation'] [aria-current='page'] > span,
nav[role='navigation'] span[aria-current='page'],
.pagination .active,
.page-item.active .page-link {
    background-color: var(--pagination-active-bg) !important;
    color: var(--pagination-active-text) !important;
    border-color: var(--pagination-active-bg) !important;
}
nav[role='navigation'] a:hover,
nav[role='navigation'] button:not(.btn-view-mode):not(#header_mobile_toggle):not(.header_mobile_toggle):hover {
    background-color: var(--pagination-hover-bg) !important;
    color: var(--pagination-active-bg) !important;
}

/* Header & Site Container Rules */
.header_container {
    position: relative !important;
}
.site_header_container,
.site_header_contents,
.footer_contents,
.site_container {
    max-width: var(--site-max-width, 1400px);
    margin-left: auto;
    margin-right: auto;
}
.site_header_contents {
    padding-top: var(--header-padding-top);
    padding-bottom: var(--header-padding-bottom);
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: space-between !important;
}
.header_logo {
    display: flex !important;
    align-items: center !important;
    margin-right: auto !important;
    flex-shrink: 0 !important;
}
#header_features_bar {
    display: flex !important;
    align-items: center !important;
    margin-left: auto !important;
    flex-shrink: 0 !important;
}
.header_row2 { display: none; }

@media only screen and (min-width: 1024px) {
    .header_container {
        min-height: var(--header-min-height);
    }
}

@media only screen and (max-width: 1023px) {
    .header_container {
        min-height: auto !important;
        height: auto !important;
    }
}

.top_sharing_container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: var(--primary-accent-color);
    position: relative;
    padding-left: 10px;
    padding-right: 10px;
    min-height: 40px;
}

@media only screen and (max-width: 767px) {
    .header_container { min-height: auto; }
}

#top_sharing_section {
    max-width: var(--site-max-width);
    width: 100%;
}

.top_sharing_contents {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    min-height: 2em;
}

.top_sharing_contents li {
    padding: 0;
    margin: 0;
    list-style-type: none;
}

.sharing_col1 {
    height: 100%;
    position: relative;
    min-width: 300px;
    flex-grow: 1;
    font-weight: 600;
    color: white;
}
.sharing_col2 {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    min-width: 300px;
    flex-grow: 1;
}
.sharing_col3 {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    position: relative;
    height: 100%;
}
.top_sharing_container a,
.top_sharing_contents a,
.top_nav_container .social-icon-link,
.top_sharing_container .social-icon-link,
.site_header_container .social-icon-link,
.social-icon-link {
    color: var(--top-nav-menu-desktop-font-color, inherit);
    transition: color 0.2s ease;
}
.top_sharing_container a:hover,
.top_sharing_contents a:hover,
.top_nav_container .social-icon-link:hover,
.top_sharing_container .social-icon-link:hover,
.site_header_container .social-icon-link:hover,
.social-icon-link:hover {
    color: var(--top-nav-menu-desktop-tab-hover-label-color, var(--top-nav-menu-desktop-font-color, inherit));
}

.header_logo {
    display: flex !important;
    align-items: center !important;
    padding: 0 !important;
    margin: 0 !important;
}
.site-logo-title {
    font-size: 1.125rem !important;
    font-weight: 800 !important;
}

@media only screen and (min-width: 1024px) {
    .header_mobile_toggle { display: none !important; }
    .top_nav_container {
        width: 100%;
        display: flex !important;
        justify-content: center;
        align-items: center;
        background-color: var(--top-nav-container-background, transparent);
        position: relative;
    }
    .dyn-nav-link,
    .top_nav_container a,
    .top_nav_container button {
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        line-height: 1.25rem !important;
        color: var(--nav-text, #334155) !important;
    }
    .dyn-nav-link:hover,
    .top_nav_container a:hover,
    .top_nav_container button:hover {
        color: var(--nav-text-hover, #4f46e5) !important;
    }
    .site_header_contents {
        display: flex !important;
        align-items: center !important;
    }
   .top_nav_container,
    .header_top_bar .top_nav_container {
        width: auto !important;
        flex: 1 1 auto !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        margin-left: auto !important;
        margin-right: auto !important;
        text-align: center !important;
    }
    .site_header_contents .top_nav_container ul {
        justify-content: center !important;
        align-items: center !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    #top_nav_area {
        max-width: var(--site-max-width, 1280px);
        width: 100%;
    }
    header[id^='top-nav-'] {
        display: block !important;
        width: 100%;
    }
    .site_header_contents .top_nav_container header[id^='top-nav-'] {
        width: auto !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    .site_header_contents .top_nav_container header[id^='top-nav-'] > div {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        max-width: none !important;
    }
}
@media only screen and (max-width: 1023px) {
    .top_nav_container,
    #top_nav_area,
    #top_nav_contents,
    #top_nav_row,
    #top_nav_row_fallback,
    header[id^='top-nav-'] {
        display: none !important;
        height: 0 !important;
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
    .site_header_contents {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
    }
    .header_logo {
        margin-right: auto !important;
        margin-left: 0 !important;
    }
    #header_features_bar {
        margin-left: auto !important;
        margin-right: 0 !important;
    }
    .header_mobile_toggle,
    #header_mobile_toggle {
        display: inline-flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        margin-left: auto !important;
    }
}

#top_nav_area {
    max-width: var(--site-max-width);
    width: auto;
}

.site_header_container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    background-color: var(--header-background-color);
    background-image: var(--header-bg-image);
    background-repeat: {$v['header_bg_repeat']};
    background-size: {$v['header_bg_size']};
    background-position: {$v['header_bg_position']};
    padding-left: 10px;
    padding-right: 10px;
}

#site_header {
    max-width: var(--site-max-width);
    width: 100%;
}

.site_header_contents {
    padding-top: var(--header-padding-top, 14px) !important;
    padding-bottom: var(--header-padding-bottom, 14px) !important;
    width: 100%;
    max-width: var(--site-max-width);
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: space-between !important;
    margin: 0;
    gap: 10px;
}

.site_header_contents li {
    padding: 0;
    margin: 0;
    list-style-type: none;
}

.header_elements { flex-grow: 1; }
.embedded_top_navigation { background: none !important; }

.header_row1 {
    min-height: 25px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: var(--secondary-accent-color);
}
.header_logo {
    position: relative;
    width: auto !important;
    max-width: max-content !important;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    margin-top: auto;
    margin-bottom: auto;
}

.header-search-form {
    min-width: 120px;
    flex-shrink: 1;
    max-width: 448px;
    width: 100%;
}
.online-store-quick-search-field {
    min-width: 100px;
    width: 100%;
}
.header_col1 { align-items: center; position: relative; min-width: 0; flex-shrink: 1; z-index: 1; }
.header_col2 {
    align-items: center;
    position: relative;
    width: 100%;
    min-width: 0;
    flex-shrink: 1;
    vertical-align: middle;
    z-index: 1;
}

.header_row2 {
    background: var(--tertiary-accent-color);
    display: none;
}

@media only screen and (max-width: 600px) {
    .header_logo {
        width: auto !important;
        max-width: max-content !important;
        flex-shrink: 0 !important;
        display: flex !important;
        align-items: center !important;
        margin-top: auto !important;
        margin-bottom: auto !important;
        margin-right: auto !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
    #header_features_bar {
        margin-left: auto !important;
        flex-shrink: 0 !important;
    }
    .header_mobile_toggle {
        margin-left: 4px !important;
        flex-shrink: 0 !important;
    }
    .header_row2 {
        display: block;
        width: 100%;
        background: var(--tertiary-accent-color);
        padding: 1px 5px;
        border-top: 1px solid var(--top-nav-menu-borders-color);
    }
    .site_header_contents {
        justify-content: space-between !important;
        align-items: center !important;
        flex-wrap: nowrap !important;
        padding-top: 10px !important;
        padding-bottom: 10px !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    #header_features {
        height: 45px;
        max-height: 45px;
        padding: 0;
        margin-bottom: 10px;
    }
}

.header_container ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.nav-dropdown {
    max-height: 22rem;
    overflow-y: auto;
    overscroll-behavior: contain;
}
#header_features { flex-grow: 0; }
.header-pre-load { display: none; }

/* Footer Rules */
.footer_container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    background-color: var(--footer-background-color);
    background-image: var(--footer-bg-image);
    background-repeat: {$v['footer_bg_repeat']};
    background-size: {$v['footer_bg_size']};
    background-position: {$v['footer_bg_position']};
    color: var(--footer-text-color);
    font-size: var(--footer-font-size);
}

.footer_container ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.footer_contents {
    max-width: var(--site-max-width);
    width: 100%;
    display: flex;
    margin: 15px auto 10px auto;
    min-height: 300px;
    padding: 0 15px;
}

.footer_contents a:link,
.footer_contents a:visited,
.footer_container a,
.footer_container .social-icon-link {
    color: var(--footer-link-color) !important;
    text-decoration: none;
    transition: color 0.2s ease;
}
.footer_contents a:hover,
.footer_container a:hover,
.footer_container .social-icon-link:hover {
    color: var(--footer-link-hover-color) !important;
    text-decoration: none;
}

.footer_rows { padding: 0; margin: 0; width: 100%; }
.footer_row1 {
    display: flex;
    justify-content: center;
    background-color: var(--primary-accent-color);
    min-height: 2em;
}
.footer_row2 {
    display: flex;
    justify-content: center;
    background-color: var(--secondary-accent-color);
    min-height: 1em;
}
.footer_row3 {
    display: flex;
    justify-content: center;
    background-color: var(--tertiary-accent-color);
    min-height: 2em;
}
.footer_row4 {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
    background-color: rgba(0,0,0,0.6);
    font-size: var(--footer-font-size);
    color: var(--footer-text-color);
    align-items: center;
    padding: 10px 25px;
}
.footer_row4 img { opacity: 0.7 !important; }
.footer_row4 img:hover { opacity: 1.0 !important; }

.site_footer_columns_primary {
    width: 100%;
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 1.5rem;
}
.site_footer_columns_primary > li {
    width: calc(20% - 1.2rem);
    flex: 1 1 calc(20% - 1.2rem);
    min-width: 0;
}
@media only screen and (max-width: 1150px) {
    .site_footer_columns_primary > li {
        width: calc(33.333% - 1rem);
        flex: 1 1 calc(33.333% - 1rem);
    }
}
@media only screen and (max-width: 900px) {
    .site_footer_columns_primary > li {
        width: calc(50% - 1rem);
        flex: 1 1 calc(50% - 1rem);
    }
}
@media only screen and (max-width: 600px) {
    .site_footer_columns_primary > li {
        width: 100%;
        flex: 1 1 100%;
    }
}

.footer_columns h3,
.footer_container h3,
.footer_container h4 {
    margin: 15px 0 10px 0 !important;
    color: var(--footer-header-title-color) !important;
    letter-spacing: 1px;
    font-weight: 600 !important;
    font-size: var(--footer-heading-font-size) !important;
}

.footer_col1, .footer_col2, .footer_col3, .footer_col4, .footer_col5 {
    padding: 0 15px;
    border-right: 1px dotted var(--tertiary-accent-color);
}
.footer_col5 { border-right: none; }

@media only screen and (max-width: 1150px) {
    .footer_col1, .footer_col2, .footer_col3, .footer_col4, .footer_col5 {
        border-right: none;
    }
}

#backtop {
    position: fixed;
    right: 20px;
    bottom: 20px;
    color: var(--backtop-icon-color, #ffffff) !important;
    text-align: center;
    background-color: var(--backtop-bg-color, {$backtopBgCss}) !important;
    height: 48px;
    width: 48px;
    line-height: 48px;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 99999;
    border-radius: var(--top-nav-menu-borders-radius, 8px);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}
#backtop.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}
#backtop:hover {
    background-color: var(--backtop-hover-bg-color, {$backtopHoverBgCss}) !important;
    transform: translateY(-2px);
}
" . ($v['header_custom_css'] ?? '') . "\n" . ($v['footer_custom_css'] ?? '');

        return \App\Services\CssMinifierService::minify($css);
    }

    /**
     * Clear cached CSS theme variables.
     */
    public static function clearCache(): void
    {
        Cache::forget('cms_header_footer_variables');
        Cache::forget('cms_header_footer_compiled_css');
    }
}
