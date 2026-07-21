<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class AdminShippingSettings extends Component
{
    use WithPagination;

    public string $activeTab = 'config';

    // Global Config
    public bool $custom_ship_options_us = false;
    public bool $custom_ship_options_int = false;
    public bool $allow_comments = false;
    public string $origin_zipcode = '';
    public string $origin_country_code = '';
    public bool $realtime_fedex = false;
    public bool $realtime_ups = false;
    public bool $realtime_usps = false;
    public bool $realtime_pickup = false;

    // Merchant Location & Currency
    public string $merchant_country_code = 'US';
    public string $currency_code = 'USD';
    public string $currency_symbol = '$';

    // Search filters
    public string $stateSearch = '';
    public string $countrySearch = '';

    // Selected state/country for inline edits
    public ?int $editingStateId = null;
    public float $editingStateTaxRate = 0.00;
    public float $editingStateVatRate = 0.00;
    public bool $editingStateActive = false;
    public int $editingStateValueType = 1;
    public string $editingStateRange = '';

    public ?int $editingCountryId = null;
    public float $editingCountryVatRate = 0.00;
    public bool $editingCountryActive = false;
    public bool $editingCountryChargeVat = false;
    public int $editingCountryValueType = 1;
    public string $editingCountryRange = '';

    // Flat Rate Form State
    public bool $showFlatRateModal = false;
    public ?int $flatRateId = null;
    public string $flatRateName = '';
    public float $flatRateAmount = 0.00;
    public bool $flatRateIsInternational = false;
    public int $flatRateSortOrder = 0;

    // Handling Charge Form State
    public bool $showHandlingModal = false;
    public ?int $handlingId = null;
    public string $handlingName = '';
    public float $handlingFee = 0.00;
    public bool $handlingIsActive = false;
    public ?float $handlingMinSubtotal = null;
    public ?float $handlingMaxSubtotal = null;
    public ?float $handlingMinWeight = null;
    public ?int $handlingMinItems = null;
    // Warehouse Locations Form State
    public bool $showWarehouseModal = false;
    public ?int $warehouseId = null;
    public string $warehouseName = '';
    public string $warehouseCode = '';
    public string $warehouseAddress = '';
    public string $warehouseCity = '';
    public string $warehouseStateCode = '';
    public string $warehouseCountryCode = 'US';
    public string $warehouseZipcode = '';
    public string $warehouseShipstationId = '';
    public bool $warehouseIsActive = true;

    public function mount(): void
    {
        $config = DB::table('shipping_configurations')->where('id', 1)->first();
        if ($config) {
            $this->custom_ship_options_us = (bool)$config->custom_ship_options_us;
            $this->custom_ship_options_int = (bool)$config->custom_ship_options_int;
            $this->allow_comments = (bool)$config->allow_comments;
            $this->origin_zipcode = $config->origin_zipcode ?? '';
            $this->origin_country_code = $config->origin_country_code ?? '';
            $this->realtime_fedex = (bool)$config->realtime_fedex;
            $this->realtime_ups = (bool)$config->realtime_ups;
            $this->realtime_usps = (bool)$config->realtime_usps;
            $this->realtime_pickup = (bool)$config->realtime_pickup;
            $this->merchant_country_code = $config->merchant_country_code ?? 'US';
            $this->currency_code = $config->currency_code ?? 'USD';
            $this->currency_symbol = $config->currency_symbol ?? '$';
        }
    }

    public function saveConfig(): void
    {
        $this->validate([
            'currency_code'         => 'required|string|max:10',
            'currency_symbol'       => 'required|string|max:10',
            'merchant_country_code' => 'required|string|max:10',
        ]);

        // VAT-inclusive pricing is auto-derived: any non-US/CA merchant uses it
        $merchantCountry = strtoupper($this->merchant_country_code);
        $vatInclusive = !in_array($merchantCountry, ['US', 'CA'], true);

        DB::table('shipping_configurations')
            ->updateOrInsert(
                ['id' => 1],
                [
                    'custom_ship_options_us'  => $this->custom_ship_options_us,
                    'custom_ship_options_int' => $this->custom_ship_options_int,
                    'allow_comments'          => $this->allow_comments,
                    'origin_zipcode'          => $this->origin_zipcode,
                    'origin_country_code'     => $this->origin_country_code,
                    'realtime_fedex'          => $this->realtime_fedex,
                    'realtime_ups'            => $this->realtime_ups,
                    'realtime_usps'           => $this->realtime_usps,
                    'realtime_pickup'         => $this->realtime_pickup,
                    'merchant_country_code'   => $merchantCountry,
                    'currency_code'           => strtoupper(trim($this->currency_code)),
                    'currency_symbol'         => trim($this->currency_symbol),
                    'vat_inclusive_pricing'   => $vatInclusive,
                    'updated_at'              => now(),
                ]
            );

        // Flush cached currency config so all subsequent requests see the new values
        \App\Services\CurrencyService::flushCache();

        session()->flash('message', 'Global shipping configuration updated successfully.');
        $this->dispatch('toast', message: 'Global shipping configurations saved.', type: 'success');
    }

    // State Inline Actions
    public function startEditState(int $id): void
    {
        $state = DB::table('shipping_states')->where('id', $id)->first();
        if ($state) {
            $this->editingStateId = $state->id;
            $this->editingStateTaxRate = (float)$state->sales_tax_rate;
            $this->editingStateVatRate = (float)$state->vat_rate;
            $this->editingStateActive = (bool)$state->is_active;
            $this->editingStateValueType = (int)$state->flat_rate_value_type;
            $this->editingStateRange = $state->flat_rate_range ?? '';
        }
    }

    public function saveState(): void
    {
        if ($this->editingStateId) {
            DB::table('shipping_states')
                ->where('id', $this->editingStateId)
                ->update([
                    'sales_tax_rate' => $this->editingStateTaxRate,
                    'vat_rate' => $this->editingStateVatRate,
                    'is_active' => $this->editingStateActive,
                    'flat_rate_value_type' => $this->editingStateValueType,
                    'flat_rate_range' => empty($this->editingStateRange) ? null : $this->editingStateRange,
                    'updated_at' => now(),
                ]);

            $this->editingStateId = null;
            $this->dispatch('toast', message: 'State tax and range settings updated.', type: 'success');
        }
    }

    // Country Inline Actions
    public function startEditCountry(int $id): void
    {
        $country = DB::table('shipping_countries')->where('id', $id)->first();
        if ($country) {
            $this->editingCountryId = $country->id;
            $this->editingCountryVatRate = (float)$country->custom_vat_rate;
            $this->editingCountryActive = (bool)$country->is_active;
            $this->editingCountryChargeVat = (bool)$country->charge_vat;
            $this->editingCountryValueType = (int)$country->flat_rate_value_type;
            $this->editingCountryRange = $country->flat_rate_range ?? '';
        }
    }

    public function saveCountry(): void
    {
        if ($this->editingCountryId) {
            DB::table('shipping_countries')
                ->where('id', $this->editingCountryId)
                ->update([
                    'custom_vat_rate' => $this->editingCountryVatRate,
                    'is_active' => $this->editingCountryActive,
                    'charge_vat' => $this->editingCountryChargeVat,
                    'flat_rate_value_type' => $this->editingCountryValueType,
                    'flat_rate_range' => empty($this->editingCountryRange) ? null : $this->editingCountryRange,
                    'updated_at' => now(),
                ]);

            $this->editingCountryId = null;
            $this->dispatch('toast', message: 'Country VAT and range settings updated.', type: 'success');
        }
    }

    // Flat Rate Actions
    public function openFlatRateModal(?int $id = null): void
    {
        $this->flatRateId = $id;
        if ($id) {
            $rate = DB::table('shipping_flat_rates')->where('id', $id)->first();
            if ($rate) {
                $this->flatRateName = $rate->name;
                $this->flatRateAmount = (float)$rate->amount;
                $this->flatRateIsInternational = (bool)$rate->is_international;
                $this->flatRateSortOrder = (int)$rate->sort_order;
            }
        } else {
            $this->flatRateName = '';
            $this->flatRateAmount = 0.00;
            $this->flatRateIsInternational = false;
            $this->flatRateSortOrder = 0;
        }
        $this->showFlatRateModal = true;
    }

    public function saveFlatRate(): void
    {
        $this->validate([
            'flatRateName' => 'required|string|max:255',
            'flatRateAmount' => 'required|numeric|min:0',
            'flatRateSortOrder' => 'required|integer',
        ]);

        $data = [
            'name' => $this->flatRateName,
            'amount' => $this->flatRateAmount,
            'is_international' => $this->flatRateIsInternational,
            'sort_order' => $this->flatRateSortOrder,
            'updated_at' => now(),
        ];

        if ($this->flatRateId) {
            DB::table('shipping_flat_rates')->where('id', $this->flatRateId)->update($data);
            $msg = 'Flat rate option updated.';
        } else {
            $data['created_at'] = now();
            DB::table('shipping_flat_rates')->insert($data);
            $msg = 'Flat rate option created.';
        }

        $this->showFlatRateModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function deleteFlatRate(int $id): void
    {
        DB::table('shipping_flat_rates')->where('id', $id)->delete();
        $this->dispatch('toast', message: 'Flat rate option deleted.', type: 'warning');
    }

    // Handling Charge Actions
    public function openHandlingModal(?int $id = null): void
    {
        $this->handlingId = $id;
        if ($id) {
            $h = DB::table('handling_charges')->where('id', $id)->first();
            if ($h) {
                $this->handlingName = $h->name;
                $this->handlingFee = (float)$h->fee;
                $this->handlingIsActive = (bool)$h->is_active;
                $this->handlingMinSubtotal = $h->min_subtotal !== null ? (float)$h->min_subtotal : null;
                $this->handlingMaxSubtotal = $h->max_subtotal !== null ? (float)$h->max_subtotal : null;
                $this->handlingMinWeight = $h->min_weight !== null ? (float)$h->min_weight : null;
                $this->handlingMinItems = $h->min_items;
            }
        } else {
            $this->handlingName = '';
            $this->handlingFee = 0.00;
            $this->handlingIsActive = false;
            $this->handlingMinSubtotal = null;
            $this->handlingMaxSubtotal = null;
            $this->handlingMinWeight = null;
            $this->handlingMinItems = null;
        }
        $this->showHandlingModal = true;
    }

    public function saveHandlingCharge(): void
    {
        $this->validate([
            'handlingName' => 'required|string|max:255',
            'handlingFee' => 'required|numeric|min:0',
        ]);

        $data = [
            'name' => $this->handlingName,
            'fee' => $this->handlingFee,
            'is_active' => $this->handlingIsActive,
            'min_subtotal' => $this->handlingMinSubtotal,
            'max_subtotal' => $this->handlingMaxSubtotal,
            'min_weight' => $this->handlingMinWeight,
            'min_items' => $this->handlingMinItems,
            'updated_at' => now(),
        ];

        if ($this->handlingId) {
            DB::table('handling_charges')->where('id', $this->handlingId)->update($data);
            $msg = 'Handling surcharge updated.';
        } else {
            $data['created_at'] = now();
            DB::table('handling_charges')->insert($data);
            $msg = 'Handling surcharge created.';
        }

        $this->showHandlingModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function deleteHandlingCharge(int $id): void
    {
        DB::table('handling_charges')->where('id', $id)->delete();
        $this->dispatch('toast', message: 'Handling surcharge deleted.', type: 'warning');
    }

    // Warehouse Location CRUD Methods
    public function openWarehouseModal(?int $id = null): void
    {
        $this->warehouseId = $id;
        if ($id) {
            $w = DB::table('warehouse_locations')->where('id', $id)->first();
            if ($w) {
                $this->warehouseName = $w->name;
                $this->warehouseCode = $w->code;
                $this->warehouseAddress = $w->address ?? '';
                $this->warehouseCity = $w->city ?? '';
                $this->warehouseStateCode = $w->state_code ?? '';
                $this->warehouseCountryCode = $w->country_code;
                $this->warehouseZipcode = $w->zipcode ?? '';
                $this->warehouseShipstationId = $w->shipstation_carrier_id ?? '';
                $this->warehouseIsActive = (bool) $w->is_active;
            }
        } else {
            $this->warehouseName = '';
            $this->warehouseCode = '';
            $this->warehouseAddress = '';
            $this->warehouseCity = '';
            $this->warehouseStateCode = '';
            $this->warehouseCountryCode = 'US';
            $this->warehouseZipcode = '';
            $this->warehouseShipstationId = '';
            $this->warehouseIsActive = true;
        }
        $this->showWarehouseModal = true;
    }

    public function saveWarehouse(): void
    {
        $this->validate([
            'warehouseName' => 'required|string|max:255',
            'warehouseCode' => 'required|string|max:50|unique:warehouse_locations,code,' . $this->warehouseId,
            'warehouseCountryCode' => 'required|string|max:10',
        ]);

        $data = [
            'name' => $this->warehouseName,
            'code' => $this->warehouseCode,
            'address' => $this->warehouseAddress ?: null,
            'city' => $this->warehouseCity ?: null,
            'state_code' => $this->warehouseStateCode ?: null,
            'country_code' => $this->warehouseCountryCode,
            'zipcode' => $this->warehouseZipcode ?: null,
            'shipstation_carrier_id' => $this->warehouseShipstationId ?: null,
            'is_active' => $this->warehouseIsActive,
            'updated_at' => now(),
        ];

        if ($this->warehouseId) {
            DB::table('warehouse_locations')->where('id', $this->warehouseId)->update($data);
            $msg = 'Warehouse location updated.';
        } else {
            $data['created_at'] = now();
            DB::table('warehouse_locations')->insert($data);
            $msg = 'Warehouse location created.';
        }

        $this->showWarehouseModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function deleteWarehouse(int $id): void
    {
        DB::table('warehouse_locations')->where('id', $id)->delete();
        $this->dispatch('toast', message: 'Warehouse location deleted.', type: 'warning');
    }

    public function render(): View
    {
        // 1. Fetch States
        $statesQuery = DB::table('shipping_states');
        if (!empty($this->stateSearch)) {
            $statesQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->stateSearch . '%')
                  ->orWhere('code', 'like', '%' . $this->stateSearch . '%');
            });
        }
        $states = $statesQuery->orderBy('country_code', 'asc')->orderBy('name', 'asc')->paginate(15, ['*'], 'statesPage');

        // 2. Fetch Countries
        $countriesQuery = DB::table('shipping_countries');
        if (!empty($this->countrySearch)) {
            $countriesQuery->where('name', 'like', '%' . $this->countrySearch . '%')
                           ->orWhere('code', 'like', '%' . $this->countrySearch . '%');
        }
        $countries = $countriesQuery->orderBy('name', 'asc')->paginate(15, ['*'], 'countriesPage');

        // 3. Fetch Flat Rates
        $flatRates = DB::table('shipping_flat_rates')->orderBy('is_international', 'asc')->orderBy('sort_order', 'asc')->get();

        // 4. Fetch Handling Charges
        $handlingCharges = DB::table('handling_charges')->orderBy('id', 'asc')->get();

        // 5. Fetch Warehouse Locations
        $warehouseLocations = DB::table('warehouse_locations')->orderBy('id', 'asc')->get();

        return view('livewire.admin-shipping-settings', [
            'states' => $states,
            'countries' => $countries,
            'flatRates' => $flatRates,
            'handlingCharges' => $handlingCharges,
            'warehouseLocations' => $warehouseLocations,
        ])->layout('layouts.app');
    }
}
