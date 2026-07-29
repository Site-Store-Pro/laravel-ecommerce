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

    // Translation Management
    public string $activeLangCode = '';
    public ?int $activeLangId = null;
    public string $trans_title = '';
    public string $trans_article_content = '';
    public string $trans_meta_description = '';
    public string $trans_status = 'pending';
    public ?string $trans_translated_at = null;

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

    // ── Translation Management ─────────────────────────────────────────────────

    public function selectTranslationLang(string $code, int $langId): void
    {
        $this->activeLangCode = $code;
        $this->activeLangId = $langId;
        $this->loadKbTranslationData();
    }

    protected function loadKbTranslationData(): void
    {
        if (!isset($this->article) || !$this->activeLangId) return;

        $trans = \App\Models\KbArticleTranslation::where('kb_article_id', $this->article->id)
            ->where('language_id', $this->activeLangId)
            ->first();

        $this->trans_title           = $trans?->title ?? '';
        $this->trans_article_content = $trans?->article_content ?? '';
        $this->trans_meta_description = $trans?->meta_description ?? '';
        $this->trans_status          = $trans?->translation_status ?? 'pending';
        $this->trans_translated_at   = $trans?->translated_at?->format('M j, Y g:i A');
    }

    public function saveKbTranslation(): void
    {
        if (!isset($this->article) || !$this->activeLangId) return;

        \App\Models\KbArticleTranslation::updateOrCreate(
            ['kb_article_id' => $this->article->id, 'language_id' => $this->activeLangId],
            [
                'title'              => $this->trans_title ?: null,
                'article_content'    => $this->trans_article_content ?: null,
                'meta_description'   => $this->trans_meta_description ?: null,
                'translation_status' => 'reviewed',
                'translated_at'      => now(),
            ]
        );

        $this->trans_status        = 'reviewed';
        $this->trans_translated_at = now()->format('M j, Y g:i A');
        session()->flash('status', 'Translation saved.');
    }

    public function autoTranslateKb(): void
    {
        if (!isset($this->article) || !$this->activeLangId) return;

        \App\Jobs\TranslateContentJob::dispatch(
            \App\Models\KbArticle::class,
            $this->article->id,
            $this->activeLangId
        );

        session()->flash('status', 'Translation job queued. Refresh in a moment to see the results.');
    }

    /**
     * Inline AI translation — calls OpenAI synchronously and pre-fills all
     * KB article translation fields so the admin can review before saving.
     * The existing autoTranslateKb() bulk queue method is unchanged.
     */
    public function aiTranslateKbInline(): void
    {
        if (!isset($this->article) || !$this->activeLangId) return;

        $lang = \App\Models\Language::find($this->activeLangId);
        if (!$lang) return;

        try {
            $svc      = app(\App\Services\TranslationService::class);
            $langName = $lang->name;

            if (!empty($this->article->title)) {
                $this->trans_title = $svc->translateText($this->article->title, $langName, 'knowledge base article title');
            }
            if (!empty($this->article->article_content)) {
                $this->trans_article_content = $svc->translateText($this->article->article_content, $langName, 'knowledge base article body HTML — preserve all HTML tags');
            }
            if (!empty($this->article->meta_description)) {
                $this->trans_meta_description = $svc->translateText($this->article->meta_description, $langName, 'SEO meta description');
            }

            $this->trans_status = 'ai_translated';
            $this->dispatch('toast', message: 'AI translation ready — review all fields and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render(): View
    {
        $activeLanguages = \App\Models\Language::getAllActive()->where('is_default', false)->values();

        return view('livewire.admin-kb-edit', [
            'categories'      => KbCategory::orderBy('sort_order')->orderBy('name')->get(),
            'showAiButton'    => !empty(config('ai.openai_api_key')) && function_exists('ai_kb_article_content'),
            'activeLanguages' => $activeLanguages,
        ]);
    }
}
