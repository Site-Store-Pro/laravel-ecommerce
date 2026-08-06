<?php

namespace App\Livewire;

use App\Models\ProductInventoryAlert;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminInventoryAlerts extends Component
{
    // ── List state ────────────────────────────────────────────────────────────
    public string $search = '';

    // ── Inline edit / create form ─────────────────────────────────────────────
    public ?int    $editingId   = null;   // null = creating new
    public string  $message     = '';
    public int     $sort_order  = 0;
    public bool    $is_active   = true;

    // ── Translation state ───────────────────────────────────────────────────
    public int   $tlLangId = 0;
    public array $tlBuffer = [];

    // ── Delete confirmation ───────────────────────────────────────────────────
    public ?int $confirmingDeleteId = null;

    // ── Flash ─────────────────────────────────────────────────────────────────
    protected function rules(): array
    {
        return [
            'message'    => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ];
    }

    // ── Open the inline form ──────────────────────────────────────────────────

    public function create(): void
    {
        $this->editingId  = null;
        $this->message    = '';
        $this->sort_order = ProductInventoryAlert::max('sort_order') + 1;
        $this->is_active  = true;
        $this->tlLangId   = 0;
        $this->tlBuffer   = [];
        $this->resetErrorBag();
    }

    public function edit(int $id): void
    {
        $alert = ProductInventoryAlert::findOrFail($id);
        $this->editingId  = $alert->id;
        $this->message    = $alert->message;
        $this->sort_order = $alert->sort_order;
        $this->is_active  = (bool) $alert->is_active;
        $this->tlLangId   = 0;
        $this->tlBuffer   = [];
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->tlLangId  = 0;
        $this->tlBuffer  = [];
        $this->resetErrorBag();
    }

    // ── Translation Methods ───────────────────────────────────────────────────

    public function selectTlLang(int $id): void
    {
        $this->tlLangId = $id;
        $this->tlBuffer = [];
        if ($this->editingId) {
            $this->loadTlFor($this->editingId);
        }
    }

    public function loadTlFor(?int $modelId): void
    {
        if (!$modelId || $this->tlLangId === 0) {
            return;
        }
        $existing = \App\Models\ProductInventoryAlertTranslation::where('product_inventory_alert_id', $modelId)
            ->where('language_id', $this->tlLangId)
            ->first();
        $this->tlBuffer = $existing ? $existing->only(['message']) : [];
    }

    public function saveTlAlert(int $modelId): void
    {
        if ($this->tlLangId === 0) {
            return;
        }
        \App\Models\ProductInventoryAlertTranslation::updateOrCreate(
            ['product_inventory_alert_id' => $modelId, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, ['translation_status' => 'reviewed', 'translated_at' => now()])
        );
        $this->dispatch('toast', type: 'success', message: 'Translation saved successfully.');
    }

    public function aiTlAlert(int $modelId): void
    {
        if ($this->tlLangId === 0) {
            return;
        }
        $record = ProductInventoryAlert::findOrFail($modelId);
        $lang   = \App\Models\Language::findOrFail($this->tlLangId);

        try {
            $svc = app(\App\Services\TranslationService::class);
            if (!empty($record->message)) {
                $this->tlBuffer['message'] = $svc->translateText($record->message, $lang->name, 'Inventory alert message (out-of-stock badge)');
            }
            $this->dispatch('toast', type: 'success', message: 'AI translation ready — review and click Save.');
        } catch (\Throwable $e) {
            $this->dispatch('toast', type: 'error', message: 'AI translation failed: ' . $e->getMessage());
        }
    }

    // ── Save ─────────────────────────────────────────────────────────────────

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            ProductInventoryAlert::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', type: 'success', message: 'Alert message updated.');
        } else {
            ProductInventoryAlert::create($data);
            $this->dispatch('toast', type: 'success', message: 'Alert message created.');
        }

        $this->editingId = null;
        $this->message   = '';
    }

    // ── Toggle active ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $alert = ProductInventoryAlert::findOrFail($id);
        $alert->update(['is_active' => ! $alert->is_active]);
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

    public function deleteAlert(): void
    {
        if ($this->confirmingDeleteId) {
            // Products with this alert assigned will automatically have
            // inventory_alert_id set to NULL (nullOnDelete FK constraint),
            // so the storefront gracefully falls back to the default message.
            ProductInventoryAlert::findOrFail($this->confirmingDeleteId)->delete();
            $this->dispatch('toast', type: 'success', message: 'Alert message deleted. Assigned products will use the default message.');
            $this->confirmingDeleteId = null;
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        $alerts = ProductInventoryAlert::ordered()
            ->when($this->search, fn ($q) => $q->where('message', 'like', '%' . $this->search . '%'))
            ->withCount('products')
            ->get();

        $activeLanguages = \App\Models\Language::where('is_active', true)
            ->where('is_default', false)
            ->get();

        return view('livewire.admin-inventory-alerts', [
            'alerts'          => $alerts,
            'activeLanguages' => $activeLanguages,
        ]);
    }
}
