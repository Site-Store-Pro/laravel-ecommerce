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
}

