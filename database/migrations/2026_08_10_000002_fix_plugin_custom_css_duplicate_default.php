<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Fix plugin CSS settings for slideshow-2026 and live-search-2026.
 *
 * Root cause:
 *   The `custom_css` plugin_settings row for each plugin was seeded with the
 *   same CSS blob as `default_css`. Additionally, the live server may have a
 *   legacy `live_css` row containing the real user-authored CSS.
 *
 * Fix (in order):
 *   1. If custom_css === default_css (duplicate), clear custom_css to ''.
 *   2. If a live_css row exists with content, copy it to custom_css
 *      (now safe because step 1 cleared the duplicate) then delete the
 *      orphaned live_css row.
 *
 * Applied to both slideshow-2026 and live-search-2026.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->fixPlugin('slideshow-2026');
        $this->fixPlugin('live-search-2026');
    }

    private function fixPlugin(string $shortcode): void
    {
        $plugin = DB::table('plugins')->where('shortcode', $shortcode)->first();
        if (!$plugin) {
            return;
        }

        $pid = $plugin->id;

        $defaultRow = DB::table('plugin_settings')
            ->where('plugin_id', $pid)
            ->where('field_name', 'default_css')
            ->first();

        $customRow = DB::table('plugin_settings')
            ->where('plugin_id', $pid)
            ->where('field_name', 'custom_css')
            ->first();

        $liveCssRow = DB::table('plugin_settings')
            ->where('plugin_id', $pid)
            ->where('field_name', 'live_css')
            ->first();

        $defaultVal = $defaultRow ? $this->normalise($defaultRow->field_value ?? '') : '';
        $customVal  = $customRow  ? $this->normalise($customRow->field_value  ?? '') : '';

        // Step 1 — Clear custom_css if it is a duplicate of default_css.
        if ($customRow && $customVal !== '' && $customVal === $defaultVal) {
            DB::table('plugin_settings')
                ->where('plugin_id', $pid)
                ->where('field_name', 'custom_css')
                ->update(['field_value' => '']);

            $customVal = ''; // treat as empty for the next step
        }

        // Step 2 — Copy live_css → custom_css (if live_css has real content and
        // custom_css is now empty), then delete the orphaned live_css row.
        if ($liveCssRow && !empty(trim($liveCssRow->field_value ?? ''))) {
            if ($customVal === '') {
                DB::table('plugin_settings')->updateOrInsert(
                    ['plugin_id' => $pid, 'field_name' => 'custom_css'],
                    ['field_value' => $liveCssRow->field_value]
                );
            }

            // Always remove the legacy live_css row regardless of whether we copied.
            DB::table('plugin_settings')
                ->where('plugin_id', $pid)
                ->where('field_name', 'live_css')
                ->delete();
        }
    }

    /** Collapse all whitespace runs to a single space and trim. */
    private function normalise(?string $value): string
    {
        return preg_replace('/\s+/', ' ', trim((string) $value));
    }

    public function down(): void
    {
        // Data-only migration — not safely reversible without storing originals.
    }
};
