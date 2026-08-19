---
title: "Subscription Expiration & Access Revocation"
sidebarTitle: "Expiration & Access Control"
description: "How digital product downloads and gated content access tokens automatically expire upon subscription cancellation, error page handling, and manual admin management."
---

# Subscription Expiration & Access Revocation

In Site Store Pro, recurring subscriptions can grant access to digital downloads and gated CMS content pages. When a recurring subscription is cancelled or lapses, the platform immediately revokes customer access to prevent unauthorized downloads or page viewing.

---

## 1. Automatic Expiration Workflow

When a subscription is cancelled—whether initiated by the customer, staff, or incoming payment processor webhook—the system executes the following revocations:

### A. Digital Product Download Expiration (`order_details.download_expiration`)
- The `download_expiration` column on the subscription's `order_details` record is set to **the previous day** (`now()->subDay()->endOfDay()`).
- Setting the timestamp to the previous day ensures that any customer attempts to download the file are immediately rejected, even across varying server/client timezone offsets.
- When an expired customer attempts to download via `/download/{orderDetail}/{token}`, [`ProductDownloadController`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/ProductDownloadController.php) validates the timestamp and returns a `403` status.

### B. Gated Content Access Token Expiration (`content_access_tokens.expires_at`)
- If the subscription includes a gated content page access token (in `content_access_tokens`), its `expires_at` timestamp is also updated to **the previous day** (`now()->subDay()->endOfDay()`).
- When an expired user attempts to access the gated content link (`/content-access/{token}`), [`ContentAccessController`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/ContentAccessController.php) detects the expired token and displays the branded `403` error page.

---

## 2. Cancellation Trigger Points

Access revocation is automatically synchronized across all cancellation pathways:

```
                          ┌──────────────────────────────────────┐
                          │     Subscription Cancellation Event  │
                          └──────────────────┬───────────────────┘
                                             │
         ┌───────────────────────────────────┼───────────────────────────────────┐
         │                                   │                                   │
┌────────▼────────┐                 ┌────────▼────────┐                 ┌────────▼────────┐
│ Customer Portal │                 │ Admin Dashboard │                 │ Gateway Webhook │
│(UserDashboard)  │                 │(AdminOrderDetails│                │(Stripe, Paddle, │
│                 │                 │& Subscriptions) │                 │ PayPal Webhooks)│
└────────┬────────┘                 └────────┬────────┘                 └────────┬────────┘
         │                                   │                                   │
         └───────────────────────────────────┼───────────────────────────────────┘
                                             │
                                  ┌──────────▼──────────┐
                                  │ SubscriptionService │
                                  └──────────┬──────────┘
                                             │
                     ┌───────────────────────┴───────────────────────┐
                     │                                               │
          ┌──────────▼──────────┐                         ┌──────────▼──────────┐
          │ order_details       │                         │content_access_tokens│
          │ download_expiration │                         │ expires_at          │
          │  = yesterday        │                         │  = yesterday        │
          └─────────────────────┘                         └─────────────────────┘
```

1. **Customer Account Portal** ([`app/Livewire/UserDashboard.php`](file:///C:/Sites/laravel-gemini/app/Livewire/UserDashboard.php)):
   Customer clicks **Cancel Subscription** in their order history.
2. **Admin Order Details** ([`app/Livewire/AdminOrderDetails.php`](file:///C:/Sites/laravel-gemini/app/Livewire/AdminOrderDetails.php)):
   Admin clicks **Cancel Sub** on an active subscription line item.
3. **Admin Subscriptions Report** ([`app/Livewire/ReportSubscriptions.php`](file:///C:/Sites/laravel-gemini/app/Livewire/ReportSubscriptions.php)):
   Admin cancels recurring billing directly from the subscriptions ledger.
4. **Gateway Lifecycle Webhooks**:
   - **Stripe** ([`app/Http/Controllers/StripeWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/StripeWebhookController.php)): `customer.subscription.deleted`, `incomplete_expired`, `unpaid`.
   - **Paddle** ([`app/Http/Controllers/PaddleWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/PaddleWebhookController.php)): `subscription.canceled`, `past_due`, `paused`.
   - **PayPal** ([`app/Http/Controllers/PayPalWebhookController.php`](file:///C:/Sites/laravel-gemini/app/Http/Controllers/PayPalWebhookController.php)): `BILLING.SUBSCRIPTION.CANCELLED`, `BILLING.SUBSCRIPTION.EXPIRED`, `BILLING.SUBSCRIPTION.SUSPENDED`.

---

## 3. Branded Error Page & Expiration Handling

When a customer visits an expired link, instead of encountering a fatal server exception or generic error, Site Store Pro serves a responsive, branded template:

- **Template**: [`resources/views/errors/403.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/errors/403.blade.php) & [`resources/views/errors/410.blade.php`](file:///C:/Sites/laravel-gemini/resources/views/errors/410.blade.php)
- **Status Code**: `403 Forbidden` / `410 Gone`
- **User-Facing Message**: *"This content access link has expired."*
- **Action Buttons**: Provides immediate navigation back to the storefront (**Return to Store**) and the customer account portal (**My Account**).

---

## 4. Admin Management & Manual Overrides

Store administrators can manually extend, reinstate, or remove expirations directly from the order details interface (`/admin/ecommerce/orders/{id}`):

### A. Dual Expirations on Order Line Items
When a purchased item grants **both** a downloadable file and gated CMS content page access (or only one of the two), the line item row displays independent controls for each:

- **File Download (`download_expiration`)**:
  - Displays file download expiry status badge (*Active*, *Expired*, or *Lifetime*).
  - Click **Edit File Expiry** to adjust the download access cutoff date.
- **Gated Page Access (`content_access_tokens.expires_at`)**:
  - Displays gated page access expiry status badge (*Active*, *Expired*, or *Lifetime*).
  - Click **Edit Page Expiry** to adjust the gated URL redemption cutoff date.

Both modals support custom date/time selection alongside 1-click presets:
- `+30 Days`
- `+90 Days`
- `+1 Year`
- `Expire (Yesterday)` — immediately revokes access.
- `No Expiry` — grants permanent lifetime access.

### B. Content Access Tokens Management Card
Below the order items table, an administrative card lists all gated content tokens issued for the order:
- **Line Item / Product**: Associated store product.
- **Access Link & Destination**: Gated token URL (with copy/test link) and final destination CMS URL.
- **First Accessed**: Timestamp of initial redemption.
- **Expires**: Expiration status badge (*Active*, *Expired*, or *No Expiration*).
- **Actions**:
  - **Edit Expiry**: Opens the token expiration modal to adjust or remove expiry.
  - **Regenerate**: Generates a new secure UUID token and issues a fresh 90-day expiry window.

---

## 5. Technical Reference & Database Schema

### Database Columns

| Table | Column | Type | Description |
|---|---|---|---|
| `order_details` | `download_expiration` | `TIMESTAMP NULL` | Timestamp until which digital downloads can be fetched. Set to yesterday on sub cancellation. |
| `order_details` | `active_subscription` | `TINYINT(1)` | `1` if recurring subscription agreement is active, `0` if cancelled. |
| `order_details` | `subscription_status` | `VARCHAR(50)` | Status slug: `active`, `cancelled`, `trialing`, `past_due`. |
| `content_access_tokens` | `expires_at` | `TIMESTAMP NULL` | Timestamp after which gated page redemption is blocked. |
| `content_access_tokens` | `accessed_at` | `TIMESTAMP NULL` | Timestamp of first successful token redemption. |
| `content_access_tokens` | `token` | `VARCHAR(64)` | Unique UUID token used in redemption URL. |