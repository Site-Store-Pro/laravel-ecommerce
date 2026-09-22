<?php

namespace App\Livewire;

use App\Models\ProductInventory;
use App\Services\InventoryImportService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminInventory extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $activeTab = 'stock'; // 'stock', 'csv_import', 'ftp_import', 'marketplace', 'reset'

    public string $search = '';
    public array $stockInputs = [];
    public array $warehouseInputs = [];
    public array $useWarehouseInputs = [];
    public array $reservedInputs = [];

    // --- Tab 2: Custom CSV Stock & Cost Import Wizard ---
    public $csvFile;
    public ?string $csvTempPath = null;
    public float $markupPercentage = 0.0;
    public string $inventoryMode = 'replace'; // 'replace' | 'add'
    public bool $autoShowInResults = false;
    public int $importStep = 1; // 1: upload & settings, 2: column mapping, 3: summary results
    public array $csvHeaders = [];
    public array $csvPreviewRows = [];
    public int $csvTotalRows = 0;
    public string $skuColumn = '';
    public string $costColumn = '';
    public string $stockColumn = '';
    public array $importResults = [];

    // --- Tab 3: FTP / SFTP Remote Feed Wizard ---
    public string $ftpProtocol = 'ftp'; // 'ftp', 'ftps', 'sftp'
    public string $ftpHost = '';
    public int $ftpPort = 21;
    public string $ftpUsername = '';
    public string $ftpPassword = '';
    public string $ftpRemotePath = '';
    public float $ftpMarkupPercentage = 0.0;
    public string $ftpInventoryMode = 'replace';
    public bool $ftpAutoShowInResults = false;
    public int $ftpStep = 1; // 1: connection config, 2: column mapping, 3: summary results
    public array $ftpHeaders = [];
    public array $ftpPreviewRows = [];
    public int $ftpTotalRows = 0;
    public string $ftpSkuColumn = '';
    public string $ftpCostColumn = '';
    public string $ftpStockColumn = '';
    public array $ftpResults = [];
    public ?string $ftpTempFilePath = null;

    // --- Tab 4: Amazon / eBay Marketplace Pricing Wizard ---
    public $marketplaceCsvFile;
    public ?string $marketplaceTempPath = null;
    public string $targetMarketplace = 'both'; // 'amazon', 'ebay', 'both'
    public float $marketplaceMarkup = 15.0;
    public int $marketplaceStep = 1; // 1: upload & settings, 2: column mapping, 3: summary results
    public array $marketplaceHeaders = [];
    public array $marketplacePreviewRows = [];
    public int $marketplaceTotalRows = 0;
    public string $marketplaceSkuColumn = '';
    public array $marketplaceResults = [];

    // --- Tab 5: Reset All Inventory to Zero & Bulk Visibility Tools ---
    public string $resetConfirmationText = '';
    public bool $resetConfirmed = false;
    public string $hideZeroConfirmationText = '';
    public bool $hideZeroConfirmed = false;

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403, 'Unauthorized e-commerce admin access.');

        // Load saved FTP / SFTP configuration from settings
        $this->ftpProtocol            = \App\Models\CmsSetting::get('ftp_protocol', 'ftp') ?: 'ftp';
        $this->ftpHost                = \App\Models\CmsSetting::get('ftp_host', '') ?: '';
        $this->ftpPort                = (int)(\App\Models\CmsSetting::get('ftp_port', 21) ?: 21);
        $this->ftpUsername            = \App\Models\CmsSetting::get('ftp_username', '') ?: '';
        $this->ftpPassword            = \App\Models\CmsSetting::get('ftp_password', '') ?: '';
        $this->ftpRemotePath          = \App\Models\CmsSetting::get('ftp_remote_path', '') ?: '';
        $this->ftpMarkupPercentage    = (float)(\App\Models\CmsSetting::get('ftp_markup_percentage', 0.0) ?: 0.0);
        $this->ftpInventoryMode       = \App\Models\CmsSetting::get('ftp_inventory_mode', 'replace') ?: 'replace';
        $this->ftpAutoShowInResults   = \App\Models\CmsSetting::isEnabled('ftp_auto_show_in_results');
    }

    public function saveStock(int $inventoryId): void
    {
        $this->validate([
            "stockInputs.{$inventoryId}" => 'required|integer|min:0',
            "warehouseInputs.{$inventoryId}" => 'required|integer|min:0',
            "useWarehouseInputs.{$inventoryId}" => 'required|boolean',
            "reservedInputs.{$inventoryId}" => 'required|integer|min:0',
        ]);

        $item = ProductInventory::findOrFail($inventoryId);
        $item->quantity_available = $this->stockInputs[$inventoryId];
        $item->warehouse_stock_level = $this->warehouseInputs[$inventoryId];
        $item->use_warehouse_stock = $this->useWarehouseInputs[$inventoryId];
        $item->reserved_stock = $this->reservedInputs[$inventoryId];
        $item->save();

        session()->flash('status', 'Stock levels updated successfully.');
    }

    public function uploadCsv(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'csvFile' => 'required|file|max:204800',
        ]);

        $path = $this->csvFile->getRealPath();
        $parsed = $service->parseCsv($path, null, 5, true);

        if (!$parsed['success']) {
            $this->addError('csvFile', $parsed['error'] ?? 'Unable to parse CSV file.');
            return;
        }

        $skuCol = $this->detectCandidateColumn($parsed['headers'], ['sku', 'code']);
        $stockCol = $this->detectCandidateColumn($parsed['headers'], ['stock_level', 'stock', 'qty', 'quantity']);
        $whseCol = $this->detectCandidateColumn($parsed['headers'], ['warehouse_level', 'warehouse_stock_level', 'whse']);
        $locCol = $this->detectCandidateColumn($parsed['headers'], ['locationid', 'location_id', 'location']);
        $costCol = $this->detectCandidateColumn($parsed['headers'], ['cost', 'item_cost', 'unit_cost']);

        $longSkuVariants = null;

        foreach ($parsed['rows'] as $row) {
            $sku = trim((string)($row[$skuCol] ?? ''));
            if (!$sku) continue;

            $variant = \App\Models\ProductVariant::where('sku', $sku)->first();
            if (!$variant) {
                // Try leading-zero trimmed or padded variations
                $trimmed = ltrim($sku, '0');
                if ($trimmed !== '') {
                    $variant = \App\Models\ProductVariant::whereIn('sku', [
                        $trimmed,
                        str_pad($trimmed, 12, '0', STR_PAD_LEFT),
                        str_pad($trimmed, 13, '0', STR_PAD_LEFT),
                        str_pad($trimmed, 14, '0', STR_PAD_LEFT),
                    ])->first();
                }
            }

            if (!$variant) {
                // Check if there is a match for records containing the SKU where system variant SKU length >= 11
                if ($longSkuVariants === null) {
                    $longSkuVariants = \App\Models\ProductVariant::whereRaw('LENGTH(sku) >= 11')->get();
                }
                foreach ($longSkuVariants as $cand) {
                    $candSku = (string)$cand->sku;
                    if (strlen($candSku) >= 11) {
                        if (str_contains($candSku, $sku) || (strlen($sku) >= 11 && str_contains($sku, $candSku)) || (ltrim($sku, '0') !== '' && ltrim($candSku, '0') === ltrim($sku, '0'))) {
                            $variant = $cand;
                            break;
                        }
                    }
                }
            }

            if (!$variant) continue;

            $inv = \App\Models\ProductInventory::firstOrCreate(['variant_id' => $variant->id]);

            if ($stockCol && isset($row[$stockCol]) && is_numeric(trim((string)$row[$stockCol]))) {
                $inv->quantity_available = (int)trim((string)$row[$stockCol]);
            }
            if ($whseCol && isset($row[$whseCol]) && is_numeric(trim((string)$row[$whseCol]))) {
                $inv->warehouse_stock_level = (int)trim((string)$row[$whseCol]);
            }
            if ($locCol && isset($row[$locCol]) && is_numeric(trim((string)$row[$locCol]))) {
                $inv->location_id = (int)trim((string)$row[$locCol]);
            }
            $inv->save();

            if ($costCol && isset($row[$costCol]) && is_numeric(trim((string)$row[$costCol]))) {
                $variant->item_cost = (float)trim((string)$row[$costCol]);
                $variant->save();
            }
        }

        session()->flash('status', 'CSV bulk stock updated successfully.');
    }

    public function uploadAndPreviewCsv(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'csvFile'          => 'required|file|max:204800', // 200MB max
            'markupPercentage' => 'nullable|numeric|min:-99.99|max:1000',
            'inventoryMode'    => 'required|in:replace,add',
        ]);

        $storedPath = $this->csvFile->storeAs('temp_imports', 'csv_stock_' . uniqid() . '.csv', 'local');
        $fullPath = \Illuminate\Support\Facades\Storage::disk('local')->path($storedPath);
        $this->csvTempPath = $fullPath;

        $parsed = $service->parseCsv($fullPath, null, 5, false);

        if (!$parsed['success']) {
            $this->addError('csvFile', $parsed['error'] ?? 'Unable to parse CSV file.');
            return;
        }

        $this->csvHeaders     = $parsed['headers'];
        $this->csvPreviewRows = $parsed['preview'];
        $this->csvTotalRows   = $parsed['total'];

        // Auto-detect candidate column mappings
        $this->skuColumn   = $this->detectCandidateColumn($this->csvHeaders, ['sku', 'inventory_sku', 'item_sku', 'prod_sku', 'code']);
        $this->costColumn  = $this->detectCandidateColumn($this->csvHeaders, ['cost', 'item_cost', 'unit_cost', 'wholesale', 'buy_price']);
        $this->stockColumn = $this->detectCandidateColumn($this->csvHeaders, ['stock', 'qty', 'quantity', 'inventory', 'stock_level', 'available']);

        $this->importStep = 2;
    }

    public function executeStockAndCostImport(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'skuColumn' => 'required|string',
        ]);

        $mapping = [
            'sku'   => $this->skuColumn,
            'cost'  => $this->costColumn ?: null,
            'stock' => $this->stockColumn ?: null,
        ];

        $source = ($this->csvTempPath && file_exists($this->csvTempPath)) ? $this->csvTempPath : [];

        $results = $service->processStockAndCostImport(
            $source,
            $mapping,
            (float)$this->markupPercentage,
            $this->inventoryMode,
            $this->autoShowInResults
        );

        $this->importResults = $results;
        $this->importStep    = 3;
        $this->resetPage();

        if ($this->csvTempPath && file_exists($this->csvTempPath)) {
            @unlink($this->csvTempPath);
            $this->csvTempPath = null;
        }

        session()->flash('status', "CSV Import Processed: {$results['stats']['updated_count']} variants updated, {$results['stats']['skipped_count']} rows skipped.");
    }

    public function resetImportWizard(): void
    {
        if ($this->csvTempPath && file_exists($this->csvTempPath)) {
            @unlink($this->csvTempPath);
        }

        $this->reset([
            'csvFile',
            'csvTempPath',
            'markupPercentage',
            'inventoryMode',
            'autoShowInResults',
            'importStep',
            'csvHeaders',
            'csvPreviewRows',
            'csvTotalRows',
            'skuColumn',
            'costColumn',
            'stockColumn',
            'importResults',
        ]);
        $this->inventoryMode = 'replace';
        $this->markupPercentage = 0.0;
        $this->autoShowInResults = false;
        $this->importStep = 1;
    }

    // --- FTP / SFTP Remote Feed Actions ---

    public function updatedFtpProtocol(string $value): void
    {
        $this->ftpPort = $value === 'sftp' ? 22 : 21;
    }

    public function updatedFtpPort(mixed $value): void
    {
        if ((int)$value === 22 && in_array($this->ftpProtocol, ['ftp', 'ftps'])) {
            $this->ftpProtocol = 'sftp';
        } elseif ((int)$value === 21 && $this->ftpProtocol === 'sftp') {
            $this->ftpProtocol = 'ftp';
        }
    }

    public function downloadAndPreviewFtp(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'ftpProtocol'        => 'required|in:ftp,ftps,sftp',
            'ftpHost'            => 'required|string|max:255',
            'ftpPort'            => 'required|integer|min:1|max:65535',
            'ftpUsername'        => 'required|string|max:255',
            'ftpPassword'        => 'nullable|string|max:255',
            'ftpRemotePath'      => 'required|string|max:500',
            'ftpMarkupPercentage'=> 'nullable|numeric|min:-99.99|max:1000',
            'ftpInventoryMode'   => 'required|in:replace,add',
            'ftpAutoShowInResults' => 'boolean',
        ]);

        // Persist configured credentials into cms_settings so user doesn't need to re-enter them
        \App\Models\CmsSetting::setMany([
            'ftp_protocol'              => $this->ftpProtocol,
            'ftp_host'                  => $this->ftpHost,
            'ftp_port'                  => (string)$this->ftpPort,
            'ftp_username'              => $this->ftpUsername,
            'ftp_password'              => $this->ftpPassword,
            'ftp_remote_path'           => $this->ftpRemotePath,
            'ftp_markup_percentage'     => (string)$this->ftpMarkupPercentage,
            'ftp_inventory_mode'        => $this->ftpInventoryMode,
            'ftp_auto_show_in_results'  => $this->ftpAutoShowInResults ? '1' : '0',
        ]);

        $download = $service->downloadRemoteFile([
            'protocol'    => $this->ftpProtocol,
            'host'        => $this->ftpHost,
            'port'        => $this->ftpPort,
            'username'    => $this->ftpUsername,
            'password'    => $this->ftpPassword,
            'remote_path' => $this->ftpRemotePath,
        ]);

        if (!$download['success'] || !$download['local_path']) {
            $this->addError('ftpHost', $download['error'] ?? 'FTP download failed. Check connection parameters.');
            return;
        }

        $this->ftpTempFilePath = $download['local_path'];
        $parsed = $service->parseCsv($this->ftpTempFilePath, null, 5, false);

        if (!$parsed['success']) {
            $this->addError('ftpRemotePath', $parsed['error'] ?? 'Failed to parse downloaded remote file.');
            return;
        }

        $this->ftpHeaders     = $parsed['headers'];
        $this->ftpPreviewRows = $parsed['preview'];
        $this->ftpTotalRows   = $parsed['total'];

        $this->ftpSkuColumn   = $this->detectCandidateColumn($this->ftpHeaders, ['sku', 'inventory_sku', 'item_sku', 'code']);
        $this->ftpCostColumn  = $this->detectCandidateColumn($this->ftpHeaders, ['cost', 'item_cost', 'unit_cost', 'wholesale']);
        $this->ftpStockColumn = $this->detectCandidateColumn($this->ftpHeaders, ['stock', 'qty', 'quantity', 'inventory', 'stock_level']);

        $this->ftpStep = 2;
    }

    public function saveFtpSettings(): void
    {
        $this->validate([
            'ftpProtocol'         => 'required|in:ftp,ftps,sftp',
            'ftpHost'             => 'required|string|max:255',
            'ftpPort'             => 'required|integer|min:1|max:65535',
            'ftpUsername'         => 'required|string|max:255',
            'ftpPassword'         => 'nullable|string|max:255',
            'ftpRemotePath'       => 'required|string|max:500',
            'ftpMarkupPercentage' => 'nullable|numeric|min:-99.99|max:1000',
            'ftpInventoryMode'    => 'required|in:replace,add',
            'ftpAutoShowInResults'=> 'boolean',
        ]);

        \App\Models\CmsSetting::setMany([
            'ftp_protocol'              => $this->ftpProtocol,
            'ftp_host'                  => $this->ftpHost,
            'ftp_port'                  => (string)$this->ftpPort,
            'ftp_username'              => $this->ftpUsername,
            'ftp_password'              => $this->ftpPassword,
            'ftp_remote_path'           => $this->ftpRemotePath,
            'ftp_markup_percentage'     => (string)$this->ftpMarkupPercentage,
            'ftp_inventory_mode'        => $this->ftpInventoryMode,
            'ftp_auto_show_in_results'  => $this->ftpAutoShowInResults ? '1' : '0',
        ]);

        session()->flash('status', 'FTP / SFTP connection credentials saved to settings.');
    }

    public function executeFtpStockAndCostImport(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'ftpSkuColumn' => 'required|string',
        ]);

        $mapping = [
            'sku'   => $this->ftpSkuColumn,
            'cost'  => $this->ftpCostColumn ?: null,
            'stock' => $this->ftpStockColumn ?: null,
        ];

        $source = ($this->ftpTempFilePath && file_exists($this->ftpTempFilePath)) ? $this->ftpTempFilePath : [];

        $results = $service->processStockAndCostImport(
            $source,
            $mapping,
            (float)$this->ftpMarkupPercentage,
            $this->ftpInventoryMode,
            $this->ftpAutoShowInResults
        );

        $this->ftpResults = $results;
        $this->ftpStep    = 3;
        $this->resetPage();

        if ($this->ftpTempFilePath && file_exists($this->ftpTempFilePath)) {
            @unlink($this->ftpTempFilePath);
            $this->ftpTempFilePath = null;
        }

        session()->flash('status', "FTP Remote Feed Synced: {$results['stats']['updated_count']} variants updated, {$results['stats']['skipped_count']} rows skipped.");
    }

    public function resetFtpWizard(): void
    {
        if ($this->ftpTempFilePath && file_exists($this->ftpTempFilePath)) {
            @unlink($this->ftpTempFilePath);
        }

        $this->reset([
            'ftpStep',
            'ftpHeaders',
            'ftpPreviewRows',
            'ftpTotalRows',
            'ftpSkuColumn',
            'ftpCostColumn',
            'ftpStockColumn',
            'ftpResults',
            'ftpTempFilePath',
        ]);
        $this->ftpStep = 1;

        // Keep saved credentials intact from settings
        $this->ftpProtocol            = \App\Models\CmsSetting::get('ftp_protocol', 'ftp') ?: 'ftp';
        $this->ftpHost                = \App\Models\CmsSetting::get('ftp_host', '') ?: '';
        $this->ftpPort                = (int)(\App\Models\CmsSetting::get('ftp_port', 21) ?: 21);
        $this->ftpUsername            = \App\Models\CmsSetting::get('ftp_username', '') ?: '';
        $this->ftpPassword            = \App\Models\CmsSetting::get('ftp_password', '') ?: '';
        $this->ftpRemotePath          = \App\Models\CmsSetting::get('ftp_remote_path', '') ?: '';
        $this->ftpMarkupPercentage    = (float)(\App\Models\CmsSetting::get('ftp_markup_percentage', 0.0) ?: 0.0);
        $this->ftpInventoryMode       = \App\Models\CmsSetting::get('ftp_inventory_mode', 'replace') ?: 'replace';
        $this->ftpAutoShowInResults   = \App\Models\CmsSetting::isEnabled('ftp_auto_show_in_results');
    }

    // --- Amazon / eBay Marketplace Pricing Actions ---

    public function uploadAndPreviewMarketplaceCsv(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'marketplaceCsvFile' => 'required|file|max:204800', // 200MB max
            'targetMarketplace'  => 'required|in:amazon,ebay,both',
            'marketplaceMarkup'  => 'required|numeric|min:0|max:1000',
        ]);

        $storedPath = $this->marketplaceCsvFile->storeAs('temp_imports', 'marketplace_' . uniqid() . '.csv', 'local');
        $fullPath = \Illuminate\Support\Facades\Storage::disk('local')->path($storedPath);
        $this->marketplaceTempPath = $fullPath;

        $parsed = $service->parseCsv($fullPath, null, 5, false);

        if (!$parsed['success']) {
            $this->addError('marketplaceCsvFile', $parsed['error'] ?? 'Unable to parse CSV file.');
            return;
        }

        $this->marketplaceHeaders     = $parsed['headers'];
        $this->marketplacePreviewRows = $parsed['preview'];
        $this->marketplaceTotalRows   = $parsed['total'];

        $this->marketplaceSkuColumn   = $this->detectCandidateColumn($this->marketplaceHeaders, ['sku', 'inventory_sku', 'item_sku', 'code']);

        $this->marketplaceStep = 2;
    }

    public function executeMarketplaceImport(InventoryImportService $service): void
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');
        \Illuminate\Support\Facades\DB::disableQueryLog();
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->validate([
            'marketplaceSkuColumn' => 'required|string',
            'targetMarketplace'    => 'required|in:amazon,ebay,both',
            'marketplaceMarkup'    => 'required|numeric|min:0|max:1000',
        ]);

        $source = ($this->marketplaceTempPath && file_exists($this->marketplaceTempPath)) ? $this->marketplaceTempPath : [];

        $results = $service->processMarketplacePricingImport(
            $source,
            $this->marketplaceSkuColumn,
            $this->targetMarketplace,
            (float)$this->marketplaceMarkup
        );

        $this->marketplaceResults = $results;
        $this->marketplaceStep    = 3;

        if ($this->marketplaceTempPath && file_exists($this->marketplaceTempPath)) {
            @unlink($this->marketplaceTempPath);
            $this->marketplaceTempPath = null;
        }

        session()->flash('status', "Marketplace Pricing Synced: {$results['stats']['updated_count']} variants updated with {$this->marketplaceMarkup}% markup on current cost. {$results['stats']['skipped_count']} skipped.");
    }

    public function resetMarketplaceWizard(): void
    {
        if ($this->marketplaceTempPath && file_exists($this->marketplaceTempPath)) {
            @unlink($this->marketplaceTempPath);
        }

        $this->reset([
            'marketplaceCsvFile',
            'marketplaceTempPath',
            'marketplaceStep',
            'marketplaceHeaders',
            'marketplacePreviewRows',
            'marketplaceTotalRows',
            'marketplaceSkuColumn',
            'marketplaceResults',
        ]);
        $this->marketplaceStep = 1;
        $this->targetMarketplace = 'both';
        $this->marketplaceMarkup = 15.0;
    }

    // --- Reset All Inventory to Zero & Bulk Visibility Tools Actions ---

    public function executeZeroInventoryReset(InventoryImportService $service): void
    {
        if (trim(strtoupper($this->resetConfirmationText)) !== 'RESET' || !$this->resetConfirmed) {
            $this->addError('resetConfirmationText', 'Please check the confirmation box and type "RESET" to confirm.');
            return;
        }

        $updatedCount = $service->resetAllInventory();

        $this->reset([
            'resetConfirmationText',
            'resetConfirmed',
        ]);
        $this->resetPage();

        session()->flash('status', "Inventory Reset Successful: All {$updatedCount} inventory item records have been set to 0 available stock.");
        $this->activeTab = 'reset';
    }

    public function executeHideZeroPriceProducts(InventoryImportService $service): void
    {
        if (trim(strtoupper($this->hideZeroConfirmationText)) !== 'HIDE' || !$this->hideZeroConfirmed) {
            $this->addError('hideZeroConfirmationText', 'Please check the confirmation box and type "HIDE" to confirm.');
            return;
        }

        $updatedCount = $service->hideZeroPriceProductsFromResults();

        $this->reset([
            'hideZeroConfirmationText',
            'hideZeroConfirmed',
        ]);
        $this->resetPage();

        session()->flash('status', "Product Visibility Updated: {$updatedCount} products with no priced variants have been set to not show in search results.");
        $this->activeTab = 'reset';
    }

    // --- Helper to guess candidate column from list of headers ---

    private function detectCandidateColumn(array $headers, array $candidates): string
    {
        foreach ($headers as $h) {
            $lower = strtolower(str_replace(['_', '-', ' '], '', $h));
            foreach ($candidates as $c) {
                $cClean = strtolower(str_replace(['_', '-', ' '], '', $c));
                if (str_contains($lower, $cClean)) {
                    return $h;
                }
            }
        }
        return $headers[0] ?? '';
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
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
            'Item Cost ($)',
            'Retail Price ($)',
            'Amazon Price ($)',
            'eBay Price ($)',
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
            $cost         = $inv->variant?->item_cost !== null ? number_format((float)$inv->variant->item_cost, 2) : '0.00';
            $price        = number_format((float)($inv->variant?->public_price ?? 0), 2);
            $amazonPrice  = $inv->variant?->amazon_price !== null ? number_format((float)$inv->variant->amazon_price, 2) : '0.00';
            $ebayPrice    = $inv->variant?->ebay_price !== null ? number_format((float)$inv->variant->ebay_price, 2) : '0.00';
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
                $cost,
                $price,
                $amazonPrice,
                $ebayPrice,
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

        $filename = 'multi_warehouse_inventory_export_' . now()->format('Y-m-d') . '.csv';

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

    public function render(): View
    {
        $query = ProductInventory::with([
            'variant.product',
            'primaryWarehouse',
            'warehouseInventories.warehouseLocation'
        ]);

        if ($this->search) {
            $query->whereHas('variant.product', function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%')
                  ->orWhere('long_description', 'like', '%' . $this->search . '%')
                  ->orWhere('seo_slug', 'like', '%' . $this->search . '%');
            })->orWhereHas('variant', function($q) {
                $q->where('sku', 'like', '%' . $this->search . '%');
            });
        }

        $inventory = $query->paginate(25);

        foreach ($inventory as $item) {
            if (!isset($this->stockInputs[$item->id])) {
                $this->stockInputs[$item->id] = $item->quantity_available;
            }
            if (!isset($this->warehouseInputs[$item->id])) {
                $this->warehouseInputs[$item->id] = $item->warehouse_stock_level;
            }
            if (!isset($this->useWarehouseInputs[$item->id])) {
                $this->useWarehouseInputs[$item->id] = (bool) $item->use_warehouse_stock;
            }
            if (!isset($this->reservedInputs[$item->id])) {
                $this->reservedInputs[$item->id] = $item->reserved_stock;
            }
        }

        return view('livewire.admin-inventory', [
            'inventory' => $inventory
        ]);
    }
}

