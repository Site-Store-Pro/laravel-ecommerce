<?php

namespace App\Livewire;

use App\Models\CheckoutCustomField;
use App\Models\CmsSetting;
use App\Models\CmsPage;
use App\Models\ContentAccessToken;
use App\Models\Order;
use App\Models\OrderCheckoutOption;
use App\Models\OrderDetail;
use App\Models\OrderPayment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShoppingCartLog;
use App\Plugins\Support\PluginManager;
use App\Plugins\Support\ShippingContext;
use App\Services\CurrencyService;
use App\Services\OptinService;
use App\Services\Payments\PaymentProcessorManager;
use App\Services\TaxCalculationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class OrderReview extends Component
{
    // Payment processor state
    public int    $activeProcessorId  = 0;   // persisted across Livewire requests for randomized checkout
    public string $gatewayToken       = '';  // payment_intent_id (Stripe) or transaction_id (Paddle)
    public string $stripeClientSecret = ''; // returned to JS for stripe.confirmCardPayment()
    public string $paddleTransactionId = ''; // returned to JS to open Paddle.Checkout.open()
    public string $paddleClientToken  = ''; // Paddle.js initialisation token
    public string $paddleEnvironment  = 'sandbox';
    public string $stripePublishableKey = '';
    public bool   $paymentReady       = false; // true once preparePayment() has run
    public bool   $stripeIsSubscription = false; // true when Stripe subscription flow is active

    // PayPal state
    public string $paypalOrderId      = '';
    public string $paypalClientId     = '';
    public string $currencyCode       = '';

    public bool $requiresShipping = false;
    public string $selectedShippingOption = '';
    public string $orderComments = '';
    public bool $allowComments = false;

    // Billing-position custom fields + opt-in
    public array $billingCustomData = [];   // values keyed by field index
    public bool  $billingOptIn      = false; // manual opt-in checkbox at billing position

    private function getCartSessionId(): string
    {
        $cookieName = 'cart_session_id';
        $sessionId = request()->cookie($cookieName);

        if (!$sessionId) {
            $sessionId = (string) \Illuminate\Support\Str::uuid();
            cookie()->queue($cookieName, $sessionId, 60 * 24 * 30); // 30 days
        }

        return $sessionId;
    }

    private function getCartQuery()
    {
        $sessionId = $this->getCartSessionId();
        $userId = Auth::id() ?? 0;

        return ShoppingCartLog::query()
            ->where('order_id', 0)
            ->where(function($query) use ($sessionId, $userId) {
                if ($userId > 0) {
                    $query->where('user_id', $userId)
                          ->orWhere(function($sub) use ($sessionId) {
                              $sub->where('cart_log_session', $sessionId)->where('user_id', 0);
                          });
                } else {
                    $query->where('cart_log_session', $sessionId)->where('user_id', 0);
                }
            });
    }

    /**
     * Build the combined list of shipping options from:
     * 1. The existing flat-rate ShippingCalculationService (admin-configured rules)
     * 2. Any active shipping plugins (FedEx, UPS, USPS, etc.) — realtime API rates
     *
     * Returns options sorted low-to-high by amount.
     * Each item: ['id' => string, 'name' => string, 'amount' => float]
     */
    private function buildShippingOptions(
        float $subtotal,
        float $totalWeight,
        int $itemCount,
        string $toZip,
        string $countryCode,
        string $stateCode
    ): array {
        // 1. Flat-rate / admin-configured options
        $flatOptions = \App\Services\ShippingCalculationService::getAvailableOptions(
            $subtotal, $totalWeight, $itemCount, $countryCode, $stateCode
        );

        // 2. Realtime plugin rates
        $pluginOptions = [];
        try {
            $user = Auth::user();
            $context = new ShippingContext(
                fromZip:       '', // plugins fall back to their own configured from-zip
                toZip:         $toZip ?: ($user->shopping_postalcode ?? ''),
                toCountry:     $countryCode,
                weightLbs:     max(0.1, $totalWeight),
                declaredValue: $subtotal,
            );

            $pluginRates = app(PluginManager::class)->getShippingRates($context);

            foreach ($pluginRates as $rate) {
                $pluginOptions[] = [
                    'id'     => 'plugin_' . $rate['code'],
                    'name'   => $rate['label'] . ($rate['days'] ? ' (' . $rate['days'] . ' day' . ($rate['days'] > 1 ? 's' : '') . ')' : ''),
                    'amount' => $rate['rate'],
                ];
            }
        } catch (\Throwable $e) {
            \Log::error('[OrderReview] Plugin shipping rates error: ' . $e->getMessage());
        }

        // 3. Merge and sort all options low-to-high by amount
        $allOptions = array_merge($flatOptions, $pluginOptions);
        usort($allOptions, fn($a, $b) => $a['amount'] <=> $b['amount']);

        return $allOptions;
    }

    public function mount(): void
    {
        // Must be authenticated (either returning user or guest registered in step 1)
        if (!Auth::check()) {
            redirect()->route('shop.checkout');
            return;
        }

        $this->requiresShipping = $this->getCartQuery()->where('item_shippable', 1)->exists();

        if ($this->getCartQuery()->count() === 0) {
            redirect()->route('shop.cart');
            return;
        }

        // Load comments config
        $config = \Illuminate\Support\Facades\DB::table('shipping_configurations')->first();
        $this->allowComments = $config ? (bool)$config->allow_comments : false;

        // Default the selected shipping option
        if ($this->requiresShipping) {
            $user = Auth::user();
            $items = $this->getCartQuery()->get();
            $discountResult = \App\Services\DiscountService::applyDiscountsToCart($items, $user);
            $subtotal = $discountResult['adjusted_subtotal'];

            $totalWeight = 0;
            foreach ($items as $item) {
                $totalWeight += $item->item_qty * $item->item_weight;
            }
            $itemCount = $items->sum('item_qty');

            $options = $this->buildShippingOptions(
                $subtotal, $totalWeight, $itemCount,
                $user->shopping_postalcode ?? '',
                $user->shipping_countrycode ?? 'US',
                $user->shipping_state ?? ''
            );

            if (!empty($options)) {
                $this->selectedShippingOption = $options[0]['id'];
            }
        } else {
            $this->selectedShippingOption = 'digital_only';
        }

        $this->currencyCode = strtoupper(CurrencyService::code());
        $this->getActiveProcessorId();
    }

    public function getActiveProcessorId(): int
    {
        if ($this->activeProcessorId <= 0) {
            $manager = app(PaymentProcessorManager::class);
            $this->activeProcessorId = $manager->activeProcessorId();
        }

        return $this->activeProcessorId;
    }

    private function calculateTotals(): array
    {
        $items = $this->getCartQuery()->get();
        $user  = Auth::user();

        $customerCountry = strtoupper($user->shipping_countrycode ?? 'US');
        $vatInclusive    = CurrencyService::isVatInclusive();
        $crossBorder     = CurrencyService::isCrossBorderExport($customerCountry);

        $discountResult  = \App\Services\DiscountService::applyDiscountsToCart($items, $user);
        $subtotal        = $discountResult['subtotal'];
        $discountAmount  = $discountResult['total_discount'];
        $adjustedSubtotal = $discountResult['adjusted_subtotal'];
        $discountsList   = $discountResult['discounts'];

        // For VAT-inclusive + cross-border export: strip VAT out of the subtotal
        // (prices in DB are assumed to include the merchant's standard VAT rate)
        if ($vatInclusive && $crossBorder) {
            $adjustedSubtotal = TaxCalculationService::adjustSubtotalForVat($adjustedSubtotal, $customerCountry);
            $subtotal         = TaxCalculationService::adjustSubtotalForVat($subtotal, $customerCountry);
        }

        $freeShipping = false;
        foreach ($discountsList as $d) {
            if (!empty($d['free_shipping'])) {
                $freeShipping = true;
            }
        }

        $shippableCount = $this->getCartQuery()->where('item_shippable', 1)->count();
        $shippable = $shippableCount > 0;

        $shippingFee = 0.00;
        $shippingMethodName = 'Digital Delivery';
        $options = [];

        if ($shippable) {
            $totalWeight = 0;
            foreach ($items as $item) {
                $totalWeight += $item->item_qty * $item->item_weight;
            }
            $itemCount = $items->sum('item_qty');

            $options = $this->buildShippingOptions(
                $adjustedSubtotal,
                $totalWeight,
                $itemCount,
                $user->shopping_postalcode ?? '',
                $customerCountry,
                $user->shipping_state ?? ''
            );

            // Find selected option
            $selectedOpt = collect($options)->firstWhere('id', $this->selectedShippingOption);
            if (!$selectedOpt && !empty($options)) {
                $selectedOpt = $options[0];
                $this->selectedShippingOption = $selectedOpt['id'];
            }

            if ($selectedOpt) {
                $shippingFee = $freeShipping ? 0.00 : (double)$selectedOpt['amount'];
                $shippingMethodName = $selectedOpt['name'];
            } else {
                $shippingFee = $freeShipping ? 0.00 : 10.00;
                $shippingMethodName = 'Flat Rate Shipping';
            }
        }

        // Calculate Handling Surcharge
        $handlingFee = 0.00;
        if ($shippable) {
            $totalWeight = 0;
            foreach ($items as $item) {
                $totalWeight += $item->item_qty * $item->item_weight;
            }
            $itemCount = $items->sum('item_qty');
            $handlingFee = \App\Services\HandlingChargeService::calculateHandlingCharge(
                $adjustedSubtotal,
                $totalWeight,
                $itemCount,
                $customerCountry
            );
        }

        // Calculate Taxes/VAT
        // For VAT-inclusive merchants: addedTax = 0 (already in price).
        // We separately compute the embedded VAT amount for display.
        $taxes    = 0.00;
        $vatEmbed = 0.00; // amount of VAT embedded in the subtotal (VAT-inclusive merchants)

        if ($shippable) {
            $address = [
                'country_code' => $customerCountry,
                'state_code'   => $user->shipping_state ?? '',
            ];

            if ($vatInclusive && !$crossBorder) {
                // Domestic VAT-inclusive: tax is embedded — extract for display
                $taxableSubtotal = 0.00;
                foreach ($items as $item) {
                    if ($item->item_taxable) {
                        $taxableSubtotal += $item->item_qty * $item->item_price;
                    }
                }
                $vatEmbed = TaxCalculationService::extractedVat($taxableSubtotal, $customerCountry);
                $taxes    = 0.00; // not added on top
            } elseif (!$vatInclusive) {
                // US/CA merchant: calculate tax to add on top
                $taxableSubtotal = 0.00;
                foreach ($items as $item) {
                    if ($item->item_taxable) {
                        $taxableSubtotal += $item->item_qty * $item->item_price;
                    }
                }
                $taxes = TaxCalculationService::calculateTax($taxableSubtotal, $address);
            }
            // cross-border export: taxes = 0, vatEmbed = 0 (VAT stripped from subtotal)
        }

        $total = $adjustedSubtotal + $taxes + $shippingFee + $handlingFee;

        return [
            'items'             => $items,
            'subtotal'          => $subtotal,
            'discountAmount'    => $discountAmount,
            'adjustedSubtotal'  => $adjustedSubtotal,
            'discountsList'     => $discountsList,
            'shippingFee'       => $shippingFee,
            'shippingMethodName'=> $shippingMethodName,
            'handlingFee'       => $handlingFee,
            'taxes'             => $taxes,
            'vatEmbed'          => $vatEmbed,
            'vatInclusive'      => $vatInclusive,
            'crossBorder'       => $crossBorder,
            'customerCountry'   => $customerCountry,
            'taxLabel'          => CurrencyService::taxLabel($customerCountry),
            'currencySymbol'    => CurrencyService::symbol(),
            'total'             => $total,
            'options'           => $shippable ? $options : [],
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 1: Prepare payment (called by the "Place Order" button via JS)
    // Returns gateway-specific data so the frontend JS can charge the card.
    // ─────────────────────────────────────────────────────────────────────
    public function preparePayment(): array
    {
        if (!Auth::check()) {
            return ['error' => 'Not authenticated'];
        }

        $totals      = $this->calculateTotals();
        $total       = $totals['total'];
        $currency    = strtolower(CurrencyService::code());
        $processorId = $this->getActiveProcessorId();
        $manager     = app(PaymentProcessorManager::class);
        $type        = $manager->activeProcessorType($processorId);
        $user        = Auth::user();

        // Detect subscription variant in the current cart
        $subVariant = $this->resolveSubscriptionVariant();

        try {
            if ($type === 'stripe') {
                /** @var \App\Services\Payments\Processors\StripeProcessor $driver */
                $driver = $manager->resolveActive($processorId);
                $isSandbox = $driver->isSandbox();

                if ($subVariant) {
                    // ── Stripe Subscription flow ─────────────────────────────
                    $stripePrice = $isSandbox
                        ? ($subVariant->stripe_sandbox_price_id ?? null)
                        : ($subVariant->stripe_live_price_id    ?? null);

                    $result = $driver->createSubscription($total, $currency, [
                        'stripe_price_id'    => $stripePrice ?: null, // null = create on-the-fly
                        'stripe_customer_id' => $user->stripe_customer_id ?? null,
                        'customer_email'     => $user->email,
                        'customer_name'      => $user->name ?? null,
                        'product_name'       => $subVariant->sku,
                        'billing_interval'   => $subVariant->stripe_billing_interval ?? 'month',
                        'trial_days'         => $subVariant->stripe_trial_enabled
                                                    ? (int) $subVariant->stripe_trial_days
                                                    : 0,
                    ]);

                    // Persist newly-created Stripe customer ID on user record
                    if (empty($user->stripe_customer_id) && !empty($result['customer_id'])) {
                        $user->stripe_customer_id = $result['customer_id'];
                        $user->save();
                    }

                    $this->stripePublishableKey  = $driver->getPublishableKey();
                    $this->stripeClientSecret    = $result['client_secret'];
                    $this->stripeIsSubscription  = true;
                    $this->paymentReady          = true;

                    return [
                        'processor'       => 'stripe',
                        'publishableKey'  => $this->stripePublishableKey,
                        'clientSecret'    => $this->stripeClientSecret,
                        'isSubscription'  => true,
                        'trialDays'       => $result['trial_days'] ?? 0,
                        'stripeAddressRequired' => (bool) (\App\Models\OrderCheckoutOption::first()->stripe_address_required ?? false),
                    ];

                } else {
                    // ── Stripe one-time PaymentIntent flow ───────────────────
                    $intent = $driver->createPaymentIntent($total, $currency);

                    $this->stripePublishableKey = $driver->getPublishableKey();
                    $this->stripeClientSecret   = $intent['client_secret'];
                    $this->stripeIsSubscription = false;
                    $this->paymentReady         = true;

                    return [
                        'processor'      => 'stripe',
                        'publishableKey' => $this->stripePublishableKey,
                        'clientSecret'   => $this->stripeClientSecret,
                        'isSubscription' => false,
                        'stripeAddressRequired' => (bool) (\App\Models\OrderCheckoutOption::first()->stripe_address_required ?? false),
                    ];
                }

            } elseif ($type === 'paddle') {
                /** @var \App\Services\Payments\Processors\PaddleProcessor $driver */
                $driver    = $manager->resolveActive($processorId);
                $isSandbox = $driver->isSandbox();

                // Validate that all dynamically created subscription items have the exact same interval and frequency
                $dynamicIntervals = [];
                foreach ($totals['items'] as $item) {
                    if (empty($item->variant_id)) {
                        continue;
                    }
                    $variant = ProductVariant::find($item->variant_id);
                    if ($variant) {
                        $paddlePriceId = $isSandbox
                            ? ($variant->paddle_sandbox_price_id ?? null)
                            : ($variant->paddle_live_price_id    ?? null);
                        
                        // Final price of item (after item-level and order-level discounts)
                        $finalUnitPrice = (float) $item->item_price;
                        if ($totals['subtotal'] > 0 && $totals['discountAmount'] > 0) {
                            $finalUnitPrice = $finalUnitPrice * (1 - ($totals['discountAmount'] / $totals['subtotal']));
                        }
                        if ($totals['vatInclusive'] && $totals['crossBorder']) {
                            $finalUnitPrice = \App\Services\TaxCalculationService::adjustSubtotalForVat($finalUnitPrice, $totals['customerCountry'] ?? 'US');
                        }
                        
                        $isDynamic = empty($paddlePriceId) || (float)$finalUnitPrice !== (float)$variant->paddle_price;
                        
                        if ($isDynamic && !empty($variant->paddle_interval)) {
                            $dynamicIntervals[] = strtolower($variant->paddle_interval) . ':' . ($variant->paddle_frequency ?: 1);
                        }
                    }
                }

                if (count(array_unique($dynamicIntervals)) > 1) {
                    return ['error' => 'All subscription items in the cart must have the same billing interval and frequency for dynamic Paddle checkout.'];
                }

                // Resolve Paddle price ID if variant has one configured (single item helper)
                $paddlePriceId = null;
                if ($subVariant) {
                    $paddlePriceId = $isSandbox
                        ? ($subVariant->paddle_sandbox_price_id ?? null)
                        : ($subVariant->paddle_live_price_id    ?? null);
                }

                $txn = $driver->createTransaction($total, strtoupper($currency), [
                    'customer_email'    => $user->email,
                    'order_description' => 'Order from ' . config('app.name'),
                    'paddle_price_id'   => $paddlePriceId,
                    'cart_items'        => $totals['items'],
                    'discount_amount'   => $totals['discountAmount'],
                    'subtotal'          => $totals['subtotal'],
                    'shipping_fee'      => $totals['shippingFee'],
                    'handling_fee'      => $totals['handlingFee'],
                    'vat_inclusive'     => $totals['vatInclusive'],
                    'cross_border'      => $totals['crossBorder'],
                    'customer_country'  => $totals['customerCountry'] ?? 'US',
                ]);

                $this->paddleTransactionId = $txn['transaction_id'];
                $this->paddleClientToken   = $txn['client_token'];
                $this->paddleEnvironment   = $txn['environment'];
                $this->paymentReady        = true;

                return [
                    'processor'     => 'paddle',
                    'transactionId' => $this->paddleTransactionId,
                    'clientToken'   => $this->paddleClientToken,
                    'environment'   => $this->paddleEnvironment,
                ];

            } elseif ($type === 'paypal') {
                /** @var \App\Services\Payments\Processors\PayPalProcessor $driver */
                $driver = $manager->resolveActive($processorId);
                $orderId = $driver->createOrder($total, $currency);

                $this->paypalOrderId = $orderId;
                $this->paypalClientId = $driver->getClientId();
                $this->currencyCode = $currency;
                $this->paymentReady = true;

                return [
                    'processor' => 'paypal',
                    'orderId'   => $orderId,
                    'clientId'  => $this->paypalClientId,
                ];

            } else {
                // Test processor — no gateway work needed
                $this->paymentReady = true;
                return ['processor' => 'test'];
            }

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('preparePayment failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Scan the active cart for a subscription-type variant.
     * Returns the first one found, or null for a standard cart.
     */
    private function resolveSubscriptionVariant(): ?ProductVariant
    {
        $cartItems = ShoppingCartLog::where('order_id', 0)
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('cart_log_session', $this->getCartSessionId());
            })
            ->get();

        foreach ($cartItems as $item) {
            if (empty($item->variant_id)) {
                continue;
            }
            $variant = ProductVariant::find($item->variant_id);
            if ($variant && $variant->isSubscriptionVariant()) {
                return $variant;
            }
        }

        return null;
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 2: Place order (called after gateway has confirmed the payment)
    // $gatewayToken = payment_intent_id (Stripe) | transaction_id (Paddle) | '' (Test)
    // ─────────────────────────────────────────────────────────────────────
    public function placeOrder(string $gatewayToken = '')
    {
        if (!Auth::check()) {
            return redirect()->route('shop.checkout');
        }

        // ── Validate billing-position custom fields BEFORE any payment call ─────────
        $user         = Auth::user();
        $isWholesale  = $user->isWholesale();

        $billingFields = CheckoutCustomField::where('position', 'billing')
            ->where('is_active', true)
            ->where(function ($q) use ($isWholesale) {
                $q->where('show_for', 'both')
                  ->orWhere('show_for', $isWholesale ? 'wholesale' : 'public');
            })
            ->orderBy('sort_order')
            ->get();

        foreach ($billingFields as $idx => $cf) {
            if (!$cf->is_required) continue;
            $val   = trim((string) ($this->billingCustomData[$idx] ?? ''));
            $error = $cf->required_error_message ?: 'This field is required.';
            if ($cf->required_type === 'email' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError("billingCustomData.{$idx}", $error);
                return;
            } elseif ($cf->required_type === 'numeric' && !is_numeric($val)) {
                $this->addError("billingCustomData.{$idx}", $error);
                return;
            } elseif (empty($val)) {
                $this->addError("billingCustomData.{$idx}", $error);
                return;
            }
        }

        $totals = $this->calculateTotals();
        $items  = $totals['items'];

        if ($items->isEmpty()) {
            session()->flash('error', 'Your shopping cart is empty.');
            return;
        }

        // Resolve active processor and charge / verify
        $processorId = $this->getActiveProcessorId();
        $manager     = app(PaymentProcessorManager::class);
        $driver      = $manager->resolve($processorId);
        $currency    = strtoupper(CurrencyService::code());

        $payload = match ($manager->activeProcessorType($processorId)) {
            'stripe' => ['payment_intent_id' => $gatewayToken],
            'paddle' => ['transaction_id'    => $gatewayToken],
            'paypal' => ['order_id'          => $gatewayToken],
            // For test processor, read gatewayToken from Livewire property (synced by wire:model radio).
            // '' = simulate success, 'fail' = simulate decline.
            default  => ['simulate' => $this->gatewayToken],
        };

        $payResult = $driver->charge($totals['total'], $currency, $payload);

        if (!$payResult->success) {
            session()->flash('error', 'Payment failed: ' . ($payResult->errorMessage ?? 'Please try again.'));
            return;
        }

        // Generate a unique invoice number
        do {
            $invoiceNo = strtoupper(\Illuminate\Support\Str::random(10));
        } while (Order::where('order_invoice_no', $invoiceNo)->exists());

        $shippableCount = $this->getCartQuery()->where('item_shippable', 1)->count();
        $shippable = $shippableCount > 0;
        $user = Auth::user();

        // Create Order
        $order = Order::create([
            'order_invoice_no' => $invoiceNo,
            'order_external_id' => (string) \Illuminate\Support\Str::uuid(),
            'order_user_id' => $user->id,
            'order_status' => $shippable ? 1 : 7, // 1 = Pending, 7 = Completed
            'order_date' => now(),
            'order_subtotal' => $totals['adjustedSubtotal'],
            'order_taxes' => $totals['taxes'],
            'order_discounts' => $totals['discountAmount'],
            'order_shipping' => $totals['shippingFee'],
            'order_shipping_date' => null,
            'order_shipping_method' => $shippable ? 1 : 0,
            'order_shipping_tracking' => null,
            'order_download' => $shippable ? 0 : 1,
            'order_total' => $totals['total'],
            'order_handling' => $totals['handlingFee'],
            'order_comments'            => $this->allowComments ? $this->orderComments : null,
            'order_shipping_method_name'=> $totals['shippingMethodName'],
        ]);

        // ── Save merged custom field data to the order ────────────────────────────────
        $billingLabelledData = [];
        foreach ($billingFields as $idx => $cf) {
            $billingLabelledData[$cf->label] = $this->billingCustomData[$idx] ?? null;
        }
        $mergedCustomData = array_merge(
            session('checkout_custom_data', []),
            $billingLabelledData
        );
        if (!empty($mergedCustomData)) {
            $order->update(['custom_field_data' => $mergedCustomData]);
        }

        // ── Handle mailing list opt-in ────────────────────────────────────────────────
        try {
            $optinMode = CmsSetting::get('checkout_optin_mode', 'off');
            $shouldSubscribe = match ($optinMode) {
                'auto'   => true,
                'manual' => $this->billingOptIn || (bool) session('checkout_opt_in', false),
                default  => false,
            };
            if ($shouldSubscribe) {
                $provider  = CmsSetting::get('checkout_optin_provider', '');
                $listId    = CmsSetting::get('checkout_optin_list_id', '');
                if ($provider && $listId) {
                    OptinService::subscribe($user->email, $user->name, $provider, $listId);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[Checkout Opt-in] Subscribe failed: ' . $e->getMessage());
        }

        // Create Order Details and deduct inventory
        foreach ($items as $item) {
            preg_match('/\(([^)]+)\)$/', $item->item_name, $matches);
            $sku = $matches[1] ?? '';
            $variant = ProductVariant::where('sku', $sku)->first();

            $attrs = json_decode($item->item_attributes, true) ?: [];
            $customizations = $attrs['customizations'] ?? [];
            $optionsFee = 0.00;
            foreach ($customizations as $cust) {
                if (isset($cust['price_modifier'])) {
                    $optionsFee += (float)$cust['price_modifier'];
                }
            }

            OrderDetail::create([
                'order_id' => $order->id,
                'item_name' => $item->item_name,
                'item_qty' => $item->item_qty,
                'final_price' => $item->item_price,
                'base_price' => $item->item_price - $optionsFee,
                'discount_price' => $item->item_discount_price,
                'options_fee' => $optionsFee,
                'options_list' => json_encode($customizations),
                'inventory_id' => $variant ? $variant->id : 0,
                'item_taxable' => (int)($item->item_taxable ?? 1),
                'download_item' => $item->item_downloadable,
                'download_expiration' => $item->item_downloadable
                    ? ($variant && $variant->download_expiration ? $variant->download_expiration : now()->addYear())
                    : null,
                'downloads_counter' => 0,
                'downloads_max_allowed' => $item->item_downloadable
                    ? ($variant && $variant->downloads_max_allowed !== null ? $variant->downloads_max_allowed : 100)
                    : null,
            ]);

            // Deduct stock from inventory
            if ($variant && !$variant->download_item && $variant->inventory) {
                $matchingInventory = null;
                if ($user->shipping_countrycode) {
                    $locationIds = \Illuminate\Support\Facades\DB::table('warehouse_locations')
                        ->where('country_code', $user->shipping_countrycode)
                        ->when($user->shipping_state, function ($q) use ($user) {
                            $q->where('state_code', $user->shipping_state);
                        })
                        ->pluck('id');
                    
                    if ($locationIds->isNotEmpty()) {
                        $matchingInventory = $variant->inventories()->whereIn('location_id', $locationIds)->first();
                    }
                }
                
                if (!$matchingInventory) {
                    $matchingInventory = $variant->inventories()->orderBy('location_id', 'asc')->first();
                }

                if ($matchingInventory) {
                    $matchingInventory->quantity_available = max(0, $matchingInventory->quantity_available - (int)$item->item_qty);
                    $matchingInventory->save();
                }
            }
        }

        // Register payment
        OrderPayment::create([
            'order_id'           => $order->id,
            'payment_date'       => now(),
            'payment_amount'     => $totals['total'],
            'payment_method'     => $payResult->processorName,
            'payment_status'     => 1, // Paid
            'authorization_code' => $payResult->authorizationCode,
            'processor_response' => $payResult->transactionId ?: 'No transaction ID',
        ]);

        // Associate completed order ID and user ID with the current shopping cart records
        $this->getCartQuery()->update([
            'order_id' => $order->id,
            'user_id' => $user->id,
        ]);

        // Generate a new cart session ID for the user's cookie so they get a fresh empty cart
        $newSessionId = (string) \Illuminate\Support\Str::uuid();
        cookie()->queue('cart_session_id', $newSessionId, 60 * 24 * 30); // 30 days

        // Handle discount / gift certificate post-order cleanup
        $couponCode = session('coupon_code');
        if ($couponCode) {
            $discount = Discount::where('code', $couponCode)->first();
            if ($discount) {
                $discount->times_redeemed = ($discount->times_redeemed ?? 0) + 1;
                // Single-use gift certificates (code_type = 1) are deactivated after redemption
                if ((int) $discount->code_type === 1) {
                    $discount->is_active = 0;
                }
                $discount->save();
            }
            session()->forget('coupon_code');
        }

        // Clear checkout custom field session data
        session()->forget(['checkout_custom_data', 'checkout_opt_in']);

        // Send Order Confirmation Email
        try {
            $statusText = $order->statusList ? $order->statusList->customerdisplay : 'Payment Received - Order Being Processed.';
            
            $itemsHtml = '<div style="margin-top: 24px; font-family: sans-serif; color: #1e293b;">';
            
            // Order details summary box
            $itemsHtml .= '<div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="padding-bottom: 12px;"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Status</span><strong style="color: #4f46e5; font-size: 14px;">' . e($statusText) . '</strong></td>';
            $itemsHtml .= '<td style="padding-bottom: 12px;" align="right"><span style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;">Order Date</span><strong style="color: #334155; font-size: 14px;">' . $order->order_date->format('F d, Y h:i A') . '</strong></td>';
            $itemsHtml .= '</tr>';
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Items Ordered section
            $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">' . e(siteLabel('email.items_ordered', 'Items Ordered')) . '</h3>';
            $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; margin-bottom: 24px; padding: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">';
            
            foreach ($order->details as $item) {
                $itemProduct = $item->variant?->product ?? null;
                $itemTitle   = ($itemProduct ? $itemProduct->getTranslated('title') : null) ?: $item->item_name;

                $itemTypeBadge = $item->download_item 
                    ? '<span style="background-color: #f0fdf4; color: #15803d; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bbf7d0; display: inline-block; margin-top: 4px;">' . e(siteLabel('email.digital_download', 'Digital Download')) . '</span>'
                    : ($item->item_shippable ? '<span style="background-color: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 4px; border: 1px solid #bae6fd; display: inline-block; margin-top: 4px;">' . e(siteLabel('email.shippable_item', 'Shippable Item')) . '</span>' : '');

                $itemsHtml .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . e($itemTitle) . '</strong>';
                $itemsHtml .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">' . e(siteLabel('email.quantity', 'Quantity')) . ': ' . number_format($item->item_qty, 0) . '</span>';
                $itemsHtml .= $itemTypeBadge;
                if ($item->download_item) {
                    $downloadUrl = route('products.download', [$item->id, $order->order_external_id]);
                    $itemsHtml .= '<div style="margin-top: 8px;">';
                    $itemsHtml .= '<a href="' . e($downloadUrl) . '" target="_blank" style="background-color: #4f46e5; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #4338ca;">' . e(siteLabel('email.download_file', 'Download File')) . '</a>';
                    $itemsHtml .= '</div>';
                }
                // View Content button — secure UUID token link, supports guest users
                if ($itemProduct) {
                    $contentUrl   = Product::resolveCompletionUrl($itemProduct->completion_redirect);
                    $contentLabel = $itemProduct->completionRedirectLabel();
                    if ($contentUrl) {
                        $recipientEmail = $order->user?->email ?? ($order->guest_email ?? '');
                        $accessToken = ContentAccessToken::generateOrRefresh($item, $contentUrl, $recipientEmail);
                        $tokenUrl    = route('content.access', $accessToken->token);
                        $itemsHtml .= '<div style="margin-top: 8px;">';
                        $itemsHtml .= '<a href="' . e($tokenUrl) . '" target="_blank" style="background-color: #7c3aed; color: #ffffff; font-size: 11px; font-weight: bold; padding: 6px 12px; border-radius: 6px; text-decoration: none; display: inline-block; border: 1px solid #6d28d9;">' . e($contentLabel) . '</a>';
                        $itemsHtml .= '</div>';
                    }
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '<td style="padding: 12px 0; vertical-align: top;" align="right">';
                $itemsHtml .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . CurrencyService::format($item->final_price * $item->item_qty) . '</strong>';
                if ($item->discount_price > 0) {
                    $itemsHtml .= '<span style="color: #94a3b8; font-size: 11px; text-decoration: line-through; display: block;">' . CurrencyService::format(($item->final_price + $item->discount_price) * $item->item_qty) . '</span>';
                }
                $itemsHtml .= '</td>';
                $itemsHtml .= '</tr>';
            }
            
            // Financial Summary Block
            $itemsHtml .= '<tr><td colspan="2" style="padding-top: 16px;">';
            $itemsHtml .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';
            
            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e(siteLabel('email.subtotal', 'Subtotal')) . '</td>';
            $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . CurrencyService::format((float)$order->order_subtotal) . '</td>';
            $itemsHtml .= '</tr>';

            if ($order->order_discounts > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;">' . e(siteLabel('email.promotional_discount', 'Promotional Discount')) . '</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #16a34a; padding-bottom: 8px;" align="right">-$' . number_format($order->order_discounts, 2) . '</td>';
                $itemsHtml .= '</tr>';
            }

            // Determine tax row label and display for the email
            $emailTaxLabel = CurrencyService::taxLabel($user->shipping_countrycode ?? 'US');
            $emailVatInclusive = CurrencyService::isVatInclusive();
            $emailCrossBorder  = CurrencyService::isCrossBorderExport($user->shipping_countrycode ?? 'US');

            if ($emailVatInclusive && !$emailCrossBorder) {
                // VAT-inclusive domestic: show embedded VAT amount as an informational row
                $emailVatRate   = CurrencyService::merchantVatRate();
                $emailVatAmount = CurrencyService::extractVat((float)$order->order_subtotal, $emailVatRate);
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e(siteLabel('email.includes', 'Includes')) . ' ' . e($emailTaxLabel) . '</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . CurrencyService::format($emailVatAmount) . '</td>';
                $itemsHtml .= '</tr>';
            } elseif (!$emailVatInclusive || ($emailVatInclusive && $emailCrossBorder)) {
                // US/CA merchant tax added on top, or cross-border export (VAT stripped, 0 tax)
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e($emailTaxLabel) . '</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . CurrencyService::format((float)$order->order_taxes) . '</td>';
                $itemsHtml .= '</tr>';
            }

            $itemsHtml .= '<tr>';
            $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e(siteLabel('email.shipping', 'Shipping')) . ' (' . e($totals['shippingMethodName']) . ')</td>';
            $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . CurrencyService::format((float)$order->order_shipping) . '</td>';
            $itemsHtml .= '</tr>';

            if ($order->order_handling > 0) {
                $itemsHtml .= '<tr>';
                $itemsHtml .= '<td style="font-size: 13px; color: #64748b; padding-bottom: 8px;">' . e(siteLabel('email.handling_surcharge', 'Handling Surcharge')) . '</td>';
                $itemsHtml .= '<td style="font-size: 13px; font-weight: 600; color: #334155; padding-bottom: 8px;" align="right">' . CurrencyService::format((float)$order->order_handling) . '</td>';
                $itemsHtml .= '</tr>';
            }

            $itemsHtml .= '<tr style="border-top: 1px solid #e2e8f0;">';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;">' . e(siteLabel('email.total_charged', 'Total Charged')) . '</td>';
            $itemsHtml .= '<td style="font-size: 16px; font-weight: 800; color: #0f172a; padding-top: 12px;" align="right">' . CurrencyService::format((float)$order->order_total) . '</td>';
            $itemsHtml .= '</tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</td></tr>';
            
            $itemsHtml .= '</table>';
            $itemsHtml .= '</div>';

            // Shipping Address section if required
            if ($order->order_shipping_method == 1 && $order->user) {
                $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">' . e(siteLabel('email.shipping_address', 'Shipping Address')) . '</h3>';
                $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 24px;">';
                $itemsHtml .= '<strong style="color: #0f172a; display: block; margin-bottom: 4px;">' . e($order->user->name) . '</strong>';
                if ($order->user->company) {
                    $itemsHtml .= '<span style="color: #64748b; display: block;">' . e($order->user->company) . '</span>';
                }
                $itemsHtml .= '<span style="display: block;">' . e($order->user->shipping_address1) . '</span>';
                if ($order->user->shipping_address2) {
                    $itemsHtml .= '<span style="display: block;">' . e($order->user->shipping_address2) . '</span>';
                }
                $statePart = $order->user->shipping_state ? ', ' . e($order->user->shipping_state) : '';
                $itemsHtml .= '<span style="display: block;">' . e($order->user->shipping_city) . $statePart . ' ' . e($order->user->shopping_postalcode) . '</span>';
                $itemsHtml .= '<strong style="display: block; margin-top: 4px; color: #475569;">' . e($order->user->shipping_country) . '</strong>';
                $itemsHtml .= '</div>';
            }

            if (!empty($order->order_comments)) {
                $itemsHtml .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px;">' . e(siteLabel('email.order_comments', 'Order Comments')) . '</h3>';
                $itemsHtml .= '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; font-size: 14px; color: #334155; line-height: 1.5; margin-bottom: 24px; white-space: pre-wrap;">';
                $itemsHtml .= e($order->order_comments);
                $itemsHtml .= '</div>';
            }

            $itemsHtml .= '</div>';

            $sym = CurrencyService::symbol();
            $vars = [
                'order_id'          => $order->order_invoice_no,
                'customer_name'     => $user->name,
                'order_total'       => CurrencyService::format((float)$order->order_total),
                'order_subtotal'    => CurrencyService::format((float)$order->order_subtotal),
                'order_taxes'       => CurrencyService::format((float)$order->order_taxes),
                'order_shipping'    => CurrencyService::format((float)$order->order_shipping),
                'order_items_table' => $itemsHtml,
                'app_name'          => config('app.name'),
                'year'              => date('Y'),
            ];

            \App\Services\EmailTemplateService::sendEmail('order_confirmation', $user->email, $user->name, $vars);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send order confirmation email: " . $e->getMessage());
        }

        $this->dispatch('cart-updated');
        session()->flash('status', "Order {$order->order_invoice_no} has been successfully placed!");

        return $this->resolveCompletionRedirect($order);
    }

    /**
     * Resolve the post-order redirect destination.
     *
     * Checks each ordered item's product for a `completion_redirect` value.
     * The first non-empty resolvable URL wins (priority: order of items).
     * Falls back to the default order confirmation page if nothing is set.
     */
    protected function resolveCompletionRedirect(Order $order): mixed
    {
        $details = OrderDetail::where('order_id', $order->id)
            ->with(['variant.product'])
            ->get();

        foreach ($details as $detail) {
            $product = $detail->variant?->product ?? null;
            if (!$product) {
                continue;
            }
            $url = Product::resolveCompletionUrl($product->completion_redirect);
            if ($url !== null) {
                return redirect()->away($url);
            }
        }

        // Default: standard order confirmation page
        return redirect()->route('shop.checkout-success', $order->order_external_id);
    }

    public function render(): View
    {
        $totals      = $this->calculateTotals();
        $processorId = $this->getActiveProcessorId();
        $manager     = app(PaymentProcessorManager::class);
        $activeType  = $manager->activeProcessorType($processorId);

        if ($activeType === 'stripe') {
            $this->stripePublishableKey = $manager->resolveActive($processorId)->getPublishableKey();
        } else {
            $this->stripePublishableKey = '';
        }

        if ($activeType === 'paddle') {
            $this->paddleEnvironment = $manager->activeProcessorIsSandbox($processorId) ? 'sandbox' : 'production';
        }

        $this->paypalClientId = '';
        if ($activeType === 'paypal') {
            /** @var \App\Services\Payments\Processors\PayPalProcessor $paypalDriver */
            $paypalDriver = $manager->resolveActive($processorId);
            $this->paypalClientId = $paypalDriver->getClientId();
        }

        $this->currencyCode = strtoupper(CurrencyService::code());

        return view('livewire.order-review', [
            'items'                => $totals['items'],
            'subtotal'             => $totals['subtotal'],
            'taxes'                => $totals['taxes'],
            'vatEmbed'             => $totals['vatEmbed'],
            'vatInclusive'         => $totals['vatInclusive'],
            'crossBorder'          => $totals['crossBorder'],
            'taxLabel'             => $totals['taxLabel'],
            'currencySymbol'       => $totals['currencySymbol'],
            'shippingFee'          => $totals['shippingFee'],
            'handlingFee'          => $totals['handlingFee'],
            'discounts'            => $totals['discountsList'],
            'totalDiscount'        => $totals['discountAmount'],
            'total'                => $totals['total'],
            'shippingOptions'      => $totals['options'],
            'user'                 => Auth::user(),
            // Payment processor context for the blade
            'activeProcessorType'      => $activeType,
            'activeProcessorIsSandbox' => $manager->activeProcessorIsSandbox($processorId),
            'stripeAddressRequired'    => (bool) (\App\Models\OrderCheckoutOption::first()->stripe_address_required ?? false),
            'paypalClientId'           => $this->paypalClientId,
            'currencyCode'             => strtoupper(CurrencyService::code()),
            // Checkout custom fields
            'billingFields'          => \App\Models\CheckoutCustomField::where('position', 'billing')
                ->where('is_active', true)
                ->where(function ($q) {
                    $user = Auth::user();
                    $isWholesale = $user?->isWholesale() ?? false;
                    $q->where('show_for', 'both')
                      ->orWhere('show_for', $isWholesale ? 'wholesale' : 'public');
                })
                ->orderBy('sort_order')
                ->get(),
            'checkoutOptinMode'      => \App\Models\CmsSetting::get('checkout_optin_mode', 'off'),
            'checkoutOptinLabel'     => \App\Models\CmsSetting::get('checkout_optin_label', 'Yes, add me to the mailing list'),
            'checkoutOptinPosition'  => \App\Models\CmsSetting::get('checkout_optin_position', 'checkout'),
        ]);
    }
}
