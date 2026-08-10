<?php

namespace App\Plugins\Display;

use App\Models\Plugin;
use App\Models\CmsSlideshow;
use App\Plugins\Contracts\DisplayPlugin;

class SlideshowPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'slideshow-2026';
    }

    public function name(): string
    {
        return 'Slideshow - Swiper Display (2026)';
    }

    public function render(array $params, Plugin $plugin): string
    {
        $id = $params['id'] ?? null;
        
        if ($id) {
            $slideshow = CmsSlideshow::where('slideshow_id', $id)->where('slideshow_active', 1)->first();
        } else {
            $slideshow = CmsSlideshow::where('slideshow_active', 1)->orderBy('sort_order')->first();
        }

        if (!$slideshow) {
            return '';
        }

        $nav = $params['nav'] ?? 'on';
        $paging = $params['paging'] ?? 'on';
        $autoplay = $params['autoplay'] ?? 'on';
        $speed = $params['speed'] ?? '5000';
        $effect = $params['effect'] ?? 'fade';

        $slides = $slideshow->slides()->where('Active', 1)->orderBy('ImageSort')->withCurrentTranslations()->get();

        if ($slides->isEmpty()) {
            return '';
        }

        $settings   = $plugin->getSettings();
        $defaultCss = $plugin->getSetting('default_css', '');
        $customCss  = $params['custom_css'] ?? ($settings['custom_css'] ?? '');
        $swiperId   = 'swiper_' . $slideshow->slideshow_id;

        return view('plugins.display.slideshow', compact('slideshow', 'slides', 'params', 'plugin', 'swiperId', 'defaultCss', 'customCss', 'nav', 'paging', 'autoplay', 'speed', 'effect'))->render();
    }
}
