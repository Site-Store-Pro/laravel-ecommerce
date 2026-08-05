<?php

namespace App\Livewire;

use App\Models\CmsModal;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminModalIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $confirmingDelete = false;
    public ?int $deletingId = null;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDelete = true;
        $this->deletingId       = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDelete = false;
        $this->deletingId       = null;
    }

    public function deleteModal(): void
    {
        if ($this->deletingId) {
            CmsModal::findOrFail($this->deletingId)->delete();
            $this->dispatch('toast', message: 'Modal deleted.', type: 'success');
        }
        $this->confirmingDelete = false;
        $this->deletingId       = null;
    }

    public function toggleActive(int $id): void
    {
        $modal = CmsModal::findOrFail($id);
        $modal->update(['is_active' => !$modal->is_active]);
        $this->dispatch('toast', message: 'Modal status updated.', type: 'success');
    }

    public function render()
    {
        $modals = CmsModal::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'))
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('livewire.admin-modal-index', compact('modals'));
    }
}
