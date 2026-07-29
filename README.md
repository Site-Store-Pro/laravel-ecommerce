# Laravel E-Commerce Platform

A comprehensive, enterprise-ready, e-commerce platform built on Laravel 13, Livewire 3, Tailwind CSS, and Alpine.js. This platform combines a dynamic, high-performance storefront, full-featured CMS / sitebuilder and e-commerce administration workspace.

---

## 🚀 Key Modules & Architecture

### 1. Storefront & E-Commerce
* **Dynamic Product Catalog & Cart:** Seamless checkout, slider carts, dynamic product layout selector (modular partials), and interactive breadcrumbs.
* **Dependent Variants & Combos:** Color/size variant selectors, progressive option filtering, and auto-resolved variant IDs.
* **Variant Color Deduplication:** Auto-deduplication of gallery thumbnails based on variant color, shade, or tint, with smart Alpine.js navigation.
* **Product Reviews & Ratings:** Customer review pipeline with star-rating UI.
* **Gift Wrapping & Personalization:** Custom checkout options configured at the variant level.
* **Product COPY & Duplication Engine:** One-click duplication of products, variants, unique SKUs, stock levels, images, customization fields, and cross-sells.
* **Product Cross-Selling & Event Details:** Cross-selling display plugin, post-cart intermediary pages, and variant event details calendar integrations.

### 2. Helpdesk & Customer Support
* **Ticket Submission & Queues:** Robust ticketing interface for customers with multiple attachments, and an operations dashboard for support agents.
* **Knowledge Base (KB):** Self-hosted TinyMCE rich text editor integration for documentation, categories, and articles.
* **Role-Based Permissions:** Admin, Staff, and Customer user role levels controlling access to ticketing, helpdesk queues, and catalog editing.

### 3. CMS, Menus & Shortcode Parser
* **Dynamic Content Parsing:** Unified `ContentParserService` that automatically processes shortcodes in CMS pages, product descriptions, and blogs.
* **CMS Form Builder:** Visual builder for public forms supporting field validations, email notifications, mailing list opt-ins, and reCAPTCHA v3.
* **Downloads & Embeds Managers:** Shortcode integrations for local/S3/URL-based asset downloads, direct URL download overrides on product variants, and responsive video/media embeds.
* **Top Navigation Builder:** Relational navigation menu trees with visibility controls and custom color schemes.

### 4. Shipping, Tax & Pricing Engine
* **VAT-Inclusive Pricing:** Automates domestic VAT rendering and handles cross-border VAT removal dynamically for international buyers.
* **Flexible Surcharges & Handling:** Support for per-item taxability (`charge_tax`), order-level handling charges, and flat-rate shipping lists.
* **Discounts & Promotions:** Advanced promotion engine with BOGO rules, stacking discounts, brand/category rules, and wholesale limitations.

### 5. Payment Gateways & Subscriptions
* **Payment Processors:** Support for Stripe, Paddle Billing, and PayPal with production/sandbox modes, webhooks, and automatic signature verification.
* **Subscription Variants:** Mixed-cart policies and recurring billing rules configured at the variant level.

### 6. Inventory & Warehouse Control
* **Multi-Warehouse Stock:** Warehouse locations management, stock level calculations, and multi-location fulfillment rules.
* **Bulk Import:** CSV importer for bulk inventory updates.
* **Webhooks & APIs:** API endpoints for automated, inbound inventory syncs and email ticket ingestion.

### 7. Extensible Plugin System
* **Modular Architecture:** Schema-backed registry and option systems for built-in and third-party external plugins. Supports URL parameter type filtering (`/admin/plugins?type=shipping`).
* **Built-in Plugins:**
  - *Display:* Slideshow, Featured Items, and Cross-Sell list selectors.
  - *Shipping Carriers:* Real-time shipping rate integrations for FedEx, UPS, and USPS with read-only status listing on `/admin/ecommerce/shipping`.

---

## 🛠️ Technology Stack

| Component | Technology | Description |
|---|---|---|
| **Backend Framework** | Laravel 13 | Core PHP MVC Architecture |
| **Frontend/Logic** | Livewire 3 | Dynamic, reactive, single-page feeling components in pure PHP |
| **JS Reactivity** | Alpine.js | Lightweight JS interaction (toggles, modals, filters) |
| **Styling & Theme** | Tailwind CSS | Sleek, custom-designed light and dark mode styling |
| **Authentication** | Breeze + Socialite | Standard credentials + Google/Facebook/GitHub OAuth |
| **Rich Text Editor** | TinyMCE (GPL) | Self-hosted rich text manager for CMS and KB |
| **Database** | MySQL / SQLite | Fully indexed relational structure |
| **Hierarchical Trees** | `staudenmeir/laravel-adjacency-list` | Adjacency list recursive categories and navigation |

---

## 🔧 Installation & Local Setup

### 1. Prerequisites
Ensure you have **PHP 8.3+**, **Composer**, **NodeJS & NPM**, and a database (MySQL/PostgreSQL/SQLite).
Required Composer package extensions:
* `phpoffice/phpspreadsheet` (Required for bulk CSV & Excel `.xlsx`/`.xls` spreadsheet importing)

### 2. Setup Commands
```bash
# Clone the repository and install PHP dependencies
git clone <repository-url> laravel-gemini
cd laravel-gemini
composer install

# Require PhpSpreadsheet for product imports (if not already installed)
composer require phpoffice/phpspreadsheet

# Install optional payment SDKs (if required)
composer require stripe/stripe-php paddlehq/paddle-php-sdk

# Install frontend dependencies and build assets
npm install
npm run build
```

### 3. Environment Config
Copy the env template and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```
Configure your database (`DB_*`), timezone, default currency, and payment credentials inside your `.env` file.

### 4. Database Setup & Seeding
```bash
# Run migrations
php artisan migrate

# Seed basic setup and default admin user
php artisan db:seed

# Optional: Seed full demo store content (products, categories, brands)
php artisan db:seed --class=DevEcommerceSeeder
```

---

## 📦 Directory Structure Highlights

```
app/
├── Http/
│   ├── Controllers/        # Standard HTTP controllers (PageController, SocialAuthController, etc.)
│   └── Middleware/         # Custom middleware (EnsureUserIsAdmin, SetLocale, etc.)
├── Livewire/               # All Livewire components (admin + storefront)
├── Models/                 # Eloquent entities (Product, CmsPage, Ticket, Language, etc.)
├── Plugins/                # Plugin contracts, display plugins, PluginManager
├── Services/               # Business logic (TranslationService, SiteLabelService, etc.)
├── Traits/                 # Reusable model traits (HasTranslations)
└── helpers.php             # Global helper functions (siteLabel(), etc.)
config/                     # Custom configurations (nav_schemes, payment_processors)
database/
├── migrations/             # All database schemas
└── seeders/                # Core, language, and demo seeders
plugins/                    # Drop-in external plugins
resources/
├── css/                    # Tailwind CSS customizations & variables
└── views/                  # Blade templates — see Blade Architecture section below
routes/
├── web.php                 # All public + admin routes
└── auth.php                # Authentication routes (Livewire Volt)
```

---

## 🗂️ Blade View Architecture

The `resources/views/` directory uses a **two-tier rendering model**. Understanding this is essential before adding or editing any view file.

### Tier 1 — Full-Page Blades (`pages/` and `user/`)

These files render a **complete HTML document** — they include their own `<!DOCTYPE html>`, `<head>`, header component, footer component, and `</html>`. They are returned directly from a controller or `Route::view()`. They are **not** Livewire components and do not use `#[Layout(...)]`.

```
resources/views/
├── pages/                         # Public-facing standalone HTML pages
│   ├── home.blade.php             # GET /  — Home page. Loads the 'home' CmsPage from the
│   │                              #         database (with translations). Rendered by Route::view.
│   ├── cms.blade.php              # GET /{slug}  — All other CMS content pages, served by
│   │                              #   PageController::show(). Receives $page (CmsPage model with
│   │                              #   translations loaded). Supports left/right sidebars, header
│   │                              #   images, plugin shortcodes, and tag display.
│   ├── cms-category.blade.php     # GET /category/{slug}  — Category archive listing.
│   ├── cms-tag.blade.php          # GET /tag/{slug}  — Tag archive listing.
│   └── cms-password.blade.php     # Served by PageController when a page has access controls
│                                  #   (purchase gate or access code) that are not yet satisfied.
│
└── user/                          # Authenticated user pages (auth middleware enforced in routes)
    ├── profile.blade.php          # GET /profile  — User profile management. Contains
    │                              #   Livewire profile sub-components (update info, password,
    │                              #   delete account). Uses x-app-layout wrapper component.
    └── dashboard.blade.php        # Legacy Breeze boilerplate kept for reference. The active
                                   #   user dashboard at GET /dashboard uses the UserDashboard
                                   #   Livewire component with layouts/public.blade.php instead.
```

> **Rule of thumb:** If the page is a complete HTML document served directly from a route (not a Livewire component), it belongs in `pages/` or `user/`.

---

### Tier 2 — Livewire Component Views (`livewire/`)

These files are **partial HTML fragments** — they contain only the content area markup. The Livewire engine injects their output into a layout wrapper file. They are referenced by Livewire components via `#[Layout('layouts.xxx')]` and **must not** include `<!DOCTYPE html>`, `<head>`, or `<body>` tags.

```
resources/views/livewire/
├── kb-landing.blade.php             # GET /kb  — Knowledge Base landing / category browser
├── kb-article-show.blade.php        # GET /kb/{seo_link}  — Full KB article view
├── kb-search-bar.blade.php          # Inline KB search component
├── admin-*.blade.php                # All admin panel component views (injected into layouts/app)
├── pages/auth/                      # Auth forms: login, register, forgot-password, etc.
├── profile/                         # Profile sub-forms (update info, update password, delete)
└── ...                              # All other Livewire component views
```

> **Rule of thumb:** If you are building a Livewire component, its view goes in `resources/views/livewire/` and the component class declares which layout wrapper to use.

---

### Layout Wrappers (`layouts/`)

Layout wrappers are **not pages** — they are HTML shells that Livewire injects component content into. Each wrapper provides a different chrome (nav, sidebar, CDN scripts, etc.).

| File | Livewire Attribute | Used By |
|---|---|---|
| `layouts/public.blade.php` | `#[Layout('layouts.public')]` | All public storefront components: ShopCatalog, ProductDetails, KbLanding, KbArticleShow, ShoppingCart, Checkout, CheckoutSuccess, OrderReview, UserDashboard, PublicTicketView, PostCartCrossSell, GuestSetPassword |
| `layouts/app.blade.php` | `#[Layout('layouts.app')]` | All admin components: AdminProducts, AdminCmsPageEdit, AdminLanguages, AdminSettings, AdminUsers, etc. |
| `layouts/guest.blade.php` | `#[Layout('layouts.guest')]` | Unauthenticated auth forms: login, register, forgot/reset password, email verification |

**`layouts/public.blade.php`** includes:
- Google Fonts loader (`<x-site-google-fonts-loader />`)
- Flag Icons CDN (`flag-icons` CSS for language switcher flags)
- Livewire styles/scripts
- RTL `dir` attribute based on current language
- `<meta name="description">` from `$metaDescription`
- `<title>` from `$metaTitle` / `$pageTitle` / site name fallback
- `@stack('meta')` for per-component head injection
- Public header (`<livewire:public-header />`) and footer (`<livewire:public-footer />`)
- Dark mode class on `<html>` based on `frontend_dark_mode` setting

---

### Blade Components (`components/`)

Small reusable snippets included anywhere via `<x-component-name />`.

```
resources/views/components/
├── application-logo.blade.php         # Site logo SVG/image
├── header-footer-styles.blade.php     # Inlines dynamic CSS from the Header/Footer CSS Manager
├── auth-session-status.blade.php      # Flash message display for auth flows
├── action-message.blade.php           # "Saved." transient feedback message
├── input-label.blade.php              # Styled form label
├── input-error.blade.php              # Inline validation error display
├── danger-button.blade.php            # Red destructive action button
├── modal.blade.php                    # Generic modal overlay
├── dropdown.blade.php / dropdown-link.blade.php  # Navigation dropdowns
└── ...                                # Additional UI primitives
```

---

### Quick Reference — Where Does a New File Go?

| Scenario | Location |
|---|---|
| New public page returned directly from a controller/route (not Livewire) | `resources/views/pages/` |
| New authenticated user page returned from a route | `resources/views/user/` |
| New Livewire component on the public storefront | `resources/views/livewire/` + `#[Layout('layouts.public')]` |
| New Livewire component in the admin panel | `resources/views/livewire/` + `#[Layout('layouts.app')]` |
| New auth form (login, register, etc.) | `resources/views/livewire/pages/auth/` + `#[Layout('layouts.guest')]` |
| New reusable snippet used via `<x-name />` | `resources/views/components/` |
| Modifying public site chrome (header/footer/meta/CDN) | `resources/views/layouts/public.blade.php` |
| Modifying admin panel chrome (sidebar/topbar) | `resources/views/layouts/app.blade.php` |

---

### CMS Page Rendering Flow

```
GET /                     → Route::view('pages.home')                   → pages/home.blade.php
GET /{slug}               → PageController::show($slug)                 → pages/cms.blade.php
GET /category/{slug}      → CmsCategoryPageController::show()           → pages/cms-category.blade.php
GET /tag/{slug}           → CmsTagPageController::show()                → pages/cms-tag.blade.php
GET /{slug} (gated)       → PageController::show() (access denied)      → pages/cms-password.blade.php
GET /kb                   → KbLanding (Livewire) → layouts/public       → livewire/kb-landing.blade.php
GET /kb/{seo_link}        → KbArticleShow (Livewire) → layouts/public   → livewire/kb-article-show.blade.php
GET /shop                 → ShopCatalog (Livewire) → layouts/public     → livewire/shop-catalog.blade.php
GET /items/{seo_link}     → ProductDetails (Livewire) → layouts/public  → livewire/product-details.blade.php
GET /profile              → Route::view('user.profile') [auth]           → user/profile.blade.php
GET /dashboard (admin)    → UserDashboard (Livewire) → layouts/public   → livewire/user-dashboard.blade.php
```

---

## 📜 Demo Store Seeding & Administration

To evaluate the system with placeholder data, run:
```bash
php artisan db:seed --class=DevEcommerceSeeder
```
This inserts:
* **Brands:** 5 brands.
* **Categories:** 10 nested categories.
* **Products & Variants:** 34 products with variants, images, and pricing.

**Admin Credentials:**
You can access the backend dashboard at `/admin` (or `/login`). Use the default seed credentials:
* **Email:** `admin@example.com`
* **Password:** `password`

---

## 🎨 Header & Footer Layout Builder & CSS Manager

### 1. Overview & Architecture
The system includes a modernized, database-driven **Header & Footer Builder** and **CSS Theme Manager**. It completely decouples layout styling and block content from hardcoded templates while remaining 100% backward-compatible with legacy shortcodes, dynamic navigation menus, and third-party plugins.

### 2. Database Schema (`cms_builder_blocks`)
The layout structure is backed by a generic database table (`cms_builder_blocks`):
- `id` (Primary Key)
- `title` & `target_element`: Human-readable label and CSS class target (e.g. `top_sharing_container`, `site_header_container`, `top_nav_container`, `header_logo`, `footer_row1-4`, `site_footer_columns_primary`, `footer_col1-5`).
- `type` & `section_type`: Block type classification (1=Header Container, 2=Header Inner Element, 3=Top Bar Column, 4=Footer Row, 5=Footer Column) and section (`header` vs `footer`).
- Device Sorting (`sort_desktop`, `sort_tablet`, `sort_mobile`): Independent sort positions for Desktop, Tablet, and Mobile views.
- Device Content (`content_desktop`, `content_tablet`, `content_mobile`): Per-device HTML and shortcode content with automatic fallback to desktop content.
  - *HTML Widgets Drawer*: Insert pre-styled hero banners, feature grids, pricing tables, and callouts into the editor.
- **CSS & Theme Manager Tab**: Interactive color pickers and inputs for root CSS variables:
  - *Accent & Header Colors*: Primary accent, Secondary accent, Tertiary accent, Header background, Top nav container background, Desktop menu font color.
  - *Header & Footer Background Images*: Upload background image files or provide CDN image URLs (`header_bg_image_url`, `footer_bg_image_url`) with configurable background repeat (`no-repeat`, `repeat`, `cover`), background size, and background position.
  - *Top Navigation Links & Drop-downs*: Menu link hover color, Drop-down background color, Drop-down link color, Drop-down link hover color.
  - *Footer Typography & Links*: Footer background color, Footer header title color, Footer link color, Footer link hover color, Footer general text color, Footer base font size, Footer heading font size.
  - *Layout Bounds & Custom CSS*: Site max width, border radii, and custom CSS textareas for header and footer overrides.
- **Quick Shortcode Generator**: One-click insertion of logos, search bars, social icons, list menus, category links, brand links, current year tags, and plugin shortcodes.

### 4. CSS Theme Variable Manager (`HeaderFooterCssManager`)
The CSS Manager service (`App\Services\HeaderFooterCssManager`) compiles and injects dynamic design tokens into the storefront `<head>` via `<x-header-footer-styles />`:
- Configurable root CSS variables stored in `cms_settings`.
- Auto-compiled responsive CSS rules for top alerts, header columns, logo scaling, top navigation containers, top menu dropdown hover states, footer headings/links/text sizing, and back-to-top floating buttons (`#backtop`).

### 5. Shortcode Parser & Dynamic Logo System (`HeaderFooterParserService`)
The parser service (`App\Services\HeaderFooterParserService`) processes raw block content:
- **Dynamic Logo & Site Title**: `{{Logo}}`, `{{Logo-Medium}}`, and `{{Logo-Small}}` resolve the site logo configured in **Admin Settings** (`CmsSetting::resolveLogoUrl()`) and site title (`CmsSetting::getSiteName()`). If no custom logo is uploaded, it falls back to the default SVG logo icon, and if no custom site title is set, it falls back to `APP_NAME` from `.env`. Both the logo icon and site title text are rendered side-by-side in an inline flex container.
- **Live Search Bar**: `{{Search Bar: Live Keyword Search}}` renders quick keyword search inputs connecting to the storefront catalog.
- **Social Media & News Flash**: `{{Social Media Icons (Small)}}` and `{{News Flash Display}}` expand social links and alert texts.
- **E-Commerce & Menus**: `{{menu||id, label}}`, `{{category||id, label}}`, `{{subcategory||id, label}}`, `{{brand||id, label}}` resolve dynamic list menus and store taxonomy links.
- **Year Tag**: `{{year}}` / `#year#` dynamically inserts the current calendar year (`date('Y')`).
- **Plugin Shortcodes**: Integrated with `ContentParserService` and `ShortcodeProcessor` for `[plugin:slug]`, `[cms-form]`, `[download]`, and `[code-embed]` tags.


### 5. Professional 5-Column Responsive Starter Footer
* **5 Primary Columns**:
  - `footer_col1`: Quick Navigation menu links (Home, Shop Catalog, Featured Products, Our Brands, Knowledge Base).
  - `footer_col2`: Customer Service links (Cart, Checkout, Order Status, Shipping Policy, Returns & Exchanges).
  - `footer_col3`: Company & Legal links (About Us, Contact Support, Privacy Policy, Terms of Service, FAQ).
  - `footer_col4`: Corporate Info & Contact details (Headquarters address, Phone with `tel:`, Email with `mailto:`, Business Hours).
  - `footer_col5`: Connect & Social Media column with `[plugin:social-icons-2026 size=md font_awesome=on]`.
* **Bottom Copyright Row (`footer_row4`)**: Responsive bottom copyright bar with dynamic `{{year}}`, copyright notice, legal links, and social icons (`[plugin:social-icons-2026 size=sm font_awesome=on]`) on the bottom right.
* **Responsive CSS Grid**: Set `.site_footer_columns_primary li` width to 18% for 5 side-by-side columns on desktop, auto-adapting to 3 columns on tablet (`30%`), 2 columns on small tablet (`45%`), and 1 column on mobile (`100%`).

### 6. Live Search 2026 Built-In Display Plugin (`[plugin:live-search-2026]`)

A next-generation, multi-content type search system built as a standard display plugin (`LiveSearchPlugin`) following the `[plugin:name-2026]` naming convention.

#### 🔍 Core Capabilities & Content Coverage
`[plugin:live-search-2026]` automatically queries across **all 4 active platform content types** and unifies them into a single search experience:
1. **Products (`Product`)**: Queries active products by `title`, `search_tags`, `short_description`, and `long_description`. Displays primary product thumbnail (`primaryThumbnailUrl()`), title, excerpt, and direct link to the product page.
2. **CMS Pages (`CmsPage`)**: Queries active pages (`is_active = 1`) by `title` and `content`. Displays featured image (`featuredImageUrl()`), page title, content snippet, and SEO page link.
3. **Knowledge Base Articles (`KbArticle`)**: Queries active articles (`article_active = 1`) by `title` and `article_content`. Displays a specialized KB book icon, article title, snippet, and article URL.
4. **Customer Testimonials (`CmsTestimonial`)**: Queries active testimonials (`is_active = 1`) by `author_name`, `company_name`, and `content`. Displays author avatar (`avatar_image`) or quote icon placeholder, author details, and testimonial excerpt.

#### ⚙️ Modes & Shortcode Parameters
* **Shortcode**: `[plugin:live-search-2026]`
* **Available Parameters**:
  * `mode=input|results`: `input` renders the search bar widget with live autocomplete dropdown; `results` renders the full search landing page results grid/list.
  * `placeholder="Custom placeholder text..."`: Overrides the default input field placeholder (default: `"Search products, pages, articles..."`).
  * `button_label="Find"`: Overrides the Go button text (default: `"Search"`).
  * `layout=list|grid`: Switches the results landing page presentation between responsive list rows and 3-column cards grid.
  * `custom_css="/* CSS overrides */"`: Injects custom CSS rules directly scoped to the search widget wrapper.
  * `custom_js="// Custom JS logic"`: Injects custom JavaScript code inside an execution closure, allowing full developer overrides of default form submit or keyup behaviors.

#### 🚀 API Endpoint & Seeded Search Page
* **Seeded Search Page**: A default CMS page with slug `/search` is automatically seeded in `DatabaseSeeder` containing `[plugin:live-search-2026 mode=results]` in its main body.
* **Live Search Autocomplete API**: Exposes `GET /api/live-search-api?q={query}` (handled by `PluginApiController@liveSearchApi`), returning instant JSON search suggestions enriched with category badges (`Product` [indigo], `CMS Page` [emerald], `Knowledge Base` [sky], `Testimonial` [amber]), thumbnail URLs, snippets, and target URLs for Alpine.js real-time dropdown rendering.

---

## 🎛️ 9. Advanced Search Filtering System (Shop Catalog)

A powerful, multi-attribute product discovery and drill-down engine for the shop storefront (`/shop`). Configurable globally via Admin Settings (**OFF by default**), it enables customers to refine product lists by multiple brands, hierarchical categories/subcategories, interactive price range sliders, and dynamic JSON variant attributes.

### ⚙️ Admin Configuration & Default State
* **Admin Setting Key**: `enable_advanced_shop_search`
* **Control Location**: **Admin &rarr; Settings &rarr; Shop Display &rarr; Enable Advanced Search Filtering Panel**
* **Default State**: **OFF (disabled)**. When disabled, the shop catalog renders in standard category/brand mode without showing the left slideout filter drawer.
* **Helper Method**: `CmsSetting::isAdvancedSearchEnabled()` evaluates truthy status.

### 🎨 Storefront Slideout Drawer & Collapsible Panel
When enabled, the Shop page (`ShopCatalog` Livewire component) features a left-side slideout panel and collapsible controls:
1. **Slideout Trigger Button**: Displayed on the shop search toolbar (`"Advanced Filters"`) complete with active filter badge counts. Clicking slides out a full-height drawer from the left side of the screen with a smooth backdrop blur overlay.
2. **Multi-Select Brand Checkboxes**: Multi-select checkboxes for all active brands in the store, allowing customers to filter products across multiple manufacturers simultaneously.
3. **Hierarchical Category & Subcategory Checkboxes**: Multi-level tree listing of categories and subcategories with recursive parent/descendant filtering.
4. **Dual Price Range Slider**: Interactive range slider and dual numeric input fields (`minPriceFilter` and `maxPriceFilter`), dynamically bounded from `0` to the highest pricing item in the store catalog (`catalogMaxPrice`). Automatically adapts calculations for wholesale users if wholesale mode is active.
5. **Dynamic JSON Variant Attribute Filtering**:
   - Automatically inspects the `attributes` JSON column across all `ProductVariant` records (e.g. `{"Size":"XL","Color":"Red"}`).
   - Dynamically extracts available attribute keys (e.g. **Size**, **Color**, **Material**, **Style**) and their unique values.
   - Generates collapsible accordion groups for each attribute key with multi-select checkboxes.
6. **Filter Action Bar**: Includes a **Reset All Filters** button to clear active checkboxes and price bounds instantly, as well as an **Apply & View Products** button.
7. **URL State Synchronization**: Active filter states (`selectedBrands`, `selectedCategories`, `minPriceFilter`, `maxPriceFilter`, `selectedAttributes`) automatically synchronize with URL query parameters for shareable search result URLs.

---

## 📦 10. Bulk Product & Variant Import System

A high-performance bulk catalog migration and spreadsheet import engine built for migrating products from legacy e-commerce platforms (or external databases). Accessible under the **Shop Administration** sidebar at `/admin/ecommerce/import` (`AdminProductImport` Livewire component).

### 🛠️ Key Capabilities & Technical Breakdown

1. **Multi-Format Spreadsheet Reader**:
   - **Supported File Extensions**: CSV (`.csv`), TXT (`.txt`), and Excel Spreadsheets (`.xlsx`, `.xls`).
   - **Required Composer Dependency**: `phpoffice/phpspreadsheet` (handles native `.xlsx`, `.xls`, and UTF-8 encoded `.csv` file parsing).

2. **Smart Header Auto-Detection & Custom Field Mapping**:
   - Automatically auto-detects header names and allows admins to configure custom dropdown mappings for all system fields:
     - `title`, `short_description`, `long_description`
     - `public_price`, `wholesale_price`
     - `categories` (Supports single category names, hierarchy syntax `Apparel > Outerwear > Jackets`, comma-separated names `Electronics, Audio`, or JSON arrays `["Electronics", "Gadgets"]`)
     - `brand` (Brand name or slug)
     - `thumbnail_url`, `main_image_url`, `zoom_images_url`
     - `image_url_source` (`1` = Direct external URL link, `0` / blank = Download remote HTTP image and store locally)
     - `variant_sku`, `variant_name`, `variant_attributes` (`Size:M, Color:Black`), `variant_price`, `variant_wholesale_price`, `inventory`

3. **Single vs. Multi-Variant Product Grouping & Duplicate Handling**:
   - **Multi-Variant Product Grouping**: Multiple rows in the spreadsheet sharing the same product title or SKU base are automatically merged into a single parent `Product` with multiple child `ProductVariant` records (Size, Color, Variant Attributes, Variant Pricing, and Stock Inventory).
   - **Single-Variant Fallback**: If an item row contains no variant options, the importer creates a default single `ProductVariant` record using the row's pricing and image fields.
   - **Exact SKU Duplicate Matching**: If an imported row contains a `variant_sku` matching an existing variant in the database, the importer updates the existing product and variant records instead of creating duplicate items.

4. **Taxonomy Auto-Creation (Categories, Subcategories & Brands)**:
   - **Categories & Subcategories**: Automatically parses category hierarchy strings (e.g. `Apparel > Outerwear > Jackets`) and recursively creates missing parent and child categories in the database, linking products to all resolved category IDs.
   - **Brands**: Automatically creates missing brand entries in the database if the brand name does not exist yet.

5. **Image URL Handling & Local Storage Downloader**:
   - **Direct External URL Mode (`image_url_source = 1`)**: Stores the remote image URL directly on the `ProductImage` record (`image_url_source = 1`), referencing external CDN image paths without consuming local disk space.
   - **Local Storage Downloader Mode (`image_url_source = 0` / default)**: Downloads remote image URLs via HTTP, saves them locally to `storage/cms_product_imports/...`, and updates relative storage paths on the `ProductImage` database record.

6. **Admin Dashboard & Sample Template Downloads**:
   - **Template Downloader**: One-click **Download Sample CSV** and **Download Sample Excel** template buttons directly on the import page.
   - **Live Preview & Execution Report**: Live preview table of parsed rows prior to execution, and a detailed post-import summary report detailing products created/updated, variants created/updated, categories auto-created, brands auto-created, images processed, and row execution errors.

---

## 🔍 11. Collated Search Index Engine & Multi-Content Live Search

An enterprise search index collation, keyword locking, and multi-content discovery engine that powers instant live search autocomplete (`[plugin:live-search-2026]`) and full-page search results.

### 🛠️ Key Capabilities & Technical Breakdown

1. **Collated Search Index Database Columns**:
   - **`cms_pages` Table**: `cms_search_index` (`LONGTEXT`) and `cms_search_index_locked` (`BOOLEAN`, default `false`).
   - **`products` Table**: `product_search_index` (`LONGTEXT`) and `product_search_index_locked` (`BOOLEAN`, default `false`).
   - **MySQL/MariaDB FULLTEXT Indexes**: Native database FULLTEXT indexes (`cms_pages_fulltext_search` on `(title, cms_search_index, content)` and `products_fulltext_search` on `(title, product_search_index, short_description)`) for relevance-ranked search performance.

2. **Automatic Index Collation & Shortcode / HTML Cleaning Engine**:
   - **Eloquent Saving Listener**: Model-level `booted()` saving listeners on `CmsPage` and `Product` automatically trigger `rebuildSearchIndex()` whenever records are created or updated.
   - **Plugin Shortcode & Embed Stripper (`stripShortcodesAndHtml`)**: Automatically detects and strips bracketed plugin shortcode tags (e.g. `[code-embed:12]`, `[plugin:brands-2026]`, `[plugin:live-search-2026]`, `[plugin:featured-items]`, etc.) and HTML tags to prevent code artifacts from cluttering search index keywords.
   - **Download & Event Keyword Auto-Indexing**: Automatically injects `"download downloads digital downloadable file files pdf ebook software"` into index fields for digital download items (`download_item = 1`), and `"event events ticket tickets experience seminar workshop admission registration"` for event products.

3. **Admin Keyword Editing & Lock Controls**:
   - **Dedicated Search Index Panel**: Available on both CMS Page Edit (`/admin/cms-pages/{id}/edit`) and Product Edit (`/admin/ecommerce/products/{id}/edit`) screens.
   - **Custom Keyword Textarea**: Allows site administrators to inspect, edit, or append custom keywords (synonyms, common misspellings, promo codes, external SKUs) directly to `cms_search_index` or `product_search_index`.
   - **Lock Index Toggle**: Toggle switch (`cms_search_index_locked` / `product_search_index_locked`) locks custom keywords, preventing automatic updates from overwriting admin-configured search keywords when saving records.
   - **Rebuild Index Button**: One-click **Rebuild Index** button refreshes search keywords from current content on demand.

4. **Multi-Content Live Search & Category / Brand Pill Badges**:
   - The Live Search API (`GET /api/live-search-api?q={query}`) and Live Search Plugin (`[plugin:live-search-2026]`) search across **6 distinct content types**:
     1. **Categories**: Enriched with purple **"Category"** pill badges, linking directly to the category's SEO slug route (`/section/{category_slug}`).
     2. **Brands**: Enriched with violet **"Brand"** pill badges and brand logo thumbnails, linking directly to the brand's SEO slug route (`/brands/{brand_slug}`).
     3. **Products**: Enriched with indigo **"Product"** pill badges, searching title, `product_search_index`, short/long descriptions, and SKUs.
     4. **CMS Pages**: Enriched with emerald **"Site Page"** pill badges, searching title, `cms_search_index`, meta descriptions, and page body.
     5. **Knowledge Base Articles**: Enriched with sky **"Knowledge Base"** pill badges.
     6. **Customer Testimonials**: Enriched with amber **"Testimonial"** pill badges.
   - **Relevance Ranking**: Title and search index matches are prioritized at the top of live dropdown suggestions and search results landing pages.

5. **Bulk Index Rebuild CLI Command**:
   - **Artisan Command**: `php artisan search:rebuild-index` (or with `--force` flag) bulk-rebuilds and cleans search index keywords for all existing CMS pages and products in the database.

---

## 📋 12. Admin Product COPY / Duplication Feature

An enterprise product cloning and duplication engine built directly into the Admin Product Manager (`/admin/ecommerce/products`, `AdminProducts` Livewire component). It allows site administrators to duplicate any existing product along with its configuration, customization fields, cross-selling links, pricing variants, stock levels, and image galleries in a single step.

### 🛠️ Key Capabilities & Technical Breakdown

1. **Interactive Duplication Modal & Custom Naming**:
   - **One-Click Trigger**: A dedicated **Copy** button on each product row in the main products table opens a slide-over modal overlay.
   - **Auto-Generated Editable Title**: Automatically pre-fills the new product title with `"{Original Product Title} - Copy {Random4Hex}"` (e.g. `"Wireless Bluetooth Headphones - Copy A7B9"`), which can be customized by the admin before saving.
   - **Auto-Generated Unique SEO Slug**: Pre-fills the SEO slug with `"{original-slug}-copy-{random4Hex}"` and automatically validates uniqueness against the database (`products.seo_slug`).

2. **Variants, Inventory & Image Duplication Control**:
   - **Flexible Duplication Toggle**: Includes a **"Duplicate All Variants, Inventory & Images"** toggle switch (checked by default).
   - **Atomic Transaction Safety**: Execution is wrapped inside a database transaction (`DB::transaction()`) to guarantee all relational entries are committed atomically.

3. **Complete Relational Data Duplication**:
   - **Base Product Attributes**: Copies short and long descriptions, meta tags, shipping rules, digital download settings, max quantities, standalone purchase rules, checkout redirects, layout types, review settings, and search index lock status.
   - **Category Assignments**: Automatically synchronizes all associated categories (`product_categories_assignments`).
   - **Product Customization Fields**: Copies all custom input fields (`ProductField`) and their selectable choices/surcharges (`ProductFieldOption`).
   - **Cross-Selling Links**: Copies all linked cross-selling recommendations (`ProductCrossSell`).
   - **Pricing Variants with Unique Random SKUs**: When variant duplication is checked, iterates through all original variants and creates new `ProductVariant` records assigned to the duplicated item, generating guaranteed unique SKUs (e.g. `SKU-COPY-WXYZ`).
   - **Stock & Inventory Levels**: Duplicates `ProductInventory` records (`quantity_available`, warehouse stock levels, reserved stock, and location assignments).
   - **Image Galleries**: Copies all image set entries (`ProductImage`) preserving thumbnail paths, main image paths, zoom paths, CDN URLs, and search image flags.
   - **Quantity Discount Breaks & Event Details**: Copies variant quantity discount ranges (`ProductQuantityDiscount`) and event ticket details (`ProductVariantEvent`).

---

## 📅 13. Events Calendar Display Plugin (`[plugin:events-calendar-2026]`)

An interactive events calendar display plugin (`EventsCalendarPlugin`) that can be inserted into any CMS page, product description, header/footer layout, or blog post via shortcode: `[plugin:events-calendar-2026]`.

### 🛠️ Key Capabilities & Technical Breakdown

1. **Automatic Event Product Discovery**:
   - Queries `ProductVariantEvent` records linked to active products where `event_start_date` is populated.
   - Resolves product title, SEO slug (`/product/{slug}`), primary thumbnail image URL, formatted admission ticket price (`$149.00`), start date/time, end date/time, event label, label background color (`label_background`), event location (`event_location`), and description (`event_description`).

2. **3 Display Modes (`layout=month|list|grid`)**:
   - **Month Grid View (`layout=month`)**: Full 7-column calendar grid with Previous Month (`<`) and Next Month (`>`) navigation, Today quick button, active month header, today indicator, and event pill badges color-coded by `label_background`.
   - **Agenda List View (`layout=list`)**: Chronological event list rows with event image thumbnail, event label pill, date & time range badge, product title, location marker, price, and "Book Event" button.
   - **Cards Grid View (`layout=grid`)**: Responsive 3-column event card grid with image header, badge overlay, date/time info, location tag, price tag, and view details CTA.

3. **Interactive Event Details Glassmorphism Modal**:
   - Clicking any event pill badge or event title opens an interactive slide-over/modal popup.
   - Displays event title, full date & time range, location, description snippet, ticket price, product image, and a direct **"Book Event Ticket"** button linking to the product page (`/product/{slug}`).

4. **Shortcode & Admin Plugin Options**:
   - **Shortcode**: `[plugin:events-calendar-2026]`
   - **Parameters**: `header="Upcoming Events"` (custom header), `layout=month|list|grid` (default layout), `max=50` (max events to display), `category="slug"` (optional category filter), `custom_css="..."` (custom styling rules).
   - **Admin Plugin Manager**: Manageable under **Admin -> Display Plugins / Plugin Manager** with configuration form options and read-only default CSS reference.

---

## 🔀 14. Post-Order Completion Redirect

A per-product override that redirects customers to a custom destination **immediately after a successful order is placed**, bypassing the default order confirmation page. Supports raw URLs, relative paths, and CMS page shortcodes — making it suitable for upsell landing pages, gated content portals, webinar rooms, member areas, or external onboarding flows.

### 🛠️ How It Works

When an order is completed, the checkout system inspects each purchased product for a `completion_redirect` value before showing the standard order confirmation page. If any purchased product has a redirect configured, the customer is sent there instead.

**Resolution Priority**: Items are evaluated in order-of-purchase sequence. The **first** product (by line-item order) with a non-empty, resolvable redirect wins. All other products' redirects are ignored for that order.

**Fallback Behaviour**: If no product in the order has a redirect configured, or if a `[page:ID]` shortcode references a CMS page that no longer exists, the system falls back gracefully to the standard order confirmation page (`/checkout/success/{external_id}`).

---

### ⚙️ Admin Configuration

Navigate to **Admin → Products → Edit Product → Advanced Settings** and scroll to the **Post-Order Completion Redirect** section.

#### Field: Destination URL / Shortcode

| Input Format | Description | Example |
|---|---|---|
| **Absolute URL** | Redirects the customer to any external or internal full URL | `https://example.com/thank-you` |
| **Relative path** | Redirects to any relative path on the same domain | `/members/welcome` |
| **CMS Page shortcode** | Resolved to the CMS page's public URL via the page's SEO slug | `[page:5]` or `[page:5 label="Welcome"]` |

- Leave this field **blank** to use the default order confirmation page (no override active).
- When a URL is set, a **violet active-redirect badge** appears below the field confirming the value and showing the exact destination the customer will be sent to.

#### Field: Button Label

When a destination URL is set, a **Button Label** field appears directly below. This controls the text of the action button that appears **in all three order-related emails** next to the line item for this product.

| Setting | Result |
|---|---|
| Leave blank | Displays `"View Content"` (system default) |
| Enter custom text | Displays your custom label (max 255 characters) |

A **live button preview** renders directly in the admin UI to show exactly how the label will appear in emails before saving.

---

### 📧 Email Integration

When a product with a `completion_redirect` set is part of an order, a **"View Content" button** (or your custom label) is automatically injected into the item row of all three order-related emails:

| Email | Trigger | Description |
|---|---|---|
| **Order Confirmation** | Sent automatically on checkout completion & available as admin resend | Appears next to each relevant line item, after the "Download File" button (if applicable) |
| **Shipment Confirmation** | Sent by admin when marking an order as shipped | Appears next to each relevant line item |
| **Download Reminder** | Manually triggered by admin from the Order Details screen | Appears next to each relevant line item |

The button is **styled in violet** (`#7c3aed`) to visually distinguish it from the indigo "Download File" button. It links directly to the resolved destination URL (with `[page:ID]` shortcodes fully resolved to absolute URLs at send time).

> **Note:** The button only appears for line items whose product has `completion_redirect` set. If a customer orders multiple products, only the products with a redirect configured will show the button — other items display normally.

---

### 🧩 CMS Page Shortcode Reference

The `[page:ID]` shortcode format is the standard CMS page link shortcode used throughout the platform. You can find the correct ID for any CMS page by:

1. Navigating to **Admin → CMS → Pages**
2. Opening the page you want to link to
3. Copying the shortcode from the **Shortcode Generator** drawer (available in the TinyMCE toolbar), or noting the numeric ID from the page's edit URL: `/admin/cms-pages/{ID}/edit`

The optional `label` attribute in the shortcode (`[page:5 label="Welcome Page"]`) is **ignored** by the redirect resolver — the label is only used in the TinyMCE Shortcode Generator for display purposes. The system always resolves the ID to the page's actual SEO slug URL.

---

### 🗄️ Database Schema

Two columns are added to the `products` table:

| Column | Type | Default | Description |
|---|---|---|---|
| `completion_redirect` | `VARCHAR(1000)` | `NULL` | Raw redirect value: absolute URL, relative path, or `[page:ID]` shortcode |
| `completion_redirect_label` | `VARCHAR(255)` | `NULL` | Custom email button label; resolves to `"View Content"` if null or empty |

Both columns are nullable. If both are `NULL`, there is **no behavioural change** to the order flow — the default confirmation page is shown and no extra button appears in emails.

---

### 🔑 Key Files Reference

| File | Role |
|---|---|
| `app/Models/Product.php` | `resolveCompletionUrl(?string $raw): ?string` — static helper that resolves raw values to absolute URLs (single source of truth) |
| `app/Models/Product.php` | `completionRedirectLabel(): string` — instance method returning the label with `"View Content"` fallback |
| `app/Livewire/OrderReview.php` | `resolveCompletionRedirect(Order $order)` — called after order placement to determine the customer's redirect destination |
| `app/Livewire/OrderReview.php` | Order confirmation email builder — injects "View Content" button per line item |
| `app/Livewire/AdminOrderDetails.php` | Shipment confirmation, download reminder, and admin order confirmation resend email builders — each injects "View Content" button per line item |
| `app/Livewire/AdminProductEdit.php` | Livewire component managing `completion_redirect` and `completion_redirect_label` in the Advanced Settings form |
| `resources/views/livewire/admin-product-edit.blade.php` | Advanced Settings panel UI — destination field, button label field, live preview, and active-redirect badge |
| `database/migrations/2026_07_26_000002_add_completion_redirect_to_products_table.php` | Adds `completion_redirect` column |
| `database/migrations/2026_07_26_000003_add_completion_redirect_label_to_products_table.php` | Adds `completion_redirect_label` column |

---

### ✅ Usage Examples

#### Example A — Redirect to an External URL

**Scenario**: You sell an online course hosted on an external portal. After purchase, redirect the customer straight to the course portal instead of the default order confirmation page, and show a **"Start Learning"** button in all order emails.

1. Go to **Admin → Products → Edit** your course product.
2. Scroll to **Advanced Settings → Post-Order Completion Redirect**.
3. Enter `https://portal.example.com/courses/my-course` in the **Destination URL** field.
4. Enter `Start Learning` in the **Button Label** field.
5. Click **Save Advanced Settings**.

**Result:**
- After checkout the customer is immediately redirected to `https://portal.example.com/courses/my-course`.
- The order confirmation, shipment confirmation, and download reminder emails all display a violet **"Start Learning"** button next to that line item.
- All other products in the store continue to use the default order confirmation page.

---

#### Example B — Redirect to a Gated CMS Page (Purchase-Verified Access)

**Scenario**: You sell a membership, digital workshop, or gated resource. The content lives on a CMS page inside your own site (e.g. `/members/workshop-materials`). You only want customers who have **purchased the product** to be able to view that page. After checkout, send them straight there, and also show an **"Access Content"** button in all order emails.

This pattern combines two separate features: the **Post-Order Completion Redirect** (on the product) and the **CMS Page Product Purchase Gate** (on the CMS page). For full details on all gate types, combinations, and the dual-gate UI, see **§ 15 — CMS Page Access Gating** below.

##### Step 1 — Note your Product ID

In **Admin → Products → Edit**, note the numeric product ID from the page URL: `/admin/ecommerce/products/{ID}/edit`.

##### Step 2 — Create or edit the destination CMS page

1. Go to **Admin → CMS → Pages** and open (or create) the page for your gated content.
2. In the **Page Access / Gating** section of the CMS page editor, set **Required Product ID** to the numeric ID of the product from Step 1.
3. Save the CMS page. Note the page ID from the URL: `/admin/cms-pages/{PAGE_ID}/edit`.

##### Step 3 — Set the Completion Redirect on the product

1. Go back to **Admin → Products → Edit** for your product.
2. Scroll to **Advanced Settings → Post-Order Completion Redirect**.
3. Enter the CMS page shortcode in the **Destination URL** field:
   ```
   [page:PAGE_ID]
   ```
   *(Replace `PAGE_ID` with the numeric ID noted in Step 2.)*
4. Enter `Access Content` (or your preferred label) in the **Button Label** field.
5. Click **Save Advanced Settings**.

##### How it resolves at checkout

When a customer completes their purchase:

1. `OrderReview::resolveCompletionRedirect()` inspects each ordered product for a `completion_redirect` value.
2. It calls `Product::resolveCompletionUrl('[page:PAGE_ID]')`, which queries `CmsPage::find(PAGE_ID)` and returns the page's absolute public URL.
3. The customer is redirected to that URL via `redirect()->away($url)`.
4. `PageController::show()` runs the purchase gate — since the customer just purchased the product, `hasPurchasedProduct()` returns `true` and they are granted access immediately.

**Result:**
- The customer lands directly on the gated content page immediately after payment — no extra login or confirmation step.
- Any future visit (while logged in and verified) is served instantly from the session cache.
- Unauthenticated visitors or users who have not purchased are blocked and redirected to login.
- All three order emails display a violet **"Access Content"** button linking directly to the resolved page URL.

---

## 🔐 15. CMS Page Access Gating

A flexible, dual-mechanism access control system for individual CMS pages. Pages can be restricted by **product purchase verification**, an **access code**, or **both simultaneously**. The two gates operate independently — satisfying either one is sufficient when both are active.

### 🛠️ Gate Types

#### A. Product Purchase Gate (`required_product_id`)

Set a numeric **Required Product ID** on a CMS page. `PageController` will check on every visit whether the authenticated user has a completed, paid order containing a variant of that product.

- **Purchase check**: calls `User::hasPurchasedProduct($productId)`, which queries the `order_details` table.
- **Session cache**: verified purchases are stored in `session('verified_purchased_products')` for the duration of the session to avoid repeated DB queries.
- **Admin bypass**: users with `isEcommerceAdmin()` always bypass this gate.

#### B. Access Code Gate (`requires_code` + `access_code`)

Set **Requires Code** to `true` and provide a plaintext **Access Code** string. Visitors must enter the correct code on the lock screen to unlock the page for the duration of their session.

- Unlocked codes are stored in `session('unlocked_access_codes')` as an array — a visitor can unlock multiple code-gated pages per session.
- Submitting an incorrect code returns a validation error on the same lock screen.
- There is no brute-force rate limiting at the application layer; use server-level throttling if needed.

---

### ⚙️ Gate Decision Matrix

| Product Gate | Code Gate | Neither satisfied | Product satisfied | Code satisfied | Both satisfied |
|---|---|---|---|---|---|
| ✅ On | ❌ Off | → Login redirect | ✅ Access granted | — | — |
| ❌ Off | ✅ On | → Lock screen (code only) | — | ✅ Access granted | — |
| ✅ On | ✅ On | → Lock screen (dual-gate UI) | ✅ Access granted | ✅ Access granted | ✅ Access granted |
| ❌ Off | ❌ Off | ✅ Public — always accessible | — | — | — |

**Key rule**: when both gates are active, satisfying **either one** grants access. The user does not need to satisfy both.

---

### 🖥️ Lock Screen UI (`pages/cms-password.blade.php`)

The lock screen has two rendering modes controlled by the `$dualGate` boolean passed from `PageController`:

#### Single Gate (code only)
- Standard lock icon header
- Plain description: *"Please enter the required access code to view its content."*
- Access code input + **Unlock Content** button

#### Dual Gate (both product + code active)
- Amber info alert: *"Already purchased this content? Log in to your account and you will be granted access automatically."*
- Access code input + **Unlock Content** button
- **"or" divider**
- **"Log in to verify purchase"** button — links to `/login` with `session('url.intended')` pre-set to the restricted page, so after successful login the user is automatically redirected back and the purchase check grants access

---

### 🔄 Login Redirect Flow (Purchase Gate)

Whenever the product gate blocks access, `PageController` sets:
```php
session(['url.intended' => route('page.show', $page->slug)]);
```
This ensures Laravel's built-in `RedirectIfAuthenticated` / `authenticated()` middleware bounces the user back to the gated page after a successful login — no extra code required.

**Full flow for an unauthenticated user visiting a purchase-gated page:**
1. Visit `/members/workshop-materials` → gate blocks, sets `url.intended`, redirects to `/login`
2. User logs in successfully
3. Laravel reads `url.intended` → redirects back to `/members/workshop-materials`
4. `PageController` runs purchase check → verified → page served

---

### 🗄️ Database Schema (CMS Pages)

| Column | Type | Description |
|---|---|---|
| `required_product_id` | `INT`, nullable | Foreign key to `products.id`. If set, product purchase gate is active |
| `requires_code` | `BOOLEAN`, default `false` | Enables the access code gate |
| `access_code` | `VARCHAR`, nullable | The plaintext access code visitors must enter |

---

### 🔑 Key Files Reference

| File | Role |
|---|---|
| `app/Http/Controllers/PageController.php` | `show()` — evaluates both gates independently, applies the AND/OR decision matrix, sets `url.intended`, and passes `$dualGate` to the view |
| `app/Http/Controllers/PageController.php` | `unlock()` — validates the submitted access code and adds it to `session('unlocked_access_codes')` on success |
| `resources/views/pages/cms-password.blade.php` | Lock screen blade — renders single-gate or dual-gate UI based on `$dualGate` variable |
| `app/Models/CmsPage.php` | `required_product_id`, `requires_code`, `access_code` fillable columns |
| `app/Models/User.php` | `hasPurchasedProduct(int $productId): bool` — DB lookup used by the purchase gate |

---

### ✅ Configuration Example — Dual Gate (Purchase OR Code)

**Scenario**: A workshop page should be accessible to anyone who has purchased a ticket, *or* to staff/partners who have been given the access code directly.

1. Go to **Admin → CMS → Pages → Edit** the workshop page.
2. In the **Page Access / Gating** section:
   - Set **Required Product ID** to the workshop ticket product's ID.
   - Enable **Requires Access Code** and enter a code (e.g. `STAFF2026`).
3. Save the page.

**Visitor experience:**
- A customer who purchased a ticket → logs in → product gate passes → immediate access.
- A staff member who hasn't purchased → enters `STAFF2026` on the lock screen → code gate passes → immediate access.
- An unauthenticated stranger → sees the lock screen with both the code input **and** the login prompt — can choose either path.
- Neither path succeeded → remains on the lock screen.

---

## 🔗 16. Secure Content Access Tokens ("View Content" Magic Links)

Every product that has a **Post-Order Completion Redirect** (§ 14) configured also gets a **secure, unique UUID magic link** injected into the "View Content" button in order emails. This replaces the previous behaviour of linking directly to the resolved URL, which would have forced guest purchasers to log in before accessing their content.

### 🎯 Why This Exists

- **Guest purchasers** (e.g. webinar attendees who check out without creating an account) receive an order confirmation email. They have no password, so they cannot log in to satisfy the CMS page purchase gate. The UUID magic link bypasses the gate for their session without any login step.
- **Security**: the token is a random UUID — it cannot be guessed. Each token is tied to a single order line item.
- **Expiry**: tokens expire after **90 days** by default, giving customers long-term access without the link being permanently valid.
- **Resend safety**: when an admin resends any order email, the token for that line item is **regenerated** with a fresh 90-day window — the old link is invalidated and the customer receives a new one.

---

### 🔄 Token Lifecycle

```
Order placed
    │
    └─► Order confirmation email built
            │
            └─► For each line item with a completion_redirect:
                    │
                    └─► ContentAccessToken::generateOrRefresh($item, $resolvedUrl, $email)
                            │
                            ├─ First send  → INSERT new UUID token, expires_at = now() + 90 days
                            └─ Resend      → UPDATE same record with new UUID + fresh 90-day expiry

Customer clicks "View Content" in email
    │
    └─► GET /content-access/{uuid}
            │
            ├─ Token not found          → 404
            ├─ Token expired            → 410 Gone
            └─ Valid token:
                    │
                    ├─ Record accessed_at (first click only)
                    ├─ Push product_id into session('verified_purchased_products')
                    └─► redirect()->away($record->redirect_url)
                                │
                                └─► PageController::show() — purchase gate passes (session key set)
                                        └─► CMS page content served ✅
```

---

### 🗄️ Database Schema

**Table: `content_access_tokens`**

| Column | Type | Description |
|---|---|---|
| `id` | `BIGINT`, PK | Auto-increment |
| `token` | `UUID`, unique, indexed | The secure random identifier used in the magic link URL |
| `order_detail_id` | `BIGINT`, FK → `order_details.id` | The specific line item this token grants access for. Cascades on delete |
| `product_id` | `INT`, indexed | The product's ID — used to set the session purchase gate bypass |
| `redirect_url` | `VARCHAR(2000)` | The pre-resolved absolute destination URL stored at generation time |
| `email` | `VARCHAR(255)` | Recipient email for audit purposes |
| `accessed_at` | `TIMESTAMP`, nullable | Timestamp of the token's first redemption (not updated on repeat clicks) |
| `expires_at` | `TIMESTAMP`, nullable | Expiry datetime — `NULL` means no expiry (not used by default) |
| `created_at` / `updated_at` | `TIMESTAMP` | Standard Laravel timestamps |

---

### 🛠️ Key Files Reference

| File | Role |
|---|---|
| `database/migrations/2026_07_26_000005_create_content_access_tokens_table.php` | Creates the `content_access_tokens` table |
| `app/Models/ContentAccessToken.php` | Eloquent model; `generateOrRefresh(OrderDetail, string, string, int): self` static helper |
| `app/Http/Controllers/ContentAccessController.php` | `redeem(string $token)` — validates, records access, sets session gate bypass, redirects |
| `routes/web.php` | `GET /content-access/{token}` — **no auth middleware**, publicly accessible |
| `app/Livewire/OrderReview.php` | Order confirmation email builder — generates token and uses `/content-access/{token}` URL in button |
| `app/Livewire/AdminOrderDetails.php` | All three admin email builders (shipment, download reminder, resend) — same token-based URL |

---

### ⚙️ How the Session Gate Bypass Works

When a valid token is redeemed, `ContentAccessController` does:

```php
$verified = session('verified_purchased_products', []);
if (!in_array($record->product_id, $verified)) {
    $verified[] = $record->product_id;
    session(['verified_purchased_products' => $verified]);
}
return redirect()->away($record->redirect_url);
```

`PageController::show()` already checks this session array as part of its purchase gate evaluation. The CMS page sees the product ID in the verified list and grants access — identical to the result of a normal authenticated purchase check, but without requiring login.

> [!NOTE]
> The session gate bypass is scoped to the visitor's **browser session**. If they close and reopen their browser, they must click the magic link again (or log in if they have a registered account). This is intentional — persistent bypass would reduce security.

---

### ✅ End-to-End Example — Guest Webinar Purchase

**Scenario**: A guest (no account) purchases a webinar ticket. The product has `completion_redirect` set to `[page:42]` (a gated CMS page with `required_product_id = 15`).

1. Guest completes checkout → order confirmation email sent.
2. Email contains a violet **"View Webinar"** button linking to `/content-access/a1b2c3d4-...`.
3. Guest clicks the button — no login prompt appears.
4. `ContentAccessController::redeem()` runs:
   - Finds the token (valid, not expired)
   - Sets `session('verified_purchased_products') = [15]`
   - Redirects to `https://yoursite.com/members/webinar-recording`
5. `PageController` checks the session → product 15 is verified → page is served.
6. Guest watches the webinar. ✅

**If the guest later creates a full account** with the same email address, they can log in and the normal `hasPurchasedProduct()` check on their order history will also grant access independently of the token.

---

## 👤 17. Guest Account Conversion Flow

When a customer completes a **guest checkout** without supplying a password, the system creates a `User` record for them (to associate their order and support magic-link content access) but stores a plain-text sentinel value instead of a real bcrypt/argon2 hash. These are called **guest users**.

If a guest user later tries to access their account dashboard or log in, they are intercepted and guided through a secure **two-step conversion flow**:

1. **Verify email first** — proves inbox ownership before any password is set.
2. **Set password second** — only reachable after clicking the signed verification link.

This order is a deliberate security decision. See [§ Security Rationale](#-security-rationale) below.

---

### 👻 Guest User Detection — The `[GUEST-USER]` Sentinel

A user is considered a **guest** when their `users.password` column contains the literal plain-text string:

```
[GUEST-USER]
```

This sentinel is stored **unhashed** during guest checkout when the customer does not supply a password. It is defined as a PHP constant:

```php
// app/Models/User.php
public const GUEST_PASSWORD = '[GUEST-USER]';

public function isGuest(): bool
{
    return $this->password === self::GUEST_PASSWORD;
}
```

#### Why a sentinel instead of `NULL`?

- `NULL` is ambiguous — some legacy flows or DB seeds might produce a `NULL` password without the user being a guest.
- A plain-text sentinel is **intentionally unverifiable** by Laravel's `Hash::check()`. Because `[GUEST-USER]` is not a valid bcrypt/argon2 string, `Auth::attempt()` will always return `false` for these accounts — they literally cannot log in via the standard login form without going through the conversion flow.
- `isGuest()` is a single, unambiguous string comparison. No compound boolean conditions.

#### How the sentinel gets written

In `Checkout::saveDetailsAndContinue()`, when a guest does not enter a password:

```php
// app/Livewire/Checkout.php
$hasProvidedPassword = !empty($this->password);
$userPassword = $hasProvidedPassword
    ? Hash::make($this->password)       // real password → bcrypt hash
    : \App\Models\User::GUEST_PASSWORD; // no password → plain-text sentinel
```

The sentinel is written directly to `users.password` via `User::create()` or `User::update()` — **never** through a `Hash::make()` call, so it remains detectable as a literal string.

---

### 🔒 Security Rationale — Verify Email Before Setting Password

> [!IMPORTANT]
> Email verification must happen **before** the set-password page is accessible. This prevents account hijacking.

**The threat**: An attacker who knows (or guesses) a guest's email address (e.g. from a publicly visible order receipt or a data leak) could potentially reach the `/account/set-password` page, set a new password, and take over the account — gaining access to all of that user's orders and content magic links.

**The fix**: The set-password page (`GuestSetPassword::mount()`) checks `$user->hasVerifiedEmail()` before rendering. If the guest has not yet clicked the signed verification link:
- They are redirected to `/email/verify`.
- `session('url.intended')` is set to `route('guest.set-password')` so that **after** clicking the link they land directly on the set-password page.

Because the verification link is a **signed, time-limited URL** (Laravel's standard signed email verification), an attacker cannot forge it. Only the real inbox owner can click it.

---

### 🔄 Full Conversion Flow

```
Guest user tries to access their account
(via /dashboard, /login, or a direct URL)
         │
         │  Entry point 1: visits /dashboard
         │  ─────────────────────────────────
         ├─► UserDashboard::mount()
         │       └─ isGuest() && !hasVerifiedEmail()
         │               ├─ session('url.intended') = route('guest.set-password')
         │               ├─ $user->sendEmailVerificationNotification()
         │               └─► redirect → GET /email/verify
         │
         │  Entry point 2: tries to log in at /login
         │  ──────────────────────────────────────────
         ├─► LoginForm::authenticate()
         │       └─ detects [GUEST-USER] sentinel before Auth::attempt()
         │               ├─ Auth::login($guest)  ← auto-logs in without a password
         │               ├─ (if unverified):
         │               │       ├─ session('url.intended') = route('guest.set-password')
         │               │       ├─ $user->sendEmailVerificationNotification()
         │               │       └─ session()->flash('guest_redirect', route('verification.notice'))
         │               └─ (if already verified):
         │                       └─ session()->flash('guest_redirect', route('guest.set-password'))
         │                       [login blade picks up guest_redirect and redirects accordingly]
         │
         │  Entry point 3: navigates directly to /account/set-password
         │  ────────────────────────────────────────────────────────────
         └─► GuestSetPassword::mount()
                 ├─ not authenticated → redirect to /login
                 ├─ not isGuest() (already converted) → redirect to /dashboard
                 └─ isGuest() && !hasVerifiedEmail() → back to /email/verify
                    [url.intended re-set to guest.set-password on every bounce]

                 ─── only reachable if: authenticated + isGuest() + hasVerifiedEmail() ───

──────────────────────────────────────────────────────────────────────────────
Step 1 — Email Verification Gate
──────────────────────────────────────────────────────────────────────────────

         GET /email/verify  (verification notice page)
                 │
                 ├─ Shows email address, "check your inbox" message
                 ├─ "Resend" button available if email was lost
                 └─ Guest clicks the signed link in their email
                         │
                         └─► email_verified_at = now()
                                 │
                                 └─► redirect → url.intended = /account/set-password ✅

──────────────────────────────────────────────────────────────────────────────
Step 2 — Set Password
──────────────────────────────────────────────────────────────────────────────

         GET /account/set-password
                 │
                 └─► GuestSetPassword renders (guards pass: authed + guest + verified)
                         │
                         └─ Guest submits password + confirmation
                                 │
                                 ├─ Hash::make($password) → overwrites [GUEST-USER] sentinel
                                 ├─ $user->save()
                                 └─► redirect → GET /dashboard ✅
                                         │
                                         └─ isGuest() now false → renders normally
```

---

### 🖥️ Set Password Page (`/account/set-password`)

The page at `GET /account/set-password` is handled by the `GuestSetPassword` Livewire component (`#[Layout('layouts.public')]`) and displays:

- **"Email verified" success notice** — teal banner confirming the inbox has been proven.
- **Password field** — minimum 8 characters, validated server-side.
- **Confirm password field** — must match exactly.
- **"Activate My Account" button** — submits, saves the hashed password, redirects to dashboard.
- **Signed-in-as footer** — shows current email with a "Sign out" button (`wire:click="logout"`).

**Access guards (checked in `mount()` in order):**

| Condition | Action |
|---|---|
| Not authenticated | → `/login` |
| Authenticated, `isGuest()` false (real account) | → `/dashboard` |
| Authenticated, `isGuest()` true, **not** email verified | → `/email/verify` (url.intended preserved) |
| Authenticated, `isGuest()` true, **email verified** ✅ | → Renders form |

---

### 🗄️ Database Impact

No new columns or tables are required. The flow uses only the existing `users.password` and `users.email_verified_at` columns.

| Column | During checkout (no password given) | After email verified | After password set |
|---|---|---|---|
| `users.password` | `[GUEST-USER]` (plain text, unhashed) | `[GUEST-USER]` (unchanged) | `$2y$…` bcrypt hash |
| `users.email_verified_at` | `NULL` | Current timestamp | Current timestamp |
| `User::isGuest()` | `true` | `true` | `false` |
| Can log in via `/login`? | ❌ blocked by sentinel check | ❌ blocked by sentinel check | ✅ yes |
| Can access `/dashboard`? | ❌ → verify email | ❌ → set password | ✅ yes |

---

### 🛠️ Key Files Reference

| File | Role |
|---|---|
| `app/Models/User.php` | `GUEST_PASSWORD` constant; `isGuest()` method — single string equality check |
| `app/Livewire/Checkout.php` | Writes `User::GUEST_PASSWORD` sentinel for guests who provide no password |
| `app/Livewire/UserDashboard.php` | `mount()` — detects guest state and routes to verify-email or set-password |
| `app/Livewire/Forms/LoginForm.php` | `authenticate()` — intercepts guest login, auto-logs in, redirects to correct step |
| `app/Livewire/GuestSetPassword.php` | Set-password component with full security guards; `logout()` action |
| `resources/views/livewire/guest-set-password.blade.php` | "Activate Your Account" page — shows only after email verified |
| `resources/views/livewire/pages/auth/login.blade.php` | Handles `guest_redirect` session flash from `LoginForm` |
| `resources/views/livewire/admin-users.blade.php` | Shows orange **Guest** pill in the Verified column for sentinel accounts |
| `routes/web.php` | `/dashboard` uses `withoutMiddleware('verified')` to allow `mount()` to handle guest routing |

---

### ✅ User Type Impact Analysis

#### `isGuest()` Truth Table

`isGuest()` returns `true` **only** when `users.password` is the exact string `[GUEST-USER]`.

| User Type | `password` column | `isGuest()` result | Notes |
|---|---|---|---|
| **Guest checkout user (no password given)** | `[GUEST-USER]` — plain text sentinel | ✅ `true` | Only path that produces this value |
| **Guest checkout user (password given at checkout)** | `$2y$…` bcrypt hash | ❌ `false` | Treated as a regular unverified user |
| **Registered user** | `$2y$…` bcrypt hash | ❌ `false` | Set at registration |
| **Social login user (new)** | `Hash::make(Str::random(32))` | ❌ `false` | Random hash assigned at OAuth callback |
| **Social login user (existing account linked)** | Existing hashed password | ❌ `false` | |
| **Admin / Staff** | `$2y$…` bcrypt hash | ❌ `false` | |

> [!IMPORTANT]
> Social login users always receive a **randomly generated hashed password** (`Hash::make(Str::random(32))`) the moment their account is created via `SocialAuthController::callback()`. This is a valid bcrypt string — never `[GUEST-USER]` — so social users are permanently excluded from the guest flow.

---

#### `UserDashboard::mount()` Decision Tree

```
mount() called for authenticated user
    │
    ├─ 1. isAdmin() || isOrderProcessor()              → redirect admin.dashboard
    │
    ├─ 2. isTicketManager()                            → redirect admin.tickets
    │
    ├─ 3. isGuest() && !hasVerifiedEmail()             → send verification email
    │      [password === '[GUEST-USER]', unverified]     set url.intended = set-password
    │                                                    → redirect verification.notice
    │
    ├─ 4. isGuest() && hasVerifiedEmail()              → redirect guest.set-password
    │      [password === '[GUEST-USER]', verified]       (email proved, now set password)
    │
    ├─ 5. !hasVerifiedEmail()                          → redirect verification.notice
    │      [real password, not yet verified]             same behaviour as before guest feature
    │
    └─ 6. (none of the above)                          → render dashboard ✅
           [verified registered / social]
```

---

#### Full Walk-Through Matrix

| User Type | Check 1–2 (staff) | Check 3 `isGuest()` + unverified | Check 4 `isGuest()` + verified | Check 5 `!hasVerifiedEmail()` | Final outcome |
|---|---|---|---|---|---|
| **Verified registered** | ❌ | ❌ `$2y$…` hash | ❌ | ❌ verified | ✅ Dashboard |
| **Unverified registered** | ❌ | ❌ `$2y$…` hash | ❌ | ✅ not verified | → `/email/verify` |
| **Social login (any provider)** | ❌ | ❌ random hash | ❌ | ❌ verified at login | ✅ Dashboard |
| **Guest — unverified** | ❌ | ✅ `[GUEST-USER]`, no `email_verified_at` | — | — | → `/email/verify` → `/account/set-password` |
| **Guest — email verified, no password yet** | ❌ | ❌ (verified check fails) | ✅ `[GUEST-USER]`, has `email_verified_at` | — | → `/account/set-password` |
| **Admin / Order Processor** | ✅ | — | — | — | → `admin.dashboard` |
| **Ticket Manager** | ✅ | — | — | — | → `admin.tickets` |

---

#### Admin Users List — Guest Indicator

The `/admin/users` list displays an orange **Guest** pill in the **Verified** column for any account whose `users.password` is the `[GUEST-USER]` sentinel:

| Display | Meaning |
|---|---|
| ✅ Verified (green) | Real account, email confirmed |
| ⚠ Unverified (amber) | Real account, email not yet confirmed |
| 🟠 **Guest** + Unverified | Sentinel account — checked out without a password |

The check uses `$user->password === \App\Models\User::GUEST_PASSWORD` directly in the blade, ensuring it is always in sync with the constant definition in the model.

---

#### Why the Routing Change Is Safe

Previously, the `verified` Laravel middleware sat in front of `/dashboard` and caught both unverified registered users *and* guest users, sending them all to `/email/verify`. The problem: guests needed to go to `/account/set-password` first, not the verification page.

The fix: `/dashboard` uses `->withoutMiddleware('verified')`. The dashboard-level verified check is now handled by check 5 in `mount()` using `$user->hasVerifiedEmail()` — identical in behaviour to the middleware, just executed two lines later.

**All other protected routes** (tickets, admin, downloads, etc.) are unchanged — they still enforce `verified` normally.

---

#### Social Login — Email Verification Detail

| Social auth scenario | `email_verified_at` set? | `provider` set? |
|---|---|---|
| New user created via OAuth | ✅ `now()` at creation | ✅ provider name |
| Existing account linked to OAuth | ✅ `now()` if previously null | ✅ provider name |
| Email not returned by provider (collect-email flow) | ✅ after email collected | ✅ provider name |

Social login users always pass all guest checks and land directly on the dashboard.

---

> [!TIP]
> To find all unconverted guest accounts in the database:
> ```sql
> SELECT id, name, email, created_at
> FROM users
> WHERE password = '[GUEST-USER]'
> ORDER BY created_at DESC;
> ```
> Or in PHP: `User::where('password', User::GUEST_PASSWORD)->get()`


---

## Product Detail Page — Pricing Features

### Quantity-Discount `/each` Label

When a **quantity-based discount tier** (configured in the Discount Manager) is active for the currently selected variant at the chosen quantity, a `/each` label is automatically appended to the displayed unit price in the buy-box.

**How it works**

- `ProductDetails::getHasQtyDiscountProperty()` checks whether `DiscountConfiguration::quantity_based` is enabled and whether the selected variant's `quantityDiscounts` relation contains a tier whose `qty_min`/`qty_max` range covers the current quantity.
- Since `quantity` is bound with `wire:model.live`, every qty change triggers a Livewire re-render that re-evaluates the property and shows or hides the label instantly.
- The label appears in **both** the discounted-price state (sale/volume price shown) and the regular-price state.

**Display example**

| Qty | Discount tier active? | Price shown |
|---|---|---|
| 1 | No | `$29.99` |
| 5 | Yes (5–10 @ 10% off) | `$26.99 /each ~~$29.99~~ Save $3.00!` |

**Files involved**

- `app/Livewire/ProductDetails.php` — `getHasQtyDiscountProperty()`
- `resources/views/livewire/partials/product-buy-box.blade.php` — `/each` span next to price

---

### Live Item Total (`show_item_total`)

An **optional** display area that shows the running **item total** (unit price × quantity) below the Add to Cart button, updating live as the customer changes their quantity.

**Database**

| Column | Table | Type | Default |
|---|---|---|---|
| `show_item_total` | `products` | `TINYINT` | `0` (off) |

**Admin configuration**

In the **Admin → Product Manager → Advanced Settings** panel, toggle:

> ☑ **Show Live Item Total Below Add to Cart**
>
> When enabled, a live Item Total (unit price × quantity) is displayed below the Add to Cart button on the product detail page. Updates automatically as the customer changes their quantity.

The toggle is off by default so existing products are unaffected.

**Storefront display**

When enabled, a styled bar appears below the Add to Cart button (and any cart error):

```
Item Total        3 × $29.99  =  $89.97
```

- Respects VAT-inclusive pricing if the store has merchant VAT rates configured.
- Respects any active discount tier (uses `calculatedPrice`, the same value shown in the price display).
- Only rendered when a variant is selected and qty ≥ 1.
- Hidden completely when the product is out of stock (the `Currently Unavailable` state).

**Files involved**

| File | Change |
|---|---|
| `database/migrations/2026_07_26_000004_add_show_item_total_to_products_table.php` | Migration — adds `show_item_total` tinyint column |
| `app/Models/Product.php` | `$fillable` + `$casts` |
| `app/Livewire/AdminProductEdit.php` | Public property, `loadProduct()`, `updateAdvancedSettings()` |
| `resources/views/livewire/admin-product-edit.blade.php` | Checkbox toggle in Advanced Settings |
| `resources/views/livewire/partials/product-buy-box.blade.php` | Item Total display block |


---

## 🌐 Multi-Language System

A comprehensive, database-driven multi-language system supporting dynamic content translation across all major content types, an AI-powered auto-translation pipeline, a frontend language switcher, and per-language currency overrides.

---

### Architecture Overview

The system follows a **child-table translation pattern**: each translatable model has a dedicated `*_translations` table. Translation records are fetched at runtime and applied transparently via a shared `HasTranslations` Eloquent trait, requiring zero changes to existing views.

```
Language (languages table)
  └── CmsPageTranslation (cms_page_translations)
  └── ProductTranslation (product_translations)
  └── KbArticleTranslation (kb_article_translations)
  └── TestimonialTranslation (testimonial_translations)
  └── NavItemTranslation (nav_item_translations)
  └── CmsListMenuItemTranslation (cms_list_menu_item_translations)
```

---

### Database Schema

#### `languages` table

| Column | Type | Description |
|---|---|---|
| `code` | varchar(10) | ISO language code (e.g. `en`, `fr`, `de`) |
| `name` | varchar(100) | English display name |
| `native_name` | varchar(100) | Native display name (e.g. Français) |
| `flag_emoji` | varchar(10) | Flag emoji for UI display |
| `is_default` | boolean | Exactly one language is the system default |
| `is_active` | boolean | Only active languages are served to users |
| `show_in_switcher` | boolean | Controls visibility in the frontend switcher |
| `rtl` | boolean | Right-to-left layout support |
| `currency_code` | varchar(10) | Override currency code (e.g. `EUR`) |
| `currency_symbol` | varchar(10) | Override symbol (e.g. `€`) |
| `currency_position` | enum | `before` or `after` |
| `decimal_separator` | varchar(5) | e.g. `.` or `,` |
| `thousands_separator` | varchar(5) | e.g. `,` or `.` |
| `sort_order` | integer | Display order in switcher |

#### Translation tables (shared pattern)

All 6 translation tables share the same column structure:

| Column | Type | Description |
|---|---|---|
| `language_id` | FK | References `languages.id` |
| `{model}_id` | FK | References the parent model |
| `{field}` | text/varchar | Translated content (nullable — falls back to default) |
| `translation_status` | enum | `pending`, `ai_translated`, `reviewed` |
| `translated_at` | timestamp | When last translated/updated |

Translatable fields per model:
- **CMS Pages**: `title`, `content`, `meta_title`, `meta_description`, `alternate_page_title`, `left_col`, `right_col`
- **Products**: `title`, `short_description`, `long_description`, `meta_title`, `meta_description`
- **KB Articles**: `title`, `article_content`, `meta_description`
- **Testimonials**: `author_name`, `content`, `author_title`, `company_name`
- **Nav Items**: `label`
- **List Menu Items**: `list_item`, `description`

---

### Core Services

#### `App\Services\LanguageService`

Singleton service bound in the container. Manages language state for each request.

```php
$service = app(LanguageService::class);

$service->current();          // Returns current Language model
$service->currentCode();      // Returns 'fr', 'de', etc.
$service->currentId();        // Returns language DB id
$service->isDefault();        // True when viewing default language
$service->setLanguage('fr');  // Store in session + 1-year cookie
$service->currencyOverride(); // Returns ['code'=>'EUR','symbol'=>'€',...] or null
$service->isRtl();            // True when current language is RTL
```

**Language persistence**: The current language is stored in:
1. `Session::get('language_code')` — for the current request
2. `Cookie 'app_language'` — persisted for 1 year

#### `App\Services\TranslationService`

Handles AI-powered translation via OpenAI.

```php
$service = app(TranslationService::class);

// Translate a single model record
$service->translateRecord($model, $languageId);

// Get coverage stats for a content type
$stats = $service->translationStats(Product::class, $languageId);
// Returns: ['total' => 50, 'translated' => 32]
```

**Shortcode protection**: Before sending content to OpenAI, all `[plugin:...]` shortcodes are extracted, replaced with neutral placeholders (`{{PLUGIN_0}}`), and reinserted after translation. This prevents the AI from mangling plugin shortcode syntax.

#### `App\Middleware\SetLocale`

Registered in the `web` middleware group. On each request it:
1. Reads the current language via `LanguageService::current()`
2. Calls `App::setLocale($lang->code)`
3. Sets `Carbon::setLocale($lang->code)` for date formatting

---

### `HasTranslations` Trait

Applied to: `CmsPage`, `Product`, `KbArticle`, `CmsTestimonial`, `NavItem`, `CmsListMenuItem`, `SiteLabel`, `Category`, `CmsPagesCategory`, `CmsPagesTag`, `KbCategory`

```php
// Eager-load translations in queries (used on public pages)
Product::withCurrentTranslations()->paginate(25);

// Manual field lookup with fallback chain
$product->getTranslated('title');         // Current lang → default lang → raw attribute
$product->getTranslated('title', $langId); // Specific language

// When translations relation is loaded, getAttribute() is overridden:
// $product->title automatically returns the translated value
// No view changes required!
```

**Automatic attribute override (key feature)**: When the `translations` relation is eager-loaded via `->withCurrentTranslations()`, the trait's `getAttribute()` override transparently returns translated field values. `$product->title` returns the French title when viewing in French — no blade changes needed.

---

### AI Translation Pipeline

#### `App\Jobs\TranslateContentJob`

Dispatches an async queued job to translate a single model record.

```php
// Queue a single record translation
TranslateContentJob::dispatch(Product::class, $productId, $languageId);

// Bulk translate all products to French
Product::pluck('id')->each(fn($id) =>
    TranslateContentJob::dispatch(Product::class, $id, $frenchId)
);
```

The job calls `TranslationService::translateRecord()` which:
1. Loads the model and its translatable fields
2. Extracts + saves `[plugin:...]` shortcodes
3. Sends content to OpenAI with a language-specific system prompt
4. Reinserts saved shortcodes at their original positions
5. Creates or updates the translation record with `translation_status = 'ai_translated'`

---

### Admin Interface

#### Language Manager — `/admin/languages`

Full CRUD for languages with:
- Add / Edit / Delete languages
- Toggle Active (enables/disables language for all users)
- Toggle Show in Switcher (controls frontend switcher visibility independently)
- Set Default language
- Currency Override: per-language code, symbol, position, decimal/thousands separators
- RTL flag for Arabic, Hebrew, etc.
- **Bulk Translate All** button per language — queues AI translation jobs for all 11 content types
- **Translation Coverage cards** — 11 progress bars (one per content type) showing X/Y translated

#### Translation Detail Dashboard — `/admin/languages/{id}/translations`

- **11 stat cards** (one per content type) with % progress bars: CMS Pages, Products, KB Articles, Testimonials, Nav Items, List Menus, Site Labels, Product Categories, CMS Categories, CMS Tags, KB Categories
- Tabbed view of untranslated records (up to 50 per tab)
- Per-record **Translate** button — dispatches a single job
- Per-type **Translate All** button
- Translation status badges: Pending / AI Translated / Reviewed

#### Per-Record Translation Tabs

Each content editor has an inline translations UI:

| Editor | UI Style | Access |
|---|---|---|
| CMS Page Edit | Dedicated sidebar tab "Translations" | Only on existing pages |
| Product Edit | Collapsible card at bottom | Always visible if languages exist |
| KB Article Edit | Collapsible card at bottom | Always visible if languages exist |
| Nav Menu Edit | Dedicated "Translations" tab | Language selector + per-item label editor |
| Site Labels Manager | Language selector dropdown | Switches between default/translated values |

Each language panel shows:
- **Language pills** — click to switch language; status badge (Reviewed / AI / Pending)
- **Editable fields** — plain text/textarea inputs (HTML supported for content fields)
- **AI Translate from English** — dispatches `TranslateContentJob` (async)
- **Save Translation** — immediately upserts the record, marks `reviewed`
- Last translated timestamp

---

### Frontend Language Switcher

#### `App\Livewire\LanguageSwitcher`

A Livewire component rendered automatically in the public header.

**Behavior:**
- **Auto-hides** when fewer than 2 languages have `show_in_switcher = true`
- Displays current language flag + code as a compact button
- Dropdown lists all switcher-enabled active languages
- Selected language shows a checkmark
- Clicking switches language (stores in session + cookie) and redirects to refresh

**Placement:** Injected into all 3 header layout positions automatically (`header_col1`, `header_col2`, `main_header`). Works regardless of which layout the admin configured in the header builder.

#### RTL Support

When an RTL language (Arabic, Hebrew, etc.) is active, `dir="rtl"` is automatically set on the `<html>` tag in `layouts/public.blade.php`. Remove via `$isRtl` variable in the layout.

---

### Currency Override

When a language has a currency override configured, `LanguageService::currencyOverride()` returns the override. The `CurrencyService` checks for this override on every price render, allowing per-language default currencies (e.g., EUR for French, GBP for English UK) without changing the base product prices.

---

### Adding a New Language (Step-by-Step)

1. Go to **Admin → Languages** (`/admin/languages`)
2. Click **Add Language**
3. Fill in: Code (`fr`), **Flag Country Code** (`fr`), Name (`French`), Native Name (`Français`)
4. Check **Active** and **Show in Switcher**
5. Optionally set Currency Override (e.g. EUR / €)
6. Click **Save**

The language switcher now appears in the frontend header.

**To translate content:**
- Open any product/page/article in the admin and use the **Translations** section
- OR go to **Languages → [Language] → View Details** and use **Translate All** buttons
- OR call `TranslateContentJob::dispatch(Model::class, $id, $languageId)` programmatically

---

### Dynamic Site Labels Translation

`SiteLabel` records (UI labels, button text, form field labels) are fully translatable using the same child-table pattern as content models.

**Database**: `site_label_translations` table — columns: `site_label_id`, `language_id`, `label_value`, `translation_status`, `translated_at`.

**How it works:**
- Default language reads `label_custom` (admin override) or `label_default` (code fallback) directly from `site_labels`
- Non-default languages use a single LEFT JOIN query: `site_label_translations.label_value` → fallback to default language text
- Cache is per-language, flushed automatically when translations are saved or bulk-translated

**Admin UI (`/admin/site-labels`):**
- Language selector dropdown at the top — switch between Default and any active language
- When a non-default language is selected:
  - Labels table shows translated values (or default if none yet)
  - **Save Translation** — upserts to `site_label_translations`, marks `reviewed`
  - **Clear Translation** — removes override, falls back to default language
  - "Show customised only" filter shows only labels that have translations for the selected language
- Bulk AI translation via **Translate Missing Items** on the Languages manager

```php
// Programmatically translate all labels to a language
SiteLabel::pluck('id')->each(fn($id) =>
    TranslateContentJob::dispatch(SiteLabel::class, $id, $languageId)
);
```

---

### Categories & Tags Translation

Four taxonomy models now support translations using the same child-table pattern:

| Model | Table | Translation Table | Translatable Fields |
|---|---|---|---|
| `Category` | `product_categories` | `category_translations` | `name`, `description` |
| `CmsPagesCategory` | `cms_pages_categories` | `cms_pages_category_translations` | `name` |
| `CmsPagesTag` | `cms_pages_tags` | `cms_pages_tag_translations` | `name` |
| `KbCategory` | `kb_categories` | `kb_category_translations` | `name`, `description` |

**Public query integration:**
- `CategoryMenuWidget` — `withCurrentTranslations()` on root + all child category eager-loads
- `KbLanding` — `withCurrentTranslations()` on `KbCategory` + nested article queries
- Categories render in the visitor's language automatically; slug-based routing remains in the default language

**Bulk translate:**
All four models are included in the **Translate Missing Items** / **Translate All** buttons on the Languages manager.

---

### Navigation Menu Translation Admin UI

`AdminNavMenuEdit` (`/admin/nav-menus/{id}`) now has a **Translations** tab:

1. Select a target language from the dropdown (only non-default active languages shown)
2. A table lists every nav item in the menu with its default label
3. Type a translated label in the input, click **Save** to upsert `nav_item_translations`
4. Click **Clear** to remove the translation and revert to the default label

The `NavItem` model's `HasTranslations` trait handles rendering the correct label automatically on the public navigation.

---

### Flag Icons Library

The language switcher and admin language manager use the **`flag-icons`** CSS library (v7.2.3) instead of Unicode emoji characters. Unicode flag emojis do not render as flag images on Windows (Chrome/Edge/Firefox), so the CSS-based approach is used for cross-platform consistency.

**CDN loaded in both layouts:**
```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons@7.2.3/css/flag-icons.min.css">
```

**Usage in blades:**
```html
{{-- Render a flag for language with flag_emoji = 'us' --}}
<span class="fi fi-us rounded-sm" style="width:1.25em;height:0.95em;"></span>
```

**Database field**: `languages.flag_emoji` stores a **2-letter ISO 3166-1 alpha-2 country code** (lowercase), e.g. `us`, `mx`, `fr`, `de`, `gb`. The admin form validates this and shows a live CSS flag preview as you type.

Common codes: `us` `mx` `gb` `fr` `de` `es` `pt` `it` `nl` `jp` `cn` `br` `au` `ca` `kr` `ru` `ar` `in` `sa` `tr`

---

### Language Deletion & Cascade

Deleting a language via **Admin → Languages** cascades automatically:
- All 12 translation tables have `FOREIGN KEY (language_id) REFERENCES languages(id) ON DELETE CASCADE`
- MySQL/InnoDB removes all translation rows in a single atomic operation
- The language cache is cleared immediately after
- The **default language is protected** and cannot be deleted (hard block in `AdminLanguages::deleteLanguage()`)

---

### File Reference

#### Core Language & Translation Infrastructure

| File | Purpose |
|---|---|
| `database/migrations/2026_07_27_000012_create_languages_table.php` | Languages table + English seed |
| `database/migrations/2026_07_27_000013_create_cms_page_translations_table.php` | CMS page translations |
| `database/migrations/2026_07_27_000014_create_product_translations_table.php` | Product translations |
| `database/migrations/2026_07_27_000015_create_kb_article_translations_table.php` | KB article translations |
| `database/migrations/2026_07_27_000016_create_testimonial_translations_table.php` | Testimonial translations |
| `database/migrations/2026_07_27_000017_create_nav_item_translations_table.php` | Nav item translations |
| `database/migrations/2026_07_27_000018_create_cms_list_menu_item_translations_table.php` | List menu item translations |
| `database/migrations/2026_07_28_000001_site_label_translations_refactor.php` | Drops legacy `language_id` from `site_labels`, creates `site_label_translations` |
| `database/migrations/2026_07_28_000002_create_category_translations_table.php` | Product category translations |
| `database/migrations/2026_07_28_000003_create_cms_pages_category_translations_table.php` | CMS page category translations |
| `database/migrations/2026_07_28_000004_create_cms_pages_tag_translations_table.php` | CMS page tag translations |
| `database/migrations/2026_07_28_000005_create_kb_category_translations_table.php` | KB category translations |
| `database/migrations/2026_07_28_000009_create_email_template_translations_table.php` | Email template translations |

#### Models

| File | Purpose |
|---|---|
| `app/Models/Language.php` | Language model with scopes, cache helpers, `getSwitcherLanguages()` |
| `app/Models/CmsPageTranslation.php` | CMS page translation child model |
| `app/Models/ProductTranslation.php` | Product translation child model |
| `app/Models/KbArticleTranslation.php` | KB article translation child model |
| `app/Models/CmsTestimonialTranslation.php` *(or `TestimonialTranslation`)* | Testimonial child model |
| `app/Models/NavItemTranslation.php` | Nav item translation child model |
| `app/Models/CmsListMenuItemTranslation.php` | List menu item translation child model |
| `app/Models/SiteLabelTranslation.php` | Site label translation child model |
| `app/Models/CategoryTranslation.php` | Product category translation child model |
| `app/Models/CmsPagesCategoryTranslation.php` | CMS category translation child model |
| `app/Models/CmsPagesTagTranslation.php` | CMS tag translation child model |
| `app/Models/KbCategoryTranslation.php` | KB category translation child model |
| `app/Models/EmailTemplateTranslation.php` | Email template translation child model |

#### Services, Jobs & Middleware

| File | Purpose |
|---|---|
| `app/Traits/HasTranslations.php` | Shared trait: `getAttribute()` override, `withCurrentTranslations()` scope, `getTranslated()` |
| `app/Services/LanguageService.php` | Singleton: language session/cookie management, current language, currency override |
| `app/Services/TranslationService.php` | OpenAI translation, shortcode protection, FK/field mapping for all 11 content types |
| `app/Services/SiteLabelService.php` | Site label resolution with LEFT JOIN translation lookup and per-language cache |
| `app/Jobs/TranslateContentJob.php` | Queued AI translation job (dispatched per model record per language) |
| `app/Http/Middleware/SetLocale.php` | Sets `App::setLocale()` + `Carbon::setLocale()` per request |

#### Admin Livewire Components

| File | Purpose |
|---|---|
| `app/Livewire/AdminLanguages.php` | Language CRUD, bulk translate, translation coverage stats (12 types incl. email templates) |
| `app/Livewire/AdminLanguageTranslations.php` | Per-language translation coverage dashboard |
| `app/Livewire/AdminSiteLabels.php` | Site labels manager with language selector + translation save/clear |
| `app/Livewire/AdminNavMenuEdit.php` | Nav menu editor with **Translations** tab for per-item label translation |
| `app/Livewire/AdminEmailTemplateEdit.php` | Email template editor with language pill nav + per-field translation panel |

#### Public Livewire Components & Views

| File | Purpose |
|---|---|
| `app/Livewire/LanguageSwitcher.php` | Frontend language switcher (session + cookie, redirect on switch) |
| `app/Livewire/CategoryMenuWidget.php` | Product category menu (uses `withCurrentTranslations()`) |
| `app/Livewire/KbLanding.php` | KB landing page (loads translated KB categories + articles) |
| `resources/views/livewire/language-switcher.blade.php` | Frontend switcher dropdown with `flag-icons` CSS flags |
| `resources/views/livewire/admin-languages.blade.php` | Admin language manager UI with `flag-icons` integration |
| `resources/views/livewire/admin-language-translations.blade.php` | Translation dashboard UI |
| `resources/views/layouts/public.blade.php` | Public layout (includes `flag-icons` CDN, RTL `dir` attribute) |
| `resources/views/layouts/app.blade.php` | Admin layout (includes `flag-icons` CDN) |

---

### Email Template Translations

All transactional email templates support per-language translations managed through the admin panel.

#### How It Works

Email templates are stored in the `email_templates` table. Each template can have one translation record per non-default language in the `email_template_translations` child table. When an email is dispatched, the system checks the recipient's language context and selects the appropriate translated template fields before rendering.

#### Translatable Fields

| Field | Description |
|---|---|
| `subject` | Email subject line |
| `header_html` | Custom HTML injected at the top of the email |
| `salutation` | Opening salutation (e.g. "Dear", "Hola") |
| `greeting` | Greeting line (e.g. "Thank you for your order") |
| `body` | Main email body content (HTML supported) |
| `sign_off` | Closing line (e.g. "Best regards") |
| `signature` | Signature block text |
| `disclaimer` | Legal disclaimer / fine print |
| `copyright` | Copyright line in the footer |
| `footer_html` | Custom HTML injected at the bottom of the email |

#### Admin UI — Per-Template Translation Panel

The email template edit screen (`/admin/email-templates/{id}/edit`) includes a **language pill navigation bar** at the top of the form:

- **English (Default)** pill — always present, edits the base template fields.
- **One pill per active non-default language** — clicking a language pill opens the **Translation Editing Panel** for that language, shown in an amber-highlighted panel below the pill bar.
- Each translated field shows the English default value as a `placeholder` for reference.
- **AI Translate** button — calls `TranslationService::translateRecord()` to auto-translate all 10 fields via OpenAI for the selected language. A spinner indicates progress.
- **Save Translation** button — saves translated field values to `email_template_translations` and sets `translation_status = 'reviewed'`.
- Status badges show whether a translation is `Pending`, `AI Translated`, or `Reviewed`.
- `header_html` and `footer_html` fields are inside collapsible accordion sections to keep the panel compact.

#### Database Schema

```
email_template_translations
├── id
├── email_template_id  (FK → email_templates.id, CASCADE DELETE)
├── language_id        (FK → languages.id, CASCADE DELETE)
├── subject            (text, nullable)
├── header_html        (longtext, nullable)
├── salutation         (text, nullable)
├── greeting           (text, nullable)
├── body               (longtext, nullable)
├── sign_off           (text, nullable)
├── signature          (text, nullable)
├── disclaimer         (text, nullable)
├── copyright          (text, nullable)
├── footer_html        (longtext, nullable)
├── translation_status (enum: pending, ai_translated, reviewed)
└── translated_at      (timestamp, nullable)
```

#### File Reference

| File | Purpose |
|---|---|
| `database/migrations/2026_07_28_000009_create_email_template_translations_table.php` | Creates `email_template_translations` table |
| `app/Models/EmailTemplate.php` | Email template model — uses `HasTranslations` trait |
| `app/Models/EmailTemplateTranslation.php` | Child translation model |
| `app/Livewire/AdminEmailTemplateEdit.php` | Admin editor with `setEditingLanguage()`, `saveTranslation()`, `aiTranslateEmail()` |
| `resources/views/livewire/admin-email-template-edit.blade.php` | Edit view with language pill nav + amber translation panel |

---

### Live Search Plugin Translations (`[plugin:live-search-2026]`)

All user-visible UI strings in the Live Search plugin are fully translated using the existing **Site Labels** system. No separate translation table is required — the plugin reads translated label values at render time via the `siteLabel()` helper, which is already language-aware.

#### How It Works

The `LiveSearchPlugin` renders HTML as a PHP string (no Blade view). Every hardcoded English string is replaced with a `siteLabel('live_search.key', 'English fallback')` call. The `siteLabel()` helper queries the `site_labels` / `site_label_translations` tables for the active language and returns the translated value, or falls back to the default if no translation exists.

The Live Search API endpoint (`GET /api/live-search-api?q={query}`) also returns translated `type_label` values in its JSON response — so the **Alpine.js autocomplete dropdown pill badges** (Product, Site Page, Knowledge Base, etc.) render in the correct language.

#### Translatable Strings

All strings are managed under the **"Live Search Plugin"** section in **Admin → Site Labels**:

| Label Key | Default (English) | Where Used |
|---|---|---|
| `live_search.button_label` | `Search` | Search button text |
| `live_search.placeholder` | `Search products, pages, articles...` | Input field placeholder |
| `live_search.loading_message` | `Searching catalog & articles...` | Autocomplete dropdown spinner |
| `live_search.no_results_inline` | `No active results found for` | Autocomplete empty state |
| `live_search.type_product` | `Product` | Autocomplete + results pill badge |
| `live_search.type_page` | `Site Page` | Autocomplete + results pill badge |
| `live_search.type_kb` | `Knowledge Base` | Autocomplete + results pill badge |
| `live_search.type_testimonial` | `Testimonial` | Autocomplete + results pill badge |
| `live_search.type_category` | `Category` | Autocomplete + results pill badge |
| `live_search.type_brand` | `Brand` | Autocomplete + results pill badge |
| `live_search.results_heading` | `Catalog & Content Search` | Results page `<h2>` title |
| `live_search.results_showing` | `Showing result(s) for` | Results count subtitle |
| `live_search.results_subtitle` | `Enter a keyword below to search...` | Empty search state subtitle |
| `live_search.start_title` | `Start Your Search` | Empty search state heading |
| `live_search.start_subtitle` | `Type a product title, page name...` | Empty search state body |
| `live_search.no_results_title` | `No Matching Results Found` | Zero results heading |
| `live_search.no_results_body` | `We couldn't find anything matching your search...` | Zero results body |
| `live_search.browse_catalog` | `Browse Full Catalog` | Zero results CTA button |
| `live_search.view_details` | `View Details` | Result card link text |
| `live_search.cat_snippet_prefix` | `Shop items in` | Category result snippet prefix |
| `live_search.brand_snippet_prefix` | `Explore products` | Brand result snippet prefix |

#### Configurable Settings vs. Label Overrides

The plugin's admin-configured `placeholder` and `button_label` values (stored in `plugin_settings`) act as the **English fallback** when passed to `siteLabel()`. If a translated site label exists for the active language, it takes priority.

Priority order (highest → lowest):
1. Translated site label for the active language (`site_label_translations`)
2. Plugin admin setting value (`plugin_settings`)
3. Hardcoded English default in the `siteLabel()` call

#### How to Add Translations

1. Navigate to **Admin → Site Labels** and select the target language from the language pill nav.
2. Find the **"Live Search Plugin"** section.
3. Enter translated values for each label key and save.
4. Alternatively, use **Admin → Languages → Bulk AI Translate** — `SiteLabel` is included in the bulk translation pipeline and will auto-translate all live search labels.

#### File Reference

| File | Purpose |
|---|---|
| `app/Plugins/Display/LiveSearchPlugin.php` | Main plugin class — all UI strings use `siteLabel()` |
| `app/Http/Controllers/PluginApiController.php` | Live search API — `type_label` values use `siteLabel()` |
| `database/seeders/SiteLabelSectionsSeeder.php` | Adds Section 14: "Live Search Plugin" |
| `database/seeders/SiteLabelsSeeder.php` | Seeds all 21 `live_search.*` label keys with English defaults |

---

### Queue Monitor — Admin-Triggered Background Job Processing

The **Queue Monitor** (`/admin/languages/queue-monitor`) lets administrators start, monitor, and stop the translation job queue worker entirely from the browser — no SSH, no command prompt required. It is designed to handle large translation batches (hundreds or thousands of jobs) without blocking the HTTP request or timing out.

Accessible from: **Admin → CMS → Queue Monitor**

---

#### Why It Exists

The AI bulk-translation pipeline dispatches `TranslateContentJob` instances to Laravel's queue. On a standard web server, running `php artisan queue:work` requires terminal access. The Queue Monitor replaces that need with a browser UI that:

- Spawns a **truly detached background process** that survives after the HTTP response is sent
- Streams the `queue:work` output to a log file in **real time**
- Polls the log every **3 seconds** and renders it in a terminal-style panel
- Tracks whether the worker is alive via a **PID file** and OS-level process checks
- Works on both **Windows** (local development) and **Linux** (production / staging servers)

---

#### Architecture

```
Browser (Admin UI)
    │
    │  wire:click="startWorker"
    ▼
AdminQueueMonitor (Livewire)
    │
    │  Spawns background process (OS-appropriate command)
    ▼
storage/app/queue_runner.php          ← detached background script
    │
    ├── Writes PID  → storage/app/queue_worker.pid
    │
    ├── Runs: php artisan queue:work
    │         --queue=default
    │         --stop-when-empty
    │         --max-jobs=<N>
    │         --tries=3
    │         --timeout=300
    │
    ├── Streams output line-by-line → storage/app/queue_worker.log
    │
    └── Deletes queue_worker.pid when finished  ← "done" signal
```

The **PID file** is the handshake between the UI and the background process:

| PID file state | Meaning |
|---|---|
| Does not exist | Worker is stopped (never started, or cleanly finished) |
| Exists + process alive | Worker is actively running |
| Exists + process dead | Abnormal exit — file is stale; component auto-removes it |

---

#### Cross-Platform Process Spawning

The `AdminQueueMonitor::startWorker()` method detects the OS at runtime and uses the appropriate background-execution command:

**Linux / macOS (production, staging):**
```bash
nohup php /path/to/queue_runner.php 500 default > /dev/null 2>&1 &
```
- `nohup` prevents the process from being killed when the web server closes the parent process
- `> /dev/null 2>&1` discards the outer shell output (the runner writes its own output to the log)
- `&` sends it to the background immediately
- PID is captured via `posix_kill($pid, 0)` for liveness checks

**Windows (local development):**
```cmd
start /B php C:\path\to\queue_runner.php 500 default
```
- `start /B` opens the process in background without a visible window
- The runner script writes its own PID to `queue_worker.pid` (since `start /B` does not return a PID to PHP)
- Liveness is checked via `tasklist /FI "PID eq {pid}"`
- The worker is stopped via `taskkill /F /T /PID {pid}`

**Linux process liveness check (in priority order):**
1. `posix_kill($pid, 0)` — available when the PHP `posix` extension is loaded (standard on Linux)
2. `file_exists("/proc/{$pid}")` — fallback via `/proc` filesystem
3. `exec("kill -TERM {$pid}")` — last-resort shell fallback for kill

---

#### The Runner Script (`storage/app/queue_runner.php`)

This is a standalone PHP script (not part of the Laravel bootstrap) that is responsible for:

1. Writing its own `getmypid()` value to `storage/app/queue_worker.pid`
2. Running `php artisan queue:work` via `popen()`, capturing output
3. Writing each line of output to `storage/app/queue_worker.log` with `fflush()` (real-time streaming)
4. Deleting the PID file when finished — this is the "done" signal the UI reads

The script derives all paths from its own `__DIR__` location, making it fully portable across environments with no hardcoded configuration.

```
storage/app/queue_runner.php
    $storageAppDir = __DIR__                       → storage/app/
    $projectRoot   = dirname(dirname(__DIR__))      → project root
    $artisan       = $projectRoot . '/artisan'
    $pidFile       = $storageAppDir . '/queue_worker.pid'
    $logFile       = $storageAppDir . '/queue_worker.log'
```

---

#### Admin UI Walkthrough

**Stats row** (auto-refreshes every 3 seconds while worker is running):

| Stat | Source |
|---|---|
| **Pending** | `SELECT COUNT(*) FROM jobs` — live queue depth |
| **Processed (log)** | Count of lines containing `Processed:` or `DONE` in current log |
| **Failed (log)** | Count of lines containing `Failed:` or `FAIL` in current log |
| **Failed (DB)** | `SELECT COUNT(*) FROM failed_jobs` — permanent failures |

**Worker Settings** (shown only when stopped):

| Setting | Default | Description |
|---|---|---|
| Max Jobs Per Run | `500` | `--max-jobs` passed to `queue:work`; worker exits after processing this many jobs regardless of queue depth |
| Queue Name | `default` | `--queue` name; set to a custom queue name if translation jobs are dispatched on a non-default queue |

**Terminal log panel:**

- Dark background (`#0d1117`) with monospace font — visually mirrors a real terminal
- Last **150 lines** of `queue_worker.log` rendered with colour-coded lines:
  - 🟢 **Green** — `Processed:` / `DONE` (successful jobs)
  - 🔴 **Red** — `Failed:` / `FAIL` / `[ERROR]` (failed jobs)
  - 🟡 **Amber** — `Processing:` (currently executing)
  - 🔵 **Sky** — `INFO` (artisan info messages)
  - ⬜ **Grey italic** — `===` separator lines written by the monitor itself
- **Auto-scroll toggle** — keeps the log pinned to the bottom as new lines arrive; can be toggled off if you want to scroll up and review earlier output
- **LIVE / IDLE** indicator in the titlebar — animated pulse dot while running
- **Clear Log** button — wipes `queue_worker.log` (useful before starting a fresh batch)

---

#### Typical Workflow

1. Dispatch a bulk AI translation batch from **Admin → Languages** (using the "Bulk AI Translate" button or the per-record ✦ AI buttons)
2. Navigate to **Admin → CMS → Queue Monitor**
3. Optionally adjust **Max Jobs Per Run** and **Queue Name**
4. Click **▶ Start Worker**
5. Watch jobs process in real time in the terminal panel
6. The worker automatically stops when the queue is empty (`--stop-when-empty`)
7. The status badge changes from **Worker Running** to **Worker Stopped**
8. Check **Failed (DB)** count — if > 0, review `storage/app/queue_worker.log` and use `php artisan queue:retry all` if needed

---

#### Configuration Notes

**`queue:work` flags used:**

| Flag | Value | Reason |
|---|---|---|
| `--stop-when-empty` | — | Worker exits cleanly once the queue drains (no infinite loop) |
| `--max-jobs` | user-selected | Safety cap to prevent runaway processing |
| `--tries` | `3` | Retry failed jobs up to 3 times before moving to `failed_jobs` |
| `--timeout` | `300` | 5-minute timeout per job (AI API calls can be slow for long content) |

**Log file behaviour:**

- The log is **appended** (not overwritten) each time the worker starts, so you can see history across multiple runs
- Start and stop events are written as `=== ... ===` separator lines
- Use **Clear Log** before a fresh batch if you want a clean view

**File locations:**

| File | Description |
|---|---|
| `storage/app/queue_worker.pid` | PID of the running worker process; deleted on clean exit |
| `storage/app/queue_worker.log` | Full log output from `queue:work`; appended per run |
| `storage/app/queue_runner.php` | Background runner script; self-contained, no Laravel boot |

> **Note:** `storage/app/queue_worker.pid` and `storage/app/queue_worker.log` should be added to `.gitignore` to avoid committing runtime files.

---

#### Troubleshooting

| Symptom | Likely cause | Fix |
|---|---|---|
| "Start Worker" click does nothing / status stays Stopped | `shell_exec()` or `popen()` disabled on the server | Check `disable_functions` in `php.ini`; both must be enabled |
| Worker starts but log shows no output | `queue_runner.php` cannot locate `artisan` | Verify `storage/app/queue_runner.php` exists and that `dirname(dirname(__DIR__))` resolves to the project root |
| Status shows "Running" after the worker has clearly finished | PID reuse — a different process took the same PID | Click **Stop Worker** to clear the stale PID file |
| Jobs keep failing (red lines in log) | AI API key missing or rate-limited | Check `OPENAI_API_KEY` / `GEMINI_API_KEY` in `.env`; review `storage/app/queue_worker.log` for exception messages |
| `taskkill` / `posix_kill` not stopping the worker on Linux | PHP `posix` extension not loaded | Add `extension=posix` to `php.ini`; the component falls back to `exec("kill -TERM")` automatically |
| Worker exits immediately, 0 jobs processed | `QUEUE_CONNECTION=sync` in `.env` | Change to `database`, `redis`, or another async driver — `sync` processes jobs inline and never queues them |

---

#### File Reference

| File | Purpose |
|---|---|
| `app/Livewire/AdminQueueMonitor.php` | Livewire component — start/stop/poll logic, cross-platform process management |
| `resources/views/livewire/admin-queue-monitor.blade.php` | Terminal-style UI — stats row, settings panel, live log with Alpine.js coloring |
| `storage/app/queue_runner.php` | Standalone background runner — writes PID, streams `queue:work` output to log |
| `storage/app/queue_worker.pid` | Runtime: PID of the active worker (deleted on exit) |
| `storage/app/queue_worker.log` | Runtime: appended log output from `queue:work` |
| `routes/web.php` | Route: `GET /admin/languages/queue-monitor` → `admin.languages.queue-monitor` |
| `resources/views/livewire/layout/navigation.blade.php` | Nav: "Queue Monitor" link in CMS dropdown and responsive menu |
