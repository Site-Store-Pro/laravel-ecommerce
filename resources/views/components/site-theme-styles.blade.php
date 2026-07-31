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

    $bodyFontFamily = \App\Models\CmsSetting::get('theme_body_font_family');
    $bodyFontSize   = \App\Models\CmsSetting::get('theme_body_font_size');
    $bodyFontColor  = \App\Models\CmsSetting::get('theme_body_font_color');

    $pFontFamily    = \App\Models\CmsSetting::get('theme_paragraph_font_family');
    $pFontSize      = \App\Models\CmsSetting::get('theme_paragraph_font_size');
    $pFontColor     = \App\Models\CmsSetting::get('theme_paragraph_font_color');

    $h1FontFamily   = \App\Models\CmsSetting::get('theme_h1_font_family');
    $h1FontSize     = \App\Models\CmsSetting::get('theme_h1_font_size');
    $h1FontColor    = \App\Models\CmsSetting::get('theme_h1_font_color');

    $h2FontFamily   = \App\Models\CmsSetting::get('theme_h2_font_family');
    $h2FontSize     = \App\Models\CmsSetting::get('theme_h2_font_size');
    $h2FontColor    = \App\Models\CmsSetting::get('theme_h2_font_color');

    $h3FontFamily   = \App\Models\CmsSetting::get('theme_h3_font_family');
    $h3FontSize     = \App\Models\CmsSetting::get('theme_h3_font_size');
    $h3FontColor    = \App\Models\CmsSetting::get('theme_h3_font_color');

    $contentBgColor = \App\Models\CmsSetting::get('theme_content_bg_color');
    $cardBgColor    = \App\Models\CmsSetting::get('theme_card_bg_color');
    $cardBorderColor= \App\Models\CmsSetting::get('theme_card_border_color');
    $cardShadow     = \App\Models\CmsSetting::get('theme_card_shadow');

    $pageBgMode     = \App\Models\CmsSetting::get('page_bg_mode', 'default');
    $pageBgColor    = \App\Models\CmsSetting::get('page_bg_color');
    $pageBgImageUrl = \App\Models\CmsSetting::resolvePageBgImageUrl();

    if ($bodyFontFamily) {
        $rawStyles .= "\nbody, html, input, button, select, textarea { font-family: {$bodyFontFamily} !important; }";
    }
    if ($bodyFontSize) {
        $rawStyles .= "\nbody { font-size: {$bodyFontSize} !important; }";
    }
    if ($bodyFontColor) {
        $rawStyles .= "\nbody { color: {$bodyFontColor} !important; }";
    }
    if ($pFontFamily) {
        $rawStyles .= "\np { font-family: {$pFontFamily} !important; }";
    }
    if ($pFontSize) {
        $rawStyles .= "\np { font-size: {$pFontSize} !important; }";
    }
    if ($pFontColor) {
        $rawStyles .= "\np { color: {$pFontColor} !important; }";
    }
    if ($h1FontFamily) {
        $rawStyles .= "\nh1 { font-family: {$h1FontFamily} !important; }";
    }
    if ($h1FontSize) {
        $rawStyles .= "\nh1 { font-size: {$h1FontSize} !important; }";
    }
    if ($h1FontColor) {
        $rawStyles .= "\nh1 { color: {$h1FontColor} !important; }";
    }
    if ($h2FontFamily) {
        $rawStyles .= "\nh2 { font-family: {$h2FontFamily} !important; }";
    }
    if ($h2FontSize) {
        $rawStyles .= "\nh2 { font-size: {$h2FontSize} !important; }";
    }
    if ($h2FontColor) {
        $rawStyles .= "\nh2 { color: {$h2FontColor} !important; }";
    }
    if ($h3FontFamily) {
        $rawStyles .= "\nh3 { font-family: {$h3FontFamily} !important; }";
    }
    if ($h3FontSize) {
        $rawStyles .= "\nh3 { font-size: {$h3FontSize} !important; }";
    }
    if ($h3FontColor) {
        $rawStyles .= "\nh3 { color: {$h3FontColor} !important; }";
    }
    if ($contentBgColor) {
        $rawStyles .= "\nmain, .site-content-area, .public-content { background-color: {$contentBgColor} !important; }";
    }
    if ($cardBgColor) {
        $rawStyles .= "\n.card, .bg-white { background-color: {$cardBgColor} !important; }";
    }
    if ($cardBorderColor) {
        $rawStyles .= "\n.card, .border-slate-200, .border-slate-100 { border-color: {$cardBorderColor} !important; }";
    }
    if ($cardShadow && $cardShadow !== 'default') {
        $shadowMap = [
            'none' => 'none !important',
            'sm'   => '0 1px 2px 0 rgb(0 0 0 / 0.05) !important',
            'md'   => '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important',
            'lg'   => '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important',
            'xl'   => '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important',
            '2xl'  => '0 25px 50px -12px rgb(0 0 0 / 0.25) !important',
        ];
        if (isset($shadowMap[$cardShadow])) {
            $rawStyles .= "\n.card, .rounded-2xl, .rounded-3xl { box-shadow: {$shadowMap[$cardShadow]}; }";
        }
    }
    if ($pageBgMode === 'color' && $pageBgColor) {
        $rawStyles .= "\nbody, .min-h-screen { background-color: {$pageBgColor} !important; background-image: none !important; }";
    } elseif ($pageBgMode === 'image' && $pageBgImageUrl) {
        $rawStyles .= "\nbody, .min-h-screen { background-image: url('{$pageBgImageUrl}') !important; background-size: cover !important; background-position: center center !important; background-attachment: fixed !important; }";
    } elseif ($pageBgMode === 'video') {
        $rawStyles .= "\nbody, .min-h-screen { background-color: transparent !important; background-image: none !important; }";
    }
@endphp
<style id="site-theme-customizer-styles">
{!! \App\Services\CssMinifierService::minify($rawStyles) !!}
</style>
