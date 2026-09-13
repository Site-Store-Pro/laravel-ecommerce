<?php

namespace App\Livewire;

use App\Models\CmsPage;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCmsPages extends Component
{
    use WithPagination;

    public string $search = '';

    // Delete Page Modal Properties
    public bool $showDeleteModal = false;
    public ?int $deletePageId = null;
    public string $deletePageTitle = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $page = CmsPage::findOrFail($id);
        $page->is_active = !$page->is_active;
        $page->save();

        session()->flash('status', 'Page active status updated successfully.');
    }

    public function confirmDeletePage(int $id): void
    {
        if ($id === 1) {
            session()->flash('error', 'The default home page (ID = 1) cannot be deleted.');
            return;
        }

        $page = CmsPage::findOrFail($id);
        $this->deletePageId = $page->id;
        $this->deletePageTitle = $page->title;
        $this->showDeleteModal = true;
    }

    public function cancelDeletePage(): void
    {
        $this->showDeleteModal = false;
        $this->deletePageId = null;
        $this->deletePageTitle = '';
    }

    public function deletePage(?int $id = null): void
    {
        $targetId = $id ?? $this->deletePageId;
        if (!$targetId) {
            $this->cancelDeletePage();
            return;
        }

        if ($targetId === 1) {
            session()->flash('error', 'The default home page (ID = 1) cannot be deleted.');
            $this->cancelDeletePage();
            return;
        }

        $page = CmsPage::find($targetId);
        if ($page) {
            $pageTitle = $page->title;
            $page->delete();
            session()->flash('status', "Page '{$pageTitle}' deleted successfully.");
            $this->dispatch('toast', type: 'success', message: "Page '{$pageTitle}' deleted successfully.");
        }

        $this->cancelDeletePage();
    }

    public function duplicatePage(int $id): void
    {
        $originalPage = CmsPage::findOrFail($id);

        // Generate unique random string
        $randomStr = strtolower(\Illuminate\Support\Str::random(5));
        $newTitle = $originalPage->title . ' ' . strtoupper($randomStr);
        $newSlug = $originalPage->slug . '-' . $randomStr;

        while (!\App\Services\UniqueSlugCheck::isUnique($newSlug, 'page')) {
            $randomStr = strtolower(\Illuminate\Support\Str::random(5));
            $newSlug = $originalPage->slug . '-' . $randomStr;
        }

        $data = $originalPage->toArray();
        unset($data['id'], $data['created_at'], $data['updated_at']);
        $data['title'] = $newTitle;
        $data['slug'] = $newSlug;

        $newPage = CmsPage::create($data);

        // Sync category & tags
        $newPage->categories()->sync($originalPage->categories()->pluck('category_id')->toArray());
        $newPage->tags()->sync($originalPage->tags()->pluck('tag_id')->toArray());

        session()->flash('status', 'Page duplicated successfully.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $hasDemoContent = \App\Services\DemoPurgeService::hasDemoContent();

        $pages = CmsPage::query()
            ->when(!$hasDemoContent, function ($query) {
                $query->where('id', '!=', 13);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->with('author')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('livewire.admin-cms-pages', compact('pages'));
    }
}
