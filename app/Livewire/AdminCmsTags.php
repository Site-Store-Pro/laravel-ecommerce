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

    public function openForm(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $tag = CmsPagesTag::findOrFail($id);
            $this->tagId = $id;
            $this->name = $tag->name;
            $this->slug = $tag->slug;
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
        return view('livewire.admin-cms-tags', compact('tags'));
    }
}
