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
    public int $product_download_item = 0;
    public int $product_shipping = 1;
    public bool $active = true;
    public array $selectedCategories = [];
    public ?int $brand_id = null;

    // AI Content Generator
    public string $aiPrompt = '';
    public string $aiResponse = '';
    public bool $showAiButton = false;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isStaff(), 403, 'Unauthorized staff access.');
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
            'download_item' => $this->product_download_item,
            'shipping' => $this->product_shipping,
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

        return view('livewire.admin-product-create', [
            'categories' => $categories,
            'brands'     => $brands,
        ]);
    }
}
