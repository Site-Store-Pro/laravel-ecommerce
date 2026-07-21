<div class="py-10 bg-slate-50/50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Shipping & Taxes Console</h1>
                <p class="text-xs text-slate-500 mt-1">Configure global flat-rates, sales tax rates, international VAT, and surcharge criteria filters.</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200/80 mb-6 gap-2">
            <button wire:click="$set('activeTab', 'config')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'config' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Global Configurations
            </button>
            <button wire:click="$set('activeTab', 'states')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'states' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                States & Provinces
            </button>
            <button wire:click="$set('activeTab', 'countries')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'countries' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Countries VAT
            </button>
            <button wire:click="$set('activeTab', 'flatrates')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'flatrates' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Custom Flat-Rates
            </button>
            <button wire:click="$set('activeTab', 'handling')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'handling' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Handling Charges
            </button>
            <button wire:click="$set('activeTab', 'warehouses')" class="px-4 py-2.5 font-bold text-xs uppercase tracking-wider border-b-2 transition duration-150 {{ $activeTab === 'warehouses' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                Warehouse Locations
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm font-bold">{{ session('message') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Tab 1: Config -->
        @if($activeTab === 'config')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-8 shadow-sm space-y-8">
                <form wire:submit.prevent="saveConfig" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Left Side: Gating & Options -->
                        <div class="space-y-5">
                            <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-2 uppercase tracking-wide">Fulfillment Gating</h3>
                            
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="custom_ship_options_us" class="mt-1 rounded text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Use Flat-Rate overrides list for US/Canada</span>
                                    <span class="text-xs text-slate-400">If enabled, checkout retrieves fixed rates defined in the Custom Flat-Rates list instead of state flat-rate range matrix grids.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="custom_ship_options_int" class="mt-1 rounded text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Use Flat-Rate overrides list for International</span>
                                    <span class="text-xs text-slate-400">If enabled, checkout retrieves international options defined in the Custom Flat-Rates list instead of country flat-rate range matrix grids.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="allow_comments" class="mt-1 rounded text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <span class="text-sm font-bold text-slate-700 block">Allow customer order comments at checkout</span>
                                    <span class="text-xs text-slate-400">Displays a comments text area during the final order review screen.</span>
                                </div>
                            </label>
                        </div>

                        <!-- Right Side: Logistics & Realtime API configs -->
                        <div class="space-y-5">
                            <h3 class="text-xs font-bold text-slate-600 border-b border-slate-100 pb-2 uppercase tracking-wide">Realtime Rate Carriers<br><span class="text-[10px] text-slate-400 normal-case font-semibold tracking-normal">Origin Settings</span></h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Origin Zip Code</label>
                                    <input type="text" wire:model="origin_zipcode" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1 uppercase tracking-wider">Origin Country Code</label>
                                    <input type="text" wire:model="origin_country_code" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>
                            </div>

                            <div class="space-y-3 pt-2">
                                <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Mock Real-Time Shipping carriers</span>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200/80 rounded-xl cursor-pointer hover:border-indigo-200">
                                        <input type="checkbox" wire:model="realtime_ups" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">UPS carrier rates</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200/80 rounded-xl cursor-pointer hover:border-indigo-200">
                                        <input type="checkbox" wire:model="realtime_fedex" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">FedEx carrier rates</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-3 bg-slate-50 border border-slate-200/80 rounded-xl cursor-pointer hover:border-indigo-200">
                                        <input type="checkbox" wire:model="realtime_usps" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="text-xs font-bold text-slate-700">USPS carrier rates</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Merchant Location & Currency Settings --}}
                    <div class="border-t border-slate-100 pt-6 mt-2 space-y-5">
                        <h3 class="text-sm font-extrabold text-slate-900 border-b border-slate-100 pb-2 uppercase tracking-wide">Merchant Location &amp; Currency</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Merchant Primary Country</label>
                                <select wire:model.live="merchant_country_code" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    @foreach(\Illuminate\Support\Facades\DB::table('shipping_countries')->orderBy('name')->get() as $c)
                                        <option value="{{ $c->code }}" @selected($merchant_country_code === $c->code)>{{ $c->name }} ({{ $c->code }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1">Sets the merchant's home tax jurisdiction.</p>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Currency Code</label>
                                <input type="text" wire:model="currency_code" maxlength="10" placeholder="USD" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm font-mono">
                                <p class="text-[10px] text-slate-400 mt-1">ISO 4217 code, e.g. USD, GBP, EUR, CAD, AUD.</p>
                            </div>
                            <div>
                                <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Currency Symbol</label>
                                <input type="text" wire:model="currency_symbol" maxlength="10" placeholder="$" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm font-mono">
                                <p class="text-[10px] text-slate-400 mt-1">Prefix symbol shown on all prices, e.g. $, £, €.</p>
                            </div>
                        </div>

                        @if(!in_array(strtoupper($merchant_country_code), ['US', 'CA']))
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 text-amber-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span><strong>VAT-Inclusive Pricing</strong> will be enabled. Product prices are assumed to include VAT at your country's standard rate. Cross-border buyers (US/CA) will have VAT automatically stripped at checkout.</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-slate-100 pt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition duration-150">
                            Save Global Configuration
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Tab 2: States -->
        @if($activeTab === 'states')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex justify-between items-center gap-4">
                    <h2 class="text-lg font-bold text-slate-900">US & Canada States / Provinces</h2>
                    <input type="text" wire:model.live="stateSearch" placeholder="Search state code or name..." class="max-w-xs px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Code / Country</th>
                                <th class="px-4 py-3">State Name</th>
                                <th class="px-4 py-3">Tax Rate</th>
                                <th class="px-4 py-3">Canada VAT</th>
                                <th class="px-4 py-3">Flat-Rate type</th>
                                <th class="px-4 py-3">Flat-Rate Ranges (X-Y=Z,Other=A)</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($states as $st)
                                @if($editingStateId === $st->id)
                                    <tr class="bg-indigo-50/20">
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $st->code }} ({{ $st->country_code }})</td>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $st->name }}</td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.001" wire:model="editingStateTaxRate" class="w-20 px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs"> %
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.001" wire:model="editingStateVatRate" class="w-20 px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs"> %
                                        </td>
                                        <td class="px-4 py-3">
                                            <select wire:model="editingStateValueType" class="px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs">
                                                <option value="1">Weight</option>
                                                <option value="2">Subtotal</option>
                                                <option value="3">Items count</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" wire:model="editingStateRange" placeholder="e.g. 0-5=5.00,Other=10.00" class="w-full px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" wire:model="editingStateActive" class="rounded text-indigo-600 focus:ring-indigo-500"> Active
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <button wire:click="saveState" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded transition duration-150">Save</button>
                                            <button wire:click="$set('editingStateId', null)" class="px-3 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded transition duration-150">Cancel</button>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $st->code }} ({{ $st->country_code }})</td>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $st->name }}</td>
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ number_format($st->sales_tax_rate, 3) }}%</td>
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ number_format($st->vat_rate, 3) }}%</td>
                                        <td class="px-4 py-3 text-xs">
                                            @if($st->flat_rate_value_type == 1)
                                                Weight
                                            @elseif($st->flat_rate_value_type == 2)
                                                Subtotal
                                            @else
                                                Items count
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs font-mono text-slate-500">{{ $st->flat_rate_range ?: 'None (10.00 Flat)' }}</td>
                                        <td class="px-4 py-3">
                                            @if($st->is_active)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">Disabled</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="startEditState({{ $st->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition duration-150">Edit</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $states->links() }}
                </div>
            </div>
        @endif

        <!-- Tab 3: Countries -->
        @if($activeTab === 'countries')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex justify-between items-center gap-4">
                    <h2 class="text-lg font-bold text-slate-900">International Countries</h2>
                    <input type="text" wire:model.live="countrySearch" placeholder="Search country code or name..." class="max-w-xs px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Code</th>
                                <th class="px-4 py-3">Country Name</th>
                                <th class="px-4 py-3">Charge VAT</th>
                                <th class="px-4 py-3">VAT Rate</th>
                                <th class="px-4 py-3">Flat-Rate type</th>
                                <th class="px-4 py-3">Flat-Rate Ranges (X-Y=Z,Other=A)</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($countries as $c)
                                @if($editingCountryId === $c->id)
                                    <tr class="bg-indigo-50/20">
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $c->code }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $c->name }}</td>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" wire:model="editingCountryChargeVat" class="rounded text-indigo-600 focus:ring-indigo-500"> Charge VAT
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" step="0.001" wire:model="editingCountryVatRate" class="w-20 px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs"> %
                                        </td>
                                        <td class="px-4 py-3">
                                            <select wire:model="editingCountryValueType" class="px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs">
                                                <option value="1">Weight</option>
                                                <option value="2">Subtotal</option>
                                                <option value="3">Items count</option>
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" wire:model="editingCountryRange" placeholder="e.g. 0-50=10.00,Other=20.00" class="w-full px-2 py-1 bg-white border border-slate-200 text-slate-800 rounded focus:outline-none text-xs">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" wire:model="editingCountryActive" class="rounded text-indigo-600 focus:ring-indigo-500"> Active
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2">
                                            <button wire:click="saveCountry" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded transition duration-150">Save</button>
                                            <button wire:click="$set('editingCountryId', null)" class="px-3 py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded transition duration-150">Cancel</button>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ $c->code }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-700">{{ $c->name }}</td>
                                        <td class="px-4 py-3 text-xs">
                                            @if($c->charge_vat)
                                                <span class="text-indigo-600 font-bold">Yes</span>
                                            @else
                                                <span class="text-slate-400">No</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 font-bold text-slate-800">{{ number_format($c->custom_vat_rate, 3) }}%</td>
                                        <td class="px-4 py-3 text-xs">
                                            @if($c->flat_rate_value_type == 1)
                                                Weight
                                            @elseif($c->flat_rate_value_type == 2)
                                                Subtotal
                                            @else
                                                Items count
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs font-mono text-slate-500">{{ $c->flat_rate_range ?: 'None (10.00 Flat)' }}</td>
                                        <td class="px-4 py-3">
                                            @if($c->is_active)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">Disabled</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <button wire:click="startEditCountry({{ $c->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition duration-150">Edit</button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $countries->links() }}
                </div>
            </div>
        @endif

        <!-- Tab 4: Flat-Rate Options -->
        @if($activeTab === 'flatrates')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-900">Custom Flat-Rate List Options</h2>
                    <button wire:click="openFlatRateModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">
                        + Add Custom Rate
                    </button>
                </div>

                {{-- ── Local Pickup toggle (top of page) ────────────────── --}}
                <div class="flex items-start gap-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <input type="checkbox" id="realtime_pickup_flatrates" wire:model="realtime_pickup"
                           class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                    <label for="realtime_pickup_flatrates" class="cursor-pointer">
                        <span class="text-sm font-bold text-slate-700 block">Include local pickup (free) option in custom list below</span>
                        <span class="text-xs text-slate-400">When enabled, a &ldquo;Local Pickup&rdquo; option with $0.00 cost is included in the shipping choices shown at checkout alongside the rates below.</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Rate Name</th>
                                <th class="px-4 py-3">Scope</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Sort Order</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($flatRates as $fr)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $fr->name }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        @if($fr->is_international)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">International</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">Domestic (US/CA)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-extrabold text-slate-900">${{ number_format($fr->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ $fr->sort_order }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button wire:click="openFlatRateModal({{ $fr->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition duration-150">Edit</button>
                                        <button onclick="confirm('Delete this option?') || event.stopImmediatePropagation()" wire:click="deleteFlatRate({{ $fr->id }})" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs rounded transition duration-150">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">No custom flat rates configured. Click above to add one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Custom Flat Rate Modal -->
                @if($showFlatRateModal)
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-6">
                            <h3 class="text-lg font-bold text-slate-900">{{ $flatRateId ? 'Edit Custom Rate' : 'Add Custom Rate' }}</h3>
                            
                            <form wire:submit.prevent="saveFlatRate" class="space-y-4">
                                <div>
                                    <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Rate Name</label>
                                    <input type="text" wire:model="flatRateName" placeholder="e.g. Standard Ground Delivery" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    @error('flatRateName') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Price Amount ($)</label>
                                        <input type="number" step="0.01" wire:model="flatRateAmount" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                        @error('flatRateAmount') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Sort Order</label>
                                        <input type="number" wire:model="flatRateSortOrder" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                        @error('flatRateSortOrder') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <label class="flex items-center gap-2 cursor-pointer pt-2">
                                    <input type="checkbox" wire:model="flatRateIsInternational" class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm font-semibold text-slate-700">Apply to International Scope</span>
                                </label>

                                <div class="pt-4 flex justify-end gap-2">
                                    <button type="button" wire:click="$set('showFlatRateModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 5: Handling Charges -->
        @if($activeTab === 'handling')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-900">Handling Charges Surcharges</h2>
                    <button wire:click="openHandlingModal" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">
                        + Add Handling surcharge
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Surcharge Name</th>
                                <th class="px-4 py-3">Fee amount</th>
                                <th class="px-4 py-3">Min Subtotal</th>
                                <th class="px-4 py-3">Max Subtotal</th>
                                <th class="px-4 py-3">Min Weight</th>
                                <th class="px-4 py-3">Min Items</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($handlingCharges as $hc)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $hc->name }}</td>
                                    <td class="px-4 py-3 font-extrabold text-slate-900">${{ number_format($hc->fee, 2) }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $hc->min_subtotal !== null ? '$'.number_format($hc->min_subtotal, 2) : '-' }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $hc->max_subtotal !== null ? '$'.number_format($hc->max_subtotal, 2) : '-' }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $hc->min_weight !== null ? number_format($hc->min_weight, 2).' lbs' : '-' }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $hc->min_items !== null ? $hc->min_items.' items' : '-' }}</td>
                                    <td class="px-4 py-3">
                                        @if($hc->is_active)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">Disabled</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button wire:click="openHandlingModal({{ $hc->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition duration-150">Edit</button>
                                        <button onclick="confirm('Delete this surcharge?') || event.stopImmediatePropagation()" wire:click="deleteHandlingCharge({{ $hc->id }})" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs rounded transition duration-150">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">No handling surcharges configured. Click above to add one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Handling Surcharge Modal -->
                @if($showHandlingModal)
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-6">
                            <h3 class="text-lg font-bold text-slate-900">{{ $handlingId ? 'Edit Surcharge' : 'Add Surcharge' }}</h3>
                            
                            <form wire:submit.prevent="saveHandlingCharge" class="space-y-4">
                                <div>
                                    <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Surcharge Name</label>
                                    <input type="text" wire:model="handlingName" placeholder="e.g. Bulk Handling Surcharge" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    @error('handlingName') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Fee amount ($)</label>
                                        <input type="number" step="0.01" wire:model="handlingFee" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                        @error('handlingFee') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex items-center pt-5">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" wire:model="handlingIsActive" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm font-semibold text-slate-700">Active status</span>
                                        </label>
                                    </div>
                                </div>

                                <h4 class="text-xs font-bold text-slate-500 pt-2 border-b border-slate-100 pb-1">Triggers / Criteria filters</h4>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-3xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Min Subtotal ($)</label>
                                        <input type="number" step="0.01" wire:model="handlingMinSubtotal" placeholder="No limit" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    </div>
                                    <div>
                                        <label class="text-3xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Max Subtotal ($)</label>
                                        <input type="number" step="0.01" wire:model="handlingMaxSubtotal" placeholder="No limit" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    </div>
                                    <div>
                                        <label class="text-3xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Min Order Weight (lbs)</label>
                                        <input type="number" step="0.1" wire:model="handlingMinWeight" placeholder="No limit" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    </div>
                                    <div>
                                        <label class="text-3xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Min Items count</label>
                                        <input type="number" wire:model="handlingMinItems" placeholder="No limit" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-xs">
                                    </div>
                                </div>

                                <div class="pt-4 flex justify-end gap-2">
                                    <button type="button" wire:click="$set('showHandlingModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 6: Warehouse Locations -->
        @if($activeTab === 'warehouses')
            <div class="bg-white border border-slate-200/60 rounded-3xl p-6 shadow-sm space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Warehouse & Fulfillment Locations</h2>
                        <p class="text-xs text-slate-400">Configure warehouse keys, location details, and ShipStation carrier mapping tags.</p>
                    </div>
                    <button wire:click="openWarehouseModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">
                        + Add Location
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="text-3xs font-extrabold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Warehouse Name</th>
                                <th class="px-4 py-3">Code / SKU Tag</th>
                                <th class="px-4 py-3">Address</th>
                                <th class="px-4 py-3">Region (State/Country)</th>
                                <th class="px-4 py-3">ShipStation ID</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($warehouseLocations as $w)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 font-mono text-xs text-slate-400">#{{ $w->id }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $w->name }}</td>
                                    <td class="px-4 py-3 font-semibold text-indigo-600 font-mono text-xs">{{ $w->code }}</td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ $w->address ?: '-' }}{{ $w->city ? ", {$w->city}" : '' }}{{ $w->zipcode ? " {$w->zipcode}" : '' }}
                                    </td>
                                    <td class="px-4 py-3 text-xs">
                                        {{ $w->state_code ?: 'N/A' }} / {{ $w->country_code }}
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono text-slate-400">
                                        {{ $w->shipstation_carrier_id ?: 'None' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($w->is_active)
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <button wire:click="openWarehouseModal({{ $w->id }})" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded transition duration-150">Edit</button>
                                        <button onclick="confirm('Delete this location? This may affect inventory records associated with it.') || event.stopImmediatePropagation()" wire:click="deleteWarehouse({{ $w->id }})" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-bold text-xs rounded transition duration-150">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">No warehouse locations configured. Click above to create one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Warehouse Locations Modal -->
                @if($showWarehouseModal)
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div class="bg-white border border-slate-100 rounded-3xl p-6 w-full max-w-md shadow-2xl space-y-6">
                            <h3 class="text-lg font-bold text-slate-900">{{ $warehouseId ? 'Edit Warehouse Location' : 'Add Warehouse Location' }}</h3>
                            
                            <form wire:submit.prevent="saveWarehouse" class="space-y-4">
                                <div>
                                    <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Warehouse Name</label>
                                    <input type="text" wire:model="warehouseName" placeholder="e.g. Dallas Distribution Center" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    @error('warehouseName') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Code / Unique Identifier</label>
                                        <input type="text" wire:model="warehouseCode" placeholder="e.g. US-DAL-1" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm font-mono">
                                        @error('warehouseCode') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="flex items-center pt-5">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" wire:model="warehouseIsActive" class="rounded text-indigo-600 focus:ring-indigo-500">
                                            <span class="text-sm font-semibold text-slate-700">Active Location</span>
                                        </label>
                                    </div>
                                </div>

                                <h4 class="text-xs font-bold text-slate-500 pt-2 border-b border-slate-100 pb-1">Address & Contact Information</h4>

                                <div>
                                    <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Street Address</label>
                                    <input type="text" wire:model="warehouseAddress" placeholder="e.g. 100 Main St" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">City</label>
                                        <input type="text" wire:model="warehouseCity" placeholder="e.g. Dallas" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    </div>
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Postal / Zip Code</label>
                                        <input type="text" wire:model="warehouseZipcode" placeholder="e.g. 75201" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">State / Province Code</label>
                                        <input type="text" wire:model="warehouseStateCode" placeholder="e.g. TX" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm uppercase">
                                    </div>
                                    <div>
                                        <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Country Code</label>
                                        <input type="text" wire:model="warehouseCountryCode" placeholder="e.g. US" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm uppercase">
                                        @error('warehouseCountryCode') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <h4 class="text-xs font-bold text-slate-500 pt-2 border-b border-slate-100 pb-1">Integrations & ShipStation</h4>

                                <div>
                                    <label class="text-2xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">ShipStation Warehouse/Carrier ID</label>
                                    <input type="text" wire:model="warehouseShipstationId" placeholder="e.g. ss-carrier-ups-12" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 text-sm font-mono">
                                </div>

                                <div class="pt-4 flex justify-end gap-2">
                                    <button type="button" wire:click="$set('showWarehouseModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition duration-150">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
