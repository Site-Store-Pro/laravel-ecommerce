<?php

namespace App\Livewire;

use App\Models\CmsForm;
use App\Models\CmsFormField;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCmsFormEdit extends Component
{
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

        // Rebuild all fields (delete existing, re-insert in order)
        CmsFormField::where('form_id', $form->id)->delete();

        foreach ($this->fields as $i => $f) {
            CmsFormField::create([
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
            ]);
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

        return view('livewire.admin-cms-form-edit');
    }
}
