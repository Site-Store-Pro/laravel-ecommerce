<?php

namespace App\Livewire;

use Livewire\Component;

class KbSearchBar extends Component
{
    public string $query = '';
    public bool $isLanding = false;

    public function updatedQuery()
    {
        if ($this->isLanding) {
            $this->dispatch('kb-search-updated', query: $this->query);
        }
    }

    public function submit()
    {
        if (! $this->isLanding) {
            return redirect()->route('kb.index', ['search' => $this->query]);
        }
    }

    public function render()
    {
        return view('livewire.kb-search-bar');
    }
}
