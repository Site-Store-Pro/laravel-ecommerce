{{--
    Payment Processor IDs — Variant Form Partial
    Included in both edit and create variant forms in admin-product-edit.blade.php
    Requires: $paddle_sandbox_price_id, $paddle_live_price_id,
              $stripe_sandbox_price_id, $stripe_live_price_id,
              $create_new_stripe_product, $stripe_billing_interval,
              $stripe_trial_enabled, $stripe_trial_days
--}}
<div class="mt-4" x-data="{ openProcessorIds: @json((bool)($paddle_sandbox_price_id || $paddle_live_price_id || $paddle_price || $paddle_interval || $stripe_sandbox_price_id || $stripe_live_price_id || $create_new_stripe_product)) }">
    <button type="button" @click="openProcessorIds = !openProcessorIds"
        class="flex items-center gap-2 text-xs font-bold text-slate-500 uppercase tracking-wider hover:text-indigo-600 transition-colors w-full text-left group">
        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="openProcessorIds ? 'rotate-90 text-indigo-500' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
        </svg>
        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
        </svg>
        Payment Processor IDs
        <span class="text-[10px] font-normal text-slate-400 normal-case tracking-normal ml-1">Paddle &amp; Stripe subscription settings</span>
        @if($paddle_sandbox_price_id || $paddle_live_price_id || $paddle_price || $paddle_interval || $stripe_sandbox_price_id || $stripe_live_price_id || $create_new_stripe_product)
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

                {{-- Free trial --}}
                <div class="flex flex-col">
                    <label class="text-[10px] font-bold text-indigo-500 block mb-1 uppercase tracking-wider">Free Trial Period</label>
                    <div class="flex items-center gap-2 mt-auto pb-0.5">
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
                            <input type="number" wire:model="stripe_trial_days"
                                min="1" max="365" placeholder="14"
                                class="w-20 px-2 py-1.5 bg-white border border-indigo-200 text-slate-800 rounded-lg text-xs focus:outline-none focus:border-indigo-400 font-mono">
                            <span class="text-[11px] text-indigo-600 font-medium whitespace-nowrap">days free</span>
                        @else
                            <span class="text-[11px] text-slate-400">No trial period</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
{{-- ── End Payment Processor IDs ───────────────────────────────────────────── --}}
