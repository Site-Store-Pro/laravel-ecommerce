<?php

namespace App\Livewire;

use App\Livewire\Forms\KbArticleForm;
use App\Models\KbCategory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminKbCreate extends Component
{
    public KbArticleForm $form;

    public string $aiResponse = '';
    public string $aiPrompt = 'Please rewrite this kb article to be SEO optimized and concise';

    public function save(): void
    {
        $article = $this->form->store();

        session()->flash('status', "Article '{$article->title}' created successfully.");

        $this->redirectRoute('admin.kb.index', navigate: true);
    }

    public function generateAiContent(): void
    {
        $this->resetErrorBag('ai_content_error');

        if (empty(config('ai.openai_api_key')) || !function_exists('ai_kb_article_content')) {
            return;
        }

        if (blank($this->form->article_content)) {
            $this->addError('ai_content_error', 'Please write some content in the KB article editor first.');
            return;
        }

        $this->aiResponse = ai_kb_article_content($this->form->article_content, $this->aiPrompt);
    }

    public function render(): View
    {
        return view('livewire.admin-kb-create', [
            'categories' => KbCategory::orderBy('sort_order')->orderBy('name')->get(),
            'showAiButton' => !empty(config('ai.openai_api_key')) && function_exists('ai_kb_article_content'),
        ]);
    }
}
