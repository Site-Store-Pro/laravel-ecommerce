@php
    $primaryColor = \App\Models\CmsSetting::get('theme_primary_color');
    $primaryColor = !empty($primaryColor) ? $primaryColor : '#1e3a8a';

    $hoverColor   = \App\Models\CmsSetting::get('theme_hover_color');
    $hoverColor   = !empty($hoverColor) ? $hoverColor : '#172554';

    $textColor    = \App\Models\CmsSetting::get('theme_text_color');
    $textColor    = !empty($textColor) ? $textColor : '#ffffff';

    $priBorder    = \App\Models\CmsSetting::get('theme_primary_border_color');
    $priBorder    = !empty($priBorder) ? $priBorder : $primaryColor;

    $priHoverText = \App\Models\CmsSetting::get('theme_primary_hover_text_color');
    $priHoverText = !empty($priHoverText) ? $priHoverText : '#ffffff';

    $borderRadius = \App\Models\CmsSetting::get('theme_border_radius', '0.75rem');

    $secBg        = \App\Models\CmsSetting::get('theme_secondary_bg_color', 'transparent');
    $secText      = \App\Models\CmsSetting::get('theme_secondary_text_color', $primaryColor);
    $secBorder    = \App\Models\CmsSetting::get('theme_secondary_border_color', $primaryColor);
    $secHoverBg   = \App\Models\CmsSetting::get('theme_secondary_hover_bg_color', $primaryColor);
    $secHoverText = \App\Models\CmsSetting::get('theme_secondary_hover_text_color', '#ffffff');

    $rawLinkColor      = \App\Models\CmsSetting::get('theme_link_color');
    $rawLinkHoverColor = \App\Models\CmsSetting::get('theme_link_hover_color');
    $rawLinkActiveColor= \App\Models\CmsSetting::get('theme_link_active_color');

    $linkColor      = !empty($rawLinkColor) ? $rawLinkColor : '#1d4ed8';
    $linkHoverColor = !empty($rawLinkHoverColor) ? $rawLinkHoverColor : '#1e40af';
    $linkActiveColor= !empty($rawLinkActiveColor) ? $rawLinkActiveColor : '#1e3a8a';

    // Dark mode defaults: soft, readable sky blue (#60a5fa) that pairs beautifully with dark theme
    $darkLinkColor      = !empty($rawLinkColor) ? $rawLinkColor : '#60a5fa';
    $darkLinkHoverColor = !empty($rawLinkHoverColor) ? $rawLinkHoverColor : '#93c5fd';
    $darkLinkActiveColor= !empty($rawLinkActiveColor) ? $rawLinkActiveColor : '#bfdbfe';

    // Admin area button colours — isolated from the frontend theme so admin UX is always safe
    $adminBtnBg      = \App\Models\CmsSetting::get('admin_btn_primary_bg',      '#4f46e5');
    $adminBtnText    = \App\Models\CmsSetting::get('admin_btn_primary_text',    '#ffffff');
    $adminBtnHoverBg = \App\Models\CmsSetting::get('admin_btn_primary_hover_bg','#4338ca');
    $adminBtnBorder  = \App\Models\CmsSetting::get('admin_btn_primary_border',  '#4f46e5');

    $rawStyles = ":root {
        --theme-primary: {$primaryColor};
        --theme-primary-hover: {$hoverColor};
        --theme-text: {$textColor};
        --theme-primary-border: {$priBorder};
        --theme-primary-hover-text: {$priHoverText};
        --theme-border-radius: {$borderRadius};
        --theme-secondary-bg: {$secBg};
        --theme-secondary-text: {$secText};
        --theme-secondary-border: {$secBorder};
        --theme-secondary-hover-bg: {$secHoverBg};
        --theme-secondary-hover-text: {$secHoverText};
        --theme-link-color: {$linkColor};
        --theme-link-hover-color: {$linkHoverColor};
        --theme-link-active-color: {$linkActiveColor};
        --theme-dark-link-color: {$darkLinkColor};
        --theme-dark-link-hover-color: {$darkLinkHoverColor};
        --theme-dark-link-active-color: {$darkLinkActiveColor};
        /* Admin-area button vars — safe defaults, overridable via admin settings */
        --admin-btn-bg: {$adminBtnBg};
        --admin-btn-text: {$adminBtnText};
        --admin-btn-hover-bg: {$adminBtnHoverBg};
        --admin-btn-border: {$adminBtnBorder};
    }

    /* Primary Buttons & Background Utilities — EXCLUDED on admin-area pages */
    body:not(.admin-area) .bg-indigo-600, body:not(.admin-area) .bg-purple-600, body:not(.admin-area) .bg-violet-600,
    body:not(.admin-area) [class*='from-indigo-600'], body:not(.admin-area) [class*='from-purple-600'], body:not(.admin-area) [class*='from-violet-600'] {
        background-image: none !important;
        background-color: var(--theme-primary) !important;
        color: var(--theme-text, #ffffff) !important;
    }
    body:not(.admin-area) .hover\:bg-indigo-500:hover, body:not(.admin-area) .hover\:bg-indigo-700:hover, body:not(.admin-area) .hover\:bg-purple-500:hover, body:not(.admin-area) .hover\:bg-purple-700:hover, body:not(.admin-area) .hover\:bg-violet-700:hover,
    body:not(.admin-area) [class*='hover:from-indigo-700']:hover, body:not(.admin-area) [class*='hover:from-purple-700']:hover {
        background-image: none !important;
        background-color: var(--theme-primary-hover) !important;
        color: var(--theme-primary-hover-text, var(--theme-text, #ffffff)) !important;
    }
    body:not(.admin-area) .border-indigo-500, body:not(.admin-area) .border-purple-500, body:not(.admin-area) .border-violet-500, body:not(.admin-area) .border-indigo-600, body:not(.admin-area) .border-purple-600, body:not(.admin-area) .border-violet-600 {
        border-color: var(--theme-primary) !important;
    }
    body:not(.admin-area) .hover\:border-indigo-300:hover, body:not(.admin-area) .hover\:border-purple-300:hover, body:not(.admin-area) .hover\:border-violet-300:hover {
        border-color: var(--theme-primary-hover) !important;
    }
    body:not(.admin-area) .focus\:ring-indigo-500:focus, body:not(.admin-area) .focus\:ring-purple-500:focus, body:not(.admin-area) .focus\:ring-violet-500:focus {
        --tw-ring-color: var(--theme-primary) !important;
        outline-color: var(--theme-primary) !important;
    }

    /* Button Border Radius & Shape — EXCLUDED on admin-area pages */
    body:not(.admin-area) button:not(nav[role='navigation'] button),
    body:not(.admin-area) .btn,
    body:not(.admin-area) .btn-primary,
    body:not(.admin-area) a.bg-indigo-600,
    body:not(.admin-area) a.bg-purple-600,
    body:not(.admin-area) a.bg-violet-600,
    body:not(.admin-area) a.bg-indigo-50,
    body:not(.admin-area) a.bg-purple-50,
    body:not(.admin-area) a.bg-violet-50,
    body:not(.admin-area) input[type=\"submit\"],
    body:not(.admin-area) input[type=\"button\"] {
        border-radius: var(--theme-border-radius) !important;
    }

    /* Primary Theme Button — EXCLUDED on admin-area pages */
    body:not(.admin-area) .btn-theme-primary {
        background-color: var(--theme-primary) !important;
        color: var(--theme-text, #ffffff) !important;
        border: 1px solid var(--theme-primary-border, var(--theme-primary)) !important;
        border-radius: var(--theme-border-radius) !important;
        padding: 10px 20px !important;
        font-weight: 700 !important;
        font-family: inherit !important;
        cursor: pointer !important;
        display: inline-block !important;
        text-align: center !important;
        text-decoration: none !important;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s !important;
    }
    body:not(.admin-area) .btn-theme-primary:hover {
        background-color: var(--theme-primary-hover) !important;
        color: var(--theme-primary-hover-text, var(--theme-text, #ffffff)) !important;
        border-color: var(--theme-primary-hover) !important;
    }
    body:not(.admin-area) .btn-theme-primary *,
    body:not(.admin-area) .btn-primary * {
        color: inherit !important;
    }

    /* Secondary Theme Button — EXCLUDED on admin-area pages */
    body:not(.admin-area) .btn-secondary, body:not(.admin-area) .btn-theme-secondary {
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
    body:not(.admin-area) .btn-secondary:hover, body:not(.admin-area) .btn-theme-secondary:hover,
    body:not(.admin-area) .btn-secondary.active, body:not(.admin-area) .btn-theme-secondary.active {
        background-color: var(--theme-secondary-hover-bg) !important;
        color: var(--theme-secondary-hover-text) !important;
    }

    /* ── Admin-Area Button Classes ────────────────────────────────────────────
       These use --admin-btn-* vars (set above in :root) and are NEVER overridden
       by the frontend theme. Admin buttons keep their own safe colour scheme.
       ─────────────────────────────────────────────────────────────────────── */
    .admin-btn-primary {
        background-color: var(--admin-btn-bg, #4f46e5) !important;
        color: var(--admin-btn-text, #ffffff) !important;
        border: 1px solid var(--admin-btn-border, #4f46e5) !important;
        border-radius: 0.75rem !important;
        padding: 8px 18px !important;
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        font-family: inherit !important;
        cursor: pointer !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.4rem !important;
        text-decoration: none !important;
        transition: background-color 0.15s, border-color 0.15s !important;
        white-space: nowrap !important;
    }
    .admin-btn-primary:hover, .admin-btn-primary:focus {
        background-color: var(--admin-btn-hover-bg, #4338ca) !important;
        border-color: var(--admin-btn-hover-bg, #4338ca) !important;
        color: var(--admin-btn-text, #ffffff) !important;
    }
    .admin-btn-primary * { color: inherit !important; }

    /* ── Admin Top-Nav Link Colours ───────────────────────────────────────────
       Override the x-nav-link component's hardcoded gray Tailwind classes so
       admin nav tabs follow the admin dark/light mode and never inherit the
       storefront link colour theme.
       ─────────────────────────────────────────────────────────────────────── */
    body.admin-area nav a.border-b-2.border-transparent {
        color: #64748b !important; /* slate-500 */
    }
    body.admin-area nav a.border-b-2.border-transparent:hover {
        color: #1e293b !important; /* slate-800 */
        border-color: #cbd5e1 !important; /* slate-300 */
    }
    body.admin-area nav a.border-b-2.border-indigo-400,
    body.admin-area nav a.border-b-2.border-indigo-500 {
        color: #1e293b !important; /* slate-800 */
        border-color: var(--admin-btn-bg, #4f46e5) !important;
    }
    /* Dark-mode overrides for admin nav links */
    html.dark body.admin-area nav a.border-b-2.border-transparent {
        color: #94a3b8 !important; /* slate-400 */
    }
    html.dark body.admin-area nav a.border-b-2.border-transparent:hover {
        color: #e2e8f0 !important; /* slate-200 */
        border-color: #475569 !important; /* slate-600 */
    }
    html.dark body.admin-area nav a.border-b-2.border-indigo-400,
    html.dark body.admin-area nav a.border-b-2.border-indigo-500 {
        color: #f1f5f9 !important; /* slate-100 */
        border-color: var(--admin-btn-bg, #818cf8) !important;
    }
    /* Also fix dropdown trigger buttons in the admin nav bar */
    body.admin-area nav button.border-b-2.border-transparent {
        color: #64748b !important;
    }
    body.admin-area nav button.border-b-2.border-transparent:hover {
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }
    body.admin-area nav button.border-b-2.border-indigo-500 {
        color: #1e293b !important;
        border-color: var(--admin-btn-bg, #4f46e5) !important;
    }
    html.dark body.admin-area nav button.border-b-2.border-transparent {
        color: #94a3b8 !important;
    }
    html.dark body.admin-area nav button.border-b-2.border-transparent:hover {
        color: #e2e8f0 !important;
        border-color: #475569 !important;
    }
    html.dark body.admin-area nav button.border-b-2.border-indigo-500 {
        color: #f1f5f9 !important;
        border-color: var(--admin-btn-bg, #818cf8) !important;
    }

    /* Standard Hyperlinks Only (Excludes ALL Buttons, Badges, Tabs & Explicitly Colored Text) */
    a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']) {
        color: var(--theme-link-color, #1d4ed8);
        transition: color 0.15s ease;
    }
    a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']):hover {
        color: var(--theme-link-hover-color, #1e40af);
    }
    a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']):active {
        color: var(--theme-link-active-color, #1e3a8a);
    }

    /* Dark Mode Hyperlinks (Excludes ALL Buttons, Badges, Tabs & Explicitly Colored Text) */
    html.dark a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']) {
        color: var(--theme-dark-link-color, #60a5fa) !important;
    }
    html.dark a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']):hover {
        color: var(--theme-dark-link-hover-color, #93c5fd) !important;
    }
    html.dark a:not(button):not(.btn):not(.btn-primary):not(.btn-secondary):not(.btn-theme-primary):not(.btn-theme-secondary):not(.btn-view-mode):not(.dyn-nav-link):not(.social-icon-link):not([x-show='open'] a):not([class*='bg-']):not([class*='btn']):not([class*='button']):not([class*='badge']):not([class*='text-white']):not([class*='text-slate-']):not([class*='text-emerald-']):not([class*='text-amber-']):not([class*='text-red-']):not([class*='from-']):active {
        color: var(--theme-dark-link-active-color, #bfdbfe) !important;
    }
    /* Footer Links in Dark Mode */
    html.dark .footer_container a,
    html.dark .footer_contents a,
    html.dark .footer_row1 a,
    html.dark .footer_row2 a,
    html.dark .footer_row3 a,
    html.dark .footer_row4 a,
    html.dark footer a {
        color: #94a3b8 !important;
        transition: color 0.15s ease !important;
    }
    html.dark .footer_container a:hover,
    html.dark .footer_contents a:hover,
    html.dark .footer_row1 a:hover,
    html.dark .footer_row2 a:hover,
    html.dark .footer_row3 a:hover,
    html.dark .footer_row4 a:hover,
    html.dark footer a:hover {
        color: #e2e8f0 !important;
    }
    html.dark nav[role='navigation'] [aria-current='page'],
    html.dark nav[role='navigation'] [aria-current='page'] > span,
    html.dark nav[role='navigation'] [aria-current='page'] > button {
        background-color: var(--pagination-active-bg, #2c4a7c) !important;
        color: var(--pagination-active-text, #ffffff) !important;
        border-color: var(--pagination-active-bg, #2c4a7c) !important;
    }
    /* Dark Mode: Strip ALL button shadows & glows entirely */
    html.dark button,
    html.dark .btn,
    html.dark .btn-primary,
    html.dark .btn-secondary,
    html.dark .btn-theme-primary,
    html.dark .btn-theme-secondary,
    html.dark a.bg-indigo-600,
    html.dark a.bg-indigo-700,
    html.dark a.bg-purple-600,
    html.dark a.bg-violet-600,
    html.dark a[class*='shadow'],
    html.dark button[class*='shadow'],
    html.dark input[type='submit'],
    html.dark input[type='button'] {
        box-shadow: none !important;
        --tw-shadow: 0 0 #0000 !important;
        --tw-shadow-colored: 0 0 #0000 !important;
        filter: none !important;
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
