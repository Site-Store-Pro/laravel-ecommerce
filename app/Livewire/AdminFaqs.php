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
