<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminProductCreate extends Component
{
    public string $title = '';
    public string $short_description = '';
    public string $long_description = '';
    public string $bullet_point_1 = '';
    public string $bullet_point_2 = '';
    public string $bullet_point_3 = '';
    public string $bullet_point_4 = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $seo_slug = '';
    public bool $active = true;
    public array $selectedCategories = [];
    public ?int $brand_id = null;

    // AI Content Generator
    public string $aiPrompt = '';
    public string $aiResponse = '';
    public bool $showAiButton = false;

    // Drawers & Shortcodes
    public string $searchProduct = '';
    public string $searchBrand = '';
    public string $searchCategory = '';
    public string $searchPage = '';
    public string $shortcodeSearchQuery = '';
    public string $shortcodeSearchScope = 'all';

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
        $hasColumn = \Illuminate\Support\Facades\Cache::rememberForever('db_has_col_show_in_results', function () {
            return \Illuminate\Support\Facades\Schema::hasColumn('products', 'show_in_results');
        });
        if (!$hasColumn) {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\Cache::forget('db_has_col_show_in_results');
        }
        $this->showAiButton = !empty(config('ai.openai_api_key'));
    }

    public function updatedTitle(string $value): void
    {
        $this->seo_slug = \App\Helpers\SeoSlugHelper::generate($value);
    }

    public function updatedSeoSlug(string $value): void
    {
        $this->seo_slug = \App\Helpers\SeoSlugHelper::generate($value);
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
            $categoryNames = Category::whereIn('id', $this->selectedCategories)->pluck('name')->toArray();
        }

        $contextLines = [];
        $contextLines[] = "Product Title: " . ($this->title ?: 'N/A');
        $contextLines[] = "Categories: " . (!empty($categoryNames) ? implode(', ', $categoryNames) : 'N/A');
        $contextLines[] = "Short Description: " . ($this->short_description ?: 'N/A');
        $contextLines[] = "Current Long Description: " . ($this->long_description ?: 'N/A');

        $context = implode("\n", $contextLines);

        $this->aiResponse = ai_product_description_content($context, $this->aiPrompt);
    }

    public function saveProduct()
    {
        $this->seo_slug = \App\Helpers\SeoSlugHelper::generate($this->seo_slug ?: $this->title);

        $this->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'bullet_point_1' => 'nullable|string|max:255',
            'bullet_point_2' => 'nullable|string|max:255',
            'bullet_point_3' => 'nullable|string|max:255',
            'bullet_point_4' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'seo_slug' => 'required|string|max:255|unique:products,seo_slug',
            'brand_id' => 'nullable|integer|exists:product_brands,id',
        ]);

        $product = Product::create([
            'active' => $this->active ? 1 : 0,
            'show_in_results' => 1,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'bullet_point_1' => $this->bullet_point_1,
            'bullet_point_2' => $this->bullet_point_2,
            'bullet_point_3' => $this->bullet_point_3,
            'bullet_point_4' => $this->bullet_point_4,
            'meta_title' => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: $this->short_description,
            'seo_slug' => $this->seo_slug,
            'download_item' => 0,
            'shipping' => 1,
            'brand_id' => $this->brand_id,
        ]);

        $product->categories()->sync($this->selectedCategories);

        session()->flash('status', 'Product created! Please set the pricing for at least one variant.');

        return redirect()->route('admin.ecommerce.product-edit', $product->id);
    }

    public function render(): View
    {
        $categories = Category::all();
        $brands = Brand::orderBy('name')->get();
        $displayPlugins = \App\Models\Plugin::active()->ofType('display')->orderBy('name', 'asc')->get();

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
                $cats = \App\Models\Category::where('name', 'like', $q)->limit($categoriesLimit)->get();
                foreach ($cats as $c) {
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
                $brnds = \App\Models\Brand::where('name', 'like', $q)->limit($brandsLimit)->get();
                foreach ($brnds as $b) {
                    $shortcodeSearchResults[] = [
                        'type'       => 'Brand',
                        'id'         => $b->id,
                        'title'      => $b->name,
                        'badgeColor' => 'bg-rose-100 text-rose-800 border-rose-200',
                        'shortcode'  => '[brand:' . $b->id . ' label="' . e($b->name) . '"]',
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'downloads') {
                $downloads = \App\Models\Download::where('title', 'like', $q)->orWhere('filename', 'like', $q)->limit($downloadsLimit)->get();
                foreach ($downloads as $d) {
                    $label = !empty($d->title) ? $d->title : $d->filename;
                    $shortcodeSearchResults[] = [
                        'type'       => 'Download',
                        'id'         => $d->id,
                        'title'      => $label,
                        'badgeColor' => 'bg-teal-100 text-teal-800 border-teal-200',
                        'shortcode'  => '[download:' . $d->uuid . ' label="' . e($label) . '"]',
                    ];
                }
            }

            if (count($shortcodeSearchResults) > 25) {
                $shortcodeSearchResults = array_slice($shortcodeSearchResults, 0, 25);
            }
        }

        return view('livewire.admin-product-create', [
            'categories'             => $categories,
            'brands'                 => $brands,
            'displayPlugins'         => $displayPlugins,
            'searchedProducts'       => $searchedProducts,
            'searchedBrands'         => $searchedBrands,
            'searchedCategories'     => $searchedCategories,
            'searchedPages'          => $searchedPages,
            'shortcodeSearchResults' => $shortcodeSearchResults,
        ]);
    }
}
