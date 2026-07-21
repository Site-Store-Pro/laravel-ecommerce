<?php

namespace App\Livewire;

use App\Models\CmsDownload;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsDownloads extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterActive = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterActive(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $download = CmsDownload::findOrFail($id);
        $download->is_active = !$download->is_active;
        $download->save();

        session()->flash('status', 'Download ' . ($download->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function deleteDownload(int $id): void
    {
        $download = CmsDownload::findOrFail($id);

        // Delete local file if present
        if ($download->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($download->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($download->file_path);
        }
        if ($download->poster_image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($download->poster_image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($download->poster_image_path);
        }

        $download->delete();
        session()->flash('status', 'Download deleted successfully.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $downloads = CmsDownload::query()
            ->when($this->search, fn($q) =>
                $q->where('internal_name', 'like', '%' . $this->search . '%')
                  ->orWhere('link_label', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterActive !== '', fn($q) =>
                $q->where('is_active', (bool) $this->filterActive)
            )
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.admin-cms-downloads', compact('downloads'));
    }
}
