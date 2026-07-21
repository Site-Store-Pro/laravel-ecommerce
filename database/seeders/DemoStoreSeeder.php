<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DemoStoreSeeder
 *
 * Converts the legacy storedemo1.sql demo data into the new variant-based schema.
 * Includes: Brands, Categories, Products, Variants, Inventory, Images (CDN), Attributes, Cross-Sells.
 *
 * Run with:
 *   php artisan db:seed --class=DemoStoreSeeder
 *
 * Idempotent — existing slugs / SKUs are skipped.
 *
 * CDN base: https://d23w3zagfzgqcb.cloudfront.net/demo/
 * image_url_source = 3 → CDN URL overrides all other image sources.
 */
class DemoStoreSeeder extends Seeder
{
    private const CDN = 'https://d23w3zagfzgqcb.cloudfront.net/demo/';

    // ─── Legacy ID → New ID maps ──────────────────────────────────────────────
    private array $brandMap    = [];   // legacy ManufacturerID → new brand id
    private array $categoryMap = [];   // legacy ProdCatID     → new category id
    private array $productMap  = [];   // legacy ProdID        → new product id

    public function run(): void
    {
        $this->command->info('🌱 DemoStoreSeeder starting…');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->seedBrands();
        $this->seedCategories();
        $this->seedProducts();
        $this->seedCrossSells();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ DemoStoreSeeder complete.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BRANDS  (legacy: sitestorepro_manufacturers)
    // ─────────────────────────────────────────────────────────────────────────
    private function seedBrands(): void
    {
        $brands = [
            ['legacy_id' => 1, 'name' => 'Prestige Design',  'slug' => 'prestige-design',  'sort' => 5],
            ['legacy_id' => 2, 'name' => 'DeMarco',          'slug' => 'demarco',           'sort' => 3],
            ['legacy_id' => 3, 'name' => 'Old Heritage',     'slug' => 'old-heritage',      'sort' => 2],
            ['legacy_id' => 4, 'name' => 'Bella Luna',       'slug' => 'bella-luna',        'sort' => 4],
            ['legacy_id' => 5, 'name' => 'Excelsior',        'slug' => 'excelsior',         'sort' => 1],
        ];

        foreach ($brands as $b) {
            $existing = DB::table('product_brands')->where('slug', $b['slug'])->first();
            if ($existing) {
                $this->brandMap[$b['legacy_id']] = $existing->id;
                if (!$existing->is_demo) {
                    DB::table('product_brands')->where('id', $existing->id)->update(['is_demo' => 1]);
                }
                continue;
            }
            $id = DB::table('product_brands')->insertGetId([
                'name'       => $b['name'],
                'slug'       => $b['slug'],
                'sort_order' => $b['sort'],
                'is_demo'    => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->brandMap[$b['legacy_id']] = $id;
            $this->command->line("  Brand: {$b['name']}");
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // CATEGORIES  (legacy: sitestorepro_prodcategory)
    // ─────────────────────────────────────────────────────────────────────────
    private function seedCategories(): void
    {
        $cats = [
            ['legacy_id' =>  1, 'name' => 'Custom Jewelry',       'slug' => 'custom-jewelry',     'sort' => 1, 'parent' => null, 'desc' => 'Rings, necklaces, earrings and fine jewellery pieces.'],
            ['legacy_id' =>  2, 'name' => 'Watches',              'slug' => 'watches',             'sort' => 2, 'parent' => null, 'desc' => "Men's and Women's watches and time pieces."],
            ['legacy_id' =>  3, 'name' => 'Downloads & Media',    'slug' => 'downloads-media',     'sort' => 3, 'parent' => null, 'desc' => 'PDF downloads and on-demand media content.'],
            ['legacy_id' =>  5, 'name' => 'Gifts & Apparel',      'slug' => 'gifts-apparel',       'sort' => 5, 'parent' => null, 'desc' => 'Sweatshirts, mugs, apparel and gift items.'],
            ['legacy_id' =>  6, 'name' => 'Service Items',        'slug' => 'service-items',       'sort' => 4, 'parent' => null, 'desc' => 'Service-only items and professional engagements.'],
            ['legacy_id' =>  7, 'name' => 'Workshops & Seminars', 'slug' => 'workshops-seminars',  'sort' => 6, 'parent' => null, 'desc' => 'In-person and online workshops, seminars and training sessions.'],
            // Sub-categories
            ['legacy_id' => 11, 'name' => 'Rings',     'slug' => 'rings',     'sort' => 1, 'parent' => 1, 'desc' => 'Fine rings and bands.'],
            ['legacy_id' => 12, 'name' => 'Bracelets', 'slug' => 'bracelets', 'sort' => 2, 'parent' => 1, 'desc' => 'Diamond, gold and silver bracelets.'],
            ['legacy_id' => 13, 'name' => 'Necklaces', 'slug' => 'necklaces', 'sort' => 3, 'parent' => 1, 'desc' => 'Pendants and necklaces.'],
            ['legacy_id' => 14, 'name' => 'Earrings',  'slug' => 'earrings',  'sort' => 4, 'parent' => 1, 'desc' => 'Diamond and gemstone earrings.'],
        ];

        // First pass: top-level
        foreach ($cats as $c) {
            if ($c['parent'] !== null) continue;
            $existing = DB::table('product_categories')->where('slug', $c['slug'])->first();
            if ($existing) {
                $this->categoryMap[$c['legacy_id']] = $existing->id;
                if (!$existing->is_demo) {
                    DB::table('product_categories')->where('id', $existing->id)->update(['is_demo' => 1]);
                }
                continue;
            }
            $id = DB::table('product_categories')->insertGetId([
                'name'        => $c['name'],
                'slug'        => $c['slug'],
                'description' => $c['desc'] ?? null,
                'parent_id'   => null,
                'sort_order'  => $c['sort'],
                'is_demo'     => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $this->categoryMap[$c['legacy_id']] = $id;
            $this->command->line("  Category: {$c['name']}");
        }

        // Second pass: children
        foreach ($cats as $c) {
            if ($c['parent'] === null) continue;
            $existing = DB::table('product_categories')->where('slug', $c['slug'])->first();
            if ($existing) { 
                $this->categoryMap[$c['legacy_id']] = $existing->id;
                if (!$existing->is_demo) {
                    DB::table('product_categories')->where('id', $existing->id)->update(['is_demo' => 1]);
                }
                continue;
            }
            $id = DB::table('product_categories')->insertGetId([
                'name'        => $c['name'],
                'slug'        => $c['slug'],
                'description' => $c['desc'] ?? null,
                'parent_id'   => $this->categoryMap[$c['parent']] ?? null,
                'sort_order'  => $c['sort'],
                'is_demo'     => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $this->categoryMap[$c['legacy_id']] = $id;
            $this->command->line("  Sub-category: {$c['name']}");
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRODUCTS
    // ─────────────────────────────────────────────────────────────────────────
    private function seedProducts(): void
    {
        foreach ($this->getProducts() as $p) {
            $existing = DB::table('products')->where('seo_slug', $p['seo_slug'])->first();
            if ($existing) {
                $this->productMap[$p['legacy_id']] = $existing->id;
                // Ensure is_demo flag is set even if this product was seeded before the flag existed
                if (!$existing->is_demo) {
                    DB::table('products')->where('id', $existing->id)->update(['is_demo' => 1]);
                    $variantIds = DB::table('product_variants')->where('product_id', $existing->id)->pluck('id');
                    DB::table('product_variants')->where('product_id', $existing->id)->update(['is_demo' => 1]);
                    DB::table('product_images')->whereIn('variant_id', $variantIds)->update(['is_demo' => 1]);
                    DB::table('product_cross_selling')->where('product_id', $existing->id)->update(['is_demo' => 1]);
                }
                $this->command->line("  [SKIP] Product already exists: {$p['title']}");
                continue;
            }

            $brandId = isset($p['brand_legacy_id']) ? ($this->brandMap[$p['brand_legacy_id']] ?? null) : null;

            $productId = DB::table('products')->insertGetId([
                'title'             => $p['title'],
                'short_description' => $p['short_desc'] ?? null,
                'long_description'  => $p['long_desc']  ?? null,
                'brand_id'          => $brandId,
                'seo_slug'          => $p['seo_slug'],
                'meta_title'        => $p['title'],
                'meta_description'  => $p['meta_desc'] ?? null,
                'is_demo'           => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $this->productMap[$p['legacy_id']] = $productId;

            // Category assignments
            foreach (($p['category_legacy_ids'] ?? []) as $legacyCatId) {
                $catId = $this->categoryMap[$legacyCatId] ?? null;
                if ($catId) {
                    DB::table('product_categories_assignments')->insertOrIgnore([
                        'product_id'  => $productId,
                        'category_id' => $catId,
                    ]);
                }
            }

            // Variants + attributes
            $this->seedVariants($productId, $p);

            // Additional images (beyond the per-variant image)
            $this->seedAdditionalImages($productId, $p);

            // Attribute fields for multi-variant products
            $this->seedAttributes($productId, $p);

            $this->command->line("  ✔ Product: {$p['title']}");
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VARIANTS
    // ─────────────────────────────────────────────────────────────────────────
    private function seedVariants(int $productId, array $p): void
    {
        $variants = $p['variants'] ?? [];
        if (empty($variants)) {
            $variants = [[
                'sku'             => $p['sku'] ?? ('DEMO-' . $p['legacy_id']),
                'label'           => 'Standard',
                'public_price'    => $p['price']           ?? 0.00,
                'wholesale_price' => $p['wholesale_price'] ?? 0.00,
                'variant_fee'     => 0.00,
                'taxable'         => $p['taxable']         ?? 1,
                'qty'             => $p['qty']             ?? 25,
                'sort_order'      => 1,
            ]];
        }

        foreach ($variants as $idx => $v) {
            if (DB::table('product_variants')->where('sku', $v['sku'])->exists()) continue;

            $variantId = DB::table('product_variants')->insertGetId([
                'product_id'             => $productId,
                'sku'                    => $v['sku'],
                'attributes'             => $v['label'] ?? null,  // store label in attributes column
                'public_price'           => $v['public_price']    ?? 0.00,
                'wholesale_price'        => $v['wholesale_price'] ?? 0.00,
                'variant_fee'            => $v['variant_fee']     ?? 0.00,
                'wholesale_variant_fee'  => 0.00,
                'charge_tax'             => $v['taxable']         ?? 1,
                'personalization_active' => $v['personalization'] ?? 0,
                'personalization_fee'    => 0.00,
                'download_item'          => $v['download_item']   ?? 0,
                'is_event'               => $v['is_event']        ?? 0,
                'is_demo'                => 1,
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            // Inventory
            DB::table('products_inventory')->insertOrIgnore([
                'variant_id'            => $variantId,
                'quantity_available'    => $v['qty']      ?? 25,
                'warehouse_stock_level' => ($v['qty'] ?? 25) + ($v['reserved'] ?? 0),
                'reserved_stock'        => $v['reserved'] ?? 0,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            // Event details
            if (!empty($v['is_event']) && !empty($v['event_start'])) {
                DB::table('product_variant_events')->insertOrIgnore([
                    'variant_id'       => $variantId,
                    'event_start_date' => $v['event_start'],
                    'event_end_date'   => $v['event_end']      ?? null,
                    'event_label'      => $v['event_label']    ?? $v['label'],
                    'label_background' => $v['event_color']    ?? '#4f46e5',
                    'show_date'        => 1,
                    'event_location'   => $v['event_location'] ?? null,
                    'event_sort'       => $v['sort_order']     ?? 0,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }

            // Primary image for this variant — use CDN URL
            $imgFile = $v['image'] ?? ($idx === 0 ? ($p['image'] ?? null) : null);
            if ($imgFile) {
                $this->insertCdnImage($variantId, $imgFile, $v['image_alt'] ?? ($v['label'] ?? $p['title']), 1);
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ADDITIONAL IMAGES  (extra gallery images for a product — attached to first variant)
    // ─────────────────────────────────────────────────────────────────────────
    private function seedAdditionalImages(int $productId, array $p): void
    {
        if (empty($p['extra_images'])) return;

        // Get the first variant id for this product
        $firstVariant = DB::table('product_variants')
            ->where('product_id', $productId)
            ->orderBy('id')
            ->value('id');

        if (!$firstVariant) return;

        foreach ($p['extra_images'] as $idx => $img) {
            // Check if this CDN url already exists for this variant
            $cdnFull = self::CDN . $img['file'];
            $exists = DB::table('product_images')
                ->where('variant_id', $firstVariant)
                ->where('cdn_url', $cdnFull)
                ->exists();
            if ($exists) continue;

            $this->insertCdnImage($firstVariant, $img['file'], $img['alt'] ?? null, $img['sort'] ?? ($idx + 2));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ATTRIBUTES  — creates product_fields + product_field_options for multi-variant products
    // Infers attribute dimension (Size, Color, Tone, Session) from variant labels
    // ─────────────────────────────────────────────────────────────────────────
    private function seedAttributes(int $productId, array $p): void
    {
        $variants = $p['variants'] ?? [];
        if (count($variants) <= 1) return;  // single-variant products don't need attributes
        if (empty($p['attribute_label'])) return;  // no attribute label defined

        // Skip if attribute field already exists for this product
        $existing = DB::table('product_fields')
            ->where('product_id', $productId)
            ->where('label', $p['attribute_label'])
            ->exists();
        if ($existing) return;

        $fieldId = DB::table('product_fields')->insertGetId([
            'product_id' => $productId,
            'label'      => $p['attribute_label'],
            'field_type' => $p['attribute_type'] ?? 'select',
            'is_required'=> 1,
            'charge_tax' => 1,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($variants as $idx => $v) {
            $optionValue = $v['attribute_value'] ?? $v['label'];
            DB::table('product_field_options')->insert([
                'product_field_id'               => $fieldId,
                'option_value'                   => $optionValue,
                'option_price_modifier'          => 0.00,
                'option_wholesale_price_modifier' => 0.00,
                'sort_order'                     => $idx + 1,
                'created_at'                     => now(),
                'updated_at'                     => now(),
            ]);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER — insert a CDN image record
    // Populates thumbnail_path, main_path, and zoom_path with their respective
    // CDN subdirectory prefixes. image_url_source = 1.
    // ─────────────────────────────────────────────────────────────────────────
    private function insertCdnImage(int $variantId, string $filename, ?string $alt, int $sort): void
    {
        // Strip any full URL down to just the bare filename
        $bare = basename($filename);

        $thumbnailPath = 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/' . $bare;
        $mainPath      = 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/'      . $bare;
        $zoomPath      = 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/'      . $bare;

        // Also keep cdn_url pointing to the legacy demo path for any code
        // that still reads that column during transition.
        $cdnUrl = str_starts_with($filename, 'http') ? $filename : self::CDN . $bare;

        DB::table('product_images')->insertOrIgnore([
            'variant_id'       => $variantId,
            'thumbnail_path'   => $thumbnailPath,
            'main_path'        => $mainPath,
            'zoom_path'        => $zoomPath,
            'cdn_url'          => $cdnUrl,
            'image_url_source' => 1,
            'alt_label'        => $alt,
            'sort_order'       => $sort,
            'active'           => 1,
            'search_image'     => $sort === 1 ? 1 : 0,
            'is_demo'          => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }


    // ─────────────────────────────────────────────────────────────────────────
    // CROSS-SELLS  (legacy: sitestorepro_prod_crosssell)
    // ─────────────────────────────────────────────────────────────────────────
    private function seedCrossSells(): void
    {
        // [ProdID, CrossSellProdID, SortOrder, DisplayType]  (1=item view, 2=post-cart)
        $legacyCrossSells = [
            [1000001, 1000012, 1, 1], [1000001, 1000013, 2, 1], [1000001, 1000007, 3, 1], [1000001, 1000009, 4, 1],
            [1000002, 1000005, 1, 1], [1000002, 1000006, 2, 1], [1000002, 1000013, 3, 1], [1000002, 1000009, 4, 1],
            [1000003, 1000002, 1, 1], [1000003, 1000001, 2, 1],
            [1000004, 1000002, 1, 1],
            [1000006, 1000005, 1, 1], [1000006, 1000004, 2, 1], [1000006, 1000002, 3, 1], [1000006, 1000003, 4, 1],
            [1000008, 1000007, 1, 1], [1000008, 1000001, 2, 1],
            [1000009, 1000001, 1, 1], [1000009, 1000007, 2, 1], [1000009, 1000008, 3, 1],
            [1000011, 1000009, 2, 1], [1000011, 1000001, 3, 1], [1000011, 1000007, 4, 1],
            [1000012, 1000009, 1, 1], [1000012, 1000001, 2, 1],
            [1000012, 1000007, 1, 2], [1000012, 1000010, 2, 2],
            [1000013, 1000007, 1, 1], [1000013, 1000008, 2, 1], [1000013, 1000012, 3, 1],
            [1000013, 1000010, 1, 2],
            [1000016, 1000017, 1, 1],
            [1000017, 1000016, 1, 1],
            [1000025, 1000015, 1, 1],
            [1000015, 1000025, 1, 1], [1000015, 1000025, 1, 2],
            [1000016, 1000014, 0, 1],
        ];

        // Merge same product+cross-sell pair that appears with different display types
        $merged = [];
        foreach ($legacyCrossSells as [$pid, $csid, $order, $type]) {
            $key = "{$pid}_{$csid}";
            if (!isset($merged[$key])) {
                $merged[$key] = ['product_id' => $pid, 'cross_sell_id' => $csid, 'sort_order' => $order, 'item_view' => false, 'post_cart' => false];
            }
            if ($type === 1) $merged[$key]['item_view'] = true;
            if ($type === 2) $merged[$key]['post_cart']  = true;
            $merged[$key]['sort_order'] = max($merged[$key]['sort_order'], $order);
        }

        $inserted = 0;
        foreach ($merged as $entry) {
            $productId   = $this->productMap[$entry['product_id']]    ?? null;
            $crossSellId = $this->productMap[$entry['cross_sell_id']] ?? null;
            if (!$productId || !$crossSellId) continue;

            $exists = DB::table('product_cross_selling')
                ->where('product_id', $productId)
                ->where('cross_sell_product_id', $crossSellId)
                ->exists();
            if ($exists) continue;

            DB::table('product_cross_selling')->insert([
                'product_id'            => $productId,
                'cross_sell_product_id' => $crossSellId,
                'sort_order'            => $entry['sort_order'],
                'display_on_item_view'  => $entry['item_view'] ? 1 : 0,
                'display_on_post_cart'  => $entry['post_cart'] ? 1 : 0,
                'is_demo'               => 1,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
            $inserted++;
        }

        $this->command->line("  Cross-sells inserted: {$inserted}");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRODUCT DATA  (titles match storedemo1.sql exactly)
    // Images use CDN prefix: https://d23w3zagfzgqcb.cloudfront.net/demo/
    // ─────────────────────────────────────────────────────────────────────────
    private function getProducts(): array
    {
        return [

            // ── JEWELRY / BRACELETS ──────────────────────────────────────────

            [
                'legacy_id'           => 1000001,
                'title'               => '14k|24k 3 Ct Bracelet',
                'seo_slug'            => 'diamond-bracelet',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Beautiful diamond bracelet set in 14K white gold. A stunning gift for any occasion.',
                'long_desc'           => '<p>This exquisite diamond bracelet is crafted in 14K white gold and features brilliant-cut diamonds totalling 1/4 carat weight. The flexible link design makes it comfortable for everyday wear while maintaining an elegant look suitable for any occasion.</p><p>This item demonstrates the standard product layout with a QTY input field.</p>',
                'meta_desc'           => 'Shop our stunning diamond bracelet collection. Fine jewellery crafted in 14K white gold.',
                'image'               => '2021_1000001.png',
                'sku'                 => 'sample-sku-1000001',
                'price'               => 599.99,
                'wholesale_price'     => 540.00,
                'taxable'             => 1,
                'qty'                 => 15,
            ],

            [
                'legacy_id'           => 1000002,
                'title'               => 'Heart Of Sapphire Ring',
                'seo_slug'            => 'ladies-gold-bracelet',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Elegant heart-of-sapphire ring — a timeless piece for any wardrobe.',
                'long_desc'           => '<p>This elegant sapphire heart ring is crafted in 14K yellow gold with a secure prong setting. The classic design suits both casual and formal occasions.</p>',
                'meta_desc'           => 'Elegant 14K gold heart sapphire ring. Shop fine jewellery.',
                'image'               => '2021_sample_002.png',
                'sku'                 => 'sample-sku-1000002',
                'price'               => 449.99,
                'wholesale_price'     => 400.00,
                'taxable'             => 1,
                'qty'                 => 20,
            ],

            [
                'legacy_id'           => 1000003,
                'title'               => 'Diamond Mosaic Ring',
                'seo_slug'            => 'diamond-mosaic-ring',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 11],
                'short_desc'          => 'Diamond flower ring with amazing, intricate design. Set in 14K white gold. Platinum available.',
                'long_desc'           => '<p>Diamond flower ring with amazing, intricate design. Diamonds are set in 14K white gold. Platinum Available.</p><p>This item shows a standard layout with a QTY input field and standard layout (product image on the right side of the item view page).</p>',
                'meta_desc'           => 'Brilliant diamond mosaic ring. Make a great gift for that special someone!',
                'image'               => '2021_sample_1000003.png',
                'sku'                 => 'sample-sku-1000003',
                'price'               => 299.99,
                'wholesale_price'     => 265.00,
                'taxable'             => 1,
                'qty'                 => 10,
                'attribute_label'     => 'Ring Size',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000003-sz5',   'label' => 'Size 5',   'attribute_value' => '5',   'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 5, 'sort_order' => 1],
                    ['sku' => 'sample-sku-1000003-sz5.5', 'label' => 'Size 5.5', 'attribute_value' => '5.5', 'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 5, 'sort_order' => 2],
                    ['sku' => 'sample-sku-1000003-sz6',   'label' => 'Size 6',   'attribute_value' => '6',   'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 5, 'sort_order' => 3],
                    ['sku' => 'sample-sku-1000003-sz6.5', 'label' => 'Size 6.5', 'attribute_value' => '6.5', 'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 3, 'sort_order' => 4],
                    ['sku' => 'sample-sku-1000003-sz7',   'label' => 'Size 7',   'attribute_value' => '7',   'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 3, 'sort_order' => 5],
                    ['sku' => 'sample-sku-1000003-sz7.5', 'label' => 'Size 7.5', 'attribute_value' => '7.5', 'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 6],
                    ['sku' => 'sample-sku-1000003-sz8',   'label' => 'Size 8',   'attribute_value' => '8',   'public_price' => 299.99, 'wholesale_price' => 265.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 7],
                ],
            ],

            [
                'legacy_id'           => 1000004,
                'title'               => '14K Ring With Cultured Pearl And Diamonds',
                'seo_slug'            => '14k-ring-cultured-pearl-and-diamonds',
                'brand_legacy_id'     => 4,
                'category_legacy_ids' => [1, 11],
                'short_desc'          => 'Beautiful cultured pearl with 6 diamonds set in a shimmering 14K gold ring.',
                'long_desc'           => '<p>Beautiful cultured pearl with 6 diamonds set in a shimmering 14K gold ring.</p><p>This sample item shows how the product can be offered with personalization and gift wrap options.</p>',
                'meta_desc'           => '14K gold ring with cultured pearl and diamond accents.',
                'image'               => '2021_sample_004.png',
                'sku'                 => 'sample-sku-1000004',
                'price'               => 789.00,
                'wholesale_price'     => 780.00,
                'taxable'             => 1,
                'qty'                 => 8,
                'attribute_label'     => 'Ring Size',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000004-sz5',   'label' => 'Size 5',   'attribute_value' => '5',   'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 3, 'sort_order' => 1, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz5.5', 'label' => 'Size 5.5', 'attribute_value' => '5.5', 'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 2, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz6',   'label' => 'Size 6',   'attribute_value' => '6',   'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 3, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz6.5', 'label' => 'Size 6.5', 'attribute_value' => '6.5', 'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 1, 'sort_order' => 4, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz7',   'label' => 'Size 7',   'attribute_value' => '7',   'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 5, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz7.5', 'label' => 'Size 7.5', 'attribute_value' => '7.5', 'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 1, 'sort_order' => 6, 'personalization' => 1],
                    ['sku' => 'sample-sku-1000004-sz8',   'label' => 'Size 8',   'attribute_value' => '8',   'public_price' => 789.00, 'wholesale_price' => 780.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 7, 'personalization' => 1],
                ],
            ],

            [
                'legacy_id'           => 1000005,
                'title'               => 'Sapphire and Diamond Ring',
                'seo_slug'            => 'brilliant-diamond-solitaire-ring',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 11],
                'short_desc'          => 'Classic sapphire and diamond ring in 14K white gold.',
                'long_desc'           => '<p>A classic sapphire and diamond ring in 14K white gold. This timeless design showcases brilliant-cut stones in a prong setting.</p>',
                'meta_desc'           => 'Classic sapphire and diamond ring. Shop our fine jewellery collection.',
                'image'               => '2021_sample_1000005.png',
                'sku'                 => 'sample-sku-1000005',
                'price'               => 1299.99,
                'wholesale_price'     => 1150.00,
                'taxable'             => 1,
                'qty'                 => 5,
            ],

            [
                'legacy_id'           => 1000006,
                'title'               => 'Ruby and Diamond Ring with 14K Band',
                'seo_slug'            => 'emerald-cut-diamond-ring',
                'brand_legacy_id'     => 4,
                'category_legacy_ids' => [1, 11],
                'short_desc'          => 'Sophisticated ruby and diamond ring in a 14K gold band setting.',
                'long_desc'           => '<p>Sophisticated ruby and diamond ring featuring a vibrant centre stone in a 14K gold four-prong setting with channel-set diamond side stones.</p>',
                'meta_desc'           => 'Elegant ruby and diamond ring in 14K gold. Premium fine jewellery.',
                'image'               => '2021_1000006.png',
                'sku'                 => 'sample-sku-1000006',
                'price'               => 2499.99,
                'wholesale_price'     => 2200.00,
                'taxable'             => 1,
                'qty'                 => 3,
            ],

            [
                'legacy_id'           => 1000007,
                'title'               => 'Diamond Wave Bracelet',
                'seo_slug'            => 'pearl-necklace',
                'brand_legacy_id'     => 3,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Classic diamond wave bracelet — set in 14K white gold.',
                'long_desc'           => '<p>Classic diamond wave bracelet in 14K white gold. Features brilliant-cut diamonds in a flowing wave design. Available in multiple sizes.</p>',
                'meta_desc'           => 'Classic diamond wave bracelet. Fine jewellery for every occasion.',
                'image'               => '2021_1000007.png',
                'sku'                 => 'sample-sku-1000007',
                'price'               => 349.99,
                'wholesale_price'     => 300.00,
                'taxable'             => 1,
                'qty'                 => 12,
            ],

            [
                'legacy_id'           => 1000008,
                'title'               => 'Pinched Style Diamond Bracelet',
                'seo_slug'            => 'diamond-pendant-necklace',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Delicate pinched-style diamond bracelet in 14K white gold.',
                'long_desc'           => '<p>This delicate pinched-style diamond bracelet features brilliant-cut diamonds throughout in fine 14K white gold. A perfect gift for any special occasion.</p>',
                'meta_desc'           => 'Pinched style diamond bracelet in 14K white gold. Shop our fine jewellery collection.',
                'image'               => '2021_1000008.png',
                'sku'                 => 'sample-sku-1000008',
                'price'               => 499.99,
                'wholesale_price'     => 440.00,
                'taxable'             => 1,
                'qty'                 => 10,
            ],

            [
                'legacy_id'           => 1000009,
                'title'               => 'Diamond Heart Bracelet With Your Initials Inscribed',
                'seo_slug'            => 'diamond-stud-earrings',
                'brand_legacy_id'     => 1,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Diamond heart bracelet with custom initial inscription in 14K white gold.',
                'long_desc'           => '<p>Classic heart-design diamond bracelet in 14K white gold with custom initial inscription. Total diamond weight: 1/2 carat. Includes personalisation option at no extra charge.</p>',
                'meta_desc'           => 'Diamond heart bracelet with initials. 14K white gold. Shop fine jewellery.',
                'image'               => '2021_1000009.png',
                'sku'                 => 'sample-sku-1000009',
                'price'               => 799.99,
                'wholesale_price'     => 720.00,
                'taxable'             => 1,
                'qty'                 => 15,
            ],

            [
                'legacy_id'           => 1000010,
                'title'               => '14k Or 24K White Gold 2 Carat Diamond Bracelet',
                'seo_slug'            => 'sapphire-diamond-earrings',
                'brand_legacy_id'     => 4,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => '2 carat diamond bracelet available in 14K or 24K white gold.',
                'long_desc'           => '<p>These stunning diamond bracelets feature 2 carats of brilliant-cut diamonds. Available in your choice of 14K or 24K white gold settings.</p>',
                'meta_desc'           => '2 carat diamond bracelet in 14K or 24K white gold. Fine jewellery.',
                'image'               => '2021_1000010.png',
                'sku'                 => 'sample-sku-1000010',
                'price'               => 1199.99,
                'wholesale_price'     => 1050.00,
                'taxable'             => 1,
                'qty'                 => 6,
            ],

            // ── WATCHES ──────────────────────────────────────────────────────

            [
                'legacy_id'           => 1000011,
                'title'               => '18k Gold 5 Carat GIA Certified Diamond Bracelet',
                'seo_slug'            => 'mens-titanium-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => '18K gold bracelet featuring 5 carats of GIA certified diamonds.',
                'long_desc'           => '<p>This premium 18K gold bracelet features 5 carats of GIA certified brilliant-cut diamonds. A statement piece for the discerning collector.</p>',
                'meta_desc'           => '18K gold 5 carat GIA certified diamond bracelet. Fine jewellery.',
                'image'               => '2021_1000011.png',
                'sku'                 => 'sample-sku-1000011',
                'price'               => 899.99,
                'wholesale_price'     => 800.00,
                'taxable'             => 1,
                'qty'                 => 8,
            ],

            [
                'legacy_id'           => 1000012,
                'title'               => 'Ruby and Diamond Bracelet',
                'seo_slug'            => 'ladies-diamond-watch',
                'brand_legacy_id'     => 4,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Elegant ruby and diamond bracelet in gold and silver tone options.',
                'long_desc'           => '<p>This elegant ruby and diamond bracelet features vivid rubies interspersed with brilliant-cut diamonds. Available in gold tone, silver tone, and rose gold.</p>',
                'meta_desc'           => 'Ruby and diamond bracelet. Elegant fine jewellery.',
                'image'               => '2021_1000012.png',
                'sku'                 => 'sample-sku-1000012',
                'price'               => 1299.99,
                'wholesale_price'     => 1150.00,
                'taxable'             => 1,
                'qty'                 => 5,
                'attribute_label'     => 'Tone',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000012-gold',   'label' => 'Gold Tone',   'attribute_value' => 'Gold Tone',   'public_price' => 1299.99, 'wholesale_price' => 1150.00, 'variant_fee' => 0.00,   'taxable' => 1, 'qty' => 3, 'sort_order' => 1],
                    ['sku' => 'sample-sku-1000012-silver',  'label' => 'Silver Tone', 'attribute_value' => 'Silver Tone', 'public_price' => 1299.99, 'wholesale_price' => 1150.00, 'variant_fee' => 0.00,   'taxable' => 1, 'qty' => 3, 'sort_order' => 2],
                    ['sku' => 'sample-sku-1000012-rose',    'label' => 'Rose Gold',   'attribute_value' => 'Rose Gold',   'public_price' => 1399.99, 'wholesale_price' => 1250.00, 'variant_fee' => 100.00, 'taxable' => 1, 'qty' => 2, 'sort_order' => 3],
                ],
            ],

            [
                'legacy_id'           => 1000013,
                'title'               => 'Sapphire, Ruby And Emerald Bracelet',
                'seo_slug'            => 'mens-dress-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [1, 12],
                'short_desc'          => 'Stunning multistone bracelet featuring sapphires, rubies and emeralds.',
                'long_desc'           => '<p>A stunning multistone bracelet featuring vivid blue sapphires, deep red rubies, and lush green emeralds set in fine gold. A bold statement piece.</p>',
                'meta_desc'           => 'Sapphire, ruby and emerald bracelet. Fine jewellery.',
                'image'               => '2021_sample_1000013.png',
                'sku'                 => 'sample-sku-1000013',
                'price'               => 1599.99,
                'wholesale_price'     => 1400.00,
                'taxable'             => 1,
                'qty'                 => 4,
            ],

            // ── DOWNLOADS & MEDIA ────────────────────────────────────────────

            [
                'legacy_id'           => 1000014,
                'title'               => 'Jewelry Cleaning eBOOK',
                'seo_slug'            => 'jewellery-care-guide-pdf',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [3],
                'short_desc'          => 'Downloadable eBook guide on cleaning and caring for your fine jewellery. Instant digital delivery.',
                'long_desc'           => '<p>Our comprehensive jewellery cleaning eBook covers cleaning techniques, storage recommendations, and maintenance tips for diamonds, pearls, gold and silver. Instant digital download after purchase.</p>',
                'meta_desc'           => 'Download our jewellery cleaning eBook. Instant digital delivery.',
                'image'               => '2021_1000014.png',
                'sku'                 => 'sample-sku-1000014',
                'price'               => 4.99,
                'wholesale_price'     => 0.00,
                'taxable'             => 0,
                'qty'                 => 999,
                'extra_images'        => [
                    ['file' => 'jewelry_cleaning_101.png',   'alt' => 'Jewelry Cleaning 101',   'sort' => 2],
                    ['file' => 'jewelry_cleaning_101-b.png', 'alt' => 'Jewelry Cleaning Guide', 'sort' => 3],
                    ['file' => 'jewelry_cleaning_101-c.png', 'alt' => 'Jewelry Care Tips',      'sort' => 4],
                ],
                'variants'            => [
                    ['sku' => 'sample-sku-1000014-dl', 'label' => 'PDF Download', 'public_price' => 4.99, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 1, 'download_item' => 1],
                ],
            ],

            [
                'legacy_id'           => 1000016,
                'title'               => 'Jewelry Repair Webinar Plus eBook',
                'seo_slug'            => 'jewelry-repair-webinar-ebook',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [3],
                'short_desc'          => 'Online webinar plus comprehensive eBook on jewellery repair techniques.',
                'long_desc'           => '<p>A comprehensive webinar and eBook bundle covering jewellery repair techniques for gold, silver, and gemstone settings. Instant digital access.</p>',
                'meta_desc'           => 'Jewellery repair webinar plus eBook. Digital download bundle.',
                'image'               => '1000016-sample-3.png',
                'sku'                 => 'sample-sku-1000016',
                'price'               => 29.99,
                'wholesale_price'     => 0.00,
                'taxable'             => 0,
                'qty'                 => 999,
                'extra_images'        => [
                    ['file' => '1000016-sample-4.png', 'alt' => 'Jewelry Repair Guide',  'sort' => 2],
                    ['file' => '1000016-sample-2.png', 'alt' => 'Jewelry Repair Webinar','sort' => 3],
                    ['file' => '1000016-sample-1.png', 'alt' => 'Jewelry Repair Bundle', 'sort' => 4],
                ],
                'variants'            => [
                    ['sku' => 'sample-sku-1000016-dl', 'label' => 'Webinar + eBook Bundle', 'public_price' => 29.99, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 1, 'download_item' => 1],
                ],
            ],

            [
                'legacy_id'           => 1000017,
                'title'               => 'Jewelry Accessorizing ONLINE Webinar',
                'seo_slug'            => 'jewelry-accessorizing-online-webinar',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [3],
                'short_desc'          => 'Online webinar on jewellery accessorizing and styling techniques.',
                'long_desc'           => '<p>Learn professional jewellery accessorizing and styling techniques in this online webinar. Covers pairing jewellery with outfits, occasion dressing, and trend guidance.</p>',
                'meta_desc'           => 'Jewellery accessorizing online webinar. Digital download.',
                'image'               => '2021_1000017.png',
                'sku'                 => 'sample-sku-1000017',
                'price'               => 19.99,
                'wholesale_price'     => 0.00,
                'taxable'             => 0,
                'qty'                 => 999,
                'variants'            => [
                    ['sku' => 'sample-sku-1000017-dl', 'label' => 'Online Webinar', 'public_price' => 19.99, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 1, 'download_item' => 1],
                ],
            ],

            // ── GIFTS & APPAREL ───────────────────────────────────────────────

            [
                'legacy_id'           => 1000015,
                'title'               => "Men's Titanium Watch",
                'seo_slug'            => 'prestige-design-sweatshirt',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [2, 5],
                'short_desc'          => "Rugged men's titanium sport watch. Premium heavyweight sweatshirt also available.",
                'long_desc'           => '<p>Premium merchandise available in Black, Burgundy and White in sizes Small through XXL. Machine washable.</p>',
                'meta_desc'           => "Men's titanium watch and premium sweatshirt. Multiple colours and sizes.",
                'image'               => '2021_burgundy sweatshirt.png',
                'sku'                 => 'sample-sku-1000015',
                'price'               => 49.99,
                'wholesale_price'     => 38.00,
                'taxable'             => 1,
                'qty'                 => 100,
                'extra_images'        => [
                    ['file' => '2021_sweatshirt_group.png', 'alt' => 'Sweatshirt Group', 'sort' => 2],
                ],
                'attribute_label'     => 'Colour / Size',
                'attribute_type'      => 'select',
                'variants'            => [
                    // Black
                    ['sku' => 'sample-sku-1000015-black-small',  'label' => 'Black / Small',   'attribute_value' => 'Black / Small',   'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 24, 'reserved' => 5,  'sort_order' => 1,  'image' => '2021_black_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-black-medium', 'label' => 'Black / Medium',  'attribute_value' => 'Black / Medium',  'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 24, 'reserved' => 5,  'sort_order' => 2,  'image' => '2021_black_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-black-large',  'label' => 'Black / Large',   'attribute_value' => 'Black / Large',   'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 24, 'reserved' => 5,  'sort_order' => 3,  'image' => '2021_black_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-black-xl',     'label' => 'Black / XL',      'attribute_value' => 'Black / XL',      'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 13, 'reserved' => 11, 'sort_order' => 4,  'image' => '2021_black_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-black-xxl',    'label' => 'Black / XXL',     'attribute_value' => 'Black / XXL',     'public_price' => 54.99, 'wholesale_price' => 42.00, 'variant_fee' => 5.00, 'taxable' => 1, 'qty' => 10, 'reserved' => 14, 'sort_order' => 5,  'image' => '2021_black_sweatshirt.png'],
                    // Burgundy
                    ['sku' => 'sample-sku-1000015-small-burgundy',  'label' => 'Burgundy / Small',  'attribute_value' => 'Burgundy / Small',  'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 14, 'reserved' => 10, 'sort_order' => 6,  'image' => '2021_burgundy sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-burgundy-medium', 'label' => 'Burgundy / Medium', 'attribute_value' => 'Burgundy / Medium', 'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 21, 'reserved' => 3,  'sort_order' => 7,  'image' => '2021_burgundy sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-large-burgundy',  'label' => 'Burgundy / Large',  'attribute_value' => 'Burgundy / Large',  'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 24, 'reserved' => 0,  'sort_order' => 8,  'image' => '2021_burgundy sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-burgungy-xl',     'label' => 'Burgundy / XL',     'attribute_value' => 'Burgundy / XL',     'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 2,  'reserved' => 22, 'sort_order' => 9,  'image' => '2021_burgundy sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-burgundy-xxl',    'label' => 'Burgundy / XXL',    'attribute_value' => 'Burgundy / XXL',    'public_price' => 54.99, 'wholesale_price' => 42.00, 'variant_fee' => 5.00, 'taxable' => 1, 'qty' => 11, 'reserved' => 13, 'sort_order' => 10, 'image' => '2021_burgundy sweatshirt.png'],
                    // White
                    ['sku' => 'sample-sku-1000015-small-white',  'label' => 'White / Small',  'attribute_value' => 'White / Small',  'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 24, 'reserved' => 0,  'sort_order' => 11, 'image' => '2021_white_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-white-medium', 'label' => 'White / Medium', 'attribute_value' => 'White / Medium', 'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 15, 'reserved' => 9,  'sort_order' => 12, 'image' => '2021_white_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-large-white',  'label' => 'White / Large',  'attribute_value' => 'White / Large',  'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 6,  'reserved' => 18, 'sort_order' => 13, 'image' => '2021_white_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-white-xl',     'label' => 'White / XL',     'attribute_value' => 'White / XL',     'public_price' => 49.99, 'wholesale_price' => 38.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'reserved' => 4,  'sort_order' => 14, 'image' => '2021_white_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000015-white-xxl',    'label' => 'White / XXL',    'attribute_value' => 'White / XXL',    'public_price' => 54.99, 'wholesale_price' => 42.00, 'variant_fee' => 5.00, 'taxable' => 1, 'qty' => 7,  'reserved' => 17, 'sort_order' => 15, 'image' => '2021_white_sweatshirt.png'],
                ],
            ],

            [
                'legacy_id'           => 1000019,
                'title'               => 'Vintage Pocket Watch',
                'seo_slug'            => 'vintage-pocket-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [2],
                'short_desc'          => 'Classic vintage pocket watch with engraved casing.',
                'long_desc'           => '<p>A beautifully crafted vintage-style pocket watch with intricate engraved casing and roman numeral dial. A perfect gift for collectors.</p>',
                'meta_desc'           => 'Vintage pocket watch. Classic engraved casing.',
                'image'               => '2021_vintage_sample.png',
                'sku'                 => 'sample-sku-1000019',
                'price'               => 129.99,
                'wholesale_price'     => 95.00,
                'taxable'             => 1,
                'qty'                 => 12,
            ],

            [
                'legacy_id'           => 1000020,
                'title'               => 'Fashion Wrist Watch',
                'seo_slug'            => 'fashion-wrist-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [2],
                'short_desc'          => 'Modern fashion wrist watch available in multiple strap colours.',
                'long_desc'           => '<p>Sleek modern fashion wrist watch with stainless steel case and interchangeable straps. Available in black, brown, and white.</p>',
                'meta_desc'           => 'Fashion wrist watch. Multiple strap options.',
                'image'               => 'watch.png',
                'sku'                 => 'sample-sku-1000020',
                'price'               => 89.99,
                'wholesale_price'     => 55.00,
                'taxable'             => 1,
                'qty'                 => 30,
                'attribute_label'     => 'Strap Colour',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000020-black', 'label' => 'Black Strap', 'attribute_value' => 'Black', 'public_price' => 89.99, 'wholesale_price' => 55.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 10, 'sort_order' => 1, 'image' => 'watch_black.png'],
                    ['sku' => 'sample-sku-1000020-brown', 'label' => 'Brown Strap', 'attribute_value' => 'Brown', 'public_price' => 89.99, 'wholesale_price' => 55.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 10, 'sort_order' => 2, 'image' => 'watch_brown.png'],
                    ['sku' => 'sample-sku-1000020-white', 'label' => 'White Strap', 'attribute_value' => 'White', 'public_price' => 89.99, 'wholesale_price' => 55.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 10, 'sort_order' => 3, 'image' => 'watch.png'],
                ],
            ],

            [
                'legacy_id'           => 1000021,
                'title'               => 'Premium Office Pens 2 Pack',
                'seo_slug'            => 'premium-office-pens-2-pack',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [5],
                'short_desc'          => 'Premium gift-boxed office pens — set of 2 with engraving option.',
                'long_desc'           => '<p>Premium ballpoint pens in a gift-ready box. Available with optional engraving. Makes a great corporate or personal gift.</p>',
                'meta_desc'           => 'Premium office pens 2 pack. Gift box included.',
                'image'               => 'sample_pens.png',
                'sku'                 => 'sample-sku-1000021',
                'price'               => 24.99,
                'wholesale_price'     => 15.00,
                'taxable'             => 1,
                'qty'                 => 50,
            ],

            [
                'legacy_id'           => 1000022,
                'title'               => 'Silver Jewelry Box',
                'seo_slug'            => 'silver-jewelry-box',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [5],
                'short_desc'          => 'Elegant silver-toned jewellery box with velvet lining.',
                'long_desc'           => '<p>An elegant silver-toned jewellery box with soft velvet lining, multiple compartments, and a lockable lid. Perfect for organising and displaying your jewellery collection.</p>',
                'meta_desc'           => 'Silver jewellery box with velvet lining. Elegant storage solution.',
                'image'               => '2021_gift_box.png',
                'sku'                 => 'sample-sku-1000022',
                'price'               => 39.99,
                'wholesale_price'     => 22.00,
                'taxable'             => 1,
                'qty'                 => 25,
            ],

            [
                'legacy_id'           => 1000023,
                'title'               => 'Modern Pocket Watch',
                'seo_slug'            => 'modern-pocket-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [2],
                'short_desc'          => 'Modern pocket watch with sleek minimalist design.',
                'long_desc'           => '<p>A modern minimalist pocket watch with clean dial and stainless steel casing. A contemporary twist on a classic accessory.</p>',
                'meta_desc'           => 'Modern pocket watch. Sleek minimalist design.',
                'image'               => '2021_pocket_modern.png',
                'sku'                 => 'sample-sku-1000023',
                'price'               => 59.99,
                'wholesale_price'     => 35.00,
                'taxable'             => 1,
                'qty'                 => 15,
            ],

            [
                'legacy_id'           => 1000024,
                'title'               => 'Modern Wrist Watch',
                'seo_slug'            => 'modern-wrist-watch',
                'brand_legacy_id'     => 5,
                'category_legacy_ids' => [2],
                'short_desc'          => 'Modern minimalist wrist watch for men and women.',
                'long_desc'           => '<p>A modern minimalist wrist watch with slim profile, clean dial, and genuine leather strap. Suitable for men and women.</p>',
                'meta_desc'           => 'Modern wrist watch. Minimalist design for men and women.',
                'image'               => '2021_modern_watch_sample.png',
                'sku'                 => 'sample-sku-1000024',
                'price'               => 79.99,
                'wholesale_price'     => 45.00,
                'taxable'             => 1,
                'qty'                 => 20,
            ],

            [
                'legacy_id'           => 1000025,
                'title'               => "Men's T-Shirt",
                'seo_slug'            => 'mens-t-shirt',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [5],
                'short_desc'          => "Premium men's t-shirt. Available in 6 colours.",
                'long_desc'           => '<p>Premium 100% cotton men\'s t-shirt available in Brown, Gray, Green, Navy, Orange, and Royal Blue. Sizes S–XXL.</p>',
                'meta_desc'           => "Men's t-shirt. Premium cotton, multiple colours.",
                'image'               => 'brown.png',
                'sku'                 => 'sample-sku-1000025',
                'price'               => 24.99,
                'wholesale_price'     => 12.00,
                'taxable'             => 1,
                'qty'                 => 120,
                'attribute_label'     => 'Colour',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000025-brown',  'label' => 'Brown',      'attribute_value' => 'Brown',      'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 1, 'image' => 'brown.png'],
                    ['sku' => 'sample-sku-1000025-gray',   'label' => 'Gray',       'attribute_value' => 'Gray',       'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 2, 'image' => 'gray.png'],
                    ['sku' => 'sample-sku-1000025-green',  'label' => 'Green',      'attribute_value' => 'Green',      'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 3, 'image' => 'green.png'],
                    ['sku' => 'sample-sku-1000025-navy',   'label' => 'Navy',       'attribute_value' => 'Navy',       'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 4, 'image' => 'navy.png'],
                    ['sku' => 'sample-sku-1000025-orange', 'label' => 'Orange',     'attribute_value' => 'Orange',     'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 5, 'image' => 'orange.png'],
                    ['sku' => 'sample-sku-1000025-royal',  'label' => 'Royal Blue', 'attribute_value' => 'Royal Blue', 'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 6, 'image' => 'royal.png'],
                ],
            ],

            [
                'legacy_id'           => 1000026,
                'title'               => "Women's T-Shirt",
                'seo_slug'            => 'womens-t-shirt',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [5],
                'short_desc'          => "Premium women's t-shirt. Available in 6 colours.",
                'long_desc'           => '<p>Premium 100% cotton women\'s fitted t-shirt available in Brown, Gray, Green, Navy, Orange, and Royal Blue. Sizes S–XXL.</p>',
                'meta_desc'           => "Women's t-shirt. Premium cotton, multiple colours.",
                'image'               => 'brown.png',
                'sku'                 => 'sample-sku-1000026',
                'price'               => 24.99,
                'wholesale_price'     => 12.00,
                'taxable'             => 1,
                'qty'                 => 120,
                'attribute_label'     => 'Colour',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000026-brown',  'label' => 'Brown',      'attribute_value' => 'Brown',      'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 1, 'image' => 'brown.png'],
                    ['sku' => 'sample-sku-1000026-gray',   'label' => 'Gray',       'attribute_value' => 'Gray',       'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 2, 'image' => 'gray.png'],
                    ['sku' => 'sample-sku-1000026-green',  'label' => 'Green',      'attribute_value' => 'Green',      'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 3, 'image' => 'green.png'],
                    ['sku' => 'sample-sku-1000026-navy',   'label' => 'Navy',       'attribute_value' => 'Navy',       'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 4, 'image' => 'navy.png'],
                    ['sku' => 'sample-sku-1000026-orange', 'label' => 'Orange',     'attribute_value' => 'Orange',     'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 5, 'image' => 'orange.png'],
                    ['sku' => 'sample-sku-1000026-royal',  'label' => 'Royal Blue', 'attribute_value' => 'Royal Blue', 'public_price' => 24.99, 'wholesale_price' => 12.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 20, 'sort_order' => 6, 'image' => 'royal.png'],
                ],
            ],

            [
                'legacy_id'           => 1000027,
                'title'               => "Women's Sweatshirt",
                'seo_slug'            => 'custom-baseball-cap',
                'brand_legacy_id'     => 2,
                'category_legacy_ids' => [5],
                'short_desc'          => "Women's sweatshirt available in Red, White, and Black.",
                'long_desc'           => '<p>Premium women\'s sweatshirt available in Red, White, and Black. Adjustable fit. Machine washable.</p>',
                'meta_desc'           => "Women's sweatshirt. Multiple colours available.",
                'image'               => '2021_sweatshirt_group_women.png',
                'sku'                 => 'sample-sku-1000027',
                'price'               => 44.99,
                'wholesale_price'     => 28.00,
                'taxable'             => 1,
                'qty'                 => 95,
                'attribute_label'     => 'Colour',
                'attribute_type'      => 'select',
                'variants'            => [
                    ['sku' => 'sample-sku-1000027-red',   'label' => 'Red',   'attribute_value' => 'Red',   'public_price' => 44.99, 'wholesale_price' => 28.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 35, 'sort_order' => 1, 'image' => '2021_red_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000027-white', 'label' => 'White', 'attribute_value' => 'White', 'public_price' => 44.99, 'wholesale_price' => 28.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 30, 'sort_order' => 2, 'image' => '2021_white_sweatshirt.png'],
                    ['sku' => 'sample-sku-1000027-black', 'label' => 'Black', 'attribute_value' => 'Black', 'public_price' => 44.99, 'wholesale_price' => 28.00, 'variant_fee' => 0.00, 'taxable' => 1, 'qty' => 30, 'sort_order' => 3, 'image' => '2021_black_sweatshirt.png'],
                ],
            ],

            // ── SERVICE ITEMS ─────────────────────────────────────────────────

            [
                'legacy_id'           => 1000029,
                'title'               => 'Product Builder Example',
                'seo_slug'            => 'web-design-consultation',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [6],
                'short_desc'          => 'One-hour web design strategy consultation with a senior designer. Book your session.',
                'long_desc'           => '<p>Book a one-on-one strategy session with one of our senior web designers. We\'ll review your current online presence, identify opportunities for improvement and provide an actionable roadmap.</p>',
                'meta_desc'           => 'Book a web design consultation. One-on-one strategy session.',
                'image'               => 'product-builder-demo.png',
                'sku'                 => 'builder-example',
                'price'               => 150.00,
                'wholesale_price'     => 0.00,
                'taxable'             => 0,
                'qty'                 => 2,
                'variants'            => [
                    ['sku' => 'builder-example-1hr', 'label' => '1-Hour Session', 'public_price' => 150.00, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 2, 'sort_order' => 1],
                    ['sku' => 'builder-example-2hr', 'label' => '2-Hour Session', 'public_price' => 275.00, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 2, 'sort_order' => 2],
                ],
            ],

            [
                'legacy_id'           => 1000030,
                'title'               => 'Donation | "Make An Offer" Example',
                'seo_slug'            => 'charitable-donation',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [6],
                'short_desc'          => 'Make a charitable donation of any amount to support our community programs.',
                'long_desc'           => '<p>Your generous donation helps support local community programs, youth initiatives and educational scholarships. All donations are tax-deductible. A receipt will be emailed to you automatically upon purchase.</p>',
                'meta_desc'           => 'Make a charitable donation. Support community programs and education.',
                'image'               => 'donate-demo.png',
                'sku'                 => 'donation-example',
                'price'               => 25.00,
                'wholesale_price'     => 0.00,
                'taxable'             => 0,
                'qty'                 => 997,
                'variants'            => [
                    ['sku' => 'donation-example-25',  'label' => '$25 Donation',  'public_price' => 25.00,  'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 1],
                    ['sku' => 'donation-example-50',  'label' => '$50 Donation',  'public_price' => 50.00,  'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 2],
                    ['sku' => 'donation-example-100', 'label' => '$100 Donation', 'public_price' => 100.00, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 3],
                    ['sku' => 'donation-example-250', 'label' => '$250 Donation', 'public_price' => 250.00, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 4],
                    ['sku' => 'donation-example-500', 'label' => '$500 Donation', 'public_price' => 500.00, 'wholesale_price' => 0.00, 'variant_fee' => 0.00, 'taxable' => 0, 'qty' => 999, 'sort_order' => 5],
                ],
            ],

            // ── WORKSHOPS & SEMINARS (Events) ─────────────────────────────────

            [
                'legacy_id'           => 1000034,
                'title'               => 'Digital Marketing Seminar',
                'seo_slug'            => 'digital-marketing-seminar',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Full-day digital marketing seminar covering SEO, social media, email marketing and paid advertising. Multiple sessions available.',
                'long_desc'           => '<p>Join our expert-led digital marketing seminar for a full-day intensive covering the latest strategies in SEO, social media marketing, email campaigns and paid advertising. Suitable for business owners and marketing professionals.</p><p>Three session times available — select the time that works best for you at checkout.</p>',
                'meta_desc'           => 'Digital marketing seminar. Full-day training on SEO, social media, email and paid ads.',
                'image'               => 'event-sample-9.png',
                'sku'                 => 'digital-marketing-seminar',
                'price'               => 199.00,
                'wholesale_price'     => 150.00,
                'taxable'             => 0,
                'qty'                 => 20,
                'attribute_label'     => 'Session',
                'attribute_type'      => 'select',
                'variants'            => [
                    [
                        'sku'             => '1st Session : 9 AM',
                        'label'           => '1st Session — 9 AM',
                        'attribute_value' => '9 AM Session',
                        'public_price'    => 199.00,
                        'wholesale_price' => 150.00,
                        'variant_fee'     => 0.00,
                        'taxable'         => 0,
                        'qty'             => 2,
                        'reserved'        => 4,
                        'sort_order'      => 1,
                        'is_event'        => 1,
                        'event_label'     => 'Digital Marketing Seminar — 9 AM',
                        'event_start'     => '2026-09-16 09:00:00',
                        'event_end'       => '2026-09-16 12:00:00',
                        'event_color'     => '#4f46e5',
                        'event_location'  => 'Main Conference Hall, Room 101',
                    ],
                    [
                        'sku'             => '2nd Session : 11 AM',
                        'label'           => '2nd Session — 11 AM',
                        'attribute_value' => '11 AM Session',
                        'public_price'    => 199.00,
                        'wholesale_price' => 150.00,
                        'variant_fee'     => 0.00,
                        'taxable'         => 0,
                        'qty'             => 14,
                        'reserved'        => 5,
                        'sort_order'      => 2,
                        'is_event'        => 1,
                        'event_label'     => 'Digital Marketing Seminar — 11 AM',
                        'event_start'     => '2026-09-16 11:00:00',
                        'event_end'       => '2026-09-16 14:00:00',
                        'event_color'     => '#4f46e5',
                        'event_location'  => 'Main Conference Hall, Room 101',
                    ],
                    [
                        'sku'             => '3rd Session : 2 PM (+$10.00)',
                        'label'           => '3rd Session — 2 PM (+$10.00)',
                        'attribute_value' => '2 PM Session (+$10)',
                        'public_price'    => 209.00,
                        'wholesale_price' => 160.00,
                        'variant_fee'     => 10.00,
                        'taxable'         => 0,
                        'qty'             => 0,
                        'reserved'        => 5,
                        'sort_order'      => 3,
                        'is_event'        => 1,
                        'event_label'     => 'Digital Marketing Seminar — 2 PM',
                        'event_start'     => '2026-09-16 14:00:00',
                        'event_end'       => '2026-09-16 17:00:00',
                        'event_color'     => '#4f46e5',
                        'event_location'  => 'Main Conference Hall, Room 101',
                    ],
                ],
            ],

            [
                'legacy_id'           => 1000031,
                'title'               => '2-Day Social Media Workshop',
                'seo_slug'            => '2-day-social-media-workshop',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Intensive 2-day social media workshop. Covering Instagram, Facebook, LinkedIn and content strategy.',
                'long_desc'           => '<p>Our popular 2-day social media workshop provides hands-on training in building and managing brand accounts across Instagram, Facebook, LinkedIn and TikTok. Includes content calendaring, analytics, and paid social fundamentals.</p>',
                'meta_desc'           => '2-day social media workshop. Hands-on training for Instagram, Facebook, LinkedIn.',
                'image'               => 'event-sample-1.png',
                'sku'                 => '2-day-social',
                'price'               => 349.00,
                'wholesale_price'     => 280.00,
                'taxable'             => 0,
                'qty'                 => 3,
                'variants'            => [
                    [
                        'sku'            => '2-day-social-oct',
                        'label'          => 'October Session',
                        'public_price'   => 349.00,
                        'wholesale_price'=> 280.00,
                        'variant_fee'    => 0.00,
                        'taxable'        => 0,
                        'qty'            => 3,
                        'sort_order'     => 1,
                        'is_event'       => 1,
                        'event_label'    => '2-Day Social Media Workshop — October',
                        'event_start'    => '2026-10-14 09:00:00',
                        'event_end'      => '2026-10-15 17:00:00',
                        'event_color'    => '#0ea5e9',
                        'event_location' => 'Training Centre, Level 2',
                    ],
                ],
            ],

            [
                'legacy_id'           => 1000032,
                'title'               => 'Inventory Management Seminar - Advanced Course',
                'seo_slug'            => 'inventory-management-seminar-advanced',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Advanced inventory management seminar for retail and e-commerce businesses.',
                'long_desc'           => '<p>This advanced course covers inventory forecasting, demand planning, warehouse optimisation, and automated replenishment strategies for retail and e-commerce businesses.</p>',
                'meta_desc'           => 'Advanced inventory management seminar. Retail and e-commerce training.',
                'image'               => 'event-sample-7.png',
                'sku'                 => 'inv-mgmt-advanced',
                'price'               => 299.00,
                'wholesale_price'     => 220.00,
                'taxable'             => 0,
                'qty'                 => 15,
            ],

            [
                'legacy_id'           => 1000033,
                'title'               => 'eCommerce Strategies Seminar',
                'seo_slug'            => 'ecommerce-strategies-seminar',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Full-day eCommerce strategies seminar covering online store setup, conversion and growth.',
                'long_desc'           => '<p>A full-day seminar covering every aspect of running a successful online store — from platform selection and product photography through to conversion optimisation and post-purchase retention.</p>',
                'meta_desc'           => 'eCommerce strategies seminar. Online store setup, conversion and growth training.',
                'image'               => 'event-sample-3.png',
                'sku'                 => 'ecom-strategies',
                'price'               => 249.00,
                'wholesale_price'     => 180.00,
                'taxable'             => 0,
                'qty'                 => 18,
            ],

            [
                'legacy_id'           => 1000035,
                'title'               => 'Business Processes Seminar',
                'seo_slug'            => 'business-processes-seminar',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Seminar on business process optimisation and workflow automation.',
                'long_desc'           => '<p>This seminar covers business process mapping, workflow automation tools, and lean methodology to help businesses reduce waste and improve operational efficiency.</p>',
                'meta_desc'           => 'Business processes seminar. Process optimisation and workflow automation training.',
                'image'               => 'event-sample-4.png',
                'sku'                 => 'biz-processes',
                'price'               => 199.00,
                'wholesale_price'     => 140.00,
                'taxable'             => 0,
                'qty'                 => 20,
            ],

            [
                'legacy_id'           => 1000036,
                'title'               => 'Inventory Management Seminar - Intro Course',
                'seo_slug'            => 'inventory-management-seminar-intro',
                'brand_legacy_id'     => null,
                'category_legacy_ids' => [7],
                'short_desc'          => 'Introductory inventory management seminar for new business owners.',
                'long_desc'           => '<p>An introductory course covering basic inventory management concepts, stock counting methods, purchase ordering, and simple reorder point calculations. Perfect for new business owners.</p>',
                'meta_desc'           => 'Introductory inventory management seminar. Training for new business owners.',
                'image'               => 'event-sample-6.png',
                'sku'                 => 'inv-mgmt-intro',
                'price'               => 149.00,
                'wholesale_price'     => 100.00,
                'taxable'             => 0,
                'qty'                 => 25,
            ],

        ];
    }
}
