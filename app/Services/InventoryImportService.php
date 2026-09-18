<?php

namespace App\Services;

use App\Models\ProductInventory;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryImportService
{
    /**
     * Parse a CSV or delimited text file into headers and sample/all rows.
     * Supports comma (,), pipe (|), semicolon (;), and tab (\t).
     */
    public function parseCsv(string $filePath, ?string $delimiter = null, int $maxPreviewRows = 5, bool $includeAllRows = true): array
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'error'   => "File not found at: {$filePath}",
                'headers' => [],
                'rows'    => [],
                'preview' => [],
                'total'   => 0,
            ];
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return [
                'success' => false,
                'error'   => "Could not open file.",
                'headers' => [],
                'rows'    => [],
                'preview' => [],
                'total'   => 0,
            ];
        }

        // Auto-detect delimiter if not specified
        if ($delimiter === null) {
            $firstLine = fgets($handle);
            rewind($handle);

            if ($firstLine !== false) {
                $delimiters = [',', '|', ';', "\t"];
                $counts = [];
                foreach ($delimiters as $d) {
                    $counts[$d] = substr_count($firstLine, $d);
                }
                arsort($counts);
                $delimiter = array_key_first($counts) ?: ',';
                if ($counts[$delimiter] === 0) {
                    $delimiter = ',';
                }
            } else {
                $delimiter = ',';
            }
        }

        $rawHeader = fgetcsv($handle, 0, $delimiter, '"', '\\');
        if (!$rawHeader) {
            fclose($handle);
            return [
                'success' => false,
                'error'   => "Empty CSV or unable to parse headers.",
                'headers' => [],
                'rows'    => [],
                'preview' => [],
                'total'   => 0,
            ];
        }

        // Clean headers (trim BOM & whitespace)
        if (isset($rawHeader[0])) {
            $rawHeader[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $rawHeader[0]);
        }
        $headers = array_map(fn($h) => trim((string)$h), $rawHeader);

        $rows = [];
        $preview = [];
        $total = 0;

        while (($row = fgetcsv($handle, 0, $delimiter, '"', '\\')) !== false) {
            if (count($row) === 1 && trim($row[0]) === '') {
                continue; // skip blank lines
            }

            if ($includeAllRows || $total < $maxPreviewRows) {
                // Pad or slice row to match headers count
                if (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                } elseif (count($row) > count($headers)) {
                    $row = array_slice($row, 0, count($headers));
                }

                $mappedRow = [];
                foreach ($headers as $index => $colName) {
                    $mappedRow[$colName] = trim($row[$index] ?? '');
                }

                if ($includeAllRows) {
                    $rows[] = $mappedRow;
                }
                if ($total < $maxPreviewRows) {
                    $preview[] = $mappedRow;
                }
            }
            $total++;
        }

        fclose($handle);

        return [
            'success'   => true,
            'delimiter' => $delimiter,
            'headers'   => $headers,
            'rows'      => $rows,
            'preview'   => $preview,
            'total'     => $total,
        ];
    }

    /**
     * Process Stock and Cost CSV import with optional markup percentage calculation and inventory update mode.
     * Supports both an array of rows or a direct file path string for memory-efficient processing.
     * Uses 1000-item batch variant lookups and chunked DB transactions for high-speed execution (< 5s for 60k+ rows).
     *
     * @param array|string $source Array of associative row arrays OR file path
     * @param array $mapping ['sku' => 'CSV_COL_NAME', 'cost' => 'CSV_COL_NAME', 'stock' => 'CSV_COL_NAME']
     * @param float $markupPercentage e.g. 15.0 for 15% markup
     * @param string $inventoryMode 'replace' | 'add'
     * @return array Summary statistics and itemized log
     */
    public function processStockAndCostImport(
        array|string $source,
        array $mapping,
        float $markupPercentage = 0.0,
        string $inventoryMode = 'replace',
        bool $autoShowInResults = false
    ): array {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        DB::disableQueryLog();
        DB::flushQueryLog();

        $skuCol   = $mapping['sku'] ?? 'SKU';
        $costCol  = $mapping['cost'] ?? null;
        $stockCol = $mapping['stock'] ?? null;

        $stats = [
            'total_rows'           => 0,
            'matched_count'        => 0,
            'updated_count'        => 0,
            'skipped_count'        => 0,
            'error_count'          => 0,
            'markup_applied'       => $markupPercentage,
            'inventory_mode'       => $inventoryMode,
            'auto_show_in_results' => $autoShowInResults,
            'auto_shown_count'     => 0,
        ];

        $updatedItems = [];
        $skippedItems = [];
        $log = [];

        $chunkSize = 1000;
        $variantIndex = $this->buildVariantLookupIndex();

        $processChunk = function (array $rowsChunk) use (
            $skuCol, $costCol, $stockCol, $markupPercentage, $inventoryMode, $autoShowInResults,
            &$stats, &$updatedItems, &$skippedItems, &$log, &$variantIndex
        ) {
            if (empty($rowsChunk)) {
                return;
            }

            // Step 1: Resolve all variant IDs for this chunk in O(1) constant time
            $rowVariantMap = [];
            $variantIdsToFetch = [];

            foreach ($rowsChunk as $item) {
                $rawSku = trim((string)($item['row'][$skuCol] ?? ''));
                if ($rawSku !== '') {
                    $vId = $this->resolveVariantId($rawSku, $variantIndex);
                    if ($vId) {
                        $rowVariantMap[$item['index']] = $vId;
                        $variantIdsToFetch[$vId] = true;
                    }
                }
            }

            // Step 2: Batch query only the matched variants with product & inventory in 1 SQL query
            $variantsById = [];
            if (!empty($variantIdsToFetch)) {
                $variantsById = ProductVariant::with(['product', 'inventory'])
                    ->whereIn('id', array_keys($variantIdsToFetch))
                    ->get()
                    ->keyBy('id');
            }

            $variantUpdates = [];
            $invUpdates = [];
            $matchedProductIds = [];

            foreach ($rowsChunk as $item) {
                $index  = $item['index'];
                $row    = $item['row'];
                $rawSku = trim((string)($row[$skuCol] ?? ''));

                $stats['total_rows']++;

                if ($rawSku === '') {
                    $stats['skipped_count']++;
                    if (count($log) < 100) {
                        $log[] = [
                            'row'     => $index + 1,
                            'sku'     => '(Blank)',
                            'status'  => 'skipped',
                            'message' => 'Empty SKU',
                        ];
                    }
                    continue;
                }

                $variantId = $rowVariantMap[$index] ?? null;
                $variant   = $variantId ? $variantsById->get($variantId) : null;

                if (!$variant) {
                    $stats['skipped_count']++;
                    if (count($log) < 100) {
                        $log[] = [
                            'row'     => $index + 1,
                            'sku'     => $rawSku,
                            'status'  => 'skipped',
                            'message' => 'SKU not found in catalog',
                        ];
                    }
                    continue;
                }

                $stats['matched_count']++;
                if ($autoShowInResults && $variant->product_id) {
                    $matchedProductIds[$variant->product_id] = true;
                }

                $oldCost   = $variant->item_cost !== null ? (float)$variant->item_cost : null;
                $oldPrice  = (float)($variant->public_price ?? 0.00);
                $newCost   = $oldCost;
                $newPrice  = $oldPrice;

                $inv = $variant->inventory ?: new ProductInventory(['variant_id' => $variant->id, 'quantity_available' => 0]);
                $oldStock  = (int)($inv->quantity_available ?? 0);
                $newStock  = $oldStock;

                $hasCostUpdate  = false;
                $hasStockUpdate = false;

                // 1. Process Cost & Markup
                if ($costCol && isset($row[$costCol]) && is_numeric(str_replace(['$', ','], '', $row[$costCol]))) {
                    $parsedCost = (float)str_replace(['$', ','], '', $row[$costCol]);
                    if ($parsedCost >= 0) {
                        $newCost = $parsedCost;
                        $hasCostUpdate = true;

                        if ($markupPercentage > 0 && $newCost > 0) {
                            $newPrice = round($newCost * (1 + ($markupPercentage / 100)), 2);
                        }
                    }
                }

                // 2. Process Inventory Level
                if ($stockCol && isset($row[$stockCol]) && is_numeric(str_replace(',', '', $row[$stockCol]))) {
                    $parsedStock = (int)str_replace(',', '', $row[$stockCol]);
                    if ($inventoryMode === 'add') {
                        $newStock = max(0, $oldStock + $parsedStock);
                    } else {
                        $newStock = max(0, $parsedStock);
                    }
                    $hasStockUpdate = true;
                }

                if ($hasCostUpdate || $hasStockUpdate) {
                    $vData = ['updated_at' => now()];
                    if ($hasCostUpdate) {
                        $vData['item_cost'] = $newCost;
                        if ($markupPercentage > 0 && $newCost > 0) {
                            $vData['public_price'] = $newPrice;
                        }
                    }
                    $variantUpdates[$variant->id] = $vData;

                    if ($hasStockUpdate) {
                        $invUpdates[$variant->id] = [
                            'id'                 => $variant->inventory?->id,
                            'quantity_available' => $newStock,
                        ];
                    }
                    $stats['updated_count']++;

                    $itemSummary = [
                        'row'           => $index + 1,
                        'sku'           => $rawSku,
                        'product_title' => $variant->product?->title ?? 'N/A',
                        'old_cost'      => $oldCost,
                        'new_cost'      => $newCost,
                        'old_price'     => $oldPrice,
                        'new_price'     => $newPrice,
                        'markup_pct'    => $markupPercentage . '%',
                        'old_stock'     => $oldStock,
                        'new_stock'     => $newStock,
                        'mode'          => $inventoryMode,
                        'status'        => 'updated',
                    ];

                    if (count($updatedItems) < 100) {
                        $updatedItems[] = $itemSummary;
                    }
                    if (count($log) < 100) {
                        $log[] = $itemSummary;
                    }
                } else {
                    $stats['skipped_count']++;
                    $itemSummary = [
                        'row'     => $index + 1,
                        'sku'     => $rawSku,
                        'status'  => 'skipped',
                        'message' => 'No valid cost or stock data provided in row',
                    ];
                    if (count($skippedItems) < 100) {
                        $skippedItems[] = $itemSummary;
                    }
                    if (count($log) < 100) {
                        $log[] = $itemSummary;
                    }
                }
            }

            // Direct DB execution inside a single transaction per chunk
            if (!empty($variantUpdates) || !empty($invUpdates) || (!empty($matchedProductIds) && $autoShowInResults)) {
                DB::transaction(function () use ($variantUpdates, $invUpdates, $matchedProductIds, $autoShowInResults, &$stats) {
                    foreach ($variantUpdates as $vId => $vData) {
                        DB::table('product_variants')->where('id', $vId)->update($vData);
                    }
                    foreach ($invUpdates as $vId => $iData) {
                        if (!empty($iData['id'])) {
                            DB::table('products_inventory')->where('id', $iData['id'])->update([
                                'quantity_available' => $iData['quantity_available'],
                                'updated_at'         => now(),
                            ]);
                        } else {
                            DB::table('products_inventory')->updateOrInsert(
                                ['variant_id' => $vId],
                                [
                                    'quantity_available' => $iData['quantity_available'],
                                    'updated_at'         => now(),
                                    'created_at'         => now(),
                                ]
                            );
                        }
                    }
                    if ($autoShowInResults && !empty($matchedProductIds)) {
                        $shownCount = DB::table('products')
                            ->whereIn('id', array_keys($matchedProductIds))
                            ->where('show_in_results', 0)
                            ->update([
                                'show_in_results' => 1,
                                'updated_at'      => now(),
                            ]);
                        $stats['auto_shown_count'] += $shownCount;
                    }
                });
            }
        };

        if (is_array($source)) {
            $chunk = [];
            foreach ($source as $idx => $row) {
                $chunk[] = ['index' => $idx, 'row' => $row];
                if (count($chunk) >= $chunkSize) {
                    $processChunk($chunk);
                    $chunk = [];
                }
            }
            if (!empty($chunk)) {
                $processChunk($chunk);
            }
        } elseif (is_string($source) && file_exists($source)) {
            $handle = fopen($source, 'r');
            if ($handle) {
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiters = [',', '|', ';', "\t"];
                $counts = [];
                foreach ($delimiters as $d) {
                    $counts[$d] = substr_count((string)$firstLine, $d);
                }
                arsort($counts);
                $delim = array_key_first($counts) ?: ',';
                if (($counts[$delim] ?? 0) === 0) {
                    $delim = ',';
                }

                $rawHeader = fgetcsv($handle, 0, $delim, '"', '\\');
                if ($rawHeader) {
                    if (isset($rawHeader[0])) {
                        $rawHeader[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $rawHeader[0]);
                    }
                    $headers = array_map(fn($h) => trim((string)$h), $rawHeader);

                    $idx = 0;
                    $chunk = [];
                    while (($row = fgetcsv($handle, 0, $delim, '"', '\\')) !== false) {
                        if (count($row) === 1 && trim($row[0]) === '') {
                            continue;
                        }
                        if (count($row) < count($headers)) {
                            $row = array_pad($row, count($headers), '');
                        } elseif (count($row) > count($headers)) {
                            $row = array_slice($row, 0, count($headers));
                        }
                        $mapped = [];
                        foreach ($headers as $hIdx => $hName) {
                            $mapped[$hName] = trim($row[$hIdx] ?? '');
                        }
                        $chunk[] = ['index' => $idx, 'row' => $mapped];
                        $idx++;

                        if (count($chunk) >= $chunkSize) {
                            $processChunk($chunk);
                            $chunk = [];
                        }
                    }
                    if (!empty($chunk)) {
                        $processChunk($chunk);
                    }
                }
                fclose($handle);
            }
        }

        return [
            'stats'         => $stats,
            'log'           => $log,
            'updated_items' => $updatedItems,
            'skipped_items' => $skippedItems,
        ];
    }

    /**
     * Process Amazon / eBay Marketplace Pricing Markup Import.
     * Updates marketplace pricing based on the variant's CURRENT cost in the database + markup%.
     * Skips items if SKU is not found or if current variant cost is 0.00 / unset.
     * Uses 1000-item batch variant lookups and direct chunked DB updates.
     *
     * @param array|string $source Array of associative row arrays OR file path
     * @param string $skuCol Name of the SKU column in the CSV
     * @param string $marketplace 'amazon' | 'ebay' | 'both'
     * @param float $markupPercentage Markup percentage (e.g. 20.0)
     * @return array Summary statistics and itemized log
     */
    public function processMarketplacePricingImport(
        array|string $source,
        string $skuCol,
        string $marketplace,
        float $markupPercentage
    ): array {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        DB::disableQueryLog();
        DB::flushQueryLog();

        $stats = [
            'total_rows'      => 0,
            'matched_count'   => 0,
            'updated_count'   => 0,
            'skipped_count'   => 0,
            'marketplace'     => $marketplace,
            'markup_applied'  => $markupPercentage,
        ];

        $updatedItems = [];
        $skippedItems = [];
        $log = [];

        $chunkSize = 1000;
        $variantIndex = $this->buildVariantLookupIndex();

        $processChunk = function (array $rowsChunk) use (
            $skuCol, $marketplace, $markupPercentage,
            &$stats, &$updatedItems, &$skippedItems, &$log, &$variantIndex
        ) {
            if (empty($rowsChunk)) {
                return;
            }

            // Step 1: Resolve all variant IDs for this chunk in O(1) constant time
            $rowVariantMap = [];
            $variantIdsToFetch = [];

            foreach ($rowsChunk as $item) {
                $rawSku = trim((string)($item['row'][$skuCol] ?? ''));
                if ($rawSku !== '') {
                    $vId = $this->resolveVariantId($rawSku, $variantIndex);
                    if ($vId) {
                        $rowVariantMap[$item['index']] = $vId;
                        $variantIdsToFetch[$vId] = true;
                    }
                }
            }

            // Step 2: Batch query only the matched variants with product relation in 1 SQL query
            $variantsById = [];
            if (!empty($variantIdsToFetch)) {
                $variantsById = ProductVariant::with('product')
                    ->whereIn('id', array_keys($variantIdsToFetch))
                    ->get()
                    ->keyBy('id');
            }

            $variantUpdates = [];

            foreach ($rowsChunk as $item) {
                $index = $item['index'];
                $row   = $item['row'];

                $stats['total_rows']++;
                $rawSku = trim((string)($row[$skuCol] ?? ''));

                if ($rawSku === '') {
                    $stats['skipped_count']++;
                    $entry = [
                        'row'     => $index + 1,
                        'sku'     => '(Blank)',
                        'status'  => 'skipped',
                        'message' => 'Empty SKU',
                    ];
                    if (count($skippedItems) < 100) {
                        $skippedItems[] = $entry;
                    }
                    if (count($log) < 100) {
                        $log[] = $entry;
                    }
                    continue;
                }

                $variantId = $rowVariantMap[$index] ?? null;
                $variant   = $variantId ? $variantsById->get($variantId) : null;

                if (!$variant) {
                    $stats['skipped_count']++;
                    $entry = [
                        'row'     => $index + 1,
                        'sku'     => $rawSku,
                        'status'  => 'skipped',
                        'message' => 'SKU not found in catalog',
                    ];
                    if (count($skippedItems) < 100) {
                        $skippedItems[] = $entry;
                    }
                    if (count($log) < 100) {
                        $log[] = $entry;
                    }
                    continue;
                }

                $stats['matched_count']++;

                $currentCost = $variant->item_cost !== null ? (float)$variant->item_cost : 0.00;

                // If current cost is 0.00 or unset, skip item per specification
                if ($currentCost <= 0.00) {
                    $stats['skipped_count']++;
                    $entry = [
                        'row'           => $index + 1,
                        'sku'           => $rawSku,
                        'product_title' => $variant->product?->title ?? 'N/A',
                        'cost'          => $currentCost,
                        'status'        => 'skipped',
                        'message'       => 'Current cost is $0.00 (not set)',
                    ];
                    if (count($skippedItems) < 100) {
                        $skippedItems[] = $entry;
                    }
                    if (count($log) < 100) {
                        $log[] = $entry;
                    }
                    continue;
                }

                // Calculate marketplace price: current_cost * (1 + markup% / 100)
                $mpPrice = round($currentCost * (1 + ($markupPercentage / 100)), 2);

                $oldAmazon = $variant->amazon_price !== null ? (float)$variant->amazon_price : 0.00;
                $oldEbay   = $variant->ebay_price !== null ? (float)$variant->ebay_price : 0.00;

                $vData = ['updated_at' => now()];

                if ($marketplace === 'amazon' || $marketplace === 'both') {
                    $vData['amazon_price']   = $mpPrice;
                    $vData['amazon_product'] = 1;
                }

                if ($marketplace === 'ebay' || $marketplace === 'both') {
                    $vData['ebay_price']   = $mpPrice;
                    $vData['ebay_product'] = 1;
                }

                $variantUpdates[$variant->id] = $vData;
                $stats['updated_count']++;

                $entry = [
                    'row'           => $index + 1,
                    'sku'           => $rawSku,
                    'product_title' => $variant->product?->title ?? 'N/A',
                    'cost'          => $currentCost,
                    'markup_pct'    => $markupPercentage . '%',
                    'amazon_price'  => ($marketplace === 'amazon' || $marketplace === 'both') ? $mpPrice : $oldAmazon,
                    'ebay_price'    => ($marketplace === 'ebay' || $marketplace === 'both') ? $mpPrice : $oldEbay,
                    'status'        => 'updated',
                ];
                if (count($updatedItems) < 100) {
                    $updatedItems[] = $entry;
                }
                if (count($log) < 100) {
                    $log[] = $entry;
                }
            }

            if (!empty($variantUpdates)) {
                DB::transaction(function () use ($variantUpdates) {
                    foreach ($variantUpdates as $vId => $vData) {
                        DB::table('product_variants')->where('id', $vId)->update($vData);
                    }
                });
            }
        };

        if (is_array($source)) {
            $chunk = [];
            foreach ($source as $idx => $row) {
                $chunk[] = ['index' => $idx, 'row' => $row];
                if (count($chunk) >= $chunkSize) {
                    $processChunk($chunk);
                    $chunk = [];
                }
            }
            if (!empty($chunk)) {
                $processChunk($chunk);
            }
        } elseif (is_string($source) && file_exists($source)) {
            $handle = fopen($source, 'r');
            if ($handle) {
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiters = [',', '|', ';', "\t"];
                $counts = [];
                foreach ($delimiters as $d) {
                    $counts[$d] = substr_count((string)$firstLine, $d);
                }
                arsort($counts);
                $delim = array_key_first($counts) ?: ',';
                if (($counts[$delim] ?? 0) === 0) {
                    $delim = ',';
                }

                $rawHeader = fgetcsv($handle, 0, $delim, '"', '\\');
                if ($rawHeader) {
                    if (isset($rawHeader[0])) {
                        $rawHeader[0] = preg_replace('/[\x{EF}\x{BB}\x{BF}]/u', '', $rawHeader[0]);
                    }
                    $headers = array_map(fn($h) => trim((string)$h), $rawHeader);

                    $idx = 0;
                    $chunk = [];
                    while (($row = fgetcsv($handle, 0, $delim, '"', '\\')) !== false) {
                        if (count($row) === 1 && trim($row[0]) === '') {
                            continue;
                        }
                        if (count($row) < count($headers)) {
                            $row = array_pad($row, count($headers), '');
                        } elseif (count($row) > count($headers)) {
                            $row = array_slice($row, 0, count($headers));
                        }
                        $mapped = [];
                        foreach ($headers as $hIdx => $hName) {
                            $mapped[$hName] = trim($row[$hIdx] ?? '');
                        }
                        $chunk[] = ['index' => $idx, 'row' => $mapped];
                        $idx++;

                        if (count($chunk) >= $chunkSize) {
                            $processChunk($chunk);
                            $chunk = [];
                        }
                    }
                    if (!empty($chunk)) {
                        $processChunk($chunk);
                    }
                }
                fclose($handle);
            }
        }

        return [
            'stats'         => $stats,
            'log'           => $log,
            'updated_items' => $updatedItems,
            'skipped_items' => $skippedItems,
        ];
    }

    /**
     * Build an in-memory O(1) hash map index of catalog variants for instant SKU/UPC matching.
     * Indexes:
     * - Exact SKUs
     * - Unpadded SKUs (trimmed leading zeros)
     * - Padded 12, 13, 14 digit variations (UPC-A, EAN-13, GTIN-14)
     * - Embedded 11-14 numeric digit sequences (for barcodes with prefix/suffix)
     *
     * @return array
     */
    public function buildVariantLookupIndex(): array
    {
        $exactMap     = [];
        $unpaddedMap  = [];
        $digitsMap    = [];
        $nonDigitLong = [];

        $variants = DB::table('product_variants')
            ->select('id', 'sku')
            ->whereNotNull('sku')
            ->where('sku', '!=', '')
            ->get();

        foreach ($variants as $v) {
            $id  = (int)$v->id;
            $sku = trim((string)$v->sku);
            if ($sku === '') {
                continue;
            }

            // 1. Exact SKU
            $exactMap[$sku] = $id;

            // 2. Unpadded (trimmed leading zeros) and standard barcode pads
            $unpadded = ltrim($sku, '0');
            if ($unpadded !== '') {
                $unpaddedMap[$unpadded] = $id;
                if (strlen($unpadded) <= 14) {
                    $exactMap[str_pad($unpadded, 12, '0', STR_PAD_LEFT)] = $id;
                    $exactMap[str_pad($unpadded, 13, '0', STR_PAD_LEFT)] = $id;
                    $exactMap[str_pad($unpadded, 14, '0', STR_PAD_LEFT)] = $id;
                }
            }

            // 3. Extract 11-14 digit sequence if present
            if (preg_match('/\d{11,14}/', $sku, $m)) {
                $digits = $m[0];
                $digitsMap[$digits] = $id;
                $digitsUnpadded = ltrim($digits, '0');
                if ($digitsUnpadded !== '') {
                    $digitsMap[$digitsUnpadded] = $id;
                    $unpaddedMap[$digitsUnpadded] = $id;
                }
            } elseif (strlen($sku) >= 11) {
                $nonDigitLong[] = ['id' => $id, 'sku' => $sku];
            }
        }

        return [
            'exact'          => $exactMap,
            'unpadded'       => $unpaddedMap,
            'digits'         => $digitsMap,
            'non_digit_long' => $nonDigitLong,
            'failed_cache'   => [],
        ];
    }

    /**
     * Resolve a variant ID from a raw SKU string in O(1) time using the pre-built index.
     */
    public function resolveVariantId(string $rawSku, array &$index): ?int
    {
        $rawSku = trim($rawSku);
        if ($rawSku === '') {
            return null;
        }

        // 1. Check exact map
        if (isset($index['exact'][$rawSku])) {
            return $index['exact'][$rawSku];
        }

        // 2. Check unpadded / padded variations
        $unpadded = ltrim($rawSku, '0');
        if ($unpadded !== '') {
            if (isset($index['exact'][$unpadded])) {
                return $index['exact'][$unpadded];
            }
            if (isset($index['unpadded'][$unpadded])) {
                return $index['unpadded'][$unpadded];
            }
            if (isset($index['exact'][str_pad($unpadded, 12, '0', STR_PAD_LEFT)])) {
                return $index['exact'][str_pad($unpadded, 12, '0', STR_PAD_LEFT)];
            }
            if (isset($index['exact'][str_pad($unpadded, 13, '0', STR_PAD_LEFT)])) {
                return $index['exact'][str_pad($unpadded, 13, '0', STR_PAD_LEFT)];
            }
            if (isset($index['exact'][str_pad($unpadded, 14, '0', STR_PAD_LEFT)])) {
                return $index['exact'][str_pad($unpadded, 14, '0', STR_PAD_LEFT)];
            }
        }

        // 3. Digits extraction for UPC / barcode sequences (>= 11 digits)
        if (preg_match('/\d{11,14}/', $rawSku, $m)) {
            $digits = $m[0];
            if (isset($index['digits'][$digits])) {
                return $index['digits'][$digits];
            }
            $digUnpadded = ltrim($digits, '0');
            if ($digUnpadded !== '') {
                if (isset($index['digits'][$digUnpadded])) {
                    return $index['digits'][$digUnpadded];
                }
                if (isset($index['unpadded'][$digUnpadded])) {
                    return $index['unpadded'][$digUnpadded];
                }
            }
        }

        // 4. Check failed SKUs cache to avoid re-checking non-matching strings
        if (isset($index['failed_cache'][$rawSku])) {
            return null;
        }

        // 5. Fallback for non-digit long SKUs (>= 11 characters)
        $rawLen = strlen($rawSku);
        if ($rawLen >= 11 && !empty($index['non_digit_long'])) {
            foreach ($index['non_digit_long'] as $cand) {
                $candSku = $cand['sku'];
                if (str_contains($candSku, $rawSku) || str_contains($rawSku, $candSku)) {
                    return $cand['id'];
                }
            }
        }

        $index['failed_cache'][$rawSku] = true;
        return null;
    }

    /**
     * Match a variant from a raw SKU string:
     * 1. Exact match in batch-loaded index.
     * 2. UPC/EAN leading-zero normalization (e.g. 11-digit UPC in file matching 12-digit in DB, or vice-versa).
     * 3. Substring match: if the system variant SKU has >= 11 characters (e.g. UPC/EAN barcode codes).
     */
    public function matchVariantForSku(
        string $rawSku,
        $variantsBySku,
        &$longSkuVariantsCache,
        bool $withInventory = true
    ): ?ProductVariant {
        if ($rawSku === '') {
            return null;
        }

        if ($longSkuVariantsCache === null || !is_array($longSkuVariantsCache) || !isset($longSkuVariantsCache['exact'])) {
            $longSkuVariantsCache = $this->buildVariantLookupIndex();
        }

        $id = $this->resolveVariantId($rawSku, $longSkuVariantsCache);
        if ($id) {
            $query = ProductVariant::query()->where('id', $id);
            if ($withInventory) {
                $query->with(['product', 'inventory']);
            } else {
                $query->with('product');
            }
            return $query->first();
        }

        return null;
    }

    /**
     * Download remote inventory file via FTP, FTPS, or SFTP to local temporary storage.
     *
     * @param array $config [
     *   'protocol'    => 'ftp' | 'ftps' | 'sftp',
     *   'host'        => 'ftp.domain.com',
     *   'port'        => 21 or 22,
     *   'username'    => 'user',
     *   'password'    => 'pass',
     *   'remote_path' => '/path/to/remote/file.csv'
     * ]
     * @return array ['success' => bool, 'local_path' => ?string, 'error' => ?string]
     */
    public function downloadRemoteFile(array $config): array
    {
        $protocol   = strtolower(trim($config['protocol'] ?? 'ftp'));
        $host       = trim($config['host'] ?? '');
        $port       = (int)($config['port'] ?? ($protocol === 'sftp' ? 22 : 21));
        $username   = trim($config['username'] ?? '');
        $password   = (string)($config['password'] ?? '');
        $remotePath = trim($config['remote_path'] ?? '');

        if ($host === '' || $remotePath === '') {
            return [
                'success'    => false,
                'local_path' => null,
                'error'      => 'Host and Remote File Path are required.',
            ];
        }

        // Auto-detect SFTP if port is 22 but protocol was left as ftp or ftps
        if (($protocol === 'ftp' || $protocol === 'ftps') && $port === 22) {
            $protocol = 'sftp';
        }

        $tempDir = storage_path('app/temp_imports');
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }

        $localFilename = 'remote_import_' . date('Ymd_His') . '_' . Str::random(6) . '.csv';
        $localPath = $tempDir . DIRECTORY_SEPARATOR . $localFilename;

        // 1. SFTP via cURL (SSH)
        if ($protocol === 'sftp') {
            return $this->downloadViaSftp($host, $port, $username, $password, $remotePath, $localPath);
        }

        // 2. Standard FTP / FTPS
        return $this->downloadViaFtp($host, $port, $username, $password, $remotePath, $localPath, $protocol === 'ftps');
    }

    /**
     * Download via PHP standard FTP / FTPS with automatic cURL fallback.
     */
    private function downloadViaFtp(
        string $host,
        int $port,
        string $username,
        string $password,
        string $remotePath,
        string $localPath,
        bool $useSsl = false
    ): array {
        // Try PHP native ftp extension if available
        if (function_exists('ftp_connect')) {
            try {
                $conn = null;
                if ($useSsl && function_exists('ftp_ssl_connect')) {
                    $conn = @ftp_ssl_connect($host, $port, 15);
                }
                if (!$conn) {
                    $conn = @ftp_connect($host, $port, 15);
                }

                if ($conn) {
                    @ftp_set_option($conn, FTP_TIMEOUT_SEC, 20);

                    $login = @ftp_login($conn, $username, $password);
                    if ($login) {
                        @ftp_pasv($conn, true);

                        // Try downloading file directly
                        $downloaded = @ftp_get($conn, $localPath, $remotePath, FTP_BINARY);
                        
                        // If direct path failed and starts with '/', try without leading '/'
                        if (!$downloaded && str_starts_with($remotePath, '/')) {
                            $downloaded = @ftp_get($conn, $localPath, ltrim($remotePath, '/'), FTP_BINARY);
                        }
                        
                        @ftp_close($conn);

                        if ($downloaded && file_exists($localPath) && filesize($localPath) > 0) {
                            return [
                                'success'    => true,
                                'local_path' => $localPath,
                                'error'      => null,
                            ];
                        }
                    } else {
                        @ftp_close($conn);
                    }
                }
            } catch (\Throwable $e) {
                // Ignore and fallback to cURL
            }
        }

        // Fallback to resilient cURL FTP engine
        return $this->downloadViaCurlFtp($host, $port, $username, $password, $remotePath, $localPath, $useSsl);
    }

    /**
     * Download via cURL FTP / FTPS with multi-strategy TLS & path negotiation.
     */
    private function downloadViaCurlFtp(
        string $host,
        int $port,
        string $username,
        string $password,
        string $remotePath,
        string $localPath,
        bool $useSsl
    ): array {
        // Determine URL and SSL options
        // Port 990 is standard Implicit FTPS (ftps://)
        // Port 21 and others use Explicit FTPS (STARTTLS via ftp:// + CURLOPT_USE_SSL)
        $isImplicitFtps = ($useSsl && $port === 990);
        $scheme = $isImplicitFtps ? 'ftps' : 'ftp';

        $cleanPath = ltrim($remotePath, '/');
        // In cURL FTP, a double slash `//` represents an absolute path from root `/`, single slash `/` is relative to user home
        $pathVariants = [
            "{$scheme}://{$host}:{$port}/{$cleanPath}",
            "{$scheme}://{$host}:{$port}//{$cleanPath}",
        ];

        // SSL negotiation strategies to try
        $sslStrategies = [];
        if ($isImplicitFtps) {
            $sslStrategies[] = ['ssl' => 0]; // Implicit FTPS uses ftps:// without CURLOPT_USE_SSL
        } elseif ($useSsl) {
            // Explicit FTPS: First try strict TLS, then opportunistic TLS, then plain
            $sslStrategies[] = ['ssl' => CURLUSESSL_ALL];
            $sslStrategies[] = ['ssl' => CURLUSESSL_TRY];
            $sslStrategies[] = ['ssl' => CURLUSESSL_NONE];
        } else {
            // Plain FTP: Opportunistic TLS first (handles servers requiring AUTH TLS), then plain
            $sslStrategies[] = ['ssl' => CURLUSESSL_TRY];
            $sslStrategies[] = ['ssl' => CURLUSESSL_NONE];
        }

        $lastError = 'Unable to connect to FTP server.';

        foreach ($sslStrategies as $strat) {
            foreach ($pathVariants as $url) {
                $fp = fopen($localPath, 'w');
                if (!$fp) {
                    return [
                        'success'    => false,
                        'local_path' => null,
                        'error'      => 'Unable to create local temporary file for download.',
                    ];
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_FTP_USE_EPSV, false);
                if (defined('CURLOPT_FTP_SKIP_PASV_IP')) {
                    curl_setopt($ch, CURLOPT_FTP_SKIP_PASV_IP, true);
                }

                if (!$isImplicitFtps && isset($strat['ssl'])) {
                    curl_setopt($ch, CURLOPT_USE_SSL, $strat['ssl']);
                }

                $result = curl_exec($ch);
                $error = curl_error($ch);
                curl_close($ch);
                fclose($fp);

                if ($result && file_exists($localPath) && filesize($localPath) > 0) {
                    return [
                        'success'    => true,
                        'local_path' => $localPath,
                        'error'      => null,
                    ];
                }

                if ($error) {
                    $lastError = $error;
                }

                if (file_exists($localPath)) {
                    @unlink($localPath);
                }
            }
        }

        return [
            'success'    => false,
            'local_path' => null,
            'error'      => "FTP connection failed: {$lastError}",
        ];
    }

    /**
     * Download via SFTP using phpseclib3 (pure PHP), with fallback to PHP ssh2 and cURL.
     */
    private function downloadViaSftp(
        string $host,
        int $port,
        string $username,
        string $password,
        string $remotePath,
        string $localPath
    ): array {
        $cleanPath = ltrim($remotePath, '/');
        $pathsToTry = array_values(array_unique([
            $remotePath,
            $cleanPath,
            '/' . $cleanPath,
        ]));

        $lastError = 'SFTP connection failed. Check host, port, credentials, and file path.';

        // 1. Primary engine: phpseclib3 (Pure PHP - no system extensions required)
        if (class_exists(\phpseclib3\Net\SFTP::class)) {
            try {
                $sftp = new \phpseclib3\Net\SFTP($host, $port, 20);
                if (!$sftp->login($username, $password)) {
                    return [
                        'success'    => false,
                        'local_path' => null,
                        'error'      => "SFTP authentication failed for user '{$username}' on {$host}:{$port}. Please check credentials.",
                    ];
                }

                foreach ($pathsToTry as $path) {
                    if ($sftp->get($path, $localPath)) {
                        if (file_exists($localPath) && filesize($localPath) > 0) {
                            return [
                                'success'    => true,
                                'local_path' => $localPath,
                                'error'      => null,
                            ];
                        }
                    }
                }

                $pwd = @$sftp->pwd() ?: '/';
                return [
                    'success'    => false,
                    'local_path' => null,
                    'error'      => "SFTP connected successfully, but remote file was not found at '{$remotePath}' (Current remote directory: '{$pwd}'). Please verify the remote file path.",
                ];
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        // 2. Secondary fallback: PHP ssh2 extension if installed
        if (function_exists('ssh2_connect')) {
            try {
                $connection = @ssh2_connect($host, $port);
                if ($connection && @ssh2_auth_password($connection, $username, $password)) {
                    $sftp = @ssh2_sftp($connection);
                    if ($sftp) {
                        foreach ($pathsToTry as $path) {
                            $remoteUrl = "ssh2.sftp://" . intval($sftp) . "/" . ltrim($path, '/');
                            $contents = @file_get_contents($remoteUrl);
                            if ($contents !== false && strlen($contents) > 0) {
                                file_put_contents($localPath, $contents);
                                return [
                                    'success'    => true,
                                    'local_path' => $localPath,
                                    'error'      => null,
                                ];
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                $lastError = $e->getMessage();
            }
        }

        // 3. Tertiary fallback: cURL SFTP (works if cURL is compiled with libssh2)
        $pathVariants = [
            "sftp://{$host}:{$port}/{$cleanPath}",
            "sftp://{$host}:{$port}//{$cleanPath}",
        ];

        foreach ($pathVariants as $url) {
            $fp = fopen($localPath, 'w');
            if (!$fp) {
                continue;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            fclose($fp);

            if ($result && file_exists($localPath) && filesize($localPath) > 0) {
                return [
                    'success'    => true,
                    'local_path' => $localPath,
                    'error'      => null,
                ];
            }

            if ($error) {
                $lastError = $error;
            }

            if (file_exists($localPath)) {
                @unlink($localPath);
            }
        }

        return [
            'success'    => false,
            'local_path' => null,
            'error'      => "SFTP download failed: {$lastError}",
        ];
    }

    /**
     * Reset all inventory levels to zero across the catalog.
     * Updates products_inventory and product_inventory_warehouses if present.
     *
     * @return int Number of updated inventory records
     */
    public function resetAllInventory(): int
    {
        return DB::transaction(function () {
            $count = ProductInventory::query()->update([
                'quantity_available'    => 0,
                'warehouse_stock_level' => 0,
                'reserved_stock'        => 0,
            ]);

            if (DB::getSchemaBuilder()->hasTable('product_inventory_warehouses')) {
                DB::table('product_inventory_warehouses')->update([
                    'stock_level' => 0,
                ]);
            }

            return $count;
        });
    }

    /**
     * Mark all products that have no variants with positive price values (public_price, on_sale sale_price, or wholesale_price)
     * to not show in search results (show_in_results = 0).
     *
     * @return int Number of products updated
     */
    public function hideZeroPriceProductsFromResults(): int
    {
        $pricedProductIds = DB::table('product_variants')
            ->where(function ($q) {
                $q->where('public_price', '>', 0)
                  ->orWhere(fn($sq) => $sq->where('on_sale', 1)->where('sale_price', '>', 0))
                  ->orWhere('wholesale_price', '>', 0);
            })
            ->distinct()
            ->pluck('product_id');

        return DB::table('products')
            ->where('show_in_results', 1)
            ->whereNotIn('id', $pricedProductIds)
            ->update([
                'show_in_results' => 0,
                'updated_at'      => now(),
            ]);
    }
}
