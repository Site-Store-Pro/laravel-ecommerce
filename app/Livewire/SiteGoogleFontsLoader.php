<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CmsSetting;

class SiteGoogleFontsLoader extends Component
{
    public string $fontsUrl = '';

    public function mount(): void
    {
        $this->fontsUrl = CmsSetting::get('google_fonts_url', '');
    }

    public function render()
    {
        return view('livewire.loaders.site-google-fonts-loader');
    }
}
