<?php

namespace App\Livewire;

use App\Models\KbArticle;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class KbArticleShow extends Component
{
    public KbArticle $article;
    public bool $hasVoted = false;

    public function mount(string $seo_link): void
    {
        $article = KbArticle::where('seo_link', $seo_link)
            ->where('article_active', 1)
            ->first();

        if (! $article) {
            abort(404);
        }

        $this->article = $article;
        $this->hasVoted = session()->has('kb_voted_' . $article->id);
    }

    public function rateArticle(int $value): void
    {
        if ($this->hasVoted || !in_array($value, [1, -1])) {
            return;
        }

        $this->article->increment('kb_rating', $value);
        session()->put('kb_voted_' . $this->article->id, true);
        $this->hasVoted = true;
    }

    public function render(): View
    {
        return view('livewire.kb-article-show')
            ->layoutData([
                'title'           => $this->article->title . ' | ' . config('app.name', 'Support Desk'),
                'metaDescription' => $this->article->meta_description
            ]);
    }
}
