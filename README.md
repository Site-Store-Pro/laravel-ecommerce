# Platform README

> **Full documentation:** [`View docs.sitestorepro.com`](https://docs.sitestorepro.com)

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


---

## AOS Animation Panel (CMS Page Editor)

The CMS page editor includes a built-in **Animations panel** that lets content authors apply scroll-triggered animations to any block element on a page — no code required. Animations are powered by [AOS (Animate On Scroll) v2.3.4](https://michalsnik.github.io/aos/).

> **Scope:** This feature is available exclusively in the CMS page editor at `/admin/cms-pages/{id}/edit`. It does not apply to products, Knowledge Base articles, plugin shortcodes, or any other editor.

---

### How It Works — End to End

1. **Author clicks the purple ◆ Animate tab** in the right-side floating panel stack on the CMS page edit view.
2. The **Animations drawer** slides in from the right — identical in behaviour to the Widgets, Plugins, Shortcodes, and Links panels.
3. Author **clicks an animation card** (e.g. *Fade Up*) to stage it. A settings form appears.
4. Author **configures** timing, easing, and behaviour options in the form.
5. Author **clicks inside a block element** (paragraph, heading, div, etc.) in the TinyMCE editor to place their cursor there.
6. Author clicks **◆ Apply to Block** — the panel finds the cursor's nearest block-level ancestor and writes `data-aos-*` attributes onto it. TinyMCE shows a dashed violet outline + `◆ fade-up` badge on the element.
7. The page is **saved** — the `data-aos-*` attributes are stored as-is in the page's HTML content in the database.
8. On the **public CMS page** (`/page/{slug}`) or **home page** (`/`), the AOS library is loaded via CDN and initialises on DOM-ready, triggering animations as the visitor scrolls.

---

### The Animations Panel UI

#### Opening the Panel

Click the **◆ Animate** button (violet) in the vertically-stacked floating sidebar on the right edge of the CMS page edit view. Only one panel can be open at a time — opening Animate closes Widgets, Plugins, Shortcodes, and Links, and vice versa.

#### Settings Form

When an animation card is selected, the following controls appear:

| Control | Type | Range / Options | Default | Description |
|---|---|---|---|---|
| **Duration** | Slider | 200 – 2000 ms | 600 ms | How long the animation plays |
| **Delay** | Slider | 0 – 1000 ms | 0 ms | Pause before the animation starts |
| **Offset** | Slider | 0 – 300 px | 80 px | Distance from the viewport bottom edge that triggers the animation |
| **Easing** | Dropdown | See list below | `ease-out-cubic` | Animation timing function |
| **Once** | Toggle | On / Off | **On** | If on, the animation only fires once per page load; if off, it re-fires every time the element enters the viewport |
| **Mirror** | Toggle | On / Off | **Off** | If on, the animation reverses as the element leaves the viewport (scroll back up) |
| **Mobile** | Toggle | On / Off | **Off** | If off, the animation is disabled on screens narrower than 768 px. Turn on to animate on mobile too. |

**Available easing options:**

| Value | Feel |
|---|---|
| `ease` | Browser default |
| `ease-in` | Starts slow, ends fast |
| `ease-out` | Starts fast, ends slow |
| `ease-in-out` | Slow at both ends |
| `linear` | Constant speed |
| `ease-out-cubic` | Smooth deceleration (recommended) |
| `ease-in-back` | Slight overshoot at start |
| `ease-out-back` | Slight overshoot at end |

#### Action Buttons

| Button | Action |
|---|---|
| **◆ Apply to Block** | Writes all staged `data-aos-*` attributes to the cursor's current block element and fires a TinyMCE change event to sync Livewire |
| **✕ Remove** | Strips all `data-aos-*` attributes from the cursor's current block element |

Both actions are **undoable** via TinyMCE's standard Ctrl+Z / Cmd+Z undo.

---

### Animation Reference

All 27 available animations, grouped by category:

#### Fade (9)

| Animation Key | Description |
|---|---|
| `fade` | Simple opacity fade in |
| `fade-up` | Fades in while moving upward |
| `fade-down` | Fades in while moving downward |
| `fade-left` | Fades in from the right, moves left |
| `fade-right` | Fades in from the left, moves right |
| `fade-up-right` | Fades in moving up and to the right |
| `fade-up-left` | Fades in moving up and to the left |
| `fade-down-right` | Fades in moving down and to the right |
| `fade-down-left` | Fades in moving down and to the left |

#### Flip (4)

| Animation Key | Description |
|---|---|
| `flip-left` | 3D flip from right to left |
| `flip-right` | 3D flip from left to right |
| `flip-up` | 3D flip from bottom to top |
| `flip-down` | 3D flip from top to bottom |

#### Slide (4)

| Animation Key | Description |
|---|---|
| `slide-up` | Slides in from below |
| `slide-down` | Slides in from above |
| `slide-left` | Slides in from the right |
| `slide-right` | Slides in from the left |

#### Zoom (10)

| Animation Key | Description |
|---|---|
| `zoom-in` | Scales up from smaller to full size |
| `zoom-in-up` | Zoom in while moving up |
| `zoom-in-down` | Zoom in while moving down |
| `zoom-in-left` | Zoom in while moving left |
| `zoom-in-right` | Zoom in while moving right |
| `zoom-out` | Scales down from larger to full size |
| `zoom-out-up` | Zoom out while moving up |
| `zoom-out-down` | Zoom out while moving down |
| `zoom-out-left` | Zoom out while moving left |
| `zoom-out-right` | Zoom out while moving right |

---

### Mobile Behaviour

By default, animations are **disabled on screens narrower than 768 px** (phones and small tablets). This is controlled per element, not globally:

- **Mobile toggle OFF** (default): The element's `data-aos` attribute is stripped before AOS initialises on any viewport under 768 px. The element renders normally without animation.
- **Mobile toggle ON**: The element receives `data-aos-mobile="true"`. The pre-init script detects this and preserves the `data-aos` attribute even on small screens.

This means you can have a page where most animations are desktop-only, but a specific hero heading animates on all devices.

**The pre-init script** (injected before `@livewireScripts` in both `home.blade.php` and `cms.blade.php`):

```js
if (window.innerWidth < 768) {
    document.querySelectorAll('[data-aos]').forEach(function(el) {
        if (el.getAttribute('data-aos-mobile') !== 'true') {
            el.removeAttribute('data-aos');
        }
    });
}
AOS.init({ once: true, offset: 80, duration: 600, easing: 'ease-out-cubic' });
```

---

### HTML Output Reference

After applying an animation via the panel, the element's saved HTML looks like:

```html
<!-- Minimal (once, no delay, mobile disabled) -->
<p data-aos="fade-up"
   data-aos-duration="600"
   data-aos-offset="80"
   data-aos-easing="ease-out-cubic"
   data-aos-once="true">
    Your paragraph content here.
</p>

<!-- Full options (with delay, mirror, mobile enabled) -->
<div data-aos="zoom-in"
     data-aos-duration="800"
     data-aos-delay="200"
     data-aos-offset="120"
     data-aos-easing="ease-out-back"
     data-aos-once="false"
     data-aos-mirror="true"
     data-aos-mobile="true">
    Your div content here.
</div>
```

The `data-aos-mobile` attribute is a **custom extension** — AOS itself ignores it. It is only read by the pre-init script to control mobile stripping. All other `data-aos-*` attributes are standard AOS data attributes.

---

### Editor Visual Indicator

While working inside TinyMCE, any element carrying a `data-aos` attribute is highlighted with:

- A **dashed violet outline** (`2px dashed #7c3aed`) with a 3 px offset
- A small **purple badge** in the top-left corner showing `◆ <animation-name>` (e.g. `◆ fade-up`)

This indicator is injected via TinyMCE's `content_style` and is **editor-only** — it does not appear on the public site.

---

### Data Persistence

`data-aos-*` attributes are stored directly in the page's HTML content column in the `cms_pages` table. No separate database columns, settings, or migrations are required. Attributes survive auto-save drafts, manual saves, and translation copies.

All four TinyMCE editor instances (left column, main content, right column, and translation content) have `data-aos`, `data-aos-duration`, `data-aos-delay`, `data-aos-offset`, `data-aos-easing`, `data-aos-once`, `data-aos-mirror`, and `data-aos-mobile` whitelisted in their `extended_valid_elements` configuration so TinyMCE never strips them during editing or on save.

---

### CDN Loading

AOS is loaded via CDN on the two public-facing page layouts only:

| File | What is loaded |
|---|---|
| `resources/views/pages/cms.blade.php` | AOS CSS in `<head>`, AOS JS + init before `</body>` |
| `resources/views/pages/home.blade.php` | AOS CSS in `<head>`, AOS JS + init before `</body>` |

**CDN URLs (AOS 2.3.4):**

```
https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css
https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js
```

AOS is **not** loaded on admin pages, product pages, Knowledge Base pages, or any other layout.

---

### Developer Reference

| Concern | File / Location |
|---|---|
| Drawer panel UI | `resources/views/partials/animate-drawer.blade.php` |
| Tab button + Alpine state | `resources/views/livewire/admin-cms-page-edit.blade.php` — `x-data` block + floating sidebar div |
| TinyMCE attribute whitelist | `admin-cms-page-edit.blade.php` — `extended_valid_elements` in all 4 `tinymce.init()` calls |
| TinyMCE editor indicator | `admin-cms-page-edit.blade.php` — `content_style` in all 4 `tinymce.init()` calls |
| AOS CDN + mobile init | `resources/views/pages/cms.blade.php` and `resources/views/pages/home.blade.php` |
| `applyToBlock()` / `removeFromBlock()` JS | Inline Alpine `x-data` in `animate-drawer.blade.php` |

**Adding a new animation:** Add a `[$key, $label]` entry to the appropriate `@foreach` in `animate-drawer.blade.php`. The key must be a valid AOS animation name. No other changes are needed.

**Changing the mobile breakpoint:** Update `window.innerWidth < 768` in both `cms.blade.php` and `home.blade.php`.

**Changing global AOS defaults:** Update the `AOS.init({...})` call in both files. Per-element settings from the drawer always override global defaults.
