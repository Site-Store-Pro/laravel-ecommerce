<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\CmsPage;
use App\Models\CmsPageTranslation;
use App\Models\KbArticle;
use App\Models\KbArticleTranslation;
use App\Models\CmsTestimonial;
use App\Models\TestimonialTranslation;
use App\Models\CategoryTranslation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * LiveSearchService
 *
 * Centralises all live-search query and result-building logic so that both
 * the API endpoint (PluginApiController@liveSearchApi) and the full-page
 * results renderer (LiveSearchPlugin@renderResultsView) use identical,
 * translation-aware logic.
 *
 * Translation strategy (Bug 3):
 *   - When the active language IS the default → search base table fields only.
 *   - When the active language is NOT the default → first collect matching IDs
 *     from the relevant *_translations table (for that language_id), then UNION
 *     with base-field matches so records without a translation are still found.
 *
 * Display translation strategy (Bug 2):
 *   - After fetching records the service loads their translations in one batch
 *     query, then picks the translated title/snippet in preference to the
 *     default-language value.
 */
class LiveSearchService
{
    /**
     * Run the full multi-type search and return a flat array of result items.
     *
     * Each item has the shape used by both the inline dropdown and the full
     * results page:
     *   id, type, type_label, badge_class, title, snippet, url, image, icon_svg
     *
     * @param  string  $q             Raw search query (will be trimmed internally).
     * @param  int     $langId        The active language ID.
     * @param  int     $defaultLangId The site default language ID.
     * @return array
     */
    public function search(string $q, int $langId, int $defaultLangId): array
    {
        $q = trim($q);
        if (strlen($q) < 2) {
            return [];
        }

        $isDefault = ($langId === $defaultLangId);

        return array_merge(
            $this->searchCategories($q, $langId, $isDefault),
            $this->searchBrands($q),
            $this->searchProducts($q, $langId, $isDefault),
            $this->searchCmsPages($q, $langId, $isDefault),
            $this->searchKbArticles($q, $langId, $isDefault),
            $this->searchTestimonials($q, $langId, $isDefault),
        );
    }

    // ── Categories ───────────────────────────────────────────────────────────

    private function searchCategories(string $q, int $langId, bool $isDefault): array
    {
        if ($isDefault) {
            $categories = Category::where(function ($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('slug', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            })->limit(6)->get();

            $translations = collect();
        } else {
            // IDs that match via translated name/description
            $translatedIds = DB::table('category_translations')
                ->where('language_id', $langId)
                ->where(function ($t) use ($q) {
                    $t->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                })
                ->pluck('category_id')
                ->toArray();

            $categories = Category::where(function ($b) use ($q, $translatedIds) {
                $b->whereIn('id', $translatedIds)            // matched a translation
                  ->orWhere('name', 'like', "%{$q}%")        // or matches base name
                  ->orWhere('slug', 'like', "%{$q}%");
            })->limit(6)->get();

            $translations = CategoryTranslation::whereIn('category_id', $categories->pluck('id'))
                ->where('language_id', $langId)
                ->get()
                ->keyBy('category_id');
        }

        $items = [];
        foreach ($categories as $cat) {
            /** @var \App\Models\CategoryTranslation|null $tl */
            $tl      = $isDefault ? null : $translations->get($cat->id);
            $name    = $tl?->name        ?: $cat->name;
            $snippet = $tl?->description ?: ($cat->description ?? '');

            $items[] = [
                'id'          => $cat->id,
                'type'        => 'category',
                'type_label'  => siteLabel('live_search.type_category', 'Category'),
                'badge_class' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/60 dark:text-purple-200',
                'title'       => $name,
                'snippet'     => Str::limit(strip_tags($snippet ?: siteLabel('live_search.cat_snippet_prefix', 'Shop items in') . " {$name}"), 120),
                'url'         => route('shop.category', $cat->slug),
                'image'       => null,
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
            ];
        }

        return $items;
    }

    // ── Brands (no translation table — always use base fields) ───────────────

    private function searchBrands(string $q): array
    {
        $brands = Brand::where(function ($b) use ($q) {
            $b->where('name', 'like', "%{$q}%")
              ->orWhere('slug', 'like', "%{$q}%")
              ->orWhere('description', 'like', "%{$q}%");
        })->limit(5)->get();

        $items = [];
        foreach ($brands as $br) {
            $logoUrl = $br->brand_icon
                ? ($br->brand_logo_s3
                    ? Storage::disk('s3')->url($br->brand_icon)
                    : Storage::disk('public')->url($br->brand_icon))
                : null;

            $items[] = [
                'id'          => $br->id,
                'type'        => 'brand',
                'type_label'  => siteLabel('live_search.type_brand', 'Brand'),
                'badge_class' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/60 dark:text-violet-200',
                'title'       => $br->name,
                'snippet'     => Str::limit(strip_tags($br->description ?? siteLabel('live_search.brand_snippet_prefix', 'Explore') . " {$br->name} products"), 120),
                'url'         => route('shop.brand', $br->slug),
                'image'       => $logoUrl,
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
            ];
        }

        return $items;
    }

    // ── Products ─────────────────────────────────────────────────────────────

    private function searchProducts(string $q, int $langId, bool $isDefault): array
    {
        if ($isDefault) {
            $products = Product::with(['variants.images'])
                ->where('active', 1)
                ->where('show_in_results', 1)
                ->where(function ($b) use ($q) {
                    $b->where('title', 'like', "%{$q}%")
                      ->orWhere('product_search_index', 'like', "%{$q}%")
                      ->orWhere('short_description', 'like', "%{$q}%")
                      ->orWhere('long_description', 'like', "%{$q}%");
                })->limit(15)->get();

            $translations = collect();
        } else {
            $translatedIds = DB::table('product_translations')
                ->where('language_id', $langId)
                ->where(function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                      ->orWhere('short_description', 'like', "%{$q}%")
                      ->orWhere('long_description', 'like', "%{$q}%");
                })
                ->pluck('product_id')
                ->toArray();

            $products = Product::with(['variants.images'])
                ->where('active', 1)
                ->where('show_in_results', 1)
                ->where(function ($b) use ($q, $translatedIds) {
                    $b->whereIn('id', $translatedIds)
                      ->orWhere('title', 'like', "%{$q}%")
                      ->orWhere('product_search_index', 'like', "%{$q}%");
                })->limit(15)->get();

            $translations = ProductTranslation::whereIn('product_id', $products->pluck('id'))
                ->where('language_id', $langId)
                ->get()
                ->keyBy('product_id');
        }

        $items = [];
        foreach ($products as $p) {
            /** @var \App\Models\ProductTranslation|null $tl */
            $tl      = $isDefault ? null : $translations->get($p->id);
            $title   = $tl?->title             ?: $p->title;
            $snippet = $tl?->short_description ?: ($p->short_description ?: $p->long_description);

            $items[] = [
                'id'          => $p->id,
                'type'        => 'product',
                'type_label'  => siteLabel('live_search.type_product', 'Product'),
                'badge_class' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-200',
                'title'       => $title,
                'snippet'     => Str::limit(strip_tags($snippet ?? ''), 120),
                'url'         => route('shop.product', $p->seo_slug ?: $p->id),
                'image'       => $p->primaryThumbnailUrl(),
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
            ];
        }

        return $items;
    }

    // ── CMS Pages ─────────────────────────────────────────────────────────────

    private function searchCmsPages(string $q, int $langId, bool $isDefault): array
    {
        $base = CmsPage::where('is_active', true)
            ->where(function ($sub) {
                $sub->where('exclude_from_search', false)
                    ->orWhereNull('exclude_from_search');
            });

        if ($isDefault) {
            $pages = (clone $base)->where(function ($b) use ($q) {
                $b->where('title', 'like', "%{$q}%")
                  ->orWhere('cms_search_index', 'like', "%{$q}%")
                  ->orWhere('meta_description', 'like', "%{$q}%")
                  ->orWhere('content', 'like', "%{$q}%");
            })->limit(10)->get();

            $translations = collect();
        } else {
            $translatedIds = DB::table('cms_page_translations')
                ->where('language_id', $langId)
                ->where(function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%")
                      ->orWhere('meta_description', 'like', "%{$q}%");
                })
                ->pluck('cms_page_id')
                ->toArray();

            $pages = (clone $base)->where(function ($b) use ($q, $translatedIds) {
                $b->whereIn('id', $translatedIds)
                  ->orWhere('title', 'like', "%{$q}%")
                  ->orWhere('cms_search_index', 'like', "%{$q}%");
            })->limit(10)->get();

            $translations = CmsPageTranslation::whereIn('cms_page_id', $pages->pluck('id'))
                ->where('language_id', $langId)
                ->get()
                ->keyBy('cms_page_id');
        }

        $items = [];
        foreach ($pages as $pg) {
            /** @var \App\Models\CmsPageTranslation|null $tl */
            $tl     = $isDefault ? null : $translations->get($pg->id);
            $title  = $tl?->title ?: $pg->title;
            $excerpt = $tl?->meta_description
                ?: (!empty(trim($pg->meta_description ?? '')) ? $pg->meta_description : strip_tags($pg->content));

            $items[] = [
                'id'          => $pg->id,
                'type'        => 'page',
                'type_label'  => siteLabel('live_search.type_page', 'Site Page'),
                'badge_class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200',
                'title'       => $title,
                'snippet'     => Str::limit(strip_tags($excerpt ?? ''), 120),
                'url'         => url('/' . ltrim($pg->slug, '/')),
                'image'       => $pg->featuredImageUrl() ?: $pg->headerImageUrl(),
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
            ];
        }

        return $items;
    }

    // ── KB Articles ───────────────────────────────────────────────────────────

    private function searchKbArticles(string $q, int $langId, bool $isDefault): array
    {
        if ($isDefault) {
            $articles = KbArticle::where('article_active', 1)
                ->where(function ($b) use ($q) {
                    $b->where('title', 'like', "%{$q}%")
                      ->orWhere('article_content', 'like', "%{$q}%");
                })->limit(10)->get();

            $translations = collect();
        } else {
            $translatedIds = DB::table('kb_article_translations')
                ->where('language_id', $langId)
                ->where(function ($t) use ($q) {
                    $t->where('title', 'like', "%{$q}%")
                      ->orWhere('article_content', 'like', "%{$q}%");
                })
                ->pluck('kb_article_id')
                ->toArray();

            $articles = KbArticle::where('article_active', 1)
                ->where(function ($b) use ($q, $translatedIds) {
                    $b->whereIn('id', $translatedIds)
                      ->orWhere('title', 'like', "%{$q}%");
                })->limit(10)->get();

            $translations = KbArticleTranslation::whereIn('kb_article_id', $articles->pluck('id'))
                ->where('language_id', $langId)
                ->get()
                ->keyBy('kb_article_id');
        }

        $items = [];
        foreach ($articles as $art) {
            /** @var \App\Models\KbArticleTranslation|null $tl */
            $tl      = $isDefault ? null : $translations->get($art->id);
            $title   = $tl?->title          ?: $art->title;
            $content = $tl?->article_content ?: $art->article_content;

            $items[] = [
                'id'          => $art->id,
                'type'        => 'kb',
                'type_label'  => siteLabel('live_search.type_kb', 'Knowledge Base'),
                'badge_class' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/60 dark:text-sky-200',
                'title'       => $title,
                'snippet'     => Str::limit(strip_tags($content ?? ''), 120),
                'url'         => route('kb.show', $art->seo_link ?: $art->id),
                'image'       => null,
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
            ];
        }

        return $items;
    }

    // ── Testimonials ─────────────────────────────────────────────────────────

    private function searchTestimonials(string $q, int $langId, bool $isDefault): array
    {
        if ($isDefault) {
            $testimonials = CmsTestimonial::where('is_active', true)
                ->where(function ($b) use ($q) {
                    $b->where('author_name', 'like', "%{$q}%")
                      ->orWhere('company_name', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
                })->limit(8)->get();

            $translations = collect();
        } else {
            $translatedIds = DB::table('testimonial_translations')
                ->where('language_id', $langId)
                ->where(function ($t) use ($q) {
                    $t->where('content', 'like', "%{$q}%")
                      ->orWhere('author_title', 'like', "%{$q}%");
                })
                ->pluck('testimonial_id')
                ->toArray();

            $testimonials = CmsTestimonial::where('is_active', true)
                ->where(function ($b) use ($q, $translatedIds) {
                    $b->whereIn('id', $translatedIds)
                      ->orWhere('author_name', 'like', "%{$q}%")
                      ->orWhere('company_name', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
                })->limit(8)->get();

            $translations = TestimonialTranslation::whereIn('testimonial_id', $testimonials->pluck('id'))
                ->where('language_id', $langId)
                ->get()
                ->keyBy('testimonial_id');
        }

        $items = [];
        foreach ($testimonials as $t) {
            /** @var \App\Models\TestimonialTranslation|null $tl */
            $tl      = $isDefault ? null : $translations->get($t->id);
            $content = $tl?->content ?: $t->content;

            $items[] = [
                'id'          => $t->id,
                'type'        => 'testimonial',
                'type_label'  => siteLabel('live_search.type_testimonial', 'Testimonial'),
                'badge_class' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200',
                'title'       => $t->author_name . ($t->company_name ? " ({$t->company_name})" : ''),
                'snippet'     => Str::limit(strip_tags($content ?? ''), 120),
                'url'         => url('/#testimonials'),
                'image'       => $t->avatar_image,
                'icon_svg'    => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>',
            ];
        }

        return $items;
    }
}
