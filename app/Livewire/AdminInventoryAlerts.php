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
        $this->resetErrorBag();
    }

    public function edit(int $id): void
    {
        $alert = ProductInventoryAlert::findOrFail($id);
        $this->editingId  = $alert->id;
        $this->message    = $alert->message;
        $this->sort_order = $alert->sort_order;
        $this->is_active  = (bool) $alert->is_active;
        $this->resetErrorBag();
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->resetErrorBag();
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

        return view('livewire.admin-inventory-alerts', ['alerts' => $alerts]);
    }
}
