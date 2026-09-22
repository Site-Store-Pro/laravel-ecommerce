<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class ShopCatalog extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $brand = null;

    #[Url]
    public $perPage = 16;

    #[Url]
    public string $sort = 'price_asc';

    // Advanced Search Filtering state
    #[Url]
    public array $selectedBrands = [];

    #[Url]
    public array $selectedCategories = [];

    #[Url]
    public ?float $minPriceFilter = null;

    #[Url]
    public ?float $maxPriceFilter = null;

    #[Url]
    public array $selectedAttributes = [];

    public bool $slideoutOpen = false;
    public ?string $catalogError = null;

    // 'grid' or 'list' — display preference, not URL-backed
    public string $viewMode = 'grid';

    // Deferred loading state for instant page switching with skeleton/spinner feedback
    public bool $readyToLoad = false;

    public function loadProducts(): void
    {
        $this->readyToLoad = true;
    }

    public function sanitizePerPage(): void
    {
        $allowed = [4, 8, 16, 20, 24, 30, 48, 64, 96];
        $val = filter_var($this->perPage, FILTER_VALIDATE_INT);
        if ($val === false || !in_array($val, $allowed, true)) {
            $this->perPage = 16;
        } else {
            $this->perPage = $val;
        }
    }

    public function sanitizeSort(): void
    {
        $allowed = ['price_asc', 'price_desc', 'title_asc', 'title_desc', 'rating_desc', 'rating_asc'];
        if (!in_array($this->sort, $allowed, true)) {
            $this->sort = 'price_asc';
        }
    }

    public function hydrate(): void
    {
        $this->sanitizePerPage();
        $this->sanitizeSort();
        $this->normalizeArrayFilters();
        $this->syncActivePreselection();
    }

    /**
     * Livewire 3 #[Url] hydration can resolve a missing/empty array query param
     * to (bool) false, which PHP 8.1 typed properties reject with a fatal error.
     * This normaliser coerces any non-array value back to [] before it touches
     * the typed properties, and also strips stray boolean entries that checkboxes
     * can inject when they are all unchecked simultaneously.
     */
    private function normalizeArrayFilters(): void
    {
        if (!is_array($this->selectedBrands)) {
            $this->selectedBrands = [];
        } else {
            $this->selectedBrands = array_values(array_filter(
                $this->selectedBrands,
                fn($v) => !is_bool($v) && $v !== '' && $v !== null
            ));
        }

        if (!is_array($this->selectedCategories)) {
            $this->selectedCategories = [];
        } else {
            $this->selectedCategories = array_values(array_filter(
                $this->selectedCategories,
                fn($v) => !is_bool($v) && $v !== '' && $v !== null
            ));
        }

        if (!is_array($this->selectedAttributes)) {
            $this->selectedAttributes = [];
        }
    }

    public function mount(?string $category_slug = null, ?string $brand_slug = null): void
    {
        if (app()->runningUnitTests()) {
            $this->readyToLoad = true;
        }

        // Restore viewMode from session or cookie
        $savedMode = session('catalog_view_mode', request()->cookie('catalog_view_mode', 'grid'));
        if (in_array($savedMode, ['grid', 'list'], true)) {
            $this->viewMode = $savedMode;
        }

        $this->sanitizePerPage();
        $this->sanitizeSort();
        $this->normalizeArrayFilters();
        if ($category_slug) {
            $this->category = $category_slug;
        }
        if ($brand_slug) {
            $this->brand = $brand_slug;
        }

        $this->syncActivePreselection();

        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing')) {
            $hasFilter = !empty($this->category) || !empty($this->brand) || !empty(trim(request()->query('search', '')));
            if (!$hasFilter) {
                $this->redirect('/', navigate: true);
                return;
            }
        }
    }

    public function setViewMode(string $mode): void
    {
        if (in_array($mode, ['grid', 'list'], true)) {
            $this->viewMode = $mode;
            session(['catalog_view_mode' => $mode]);
            cookie()->queue(cookie('catalog_view_mode', $mode, 60 * 24 * 30));
        }
    }

    public function updatedViewMode(string $value): void
    {
        if (in_array($value, ['grid', 'list'], true)) {
            session(['catalog_view_mode' => $value]);
            cookie()->queue(cookie('catalog_view_mode', $value, 60 * 24 * 30));
        }
    }

    private function syncActivePreselection(): void
    {
        if ($this->category) {
            $catModel = Category::where('slug', $this->category)->first();
            if ($catModel && !in_array((string)$catModel->id, array_map('strval', $this->selectedCategories), true)) {
                $this->selectedCategories[] = (string) $catModel->id;
            }
        }
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel && !in_array((string)$brandModel->id, array_map('strval', $this->selectedBrands), true)) {
                $this->selectedBrands[] = (string) $brandModel->id;
            }
        }
    }

    public function updatedSort(): void { $this->resetPage(); }

    public function updatedSelectedBrands(): void
    {
        // Normalize first — unchecking all boxes can deliver false instead of []
        $this->normalizeArrayFilters();
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel && !in_array((string)$brandModel->id, array_map('strval', $this->selectedBrands), true)) {
                $this->brand = null;
            }
        }
        $this->resetPage();
    }

    public function updatedSelectedCategories(): void
    {
        // Normalize first — unchecking all boxes can deliver false instead of []
        $this->normalizeArrayFilters();
        if ($this->category) {
            $catModel = Category::where('slug', $this->category)->first();
            if ($catModel && !in_array((string)$catModel->id, array_map('strval', $this->selectedCategories), true)) {
                $this->category = null;
            }
        }
        $this->resetPage();
    }

    public function updatedMinPriceFilter(): void { $this->resetPage(); }
    public function updatedMaxPriceFilter(): void { $this->resetPage(); }
    public function updatedSelectedAttributes(): void
    {
        if (is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $key => $val) {
                if (is_bool($val)) {
                    unset($this->selectedAttributes[$key]);
                } elseif (is_array($val)) {
                    $filtered = array_values(array_filter($val, function ($item) {
                        return !is_bool($item) && is_string($item) && trim($item) !== '';
                    }));
                    if (empty($filtered)) {
                        unset($this->selectedAttributes[$key]);
                    } else {
                        $this->selectedAttributes[$key] = array_values(array_unique($filtered));
                    }
                } elseif (is_string($val) && trim($val) !== '') {
                    $this->selectedAttributes[$key] = [trim($val)];
                } else {
                    unset($this->selectedAttributes[$key]);
                }
            }
        } else {
            $this->selectedAttributes = [];
        }
        $this->resetPage();
    }

    public function getHasActiveFiltersProperty(): bool
    {
        $hasSelectedAttrs = false;
        if (is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $vals) {
                if (is_array($vals) && !empty($vals)) {
                    $filtered = array_filter($vals, fn($v) => !is_bool($v) && is_string($v) && trim($v) !== '');
                    if (!empty($filtered)) {
                        $hasSelectedAttrs = true;
                        break;
                    }
                } elseif (!is_bool($vals) && is_string($vals) && trim($vals) !== '') {
                    $hasSelectedAttrs = true;
                    break;
                }
            }
        }

        return !empty($this->category)
            || !empty($this->brand)
            || !empty(trim($this->search))
            || !empty($this->selectedBrands)
            || !empty($this->selectedCategories)
            || $hasSelectedAttrs
            || $this->minPriceFilter !== null
            || $this->maxPriceFilter !== null;
    }

    public function getCanonicalUrlProperty(): string
    {
        // 1. If currently on dedicated category route: /section/{category_slug}
        if (request()->routeIs('shop.category') && !empty($this->category)) {
            return url('/section/' . ltrim($this->category, '/'));
        }

        // 2. If currently on dedicated brand route: /brands/{brand_slug}
        if (request()->routeIs('shop.brand') && !empty($this->brand)) {
            return url('/brands/' . ltrim($this->brand, '/'));
        }

        $hasCategory = !empty($this->category);
        $hasBrand = !empty($this->brand);
        $hasSearch = !empty(trim($this->search));
        $hasMinPrice = $this->minPriceFilter !== null;
        $hasMaxPrice = $this->maxPriceFilter !== null;

        $hasSelectedAttrs = false;
        if (is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $vals) {
                if (is_array($vals) && !empty($vals)) {
                    $filtered = array_filter($vals, fn($v) => !is_bool($v) && is_string($v) && trim($v) !== '');
                    if (!empty($filtered)) {
                        $hasSelectedAttrs = true;
                        break;
                    }
                } elseif (!is_bool($vals) && is_string($vals) && trim($vals) !== '') {
                    $hasSelectedAttrs = true;
                    break;
                }
            }
        }

        $extraCategories = false;
        if (!empty($this->selectedCategories)) {
            if ($hasCategory) {
                $catModel = \App\Models\Category::where('slug', $this->category)->first();
                $catId = $catModel ? (string) $catModel->id : null;
                $filteredCats = array_filter($this->selectedCategories, fn($id) => (string)$id !== $catId);
                $extraCategories = !empty($filteredCats);
            } else {
                $extraCategories = true;
            }
        }

        $extraBrands = false;
        if (!empty($this->selectedBrands)) {
            if ($hasBrand) {
                $brandModel = \App\Models\Brand::where('slug', $this->brand)->first();
                $brandId = $brandModel ? (string) $brandModel->id : null;
                $filteredBrands = array_filter($this->selectedBrands, fn($id) => (string)$id !== $brandId);
                $extraBrands = !empty($filteredBrands);
            } else {
                $extraBrands = true;
            }
        }

        // 3. If only category query is active on /shop -> canonical points to /section/slug
        if ($hasCategory && !$hasBrand && !$hasSearch && !$hasMinPrice && !$hasMaxPrice && !$hasSelectedAttrs && !$extraCategories && !$extraBrands) {
            return url('/section/' . ltrim($this->category, '/'));
        }

        // 4. If only brand query is active on /shop -> canonical points to /brands/slug
        if ($hasBrand && !$hasCategory && !$hasSearch && !$hasMinPrice && !$hasMaxPrice && !$hasSelectedAttrs && !$extraCategories && !$extraBrands) {
            return url('/brands/' . ltrim($this->brand, '/'));
        }

        // 5. Default catalog canonical
        return url('/shop');
    }

    public function resetAllAdvancedFilters(): mixed
    {
        $this->category = null;
        $this->brand = null;
        $this->search = '';
        $this->selectedBrands = [];
        $this->selectedCategories = [];
        $this->selectedAttributes = [];
        $this->minPriceFilter = null;
        $this->maxPriceFilter = null;
        $this->resetPage();

        if (request()->routeIs('shop.category') || request()->routeIs('shop.brand')) {
            if (\App\Models\CmsSetting::isEnabled('disable_shop_landing')) {
                return $this->redirect('/');
            }
            return $this->redirectRoute('shop.index');
        }

        return null;
    }

    public function setCategory(?string $slug): mixed
    {
        if (empty($slug)) {
            return $this->clearCategory();
        }

        $newCat = Category::where('slug', $slug)->first();
        if (!$newCat) {
            return $this->clearCategory();
        }

        if ($this->category) {
            $prevCat = Category::where('slug', $this->category)->first();
            if ($prevCat) {
                $this->selectedCategories = array_values(array_filter(
                    $this->selectedCategories,
                    fn($cId) => (int)$cId !== (int)$prevCat->id
                ));
            }
        }
        if (!in_array((string)$newCat->id, array_map('strval', $this->selectedCategories), true)) {
            $this->selectedCategories[] = (string)$newCat->id;
        }

        $this->category = $newCat->slug;
        $this->resetPage();

        if (request()->routeIs('shop.category')) {
            $params = ['category_slug' => $newCat->slug];
            if ($this->brand) {
                $params['brand'] = $this->brand;
            }
            if (!empty(trim($this->search))) {
                $params['search'] = trim($this->search);
            }
            return $this->redirectRoute('shop.category', $params);
        }

        return null;
    }

    public function removeCategoryPill(int $categoryId): mixed
    {
        if ($this->category) {
            $currentCat = Category::where('slug', $this->category)->first();
            if ($currentCat) {
                $chain = $currentCat->getBreadcrumbChain();
                $chainIds = array_map(fn($c) => (int)$c->id, $chain);
                $index = array_search((int)$categoryId, $chainIds, true);

                if ($index !== false) {
                    if ($index === 0) {
                        return $this->clearCategory();
                    } else {
                        $parentCat = $chain[$index - 1];
                        return $this->setCategory($parentCat->slug);
                    }
                }
            }
        }

        return $this->removeSelectedCategory($categoryId);
    }

    public function clearCategory(): mixed
    {
        if ($this->category) {
            $catModel = Category::where('slug', $this->category)->first();
            if ($catModel) {
                $catId = (int) $catModel->id;
                $this->selectedCategories = array_values(array_filter(
                    $this->selectedCategories,
                    fn($cId) => (int)$cId !== $catId
                ));
            }
        }
        $this->category = null;
        $this->resetPage();

        if (request()->routeIs('shop.category')) {
            if ($this->brand) {
                return $this->redirectRoute('shop.brand', ['brand_slug' => $this->brand]);
            }
            if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && empty(trim($this->search))) {
                return $this->redirect('/');
            }
            return $this->redirectRoute('shop.index');
        }
        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && !$this->brand && empty(trim($this->search))) {
            return $this->redirect('/');
        }
        return null;
    }

    public function clearBrand(): mixed
    {
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel) {
                $bId = (int) $brandModel->id;
                $this->selectedBrands = array_values(array_filter(
                    $this->selectedBrands,
                    fn($brandId) => (int)$brandId !== $bId
                ));
            }
        }
        $this->brand = null;
        $this->resetPage();

        if (request()->routeIs('shop.brand')) {
            if ($this->category) {
                return $this->redirectRoute('shop.category', ['category_slug' => $this->category]);
            }
            if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && empty(trim($this->search))) {
                return $this->redirect('/');
            }
            return $this->redirectRoute('shop.index');
        }
        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && !$this->category && empty(trim($this->search))) {
            return $this->redirect('/');
        }
        return null;
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function clearPriceFilter(): void
    {
        $this->minPriceFilter = null;
        $this->maxPriceFilter = null;
        $this->resetPage();
    }

    public function removeSelectedBrand(int $id): mixed
    {
        $this->selectedBrands = array_values(array_filter(
            $this->selectedBrands,
            fn($bId) => (int)$bId !== $id
        ));
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel && (int)$brandModel->id === $id) {
                return $this->clearBrand();
            }
        }
        $this->resetPage();
        return null;
    }

    public function removeSelectedCategory(int $id): mixed
    {
        $this->selectedCategories = array_values(array_filter(
            $this->selectedCategories,
            fn($cId) => (int)$cId !== $id
        ));
        if ($this->category) {
            $catModel = Category::where('slug', $this->category)->first();
            if ($catModel && (int)$catModel->id === $id) {
                return $this->clearCategory();
            }
        }
        $this->resetPage();
        return null;
    }

    public function removeSelectedAttribute(string $key, string $val): void
    {
        if (isset($this->selectedAttributes[$key])) {
            if (is_array($this->selectedAttributes[$key])) {
                $this->selectedAttributes[$key] = array_values(array_filter(
                    $this->selectedAttributes[$key],
                    fn($v) => (string)$v !== (string)$val
                ));
                if (empty($this->selectedAttributes[$key])) {
                    unset($this->selectedAttributes[$key]);
                }
            } else {
                unset($this->selectedAttributes[$key]);
            }
        }
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingBrand(): void
    {
        $this->resetPage();
    }

    private function getCartSessionId(): string
    {
        return \App\Services\CartSessionService::getCartSessionId();
    }

    public function buyNow(int $variantId)
    {
        $variant = ProductVariant::with(['inventory', 'product.fields'])->findOrFail($variantId);
        $product = $variant->product;

        if ($product && ($product->fields->isNotEmpty() || $product->is_donation_or_bill_pay)) {
            return redirect()->route('shop.product', $product->seo_slug);
        }

        $sessionId = $this->getCartSessionId();
        $userId = auth()->id() ?? 0;

        if (!$variant->download_item && $variant->inventory) {
            $available = $variant->getStockForFulfillment(
                auth()->user()?->shipping_countrycode,
                auth()->user()?->shipping_state
            );
            if ($available <= 0) {
                $this->catalogError = "Item is out of stock.";
                session()->flash('error', $this->catalogError);
                $this->dispatch('show-catalog-error', message: $this->catalogError);
                return;
            }
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        $price = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
        $discountPrice = 0;
        if ($userType != 2 && $variant->isOnSaleActive()) {
            $discountPrice = $price - $variant->sale_price;
            $price = $variant->sale_price;
        }

        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
        if ($variantFee > 0) {
            $price += $variantFee;
        }

        $cartItems = \App\Services\CartSessionService::getCartQuery($sessionId)->get();

        $skusInCart = [];
        foreach ($cartItems as $ci) {
            if (preg_match('/\(([^)]+)\)$/', $ci->item_name, $matches)) {
                $skusInCart[] = $matches[1];
            }
        }

        if (!empty($skusInCart)) {
            $hasStandaloneInCart = \App\Models\ProductVariant::whereIn('sku', $skusInCart)
                ->whereHas('product', function ($q) {
                    $q->where('standalone_purchase', 1);
                })
                ->exists();

            if ($hasStandaloneInCart) {
                $this->catalogError = "Your cart contains a standalone item which cannot be purchased with other items.";
                session()->flash('error', $this->catalogError);
                $this->dispatch('show-catalog-error', message: $this->catalogError);
                return;
            }
        }

        if ($product && $product->standalone_purchase == 1 && $cartItems->isNotEmpty()) {
            $onlySameSku = true;
            foreach ($skusInCart as $skuInCart) {
                if ($skuInCart !== $variant->sku) {
                    $onlySameSku = false;
                    break;
                }
            }
            if (!$onlySameSku) {
                $this->catalogError = "This standalone item cannot be purchased with other items. Please empty your cart first.";
                session()->flash('error', $this->catalogError);
                $this->dispatch('show-catalog-error', message: $this->catalogError);
                return;
            }
        }

        $cartItem = \App\Services\CartSessionService::getCartQuery($sessionId)
            ->where('variant_id', $variant->id)
            ->where('item_attributes', $variant->attributes)
            ->first();

        if ($cartItem && $product && $product->max_qty == 1) {
            $this->catalogError = "You can only purchase a maximum of 1 unit of this item per order.";
            session()->flash('error', $this->catalogError);
            $this->dispatch('show-catalog-error', message: $this->catalogError);
            if ($product->checkout_redirect == 1 || $product->standalone_purchase == 1) {
                return redirect()->route('shop.checkout');
            }
            return;
        }

        $formattedItemName = \App\Services\CartSessionService::formatCartItemName($variant->product, $variant);
        $qtyToAdd = 1;

        if ($cartItem) {
            $cartItem->item_qty += $qtyToAdd;
            $cartItem->save();
        } else {
            ShoppingCartLog::create([
                'cart_log_session' => $sessionId,
                'item_name' => $formattedItemName,
                'item_qty' => $qtyToAdd,
                'item_price' => $price,
                'item_discount_price' => $discountPrice,
                'item_attributes' => $variant->attributes ?? '',
                'item_shippable' => $variant->shipping,
                'item_weight' => $variant->weight ?? 0,
                'item_taxable' => $this->resolveItemTaxable($variant, $variant->product),
                'item_downloadable' => $variant->download_item,
                'variant_id' => $variant->id,
                'order_id' => 0,
                'user_id' => $userId
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('status', 'Item successfully added to your cart!');

        if ($product && ($product->checkout_redirect == 1 || $product->standalone_purchase == 1)) {
            return redirect()->route('shop.checkout');
        }

        $this->dispatch('show-cart-modal',
            itemName: $formattedItemName,
            qty: 1,
        );
    }

    public function render(): View
    {
        $this->sanitizePerPage();

        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing')) {
            $hasFilter = !empty($this->category) || !empty($this->brand) || !empty(trim($this->search));
            if (!$hasFilter) {
                $this->redirect('/', navigate: true);
            }
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;
        $priceCol = ($userType === 2) ? 'wholesale_price' : 'public_price';

        // Calculate maximum catalog item price for range slider (cached for 1 hour)
        $catalogMaxPrice = (float) \Illuminate\Support\Facades\Cache::remember('shop_catalog_max_price_' . $priceCol, 3600, function () use ($priceCol) {
            return ProductVariant::whereHas('product', fn($q) => $q->active()->showInResults())
                ->max(DB::raw("CASE WHEN on_sale = 1 AND sale_price > 0 THEN sale_price ELSE {$priceCol} END")) ?? 500;
        });
        if ($catalogMaxPrice <= 0) {
            $catalogMaxPrice = 500;
        }

        $advancedSearchEnabled = \App\Models\CmsSetting::isAdvancedSearchEnabled();
        $advancedSearchAttributesEnabled = \App\Models\CmsSetting::isAdvancedSearchAttributesEnabled();

        // ── Base query (shared scope) ────────────────────────────────────────
        $baseQuery = Product::query()
            ->active()
            ->showInResults()
            ->when($this->category, function ($query) {
                $categoryModel = Category::where('slug', $this->category)->first();
                if ($categoryModel) {
                    $categoryIds = Category::getDescendantIdsFor($categoryModel->id);
                    $query->whereExists(function ($sub) use ($categoryIds) {
                        $sub->select(DB::raw(1))
                            ->from('product_categories_assignments')
                            ->whereColumn('product_categories_assignments.product_id', 'products.id')
                            ->whereIn('product_categories_assignments.category_id', $categoryIds);
                    });
                }
            })
            ->when($this->brand, function ($query) {
                $brandId = Brand::where('slug', $this->brand)->value('id');
                if ($brandId) {
                    $query->where('products.brand_id', $brandId);
                }
            })
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($sub) use ($searchTerm) {
                    $sub->where('title', 'like', $searchTerm)
                        ->orWhere('short_description', 'like', $searchTerm)
                        ->orWhere('long_description', 'like', $searchTerm)
                        ->orWhereExists(function ($brandSub) use ($searchTerm) {
                            $brandSub->select(DB::raw(1))
                                ->from('product_brands')
                                ->whereColumn('product_brands.id', 'products.brand_id')
                                ->where('name', 'like', $searchTerm);
                        })
                        ->orWhereExists(function ($catSub) use ($searchTerm) {
                            $catSub->select(DB::raw(1))
                                ->from('product_categories_assignments')
                                ->join('product_categories', 'product_categories.id', '=', 'product_categories_assignments.category_id')
                                ->whereColumn('product_categories_assignments.product_id', 'products.id')
                                ->where('product_categories.name', 'like', $searchTerm);
                        })
                        ->orWhereExists(function ($varSub) use ($searchTerm) {
                            $varSub->select(DB::raw(1))
                                ->from('product_variants')
                                ->whereColumn('product_variants.product_id', 'products.id')
                                ->where('sku', 'like', $searchTerm);
                        });
                });
            })
            // Advanced Multi-Brand Filter (checkboxes)
            ->when($advancedSearchEnabled && !empty($this->selectedBrands), function ($query) {
                $query->whereIn('products.brand_id', array_map('intval', $this->selectedBrands));
            })
            // Advanced Multi-Category / Subcategory Filter (checkboxes)
            ->when($advancedSearchEnabled && !empty($this->selectedCategories), function ($query) {
                $selectedCatIds = array_filter(array_map('intval', $this->selectedCategories));
                if (!empty($selectedCatIds)) {
                    $allCatIds = Category::getDescendantIdsFor($selectedCatIds);
                    $query->whereExists(function ($sub) use ($allCatIds) {
                        $sub->select(DB::raw(1))
                            ->from('product_categories_assignments')
                            ->whereColumn('product_categories_assignments.product_id', 'products.id')
                            ->whereIn('product_categories_assignments.category_id', $allCatIds);
                    });
                }
            })
            // Price Range Slider Filter
            ->when($advancedSearchEnabled && ($this->minPriceFilter !== null || $this->maxPriceFilter !== null), function ($query) use ($priceCol, $userType, $catalogMaxPrice) {
                $minP = (float) ($this->minPriceFilter ?? 0);
                $maxP = (float) ($this->maxPriceFilter ?? $catalogMaxPrice);
                $query->whereExists(function ($sub) use ($priceCol, $minP, $maxP, $userType) {
                    $sub->select(DB::raw(1))
                        ->from('product_variants')
                        ->whereColumn('product_variants.product_id', 'products.id');
                    if ($userType !== 2) {
                        $sub->where(function ($s) use ($priceCol, $minP, $maxP) {
                            $s->where(function ($s1) use ($minP, $maxP) {
                                $s1->where('on_sale', 1)->where('sale_price', '>', 0)
                                   ->whereBetween('sale_price', [$minP, $maxP]);
                            })->orWhere(function ($s2) use ($priceCol, $minP, $maxP) {
                                $s2->where(function ($s3) {
                                    $s3->where('on_sale', 0)->orWhereNull('sale_price')->orWhere('sale_price', 0);
                                })->whereBetween($priceCol, [$minP, $maxP]);
                            });
                        });
                    } else {
                        $sub->whereBetween($priceCol, [$minP, $maxP]);
                    }
                });
            })
            // Dynamic Variant Attributes JSON Filter
            ->when($advancedSearchEnabled && $advancedSearchAttributesEnabled && !empty($this->selectedAttributes), function ($query) {
                foreach ($this->selectedAttributes as $attrKey => $attrVals) {
                    if (is_bool($attrVals) || empty($attrVals)) continue;
                    $attrVals = (array) $attrVals;
                    $query->whereExists(function ($sub) use ($attrKey, $attrVals) {
                        $sub->select(DB::raw(1))
                            ->from('product_variants')
                            ->whereColumn('product_variants.product_id', 'products.id')
                            ->where(function ($s) use ($attrKey, $attrVals) {
                                foreach ($attrVals as $val) {
                                    if (is_bool($val) || is_array($val)) continue;
                                    $val = trim((string) $val);
                                    if ($val === '') continue;
                                    $s->orWhere('attributes', 'like', '%"' . $attrKey . '":"' . $val . '"%')
                                      ->orWhere('attributes', 'like', '%' . $attrKey . ':' . $val . '%')
                                      ->orWhere('attributes', 'like', '%' . $val . '%');
                                }
                            });
                    });
                }
            });

        // ── Paginated product list with dynamic sorting ─────────────────────
        $hideZero = \App\Models\CmsSetting::isEnabled('hide_zero_price_variants');
        $variantPriceCondition = $hideZero ? ' AND (public_price > 0 OR (on_sale = 1 AND sale_price > 0))' : '';
        $sortPriceSubquery = "(SELECT MIN(CASE WHEN on_sale = 1 AND sale_price > 0 THEN sale_price ELSE public_price END) FROM product_variants WHERE product_variants.product_id = products.id{$variantPriceCondition})";
        $sortRatingSubquery = 'COALESCE((SELECT AVG(rating) FROM product_reviews WHERE product_reviews.product_id = products.id AND approved = 1), products.reviews_rating, 0)';

        $productsQuery = (clone $baseQuery)->with([
            'brand',
            'categories.translations',
            'variants' => function ($q) use ($hideZero) {
                if ($hideZero) {
                    $q->where(fn($sq) => $sq->where('public_price', '>', 0)->orWhere(fn($sq2) => $sq2->where('on_sale', 1)->where('sale_price', '>', 0)));
                }
            },
            'variants.inventory',
            'variants.images',
            'fields',
        ]);

        switch ($this->sort) {
            case 'price_desc':
                $productsQuery->orderByRaw("{$sortPriceSubquery} DESC");
                break;
            case 'title_asc':
                $productsQuery->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $productsQuery->orderBy('title', 'desc');
                break;
            case 'rating_desc':
                $productsQuery->orderByRaw("{$sortRatingSubquery} DESC")->orderBy('title', 'asc');
                break;
            case 'rating_asc':
                $productsQuery->orderByRaw("{$sortRatingSubquery} ASC")->orderBy('title', 'asc');
                break;
            case 'price_asc':
            default:
                $productsQuery->orderByRaw("{$sortPriceSubquery} ASC");
                break;
        }

        if (!$this->readyToLoad) {
            $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage, 1, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } elseif (\App\Models\CmsSetting::isEnabled('shop_disable_default_product_listing') && !$this->hasActiveFilters) {
            $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, $this->perPage, 1, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } else {
            $products = $productsQuery->withCurrentTranslations()->paginate($this->perPage);
        }

        // ── Filter panel data ───────────────────────────────────────────────
        $filterCategories = collect();
        $filterBrands     = collect();

        // 1. Build product scope for category filter pills (applies brand, search, price, attributes)
        $catScopeQuery = Product::query()->active()->showInResults();
        if ($this->brand) {
            $bId = Brand::where('slug', $this->brand)->value('id');
            if ($bId) {
                $catScopeQuery->where('products.brand_id', $bId);
            }
        }
        if (!empty($this->selectedBrands)) {
            $catScopeQuery->whereIn('products.brand_id', array_map('intval', $this->selectedBrands));
        }
        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            $catScopeQuery->where(function ($sub) use ($searchTerm) {
                $sub->where('title', 'like', $searchTerm)
                    ->orWhere('short_description', 'like', $searchTerm)
                    ->orWhere('long_description', 'like', $searchTerm);
            });
        }

        $assignedCategoryIds = DB::table('product_categories_assignments')
            ->joinSub($catScopeQuery->select('products.id'), 'scoped_products', 'scoped_products.id', '=', 'product_categories_assignments.product_id')
            ->distinct()
            ->pluck('product_categories_assignments.category_id');

        $assignedSet = array_flip($assignedCategoryIds->all());

        // Fetch visible category tree once for both drill-down and advanced search
        $allAvailableCategories = Category::withCurrentTranslations()
            ->where('is_visible_in_menu', true)
            ->where(function($q) {
                $q->whereNull('parent_id')
                  ->orWhere('parent_id', 0);
            })
            ->with([
                'children' => fn($q) => $q->where('is_visible_in_menu', true)->withCurrentTranslations()->orderBy('sort_order')->orderBy('name'),
                'children.children' => fn($q) => $q->where('is_visible_in_menu', true)->withCurrentTranslations()->orderBy('sort_order')->orderBy('name'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        if (!$this->category) {
            // Filter tree by assignedSet
            $filterCategories = $allAvailableCategories->filter(function ($root) use ($assignedSet) {
                $hasSelf = isset($assignedSet[$root->id]);
                $filteredChildren = $root->children->filter(function ($child) use ($assignedSet) {
                    $hasChildSelf = isset($assignedSet[$child->id]);
                    $filteredGrandchildren = $child->children->filter(fn($gc) => isset($assignedSet[$gc->id]))->values();
                    $child->setRelation('children', $filteredGrandchildren);
                    return $hasChildSelf || $filteredGrandchildren->isNotEmpty();
                })->values();

                $root->setRelation('children', $filteredChildren);
                return $hasSelf || $filteredChildren->isNotEmpty();
            })->values();
        } else {
            $activeCategory = Category::withCurrentTranslations()
                ->where('slug', $this->category)
                ->where('is_visible_in_menu', true)
                ->with([
                    'children' => fn($q) => $q->where('is_visible_in_menu', true)->withCurrentTranslations()->orderBy('sort_order')->orderBy('name'),
                    'children.children' => fn($q) => $q->where('is_visible_in_menu', true)->withCurrentTranslations()->orderBy('sort_order')->orderBy('name'),
                ])->first();

            if ($activeCategory && $activeCategory->children->isNotEmpty()) {
                $filterCategories = $activeCategory->children
                    ->filter(fn($child) => $child->is_visible_in_menu)
                    ->map(function ($child) use ($assignedSet) {
                        $hasChildSelf = isset($assignedSet[$child->id]);
                        $filteredGrandchildren = $child->children
                            ->filter(fn($gc) => $gc->is_visible_in_menu && isset($assignedSet[$gc->id]))
                            ->values();
                        $child->setRelation('children', $filteredGrandchildren);
                        return ($hasChildSelf || $filteredGrandchildren->isNotEmpty()) ? $child : null;
                    })
                    ->filter()
                    ->values();
            }
        }

        // 2. Build product scope for available brands (applies category, search, etc.)
        if (!$this->brand) {
            $brandScopeQuery = Product::query()
                ->active()
                ->showInResults()
                ->whereNotNull('brand_id');

            if ($this->category) {
                $catModel = Category::where('slug', $this->category)->first();
                if ($catModel) {
                    $scopedCatIds = Category::getDescendantIdsFor($catModel->id);
                    $brandScopeQuery->whereExists(function ($sub) use ($scopedCatIds) {
                        $sub->select(DB::raw(1))
                            ->from('product_categories_assignments')
                            ->whereColumn('product_categories_assignments.product_id', 'products.id')
                            ->whereIn('product_categories_assignments.category_id', $scopedCatIds);
                    });
                }
            }
            if (!empty($this->selectedCategories)) {
                $scopedCatIds = Category::getDescendantIdsFor(array_map('intval', $this->selectedCategories));
                $brandScopeQuery->whereExists(function ($sub) use ($scopedCatIds) {
                    $sub->select(DB::raw(1))
                        ->from('product_categories_assignments')
                        ->whereColumn('product_categories_assignments.product_id', 'products.id')
                        ->whereIn('product_categories_assignments.category_id', $scopedCatIds);
                });
            }
            if (!empty(trim($this->search))) {
                $searchTerm = '%' . trim($this->search) . '%';
                $brandScopeQuery->where(function ($sub) use ($searchTerm) {
                    $sub->where('title', 'like', $searchTerm)
                        ->orWhere('short_description', 'like', $searchTerm)
                        ->orWhere('long_description', 'like', $searchTerm);
                });
            }

            $matchingBrandIds = $brandScopeQuery->distinct()->pluck('brand_id')->all();
            $filterBrands = Brand::visibleInMenu()
                ->whereIn('id', $matchingBrandIds)
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'brand_icon', 'brand_logo_s3', 'show_image', 'brand_icon_direct_url']);
        }

        // Available all brands for Advanced Search Checkbox panel
        $allAvailableBrands = $advancedSearchEnabled ? Brand::visibleInMenu()->orderBy('name')->get() : collect();

        // Extract available variant JSON attributes
        $availableVariantAttributes = [];
        if ($advancedSearchEnabled && $advancedSearchAttributesEnabled) {
            $hasScope = !empty($this->category) || !empty($this->brand) || !empty($this->search) || !empty($this->selectedBrands) || !empty($this->selectedCategories);

            if ($hasScope) {
                // Scope to currently filtered base products (capped to 500 products for sub-10ms performance)
                $scopedProductIds = (clone $baseQuery)->limit(500)->pluck('products.id');
                $variantAttributesRaw = ProductVariant::whereIn('product_id', $scopedProductIds)
                    ->whereNotNull('attributes')
                    ->where('attributes', '!=', '')
                    ->pluck('attributes');
                $availableVariantAttributes = $this->parseVariantAttributes($variantAttributesRaw, 60);
            } else {
                // Global top variant attributes cached for 30 minutes
                $availableVariantAttributes = \Illuminate\Support\Facades\Cache::remember('shop_catalog_available_variant_attrs_v2', 1800, function () {
                    $variantAttributesRaw = ProductVariant::whereNotNull('attributes')
                        ->where('attributes', '!=', '')
                        ->pluck('attributes');
                    return $this->parseVariantAttributes($variantAttributesRaw, 60);
                });
            }

            // Always ensure any currently active user-selected attribute values remain in the list
            if (is_array($this->selectedAttributes)) {
                foreach ($this->selectedAttributes as $attrKey => $selectedVals) {
                    if (is_array($selectedVals)) {
                        foreach ($selectedVals as $sVal) {
                            if (is_string($sVal) && $sVal !== '') {
                                if (!isset($availableVariantAttributes[$attrKey])) {
                                    $availableVariantAttributes[$attrKey] = [];
                                }
                                if (!in_array($sVal, $availableVariantAttributes[$attrKey])) {
                                    $availableVariantAttributes[$attrKey][] = $sVal;
                                    sort($availableVariantAttributes[$attrKey]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Count active advanced filter badges
        $activeAttributeCount = 0;
        if ($advancedSearchEnabled && $advancedSearchAttributesEnabled && is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $vals) {
                if (is_array($vals)) {
                    $activeAttributeCount += count(array_filter($vals, fn($v) => !is_bool($v) && is_string($v) && trim($v) !== ''));
                }
            }
        }

        $activeFilterCount = ($advancedSearchEnabled ? count($this->selectedBrands) : 0)
            + ($advancedSearchEnabled ? count($this->selectedCategories) : 0)
            + $activeAttributeCount
            + (($advancedSearchEnabled && ($this->minPriceFilter !== null || $this->maxPriceFilter !== null)) ? 1 : 0);

        // ── Resolve active filter models & compute page heading ──────────────
        $activeCategory = $this->category
            ? Category::withCurrentTranslations()->where('slug', $this->category)->first()
            : null;
        $activeBrand = $this->brand
            ? Brand::where('slug', $this->brand)->first()
            : null;

        $breadcrumbChain = $activeCategory ? $activeCategory->getBreadcrumbChain() : [];

        $categoryTitle = !empty($breadcrumbChain)
            ? collect($breadcrumbChain)->pluck('name')->implode(' › ')
            : '';

        $defaultDescription = siteLabel('catalog.page_description', 'Browse our curated catalog. Enjoy exclusive wholesale pricing if eligible.');

        if ($activeCategory && $activeBrand) {
            $pageTitle       = $categoryTitle . ' › ' . $activeBrand->name;
            $pageDescription = '';
        } elseif ($activeCategory) {
            $pageTitle       = $categoryTitle;
            $pageDescription = !empty(trim($activeCategory->description ?? '')) ? trim($activeCategory->description) : '';
        } elseif ($activeBrand) {
            $pageTitle       = $activeBrand->name;
            $pageDescription = !empty(trim($activeBrand->description ?? '')) ? trim($activeBrand->description) : '';
        } elseif (!empty(trim($this->search))) {
            $pageTitle       = 'Search results for "' . trim($this->search) . '"';
            $pageDescription = 'Showing items matching your search.';
        } else {
            $pageTitle       = siteLabel('catalog.page_title', 'E-Commerce Products');
            $pageDescription = $defaultDescription;
        }

        $siteName  = \App\Models\CmsSetting::getSiteName();
        $metaTitle = $pageTitle . ($siteName ? ' | ' . $siteName : '');

        $selectedCategoryModels = collect();
        if (!empty($this->selectedCategories)) {
            $selectedCategoryModels = Category::withCurrentTranslations()->whereIn('id', $this->selectedCategories)->get()->keyBy('id');
        }

        // Store the full catalog & search URL in session so Product Details page can link back to exact search, filters & pagination
        $currentCatalogUrl = $this->getCurrentCatalogUrl();
        session([
            'last_catalog_url'   => $currentCatalogUrl,
            'last_search_url'    => $currentCatalogUrl,
            'last_search_active' => (trim($this->search) !== '' || $this->hasActiveFilters),
        ]);

        $gaEcommerceData = null;
        if (\App\Services\GoogleAnalyticsService::isEnabled() && $products->isNotEmpty()) {
            $listName = $activeCategory ? $activeCategory->name : ($activeBrand ? $activeBrand->name : (!empty($this->search) ? 'Search Results: ' . $this->search : 'Catalog Products'));
            $listId = $activeCategory ? 'category_' . $activeCategory->id : ($activeBrand ? 'brand_' . $activeBrand->id : 'catalog_products');
            $gaEcommerceData = \App\Services\GoogleAnalyticsService::formatItemList($products->items(), $listName, $listId);
        }

        // Resolve exclusive catalog header banner image
        // Display header image ONLY if filtered by category OR brand, but NOT both and NOT neither.
        $headerImageUrl = null;
        $headerImageAlt = null;

        $isCategoryFiltered = !empty($this->category) || !empty($this->selectedCategories);
        $isBrandFiltered    = !empty($this->brand) || !empty($this->selectedBrands);

        if ($isCategoryFiltered xor $isBrandFiltered) {
            if ($isCategoryFiltered) {
                $targetCat = $activeCategory;
                if (!$targetCat && !empty($this->selectedCategories)) {
                    $firstCatId = (int) reset($this->selectedCategories);
                    $targetCat = $selectedCategoryModels->get($firstCatId) ?? Category::find($firstCatId);
                }
                if ($targetCat && method_exists($targetCat, 'getHeaderImageUrl')) {
                    $headerImageUrl = $targetCat->getHeaderImageUrl();
                    $headerImageAlt = $targetCat->name;
                }
            } elseif ($isBrandFiltered) {
                $targetBrand = $activeBrand;
                if (!$targetBrand && !empty($this->selectedBrands)) {
                    $firstBrandVal = reset($this->selectedBrands);
                    $targetBrand = is_numeric($firstBrandVal) ? Brand::find((int)$firstBrandVal) : Brand::where('slug', $firstBrandVal)->first();
                }
                if ($targetBrand && method_exists($targetBrand, 'getHeaderImageUrl')) {
                    $headerImageUrl = $targetBrand->getHeaderImageUrl();
                    $headerImageAlt = $targetBrand->name;
                }
            }
        }

        return view('livewire.shop-catalog', [
            'products'                        => $products,
            'headerImageUrl'                  => $headerImageUrl,
            'headerImageAlt'                  => $headerImageAlt,
            'userType'                        => $userType,
            'filterCategories'                => $filterCategories,
            'filterBrands'                    => $filterBrands,
            'allAvailableBrands'              => $allAvailableBrands,
            'allAvailableCategories'          => $allAvailableCategories,
            'selectedCategoryModels'          => $selectedCategoryModels,
            'availableVariantAttributes'      => $availableVariantAttributes,
            'catalogMaxPrice'                 => $catalogMaxPrice,
            'advancedSearchEnabled'           => $advancedSearchEnabled,
            'advancedSearchAttributesEnabled' => $advancedSearchAttributesEnabled,
            'activeFilterCount'               => $activeFilterCount,
            'hasActiveFilters'                => $this->hasActiveFilters,
            'activeCategory'                  => $activeCategory,
            'breadcrumbChain'                 => $breadcrumbChain,
            'activeBrand'                     => $activeBrand,
            'pageTitle'                       => $pageTitle,
            'pageDescription'                 => $pageDescription,
            'viewMode'                        => $this->viewMode,
            'currencySymbol'                  => \App\Services\CurrencyService::symbol(),
            'vatInclusive'                    => \App\Services\CurrencyService::isVatInclusive(),
            'merchantVatRate'                 => \App\Services\CurrencyService::merchantVatRate(),
            'readyToLoad'                     => $this->readyToLoad,
            'gaEcommerceData'                 => $gaEcommerceData,
        ])->layout('layouts.public', [
            'metaTitle'    => $metaTitle,
            'title'        => $metaTitle,
            'canonicalUrl' => $this->canonicalUrl,
        ]);
    }

    public function getCurrentCatalogUrl(): string
    {
        $params = [];
        if (trim($this->search) !== '') {
            $params['search'] = trim($this->search);
        }
        if (!empty($this->selectedBrands)) {
            $params['selectedBrands'] = array_values(array_map('intval', (array)$this->selectedBrands));
        }
        if (!empty($this->selectedCategories)) {
            $params['selectedCategories'] = array_values(array_map('intval', (array)$this->selectedCategories));
        }
        if ($this->minPriceFilter !== null && $this->minPriceFilter > 0) {
            $params['minPriceFilter'] = $this->minPriceFilter;
        }
        if ($this->maxPriceFilter !== null && $this->maxPriceFilter > 0) {
            $params['maxPriceFilter'] = $this->maxPriceFilter;
        }
        if (!empty($this->selectedAttributes)) {
            $params['selectedAttributes'] = $this->selectedAttributes;
        }
        if ($this->sort !== 'price_asc') {
            $params['sort'] = $this->sort;
        }
        if ($this->perPage && (int)$this->perPage !== 16) {
            $params['perPage'] = (int)$this->perPage;
        }
        $currentPage = $this->getPage();
        if ($currentPage > 1) {
            $params['page'] = $currentPage;
        }

        if (request()->routeIs('shop.brand') && $this->brand) {
            if ($this->category) {
                $params['category'] = $this->category;
            }
            return route('shop.brand', array_merge(['brand_slug' => $this->brand], $params));
        }

        if ($this->category) {
            if ($this->brand) {
                $params['brand'] = $this->brand;
            }
            return route('shop.category', array_merge(['category_slug' => $this->category], $params));
        }

        if ($this->brand) {
            if ($this->category) {
                $params['category'] = $this->category;
            }
            return route('shop.brand', array_merge(['brand_slug' => $this->brand], $params));
        }

        return route('shop.index', $params);
    }

    private function resolveItemTaxable(\App\Models\ProductVariant $variant, $product): int
    {
        if ((int)($variant->charge_tax ?? 1) === 1) {
            return 1;
        }
        return \App\Models\ProductField::where('product_id', $product->id)
            ->where('charge_tax', 1)
            ->exists() ? 1 : 0;
    }

    protected function parseVariantAttributes($variantAttributesRaw, int $limitPerKey = 60): array
    {
        $attrs = [];
        foreach ($variantAttributesRaw as $attrRaw) {
            $attrArray = is_string($attrRaw) ? json_decode($attrRaw, true) : $attrRaw;
            if (!is_array($attrArray)) {
                $pairs = explode(',', (string)$attrRaw);
                $attrArray = [];
                foreach ($pairs as $pair) {
                    if (str_contains($pair, ':')) {
                        [$k, $v] = explode(':', $pair, 2);
                        $attrArray[trim($k)] = trim($v);
                    }
                }
            }
            if (is_array($attrArray)) {
                foreach ($attrArray as $aKey => $aVal) {
                    $aKey = trim((string)$aKey);
                    if (empty($aKey) || in_array(strtolower($aKey), ['sku', 'price', 'weight', 'inventory'])) continue;
                    if (is_array($aVal)) {
                        foreach ($aVal as $vItem) {
                            $vItem = trim((string)$vItem);
                            if ($vItem !== '') {
                                $attrs[$aKey][$vItem] = ($attrs[$aKey][$vItem] ?? 0) + 1;
                            }
                        }
                    } else {
                        $aVal = trim((string)$aVal);
                        if ($aVal !== '') {
                            $attrs[$aKey][$aVal] = ($attrs[$aKey][$aVal] ?? 0) + 1;
                        }
                    }
                }
            }
        }

        $result = [];
        foreach ($attrs as $k => $counts) {
            ksort($counts);
            if ($limitPerKey > 0 && count($counts) > $limitPerKey) {
                $counts = array_slice($counts, 0, $limitPerKey, true);
            }
            $result[$k] = array_keys($counts);
        }
        ksort($result);
        return $result;
    }
}
