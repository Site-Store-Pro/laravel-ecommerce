<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminEcommerceCategories extends Component
{
    use WithPagination;

    // Category Form State
    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $parent_id = null;
    public int $sort_order = 0;
    public bool $is_visible_in_menu = true;

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
        $this->parent_id = null;
        $this->sort_order = 0;
        $this->is_visible_in_menu = true;
        $this->isEditing = false;
        $this->isCreating = false;
        
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
        $this->parent_id = $category->parent_id;
        $this->sort_order = $category->sort_order;
        $this->is_visible_in_menu = (bool) $category->is_visible_in_menu;
        
        $this->isEditing = true;
    }

    public function saveCategory(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug,' . ($this->categoryId ?? 'NULL') . ',id',
            'parent_id' => 'nullable|integer|exists:product_categories,id',
            'sort_order' => 'required|integer',
            'is_visible_in_menu' => 'required|boolean',
        ];

        // Prevent setting parent_id to itself
        if ($this->categoryId && $this->parent_id == $this->categoryId) {
            $this->addError('parent_id', 'A category cannot be its own parent.');
            return;
        }

        $this->validate($rules);

        if ($this->isEditing && $this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'name'              => $this->name,
                'slug'              => $this->slug,
                'description'       => $this->description ?: null,
                'parent_id'         => $this->parent_id,
                'sort_order'        => $this->sort_order,
                'is_visible_in_menu' => $this->is_visible_in_menu,
            ]);
            session()->flash('status', 'Category updated successfully.');
        } else {
            Category::create([
                'name'              => $this->name,
                'slug'              => $this->slug,
                'description'       => $this->description ?: null,
                'parent_id'         => $this->parent_id,
                'sort_order'        => $this->sort_order,
                'is_visible_in_menu' => $this->is_visible_in_menu,
            ]);
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
            'categoryTree' => $categoryTree
        ]);
    }
}
