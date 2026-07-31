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
    public $perPage = 25;

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

    public function sanitizePerPage(): void
    {
        $allowed = [5, 10, 15, 25, 50, 75, 100];
        $val = filter_var($this->perPage, FILTER_VALIDATE_INT);
        if ($val === false || !in_array($val, $allowed, true)) {
            $this->perPage = 25;
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
        $this->syncActivePreselection();
    }

    public function mount(?string $category_slug = null, ?string $brand_slug = null): void
    {
        $this->sanitizePerPage();
        $this->sanitizeSort();
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
    public function updatedSelectedBrands(): void { $this->resetPage(); }
    public function updatedSelectedCategories(): void { $this->resetPage(); }
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
            return $this->redirectRoute('shop.index', navigate: true);
        }

        return null;
    }

    public function clearCategory(): mixed
    {
        $this->category = null;
        $this->resetPage();
        if (request()->routeIs('shop.category')) {
            if ($this->brand) {
                return $this->redirectRoute('shop.brand', ['brand_slug' => $this->brand], navigate: true);
            }
            if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && empty(trim($this->search))) {
                return $this->redirect('/', navigate: true);
            }
            return $this->redirectRoute('shop.index', navigate: true);
        }
        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && !$this->brand && empty(trim($this->search))) {
            return $this->redirect('/', navigate: true);
        }
        return null;
    }

    public function clearBrand(): mixed
    {
        $this->brand = null;
        $this->resetPage();
        if (request()->routeIs('shop.brand')) {
            if ($this->category) {
                return $this->redirectRoute('shop.category', ['category_slug' => $this->category], navigate: true);
            }
            if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && empty(trim($this->search))) {
                return $this->redirect('/', navigate: true);
            }
            return $this->redirectRoute('shop.index', navigate: true);
        }
        if (\App\Models\CmsSetting::isEnabled('disable_shop_landing') && !$this->category && empty(trim($this->search))) {
            return $this->redirect('/', navigate: true);
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

    public function removeSelectedBrand(int $id): void
    {
        $this->selectedBrands = array_values(array_filter(
            $this->selectedBrands,
            fn($bId) => (int)$bId !== $id
        ));
        if ($this->brand) {
            $brandModel = Brand::where('slug', $this->brand)->first();
            if ($brandModel && $brandModel->id === $id) {
                $this->clearBrand();
                return;
            }
        }
        $this->resetPage();
    }

    public function removeSelectedCategory(int $id): void
    {
        $this->selectedCategories = array_values(array_filter(
            $this->selectedCategories,
            fn($cId) => (int)$cId !== $id
        ));
        if ($this->category) {
            $catModel = Category::where('slug', $this->category)->first();
            if ($catModel && $catModel->id === $id) {
                $this->clearCategory();
                return;
            }
        }
        $this->resetPage();
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
        $cookieName = 'cart_session_id';
        $sessionId = request()->cookie($cookieName);

        if (!$sessionId) {
            $sessionId = (string) Str::uuid();
            cookie()->queue($cookieName, $sessionId, 60 * 24 * 30); // 30 days
        }

        return $sessionId;
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
        if ($userType != 2 && $variant->on_sale && $variant->sale_price > 0) {
            $discountPrice = $price - $variant->sale_price;
            $price = $variant->sale_price;
        }

        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
        if ($variantFee > 0) {
            $price += $variantFee;
        }

        $cartItems = ShoppingCartLog::where('order_id', 0)
            ->where(function($query) use ($sessionId, $userId) {
                if ($userId > 0) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('cart_log_session', $sessionId)
                          ->where('user_id', 0);
                }
            })->get();

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

        // IMPORTANT: must also filter by item_name (which encodes the SKU) so we only
        // match THIS product's cart row — not any other simple product whose item_attributes
        // is also an empty string ''.
        $cartItem = ShoppingCartLog::where(function($query) use ($sessionId, $userId) {
            if ($userId > 0) {
                $query->where('user_id', $userId);
            } else {
                $query->where('cart_log_session', $sessionId)
                      ->where('user_id', 0);
            }
        })
        ->where('item_name', 'like', '%(' . $variant->sku . ')')
        ->where('item_attributes', $variant->attributes)
        ->where('order_id', 0)
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

        $qtyToAdd = 1;

        if ($cartItem) {
            $cartItem->item_qty += $qtyToAdd;
            $cartItem->save();
        } else {
            ShoppingCartLog::create([
                'cart_log_session' => $sessionId,
                'item_name' => $variant->product->title . ' (' . $variant->sku . ')',
                'item_qty' => $qtyToAdd,
                'item_price' => $price,
                'item_discount_price' => $discountPrice,
                'item_attributes' => $variant->attributes ?? '',
                'item_shippable' => $variant->shipping,
                'item_weight' => $variant->weight ?? 0,
                'item_taxable' => $this->resolveItemTaxable($variant, $variant->product),
                'item_downloadable' => $variant->download_item,
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
            itemName: $variant->product->title . ' (' . $variant->sku . ')',
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

        // Calculate maximum catalog item price for range slider
        $catalogMaxPrice = (float) (ProductVariant::max(DB::raw("CASE WHEN on_sale = 1 AND sale_price > 0 THEN sale_price ELSE {$priceCol} END")) ?? 500);
        if ($catalogMaxPrice <= 0) {
            $catalogMaxPrice = 500;
        }

        // ── Base query (shared scope) ────────────────────────────────────────
        $baseQuery = Product::query()
            ->when($this->category, function ($query) {
                $categoryModel = Category::where('slug', $this->category)->first();
                if ($categoryModel) {
                    $categoryIds = $categoryModel->descendantsAndSelf()->pluck('id');
                    $query->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('product_categories.id', $categoryIds);
                    });
                }
            })
            ->when($this->brand, function ($query) {
                $query->whereHas('brand', function ($q) {
                    $q->where('slug', $this->brand);
                });
            })
            ->when($this->search, function ($query) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($sub) use ($searchTerm) {
                    $sub->where('title', 'like', $searchTerm)
                        ->orWhere('short_description', 'like', $searchTerm)
                        ->orWhere('long_description', 'like', $searchTerm)
                        ->orWhereHas('brand', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        })
                        ->orWhereHas('categories', function ($q) use ($searchTerm) {
                            $q->where('name', 'like', $searchTerm);
                        })
                        ->orWhereHas('variants', function ($q) use ($searchTerm) {
                            $q->where('sku', 'like', $searchTerm);
                        });
                });
            })
            // Advanced Multi-Brand Filter (checkboxes)
            ->when(!empty($this->selectedBrands), function ($query) {
                $query->whereIn('brand_id', $this->selectedBrands);
            })
            // Advanced Multi-Category / Subcategory Filter (checkboxes)
            ->when(!empty($this->selectedCategories), function ($query) {
                $cats = Category::whereIn('id', $this->selectedCategories)->get();
                $allCatIds = collect();
                foreach ($cats as $cat) {
                    $allCatIds = $allCatIds->merge($cat->descendantsAndSelf()->pluck('id'));
                }
                $allCatIds = $allCatIds->unique();
                $query->whereHas('categories', function ($q) use ($allCatIds) {
                    $q->whereIn('product_categories.id', $allCatIds);
                });
            })
            // Price Range Slider Filter
            ->when(($this->minPriceFilter !== null || $this->maxPriceFilter !== null), function ($query) use ($priceCol, $userType, $catalogMaxPrice) {
                $minP = (float) ($this->minPriceFilter ?? 0);
                $maxP = (float) ($this->maxPriceFilter ?? $catalogMaxPrice);
                $query->whereHas('variants', function ($vQuery) use ($priceCol, $minP, $maxP, $userType) {
                    if ($userType !== 2) {
                        $vQuery->where(function ($sub) use ($priceCol, $minP, $maxP) {
                            $sub->where(function ($s1) use ($minP, $maxP) {
                                $s1->where('on_sale', 1)->where('sale_price', '>', 0)
                                   ->whereBetween('sale_price', [$minP, $maxP]);
                            })->orWhere(function ($s2) use ($priceCol, $minP, $maxP) {
                                $s2->where(function ($s3) {
                                    $s3->where('on_sale', 0)->orWhereNull('sale_price')->orWhere('sale_price', 0);
                                })->whereBetween($priceCol, [$minP, $maxP]);
                            });
                        });
                    } else {
                        $vQuery->whereBetween($priceCol, [$minP, $maxP]);
                    }
                });
            })
            // Dynamic Variant Attributes JSON Filter
            ->when(!empty($this->selectedAttributes), function ($query) {
                foreach ($this->selectedAttributes as $attrKey => $attrVals) {
                    if (is_bool($attrVals) || empty($attrVals)) continue;
                    $attrVals = (array) $attrVals;
                    $query->whereHas('variants', function ($vQuery) use ($attrKey, $attrVals) {
                        $vQuery->where(function ($sub) use ($attrKey, $attrVals) {
                            foreach ($attrVals as $val) {
                                if (is_bool($val) || is_array($val)) continue;
                                $val = trim((string) $val);
                                if ($val === '') continue;
                                $sub->orWhere('attributes', 'like', '%"' . $attrKey . '":"' . $val . '"%')
                                    ->orWhere('attributes', 'like', '%' . $attrKey . ':' . $val . '%')
                                    ->orWhere('attributes', 'like', '%' . $val . '%');
                            }
                        });
                    });
                }
            });

        // ── Paginated product list with dynamic sorting ─────────────────────
        $sortPriceSubquery = '(SELECT MIN(CASE WHEN on_sale = 1 AND sale_price > 0 THEN sale_price ELSE public_price END) FROM product_variants WHERE product_variants.product_id = products.id)';
        $sortRatingSubquery = 'COALESCE((SELECT AVG(rating) FROM product_reviews WHERE product_reviews.product_id = products.id AND approved = 1), products.reviews_rating, 0)';

        $productsQuery = (clone $baseQuery)->with(['variants.inventory', 'variants.images']);

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

        $products = $productsQuery->withCurrentTranslations()->paginate($this->perPage);

        // ── Filter panel data ───────────────────────────────────────────────
        $filterCategories = collect();
        $filterBrands     = collect();

        if (!$this->category) {
            $productIds = (clone $baseQuery)->pluck('id');

            $assignedCategoryIds = \DB::table('product_categories_assignments')
                ->whereIn('product_id', $productIds)
                ->pluck('category_id')
                ->unique();

            $assignedCategories = Category::with(['ancestors', 'children'])
                ->whereIn('id', $assignedCategoryIds)
                ->where('is_visible_in_menu', true)
                ->get();

            $rootIds = $assignedCategories
                ->flatMap(fn($c) => $c->ancestors->isEmpty()
                    ? collect([$c->id])
                    : $c->ancestors->where('parent_id', null)->pluck('id')
                )
                ->merge(
                    $assignedCategories->where('parent_id', null)->pluck('id')
                )
                ->unique();

            $filterCategories = Category::withCurrentTranslations()->with(['children' => function ($q) {
                    $q->where('is_visible_in_menu', true)->orderBy('sort_order')->orderBy('name')->withCurrentTranslations();
                }, 'children.children' => function ($q) {
                    $q->where('is_visible_in_menu', true)->orderBy('sort_order')->orderBy('name')->withCurrentTranslations();
                }])
                ->whereIn('id', $rootIds)
                ->where('is_visible_in_menu', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->filter(function ($root) use ($assignedCategoryIds) {
                    $subtreeIds = $root->descendantsAndSelf()->pluck('id');
                    return $assignedCategoryIds->intersect($subtreeIds)->isNotEmpty();
                })
                ->map(function ($root) use ($assignedCategoryIds) {
                    $filteredChildren = $root->children->map(function ($child) use ($assignedCategoryIds) {
                        $filteredGrandchildren = $child->children
                            ->filter(fn($gc) => $gc->is_visible_in_menu && $assignedCategoryIds->contains($gc->id))
                            ->values();
                        $child->setRelation('children', $filteredGrandchildren);
                        return $child;
                    })->filter(function ($child) use ($assignedCategoryIds) {
                        return $child->is_visible_in_menu && ($assignedCategoryIds->contains($child->id) || $child->children->isNotEmpty());
                    })->values();

                    $root->setRelation('children', $filteredChildren);
                    return $root;
                })
                ->values();
        } elseif ($this->category) {
            $activeCategory = Category::withCurrentTranslations()
                ->where('slug', $this->category)
                ->where('is_visible_in_menu', true)
                ->with(['children' => function ($q) {
                    $q->where('is_visible_in_menu', true)->withCurrentTranslations();
                }, 'children.children' => function ($q) {
                    $q->where('is_visible_in_menu', true)->withCurrentTranslations();
                }])->first();
            if ($activeCategory && $activeCategory->children->isNotEmpty()) {
                $productIds = (clone $baseQuery)->pluck('id');
                $assignedCategoryIds = \DB::table('product_categories_assignments')
                    ->whereIn('product_id', $productIds)
                    ->pluck('category_id')
                    ->unique();

                $filterCategories = $activeCategory->children
                    ->filter(fn($child) => $child->is_visible_in_menu)
                    ->map(function ($child) use ($assignedCategoryIds) {
                        $filteredGrandchildren = $child->children
                            ->filter(fn($gc) => $gc->is_visible_in_menu && $assignedCategoryIds->contains($gc->id))
                            ->values();
                        $child->setRelation('children', $filteredGrandchildren);
                        return $child;
                    })
                    ->filter(function ($child) use ($assignedCategoryIds) {
                        $subtreeIds = $child->descendantsAndSelf()->pluck('id');
                        return $assignedCategoryIds->intersect($subtreeIds)->isNotEmpty();
                    })
                    ->values();
            }
        }

        if (!$this->brand) {
            $productIds = isset($productIds) ? $productIds : (clone $baseQuery)->pluck('id');
            $filterBrands = \App\Models\Brand::visibleInMenu()
                ->whereHas('products', function ($q) use ($productIds) {
                    $q->whereIn('products.id', $productIds);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'brand_icon', 'brand_logo_s3']);
        }

        // Available all brands and categories for Advanced Search Checkbox panel
        $allAvailableBrands = \App\Models\Brand::visibleInMenu()->orderBy('name')->get();
        $allAvailableCategories = \App\Models\Category::withCurrentTranslations()
            ->where('is_visible_in_menu', true)
            ->where(function($q) {
                $q->whereNull('parent_id')
                  ->orWhere('parent_id', 0)
                  ->orWhereDoesntHave('parent');
            })
            ->with(['children' => function ($q) {
                $q->where('is_visible_in_menu', true)->orderBy('sort_order')->orderBy('name')->withCurrentTranslations();
            }, 'children.children' => function ($q) {
                $q->where('is_visible_in_menu', true)->orderBy('sort_order')->orderBy('name')->withCurrentTranslations();
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Extract available variant JSON attributes
        $advancedSearchEnabled = \App\Models\CmsSetting::isAdvancedSearchEnabled();
        $availableVariantAttributes = [];
        if ($advancedSearchEnabled) {
            $variantAttributesRaw = ProductVariant::whereNotNull('attributes')
                ->where('attributes', '!=', '')
                ->pluck('attributes');

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
                                    $availableVariantAttributes[$aKey][$vItem] = true;
                                }
                            }
                        } else {
                            $aVal = trim((string)$aVal);
                            if ($aVal !== '') {
                                $availableVariantAttributes[$aKey][$aVal] = true;
                            }
                        }
                    }
                }
            }

            foreach ($availableVariantAttributes as $k => $vMap) {
                ksort($vMap);
                $availableVariantAttributes[$k] = array_keys($vMap);
                if (!isset($this->selectedAttributes[$k]) || !is_array($this->selectedAttributes[$k])) {
                    $this->selectedAttributes[$k] = [];
                }
            }
            ksort($availableVariantAttributes);
        }

        // Count active advanced filter badges
        $activeAttributeCount = 0;
        if (is_array($this->selectedAttributes)) {
            foreach ($this->selectedAttributes as $vals) {
                if (is_array($vals)) {
                    $activeAttributeCount += count(array_filter($vals, fn($v) => !is_bool($v) && is_string($v) && trim($v) !== ''));
                }
            }
        }

        $activeFilterCount = count($this->selectedBrands)
            + count($this->selectedCategories)
            + $activeAttributeCount
            + (($this->minPriceFilter !== null || $this->maxPriceFilter !== null) ? 1 : 0);

        // ── Resolve active filter models & compute page heading ──────────────
        $activeCategory = $this->category
            ? Category::withCurrentTranslations()->where('slug', $this->category)->first()
            : null;
        $activeBrand = $this->brand
            ? Brand::where('slug', $this->brand)->first()
            : null;

        $categoryTitle = $activeCategory
            ? $activeCategory->ancestorsAndSelf()->withCurrentTranslations()->get()->pluck('name')->reverse()->implode(' › ')
            : '';

        $defaultDescription = siteLabel('catalog.page_description', 'Browse our curated catalog. Enjoy exclusive wholesale pricing if eligible.');

        if ($activeCategory && $activeBrand) {
            $pageTitle       = $categoryTitle . ' › ' . $activeBrand->name;
            $pageDescription = '';
        } elseif ($activeCategory) {
            $pageTitle       = $categoryTitle;
            $pageDescription = $activeCategory->description ?? $defaultDescription;
        } elseif ($activeBrand) {
            $pageTitle       = $activeBrand->name;
            $pageDescription = $activeBrand->description ?? $defaultDescription;
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

        return view('livewire.shop-catalog', [
            'products'                   => $products,
            'userType'                   => $userType,
            'filterCategories'           => $filterCategories,
            'filterBrands'               => $filterBrands,
            'allAvailableBrands'         => $allAvailableBrands,
            'allAvailableCategories'     => $allAvailableCategories,
            'selectedCategoryModels'     => $selectedCategoryModels,
            'availableVariantAttributes' => $availableVariantAttributes,
            'catalogMaxPrice'            => $catalogMaxPrice,
            'advancedSearchEnabled'      => $advancedSearchEnabled,
            'activeFilterCount'          => $activeFilterCount,
            'activeCategory'             => $activeCategory,
            'activeBrand'                => $activeBrand,
            'pageTitle'                  => $pageTitle,
            'pageDescription'            => $pageDescription,
            'viewMode'                   => $this->viewMode,
            'currencySymbol'             => \App\Services\CurrencyService::symbol(),
            'vatInclusive'               => \App\Services\CurrencyService::isVatInclusive(),
            'merchantVatRate'            => \App\Services\CurrencyService::merchantVatRate(),
        ])->layout('layouts.public', [
            'metaTitle' => $metaTitle,
            'title'     => $metaTitle,
        ]);
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
}
