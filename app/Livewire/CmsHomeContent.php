<?php

namespace App\Livewire;

use App\Models\CmsPage;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;

class CmsHomeContent extends Component
{
    public function render()
    {
        $page = CmsPage::find(1);
        $content = '';

        if ($page) {
            $heroTitle = $page->title ?: 'Everything you need to support & shop';
            $heroContent = '<p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">Submit support tickets, browse physical and digital merchandise, download your orders instantly, and resolve queries using our responsive Knowledge Base.</p>';
            
            try {
                $content = Blade::render($page->content, [
                    'heroTitle' => $heroTitle,
                    'heroContent' => $heroContent,
                ]);
            } catch (\Throwable $e) {
                $content = $page->content;
            }
        }

        return view('livewire.cms-home-content', compact('content'));
    }
}
