<?php

namespace App\Livewire;

use App\Models\CheckoutCustomField;
use App\Models\CmsSetting;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderProcessor;
use App\Models\OrderCheckoutOption;
use App\Services\OptinService;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminCheckoutProcessors extends Component
{
    use WithPagination;

    // Config form
    public int $primary_processor = 0;
    public int $secondary_processor = -1;  // -1 = None (0 is reserved for Test Processor)
    public int $tertiary_processor = -1;
    public bool $randomize_processor = false;
    public bool $paypal_express = false;
    public string $retail_minimum = '0.00';
    public string $wholesale_minimum = '0.00';
    public bool $stripe_address_required = false;

    // Processor CRUD
    public string $new_processor_name = '';
    public int $new_processor_production = 0;
    public ?int $edit_processor_id = null;
    public string $edit_processor_name = '';
    public int $edit_processor_production = 0;
    public ?int $delete_processor_id = null;
    public bool $showDeleteProcessorConfirm = false;
    public bool $showAddProcessor = false;  // hidden by default; toggle to reveal add form

    // ── Checkout Custom Fields ────────────────────────────────────────────────
    /** @var array<int, array<string, mixed>> */
    public array $checkoutFields = [];

    // Field editor state
    public ?int $editingFieldIndex = null;

    // Opt-in settings
    public string $checkoutOptinMode     = 'off';       // off | auto | manual
    public string $checkoutOptinLabel    = 'Yes, add me to the mailing list';
    public string $checkoutOptinPosition = 'checkout';  // checkout | billing
    public string $checkoutOptinProvider = '';          // mailchimp | constant_contact | klaviyo
    public string $checkoutOptinListId   = '';

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $this->loadConfig();
        $this->loadCheckoutFields();
        $this->loadOptinSettings();
    }

    private function loadConfig(): void
    {
        $opts = OrderCheckoutOption::first();
        if ($opts) {
            $this->primary_processor = (int) $opts->primary_processor;
            // 0 in the DB means "None" for secondary/tertiary — map to -1 so it doesn't
            // collide with the Test Processor which also has processor_id = 0.
            $this->secondary_processor = ((int) $opts->secondary_processor) > 0
                ? (int) $opts->secondary_processor
                : -1;
            $this->tertiary_processor = ((int) $opts->tertiary_processor) > 0
                ? (int) $opts->tertiary_processor
                : -1;
            $this->randomize_processor = (bool) $opts->randomize_processor;
            $this->paypal_express = (bool) $opts->paypal_express;
            $this->retail_minimum = number_format((float) $opts->retail_minimum, 2);
            $this->wholesale_minimum = number_format((float) $opts->wholesale_minimum, 2);
            $this->stripe_address_required = (bool) ($opts->stripe_address_required ?? false);
        }
    }

    public function saveConfig(): void
    {
        $this->validate([
            'retail_minimum' => 'required|numeric|min:0',
            'wholesale_minimum' => 'required|numeric|min:0',
        ]);

        $opts = OrderCheckoutOption::firstOrNew(['id' => 1]);
        $opts->primary_processor = $this->primary_processor;
        // -1 means "None" in the form — store as 0 in the DB
        $opts->secondary_processor = $this->secondary_processor > 0 ? $this->secondary_processor : 0;
        $opts->tertiary_processor  = $this->tertiary_processor  > 0 ? $this->tertiary_processor  : 0;
        $opts->randomize_processor = $this->randomize_processor ? 1 : 0;
        $opts->paypal_express = $this->paypal_express ? 1 : 0;
        $opts->retail_minimum = (float) $this->retail_minimum;
        $opts->wholesale_minimum = (float) $this->wholesale_minimum;
        $opts->stripe_address_required = $this->stripe_address_required ? 1 : 0;
        $opts->save();

        session()->flash('status', 'Checkout configuration saved successfully.');
    }

    public function addProcessor(): void
    {
        $this->validate([
            'new_processor_name' => 'required|string|max:255',
        ], [], [
            'new_processor_name' => 'Processor Name',
        ]);

        // Auto-increment processor_id to start custom ones at 100
        $maxId = OrderProcessor::max('processor_id') ?? 0;
        $nextId = max(100, $maxId + 1);

        OrderProcessor::create([
            'processor_id' => $nextId,
            'processor_name' => $this->new_processor_name,
            'production' => $this->new_processor_production,
        ]);

        $this->new_processor_name = '';
        $this->new_processor_production = 0;
        session()->flash('status', 'Payment processor added.');
    }

    public function startEditProcessor(int $id): void
    {
        $proc = OrderProcessor::findOrFail($id);
        $this->edit_processor_id = $id;
        $this->edit_processor_name = $proc->processor_name;
        $this->edit_processor_production = (int) $proc->production;
    }

    public function saveEditProcessor(): void
    {
        $this->validate([
            'edit_processor_name' => 'required|string|max:255',
        ]);
        $proc = OrderProcessor::findOrFail($this->edit_processor_id);
        $proc->processor_name = $this->edit_processor_name;
        $proc->production = $this->edit_processor_production;
        $proc->save();
        $this->edit_processor_id = null;
        session()->flash('status', 'Processor updated.');
    }

    public function cancelEditProcessor(): void
    {
        $this->edit_processor_id = null;
    }

    public function confirmDeleteProcessor(int $id): void
    {
        $this->delete_processor_id = $id;
        $this->showDeleteProcessorConfirm = true;
    }

    public function deleteProcessor(): void
    {
        if ($this->delete_processor_id) {
            OrderProcessor::findOrFail($this->delete_processor_id)->delete();
        }
        $this->delete_processor_id = null;
        $this->showDeleteProcessorConfirm = false;
        session()->flash('status', 'Processor deleted.');
    }

    public function cancelDeleteProcessor(): void
    {
        $this->delete_processor_id = null;
        $this->showDeleteProcessorConfirm = false;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Checkout Custom Fields
    // ─────────────────────────────────────────────────────────────────────────

    private function loadCheckoutFields(): void
    {
        $this->checkoutFields = CheckoutCustomField::orderBy('position')
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
                'position'               => $f->position,
                'show_for'               => $f->show_for ?? 'both',
                'is_active'              => $f->is_active,
                'sort_order'             => $f->sort_order,
            ])
            ->values()
            ->toArray();
    }

    private function loadOptinSettings(): void
    {
        $this->checkoutOptinMode     = CmsSetting::get('checkout_optin_mode', 'off');
        $this->checkoutOptinLabel    = CmsSetting::get('checkout_optin_label', 'Yes, add me to the mailing list');
        $this->checkoutOptinPosition = CmsSetting::get('checkout_optin_position', 'checkout');
        $this->checkoutOptinProvider = CmsSetting::get('checkout_optin_provider', '');
        $this->checkoutOptinListId   = CmsSetting::get('checkout_optin_list_id', '');
    }

    public function addCheckoutField(): void
    {
        $this->checkoutFields[] = [
            'id'                     => null,
            'type'                   => 'input',
            'label'                  => '',
            'instructions'           => '',
            'is_required'            => false,
            'required_type'          => 'non_blank',
            'required_error_message' => '',
            'html_above'             => '',
            'options'                => [],
            'position'               => 'checkout',
            'show_for'               => 'both',
            'is_active'              => true,
            'sort_order'             => count($this->checkoutFields),
        ];
        $this->editingFieldIndex = count($this->checkoutFields) - 1;
    }

    public function toggleEditCheckoutField(int $index): void
    {
        $this->editingFieldIndex = ($this->editingFieldIndex === $index) ? null : $index;
    }

    public function removeCheckoutField(int $index): void
    {
        // If it has a DB ID, delete it
        if (!empty($this->checkoutFields[$index]['id'])) {
            CheckoutCustomField::find($this->checkoutFields[$index]['id'])?->delete();
        }
        array_splice($this->checkoutFields, $index, 1);
        $this->editingFieldIndex = null;
    }

    public function moveCheckoutFieldUp(int $index): void
    {
        if ($index > 0) {
            [$this->checkoutFields[$index - 1], $this->checkoutFields[$index]] =
                [$this->checkoutFields[$index], $this->checkoutFields[$index - 1]];
            if ($this->editingFieldIndex === $index) {
                $this->editingFieldIndex = $index - 1;
            } elseif ($this->editingFieldIndex === $index - 1) {
                $this->editingFieldIndex = $index;
            }
        }
    }

    public function moveCheckoutFieldDown(int $index): void
    {
        if ($index < count($this->checkoutFields) - 1) {
            [$this->checkoutFields[$index + 1], $this->checkoutFields[$index]] =
                [$this->checkoutFields[$index], $this->checkoutFields[$index + 1]];
            if ($this->editingFieldIndex === $index) {
                $this->editingFieldIndex = $index + 1;
            } elseif ($this->editingFieldIndex === $index + 1) {
                $this->editingFieldIndex = $index;
            }
        }
    }

    public function addCheckoutFieldOption(int $index): void
    {
        $this->checkoutFields[$index]['options'][] = '';
    }

    public function removeCheckoutFieldOption(int $index, int $optionIndex): void
    {
        array_splice($this->checkoutFields[$index]['options'], $optionIndex, 1);
    }

    public function saveCheckoutFields(): void
    {
        // Validate every field has a label
        foreach ($this->checkoutFields as $i => $f) {
            if (empty(trim($f['label']))) {
                $this->addError("checkoutFields.{$i}.label", 'Label is required.');
                return;
            }
        }

        // Delete all existing and re-insert (same pattern as CMS form builder)
        CheckoutCustomField::truncate();

        foreach ($this->checkoutFields as $i => $f) {
            CheckoutCustomField::create([
                'type'                   => $f['type'],
                'label'                  => $f['label'],
                'instructions'           => $f['instructions'] ?: null,
                'is_required'            => (bool) ($f['is_required'] ?? false),
                'required_type'          => $f['is_required'] ? ($f['required_type'] ?: 'non_blank') : null,
                'required_error_message' => $f['is_required'] ? ($f['required_error_message'] ?: null) : null,
                'html_above'             => $f['html_above'] ?: null,
                'options'                => !empty($f['options']) ? array_values(array_filter($f['options'], fn($o) => $o !== '')) : null,
                'position'               => $f['position'],
                'show_for'               => $f['show_for'] ?? 'both',
                'sort_order'             => $i,
                'is_active'              => (bool) ($f['is_active'] ?? true),
            ]);
        }

        $this->loadCheckoutFields();
        $this->editingFieldIndex = null;
        session()->flash('fields_status', 'Checkout fields saved successfully.');
    }

    public function saveOptinSettings(): void
    {
        CmsSetting::set('checkout_optin_mode', $this->checkoutOptinMode);
        CmsSetting::set('checkout_optin_label', $this->checkoutOptinLabel);
        CmsSetting::set('checkout_optin_position', $this->checkoutOptinPosition);
        CmsSetting::set('checkout_optin_provider', $this->checkoutOptinMode !== 'off' ? $this->checkoutOptinProvider : '');
        CmsSetting::set('checkout_optin_list_id', $this->checkoutOptinMode !== 'off' ? $this->checkoutOptinListId : '');

        session()->flash('fields_status', 'Opt-in settings saved.');
    }

    // ─────────────────────────────────────────────────────────────────────────

    public function render(): View
    {
        $processors = OrderProcessor::orderBy('processor_id')->get();
        $payments = OrderPayment::with('order')
            ->orderBy('payment_date', 'desc')
            ->paginate(25);

        return view('livewire.admin-checkout-processors', [
            'processors' => $processors,
            'payments'   => $payments,
        ]);
    }
}
