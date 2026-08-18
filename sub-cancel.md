# Subscription Cancellation & Recurring Billing System Documentation

## 1. Executive Summary

This document provides a comprehensive technical and operational reference for the subscription cancellation system across **Stripe**, **Paddle**, and **PayPal**.

The platform supports:
1. **Customer-Initiated Cancellation**: Direct, one-click cancellation with confirmation in the Customer Account Portal (`/account`) and the Order Status Tracker Lookup Plugin.
2. **Admin-Initiated Cancellation**: Staff-controlled cancellation buttons in Admin Order Details (`/admin/ecommerce/orders/{id}`) and the Subscriptions Management Report (`/admin/ecommerce/reports`).
3. **Provider Direct Dashboard Cancellation Sync**: Automated webhook listeners that detect when an administrator or customer cancels a subscription directly inside the Stripe Dashboard, Paddle Dashboard, or PayPal Business Portal.
4. **Dynamic Multi-Gateway Resolution**: A unified `SubscriptionService` that determines the correct provider dynamically and executes the appropriate API call without hardcoding.
5. **Subscription Tracking & Reporting**: Real-time line item status tracking via `active_subscription` in `order_details` and a dedicated Admin Subscriptions Report with complete past payment audit trails.

---

## 2. Architecture & Cancellation Mechanisms by Gateway

```
                             +--------------------------------------+
                             ¦          Trigger Point               ¦
                             ¦  - Customer Account Portal           ¦
                             ¦  - Order Status Tracker Lookup       ¦
                             ¦  - Admin Order Details View          ¦
                             ¦  - Admin Subscriptions Report        ¦
                             ¦  - Direct on Gateway Dashboard       ¦
                             +--------------------------------------+
                                                ¦
                 +-------------------------------------------------------------+
                 ?                                                             ?
     [Local App Cancellation]                                      [Direct Dashboard Cancellation]
                 ¦                                                             ¦
                 ?                                                             ?
     SubscriptionService::cancelSubscription()                     Provider Webhook Triggered
                 ¦                                                             ¦
   +-------------+-------------+                                 +-------------+-------------+
   ?             ?             ?                                 ?             ?             ?
Stripe SDK    Paddle API   PayPal API                       Stripe Webhook Paddle Webhook PayPal Webhook
(cancel())   (/cancel)    (/cancel)                         (deleted)      (canceled)     (CANCELLED)
   ¦             ¦             ¦                                 ¦             ¦             ¦
   +---------------------------+                                 +---------------------------+
                 ¦                                                             ¦
                 +-------------------------------------------------------------+
                                                ¦
                                                ?
                                   [Update Local Database]
                                   order_details.active_subscription = 0
                                   order_details.subscription_status = 'cancelled'
```

---

### A. Stripe Billing

#### 1. In-App API Cancellation
- **Location**: `app/Services/Payments/Processors/StripeProcessor.php`
- **Method**: `cancelSubscription(string $subscriptionId): bool`
- **Mechanism**: Calls the Stripe PHP SDK:
  ```php
  $this->client()->subscriptions->cancel($subscriptionId);
  ```
- **Behavior**: Immediately terminates the recurring subscription agreement in Stripe.

#### 2. Direct Stripe Dashboard Cancellation (Webhooks)
- **Location**: `app/Http/Controllers/StripeWebhookController.php`
- **Endpoint**: `POST /webhooks/stripe`
- **Events Listened To**:
  - `customer.subscription.deleted`: Triggered immediately when an admin cancels a subscription in the Stripe Dashboard.
  - `customer.subscription.updated`: Triggered when subscription status changes to `canceled` or `paused`.
- **Database Action**:
  - Matches `order_details.subscription_plan_id` or `order_payments.authorization_code`.
  - Sets `active_subscription = 0` and `subscription_status = 'cancelled'`.

---

### B. Paddle Billing

#### 1. In-App API Cancellation
- **Location**: `app/Services/Payments/Processors/PaddleProcessor.php`
- **Method**: `cancelSubscription(string $subscriptionId, string $effectiveFrom = 'immediately'): bool`
- **Mechanism**: Issues an authenticated HTTP POST request to Paddle API:
  ```
  POST https://api.paddle.com/subscriptions/{id}/cancel
  Headers: Authorization: Bearer {apiKey}, Content-Type: application/json
  Body: { "effective_from": "immediately" }
  ```
- **Behavior**: Immediately cancels the recurring subscription in Paddle.

#### 2. Direct Paddle Dashboard Cancellation (Webhooks)
- **Location**: `app/Http/Controllers/PaddleWebhookController.php`
- **Endpoint**: `POST /webhooks/paddle`
- **Events Listened To**:
  - `subscription.canceled`: Triggered when an admin cancels the subscription in the Paddle Dashboard.
  - `subscription.updated`: Checks for `data.status === 'canceled'` or `'past_due'`.
- **Database Action**:
  - Matches `order_details.subscription_plan_id` or `order_payments.authorization_code`.
  - Sets `active_subscription = 0` and `subscription_status = 'cancelled'`.

---

### C. PayPal Subscriptions

#### 1. In-App API Cancellation
- **Location**: `app/Services/Payments/Processors/PayPalProcessor.php`
- **Method**: `cancelSubscription(string $subscriptionId, string $reason = 'Cancelled by customer', ?bool $forceSandbox = null): bool`
- **Mechanism**: Issues an authenticated HTTP POST request using OAuth2 Bearer token:
  ```
  POST https://api-m.paypal.com/v1/billing/subscriptions/{id}/cancel
  Headers: Authorization: Bearer {accessToken}, Content-Type: application/json
  Body: { "reason": "Cancelled by customer/admin" }
  ```
- **Behavior**: PayPal returns `HTTP 204 No Content`, and the billing agreement is cancelled immediately.

#### 2. Direct PayPal Dashboard Cancellation (Webhooks)
- **Location**: `app/Http/Controllers/PayPalWebhookController.php`
- **Endpoint**: `POST /webhooks/paypal`
- **Events Listened To**:
  - `BILLING.SUBSCRIPTION.CANCELLED`: Triggered when an admin or customer cancels the subscription inside their PayPal Business / Personal account.
  - `BILLING.SUBSCRIPTION.SUSPENDED`: Triggered when suspended by admin or PayPal risk.
  - `BILLING.SUBSCRIPTION.EXPIRED`: Triggered when a fixed-cycle subscription ends.
- **Database Action**:
  - Matches `order_details.subscription_plan_id` or `order_payments.authorization_code`.
  - Sets `active_subscription = 0` and `subscription_status = 'cancelled'`.

---

## 3. Dynamic Gateway Resolver (`SubscriptionService`)

- **File**: `app/Services/Payments/SubscriptionService.php`
- **Primary Method**: `cancelSubscription(OrderDetail $orderDetail, string $reason = '...'): bool`
- **Resolution Strategy**:
  1. Checks `$orderDetail->subscription_provider` (`'stripe'`, `'paddle'`, `'paypal'`).
  2. Fallback heuristic on agreement ID prefixes:
     - `sub_` or `seti_` -> **Stripe**
     - `I-` or `P-` -> **PayPal**
     - `sub_01` or `pri_` -> **Paddle**
  3. Fallback to `order_payments` table inspecting `payment_method` string and transaction IDs.
  4. Fallback to `product_variants` configuration fields.
  5. Resolves processor instance dynamically from `PaymentProcessorManager` and executes remote cancellation.
  6. Updates `order_details.active_subscription = 0` and `order_details.subscription_status = 'cancelled'`.

---

## 4. Database Schema & Line Item Tracking

### `order_details` Table

| Column Name | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `active_subscription` | `TINYINT(1)` | `0` | `1` = Active recurring subscription; `0` = Cancelled or non-subscription item. |
| `subscription` | `TINYINT(1)` | `0` | `1` = Item was purchased as a subscription. |
| `subscription_provider` | `VARCHAR(50)` | `NULL` | Billing gateway provider (`stripe`, `paddle`, `paypal`). |
| `subscription_plan_id` | `VARCHAR(191)` | `NULL` | Gateway subscription agreement ID (e.g. `I-BW452GLLEP10`, `sub_1Nv0p...`). |
| `subscription_user_id` | `BIGINT UNSIGNED`| `NULL` | Linked customer user ID. |
| `subscription_status` | `VARCHAR(50)` | `NULL` | Current status string (`active`, `cancelled`, `paused`). |

---

## 5. UI Integration & User Experience

### 1. Customer Account Manager (`/account`)
- **Controller**: `app/Livewire/UserDashboard.php`
- **View**: `resources/views/livewire/user-dashboard.blade.php`
- **Features**:
  - On the Order Details view, active subscriptions display an **Active Subscription** badge with an animated pulse indicator.
  - An inline **Cancel Subscription** button triggers a localized browser confirmation prompt.
  - Integrated with the site label translation system:
    - `@label('account.subscription_active', 'Active Subscription')`
    - `@label('account.subscription_cancelled', 'Cancelled Subscription')`
    - `@label('account.cancel_subscription', 'Cancel Subscription')`
    - `siteLabel('account.cancel_confirm', 'Are you sure you want to cancel this recurring subscription?')`
  - Prevents non-owners from cancelling subscriptions via strict backend ownership verification (`$orderDetail->order->order_user_id === auth()->id()`).

### 2. Order Status Tracker Plugin (Guest & Customer Order Lookup)
- **Plugin Class**: `app/Plugins/Display/OrderStatusTrackerPlugin.php`
- **Features**:
  - Displays subscription status badges on order tracking lookups.
  - Provides a **Cancel Subscription** form action with order verification so customers can manage their subscriptions even from the public tracking widget.

### 3. Admin Order Details (`/admin/ecommerce/orders/{id}`)
- **Controller**: `app/Livewire/AdminOrderDetails.php`
- **View**: `resources/views/livewire/admin-order-details.blade.php`
- **Features**:
  - Displays **Active Subscription** or **Cancelled Sub** badges on line items.
  - Staff can click **Cancel Sub** to immediately revoke the agreement with the payment processor.

### 4. Subscriptions & Recurring Billing Admin Report (`/admin/ecommerce/reports`)
- **Controller**: `app/Livewire/ReportSubscriptions.php`
- **View**: `resources/views/livewire/report-subscriptions.blade.php`
- **Features**:
  - **KPI Metric Cards**: Total Subscriptions, Active Subscriptions, Cancelled Subscriptions, and Active Monthly Recurring Value (MRV).
  - **Date Range Filters**: 30 Days, 60 Days, 90 Days, 120 Days, Year-to-Date, or Custom From/To range.
  - **Search & Filter**: Filter by status (All, Active, Cancelled) and gateway provider (All, Stripe, Paddle, PayPal), plus live search across customer name, email, order #, and subscription ID.
  - **Past Payments Audit History**: Each row features an expandable payment history drawer that lists **all past payment transactions** recorded in `order_payments` associated with that subscription / order.
  - **Direct Admin Actions**: Cancel subscription directly from the report table.
  - **Data Exporting**: Instant **Export CSV** and **Export Excel (XLSX)**.

---

## 6. Code & File Inventory

| Component | File Path |
| :--- | :--- |
| **Migration** | [`database/migrations/2026_08_18_000003_add_active_subscription_to_order_details_table.php`](file:///C:/Sites/laravel-gemini/database/migrations/2026_08_18_000003_add_active_subscription_to_order_details_table.php) |
| **Order Detail Model** | [`app/Models/OrderDetail.php`](file:///C:/Sites/laravel-gemini/app/Models/OrderDetail.php) |
| **Subscription Service** | [`app/Services/Payments/SubscriptionService.php`](file:///C:/Sites/laravel-gemini/app/Services/Payments/SubscriptionService.php) |
| **Stripe Processor** | [`app/Services/Payments/Processors/StripeProcessor.php`](file:///C:/Sites/laravel-gemini/app/Services/Payments/Processors/StripeProcessor.php) |
| **Paddle Processor** | [`app/Services/Payments/Processors/PaddleProcessor.php`](file:///C:/Sites/laravel-gemini/app/Services/Payments/Processors/PaddleProcessor.php) |
| **PayPal Processor** | [`app/Services/Payments/Processors/PayPalProcessor.php`](file:///C:/Sites/laravel-gemini/app/Services/Payments/Processors/PayPalProcessor.php) |
| **Stripe Webhook** | [`app/Http/Controllers/StripeWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/StripeWebhookController.php) |
| **Paddle Webhook** | [`app/Http/Controllers/PaddleWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/PaddleWebhookController.php) |
| **PayPal Webhook** | [`app/Http/Controllers/PayPalWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/PayPalWebhookController.php) |
| **Checkout Placement** | [`app/Livewire/OrderReview.php`](file:///C:/Sites/laravel-gemini/app/Livewire/OrderReview.php) |
| **Customer Dashboard** | [`app/Livewire/UserDashboard.php`](file:///C:/Sites/laravel-gemini/app/Livewire/UserDashboard.php) & [`resources/views/livewire/user-dashboard.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/livewire/user-dashboard.blade.php) |
| **Order Lookup Plugin** | [`app/Plugins/Display/OrderStatusTrackerPlugin.php`](file:///C:/Sites/laravel-gemini/app/Plugins/Display/OrderStatusTrackerPlugin.php) |
| **Admin Order Details** | [`app/Livewire/AdminOrderDetails.php`](file:///C:/Sites/laravel-gemini/app/Livewire/AdminOrderDetails.php) & [`resources/views/livewire/admin-order-details.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/livewire/admin-order-details.blade.php) |
| **Subscriptions Report** | [`app/Livewire/ReportSubscriptions.php`](file:///C:/Sites/laravel-gemini/app/Livewire/ReportSubscriptions.php) & [`resources/views/livewire/report-subscriptions.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/livewire/report-subscriptions.blade.php) |
| **Admin Reports Hub** | [`app/Livewire/AdminReports.php`](file:///C:/Sites/laravel-gemini/app/Livewire/AdminReports.php) & [`resources/views/livewire/admin-reports.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/livewire/admin-reports.blade.php) |
| **Feature Tests** | [`tests/Feature/SubscriptionCancellationTest.php`](file:///C:/Sites/laravel-gemini/tests/Feature/SubscriptionCancellationTest.php) |
