<?php

/**
 * Navigation color scheme definitions.
 *
 * Each scheme maps CSS custom-property names to values.
 * These are injected as CSS variables scoped to #top-nav-{slug} in the nav blade component.
 * Custom CSS entered in the admin is appended after these variables.
 */

return [

    'default' => [
        '--nav-bg'               => 'rgba(255,255,255,0.85)',
        '--nav-backdrop'         => 'blur(12px)',
        '--nav-border'           => 'rgba(226,232,240,0.8)',    // slate-200/80
        '--nav-text'             => '#475569',                  // slate-600
        '--nav-text-hover'       => '#4f46e5',                  // indigo-600
        '--nav-logo-filter'      => 'none',
        '--nav-dropdown-bg'      => '#ffffff',
        '--nav-dropdown-border'  => '#e2e8f0',                  // slate-200
        '--nav-dropdown-shadow'  => '0 10px 40px rgba(0,0,0,.10)',
        '--nav-dropdown-text'    => '#1e293b',                  // slate-800
        '--nav-dropdown-hover-bg'=> '#f8fafc',                  // slate-50
        '--nav-mobile-bg'        => '#ffffff',
        '--nav-mobile-text'      => '#1e293b',
        '--nav-badge-bg'         => '#4f46e5',                  // indigo-600
        '--nav-badge-text'       => '#ffffff',
    ],

    'dark' => [
        '--nav-bg'               => 'rgba(15,23,42,0.95)',      // slate-900
        '--nav-backdrop'         => 'blur(12px)',
        '--nav-border'           => 'rgba(51,65,85,0.8)',       // slate-700/80
        '--nav-text'             => '#cbd5e1',                  // slate-300
        '--nav-text-hover'       => '#a5b4fc',                  // indigo-300
        '--nav-logo-filter'      => 'brightness(0) invert(1)',
        '--nav-dropdown-bg'      => '#1e293b',                  // slate-800
        '--nav-dropdown-border'  => '#334155',                  // slate-700
        '--nav-dropdown-shadow'  => '0 10px 40px rgba(0,0,0,.40)',
        '--nav-dropdown-text'    => '#e2e8f0',                  // slate-200
        '--nav-dropdown-hover-bg'=> '#334155',                  // slate-700
        '--nav-mobile-bg'        => '#1e293b',
        '--nav-mobile-text'      => '#e2e8f0',
        '--nav-badge-bg'         => '#6366f1',                  // indigo-500
        '--nav-badge-text'       => '#ffffff',
    ],

    'indigo' => [
        '--nav-bg'               => 'linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%)',
        '--nav-backdrop'         => 'none',
        '--nav-border'           => 'rgba(255,255,255,0.15)',
        '--nav-text'             => 'rgba(255,255,255,0.85)',
        '--nav-text-hover'       => '#ffffff',
        '--nav-logo-filter'      => 'brightness(0) invert(1)',
        '--nav-dropdown-bg'      => '#4338ca',                  // indigo-700
        '--nav-dropdown-border'  => 'rgba(255,255,255,0.15)',
        '--nav-dropdown-shadow'  => '0 10px 40px rgba(79,70,229,.40)',
        '--nav-dropdown-text'    => '#ffffff',
        '--nav-dropdown-hover-bg'=> 'rgba(255,255,255,0.10)',
        '--nav-mobile-bg'        => '#4338ca',
        '--nav-mobile-text'      => '#ffffff',
        '--nav-badge-bg'         => '#ffffff',
        '--nav-badge-text'       => '#4f46e5',
    ],

    'slate' => [
        '--nav-bg'               => '#1e293b',                  // slate-800
        '--nav-backdrop'         => 'none',
        '--nav-border'           => '#334155',                  // slate-700
        '--nav-text'             => '#94a3b8',                  // slate-400
        '--nav-text-hover'       => '#f1f5f9',                  // slate-100
        '--nav-logo-filter'      => 'brightness(0) invert(1)',
        '--nav-dropdown-bg'      => '#0f172a',                  // slate-900
        '--nav-dropdown-border'  => '#1e293b',
        '--nav-dropdown-shadow'  => '0 10px 40px rgba(0,0,0,.50)',
        '--nav-dropdown-text'    => '#e2e8f0',
        '--nav-dropdown-hover-bg'=> '#1e293b',
        '--nav-mobile-bg'        => '#0f172a',
        '--nav-mobile-text'      => '#e2e8f0',
        '--nav-badge-bg'         => '#6366f1',
        '--nav-badge-text'       => '#ffffff',
    ],

    'transparent' => [
        '--nav-bg'               => 'transparent',
        '--nav-backdrop'         => 'none',
        '--nav-border'           => 'transparent',
        '--nav-text'             => 'rgba(255,255,255,0.90)',
        '--nav-text-hover'       => '#ffffff',
        '--nav-logo-filter'      => 'brightness(0) invert(1)',
        '--nav-dropdown-bg'      => 'rgba(15,23,42,0.92)',
        '--nav-dropdown-border'  => 'rgba(255,255,255,0.10)',
        '--nav-dropdown-shadow'  => '0 10px 40px rgba(0,0,0,.40)',
        '--nav-dropdown-text'    => '#f1f5f9',
        '--nav-dropdown-hover-bg'=> 'rgba(255,255,255,0.08)',
        '--nav-mobile-bg'        => 'rgba(15,23,42,0.96)',
        '--nav-mobile-text'      => '#f1f5f9',
        '--nav-badge-bg'         => '#ffffff',
        '--nav-badge-text'       => '#1e293b',
    ],

    // 'custom' scheme uses no preset vars — relies entirely on the menu's custom_css field
    'custom' => [],

];
