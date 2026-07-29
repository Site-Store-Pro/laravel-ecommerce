<?php

namespace App\Livewire;

use App\Models\CmsPagesTag;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsTags extends Component
{
    use WithPagination;

    public string $name = '';
    public string $slug = '';
    public ?int $tagId = null;
    public bool $isFormOpen = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
    ];

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
        $existing = \App\Models\CmsPagesTagTranslation::where('cms_pages_tag_id', $id)
            ->where('language_id', $this->tlLangId)
            ->first();
        $this->tlBuffer = $existing ? $existing->only(['name']) : [];
    }

    public function saveTlTag(int $id): void
    {
        if ($this->tlLangId === 0) { return; }
        \App\Models\CmsPagesTagTranslation::updateOrCreate(
            ['cms_pages_tag_id' => $id, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, ['translation_status' => 'reviewed', 'translated_at' => now()])
        );
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTlTag(int $id): void
    {
        if ($this->tlLangId === 0) { return; }
        $record = CmsPagesTag::findOrFail($id);
        $lang = \App\Models\Language::findOrFail($this->tlLangId);
        try {
            $svc = app(\App\Services\TranslationService::class);
            if (!empty($record->name)) {
                $this->tlBuffer['name'] = $svc->translateText($record->name, $lang->name, 'cms pages tag translation');
            }
            $this->dispatch('toast', message: 'AI translation ready — review and save.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function openForm(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $tag = CmsPagesTag::findOrFail($id);
            $this->tagId = $id;
            $this->name = $tag->name;
            $this->slug = $tag->slug;
            $this->loadTlFor($id);
        } else {
            $this->tagId = null;
            $this->name = '';
            $this->slug = '';
        }
        $this->isFormOpen = true;
    }

    public function updatedName(): void
    {
        if (!$this->tagId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function save(): void
    {
        $this->validate();

        // Custom validation check for unique slug across CMS tables
        $isUnique = \App\Services\UniqueSlugCheck::isUnique($this->slug, 'tag', $this->tagId);
        if (!$isUnique) {
            $this->addError('slug', 'This slug is already in use by a page, category, or tag.');
            return;
        }

        if ($this->tagId) {
            $tag = CmsPagesTag::findOrFail($this->tagId);
            $tag->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('status', 'Tag updated successfully.');
        } else {
            CmsPagesTag::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            session()->flash('status', 'Tag created successfully.');
        }

        $this->isFormOpen = false;
        $this->resetPage();
    }

    public function deleteTag(int $id): void
    {
        $tag = CmsPagesTag::findOrFail($id);
        $tag->delete();
        session()->flash('status', 'Tag deleted successfully.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
        $tags = CmsPagesTag::orderBy('name', 'asc')->paginate(25);
        $activeLanguages = \App\Models\Language::active()->where('is_default', false)->get();
        return view('livewire.admin-cms-tags', compact('tags', 'activeLanguages'));
    }
}
