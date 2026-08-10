<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use App\Models\ProductVariant;
use App\Models\ProductVariantEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductImportService
{
    /**
     * Parse a CSV or Excel file into an array of rows and headers.
     */
    public function parseFile(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if (in_array($extension, ['csv', 'txt'])) {
            return $this->parseCsvFile($filePath);
        }

        return $this->parseExcelFile($filePath);
    }

    private function parseCsvFile(string $filePath): array
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $headers = null;
            while (($data = fgetcsv($handle, 10000, ',')) !== false) {
                // Trim BOM if present on first cell
                if (isset($data[0])) {
                    $data[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $data[0]);
                }
                if ($headers === null) {
                    $headers = array_map(fn($h) => trim((string)$h), $data);
                    continue;
                }
                if (count($data) === count($headers)) {
                    $row = array_combine($headers, array_map('trim', $data));
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }

        return [
            'headers' => $headers ?? [],
            'rows'    => $rows,
        ];
    }

    private function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet   = $spreadsheet->getActiveSheet();
        $excelData   = $worksheet->toArray(null, true, true, true);

        if (empty($excelData)) {
            return ['headers' => [], 'rows' => []];
        }

        $headerRow = array_shift($excelData);
        $headers   = array_map(fn($h) => trim((string)$h), array_values($headerRow));

        $rows = [];
        foreach ($excelData as $rowValues) {
            $vals = array_values($rowValues);
            if (count($vals) === count($headers)) {
                $row = array_combine($headers, array_map('trim', $vals));
                // Only include non-empty rows
                if (array_filter($row)) {
                    $rows[] = $row;
                }
            }
        }

        return [
            'headers' => $headers,
            'rows'    => $rows,
        ];
    }

    /**
     * Map raw row headers to standardized keys.
     */
    public function autoDetectMapping(array $headers): array
    {
        $mapping = [];
        $aliases = [
            'title'                  => ['title', 'product title', 'name', 'product_name', 'item_name', 'product'],
            'short_description'      => ['short_description', 'short desc', 'short description', 'summary'],
            'long_description'       => ['long_description', 'long desc', 'description', 'body', 'details', 'content'],
            'meta_title'             => ['meta_title', 'meta title', 'seo_title', 'seo title', 'page_title'],
            'meta_description'       => ['meta_description', 'meta description', 'seo_description', 'seo description', 'page_description'],
            'public_price'           => ['public_price', 'price', 'retail_price', 'retail price', 'public price', 'msrp', 'regular price'],
            'wholesale_price'        => ['wholesale_price', 'wholesale price', 'wholesale', 'trade_price', 'b2b_price'],
            'categories'             => ['categories', 'category', 'category_name', 'tags', 'departments'],
            'brand'                  => ['brand', 'brand_name', 'manufacturer', 'vendor'],
            'thumbnail_url'          => ['thumbnail_url', 'thumbnail url', 'thumbnail', 'thumb_url', 'thumb url', 'thumb', 'small_image'],
            'main_image_url'         => ['main_image_url', 'main image url', 'main_image', 'main image', 'featured_image', 'featured image', 'image_url', 'image url', 'image', 'picture'],
            'zoom_images_url'        => ['zoom_images_url', 'zoom images url', 'zoom_images', 'zoom images', 'gallery_images', 'gallery images', 'additional_images', 'additional images', 'more_images', 'images'],
            'image_url_source'       => ['image_url_source', 'image source type', 'image_source_type', 'external_image', 'remote_image', 'use_direct_url'],
            'variant_sku'            => ['variant_sku', 'variant sku', 'sku', 'product_sku', 'item_code'],
            'variant_name'           => ['variant_name', 'variant name', 'variant title', 'option_name', 'option name', 'variant', 'version', 'variant label', 'option label', 'selector label'],
            'variant_attributes'     => ['variant_attributes', 'variant attributes', 'attributes', 'options', 'specs', 'properties'],
            'variant_price'          => ['variant_price', 'variant price', 'option_price', 'option price'],
            'variant_wholesale_price'=> ['variant_wholesale_price', 'variant wholesale price', 'variant wholesale'],
            'inventory'              => ['inventory', 'stock quantity', 'stock_quantity', 'stock', 'qty', 'quantity', 'inventory_level'],
            'charge_tax'             => ['charge_tax', 'taxable', 'tax', 'sales_tax', 'charge tax'],
            'shipping'               => ['shipping', 'shippable', 'is_shippable', 'requires_shipping', 'physical'],
            'weight'                 => ['weight', 'item_weight', 'product_weight', 'ship_weight'],
            'weight_type'            => ['weight_type', 'weight_unit', 'weight unit', 'unit_of_weight', 'weight type'],
            'download_item'          => ['download_item', 'downloadable', 'digital', 'is_digital', 'is_download', 'download item'],
            'direct_download_url'    => ['direct_download_url', 'download_url', 'download url', 'file_url', 'digital_url', 'asset_url'],
            'is_event'               => ['is_event', 'event', 'is event', 'ticket'],
            'event_label'            => ['event_label', 'event_title', 'event label', 'event title', 'event_name', 'event name'],
            'event_location'         => ['event_location', 'event location', 'venue', 'location'],
            'event_start_date'       => ['event_start_date', 'event start date', 'start_date', 'start date', 'event_start', 'event date'],
            'event_end_date'         => ['event_end_date', 'event end date', 'end_date', 'end date', 'event_end'],
        ];

        foreach ($headers as $header) {
            $headerLower = strtolower(trim($header));
            $foundKey = null;
            foreach ($aliases as $stdKey => $possibleAliases) {
                if (in_array($headerLower, $possibleAliases, true)) {
                    $foundKey = $stdKey;
                    break;
                }
            }
            if ($foundKey && !isset($mapping[$foundKey])) {
                $mapping[$foundKey] = $header;
            }
        }

        return $mapping;
    }

    /**
     * Execute full bulk import with variant grouping, SKU updating, category/brand creation, and image processing.
     */
    public function executeImport(array $rows, array $columnMapping = []): array
    {
        $stats = [
            'products_created'   => 0,
            'products_updated'   => 0,
            'variants_created'   => 0,
            'variants_updated'   => 0,
            'categories_created' => 0,
            'brands_created'     => 0,
            'images_processed'   => 0,
            'errors'             => [],
        ];

        if (empty($rows)) {
            return $stats;
        }

        // Group rows by product identifier (Title or SKU prefix base)
        $groupedProducts = [];
        foreach ($rows as $index => $rawRow) {
            $mapped = $this->extractMappedRow($rawRow, $columnMapping);
            
            $title = $mapped['title'] ?: ($mapped['variant_sku'] ?: 'Imported Product #' . ($index + 1));
            // Grouping key: normalized slug of title
            $groupKey = Str::slug($title);
            if (empty($groupKey)) {
                $groupKey = 'item-' . ($index + 1);
            }

            if (!isset($groupedProducts[$groupKey])) {
                $groupedProducts[$groupKey] = [
                    'title'             => $title,
                    'short_description' => $mapped['short_description'],
                    'long_description'  => $mapped['long_description'],
                    'categories'        => $mapped['categories'],
                    'brand'             => $mapped['brand'],
                    'variant_label'     => $mapped['variant_name'],  // maps to products.variant_label (the selector label)
                    'meta_title'        => $mapped['meta_title'],
                    'meta_description'  => $mapped['meta_description'],
                    'rows'              => [],
                ];
            }

            $groupedProducts[$groupKey]['rows'][] = $mapped;
        }

        foreach ($groupedProducts as $group) {
            try {
                DB::beginTransaction();

                // 1. Resolve or Create Brand
                $brandId = null;
                if (!empty($group['brand'])) {
                    $brand = Brand::where('name', $group['brand'])
                        ->orWhere('slug', Str::slug($group['brand']))
                        ->first();
                    if (!$brand) {
                        $brand = Brand::create([
                            'name'               => $group['brand'],
                            'slug'               => Str::slug($group['brand']),
                            'is_visible_in_menu' => true,
                        ]);
                        $stats['brands_created']++;
                    }
                    $brandId = $brand->id;
                }

                // 2. Resolve or Create Product
                // Check if any row SKU matches an existing variant -> update existing product
                $existingProduct = null;
                foreach ($group['rows'] as $r) {
                    if (!empty($r['variant_sku'])) {
                        $matchedVar = ProductVariant::where('sku', $r['variant_sku'])->first();
                        if ($matchedVar) {
                            $existingProduct = $matchedVar->product;
                            break;
                        }
                    }
                }

                if (!$existingProduct) {
                    $existingProduct = Product::where('title', $group['title'])
                        ->orWhere('seo_slug', Str::slug($group['title']))
                        ->first();
                }

                $isNewProduct = false;
                if ($existingProduct) {
                    $existingProduct->update([
                        'title'             => $group['title'],
                        'short_description' => $group['short_description'] ?: $existingProduct->short_description,
                        'long_description'  => $group['long_description'] ?: $existingProduct->long_description,
                        'brand_id'          => $brandId ?: $existingProduct->brand_id,
                        'variant_label'     => $group['variant_label'] ?: $existingProduct->variant_label,
                        'meta_title'        => $group['meta_title'] ?: $existingProduct->meta_title,
                        'meta_description'  => $group['meta_description'] ?: $existingProduct->meta_description,
                    ]);
                    $product = $existingProduct;
                    $stats['products_updated']++;
                } else {
                    $product = Product::create([
                        'title'             => $group['title'],
                        'short_description' => $group['short_description'],
                        'long_description'  => $group['long_description'],
                        'seo_slug'          => $this->generateUniqueSlug($group['title']),
                        'brand_id'          => $brandId,
                        'download_item'     => 0,
                        'shipping'          => 1,
                        'variant_label'     => $group['variant_label'] ?: 'Select Option:',
                        'meta_title'        => $group['meta_title'] ?: null,
                        'meta_description'  => $group['meta_description'] ?: null,
                    ]);
                    $isNewProduct = true;
                    $stats['products_created']++;
                }

                // 3. Resolve & Attach Categories (supports subcategories, comma-separated names, or JSON array)
                if (!empty($group['categories'])) {
                    $categoryIds = $this->resolveCategoryIds($group['categories'], $stats);
                    if (!empty($categoryIds)) {
                        $product->categories()->syncWithoutDetaching($categoryIds);
                    }
                }

                // 4. Import / Update Variants
                foreach ($group['rows'] as $vRow) {
                    $sku = !empty($vRow['variant_sku']) 
                        ? $vRow['variant_sku'] 
                        : strtoupper(Str::slug($group['title'])) . '-' . Str::random(5);

                    $pubPrice   = (float) ($vRow['variant_price'] ?: ($vRow['public_price'] ?: 0.00));
                    $wsPrice    = (float) ($vRow['variant_wholesale_price'] ?: ($vRow['wholesale_price'] ?: 0.00));
                    $attributes = $this->parseVariantAttributes($vRow['variant_attributes'], $vRow['variant_name']);

                    $variant = ProductVariant::where('product_id', $product->id)
                        ->where('sku', $sku)
                        ->first();

                    if (!$variant && !empty($vRow['variant_sku'])) {
                        $variant = ProductVariant::where('sku', $vRow['variant_sku'])->first();
                    }

                    if ($variant) {
                        $variant->update([
                            'product_id'          => $product->id,
                            'public_price'        => $pubPrice,
                            'wholesale_price'     => $wsPrice,
                            'attributes'          => !empty($attributes) ? json_encode($attributes) : $variant->attributes,
                            'charge_tax'          => $vRow['charge_tax'] !== '' ? (int) $vRow['charge_tax'] : $variant->charge_tax,
                            'shipping'            => $vRow['shipping'] !== '' ? (int) $vRow['shipping'] : $variant->shipping,
                            'weight'              => $vRow['weight'] !== '' ? (float) $vRow['weight'] : $variant->weight,
                            'weight_type'         => $vRow['weight_type'] ?: $variant->weight_type,
                            'download_item'       => $vRow['download_item'] !== '' ? (int) $vRow['download_item'] : $variant->download_item,
                            'direct_download_url' => $vRow['direct_download_url'] ?: $variant->direct_download_url,
                            'is_event'            => $vRow['is_event'] !== '' ? (bool) $vRow['is_event'] : $variant->is_event,
                        ]);
                        $stats['variants_updated']++;
                    } else {
                        $variant = ProductVariant::create([
                            'product_id'          => $product->id,
                            'sku'                 => $sku,
                            'public_price'        => $pubPrice,
                            'wholesale_price'     => $wsPrice,
                            'attributes'          => !empty($attributes) ? json_encode($attributes) : null,
                            'on_sale'             => 0,
                            'charge_tax'          => $vRow['charge_tax'] !== '' ? (int) $vRow['charge_tax'] : 1,
                            'shipping'            => $vRow['shipping'] !== '' ? (int) $vRow['shipping'] : 1,
                            'weight'              => $vRow['weight'] !== '' ? (float) $vRow['weight'] : 0,
                            'weight_type'         => $vRow['weight_type'] ?: 'lbs',
                            'download_item'       => $vRow['download_item'] !== '' ? (int) $vRow['download_item'] : 0,
                            'direct_download_url' => $vRow['direct_download_url'] ?: null,
                            'is_event'            => $vRow['is_event'] !== '' ? (bool) $vRow['is_event'] : false,
                        ]);
                        $stats['variants_created']++;
                    }

                    // Event details — upsert into product_variant_events if is_event is set
                    if ($variant->is_event || $vRow['is_event'] !== '') {
                        if ((bool) ($vRow['is_event'] ?? false)) {
                            ProductVariantEvent::updateOrCreate(
                                ['variant_id' => $variant->id],
                                [
                                    'event_label'      => $vRow['event_label'] ?: null,
                                    'event_location'   => $vRow['event_location'] ?: null,
                                    'event_start_date' => $vRow['event_start_date'] ?: null,
                                    'event_end_date'   => $vRow['event_end_date'] ?: null,
                                ]
                            );
                        }
                    }

                    // Stock quantity / inventory
                    if (isset($vRow['inventory']) && $vRow['inventory'] !== '') {
                        $stockVal = (int) $vRow['inventory'];
                        $inv = ProductInventory::where('variant_id', $variant->id)->first();
                        if ($inv) {
                            $inv->update(['quantity_available' => $stockVal]);
                        } else {
                            ProductInventory::create([
                                'variant_id'         => $variant->id,
                                'quantity_available'  => $stockVal,
                                'reserved_stock'      => 0,
                            ]);
                        }
                    }

                    // 5. Process Images (Thumbnail, Main Image, Zoom Images)
                    $this->processVariantImages($variant, $vRow, $stats);
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $stats['errors'][] = 'Product "' . $group['title'] . '": ' . $e->getMessage();
            }
        }

        return $stats;
    }

    private function extractMappedRow(array $rawRow, array $columnMapping): array
    {
        $get = function($key) use ($rawRow, $columnMapping) {
            if (isset($columnMapping[$key]) && isset($rawRow[$columnMapping[$key]])) {
                return trim((string)$rawRow[$columnMapping[$key]]);
            }
            if (isset($rawRow[$key])) {
                return trim((string)$rawRow[$key]);
            }
            return '';
        };

        return [
            'title'                   => $get('title'),
            'short_description'       => $get('short_description'),
            'long_description'        => $get('long_description'),
            'meta_title'              => $get('meta_title'),
            'meta_description'        => $get('meta_description'),
            'public_price'            => $get('public_price'),
            'wholesale_price'         => $get('wholesale_price'),
            'categories'              => $get('categories'),
            'brand'                   => $get('brand'),
            'thumbnail_url'           => $get('thumbnail_url'),
            'main_image_url'          => $get('main_image_url'),
            'zoom_images_url'         => $get('zoom_images_url'),
            'image_url_source'        => $get('image_url_source'),
            'variant_sku'             => $get('variant_sku'),
            'variant_name'            => $get('variant_name'),   // maps to products.variant_label
            'variant_attributes'      => $get('variant_attributes'),
            'variant_price'           => $get('variant_price'),
            'variant_wholesale_price' => $get('variant_wholesale_price'),
            'inventory'               => $get('inventory'),
            // Variant physical / fulfilment fields
            'charge_tax'              => $get('charge_tax'),
            'shipping'                => $get('shipping'),
            'weight'                  => $get('weight'),
            'weight_type'             => $get('weight_type'),
            // Download / digital
            'download_item'           => $get('download_item'),
            'direct_download_url'     => $get('direct_download_url'),
            // Event
            'is_event'                => $get('is_event'),
            'event_label'             => $get('event_label'),
            'event_location'          => $get('event_location'),
            'event_start_date'        => $get('event_start_date'),
            'event_end_date'          => $get('event_end_date'),
        ];
    }

    private function resolveCategoryIds(string $catInput, array &$stats): array
    {
        $categoryIds = [];
        // Check if JSON array
        $categoriesList = [];
        if (str_starts_with($catInput, '[') && str_ends_with($catInput, ']')) {
            $decoded = json_decode($catInput, true);
            if (is_array($decoded)) {
                $categoriesList = $decoded;
            }
        }

        if (empty($categoriesList)) {
            // Split by comma
            $categoriesList = explode(',', $catInput);
        }

        foreach ($categoriesList as $item) {
            $item = trim($item);
            if ($item === '') continue;

            // Handle hierarchy split e.g. "Electronics > Audio > Headphones"
            $parts = preg_split('/[\>\|]/', $item);
            $parentId = null;
            foreach ($parts as $partName) {
                $partName = trim($partName);
                if (empty($partName)) continue;

                $query = Category::where('name', $partName);
                if ($parentId === null) {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $parentId);
                }

                $catObj = $query->first();
                if (!$catObj) {
                    $catObj = Category::create([
                        'name'               => $partName,
                        'slug'               => Str::slug($partName),
                        'parent_id'          => $parentId,
                        'is_visible_in_menu' => true,
                    ]);
                    $stats['categories_created']++;
                }

                $parentId = $catObj->id;
                $categoryIds[] = $catObj->id;
            }
        }

        return array_values(array_unique($categoryIds));
    }

    private function parseVariantAttributes(string $attrRaw, string $variantName): array
    {
        $attributes = [];
        if (!empty($attrRaw)) {
            if (str_starts_with($attrRaw, '{') && str_ends_with($attrRaw, '}')) {
                $decoded = json_decode($attrRaw, true);
                if (is_array($decoded)) {
                    $attributes = $decoded;
                }
            }
            if (empty($attributes)) {
                // Split e.g. "Size:L, Color:Black" or "Size=L|Color=Black"
                $pairs = preg_split('/[,\|]/', $attrRaw);
                foreach ($pairs as $pair) {
                    if (str_contains($pair, ':')) {
                        [$k, $v] = explode(':', $pair, 2);
                        $attributes[trim($k)] = trim($v);
                    } elseif (str_contains($pair, '=')) {
                        [$k, $v] = explode('=', $pair, 2);
                        $attributes[trim($k)] = trim($v);
                    }
                }
            }
        }

        if (empty($attributes) && !empty($variantName)) {
            $attributes['Option'] = $variantName;
        }

        return $attributes;
    }

    private function processVariantImages(ProductVariant $variant, array $vRow, array &$stats): void
    {
        $thumbUrl  = $vRow['thumbnail_url'];
        $mainUrl   = $vRow['main_image_url'];
        $zoomUrls  = $vRow['zoom_images_url'];
        $isDirectUrl = (int) ($vRow['image_url_source'] ?? 0) === 1;

        if (empty($thumbUrl) && empty($mainUrl) && empty($zoomUrls)) {
            return;
        }

        // Parse zoom images list if multiple
        $zoomList = [];
        if (!empty($zoomUrls)) {
            if (str_starts_with($zoomUrls, '[') && str_ends_with($zoomUrls, ']')) {
                $decoded = json_decode($zoomUrls, true);
                if (is_array($decoded)) {
                    $zoomList = $decoded;
                }
            }
            if (empty($zoomList)) {
                $zoomList = array_map('trim', explode(',', $zoomUrls));
            }
        }

        $mainTarget = $mainUrl ?: ($thumbUrl ?: ($zoomList[0] ?? null));
        $thumbTarget = $thumbUrl ?: $mainTarget;
        $zoomTarget  = $zoomList[0] ?? $mainTarget;

        if (!$mainTarget) {
            return;
        }

        if ($isDirectUrl) {
            // Store as external URL links directly
            ProductImage::updateOrCreate(
                [
                    'variant_id' => $variant->id,
                    'main_path'  => $mainTarget,
                ],
                [
                    'thumbnail_path'   => $thumbTarget,
                    'zoom_path'        => $zoomTarget,
                    'image_url_source' => 1,
                    'search_image'     => 1,
                    'active'           => 1,
                ]
            );
            $stats['images_processed']++;
            return;
        }

        // Download images locally & update paths automatically
        $localMain  = $this->downloadAndSaveImage($mainTarget);
        $localThumb = $thumbTarget === $mainTarget ? $localMain : $this->downloadAndSaveImage($thumbTarget);
        $localZoom  = $zoomTarget === $mainTarget ? $localMain : $this->downloadAndSaveImage($zoomTarget);

        ProductImage::updateOrCreate(
            [
                'variant_id' => $variant->id,
                'main_path'  => $localMain ?: $mainTarget,
            ],
            [
                'thumbnail_path'   => $localThumb ?: $thumbTarget,
                'zoom_path'        => $localZoom ?: $zoomTarget,
                'image_url_source' => $localMain ? 0 : 1,
                'search_image'     => 1,
                'active'           => 1,
            ]
        );
        $stats['images_processed']++;
    }

    private function downloadAndSaveImage(string $url): ?string
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                $contentType = $response->header('Content-Type');
                $ext = 'jpg';
                if (str_contains($contentType, 'png')) $ext = 'png';
                elseif (str_contains($contentType, 'webp')) $ext = 'webp';
                elseif (str_contains($contentType, 'gif')) $ext = 'gif';

                $filename = 'imported_' . Str::random(12) . '.' . $ext;
                $storagePath = 'cms_product_imports/' . $filename;

                Storage::disk('public')->put($storagePath, $response->body());
                return $storagePath;
            }
        } catch (\Throwable $e) {
            // Return null to fallback to remote URL
        }

        return null;
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Product::where('seo_slug', 'like', $slug . '%')->count();
        return $count > 0 ? $slug . '-' . ($count + 1) : $slug;
    }
}
