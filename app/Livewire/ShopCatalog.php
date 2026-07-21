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

    public string $search = '';

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $brand = null;

    #[Url]
    public $perPage = 25;

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

    public function hydrate(): void
    {
        $this->sanitizePerPage();
    }

    public function mount(?string $category_slug = null, ?string $brand_slug = null): void
    {
        $this->sanitizePerPage();
        if ($category_slug) {
            $this->category = $category_slug;
        }
        if ($brand_slug) {
            $this->brand = $brand_slug;
        }
    }

    public function clearCategory(): mixed
    {
        $this->category = null;
        $this->resetPage();
        // If category was locked into the URL path via the shop.category route,
        // we must redirect away from that path. Preserve any active brand filter.
        if (request()->routeIs('shop.category')) {
            if ($this->brand) {
                return redirect()->route('shop.brand', ['brand_slug' => $this->brand]);
            }
            return redirect()->route('shop.index');
        }
        return null;
    }

    public function clearBrand(): mixed
    {
        $this->brand = null;
        $this->resetPage();
        // If brand was locked into the URL path via the shop.brand route,
        // we must redirect away from that path. Preserve any active category filter.
        if (request()->routeIs('shop.brand')) {
            if ($this->category) {
                return redirect()->route('shop.category', $this->category);
            }
            return redirect()->route('shop.index');
        }
        return null;
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

        if ($product && $product->fields->isNotEmpty()) {
            return redirect()->route('shop.product', $product->seo_slug);
        }

        $sessionId = $this->getCartSessionId();
        $userId = auth()->id() ?? 0;

        // Check inventory
        if (!$variant->download_item && $variant->inventory) {
            $available = $variant->getStockForFulfillment(
                auth()->user()?->shipping_countrycode,
                auth()->user()?->shipping_state
            );
            if ($available <= 0) {
                session()->flash('error', "Item is out of stock.");
                return;
            }
        }

        // Fetch user type
        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        // Calculate price based on user type & sale status
        $price = $userType == 2 ? $variant->wholesale_price : $variant->public_price;
        $discountPrice = 0;
        if ($userType != 2 && $variant->on_sale && $variant->sale_price > 0) {
            $discountPrice = $price - $variant->sale_price;
            $price = $variant->sale_price;
        }

        // Add variant_fee or wholesale_variant_fee if configured
        $variantFee = $userType == 2 ? $variant->wholesale_variant_fee : $variant->variant_fee;
        if ($variantFee > 0) {
            $price += $variantFee;
        }

        // Fetch current active cart items
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

        // A. Is there a standalone item already in the cart?
        if (!empty($skusInCart)) {
            $hasStandaloneInCart = \App\Models\ProductVariant::whereIn('sku', $skusInCart)
                ->whereHas('product', function ($q) {
                    $q->where('standalone_purchase', 1);
                })
                ->exists();

            if ($hasStandaloneInCart) {
                session()->flash('error', "Your cart contains a standalone item which cannot be purchased with other items.");
                return;
            }
        }

        // B. Is this item a standalone purchase, and the cart has OTHER items?
        if ($product && $product->standalone_purchase == 1 && $cartItems->isNotEmpty()) {
            $onlySameSku = true;
            foreach ($skusInCart as $skuInCart) {
                if ($skuInCart !== $variant->sku) {
                    $onlySameSku = false;
                    break;
                }
            }
            if (!$onlySameSku) {
                session()->flash('error', "This standalone item cannot be purchased with other items. Please empty your cart first.");
                return;
            }
        }

        // Check if item is already in cart
        $cartItem = ShoppingCartLog::where(function($query) use ($sessionId, $userId) {
            if ($userId > 0) {
                $query->where('user_id', $userId);
            } else {
                $query->where('cart_log_session', $sessionId)
                      ->where('user_id', 0);
            }
        })
        ->where('item_attributes', $variant->attributes) // match exactly by configuration
        ->where('order_id', 0)
        ->first();

        // C. If max_qty = 1, prevent adding it again if it exists
        if ($cartItem && $product && $product->max_qty == 1) {
            session()->flash('error', "You can only purchase a maximum of 1 unit of this item per order.");
            if ($product->checkout_redirect == 1 || $product->standalone_purchase == 1) {
                return redirect()->route('shop.checkout');
            }
            return;
        }

        $qtyToAdd = ($product && $product->max_qty == 1) ? 1 : 1;

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

        // checkout_redirect / standalone → go straight to checkout
        if ($product && ($product->checkout_redirect == 1 || $product->standalone_purchase == 1)) {
            return redirect()->route('shop.checkout');
        }

        // Fire browser event — the global modal in public.blade.php will display it.
        $this->dispatch('show-cart-modal',
            itemName: $variant->product->title . ' (' . $variant->sku . ')',
            qty: 1,
        );
    }

    public function render(): View
    {
        $this->sanitizePerPage();

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
            });

        // ── Paginated product list ───────────────────────────────────────────
        $products = (clone $baseQuery)
            ->with(['variants.inventory', 'variants.images'])
            ->latest()
            ->paginate($this->perPage);

        // ── Filter panel data (only when no active filter of that type) ──────
        $filterCategories = collect();
        $filterBrands     = collect();

        // Category drill-down: show when no category is active, or show
        // children of the active category when one is selected.
        if (!$this->category) {
            // All product IDs in the current result set (un-paginated)
            $productIds = (clone $baseQuery)->pluck('id');

            // Category IDs assigned to those products
            $assignedCategoryIds = \DB::table('product_categories_assignments')
                ->whereIn('product_id', $productIds)
                ->pluck('category_id')
                ->unique();

            // Load those categories with their ancestors so we can build the tree
            $assignedCategories = Category::with(['ancestors', 'children'])
                ->whereIn('id', $assignedCategoryIds)
                ->get();

            // Collect every root-level ancestor that appears in the result set
            $rootIds = $assignedCategories
                ->flatMap(fn($c) => $c->ancestors->isEmpty()
                    ? collect([$c->id])
                    : $c->ancestors->where('parent_id', null)->pluck('id')
                )
                ->merge(
                    $assignedCategories->where('parent_id', null)->pluck('id')
                )
                ->unique();

            // Build nested tree: root → children → grandchildren
            $filterCategories = Category::with(['children.children'])
                ->whereIn('id', $rootIds)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->filter(function ($root) use ($assignedCategoryIds) {
                    // Only include roots that have at least one assigned category in their subtree
                    $subtreeIds = $root->descendantsAndSelf()->pluck('id');
                    return $assignedCategoryIds->intersect($subtreeIds)->isNotEmpty();
                })
                ->map(function ($root) use ($assignedCategoryIds) {
                    // ── In-memory filter: children & grandchildren ──────────────
                    // Relations are already loaded via with(['children.children']),
                    // so no extra queries are fired here.
                    $filteredChildren = $root->children->map(function ($child) use ($assignedCategoryIds) {
                        // Filter grandchildren: only keep those directly assigned
                        $filteredGrandchildren = $child->children
                            ->filter(fn($gc) => $assignedCategoryIds->contains($gc->id))
                            ->values();
                        $child->setRelation('children', $filteredGrandchildren);
                        return $child;
                    })->filter(function ($child) use ($assignedCategoryIds) {
                        // Keep child if it is directly assigned OR has at least one valid grandchild
                        return $assignedCategoryIds->contains($child->id)
                            || $child->children->isNotEmpty();
                    })->values();

                    $root->setRelation('children', $filteredChildren);
                    return $root;
                })
                ->values();
        } elseif ($this->category) {
            // Active category: show its direct children as sub-filters
            $activeCategory = Category::where('slug', $this->category)->with('children.children')->first();
            if ($activeCategory && $activeCategory->children->isNotEmpty()) {
                $productIds = (clone $baseQuery)->pluck('id');
                $assignedCategoryIds = \DB::table('product_categories_assignments')
                    ->whereIn('product_id', $productIds)
                    ->pluck('category_id')
                    ->unique();

                $filterCategories = $activeCategory->children
                    ->map(function ($child) use ($assignedCategoryIds) {
                        // Filter grandchildren: only keep those directly assigned
                        $filteredGrandchildren = $child->children
                            ->filter(fn($gc) => $assignedCategoryIds->contains($gc->id))
                            ->values();
                        $child->setRelation('children', $filteredGrandchildren);
                        return $child;
                    })
                    ->filter(function ($child) use ($assignedCategoryIds) {
                        // Keep child if it is directly assigned OR has at least one valid grandchild
                        $subtreeIds = $child->descendantsAndSelf()->pluck('id');
                        return $assignedCategoryIds->intersect($subtreeIds)->isNotEmpty();
                    })
                    ->values();
            }
        }

        // Brand filter: show when no brand is active and multiple brands exist
        if (!$this->brand) {
            $productIds = isset($productIds) ? $productIds : (clone $baseQuery)->pluck('id');
            $filterBrands = \App\Models\Brand::whereHas('products', function ($q) use ($productIds) {
                    $q->whereIn('products.id', $productIds);
                })
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'brand_icon', 'brand_logo_s3']);
        }

        $userType = (auth()->check() && auth()->user()->isWholesale()) ? 2 : 1;

        // ── Resolve active filter models & compute page heading ──────────────
        $activeCategory = $this->category
            ? Category::where('slug', $this->category)->first()
            : null;
        $activeBrand = $this->brand
            ? Brand::where('slug', $this->brand)->first()
            : null;

        if ($activeCategory && $activeBrand) {
            // Both filters active → "Category Name > Brand Name", no subtitle
            $pageTitle       = $activeCategory->name . ' › ' . $activeBrand->name;
            $pageDescription = '';
        } elseif ($activeCategory) {
            $pageTitle       = $activeCategory->name;
            $pageDescription = $activeCategory->description ?? 'Browse our curated catalog. Enjoy exclusive wholesale pricing if eligible.';
        } elseif ($activeBrand) {
            $pageTitle       = $activeBrand->name;
            $pageDescription = $activeBrand->description ?? 'Browse our curated catalog. Enjoy exclusive wholesale pricing if eligible.';
        } else {
            $pageTitle       = 'E-Commerce Products';
            $pageDescription = 'Browse our curated catalog. Enjoy exclusive wholesale pricing if eligible.';
        }

        return view('livewire.shop-catalog', [
            'products'         => $products,
            'userType'         => $userType,
            'filterCategories' => $filterCategories,
            'filterBrands'     => $filterBrands,
            'activeCategory'   => $activeCategory,
            'activeBrand'      => $activeBrand,
            'pageTitle'        => $pageTitle,
            'pageDescription'  => $pageDescription,
            'viewMode'         => $this->viewMode,
            'currencySymbol'   => \App\Services\CurrencyService::symbol(),
            'vatInclusive'     => \App\Services\CurrencyService::isVatInclusive(),
            'merchantVatRate'  => \App\Services\CurrencyService::merchantVatRate(),
        ]);
    }

    /**
     * Determine if a cart item is taxable.
     * Returns 1 if the variant has charge_tax=1 OR if ANY product field
     * on the product has charge_tax=1 (OR logic — most permissive wins).
     */
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
