<?php

namespace App\Livewire;

use App\Models\KbCategory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class AdminKbCategories extends Component
{
    use WithPagination;

    public string $search = '';

    // Inline create/edit form state
    public ?int $editingId = null;
    public bool $creating = false;

    #[Validate]
    public string $name = '';

    #[Validate]
    public string $slug = '';

    #[Validate]
    public string $description = '';

    #[Validate]
    public int $sort_order = 0;

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\-_]+$/i',
                              Rule::unique('kb_categories', 'slug')->ignore($this->editingId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order'  => ['required', 'integer', 'min:0'],
        ];
    }

    public function updatedName(string $value): void
    {
        // Auto-generate slug only when creating a new category
        if ($this->creating && ! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    #[\Livewire\Attributes\On('start-creating')]
    public function startCreating(): void
    {
        $this->reset(['name', 'slug', 'description', 'sort_order', 'editingId']);
        $this->creating = true;
    }

    public function cancelForm(): void
    {
        $this->reset(['name', 'slug', 'description', 'sort_order', 'editingId', 'creating']);
    }

    public function save(): void
    {
        $this->validate();

        KbCategory::create([
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description ?: null,
            'sort_order'  => $this->sort_order,
        ]);

        $this->cancelForm();
        session()->flash('status', "Category '{$this->name}' created successfully.");
    }

    public function startEditing(int $id): void
    {
        $category = KbCategory::findOrFail($id);
        $this->editingId   = $category->id;
        $this->name        = $category->name;
        $this->slug        = $category->slug;
        $this->description = $category->description ?? '';
        $this->sort_order  = $category->sort_order;
        $this->creating    = true;
    }

    public function update(): void
    {
        $this->validate();

        $category = KbCategory::findOrFail($this->editingId);
        $category->update([
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description ?: null,
            'sort_order'  => $this->sort_order,
        ]);

        $this->cancelForm();
        session()->flash('status', "Category '{$this->name}' updated successfully.");
    }

    public function deleteCategory(int $id): void
    {
        $category = KbCategory::findOrFail($id);
        $name = $category->name;
        $category->delete();
        session()->flash('status', "Category '{$name}' has been deleted.");
    }

    public function render(): View
    {
        $categories = KbCategory::query()
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->withCount('articles')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(2);

        return view('livewire.admin-kb-categories', compact('categories'));
    }
}
