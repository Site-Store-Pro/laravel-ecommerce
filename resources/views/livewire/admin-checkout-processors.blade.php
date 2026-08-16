<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Page Header -->
        <div class="flex items-center gap-4">
           
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Processors &amp; Payments</h1>
                <p class="text-sm text-slate-500 mt-0.5">Configure active payment processors, checkout minimums, and manage the payment gateway list.</p>
            </div>
        </div>

        <x-toast-alert />

        <!-- ======================================================== -->
        <!-- Section 1: Checkout Configuration                        -->
        <!-- ======================================================== -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            <h2 class="text-base font-extrabold text-slate-800 pb-4 mb-6 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Checkout Configuration
            </h2>

            <form wire:submit.prevent="saveConfig" class="space-y-6">
                <!-- Processor Assignments -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Primary Processor</label>
                        <select wire:model="primary_processor" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            <option value="0">— None —</option>
                            @foreach($processors as $proc)
                                <option value="{{ $proc->processor_id }}">{{ $proc->processor_name }} {{ $proc->production ? '(Production)' : '(Sandbox)' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Secondary Processor</label>
                        <select wire:model="secondary_processor" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            <option value="-1">&mdash; None &mdash;</option>
                            @foreach($processors as $proc)
                                @if($proc->processor_id !== 0){{-- Test Processor not available for secondary/tertiary --}}
                                    <option value="{{ $proc->processor_id }}">{{ $proc->processor_name }} {{ $proc->production ? '(Production)' : '(Sandbox)' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Tertiary Processor</label>
                        <select wire:model="tertiary_processor" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            <option value="-1">&mdash; None &mdash;</option>
                            @foreach($processors as $proc)
                                @if($proc->processor_id !== 0){{-- Test Processor not available for secondary/tertiary --}}
                                    <option value="{{ $proc->processor_id }}">{{ $proc->processor_name }} {{ $proc->production ? '(Production)' : '(Sandbox)' }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Toggles + Minimums -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" wire:model="randomize_processor" class="sr-only peer">
                                <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Randomize Processor Selection</span>
                        </label>
                        @if(false)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" wire:model="paypal_express" class="sr-only peer">
                                <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">Enable PayPal Express</span>
                        </label>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Retail Minimum ($)</label>
                            <input type="number" step="0.01" min="0" wire:model="retail_minimum"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            @error('retail_minimum') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Wholesale Minimum ($)</label>
                            <input type="number" step="0.01" min="0" wire:model="wholesale_minimum"
                                   class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            @error('wholesale_minimum') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveConfig"
                            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-sm font-bold rounded-xl shadow-md shadow-indigo-200 hover:opacity-90 transition-all flex items-center gap-2">
                        <svg wire:loading wire:target="saveConfig" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Save Checkout Configuration
                    </button>
                </div>
            </form>
        </div>

        <!-- ======================================================== -->
        <!-- Section 2: Payment Processors CRUD                       -->
        <!-- ======================================================== -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            <h2 class="text-base font-extrabold text-slate-800 pb-4 mb-6 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Payment Processors
            </h2>

            <!-- Delete Confirmation -->
            @if($showDeleteProcessorConfirm)
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center justify-between gap-4 flex-wrap">
                    <span class="text-sm font-bold text-red-700">⚠ Are you sure you want to delete this processor?</span>
                    <div class="flex items-center gap-2">
                        <button wire:click="deleteProcessor" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-500 transition-all">Yes, Delete</button>
                        <button wire:click="cancelDeleteProcessor" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">Cancel</button>
                    </div>
                </div>
            @endif

            <!-- Processors Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50 rounded-xl">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Processor Name</th>
                            <th class="px-4 py-3">Mode</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($processors as $proc)
                            <tr class="hover:bg-slate-50/50">
                                @if($edit_processor_id === $proc->id)
                                    <td class="px-4 py-3 text-slate-500 font-mono text-xs">{{ $proc->processor_id }}</td>
                                    <td class="px-4 py-3">
                                        @if(in_array($proc->processor_id, [1, 2, 3]))
                                            <div class="font-semibold text-slate-800">{{ $proc->processor_name }}</div>
                                        @else
                                            <input type="text" wire:model="edit_processor_name"
                                                   class="w-full px-3 py-1.5 bg-white border border-indigo-300 text-slate-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-sm">
                                            @error('edit_processor_name') <span class="text-xs text-red-500 block mt-1">{{ $message }}</span> @enderror
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" wire:model.number="edit_processor_production" class="sr-only peer" true-value="1" false-value="0">
                                                <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                            </div>
                                            <span class="text-xs font-semibold {{ $edit_processor_production ? 'text-emerald-700' : 'text-slate-400' }}">{{ $edit_processor_production ? 'Production' : 'Sandbox' }}</span>
                                        </label>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="saveEditProcessor" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-500 transition-all">Save</button>
                                            <button wire:click="cancelEditProcessor" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-all">Cancel</button>
                                        </div>
                                    </td>
                                @else
                                    <td class="px-4 py-3 text-slate-400 font-mono text-xs">{{ $proc->processor_id }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $proc->processor_name }}</td>
                                    <td class="px-4 py-3">
                                        @if($proc->processor_id === 0)
                                            {{-- Test processor: no mode badge, it is always test-only --}}
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-full text-xs font-bold">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Test Mode
                                            </span>
                                        @elseif($proc->production)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-xs font-bold">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Production
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-full text-xs font-bold">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span> Sandbox
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Edit: hidden for the test processor (processor_id = 0) --}}
                                            @if($proc->processor_id !== 0)
                                                <button wire:click="startEditProcessor({{ $proc->id }})" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">Edit</button>
                                            @endif
                                            {{-- Delete: hidden for the built-in processors (Test, Stripe, Paddle, PayPal) --}}
                                            @if($proc->processor_id >= 100)
                                                <button wire:click="confirmDeleteProcessor({{ $proc->id }})" class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-all">Delete</button>
                                            @endif
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400 italic">No payment processors defined yet. Add one below.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Stripe-specific configuration options -->
            <div class="mt-6 pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Stripe Specific Settings</h3>
                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" wire:model.live="stripe_address_required" wire:change="saveConfig" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                        <div>
                            <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 block">Stripe Address Requirement</span>
                            <span class="text-xs text-slate-500">Require the customer's full address during Stripe checkout. When disabled, only the card fields will be shown.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Add New Processor Form — disabled. Change false to true below to re-enable. --}}
            @if(false)
                <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-4">Add New Processor</h3>
                    <div class="flex items-end gap-4 flex-wrap">
                        <div class="flex-1 min-w-[200px]">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Processor Name</label>
                            <input type="text" wire:model="new_processor_name" placeholder="e.g. Stripe, Authorize.net, PayPal"
                                   class="w-full px-3 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-xl focus:outline-none focus:border-indigo-500 font-medium text-sm">
                            @error('new_processor_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1.5">Mode</label>
                            <label class="flex items-center gap-2 cursor-pointer h-[42px]">
                                <div class="relative">
                                    <input type="checkbox" wire:model.number="new_processor_production" class="sr-only peer" true-value="1" false-value="0">
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </div>
                                <span class="text-sm font-semibold {{ $new_processor_production ? 'text-emerald-700' : 'text-amber-600' }}">{{ $new_processor_production ? 'Production' : 'Sandbox' }}</span>
                            </label>
                        </div>
                        <button wire:click="addProcessor" wire:loading.attr="disabled" wire:target="addProcessor"
                                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 transition-all flex items-center gap-2">
                            <svg wire:loading wire:target="addProcessor" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Add Processor
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- ======================================================== -->
        <!-- Section 3: Payments Log                                  -->
        <!-- ======================================================== -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            <h2 class="text-base font-extrabold text-slate-800 pb-4 mb-6 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Payments Log
                <span class="ml-auto text-xs font-semibold text-slate-400">25 per page</span>
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-4 py-3">Method</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Auth Code</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 text-xs text-slate-500">{{ $payment->payment_date->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3">
                                    @if($payment->order)
                                        <a href="{{ route('admin.ecommerce.order-details', $payment->order->id) }}" wire:navigate
                                           class="font-bold text-indigo-600 hover:underline">
                                            {{ $payment->order->order_invoice_no }}
                                        </a>
                                    @else
                                        <span class="text-slate-400 italic text-xs">Order removed</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-slate-800">${{ number_format($payment->payment_amount, 2) }}</td>
                                <td class="px-4 py-3 text-xs text-slate-600">{{ $payment->payment_method }}</td>
                                <td class="px-4 py-3">
                                    @if($payment->payment_status == 1)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full text-[10px] font-bold">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Paid
                                        </span>
                                    @elseif($payment->payment_status == 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-700 border border-red-100 rounded-full text-[10px] font-bold">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Failed
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">{{ $payment->payment_status }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-indigo-600">{{ $payment->authorization_code }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-400 italic">No payment records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
                <div class="mt-6">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>

        {{-- ================================================================ --}}
        {{-- Section 4: Checkout Custom Fields                                --}}
        {{-- ================================================================ --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
                <h2 class="text-base font-extrabold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Checkout Custom Fields
                </h2>
                <button type="button" wire:click="addCheckoutField"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Field
                </button>
            </div>

            @if(session('fields_status'))
                <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold rounded-2xl">
                    {{ session('fields_status') }}
                </div>
            @endif

            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Add custom fields that appear during checkout. <strong>Checkout</strong> position fields appear below the shipping/customer info form on Step 1.
                <strong>Billing Page</strong> position fields appear above the payment section on Step 2, with optional user-type filtering.
            </p>

            @if(empty($checkoutFields))
                <div class="text-center py-10 text-slate-400 text-sm italic border border-dashed border-slate-200 rounded-2xl">
                    No checkout fields configured. Click <strong>Add Field</strong> to get started.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($checkoutFields as $i => $field)
                        <div class="border border-slate-200 rounded-2xl overflow-hidden {{ $field['is_active'] ? '' : 'opacity-60' }}">

                            {{-- Field header row --}}
                            <div class="flex items-center gap-3 px-4 py-3 bg-slate-50 cursor-pointer"
                                 wire:click="toggleEditCheckoutField({{ $i }})">
                                {{-- Type badge --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-100 text-indigo-700 uppercase tracking-wide shrink-0">{{ $field['type'] }}</span>
                                {{-- Position badge --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $field['position'] === 'checkout' ? 'bg-sky-100 text-sky-700' : 'bg-violet-100 text-violet-700' }} uppercase tracking-wide shrink-0">
                                    {{ $field['position'] === 'checkout' ? 'Checkout' : 'Billing Page' }}
                                </span>
                                @if($field['position'] === 'billing')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-slate-200 text-slate-600 uppercase tracking-wide shrink-0">{{ $field['show_for'] }}</span>
                                @endif
                                <span class="flex-1 text-sm font-semibold text-slate-700 truncate">{{ $field['label'] ?: '(untitled field)' }}</span>
                                @if($field['is_required'])
                                    <span class="text-red-400 text-[10px] font-bold uppercase shrink-0">Required</span>
                                @endif

                                {{-- Reorder --}}
                                <div class="flex gap-1 shrink-0" onclick="event.stopPropagation()">
                                    <button type="button" wire:click.stop="moveCheckoutFieldUp({{ $i }})" @if($i === 0) disabled @endif
                                            class="p-1 rounded-lg hover:bg-slate-200 disabled:opacity-30 text-slate-500 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                    </button>
                                    <button type="button" wire:click.stop="moveCheckoutFieldDown({{ $i }})" @if($i === count($checkoutFields)-1) disabled @endif
                                            class="p-1 rounded-lg hover:bg-slate-200 disabled:opacity-30 text-slate-500 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <button type="button" wire:click.stop="removeCheckoutField({{ $i }})"
                                            class="p-1 rounded-lg hover:bg-red-100 text-slate-400 hover:text-red-600 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Expanded editor --}}
                            @if($editingFieldIndex === $i)
                                <div class="p-5 space-y-5 border-t border-slate-100">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                        {{-- Field Type --}}
                                        <div>
                                            <label class="text-xs font-bold text-slate-600 block mb-1.5">Field Type</label>
                                            <select wire:model.live="checkoutFields.{{ $i }}.type"
                                                    class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                                <option value="input">Text Input</option>
                                                <option value="textarea">Textarea</option>
                                                <option value="select">Dropdown (Select)</option>
                                                <option value="radio">Radio Group</option>
                                                <option value="checkbox">Single Checkbox</option>
                                                <option value="checkbox_group">Checkbox Group</option>
                                            </select>
                                        </div>

                                        {{-- Label --}}
                                        <div>
                                            <label class="text-xs font-bold text-slate-600 block mb-1.5">Label <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="checkoutFields.{{ $i }}.label" placeholder="Field label"
                                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                            @error("checkoutFields.{$i}.label") <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Instructions --}}
                                        <div class="md:col-span-2">
                                            <label class="text-xs font-bold text-slate-600 block mb-1.5">Instructions <span class="text-slate-400 font-normal">(small text below label)</span></label>
                                            <input type="text" wire:model="checkoutFields.{{ $i }}.instructions" placeholder="Optional helper text"
                                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                        </div>

                                        {{-- Position --}}
                                        <div>
                                            <label class="text-xs font-bold text-slate-600 block mb-2">Position</label>
                                            <div class="flex gap-2">
                                                @foreach(['checkout' => 'Checkout (Step 1)', 'billing' => 'Billing Page (Step 2)'] as $pval => $plbl)
                                                    <label class="cursor-pointer">
                                                        <input type="radio" wire:model.live="checkoutFields.{{ $i }}.position" value="{{ $pval }}" class="sr-only">
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                            {{ ($field['position'] ?? 'checkout') === $pval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                                            {{ $plbl }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Show For (billing position only) --}}
                                        @if(($field['position'] ?? 'checkout') === 'billing')
                                            <div>
                                                <label class="text-xs font-bold text-slate-600 block mb-2">Show For</label>
                                                <div class="flex gap-2">
                                                    @foreach(['both' => 'All Users', 'public' => 'Public Only', 'wholesale' => 'Wholesale Only'] as $sval => $slbl)
                                                        <label class="cursor-pointer">
                                                            <input type="radio" wire:model.live="checkoutFields.{{ $i }}.show_for" value="{{ $sval }}" class="sr-only">
                                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                                {{ ($field['show_for'] ?? 'both') === $sval ? 'bg-violet-600 text-white border-violet-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-violet-300' }}">
                                                                {{ $slbl }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Options for select/radio/checkbox_group --}}
                                    @if(in_array($field['type'] ?? 'input', ['select', 'radio', 'checkbox_group']))
                                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl space-y-3">
                                            <div class="flex items-center justify-between">
                                                <label class="text-xs font-bold text-slate-600">Options</label>
                                                <button type="button" wire:click="addCheckoutFieldOption({{ $i }})"
                                                        class="text-xs text-indigo-600 font-bold hover:underline">+ Add Option</button>
                                            </div>
                                            @forelse($field['options'] ?? [] as $oi => $opt)
                                                <div class="flex items-center gap-2">
                                                    <input type="text" wire:model="checkoutFields.{{ $i }}.options.{{ $oi }}" placeholder="Option text"
                                                           class="flex-1 px-3 py-1.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                                    <button type="button" wire:click="removeCheckoutFieldOption({{ $i }}, {{ $oi }})"
                                                            class="text-slate-400 hover:text-red-500 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @empty
                                                <p class="text-xs text-slate-400 italic">No options yet. Click "Add Option" above.</p>
                                            @endforelse
                                        </div>
                                    @endif

                                    {{-- Required settings --}}
                                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                                        <div class="flex items-center gap-3">
                                            <button type="button" wire:click="$set('checkoutFields.{{ $i }}.is_required', {{ $field['is_required'] ? 'false' : 'true' }})"
                                                    class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors duration-200 {{ $field['is_required'] ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $field['is_required'] ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                            </button>
                                            <span class="text-sm font-semibold text-slate-700">Required field</span>
                                        </div>
                                        @if($field['is_required'])
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-xs font-bold text-slate-600 block mb-1.5">Validation Type</label>
                                                    <select wire:model="checkoutFields.{{ $i }}.required_type"
                                                            class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                                        <option value="non_blank">Not Empty</option>
                                                        <option value="email">Valid Email</option>
                                                        <option value="numeric">Numeric</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="text-xs font-bold text-slate-600 block mb-1.5">Error Message <span class="text-slate-400 font-normal">(optional)</span></label>
                                                    <input type="text" wire:model="checkoutFields.{{ $i }}.required_error_message"
                                                           placeholder="e.g. This field is required."
                                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Active toggle --}}
                                    <div class="flex items-center gap-3">
                                        <button type="button" wire:click="$set('checkoutFields.{{ $i }}.is_active', {{ $field['is_active'] ? 'false' : 'true' }})"
                                                class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors duration-200 {{ $field['is_active'] ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform duration-200 {{ $field['is_active'] ? 'translate-x-4.5' : 'translate-x-0.5' }}"></span>
                                        </button>
                                        <span class="text-sm text-slate-600">Field is <strong>{{ $field['is_active'] ? 'active' : 'inactive' }}</strong></span>
                                    </div>

                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-6 flex justify-end">
                <button type="button" wire:click="saveCheckoutFields"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Checkout Fields
                </button>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- Section 5: Checkout Mailing List Opt-in                         --}}
        {{-- ================================================================ --}}
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
            <h2 class="text-base font-extrabold text-slate-800 pb-4 mb-6 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Checkout Mailing List Opt-in
            </h2>

            <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                Automatically or optionally subscribe customers to a mailing list when they complete an order.
                The customer's <strong>name and email</strong> are taken from their checkout account — no extra fields needed.
            </p>

            <div class="space-y-6">

                {{-- Mode --}}
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-3">Opt-in Mode</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['off' => 'Off', 'auto' => 'Auto (subscribe everyone)', 'manual' => 'Manual (show checkbox)'] as $mval => $mlbl)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="checkoutOptinMode" value="{{ $mval }}" class="sr-only">
                                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold border transition duration-150
                                    {{ $checkoutOptinMode === $mval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                    {{ $mlbl }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-slate-400 mt-2 leading-relaxed">
                        <strong>Auto</strong>: every completed order silently subscribes the customer's email+name. &nbsp;
                        <strong>Manual</strong>: a checkbox appears at checkout so the customer can choose.
                    </p>
                </div>

                @if($checkoutOptinMode !== 'off')
                    @if($checkoutOptinMode === 'manual')
                        {{-- Checkbox label --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-1.5">Checkbox Label</label>
                                <input type="text" wire:model="checkoutOptinLabel" placeholder="Yes, add me to the mailing list"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm text-slate-800 focus:outline-none focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-700 block mb-2">Checkbox Position</label>
                                <div class="flex gap-2">
                                    @foreach(['checkout' => 'Checkout (Step 1)', 'billing' => 'Billing Page (Step 2)'] as $pval => $plbl)
                                        <label class="cursor-pointer">
                                            <input type="radio" wire:model.live="checkoutOptinPosition" value="{{ $pval }}" class="sr-only">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition duration-150
                                                {{ $checkoutOptinPosition === $pval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                                {{ $plbl }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Provider --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-2">Provider</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['mailchimp' => 'Mailchimp', 'constant_contact' => 'Constant Contact', 'klaviyo' => 'Klaviyo'] as $pval => $plbl)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="checkoutOptinProvider" value="{{ $pval }}" class="sr-only">
                                    <span class="inline-flex items-center px-3 py-2 rounded-xl text-xs font-bold border transition duration-150
                                        {{ $checkoutOptinProvider === $pval ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-indigo-300' }}">
                                        {{ $plbl }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- List ID --}}
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1.5">List / Audience ID <span class="text-red-500">*</span></label>
                        <p class="text-[10px] text-slate-400 mb-2">The list or audience ID from your provider's dashboard (not the API key).</p>
                        <input type="text" wire:model="checkoutOptinListId" placeholder="e.g. abc123def456"
                               class="w-full md:w-96 px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono text-slate-800 focus:outline-none focus:border-indigo-400">
                    </div>

                    {{-- .env hint --}}
                    <div class="p-3 bg-amber-50 border border-amber-100 rounded-2xl text-xs text-amber-800 leading-relaxed">
                        <strong>Provider API key</strong> must be set in <code class="bg-amber-100 px-1 rounded">.env</code>:
                        @if($checkoutOptinProvider === 'mailchimp')
                            <code class="block mt-1 text-[10px]">MAILCHIMP_API_KEY=…</code>
                            <code class="block text-[10px]">MAILCHIMP_SERVER_PREFIX=us1</code>
                        @elseif($checkoutOptinProvider === 'constant_contact')
                            <code class="block mt-1 text-[10px]">CONSTANT_CONTACT_API_KEY=…</code>
                        @elseif($checkoutOptinProvider === 'klaviyo')
                            <code class="block mt-1 text-[10px]">KLAVIYO_API_KEY=…</code>
                        @else
                            Select a provider above to see the required key.
                        @endif
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" wire:click="saveOptinSettings"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Opt-in Settings
                </button>
            </div>
        </div>

    </div>
</div>
