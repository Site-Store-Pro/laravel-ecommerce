<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DemoPurgeService
{
    /**
     * Determine whether any demo content currently exists in the database.
     */
    public static function hasDemoContent(): bool
    {
        try {
            return DB::table('products')->where('is_demo', 1)->exists()
                || DB::table('cms_testimonials')->where('is_demo', 1)->exists()
                || DB::table('cms_slideshows')->where('is_demo', 1)->exists()
                || DB::table('cms_pages')->where('is_demo', 1)->exists()
                || DB::table('cms_downloads')->where('is_demo', 1)->exists()
                || DB::table('kb_articles')->where('is_demo', 1)->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Purge all demo-seeded records across all tables in safe foreign-key order.
     */
    public static function purgeDemoContent(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        try {
            // 1. Demo product reviews
            DB::table('product_reviews')->where('is_demo', 1)->delete();

            // 2. Demo product cross-selling
            DB::table('product_cross_selling')->where('is_demo', 1)->delete();

            // 3. Demo product images
            DB::table('product_images')->where('is_demo', 1)->delete();

            // 4. Demo variant IDs for child row cleanup
            $demoVariantIds = DB::table('product_variants')
                ->where('is_demo', 1)
                ->pluck('id')
                ->toArray();

            if (!empty($demoVariantIds)) {
                DB::table('product_variant_events')->whereIn('variant_id', $demoVariantIds)->delete();
                DB::table('products_inventory')->whereIn('variant_id', $demoVariantIds)->delete();
                DB::table('product_variant_translations')->whereIn('product_variant_id', $demoVariantIds)->delete();
            }

            // 5. Demo product IDs for child row cleanup
            $demoProductIds = DB::table('products')
                ->where('is_demo', 1)
                ->pluck('id')
                ->toArray();

            if (!empty($demoProductIds)) {
                $demoFieldIds = DB::table('product_fields')
                    ->whereIn('product_id', $demoProductIds)
                    ->pluck('id')
                    ->toArray();

                if (!empty($demoFieldIds)) {
                    DB::table('product_field_option_translations')->whereIn('product_field_option_id', function ($q) use ($demoFieldIds) {
                        $q->select('id')->from('product_field_options')->whereIn('product_field_id', $demoFieldIds);
                    })->delete();
                    DB::table('product_field_options')->whereIn('product_field_id', $demoFieldIds)->delete();
                    DB::table('product_field_translations')->whereIn('product_field_id', $demoFieldIds)->delete();
                }

                DB::table('product_fields')->whereIn('product_id', $demoProductIds)->delete();
                DB::table('product_categories_assignments')->whereIn('product_id', $demoProductIds)->delete();
                $alertIds = DB::table('products')->whereIn('id', $demoProductIds)->pluck('inventory_alert_id')->filter()->toArray();
                if (!empty($alertIds)) {
                    DB::table('product_inventory_alert_translations')->whereIn('product_inventory_alert_id', $alertIds)->delete();
                    DB::table('product_inventory_alerts')->whereIn('id', $alertIds)->delete();
                }
            }

            // 6. Demo variants & products
            DB::table('product_variants')->where('is_demo', 1)->delete();
            DB::table('products')->where('is_demo', 1)->delete();

            // 7. Demo brands
            DB::table('product_brands')->where('is_demo', 1)->delete();

            // 8. Demo categories & category translations
            $demoCatIds = DB::table('product_categories')->where('is_demo', 1)->pluck('id')->toArray();
            if (!empty($demoCatIds)) {
                DB::table('category_translations')->whereIn('category_id', $demoCatIds)->delete();
            }
            DB::table('product_categories')->where('is_demo', 1)->orderByDesc('parent_id')->delete();

            // 9. Demo testimonials & translations
            $demoTestimonialIds = DB::table('cms_testimonials')->where('is_demo', 1)->pluck('id')->toArray();
            if (!empty($demoTestimonialIds)) {
                DB::table('testimonial_translations')->whereIn('testimonial_id', $demoTestimonialIds)->delete();
            }
            DB::table('cms_testimonials')->where('is_demo', 1)->delete();

            // 10. Demo slideshows & slides
            $demoSlideshowIds = DB::table('cms_slideshows')->where('is_demo', 1)->orWhere('slideshow_id', 1)->pluck('slideshow_id')->toArray();
            if (!empty($demoSlideshowIds)) {
                $slideIds = DB::table('cms_slides')->whereIn('slideshow_id', $demoSlideshowIds)->orWhere('is_demo', 1)->pluck('id')->toArray();
                if (!empty($slideIds)) {
                    DB::table('cms_slide_translations')->whereIn('cms_slide_id', $slideIds)->delete();
                }
                DB::table('cms_slides')->whereIn('slideshow_id', $demoSlideshowIds)->orWhere('is_demo', 1)->delete();
                DB::table('cms_slideshows')->whereIn('slideshow_id', $demoSlideshowIds)->orWhere('is_demo', 1)->delete();
            }

            // 11. Demo CMS Pages (Excluding ID 13, which is seeded in default install seed)
            $demoPageIds = DB::table('cms_pages')->where('is_demo', 1)->pluck('id')->toArray();
            if (!empty($demoPageIds)) {
                DB::table('cms_page_translations')->whereIn('cms_page_id', $demoPageIds)->delete();
                DB::table('cms_pages')->whereIn('id', $demoPageIds)->delete();
            }

            // Page ID 13 is seeded in default install seed: set active field to 0 when demo content is not installed/purged
            try {
                DB::table('cms_pages')->where('id', 13)->update(['is_active' => 0]);
            } catch (\Throwable $e) {
                // Fail silently if record no longer exists or table structure differs
            }

            // 12. Demo CMS Download (ID 1)
            DB::table('cms_downloads')->where('is_demo', 1)->orWhere('id', 1)->delete();

            // 13. Demo KB Articles & Categories
            $demoKbArtIds = DB::table('kb_articles')->where('is_demo', 1)->pluck('id')->toArray();
            if (!empty($demoKbArtIds)) {
                DB::table('kb_article_translations')->whereIn('kb_article_id', $demoKbArtIds)->delete();
            }
            DB::table('kb_articles')->where('is_demo', 1)->delete();

            $demoKbCatIds = DB::table('kb_categories')->where('is_demo', 1)->pluck('id')->toArray();
            if (!empty($demoKbCatIds)) {
                DB::table('kb_category_translations')->whereIn('kb_category_id', $demoKbCatIds)->delete();
            }
            DB::table('kb_categories')->where('is_demo', 1)->delete();

            Log::info('[DemoPurgeService] Successfully purged all demo content.');

        } finally {
            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
    }
}
