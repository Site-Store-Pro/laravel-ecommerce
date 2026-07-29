<?php

namespace App\Livewire;

use App\Models\Language;
use App\Services\LanguageService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public function switchLanguage(string $code): void
    {
        app(LanguageService::class)->setLanguage($code);

        // During a Livewire AJAX action, request()->fullUrl() returns
        // /livewire/update (the endpoint), not the actual page URL.
        // The Referer header always points to the real page being viewed.
        $pageUrl = request()->headers->get('referer', url('/'));

        $this->redirect($pageUrl, navigate: true);
    }

    public function render(): View
    {
        $languages = Language::getSwitcherLanguages();
        $current   = app(LanguageService::class)->current();

        return view('livewire.language-switcher', [
            'languages' => $languages,
            'current'   => $current,
        ]);
    }
}
