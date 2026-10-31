<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt — Order {{ $order->order_invoice_no }} | {{ $companyName }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                background: #ffffff !important;
                color: #0f172a !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .page-break-inside-avoid {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 antialiased min-h-screen py-8 px-4 sm:px-6 lg:px-8 print:py-0 print:px-0">

    <!-- Top Admin Action Bar (Hidden when printing) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between flex-wrap gap-3 bg-white border border-slate-200 rounded-2xl px-5 py-3.5 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ecommerce.order-details', $order->id) }}"
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-xs font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Order Details
            </a>
            <span class="text-xs text-slate-400 hidden sm:inline">|</span>
            <span class="text-xs font-semibold text-slate-600 hidden sm:inline">Printer-Friendly Receipt Preview</span>
        </div>

        <div class="flex items-center gap-2.5">
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.settings') }}#invoice-header-settings"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Invoice Header Settings
                </a>
            @endif

            <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Receipt
            </button>
        </div>
    </div>

    <!-- Printable Receipt Sheet -->
    <div class="receipt-container max-w-4xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-sm p-8 sm:p-10 space-y-6">

        <!-- 1. Dynamic Company / Invoice Header -->
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-6 border-b-2 border-slate-900">
            <div class="space-y-1.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $companyName }}
                </h1>
                @if(!empty($companyAddress))
                    <div class="text-sm text-slate-600 leading-relaxed">
                        {!! nl2br(e($companyAddress)) !!}
                    </div>
                @endif
                @if(!empty($companyPhoneEmail))
                    <div class="text-sm font-medium text-slate-700 pt-0.5">
                        {{ $companyPhoneEmail }}
                    </div>
                @endif
            </div>

            <div class="sm:text-right space-y-1 shrink-0">
                <div class="inline-block px-3 py-1 bg-slate-100 border border-slate-300 rounded-lg text-xs font-extrabold uppercase tracking-widest text-slate-800">
                    Order Receipt / Invoice
                </div>
                <div class="pt-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Order / Invoice #</span>
                    <span class="text-base font-extrabold text-slate-900 font-mono">{{ $order->order_invoice_no }}</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Order Date</span>
                    <span class="text-sm font-semibold text-slate-700">{{ $order->order_date?->format('F d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- 2. Order Status & Customer Overview Banner -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 grid grid-cols-1 sm:grid-cols-3 gap-4 page-break-inside-avoid">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Order Status</span>
                <span class="text-sm font-extrabold text-emerald-700 mt-0.5 block">
                    {{ $order->statusList ? $order->statusList->customerdisplay : 'Payment Received - Order Being Processed.' }}
                </span>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Customer</span>
                <span class="text-sm font-bold text-slate-900 mt-0.5 block">
                    {{ $order->user ? $order->user->name : 'Guest Customer' }}
                </span>
                @if($order->user?->email)
                    <span class="text-xs text-slate-600 block">{{ $order->user->email }}</span>
                @endif
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Order Date</span>
                <span class="text-sm font-bold text-slate-800 mt-0.5 block">
                    {{ $order->order_date?->format('F d, Y h:i A') }}
                </span>
            </div>
        </div>

        <!-- 4. Shipping Confirmation / Tracking Information (Included when not empty) -->
        @if(!empty($order->order_shipping_tracking) || !empty($order->order_shipping_date))
            <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 page-break-inside-avoid">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8l1.293 9.293A1 1 0 007.28 18h9.44a1 1 0 00.987-.836L19 8M10 12h4"/>
                    </svg>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-emerald-900">
                        Shipping Confirmation &amp; Tracking Information
                    </h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    @if(!empty($order->order_shipping_date))
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700/80 uppercase tracking-wider block">Shipped Date</span>
                            <span class="font-bold text-slate-900">{{ $order->order_shipping_date->format('F d, Y') }}</span>
                        </div>
                    @endif
                    @if(!empty($order->order_shipping_method_name))
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700/80 uppercase tracking-wider block">Shipping Method</span>
                            <span class="font-bold text-slate-900">{{ $order->order_shipping_method_name }}</span>
                        </div>
                    @endif
                    @if(!empty($order->order_shipping_tracking))
                        <div>
                            <span class="text-[11px] font-bold text-emerald-700/80 uppercase tracking-wider block">Tracking Number</span>
                            <span class="font-extrabold text-indigo-700 font-mono break-all">{{ $order->order_shipping_tracking }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- 5. Items Ordered Section -->
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="bg-slate-50 px-5 py-3 border-b border-slate-200">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">Items Ordered</h2>
            </div>

            <div class="divide-y divide-slate-200">
                @foreach($order->details as $item)
                    @php
                        $rawOpts = $item->options_list ? json_decode($item->options_list, true) : [];
                        $opts = isset($rawOpts['customizations']) && is_array($rawOpts['customizations'])
                            ? $rawOpts['customizations']
                            : (is_array($rawOpts) ? $rawOpts : []);
                        $product = $item->variant?->product;
                        $hasRedirect = $product && !empty($product->completion_redirect);
                        $accessToken = $item->contentAccessToken;
                        $lineTotal = \App\Services\CurrencyService::format((float) $item->final_price * $item->item_qty);
                        $origTotal = \App\Services\CurrencyService::format(((float) $item->final_price + (float) $item->discount_price) * $item->item_qty);
                    @endphp
                    <div class="p-5 flex items-start justify-between gap-4 page-break-inside-avoid">
                        <div class="space-y-1.5 flex-1">
                            <div class="font-bold text-slate-900 text-sm">
                                {{ $item->item_name }}
                            </div>

                            @if(!empty($opts))
                                <div class="pl-3 border-l-2 border-slate-300 space-y-0.5 text-xs text-slate-600">
                                    @foreach($opts as $opt)
                                        @if(is_array($opt))
                                            @php
                                                $optName = $opt['OptionName'] ?? $opt['label'] ?? '';
                                                $optVal = $opt['Value'] ?? $opt['value'] ?? '';
                                            @endphp
                                            @if($optName !== '' || $optVal !== '')
                                                <div>
                                                    @if($optName !== '')
                                                        <span class="font-semibold text-slate-700">{{ $optName }}:</span>
                                                    @endif
                                                    <span>{{ $optVal }}</span>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-600">
                                <span class="font-semibold">Qty: {{ $item->item_qty }}</span>
                                <span>&bull;</span>
                                @if($item->download_item)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 font-bold text-[11px]">
                                        Digital Download
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-50 text-amber-800 font-bold text-[11px]">
                                        Shippable Item
                                    </span>
                                @endif
                            </div>

                            @if($item->download_item || ($hasRedirect && $accessToken))
                                <div class="flex flex-wrap items-center gap-2 pt-1.5">
                                    @if($item->download_item)
                                        @php
                                            $downloadUrl = route('products.download', [$item->order_detail_external_id ?: $item->id, $order->order_external_id]);
                                        @endphp
                                        <a href="{{ $downloadUrl }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-indigo-600 text-white text-[11px] font-bold hover:bg-indigo-500">
                                            &#11015; Download File ({{ $downloadUrl }})
                                        </a>
                                    @endif
                                    @if($hasRedirect && $accessToken)
                                        @php
                                            $contentUrl = route('content.access', $accessToken->token);
                                        @endphp
                                        <a href="{{ $contentUrl }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-emerald-600 text-white text-[11px] font-bold hover:bg-emerald-500">
                                            &#128279; View Content ({{ $contentUrl }})
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="text-right shrink-0">
                            <div class="font-extrabold text-slate-900 text-sm">{{ $lineTotal }}</div>
                            @if($item->discount_price > 0)
                                <div class="text-xs text-slate-400 line-through">{{ $origTotal }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 6. Financial Summary & Shipping Address / Comments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 page-break-inside-avoid">
            <!-- Left Column: Shipping Address & Order Comments -->
            <div class="md:col-span-6 space-y-4">
                @if($order->order_shipping_method == 1 && $order->user)
                    <div class="border border-slate-200 rounded-xl p-5 bg-white">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">
                            Shipping Address
                        </h3>
                        <div class="text-sm text-slate-700 leading-relaxed">
                            <div class="font-bold text-slate-900">{{ $order->user->name }}</div>
                            @if(!empty($order->user->company))
                                <div>{{ $order->user->company }}</div>
                            @endif
                            <div>{{ $order->user->shipping_address1 }}</div>
                            @if(!empty($order->user->shipping_address2))
                                <div>{{ $order->user->shipping_address2 }}</div>
                            @endif
                            <div>
                                {{ $order->user->shipping_city }}@if(!empty($order->user->shipping_state)), {{ $order->user->shipping_state }}@endif {{ $order->user->shopping_postalcode }}
                            </div>
                            <div>{{ $order->user->shipping_country }}</div>
                        </div>
                    </div>
                @endif

                @if(!empty($order->order_comments))
                    <div class="border border-amber-200 rounded-xl p-5 bg-amber-50/60">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-amber-900 mb-1.5">
                            Order Comments
                        </h3>
                        <p class="text-sm text-amber-900 leading-relaxed whitespace-pre-line">{{ $order->order_comments }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Financial Summary -->
            <div class="{{ ($order->order_shipping_method == 1 && $order->user) || !empty($order->order_comments) ? 'md:col-span-6' : 'md:col-span-12' }}">
                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50 space-y-2.5 text-sm">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 pb-2 border-b border-slate-200">
                        Financial Summary
                    </h3>

                    @if($showSubtotal)
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900">{{ \App\Services\CurrencyService::format((float) $order->order_subtotal) }}</span>
                        </div>
                    @endif

                    @if($order->order_discounts > 0)
                        <div class="flex justify-between text-emerald-700">
                            <span>Promotional Discount</span>
                            <span class="font-bold">-{{ \App\Services\CurrencyService::format((float) $order->order_discounts) }}</span>
                        </div>
                    @endif

                    @if($showTax)
                        @if($isExport)
                            <div class="flex justify-between text-emerald-700">
                                <span>{{ $taxLabel }}</span>
                                <span class="font-bold">{{ \App\Services\CurrencyService::format(0) }}</span>
                            </div>
                        @elseif($isVatInclusive)
                            <div class="flex justify-between text-slate-600">
                                <span>Includes {{ $taxLabel }}</span>
                                <span class="font-semibold text-slate-900">{{ \App\Services\CurrencyService::format($vatIncludedAmount) }}</span>
                            </div>
                        @else
                            <div class="flex justify-between text-slate-600">
                                <span>{{ $taxLabel }}</span>
                                <span class="font-semibold text-slate-900">{{ \App\Services\CurrencyService::format((float) $order->order_taxes) }}</span>
                            </div>
                        @endif
                    @endif

                    @if($showShipping)
                        <div class="flex justify-between text-slate-600">
                            <span>Shipping ({{ $order->order_shipping_method_name ?? 'Standard' }})</span>
                            <span class="font-semibold text-slate-900">{{ \App\Services\CurrencyService::format((float) $order->order_shipping) }}</span>
                        </div>
                    @endif

                    @if($order->order_handling > 0)
                        <div class="flex justify-between text-slate-600">
                            <span>Handling Surcharge</span>
                            <span class="font-semibold text-slate-900">{{ \App\Services\CurrencyService::format((float) $order->order_handling) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center pt-3 border-t-2 border-slate-300 text-base font-extrabold text-slate-900">
                        <span>Total Charged</span>
                        <span class="text-indigo-700">{{ \App\Services\CurrencyService::format((float) $order->order_total) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
