<?php

namespace App\Livewire;

use App\Models\CmsPage;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CmsPageRating extends Component
{
    public int $pageId;
    public ?CmsPage $page = null;
    public bool $hasVoted = false;

    public function mount(int $pageId): void
    {
        $this->pageId = $pageId;
        $this->page = CmsPage::find($pageId);
        $this->hasVoted = session()->has('cms_page_voted_' . $pageId);
    }

    public function ratePage(int $value): void
    {
        if ($this->hasVoted || !in_array($value, [1, -1]) || !$this->page) {
            return;
        }

        $this->page->increment('page_ranking', $value);
        session()->put('cms_page_voted_' . $this->pageId, true);
        $this->hasVoted = true;
    }

    public function render(): View
    {
        return view('livewire.cms-page-rating');
    }
}
