@php
    $primaryColor = \App\Models\CmsSetting::get('theme_primary_color', '#4f46e5');
    $hoverColor   = \App\Models\CmsSetting::get('theme_hover_color', '#4338ca');
    $textColor    = \App\Models\CmsSetting::get('theme_text_color', '#ffffff');
    $borderRadius = \App\Models\CmsSetting::get('theme_border_radius', '0.75rem');

    $secBg        = \App\Models\CmsSetting::get('theme_secondary_bg_color', 'transparent');
    $secText      = \App\Models\CmsSetting::get('theme_secondary_text_color', $primaryColor);
    $secBorder    = \App\Models\CmsSetting::get('theme_secondary_border_color', $primaryColor);
    $secHoverBg   = \App\Models\CmsSetting::get('theme_secondary_hover_bg_color', $primaryColor);
    $secHoverText = \App\Models\CmsSetting::get('theme_secondary_hover_text_color', '#ffffff');

    $rawStyles = ":root {
        --theme-primary: {$primaryColor};
        --theme-primary-hover: {$hoverColor};
        --theme-text: {$textColor};
        --theme-border-radius: {$borderRadius};
        --theme-secondary-bg: {$secBg};
        --theme-secondary-text: {$secText};
        --theme-secondary-border: {$secBorder};
        --theme-secondary-hover-bg: {$secHoverBg};
        --theme-secondary-hover-text: {$secHoverText};
    }
    .bg-indigo-600, .bg-purple-600, .bg-violet-600,
    .bg-gradient-to-r.from-indigo-600.to-violet-600 {
        background-image: none !important;
        background-color: var(--theme-primary) !important;
        color: var(--theme-text) !important;
    }
    .hover\:bg-indigo-500:hover, .hover\:bg-indigo-700:hover, .hover\:bg-purple-500:hover, .hover\:bg-purple-700:hover, .hover\:bg-violet-700:hover,
    .bg-gradient-to-r.from-indigo-600.to-violet-600:hover {
        background-image: none !important;
        background-color: var(--theme-primary-hover) !important;
    }
    .text-indigo-600, .text-purple-600, .text-violet-600 {
        color: var(--theme-primary) !important;
    }
    .hover\:text-indigo-700:hover, .hover\:text-purple-700:hover, .hover\:text-violet-700:hover {
        color: var(--theme-primary-hover) !important;
    }
    .border-indigo-500, .border-purple-500, .border-violet-500, .border-indigo-600, .border-purple-600, .border-violet-600 {
        border-color: var(--theme-primary) !important;
    }
    .hover\:border-indigo-300:hover, .hover\:border-purple-300:hover, .hover\:border-violet-300:hover {
        border-color: var(--theme-primary-hover) !important;
    }
    .focus\:ring-indigo-500:focus, .focus\:ring-purple-500:focus, .focus\:ring-violet-500:focus {
        --tw-ring-color: var(--theme-primary) !important;
        outline-color: var(--theme-primary) !important;
    }
    button,
    .btn,
    .btn-primary,
    a.bg-indigo-600,
    a.bg-purple-600,
    a.bg-violet-600,
    a.bg-indigo-50,
    a.bg-purple-50,
    a.bg-violet-50,
    input[type=\"submit\"],
    input[type=\"button\"] {
        border-radius: var(--theme-border-radius) !important;
    }
    .btn-theme-primary {
        background-color: var(--theme-primary) !important;
        color: var(--theme-text) !important;
        border-radius: var(--theme-border-radius) !important;
        border: none !important;
        padding: 10px 20px !important;
        font-weight: 700 !important;
        font-family: inherit !important;
        cursor: pointer !important;
        display: inline-block !important;
        text-align: center !important;
        text-decoration: none !important;
        transition: background-color 0.2s !important;
    }
    .btn-theme-primary:hover {
        background-color: var(--theme-primary-hover) !important;
    }
    .btn-secondary, .btn-theme-secondary {
        background-color: var(--theme-secondary-bg) !important;
        color: var(--theme-secondary-text) !important;
        border: 1px solid var(--theme-secondary-border) !important;
        border-radius: var(--theme-border-radius) !important;
        padding: 6px 14px !important;
        font-weight: 700 !important;
        font-family: inherit !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        transition: all 0.2s ease-in-out !important;
    }
    .btn-secondary:hover, .btn-theme-secondary:hover,
    .btn-secondary.active, .btn-theme-secondary.active {
        background-color: var(--theme-secondary-hover-bg) !important;
        color: var(--theme-secondary-hover-text) !important;
    }";
@endphp
<style id="site-theme-customizer-styles">
{!! \App\Services\CssMinifierService::minify($rawStyles) !!}
</style>
