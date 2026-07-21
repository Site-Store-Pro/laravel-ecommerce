# Laravel Helpdesk, Ticketing & E-Commerce Platform

A comprehensive, enterprise-ready e-commerce platform built on Laravel 13, Livewire 3, Tailwind CSS, and Alpine.js. This platform combines a dynamic, high-performance storefront and e-commerce administration workspace.

---

## 🚀 Key Modules & Architecture

### 1. Storefront & E-Commerce
* **Dynamic Product Catalog & Cart:** Seamless checkout, slider carts, dynamic product layout selector (modular partials), and interactive breadcrumbs.
* **Dependent Variants & Combos:** Color/size variant selectors, progressive option filtering, and auto-resolved variant IDs.
* **Variant Color Deduplication:** Auto-deduplication of gallery thumbnails based on variant color, shade, or tint, with smart Alpine.js navigation.
* **Product Reviews & Ratings:** Customer review pipeline with star-rating UI.
* **Gift Wrapping & Personalization:** Custom checkout options configured at the variant level.
* **Product Cross-Selling & Event Details:** Cross-selling display plugin, post-cart intermediary pages, and variant event details calendar integrations.

### 2. Helpdesk & Customer Support
* **Ticket Submission & Queues:** Robust ticketing interface for customers with multiple attachments, and an operations dashboard for support agents.
* **Knowledge Base (KB):** Self-hosted TinyMCE rich text editor integration for documentation, categories, and articles.
* **Role-Based Permissions:** Admin, Staff, and Customer user role levels controlling access to ticketing, helpdesk queues, and catalog editing.

### 3. CMS, Menus & Shortcode Parser
* **Dynamic Content Parsing:** Unified `ContentParserService` that automatically processes shortcodes in CMS pages, product descriptions, and blogs.
* **CMS Form Builder:** Visual builder for public forms supporting field validations, email notifications, mailing list opt-ins, and reCAPTCHA v3.
* **Downloads & Embeds Managers:** Shortcode integrations for local/S3/URL-based asset downloads and responsive video/media embeds.
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
* **Modular Architecture:** Schema-backed registry and option systems for built-in and third-party external plugins.
* **Built-in Plugins:**
  - *Display:* Slideshow, Featured Items, and Cross-Sell list selectors.
  - *Shipping Carriers:* Real-time shipping rate integrations for FedEx, UPS, and USPS.

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
Ensure you have **PHP 8.2+**, **Composer**, **NodeJS & NPM**, and a database (MySQL/PostgreSQL/SQLite).

### 2. Setup Commands
```bash
# Clone the repository and install PHP dependencies
git clone <repository-url> laravel-gemini
cd laravel-gemini
composer install

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
├── Http/Controllers/       # Main controllers & APIs
├── Livewire/               # Storefront & Admin Livewire components
├── Models/                 # Eloquent entities (Product, Ticket, etc.)
├── Plugins/                # Plugin contracts & PluginManager
├── Services/               # E-commerce, Tax, & Parsing engines
config/                     # Custom configurations (nav_schemes, payment_processors)
database/
├── migrations/             # Database schemas
└── seeders/                # Core and Demo Seeders
plugins/                    # Directory for drop-in external plugins
resources/
├── css/                    # Tailwind CSS customizations & variables
└── views/                  # Blade and Livewire UI views
routes/                     # Web, API, and webhook routes
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
