<?php

namespace App\Livewire;

use App\Models\KbArticle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminKbIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $article = KbArticle::findOrFail($id);
        $newStatus = $article->article_active === 1 ? 0 : 1;
        $article->update([
            'article_active' => $newStatus,
            'date_modified' => now()->format('Y-m-d H:i:s'),
        ]);

        $statusStr = $newStatus === 1 ? 'activated' : 'deactivated';
        session()->flash('status', "Article '{$article->title}' has been {$statusStr}.");
    }

    public function deleteArticle(int $id): void
    {
        $article = KbArticle::findOrFail($id);
        $title = $article->title;
        $article->delete();

        session()->flash('status', "Article '{$title}' has been deleted.");
    }

    public function render(): View
    {
        $articles = KbArticle::query()
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('article_content', 'like', '%' . $this->search . '%')
                          ->orWhere('seo_link', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('article_active', $this->statusFilter === 'active' ? 1 : 0);
            })
            ->orderBy('sort_order')
            ->orderByDesc('date_added')
            ->paginate(25);

        $totalCount = KbArticle::count();
        $activeCount = KbArticle::where('article_active', 1)->count();
        $inactiveCount = KbArticle::where('article_active', 0)->count();

        return view('livewire.admin-kb-index', [
            'articles' => $articles,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }
}
