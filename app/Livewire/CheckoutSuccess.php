<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\CurrencyService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class CheckoutSuccess extends Component
{
    public Order $order;

    public function mount(string $external_id): void
    {
        $this->order = Order::with(['details', 'user', 'statusList'])
            ->where('order_external_id', $external_id)
            ->firstOrFail();
    }

    public function render(): View
    {
        $customerCountry = $this->order->user?->shipping_countrycode ?? 'US';

        return view('livewire.checkout-success', [
            'currencySymbol' => CurrencyService::symbol(),
            'taxLabel'       => CurrencyService::taxLabel($customerCountry),
            'vatInclusive'   => CurrencyService::isVatInclusive(),
            'crossBorder'    => CurrencyService::isCrossBorderExport($customerCountry),
            'vatEmbed'       => CurrencyService::isVatInclusive() && !CurrencyService::isCrossBorderExport($customerCountry)
                ? CurrencyService::extractVat(
                    (float)$this->order->order_subtotal,
                    CurrencyService::merchantVatRate()
                )
                : 0.0,
        ]);
    }
}
