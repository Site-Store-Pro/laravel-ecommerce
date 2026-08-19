# Payment Refunds & Gateway Integration Guide

This document details the architecture, gateway linkages, and step-by-step procedures for issuing partial or full order refunds within the store administration portal.

---

## Partial or Full Refunds

Partial or full refunds are issued directly from the Order Details page (`/admin/ecommerce/orders/{id}`).

<Steps>
  <Step title="Open the Order">
    Navigate to **Admin → Orders** and click the order you need to refund.
  </Step>
  <Step title="Set Order Status (Optional for Full Refunds)">
    Change the order status using the **Update Order Status** dropdown to either **Refunded** or **Partially Refunded** and then click the **Apply Status** button (this will not send the customer an email automatically).
    
    > **Note:** If the entire order amount is refunded across payments, the order status will automatically update to **Refunded** upon processing the refund, allowing you to skip this manual status update.
  </Step>
  <Step title="Click the Refund Button in Payments List">
    Locate the **Payments** section and click the **Refund** button next to the specific payment you want to refund.
    
    > **Automatic Gateway Dispatch:** If the payment was processed via **Stripe**, **PayPal**, or **Paddle**, the refund will automatically post directly to the customer's payment provider via their API.
  </Step>
  <Step title="Enter Refund Amount">
    In the refund modal, review the maximum refundable balance. The field **defaults to the full remaining payment amount**, but can be modified to any smaller amount for partial refunds. You may also add an optional administrative note or reason.
  </Step>
  <Step title="Confirm & Process Refund">
    Ensure the **Post refund via Payment Processor API** toggle is enabled (auto-checked for gateway transactions), and click **Confirm & Process Refund**. The gateway executes the credit, records the refund transaction in `order_refunds`, updates the payment status, and recalculates the balance due.
  </Step>
</Steps>

---

## 1. Direct Gateway API Linkages

When an admin processes a refund, the system resolves the associated payment method and interacts with the respective processor API:

### 1. Stripe Integration
- **Driver**: `App\Services\Payments\Processors\StripeProcessor`
- **Mechanism**: Calls the Stripe REST API via `\Stripe\Refund::create()`.
- **Identifier Resolution**:
  - Automatically handles Payment Intent IDs (`pi_...`), Charge IDs (`ch_...`), or Customer Payment Methods (`pm_...`).
  - If a Subscription agreement ID (`sub_...`) is provided, it retrieves the latest invoice and its underlying PaymentIntent to execute the refund.
- **Amount Handling**: Converted to integer cents with currency preservation.

### 2. PayPal Integration
- **Driver**: `App\Services\Payments\Processors\PayPalProcessor`
- **Mechanism**: Calls the PayPal Captures API v2 (`POST /v2/payments/captures/{capture_id}/refund`).
- **Identifier & Subscription Fallback**:
  - Automatically resolves capture IDs from PayPal Checkout Order IDs or Subscription agreement IDs (`I-...`).
  - If the transaction is a subscription billing agreement sale, it automatically falls back to PayPal's Sale Refund API (`POST /v1/payments/sale/{sale_id}/refund`).
- **Error Diagnostics**: Intercepts granular error issues and descriptions from PayPal API responses (e.g., already refunded, capture expired, or balance limits) and displays clear toast notifications.

### 3. Paddle Billing Integration
- **Driver**: `App\Services\Payments\Processors\PaddleProcessor`
- **Mechanism**: Calls the Paddle Billing Adjustments API (`POST /adjustments`).
- **Adjustment Types**:
  - **Full Refund**: Dispatches `action: 'refund'`, `type: 'full'`, and the `transaction_id`.
  - **Partial Refund**: Retrieves the transaction line items and creates a partial line adjustment with `type: 'partial'` and item-level amount tracking.
- **Identifier Resolution**: Resolves transaction IDs directly or via Subscription IDs (`sub_...`) and Customer IDs (`ctm_...`).

### 4. Test Gateway & Offline Ledgers
- **Test Driver**: `App\Services\Payments\Processors\TestProcessor` returns simulated refund authorizations (`TEST-RFND-XXXXXXXX`) for testing environments.
- **Offline / Manual Override**: Administrators can uncheck the **Post refund via Payment Processor API** toggle if the payment was already settled outside the gateway (e.g., cash, check, wire transfer, or direct bank chargeback resolution).

---

## 2. Payments Table & Status Behavior

The **Payments** table on the Order Details view provides clear real-time visibility into the lifecycle of each charge:

| Status Badge | Condition | Description |
| :--- | :--- | :--- |
| **Paid** (Green) | `payment_status = 1` | Charge is captured with no refund balance deducted. |
| **Partially Refunded** (Amber) | `payment_status = 3` or `refunded_amount > 0` | A portion of the payment has been refunded. Displays the refunded amount subtext (e.g. `-$25.00 refunded`). |
| **Refunded** (Red) | `payment_status = 2` or `remaining_refundable = 0` | The payment has been 100% refunded. The Refund button is disabled with a checkmark. |
| **Pending** (Slate) | `payment_status = 0` | Payment authorization is incomplete or awaiting settlement. |

### Balance Summary Calculations
The order ledger dynamically reflects:
- **Order Total**: Original total order charge.
- **Total Paid**: Sum of all payments recorded in `order_payments`.
- **Total Refunded**: Sum of all refunds recorded in `order_refunds` (displayed in red).
- **Balance Due**: `max(0, Order Total - Total Paid + Total Refunded)`. Displays a green **Paid in Full** badge when the net balance due is $0.00.

---

## 3. Automated Order Status & Inventory Safeguards

### Automatic Order Status Elevation
- When cumulative refunds equal or exceed the order's total charge (`order_total`), the overall `orders.order_status` is automatically set to **3 (Refunded)**.
- For partial refunds, administrators can optionally set the order status to **Partially Refunded** via the status dropdown.

### Inventory Restocking Safeguard
- When the **first refund** on an order is created, the system iterates over the order line items (`order_details`) and increments the available product stock (`product_inventory.quantity_available += item_qty`).
- Subsequent partial refunds on the same order will not duplicate inventory restocks.

---

## 4. Code & Architecture Reference

| Component | Path | Description |
| :--- | :--- | :--- |
| **Refund Service** | `app/Services/Payments/PaymentRefundService.php` | Centralized refund orchestrator managing validation, gateway API dispatch, and database transactions. |
| **Contract** | `app/Services/Payments/Contracts/PaymentProcessorInterface.php` | Defines the standard `refund(string $transactionId, float $amount, ?string $reason, string $currency)` contract. |
| **Stripe Processor** | `app/Services/Payments/Processors/StripeProcessor.php` | Stripe API refund implementation. |
| **PayPal Processor** | `app/Services/Payments/Processors/PayPalProcessor.php` | PayPal Captures v2 and Subscriptions v1 refund implementation. |
| **Paddle Processor** | `app/Services/Payments/Processors/PaddleProcessor.php` | Paddle Billing Adjustments API refund implementation. |
| **Test Processor** | `app/Services/Payments/Processors/TestProcessor.php` | Simulated sandbox test refund driver. |
| **Order Payment Model** | `app/Models/OrderPayment.php` | Includes `refunds()` relation, `refunded_amount`, and `remaining_refundable` accessors. |
| **Order Refund Model** | `app/Models/OrderRefund.php` | Includes `payment()` relation and `order_payment_id` attribute. |
| **Admin Controller** | `app/Livewire/AdminOrderDetails.php` | Livewire component managing modal state, validation, and refund execution. |
| **Blade View** | `resources/views/livewire/admin-order-details.blade.php` | Renders the Payments table, status badges, and interactive refund modal. |
| **Toast Component** | `resources/views/components/toast-alert.blade.php` | Renders real-time success and error alerts with secure HTML entity encoding. |
