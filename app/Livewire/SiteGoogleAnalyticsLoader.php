<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CmsSetting;

class SiteGoogleAnalyticsLoader extends Component
{
    public string $gaId = '';

    public function mount(): void
    {
        $this->gaId = CmsSetting::get('google_analytics_id', '');
    }

    public function render()
    {
        return view('livewire.loaders.site-google-analytics-loader');
    }
}
