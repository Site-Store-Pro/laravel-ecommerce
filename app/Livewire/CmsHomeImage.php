<?php

namespace App\Livewire;

use App\Models\CmsPage;
use Livewire\Component;

class CmsHomeImage extends Component
{
    public function render()
    {
        $page = CmsPage::find(1);
        return view('livewire.cms-home-image', compact('page'));
    }
}
