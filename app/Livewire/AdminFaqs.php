<?php

namespace App\Livewire;

use App\Models\CmsFaq;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminFaqs extends Component
{
    // ── List state ────────────────────────────────────────────────────────────
    public string $search = '';

    // ── Form fields ───────────────────────────────────────────────────────────
    public ?int    $faqId     = null;
    public string  $question  = '';
    public string  $answer    = '';
    public bool    $is_active = true;
    public int     $sort_order = 0;

    // ── UI state ──────────────────────────────────────────────────────────────
    public bool    $isCreating        = false;
    public bool    $isEditing         = false;
    public ?int    $confirmingDeleteId = null;

    // ── Translation state ──────────────────────────────────────────────────────
    public ?string $activeLangCode   = null;
    public ?int    $activeLangId     = null;
    public string  $trans_question   = '';
    public string  $trans_answer     = '';
    public string  $trans_status     = 'pending';
    public ?string $trans_translated_at = null;

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    public function updatingSearch(): void
    {
        // live search resets nothing — we load all (no pagination) to keep drag-sort working
    }

    // ── Form helpers ──────────────────────────────────────────────────────────

    private function resetForm(): void
    {
        $this->faqId      = null;
        $this->question   = '';
        $this->answer     = '';
        $this->is_active  = true;
        $this->sort_order = 0;
        $this->isCreating = false;
        $this->isEditing  = false;

        $this->activeLangCode   = null;
        $this->activeLangId     = null;
        $this->trans_question   = '';
        $this->trans_answer     = '';
        $this->trans_status     = 'pending';
        $this->trans_translated_at = null;

        $this->resetErrorBag();
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->sort_order = (int) (CmsFaq::max('sort_order') ?? 0) + 1;
        $this->isCreating = true;
    }

    public function editFaq(int $id): void
    {
        $this->resetForm();
        $faq = CmsFaq::findOrFail($id);

        $this->faqId      = $faq->id;
        $this->question   = $faq->question;
        $this->answer     = $faq->answer;
        $this->is_active  = (bool) $faq->is_active;
        $this->sort_order = $faq->sort_order;
        $this->isEditing  = true;

        $activeLangs = \App\Models\Language::where('is_active', true)->where('is_default', false)->get();
        if ($activeLangs->isNotEmpty()) {
            $first = $activeLangs->first();
            $this->selectTranslationLang($first->code, $first->id);
        }
    }

    public function selectTranslationLang(string $code, int $id): void
    {
        $this->activeLangCode = $code;
        $this->activeLangId   = $id;

        if (!$this->faqId) return;

        $t = \App\Models\CmsFaqTranslation::where('cms_faq_id', $this->faqId)
            ->where('language_id', $id)
            ->first();

        if ($t) {
            $this->trans_question     = $t->question ?? '';
            $this->trans_answer       = $t->answer ?? '';
            $this->trans_status       = $t->translation_status ?? 'pending';
            $this->trans_translated_at = $t->translated_at ? $t->translated_at->format('M j, Y g:i A') : null;
        } else {
            $this->trans_question     = '';
            $this->trans_answer       = '';
            $this->trans_status       = 'pending';
            $this->trans_translated_at = null;
        }
    }

    public function saveTranslation(): void
    {
        if (!$this->faqId || !$this->activeLangId) return;

        \App\Models\CmsFaqTranslation::updateOrCreate(
            [
                'cms_faq_id'  => $this->faqId,
                'language_id' => $this->activeLangId,
            ],
            [
                'question'           => trim($this->trans_question),
                'answer'             => $this->trans_answer,
                'translation_status' => 'reviewed',
                'translated_at'      => now(),
            ]
        );

        $this->trans_status        = 'reviewed';
        $this->trans_translated_at = now()->format('M j, Y g:i A');
        $this->dispatch('toast', message: 'FAQ translation saved successfully.', type: 'success');
    }

    public function aiTranslateFaqInline(): void
    {
        if (!$this->faqId || !$this->activeLangId) return;

        $faq  = CmsFaq::find($this->faqId);
        $lang = \App\Models\Language::find($this->activeLangId);

        if (!$faq || !$lang) return;

        try {
            $svc      = app(\App\Services\TranslationService::class);
            $langName = $lang->name;

            if (!empty($faq->question)) {
                $this->trans_question = $svc->translateText($faq->question, $langName, 'FAQ question prompt');
            }
            if (!empty($faq->answer)) {
                $this->trans_answer = $svc->translateText($faq->answer, $langName, 'FAQ answer HTML content');
            }

            $this->trans_status = 'ai_translated';
            $this->dispatch('toast', message: 'AI FAQ translation generated — review fields below and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function autoTranslateFaq(): void
    {
        if (!$this->faqId || !$this->activeLangId) return;

        \App\Jobs\TranslateContentJob::dispatch(
            CmsFaq::class,
            $this->faqId,
            $this->activeLangId
        );

        $this->dispatch('toast', message: 'FAQ translation job queued for background processing.', type: 'success');
    }

    public function translateAllLanguages(): void
    {
        if (!$this->faqId) return;

        $languages = \App\Models\Language::where('is_active', true)->where('is_default', false)->get();
        if ($languages->isEmpty()) {
            $this->dispatch('toast', message: 'No active non-default languages found.', type: 'warning');
            return;
        }

        foreach ($languages as $lang) {
            \App\Jobs\TranslateContentJob::dispatch(CmsFaq::class, $this->faqId, $lang->id);
        }

        $this->dispatch('toast', message: $languages->count() . ' translation job(s) queued for FAQ.', type: 'success');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    // ── Save ─────────────────────────────────────────────────────────────────

    private function rules(): array
    {
        return [
            'question'   => 'required|string|max:1000',
            'answer'     => 'required|string',
            'is_active'  => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function saveFaq(): void
    {
        $this->validate($this->rules());

        $data = [
            'question'   => trim($this->question),
            'answer'     => $this->answer,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->isEditing && $this->faqId) {
            CmsFaq::findOrFail($this->faqId)->update($data);
            $this->dispatch('toast', type: 'success', message: 'FAQ updated.');
        } else {
            CmsFaq::create($data);
            $this->dispatch('toast', type: 'success', message: 'FAQ created.');
        }

        $this->resetForm();
    }

    // ── Toggle ────────────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $faq = CmsFaq::findOrFail($id);
        $faq->update(['is_active' => ! $faq->is_active]);
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteFaq(): void
    {
        if ($this->confirmingDeleteId) {
            CmsFaq::findOrFail($this->confirmingDeleteId)->delete();
            $this->dispatch('toast', type: 'success', message: 'FAQ deleted.');
            $this->confirmingDeleteId = null;
            $this->resetForm();
        }
    }

    // ── Drag-to-sort (Alpine.js + SortableJS calls this) ─────────────────────

    public function updateFaqOrder(array $order): void
    {
        foreach ($order as $position => $faqId) {
            CmsFaq::where('id', $faqId)->update(['sort_order' => $position + 1]);
        }
        $this->dispatch('toast', type: 'success', message: 'FAQ order saved.');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        $faqs = CmsFaq::ordered()
            ->when($this->search, function ($q) {
                $q->where('question', 'like', '%' . $this->search . '%')
                  ->orWhere('answer',   'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.admin-faqs', ['faqs' => $faqs]);
    }
}
