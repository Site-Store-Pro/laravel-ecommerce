<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductInventory;
use App\Models\ProductImage;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class AdminProductEdit extends Component
{
    use WithFileUploads;

    public int $productId;
    public Product $product;

    // Product Details Form
    public string $title = '';
    public string $short_description = '';
    public string $long_description = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $seo_slug = '';
    public array $selectedCategories = [];
    public ?int $brand_id = null;

    // Search Index Keywords & Locking
    public string $product_search_index = '';
    public bool $product_search_index_locked = false;

    // AI Content Generator
    public string $aiPrompt = '';
    public string $aiResponse = '';

    // Advanced Product Options
    public int    $max_qty              = 0;
    public int    $checkout_redirect    = 0;
    public string $completion_redirect  = '';  // Post-order redirect: raw URL or [page:ID] shortcode
    public string $completion_redirect_label = ''; // Button label; displayed as 'View Content' if blank
    public int    $standalone_purchase  = 0;
    public int    $dependent_variants   = 0;
    public int    $hide_inventory_levels = 0;
    public int    $layout_type          = 1;
    public int    $featured_item        = 0;
    public int    $show_item_total      = 0;  // Show live item total (price × qty) below Add to Cart
    public string $variant_label        = 'Select Option:';  // Label above variant selector on storefront
    public string $product_video_embed  = '';               // Embed code/shortcode for layout types 3 & 5
    public bool   $is_donation_or_bill_pay = false;
    public bool   $allow_custom_amount = false;
    public ?float $custom_amount_min   = null;
    public ?float $custom_amount_max   = null;
    public string $custom_amount_options = '';
    public ?int   $inventory_alert_id    = null;  // Out-of-stock alert message assigned to this product


    // Translation Management
    public string $activeLangCode = '';
    public ?int $activeLangId = null;
    public string $trans_title = '';
    public string $trans_short_description = '';
    public string $trans_long_description = '';
    public string $trans_meta_title = '';
    public string $trans_meta_description = '';
    public string $trans_status = 'pending';
    public ?string $trans_translated_at = null;

    // Variant Personalization Translation
    public string $variantTransLangCode = '';
    public ?int $variantTransLangId = null;
    public string $trans_personalization_label = '';
    public string $trans_personalization_details_label = '';
    public string $trans_personalization_placeholder = '';
    /** Flat map: raw attribute key or value => translated string. e.g. ['Color'=>'Couleur','Blue'=>'Bleu'] */
    public array $trans_attributes = [];

    // Field & Option Translation
    public string $fieldTransLangCode = '';
    public ?int $fieldTransLangId = null;
    public string $trans_field_label = '';
    public array $trans_field_options = []; // [option_id => translated_value]

    // Reviews Management
    public bool $reviews_enabled = true;
    public bool $isEditingReview = false;
    public ?int $selectedReviewId = null;
    public string $reviewName = '';
    public string $reviewLocation = '';
    public int $reviewRating = 5;
    public string $reviewComments = '';
    public bool $reviewApproved = false;

    // Variant Edit Form
    public bool $isEditingVariant = false;
    public bool $isCreatingVariant = false;
    public int $selectedVariantId = 0;
    public string $sku = '';
    public float $public_price = 0.00;
    public float $wholesale_price = 0.00;
    public int $on_sale = 0;
    public float $sale_price = 0.00;
    public float $variant_fee = 0.00;
    public float $wholesale_variant_fee = 0.00;
    public int $personalization_active = 0;
    public float $personalization_fee = 0.00;
    public string $personalization_label = '';
    public string $personalization_details_label = '';
    public string $personalization_placeholder = '';
    public int $shipping = 1;
    public int $charge_tax = 1;           // variant: 1=taxable, 0=exempt
    public float $weight = 0.0;
    public string $weight_type = 'lbs';
    public string $variantAttributes = '';
    public array $inlineAttributes = [];
    public int $quantity_available = 10;
    public int $reserved_stock = 0;
    public int $warehouse_stock_level = 0;
    public bool $use_warehouse_stock = false;

    // Payment Processor Price IDs (per variant)
    public string $paddle_sandbox_price_id    = '';
    public string $paddle_live_price_id        = '';
    public ?float $paddle_price                = null;
    public string $paddle_interval            = '';
    public ?int    $paddle_frequency           = 1;
    public string $paddle_currency_code       = 'USD';
    public string $stripe_sandbox_price_id     = '';
    public string $stripe_live_price_id        = '';
    public int    $create_new_stripe_product   = 0;
    public string $stripe_billing_interval     = 'month'; // month | year | week
    public int    $stripe_trial_enabled        = 0;
    public int    $stripe_trial_days           = 0;

    // Dynamic Customization Form Builder Properties
    public bool $isEditingField = false;
    public ?int $selectedFieldId = null;
    public string $customFieldLabel = '';
    public string $customFieldType = 'text';
    public bool $customFieldIsRequired = false;
    public int $customFieldSortOrder = 0;
    public int $customFieldChargeTax = 1; // product field: 1=taxable, 0=exempt
    public array $fieldOptions = [];
    public array $qtyDiscounts = [];

    // Files & S3 Configuration
    public int $download_item = 0;
    public string $download_location = '';
    public string $direct_download_url = '';
    public string $download_label = '';
    public int $download_s3 = 0;
    public string $download_s3_region = '';
    public string $download_s3_bucket_name = '';
    public string $download_s3_access_key_id = '';
    public string $download_s3_secret_access_key = '';
    public int $image_s3 = 0;
    public string $cdn_url = '';
    public string $s3_folder = '';

    // File Upload bindings
    public $downloadFile;
    public ?string $download_expiration = null;
    public int $downloads_max_allowed = 100;

    // Current File Paths
    public ?string $current_download_location = null;

    // Event Details (per variant)
    public bool $is_event = false;

    // Cross-Selling
    public string $crossSellSearch = '';
    public array $crossSellResults = [];
    public bool $crossSellSearchActive = false;
    public string $event_start_date = '';
    public string $event_end_date = '';
    public string $event_label = '';
    public string $alternate_label = '';
    public string $label_background = '#4f46e5';
    public bool $show_date = true;
    public string $event_location = '';
    public string $event_description = '';
    public float $event_sort = 0.0;

    // Multiple Image Sets Properties
    public $imageSets = [];
    public $deletedImageSetIds = [];
    public bool $imageAutoSaved = false; // Livewire-native flash for auto-save confirmation

    // Replacement flat files arrays
    public $replaceThumbnail = [];
    public $replaceMain = [];
    public $replaceZoom = [];

    // New Image Set inputs
    public $new_thumbnail;
    public $new_main;
    public $new_zoom;
    public int $new_image_s3 = 0;
    public string $new_cdn_url = '';

    // Direct URL mode for new image sets
    public bool $new_image_url_source = false; // checkbox toggle
    public string $new_thumbnail_url = '';
    public string $new_main_url = '';
    public string $new_zoom_url = '';
    public string $new_image_s3_region = '';
    public string $new_image_s3_bucket_name = '';
    public string $new_image_s3_access_key_id = '';
    public string $new_image_s3_secret_access_key = '';

    public string $new_image_alt = '';
    public string $new_zoom_label = '';

    // Shortcode & Link Generator Drawer
    public string $shortcodeSearchQuery = '';
    public string $shortcodeSearchScope = 'all';
    public string $searchProduct = '';
    public string $searchBrand = '';
    public string $searchCategory = '';
    public string $searchPage = '';

    public function mount(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
        if (!\Illuminate\Support\Facades\Schema::hasColumn('products', 'is_donation_or_bill_pay')) {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        }
        $this->productId = $id;
        $this->loadProduct();

        if (request()->query('create_variant')) {
            $this->startCreateVariant();
        }
    }

    private function loadProduct(): void
    {
        $this->product = Product::with([
            'variants.inventory',
            'variants.eventDetails',
            'categories',
            'fields.options',
            'crossSells.crossSellProduct',
        ])->findOrFail($this->productId);
        $this->title = $this->product->title;
        $this->short_description = $this->product->short_description ?? '';
        $this->long_description = $this->product->long_description ?? '';
        $this->meta_title = $this->product->meta_title ?? '';
        $this->meta_description = $this->product->meta_description ?? '';
        $this->seo_slug = $this->product->seo_slug ?? '';
        $this->selectedCategories = $this->product->categories->pluck('id')->toArray();
        $this->brand_id = $this->product->brand_id;
        $this->max_qty = (int) ($this->product->max_qty ?? 0);
        $this->checkout_redirect = (int) ($this->product->checkout_redirect ?? 0);
        $this->standalone_purchase = (int) ($this->product->standalone_purchase ?? 0);
        $this->dependent_variants = (int) ($this->product->dependent_variants ?? 0);
        $this->hide_inventory_levels = (int) ($this->product->hide_inventory_levels ?? 0);
        $this->layout_type = (int) ($this->product->layout_type ?? 1);
        $this->reviews_enabled = (bool) ($this->product->reviews_enabled ?? true);
        $this->featured_item = (int) ($this->product->featured_item ?? 0);
        $this->show_item_total = (int) ($this->product->show_item_total ?? 0);
        $this->variant_label = (string) ($this->product->variant_label ?? 'Select Option:');
        $this->product_video_embed = (string) ($this->product->product_video_embed ?? '');
        $this->completion_redirect = (string) ($this->product->completion_redirect ?? '');
        $this->product_search_index = $this->product->product_search_index ?? '';
        $this->product_search_index_locked = (bool) ($this->product->product_search_index_locked ?? false);
        $this->is_donation_or_bill_pay = (bool) ($this->product->is_donation_or_bill_pay ?? false);
        $this->allow_custom_amount = (bool) ($this->product->allow_custom_amount ?? false);
        $this->custom_amount_min = $this->product->custom_amount_min !== null ? (float) $this->product->custom_amount_min : null;
        $this->custom_amount_max = $this->product->custom_amount_max !== null ? (float) $this->product->custom_amount_max : null;
        $this->custom_amount_options = (string) ($this->product->custom_amount_options ?? '');
        $this->inventory_alert_id    = $this->product->inventory_alert_id ? (int) $this->product->inventory_alert_id : null;
    }

    public function updateProduct(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'meta_title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'brand_id' => 'nullable|integer|exists:product_brands,id',
        ]);

        $this->product->update([
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description ?: $this->short_description,
            'seo_slug' => $this->seo_slug ?: Str::slug($this->title),
            'brand_id' => $this->brand_id,
            'product_search_index' => $this->product_search_index,
            'product_search_index_locked' => $this->product_search_index_locked,
            'variant_label' => trim($this->variant_label) ?: 'Select Option:',
        ]);

        $this->product->categories()->sync($this->selectedCategories);

        session()->flash('status', 'Product details updated successfully.');
        $this->loadProduct();
    }

    public function rebuildIndexKeywords(): void
    {
        $this->product->loadMissing(['brand', 'categories', 'variants']);
        $this->product->fill([
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'seo_slug' => $this->seo_slug,
            'product_search_index_locked' => false,
        ]);
        $this->product_search_index = $this->product->rebuildSearchIndex(force: true);
        $this->dispatch('toast', type: 'info', message: 'Product search index keywords generated.');
    }

    /**
     * Dedicated save for the variant selector label.
     * Called by the inline save button above the variant list in the admin.
     */
    public function updateVariantLabel(): void
    {
        $this->validate(['variant_label' => 'nullable|string|max:255']);

        $this->product->update([
            'variant_label' => trim($this->variant_label) ?: 'Select Option:',
        ]);

        $this->dispatch('toast', type: 'success', message: 'Variant selector label saved.');
        $this->loadProduct();
    }

    /**
     * Save layout type and video embed. Separated from updateAdvancedSettings()
     * so it has its own dedicated save button in the Layout & Video section card.
     */
    public function updateLayoutSettings(): void
    {
        $this->validate([
            'layout_type'         => 'required|integer|in:1,2,3,4,5,6',
            'product_video_embed' => 'nullable|string|max:10000',
        ]);

        $this->product->update([
            'layout_type'         => (int) $this->layout_type,
            'product_video_embed' => trim($this->product_video_embed) ?: null,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Layout settings saved.');
        $this->loadProduct();
    }

    public function updateAdvancedSettings(): void
    {
        $this->validate([
            'max_qty'               => 'nullable|boolean',
            'checkout_redirect'     => 'nullable|boolean',
            'completion_redirect'         => 'nullable|string|max:1000',
            'completion_redirect_label'   => 'nullable|string|max:255',
            'standalone_purchase'   => 'nullable|boolean',
            'dependent_variants'    => 'nullable|boolean',
            'hide_inventory_levels' => 'nullable|boolean',
            'reviews_enabled'       => 'boolean',
            'featured_item'         => 'nullable|boolean',
            'show_item_total'       => 'nullable|boolean',
            'is_donation_or_bill_pay' => 'boolean',
            'allow_custom_amount'    => 'boolean',
            'custom_amount_min'      => 'nullable|numeric|min:0',
            'custom_amount_max'      => 'nullable|numeric|min:0',
            'custom_amount_options'  => 'nullable|string|max:500',
            'inventory_alert_id'     => 'nullable|integer|exists:product_inventory_alerts,id',
        ]);

        // Validate preset options format when custom amount entry is disabled
        if ($this->is_donation_or_bill_pay && !$this->allow_custom_amount && !empty(trim($this->custom_amount_options))) {
            $parts = explode(',', $this->custom_amount_options);
            foreach ($parts as $part) {
                $clean = trim($part);
                if ($clean !== '' && (!is_numeric($clean) || floatval($clean) <= 0)) {
                    $this->addError('custom_amount_options', "Preset amounts must contain valid positive numbers separated by commas (e.g. '10, 25, 50, 100'). Invalid value: '{$clean}'");
                    return;
                }
            }
        }

        $this->product->update([
            'max_qty'               => (int) $this->max_qty,
            'checkout_redirect'     => (int) $this->checkout_redirect,
            'completion_redirect'         => trim($this->completion_redirect) ?: null,
            'completion_redirect_label'   => trim($this->completion_redirect_label) ?: null,
            'standalone_purchase'   => (int) $this->standalone_purchase,
            'dependent_variants'    => (int) $this->dependent_variants,
            'hide_inventory_levels' => (int) $this->hide_inventory_levels,
            'reviews_enabled'       => (int) $this->reviews_enabled,
            'featured_item'         => (int) $this->featured_item,
            'show_item_total'       => (int) $this->show_item_total,
            'is_donation_or_bill_pay' => (bool) $this->is_donation_or_bill_pay,
            'allow_custom_amount'    => (bool) $this->allow_custom_amount,
            'custom_amount_min'      => $this->custom_amount_min !== null && $this->custom_amount_min !== '' ? (float) $this->custom_amount_min : null,
            'custom_amount_max'      => $this->custom_amount_max !== null && $this->custom_amount_max !== '' ? (float) $this->custom_amount_max : null,
            'custom_amount_options'  => trim($this->custom_amount_options) ?: null,
            'inventory_alert_id'     => $this->inventory_alert_id ?: null,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Advanced settings saved.');
        $this->loadProduct();
    }

    public function startCreateVariant(): void
    {
        $this->isCreatingVariant = true;
        $this->isEditingVariant = false;
        $this->resetVariantForm();
        
        $this->imageSets = [];
        $this->deletedImageSetIds = [];
        $this->replaceThumbnail = [];
        $this->replaceMain = [];
        $this->replaceZoom = [];

        $this->new_thumbnail = null;
        $this->new_main = null;
        $this->new_zoom = null;
        $this->new_image_s3 = 0;
        $this->new_cdn_url = '';
        $this->new_image_url_source = false;
        $this->new_thumbnail_url = '';
        $this->new_main_url = '';
        $this->new_zoom_url = '';

        // Pre-populate SKU from product title
        $this->sku = $this->generateSku();
    }

    /**
     * Generate a unique SKU based on the product title.
     * Format: TITLE-SLUG-XXXX  (4 random uppercase alphanumeric chars)
     * Loops until a value not already in product_variants is found.
     */
    public function generateSku(): string
    {
        $base = Str::upper(Str::slug($this->title, '-'));
        // Limit base to 30 chars so the full SKU stays readable
        $base = Str::substr($base, 0, 30);
        $base = rtrim($base, '-') ?: 'SKU';

        do {
            $candidate = $base . '-' . Str::upper(Str::random(4));
        } while (ProductVariant::where('sku', $candidate)->exists());

        return $candidate;
    }

    /**
     * Public Livewire action: regenerate and apply a fresh SKU.
     * Called by the "Regenerate" button in the Add Variant form.
     */
    public function generateSkuAndSet(): void
    {
        $this->sku = $this->generateSku();
    }

    public function cancelCreateVariant(): void
    {
        $this->isCreatingVariant = false;
        $this->imageSets = [];
        $this->deletedImageSetIds = [];
        $this->qtyDiscounts = [];
    }

    private function resetVariantForm(): void
    {
        $this->sku = '';
        $this->public_price = 0.00;
        $this->wholesale_price = 0.00;
        $this->on_sale = 0;
        $this->sale_price = 0.00;
        $this->variant_fee = 0.00;
        $this->wholesale_variant_fee = 0.00;
        $this->personalization_active = 0;
        $this->personalization_fee = 0.00;
        $this->personalization_label = 'Add Gift Wrapping / Personalization';
        $this->personalization_details_label = 'Personalization Details / Gift Message';
        $this->personalization_placeholder = 'Enter names for engraving, personalization details, or a custom gift message here...';
        $this->shipping = 1;
        $this->charge_tax = 1; // fix: was previously missing causing bug with tax-exempt variants
        $this->weight = 0.0;
        $this->weight_type = 'lbs';
        $this->variantAttributes = '';
        $this->inlineAttributes = [];
        $this->quantity_available = 10;
        $this->reserved_stock = 0;
        $this->warehouse_stock_level = 0;
        $this->use_warehouse_stock = false;

        $this->download_item = 0;
        $this->download_location = '';
        $this->download_s3 = 0;
        $this->download_s3_region = '';
        $this->download_s3_bucket_name = '';
        $this->download_s3_access_key_id = '';
        $this->download_s3_secret_access_key = '';
        $this->image_s3 = 0;
        $this->cdn_url = '';
        $this->s3_folder = '';
        $this->new_image_s3_region = '';
        $this->new_image_s3_bucket_name = '';
        $this->new_image_s3_access_key_id = '';
        $this->new_image_s3_secret_access_key = '';

        $this->qtyDiscounts = [];

        $this->downloadFile = null;
        $this->current_download_location = null;
        $this->direct_download_url = '';
        $this->download_label = '';
        $this->download_expiration = now()->addYear()->format('Y-m-d\TH:i');
        $this->downloads_max_allowed = 100;

        // Event details
        $this->is_event = false;
        $this->event_start_date = '';
        $this->event_end_date = '';
        $this->event_label = '';
        $this->alternate_label = '';
        $this->label_background = '#4f46e5';
        $this->show_date = true;
        $this->event_location = '';
        $this->event_description = '';
        $this->event_sort = 0.0;

        // Payment processor price IDs
        $this->paddle_sandbox_price_id  = '';
        $this->paddle_live_price_id     = '';
        $this->paddle_price             = null;
        $this->paddle_interval          = '';
        $this->paddle_frequency         = 1;
        $this->paddle_currency_code     = 'USD';
        $this->stripe_sandbox_price_id  = '';
        $this->stripe_live_price_id     = '';
        $this->create_new_stripe_product = 0;
        $this->stripe_billing_interval  = 'month';
        $this->stripe_trial_enabled     = 0;
        $this->stripe_trial_days        = 0;
    }


    public function addAttribute(): void
    {
        $this->inlineAttributes[] = ['key' => '', 'value' => ''];
    }

    public function removeAttribute(int $index): void
    {
        unset($this->inlineAttributes[$index]);
        $this->inlineAttributes = array_values($this->inlineAttributes);
        $this->updatedInlineAttributes();
    }

    public function updatedInlineAttributes(): void
    {
        $compiled = [];
        foreach ($this->inlineAttributes as $attr) {
            if (!empty($attr['key'])) {
                $compiled[trim($attr['key'])] = trim($attr['value'] ?? '');
            }
        }
        $this->variantAttributes = json_encode($compiled, JSON_PRETTY_PRINT);
    }

    public function updatedVariantAttributes($value): void
    {
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            $this->inlineAttributes = [];
            foreach ($decoded as $k => $v) {
                $this->inlineAttributes[] = ['key' => $k, 'value' => $v];
            }
        }
    }

    public function duplicateVariant(int $variantId): void
    {
        $original = ProductVariant::with(['inventory', 'images'])->findOrFail($variantId);
        
        $newSku = $original->sku . '-DUP-' . Str::upper(Str::random(4));
        while (ProductVariant::where('sku', $newSku)->exists()) {
            $newSku = $original->sku . '-DUP-' . Str::upper(Str::random(4));
        }

        $duplicate = ProductVariant::create([
            'product_id' => $original->product_id,
            'sku' => $newSku,
            'public_price' => $original->public_price,
            'wholesale_price' => $original->wholesale_price,
            'on_sale' => $original->on_sale,
            'sale_price' => $original->sale_price,
            'variant_fee' => $original->variant_fee,
            'wholesale_variant_fee' => $original->wholesale_variant_fee,
            'personalization_active' => $original->personalization_active,
            'personalization_fee' => $original->personalization_fee,
            'personalization_label' => $original->personalization_label,
            'personalization_details_label' => $original->personalization_details_label,
            'personalization_placeholder' => $original->personalization_placeholder,
            'shipping' => $original->shipping,
            'charge_tax' => $original->charge_tax ?? 1,
            'weight' => $original->weight,
            'weight_type' => $original->weight_type,
            'attributes' => $original->attributes,
            'download_item' => $original->download_item,
            'download_location' => $original->download_location,
            'direct_download_url' => $original->direct_download_url,
            'download_label' => $original->download_label,
            'download_expiration' => $original->download_expiration,
            'downloads_max_allowed' => $original->downloads_max_allowed,
            'download_s3' => $original->download_s3,
            'download_s3_region' => $original->download_s3_region,
            'download_s3_bucket_name' => $original->download_s3_bucket_name,
            'download_s3_access_key_id' => $original->download_s3_access_key_id,
            'download_s3_secret_access_key' => $original->download_s3_secret_access_key,
            'download_cdn_url' => $original->download_cdn_url,
            // Processor price IDs
            'paddle_sandbox_price_id'   => $original->paddle_sandbox_price_id,
            'paddle_live_price_id'      => $original->paddle_live_price_id,
            'paddle_price'              => $original->paddle_price,
            'paddle_interval'           => $original->paddle_interval,
            'paddle_frequency'          => $original->paddle_frequency,
            'paddle_currency_code'      => $original->paddle_currency_code ?? 'USD',
            'stripe_sandbox_price_id'   => $original->stripe_sandbox_price_id,
            'stripe_live_price_id'      => $original->stripe_live_price_id,
            'create_new_stripe_product' => $original->create_new_stripe_product ?? 0,
            'stripe_billing_interval'   => $original->stripe_billing_interval   ?? 'month',
            'stripe_trial_enabled'      => $original->stripe_trial_enabled      ?? 0,
            'stripe_trial_days'         => $original->stripe_trial_days         ?? 0,
            'is_event'                  => $original->is_event ?? false,
        ]);


        if ($original->inventory) {
            ProductInventory::create([
                'variant_id' => $duplicate->id,
                'quantity_available' => $original->inventory->quantity_available,
                'warehouse_stock_level' => $original->inventory->warehouse_stock_level,
                'use_warehouse_stock' => $original->inventory->use_warehouse_stock,
                'reserved_stock' => $original->inventory->reserved_stock,
                'location_id' => $original->inventory->location_id
            ]);
        } else {
            ProductInventory::create([
                'variant_id' => $duplicate->id,
                'quantity_available' => 0,
                'warehouse_stock_level' => 0,
                'use_warehouse_stock' => false,
                'reserved_stock' => 0,
                'location_id' => 1
            ]);
        }

        foreach ($original->images as $img) {
            ProductImage::create([
                'variant_id' => $duplicate->id,
                'thumbnail_path' => $img->thumbnail_path,
                'main_path' => $img->main_path,
                'zoom_path' => $img->zoom_path,
                'image_alt' => $img->image_alt,
                'zoom_label' => $img->zoom_label,
                'image_s3' => $img->image_s3,
                'image_url_source' => $img->image_url_source,
                'cdn_url' => $img->cdn_url,
                'search_image' => $img->search_image,
                'active' => $img->active,
                'image_s3_region' => $img->image_s3_region,
                'image_s3_bucket_name' => $img->image_s3_bucket_name,
                'image_s3_access_key_id' => $img->image_s3_access_key_id,
                'image_s3_secret_access_key' => $img->image_s3_secret_access_key,
            ]);
        }

        session()->flash('status', "Variant '{$original->sku}' duplicated successfully as '{$newSku}'.");
        // Duplicate event details if present
        if ($original->is_event && $original->eventDetails) {
            $evt = $original->eventDetails;
            \App\Models\ProductVariantEvent::create([
                'variant_id'        => $duplicate->id,
                'event_start_date'  => $evt->event_start_date,
                'event_end_date'    => $evt->event_end_date,
                'event_label'       => $evt->event_label,
                'alternate_label'   => $evt->alternate_label,
                'label_background'  => $evt->label_background,
                'show_date'         => $evt->show_date,
                'event_location'    => $evt->event_location,
                'event_description' => $evt->event_description,
                'event_sort'        => $evt->event_sort,
            ]);
        }
        $this->loadProduct();
    }

    public function saveVariant(): void
    {
        $this->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku',
            'public_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'variant_fee' => 'required|numeric|min:0',
            'wholesale_variant_fee' => 'required|numeric|min:0',
            'personalization_active' => 'required|integer|in:0,1',
            'personalization_fee' => 'required|numeric|min:0',
            'personalization_label' => 'nullable|string|max:255',
            'personalization_details_label' => 'nullable|string|max:255',
            'personalization_placeholder' => 'nullable|string|max:500',
            'quantity_available' => 'required|integer|min:0',
            'warehouse_stock_level' => 'required|integer|min:0',
            'reserved_stock' => 'required|integer|min:0',
            'downloadFile' => 'nullable|file|max:51200',
            'download_expiration' => 'nullable',
            'downloads_max_allowed' => 'nullable|integer|min:1',
            'paddle_price' => 'nullable|numeric|min:0',
            'paddle_interval' => 'nullable|string|in:day,week,month,year,',
            'paddle_frequency' => 'nullable|integer|min:1',
            'paddle_currency_code' => 'required|string|max:10',
            'event_start_date' => $this->is_event ? 'required' : 'nullable',
            'event_label'      => $this->is_event ? 'required|string|max:255' : 'nullable',
        ]);

        if ($this->download_item && !$this->downloadFile && !$this->direct_download_url) {
            $this->addError('downloadFile', 'A download file or direct download URL is required when Digital Product is enabled.');
            return;
        }

        // Auto-commit any pending URL-mode image set that hasn't been explicitly added yet.
        // This handles the case where the user filled in URLs and clicked Save without
        // pressing "Add Image Set" first.
        if ($this->new_image_url_source && $this->new_thumbnail_url && $this->new_main_url) {
            $this->addImageSet();
        }

        // Sync inline attributes back to JSON string before saving
        $this->updatedInlineAttributes();

        if ($this->download_s3 == 2) {
            $this->validate([
                'download_s3_region'            => 'required|string',
                'download_s3_bucket_name'       => 'required|string',
                'download_s3_access_key_id'     => 'required|string',
                'download_s3_secret_access_key' => 'required|string',
            ]);
        }

        if ($this->new_image_s3 == 2) {
            $this->validate([
                'new_image_s3_region'            => 'required|string',
                'new_image_s3_bucket_name'       => 'required|string',
                'new_image_s3_access_key_id'     => 'required|string',
                'new_image_s3_secret_access_key' => 'required|string',
            ]);
        }

        foreach ($this->imageSets as $index => $set) {
            if (($set['image_s3'] ?? 0) == 2) {
                $this->validate([
                    "imageSets.{$index}.image_s3_region"            => 'required|string',
                    "imageSets.{$index}.image_s3_bucket_name"       => 'required|string',
                    "imageSets.{$index}.image_s3_access_key_id"     => 'required|string',
                    "imageSets.{$index}.image_s3_secret_access_key" => 'required|string',
                ], [], [
                    "imageSets.{$index}.image_s3_region"            => "Image Set #" . ($index + 1) . " S3 Region",
                    "imageSets.{$index}.image_s3_bucket_name"       => "Image Set #" . ($index + 1) . " S3 Bucket Name",
                    "imageSets.{$index}.image_s3_access_key_id"     => "Image Set #" . ($index + 1) . " S3 Access Key ID",
                    "imageSets.{$index}.image_s3_secret_access_key" => "Image Set #" . ($index + 1) . " S3 Secret Access Key",
                ]);
            }
        }

        $variant = ProductVariant::create([
            'product_id' => $this->productId,
            'sku' => $this->sku,
            'public_price' => $this->public_price,
            'wholesale_price' => $this->wholesale_price,
            'on_sale' => $this->on_sale,
            'sale_price' => $this->on_sale ? $this->sale_price : 0.00,
            'variant_fee' => $this->variant_fee,
            'wholesale_variant_fee' => $this->wholesale_variant_fee,
            'personalization_active' => $this->personalization_active,
            'personalization_fee' => $this->personalization_fee,
            'personalization_label' => $this->personalization_label,
            'personalization_details_label' => $this->personalization_details_label,
            'personalization_placeholder' => $this->personalization_placeholder,
            'shipping' => $this->shipping,
            'charge_tax' => $this->charge_tax,
            'weight' => $this->weight,
            'weight_type' => $this->weight_type,
            'attributes' => $this->variantAttributes,
            'download_item' => $this->download_item,
            'direct_download_url' => $this->direct_download_url ?: null,
            'download_label' => $this->download_label ?: null,
            'download_s3' => $this->download_s3,
            'download_s3_region' => $this->download_s3_region,
            'download_s3_bucket_name' => $this->download_s3_bucket_name,
            'download_s3_access_key_id' => $this->download_s3_access_key_id,
            'download_s3_secret_access_key' => $this->download_s3_secret_access_key,
            'download_cdn_url' => $this->cdn_url,
            'download_expiration' => $this->download_item ? $this->download_expiration : null,
            'downloads_max_allowed' => $this->download_item ? $this->downloads_max_allowed : null,
            // Processor price IDs
            'paddle_sandbox_price_id'   => $this->paddle_sandbox_price_id   ?: null,
            'paddle_live_price_id'      => $this->paddle_live_price_id      ?: null,
            'paddle_price'              => $this->paddle_price              !== null ? (float)$this->paddle_price : null,
            'paddle_interval'           => $this->paddle_interval           ?: null,
            'paddle_frequency'          => $this->paddle_frequency          !== null ? (int)$this->paddle_frequency : null,
            'paddle_currency_code'      => $this->paddle_currency_code      ?: 'USD',
            'stripe_sandbox_price_id'   => $this->stripe_sandbox_price_id   ?: null,
            'stripe_live_price_id'      => $this->stripe_live_price_id      ?: null,
            'create_new_stripe_product' => $this->create_new_stripe_product,
            'stripe_billing_interval'   => $this->stripe_billing_interval   ?: 'month',
            'stripe_trial_enabled'      => $this->stripe_trial_enabled,
            'stripe_trial_days'         => $this->stripe_trial_enabled ? $this->stripe_trial_days : 0,
        ]);

        $this->syncQtyDiscounts($variant);

        // Event details
        $variant->update(['is_event' => $this->is_event]);
        if ($this->is_event) {
            \App\Models\ProductVariantEvent::updateOrCreate(
                ['variant_id' => $variant->id],
                [
                    'event_start_date'  => $this->event_start_date ?: now(),
                    'event_end_date'    => $this->event_end_date    ?: null,
                    'event_label'       => $this->event_label,
                    'alternate_label'   => $this->alternate_label   ?: null,
                    'label_background'  => $this->label_background  ?: '#4f46e5',
                    'show_date'         => $this->show_date,
                    'event_location'    => $this->event_location     ?: null,
                    'event_description' => $this->event_description  ?: null,
                    'event_sort'        => $this->event_sort,
                ]
            );
        } else {
            \App\Models\ProductVariantEvent::where('variant_id', $variant->id)->delete();
        }

        ProductInventory::create([
            'variant_id' => $variant->id,
            'quantity_available' => $this->quantity_available,
            'warehouse_stock_level' => $this->warehouse_stock_level,
            'use_warehouse_stock' => $this->use_warehouse_stock,
            'reserved_stock' => $this->reserved_stock,
            'location_id' => 1
        ]);

        if (!$this->storeUploadedFiles($variant)) {
            $variant->delete();
            return;
        }

        $this->isCreatingVariant = false;
        session()->flash('status', 'Variant added successfully.');
        $this->loadProduct();
    }

    public function startEditVariant(int $variantId): void
    {
        $variant = ProductVariant::with(['inventory', 'images'])->findOrFail($variantId);
        $this->selectedVariantId = $variant->id;
        $this->sku = $variant->sku ?? '';
        $this->public_price = (float) ($variant->public_price ?? 0.00);
        $this->wholesale_price = (float) ($variant->wholesale_price ?? 0.00);
        $this->on_sale = (int) ($variant->on_sale ?? 0);
        $this->sale_price = (float) ($variant->sale_price ?? 0.00);
        $this->variant_fee = (float) ($variant->variant_fee ?? 0.00);
        $this->wholesale_variant_fee = (float) ($variant->wholesale_variant_fee ?? 0.00);
        $this->personalization_active = (int) ($variant->personalization_active ?? 0);
        $this->personalization_fee = (float) ($variant->personalization_fee ?? 0.00);
        $this->personalization_label = $variant->personalization_label ?? 'Add Gift Wrapping / Personalization';
        $this->personalization_details_label = $variant->personalization_details_label ?? 'Personalization Details / Gift Message';
        $this->personalization_placeholder = $variant->personalization_placeholder ?? 'Enter names for engraving, personalization details, or a custom gift message here...';
        $this->shipping = (int) ($variant->shipping ?? 1);
        $this->charge_tax = (int) ($variant->charge_tax ?? 1);
        $this->weight = (float) ($variant->weight ?? 0.0);
        $this->weight_type = $variant->weight_type ?? 'lbs';
        $this->variantAttributes = $variant->attributes ?? '';

        // Processor price IDs
        $this->paddle_sandbox_price_id    = $variant->paddle_sandbox_price_id    ?? '';
        $this->paddle_live_price_id       = $variant->paddle_live_price_id       ?? '';
        $this->paddle_price               = $variant->paddle_price !== null ? (float)$variant->paddle_price : null;
        $this->paddle_interval            = $variant->paddle_interval            ?? '';
        $this->paddle_frequency           = $variant->paddle_frequency !== null ? (int)$variant->paddle_frequency : 1;
        $this->paddle_currency_code       = $variant->paddle_currency_code       ?? 'USD';
        $this->stripe_sandbox_price_id    = $variant->stripe_sandbox_price_id    ?? '';
        $this->stripe_live_price_id       = $variant->stripe_live_price_id       ?? '';
        $this->create_new_stripe_product  = (int) ($variant->create_new_stripe_product ?? 0);
        $this->stripe_billing_interval    = $variant->stripe_billing_interval    ?? 'month';
        $this->stripe_trial_enabled       = (int) ($variant->stripe_trial_enabled ?? 0);
        $this->stripe_trial_days          = (int) ($variant->stripe_trial_days   ?? 0);

        
        // Decode attributes to the inline array
        $decoded = json_decode($this->variantAttributes, true);
        $this->inlineAttributes = [];
        if (is_array($decoded)) {
            foreach ($decoded as $k => $v) {
                $this->inlineAttributes[] = ['key' => $k, 'value' => $v];
            }
        }

        $this->quantity_available = $variant->inventory ? (int) $variant->inventory->quantity_available : 0;
        $this->reserved_stock = $variant->inventory ? (int) $variant->inventory->reserved_stock : 0;
        $this->warehouse_stock_level = $variant->inventory ? (int) $variant->inventory->warehouse_stock_level : 0;
        $this->use_warehouse_stock = $variant->inventory ? (bool) $variant->inventory->use_warehouse_stock : false;
        
        $this->download_item = $variant->download_item;
        $this->download_location = $variant->download_location ?? '';
        $this->direct_download_url = $variant->direct_download_url ?? '';
        $this->download_label = $variant->download_label ?? '';
        $this->download_s3 = $variant->download_s3;
        $this->download_s3_region = $variant->download_s3_region ?? '';
        $this->download_s3_bucket_name = $variant->download_s3_bucket_name ?? '';
        $this->download_s3_access_key_id = $variant->download_s3_access_key_id ?? '';
        $this->download_s3_secret_access_key = $variant->download_s3_secret_access_key ?? '';
        $this->download_expiration = $variant->download_expiration ? $variant->download_expiration->format('Y-m-d\TH:i') : null;
        $this->downloads_max_allowed = $variant->downloads_max_allowed ?? 100;
 
        $this->image_s3 = 0;
        $this->cdn_url = $variant->download_cdn_url ?? '';
        $this->s3_folder = '';

        $this->qtyDiscounts = \App\Models\ProductQuantityDiscount::where('product_variant_id', $variant->id)
            ->orderBy('qty_min')
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'qty_min' => (int) $d->qty_min,
                'qty_max' => (int) $d->qty_max,
                'discount_value' => (float) $d->discount_value,
                'value_type' => (int) $d->value_type,
            ])
            ->toArray();
  
        $this->current_download_location = $variant->download_location;
        $this->downloadFile = null;

        // Event details
        $this->is_event = (bool) ($variant->is_event ?? false);
        $evt = $variant->eventDetails;
        $this->event_start_date = $evt ? $evt->event_start_date->format('Y-m-d\TH:i') : '';
        $this->event_end_date   = ($evt && $evt->event_end_date) ? $evt->event_end_date->format('Y-m-d\TH:i') : '';
        $this->event_label      = $evt->event_label      ?? '';
        $this->alternate_label  = $evt->alternate_label  ?? '';
        $this->label_background = $evt->label_background ?? '#4f46e5';
        $this->show_date        = (bool) ($evt->show_date ?? true);
        $this->event_location   = $evt->event_location   ?? '';
        $this->event_description = $evt->event_description ?? '';
        $this->event_sort       = (float) ($evt->event_sort ?? 0.0);

        // Reset image set properties
        $this->imageSets = $variant->images->map(function ($set) {
            return [
                'id'                         => $set->id,
                'thumbnail_path'             => $set->thumbnail_path,
                'main_path'                  => $set->main_path,
                'zoom_path'                  => $set->zoom_path,
                'thumbnail_url'              => $set->thumbnailUrl(),
                'main_url'                   => $set->mainUrl(),
                'zoom_url'                   => $set->zoomUrl(),
                'search_image'               => $set->search_image,
                'active'                     => $set->active,
                'image_s3'                   => $set->image_s3,
                'image_url_source'           => (int) $set->image_url_source,
                'cdn_url'                    => $set->cdn_url,
                'image_s3_region'            => $set->image_s3_region,
                'image_s3_bucket_name'       => $set->image_s3_bucket_name,
                'image_s3_access_key_id'     => $set->image_s3_access_key_id,
                'image_s3_secret_access_key' => $set->image_s3_secret_access_key,
                'file_thumbnail'             => null,
                'file_main'                  => null,
                'file_zoom'                  => null,
                'image_alt'                  => $set->image_alt ?? '',
                'zoom_label'                 => $set->zoom_label ?? '',
            ];
        })->toArray();

        $this->deletedImageSetIds = [];
        $this->replaceThumbnail = [];
        $this->replaceMain = [];
        $this->replaceZoom = [];

        // Reset new image set inputs
        $this->new_thumbnail = null;
        $this->new_main = null;
        $this->new_zoom = null;
        $this->new_image_s3 = 0;
        $this->new_cdn_url = '';
        $this->new_image_url_source = false;
        $this->new_thumbnail_url = '';
        $this->new_main_url = '';
        $this->new_zoom_url = '';
        $this->new_image_s3_region = '';
        $this->new_image_s3_bucket_name = '';
        $this->new_image_s3_access_key_id = '';
        $this->new_image_s3_secret_access_key = '';
        $this->new_image_alt = '';
        $this->new_zoom_label = '';

        // Reset variant translation panel so stale state from a prior variant edit
        // doesn't carry over when the user switches to a different variant.
        $this->variantTransLangCode                = '';
        $this->variantTransLangId                  = null;
        $this->trans_personalization_label         = '';
        $this->trans_personalization_details_label = '';
        $this->trans_personalization_placeholder   = '';
        $this->trans_attributes                    = [];


        $this->isEditingVariant = true;
    }

    public function cancelEditVariant(): void
    {
        $this->isEditingVariant = false;
        $this->selectedVariantId = 0;
        $this->imageSets = [];
        $this->deletedImageSetIds = [];
        $this->qtyDiscounts = [];
    }

    public function updateVariant(): void
    {
        // Sync inline attributes back to JSON string before validation
        $this->updatedInlineAttributes();

        $this->validate([
            'sku' => 'required|string|max:255|unique:product_variants,sku,' . $this->selectedVariantId,
            'public_price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0',
            'variant_fee' => 'required|numeric|min:0',
            'wholesale_variant_fee' => 'required|numeric|min:0',
            'personalization_active' => 'required|integer|in:0,1',
            'personalization_fee' => 'required|numeric|min:0',
            'personalization_label' => 'nullable|string|max:255',
            'personalization_details_label' => 'nullable|string|max:255',
            'personalization_placeholder' => 'nullable|string|max:500',
            'quantity_available' => 'required|integer|min:0',
            'warehouse_stock_level' => 'required|integer|min:0',
            'reserved_stock' => 'required|integer|min:0',
            'downloadFile' => 'nullable|file|max:51200',
            'download_expiration' => 'nullable',
            'downloads_max_allowed' => 'nullable|integer|min:1',
            'paddle_price' => 'nullable|numeric|min:0',
            'paddle_interval' => 'nullable|string|in:day,week,month,year,',
            'paddle_frequency' => 'nullable|integer|min:1',
            'paddle_currency_code' => 'required|string|max:10',
            'event_start_date' => $this->is_event ? 'required' : 'nullable',
            'event_label'      => $this->is_event ? 'required|string|max:255' : 'nullable',
        ]);

        if ($this->download_item && !$this->downloadFile && !$this->current_download_location && !$this->direct_download_url) {
            $this->addError('downloadFile', 'A download file or direct download URL is required when Digital Product is enabled.');
            return;
        }

        if ($this->download_s3 == 2) {
            $this->validate([
                'download_s3_region'            => 'required|string',
                'download_s3_bucket_name'       => 'required|string',
                'download_s3_access_key_id'     => 'required|string',
                'download_s3_secret_access_key' => 'required|string',
            ]);
        }

        // Auto-commit any pending URL-mode image set that hasn't been explicitly added yet.
        if ($this->new_image_url_source && $this->new_thumbnail_url && $this->new_main_url) {
            $this->addImageSet();
        }

        if ($this->new_image_s3 == 2) {
            $this->validate([
                'new_image_s3_region'            => 'required|string',
                'new_image_s3_bucket_name'       => 'required|string',
                'new_image_s3_access_key_id'     => 'required|string',
                'new_image_s3_secret_access_key' => 'required|string',
            ]);
        }

        foreach ($this->imageSets as $index => $set) {
            if (($set['image_s3'] ?? 0) == 2) {
                $this->validate([
                    "imageSets.{$index}.image_s3_region"            => 'required|string',
                    "imageSets.{$index}.image_s3_bucket_name"       => 'required|string',
                    "imageSets.{$index}.image_s3_access_key_id"     => 'required|string',
                    "imageSets.{$index}.image_s3_secret_access_key" => 'required|string',
                ], [], [
                    "imageSets.{$index}.image_s3_region"            => "Image Set #" . ($index + 1) . " S3 Region",
                    "imageSets.{$index}.image_s3_bucket_name"       => "Image Set #" . ($index + 1) . " S3 Bucket Name",
                    "imageSets.{$index}.image_s3_access_key_id"     => "Image Set #" . ($index + 1) . " S3 Access Key ID",
                    "imageSets.{$index}.image_s3_secret_access_key" => "Image Set #" . ($index + 1) . " S3 Secret Access Key",
                ]);
            }
        }

        $variant = ProductVariant::findOrFail($this->selectedVariantId);
        $variant->update([
            'sku' => $this->sku,
            'public_price' => $this->public_price,
            'wholesale_price' => $this->wholesale_price,
            'on_sale' => $this->on_sale,
            'sale_price' => $this->on_sale ? $this->sale_price : 0.00,
            'variant_fee' => $this->variant_fee,
            'wholesale_variant_fee' => $this->wholesale_variant_fee,
            'personalization_active' => $this->personalization_active,
            'personalization_fee' => $this->personalization_fee,
            'personalization_label' => $this->personalization_label,
            'personalization_details_label' => $this->personalization_details_label,
            'personalization_placeholder' => $this->personalization_placeholder,
            'shipping' => $this->shipping,
            'charge_tax' => $this->charge_tax,
            'weight' => $this->weight,
            'weight_type' => $this->weight_type,
            'attributes' => $this->variantAttributes,
            'download_item' => $this->download_item,
            'direct_download_url' => $this->direct_download_url ?: null,
            'download_label' => $this->download_label ?: null,
            'download_s3' => $this->download_s3,
            'download_s3_region' => $this->download_s3_region,
            'download_s3_bucket_name' => $this->download_s3_bucket_name,
            'download_s3_access_key_id' => $this->download_s3_access_key_id,
            'download_s3_secret_access_key' => $this->download_s3_secret_access_key,
            'download_cdn_url' => $this->cdn_url,
            'download_expiration' => $this->download_item ? $this->download_expiration : null,
            'downloads_max_allowed' => $this->download_item ? $this->downloads_max_allowed : null,
            // Processor price IDs
            'paddle_sandbox_price_id'  => $this->paddle_sandbox_price_id  ?: null,
            'paddle_live_price_id'     => $this->paddle_live_price_id     ?: null,
            'paddle_price'             => $this->paddle_price             !== null ? (float)$this->paddle_price : null,
            'paddle_interval'          => $this->paddle_interval          ?: null,
            'paddle_frequency'         => $this->paddle_frequency         !== null ? (int)$this->paddle_frequency : null,
            'paddle_currency_code'     => $this->paddle_currency_code     ?: 'USD',
            'stripe_sandbox_price_id'  => $this->stripe_sandbox_price_id  ?: null,
            'stripe_live_price_id'     => $this->stripe_live_price_id     ?: null,
            'create_new_stripe_product'=> $this->create_new_stripe_product,
            'stripe_billing_interval'  => $this->stripe_billing_interval  ?: 'month',
            'stripe_trial_enabled'     => $this->stripe_trial_enabled,
            'stripe_trial_days'        => $this->stripe_trial_enabled ? $this->stripe_trial_days : 0,
        ]);

        if ($variant->inventory) {
            $variant->inventory->update([
                'quantity_available' => $this->quantity_available,

                'warehouse_stock_level' => $this->warehouse_stock_level,
                'use_warehouse_stock' => $this->use_warehouse_stock,
                'reserved_stock' => $this->reserved_stock,
            ]);
        } else {
            ProductInventory::create([
                'variant_id' => $variant->id,
                'quantity_available' => $this->quantity_available,
                'warehouse_stock_level' => $this->warehouse_stock_level,
                'use_warehouse_stock' => $this->use_warehouse_stock,
                'reserved_stock' => $this->reserved_stock,
                'location_id' => 1
            ]);
        }

        if (!$this->storeUploadedFiles($variant)) {
            return;
        }
        $this->syncQtyDiscounts($variant);

        // Event details
        $variant->update(['is_event' => $this->is_event]);
        if ($this->is_event) {
            \App\Models\ProductVariantEvent::updateOrCreate(
                ['variant_id' => $variant->id],
                [
                    'event_start_date'  => $this->event_start_date ?: now(),
                    'event_end_date'    => $this->event_end_date    ?: null,
                    'event_label'       => $this->event_label,
                    'alternate_label'   => $this->alternate_label   ?: null,
                    'label_background'  => $this->label_background  ?: '#4f46e5',
                    'show_date'         => $this->show_date,
                    'event_location'    => $this->event_location     ?: null,
                    'event_description' => $this->event_description  ?: null,
                    'event_sort'        => $this->event_sort,
                ]
            );
        } else {
            \App\Models\ProductVariantEvent::where('variant_id', $variant->id)->delete();
        }

        $this->isEditingVariant = false;
        $this->selectedVariantId = 0;
        $this->qtyDiscounts = [];
        session()->flash('status', 'Variant details and stock levels updated successfully.');
        $this->loadProduct();
    }

    private function storeUploadedFiles(ProductVariant $variant): bool
    {
        // 1. Handle downloadable product file
        if ($this->downloadFile) {
            $diskName = 'public';
            if ($this->download_s3 == 1) {
                $diskName = 's3';
            } elseif ($this->download_s3 == 2) {
                $diskName = 'custom_s3_' . $variant->id;
                config([
                    "filesystems.disks.{$diskName}" => [
                        'driver' => 's3',
                        'key' => $variant->download_s3_access_key_id,
                        'secret' => $variant->download_s3_secret_access_key,
                        'region' => $variant->download_s3_region,
                        'bucket' => $variant->download_s3_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            }

            $folder = $this->s3_folder ?: 'downloads';
            
            // Delete old file if exists (best-effort — swallow S3 connectivity errors)
            if ($variant->download_location) {
                try {
                    if (Storage::disk($diskName)->exists($variant->download_location)) {
                        Storage::disk($diskName)->delete($variant->download_location);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Could not delete old download file.', [
                        'disk'  => $diskName,
                        'path'  => $variant->download_location,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            try {
                \Log::debug("Attempting to store downloadFile", [
                    'diskName' => $diskName,
                    'folder' => $folder,
                    'file_class' => get_class($this->downloadFile),
                    'file_name' => $this->downloadFile->getClientOriginalName(),
                    'file_size' => $this->downloadFile->getSize(),
                    'temp_path' => $this->downloadFile->getRealPath()
                ]);

                $path = $this->downloadFile->store($folder, $diskName);

                \Log::debug("Store completed", ['path' => $path]);

                if (!$path || !Storage::disk($diskName)->exists($path)) {
                    $exists = $path ? Storage::disk($diskName)->exists($path) : false;
                    $absPath = '';
                    try {
                        $absPath = $path ? Storage::disk($diskName)->path($path) : 'N/A';
                    } catch (\Throwable $e) {
                        $absPath = 'Unable to resolve path: ' . $e->getMessage();
                    }
                    \Log::error("File does not exist after store", [
                        'path' => $path,
                        'exists' => $exists,
                        'absolute_path' => $absPath
                    ]);
                    $this->addError('downloadFile', "Failed to store the uploaded file. Verified path: '" . var_export($path, true) . "' (absolute: {$absPath}) does not exist. Please check folder permissions.");
                    return false;
                }
            } catch (\Throwable $e) {
                \Log::error("Store failed with exception", [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $this->addError('downloadFile', 'Failed to store file: ' . $e->getMessage());
                return false;
            }

            $variant->update(['download_location' => $path]);
        }

        // 2. Handle deleted image sets
        foreach ($this->deletedImageSetIds as $id) {
            $oldSet = ProductImage::find($id);
            if ($oldSet) {
                $this->deleteSetFiles($oldSet, $variant);
                $oldSet->delete();
            }
        }

        // 3. Handle image sets additions / replacements
        foreach ($this->imageSets as $index => $set) {
            $thumbnailFile = $this->replaceThumbnail[$index] ?? $set['file_thumbnail'] ?? null;
            $mainFile = $this->replaceMain[$index] ?? $set['file_main'] ?? null;
            $zoomFile = $this->replaceZoom[$index] ?? $set['file_zoom'] ?? null;

            $thumbnailPath = $set['thumbnail_path'] ?? '';
            $mainPath = $set['main_path'] ?? '';
            $zoomPath = $set['zoom_path'] ?? null;

            $imageS3 = $set['image_s3'] ?? 0;
            $cdnUrl = $set['cdn_url'] ?? null;

            $s3Config = null;
            if ($imageS3 == 2) {
                $s3Config = [
                    'key'    => $set['image_s3_access_key_id'] ?? null,
                    'secret' => $set['image_s3_secret_access_key'] ?? null,
                    'region' => $set['image_s3_region'] ?? null,
                    'bucket' => $set['image_s3_bucket_name'] ?? null,
                ];
            }

            // Upload thumbnail if updated
            if ($thumbnailFile) {
                if ($set['id'] && ($set['thumbnail_path'] ?? '')) {
                    $this->deleteFileHelper($set['thumbnail_path'], $set['image_s3'], $variant, $s3Config);
                }
                $thumbnailPath = $this->uploadFileHelper($thumbnailFile, $imageS3, 'images/thumbnails', $variant, $s3Config);
            }

            // Upload main if updated
            if ($mainFile) {
                if ($set['id'] && ($set['main_path'] ?? '')) {
                    $this->deleteFileHelper($set['main_path'], $set['image_s3'], $variant, $s3Config);
                }
                $mainPath = $this->uploadFileHelper($mainFile, $imageS3, 'images/mains', $variant, $s3Config);
            }

            // Upload zoom if updated
            if ($zoomFile) {
                if ($set['id'] && ($set['zoom_path'] ?? '')) {
                    $this->deleteFileHelper($set['zoom_path'], $set['image_s3'], $variant, $s3Config);
                }
                $zoomPath = $this->uploadFileHelper($zoomFile, $imageS3, 'images/zooms', $variant, $s3Config);
            }

            $urlSource = (int)($set['image_url_source'] ?? 0);

            // When image_url_source == 1, the URLs live in thumbnail_path / main_path / zoom_path.
            $resolvedThumbnailPath = ($urlSource === 1) ? ($set['thumbnail_path'] ?? '') : $thumbnailPath;
            $resolvedMainPath      = ($urlSource === 1) ? ($set['main_path'] ?? '') : $mainPath;
            $resolvedZoomPath      = ($urlSource === 1) ? ($set['zoom_path'] ?? null) : $zoomPath;

            $imageData = [
                'thumbnail_path'             => $resolvedThumbnailPath,
                'main_path'                  => $resolvedMainPath,
                'zoom_path'                  => $resolvedZoomPath,
                'image_s3'                   => ($urlSource === 1) ? 0 : $imageS3,
                'image_url_source'           => $urlSource,
                'cdn_url'                    => ($urlSource === 1) ? null : $cdnUrl,
                'search_image'               => $set['search_image'] ?? 0,
                'active'                     => $set['active'] ?? 1,
                'image_s3_region'            => ($urlSource === 1) ? null : ($set['image_s3_region'] ?? null),
                'image_s3_bucket_name'       => ($urlSource === 1) ? null : ($set['image_s3_bucket_name'] ?? null),
                'image_s3_access_key_id'     => ($urlSource === 1) ? null : ($set['image_s3_access_key_id'] ?? null),
                'image_s3_secret_access_key' => ($urlSource === 1) ? null : ($set['image_s3_secret_access_key'] ?? null),
                'image_alt'                  => $set['image_alt'] ?? '',
                'zoom_label'                 => $set['zoom_label'] ?? '',
            ];

            if ($set['id']) {
                // Update existing record
                $imgRecord = ProductImage::findOrFail($set['id']);
                $imgRecord->update($imageData);
            } else {
                // Create new record
                $imageData['variant_id'] = $variant->id;
                ProductImage::create($imageData);
            }
        }

        return true;
    }

    private function uploadFileHelper($file, int $s3Setting, string $folder, ProductVariant $variant, ?array $s3Config = null): string
    {
        $diskName = 'public';
        if ($s3Setting == 1) {
            $diskName = 's3';
        } elseif ($s3Setting == 2) {
            $diskName = 'custom_image_s3_' . ($variant->id ?: 'temp');
            $key = $s3Config ? ($s3Config['key'] ?? '') : $variant->download_s3_access_key_id;
            $secret = $s3Config ? ($s3Config['secret'] ?? '') : $variant->download_s3_secret_access_key;
            $region = $s3Config ? ($s3Config['region'] ?? '') : $variant->download_s3_region;
            $bucket = $s3Config ? ($s3Config['bucket'] ?? '') : $variant->download_s3_bucket_name;

            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $key,
                    'secret' => $secret,
                    'region' => $region,
                    'bucket' => $bucket,
                    'use_path_style_endpoint' => false,
                ]
            ]);
        }

        $prefix = $this->s3_folder ? trim($this->s3_folder, '/') . '/' : '';

        try {
            return $file->store($prefix . $folder, $diskName) ?: '';
        } catch (\Throwable $e) {
            \Log::warning('Image upload failed (bad S3 config or connectivity).', [
                'disk'      => $diskName,
                'folder'    => $prefix . $folder,
                'error'     => $e->getMessage(),
            ]);
            return '';
        }
    }

    private function deleteFileHelper(?string $path, int $s3Setting, ProductVariant $variant, ?array $s3Config = null): void
    {
        if (!$path) return;

        $diskName = 'public';
        if ($s3Setting == 1) {
            $diskName = 's3';
        } elseif ($s3Setting == 2) {
            $diskName = 'custom_image_s3_' . ($variant->id ?: 'temp');
            $key = $s3Config ? ($s3Config['key'] ?? '') : $variant->download_s3_access_key_id;
            $secret = $s3Config ? ($s3Config['secret'] ?? '') : $variant->download_s3_secret_access_key;
            $region = $s3Config ? ($s3Config['region'] ?? '') : $variant->download_s3_region;
            $bucket = $s3Config ? ($s3Config['bucket'] ?? '') : $variant->download_s3_bucket_name;

            config([
                "filesystems.disks.{$diskName}" => [
                    'driver' => 's3',
                    'key' => $key,
                    'secret' => $secret,
                    'region' => $region,
                    'bucket' => $bucket,
                    'use_path_style_endpoint' => false,
                ]
            ]);
        }

        try {
            if (Storage::disk($diskName)->exists($path)) {
                Storage::disk($diskName)->delete($path);
            }
        } catch (\Throwable $e) {
            // Best-effort delete: swallow connectivity / credential errors so a bad
            // S3 config never prevents the variant from being saved or the row deleted.
            \Log::warning('Could not delete image file (bad S3 config or connectivity).', [
                'disk'  => $diskName,
                'path'  => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function deleteSetFiles(ProductImage $set, ProductVariant $variant): void
    {
        $s3Config = null;
        if ($set->image_s3 == 2) {
            $s3Config = [
                'key'    => $set->image_s3_access_key_id,
                'secret' => $set->image_s3_secret_access_key,
                'region' => $set->image_s3_region,
                'bucket' => $set->image_s3_bucket_name,
            ];
        }
        $this->deleteFileHelper($set->thumbnail_path, $set->image_s3, $variant, $s3Config);
        $this->deleteFileHelper($set->main_path, $set->image_s3, $variant, $s3Config);
        $this->deleteFileHelper($set->zoom_path, $set->image_s3, $variant, $s3Config);
    }

    public function toggleSearchImage(int $index): void
    {
        $variantCount = ProductVariant::where('product_id', $this->productId)->count();
        $currentlyActive = ($this->imageSets[$index]['search_image'] ?? 0) == 1;

        if ($currentlyActive) {
            $imageSetsCount = count($this->imageSets);
            if ($variantCount > 1 || $imageSetsCount > 1) {
                $this->imageSets[$index]['search_image'] = 0;
            }
        } else {
            foreach ($this->imageSets as $i => $set) {
                if ($i == $index) {
                    $this->imageSets[$i]['search_image'] = 1;
                } else {
                    $this->imageSets[$i]['search_image'] = 0;
                }
            }
        }
    }

    public function toggleActiveImage(int $index): void
    {
        $this->imageSets[$index]['active'] = $this->imageSets[$index]['active'] == 1 ? 0 : 1;
    }

    public function removeImageSet(int $index): void
    {
        $set = $this->imageSets[$index];
        if (isset($set['id']) && $set['id']) {
            $this->deletedImageSetIds[] = $set['id'];
        }
        unset($this->imageSets[$index]);
        $this->imageSets = array_values($this->imageSets);

        $this->replaceThumbnail = array_values($this->replaceThumbnail);
        $this->replaceMain = array_values($this->replaceMain);
        $this->replaceZoom = array_values($this->replaceZoom);
    }

    public function addImageSet(): void
    {
        if ($this->new_image_url_source) {
            // URL mode: validate that thumbnail and main URLs are provided
            $this->validate([
                'new_thumbnail_url' => 'required|url|max:2048',
                'new_main_url'      => 'required|url|max:2048',
                'new_zoom_url'      => 'nullable|url|max:2048',
            ]);

            $isFirst = empty($this->imageSets);

            $this->imageSets[] = [
                'id'                         => null,
                'thumbnail_path'             => $this->new_thumbnail_url,
                'main_path'                  => $this->new_main_url,
                'zoom_path'                  => $this->new_zoom_url ?: null,
                'thumbnail_url'              => $this->new_thumbnail_url,
                'main_url'                   => $this->new_main_url,
                'zoom_url'                   => $this->new_zoom_url ?: null,
                'search_image'               => $isFirst ? 1 : 0,
                'active'                     => 1,
                'image_s3'                   => 0,
                'image_url_source'           => 1,
                'cdn_url'                    => null,
                'image_s3_region'            => null,
                'image_s3_bucket_name'       => null,
                'image_s3_access_key_id'     => null,
                'image_s3_secret_access_key' => null,
                'file_thumbnail'             => null,
                'file_main'                  => null,
                'file_zoom'                  => null,
                'image_alt'                  => $this->new_image_alt,
                'zoom_label'                 => $this->new_zoom_label,
            ];

            // Reset the URL toggle back to file-upload mode after adding
            $this->new_image_url_source = false;
        } else {
            // File-upload mode
            $this->validate([
                'new_thumbnail' => 'required|image|max:5120',
                'new_main'      => 'required|image|max:5120',
                'new_zoom'      => 'nullable|image|max:5120',
            ]);

            $isFirst = empty($this->imageSets);

            $this->imageSets[] = [
                'id'                         => null,
                'thumbnail_path'             => '',
                'main_path'                  => '',
                'zoom_path'                  => null,
                'thumbnail_url'              => $this->new_thumbnail->temporaryUrl(),
                'main_url'                   => $this->new_main->temporaryUrl(),
                'zoom_url'                   => $this->new_zoom ? $this->new_zoom->temporaryUrl() : null,
                'search_image'               => $isFirst ? 1 : 0,
                'active'                     => 1,
                'image_s3'                   => $this->new_image_s3,
                'image_url_source'           => 0,
                'cdn_url'                    => $this->new_cdn_url,
                'image_s3_region'            => $this->new_image_s3_region,
                'image_s3_bucket_name'       => $this->new_image_s3_bucket_name,
                'image_s3_access_key_id'     => $this->new_image_s3_access_key_id,
                'image_s3_secret_access_key' => $this->new_image_s3_secret_access_key,
                'file_thumbnail'             => $this->new_thumbnail,
                'file_main'                  => $this->new_main,
                'file_zoom'                  => $this->new_zoom,
                'image_alt'                  => $this->new_image_alt,
                'zoom_label'                 => $this->new_zoom_label,
            ];

            $this->new_thumbnail = null;
            $this->new_main = null;
            $this->new_zoom = null;
            $this->new_image_s3 = 0;
            $this->new_cdn_url = '';
            $this->new_image_s3_region = '';
            $this->new_image_s3_bucket_name = '';
            $this->new_image_s3_access_key_id = '';
            $this->new_image_s3_secret_access_key = '';
        }

        // Reset URL inputs and alt inputs after adding
        $this->new_thumbnail_url = '';
        $this->new_main_url = '';
        $this->new_zoom_url = '';
        $this->new_image_alt = '';
        $this->new_zoom_label = '';

        // ── Auto-save in edit mode ─────────────────────────────────────────────
        // If we're editing an existing variant, persist the new image set to the
        // database immediately so the user never loses it even if they forget to
        // click "Save Variant & Stock".
        if ($this->isEditingVariant && $this->selectedVariantId) {
            $variant = ProductVariant::find($this->selectedVariantId);
            if ($variant) {
                $this->storeUploadedFiles($variant);

                // Reload imageSets from DB so every entry has its real `id`.
                // This prevents a duplicate INSERT when the user later hits Save.
                $this->imageSets = $variant->fresh()->images->map(function ($set) {
                    return [
                        'id'                         => $set->id,
                        'thumbnail_path'             => $set->thumbnail_path,
                        'main_path'                  => $set->main_path,
                        'zoom_path'                  => $set->zoom_path,
                        'thumbnail_url'              => $set->thumbnailUrl(),
                        'main_url'                   => $set->mainUrl(),
                        'zoom_url'                   => $set->zoomUrl(),
                        'search_image'               => $set->search_image,
                        'active'                     => $set->active,
                        'image_s3'                   => $set->image_s3,
                        'image_url_source'           => (int) $set->image_url_source,
                        'cdn_url'                    => $set->cdn_url,
                        'image_s3_region'            => $set->image_s3_region,
                        'image_s3_bucket_name'       => $set->image_s3_bucket_name,
                        'image_s3_access_key_id'     => $set->image_s3_access_key_id,
                        'image_s3_secret_access_key' => $set->image_s3_secret_access_key,
                        'file_thumbnail'             => null,
                        'file_main'                  => null,
                        'file_zoom'                  => null,
                        'image_alt'                  => $set->image_alt ?? '',
                        'zoom_label'                 => $set->zoom_label ?? '',
                    ];
                })->toArray();

                // Clear processed state
                $this->deletedImageSetIds = [];
                $this->replaceThumbnail   = [];
                $this->replaceMain        = [];
                $this->replaceZoom        = [];

                session()->flash('image_auto_saved', 'Image set auto-saved to the variant.');
                $this->imageAutoSaved = true;
            }
        }
    }

    public function deleteVariant(int $variantId): void
    {
        $variant = ProductVariant::findOrFail($variantId);
        $variant->delete();
        session()->flash('status', 'Variant deleted successfully.');
        $this->loadProduct();
    }
    public function addFieldOptionRow(): void
    {
        $this->fieldOptions[] = [
            'id' => null,
            'option_value' => '',
            'option_price_modifier' => 0.00,
            'option_wholesale_price_modifier' => 0.00,
            'sort_order' => count($this->fieldOptions)
        ];
    }

    public function removeFieldOptionRow(int $index): void
    {
        unset($this->fieldOptions[$index]);
        $this->fieldOptions = array_values($this->fieldOptions);
    }

    public function editCustomField(int $fieldId): void
    {
        $field = \App\Models\ProductField::with('options')->findOrFail($fieldId);
        $this->isEditingField = true;
        $this->selectedFieldId = $field->id;
        $this->customFieldLabel = $field->label;
        $this->customFieldType = $field->field_type;
        $this->customFieldIsRequired = $field->is_required;
        $this->customFieldSortOrder = $field->sort_order;
        $this->customFieldChargeTax = (int)($field->charge_tax ?? 1);

        $this->fieldOptions = $field->options->map(function ($opt) {
            return [
                'id' => $opt->id,
                'option_value' => $opt->option_value,
                'option_price_modifier' => (float) $opt->option_price_modifier,
                'option_wholesale_price_modifier' => (float) $opt->option_wholesale_price_modifier,
                'sort_order' => $opt->sort_order,
            ];
        })->toArray();
    }

    public function deleteCustomField(int $fieldId): void
    {
        $field = \App\Models\ProductField::findOrFail($fieldId);
        $field->delete();

        session()->flash('status', 'Custom field deleted successfully.');
        $this->loadProduct();
    }

    public function resetFieldForm(): void
    {
        $this->isEditingField = false;
        $this->selectedFieldId = null;
        $this->customFieldLabel = '';
        $this->customFieldType = 'text';
        $this->customFieldIsRequired = false;
        $this->customFieldSortOrder = 0;
        $this->customFieldChargeTax = 1;
        $this->fieldOptions = [];
    }

    public function saveCustomField(): void
    {
        $this->validate([
            'customFieldLabel' => 'required|string|max:255',
            'customFieldType' => 'required|string|in:text,textarea,select,radio,checkbox,multiselect_checkbox',
            'customFieldIsRequired' => 'boolean',
            'customFieldSortOrder' => 'integer',
            'fieldOptions.*.option_value' => 'required_if:customFieldType,select,radio,checkbox,multiselect_checkbox|string|max:255',
            'fieldOptions.*.option_price_modifier' => 'numeric',
            'fieldOptions.*.option_wholesale_price_modifier' => 'numeric',
            'fieldOptions.*.sort_order' => 'integer',
        ]);

        if ($this->isEditingField && $this->selectedFieldId) {
            $field = \App\Models\ProductField::findOrFail($this->selectedFieldId);
            $field->update([
                'label' => $this->customFieldLabel,
                'field_type' => $this->customFieldType,
                'is_required' => $this->customFieldIsRequired,
                'sort_order' => $this->customFieldSortOrder,
                'charge_tax' => $this->customFieldChargeTax,
            ]);
            
            // Delete options that are no longer present
            $keepOptionIds = collect($this->fieldOptions)->pluck('id')->filter()->toArray();
            $field->options()->whereNotIn('id', $keepOptionIds)->delete();

            session()->flash('status', 'Custom field updated successfully.');
        } else {
            $field = \App\Models\ProductField::create([
                'product_id' => $this->productId,
                'label' => $this->customFieldLabel,
                'field_type' => $this->customFieldType,
                'is_required' => $this->customFieldIsRequired,
                'sort_order' => $this->customFieldSortOrder,
                'charge_tax' => $this->customFieldChargeTax,
            ]);
            session()->flash('status', 'Custom field created successfully.');
        }

        // Save options
        if (in_array($this->customFieldType, ['select', 'radio', 'checkbox', 'multiselect_checkbox'])) {
            foreach ($this->fieldOptions as $opt) {
                if (empty($opt['option_value'])) continue;

                if (!empty($opt['id'])) {
                    $optionModel = \App\Models\ProductFieldOption::findOrFail($opt['id']);
                    $optionModel->update([
                        'option_value' => $opt['option_value'],
                        'option_price_modifier' => $opt['option_price_modifier'],
                        'option_wholesale_price_modifier' => $opt['option_wholesale_price_modifier'],
                        'sort_order' => $opt['sort_order'],
                    ]);
                } else {
                    \App\Models\ProductFieldOption::create([
                        'product_field_id' => $field->id,
                        'option_value' => $opt['option_value'],
                        'option_price_modifier' => $opt['option_price_modifier'],
                        'option_wholesale_price_modifier' => $opt['option_wholesale_price_modifier'],
                        'sort_order' => $opt['sort_order'],
                    ]);
                }
            }
        } else {
            // Delete all options if type changed to text/textarea
            $field->options()->delete();
        }

        $this->resetFieldForm();
        $this->loadProduct();
    }
    public function addQtyDiscount(): void
    {
        $this->qtyDiscounts[] = [
            'qty_min' => 1,
            'qty_max' => 1000000,
            'discount_value' => 0.00,
            'value_type' => 1 // 1 = $, 2 = %
        ];
    }

    public function removeQtyDiscount(int $index): void
    {
        unset($this->qtyDiscounts[$index]);
        $this->qtyDiscounts = array_values($this->qtyDiscounts);
    }

    private function syncQtyDiscounts(ProductVariant $variant): void
    {
        \App\Models\ProductQuantityDiscount::where('product_variant_id', $variant->id)->delete();
        foreach ($this->qtyDiscounts as $break) {
            \App\Models\ProductQuantityDiscount::create([
                'product_variant_id' => $variant->id,
                'qty_min' => (int) $break['qty_min'],
                'qty_max' => (int) $break['qty_max'],
                'discount_value' => (float) $break['discount_value'],
                'value_type' => (int) $break['value_type']
            ]);
        }
    }

    public function recalculateProductRating(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $approvedReviews = $product->reviews()->where('approved', true)->get();
        if ($approvedReviews->isEmpty()) {
            $product->update(['reviews_rating' => 0.00]);
        } else {
            $average = $approvedReviews->avg('rating');
            $product->update(['reviews_rating' => round($average, 2)]);
        }
    }

    public function toggleReviewApproval(int $reviewId): void
    {
        $review = \App\Models\ProductReview::findOrFail($reviewId);
        $review->update(['approved' => !$review->approved]);
        $this->recalculateProductRating($review->product_id);
        
        session()->flash('status', 'Review approval status updated.');
        $this->loadProduct();
    }

    public function deleteReview(int $reviewId): void
    {
        $review = \App\Models\ProductReview::findOrFail($reviewId);
        $productId = $review->product_id;
        $review->delete();
        $this->recalculateProductRating($productId);
        
        session()->flash('status', 'Review deleted successfully.');
        $this->loadProduct();
    }

    public function editReview(int $reviewId): void
    {
        $review = \App\Models\ProductReview::findOrFail($reviewId);
        $this->selectedReviewId = $review->id;
        $this->reviewName = $review->name;
        $this->reviewLocation = $review->location ?? '';
        $this->reviewRating = $review->rating;
        $this->reviewComments = $review->comments ?? '';
        $this->reviewApproved = (bool) $review->approved;
        $this->isEditingReview = true;
    }

    public function cancelEditReview(): void
    {
        $this->isEditingReview = false;
        $this->resetReviewForm();
    }

    public function saveReview(): void
    {
        $this->validate([
            'reviewName' => 'required|string|max:255',
            'reviewLocation' => 'nullable|string|max:255',
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComments' => 'nullable|string',
            'reviewApproved' => 'boolean',
        ]);

        $review = \App\Models\ProductReview::findOrFail($this->selectedReviewId);
        $review->update([
            'name' => strip_tags(trim($this->reviewName)),
            'location' => strip_tags(trim($this->reviewLocation)),
            'rating' => $this->reviewRating,
            'comments' => strip_tags(trim($this->reviewComments)),
            'approved' => $this->reviewApproved,
        ]);

        $this->recalculateProductRating($review->product_id);
        $this->isEditingReview = false;
        $this->resetReviewForm();
        
        session()->flash('status', 'Review updated successfully.');
        $this->loadProduct();
    }

    private function resetReviewForm(): void
    {
        $this->selectedReviewId = null;
        $this->reviewName = '';
        $this->reviewLocation = '';
        $this->reviewRating = 5;
        $this->reviewComments = '';
        $this->reviewApproved = false;
    }

    public function generateAiContent(): void
    {
        $this->resetErrorBag('ai_content_error');

        $apiKey = config('ai.openai_api_key');
        if (empty($apiKey) || !function_exists('ai_product_description_content')) {
            return;
        }

        $categoryNames = [];
        if (!empty($this->selectedCategories)) {
            $categoryNames = \App\Models\Category::whereIn('id', $this->selectedCategories)->pluck('name')->toArray();
        }

        $contextLines = [];
        $contextLines[] = "Product Title: " . ($this->title ?: 'N/A');
        $contextLines[] = "Categories: " . (!empty($categoryNames) ? implode(', ', $categoryNames) : 'N/A');
        $contextLines[] = "Short Description: " . ($this->short_description ?: 'N/A');
        $contextLines[] = "Current Long Description: " . ($this->long_description ?: 'N/A');

        $context = implode("\n", $contextLines);

        $res = ai_product_description_content($context, $this->aiPrompt);
        if (function_exists('wrap_prose_content')) {
            $res = wrap_prose_content($res);
        }
        $this->aiResponse = $res;
    }

    public function render(): View
    {
        $showAiButton = !empty(config('ai.openai_api_key')) && function_exists('ai_product_description_content');
        $categoryTree   = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands         = \App\Models\Brand::orderBy('name')->get();
        $displayPlugins = \App\Models\Plugin::active()->ofType('display')->orderBy('name', 'asc')->get();
        $inventoryAlerts = \App\Models\ProductInventoryAlert::active()->get();

        // Link Generator Drawer searches
        $searchedProducts = [];
        if (strlen($this->searchProduct) >= 2) {
            $searchedProducts = Product::where('title', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('seo_slug', 'like', '%' . $this->searchProduct . '%')
                ->limit(25)->get();
        }

        $searchedBrands = [];
        if (strlen($this->searchBrand) >= 2) {
            $searchedBrands = \App\Models\Brand::where('name', 'like', '%' . $this->searchBrand . '%')
                ->orWhere('slug', 'like', '%' . $this->searchBrand . '%')
                ->limit(25)->get();
        }

        $searchedCategories = [];
        if (strlen($this->searchCategory) >= 2) {
            $searchedCategories = \App\Models\Category::where('name', 'like', '%' . $this->searchCategory . '%')
                ->orWhere('slug', 'like', '%' . $this->searchCategory . '%')
                ->limit(25)->get();
        }

        $searchedPages = [];
        if (strlen($this->searchPage) >= 2) {
            $searchedPages = \App\Models\CmsPage::where('title', 'like', '%' . $this->searchPage . '%')
                ->orWhere('slug', 'like', '%' . $this->searchPage . '%')
                ->limit(25)->get();
        }

        // Shortcode Generator Drawer search
        $shortcodeSearchResults = [];
        if (!empty($this->shortcodeSearchQuery)) {
            $q = '%' . $this->shortcodeSearchQuery . '%';

            $pagesLimit      = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $productsLimit   = ($this->shortcodeSearchScope === 'all') ? 10 : 25;
            $categoriesLimit = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $brandsLimit     = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $downloadsLimit  = ($this->shortcodeSearchScope === 'all') ? 5 : 25;

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'pages') {
                $pages = \App\Models\CmsPage::where('title', 'like', $q)->limit($pagesLimit)->get();
                foreach ($pages as $p) {
                    $shortcodeSearchResults[] = [
                        'type'       => 'Page',
                        'id'         => $p->id,
                        'title'      => $p->title,
                        'badgeColor' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'shortcode'  => '[page:' . $p->id . ' label="' . e($p->title) . '"]',
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'products') {
                $productsList = Product::where('title', 'like', $q)->limit($productsLimit)->get();
                foreach ($productsList as $p) {
                    $shortcodeSearchResults[] = [
                        'type'       => 'Product',
                        'id'         => $p->id,
                        'title'      => $p->title,
                        'badgeColor' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'shortcode'  => '[product:' . $p->id . ' label="' . e($p->title) . '"]',
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'categories') {
                $categories = \App\Models\Category::where('name', 'like', $q)->limit($categoriesLimit)->get();
                foreach ($categories as $c) {
                    $shortcodeSearchResults[] = [
                        'type'       => 'Category',
                        'id'         => $c->id,
                        'title'      => $c->name,
                        'badgeColor' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'shortcode'  => '[category:' . $c->id . ' label="' . e($c->name) . '"]',
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'brands') {
                $brandsList = \App\Models\Brand::where('name', 'like', $q)->limit($brandsLimit)->get();
                foreach ($brandsList as $b) {
                    $shortcodeSearchResults[] = [
                        'type'       => 'Brand',
                        'id'         => $b->id,
                        'title'      => $b->name,
                        'badgeColor' => 'bg-violet-100 text-violet-800 border-violet-200',
                        'shortcode'  => '[brand:' . $b->id . ' label="' . e($b->name) . '"]',
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'downloads') {
                $downloads = \App\Models\CmsDownload::where('is_active', true)
                    ->where(function ($q2) use ($q) {
                        $q2->where('internal_name', 'like', $q)
                           ->orWhere('link_label', 'like', $q);
                    })
                    ->limit($downloadsLimit)->get();
                foreach ($downloads as $d) {
                    $label = $d->link_label ?: $d->internal_name;
                    $shortcodeSearchResults[] = [
                        'type'       => 'Download',
                        'id'         => $d->id,
                        'title'      => $d->internal_name,
                        'badgeColor' => 'bg-teal-100 text-teal-800 border-teal-200',
                        'shortcode'  => '[download:' . $d->uuid . ' label="' . e($label) . '"]',
                    ];
                }
            }

            if (count($shortcodeSearchResults) > 25) {
                $shortcodeSearchResults = array_slice($shortcodeSearchResults, 0, 25);
            }
        }

        return view('livewire.admin-product-edit', [
            'categoryTree'          => $categoryTree,
            'brands'                => $brands,
            'displayPlugins'        => $displayPlugins,
            'searchedProducts'      => $searchedProducts,
            'searchedBrands'        => $searchedBrands,
            'searchedCategories'    => $searchedCategories,
            'searchedPages'         => $searchedPages,
            'shortcodeSearchResults'=> $shortcodeSearchResults,
            'showAiButton'          => $showAiButton,
            'activeLanguages'       => \App\Models\Language::getAllActive()->where('is_default', false)->values(),
            'inventoryAlerts'       => $inventoryAlerts,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Cross-Selling
    // ─────────────────────────────────────────────────────────────

    /**
     * Fired whenever $crossSellSearch changes (debounced in blade via wire:model.live.debounce.400ms).
     * Returns up to 25 products, excluding the current product and any already-added cross-sell products.
     */
    public function updatedCrossSellSearch(string $value): void
    {
        $value = trim($value);
        if ($value === '') {
            $this->crossSellResults = [];
            $this->crossSellSearchActive = false;
            return;
        }

        $this->crossSellSearchActive = true;

        // IDs already added as cross-sells for this product
        $existingIds = $this->product->crossSells->pluck('cross_sell_product_id')->toArray();
        // Also exclude the product itself
        $existingIds[] = $this->productId;

        $this->crossSellResults = Product::where(function ($q) use ($value) {
                $q->where('title', 'like', '%' . $value . '%')
                  ->orWhere('id', $value);
            })
            ->whereNotIn('id', $existingIds)
            ->orderBy('title')
            ->limit(25)
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'title'     => $p->title,
                'thumbnail' => $p->primaryThumbnailUrl(),
            ])
            ->toArray();
    }

    /**
     * Add a product as a cross-sell. Enforces max 10 per product.
     */
    public function addCrossSell(int $productId): void
    {
        // Enforce max 10
        if ($this->product->crossSells->count() >= 10) {
            $this->addError('crossSell', 'A product can have a maximum of 10 cross-selling items.');
            return;
        }

        // Guard: don't add current product or duplicates
        $alreadyExists = $this->product->crossSells
            ->where('cross_sell_product_id', $productId)
            ->isNotEmpty();

        if ($alreadyExists || $productId === $this->productId) {
            return;
        }

        // Determine next sort order
        $nextSort = ($this->product->crossSells->max('sort_order') ?? 0) + 1;

        \App\Models\ProductCrossSell::create([
            'product_id'            => $this->productId,
            'cross_sell_product_id' => $productId,
            'sort_order'            => $nextSort,
            'display_on_item_view'  => true,
            'display_on_post_cart'  => false,
        ]);

        // Reset search
        $this->crossSellSearch = '';
        $this->crossSellResults = [];
        $this->crossSellSearchActive = false;

        session()->flash('status', 'Cross-sell product added.');
        $this->loadProduct();
    }

    /**
     * Remove a cross-sell entry by its ID.
     */
    public function removeCrossSell(int $crossSellId): void
    {
        \App\Models\ProductCrossSell::where('id', $crossSellId)
            ->where('product_id', $this->productId)
            ->delete();

        session()->flash('status', 'Cross-sell product removed.');
        $this->loadProduct();
    }

    /**
     * Toggle display_on_item_view for a cross-sell entry.
     */
    public function toggleCrossSellItemView(int $crossSellId): void
    {
        $entry = \App\Models\ProductCrossSell::where('id', $crossSellId)
            ->where('product_id', $this->productId)
            ->firstOrFail();
        $entry->update(['display_on_item_view' => !$entry->display_on_item_view]);
        $this->loadProduct();
    }

    /**
     * Toggle display_on_post_cart for a cross-sell entry.
     */
    public function toggleCrossSellPostCart(int $crossSellId): void
    {
        $entry = \App\Models\ProductCrossSell::where('id', $crossSellId)
            ->where('product_id', $this->productId)
            ->firstOrFail();
        $entry->update(['display_on_post_cart' => !$entry->display_on_post_cart]);
        $this->loadProduct();
    }

    /**
     * Update the sort_order of a cross-sell entry.
     */
    public function updateCrossSellOrder(int $crossSellId, float $order): void
    {
        \App\Models\ProductCrossSell::where('id', $crossSellId)
            ->where('product_id', $this->productId)
            ->update(['sort_order' => $order]);
        $this->loadProduct();
    }

    // ── Translation Management ─────────────────────────────────────────────────

    public function selectTranslationLang(string $code, int $langId): void
    {
        $this->activeLangCode = $code;
        $this->activeLangId = $langId;
        $this->loadProductTranslationData();
    }

    protected function loadProductTranslationData(): void
    {
        if (!isset($this->productId) || !$this->activeLangId) return;

        $trans = \App\Models\ProductTranslation::where('product_id', $this->productId)
            ->where('language_id', $this->activeLangId)
            ->first();

        $this->trans_title             = $trans?->title ?? '';
        $this->trans_short_description = $trans?->short_description ?? '';
        $this->trans_long_description  = $trans?->long_description ?? '';
        $this->trans_meta_title        = $trans?->meta_title ?? '';
        $this->trans_meta_description  = $trans?->meta_description ?? '';
        $this->trans_status            = $trans?->translation_status ?? 'pending';
        $this->trans_translated_at     = $trans?->translated_at?->format('M j, Y g:i A');
    }

    public function saveProductTranslation(): void
    {
        if (!isset($this->productId) || !$this->activeLangId) return;

        \App\Models\ProductTranslation::updateOrCreate(
            ['product_id' => $this->productId, 'language_id' => $this->activeLangId],
            [
                'title'             => $this->trans_title ?: null,
                'short_description' => $this->trans_short_description ?: null,
                'long_description'  => $this->trans_long_description ?: null,
                'meta_title'        => $this->trans_meta_title ?: null,
                'meta_description'  => $this->trans_meta_description ?: null,
                'translation_status'=> 'reviewed',
                'translated_at'     => now(),
            ]
        );

        $this->trans_status        = 'reviewed';
        $this->trans_translated_at = now()->format('M j, Y g:i A');
        session()->flash('success', 'Translation saved.');
    }

    public function autoTranslateProduct(): void
    {
        if (!isset($this->productId) || !$this->activeLangId) return;

        \App\Jobs\TranslateContentJob::dispatch(
            \App\Models\Product::class,
            $this->productId,
            $this->activeLangId
        );

        session()->flash('success', 'Translation job queued. Refresh in a moment to see the results.');
    }

    /**
     * Inline AI translation — calls OpenAI synchronously and pre-fills all
     * product translation fields so the admin can review before saving.
     * The existing autoTranslateProduct() bulk queue method is unchanged.
     */
    public function aiTranslateProductInline(): void
    {
        if (!isset($this->productId) || !$this->activeLangId) return;

        $product = \App\Models\Product::find($this->productId);
        $lang    = \App\Models\Language::find($this->activeLangId);

        if (!$product || !$lang) return;

        try {
            $svc      = app(\App\Services\TranslationService::class);
            $langName = $lang->name;

            if (!empty($product->title)) {
                $this->trans_title = $svc->translateText($product->title, $langName, 'product name / title');
            }
            if (!empty($product->short_description)) {
                $this->trans_short_description = $svc->translateText($product->short_description, $langName, 'product short description');
            }
            if (!empty($product->long_description)) {
                $this->trans_long_description = $svc->translateText($product->long_description, $langName, 'product long description HTML — preserve HTML tags');
            }
            if (!empty($product->meta_title)) {
                $this->trans_meta_title = $svc->translateText($product->meta_title, $langName, 'SEO meta title');
            }
            if (!empty($product->meta_description)) {
                $this->trans_meta_description = $svc->translateText($product->meta_description, $langName, 'SEO meta description');
            }

            $this->trans_status = 'ai_translated';
            $this->dispatch('toast', message: 'AI translation ready — review all fields and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    // ── Variant Personalization Translation ───────────────────────────────────

    /**
     * Select a language for editing variant personalization label translations.
     * Loads any existing translation into the trans_personalization_* properties.
     */
    public function selectVariantTranslationLang(string $code, int $langId): void
    {
        $this->variantTransLangCode = $code;
        $this->variantTransLangId   = $langId;

        if ($code && $langId && $this->selectedVariantId) {
            $trans = \App\Models\ProductVariantTranslation::where('product_variant_id', $this->selectedVariantId)
                ->where('language_id', $langId)
                ->first();

            $this->trans_personalization_label         = $trans?->personalization_label ?? '';
            $this->trans_personalization_details_label = $trans?->personalization_details_label ?? '';
            $this->trans_personalization_placeholder   = $trans?->personalization_placeholder ?? '';

            // Load existing attribute label translations (flat raw→translated map).
            $this->trans_attributes = $trans?->attributes_translated ?? [];

            // Pre-populate with raw attribute keys/values so every field is visible
            // in the UI even before any translation has been saved.
            $variant = \App\Models\ProductVariant::find($this->selectedVariantId);
            if ($variant) {
                $rawAttrs = json_decode($variant->attributes ?? '{}', true) ?: [];
                foreach ($rawAttrs as $rawKey => $rawVal) {
                    $this->trans_attributes[$rawKey] ??= '';
                    $this->trans_attributes[$rawVal] ??= '';
                }
            }
        } else {
            $this->trans_personalization_label         = '';
            $this->trans_personalization_details_label = '';
            $this->trans_personalization_placeholder   = '';
            $this->trans_attributes                    = [];
        }
    }

    /**
     * Persist the variant personalization label translations for the selected language.
     */
    public function saveVariantTranslation(): void
    {
        if (!$this->variantTransLangId || !$this->selectedVariantId) return;

        // Strip blank entries from the attributes map before persisting —
        // keeps the JSON lean and makes isEmpty checks reliable.
        $attrsToSave = array_filter(
            $this->trans_attributes,
            fn($v) => is_string($v) && trim($v) !== ''
        );

        \App\Models\ProductVariantTranslation::updateOrCreate(
            [
                'product_variant_id' => $this->selectedVariantId,
                'language_id'        => $this->variantTransLangId,
            ],
            [
                'personalization_label'         => $this->trans_personalization_label ?: null,
                'personalization_details_label' => $this->trans_personalization_details_label ?: null,
                'personalization_placeholder'   => $this->trans_personalization_placeholder ?: null,
                'attributes_translated'         => !empty($attrsToSave) ? $attrsToSave : null,
            ]
        );

        $this->dispatch('toast', type: 'success', message: 'Variant translation saved.');
    }

    // ── Field & Option Translation ─────────────────────────────────────────────

    /**
     * Select a language for editing field label / option value translations.
     * Loads any existing translations into trans_field_label and trans_field_options.
     */
    public function selectFieldTranslationLang(string $code, int $langId): void
    {
        $this->fieldTransLangCode = $code;
        $this->fieldTransLangId   = $langId;

        if ($code && $langId && $this->selectedFieldId) {
            $trans = \App\Models\ProductFieldTranslation::where('product_field_id', $this->selectedFieldId)
                ->where('language_id', $langId)
                ->first();

            $this->trans_field_label   = $trans?->label ?? '';
            $this->trans_field_options = [];

            foreach ($this->fieldOptions as $opt) {
                if (!empty($opt['id'])) {
                    $optTrans = \App\Models\ProductFieldOptionTranslation::where('product_field_option_id', $opt['id'])
                        ->where('language_id', $langId)
                        ->first();
                    $this->trans_field_options[$opt['id']] = $optTrans?->option_value ?? '';
                }
            }
        } else {
            $this->trans_field_label   = '';
            $this->trans_field_options = [];
        }
    }

    /**
     * Persist the field label and all option value translations for the selected language.
     */
    public function saveFieldTranslation(): void
    {
        if (!$this->fieldTransLangId || !$this->selectedFieldId) return;

        \App\Models\ProductFieldTranslation::updateOrCreate(
            [
                'product_field_id' => $this->selectedFieldId,
                'language_id'      => $this->fieldTransLangId,
            ],
            ['label' => $this->trans_field_label ?: null]
        );

        foreach ($this->trans_field_options as $optionId => $translatedValue) {
            \App\Models\ProductFieldOptionTranslation::updateOrCreate(
                [
                    'product_field_option_id' => $optionId,
                    'language_id'             => $this->fieldTransLangId,
                ],
                ['option_value' => $translatedValue ?: null]
            );
        }

        $this->dispatch('toast', type: 'success', message: 'Field translation saved.');
    }
}
