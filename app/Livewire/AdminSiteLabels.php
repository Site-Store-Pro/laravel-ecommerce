<?php

namespace App\Livewire;

use App\Models\Language;
use App\Models\SiteLabel;
use App\Models\SiteLabelSection;
use App\Models\SiteLabelTranslation;
use App\Services\LanguageService;
use App\Services\SiteLabelService;
use App\Services\TranslationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSiteLabels extends Component
{
    use WithPagination;

    // ── Filters ───────────────────────────────────────────────────────────────
    public int    $activeSection   = 0;    // 0 = All sections
    public string $search          = '';
    public bool   $showCustomOnly  = false;
    public int    $perPage         = 25;

    /** Language being viewed/edited. 0 = default language. */
    public int $selectedLanguageId = 0;

    // ── Inline edit ───────────────────────────────────────────────────────────
    /** Buffer of { id => draft value } while the admin is typing. */
    public array $editBuffer = [];

    // ── Flash ─────────────────────────────────────────────────────────────────
    public string $flashMessage = '';
    public string $flashType    = 'success';   // success | error

    protected SiteLabelService $svc;

    public function boot(SiteLabelService $svc): void
    {
        $this->svc = $svc;
    }

    public function updatedSearch(): void         { $this->resetPage(); }
    public function updatedActiveSection(): void  { $this->resetPage(); $this->editBuffer = []; }
    public function updatedShowCustomOnly(): void { $this->resetPage(); }
    public function updatedSelectedLanguageId(): void { $this->resetPage(); $this->editBuffer = []; }

    // ── Computed ──────────────────────────────────────────────────────────────

    #[Computed]
    public function sections(): \Illuminate\Database\Eloquent\Collection
    {
        return SiteLabelSection::orderBy('sort_order')->get();
    }

    #[Computed]
    public function languages(): \Illuminate\Database\Eloquent\Collection
    {
        return Language::active()->get();
    }

    #[Computed]
    public function isDefaultLanguage(): bool
    {
        $defaultId = app(LanguageService::class)->defaultId();
        return $this->selectedLanguageId === 0 || $this->selectedLanguageId === $defaultId;
    }

    #[Computed]
    public function labels(): LengthAwarePaginator
    {
        $q = SiteLabel::query()
            ->with('section')
            ->orderBy('section_id')
            ->orderBy('label_key');

        // Eager-load the translation for the selected language (if non-default)
        if (!$this->isDefaultLanguage) {
            $langId = $this->selectedLanguageId;
            $q->with(['translations' => fn ($tq) => $tq->where('language_id', $langId)]);
        }

        if ($this->activeSection > 0) {
            $q->where('section_id', $this->activeSection);
        }

        if ($this->showCustomOnly) {
            if ($this->isDefaultLanguage) {
                $q->whereNotNull('label_custom')->where('label_custom', '!=', '');
            } else {
                // Show only labels that have been translated for this language
                $langId = $this->selectedLanguageId;
                $q->whereHas('translations', fn ($tq) => $tq->where('language_id', $langId)->whereNotNull('label_value'));
            }
        }

        if ($this->search !== '') {
            $term = '%' . $this->search . '%';
            $q->where(function ($sub) use ($term) {
                $sub->where('label_key', 'like', $term)
                    ->orWhere('label_description', 'like', $term)
                    ->orWhere('label_default', 'like', $term)
                    ->orWhere('file_name', 'like', $term);
            });
        }

        return $q->paginate($this->perPage);
    }

    /** Count per section for sidebar badges. */
    #[Computed]
    public function sectionCounts(): array
    {
        return SiteLabel::selectRaw('section_id, count(*) as cnt')
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id')
            ->toArray();
    }

    /** Count of labels with custom overrides per section. */
    #[Computed]
    public function customCounts(): array
    {
        return SiteLabel::selectRaw('section_id, count(*) as cnt')
            ->whereNotNull('label_custom')
            ->where('label_custom', '!=', '')
            ->groupBy('section_id')
            ->pluck('cnt', 'section_id')
            ->toArray();
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    /** Save a label override for the DEFAULT language. */
    public function saveLabel(int $id): void
    {
        $label = SiteLabel::findOrFail($id);
        $value = trim($this->editBuffer[$id] ?? '');

        $label->update([
            'label_custom' => $value !== '' ? $value : null,
            'last_updated' => now(),
        ]);

        unset($this->editBuffer[$id]);
        $this->svc->clearDefaultCache();
        $this->flash('Label saved.', 'success');
    }

    /** Save a translated override for a NON-default language. */
    public function saveTranslation(int $id): void
    {
        $value  = trim($this->editBuffer[$id] ?? '');
        $langId = $this->selectedLanguageId;

        SiteLabelTranslation::updateOrCreate(
            ['site_label_id' => $id, 'language_id' => $langId],
            [
                'label_value'        => $value !== '' ? $value : null,
                'translation_status' => 'reviewed',
                'translated_at'      => now(),
            ]
        );

        unset($this->editBuffer[$id]);
        $this->svc->clearCache($langId);
        $this->flash('Translation saved.', 'success');
    }

    /** Clear the DEFAULT language custom override. */
    public function clearLabel(int $id): void
    {
        $label = SiteLabel::findOrFail($id);
        $label->update(['label_custom' => null, 'last_updated' => now()]);
        unset($this->editBuffer[$id]);

        $this->svc->clearDefaultCache();
        $this->flash('Custom override removed.', 'success');
    }

    /** Clear a NON-default language translation. */
    public function clearTranslation(int $id): void
    {
        $langId = $this->selectedLanguageId;
        SiteLabelTranslation::where('site_label_id', $id)
            ->where('language_id', $langId)
            ->delete();
        unset($this->editBuffer[$id]);

        $this->svc->clearCache($langId);
        $this->flash('Translation removed — will fall back to default language.', 'success');
    }

    public function resetSection(): void
    {
        $q = SiteLabel::query();
        if ($this->activeSection > 0) {
            $q->where('section_id', $this->activeSection);
        }
        $q->update(['label_custom' => null, 'last_updated' => now()]);

        $this->editBuffer = [];
        $this->svc->clearAllCache();
        $this->flash('All custom overrides in this section have been reset.', 'success');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function flash(string $message, string $type = 'success'): void
    {
        $this->flashMessage = $message;
        $this->flashType    = $type;
    }

    public function clearFlash(): void
    {
        $this->flashMessage = '';
    }

    /**
     * AI-translate a single label for the selected non-default language.
     * Result is placed in the edit buffer so the admin can review before saving.
     */
    public function aiTranslateLabelInline(int $id): void
    {
        if ($this->isDefaultLanguage) return;

        $label = SiteLabel::findOrFail($id);
        $lang  = Language::findOrFail($this->selectedLanguageId);

        $source = $label->resolve();   // effective default-language text
        if (empty($source)) {
            $this->flash('Label has no source text to translate.', 'error');
            return;
        }

        try {
            $translated = app(TranslationService::class)
                ->translateText($source, $lang->name, 'short UI label text');

            $this->editBuffer[$id] = $translated;
            $this->flash('AI translation ready — review and save.', 'success');
        } catch (\Throwable $e) {
            $this->flash('AI translation failed: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * AI-translate ALL labels on the current visible page for the selected language.
     * Results are placed in the edit buffer for review.
     */
    public function aiTranslateSectionInline(): void
    {
        if ($this->isDefaultLanguage) return;

        $lang = Language::findOrFail($this->selectedLanguageId);

        $translated = 0;
        $failed     = 0;

        try {
            $svc = app(TranslationService::class);

            foreach ($this->labels as $label) {
                $source = $label->resolve();
                if (empty($source)) continue;

                try {
                    $this->editBuffer[$label->id] = $svc->translateText(
                        $source,
                        $lang->name,
                        'short UI label text'
                    );
                    $translated++;
                } catch (\Throwable) {
                    $failed++;
                }
            }

            $this->flash(
                "AI translated {$translated} labels" . ($failed ? " ({$failed} failed)" : '') . ' — review and save all.',
                $failed === 0 ? 'success' : 'error'
            );
        } catch (\Throwable $e) {
            $this->flash('AI translation failed: ' . $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        // Pre-populate editBuffer with existing saved values for every row on the
        // current page. Without this, Alpine hydration sets wire:model-bound
        // textareas to the empty buffer value (overriding the server-rendered
        // content), so clicking Save would write null and delete the saved data.
        //
        // The array_key_exists guard ensures we never overwrite text the admin
        // has already typed into the buffer during the current session.
        foreach ($this->labels->items() as $label) {
            if (array_key_exists($label->id, $this->editBuffer)) {
                continue; // Admin has unsaved edits — don't overwrite
            }

            if ($this->isDefaultLanguage) {
                // Populate with the existing custom override (skip blank rows)
                if ($label->label_custom !== null && $label->label_custom !== '') {
                    $this->editBuffer[$label->id] = $label->label_custom;
                }
            } else {
                // Populate with the existing translation (skip untranslated rows)
                $trans = $label->translations->first(); // eager-loaded in labels()
                if ($trans && !empty($trans->label_value)) {
                    $this->editBuffer[$label->id] = $trans->label_value;
                }
            }
        }

        return view('livewire.admin-site-labels')
            ->layout('layouts.app');
    }
}
