<?php

namespace App\Livewire;

use App\Models\CmsForm;
use App\Models\CmsFormField;
use App\Models\CmsFormFieldTranslation;
use App\Models\CmsFormTranslation;
use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCmsFormEdit extends Component
{
    // ── Active Top Tab ────────────────────────────────────────────────────────
    public string $activeTab = 'form'; // 'form' or 'translations'

    // ── Form settings ─────────────────────────────────────────────────────────
    public ?int   $formId               = null;
    public string $name                 = '';
    public string $slug                 = '';
    public string $submit_button_label  = 'Submit';
    public string $custom_css           = '';
    public string $confirmation_message = 'Thank you! Your submission has been received.';
    public string $redirect_url         = '';
    public string $email_to             = '';
    public string $email_subject        = '';
    public bool   $is_active            = true;
    // Opt-in
    public bool   $auto_optin           = false;
    public string $optin_provider       = '';
    public string $optin_list_id        = '';

    // ── Fields builder ────────────────────────────────────────────────────────
    /** @var array<int, array<string, mixed>> */
    public array $fields = [];

    /** Index of the field currently being edited (-1 = none open) */
    public int $editingFieldIndex = -1;

    // ── Translation state ─────────────────────────────────────────────────────
    public int   $tlLangId       = 0;
    public array $tlFormBuffer   = [
        'name'                 => '',
        'submit_button_label'  => '',
        'confirmation_message' => '',
    ];
    /** @var array<int|string, array{label: string, instructions: string, required_error_message: string, html_above: string, options: array<string>}> */
    public array  $tlFieldsBuffer = [];
    public string $tlStatus       = '';
    public string $tlTranslatedAt = '';

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        if ($id) {
            $form = CmsForm::with('fields')->findOrFail($id);

            $this->formId               = $form->id;
            $this->name                 = $form->name;
            $this->slug                 = $form->slug;
            $this->submit_button_label  = $form->submit_button_label;
            $this->custom_css           = $form->custom_css ?? '';
            $this->confirmation_message = $form->confirmation_message ?? '';
            $this->redirect_url         = $form->redirect_url ?? '';
            $this->email_to             = $form->email_to ?? '';
            $this->email_subject        = $form->email_subject ?? '';
            $this->is_active            = $form->is_active;
            $this->auto_optin           = $form->auto_optin;
            $this->optin_provider       = $form->optin_provider ?? '';
            $this->optin_list_id        = $form->optin_list_id ?? '';

            $this->fields = $form->fields->map(fn ($f) => [
                'id'                    => $f->id,
                'type'                  => $f->type,
                'label'                 => $f->label,
                'instructions'          => $f->instructions ?? '',
                'is_required'           => $f->is_required,
                'required_type'         => $f->required_type ?? 'non_blank',
                'required_error_message'=> $f->required_error_message ?? '',
                'html_above'            => $f->html_above ?? '',
                'options'               => $f->options ?? [],
                'sort_order'            => $f->sort_order,
                'field_role'            => $f->field_role ?? '',
            ])->values()->toArray();
        }
    }

    // ── Slug auto-generation ──────────────────────────────────────────────────

    public function updatedName(string $value): void
    {
        if (! $this->formId) {
            // Auto-generate slug only on create
            $base = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $value));
            $this->slug = trim($base, '-');
        }
    }

    // ── Field builder actions ─────────────────────────────────────────────────

    public function addField(): void
    {
        $this->fields[] = [
            'id'                     => null,
            'type'                   => 'input',
            'label'                  => '',
            'instructions'           => '',
            'is_required'            => false,
            'required_type'          => 'non_blank',
            'required_error_message' => '',
            'html_above'             => '',
            'options'                => [],
            'sort_order'             => count($this->fields),
            'field_role'             => '',
        ];

        $this->editingFieldIndex = count($this->fields) - 1;
    }

    public function removeField(int $index): void
    {
        array_splice($this->fields, $index, 1);
        $this->reindexFields();

        if ($this->editingFieldIndex === $index) {
            $this->editingFieldIndex = -1;
        } elseif ($this->editingFieldIndex > $index) {
            $this->editingFieldIndex--;
        }
    }

    public function moveFieldUp(int $index): void
    {
        if ($index <= 0) return;
        [$this->fields[$index], $this->fields[$index - 1]] = [$this->fields[$index - 1], $this->fields[$index]];
        $this->reindexFields();
        if ($this->editingFieldIndex === $index) $this->editingFieldIndex--;
        elseif ($this->editingFieldIndex === $index - 1) $this->editingFieldIndex++;
    }

    public function moveFieldDown(int $index): void
    {
        if ($index >= count($this->fields) - 1) return;
        [$this->fields[$index], $this->fields[$index + 1]] = [$this->fields[$index + 1], $this->fields[$index]];
        $this->reindexFields();
        if ($this->editingFieldIndex === $index) $this->editingFieldIndex++;
        elseif ($this->editingFieldIndex === $index + 1) $this->editingFieldIndex--;
    }

    public function toggleEditField(int $index): void
    {
        $this->editingFieldIndex = ($this->editingFieldIndex === $index) ? -1 : $index;
    }

    public function addOption(int $fieldIndex): void
    {
        $this->fields[$fieldIndex]['options'][] = '';
    }

    public function removeOption(int $fieldIndex, int $optIndex): void
    {
        array_splice($this->fields[$fieldIndex]['options'], $optIndex, 1);
    }

    /** Called from JS via $wire.setFieldHtmlAbove(index, html) to sync TinyMCE content */
    public function setFieldHtmlAbove(int $index, string $html): void
    {
        if (isset($this->fields[$index])) {
            $this->fields[$index]['html_above'] = $html;
        }
    }

    // ── Save ──────────────────────────────────────────────────────────────────

    public function save(): void
    {
        $this->validate([
            'name'                 => 'required|string|max:255',
            'slug'                 => 'required|string|max:255|alpha_dash',
            'submit_button_label'  => 'required|string|max:100',
            'email_to'             => 'nullable|email|max:255',
            'redirect_url'         => 'nullable|url|max:500',
            'fields.*.label'       => 'required|string|max:255',
            'fields.*.type'        => 'required|in:input,textarea,select,radio,checkbox,checkbox_group',
        ], [
            'slug.alpha_dash'      => 'The slug may only contain letters, numbers, dashes, and underscores.',
            'fields.*.label.required' => 'Every field must have a label.',
        ]);

        // Enforce slug uniqueness
        $slugQuery = CmsForm::where('slug', $this->slug);
        if ($this->formId) $slugQuery->where('id', '!=', $this->formId);
        if ($slugQuery->exists()) {
            $this->addError('slug', 'This slug is already taken.');
            return;
        }

        $data = [
            'name'                 => $this->name,
            'slug'                 => $this->slug,
            'submit_button_label'  => $this->submit_button_label,
            'custom_css'           => $this->custom_css ?: null,
            'confirmation_message' => $this->confirmation_message ?: null,
            'redirect_url'         => $this->redirect_url ?: null,
            'email_to'             => $this->email_to ?: null,
            'email_subject'        => $this->email_subject ?: null,
            'auto_optin'           => $this->auto_optin,
            'optin_provider'       => $this->auto_optin ? ($this->optin_provider ?: null) : null,
            'optin_list_id'        => $this->auto_optin ? ($this->optin_list_id ?: null) : null,
            'is_active'            => $this->is_active,
        ];

        if ($this->formId) {
            $form = CmsForm::findOrFail($this->formId);
            $form->update($data);
        } else {
            $form = CmsForm::create($data);
            $this->formId = $form->id;
        }

        // Keep track of existing field IDs to delete only deleted ones (preserves translation integrity)
        $existingFieldIds = collect($this->fields)->pluck('id')->filter()->all();
        CmsFormField::where('form_id', $form->id)->whereNotIn('id', $existingFieldIds)->delete();

        foreach ($this->fields as $i => $f) {
            $fieldData = [
                'form_id'                => $form->id,
                'type'                   => $f['type'],
                'label'                  => $f['label'],
                'instructions'           => $f['instructions'] ?: null,
                'is_required'            => (bool) ($f['is_required'] ?? false),
                'required_type'          => $f['is_required'] ? ($f['required_type'] ?: 'non_blank') : null,
                'required_error_message' => $f['is_required'] ? ($f['required_error_message'] ?: null) : null,
                'html_above'             => $f['html_above'] ?: null,
                'options'                => ! empty($f['options']) ? $f['options'] : null,
                'sort_order'             => $i,
                'field_role'             => $f['field_role'] ?: null,
            ];

            if (!empty($f['id'])) {
                CmsFormField::where('id', $f['id'])->where('form_id', $form->id)->update($fieldData);
            } else {
                CmsFormField::create($fieldData);
            }
        }

        // Reload fields with fresh IDs from DB
        $this->fields = CmsFormField::where('form_id', $form->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($f) => [
                'id'                     => $f->id,
                'type'                   => $f->type,
                'label'                  => $f->label,
                'instructions'           => $f->instructions ?? '',
                'is_required'            => $f->is_required,
                'required_type'          => $f->required_type ?? 'non_blank',
                'required_error_message' => $f->required_error_message ?? '',
                'html_above'             => $f->html_above ?? '',
                'options'                => $f->options ?? [],
                'sort_order'             => $f->sort_order,
                'field_role'             => $f->field_role ?? '',
            ])->values()->toArray();

        session()->flash('status', 'Form saved successfully.');
    }

    // ── Translation Methods ───────────────────────────────────────────────────

    public function selectTlLang(int $langId): void
    {
        $this->tlLangId = $langId;
        $this->tlFormBuffer = [
            'name'                 => '',
            'submit_button_label'  => '',
            'confirmation_message' => '',
        ];
        $this->tlFieldsBuffer = [];
        $this->tlStatus = '';
        $this->tlTranslatedAt = '';

        if (!$this->formId) {
            return;
        }

        // Load Form Header Translation
        $existingForm = CmsFormTranslation::where('cms_form_id', $this->formId)
            ->where('language_id', $langId)
            ->first();

        if ($existingForm) {
            $this->tlFormBuffer = [
                'name'                 => $existingForm->name ?? '',
                'submit_button_label'  => $existingForm->submit_button_label ?? '',
                'confirmation_message' => $existingForm->confirmation_message ?? '',
            ];
            $this->tlStatus = $existingForm->translation_status;
            $this->tlTranslatedAt = $existingForm->translated_at
                ? $existingForm->translated_at->format('M d, Y H:i')
                : '';
        }

        // Load Field Translations
        foreach ($this->fields as $f) {
            $fieldId = $f['id'] ?? null;
            if (!$fieldId) continue;

            $existingField = CmsFormFieldTranslation::where('cms_form_field_id', $fieldId)
                ->where('language_id', $langId)
                ->first();

            $this->tlFieldsBuffer[$fieldId] = [
                'label'                  => $existingField?->label ?? '',
                'instructions'           => $existingField?->instructions ?? '',
                'required_error_message' => $existingField?->required_error_message ?? '',
                'html_above'             => $existingField?->html_above ?? '',
                'options'                => $existingField?->options ?? ($f['options'] ?? []),
            ];
        }
    }

    public function saveTlForm(): void
    {
        if (!$this->tlLangId || !$this->formId) {
            return;
        }

        // 1. Save Form Translation
        CmsFormTranslation::updateOrCreate(
            ['cms_form_id' => $this->formId, 'language_id' => $this->tlLangId],
            [
                'name'                 => $this->tlFormBuffer['name'] ?: null,
                'submit_button_label'  => $this->tlFormBuffer['submit_button_label'] ?: null,
                'confirmation_message' => $this->tlFormBuffer['confirmation_message'] ?: null,
                'translation_status'   => 'reviewed',
                'translated_at'        => now(),
            ]
        );

        // 2. Save Field Translations
        foreach ($this->fields as $f) {
            $fieldId = $f['id'] ?? null;
            if (!$fieldId || !isset($this->tlFieldsBuffer[$fieldId])) continue;

            $b = $this->tlFieldsBuffer[$fieldId];
            CmsFormFieldTranslation::updateOrCreate(
                ['cms_form_field_id' => $fieldId, 'language_id' => $this->tlLangId],
                [
                    'label'                  => $b['label'] ?: null,
                    'instructions'           => $b['instructions'] ?: null,
                    'required_error_message' => $b['required_error_message'] ?: null,
                    'html_above'             => $b['html_above'] ?: null,
                    'options'                => !empty($b['options']) ? $b['options'] : null,
                    'translation_status'     => 'reviewed',
                    'translated_at'          => now(),
                ]
            );
        }

        $this->tlStatus = 'reviewed';
        $this->tlTranslatedAt = now()->format('M d, Y H:i');
        $this->dispatch('toast', message: 'Form translations saved successfully.', type: 'success');
    }

    public function aiTlForm(): void
    {
        if (!$this->tlLangId || !$this->formId) {
            return;
        }

        $lang = Language::findOrFail($this->tlLangId);

        try {
            $svc = app(TranslationService::class);

            // Translate Form Header
            if (!empty($this->name)) {
                $this->tlFormBuffer['name'] = $svc->translateText($this->name, $lang->name, 'Form title or name');
            }
            if (!empty($this->submit_button_label)) {
                $this->tlFormBuffer['submit_button_label'] = $svc->translateText($this->submit_button_label, $lang->name, 'Form submit button label (e.g. Send Message, Submit)');
            }
            if (!empty(strip_tags($this->confirmation_message))) {
                $this->tlFormBuffer['confirmation_message'] = $svc->translateText($this->confirmation_message, $lang->name, 'Form submission confirmation success message (HTML)');
            }

            // Translate Form Fields
            foreach ($this->fields as $f) {
                $fieldId = $f['id'] ?? null;
                if (!$fieldId) continue;

                if (!isset($this->tlFieldsBuffer[$fieldId])) {
                    $this->tlFieldsBuffer[$fieldId] = [
                        'label'                  => '',
                        'instructions'           => '',
                        'required_error_message' => '',
                        'html_above'             => '',
                        'options'                => [],
                    ];
                }

                if (!empty($f['label'])) {
                    $this->tlFieldsBuffer[$fieldId]['label'] = $svc->translateText($f['label'], $lang->name, 'Form field label');
                }
                if (!empty($f['instructions'])) {
                    $this->tlFieldsBuffer[$fieldId]['instructions'] = $svc->translateText($f['instructions'], $lang->name, 'Form field helper instructions');
                }
                if (!empty($f['required_error_message'])) {
                    $this->tlFieldsBuffer[$fieldId]['required_error_message'] = $svc->translateText($f['required_error_message'], $lang->name, 'Form field validation error message');
                }
                if (!empty(strip_tags($f['html_above'] ?? ''))) {
                    $this->tlFieldsBuffer[$fieldId]['html_above'] = $svc->translateText($f['html_above'], $lang->name, 'HTML block above form field');
                }

                // Translate options if present
                if (!empty($f['options']) && is_array($f['options'])) {
                    $tlOptions = [];
                    foreach ($f['options'] as $opt) {
                        if (empty(trim((string)$opt))) continue;
                        $tlOptions[] = $svc->translateText((string)$opt, $lang->name, 'Option choice label for dropdown, radio, or checkbox');
                    }
                    $this->tlFieldsBuffer[$fieldId]['options'] = $tlOptions;
                }
            }

            $this->dispatch('toast', message: 'AI translation generated for ' . $lang->name . '. Review and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function reindexFields(): void
    {
        $this->fields = array_values($this->fields);
        foreach ($this->fields as $i => &$f) {
            $f['sort_order'] = $i;
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);

        $activeLanguages = Language::where('is_active', true)
            ->where('is_default', false)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin-cms-form-edit', [
            'activeLanguages' => $activeLanguages,
        ]);
    }
}
