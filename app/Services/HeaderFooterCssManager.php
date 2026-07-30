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
            'header_min_height'                              => '201px',
            'header_padding_top'                             => '5px',
            'header_padding_bottom'                          => '5px',
            'nav_inside_main_header'                         => '0',
            'search_placement_desktop'                       => 'main_header',
            'search_placement_tablet'                        => 'main_header',
            'mobile_search_position'                         => 'top',
            'header_custom_css'                              => '',
            'footer_custom_css'                              => '',
            'backtop_bg_color'                               => '',
            'backtop_hover_bg_color'                         => '',
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
        Cache::forget('cms_settings_all');
        Cache::forget('header_footer_compiled_css');
    }

    /**
     * Compile CSS rules and :root variables.
     */
    public static function compileCss(?array $vars = null): string
    {
        $v = $vars ?? self::getActiveVariables();
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
}

/* Header Container Rules */
.header_container {
    position: relative !important;
}
.site_header_contents {
    padding-top: var(--header-padding-top, 5px) !important;
    padding-bottom: var(--header-padding-bottom, 5px) !important;
}
.header_row2 { display: none; }

@media only screen and (min-width: 1024px) {
    .header_container {
        min-height: var(--header-min-height, 201px);
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
    padding-left: 25px;
    padding-right: 25px;
    min-height: 40px;
}

@media only screen and (max-width: 600px) {
    .top_sharing_container { display: none; }
    .header_container { min-height: auto; }
    .header_col2 { display: none; }
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
    #top_nav_area {
        max-width: var(--site-max-width, 1280px);
        width: 100%;
    }
    header[id^='top-nav-'] {
        display: block !important;
        width: 100%;
    }
}
@media only screen and (max-width: 1023px) {
    .top_nav_container,
    #top_nav_area,
    #top_nav_contents,
    header[id^='top-nav-'] {
        display: none !important;
        height: 0 !important;
        min-height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
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
    padding-left: 25px;
    padding-right: 25px;
}

#site_header {
    max-width: var(--site-max-width);
    width: 100%;
}

.site_header_contents {
    padding: 25px 0;
    width: 100%;
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    justify-content: space-between;
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
    color: #fff;
    text-align: center;
    background-color: {$backtopBgCss};
    height: 48px;
    width: 48px;
    line-height: 48px;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 99999;
    border-radius: var(--top-nav-menu-borders-radius);
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
    background-color: {$backtopHoverBgCss};
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
