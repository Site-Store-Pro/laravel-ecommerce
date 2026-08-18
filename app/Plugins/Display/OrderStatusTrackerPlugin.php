<?php

namespace App\Plugins\Display;

use App\Models\Order;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use App\Services\CurrencyService;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Log;

class OrderStatusTrackerPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'order-tracker-2026';
    }

    public function name(): string
    {
        return 'Order Status Tracker 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $langId = app(LanguageService::class)->currentId();
            $settings = $plugin->getSettingsForLanguage($langId);

            $headerTitle      = $params['header']              ?? $settings['header_title']       ?? 'Track Your Order';
            $orderNumberLabel = $params['order_number_label']  ?? $settings['order_number_label'] ?? 'Order Number';
            $emailLabel       = $params['email_label']         ?? $settings['email_label']        ?? 'Email Address';
            $buttonLabel      = $params['button_label']        ?? $settings['button_label']       ?? 'Track Order';
            $errorNotFound    = $params['error_not_found']     ?? $settings['error_not_found']    ?? 'No order found matching the provided order number and email address.';
            $statusLabel      = $params['status_label']        ?? $settings['status_label']       ?? 'Order Status';
            $dateLabel        = $params['date_label']          ?? $settings['date_label']         ?? 'Order Date';
            $totalLabel       = $params['total_label']         ?? $settings['total_label']        ?? 'Order Total';
            $trackingLabel    = $params['tracking_label']      ?? $settings['tracking_label']     ?? 'Shipping Tracking';
            $itemsLabel       = $params['items_label']         ?? $settings['items_label']        ?? 'Ordered Items';

            $inputOrderNum = trim(request()->input('ost_order_number', ''));
            $inputEmail    = strtolower(trim(request()->input('ost_email', '')));
            $hasSubmitted  = request()->has('ost_submit') || (!empty($inputOrderNum) && !empty($inputEmail));

            $order = null;
            $error = null;
            $successMessage = null;

            if ($hasSubmitted) {
                if (empty($inputOrderNum) || empty($inputEmail)) {
                    $error = $errorNotFound;
                } else {
                    $order = $this->lookupOrder($inputOrderNum, $inputEmail);
                    if (!$order) {
                        $error = $errorNotFound;
                    } elseif (request()->has('ost_cancel_sub_id')) {
                        $cancelDetailId = (int) request()->input('ost_cancel_sub_id');
                        $detailToCancel = $order->details->firstWhere('id', $cancelDetailId);
                        if ($detailToCancel && $detailToCancel->active_subscription) {
                            try {
                                app(\App\Services\Payments\SubscriptionService::class)->cancelSubscription($detailToCancel, 'Cancelled by customer via Order Status Tracker lookup');
                                $successMessage = siteLabel('account.cancel_success', 'Subscription has been cancelled successfully.');
                                // Refresh order details
                                $order = $this->lookupOrder($inputOrderNum, $inputEmail);
                            } catch (\Throwable $e) {
                                Log::error("OrderStatusTrackerPlugin cancel subscription error: " . $e->getMessage());
                                $error = siteLabel('account.cancel_failed', 'Failed to cancel subscription: ') . $e->getMessage();
                            }
                        }
                    }
                }
            }

            $instanceId = 'order_tracker_' . uniqid();
            $actionUrl  = request()->url();
            $currencySym = CurrencyService::symbol();

            $html = '<div id="' . $instanceId . '" class="order-tracker-plugin-wrapper w-full max-w-2xl mx-auto my-6 p-6 sm:p-8 bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-3xl shadow-sm">';

            if (!empty($headerTitle)) {
                $html .= '<div class="mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">';
                $html .= '<h3 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">' . e($headerTitle) . '</h3>';
                $html .= '<p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Enter your order details below to view current fulfillment status and tracking info.</p>';
                $html .= '</div>';
            }

            // Success message display
            if ($successMessage) {
                $html .= '<div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl flex items-start gap-3 text-emerald-700 dark:text-emerald-300 text-sm font-semibold">';
                $html .= '<svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                $html .= '<div>' . e($successMessage) . '</div>';
                $html .= '</div>';
            }

            // Error display
            if ($error) {
                $html .= '<div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl flex items-start gap-3 text-rose-700 dark:text-rose-300 text-sm font-semibold">';
                $html .= '<svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                $html .= '<div>' . e($error) . '</div>';
                $html .= '</div>';
            }

            // Lookup Form
            $html .= '<form action="' . e($actionUrl) . '" method="GET" class="space-y-4 mb-6">';
            $html .= '<div>';
            $html .= '<label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">' . e($orderNumberLabel) . ' *</label>';
            $html .= '<input type="text" name="ost_order_number" value="' . e($inputOrderNum) . '" placeholder="e.g. VY0RR0MICZ" required ';
            $html .= 'class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-indigo-500 font-mono">';
            $html .= '</div>';

            $html .= '<div>';
            $html .= '<label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">' . e($emailLabel) . ' *</label>';
            $html .= '<input type="email" name="ost_email" value="' . e($inputEmail) . '" placeholder="e.g. customer@example.com" required ';
            $html .= 'class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-700/60 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-indigo-500">';
            $html .= '</div>';

            $html .= '<button type="submit" name="ost_submit" value="1" ';
            $html .= 'class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm focus:outline-none flex items-center justify-center gap-2">';
            $html .= '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
            $html .= e($buttonLabel);
            $html .= '</button>';
            $html .= '</form>';

            // Render Order Result Details if found (matching Order Success page details)
            if ($order) {
                $statusDisplay = $order->statusList ? $order->statusList->customerdisplay : siteLabel('success.payment_received', 'Payment Received - Order Being Processed.');
                $customerCountry = $order->user?->shipping_countrycode ?? 'US';
                $taxLabel     = CurrencyService::taxLabel($customerCountry);
                $vatInclusive = CurrencyService::isVatInclusive();
                $crossBorder  = CurrencyService::isCrossBorderExport($customerCountry);
                $vatEmbed     = $vatInclusive && !$crossBorder
                    ? CurrencyService::extractVat((float)$order->order_subtotal, CurrencyService::merchantVatRate())
                    : 0.0;

                $html .= '<div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 space-y-6">';

                // 1. Order Status Display Card
                $html .= '<div class="bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800/80 rounded-2xl p-5 text-center shadow-sm">';
                $html .= '<span class="text-xs font-bold text-indigo-400 dark:text-indigo-400 block uppercase tracking-wider mb-1">' . e($statusLabel) . '</span>';
                $html .= '<p class="text-base font-extrabold text-indigo-800 dark:text-indigo-200">' . e($statusDisplay) . '</p>';
                if (!empty($order->order_shipping_tracking)) {
                    $html .= '<div class="mt-3 pt-3 border-t border-indigo-100 dark:border-indigo-800/50 flex flex-wrap items-center justify-center gap-2 text-xs font-semibold text-indigo-700 dark:text-indigo-300">';
                    $html .= '<svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    $html .= '<span>' . e($trackingLabel) . ': <code class="font-mono bg-white dark:bg-slate-800 px-2.5 py-1 rounded-md border border-indigo-200 dark:border-indigo-700 font-bold text-slate-800 dark:text-slate-100">' . e($order->order_shipping_tracking) . '</code></span>';
                    $html .= '</div>';
                }
                $html .= '</div>';

                // 2. Order Info Grid (Order #, Date, Customer, Email)
                $html .= '<div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-700 rounded-2xl p-5 grid grid-cols-2 gap-4 text-sm text-left shadow-sm">';
                $html .= '<div>';
                $html .= '<span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Order #</span>';
                $html .= '<span class="font-extrabold text-slate-800 dark:text-slate-100">' . e($order->order_invoice_no) . '</span>';
                $html .= '</div>';
                $html .= '<div>';
                $html .= '<span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">' . e($dateLabel) . '</span>';
                $html .= '<span class="font-bold text-slate-700 dark:text-slate-300">' . ($order->order_date ? $order->order_date->format('F d, Y h:i A') : 'N/A') . '</span>';
                $html .= '</div>';
                $html .= '<div class="pt-2 border-t border-slate-200/50 dark:border-slate-800">';
                $html .= '<span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">Customer Name</span>';
                $html .= '<span class="font-bold text-slate-700 dark:text-slate-300">' . e($order->user ? $order->user->name : siteLabel('success.guest_user', 'Guest User')) . '</span>';
                $html .= '</div>';
                $html .= '<div class="pt-2 border-t border-slate-200/50 dark:border-slate-800">';
                $html .= '<span class="text-xs font-bold text-slate-400 dark:text-slate-500 block uppercase tracking-wider">' . e($emailLabel) . '</span>';
                $html .= '<span class="font-semibold text-slate-600 dark:text-slate-400">' . e($order->user ? $order->user->email : '-') . '</span>';
                $html .= '</div>';
                $html .= '</div>';

                // 3. Ordered Items Details & Financial Summary
                if ($order->details && $order->details->isNotEmpty()) {
                    $html .= '<div class="text-left space-y-3 pt-1">';
                    $html .= '<h3 class="text-xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">' . e($itemsLabel) . '</h3>';
                    $html .= '<div class="space-y-3 bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-700 rounded-2xl p-5 sm:p-6 shadow-sm">';

                    foreach ($order->details as $item) {
                        $itemTitle = $item->item_name ?? $item->item_title ?? $item->product_name ?? 'Product Item';
                        $finalLinePrice = (float)($item->final_price ?? $item->item_price ?? 0) * (float)($item->item_qty ?? 1);
                        $discountPrice  = (float)($item->discount_price ?? 0);

                        $html .= '<div class="flex items-center justify-between text-sm gap-4 pb-3 border-b border-slate-200/60 dark:border-slate-800 last:pb-0 last:border-b-0">';
                        $html .= '<div class="flex-1">';
                        $html .= '<span class="font-bold text-slate-800 dark:text-slate-100">' . e($itemTitle) . '</span>';
                        $html .= '<span class="text-xs text-slate-500 dark:text-slate-400 block mt-0.5">Quantity: ' . number_format((float)($item->item_qty ?? 1), 0) . '</span>';

                        if (!empty($item->download_item) && !empty($order->order_external_id)) {
                            $downloadUrl = route('products.download', [$item->id, $order->order_external_id]);
                            $html .= '<div class="flex flex-wrap items-center gap-2 mt-1.5">';
                            $html .= '<span class="inline-block bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 text-[10px] px-1.5 py-0.5 rounded font-bold border border-teal-200 dark:border-teal-800">Ready for download</span>';
                            $html .= '<a href="' . e($downloadUrl) . '" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-bold rounded-lg transition duration-150 shadow-sm">';
                            $html .= '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>';
                            $html .= 'Download File';
                            $html .= '</a>';
                            $html .= '</div>';
                        } elseif (!empty($item->item_shippable)) {
                            $html .= '<span class="inline-block bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-[10px] px-1.5 py-0.5 rounded font-bold border border-indigo-200 dark:border-indigo-800 mt-1.5">Will be shipped</span>';
                        }

                        // Subscription Status & Cancel Button
                        if (!empty($item->active_subscription)) {
                            $cancelPrompt = siteLabel('account.cancel_confirm', 'Are you sure you want to cancel this recurring subscription?');
                            $cancelLabel = siteLabel('account.cancel_subscription', 'Cancel Subscription');
                            $activeLabel = siteLabel('account.subscription_active', 'Active Subscription');

                            $html .= '<div class="flex flex-wrap items-center gap-2 mt-2">';
                            $html .= '<span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10px] px-2 py-0.5 rounded-full font-bold border border-emerald-200 dark:border-emerald-800">';
                            $html .= '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ' . e($activeLabel);
                            $html .= '</span>';
                            $html .= '<form action="' . e($actionUrl) . '" method="GET" class="inline" onsubmit="return confirm(\'' . addslashes($cancelPrompt) . '\');">';
                            $html .= '<input type="hidden" name="ost_order_number" value="' . e($inputOrderNum) . '">';
                            $html .= '<input type="hidden" name="ost_email" value="' . e($inputEmail) . '">';
                            $html .= '<input type="hidden" name="ost_cancel_sub_id" value="' . (int)$item->id . '">';
                            $html .= '<button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-lg text-xs font-bold transition duration-150 shadow-xs cursor-pointer">';
                            $html .= '<svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> ';
                            $html .= e($cancelLabel);
                            $html .= '</button>';
                            $html .= '</form>';
                            $html .= '</div>';
                        } elseif (!empty($item->subscription) && empty($item->active_subscription)) {
                            $cancelledLabel = siteLabel('account.subscription_cancelled', 'Cancelled Subscription');
                            $html .= '<div class="mt-2">';
                            $html .= '<span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] px-2 py-0.5 rounded-full font-bold border border-slate-200 dark:border-slate-700">';
                            $html .= '<span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> ' . e($cancelledLabel);
                            $html .= '</span>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';

                        $html .= '<div class="text-right shrink-0">';
                        $html .= '<span class="font-bold text-slate-900 dark:text-slate-100 block">' . $currencySym . number_format($finalLinePrice, 2) . '</span>';
                        if ($discountPrice > 0) {
                            $origLinePrice = ($finalLinePrice + ($discountPrice * (float)($item->item_qty ?? 1)));
                            $html .= '<span class="line-through text-slate-400 text-[10px] block">' . $currencySym . number_format($origLinePrice, 2) . '</span>';
                        }
                        $html .= '</div>';
                        $html .= '</div>';
                    }

                    // Financial Summary Breakdown
                    $html .= '<div class="border-t border-slate-200/80 dark:border-slate-700 mt-4 pt-4 space-y-2">';

                    // Subtotal
                    $html .= '<div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">';
                    $html .= '<span>Subtotal</span>';
                    $html .= '<span class="font-semibold text-slate-800 dark:text-slate-200">' . $currencySym . number_format((float)$order->order_subtotal, 2) . '</span>';
                    $html .= '</div>';

                    // Discounts
                    if ((float)$order->order_discounts > 0) {
                        $html .= '<div class="flex justify-between text-xs text-emerald-600 dark:text-emerald-400 font-semibold">';
                        $html .= '<span>Promotional Discount</span>';
                        $html .= '<span>-' . $currencySym . number_format((float)$order->order_discounts, 2) . '</span>';
                        $html .= '</div>';
                    }

                    // Taxes / VAT
                    if ($vatInclusive && !$crossBorder) {
                        if ($vatEmbed > 0) {
                            $html .= '<div class="flex justify-between text-xs text-slate-400">';
                            $html .= '<span class="italic">Includes ' . e($taxLabel) . ' ' . $currencySym . number_format($vatEmbed, 2) . '</span>';
                            $html .= '<span></span>';
                            $html .= '</div>';
                        }
                    } else {
                        $html .= '<div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">';
                        $html .= '<span>' . e($taxLabel) . '</span>';
                        $html .= '<span class="font-semibold text-slate-800 dark:text-slate-200">' . $currencySym . number_format((float)$order->order_taxes, 2) . '</span>';
                        $html .= '</div>';
                    }

                    // Shipping
                    $shippingName = !empty($order->order_shipping_method_name) ? $order->order_shipping_method_name : siteLabel('success.flat_rate', 'Flat Rate');
                    $html .= '<div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">';
                    $html .= '<span>Shipping (' . e($shippingName) . ')</span>';
                    $html .= '<span class="font-semibold text-slate-800 dark:text-slate-200">' . $currencySym . number_format((float)$order->order_shipping, 2) . '</span>';
                    $html .= '</div>';

                    // Handling
                    if ((float)$order->order_handling > 0) {
                        $html .= '<div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">';
                        $html .= '<span>Handling Surcharge</span>';
                        $html .= '<span class="font-semibold text-slate-800 dark:text-slate-200">' . $currencySym . number_format((float)$order->order_handling, 2) . '</span>';
                        $html .= '</div>';
                    }

                    // Total
                    $html .= '<div class="border-t border-slate-200/80 dark:border-slate-700 pt-3 flex justify-between text-base font-extrabold text-slate-900 dark:text-white">';
                    $html .= '<span>Total Charged</span>';
                    $html .= '<span>' . $currencySym . number_format((float)$order->order_total, 2) . '</span>';
                    $html .= '</div>';

                    $html .= '</div>'; // summary
                    $html .= '</div>'; // space-y-3 box
                    $html .= '</div>'; // text-left section
                }

                // 4. Shipping Address Card (if applicable)
                if ($order->order_shipping_method == 1 && $order->user) {
                    $html .= '<div class="text-left space-y-2 pt-1">';
                    $html .= '<h3 class="text-xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Shipping Address</h3>';
                    $html .= '<div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-700 rounded-2xl p-5 text-sm text-slate-700 dark:text-slate-300 space-y-1 shadow-sm">';
                    $html .= '<p class="font-bold text-slate-900 dark:text-slate-100">' . e($order->user->name) . '</p>';
                    if (!empty($order->user->company)) {
                        $html .= '<p class="text-slate-500 dark:text-slate-400">' . e($order->user->company) . '</p>';
                    }
                    if (!empty($order->user->shipping_address1)) {
                        $html .= '<p>' . e($order->user->shipping_address1) . '</p>';
                    }
                    if (!empty($order->user->shipping_address2)) {
                        $html .= '<p>' . e($order->user->shipping_address2) . '</p>';
                    }
                    $cityStateZip = trim($order->user->shipping_city . ($order->user->shipping_state ? ', ' . $order->user->shipping_state : '') . ' ' . ($order->user->shopping_postalcode ?? $order->user->shipping_postalcode ?? ''));
                    if (!empty($cityStateZip)) {
                        $html .= '<p>' . e($cityStateZip) . '</p>';
                    }
                    if (!empty($order->user->shipping_country)) {
                        $html .= '<p class="font-semibold">' . e($order->user->shipping_country) . '</p>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                }

                // 5. Order Comments Card (if applicable)
                if (!empty($order->order_comments)) {
                    $html .= '<div class="text-left space-y-2 pt-1">';
                    $html .= '<h3 class="text-xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Order Comments</h3>';
                    $html .= '<div class="bg-slate-50 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-700 rounded-2xl p-5 text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap shadow-sm">';
                    $html .= e($order->order_comments);
                    $html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>'; // order found wrapper
            }

            $html .= '</div>';

            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss  = $params['custom_css'] ?? $settings['custom_css'] ?? '';

            $cssHtml = '';
            if (!empty($defaultCss) || !empty($customCss)) {
                $cssHtml = "<style>\n";
                if (!empty($defaultCss)) {
                    $cssHtml .= \App\Services\CssMinifierService::minify($defaultCss) . "\n";
                }
                if (!empty($customCss)) {
                    $cssHtml .= \App\Services\CssMinifierService::minify($customCss) . "\n";
                }
                $cssHtml .= "</style>";
            }

            return $cssHtml . $html;

        } catch (\Throwable $e) {
            Log::error('[OrderStatusTrackerPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: order-tracker-2026] ' . e($e->getMessage()) . ' -->';
        }
    }

    /**
     * Helper to lookup an order matching order identifier and customer email.
     */
    private function lookupOrder(string $orderNum, string $email): ?Order
    {
        return Order::with(['details', 'user', 'statusList'])
            ->where('order_invoice_no', $orderNum)
            ->whereHas('user', function ($query) use ($email) {
                $query->whereRaw('LOWER(email) = ?', [strtolower(trim($email))]);
            })
            ->first();
    }
}
