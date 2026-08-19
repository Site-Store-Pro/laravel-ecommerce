<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
class AdminReports extends Component
{
    public string $activeTab = 'report_order_activity';

    // Order Export Filters
    public string $orderStartDate = '';
    public string $orderEndDate = '';
    public string $orderExportFormat = 'csv';

    // Tax / VAT Report Filters
    public string $taxStartDate = '';
    public string $taxEndDate = '';
    public string $taxCountry = '';
    public string $taxState = '';
    public string $taxExportFormat = 'csv';

    // Product Export Filters
    public string $productExportFormat = 'csv';
    public string $amazonExportFormat  = 'csv';
    public string $ebayExportFormat    = 'csv';

    public function mount(): void
    {
        abort_unless(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOrderProcessor()), 403, 'Unauthorized reports access.');

        $this->orderStartDate = now()->subDays(30)->format('Y-m-d');
        $this->orderEndDate = now()->format('Y-m-d');

        $this->taxStartDate = now()->subDays(30)->format('Y-m-d');
        $this->taxEndDate = now()->format('Y-m-d');
    }

    public function updatedTaxCountry(): void
    {
        $this->taxState = '';
    }

    // ── 1. ORDER EXPORT ───────────────────────────────────────────────────────

    public function exportOrders(): StreamedResponse
    {
        $query = Order::with(['user', 'details.variant'])
            ->whereBetween('order_date', [
                $this->orderStartDate . ' 00:00:00',
                $this->orderEndDate . ' 23:59:59'
            ])
            ->latest('order_date');

        $orders = $query->get();

        $rows = [];
        $headers = [
            'Order ID', 'Invoice No', 'External ID', 'Order Date', 'Order Status',
            'Customer Name', 'Customer Email', 'Shipping Address', 'Shipping City',
            'Shipping State', 'Shipping Postal Code', 'Shipping Country',
            'Line Item Name', 'Variant / SKU', 'Item Quantity', 'Unit Price', 'Line Total',
            'Order Subtotal', 'Shipping Amount', 'Handling Amount', 'Tax Amount', 'Discounts Applied', 'Order Total'
        ];
        $rows[] = $headers;

        foreach ($orders as $order) {
            $user = $order->user;
            $customerName  = $user ? $user->name : 'Guest Customer';
            $customerEmail = $user ? $user->email : '-';
            $addr1         = $user ? trim(($user->shipping_address1 ?? '') . ' ' . ($user->shipping_address2 ?? '')) : '-';
            $city          = $user ? ($user->shipping_city ?? '-') : '-';
            $state         = $user ? ($user->shipping_state ?? '-') : '-';
            $zip           = $user ? ($user->shopping_postalcode ?? '-') : '-';
            $country       = $user ? ($user->shipping_country ?? '-') : '-';

            $statusText = match ((int)$order->order_status) {
                1 => 'Pending',
                2 => 'Shipped',
                3 => 'Refunded',
                4 => 'Canceled',
                5 => 'Partially Shipped',
                6 => 'Back Ordered',
                7 => 'Completed',
                default => 'Awaiting Payment',
            };

            if ($order->details->isNotEmpty()) {
                foreach ($order->details as $detail) {
                    $rows[] = [
                        $order->id,
                        $order->order_invoice_no,
                        $order->order_external_id ?: '-',
                        $order->order_date ? $order->order_date->format('Y-m-d H:i:s') : '',
                        $statusText,
                        $customerName,
                        $customerEmail,
                        $addr1,
                        $city,
                        $state,
                        $zip,
                        $country,
                        $detail->item_name,
                        $detail->variant ? ($detail->variant->name ?: $detail->variant->sku) : '-',
                        (int)$detail->item_qty,
                        number_format((float)$detail->final_price, 2, '.', ''),
                        number_format((float)$detail->final_price * (float)$detail->item_qty, 2, '.', ''),
                        number_format((float)$order->order_subtotal, 2, '.', ''),
                        number_format((float)$order->order_shipping, 2, '.', ''),
                        number_format((float)$order->order_handling, 2, '.', ''),
                        number_format((float)$order->order_taxes, 2, '.', ''),
                        number_format((float)$order->order_discounts, 2, '.', ''),
                        number_format((float)$order->order_total, 2, '.', ''),
                    ];
                }
            } else {
                $rows[] = [
                    $order->id,
                    $order->order_invoice_no,
                    $order->order_external_id ?: '-',
                    $order->order_date ? $order->order_date->format('Y-m-d H:i:s') : '',
                    $statusText,
                    $customerName,
                    $customerEmail,
                    $addr1,
                    $city,
                    $state,
                    $zip,
                    $country,
                    '-', '-', 0, '0.00', '0.00',
                    number_format((float)$order->order_subtotal, 2, '.', ''),
                    number_format((float)$order->order_shipping, 2, '.', ''),
                    number_format((float)$order->order_handling, 2, '.', ''),
                    number_format((float)$order->order_taxes, 2, '.', ''),
                    number_format((float)$order->order_discounts, 2, '.', ''),
                    number_format((float)$order->order_total, 2, '.', ''),
                ];
            }
        }

        $filename = 'orders_export_' . $this->orderStartDate . '_to_' . $this->orderEndDate;

        if ($this->orderExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 2. SALES TAX / VAT EXPORT ─────────────────────────────────────────────

    public function exportTaxReport(): StreamedResponse
    {
        $query = Order::with('user')
            ->whereBetween('order_date', [
                $this->taxStartDate . ' 00:00:00',
                $this->taxEndDate . ' 23:59:59'
            ]);

        if (!empty($this->taxCountry)) {
            $query->whereHas('user', function ($q) {
                $q->where('shipping_countrycode', $this->taxCountry)
                  ->orWhere('shipping_country', $this->taxCountry);
            });
        }

        if (!empty($this->taxState)) {
            $query->whereHas('user', function ($q) {
                $q->where('shipping_state', $this->taxState);
            });
        }

        $orders = $query->latest('order_date')->get();

        $rows = [];
        $headers = [
            'Order ID', 'Invoice No', 'Order Date', 'Customer Name', 'Customer Email',
            'Shipping Country', 'Shipping State', 'Taxable Subtotal', 'Tax Rate / Method', 'Tax / VAT Amount', 'Order Total'
        ];
        $rows[] = $headers;

        foreach ($orders as $order) {
            $user = $order->user;
            $rows[] = [
                $order->id,
                $order->order_invoice_no,
                $order->order_date ? $order->order_date->format('Y-m-d H:i:s') : '',
                $user ? $user->name : 'Guest Customer',
                $user ? $user->email : '-',
                $user ? ($user->shipping_countrycode ?: $user->shipping_country) : '-',
                $user ? ($user->shipping_state ?: '-') : '-',
                number_format((float)$order->order_subtotal, 2, '.', ''),
                ((float)$order->order_subtotal > 0 && (float)$order->order_taxes > 0)
                    ? number_format(((float)$order->order_taxes / (float)$order->order_subtotal) * 100, 2, '.', '') . '%'
                    : '0.00%',
                number_format((float)$order->order_taxes, 2, '.', ''),
                number_format((float)$order->order_total, 2, '.', ''),
            ];
        }

        $filename = 'tax_vat_report_' . $this->taxStartDate . '_to_' . $this->taxEndDate;

        if ($this->taxExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 3. PRODUCT EXPORT ─────────────────────────────────────────────────────

    public function exportProducts(): StreamedResponse
    {
        $products = Product::with(['variants.inventory', 'categories', 'brand', 'images'])
            ->orderBy('id')
            ->get();

        $rows = [];
        $headers = [
            'Title', 'Short Description', 'Long Description', 'Category', 'Brand',
            'Public Price', 'Wholesale Price', 'Thumbnail URL', 'Main Image URL', 'Zoom Images URL',
            'Image Source Type', 'Variant SKU', 'Variant Name', 'Variant Attributes', 'Variant Price',
            'Variant Wholesale Price', 'Stock Quantity'
        ];
        $rows[] = $headers;

        foreach ($products as $product) {
            $categoryNames = $product->categories->pluck('name')->implode(', ');
            $brandName     = $product->brand ? $product->brand->name : '';
            $thumbUrl      = $product->primaryThumbnailUrl() ?: '';
            $mainImgUrl    = $product->primaryMainImageUrl() ?: '';
            $zoomUrls      = $product->images->pluck('image_url')->implode(', ');

            if ($product->variants->isNotEmpty()) {
                foreach ($product->variants as $variant) {
                    $attrString = '';
                    if (!empty($variant->attributes_json)) {
                        $attrs = is_array($variant->attributes_json) ? $variant->attributes_json : json_decode($variant->attributes_json, true);
                        if (is_array($attrs)) {
                            $pairs = [];
                            foreach ($attrs as $k => $v) {
                                $pairs[] = "$k:$v";
                            }
                            $attrString = implode(', ', $pairs);
                        }
                    }

                    $stock = $variant->inventory ? $variant->inventory->quantity : 0;

                    $rows[] = [
                        $product->title,
                        $product->short_description ?: '',
                        $product->long_description ?: '',
                        $categoryNames,
                        $brandName,
                        number_format((float)$variant->price, 2, '.', ''),
                        number_format((float)($variant->wholesale_price ?: $variant->price), 2, '.', ''),
                        $thumbUrl,
                        $mainImgUrl,
                        $zoomUrls,
                        '0',
                        $variant->sku ?: '',
                        $variant->name ?: '',
                        $attrString,
                        number_format((float)$variant->price, 2, '.', ''),
                        number_format((float)($variant->wholesale_price ?: $variant->price), 2, '.', ''),
                        (string)$stock,
                    ];
                }
            } else {
                $rows[] = [
                    $product->title,
                    $product->short_description ?: '',
                    $product->long_description ?: '',
                    $categoryNames,
                    $brandName,
                    '0.00', '0.00',
                    $thumbUrl,
                    $mainImgUrl,
                    $zoomUrls,
                    '0', '', '', '', '0.00', '0.00', '0'
                ];
            }
        }

        $filename = 'products_export_' . now()->format('Y-m-d');

        if ($this->productExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 4. AMAZON PRODUCT EXPORT ──────────────────────────────────────────────

    public function exportAmazonProducts(): StreamedResponse
    {
        $variants = \App\Models\ProductVariant::with('product')
            ->where('amazon_product', true)
            ->get();

        $rows = [];
        $rows[] = [
            'SKU',
            'Title',
            'long_description',
            'Price',
            'ASIN',
            'amazon_bullet_points',
            'amazon_item_type',
            'amazon_condition',
        ];

        foreach ($variants as $variant) {
            $product = $variant->product;
            if (!$product) continue;

            $price = ($variant->amazon_price !== null && (float)$variant->amazon_price > 0)
                ? (float)$variant->amazon_price
                : (float)$variant->public_price;

            $rows[] = [
                $variant->sku ?: '',
                $product->title ?: '',
                $product->long_description ?: '',
                number_format($price, 2, '.', ''),
                $variant->amazon_asin ?: '',
                $variant->amazon_bullet_points ?: '',
                $variant->amazon_item_type ?: '',
                $variant->amazon_condition ?: 'New',
            ];
        }

        $filename = 'amazon_products_export_' . now()->format('Y-m-d');

        if ($this->amazonExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 5. EBAY PRODUCT EXPORT ────────────────────────────────────────────────

    public function exportEbayProducts(): StreamedResponse
    {
        $variants = \App\Models\ProductVariant::with('product')
            ->where('ebay_product', true)
            ->get();

        $rows = [];
        $rows[] = [
            'SKU',
            'Title',
            'long_description',
            'Price',
            'eBay_category_id',
            'eBay_listing_type',
            'ebay_options',
            'ebay_shipping_profile_id',
            'ebay_return_policy_id',
        ];

        foreach ($variants as $variant) {
            $product = $variant->product;
            if (!$product) continue;

            $price = ($variant->ebay_price !== null && (float)$variant->ebay_price > 0)
                ? (float)$variant->ebay_price
                : (float)$variant->public_price;

            $rows[] = [
                $variant->sku ?: '',
                $product->title ?: '',
                $product->long_description ?: '',
                number_format($price, 2, '.', ''),
                $variant->ebay_category_id ?: '',
                $variant->ebay_listing_type ?: 'Fixed Price',
                $variant->ebay_options ?: '',
                $variant->ebay_shipping_profile_id ?: '',
                $variant->ebay_return_policy_id ?: '',
            ];
        }

        $filename = 'ebay_products_export_' . now()->format('Y-m-d');

        if ($this->ebayExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 6. MULTI-WAREHOUSE INVENTORY EXPORT ───────────────────────────────────

    public string $inventoryExportFormat = 'csv';

    public function exportMultiWarehouseInventory(): StreamedResponse
    {
        $inventories = ProductInventory::with([
            'variant.product',
            'primaryWarehouse',
            'warehouseInventories.warehouseLocation'
        ])
        ->orderBy('id')
        ->get();

        $rows = [];
        $rows[] = [
            'Product Title',
            'SKU',
            'Shelf Stock (Available)',
            'Primary Warehouse Facility',
            'Primary Warehouse Code',
            'Main Warehouse Stock',
            'Use Warehouse Stock',
            'Reserved Stock',
            'Child Warehouse Locations & Quantities',
            'Calculated Total Available Stock',
        ];

        foreach ($inventories as $inv) {
            $productTitle = $inv->variant?->product?->title ?? 'N/A';
            $sku          = $inv->variant?->sku ?? 'N/A';
            $primaryName  = $inv->primaryWarehouse?->name ?? 'Main Warehouse';
            $primaryCode  = $inv->primaryWarehouse?->code ?? 'MAIN-01';

            $childList = [];
            if ($inv->warehouseInventories->isNotEmpty()) {
                foreach ($inv->warehouseInventories as $wChild) {
                    $facilityName = $wChild->warehouseLocation?->name ?? "Warehouse #{$wChild->warehouse_location_id}";
                    $facilityCode = $wChild->warehouseLocation?->code ?? '';
                    $childList[]  = "{$facilityName}" . ($facilityCode ? " ({$facilityCode})" : "") . ": {$wChild->stock_level}";
                }
            }
            $childStr = implode(' | ', $childList);

            $rows[] = [
                $productTitle,
                $sku,
                (int) $inv->quantity_available,
                $primaryName,
                $primaryCode,
                (int) $inv->warehouse_stock_level,
                $inv->use_warehouse_stock ? 'Yes' : 'No',
                (int) $inv->reserved_stock,
                $childStr ?: 'None',
                (int) $inv->available_stock,
            ];
        }

        $filename = 'multi_warehouse_inventory_export_' . now()->format('Y-m-d');

        if ($this->inventoryExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 7. RETAIL & WHOLESALE CUSTOMERS EXPORT ─────────────────────────────

    public string $customerExportFormat = 'csv';

    public function exportCustomers(): StreamedResponse
    {
        $users = User::where(function ($query) {
            $query->whereIn('role_id', [1, 2])
                  ->orWhereExists(function ($sub) {
                      $sub->select(DB::raw(1))
                          ->from('orders')
                          ->whereColumn('orders.order_user_id', 'users.id');
                  });
        })
        ->orderBy('id')
        ->get();

        $rows = [];
        $rows[] = [
            'User ID',
            'Full Name',
            'Email',
            'Account Role',
            'Opt-in Status',
            'Registered Date'
        ];

        foreach ($users as $user) {
            $roleLabel = is_object($user->role_id) ? $user->role_id->label() : ($user->role_id == 2 ? 'Wholesale' : 'Customer');
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $roleLabel,
                (int) $user->opt_in,
                $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
            ];
        }

        $filename = 'customers_export_' . now()->format('Y-m-d');

        if ($this->customerExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── 8. OPT-IN SUBSCRIBERS EXPORT ───────────────────────────────────────

    public string $optInExportFormat = 'csv';

    public function exportOptInSubscribers(): StreamedResponse
    {
        $users = User::whereIn('role_id', [1, 2])
            ->where('opt_in', 1)
            ->orderBy('id')
            ->get();

        $rows = [];
        $rows[] = [
            'Full Name',
            'First Name',
            'Last Name',
            'Email',
            'Opt-in Status'
        ];

        foreach ($users as $user) {
            $fullName = trim($user->name);
            $parts = explode(' ', $fullName, 2);
            $firstName = $parts[0] ?? '';
            $lastName = $parts[1] ?? '';

            $rows[] = [
                $fullName,
                $firstName,
                $lastName,
                $user->email,
                1 // 1 for everybody in this report
            ];
        }

        $filename = 'optin_subscribers_export_' . now()->format('Y-m-d');

        if ($this->optInExportFormat === 'xlsx') {
            return $this->streamExcel($rows, $filename . '.xlsx');
        }

        return $this->streamCsv($rows, $filename . '.csv');
    }

    // ── HELPERS: STREAMING CSV & EXCEL ────────────────────────────────────────

    protected function streamCsv(array $rows, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function streamExcel(array $rows, string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($rows as $rowIndex => $rowValues) {
            foreach ($rowValues as $colIndex => $cellValue) {
                $colLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($colLetter . ($rowIndex + 1), $cellValue);
            }
        }

        $headers = [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── RENDER ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        // 1. High level metrics
        $totalSales = DB::table('order_payments')->where('payment_status', 1)->sum('payment_amount');
        $totalOrdersCount = DB::table('orders')->count();
        $pendingOrdersCount = DB::table('orders')->whereIn('order_status', [1, 5, 6, 10])->count();
        $customersCount = DB::table('users')
            ->where(function ($query) {
                $query->whereIn('role_id', [\App\Enums\UserRole::User->value, \App\Enums\UserRole::Wholesale->value])
                      ->orWhereExists(function ($sub) {
                          $sub->select(DB::raw(1))
                              ->from('orders')
                              ->whereColumn('orders.order_user_id', 'users.id');
                      });
            })
            ->count();

        // 2. Tax Report calculated summary
        $taxQuery = Order::with('user')
            ->whereBetween('order_date', [
                $this->taxStartDate . ' 00:00:00',
                $this->taxEndDate . ' 23:59:59'
            ]);

        if (!empty($this->taxCountry)) {
            $taxQuery->whereHas('user', function ($q) {
                $q->where('shipping_countrycode', $this->taxCountry)
                  ->orWhere('shipping_country', $this->taxCountry);
            });
        }

        if (!empty($this->taxState)) {
            $taxQuery->whereHas('user', function ($q) {
                $q->where('shipping_state', $this->taxState);
            });
        }

        $taxOrders = $taxQuery->latest('order_date')->get();
        $taxableSalesTotal = $taxOrders->sum('order_subtotal');
        $taxCollectedTotal = $taxOrders->sum('order_taxes');

        // 3. Dropdown options for countries & states
        $countries = DB::table('shipping_countries')
            ->orderBy('name')
            ->get();

        $states = collect();
        if (in_array(strtoupper($this->taxCountry), ['US', 'CA'])) {
            $states = DB::table('shipping_states')
                ->where('country_code', strtoupper($this->taxCountry))
                ->orderBy('name')
                ->get();
        }

        return view('livewire.admin-reports', [
            'totalSales'         => $totalSales,
            'totalOrdersCount'   => $totalOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'customersCount'     => $customersCount,
            'taxOrders'          => $taxOrders,
            'taxableSalesTotal'  => $taxableSalesTotal,
            'taxCollectedTotal'  => $taxCollectedTotal,
            'countries'          => $countries,
            'states'             => $states,
        ]);
    }
}
