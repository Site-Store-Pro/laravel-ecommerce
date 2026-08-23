<?php

namespace App\Services;

use App\Plugins\Support\ShortcodeProcessor;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class ContentParserService
{
    /**
     * Parse dynamic content containing Blade/Livewire directives and conditions,
     * then expand any [plugin:slug] shortcodes.
     *
     * @param string|null $content The raw content from the database.
     * @param array $data Additional variables to make available in the Blade context.
     * @return string
     */
    public static function parse(?string $content, array $data = []): string
    {
        if (empty($content)) {
            return '';
        }

        try {
            // Share standard session/auth variables implicitly if not provided
            if (!isset($data['user']) && auth()->check()) {
                $data['user'] = auth()->user();
            }

            $rendered = Blade::render($content, $data);

            // Expand [plugin:slug] shortcodes after Blade compilation
            $rendered = app(ShortcodeProcessor::class)->process($rendered);

            // Minify embedded <style> blocks in the CMS content before output
            $rendered = static::minifyEmbeddedStyles($rendered);

            return $rendered;
        } catch (\Throwable $e) {
            // Log the error and fall back to raw content so the page doesn't crash completely
            Log::error("Blade dynamic parsing failed: " . $e->getMessage(), [
                'content_preview' => substr($content, 0, 200),
                'exception' => $e
            ]);
            return $content;
        }
    }

    /**
     * Finds all <style>...</style> blocks in the HTML/CMS content and minifies their CSS.
     */
    public static function minifyEmbeddedStyles(string $html): string
    {
        if (!str_contains($html, '<style')) {
            return $html;
        }

        return preg_replace_callback(
            '/<style(\b[^>]*)>(.*?)<\/style>/is',
            function ($matches) {
                $attributes = $matches[1];
                $rawCss     = $matches[2];
                $minified   = CssMinifierService::minify($rawCss);
                return "<style{$attributes}>{$minified}</style>";
            },
            $html
        );
    }
}

