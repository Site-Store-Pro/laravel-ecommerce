<?php

namespace App\Services;

class UniqueSlugCheck
{
    /**
     * Verifies that the slug is unique across cms_pages, cms_pages_categories, and cms_pages_tags.
     */
    public static function isUnique(string $slug, string $type, ?int $ignoreId = null): bool
    {
        // 1. Check cms_pages
        $pageQuery = \App\Models\CmsPage::where('slug', $slug);
        if ($type === 'page' && $ignoreId) {
            $pageQuery->where('id', '!=', $ignoreId);
        }
        if ($pageQuery->exists()) {
            return false;
        }

        // 2. Check cms_pages_categories
        $catQuery = \App\Models\CmsPagesCategory::where('slug', $slug);
        if ($type === 'category' && $ignoreId) {
            $catQuery->where('id', '!=', $ignoreId);
        }
        if ($catQuery->exists()) {
            return false;
        }

        // 3. Check cms_pages_tags
        $tagQuery = \App\Models\CmsPagesTag::where('slug', $slug);
        if ($type === 'tag' && $ignoreId) {
            $tagQuery->where('id', '!=', $ignoreId);
        }
        if ($tagQuery->exists()) {
            return false;
        }

        return true;
    }
}
