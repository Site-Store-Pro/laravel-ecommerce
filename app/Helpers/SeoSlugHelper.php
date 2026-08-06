<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SeoSlugHelper
{
    /**
     * Common English stop words to remove for clean, SEO-optimized URL slugs.
     */
    protected static array $stopWords = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'but', 'by',
        'for', 'from', 'if', 'in', 'into', 'is', 'it', 'no', 'not',
        'of', 'on', 'or', 'such', 'that', 'the', 'their', 'then',
        'there', 'these', 'they', 'this', 'to', 'was', 'will', 'with'
    ];

    /**
     * Generate an SEO-optimized slug from title/text.
     * Replaces spaces/underscores with dashes, removes stop words & invalid characters.
     */
    public static function generate(string $text): string
    {
        if (empty(trim($text))) {
            return '';
        }

        // Convert accents to ASCII and lowercase
        $clean = Str::ascii(mb_strtolower(trim($text)));

        // Strip invalid characters (keep a-z, 0-9, spaces, hyphens)
        $clean = preg_replace('/[^a-z0-9\s\-]/', '', $clean);

        // Split into token words
        $words = preg_split('/[\s_\-]+/', $clean, -1, PREG_SPLIT_NO_EMPTY);

        // Filter out stop words
        $filtered = array_filter($words, fn($w) => !in_array($w, self::$stopWords, true));

        // If filtering removed all words (e.g. title "To Be or Not to Be"), keep original words
        if (empty($filtered)) {
            $filtered = $words;
        }

        // Join with dash and sanitize multiple dashes
        $slug = implode('-', $filtered);
        $slug = preg_replace('/-+/', '-', $slug);

        return trim($slug, '-');
    }
}
