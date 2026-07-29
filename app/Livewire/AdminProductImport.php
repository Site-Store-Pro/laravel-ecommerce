<?php

namespace App\Livewire;

use App\Services\ProductImportService;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminProductImport extends Component
{
    use WithFileUploads;

    public $importFile = null;
    public array $headers = [];
    public array $previewRows = [];
    public array $allRows = [];
    public array $columnMapping = [];
    public array $availableStandardKeys = [
        'title'                   => 'Product Title',
        'short_description'       => 'Short Description',
        'long_description'        => 'Long Description',
        'public_price'            => 'Public Price',
        'wholesale_price'         => 'Wholesale Price',
        'categories'              => 'Category / Categories',
        'brand'                   => 'Brand Name',
        'thumbnail_url'           => 'Thumbnail Image URL',
        'main_image_url'          => 'Main Image URL',
        'zoom_images_url'         => 'Zoom / Gallery Image URLs',
        'image_url_source'        => 'Image Source Type (1=Direct URL, 0=Download)',
        'variant_sku'             => 'Variant SKU',
        'variant_name'            => 'Variant Name / Option',
        'variant_attributes'      => 'Variant Attributes (Size:M, Color:Blue)',
        'variant_price'           => 'Variant Price',
        'variant_wholesale_price' => 'Variant Wholesale Price',
        'inventory'               => 'Stock Quantity / Inventory',
    ];

    public bool $fileParsed = false;
    public bool $importing = false;
    public ?array $importStats = null;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
    }

    public function updatedImportFile(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $service = app(ProductImportService::class);
        $tempPath = $this->importFile->getRealPath();

        $parsed = $service->parseFile($tempPath);
        $this->headers     = $parsed['headers'];
        $this->allRows     = $parsed['rows'];
        $this->previewRows = array_slice($parsed['rows'], 0, 10);

        $this->columnMapping = $service->autoDetectMapping($this->headers);
        $this->fileParsed    = true;
        $this->importStats   = null;
    }

    public function resetUpload(): void
    {
        $this->importFile    = null;
        $this->headers       = [];
        $this->previewRows   = [];
        $this->allRows       = [];
        $this->columnMapping = [];
        $this->fileParsed    = false;
        $this->importStats   = null;
    }

    public function executeImport(): void
    {
        if (empty($this->allRows)) {
            session()->flash('error', 'No import data loaded. Please upload a file first.');
            return;
        }

        $service = app(ProductImportService::class);
        $this->importStats = $service->executeImport($this->allRows, $this->columnMapping);
        session()->flash('status', 'Import completed successfully!');
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product_import_sample_template.csv"',
        ];

        $sampleData = [
            [
                'Title', 'Short Description', 'Long Description', 'Category', 'Brand',
                'Public Price', 'Wholesale Price', 'Thumbnail URL', 'Main Image URL', 'Zoom Images URL',
                'Image Source Type', 'Variant SKU', 'Variant Name', 'Variant Attributes', 'Variant Price',
                'Variant Wholesale Price', 'Stock Quantity'
            ],
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '0', 'TSHIRT-BLK-M', 'Black / Medium', 'Color:Black, Size:Medium', '29.99', '14.99', '50'
            ],
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '0', 'TSHIRT-BLK-L', 'Black / Large', 'Color:Black, Size:Large', '31.99', '15.99', '75'
            ],
            [
                'Pro Wireless Headphones', 'Noise canceling Bluetooth headphones', '<p>Crystal clear audio with long battery life.</p>',
                'Electronics, Audio', 'SoundPro', '149.00', '95.00',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '1', 'HD-WIRELESS-01', 'Default', '', '149.00', '95.00', '100'
            ],
        ];

        $callback = function () use ($sampleData) {
            $file = fopen('php://output', 'w');
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadSampleExcel(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sampleData = [
            [
                'Title', 'Short Description', 'Long Description', 'Category', 'Brand',
                'Public Price', 'Wholesale Price', 'Thumbnail URL', 'Main Image URL', 'Zoom Images URL',
                'Image Source Type', 'Variant SKU', 'Variant Name', 'Variant Attributes', 'Variant Price',
                'Variant Wholesale Price', 'Stock Quantity'
            ],
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '0', 'TSHIRT-BLK-M', 'Black / Medium', 'Color:Black, Size:Medium', '29.99', '14.99', '50'
            ],
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '0', 'TSHIRT-BLK-L', 'Black / Large', 'Color:Black, Size:Large', '31.99', '15.99', '75'
            ],
            [
                'Pro Wireless Headphones', 'Noise canceling Bluetooth headphones', '<p>Crystal clear audio with long battery life.</p>',
                'Electronics, Audio', 'SoundPro', '149.00', '95.00',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200',
                '1', 'HD-WIRELESS-01', 'Default', '', '149.00', '95.00', '100'
            ],
        ];

        foreach ($sampleData as $rowIndex => $rowValues) {
            foreach ($rowValues as $colIndex => $cellValue) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $sheet->setCellValue($colLetter . ($rowIndex + 1), $cellValue);
            }
        }

        $headers = [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="product_import_sample_template.xlsx"',
        ];

        $callback = function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin-product-import')
            ->layout('layouts.app');
    }
}
