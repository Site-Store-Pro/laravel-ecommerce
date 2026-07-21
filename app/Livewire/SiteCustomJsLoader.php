<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CmsSetting;

class SiteCustomJsLoader extends Component
{
    public string $scripts = '';

    public function mount(): void
    {
        $this->scripts = CmsSetting::get('custom_js_loader', '');
    }

    public function render()
    {
        return view('livewire.loaders.site-custom-js-loader');
    }
}
