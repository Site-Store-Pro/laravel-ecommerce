<?php

namespace App\Services;

class ShortcodeParser
{
    /**
     * Parse content containing shortcodes, plugins, and dynamic markup.
     * Alias for ContentParserService::parse().
     *
     * @param string|null $content
     * @param array $data
     * @return string
     */
    public static function parse(?string $content, array $data = []): string
    {
        return ContentParserService::parse($content, $data);
    }
}
