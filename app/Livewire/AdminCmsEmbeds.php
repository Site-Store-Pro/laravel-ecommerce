<?php

namespace App\Livewire;

use App\Models\CmsEmbed;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsEmbeds extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filterType  = '';   // '' = all, '0'=YouTube, '1'=Vimeo, '2'=Other
    public string $filterActive = '';  // '' = all, '1'=active, '0'=inactive

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingFilterActive(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $embed = CmsEmbed::findOrFail($id);
        $embed->is_active = !$embed->is_active;
        $embed->save();

        session()->flash('status', 'Embed ' . ($embed->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function deleteEmbed(int $id): void
    {
        CmsEmbed::findOrFail($id)->delete();
        session()->flash('status', 'Code embed deleted successfully.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $embeds = CmsEmbed::query()
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterType !== '', fn($q) =>
                $q->where('embed_type', (int) $this->filterType)
            )
            ->when($this->filterActive !== '', fn($q) =>
                $q->where('is_active', (bool) $this->filterActive)
            )
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.admin-cms-embeds', compact('embeds'));
    }
}
