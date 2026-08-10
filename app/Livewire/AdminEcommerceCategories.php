<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminEcommerceCategories extends Component
{
    use WithPagination, WithFileUploads;

    // Category Form State
    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $category_image = '';
    public $category_image_file = null;
    public ?int $parent_id = null;
    public int $sort_order = 0;
    public bool $is_visible_in_menu = true;
    public bool $display_label_in_plugins = true;
    public bool $display_image_in_plugins = true;

    // Image storage settings
    public int $category_image_s3 = 0;   // 0=Local, 1=Global S3, 2=Custom S3
    public string $category_image_cdn_url = '';
    public string $category_image_region = '';
    public string $category_image_bucket_name = '';
    public string $category_image_access_key_id = '';
    public string $category_image_secret_access_key = '';
    public string $category_image_direct_url = '';  // Direct URL (highest priority)

    // Search / Filter
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Selected category for showing assigned products
    public ?int $selectedCategoryIdForProducts = null;
    public string $selectedCategoryName = '';
    public array $categoryProducts = [];

    // Modes
    public bool $isEditing = false;
    public bool $isCreating = false;

    // Translation state
    public int $tlLangId = 0;
    public array $tlBuffer = [];

    public function mount(): void
    {
        // Category management is for admins only
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403, 'Unauthorized admin access.');
    }

    public function showProducts(int $categoryId, string $categoryName): void
    {
        $this->resetForm();
        $this->selectedCategoryIdForProducts = $categoryId;
        $this->selectedCategoryName = $categoryName;

        $category = Category::findOrFail($categoryId);
        $categoryIds = $category->descendantsAndSelf()->pluck('id');

        $this->categoryProducts = \App\Models\Product::whereHas('categories', function($q) use ($categoryIds) {
            $q->whereIn('product_categories.id', $categoryIds);
        })
        ->orderBy('title')
        ->get(['id', 'title'])
        ->toArray();
    }

    public function closeProductsList(): void
    {
        $this->selectedCategoryIdForProducts = null;
        $this->selectedCategoryName = '';
        $this->categoryProducts = [];
    }

    public function updatedName(string $value): void
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    private function resetForm(): void
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->category_image = '';
        $this->category_image_file = null;
        $this->parent_id = null;
        $this->sort_order = 0;
        $this->is_visible_in_menu = true;
        $this->display_label_in_plugins = true;
        $this->display_image_in_plugins = true;
        $this->isEditing = false;
        $this->isCreating = false;
        $this->category_image_s3 = 0;
        $this->category_image_cdn_url = '';
        $this->category_image_region = '';
        $this->category_image_bucket_name = '';
        $this->category_image_access_key_id = '';
        $this->category_image_secret_access_key = '';
        $this->category_image_direct_url = '';
        
        $this->selectedCategoryIdForProducts = null;
        $this->selectedCategoryName = '';
        $this->categoryProducts = [];
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function editCategory(int $id): void
    {
        $this->resetForm();
        $category = Category::findOrFail($id);
        
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->category_image = $category->category_image ?? '';
        $this->parent_id = $category->parent_id;
        $this->sort_order = $category->sort_order;
        $this->is_visible_in_menu = (bool) $category->is_visible_in_menu;
        $this->display_label_in_plugins = (bool) ($category->display_label_in_plugins ?? true);
        $this->display_image_in_plugins = (bool) ($category->display_image_in_plugins ?? true);
        $this->category_image_s3 = (int) ($category->category_image_s3 ?? 0);
        $this->category_image_cdn_url = $category->category_image_cdn_url ?? '';
        $this->category_image_region = $category->category_image_region ?? '';
        $this->category_image_bucket_name = $category->category_image_bucket_name ?? '';
        $this->category_image_access_key_id = $category->category_image_access_key_id ?? '';
        $this->category_image_secret_access_key = $category->category_image_secret_access_key ?? '';
        $this->category_image_direct_url = $category->category_image_direct_url ?? '';
        
        $this->isEditing = true;
        $this->loadTlFor($id);
    }

    public function selectTlLang(int $id): void
    {
        $this->tlLangId = $id;
        $this->tlBuffer = [];
        if ($this->categoryId) {
            $this->loadTlFor($this->categoryId);
        }
    }

    public function loadTlFor(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        $existing = \App\Models\CategoryTranslation::where('category_id', $modelId)
            ->where('language_id', $this->tlLangId)
            ->first();
        $this->tlBuffer = $existing ? $existing->only(['name', 'description']) : [];
    }

    public function saveTlCategory(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        \App\Models\CategoryTranslation::updateOrCreate(
            ['category_id' => $modelId, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, ['translation_status' => 'reviewed', 'translated_at' => now()])
        );
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTlCategory(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        $record = Category::findOrFail($modelId);
        $lang = Language::findOrFail($this->tlLangId);
        try {
            $svc = app(\App\Services\TranslationService::class);
            foreach (['name', 'description'] as $field) {
                if (!empty($record->$field)) {
                    $this->tlBuffer[$field] = $svc->translateText($record->$field, $lang->name, 'Category context');
                }
            }
            $this->dispatch('toast', message: 'AI translation ready — review and save.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function saveCategory(): void
    {
        $rules = [
            'name'                          => 'required|string|max:255',
            'slug'                          => 'required|string|max:255|unique:product_categories,slug,' . ($this->categoryId ?? 'NULL') . ',id',
            'category_image'                => 'nullable|string|max:2048',
            'category_image_file'           => 'nullable|image|max:4096',
            'parent_id'                     => 'nullable|integer|exists:product_categories,id',
            'sort_order'                    => 'required|integer',
            'is_visible_in_menu'            => 'required|boolean',
            'display_label_in_plugins'      => 'required|boolean',
            'display_image_in_plugins'      => 'required|boolean',
            'category_image_s3'             => 'required|integer',
            'category_image_cdn_url'        => 'nullable|url|max:500',
            'category_image_direct_url'     => 'nullable|url|max:1000',
            'category_image_region'         => 'nullable|string|max:100',
            'category_image_bucket_name'    => 'nullable|string|max:255',
            'category_image_access_key_id'  => 'nullable|string|max:255',
            'category_image_secret_access_key' => 'nullable|string|max:500',
        ];

        // Prevent setting parent_id to itself
        if ($this->categoryId && $this->parent_id == $this->categoryId) {
            $this->addError('parent_id', 'A category cannot be its own parent.');
            return;
        }

        $this->validate($rules);

        // ── Resolve final image path / URL ────────────────────────────────────
        // Priority: direct URL > file upload > existing value
        $finalImagePath = $this->category_image ?: null;

        if (!empty($this->category_image_direct_url)) {
            // Direct URL — store as-is, no file upload needed
            $finalImagePath = $this->category_image_direct_url;
        } elseif ($this->category_image_file) {
            // File upload — pick the correct disk
            if ($this->category_image_s3 == 2) {
                $diskName = 'custom_s3_categories_' . ($this->categoryId ?: 'new');
                config([
                    "filesystems.disks.{$diskName}" => [
                        'driver' => 's3',
                        'key'    => $this->category_image_access_key_id,
                        'secret' => $this->category_image_secret_access_key,
                        'region' => $this->category_image_region,
                        'bucket' => $this->category_image_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            } elseif ($this->category_image_s3 == 1) {
                $diskName = 's3';
            } else {
                $diskName = 'public';
            }

            $stored_path = $this->category_image_file->store('uploads/categories', $diskName);

            // Apply CDN prefix if provided
            if (!empty($this->category_image_cdn_url)) {
                $finalImagePath = rtrim($this->category_image_cdn_url, '/') . '/' . ltrim($stored_path, '/');
            } elseif ($diskName === 'public') {
                $finalImagePath = asset('storage/' . $stored_path);
            } else {
                $finalImagePath = $stored_path;
            }
        }

        $saveData = [
            'name'                             => $this->name,
            'slug'                             => $this->slug,
            'description'                      => $this->description ?: null,
            'category_image'                   => $finalImagePath,
            'parent_id'                        => $this->parent_id,
            'sort_order'                       => $this->sort_order,
            'is_visible_in_menu'               => $this->is_visible_in_menu,
            'display_label_in_plugins'         => $this->display_label_in_plugins,
            'display_image_in_plugins'         => $this->display_image_in_plugins,
            'category_image_s3'                => $this->category_image_s3,
            'category_image_cdn_url'           => $this->category_image_cdn_url ?: null,
            'category_image_region'            => $this->category_image_region ?: null,
            'category_image_bucket_name'       => $this->category_image_bucket_name ?: null,
            'category_image_access_key_id'     => $this->category_image_access_key_id ?: null,
            'category_image_secret_access_key' => $this->category_image_secret_access_key ?: null,
            'category_image_direct_url'        => $this->category_image_direct_url ?: null,
        ];

        if ($this->isEditing && $this->categoryId) {
            Category::findOrFail($this->categoryId)->update($saveData);
            session()->flash('status', 'Category updated successfully.');
        } else {
            Category::create($saveData);
            session()->flash('status', 'Category created successfully.');
        }

        $this->resetForm();
    }

    public function deleteCategory(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->delete(); // Cascades children delete
        session()->flash('status', 'Category and its descendants deleted successfully.');
        $this->resetForm();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        // Query visible / matching categories
        $categoriesQuery = Category::query();
        if ($this->search) {
            $categoriesQuery->where('name', 'like', '%' . $this->search . '%')
                           ->orWhere('slug', 'like', '%' . $this->search . '%');
        }

        $categories = $categoriesQuery->orderBy('sort_order')->paginate(25);

        // For parent categories dropdown (excluding current category in edit mode)
        $parentOptions = Category::query();
        if ($this->categoryId) {
            $parentOptions->where('id', '!=', $this->categoryId);
        }
        $parentOptions = $parentOptions->orderBy('sort_order')->get();

        // Get recursive tree view (top-level nodes)
        $treeQuery = Category::whereNull('parent_id');
        if ($this->search) {
            $treeQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }
        $categoryTree = $treeQuery->with('children')->orderBy('sort_order')->get();

        return view('livewire.admin-ecommerce-categories', [
            'categories' => $categories,
            'parentOptions' => $parentOptions,
            'categoryTree' => $categoryTree,
            'activeLanguages' => Language::active()->where('is_default', false)->get(),
        ]);
    }
}
