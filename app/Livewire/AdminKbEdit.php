<?php

namespace App\Livewire;

use App\Livewire\Forms\KbArticleForm;
use App\Models\KbArticle;
use App\Models\KbCategory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminKbEdit extends Component
{
    public KbArticleForm $form;
    public KbArticle $article;

    public string $aiResponse = '';
    public string $aiPrompt = 'Please rewrite this kb article to be SEO optimized and concise';

    public function mount(KbArticle $article): void
    {
        $this->article = $article;
        $this->form->fillFromArticle($article);
    }

    public function save(): void
    {
        $this->form->update($this->article);

        $this->article->refresh();

        session()->flash('status', "Article '{$this->article->title}' updated successfully.");

        $this->redirectRoute('admin.kb.index', navigate: true);
    }

    public function deleteArticle(): void
    {
        $title = $this->article->title;
        $this->article->delete();

        session()->flash('status', "Article '{$title}' has been deleted.");

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
        return view('livewire.admin-kb-edit', [
            'categories' => KbCategory::orderBy('sort_order')->orderBy('name')->get(),
            'showAiButton' => !empty(config('ai.openai_api_key')) && function_exists('ai_kb_article_content'),
        ]);
    }
}
