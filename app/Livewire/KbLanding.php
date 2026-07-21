<?php

namespace App\Livewire;

use App\Models\KbArticle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class KbLanding extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[On('kb-search-updated')]
    public function updateSearch($query): void
    {
        $this->search = $query;
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $articles = KbArticle::query()
            ->where('article_active', 1)
            ->when($this->search, function ($query) {
                $query->where(function ($inner) {
                    $inner->where('title', 'like', '%' . $this->search . '%')
                          ->orWhere('article_content', 'like', '%' . $this->search . '%');
                })->orderBy('sort_order');
            }, function ($query) {
                $query->orderByDesc('kb_rating');
            })
            ->paginate(2);

        $categories = collect();
        if (! $this->search) {
            $categories = \App\Models\KbCategory::with(['articles' => function($q) {
                $q->where('article_active', 1)->orderBy('sort_order', 'asc');
            }])->orderBy('sort_order', 'asc')->get();
        }

        return view('livewire.kb-landing', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
