# Platform README

> **Full documentation:** [`Di Sute`](https://docs.sitestorepro.com)

---

## What is this?

A production-ready **e-commerce, CMS, and helpdesk platform** built on:

- **Laravel 13** — application framework & routing
- **Livewire 3** — reactive server-side UI components
- **Alpine.js** — lightweight client-side interactivity
- **Tailwind CSS** — utility-first styling with custom admin breakpoints
- **TinyMCE** — rich-text editing throughout CMS and Knowledge Base
- **Vite** — asset bundling (CSS + JS)

It ships as a single unified application covering a public-facing storefront, a full CMS site-builder, a helpdesk / ticketing system, and a comprehensive admin panel — all driven by a database-backed settings layer (`cms_settings`) with no hard-coded configuration required.

---

## Core Modules at a Glance

| Module | Description |
|---|---|
| **Storefront & Shop** | Product catalog, dependent variants, cart, checkout, payments |
| **CMS** | Pages, posts, media, downloads, shortcodes, plugin embeds |
| **Helpdesk** | Tickets, queues, agent assignments, inbound email ingestion |
| **Knowledge Base** | Categories, articles, full-text search |
| **Plugin System** | Display plugins (carousels, testimonials, events, CTAs, etc.) rendered via shortcodes |
| **Navigation Builder** | Relational menu trees with visibility controls |
| **Multilingual** | Per-language translations, AI batch translation, queue monitor |
| **Global Settings** | Theme colors, dark/light mode, logos, favicons, fonts, storage backends |
| **Admin Panel** | Role-gated dashboard for all of the above |

---

## User Roles

| Role | Access |
|---|---|
| **Admin (1)** | Full platform control |
| **Order Processor (2)** | E-commerce & order management |
| **Staff / Ticket Manager (3–4)** | Helpdesk queues and assigned tickets |
| **Customer (5)** | Storefront, orders, tickets, downloads |
| **Guest** | Public storefront and KB only |

---

## Help Documentation

All detailed feature references, configuration guides, and developer notes live in a single consolidated file:

📄 **[`help-doc.md`](./help-doc.md)** — 10,000+ lines covering all 42 sections including installation, architecture, every module, API endpoints, and the production checklist.

### Quick section index

| # | Section |
|---|---|
| 1 | Platform Overview & Key Modules |
| 2 | Technology Stack |
| 3 | Installation & Local Setup |
| 4 | Directory Structure & Blade View Architecture |
| 5 | User Roles & Permissions |
| 6 | Demo Seeding & Default Credentials |
| 7–8 | E-Commerce: Storefront & Administration |
| 9 | Product Reviews & Ratings |
| 10 | Image & Asset Storage |
| 11–13 | Discounts, Shipping, Tax, Payments & Subscriptions |
| 14–15 | Digital Downloads & CMS Downloads Manager |
| 16–18 | Inventory, Bulk Import & Product Duplication |
| 19–21 | CMS Pages, Access Gating & Post-Order Redirects |
| 22 | Events Calendar Plugin |
| 23–24 | Header/Footer Builder & Global Settings |
| 25 | Advanced Shop Search Filtering |
| 26–28 | Plugin System, Shortcodes & List Menus |
| 29–30 | Search Engine & Dynamic Email Notifications |
| 31 | Multilingual & Translation System |
| 32–33 | Knowledge Base & Helpdesk Ticketing |
| 34 | Dynamic Blade & Livewire Content Parser |
| 35–38 | Security, Inbound Email, API/Webhooks & Timezones |
| 39–41 | Currency, VAT, Product Layouts & Production Checklist |
| 42 | Developer Reference |

---

## Quick Start

```bash
# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Copy and configure environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed demo data (optional)
php artisan migrate
php artisan db:seed --class=DemoStoreSeeder

# Build assets
npm run build

# Serve locally
php artisan serve
npm run dev
```

See **[Installation & Local Setup](./help-doc.md#installation--local-setup)** in `help-doc.md` for full details including S3, queue workers, and production checklist.

---

## Abandoned Cart Reminders

The platform includes an automated abandoned cart recovery system that sends follow-up email notifications to customers who leave items in their cart without completing checkout.

### Overview & Schedule

1. **24-Hour Reminder (Day 1)**:
   - **Template**: `Default 24-Hour Abandoned Cart Reminder` (`abandoned_cart_reminder_1`).
   - **Default Subject**: `Did you leave something behind? Return to your cart!`
   - **Trigger**: Sent automatically 24 hours after a shopping cart is last updated without an order placed.
2. **7-Day Reminder (Week 1)**:
   - **Template**: `Default 7-Day Abandoned Cart Reminder` (`abandoned_cart_reminder_2`).
   - **Default Subject**: `Your cart is waiting for you! Take another look`
   - **Trigger**: Sent automatically 7 days after the cart was abandoned (if Email #1 was sent and no order has been placed).

### Email Formatting & Cart Details Card
- Matches the clean responsive styling of order confirmation messages.
- Dynamically renders the `{cart_items_table}` block showing item names, variant/attribute specifications, quantities, unit prices, line totals, and a direct call-to-action button to return to cart.
- **Financial summary lines (subtotal, totals, tax, shipping) are excluded** per design requirements—focusing strictly on the saved cart items.

### Admin Controls (`/admin/settings/`)
Admins manage abandoned cart notifications under **Admin -> Global Settings -> Shop Settings & Display**:
- **Enable 24-Hour Abandoned Cart Reminder Emails**: Toggle ON / OFF (`enable_abandoned_cart_reminder_1`).
- **Enable 7-Day Abandoned Cart Reminder Emails**: Toggle ON / OFF (`enable_abandoned_cart_reminder_2`).

### Customizing Email Templates (`/admin/email-templates/`)
Admins can customize subjects, greetings, salutations, body content, headers, and footers anytime under **Admin -> Email Templates**:
- `abandoned_cart_reminder_1` (24-Hour Reminder)
- `abandoned_cart_reminder_2` (7-Day Reminder)

### Automated Execution & Failsafes
- **Web-Triggered Failsafe (Zero Config)**: Runs automatically in the background rate-limited once per hour on web traffic. Works out of the box on all installations without requiring terminal commands or server cron setups.
- **Manual Command**: Can be triggered manually anytime via `php artisan shop:send-abandoned-cart-reminders`.
- **Duplicate Prevention**: Stately database timestamp locks (`abandoned_reminder_1_sent_at` and `abandoned_reminder_2_sent_at`) ensure no customer ever receives duplicate reminder emails.

---

## Standalone Reports & Exports (`/admin/reports/`)

The platform features a central **Analytics & Standalone Reports** hub at `/admin/reports/` providing interactive performance dashboards and file export generators:

### 1. Dashboard Analytics & Performance
- Recreates all interactive dashboard charts and metric widgets (`Order Activity`, `Completed vs. Abandoned Carts`, `Cart Funnel Conversion`, `Customer Spend Distribution`, `Top Product Performance`).

### 2. Order Export Report
- **Date Range Selector**: Filter transactions by custom start and end dates.
- **Export Formats**: Streamable **CSV** (`.csv`) or **Excel** (`.xlsx`) via `PhpOffice\PhpSpreadsheet`.
- **Line Item Details**: Includes Order ID, Invoice No, External ID, Date, Status, Customer Name, Email, Shipping Address, City, State, Postal Code, Country, Purchased Line Item Name, Variant/SKU, Quantity, Unit Price, Line Total, Subtotal, Shipping Amount, Handling Amount, Tax Amount, Discounts Applied, and Order Total.

### 3. Sales Tax & VAT Audit Report
- **Filters**: Date Range, Country dropdown (`shipping_countries`), and State/Province dropdown (`shipping_states` for US/CA or free text).
- **Summary Cards**: Totals matching order count, taxable sales subtotal, and total tax/VAT collected.
- **Detail Table & Export**: Breakdown table of matching transactions exportable to CSV or Excel (.xlsx).

### 4. Products Catalog Export
- **Import Compatibility**: Exports the entire products catalog into the **exact schema format** used by the Product Bulk Import tool (`AdminProductImport`).
- **Export Formats**: Streamable **CSV** (`.csv`) or **Excel** (`.xlsx`).
