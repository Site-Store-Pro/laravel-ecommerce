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
        // Product-level fields
        'title'                   => 'Product Title',
        'short_description'       => 'Short Description',
        'long_description'        => 'Long Description',
        'meta_title'              => 'Meta Title (SEO)',
        'meta_description'        => 'Meta Description (SEO)',
        'public_price'            => 'Public Price',
        'wholesale_price'         => 'Wholesale Price',
        'categories'              => 'Category / Categories',
        'brand'                   => 'Brand Name',
        // Images
        'thumbnail_url'           => 'Thumbnail Image URL',
        'main_image_url'          => 'Main Image URL',
        'zoom_images_url'         => 'Zoom / Gallery Image URLs',
        'image_url_source'        => 'Image Source Type (1=Direct URL, 0=Download)',
        // Variant core
        'variant_sku'             => 'Variant SKU',
        'variant_name'            => 'Variant Name / Label',
        'variant_attributes'      => 'Variant Attributes (Size:M, Color:Blue)',
        'variant_price'           => 'Variant Price',
        'variant_wholesale_price' => 'Variant Wholesale Price',
        'inventory'               => 'Stock Quantity / Inventory',
        // Variant fulfilment
        'charge_tax'              => 'Charge Tax (1=Yes, 0=No)',
        'shipping'                => 'Shippable / Physical (1=Yes, 0=No)',
        'weight'                  => 'Weight (numeric)',
        'weight_type'             => 'Weight Unit (lbs / ozs / kg / g)',
        // Digital download
        'download_item'           => 'Downloadable / Digital (1=Yes, 0=No)',
        'direct_download_url'     => 'Direct Download URL',
        // Event
        'is_event'                => 'Is Event / Ticket (1=Yes, 0=No)',
        'event_label'             => 'Event Title / Label',
        'event_location'          => 'Event Location / Venue',
        'event_start_date'        => 'Event Start Date (YYYY-MM-DD HH:MM)',
        'event_end_date'          => 'Event End Date (YYYY-MM-DD HH:MM)',
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
        $this->previewRows = array_slice($parsed['rows'], 0, 50);

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

        // Clear the file/mapping state so the upload panel and execute button are hidden.
        // importStats is intentionally kept so the summary card remains visible.
        $this->importFile    = null;
        $this->headers       = [];
        $this->previewRows   = [];
        $this->allRows       = [];
        $this->columnMapping = [];
        $this->fileParsed    = false;
    }

    public function downloadSampleCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product_import_sample_template.csv"',
        ];

        $sampleData = [
            ['Title','Short Description','Long Description','Meta Title','Meta Description','Category','Brand','Public Price','Wholesale Price','Thumbnail URL','Main Image URL','Zoom Images URL','Image Source Type','Variant SKU','Variant Name','Variant Attributes','Variant Price','Variant Wholesale Price','Stock Quantity','Charge Tax','Shipping','Weight','Weight Type','Downloadable','Direct Download URL','Is Event','Event Title','Event Location','Event Start Date','Event End Date'],
            ['Classic Cotton T-Shirt','Premium 100% Cotton Tee','<p>Comfortable everyday crewneck t-shirt.</p>','Classic Cotton T-Shirt | Acme Clothing','Shop the Classic Cotton T-Shirt in multiple colors.','Apparel > Shirts','Acme Clothing','29.99','14.99','https://picsum.photos/200/200','https://picsum.photos/800/800','https://picsum.photos/1200/1200','0','TSHIRT-BLK-M','Choose Your Style:','Color:Black, Size:Medium','29.99','14.99','50','1','1','0.4','lbs','0','','0','','','',''],
            ['Classic Cotton T-Shirt','Premium 100% Cotton Tee','<p>Comfortable everyday crewneck t-shirt.</p>','Classic Cotton T-Shirt | Acme Clothing','Shop the Classic Cotton T-Shirt in multiple colors.','Apparel > Shirts','Acme Clothing','29.99','14.99','https://picsum.photos/200/200','https://picsum.photos/800/800','https://picsum.photos/1200/1200','0','TSHIRT-BLK-L','Choose Your Style:','Color:Black, Size:Large','31.99','15.99','75','1','1','0.4','lbs','0','','0','','','',''],
            ['PHP Mastery eBook','Complete guide to modern PHP','<p>Master PHP 8 with real-world examples.</p>','PHP Mastery eBook | DevPress','Download the ultimate PHP guide. Instant access.','eBooks, Software','DevPress','49.00','0.00','https://picsum.photos/200/200','https://picsum.photos/800/800','','1','EBOOK-PHP-001','Select Option:','','49.00','0.00','999','1','0','0','lbs','1','https://example.com/downloads/php-mastery.pdf','0','','','',''],
            ['Laravel Workshop 2025','Full-day hands-on Laravel workshop','<p>Build a complete Laravel app from scratch.</p>','Laravel Workshop 2025 | Live Event','Join us for a full-day Laravel workshop. Limited seats.','Events, Training','DevPress','199.00','149.00','https://picsum.photos/200/200','https://picsum.photos/800/800','','1','LARAVEL-WS-NYC','Select Date:','City:New York','199.00','149.00','30','1','0','0','lbs','0','','1','Laravel Workshop - New York','New York Convention Center, NY','2025-10-15 09:00','2025-10-15 17:00'],
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
            // Header row
            [
                'Title', 'Short Description', 'Long Description', 'Meta Title', 'Meta Description',
                'Category', 'Brand', 'Public Price', 'Wholesale Price',
                'Thumbnail URL', 'Main Image URL', 'Zoom Images URL', 'Image Source Type',
                'Variant SKU', 'Variant Name', 'Variant Attributes', 'Variant Price', 'Variant Wholesale Price', 'Stock Quantity',
                'Charge Tax', 'Shipping', 'Weight', 'Weight Type',
                'Downloadable', 'Direct Download URL',
                'Is Event', 'Event Title', 'Event Location', 'Event Start Date', 'Event End Date',
            ],
            // Product 1, variant 1
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Classic Cotton T-Shirt | Acme Clothing', 'Shop the Classic Cotton T-Shirt in multiple colors and sizes.',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200', '0',
                'TSHIRT-BLK-M', 'Choose Your Style:', 'Color:Black, Size:Medium', '29.99', '14.99', '50',
                '1', '1', '0.4', 'lbs',
                '0', '',
                '0', '', '', '', '',
            ],
            // Product 1, variant 2
            [
                'Classic Cotton T-Shirt', 'Premium 100% Cotton Tee', '<p>Comfortable everyday crewneck t-shirt.</p>',
                'Classic Cotton T-Shirt | Acme Clothing', 'Shop the Classic Cotton T-Shirt in multiple colors and sizes.',
                'Apparel > Shirts', 'Acme Clothing', '29.99', '14.99',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', 'https://picsum.photos/1200/1200', '0',
                'TSHIRT-BLK-L', 'Choose Your Style:', 'Color:Black, Size:Large', '31.99', '15.99', '75',
                '1', '1', '0.4', 'lbs',
                '0', '',
                '0', '', '', '', '',
            ],
            // Product 2, single variant (digital)
            [
                'PHP Mastery eBook', 'Complete guide to modern PHP development', '<p>Master PHP 8 with real-world examples.</p>',
                'PHP Mastery eBook | Learn PHP Fast', 'Download the ultimate PHP development guide. Instant access.',
                'eBooks, Software', 'DevPress', '49.00', '0.00',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', '', '1',
                'EBOOK-PHP-001', 'Select Option:', '', '49.00', '0.00', '999',
                '1', '0', '0', 'lbs',
                '1', 'https://example.com/downloads/php-mastery.pdf',
                '0', '', '', '', '',
            ],
            // Product 3, event with two date variants
            [
                'Laravel Workshop 2025', 'Full-day hands-on Laravel workshop', '<p>Build a complete Laravel app from scratch.</p>',
                'Laravel Workshop 2025 | Live Event', 'Join us for a full-day Laravel workshop. Limited seats available.',
                'Events, Training', 'DevPress', '199.00', '149.00',
                'https://picsum.photos/200/200', 'https://picsum.photos/800/800', '', '1',
                'LARAVEL-WS-NYC', 'Select Date:', 'City:New York', '199.00', '149.00', '30',
                '1', '0', '0', 'lbs',
                '0', '',
                '1', 'Laravel Workshop — New York', 'New York Convention Center, NY', '2025-10-15 09:00', '2025-10-15 17:00',
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
