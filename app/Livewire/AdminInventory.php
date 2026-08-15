<?php

namespace App\Livewire;

use App\Models\ProductInventory;
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

    public string $search = '';
    public array $stockInputs = [];
    public array $warehouseInputs = [];
    public array $useWarehouseInputs = [];
    public array $reservedInputs = [];

    public $csvFile;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403, 'Unauthorized e-commerce admin access.');
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

    public function uploadCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|max:2048', // 2MB max
        ]);

        $path = $this->csvFile->getRealPath();
        $file = fopen($path, 'r');
        
        $header = null;
        $updatedCount = 0;
        $skippedCount = 0;

        while (($row = fgetcsv($file, 1000, ',')) !== false) {
            // Check if it's pipe-separated
            if (count($row) === 1 && strpos($row[0], '|') !== false) {
                $row = explode('|', $row[0]);
            }

            if ($header === null) {
                $header = $row;
                // If first element is 'sku' (case-insensitive), skip header line
                if (stripos($row[0], 'sku') !== false) {
                    continue;
                }
            }

            if (count($row) < 2) {
                $skippedCount++;
                continue;
            }

            $sku = trim($row[0]);
            $stockLevel = isset($row[1]) ? (int)trim($row[1]) : 0;
            $warehouseLevel = isset($row[2]) ? (int)trim($row[2]) : 0;
            $locationId = isset($row[3]) ? (int)trim($row[3]) : 1;

            $variant = \App\Models\ProductVariant::where('sku', $sku)->first();
            if ($variant) {
                ProductInventory::updateOrCreate(
                    ['variant_id' => $variant->id],
                    [
                        'quantity_available' => $stockLevel,
                        'warehouse_stock_level' => $warehouseLevel,
                        'location_id' => $locationId,
                    ]
                );
                $updatedCount++;
            } else {
                $skippedCount++;
            }
        }

        fclose($file);
        $this->reset(['csvFile']);
        $this->resetPage();

        session()->flash('status', "CSV processed: {$updatedCount} records updated, {$skippedCount} records skipped.");
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
