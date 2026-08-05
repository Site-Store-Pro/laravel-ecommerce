<?php

namespace App\Plugins\Display;

use App\Models\CmsSetting;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class SocialIconsPlugin implements DisplayPlugin
{
    private static bool $faCdnLoaded = false;

    public function slug(): string
    {
        return 'social-icons-2026';
    }

    public function name(): string
    {
        return 'Social Media Icons 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $align      = strtolower($params['align']        ?? $settings['alignment']        ?? 'left');
            $size       = strtolower($params['size']         ?? $settings['icon_size']        ?? 'md'); // sm, md, lg
            $style      = strtolower($params['style']        ?? $params['icon_style']         ?? $settings['icon_style'] ?? 'non-circle');
            $useFa      = strtolower($params['font_awesome'] ?? $settings['use_font_awesome'] ?? 'on') === 'on';

            $isCircle   = in_array($style, ['circle', 'rounded']);

            // Gather social links and contact icons from settings or CmsSetting defaults
            $phone     = $params['phone']     ?? $settings['phone_number']  ?? CmsSetting::get('social_phone', CmsSetting::get('store_phone', ''));
            $email     = $params['email']     ?? $settings['email_address']  ?? CmsSetting::get('social_email', CmsSetting::get('store_email', ''));
            $facebook  = $params['facebook']  ?? $settings['facebook_url']  ?? CmsSetting::get('social_facebook', '');
            $twitter   = $params['twitter']   ?? $settings['twitter_url']   ?? CmsSetting::get('social_twitter', '');
            $instagram = $params['instagram'] ?? $settings['instagram_url'] ?? CmsSetting::get('social_instagram', '');
            $youtube   = $params['youtube']   ?? $settings['youtube_url']   ?? CmsSetting::get('social_youtube', '');
            $linkedin  = $params['linkedin']  ?? $settings['linkedin_url']  ?? CmsSetting::get('social_linkedin', '');
            $pinterest = $params['pinterest'] ?? $settings['pinterest_url'] ?? CmsSetting::get('social_pinterest', '');
            $tiktok    = $params['tiktok']    ?? $settings['tiktok_url']    ?? CmsSetting::get('social_tiktok', '');

            // Format phone and email URLs if needed
            $phoneUrl = !empty($phone) ? (str_starts_with($phone, 'tel:') ? $phone : 'tel:' . preg_replace('/[^\d\+]/', '', $phone)) : '';
            $emailUrl = !empty($email) ? (str_starts_with($email, 'mailto:') ? $email : 'mailto:' . trim($email)) : '';

            $links = array_filter([
                'phone'     => ['url' => $phoneUrl,  'name' => 'Phone',       'fa' => 'fa-solid fa-phone',    'is_contact' => true],
                'email'     => ['url' => $emailUrl,  'name' => 'Email',       'fa' => 'fa-solid fa-envelope', 'is_contact' => true],
                'facebook'  => ['url' => $facebook,  'name' => 'Facebook',    'fa' => 'fa-brands fa-facebook-f'],
                'twitter'   => ['url' => $twitter,   'name' => 'X (Twitter)', 'fa' => 'fa-brands fa-x-twitter'],
                'instagram' => ['url' => $instagram, 'name' => 'Instagram',   'fa' => 'fa-brands fa-instagram'],
                'youtube'   => ['url' => $youtube,   'name' => 'YouTube',     'fa' => 'fa-brands fa-youtube'],
                'linkedin'  => ['url' => $linkedin,  'name' => 'LinkedIn',    'fa' => 'fa-brands fa-linkedin-in'],
                'pinterest' => ['url' => $pinterest, 'name' => 'Pinterest',   'fa' => 'fa-brands fa-pinterest-p'],
                'tiktok'    => ['url' => $tiktok,    'name' => 'TikTok',      'fa' => 'fa-brands fa-tiktok'],
            ], fn($item) => !empty($item['url']));

            if (empty($links)) {
                // Fallback default sample links if none set in DB
                $links = [
                    'facebook'  => ['url' => 'https://facebook.com',  'name' => 'Facebook',    'fa' => 'fa-brands fa-facebook-f'],
                    'twitter'   => ['url' => 'https://twitter.com',   'name' => 'X (Twitter)', 'fa' => 'fa-brands fa-x-twitter'],
                    'instagram' => ['url' => 'https://instagram.com', 'name' => 'Instagram',   'fa' => 'fa-brands fa-instagram'],
                    'youtube'   => ['url' => 'https://youtube.com',   'name' => 'YouTube',     'fa' => 'fa-brands fa-youtube'],
                ];
            }

            $alignClass = match ($align) {
                'center' => 'justify-center',
                'right'  => 'justify-end',
                default  => 'justify-start',
            };

            if ($isCircle) {
                $iconClass = match ($size) {
                    'sm'    => 'w-7 h-7 text-xs rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white shadow-sm',
                    'lg'    => 'w-11 h-11 text-lg rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white shadow-sm',
                    default => 'w-9 h-9 text-sm rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white shadow-sm',
                };
            } else {
                $iconClass = match ($size) {
                    'sm'    => 'text-sm p-1 bg-transparent social-icon-link',
                    'lg'    => 'text-2xl p-1 bg-transparent social-icon-link',
                    default => 'text-xl p-1 bg-transparent social-icon-link',
                };
            }

            $headerColor   = $params['header_color'] ?? $params['header_icon_color'] ?? $settings['header_icon_color'] ?? '#ffffff';
            $footerColor   = $params['footer_color'] ?? $params['footer_icon_color'] ?? $settings['footer_icon_color'] ?? '';
            $colorOverride = $params['color']        ?? $params['color_override']      ?? $settings['icon_color_override'] ?? '';

            $cssRules = '';
            if (!empty($headerColor)) {
                $cssRules .= "
                    .top_sharing_container .social-icon-link,
                    .top_sharing_contents .social-icon-link,
                    .top_nav_container .social-icon-link,
                    .site_header_container .social-icon-link {
                        color: {$headerColor} !important;
                    }
                ";
            }
            if (!empty($footerColor)) {
                $cssRules .= "
                    .footer_container .social-icon-link,
                    .footer_contents .social-icon-link {
                        color: {$footerColor} !important;
                    }
                ";
            }
            if (!empty($colorOverride)) {
                $cssRules .= "
                    .social-icon-link {
                        color: {$colorOverride} !important;
                    }
                ";
            }

            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss  = $params['custom_css'] ?? $settings['custom_css'] ?? '';

            $html = '';

            if (!empty($defaultCss) || !empty($customCss) || !empty($cssRules)) {
                $html .= "<style>\n";
                if (!empty($defaultCss)) {
                    $html .= \App\Services\CssMinifierService::minify($defaultCss) . "\n";
                }
                if (!empty($customCss)) {
                    $html .= \App\Services\CssMinifierService::minify($customCss) . "\n";
                }
                if (!empty($cssRules)) {
                    $html .= \App\Services\CssMinifierService::minify($cssRules) . "\n";
                }
                $html .= "</style>\n";
            }

            // Inject FontAwesome 6 CDN if enabled and not already loaded on this request
            $alreadyLoadedInRequest = app()->bound('fa_cdn_loaded');

            if ($useFa) {
                if (!$alreadyLoadedInRequest) {
                    app()->instance('fa_cdn_loaded', true);
                    $html .= '<link id="fa-cdn-css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
                }

                // Client-side DOM guard: guarantees FontAwesome is in document.head regardless of render order or multiple instances
                $html .= '<script>
                    if (!window.faCdnLoaded && !document.getElementById("fa-cdn-css") && !document.querySelector("link[href*=\"font-awesome\"]")) {
                        window.faCdnLoaded = true;
                        var link = document.createElement("link");
                        link.id = "fa-cdn-css";
                        link.rel = "stylesheet";
                        link.href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css";
                        link.integrity = "sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==";
                        link.crossOrigin = "anonymous";
                        link.referrerPolicy = "no-referrer";
                        document.head.appendChild(link);
                    }
                </script>';
            }

            $html .= '<div class="social-icons-wrapper flex items-center gap-3 ' . $alignClass . ' py-1">';

            foreach ($links as $key => $item) {
                $targetAttr = !empty($item['is_contact']) ? '' : 'target="_blank" rel="noopener noreferrer" ';
                $html .= '<a href="' . e($item['url']) . '" ' . $targetAttr;
                $html .= 'class="inline-flex items-center justify-center ' . $iconClass . ' transition-all shrink-0" ';
                $html .= 'title="' . e($item['name']) . '">';
                
                if ($useFa) {
                    $html .= '<i class="' . e($item['fa']) . '"></i>';
                } else {
                    $html .= '<span class="font-bold uppercase text-[10px]">' . substr($item['name'], 0, 2) . '</span>';
                }

                $html .= '</a>';
            }

            $html .= '</div>';

            return $html;

        } catch (\Throwable $e) {
            Log::error('[SocialIconsPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: social-icons-2026] ' . e($e->getMessage()) . ' -->';
        }
    }
}
