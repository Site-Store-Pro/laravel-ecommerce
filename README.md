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

---

## Plugin CSS System

All display plugins support a **three-tier CSS precedence model** that gives you full control over styling — from a sensible built-in default through to inline shortcode overrides.

### How it works

Each display plugin outputs a scoped `<style>` block immediately before its HTML. CSS is layered in this order (later layers override earlier ones):

| Layer | Source | Priority |
|---|---|---|
| 1 — Default CSS | `default_css` plugin setting (read-only reference) | Lowest |
| 2 — Custom CSS | `custom_css` plugin setting (editable in admin) | Middle |
| 3 — Shortcode CSS | `custom_css=` parameter in the shortcode | Highest |

All CSS values are minified via `CssMinifierService` before output.

---

### Admin — Plugin Settings Panel

Navigate to **Admin → Plugin Manager** and select any display plugin. The settings panel exposes two CSS fields:

- **Default Plugin CSS (Read-Only Reference)** — displays the plugin's built-in base stylesheet. This field is informational only and cannot be edited. Copy rules from here into the Custom CSS field to customise them.
- **Custom CSS Overrides** — a full CSS code editor (syntax-highlighted). Anything entered here is appended after the default CSS and will override matching rules. Leave empty to use only the default styles.

Changes saved here apply **site-wide** to every instance of that plugin shortcode.

---

### Shortcode Parameter — `custom_css`

Any individual shortcode can inject its own CSS by passing a `custom_css` parameter. This overrides **both** the default and admin custom CSS for that specific instance only.

```
[plugin:slideshow-2026 custom_css=".slideshow-plugin-heading { font-size: 3rem; }"]
[plugin:faqs-2026 custom_css=".faq-accordion { background: #f0f4ff; border-radius: 1rem; }"]
[plugin:brands-2026 display=grid custom_css=".brand-logo-img { filter: none; }"]
```

> **Note:** The `custom_css` shortcode value completely replaces the admin-level custom CSS for that render. The `default_css` is always output first regardless.

---

### Affected Plugins

All plugins below support the full three-tier CSS system:

| Plugin | Shortcode | PHP Class | Notes |
|---|---|---|---|
| Slideshow - Swiper Display | `slideshow-2026` | `SlideshowPlugin` | CSS scoped to the swiper wrapper; default includes responsive breakpoints |
| Live Search Display | `live-search-2026` | `LiveSearchPlugin` | CSS scoped to `.live-search-2026-wrapper`; default includes form and button styles |
| Featured Items Display | `featured-items` | `FeaturedItemsPlugin` | CSS prepended before Livewire component render |
| Cross-Sell List Display | `cross-sell-list` | `CrossSellListPlugin` | CSS prepended before Livewire component render |
| Brands Display | `brands-2026` | `BrandsPlugin` | Default CSS includes slider navigation, pagination, and responsive grid rules |
| Events Calendar Display | `events-calendar-2026` | `EventsCalendarPlugin` | CSS output in blade view; default and custom both minified |
| FAQ Accordion Display | `faqs-2026` | `FaqsPlugin` | CSS block prepended to accordion HTML; default and custom merged |
| Testimonials Display | `testimonials-2026` | `TestimonialsPlugin` | CSS block prepended to slider/list HTML |
| Top-Level Categories Display | `categories-2026` | `CategoriesPlugin` | CSS block prepended to grid/list/slider HTML |
| CMS Modal Display | `modal` | `ModalDisplayPlugin` | Plugin-level CSS output before per-instance scoped modal rules; `custom_css` shortcode param also supported |

---

### Per-Plugin CSS Class Reference

Use these class names as selectors when writing custom CSS in the admin or shortcode:

#### Slideshow (`slideshow-2026`)
```css
.slideshow-plugin-wrapper   { /* outer container */ }
.slideshow-plugin-slide     { /* each slide (background-image) */ }
.slideshow-plugin-overlay   { /* alignment flex container over slide */ }
.slideshow-plugin-content   { /* text/button content box */ }
.slideshow-plugin-heading   { /* slide title h2 */ }
.slideshow-plugin-subheading{ /* slide subtitle p */ }
.slideshow-plugin-btn       { /* slide CTA button */ }
```

#### Live Search (`live-search-2026`)
```css
.live-search-2026-wrapper   { /* outer container */ }
.live-search-form           { /* flex form row */ }
.live-search-form input     { /* text input */ }
.live-search-form button    { /* submit button */ }
.live-search-results        { /* dropdown results panel */ }
```

#### Featured Items / Cross-Sell (`featured-items`, `cross-sell-list`)
```css
/* CSS is prepended before Livewire; use Tailwind utility overrides
   or target the Livewire component's rendered classes directly. */
```

#### Brands (`brands-2026`)
```css
.brands-plugin-grid         { /* grid layout container */ }
.brands-plugin-slider-outer { /* slider outer wrapper (with nav padding) */ }
.brand-slide-card           { /* individual brand card */ }
.brand-logo-img             { /* brand logo <img> */ }
.brands-swiper-prev/next    { /* custom nav arrow buttons */ }
```

#### Events Calendar (`events-calendar-2026`)
```css
/* The calendar renders a rich Alpine.js component. Target the
   wrapper ID (#cal_XXXXXXXX) for scoped rules or use the
   .event-card, .cal-day-cell classes inside the view. */
```

#### FAQ Accordion (`faqs-2026`)
```css
.faq-accordion              { /* outer wrapper */ }
.faq-item                   { /* individual question row */ }
.faq-question               { /* question button/trigger */ }
.faq-answer                 { /* collapsible answer panel */ }
```

#### Testimonials (`testimonials-2026`)
```css
.testimonials-plugin-section { /* slider outer wrapper */ }
.testimonials-plugin-list    { /* list layout container */ }
.tmn-card                    { /* individual testimonial card */ }
```

#### Top-Level Categories (`categories-2026`)
```css
.categories-plugin-wrapper  { /* outer container */ }
.categories-plugin-card     { /* individual category link */ }
.category-logo-img          { /* category image */ }
```

#### CMS Modal (`modal`)
```css
.cms-modal-panel            { /* modal panel container */ }
/* Per-instance rules are scoped to #cms-modal-outer-{id}.
   Plugin-level custom CSS applies globally to all modals. */
```

---

### Implementation Details

- **File:** `app/Plugins/Display/<PluginName>Plugin.php` — reads `default_css` and `custom_css` from `$plugin->getSetting()` / `$plugin->getSettings()`
- **File:** `database/seeders/PluginSeeder.php` — defines the `default_css` (`text-only`, read-only) and `custom_css` (`textarea`, `field_editor: css`) options for each plugin
- **Service:** `app/Services/CssMinifierService::minify()` — strips comments and collapses whitespace before inline output
- **Priority rule:** shortcode `custom_css=` param → admin `custom_css` setting → `default_css` is always output as the base layer
