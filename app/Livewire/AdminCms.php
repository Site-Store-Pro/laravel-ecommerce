<?php

namespace App\Livewire;

use App\Models\Cms;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCms extends Component
{
    public Cms $cms;

    public string $title = '';
    public string $content = '';
    public string $meta_title = '';
    public string $meta_description = '';

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403, 'Unauthorized e-commerce admin access.');

        $this->cms = Cms::firstOrCreate(
            ['label' => 'home_page'],
            [
                'title' => 'Everything you need to support & shop',
                'content' => '<p class="text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">Submit support tickets, browse physical and digital merchandise, download your orders instantly, and resolve queries using our responsive Knowledge Base.</p>',
                'meta_title' => 'Welcome to Support & Shop',
                'meta_description' => 'Submit support tickets, browse physical and digital merchandise, download your orders instantly, and resolve queries.',
            ]
        );

        $this->title = $this->cms->title ?? '';
        $this->content = $this->cms->content ?? '';
        $this->meta_title = $this->cms->meta_title ?? '';
        $this->meta_description = $this->cms->meta_description ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $this->cms->update([
            'title' => $this->title,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        session()->flash('status', 'Home page CMS content saved successfully.');
    }

    public function render(): View
    {
        return view('livewire.admin-cms');
    }
}
