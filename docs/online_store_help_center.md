# 🛒 Laravel E-Commerce Platform — Master Help & Documentation System

Welcome to the official, unified documentation center for the **Laravel E-Commerce Platform** (built on Laravel 13, Livewire 3, Alpine.js, and Tailwind CSS).

This documentation system is designed with dual pathways for both **Store Developers** (setting up, configuring, and extending the codebase) and **Store Administrators** (managing daily operations, catalog items, layout options, design themes, orders, payment gateways, support tickets, digital downloads, forms, translations, and access control).

---

## 📑 Table of Contents

1. [🚀 1. Quick Start & Environment Setup](#1-quick-start--environment-setup)
   - [1.1 Developer Prerequisites & Installation](#11-developer-prerequisites--installation)
   - [1.2 Database Setup & Seeders](#12-database-setup--seeders)
   - [1.3 Admin Access & Initial Store Check](#13-admin-access--initial-store-check)
2. [🏗️ 2. Codebase Architecture & Rendering Engine](#2-codebase-architecture--rendering-engine)
   - [2.1 Technical Stack Overview](#21-technical-stack-overview)
   - [2.2 Project Directory Layout](#22-project-directory-layout)
   - [2.3 Two-Tier Blade View Model](#23-two-tier-blade-view-model)
   - [2.4 Layout Wrappers (`layouts.public` vs `layouts.app`)](#24-layout-wrappers-layoutspublic-vs-layoutsapp)
   - [2.5 CMS Page Rendering Pipeline](#25-cms-page-rendering-pipeline)
3. [🖼️ 3. Product Page Layouts & CMS Page Layout System](#3-product-page-layouts--cms-page-layout-system)
   - [3.1 The 6 Product View Layout Options & Video Embeds](#31-the-6-product-view-layout-options--video-embeds)
   - [3.2 CMS Page Layout Options (Full Width, Sidebars)](#32-cms-page-layout-options-full-width-sidebars)
   - [3.3 Header Banners, Background Images & Per-Page Background Videos](#33-header-banners-background-images--per-page-background-videos)
4. [🎨 4. Site Theme, Header/Footer & Layout Customization](#4-site-theme-headerfooter--layout-customization)
   - [4.1 Admin: Global Appearance & Background Media](#41-admin-global-appearance--background-media)
   - [4.2 Admin: Typography Scale & Color Schemes](#42-admin-typography-scale--color-schemes)
   - [4.3 Admin: Dynamic Header & 5-Column Footer Builder](#43-admin-dynamic-header--5-column-footer-builder)
   - [4.4 Developer: CSS Theme Manager & Token Compiler](#44-developer-css-theme-manager--token-compiler)
5. [📦 5. Product Catalog, Variants & Bulk Management](#5-product-catalog-variants--bulk-management)
   - [5.1 Admin: Products, Dependent Variants & Deduplicated Galleries](#51-admin-products-dependent-variants--deduplicated-galleries)
   - [5.2 Admin: Product COPY & Cloning Engine](#52-admin-product-copy--cloning-engine)
   - [5.3 Admin: Bulk CSV / Excel Spreadsheet Importer](#53-admin-bulk-csv--excel-spreadsheet-importer)
   - [5.4 Admin: Custom Donation & Bill-Pay Items](#54-admin-custom-donation--bill-pay-items)
   - [5.5 Developer: Catalog Schemas & Eloquent Models](#55-developer-catalog-schemas--eloquent-models)
6. [💰 6. Pricing, Taxes, Shipping & Promotions Engine](#6-pricing-taxes-shipping--promotions-engine)
   - [6.1 Admin: VAT-Inclusive Pricing & Cross-Border Rules](#61-admin-vat-inclusive-pricing--cross-border-rules)
   - [6.2 Admin: Quantity Discount Tiers & Live Item Total](#62-admin-quantity-discount-tiers--live-item-total)
   - [6.3 Admin: Promotions Engine & Stacking Rules](#63-admin-promotions-engine--stacking-rules)
   - [6.4 Developer: `DiscountService` & Currency Override Mechanics](#64-developer-discountservice--currency-override-mechanics)
7. [💳 7. Payment Gateways, Webhooks & Custom Payment Plugins](#7-payment-gateways-webhooks--custom-payment-plugins)
   - [7.1 Admin: Built-in Payment Processors (Stripe, Paddle, PayPal, Test Mode)](#71-admin-built-in-payment-processors-stripe-paddle-paypal-test-mode)
   - [7.2 Admin: Webhook Configuration & Production Toggles](#72-admin-webhook-configuration--production-toggles)
   - [7.3 Developer: Stripe, Paddle & PayPal Extension Architecture](#73-developer-stripe-paddle--paypal-extension-architecture)
   - [7.4 Developer: Building & Registering Custom Payment Plugins](#74-developer-building--registering-custom-payment-plugins)
8. [🎫 8. Helpdesk Support Ticket Manager & Role Permissions](#8-helpdesk-support-ticket-manager--role-permissions)
   - [8.1 Customer: Submitting Tickets & Attachments](#81-customer-submitting-tickets--attachments)
   - [8.2 Admin & Staff: Support Queue Dashboard & Operations](#82-admin--staff-support-queue-dashboard--operations)
   - [8.3 Admin: Knowledge Base (KB) Article Cross-Linking](#83-admin-knowledge-base-kb-article-cross-linking)
   - [8.4 Developer: User Role Levels & Ticketing Database Schemas](#84-developer-user-role-levels--ticketing-database-schemas)
9. [📥 9. Digital Downloads & Asset Management Systems](#9-digital-downloads--asset-management-systems)
   - [9.1 Overview: Understanding the Two Distinct Download Engines](#91-overview-understanding-the-two-distinct-download-engines)
   - [9.2 Engine 1 — Order-Based Digital Product Downloads](#92-engine-1--order-based-digital-product-downloads)
   - [9.3 Engine 2 — CMS Asset Downloads Manager (`[download:ID]`)](#93-engine-2--cms-asset-downloads-manager-downloadid)
10. [🧩 10. CMS Code Embeds, Form Builder & Navigation Systems](#10-cms-code-embeds-form-builder--navigation-systems)
    - [10.1 CMS Code Embeds Manager (`[code-embed:ID]`)](#101-cms-code-embeds-manager-code-embedid)
    - [10.2 Visual Form Builder (`[cms-form:ID]`) & Opt-ins](#102-visual-form-builder-cms-formid--opt-ins)
    - [10.3 Top Navigation Builder & Relational List Menus (`[list-menu:ID]`)](#103-top-navigation-builder--relational-list-menus-list-menuid)
11. [🔍 11. Catalog Discovery, Live Search & Events](#11-catalog-discovery-live-search--events)
    - [11.1 Admin: Advanced Shop Search Filtering Drawer](#111-admin-advanced-shop-search-filtering-drawer)
    - [11.2 Admin: Multi-Content Live Search (`[plugin:live-search-2026]`)](#112-admin-multi-content-live-search-pluginlive-search-2026)
    - [11.3 Admin: Events Calendar Integration (`[plugin:events-calendar-2026]`)](#113-admin-events-calendar-integration-pluginevents-calendar-2026)
    - [11.4 Developer: Collated Search Index Engine](#114-developer-collated-search-index-engine)
12. [🔐 12. Access Control, Content Gating & Guest Users](#12-access-control-content-gating--guest-users)
    - [12.1 Admin: Post-Order Completion Redirects & Email Action Buttons](#121-admin-post-order-completion-redirects--email-action-buttons)
    - [12.2 Admin: CMS Page Access Gating (Purchase Check vs. Access Code)](#122-admin-cms-page-access-gating-purchase-check-vs-access-code)
    - [12.3 Developer: Secure UUID Magic Links (`content_access_tokens`)](#123-developer-secure-uuid-magic-links-content_access_tokens)
    - [12.4 Developer: Guest Account Conversion Flow (`[GUEST-USER]`)](#124-developer-guest-account-conversion-flow-guest-user)
13. [🌐 13. Multi-Language System & AI Translation Pipeline](#13-multi-language-system--ai-translation-pipeline)
    - [13.1 Admin: Language Management, Flags & Switchers](#131-admin-language-management-flags--switchers)
    - [13.2 Admin: Bulk AI Content Translation & Editor Tabs](#132-admin-bulk-ai-content-translation--editor-tabs)
    - [13.3 Admin: Site Labels & Multilingual Email Templates](#133-admin-site-labels--multilingual-email-templates)
    - [13.4 Developer: Child-Table Translation Pattern & Eager Loading](#134-developer-child-table-translation-pattern--eager-loading)
    - [13.5 Developer: `TranslationService` & Shortcode Protection](#135-developer-translationservice--shortcode-protection)
14. [⚡ 14. Browser Queue Monitor & E-Commerce Analytics](#14-browser-queue-monitor--e-commerce-analytics)
    - [14.1 Admin: Queue Monitor Operations (`/admin/languages/queue-monitor`)](#141-admin-queue-monitor-operations-adminlanguagesqueue-monitor)
    - [14.2 Admin: E-Commerce Analytics & Sales Performance Reports](#142-admin-e-commerce-analytics--sales-performance-reports)
    - [14.3 Developer: Detached Process Execution & Liveness Architecture](#143-developer-detached-process-execution--liveness-architecture)
15. [🔌 15. Extensible Plugin System & Included Display Plugins](#15-extensible-plugin-system--included-display-plugins)
    - [15.1 Admin: Plugin Manager (`/admin/plugins`), Shortcodes & Settings](#151-admin-plugin-manager-adminplugins-shortcodes--settings)
    - [15.2 Detailed Guide to All Included Display Plugins](#152-detailed-guide-to-all-included-display-plugins)
    - [15.3 Developer: `DisplayPlugin` & `ShippingPlugin` Interfaces](#153-developer-displayplugin--shippingplugin-interfaces)
    - [15.4 Developer: Creating Built-in & Drop-in Plugins (`plugin.json`)](#154-developer-creating-built-in--drop-in-plugins-pluginjson)
    - [15.5 Developer: Database Schema & API Reference](#155-developer-database-schema--api-reference)

---

## 🚀 1. Quick Start & Environment Setup

### 1.1 Developer Prerequisites & Full Composer Requirements Catalog

Ensure your server meets the following core system requirements:
- **PHP**: `8.3+` with required extensions: `pdo`, `pdo_sqlite`, `pdo_mysql`, `mbstring`, `gd` or `imagick`, `curl`, `xml`, `zip`.
- **Composer**: `2.x+` PHP dependency manager.
- **Node.js & NPM**: v18+ or v20+ for Vite frontend asset compilation.
- **Database**: MySQL 8.0+, MariaDB 10.5+, or PostgreSQL 15+ (SQLite supported for development).

#### Combined & Accurate Composer Dependencies (`composer.json`)

The platform relies on the following production and development Composer packages:

##### Production Dependencies (`require`):
| Package | Version | Purpose & Subsystem |
|---|---|---|
| `php` | `^8.3` | Minimum required PHP runtime version |
| `laravel/framework` | `^13.0` | Core Laravel application web framework |
| `phpoffice/phpspreadsheet` | `^5.9` | High-performance spreadsheet reader for bulk catalog CSV, TXT, XLSX, and XLS migration engine (`AdminProductImport`) |
| `stripe/stripe-php` | `*` | Native Stripe PaymentIntents & Webhooks API SDK for built-in Stripe gateway (`processor_id = 1`) |
| `paddlehq/paddle-php-sdk` | `*` | Native Paddle Billing API v2 SDK for built-in Paddle gateway (`processor_id = 2`) |
| `laravel/socialite` | `^5.28` | Multi-provider OAuth authentication framework (Google, GitHub, Facebook login) |
| `league/flysystem-aws-s3-v3` | `^3.35` | Amazon S3 storage driver for remote CMS download files (`[download:ID]`) and product media galleries |
| `livewire/livewire` | `^3.6.4` | Full-stack reactive Livewire components powering all storefront drawers & admin managers |
| `livewire/volt` | `^1.7.0` | Single-file Livewire component functional API |
| `openai-php/client` | `^0.20.0` | OpenAI API client for AI multi-language translation pipeline (`TranslationService`) |
| `staudenmeir/laravel-adjacency-list` | `^1.26` | Recursive Eloquent tree traversal for nested category & subcategory hierarchies |
| `zbateson/mail-mime-parser` | `^4.0` | Inbound MIME email parser for processing email-based ticket replies (`TicketReplyParser`) |
| `php-http/guzzle7-adapter` | `^1.1` | Guzzle 7 HTTP adapter for payment SDKs & external API calls |
| `laravel/tinker` | `^3.0` | Artisan interactive REPL shell |

##### Development Dependencies (`require-dev`):
| Package | Version | Purpose & Subsystem |
|---|---|---|
| `phpunit/phpunit` | `^12.5.12` | Automated unit & feature testing framework |
| `fakerphp/faker` | `^1.23` | Realistic fake data generator for catalog seeders (`DemoStoreSeeder`) |
| `laravel/breeze` | `^2.4` | Lightweight authentication scaffolding |
| `laravel/pail` | `^1.2.5` | Real-time CLI tail log viewer (`php artisan pail`) |
| `laravel/pint` | `^1.27` | Code style fixer & linter (`./vendor/bin/pint`) |
| `mockery/mockery` | `^1.6` | Mocking framework for PHPUnit tests |
| `nunomaduro/collision` | `^8.6` | Beautiful CLI error reporting & stack trace renderer |

#### Installation Commands
```bash
# 1. Clone the repository
git clone <repository-url> laravel-gemini
cd laravel-gemini

# 2. Install all Composer dependencies (includes PhpSpreadsheet, Stripe, Paddle, S3, OpenAI, Livewire)
composer install

# 3. Install frontend dependencies & compile assets
npm install
npm run build

# 4. Configure environment
cp .env.example .env
php artisan key:generate
```

---

### 1.2 Master Environment Configuration (`.env`) Blueprint

Configure all system credentials inside your root `.env` file:

```ini
APP_NAME="Online Store"
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://localhost:8000

# -----------------------------------------------------------------------------
# Database Connection (SQLite for local dev, MySQL 8.0+ for production)
# -----------------------------------------------------------------------------
DB_CONNECTION=sqlite
# Or for Production MySQL / MariaDB:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel_store
# DB_USERNAME=root
# DB_PASSWORD=secret

# -----------------------------------------------------------------------------
# Social OAuth Providers (Google, Facebook, GitHub)
# -----------------------------------------------------------------------------
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret

FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret

GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret

# -----------------------------------------------------------------------------
# AWS S3 Storage & CloudFront CDN Distribution
# -----------------------------------------------------------------------------
AWS_ACCESS_KEY_ID=AKIA...
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=my-store-assets-bucket
AWS_URL=https://d111111abcdef8.cloudfront.net  # CloudFront CDN Base URL
AWS_USE_PATH_STYLE_ENDPOINT=false

# -----------------------------------------------------------------------------
# Built-in Payment Gateway Credentials
# -----------------------------------------------------------------------------
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

PADDLE_CLIENT_SIDE_TOKEN=test_...
PADDLE_API_KEY=pdk_...
PADDLE_WEBHOOK_SECRET=pwh_...

PAYPAL_CLIENT_ID=A...
PAYPAL_CLIENT_SECRET=E...
PAYPAL_MODE=sandbox

# -----------------------------------------------------------------------------
# AI Auto-Translation API
# -----------------------------------------------------------------------------
OPENAI_API_KEY=sk-...
```

---

### 1.3 Database Migration & Seeding Options

Run database migrations and choose your initial seeding strategy:

```bash
# Run core schema migrations
php artisan migrate

# Seed basic platform defaults (Languages, Site Labels, Plugins, Admin user)
php artisan db:seed

# Seed full demo store content (34 Products, 10 Categories, 5 Brands, Sample Tickets)
php artisan db:seed --class=DemoStoreSeeder
```

---

### 1.3 Admin Access & Initial Store Check

Access the store admin workspace at `/admin` (or `/login`).

> [!KEYBOARD]
> **Default Admin Credentials:**
> - **Email:** `admin@example.com`
> - **Password:** `password`

Upon first login, navigate to **Admin → Global Settings** to confirm your store name, default currency, and active modules.

---

## 🏗️ 2. Codebase Architecture & Rendering Engine

### 2.1 Technical Stack Overview

| Component | Technology | Role |
|---|---|---|
| **Core Framework** | Laravel 13 | PHP MVC Architecture, Routing, Eloquent ORM |
| **Reactive Frontend** | Livewire 3 | Server-driven reactive SPA-like UI components |
| **JS Interactions** | Alpine.js | Micro-interactions (modals, drawers, live search) |
| **Styling System** | Tailwind CSS | Sleek, customizable design tokens with dark mode |
| **Authentication** | Breeze + Socialite | Session auth + Google/Facebook/GitHub OAuth |
| **Rich Text Editor** | TinyMCE (GPL) | Integrated rich text editor for CMS & KB |
| **Hierarchical Trees** | `staudenmeir/laravel-adjacency-list` | Adjacency list recursive trees for categories & nav |

---

### 2.2 Project Directory Layout

```
app/
├── Http/
│   ├── Controllers/        # Standard HTTP controllers (PageController, PluginApiController)
│   └── Middleware/         # Custom middleware (EnsureUserIsAdmin, SetLocale)
├── Livewire/               # Livewire components (Storefront + Admin)
├── Models/                 # Eloquent entities (Product, CmsPage, User, Ticket, Language)
├── Plugins/                # Plugin contracts, manager, display & shipping implementations
├── Services/               # Core business services (TranslationService, TicketAttachmentService)
├── Traits/                 # Model traits (HasTranslations)
└── helpers.php             # Global helper functions (siteLabel(), etc.)
config/                     # Custom configurations (payment_processors, etc.)
database/
├── migrations/             # All platform database migrations
└── seeders/                # Core seeders (DevEcommerceSeeder, PluginSeeder, etc.)
payment-processors/         # Custom & built-in payment processor extensions
plugins/                    # Drop-in root external plugins directory
resources/
├── css/                    # Tailwind CSS custom style rules & variables
└── views/                  # Blade templates (Tier 1 Pages vs. Tier 2 Livewire)
routes/
├── web.php                 # Storefront & Admin HTTP routes
└── auth.php                # Authentication routes (Volt/Breeze)
```

---

### 2.3 Two-Tier Blade View Model

The application strictly separates **Full-Page Blades** from **Livewire Partial Components**.

#### Tier 1 — Full-Page Blades (`resources/views/pages/` and `user/`)
- Render complete HTML documents (`<!DOCTYPE html>`, `<head>`, header/footer).
- Returned directly from Controllers or `Route::view()`.
- **Must NOT** use Livewire `#[Layout(...)]` attributes.

#### Tier 2 — Livewire Component Views (`resources/views/livewire/`)
- Partial HTML snippets containing only content area markup.
- Injected automatically into a Layout Wrapper by Livewire.
- **Must NOT** contain `<!DOCTYPE html>`, `<head>`, or `<body>` tags.

---

### 2.4 Layout Wrappers (`layouts.public` vs `layouts.app`)

| Wrapper | Livewire Attribute | Used By | Features Included |
|---|---|---|---|
| `layouts/public.blade.php` | `#[Layout('layouts.public')]` | Storefront, Catalog, KB, Tickets, Checkout, Auth pages | Google Fonts, Flag Icons CDN, RTL `dir` attribute, Livewire scripts, dynamic theme CSS, Public Header & Footer |
| `layouts/app.blade.php` | `#[Layout('layouts.app')]` | Admin Dashboard, Products, Tickets, Settings, Plugins | Isolated Admin Chrome, CMS Sidebar, Operations Topbar, Dark mode toggle |

---

### 2.5 Dynamic CMS Site Builder Routing & Reserved `/kb/` Namespace

The platform features a **100% dynamic CMS Site Builder engine** managed under **Admin → CMS → Pages**:

#### Dynamic CMS Builder Pages & Nested Sub-Directory Slugs
- **Dynamic Homepage (`slug = 'home'`)**: The storefront root (`GET /`) dynamically resolves and renders the CMS page assigned the `home` slug.
- **Custom Sub-Directory Slugs (`/blog/article-name`, `/about/team`)**: Administrators can create nested sub-directory paths directly in the CMS Page slug input (e.g. `blog/custom-article-title`, `company/about-us`, `legal/terms-and-conditions`). The primary CMS route (`GET /{slug}`, `PageController::show`) uses wildcard regex matching (`where('slug', '.*')`) to resolve multi-segment nested paths dynamically.

#### Reserved `/kb/` Knowledge Base Route Space
- Knowledge Base articles (`KbArticle` model) are strictly isolated under the reserved `/kb/` route namespace (`GET /kb`, `GET /kb/category/{slug}`, `GET /kb/{slug}`).
- **Route Isolation**: General CMS site builder pages cannot override or collide with the `/kb/` namespace, guaranteeing clean route separation between site marketing pages and customer support knowledge base articles.

```
GET /                     → Route::view('pages.home')             → CMS Page (slug='home')
GET /{slug}               → PageController::show($slug)           → Dynamic CMS Page (Supports nested slugs: /blog/post, /about/team)
GET /kb/{slug}            → KbArticleShow (Livewire)              → Reserved Support Knowledge Base Space
GET /category/{slug}      → CmsCategoryPageController::show()     → CMS Category Page
GET /tag/{slug}           → CmsTagPageController::show()          → CMS Tag Page
GET /shop                 → ShopCatalog (Livewire)                → Product Catalog Browse
GET /items/{seo_link}     → ProductDetails (Livewire)             → Product Buy Box & Layouts
GET /tickets/create       → CreateTicket (Livewire)               → Customer Support Portal
```

---

## 🖼️ 3. Product Page Layouts & CMS Page Layout System

### 3.1 The 6 Product View Layout Options & Video Embeds

Administrators can configure the presentation of individual product detail pages via **Admin → Shop → Products → Edit → Layout & Video Embed** (`layout_type`):

```
                               ┌──────────────────────────────────────────────┐
                               │        6 PRODUCT PAGE LAYOUT OPTIONS         │
                               └──────────────────────┬───────────────────────┘
                                                      │
        ┌───────────────────┬───────────────────┼───────────────────┬───────────────────┐
        ▼                   ▼                   ▼                   ▼                   ▼
    Layout 1            Layout 2            Layout 3            Layout 4            Layout 5 & 6
  Right Images        Left Images       Right Images +       Centered Layout     Centered Video Header
   (Default)                             Video Below         Images On Top       & No-Images Layout
```

#### Detailed Breakdown of Layout Types:

1. **Layout 1 — Right Side Images (Default)**: Standard e-commerce buy-box on the left column with interactive variant selectors, pricing, and Add to Cart button; main image gallery and thumbnails positioned on the right.
2. **Layout 2 — Left Side Images**: Reversed layout putting the interactive buy-box on the right column and the primary image gallery on the left column.
3. **Layout 3 — Right Side Images + Large Video Player Below**: Right-side gallery layout paired with a dedicated, full-width video player container below the buy-box and gallery. Activated via `product_video_embed`.
4. **Layout 4 — Centered Layout With Images On Top**: Full-width hero layout placing the product image gallery on top in a wide carousel/hero format, with centered description, buy-box, and customization options below.
5. **Layout 5 — Centered Layout + Large Video Player On Top**: Hero layout featuring a large video player header at the very top of the product page, followed by centered product details, options, and buy-box.
6. **Layout 6 — No Images | Video On Page**: Minimalist, high-converting video-first layout designed for digital products, courses, or single-video items. Hides the top image gallery space and presents the video player directly alongside the buy box.

#### Video Embed Field (`product_video_embed`)
When Layout 3, 5, or 6 is selected, the **Video Embed** text area is enabled in the admin editor. Acceptable formats:
- **CMS Code Embed Shortcode**: `[code-embed:12]` (Renders a managed code embed record).
- **Raw iFrame Embed**: `<iframe src="https://www.youtube.com/embed/..." ...></iframe>`

---

### 3.2 CMS Page Layout Options (Full Width, Sidebars)

Configured under **Admin → CMS → Pages → Edit Page** (`layout` column in `cms_pages` table):

| Layout Setting | Value | Rendering Behavior |
|---|---|---|
| **Full Width** | `full` / `1` | Clean, single-column content container. Ideal for landing pages, homepages, and landing grids |
| **Left Sidebar** | `left_sidebar` / `2` | 2-column layout rendering custom sidebars, category navigation trees, or sub-menus on the left |
| **Right Sidebar** | `right_sidebar` / `3` | 2-column layout rendering custom sidebars, search bars, or recent posts on the right |

---

### 3.3 Header Banners, Background Images & Per-Page Background Videos

CMS pages support per-page header images and background media overrides defined in the **Header & Background Images** tab of the page editor:

#### 1. Header Banner Image (`header_image_url`)
Upload or link a custom header image. Rendered as a full-width hero header section at the top of the CMS page with the page title layered over it. Supports **Alternate Page Title** text overrides.

#### 2. Per-Page Background Image (`page_bg_image_url`)
Overrides the site's global background image for that specific page, rendering a fixed background image container (`background-attachment: fixed; background-size: cover`).

#### 3. Per-Page Background Video (`background_video_url` / `background_video`)
Configure a fixed looping background video overlay (`autoplay loop muted playsinline`) specifically for an individual CMS page.
- **Priority**: Per-page background video settings take **highest priority**, overriding both global site background videos and per-page background images.
- **Storage Options**: Supports local file upload (`storage/app/public/backgrounds/...`), AWS S3, Custom AWS S3 Credentials, or direct MP4/WebM CDN URL.

---

### 3.4 Admin: Slideshow Images Manager, Plugin & Full-Width Header Slideshows

Manage slideshow banner decks in **Admin → CMS → Slideshows** (`/admin/cms/slideshows`, `AdminSlideshows` & `AdminSlideshowEdit`).

#### 1. Managing Slides & Decks (`slideshows` and `slideshow_slides` tables)
- **Slide Deck Creation**: Group slide images into named decks (e.g. *"Homepage Hero Banner"*, *"Seasonal Sale Deck"*).
- **Slide Configuration**: Each slide image record supports:
  - **Background Image**: High-res image file (`image_path`).
  - **Headlines**: Primary title (`title`) and subtitle (`subtitle`).
  - **Primary CTA Button**: Button text (`button_text`) and destination link (`button_url`).
  - **Secondary CTA Button**: Secondary button text (`secondary_button_text`) and destination link (`secondary_button_url`).
  - **Text Alignment**: Position slide copy (Left, Center, Right).
  - **Sort Order**: Reorder slide sequences (`sort_order`).

#### 2. Dedicated CMS Page Full-Width Header Slideshow (`include_slideshow`)
Administrators can assign a full-width hero slideshow banner to the top of any CMS page:
- On **CMS Page Edit** (`/admin/cms-pages/{id}/edit`), select a deck from **Include Top Header Slideshow** (`include_slideshow = slideshow_id`).
- **Rendering**: When set, the system automatically renders a full-width hero slideshow slider above the page body container. Ideal for homepages (`slug = 'home'`), landing pages, or promotional campaign pages.

#### 3. Embedding Slideshows via Plugin (`[plugin:slideshow-2026]`)
Embed interactive slideshow sliders into any CMS page body content or layout block using shortcodes:
- `[plugin:slideshow-2026 id=1]` — Embed specific slideshow deck ID 1.
- `[plugin:slideshow-2026 auto_play=1 speed=5000]` — Customize slider rotation speed.

---

## 🎨 4. Site Theme, Header/Footer & Layout Customization

## 🎨 4. Site Theme, Header/Footer & Layout Customization

### 4.1 Admin: Exhaustive Guide to All Global Settings (`/admin/settings`, `AdminSettings`)

Navigate to **Admin → Global Settings** (`/admin/settings`, `AdminSettings`). All platform settings are stored key-value in `cms_settings` and cached in Redis/File cache (`Cache::remember('cms_settings_all')`).

#### 1. Site Identity
- **Store Name (`site_name`)**: Displayed in top navigation, footers, email subjects, and browser title tags.
- **Logo Storage Type (`logo_type`)**: Select logo storage driver (`local`, `s3`, `cdn`, `url`, `svg`).
- **Logo Upload & External Overrides**: Upload local logo images, set CloudFront/CDN URL (`logo_cdn_url`), paste inline SVG code (`logo_svg_html`), or configure custom AWS S3 bucket credentials.

#### 2. Appearance & Dark Mode Tokens
- **Frontend Dark Mode (`frontend_dark_mode`)**: Enable dark mode styling for storefront visitors.
- **Admin Dark Mode (`admin_dark_mode`)**: Enable dark slate theme for the admin control panel.
- **Brand Color Palette**:
  - Primary Brand Color (`theme_primary_color`, e.g. `#4f46e5`).
  - Brand Hover Color (`theme_hover_color`, e.g. `#4338ca`).
  - Primary Text Color (`theme_text_color`, e.g. `#ffffff`).
  - Button Corner Radius (`theme_border_radius`: `0.75rem`, `0.5rem`, `9999px`).
  - Secondary Button Colors (`theme_secondary_bg_color`, `theme_secondary_text_color`, `theme_secondary_border_color`, `theme_secondary_hover_bg_color`, `theme_secondary_hover_text_color`).
- **Back-to-Top Floating Button**: Custom background color (`backtop_bg_color`), hover color (`backtop_hover_bg_color`), and icon color (`backtop_icon_color`).

#### 3. Shop Display & Filter Pill Styling Tokens
- **Shop Catalog View Modes**: Customize active/inactive background and text colors for Grid vs. List view mode buttons (`shop_view_mode_active_bg`, `shop_view_mode_active_text`, `shop_view_mode_inactive_bg`, `shop_view_mode_inactive_text`).
- **Category Filter Pills**: Custom background (`shop_category_pill_bg`), text (`shop_category_pill_text`), border (`shop_category_pill_border`), and hover colors.
- **Brand Filter Pills**: Custom background (`shop_brand_pill_bg`), text (`shop_brand_pill_text`), border (`shop_brand_pill_border`), and hover colors.
- **Subcategory Filter Pills**: Custom background (`shop_subcat_pill_bg`), text (`shop_subcat_pill_text`), border (`shop_subcat_pill_border`), and hover colors.
- **Sitewide Pagination Buttons**: Active (`pagination_active_bg`, `pagination_active_text`), inactive (`pagination_inactive_bg`, `pagination_inactive_text`), and hover (`pagination_hover_bg`) button colors.

#### 4. Page Background Media Overrides
- **Background Mode (`page_bg_mode`)**: Choose between `default` (Tailwind skin), `color` (solid hex background), `image` (wallpaper image), or `video` (looping video).
- **Solid Hex Background (`page_bg_color`)**: Applied when mode is `color`.
- **Background Image Storage (`page_bg_image_type`)**: Upload local image, enter external URL, or provide S3 bucket credentials (`page_bg_image_s3_bucket`, `page_bg_image_s3_key`, `page_bg_image_s3_secret`, `page_bg_image_s3_region`).
- **Background Video Storage (`page_bg_video_type`)**: Upload local MP4/WebM video file, enter external video CDN URL (`page_bg_video_url`), or configure S3 bucket video credentials.
- **Glassmorphism Dark Overlay**: Set background overlay tint color (`page_bg_overlay_color`, e.g. `#000000`) and opacity level (`page_bg_overlay_opacity`, `0` to `100%`) to ensure text readability over background media.

#### 5. Typography Scale & Google Fonts Loader
- **Google Fonts Loader (`google_fonts_url`)**: Paste any Google Fonts CSS import URL (e.g. `https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap`).
- **Element Typography Tokens**:
  - **Body Text**: Family (`theme_body_font_family`), size (`theme_body_font_size`), color (`theme_body_font_color`).
  - **Paragraphs (`<p>`)**: Family (`theme_paragraph_font_family`), size (`theme_paragraph_font_size`), color (`theme_paragraph_font_color`).
  - **Headings (`<h1>`, `<h2>`, `<h3>`)**: Independent font family, font size, and text color controls for H1, H2, and H3 elements.

#### 6. Content Cards & Panel Containers
- **Content Background Color (`theme_content_bg_color`)**: Background color for main content containers.
- **Card Background Color (`theme_card_bg_color`)**: Background color for white glassmorphism cards.
- **Card Border Color (`theme_card_border_color`)**: Border color for UI card containers.
- **Card Shadow Token (`theme_card_shadow`)**: Box shadow class (`shadow-sm`, `shadow-md`, `shadow-xl`).

#### 7. Tracking & Custom JavaScript Loaders
- **Google Analytics ID (`google_analytics_id`)**: Paste GA4 measurement ID (`G-XXXXXXXXXX`) for automatic page view tracking.
- **Custom JavaScript Loader (`custom_js_loader`)**: Paste custom tracking scripts, chat widgets, or pixel code inserted before `</head>`.

#### 8. System Timezone
- **Global Timezone (`timezone`)**: Select store timezone (e.g. `America/New_York`, `Europe/London`, `Asia/Tokyo`). Updating setting immediately changes runtime configuration (`config(['app.timezone' => $timezone])`).

#### 9. Reviews & CMS Downloads Settings
- **Product Reviews Toggle (`enable_reviews`)**: Enable/disable customer reviews sitewide.
- **Third-Party Review Scripts (`third_party_reviews_js`)**: Paste third-party review widget code (Yotpo, Trustpilot).
- **CMS File Icon Pack (`file_icon_pack`)**: Select download file format icon design (`vivid`, `classic`, `square`).

#### 10. Shop Catalog Display Controls
- **Product Image Orientation (`product_image_orientation`)**: Select catalog thumbnail aspect ratio (`16:9` widescreen vs. `1:1` square).
- **Disable Shop Landing (`disable_shop_landing`)**: Toggle whether visiting `/shop` displays the full catalog grid or redirects to homepage.
- **Enable Advanced Shop Search Filtering Drawer (`enable_advanced_shop_search`)**: Toggle slide-out search drawer on `/shop`.

---

### 4.2 Admin: Dynamic Header & 5-Column Responsive Footer Builder

Navigate to **Admin → Header & Footer Builder** (`/admin/header-footer-builder`, `AdminHeaderFooterBuilder`).

#### 1. Top Navigation & Header Controls
- **Sticky Header Toggle (`top_nav_sticky`)**: Toggle sticky top navigation header bar (`position: sticky; top: 0; z-index: 50`).
- **Header Layout & Logo Alignment**: Position logo (Left, Center, Right), search icon drawer trigger, language flag switcher (`LanguageSwitcher`), and slide-cart trigger (`SlideCart`).

#### 2. 5-Column Responsive Starter Footer
The footer layout is divided into 5 dynamic widget columns:
- **Column 1 — Primary Navigation (`footer_col1`)**: Main navigation menu links, sitemap links, and store branding.
- **Column 2 — Customer Service (`footer_col2`)**: Helpdesk ticket links (`/contact`), Order tracking (`/account/orders`), shipping policies, and FAQ pages.
- **Column 3 — Company & Legal (`footer_col3`)**: Terms of Service, Privacy Policy, Return Policy, and About Us pages.
- **Column 4 — Contact & Business Hours (`footer_col4`)**: Store address, phone number, support email, and store operating hours.
- **Column 5 — Social Proof & Newsletter (`footer_col5`)**: Social media icon row (`[plugin:social-icons-2026]`) and interactive newsletter subscription opt-in box (`OptinService`).

#### 3. Bottom Copyright & Payment Method Row
- **Dynamic Year Replacement**: Type `{{year}}` in copyright text to auto-render current calendar year (e.g. `© 2026 My Store. All rights reserved.`).
- **Payment Method Badges**: Toggle accepted payment processor icon badges (Visa, Mastercard, Amex, PayPal, Stripe, Apple Pay, Google Pay) in footer.

---

---

### 4.4 Developer: CSS Theme Manager & Token Compiler

The `App\Services\HeaderFooterCssManager` service compiles root CSS variables stored in `cms_settings` into responsive CSS rules.

- Applied to public layout via `<x-header-footer-styles />` and `<x-site-theme-styles />`.
- When settings are modified in admin, `CmsSetting::setMany()` executes `HeaderFooterCssManager::clearCompiledCssCache()` and flushes platform caches.

---

## 📦 5. Product Catalog, Variants & Bulk Management

### 5.1 Admin: Products, Dependent Variants & Deduplicated Galleries

Navigate to **Admin → Shop → Products** (`/admin/ecommerce/products`).

- **Dependent Variants**: Define variant options (Size, Color, Material) with independent pricing, SKUs, and stock levels.
- **Variant Color Deduplication**: The storefront gallery automatically deduplicates image thumbnails based on the selected variant's color tag, smoothly filtering displayed photos using Alpine.js.

---

### 5.2 Admin: Product COPY & Cloning Engine

Click the **Copy** action button on any product row in the admin product list.

```
Original Product ──► Slide-over Copy Modal ──► DB::transaction() ──► New Duplicated Product
```

#### What gets duplicated atomically:
1. Short/Long descriptions, Meta tags, Shipping rules, and Layout types.
2. Category assignments (`product_categories_assignments`).
3. Custom option fields (`ProductField` & `ProductFieldOption`).
4. Cross-sell recommendations (`ProductCrossSell`).
5. All Variants with newly generated unique SKUs (e.g. `SKU-COPY-A7B9`).
6. Inventory stock levels (`ProductInventory`).
7. Image galleries (`ProductImage`).
8. Quantity discount tiers and Event calendar details.

---

### 5.3 Admin: Bulk CSV / Excel Spreadsheet Importer

Navigate to **Admin → Shop → Bulk Product Import** (`/admin/ecommerce/import`).

- **Supported Formats**: `.csv`, `.txt`, `.xlsx`, `.xls` via `phpoffice/phpspreadsheet`.
- **Auto-Header Mapping**: Map spreadsheet columns to system fields (`title`, `public_price`, `categories`, `variant_sku`, etc.).
- **Taxonomy Auto-Creation**: Category string syntax like `Apparel > Outerwear > Jackets` automatically parses and creates missing category/subcategory records.
- **Image Downloading**: Choose between linking direct external CDN URLs (`image_url_source = 1`) or downloading remote images to local storage (`image_url_source = 0`).

---

### 5.4 Admin: Custom Donation & Bill-Pay Items

Mark any product as a Donation / Bill-Pay item (`is_donation_or_bill_pay = true`):
- **Custom Customer Amount**: Set `allow_custom_amount = true` to render a currency input field on the storefront with optional min/max validation rules.
- **Preset Selection Menu**: Set `allow_custom_amount = false` and enter comma-delimited numbers (e.g. `10, 25, 50, 100`) to render interactive amount selection buttons.
- Disables direct "Buy Now" catalog actions, forcing customers to select their amount on the details view.

---

### 5.5 Pre-Launch Demo Store Data Cleanup Engine (`AdminSettings::purgeDemoContent`)

When preparing your online store for official production launch, administrators can purge all demo catalog items, sample products, demo categories, demo brands, and demo support tickets with a single click.

#### How Demo Content Detection Works (`$hasDemoContent`)
Seeded demo items are flagged with `is_demo = 1` in the database.
- When demo records exist, a prominent **"Purge Demo Store Content"** alert banner automatically displays at the top of **Admin → Settings** (`/admin/settings`).

#### 11-Step Cascading Deletion Sequence
Clicking **Purge Demo Content** launches an atomic deletion script (`purgeDemoContent()`) that removes all demo records in strict foreign-key dependency order:

```
1. Delete demo cross-sell links (product_cross_selling where is_demo = 1)
2. Delete demo product images (product_images where is_demo = 1)
3. Delete demo event details (product_variant_events for demo variants)
4. Delete demo warehouse stock (products_inventory for demo variants)
5. Delete demo option choices (product_field_options for demo fields)
6. Delete demo product fields (product_fields for demo products)
7. Delete demo category links (product_categories_assignments for demo products)
8. Delete demo variants (product_variants where is_demo = 1)
9. Delete demo products (products where is_demo = 1)
10. Delete demo brands (product_brands where is_demo = 1)
11. Delete demo categories (product_categories where is_demo = 1, children first)
```

> [!CAUTION]
> **Pre-Launch Purge Action**: Purging demo store content is permanent and cannot be undone. It wipes all sample products, variants, brands, and categories while preserving custom products created by administrators.

---

### 5.6 Developer: Catalog Schemas & Eloquent Models

Core catalog Eloquent entities:
- `Product`: Base product model (`app/Models/Product.php`).
- `ProductVariant`: Pricing, SKU, and variant attribute JSON storage (`app/Models/ProductVariant.php`).
- `ProductInventory`: Warehouse stock quantities (`app/Models/ProductInventory.php`).
- `ProductImage`: Multi-image set records (`app/Models/ProductImage.php`).

---

### 5.7 Admin & Developer: Product Review System (Global Toggle vs. Per-Item Management)

The platform includes an integrated customer product rating and review system.

#### 1. Sitewide Global Toggle (`enable_reviews`)
Manage global review settings in **Admin → Global Settings → Reviews Settings** (`/admin/settings`, `AdminSettings`):
- **Enable Product Reviews (`enable_reviews = true/false`)**: Toggling OFF disables customer review tabs, star rating badges, and review submission forms across the entire store.
- **Third-Party Review Integration (`third_party_reviews_js`)**: Paste third-party JavaScript snippets (e.g. Yotpo, Trustpilot, Stamped.io) to override native reviews with an external service.

#### 2. Per-Item Review Management (`allow_reviews`)
Configure reviews on individual products under **Admin → Products → Edit Product**:
- Toggle **Allow Customer Reviews** (`allow_reviews = true/false`) to disable review submissions for specific items (e.g. custom services or gift cards) while keeping reviews enabled sitewide.

#### 3. Admin Review Approval Queue (`/admin/ecommerce/reviews`)
Navigate to **Admin → Shop → Reviews** (`AdminProductReviews`):
- **Moderation Queue**: Customer reviews are submitted in `pending` status (`approved = 0`) to prevent spam.
- **Approval & Rating Recalculation**: Clicking **Approve** (`approved = 1`) automatically recalculates the product's average rating (`reviews_rating` 1.0–5.0 stars) and total review count, instantly updating rating stars on catalog cards (`ShopCatalog`) and product buy boxes (`ProductDetails`).

---

### 5.8 Admin & Developer: Inventory Levels, Multi-Warehouse System & Bulk Inventory Importer

Navigate to **Admin → Shop → Inventory** (`/admin/ecommerce/inventory`, `AdminInventory`).

#### 1. Multi-Tier Stock Levels (`ProductInventory` model)
Stock counts are tracked per variant across 4 distinct quantity indicators:
- **Available Stock (`quantity_available`)**: Active retail stock available for instant customer checkout.
- **Warehouse Stock Level (`warehouse_stock_level`)**: Deep back-stock inventory stored in fulfillment centers.
- **Use Warehouse Stock Toggle (`use_warehouse_stock = true/false`)**: Specifies whether the storefront automatically draws from warehouse back-stock when primary available stock drops to 0.
- **Reserved Stock (`reserved_stock`)**: Stock held by active pending customer orders awaiting payment or shipment confirmation.

#### 2. Multi-Warehouse Fulfillment System (`shipping_warehouse_locations` table)
Manage physical warehouse locations in **Admin → Shipping & Taxes → Warehouses** (`/admin/shipping`, `AdminShippingSettings`):
- **Warehouse Location Records**: Store Warehouse Name (`name`), Code (`code`), Address, City, State, Country, Zipcode, and ShipStation Location ID (`shipstation_id`).
- **Location Mapping**: Link product variant inventory rows (`location_id`) to specific physical warehouses for localized shipping origin calculations and 3PL fulfillment integrations.

#### 3. Bulk CSV / Excel Inventory Level Importer (`AdminInventory::uploadCsv`)
Bulk update warehouse stock quantities and locations across thousands of SKUs:
- **Import Interface**: Click **Upload CSV** in the Inventory Manager (`/admin/ecommerce/inventory`).
- **File Format**: Supports `.csv`, `.txt` (comma or pipe `|` delimited).
- **Column Mapping**: `[SKU, Quantity Available, Warehouse Stock Level, Location ID]`.
- **Atomic SKU Matching**: Matches rows by `variant_sku` and updates or creates corresponding `ProductInventory` records instantly.

---

### 5.9 Admin & Staff: Online Orders Manager & Order Details Operations

Manage customer orders in **Admin → Shop → Orders** (`/admin/ecommerce/orders`, `AdminOrders` & `AdminOrderDetails`).

#### 1. Orders Queue Dashboard (`AdminOrders`)
Filter orders by status: All, Pending (`0`), Processing (`1`), Shipped (`2`), Refunded (`3`), Cancelled (`4`), Completed (`5`). Features live search by customer name, order ID, email, or tracking number.

#### 2. Order Details Operations (`/admin/ecommerce/orders/{id}`, `AdminOrderDetails`)
Clicking any order opens a full operations workspace:

##### 📧 Resend Duplicate Order Emails
- **Send Duplicate Order Confirmation**: Re-triggers the `order_confirmation` email template to the customer's email address with itemized receipt table and payment breakdown.
- **Send Shipment Confirmation**: Re-triggers the `order_shipment` email template containing dispatch date and carrier tracking number.
- **Send Download Link Reminder**: Re-triggers the `download_reminder` email template with digital asset download links and magic UUID access tokens (`ContentAccessToken`).

##### 🚚 Shipment & Carrier Tracking Log
- **Mark as Shipped**: Enter shipping date (`shipDate`) and carrier tracking number (`trackingNumber`). Automatically updates status to `Shipped (2)` and dispatches shipment email.

##### 💳 Payments Ledger & Manual Payment Entry
- **View Payment History**: Inspect all entries in `order_payments` table (payment gateway, transaction ID, date, status, authorization code).
- **Add Manual Payment**: Log manual payments (Check, Wire Transfer, Cash, POS Terminal) with payment date, amount, authorization code, and notes.

##### 💸 Partial / Full Refund Engine
- Process partial or full dollar refunds (`processRefund()`).
- Restocks variant inventory levels (`quantity_available`) automatically upon refund or order deletion.

---

## 💰 6. Pricing, Taxes, Shipping & Promotions Engine

### 6.1 Admin & Developer: Tax System Breakdown (US Sales Tax vs Canadian GST/PST/HST vs International VAT)

Configure global shipping, merchant location, and tax settings in **Admin → Shipping & Taxes** (`/admin/shipping`, `AdminShippingSettings`).

The platform supports 3 distinct regional tax models:

#### 1. US Sales Tax Model (`merchant_country_code = 'US'`)
- **Exclusive Tax Model**: Catalog item prices do NOT include tax. Tax is calculated dynamically on top of the order subtotal at checkout based on the customer's delivery location.
- **Per-State Sales Tax Rates (`shipping_states` table)**: Administrators set individual state sales tax percentages (`sales_tax_rate`, e.g. California 7.25%, New York 8.00%, Texas 6.25%).
- **Tax Calculation**: At checkout, `TaxService` resolves the destination state code and applies `subtotal × sales_tax_rate`.

#### 2. Canadian Tax System (`merchant_country_code = 'CA'`)
- **Exclusive Tax Model**: Taxes are calculated on top of item prices at checkout.
- **Federal & Provincial Rate Structure (`shipping_states` table with `country_code = 'CA'`)**:
  - **GST (Goods & Services Tax)**: Federal 5% tax applied across Canada.
  - **PST (Provincial Sales Tax)** / **RST**: Charged in non-participating provinces (e.g. BC 7% PST, Saskatchewan 6% PST, Manitoba 7% RST).
  - **HST (Harmonized Sales Tax)**: Single combined rate in participating provinces (e.g. Ontario 13% HST, Nova Scotia 15% HST, New Brunswick 15% HST, PEI 15% HST).

#### 3. International VAT Model (`vat_inclusive_pricing = true`)
- **Inclusive Tax Model**: Automatically activated for merchants outside US/CA (e.g. UK, EU, Australia). Store prices displayed to customers include domestic VAT (e.g. £120.00 listed item price includes £100 net + £20 VAT at 20%).
- **Cross-Border Tax Stripping Engine**: When an international customer (outside the merchant's VAT zone) enters a foreign delivery address during checkout, `TaxService` / `DiscountService` automatically calculates and strips out domestic VAT from line item totals, offering tax-free export pricing (e.g. price drops to £100.00 at checkout).

---

### 6.2 Admin: Quantity Discount Tiers & Live Item Total

#### Quantity Discount Tier `/each` Label
When a quantity discount tier is active for a variant (e.g. 5–10 items @ 10% off), a `/each` badge automatically appends to the unit price in the buy box as the customer adjusts the quantity selector.

#### Live Item Total (`show_item_total`)
Toggle **Show Live Item Total Below Add to Cart** in Product Advanced Settings. When active, displays running subtotal (`Quantity × Calculated Price = Total`) updating live.

---

### 6.3 Admin: Detailed Guide to All 7 Discount Types & Storefront Execution

Navigate to **Admin → Shop → Promotions & Discounts** (`/admin/ecommerce/discounts`, `AdminDiscounts`, `AdminDiscountEdit`).

The platform features a 7-type rules engine supporting percentage-off, fixed-amount-off, category/brand collections, customer tier targeting, and BOGO deals:

| Type ID | Discount Type Name | Slug / Key | Scope & Behavior | Key Parameters |
|---|---|---|---|---|
| **1** | **Coupon Code** | `code` | Order-level discount triggered by entering a promo code string in the cart/checkout. Supports standard promo codes (`code_type = 0`) or gift certificates (`code_type = 1`). | `code`, `code_type`, `order_minimum`, `order_maximum`, `order_qty_min`, `order_qty_max`, `order_weight_min`, `order_weight_max` |
| **2** | **Preferred Customer** | `preferred` | Targeted discount assigned directly to specific user accounts (`users.preferred_discount_id`). Automatically applies during checkout whenever an eligible preferred customer logs in. | `preferred_discount_id`, `value_type`, `value` |
| **3** | **General Order Value** | `order_value` | Order-level discount applied automatically when the cart subtotal falls within the configured minimum (`order_minimum`) and maximum (`order_maximum`) thresholds. | `order_minimum`, `order_maximum`, `value_type`, `value` |
| **4** | **New Customer / First Order** | `new_customer` | Order-level discount applied automatically on a customer's first purchase (`!Order::where('order_user_id', $user->id)->exists()`). | `value_type`, `value`, `order_minimum` |
| **5** | **Category & Brand** | `category_brand` | Item-level discount targeting products in specific categories (`category_id`) or brands (`brand_id`). | `category_id`, `brand_id`, `cat_qty_min`, `cat_qty_max`, `cat_subtotal_min`, `brand_qty_min`, `brand_subtotal_min` |
| **6** | **Item-Specific** | `item_specific` | Item-level discount targeting an individual product ID (`product_id`). | `product_id`, `item_qty_min`, `item_qty_max`, `item_subtotal_min`, `item_subtotal_max` |
| **7** | **BOGO (Buy X Get Y)** | `bogo` | Buy X items of trigger product (`buy_x_get_y`) and get Y items of target product (`product_id_y`) at a discounted or 100% free rate (`product_y_percent`). | `buy_x_get_y`, `product_id_y`, `free_range1` (qty X), `free_range2` (qty Y), `product_y_percent`, `bogo_cart_text` |

#### Value Types (`value_type`):
- `1` = Specific Value Off ($ amount subtracted).
- `2` = Percent Off (% calculated and subtracted).

#### Additional Rule Conditions:
- **Free Shipping (`free_shipping = 1`)**: Grants 100% free shipping when applied.
- **Wholesale Only (`wholesale_only = 1`)**: Scoped exclusively to wholesale user accounts (`role_id` wholesale).
- **Date Window**: `start_date` and `expiration_date`.
- **Promotional Badge Banner (`show_get_x_free` + `show_get_x_text`)**: Automatically renders custom promo callouts (e.g. *"Buy 2 Get 1 Free!"*) on matching product detail pages.

---

### 6.4 Admin & Developer: Included Shipping Methods, Dropdown Overrides & Real-time Rate Providers

Manage shipping rules, carriers, and rates in **Admin → Shipping & Taxes** (`/admin/shipping`, `AdminShippingSettings`).

#### 1. Flat-Rate Shipping Options (`shipping_flat_rates` table)
- **Domestic & International Flat Rates**: Configure fixed-rate delivery methods for domestic (`custom_ship_options_us`) and international (`custom_ship_options_int`) shipping zones.
- **Rules & Conditions**: Define rates based on order subtotal ranges (`flatRateMinSubtotal`, `flatRateMaxSubtotal`), total shipment weight, or flat per-order fees (e.g. Standard Ground $5.99, Priority Air $14.99).

#### 2. Menu Drop-Down Shipping Override
- **Admin Configuration**: Select the shipping display method presented to customers during checkout:
  - **Flat-Rate Override Dropdown**: Force standard flat rates as a clean select dropdown.
  - **Live Carrier Rate Dropdown**: Display live carrier rates (FedEx, UPS, USPS) dynamically formatted in a drop-down menu.
  - **Custom Shipping Method Dropdown**: Present custom-configured shipping choices with delivery timeframe labels (e.g. *"Express (1-2 Days) — $15.00"*).

#### 3. Real-time Rate Providers & Included Mock Real-time Provider
- **Real-Time Carrier API Drivers**: Toggles for **FedEx** (`realtime_fedex`), **UPS** (`realtime_ups`), **USPS** (`realtime_usps`), and **Local Store Pickup** (`realtime_pickup`).
- **Included Mock Real-time Rate Provider (`MockShippingRateProvider` / `MockShippingService`)**:
  - The platform includes a built-in mock real-time shipping rate provider.
  - **Development & Testing Utility**: Simulates live API carrier responses (e.g. *"FedEx Ground (3-5 Days) — $9.99"*, *"UPS 2nd Day Air — $18.50"*, *"USPS Priority Express — $32.00"*) based on package weight and origin/destination zip codes without requiring live carrier API credentials.

---

### 6.5 Storefront Execution & Two-Phase Calculation Pipeline


`App\Services\DiscountService` processes all shopping cart items using a deterministic 3-phase pipeline:

```
[ Shopping Cart Items ]
          │
          ▼
┌─────────────────────────────────────────────────────────┐
│ Phase 1: Item-Level Evaluation Sequence (Per Line Item) │
│ 1. Category & Brand Discount (Type 5)                  │
│ 2. Item-Specific Discount (Type 6)                     │
│ 3. On-Sale Special Price (variant->sale_price)          │
│ 4. Quantity Break Tier (variant->quantityDiscounts)     │
│ 5. Wholesale Price (variant->wholesale_price)           │
│ ──► Last applicable rule in sequence sets item_price    │
└─────────────────────────┬───────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ Phase 2: BOGO Evaluation                                │
│ • Validates trigger product X is in cart (>= free_range1)│
│ • Applies product_y_percent discount to target Y        │
│ • Locks BOGO target item qty edits in slide cart        │
└─────────────────────────┬───────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ Phase 3: Order-Level Discounts Sequence               │
│ • Priority: Coupon (1) ──► General (3) ──► Preferred (2)│
│            ──► New Customer (4)                         │
│ • Validates min/max order spend, qty, weight, & role    │
│ • Honors `allow_multiple_order_discounts` stacking config│
└─────────────────────────────────────────────────────────┘
```

---

### 6.5 Developer: `DiscountService` & Currency Override Mechanics

- `App\Services\DiscountService::applyDiscountsToCart($items, $user)`: Recalculates item prices, applies BOGO discounts, evaluates order-level coupons/tiers, and returns `[items, subtotal, discounts, total_discount, adjusted_subtotal]`.
- `App\Services\DiscountService::getDiscountedPriceForVariant($variant, $user, $qty)`: Single-variant helper for catalog & buy-box pricing displays.
- `App\Services\CurrencyService`: Checks active language currency overrides (`LanguageService::currencyOverride()`) to format symbol, placement, and decimal separators dynamically.

---

## 💳 7. Payment Gateways, Webhooks & Custom Payment Plugins

### 7.1 Admin: Built-in Payment Processors (Stripe, Paddle, PayPal, Test Mode)

The platform includes 4 default payment processors out-of-the-box:

| Processor ID | Name | Driver Location | SDK / Package Requirement |
|---|---|---|---|
| **0** | **Test Gateway** | `TestProcessor.php` | Built-in (no external package or credentials needed) |
| **1** | **Stripe** | `StripeProcessor.php` | `composer require stripe/stripe-php` + `.env` keys |
| **2** | **Paddle Billing** | `PaddleProcessor.php` | `composer require paddlehq/paddle-php-sdk` + `.env` keys |
| **3** | **PayPal** | `PayPalProcessor.php` | Built-in HTTP client (`Illuminate\Support\Facades\Http`) + `.env` keys |

#### Configuring Gateways in Admin
1. Navigate to **Admin → Checkout → Processors** (`/admin/ecommerce/checkout-processors`).
2. Select the desired processor as the **Primary Payment Gateway**.
3. Toggle between **Production** (`production = 1`) and **Sandbox / Test Mode** (`production = 0`).

---

### 7.2 Admin: Webhook Configuration & Production Toggles

To enable automatic order updates, subscription billing callbacks, and refund processing, configure your environment variables and register webhook endpoints in gateway developer dashboards:

#### 1. Stripe Setup (`.env`)
```env
# Production Keys
STRIPE_PUBLISHABLE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx

# Sandbox / Test Keys
STRIPE_SANDBOX_PUBLISHABLE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxx
STRIPE_SANDBOX_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxx
```
- **Stripe Dashboard Webhook Endpoint**: `https://yourdomain.com/webhooks/stripe`
- **Events**: `payment_intent.succeeded`, `payment_intent.payment_failed`, `charge.refunded`, `customer.subscription.created`, `customer.subscription.updated`, `customer.subscription.deleted`, `invoice.payment_succeeded`.

#### 2. Paddle Billing Setup (`.env`)
```env
# Production (vendors.paddle.com)
PADDLE_API_KEY=your_live_api_key
PADDLE_CLIENT_TOKEN=your_live_client_token
PADDLE_WEBHOOK_SECRET=pdl_ntf_xxxxxxxxxxxxxxxx

# Sandbox (sandbox-vendors.paddle.com)
PADDLE_SANDBOX_API_KEY=your_sandbox_api_key
PADDLE_SANDBOX_CLIENT_TOKEN=your_sandbox_client_token
```
- **Paddle Dashboard Webhook Endpoint**: `https://yourdomain.com/webhooks/paddle`
- **Events**: `transaction.completed`, `transaction.payment_failed`, `customer.created`, `subscription.created`, `subscription.updated`, `subscription.canceled`.

#### 3. PayPal Setup (`.env`)
```env
# Production Credentials
PAYPAL_CLIENT_ID=your_live_client_id
PAYPAL_CLIENT_SECRET=your_live_client_secret

# Sandbox / Test Credentials
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_client_secret
```
- **PayPal Webhook Endpoint**: `https://yourdomain.com/webhooks/paypal`
- **Events**: `CHECKOUT.ORDER.APPROVED`, `PAYMENT.CAPTURE.COMPLETED`, `PAYMENT.CAPTURE.DENIED`.

---

### 7.3 Developer: Stripe, Paddle & PayPal Extension Architecture

Developers can customize or override built-in payment behavior **without modifying core platform code** by creating extension classes. Extensions are **auto-detected** by `config/payment_processors.php`.

#### Extending Stripe (`payment-processors/stripe/StripeProcessorExtension.php`)
This file is auto-detected. If present, it automatically replaces `StripeProcessor` for `processor_id = 1`:

```php
<?php

namespace PaymentProcessors\Stripe;

use App\Services\Payments\Processors\StripeProcessor as BaseStripeProcessor;

class StripeProcessorExtension extends BaseStripeProcessor
{
    public function getName(): string
    {
        return 'Customized Stripe' . ($this->isSandbox() ? ' (Sandbox)' : '');
    }

    public function createPaymentIntent(float $amount, string $currency = 'usd'): array
    {
        $result = parent::createPaymentIntent($amount, $currency);
        // Custom logging or metadata injection
        return $result;
    }
}
```

#### Extending Paddle (`payment-processors/paddle/PaddleProcessorExtension.php`)
Auto-detected for `processor_id = 2`:

```php
<?php

namespace PaymentProcessors\Paddle;

use App\Services\Payments\Processors\PaddleProcessor as BasePaddleProcessor;

class PaddleProcessorExtension extends BasePaddleProcessor
{
    public function createTransaction(float $amount, string $currency = 'USD', array $meta = []): array
    {
        $meta['custom_store_ref'] = config('app.name');
        return parent::createTransaction($amount, $currency, $meta);
    }
}
```

#### Extending PayPal (`payment-processors/paypal/PayPalProcessorExtension.php`)
Auto-detected for `processor_id = 3`:

```php
<?php

namespace PaymentProcessors\PayPal;

use App\Services\Payments\Processors\PayPalProcessor as BasePayPalProcessor;

class PayPalProcessorExtension extends BasePayPalProcessor
{
    public function getName(): string
    {
        return 'Customized PayPal' . ($this->isSandbox() ? ' (Sandbox)' : '');
    }

    public function charge(float $amount, string $currency, array $payload): \App\Services\Payments\PaymentResult
    {
        // Custom pre/post processing before capturing PayPal order
        return parent::charge($amount, $currency, $payload);
    }
}
```

---

### 7.4 Developer: Building & Registering Custom Payment Plugins

To add a brand-new third-party payment gateway (e.g. Authorize.net, Braintree, Square, Razorpay, or Mollie), follow this 9-step integration blueprint:

#### Step 1: Copy Template Directory
Copy the processor template directory:
```bash
cp -r payment-processors/example-gateway payment-processors/my-gateway
```

#### Step 2: Create Main Processor Driver
Rename and create `payment-processors/my-gateway/MyGatewayProcessor.php`. It must implement `PaymentProcessorInterface`:

```php
<?php

namespace PaymentProcessors\MyGateway;

use App\Services\Payments\Contracts\PaymentProcessorInterface;
use App\Services\Payments\PaymentResult;

class MyGatewayProcessor implements PaymentProcessorInterface
{
    public function getName(): string
    {
        return 'My Gateway' . ($this->isSandbox() ? ' (Sandbox)' : '');
    }

    public function isSandbox(): bool
    {
        return config('services.my_gateway.sandbox', true);
    }

    public function charge(float $amount, string $currency, array $payload): PaymentResult
    {
        try {
            $transactionId = $payload['transaction_id'] ?? 'TXN_' . time();

            return new PaymentResult(
                success: true,
                authorizationCode: 'AUTH_' . rand(1000, 9999),
                transactionId: $transactionId,
                errorMessage: '',
                processorName: $this->getName()
            );
        } catch (\Throwable $e) {
            return new PaymentResult(
                success: false,
                authorizationCode: '',
                transactionId: '',
                errorMessage: $e->getMessage(),
                processorName: $this->getName()
            );
        }
    }
}
```

#### Step 3: Add Credentials to `.env`
```env
MY_GATEWAY_API_KEY=your_live_key
MY_GATEWAY_SANDBOX_API_KEY=your_sandbox_key
```

#### Step 4: Insert Database Record
Insert a row into `order_processors` using a custom processor ID starting at **100 or higher** (IDs 0 to 99 are reserved for built-in processors):

```sql
INSERT INTO order_processors (processor_id, processor_name, production, created_at, updated_at)
VALUES (100, 'My Gateway', 0, NOW(), NOW());
```

#### Step 5: Register in `config/payment_processors.php`
Add these two lines in the custom processors section at the bottom of `config/payment_processors.php`:

```php
require_once base_path('payment-processors/my-gateway/MyGatewayProcessor.php');
$processors[100] = \PaymentProcessors\MyGateway\MyGatewayProcessor::class;
```

> **Note**: The array index (`100`) must match the `processor_id` value in the `order_processors` database table.

#### Step 6: Update Processor Type Mapping
In `App\Services\Payments\PaymentProcessorManager::activeProcessorType()`, add your processor's slug:

```php
return match ($this->activeProcessorId()) {
    1       => 'stripe',
    2       => 'paddle',
    3       => 'paypal',
    100     => 'my-gateway',   // <-- Custom processor slug
    default => 'test',
};
```

#### Step 7: Handle Frontend JS Checkout Integration
In `OrderReview::preparePayment()`, add an `elseif ($type === 'my-gateway')` branch that fetches your processor's client tokens or configuration parameters and passes them to the Alpine checkout component.

#### Step 8: Interface Contract & DTO Reference
```php
// app/Services/Payments/Contracts/PaymentProcessorInterface.php

interface PaymentProcessorInterface
{
    public function charge(float $amount, string $currency, array $payload): PaymentResult;
    public function isSandbox(): bool;
    public function getName(): string;
}
```

#### Step 9: Enable in Admin Workspace
Navigate to **Admin → Checkout → Processors** (`/admin/ecommerce/checkout-processors`) → Select **My Gateway** as Primary → Toggle Sandbox/Production as needed.

---

## 🎫 8. Helpdesk Support Ticket Manager & Role Permissions

### 8.1 Customer: Submitting Tickets & Attachments

Authenticated customers can submit support requests at `/tickets/create` (`CreateTicket` Livewire component):

- **Fields**: Department selection, Subject, Priority (Low, Normal, High, Urgent), Related Order selection, and Message body.
- **Multiple Attachments**: Upload multiple images, PDFs, or log files processed via `App\Services\TicketAttachmentService`. Attached files are stored in `storage/app/tickets/attachments/...` with secure access validation.
- **Guest vs Customer View**: Registered customers view their open and closed tickets in their account dashboard; public guests can access specific ticket status pages using encrypted token links (`PublicTicketView`).

---

### 8.2 Admin & Staff: Support Queue Dashboard & Operations

Support staff and administrators manage tickets at `/admin/tickets` (`AdminTicketShow` Livewire component).

- **Ticket Queue Dashboard**: Filter tickets by Status (`Open`, `Pending Staff Reply`, `Customer Responded`, `Resolved`, `Closed`), Priority, Department, or Assigned Staff Agent.
- **Staff Replies & Internal Notes**: Support agents can post public replies to customers (triggering `TicketNotificationService` email notifications) or add private **Internal Staff Notes** visible only to operational teams.
- **Status & Priority Toggles**: Change ticket priority or mark resolved in one click.

---

### 8.3 Admin: Knowledge Base (KB) Article Cross-Linking

The Helpdesk integrates directly with the self-hosted **Knowledge Base (KB)** system (`/admin/kb`):

- **TinyMCE Rich Text Editor**: Self-hosted TinyMCE rich text manager for writing detailed solution documentation.
- **KB Category Browser**: Organize articles into hierarchical categories (`KbCategory`) with multi-language support.
- **Ticket Reply Cross-Linking**: Support agents can insert direct Knowledge Base article links (`/kb/{seo_link}`) directly into ticket replies to resolve common customer inquiries instantly.

---

### 8.4 Developer: User Role Levels & Ticketing Database Schemas

Platform access is controlled by 3 user role levels stored in `users.role_id`:

| Role ID | Role Name | Permissions |
|---|---|---|
| **1** | **Customer** | Submit support tickets, view personal order history, manage profile |
| **3** | **Admin** | Full system access across catalog, settings, plugins, themes, users, and tickets |
| **4** | **Staff / Support Agent** | Access to support ticket queues, helpdesk management, and order processing |

#### Database Schema
- `tickets`: `id`, `ticket_number`, `user_id`, `department_id`, `assigned_to`, `subject`, `priority`, `status`, `last_reply_at`.
- `ticket_messages`: `id`, `ticket_id`, `user_id`, `is_staff_reply`, `is_internal_note`, `message_body`.
- `ticket_attachments`: `id`, `ticket_message_id`, `original_filename`, `storage_path`, `file_size`, `mime_type`.

---

## 📥 9. Digital Downloads & Asset Management Systems

### 9.1 Overview: Understanding the Two Distinct Download Engines

The platform includes **TWO completely separate download engines** tailored for different use cases:

```
                  ┌──────────────────────────────────────────────┐
                  │          DIGITAL DOWNLOAD ENGINES            │
                  └──────────────────────┬───────────────────────┘
                                         │
                 ┌───────────────────────┴───────────────────────┐
                 ▼                                               ▼
  ENGINE 1: Order-Based Downloads               ENGINE 2: CMS Asset Downloads
  ────────────────────────────────               ──────────────────────────────
  • Attached to paid products/variants           • Embedded in CMS pages via [download:ID]
  • Gated by purchase completion                 • Public or gated site documentation
  • Enforces download count & expiry             • Asset file manager with counters
```

---

### 9.2 Engine 1 — Order-Based Digital Product Downloads

Used for digital products sold in your catalog (e-books, software installers, zip archives, audio/video files).

#### Configuration on Products
1. In **Admin → Products → Edit Product**, check **Digital Download Item** (`download_item = 1`).
2. Attach digital assets at the variant level:
   - **Local File Upload**: Upload asset files directly into secure storage.
   - **Custom S3 Disk**: Store files on AWS S3 buckets.
   - **Direct URL Override**: Provide a direct external file link.

#### Customer Access & Expiry Enforcement
- Upon order payment completion, secure download links are generated in the customer's order summary and confirmation emails.
- **Max Downloads (`max_downloads`)**: Limit the maximum number of times a customer can download the file (e.g. 5 downloads).
- **Download Expiry**: Automatically expire download access X days after purchase.
- **Admin Download Reminders**: Support staff can click **Send Download Reminder** on the Order Details screen (`AdminOrderDetails`) to resend access emails.

---

### 9.3 Engine 2 — CMS Asset Downloads Manager (`[download:ID]`)

Used for downloadable PDF brochures, spec sheets, user manuals, and software documentation embedded into CMS pages, product descriptions, or blog posts.

#### Managing Assets in Admin (`/admin/cms-downloads`)
Navigate to **Admin → CMS → Asset Downloads** (`AdminCmsDownloads` & `AdminCmsDownloadEdit`).

- Upload files locally, select S3 storage, or enter external URLs.
- Track total download counts (`download_count`) per asset.
- Add descriptive titles and file format labels.

#### Embedding via Shortcode
Insert the shortcode tag anywhere in CMS content or Blade templates:

```
[download:12]
[download:12 label="Download User Manual (PDF)"]
```

`ContentParserService` automatically parses `[download:ID]` tags, resolving the asset title, file extension badge, file size (e.g. `2.4 MB`), and generating a tracked download link.

---

## 🧩 10. CMS Code Embeds, Form Builder & Navigation Systems

### 10.1 CMS Code Embeds Manager (`[code-embed:ID]`)

Navigate to **Admin → CMS → Code Embeds** (`AdminCmsEmbeds` & `AdminCmsEmbedEdit`).

Create reusable, responsive HTML/JS code snippets without breaking the TinyMCE editor:
- **Use Cases**: Video iFrames (YouTube, Vimeo), responsive charts, custom JavaScript calculators, audio players, external widgets.
- **Shortcode**: `[code-embed:5]`
- `ContentParserService` strips raw code execution from the WYSIWYG editor while cleanly expanding `[code-embed:ID]` on the public storefront.

---

### 10.2 Visual Form Builder (`[cms-form:ID]`) & Opt-ins

Navigate to **Admin → CMS → Forms** (`AdminCmsForms`, `AdminCmsFormEdit`, & `AdminCmsFormSubmissions`).

- **Visual Field Builder**: Build custom contact or inquiry forms with drag-and-drop fields, custom validation rules, required fields, and placeholder text.
- **Security & Spam Protection**: Integrates reCAPTCHA v3 verification (`RecaptchaService`) and rate limiting.
- **Email Notifications & Opt-ins**: Configure recipient notification emails and automatically subscribe form submitters to mailing lists (`OptinService`).
- **Submission Log**: View, filter, and export form submission records in the admin workspace.
- **Shortcode**: `[cms-form:3]`

---

### 10.3 Top Navigation Builder & Relational List Menus (`[list-menu:ID]`)

#### Top Navigation Builder (`/admin/nav-menus`)
- Build multi-level relational dropdown navigation menus (`AdminNavMenus`, `NavItemRenderer`).
- Configure hover styles, color schemes, visibility rules, and per-item translations.

#### Relational List Menus (`/admin/cms-list-menus`)
- Build structured list menus (e.g. Footer link lists, Sidebar navigation).
- Embed anywhere using shortcode: `[list-menu:4]`.

---

### 10.4 Admin: Slide-Out Editor Drawers (Shortcodes, Links, Plugins & Widgets)

Both the **CMS Page Editor** (`/admin/cms-pages/{id}/edit`, `AdminCmsPageEdit`) and **Product Editor** (`/admin/ecommerce/products/{id}/edit`, `AdminProductEdit`) feature a **Unified Floating Sidebar Tab Container** docked on the right side of the screen.

Clicking any floating tab slides out a dedicated drawer over the editor:

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│ Editor Window                                             ┌───────────────────┐ │
│                                                           │ 🩵 Widgets Drawer │ │
│ [ WYSIWYG TinyMCE Rich Text Editor ]                      │ 💚 Plugins Panel  │ │
│                                                           │ 💙 Shortcodes Tool│ │
│                                                           │ 🧡 Links Generator│ │
│                                                           └───────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────────┘
```

#### 1. 🩵 Widgets Drawer (`partials.html-widgets-drawer`)
- **Slide-out Library**: Access a library of pre-built, responsive HTML cards, hero sections, feature grids, alert banners, callout boxes, and glassmorphism elements.
- **One-Click Insertion**: Click **Insert Widget** to inject formatted HTML directly into the TinyMCE editor at the current cursor position.

#### 2. 💚 Plugins Panel (`partials.display-plugins-drawer`)
- **Interactive Reference**: Browse installed Display Plugins (`slideshow-2026`, `featured-items`, `cross-sells`, `live-search-2026`, `events-calendar-2026`, `social-icons-2026`, `brands-2026`).
- **Options & CSS Inspection**: Inspect available shortcode parameters (e.g. `layout=month`, `category="electronics"`, `header="Popular Products"`) and inspect default CSS reference rules.

#### 3. 💙 Shortcodes Generator Drawer (`partials.shortcodes-generator-drawer`)
- **ID-Free Shortcode Builder**: Search and select dynamic elements without memorizing numeric database IDs:
  - `[page:ID]` — CMS Pages
  - `[download:ID]` — Digital Downloads
  - `[code-embed:ID]` — Reusable Code & Video Embeds
  - `[cms-form:ID]` — Visual Interactive Forms
  - `[list-menu:ID]` — Navigation List Menus
  - `[plugin:...]` — Display Plugins
- **One-Click Actions**: Click **Insert into Editor** or **Copy Shortcode** to place the generated tag directly into the editor field.

#### 4. 🧡 Link Generator Drawer (`partials.link-generator-drawer`)
- **Multi-Entity Autocomplete**: Real-time autocomplete drawer searching across **Products, Brands, Categories, and CMS Pages**.
- **Formatted Link Output**: Instantly generates formatted HTML anchor tags or internal SEO slug links with custom link text (e.g. `<a href="/items/wireless-headphones">Wireless Headphones</a>` or `<a href="/section/audio">Audio Category</a>`).
- **Direct Insertion**: Click **Insert Link** to inject the link into TinyMCE at the cursor selection.

---

### 10.5 Admin: Included Testimonials Manager & Testimonials Plugin (`[plugin:testimonials-2026]`)

Manage customer quotes and social proof testimonials in **Admin → Testimonials** (`/admin/testimonials`, `AdminTestimonialsManager`).

#### 1. Managing Testimonials (`testimonials` table)
- **Author Identity**: Store customer name (`author_name`), title/company (`author_title`), and avatar image (`avatar_path`).
- **Star Rating**: Assign 1 to 5 star ratings (`rating`).
- **Quote Body**: Rich customer testimonial quote text (`content`).
- **Active & Sort Order**: Reorder display sequence (`sort_order`) and toggle active status (`is_active`).

#### 2. Embedding via Shortcode (`[plugin:testimonials-2026]`)
Embed testimonial carousels or card grids on any CMS page or layout block:
- `[plugin:testimonials-2026]` — Render default interactive slider / carousel layout.
- `[plugin:testimonials-2026 layout=grid]` — Render responsive card grid layout.
- `[plugin:testimonials-2026 count=3]` — Limit to top 3 testimonials.



---

## 🔍 11. Catalog Discovery, Live Search & Events

### 11.1 Admin: Advanced Shop Search Filtering Drawer

Toggle **Enable Advanced Shop Search Filtering Panel** in **Admin → Settings → Shop Display** (OFF by default).

When enabled, a slideout drawer appears on `/shop` featuring:
1. Multi-select brand checkboxes.
2. Hierarchical category/subcategory tree selector.
3. Dual interactive price range slider (`minPriceFilter` to `maxPriceFilter`).
4. Dynamic JSON variant attribute accordions (Color, Size, Material).
5. Automatic URL query string synchronization.

---

### 11.2 Admin: Multi-Content Live Search (`[plugin:live-search-2026]`)

Insert `[plugin:live-search-2026]` into any CMS page or layout block.

```
Search Query ──► /api/live-search-api?q=query ──► Unified Results JSON
```

Queries across **6 distinct content types**:
- 🟪 **Categories** (`/section/{slug}`)
- 🟪 **Brands** (`/brands/{slug}`)
- 🟦 **Products** (`/items/{slug}`)
- 🟩 **CMS Pages** (`/{slug}`)
- 🩵 **Knowledge Base Articles** (`/kb/{slug}`)
- 🟧 **Testimonials**

---

### 11.3 Admin: Events Calendar Integration (`[plugin:events-calendar-2026]`)

Insert `[plugin:events-calendar-2026]` to render an interactive event calendar.

- **Modes**: Month Grid (`layout=month`), Agenda List (`layout=list`), Cards Grid (`layout=grid`).
- Automatically queries `ProductVariantEvent` records. Clicking any event opens a glassmorphism modal with direct **"Book Event Ticket"** checkout links.

---

### 11.4 Developer: Collated Search Index Engine

`CmsPage` and `Product` models contain `cms_search_index` and `product_search_index` `LONGTEXT` columns backed by MySQL FULLTEXT indexes.

- **Auto-Indexing**: Model `booted()` listeners execute `rebuildSearchIndex()`, stripping shortcodes (`[plugin:...]`) and HTML while auto-injecting terms for downloads and event tickets.
- **Search Index Lock**: Admins can check **Lock Search Keywords** (`cms_search_index_locked`) to manually append custom keywords without auto-overwriting.
- **Artisan CLI**: Run `php artisan search:rebuild-index` to bulk-rebuild all search keywords.

---

## 🔐 12. Access Control, Content Gating & Guest Users

### 12.1 Built-in Social Provider Logins (Google, Facebook, GitHub) & User Verification Matrix

The platform integrates multi-provider OAuth authentication (`laravel/socialite` for Google, Facebook, GitHub) alongside standard password registration and guest checkout.

#### User Registration & Email Verification Matrix

| Account Type / Method | Password Status | Email Verification Status | Access Level Granted | Conversion / Verification Flow |
|---|---|---|---|---|
| **Social OAuth Login** (Google, Facebook, GitHub) | Null (No password required) | **Auto-Verified** (`email_verified_at = now()`) | **Full Account Access** (Instant access to customer dashboard, order history, ticket portal) | **Auto-Verified on Login**: The third-party OAuth provider authenticates identity. `Socialite` handles user creation (`User::firstOrCreate`), setting `email_verified_at` automatically. |
| **Standard Password Registration** | Bcrypt Hashed Password | **Pending Verification** (`email_verified_at = null`) | **Restricted Account Access** (Can browse store, but redirected to `/email/verify` when accessing dashboard/tickets) | **Email Verification Required**: System sends a signed verification link to the user's inbox upon registration. Clicking the link populates `email_verified_at` and unlocks full access. |
| **Guest Checkout Provisioning** | `[GUEST-USER]` Sentinel String | **Unverified** (`email_verified_at = null`) | **Order-Only Access** (Receives order confirmation email & instant download link, but cannot log in directly) | **2-Step Guest Account Conversion**: <br>1. **Step 1 (Verify Email)**: Click signed link in order confirmation email. <br>2. **Step 2 (Set Password)**: Signed link sets `email_verified_at = now()` and routes to `GET /account/set-password`, replacing `[GUEST-USER]` sentinel with a real bcrypt password hash. |

---

### 12.2 Admin: Post-Order Completion Redirects & Email Action Buttons

Configure in **Admin → Products → Edit → Advanced Settings**:

- **Destination URL / Shortcode**: Enter a full URL (`https://...`), relative path (`/members/...`), or CMS shortcode (`[page:12]`).
- **Email Button Label**: Custom text (e.g. `"Access Workshop"`) rendered as a violet action button in Order Confirmation, Shipment Confirmation, and Download Reminder emails.

---

### 12.3 Admin: CMS Page Access Gating (Purchase Check vs. Access Code)

Configure under **CMS Page Edit → Page Access / Gating**:

| Product Gate (`required_product_id`) | Code Gate (`requires_code` + `access_code`) | Visitor Outcome |
|---|---|---|
| ❌ Off | ❌ Off | Publicly accessible to everyone |
| ✅ On | ❌ Off | Blocked unless user has a verified paid order for the product |
| ❌ Off | ✅ On | Blocked by lock screen until access code is entered |
| ✅ On | ✅ On | **Dual-Gate UI**: Satisfying EITHER purchase OR entering code grants access |

---

### 12.4 Developer: Secure UUID Magic Links (`content_access_tokens`)

When a product with a `completion_redirect` is ordered, the system generates a secure random UUID token (`content_access_tokens` table):

```
Order Completed ──► ContentAccessToken::generateOrRefresh() ──► Email Link: /content-access/{uuid}
```

- **Guest Access**: Allows guest purchasers to bypass CMS page purchase gates without logging in.
- **Expiry**: Tokens expire after **90 days** by default (regenerated on admin email resends).
- **Redemption Flow**: Clicking `/content-access/{uuid}` validates the token, pushes `product_id` into `session('verified_purchased_products')`, and redirects to the content page.

---

### 12.5 Developer: Guest Account Conversion Flow (`[GUEST-USER]`)

When a customer checks out as a guest without supplying a password, `Checkout.php` saves a sentinel value:

```php
public const GUEST_PASSWORD = '[GUEST-USER]';
```

#### Security Enforcement
1. **Unverifiable Password**: `Hash::check()` always fails on `[GUEST-USER]`, preventing direct form logins.
2. **Two-Step Conversion Flow**:
   - **Step 1 (Verify Email)**: Guest attempts to access dashboard → redirected to `/email/verify`. Must click the signed verification link sent to their inbox.
   - **Step 2 (Set Password)**: Signed link sets `email_verified_at` and routes to `GET /account/set-password` where the guest enters a new password, replacing the sentinel with a real bcrypt hash.


---

## 🌐 13. Multi-Language System & Email Template Engine

### 13.1 Admin: Language Management, Flags & Switchers

Navigate to **Admin → Languages** (`/admin/languages`).

- **Flag Icons**: Uses `flag-icons` CSS library (2-letter country code, e.g. `us`, `fr`, `de`, `es`).
- **Currency Overrides**: Per-language currency code, symbol, and placement (e.g. EUR / € for French).
- **RTL Support**: Toggling RTL sets `dir="rtl"` on `<html>` for Arabic, Hebrew, etc.

---

### 13.2 Admin: Detailed Guide to the 10 Email Template Types & Placeholders

Navigate to **Admin → Email Templates** (`/admin/email-templates`, `AdminEmailTemplates`, `AdminEmailTemplateEdit`).

The platform includes 10 default, system-triggered email template types (`email_template_types` table):

| ID | Template Name | Type Slug | Trigger Event | Key Available Variables / Placeholders |
|---|---|---|---|---|
| **1** | **Order Confirmation** | `order_confirmation` | Customer completes an order checkout | `{{order_id}}`, `{{customer_name}}`, `{{order_total}}`, `{{order_subtotal}}`, `{{order_taxes}}`, `{{order_shipping}}`, `{{order_items_table}}`, `{{completion_redirect}}` button |
| **2** | **Order Shipment Confirmation** | `order_shipment` | Admin marks order as shipped | `{{order_id}}`, `{{customer_name}}`, `{{tracking_number}}`, `{{order_items_table}}` |
| **3** | **Download Order Reminder** | `download_reminder` | Admin clicks "Send Download Reminder" | `{{order_id}}`, `{{customer_name}}`, `{{download_links}}`, `{{order_items_table}}` |
| **4** | **Customer Registration (Retail)** | `registration_retail` | New retail customer registers account | `{{customer_name}}`, `{{app_name}}` |
| **5** | **Customer Registration (Wholesale)** | `registration_wholesale` | New wholesale customer registers account | `{{customer_name}}`, `{{app_name}}` |
| **6** | **Account Activation / Verification** | `account_activation` | User requests verification or guest account setup | `{{customer_name}}`, `{{activation_url}}`, `{{app_name}}` |
| **7** | **Reset Password** | `password_reset` | User submits password reset request | `{{customer_name}}`, `{{reset_url}}`, `{{app_name}}` |
| **8** | **Support Ticket Submitted** | `ticket_submitted` | New support ticket submitted by customer/guest | `{{customer_name}}`, `{{ticket_title}}`, `{{ticket_status}}`, `{{ticket_url}}` |
| **9** | **Support Ticket Reply Received** | `ticket_reply` | Staff posts reply to a support ticket | `{{customer_name}}`, `{{reply_author}}`, `{{reply_body}}`, `{{ticket_title}}`, `{{ticket_url}}` |
| **10** | **Support Ticket Status Updated** | `ticket_status` | Support ticket status changes (Resolved/Closed) | `{{customer_name}}`, `{{ticket_title}}`, `{{previous_status}}`, `{{ticket_status}}`, `{{ticket_url}}` |

---

### 13.3 Admin: Email Layout Builder, Active Profiles & Multi-Language AI Translation Tabs

#### 1. Multiple Profiles & Active Selection (`is_active`)
Each email type supports multiple profiles (e.g. Default Order Confirmation vs. Holiday Order Confirmation). Only **one profile per email type** can be active (`is_active = 1`). Toggling a profile active automatically deactivates siblings.

#### 2. Visual Layout Builder Blocks
- **Header & Branding**: Custom `from_address`, `from_name`, `bcc_address`, `header_html`, `banner_image_url`, `banner_image_link`, `show_banner`.
- **Salutation & Greeting**: `include_salutation`, `salutation` (e.g. `"Dear {{customer_name}},"`), `greeting` (HTML card).
- **Body HTML**: Primary message content (`body`). For order templates (`order_confirmation`, `order_shipment`, `download_reminder`), if `{{order_items_table}}` is missing from the body text, `EmailTemplateService` automatically appends `<p>{{order_items_table}}</p>` to ensure order line items are always rendered.
- **Sign-Off & Footer**: `sign_off`, `signature`, `disclaimer`, `copyright`, `footer_image_url`, `footer_image_link`, `show_footer_image`, `footer_html`.

#### 3. Inline Multi-Language AI Translation Tabs
The email template editor includes language tabs for every active site language (`email_template_translations` table).
- Administrators can inspect, edit, or click **AI Translate Email** to auto-translate subject lines, greetings, body HTML, sign-offs, and footer disclaimers via `TranslationService`.

#### 4. Live Email Preview Modal
Click **Preview Template** in the editor to launch an in-browser modal rendering the HTML email populated with realistic mock order data, items table, tracking number, and activation links.

---

### 13.4 Developer: Child-Table Translation Pattern & Eager Loading

All translatable entities use dedicated `*_translations` child tables:

```
languages
  ├── cms_page_translations
  ├── product_translations
  ├── kb_article_translations
  ├── testimonial_translations
  ├── nav_item_translations
  ├── site_label_translations
  └── email_template_translations
```

#### The `HasTranslations` Trait & `EmailTemplateService`
- `EmailTemplateService::sendEmail($slug, $toEmail, $toName, $vars, $languageId)` fetches the active profile for `$slug`, eager-loads translations for `$languageId`, parses `{{variable}}` replacements, and dispatches `App\Mail\DynamicTemplateMail`.

---

### 13.5 Developer: `TranslationService` & Shortcode Protection

`App\Services\TranslationService` interfaces with OpenAI for automated translations.

> [!IMPORTANT]
> **Shortcode Protection Engine**: Before sending text to OpenAI, `TranslationService` strips bracketed tags (e.g. `[plugin:slideshow-2026 id=2]`), replacing them with neutral placeholders (`{{PLUGIN_0}}`). After translation returns, placeholders are restored to original shortcode syntax.

---

### 13.6 Admin & Developer: OpenAI Integration (AI Content Generation & AI Translation Pipeline)

The platform features an integrated OpenAI AI engine powered by `openai-php/client` (`^0.20.0`).

#### 1. Environment Credential Requirement (`.env`)
To enable AI features, configure your API key in `.env`:

```ini
# OpenAI API Key (Required for AI Content Generation & AI Translations)
OPENAI_API_KEY=sk-proj-your-actual-openai-api-key-here
```

#### 2. AI Content Generation for CMS Pages & Products
Administrators can generate new page body content, product descriptions, meta titles, and meta descriptions directly inside page and product editors:
- **CMS Page Editor (`/admin/cms-pages/{id}/edit`)**: Open the **AI Content Generator** card, type a prompt (e.g. *"Write a detailed 4-paragraph overview for an online store about page with company mission and values"*), and click **Generate Content with AI**.
- **Product Editor (`/admin/ecommerce/products/{id}/edit`)**: Type a product prompt (e.g. *"Write a compelling marketing description for wireless noise-canceling headphones with key feature bullet points"*), and click **Generate Content with AI** to auto-populate rich HTML content, short description, meta title, and meta description fields.

#### 3. AI Multi-Language Translation Pipeline
The AI translation service translates content into any active site language:
- **Bulk Language Auto-Translation (`/admin/languages`)**: Click **Bulk Translate All** on any language card. The background translation pipeline translates records across **11 core database entities**:
  1. `cms_pages` & `cms_page_translations`
  2. `products` & `product_translations`
  3. `product_variants` & `product_variant_translations`
  4. `kb_articles` & `kb_article_translations`
  5. `testimonials` & `testimonial_translations`
  6. `nav_items` & `nav_item_translations`
  7. `cms_list_menu_items` & `cms_list_menu_item_translations`
  8. `product_categories` & `category_translations`
  9. `site_labels` & `site_label_translations`
  10. `email_templates` & `email_template_translations`
  11. `plugin_settings` & `plugin_setting_translations`
- **Inline Editor Translation Tabs**: Inspect, manually edit, or click **AI Translate Record** / **AI Translate Email** inside individual editor translation cards.



### 13.5 Developer: `TranslationService` & Shortcode Protection

`App\Services\TranslationService` interfaces with OpenAI for automated translations.

> [!IMPORTANT]
> **Shortcode Protection Engine**: Before sending text to OpenAI, `TranslationService` strips bracketed tags (e.g. `[plugin:slideshow-2026 id=2]`), replacing them with neutral placeholders (`{{PLUGIN_0}}`). After translation returns, placeholders are restored to original shortcode syntax.

---

## ⚡ 14. Browser Queue Monitor & E-Commerce Analytics

### 14.1 Admin: Queue Monitor Operations (`/admin/languages/queue-monitor`)

Navigate to **Admin → CMS → Queue Monitor**.

Allows administrators to run, monitor, and stop background translation job workers directly from the browser without SSH access.

- **Real-Time Terminal**: Streams `queue:work` logs with color-coded status badges (🟢 Processed, 🔴 Failed, 🟡 Processing).
- **Controls**: Start Worker, Stop Worker, Set Max Jobs limit, Clear Log.

---

### 14.2 Admin: E-Commerce Analytics & Sales Performance Reports

Navigate to **Admin → Reports & Analytics**:
- **Cart Conversion Rate (`ReportCartConversion`)**: Tracks checkout funnel conversion percentages.
- **Completed vs. Abandoned Carts (`ReportCompletedVsAbandoned`)**: Visual comparison of finalized orders versus abandoned carts.
- **Customer Lifetime Spend (`ReportCustomerSpend`)**: Ranks top spending customers for loyalty campaigns.
- **Product Performance (`ReportProductPerformance`)**: Identifies top-selling items and inventory velocity.

---

### 14.3 Developer: Detached Process Execution & Liveness Architecture

```
Admin Livewire ──► Spawns storage/app/queue_runner.php ──► Writes storage/app/queue_worker.pid
                                  │
                                  └── Runs `php artisan queue:work` ──► Streams queue_worker.log
```

- **Cross-Platform Compatibility**:
  - **Linux/macOS**: Spawns via `nohup php ... > /dev/null 2>&1 &`. Liveness verified via `posix_kill($pid, 0)` or `/proc/{pid}`.
  - **Windows**: Spawns via `start /B php ...`. Liveness verified via `tasklist /FI "PID eq {pid}"`.
- **PID File Handshake**: `queue_worker.pid` indicates an active worker; when `queue:work` finishes (`--stop-when-empty`), the runner script automatically removes the PID file.

---

## 🔌 15. Extensible Plugin System & Included Display Plugins

### 15.1 Admin: Plugin Manager (`/admin/plugins`), Shortcodes & Settings

Navigate to **Admin → Display Plugins / Plugin Manager** (`/admin/plugins`).

- **Plugin Table**: View name, type badge (Display, Shipping, Email), shortcode, version, and active status toggle.
- **Settings Drawer (3 Tabs)**:
  1. **Settings Tab**: Data-driven options form (input, textarea, dark CSS code editor, checkboxes, selects).
  2. **Usage Tab**: Copyable shortcode snippet and parameter guide.
  3. **Activation Tab**: License key validation (for activation-required plugins).

---

### 15.2 Detailed Guide to All Included Display Plugins

The platform comes pre-packaged with 7 built-in display plugins:

#### 1. Slideshow Plugin (`[plugin:slideshow-2026]`)
- **Shortcode**: `[plugin:slideshow-2026]`
- **Parameters**: `id=1`, `nav=on|off`, `paging=on|off`, `autoplay=on|off`, `speed=3000`
- **Engine**: Swiper.js touch carousel with custom dark CSS theme manager (`live_css`).
- **Data Manager**: Manage slide titles, captions, images, buttons, and targets at `/admin/slideshows`.

#### 2. Featured Items Plugin (`[plugin:featured-items]`)
- **Shortcode**: `[plugin:featured-items]`
- **Parameters**: `max=8`, `columns=4`, `category=slug`
- Renders responsive product cards for all items where **Featured Item** (`featured_item = 1`) is enabled in catalog settings.

#### 3. Cross-Sell List Selector Plugin (`[plugin:cross-sells]`)
- **Shortcode**: `[plugin:cross-sells]`
- **Parameters**: `product_id=ID`, `title="You May Also Like"`
- Renders linked recommendation cards configured at the product level (`ProductCrossSell`).

#### 4. Multi-Content Live Search Plugin (`[plugin:live-search-2026]`)
- **Shortcode**: `[plugin:live-search-2026]`
- **Parameters**: `mode=input|results`, `placeholder="..."`, `layout=list|grid`
- Queries across Categories, Brands, Products, CMS Pages, Knowledge Base, and Testimonials.

#### 5. Interactive Events Calendar Plugin (`[plugin:events-calendar-2026]`)
- **Shortcode**: `[plugin:events-calendar-2026]`
- **Parameters**: `layout=month|list|grid`, `category="slug"`, `max=50`
- Renders 7-column interactive calendar, agenda lists, and ticket booking glassmorphism modals.

#### 6. Social Media Icons Plugin (`[plugin:social-icons-2026]`)
- **Shortcode**: `[plugin:social-icons-2026]`
- **Parameters**: `size=sm|md|lg`, `font_awesome=on`
- Displays active social media profiles (Facebook, Twitter, Instagram, LinkedIn, YouTube).

#### 7. Brands Display Plugin (`[plugin:brands-2026]`)
- **Shortcode**: `[plugin:brands-2026]`
- **Parameters**: `layout=grid|carousel`, `show_logos=true`
- Renders brand logo grids linking directly to brand catalog routes (`/brands/{slug}`).

---

### 15.3 Developer: `DisplayPlugin` & `ShippingPlugin` Interfaces

#### `DisplayPlugin` Contract
```php
namespace App\Plugins\Contracts;

use App\Models\Plugin;

interface DisplayPlugin
{
    public function slug(): string;
    public function name(): string;
    public function render(array $params, Plugin $plugin): string;
}
```

#### `ShippingPlugin` Contract
```php
namespace App\Plugins\Contracts;

use App\Models\Plugin;
use App\Plugins\Support\ShippingContext;

interface ShippingPlugin
{
    public function slug(): string;
    public function name(): string;
    public function calculateRates(ShippingContext $context, Plugin $plugin): array;
}
```

---

### 15.4 Developer: Creating Built-in & Drop-in Plugins (`plugin.json`)

#### Creating a Built-in Plugin
1. Create PHP class in `app/Plugins/Display/` implementing `DisplayPlugin`.
2. Register class in `App\Providers\PluginServiceProvider::boot()`:
   ```php
   $manager->register(\App\Plugins\Display\MyCustomPlugin::class);
   ```
3. Add plugin record & options in `database/seeders/PluginSeeder.php` and run `php artisan db:seed --class=PluginSeeder`.

#### Creating a Drop-in External Plugin
Drop-in plugins live in the root `/plugins/` directory:

```
plugins/
  my-banner-plugin/
    plugin.json
    MyBannerPlugin.php
```

`plugin.json` schema:
```json
{
  "class": "MyBannerPlugin",
  "name": "My Banner Plugin",
  "version": "1.0",
  "type": "display",
  "shortcode": "my-banner",
  "description": "Drop-in promotional banner plugin",
  "author": "Store Dev",
  "options": [
    {
      "field_name": "banner_text",
      "field_label": "Banner Text",
      "field_type": "input",
      "field_required": "yes",
      "sort_order": 10,
      "field_default_value": "Welcome to our store!"
    }
  ]
}
```

`PluginManager::discoverExternalPlugins()` automatically detects `/plugins/*/plugin.json` on boot, syncs settings to the database, and registers the shortcode.

---

### 15.5 Developer: Database Schema & API Reference

#### Core Tables
- `plugins`: Registered plugin instances (`api_id`, `filename`, `type`, `shortcode`, `activation_status`).
- `plugin_options`: Settings form field definitions (`field_name`, `field_type`, `field_editor`, `field_default_value`).
- `plugin_settings`: Stored setting values (`field_name`, `field_value`).

#### Helper Methods (`App\Models\Plugin`)
```php
// Get single setting with fallback
$value = $plugin->getSetting('banner_text', 'Default Text');

// Get all settings as key => value array
$settings = $plugin->getSettings();

// Bulk save settings
$plugin->saveSettings(['banner_text' => 'New Promo Text']);

// Query active display plugins
$activeDisplay = Plugin::scopeActive()->scopeOfType('display')->get();
```

---

*End of Master Help & Documentation System.*
