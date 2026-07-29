<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductInventory;
use App\Models\ProductCrossSell;
use App\Models\ProductField;
use App\Models\ProductFieldOption;
use App\Models\ProductImage;
use App\Models\ProductQuantityDiscount;
use App\Models\ProductVariantEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminProducts extends Component
{
    use WithPagination;

    public string $search = '';
    public array $expandedProducts = [];

    // Advanced Filters
    public bool $showAdvancedFilters = false;
    public ?int $filterBrandId = null;
    public array $filterCategoryIds = [];
    public string $filterPriceMin = '';
    public string $filterPriceMax = '';
    public string $filterProductType = '';   // '', 'download', 'shippable', 'event', 'featured'
    public string $filterStockStatus = '';   // '', 'in_stock', 'out_of_stock'
    public string $filterSortBy = 'newest';  // newest, oldest, alpha_asc, alpha_desc, price_asc, price_desc
    public string $filterAttribute = '';     // free-text attribute key search
    public string $filterAttributeValue = ''; // optional value

    // Product Form
    public string $title = '';
    public string $short_description = '';
    public string $long_description = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $seo_slug = '';
    public int $product_download_item = 0;
    public int $product_shipping = 1;
    public array $selectedCategories = [];
    public ?int $brand_id = null;

    // AI Content Generator
    public string $aiPrompt = '';
    public string $aiResponse = '';

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

        $this->aiResponse = ai_product_description_content($context, $this->aiPrompt);
    }

    public function updatedTitle(string $value): void
    {
        $this->seo_slug = Str::slug($value);
    }

    public bool $isCreatingProduct = false;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
    }

    public function toggleCreateProduct(): void
    {
        $this->isCreatingProduct = !$this->isCreatingProduct;
        $this->resetProductForm();
    }

    private function resetProductForm(): void
    {
        $this->title = '';
        $this->short_description = '';
        $this->long_description = '';
        $this->meta_title = '';
        $this->meta_description = '';
        $this->seo_slug = '';
        $this->product_download_item = 0;
        $this->product_shipping = 1;
        $this->selectedCategories = [];
        $this->brand_id = null;
        $this->aiPrompt = '';
        $this->aiResponse = '';
    }

    public function saveProduct(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_slug' => 'required|string|max:255|unique:products,seo_slug',
            'brand_id' => 'nullable|integer|exists:product_brands,id',
        ]);

        $product = Product::create([
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'meta_title' => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: $this->short_description,
            'seo_slug' => $this->seo_slug,
            'download_item' => $this->product_download_item,
            'shipping' => $this->product_shipping,
            'brand_id' => $this->brand_id,
        ]);

        $product->categories()->sync($this->selectedCategories);

        $this->isCreatingProduct = false;
        $this->resetProductForm();
        session()->flash('status', 'Product created successfully.');
    }



    // Copy / Duplicate Product Form Properties
    public bool $showCopyModal = false;
    public ?int $copyProductId = null;
    public string $copyOriginalTitle = '';
    public string $copyProductTitle = '';
    public string $copyProductSlug = '';
    public bool $copyVariantsAndImages = true;

    public function openCopyModal(int $productId): void
    {
        $product = Product::findOrFail($productId);

        $randomCode = Str::upper(Str::random(4));
        $this->copyProductId = $product->id;
        $this->copyOriginalTitle = $product->title;
        $this->copyProductTitle = $product->title . ' - Copy ' . $randomCode;

        $baseSlug = Str::slug($product->seo_slug ?: $product->title);
        $candidateSlug = $baseSlug . '-copy-' . strtolower($randomCode);
        while (Product::where('seo_slug', $candidateSlug)->exists()) {
            $randomCode = Str::upper(Str::random(4));
            $candidateSlug = $baseSlug . '-copy-' . strtolower($randomCode);
        }
        $this->copyProductSlug = $candidateSlug;
        $this->copyVariantsAndImages = true;
        $this->showCopyModal = true;
    }

    public function closeCopyModal(): void
    {
        $this->showCopyModal = false;
        $this->copyProductId = null;
        $this->copyOriginalTitle = '';
        $this->copyProductTitle = '';
        $this->copyProductSlug = '';
        $this->copyVariantsAndImages = true;
    }

    public function updatedCopyProductTitle(string $value): void
    {
        if (!empty($value)) {
            $this->copyProductSlug = Str::slug($value);
        }
    }

    public function duplicateProduct(): void
    {
        $this->validate([
            'copyProductTitle' => 'required|string|max:255',
            'copyProductSlug'  => 'required|string|max:255|unique:products,seo_slug',
        ]);

        if (!$this->copyProductId) {
            return;
        }

        $original = Product::with([
            'variants.inventory',
            'variants.images',
            'variants.eventDetails',
            'fields.options',
            'categories',
            'crossSells',
        ])->findOrFail($this->copyProductId);

        DB::transaction(function () use ($original, &$newProduct) {
            // 1. Duplicate Base Product
            $newProduct = Product::create([
                'title'                       => $this->copyProductTitle,
                'short_description'           => $original->short_description,
                'long_description'            => $original->long_description,
                'meta_title'                  => $original->meta_title,
                'meta_description'            => $original->meta_description,
                'seo_slug'                    => $this->copyProductSlug,
                'download_item'               => $original->download_item,
                'shipping'                    => $original->shipping,
                'brand_id'                    => $original->brand_id,
                'max_qty'                     => $original->max_qty,
                'checkout_redirect'           => $original->checkout_redirect,
                'standalone_purchase'         => $original->standalone_purchase,
                'dependent_variants'          => $original->dependent_variants,
                'hide_inventory_levels'       => $original->hide_inventory_levels,
                'layout_type'                 => $original->layout_type,
                'reviews_enabled'             => $original->reviews_enabled,
                'reviews_rating'              => $original->reviews_rating,
                'featured_item'               => $original->featured_item,
                'product_search_index_locked' => $original->product_search_index_locked,
            ]);

            // 2. Sync Categories
            if ($original->categories->isNotEmpty()) {
                $newProduct->categories()->sync($original->categories->pluck('id')->toArray());
            }

            // 3. Duplicate Custom Fields & Options
            foreach ($original->fields as $field) {
                $newField = ProductField::create([
                    'product_id' => $newProduct->id,
                    'label'      => $field->label,
                    'field_type' => $field->field_type,
                    'is_required'=> $field->is_required,
                    'charge_tax' => $field->charge_tax,
                    'sort_order' => $field->sort_order,
                ]);

                foreach ($field->options as $opt) {
                    ProductFieldOption::create([
                        'product_field_id'               => $newField->id,
                        'option_value'                   => $opt->option_value,
                        'option_price_modifier'           => $opt->option_price_modifier,
                        'option_wholesale_price_modifier' => $opt->option_wholesale_price_modifier,
                        'sort_order'                     => $opt->sort_order,
                    ]);
                }
            }

            // 4. Duplicate Cross-sells
            foreach ($original->crossSells as $cs) {
                ProductCrossSell::create([
                    'product_id'            => $newProduct->id,
                    'cross_sell_product_id' => $cs->cross_sell_product_id,
                    'sort_order'            => $cs->sort_order,
                    'display_on_item_view'  => $cs->display_on_item_view,
                    'display_on_post_cart'   => $cs->display_on_post_cart,
                ]);
            }

            // 5. Duplicate Variants, Inventory & Images if selected
            if ($this->copyVariantsAndImages && $original->variants->isNotEmpty()) {
                foreach ($original->variants as $var) {
                    $randomSkuCode = Str::upper(Str::random(4));
                    $newSku = $var->sku . '-COPY-' . $randomSkuCode;
                    while (ProductVariant::where('sku', $newSku)->exists()) {
                        $randomSkuCode = Str::upper(Str::random(4));
                        $newSku = $var->sku . '-COPY-' . $randomSkuCode;
                    }

                    $newVar = ProductVariant::create([
                        'product_id'                    => $newProduct->id,
                        'sku'                           => $newSku,
                        'public_price'                  => $var->public_price,
                        'wholesale_price'               => $var->wholesale_price,
                        'on_sale'                       => $var->on_sale,
                        'sale_price'                    => $var->sale_price,
                        'variant_fee'                   => $var->variant_fee,
                        'wholesale_variant_fee'         => $var->wholesale_variant_fee,
                        'personalization_active'        => $var->personalization_active,
                        'personalization_fee'           => $var->personalization_fee,
                        'personalization_label'         => $var->personalization_label,
                        'personalization_details_label' => $var->personalization_details_label,
                        'personalization_placeholder'   => $var->personalization_placeholder,
                        'shipping'                      => $var->shipping,
                        'charge_tax'                    => $var->charge_tax ?? 1,
                        'weight'                        => $var->weight,
                        'weight_type'                   => $var->weight_type,
                        'attributes'                    => $var->attributes,
                        'download_item'                 => $var->download_item,
                        'download_location'             => $var->download_location,
                        'direct_download_url'           => $var->direct_download_url,
                        'download_label'                => $var->download_label,
                        'download_expiration'           => $var->download_expiration,
                        'downloads_max_allowed'         => $var->downloads_max_allowed,
                        'download_s3'                   => $var->download_s3,
                        'download_s3_region'            => $var->download_s3_region,
                        'download_s3_bucket_name'       => $var->download_s3_bucket_name,
                        'download_s3_access_key_id'     => $var->download_s3_access_key_id,
                        'download_s3_secret_access_key' => $var->download_s3_secret_access_key,
                        'download_cdn_url'              => $var->download_cdn_url,
                        'paddle_sandbox_price_id'       => $var->paddle_sandbox_price_id,
                        'paddle_live_price_id'          => $var->paddle_live_price_id,
                        'paddle_price'                  => $var->paddle_price,
                        'paddle_interval'               => $var->paddle_interval,
                        'paddle_frequency'              => $var->paddle_frequency,
                        'paddle_currency_code'          => $var->paddle_currency_code,
                        'stripe_sandbox_price_id'       => $var->stripe_sandbox_price_id,
                        'stripe_live_price_id'          => $var->stripe_live_price_id,
                        'create_new_stripe_product'     => $var->create_new_stripe_product,
                        'stripe_billing_interval'       => $var->stripe_billing_interval,
                        'stripe_trial_enabled'          => $var->stripe_trial_enabled,
                        'stripe_trial_days'             => $var->stripe_trial_days,
                        'is_event'                      => $var->is_event,
                    ]);

                    // Inventory
                    if ($var->inventory) {
                        ProductInventory::create([
                            'variant_id'            => $newVar->id,
                            'quantity_available'    => $var->inventory->quantity_available,
                            'warehouse_stock_level' => $var->inventory->warehouse_stock_level,
                            'use_warehouse_stock'   => $var->inventory->use_warehouse_stock,
                            'reserved_stock'        => $var->inventory->reserved_stock,
                            'location_id'           => $var->inventory->location_id ?? 1,
                        ]);
                    } else {
                        ProductInventory::create([
                            'variant_id'            => $newVar->id,
                            'quantity_available'    => 0,
                            'warehouse_stock_level' => 0,
                            'use_warehouse_stock'   => false,
                            'reserved_stock'        => 0,
                            'location_id'           => 1,
                        ]);
                    }

                    // Images
                    foreach ($var->images as $img) {
                        ProductImage::create([
                            'variant_id'                 => $newVar->id,
                            'thumbnail_path'             => $img->thumbnail_path,
                            'main_path'                  => $img->main_path,
                            'zoom_path'                  => $img->zoom_path,
                            'image_alt'                  => $img->image_alt,
                            'zoom_label'                 => $img->zoom_label,
                            'image_s3'                   => $img->image_s3,
                            'image_url_source'           => $img->image_url_source,
                            'cdn_url'                    => $img->cdn_url,
                            'search_image'               => $img->search_image,
                            'active'                     => $img->active,
                            'image_s3_region'            => $img->image_s3_region,
                            'image_s3_bucket_name'       => $img->image_s3_bucket_name,
                            'image_s3_access_key_id'     => $img->image_s3_access_key_id,
                            'image_s3_secret_access_key' => $img->image_s3_secret_access_key,
                        ]);
                    }

                    // Quantity discount breaks
                    $discounts = ProductQuantityDiscount::where('product_variant_id', $var->id)->get();
                    foreach ($discounts as $d) {
                        ProductQuantityDiscount::create([
                            'product_variant_id' => $newVar->id,
                            'qty_min'            => $d->qty_min,
                            'qty_max'            => $d->qty_max,
                            'discount_value'     => $d->discount_value,
                            'value_type'         => $d->value_type,
                        ]);
                    }

                    // Event Details if present
                    if ($var->is_event && $var->eventDetails) {
                        $evt = $var->eventDetails;
                        ProductVariantEvent::create([
                            'variant_id'        => $newVar->id,
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
                }
            } else {
                // If not copying variants, create a single default base variant for the product
                $baseSku = Str::upper(Str::slug($this->copyProductTitle) . '-DEF-' . Str::random(4));
                $newVar = ProductVariant::create([
                    'product_id'   => $newProduct->id,
                    'sku'          => $baseSku,
                    'public_price' => 0.00,
                    'wholesale_price' => 0.00,
                    'shipping'     => $original->shipping,
                    'charge_tax'   => 1,
                ]);
                ProductInventory::create([
                    'variant_id'         => $newVar->id,
                    'quantity_available' => 0,
                    'warehouse_stock_level' => 0,
                    'use_warehouse_stock' => false,
                    'reserved_stock'     => 0,
                    'location_id'        => 1,
                ]);
            }
        });

        $this->closeCopyModal();
        session()->flash('status', "Product '{$newProduct->title}' duplicated successfully.");
    }

    public function deleteProduct(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $product->delete();
        session()->flash('status', 'Product deleted successfully.');
    }

    public function toggleProductExpand(int $productId): void
    {
        if (in_array($productId, $this->expandedProducts)) {
            $this->expandedProducts = array_values(array_diff($this->expandedProducts, [$productId]));
        } else {
            $this->expandedProducts[] = $productId;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterBrandId(): void      { $this->resetPage(); }
    public function updatingFilterCategoryIds(): void  { $this->resetPage(); }
    public function updatingFilterPriceMin(): void     { $this->resetPage(); }
    public function updatingFilterPriceMax(): void     { $this->resetPage(); }
    public function updatingFilterProductType(): void  { $this->resetPage(); }
    public function updatingFilterStockStatus(): void  { $this->resetPage(); }
    public function updatingFilterSortBy(): void       { $this->resetPage(); }
    public function updatingFilterAttribute(): void    { $this->resetPage(); }
    public function updatingFilterAttributeValue(): void { $this->resetPage(); }

    public function toggleAdvancedFilters(): void
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    public function resetFilters(): void
    {
        $this->search              = '';
        $this->filterBrandId       = null;
        $this->filterCategoryIds   = [];
        $this->filterPriceMin      = '';
        $this->filterPriceMax      = '';
        $this->filterProductType   = '';
        $this->filterStockStatus   = '';
        $this->filterSortBy        = 'newest';
        $this->filterAttribute     = '';
        $this->filterAttributeValue = '';
        $this->resetPage();
    }

    public function getActiveFilterCountProperty(): int
    {
        $count = 0;
        if ($this->filterBrandId)        $count++;
        if (!empty($this->filterCategoryIds)) $count++;
        if ($this->filterPriceMin !== '') $count++;
        if ($this->filterPriceMax !== '') $count++;
        if ($this->filterProductType)    $count++;
        if ($this->filterStockStatus)    $count++;
        if ($this->filterAttribute)      $count++;
        return $count;
    }

    public function render(): View
    {
        $query = Product::with(['variants.images', 'brand', 'categories']);

        // Keyword search
        if ($this->search) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('short_description', 'like', "%{$s}%")
                  ->orWhere('long_description', 'like', "%{$s}%")
                  ->orWhere('seo_slug', 'like', "%{$s}%")
                  ->orWhereHas('variants', fn($vq) => $vq->where('sku', 'like', "%{$s}%"));
            });
        }

        // Brand filter
        if ($this->filterBrandId) {
            $query->where('brand_id', $this->filterBrandId);
        }

        // Category filter (multi-select)
        if (!empty($this->filterCategoryIds)) {
            $catIds = $this->filterCategoryIds;
            $query->whereHas('categories', fn($q) => $q->whereIn('product_categories_assignments.category_id', $catIds));
        }

        // Price range filter (against variant public_price)
        if ($this->filterPriceMin !== '') {
            $min = (float) $this->filterPriceMin;
            $query->whereHas('variants', fn($q) => $q->where('public_price', '>=', $min));
        }
        if ($this->filterPriceMax !== '') {
            $max = (float) $this->filterPriceMax;
            $query->whereHas('variants', fn($q) => $q->where('public_price', '<=', $max));
        }

        // Product type filter
        match ($this->filterProductType) {
            'download'  => $query->whereHas('variants', fn($q) => $q->where('download_item', 1)),
            'shippable' => $query->whereHas('variants', fn($q) => $q->where('shipping', 1)->where('download_item', 0)),
            'event'     => $query->whereHas('variants.eventDetails'),
            'featured'  => $query->where('featured_item', 1),
            default     => null,
        };

        // Stock status filter
        if ($this->filterStockStatus === 'in_stock') {
            $query->whereHas('variants.inventory', fn($q) => $q->where('quantity_available', '>', 0));
        } elseif ($this->filterStockStatus === 'out_of_stock') {
            $query->whereDoesntHave('variants.inventory', fn($q) => $q->where('quantity_available', '>', 0));
        }

        // Attribute key/value filter (searches JSON attributes column on variants)
        if ($this->filterAttribute) {
            $attrKey = $this->filterAttribute;
            $attrVal = $this->filterAttributeValue;
            $query->whereHas('variants', function ($q) use ($attrKey, $attrVal) {
                $q->where('attributes', 'like', '%"' . $attrKey . '"%');
                if ($attrVal) {
                    $q->where('attributes', 'like', '%"' . $attrVal . '"%');
                }
            });
        }

        // Sorting
        match ($this->filterSortBy) {
            'oldest'     => $query->oldest(),
            'alpha_asc'  => $query->orderBy('title', 'asc'),
            'alpha_desc' => $query->orderBy('title', 'desc'),
            'price_asc'  => $query->orderByRaw('(SELECT MIN(public_price) FROM product_variants WHERE product_id = products.id) ASC'),
            'price_desc' => $query->orderByRaw('(SELECT MAX(public_price) FROM product_variants WHERE product_id = products.id) DESC'),
            default      => $query->latest(),
        };

        $products = $query->paginate(25);

        $recentlyEdited = Product::with(['variants.images'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $categoryTree = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();

        $showAiButton = !empty(config('ai.openai_api_key')) && function_exists('ai_product_description_content');

        return view('livewire.admin-products', [
            'products'          => $products,
            'expandedProducts'  => $this->expandedProducts,
            'categoryTree'      => $categoryTree,
            'brands'            => $brands,
            'recentlyEdited'    => $recentlyEdited,
            'showAiButton'      => $showAiButton,
            'activeFilterCount' => $this->getActiveFilterCountProperty(),
        ]);
    }
}
