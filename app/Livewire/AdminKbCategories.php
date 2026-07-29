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

    // Translation state
    public int $tlLangId = 0;
    public array $tlBuffer = [];

    public function selectTlLang(int $id): void
    {
        $this->tlLangId = $id;
        $this->tlBuffer = [];
    }

    public function loadTlFor(int $id): void
    {
        if ($this->tlLangId === 0) { return; }
        $existing = \App\Models\KbCategoryTranslation::where('kb_category_id', $id)
            ->where('language_id', $this->tlLangId)
            ->first();
        $this->tlBuffer = $existing ? $existing->only(['name', 'description']) : [];
    }

    public function saveTlCategory(int $id): void
    {
        if ($this->tlLangId === 0) { return; }
        \App\Models\KbCategoryTranslation::updateOrCreate(
            ['kb_category_id' => $id, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, ['translation_status' => 'reviewed', 'translated_at' => now()])
        );
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTlCategory(int $id): void
    {
        if ($this->tlLangId === 0) { return; }
        $record = KbCategory::findOrFail($id);
        $lang = \App\Models\Language::findOrFail($this->tlLangId);
        try {
            $svc = app(\App\Services\TranslationService::class);
            foreach (['name', 'description'] as $field) {
                if (!empty($record->$field)) {
                    $this->tlBuffer[$field] = $svc->translateText($record->$field, $lang->name, 'kb category translation');
                }
            }
            $this->dispatch('toast', message: 'AI translation ready — review and save.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

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
        $this->loadTlFor($id);
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

        $activeLanguages = \App\Models\Language::active()->where('is_default', false)->get();

        return view('livewire.admin-kb-categories', compact('categories', 'activeLanguages'));
    }
}
