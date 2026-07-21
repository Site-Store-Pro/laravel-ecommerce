<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductInventory;
use Illuminate\Contracts\View\View;
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

    public function render(): View
    {
        $products = Product::with(['variants.images'])
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('short_description', 'like', '%' . $this->search . '%')
                        ->orWhere('long_description', 'like', '%' . $this->search . '%')
                        ->orWhere('seo_slug', 'like', '%' . $this->search . '%')
                        ->orWhereHas('variants', function ($variantQuery) {
                            $variantQuery->where('sku', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(25);

        $recentlyEdited = Product::with(['variants.images'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        $categoryTree = \App\Models\Category::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();

        return view('livewire.admin-products', [
            'products' => $products,
            'expandedProducts' => $this->expandedProducts,
            'categoryTree' => $categoryTree,
            'brands' => $brands,
            'recentlyEdited' => $recentlyEdited
        ]);
    }
}
