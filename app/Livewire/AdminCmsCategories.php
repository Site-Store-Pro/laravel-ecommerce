<?php

namespace App\Livewire;

use App\Models\CmsPagesCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsCategories extends Component
{
    use WithPagination;

    public string $name = '';
    public string $slug = '';
    public ?int $categoryId = null;
    public bool $isFormOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
    ];

    public function openForm(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $category = CmsPagesCategory::findOrFail($id);
            $this->categoryId = $id;
            $this->name = $category->name;
            $this->slug = $category->slug;
        } else {
            $this->categoryId = null;
            $this->name = '';
            $this->slug = '';
        }
        $this->isFormOpen = true;
    }

    public function updatedName(): void
    {
        if (!$this->categoryId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(): void
    {
        $this->validate();

        // Custom validation check for unique slug across CMS tables
        $isUnique = \App\Services\UniqueSlugCheck::isUnique($this->slug, 'category', $this->categoryId);
        if (!$isUnique) {
            $this->addError('slug', 'This slug is already in use by a page, category, or tag.');
            return;
        }

        if ($this->categoryId) {
            $category = CmsPagesCategory::findOrFail($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('status', 'Category updated successfully.');
        } else {
            CmsPagesCategory::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('status', 'Category created successfully.');
        }

        $this->isFormOpen = false;
        $this->resetPage();
    }

    public function deleteCategory(int $id): void
    {
        $category = CmsPagesCategory::findOrFail($id);
        $category->delete();
        session()->flash('status', 'Category deleted successfully.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
        $categories = CmsPagesCategory::orderBy('name', 'asc')->paginate(25);
        return view('livewire.admin-cms-categories', compact('categories'));
    }
}
