{{--
    Payment Processor IDs — Variant Form Partial
    Included in both edit and create variant forms in admin-product-edit.blade.php
    Requires: $paddle_sandbox_price_id, $paddle_live_price_id,
              $stripe_sandbox_price_id, $stripe_live_price_id,
              $paypal_sandbox_plan_id, $paypal_live_plan_id,
              $create_new_stripe_product, $stripe_billing_interval,
              $stripe_trial_enabled, $stripe_trial_days
--}}
<div class="mt-4" x-data="{ openProcessorIds: @json((bool)($paddle_sandbox_price_id || $paddle_live_price_id || $paddle_price || $paddle_interval || $stripe_sandbox_price_id || $stripe_live_price_id || $create_new_stripe_product || $paypal_sandbox_plan_id || $paypal_live_plan_id)) }">
    <button type="button" @click="openProcessorIds = !openProcessorIds"
        class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-indigo-600 transition-colors w-full text-left group">
        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="openProcessorIds ? 'rotate-90 text-indigo-500' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Payment Processor IDs
        <span class="text-[10px] font-normal text-slate-400 normal-case tracking-normal ml-1">PayPal, Stripe &amp; Paddle subscription settings</span>
        @if($paddle_sandbox_price_id || $paddle_live_price_id || $paddle_price || $paddle_interval || $stripe_sandbox_price_id || $stripe_live_price_id || $create_new_stripe_product || $paypal_sandbox_plan_id || $paypal_live_plan_id)
            <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Configured
            </span>
        @endif
    </button>

    <div x-show="openProcessorIds"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-3 space-y-3">

        {{-- ── Paddle ─────────────────────────────────────────────────────────── --}}
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 space-y-3">
            <p class="text-xs font-bold text-blue-600 uppercase tracking-wider flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                Paddle Price IDs
            </p>
            <p class="text-[11px] text-blue-700 leading-relaxed">
                When set, the matching price ID (sandbox or live) is passed to Paddle.js at checkout instead of a custom amount. Leave blank to use standard amount billing.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Sandbox Price ID</label>
                    <input type="text" wire:model="paddle_sandbox_price_id"
                        placeholder="pri_sandbox_xxxxxxxxx"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400 font-mono placeholder-slate-300">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Live Price ID</label>
                    <input type="text" wire:model="paddle_live_price_id"
                        placeholder="pri_xxxxxxxxx"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400 font-mono placeholder-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 border-t border-blue-100 pt-3">
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Paddle Catalog Price</label>
                    <input type="number" step="0.01" wire:model="paddle_price"
                        placeholder="0.00"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400 font-mono placeholder-slate-300">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Currency Code</label>
                    <input type="text" wire:model="paddle_currency_code"
                        placeholder="USD"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400 font-mono placeholder-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3">
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Billing Interval</label>
                    <select wire:model="paddle_interval"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400">
                        <option value="">One-Time (No subscription)</option>
                        <option value="day">Daily</option>
                        <option value="week">Weekly</option>
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-blue-500 block mb-1 uppercase tracking-wider">Billing Frequency</label>
                    <input type="number" min="1" wire:model="paddle_frequency"
                        placeholder="1"
                        class="w-full px-3 py-2 bg-white border border-blue-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-blue-400 font-mono placeholder-slate-300">
                </div>
            </div>
        </div>

        {{-- ── Stripe ──────────────────────────────────────────────────────────── --}}
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 space-y-3">
            <p class="text-xs font-bold text-indigo-600 uppercase tracking-wider flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500"></span>
                Stripe Subscription
            </p>
            <p class="text-[11px] text-indigo-700 leading-relaxed">
                Stripe price IDs enable <strong>subscription billing</strong>. Checkout will create a recurring Stripe Subscription instead of a one-time payment when these fields are set.
            </p>

            {{-- Create-on-the-fly toggle --}}
            <div class="bg-white border border-indigo-200 rounded-xl p-3 flex items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-700">Create new Stripe product on checkout</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        When <span class="font-semibold text-indigo-600">ON</span>: a new Stripe Product + recurring Price is created automatically at checkout time.
                        Price ID fields below are ignored when this is enabled.
                    </p>
                </div>
                <label class="flex items-center gap-2 cursor-pointer shrink-0">
                    <div class="relative">
                        <input type="checkbox" wire:model.live.number="create_new_stripe_product" class="sr-only peer" true-value="1" false-value="0">
                        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500"></div>
                    </div>
                    <span class="text-xs font-bold w-7 {{ $create_new_stripe_product ? 'text-indigo-700' : 'text-slate-400' }} transition-colors">
                        {{ $create_new_stripe_product ? 'ON' : 'OFF' }}
                    </span>
                </label>
            </div>

            @if(!$create_new_stripe_product)
                {{-- Existing price IDs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-bold text-indigo-500 block mb-1 uppercase tracking-wider">Test / Sandbox Price ID</label>
                        <input type="text" wire:model="stripe_sandbox_price_id"
                            placeholder="price_test_xxxxxxxxx"
                            class="w-full px-3 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-indigo-400 font-mono placeholder-slate-300">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-indigo-500 block mb-1 uppercase tracking-wider">Live Price ID</label>
                        <input type="text" wire:model="stripe_live_price_id"
                            placeholder="price_xxxxxxxxx"
                            class="w-full px-3 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-indigo-400 font-mono placeholder-slate-300">
                    </div>
                </div>
            @else
                <div class="bg-indigo-100 border border-indigo-200 rounded-xl p-3">
                    <p class="text-xs text-indigo-700 font-medium flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                        A new Stripe Product + recurring Price will be created automatically when this variant is purchased.
                    </p>
                </div>
            @endif

            {{-- Billing interval + trial (always visible under the Stripe section) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-3 border-t border-indigo-100">
                {{-- Billing interval --}}
                <div>
                    <label class="text-[10px] font-bold text-indigo-500 block mb-1 uppercase tracking-wider">Billing Interval</label>
                    <select wire:model="stripe_billing_interval"
                        class="w-full px-3 py-2 bg-white border border-indigo-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-indigo-400">
                        <option value="month">Monthly (every month)</option>
                        <option value="year">Yearly (every year)</option>
                        <option value="week">Weekly (every week)</option>
                    </select>
                </div>

                {{-- Free/Paid trial --}}
                <div class="flex flex-col sm:col-span-2">
                    <label class="text-[10px] font-bold text-indigo-500 block mb-1 uppercase tracking-wider">Trial Period & Pricing</label>
                    <div class="flex flex-wrap items-center gap-3 mt-auto pb-0.5">
                        <label class="flex items-center gap-1.5 cursor-pointer shrink-0" title="{{ $stripe_trial_enabled ? 'Disable trial' : 'Enable trial' }}">
                            <div class="relative">
                                <input type="checkbox" wire:model.live.number="stripe_trial_enabled" class="sr-only peer" true-value="1" false-value="0">
                                <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-indigo-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-500"></div>
                            </div>
                            <span class="text-[11px] font-semibold {{ $stripe_trial_enabled ? 'text-indigo-600' : 'text-slate-400' }}">
                                {{ $stripe_trial_enabled ? 'On' : 'Off' }}
                            </span>
                        </label>
                        @if($stripe_trial_enabled)
                            <div class="flex items-center gap-1.5">
                                <input type="number" wire:model="stripe_trial_days"
                                    min="1" max="365" placeholder="14"
                                    class="w-16 px-2 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg text-xs focus:outline-none focus:border-indigo-400 font-mono">
                                <span class="text-[11px] text-indigo-600 font-medium whitespace-nowrap">days</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-[11px] text-slate-500 font-medium">Trial Price:</span>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-xs text-slate-400">$</span>
                                    <input type="number" step="0.01" min="0" wire:model="stripe_trial_price"
                                        placeholder="0.00"
                                        class="w-20 pl-5 pr-2 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg text-xs focus:outline-none focus:border-indigo-400 font-mono">
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-[11px] text-slate-500 font-medium">Display Label:</span>
                                <input type="text" wire:model="stripe_trial_label"
                                    placeholder="e.g. Free Trial"
                                    class="w-32 px-2 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg text-xs focus:outline-none focus:border-indigo-400">
                            </div>
                            <p class="w-full text-[10px] text-slate-500 mt-1">
                                <span class="font-bold text-indigo-600">Note:</span> If an optional Display Label is entered (e.g. &ldquo;Free Trial&rdquo;), it will be shown to customers in place of the trial price across the catalog, product page, cart, and widgets. If left blank, the numeric Trial Price is displayed. The Public Price is billed by Stripe after the trial period ends.
                            </p>
                        @else
                            <span class="text-[11px] text-slate-400">No trial period</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ── PayPal ──────────────────────────────────────────────────────────── --}}
        <div class="bg-amber-50/70 border border-amber-200 rounded-2xl p-4 space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-amber-700 uppercase tracking-wider flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-amber-500"></span>
                    PayPal Subscriptions
                </p>
                <span class="text-[10px] text-amber-700 font-medium">Automatic Plan Generator &amp; Manual Entry</span>
            </div>
            
            <p class="text-[11px] text-amber-800 leading-relaxed">
                Configure recurring subscription parameters below and click <strong>Generate Plan</strong> to automatically create the Product &amp; Billing Plan directly in PayPal, or manually enter existing <code class="font-mono bg-amber-100/80 px-1 py-0.5 rounded text-amber-900">P-xxxx</code> Plan IDs.
            </p>

            @error('paypal_plan_error')
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-xl text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- Subscription Interval, Frequency & Total Cycles --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-white/80 border border-amber-200/80 rounded-xl p-3">
                <div>
                    <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Billing Interval</label>
                    <select wire:model="paypal_billing_interval"
                        class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400">
                        <option value="month">Monthly</option>
                        <option value="year">Yearly</option>
                        <option value="week">Weekly</option>
                        <option value="day">Daily</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Billing Frequency</label>
                    <div class="flex items-center gap-1.5">
                        <input type="number" min="1" max="99" wire:model="paypal_billing_frequency"
                            placeholder="1"
                            class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono">
                    </div>
                    <span class="text-[10px] text-slate-400">e.g. 1 = every month, 3 = every 3 months</span>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Total Cycles (Duration)</label>
                    <input type="number" min="0" max="999" wire:model="paypal_total_cycles"
                        placeholder="0"
                        class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono">
                    <span class="text-[10px] text-slate-400">0 = Infinite (ongoing until cancelled)</span>
                </div>
            </div>

            {{-- Trial Period Configuration --}}
            <div class="bg-white/80 border border-amber-200/80 rounded-xl p-3 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-amber-800">Trial Period &amp; Pricing</span>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" wire:model.live.number="paypal_trial_enabled" class="sr-only peer" true-value="1" false-value="0">
                            <div class="w-9 h-5 bg-slate-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-amber-400 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                        </div>
                        <span class="text-xs font-semibold {{ $paypal_trial_enabled ? 'text-amber-700' : 'text-slate-400' }}">
                            {{ $paypal_trial_enabled ? 'Enabled' : 'Disabled' }}
                        </span>
                    </label>
                </div>

                @if($paypal_trial_enabled)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-amber-100">
                        <div>
                            <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Trial Duration (Days)</label>
                            <input type="number" min="1" max="365" wire:model="paypal_trial_days"
                                placeholder="14"
                                class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Trial Price ($ USD)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-xs text-slate-400">$</span>
                                <input type="number" step="0.01" min="0" wire:model="paypal_trial_price"
                                    placeholder="0.00"
                                    class="w-full pl-6 pr-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- One-Click Action Buttons to Generate Plans On-The-Fly --}}
            <div class="bg-amber-100/60 border border-amber-300/60 rounded-xl p-3 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-amber-900">One-Click Plan Generator</p>
                    <p class="text-[11px] text-amber-700">Creates the product &amp; plan via PayPal REST API and fills the Plan ID fields below.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" wire:click="generatePayPalPlan('sandbox')" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition-all disabled:opacity-50 cursor-pointer">
                        <svg wire:loading.remove wire:target="generatePayPalPlan('sandbox')" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <svg wire:loading wire:target="generatePayPalPlan('sandbox')" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Generate Sandbox Plan</span>
                    </button>
                    <button type="button" wire:click="generatePayPalPlan('live')" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold shadow-sm transition-all disabled:opacity-50 cursor-pointer">
                        <svg wire:loading.remove wire:target="generatePayPalPlan('live')" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <svg wire:loading wire:target="generatePayPalPlan('live')" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Generate Live Plan</span>
                    </button>
                </div>
            </div>

            {{-- Plan ID Fields (populated or manually editable) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Test / Sandbox Plan ID</label>
                    <input type="text" wire:model="paypal_sandbox_plan_id"
                        placeholder="P-xxxxxxxxxxxxxxxxxxxxxxxx"
                        class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono placeholder-slate-300">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-amber-700 block mb-1 uppercase tracking-wider">Live Plan ID</label>
                    <input type="text" wire:model="paypal_live_plan_id"
                        placeholder="P-xxxxxxxxxxxxxxxxxxxxxxxx"
                        class="w-full px-3 py-2 bg-white border border-amber-200 text-slate-800 rounded-xl text-xs focus:outline-none focus:border-amber-400 font-mono placeholder-slate-300">
                </div>
            </div>
        </div>

    </div>
</div>
{{-- ── End Payment Processor IDs ───────────────────────────────────────────── --}}
