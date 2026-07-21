@php
    $primaryColor = \App\Models\CmsSetting::get('theme_primary_color', '#4f46e5');
    $hoverColor = \App\Models\CmsSetting::get('theme_hover_color', '#4338ca');
    $textColor = \App\Models\CmsSetting::get('theme_text_color', '#ffffff');
    $borderRadius = \App\Models\CmsSetting::get('theme_border_radius', '0.75rem');
@endphp
<style id="site-theme-customizer-styles">
:root {
    --theme-primary: {{ $primaryColor }};
    --theme-primary-hover: {{ $hoverColor }};
    --theme-text: {{ $textColor }};
    --theme-border-radius: {{ $borderRadius }};
}

/* 1. Background Overrides */
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

/* 2. Text Overrides */
.text-indigo-600, .text-purple-600, .text-violet-600 {
    color: var(--theme-primary) !important;
}
.hover\:text-indigo-700:hover, .hover\:text-purple-700:hover, .hover\:text-violet-700:hover {
    color: var(--theme-primary-hover) !important;
}

/* 3. Border Overrides */
.border-indigo-500, .border-purple-500, .border-violet-500, .border-indigo-600, .border-purple-600, .border-violet-600 {
    border-color: var(--theme-primary) !important;
}
.hover\:border-indigo-300:hover, .hover\:border-purple-300:hover, .hover\:border-violet-300:hover {
    border-color: var(--theme-primary-hover) !important;
}

/* 4. Focus Ring Overrides */
.focus\:ring-indigo-500:focus, .focus\:ring-purple-500:focus, .focus\:ring-violet-500:focus {
    --tw-ring-color: var(--theme-primary) !important;
    outline-color: var(--theme-primary) !important;
}

/* 5. Border Radius Overrides for Theme Buttons */
button,
.btn,
.btn-primary,
a.bg-indigo-600,
a.bg-purple-600,
a.bg-violet-600,
a.bg-indigo-50,
a.bg-purple-50,
a.bg-violet-50,
input[type="submit"],
input[type="button"] {
    border-radius: var(--theme-border-radius) !important;
}

/* 6. Dynamic Theme Primary Button */
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
</style>
