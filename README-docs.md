# Laravel Helpdesk, Ticketing & E-Commerce Platform

A comprehensive customer support system and fully integrated e-commerce catalog/checkout application built on a modern stack:

- **Laravel 13**
- **Livewire 3**
- **Tailwind CSS (Modern generic light palette)**
- **Alpine.js**
- **Laravel Socialite (Google, Facebook, GitHub)**
- **TinyMCE rich-text editor (self-hosted GPL, fully customized toolbar)**
- **Database-backed CMS system with live HTML widget library**

This platform offers a unified solution for SaaS companies and online businesses, combining a robust customer support system (helpdesk, knowledge base) with a premium storefront and shop administration panel.

---

## Table of Contents

- [Technology Stack](#technology-stack)
- [Installation &amp; Local Setup](#installation--local-setup)
- [E-Commerce Features](#e-commerce-features)
- [Discount Configuration &amp; Calculation Engine](#discount-configuration--calculation-engine)
- [Dynamic Email Notifications System](#dynamic-email-notifications-system)
- [Support &amp; Helpdesk Features](#support--helpdesk-features)
- [User Roles &amp; Permissions](#user-roles--permissions)
- [Database Seeders &amp; Demo Accounts](#database-seeders--demo-accounts)
- [Digital Downloads &amp; Storage Settings](#digital-downloads--storage-settings)
- [CMS Downloads Manager](#cms-downloads-manager)
- [Inbound Email Webhook Ingestion](#inbound-email-webhook-ingestion)
- [Routing Directory &amp; Endpoints](#routing-directory--endpoints)
- [Timezone Configuration](#timezone-configuration)
- [Merchant Location, Currency &amp; VAT Settings](#merchant-location-currency--vat-settings)
- [VAT-Inclusive Pricing &amp; Cross-Border VAT Removal](#vat-inclusive-pricing--cross-border-vat-removal)
- [Per-Item Taxability (charge_tax)](#3-per-item-taxability-charge_tax)
- [Payment Processors](#payment-processors)
- [Subscription Variants & Recurring Billing](#subscription-variants--recurring-billing)
- [Dynamic Product Layout Selector](#dynamic-product-layout-selector)
- [Upgrading an Existing Install](#upgrading-an-existing-install)
- [Dynamic Blade &amp; Livewire Parser](#dynamic-blade--livewire-parser)
- [Reusable List Menus &amp; Shortcodes](#reusable-list-menus--shortcodes)
- [Admin Failsafe &amp; Editor Usability Features](#admin-failsafe--editor-usability-features)
- [Production Checklist](#production-checklist)
- [Plugin System](#plugin-system)
  - [Overview](#plugin-system-overview)
  - [Database Schema](#plugin-database-schema)
  - [File Architecture](#plugin-file-architecture)
  - [Shortcode Syntax](#shortcode-syntax)
  - [Built-in Plugins](#built-in-plugins)
    - [Slideshow Plugin](#slideshow-plugin)
    - [Featured Items Plugin](#featured-items-plugin)
    - [FedEx Shipping Plugin](#fedex-shipping-plugin)
    - [UPS Shipping Plugin](#ups-shipping-plugin)
    - [USPS Shipping Plugin](#usps-shipping-plugin)
  - [Shipping Provider Setup Guide](#shipping-provider-setup-guide)
    - [How Realtime Rates Work](#how-realtime-rates-work)
    - [FedEx Setup](#fedex-setup)
    - [UPS Setup](#ups-setup)
    - [USPS Setup](#usps-setup)
  - [Admin Panel](#plugin-admin-panel)
  - [Creating a Built-in Plugin](#creating-a-built-in-plugin)
  - [Creating a Drop-in Plugin](#creating-a-drop-in-plugin)
  - [Accessing Settings in Code](#accessing-plugin-settings-in-code)
  - [Plugin Deployment Checklist](#plugin-deployment-checklist)

---

# Technology Stack

| Component | Technology | Description |
|---|---|---|
| **Backend Framework** | Laravel 13 | Core PHP MVC Architecture |
| **Frontend/Logic** | Livewire 3 | Dynamic, reactive, single-page feeling components in pure PHP |
| **JS Sprinkles** | Alpine.js | Lightweight JS reactivity for toggles, dropdowns, and animations |
| **Design/Styling** | Tailwind CSS | Sleek, custom-designed light-themed design system |
| **Authentication** | Breeze + Socialite | Social login (Google, Facebook, GitHub) + standard email/pass |
| **Rich Text Editor** | TinyMCE (self-hosted GPL) | Embedded in Knowledge Base, CMS, and Product long description editors |
| **Database** | MySQL / SQLite | Fully relational structure with performance indexing |
| **Hierarchical Tree** | staudenmeir/laravel-adjacency-list | Recursive CTE relationships for infinite category nesting |

### Optional Payment SDK Dependencies

These packages are only required if the corresponding payment processor is enabled in `config/payment_processors.php`:

| Package | Version | Required For |
|---|---|---|
| `stripe/stripe-php` | ^20.0 | Stripe payment processor (processor_id = 1) |
| `paddlehq/paddle-php-sdk` | ^1.0 | Paddle Billing payment processor (processor_id = 2) |

Install only the packages you need:
```bash
# Stripe only
composer require stripe/stripe-php

# Paddle only
composer require paddlehq/paddle-php-sdk

# Both
composer require stripe/stripe-php paddlehq/paddle-php-sdk
```

---

## Installation & Local Setup

### 1. Requirements
Ensure your development machine has:
* PHP 8.3+
* Composer
* Node.js & npm
* SQLite or MySQL/MariaDB

### 2. Initial Setup
Clone the repository and install dependencies:
```bash
git clone <repository-url> laravel-ecommerce-helpdesk
cd laravel-ecommerce-helpdesk
composer install
npm install
```

### 3. Environment & Key Configuration
Create a local environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```
*Configure your database (`DB_CONNECTION`, etc.), mail, and Socialite client credentials inside the generated `.env` file.*

### 4. Database Setup & Seeding
The e-commerce and CMS tables, relationships, and performance indexes have been squashed from the initial installation onward. Running migrations builds the complete schema from scratch in two clean steps:
1. `0001_01_01_000000_initial_install.php`: Installs base ticketing, user registry, and queue tables.
2. `2026_07_01_100000_create_ecommerce_and_cms_tables.php`: Installs all consolidated storefront, variants, inventories, orders, discounts, pages, layouts, settings, shipping, warehouse locations, and email template tables.

To execute the database migrations and populate the platform with default roles, administrative accounts, knowledge base articles, products, variants, and home page CMS blocks:
```bash
php artisan migrate:fresh --seed
```

**Developer QA Seed Data** (not included in production distribution):
```bash
php artisan db:seed --class=DevEcommerceSeeder
```
This populates 500 products, 75 brands, and 110 categories (80 top-level + 30 subcategories) with placeholder images from `picsum.photos` as direct external URLs.

### 5. Compile Frontend Assets
Run the Vite development server locally:
```bash
npm run dev
```

---

# E-Commerce Features

### Storefront & Shopping Cart

* **Shop Catalog (`/shop`)**: Browse products with live search, customizable pagination overrides (dropdown selector supporting 5, 10, 15, 25, 50, 75, or 100 items per page with strict whitelist sanitization of the `perPage` query parameter to prevent type injection or invalid limits), and an icon-based Grid/List layout toggle. Images in the catalog act as plain, non-zooming links directly to their details pages.

* **Hierarchical Category Drill-Down**: Contextual drill-down navigation panel showing nested categories/subcategories/grandchildren present in the current result set. Clicking a category drills into `/section/{slug}`.

* **Combined Brand & Category Filters**: Allows users to select a category and a brand simultaneously, applying both filters concurrently to narrow down results instead of having them overwrite each other.

* **Clean Routing & URLs**: Simplifies URLs dynamically (e.g. `/section/notebooks`) when only a single category or brand filter is active, preventing redundant URL query parameters (like `?category=notebooks&brand=`). All brand links route to `/brands/{slug}`.

* **Brand Filter Constraint**: Dynamically filters subcategory options on brand selection, ensuring users only see categories containing products matching that brand.

* **Dynamic Header & Descriptions**: Category and brand landing pages dynamically swap the generic page title and description with the database category or brand name/description (or joins them with `>` when multiple filters are active).

* **Alpine.js Variant Gallery**: Premium image details gallery with:
  * **Hover Zoom (Lens Magnifier)**: Moves a 200% resolution zoomed lens dynamically following the user's cursor.
  * **Click-to-Open Lightbox Modal**: Frosted-glass full-screen modal showing high-res zoom images with previous/next navigation buttons.
  * **Image Alt & Zoom Labels**: Pulls custom, accessible `image_alt` tags and descriptive `zoom_label` captions in the lightbox (configurable in database/seeders).
  * **Variant Sync**: Flat list of images across all variants. Selecting any thumbnail swaps the main image client-side and automatically updates the active variant choice and pricing.
  * **Thumbnail Strip Visibility**: Automatically hides the thumbnail strip when the product has only 1 total image across all variants.

* **"You May Also Like" Carousel**: Horizontal scrollable product recommendation track at the bottom of detail pages showing related products from the same category or brand, featuring previous/next slide navigation.

* **SEO-Friendly Product Details (`/items/{seo_slug}`)**: Detailed descriptions, pricing, and active option selection:
  * **Server-side Quantity Validation**: Restricts input quantities to integers between 1 and 10,000, sanitizing non-numeric inputs server-side to prevent type crashes.
  * **Dynamic Drill-Down Selectors**: Auto-detects complex variant attributes (e.g. Color and Size) and renders clean button options. Selecting a color progressively filters and disables unavailable size options.
  * **Reactive Price & Fee Updates**: Automatically updates price and adds variant-specific fees (`variant_fee`) dynamically.
  * **Dynamic Product Customization Forms (Product Builder)**: Renders user input fields (text, textareas, selects, radio groups, checkboxes, and multi-select checkboxes) defined by admins. Pricing updates in real time to include selection surcharges (supporting distinct Retail vs. Wholesale pricing rules).

* **Active Discount Strikethrough Display**: When a product has an active discount, the regular price is displayed with a strikethrough next to the highlighted discount sale price. This consistent visual marked-down pricing is rendered across the product details view, cart page, cart slide-out drawer, checkout sidebar, order review summary, successful checkout receipt, administrative dashboard order fulfillment page, and dynamic HTML email tables.

* **Gift Wrapping & Personalization Options**: Variants can be configured by administrators to support custom gift wrapping or engraving/personalization with an optional fee. If active, the public product details page shows option controls to enter engraving details/gift messages, dynamically adding the fee to the cart line item price.

* **SEO-Friendly Category Pages (`/section/{category_slug}`)**: Filtered product listings on clean, indexable URL paths.
* **SEO-Friendly Brand Pages (`/brands/{brand_slug}`)**: Clean, shareable brand-filtered catalog pages. All brand links in the navigation mega-menu and admin panel use this route.

* **Reactive Cart (`/cart`)**: Manage item quantities dynamically. Sessions persist using cookie-backed database logging (`shopping_cart_log`).

* **Checkout Flow (`/checkout`)**: Guest details entry, inline social/traditional login, and simulated payment gateway verification. Digital-only orders bypass shipping and tax; shippable orders calculate applicable sales tax or VAT after an address is entered.
  * **Auto-Bypass to Review**: To reduce checkout friction, returning customers or users authenticated via social providers bypass the `/checkout` details form and go straight to the payment review screen (`/checkout/review`) if their profile contains complete info (name/email for digital-only orders) or complete shipping details (name/email/address/city/zip/country/state for physical orders).
  * **Selective Tax**: Tax is only applied to the selective taxable subtotal. Only line items whose variant (or any associated product customization field) has `charge_tax = 1` are included in the taxable base. See [Per-Item Taxability](#3-per-item-taxability-charge_tax) and [VAT-Inclusive Pricing & Cross-Border VAT Removal](#vat-inclusive-pricing--cross-border-vat-removal).
  * **Empty Cart Gating**: Accessing `/checkout` with an empty shopping cart is strictly blocked and automatically redirects the user back to the store homepage (`/`).
  * **Mixed-Cart Blocking**: Subscription variants (those with any Stripe or Paddle Price IDs configured) and regular one-time-purchase items **cannot coexist in the same cart**. Attempting to add a subscription item when the cart contains regular items (or vice versa) is blocked immediately with a descriptive flash error. The customer must clear the conflicting item before proceeding. This enforcement happens at `addToCart()` time in `ProductDetails.php`.

* **Checkout Success (`/checkout/success/{id}`)**: Formatted invoice displayed on completion. Include direct secure download links for digital products.

* **Dynamic Categories Mega-Menu**: Recursive navigation dropdown in the public header showing only categories flagged for menus that contain active products.

* **Brands Mega-Menu**: A Livewire component in the main navigation showing all active brands with logos and descriptions. Desktop renders as a floating panel; mobile as an accordion. All links go to `/brands/{slug}`.

* **Hierarchical Breadcrumbs**: Product detail pages display a full recursive category ancestor trail (e.g., `Shop / Electronics / Laptops / MacBook Pro`).

---

### E-Commerce Administration

* **Admin Home Dashboard (`/admin/dashboard`)**: The primary landing page for admins and agents, replacing the support tickets list. It includes:
  * **Summary KPI Cards**: Real-time counters for Total Sales Revenue, Orders Processed, Pending Orders, and Active Customers.
  * **Order Activity & Revenue Trends Widget**: Plots daily sales volume and revenue performance over time using a custom interactive SVG graph.
  * **Orders vs Abandoned Carts Widget**: Shows a breakdown of completed checkout orders versus abandoned guest/registered sessions with conversion rate tracking.
  * **Cart Conversion Funnel**: Maps customer progression from initiated sessions down to cart abandonment categories (Guest vs Registered) and successful checkouts.
  * **Customer Spend Analysis**: Ranks customer spend showing names, emails, total purchase value, and purchase frequency.
  * **Product Sales Performance**: A tabular list of top-selling and bottom-selling items by unit volume and revenue share.
  * **Flexible Date Filters**: All reports support default 30-day views, quick-bubbles for 60d, 90d, 120d, and Year-To-Date (YTD), or custom range selection.

* **Pending Orders Queue (`/admin/pending-orders`)**: Lists all active, non-completed orders requiring fulfillment or processing. Paginated at 25.

* **Product Catalog (`/admin/ecommerce/products`)**: Create and manage products, variants, pricing, weight, and attributes. Paginated at 25.

* **Product Edit Page (`/admin/ecommerce/products/{id}/edit`)**:
  * **Anchor Quick-Nav Bar**: Jump links at the top — _Product Details_, _Prices & Variants_, _Advanced Settings_, and a **"View Product Page"** link to view the product live on the public storefront.
  * **TinyMCE Long Description**: Self-hosted TinyMCE GPL editor with full toolbar (styles, bold/italic, lists, alignment, link, image, supercode, fullscreen) integrated with the custom Supercode source code editor for syntax highlighting, autocomplete, and cursor-position synchronization.
  * **Prices & Variants**: Full variant management with pricing, inventory, download config, and image set management:
    * **Inline Variant Attributes Builder**: An easy-to-use key-value builder for non-technical admins (e.g. Color: Blue, Size: XL) that automatically compiles attributes into JSON format, paired with a raw JSON textarea for advanced users.
    * **Variant Fee (`variant_fee`)**: A currency field to specify an additional selection fee (e.g., +$5.00 for size XXL). The fee is displayed to customers and automatically added to the item's unit price and order total on cart additions.
    * **Wholesale Variant Fee (`wholesale_variant_fee`)**: Added alongside the retail variant fee to specify distinct price surcharges for wholesale customer tiers.
    * **Charge Sales Tax / VAT (`charge_tax`)**: A per-variant toggle (default: **ON / Taxable**) that controls whether this variant is included in the taxable subtotal during checkout. When toggled off, the variant's line-item price is excluded from tax calculations entirely — only variants with `charge_tax = 1` contribute to the taxable base. The toggle is displayed in both the variant edit and create forms and also appears as a **Tax** column (green ● Taxable / grey ● Exempt badge) in the variants list table for quick visual reference.
    * **Payment Processor IDs**: Each variant has a collapsible **Payment Processor IDs** section in both the edit and create forms. This panel stores the gateway-specific price/plan identifiers needed to support **subscription (recurring) billing**. Fields include:

      | Field | Description |
      |---|---|
      | `paddle_sandbox_price_id` | Paddle Price ID for sandbox/test mode (e.g. `pri_sandbox_xxxxxxxxx`) |
      | `paddle_live_price_id` | Paddle Price ID for live/production mode (e.g. `pri_xxxxxxxxx`) |
      | `stripe_sandbox_price_id` | Stripe Test Price ID (e.g. `price_test_xxxxxxxxx`) |
      | `stripe_live_price_id` | Stripe Live Price ID (e.g. `price_xxxxxxxxx`) |
      | `create_new_stripe_product` | Toggle — when **ON**, a new Stripe Product + recurring Price is created automatically at checkout; existing price ID fields are ignored |
      | `stripe_billing_interval` | Billing frequency when creating on-the-fly: `month` (Monthly), `year` (Yearly), or `week` (Weekly) |
      | `stripe_trial_enabled` | Toggle — enables a free trial period for new subscribers |
      | `stripe_trial_days` | Number of free trial days (e.g. `14`) — only active when `stripe_trial_enabled = 1` |

      When any Stripe field is configured, the checkout switches from a standard one-time PaymentIntent to a **Stripe Subscription** flow. When any Paddle price ID is configured, the correct ID (sandbox or live, based on processor mode) is passed to Paddle.js at checkout instead of a custom amount item. The section collapses by default and shows a **Configured** badge in the header when any field is set.

    * **Duplicate Variant**: A one-click duplication tool that clones a variant's attributes, prices, custom S3 credentials, inventory levels, processor price IDs, and associated image sets, assigning a unique duplicated SKU for fast catalog creation.
    * **Download Expiration &amp; Limits**: Variants configured as digital downloads can define a specific download expiration date (defaults to 1 year from creation) and maximum download limit (defaults to 100). These limits are automatically captured in the order details record during checkout.
  * **Product Customization Fields Panel**: A dynamic form builder enabling admins to create required/optional customization fields (text inputs, selects, radio groups, checkboxes, and multi-select checkboxes) with custom sort orders. Supports assigning distinct retail and wholesale price surcharges for each choice. Each field also has a **Tax Price Modifier** toggle (`charge_tax`, default: **ON**) — when a product field is marked as taxable, its price modifier contributes to the taxable total under the OR logic described in [Per-Item Taxability](#3-per-item-taxability-charge_tax).

  * **Advanced Settings Panel**: Configures advanced checkout and purchase rules:
    * **Max 1 per order (`max_qty`)**: Disables quantity adjustment selectors in the item details view, shopping cart page, and slide-out cart drawer, forcing the quantity limit to exactly 1.
    * **Redirect to Checkout (`checkout_redirect`)**: Bypasses the shopping cart page/modal entirely on add-to-cart, immediately redirecting the user to the checkout screen.
    * **Standalone Purchase (`standalone_purchase`)**: Restricts purchase bundling. Forces checkout redirection and blocks adding other items to the cart if a standalone item is present.
    * **Use Dependent Selectors (`dependent_variants`)**: Enables progressive drill-down buttons (e.g. Color then Size) on the public product details view. If disabled, defaults to showing all variations in a flat row list.
    * **Hide Inventory Levels (`hide_inventory_levels`)**: Hides the public stock level indicator (e.g. "12 in stock" or "Out of stock") on the product details page when enabled.

* **Direct URL Image Source**: Each image set has an `image_url_source` flag. When enabled (`1`), the path columns are treated as direct external URLs and served as-is, bypassing all Storage disk resolution. The add-set form has an **"Enter Image URLs"** toggle that switches between file-upload mode and URL-entry mode with live preview. Existing URL-mode sets show inline editable URL inputs and an amber "Direct URL" badge. Resolution is consistent across admin, product detail, and catalog.

* **Categories Management (`/admin/ecommerce/categories`)**: Recursive nested category tree with drag-and-drop sort and menu visibility controls. Paginated at 25. Product count badge opens a right-panel list of assigned products with edit links.

* **Brands Management (`/admin/ecommerce/brands`)**: Brand CRUD with name, slug, description, sort order, website URL, and **brand logo upload** (local disk or S3, optional). Paginated at 25. Product count badge opens a right-panel product list.

* **Inventory & Multi-Warehouse Management (`/admin/ecommerce/inventory`)**:
  * **Warehouse stock level**: Allows defining available stock specifically at local warehouses (`warehouse_stock_level`) with a toggle checkbox to enable/disable warehouse inventory inclusion in calculations (`Available + Warehouse - Reserved = Current Inventory`). By default, it is inactive (0) and defaults to zero.
  * **CSV Bulk Upload**: Supports importing inventory values from a CSV file (format: `SKU|stock_level|warehouse_level|location_id`) to automatically update variant inventory records.
  * **Warehouse Location Management**: Manage multiple warehouse hubs (storing keys, addresses, and ShipStation warehouse carrier IDs) under the *Warehouse Locations* tab in the Shipping Settings CRUD panel.
  * **Location-based Fulfillment Resolution**: Cart and checkout logic resolves the nearest fulfillment location matching the buyer's country and state codes to verify and deduct stock.

* **Dynamic CMS Pages & Security Gates (`/admin/cms-pages`)**: Premium custom pages manager. Features include:
  - **Dynamic Homepage Integration**: The main landing page (`/`) is fully database-driven, pulling meta settings and layouts dynamically from the CMS Page record with ID = 1 using embedded Livewire components (`CmsHomeImage` and `CmsHomeContent`). Deletion of ID = 1 is strictly prevented.
  - **Security Gating**: Supports passcode gating and product-purchase gating (completed order status 7 check) with session authorization memory.
  - **Draft/Active Mode Gating**: Hidden from the public (returns 404) but previewable/viewable by authenticated administrators.
  - **Publishing & Visibility Settings**: Toggle show/hide of page title, author info, or date on the frontend header. Custom author text override.
  - **Flex-Based Column Layouts**: Standardized flex layouts (Single Column, Left Sidebar, Right Sidebar, or Both Sidebars) with matching conditional TinyMCE editors in the workspace.
  - **Asset Integration**: Custom CSS, JavaScript (auto-navigated reloaded script blocks), header images, and background images.
  - **Failsafe Revisions & Autosaves**: 10-minute idle background autosaving, visual revisions previewing, one-click restoration, and automatic pre-restore backup generation to prevent accidental replacement data loss.
  - **Pages & Posts Workspace**: CMS management workspace supports dynamic classification into Pages (1) and Posts (2) linking to parent-child lookup table `cms_page_types`, with additional support for custom sorting (`custom_sorting`), and selection options for many-to-many categorizations (`cms_pages_categories`) and tags (`cms_pages_tags`).
  - **CMS Categories & Tags CRUD Panels**: Complete admin management panels for adding, updating, and deleting categories (`/admin/cms-categories`) and tags (`/admin/cms-tags`), with direct external links to preview their public landing pages.
  - **Cross-Table Unique Slug Validation**: Prevents any CMS Page, Category, or Tag from sharing a duplicate slug.
  - **Category & Tag Archives Landing Pages**: Fully styled public landing pages `/category/{slug}` and `/tag/{slug}` displaying active, non-gated content sorted by date and custom sorting priority.
  - **Featured Image Upload & Custom S3 Storage**: Fully integrated featured image upload setting in "Publishing Info" tab supporting local uploads, global S3 bucket storage, and custom S3 credentials configuration.
  - **CMS Page Duplication**: One-click duplication action in admin pages index table, cloning all parameters and relationships with a unique suffix-tagged slug.
  - **Helpful Rating Widget**: Interactive thumbs up/down rating widget with session lock voting prevention displayed on front-end pages when `Hide Page Ranking` is toggled off (0).
  - **Collapsible Navigation Sidebar**: The left-hand CMS tab menu (Page Details, Publishing Info, Security & Gating, Layout & Media, Custom CSS & JS, Revisions History) can be collapsed or expanded at any time via a toggle button. When collapsed, the content area automatically expands to full width (`col-span-12`), maximising screen real estate for the TinyMCE editors.
  - **Inline Image Upload for TinyMCE**: Drag-and-drop or paste images directly into any TinyMCE editor pane. Images are uploaded via `POST /admin/cms-pages/upload-image` (handled by `CmsImageUploadController`) and stored under `/storage/app/public/cms_inline/`. The response returns a site-relative URL (`/storage/cms_inline/…`) that renders immediately inside the editor, regardless of `APP_URL` port configuration.
  - **Enhanced TinyMCE Editor Configuration**: All CMS editors (main body, left sidebar column, right sidebar column) now share a production-grade TinyMCE setup matching a typical custom-tooled publishing environment:
    - **Plugins**: `preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media table charmap advlist lists wordcount help quickbars emoticons supercode`
    - **Menubar**: `edit view insert format tools table`
    - **Toolbar (sliding mode)**: Full suite including `fullscreen`, `undo/redo`, `supercode` (enhanced source editor), inline formatting, font family/size/line-height, text & background colour, alignment, indent/outdent, lists, charmap, emoticons, preview, image/media/link/anchor, and RTL/LTR direction controls
    - **Quick Selection Toolbar**: `bold | italic | quicklink`
    - **Quick Insert Toolbar**: Disabled (`false`) to avoid accidental snippet insertion
    - **Context Menu**: `link image`
    - **Tailwind CSS Preview**: Tailwind 2.x CDN stylesheet loaded inside the editor iframe (`content_css`) so that Tailwind widget snippets render accurately while editing
    - **Branding & Promotion**: Both disabled
    - **Supercode Source Code Editor**: Replaces the basic code view with an integrated syntax highlighter (Ace Editor) in Monokai theme, including:
      - **Syntax Highlighting & Line Numbers**: Clean, IDE-like dark theme code representation.
      - **HTML Tag Autocomplete**: Automatic tags and attributes suggestions as you type.
      - **Cursor & Selection Sync**: Auto-detects text highlighting/cursor position in the WYSIWYG pane using DOM bookmark tokens, opening the code view positioned precisely at that spot.
  - **HTML Widget Library Drawer**: A sticky, slide-out panel on the right edge of the admin CMS editor that houses reusable Tailwind HTML snippet cards. Open/close it at any time with the **Widget Library** toggle button. Each card can be dragged directly into any TinyMCE editor frame or clicked to insert at the current cursor. Current widgets: Callout Banner, FAQ Accordion, 2-Column Features Grid, and CTA Button.
  - **Display Plugins Drawer**: A separate, sticky, slide-out drawer on the right edge of the editor (staggered at `top-[60%]` to prevent overlaps with the HTML Widget Library tab at `top-[40%]`). It queries all active display-type plugins (e.g. Slideshow, Featured Items) and allows admins to drag and drop shortcode tokens directly into the TinyMCE canvas, or click any card to append the shortcode block at the bottom of the editor automatically. Includes descriptive warning-style usage instructions at the top.
  - **Link Generator Drawer**: A sticky, slide-out drawer on the right edge of the editor (staggered at `top: calc(60% + 20px + 100px)` to avoid overlaps with Display Plugins and HTML Widgets). It provides a tabbed interface containing live search inputs for Products, Brands, Categories, and CMS Pages. Admins can search database records in real-time, click a record to select it, and copy three link formats: (1) Full public URL, (2) HTML Anchor link, and (3) Primary styled Tailwind CTA link button. Includes copy-status badge verification.
  - **Header & Title Customizations**: Layout-level settings situated under the Layout & Media tab:
    - **Alternate Page Title**: Overrides the main page title on the public frontend template if specified.
    - **Page Title Alignment**: Flexbox-based vertical and horizontal alignment options (e.g. top-left, middle-left, top-center, middle-center, etc.) to position and align the page header title text.
    - **Custom Page Title CSS**: Custom CSS style blocks loaded in a `<style>` block in the public page `<head>`.
    - **Slideshow Plugin Integration**: An input placeholder supporting slideshow plugin shortcodes (e.g. `[plugin:slideshow-2026]`). Entering content compiles a zero-padding full-width block directly above the page header/title section on the public frontend.
    - **Minimum Header Height**: Custom height string (e.g. `320px`, `400px`, `50vh`) applied as the minimum height (`min-height`) of the public page header banner. Defaults to `'320px'`.

* **Orders & Refunds (`/admin/ecommerce/orders`)**: Order lookup and partial refund portal. Paginated at 25. Includes:
  - **Order Comments Block**: Renders customer comments directly above the "Items Purchased" section, displaying `"no customer comments for this order"` when empty.
  - **Payment History logs**: Structured log list at the bottom of the details page showing the date, method, status, authorization code, and amount of all order payments.

* **Discounts CRUD Dashboard (`/admin/ecommerce/discounts`)**: Dedicated hub listing all store discounts (Coupons, Item Specifics, Category/Brand Promos, BOGOs, and Customer Preferred discounts). Supports search, pagination (25), and deletion controls.
* **Discount Creator/Editor (`/admin/ecommerce/discounts/{id}/edit`)**: Clean creation and editing options supporting custom S3 override bucket inputs, date range picking, live search selectors for products, brands, and categories, and buy-X-get-Y setups.
* **Storewide Discounts Configuration (`/admin/ecommerce/discounts/config`)**: Global manager to customize priority sequencing, tax logic, wholesale exclusions, and stacking constraints (One At A Time vs Stackable).

---

### Product Reviews & Ratings System

A fully-featured, moderatable reviews and comments system is integrated for storefront items. 

* **Storefront Ratings & Feedback**:
  - **Clipping Fractional Stars**: Average ratings are computed dynamically and rendered using a precise SVG star track where decimal scores (e.g., `4.3`) are visually clipped down to the exact percentage.
  - **Sorting Options**: Customers can sort reviews by: Most Recent (default), Highest Rated, or Lowest Rated.
  - **Interactive Star Form**: Allows users to rate items using an Alpine.js-powered star-hover component, providing Name (required), Location (optional), and Comments (optional).
  - **XSS Sanitization Shield**: Strips all HTML and script elements from user-submitted name, location, and comments before database storage.
  
* **Global & Product Configuration**:
  - **Global Setting controls (`/admin/settings`)**: Turn the review system on/off globally.
  - **Third-Party Review Snippets override**: Pasting code into the settings text area automatically disables the native reviews form and renders the third-party JavaScript widget at the bottom of the item page.
  - **Product-Specific Toggles**: Turn reviews on/off per individual product on the admin product details page.

* **Admin Moderation & CRUD**:
  - **Global Moderation CRUD (`/admin/ecommerce/reviews`)**: View, search, and moderate reviews across the entire site. Supports filtering by product and approval status.
  - **Per-Product reviews list**: Embedded table inside each product edit form, allowing admins to approve, unapprove, edit, or delete reviews inline.
  - **Average Rating Recalculation**: Automates rating score recalculation (`reviews_rating`) on the parent `Product` model whenever reviews are approved, unapproved, or deleted.

---

### Image & Asset Storage


| Mode | Description |
|---|---|
| **Local Storage** | Saved to `public` disk under `storage/` |
| **Global S3** | Saved to the AWS S3 bucket defined in `.env` |
| **Custom S3 Credentials** | Per-variant alternate bucket, region, and keys |
| **Direct External URL** | `image_url_source = 1` — path rendered as-is, no Storage lookup |

**CDN Support**: Global `CDN_URL` env variable or per-variant CDN override for CloudFront/Cloudflare.

**Performance Indexes**: `products.seo_slug`, `orders.order_invoice_no`, `orders.order_external_id`, `product_categories_assignments(category_id, product_id)`, `product_brand_assignments(brand_id, product_id)`.

---

# Discount Configuration & Calculation Engine

A highly flexible, priority-based discount processing engine handles pricing adjustments dynamically across the storefront shopping cart, sliding drawer cart, checkout coupon code input forms, and final order placement.

### 1. Core Features & Stacking Logic
* **Priority Sequence Execution**: Deductions are applied dynamically in sequence to prevent conflict:
  * **Individual Item Discounts**: `Category & Brand Level` -> `Item Specific` -> `Special Sale Price` -> `Item Quantity Breaks` -> `Wholesale Price`.
  * **Order Sub-Total Discounts**: `Coupon Code` -> `General Order Discount` -> `Preferred Customer Discount` -> `New User Promo`.
* **Stacking Configuration**: Global switch in the Admin panel configures sub-total discounts to apply **"One At A Time"** (highest value wins) or **"Stacked"** (cumulative deductions).
* **Tax and Shipping Exclusions**: Supports calculation order options:
  * Deducts order-based discounts **before** calculating sales tax (8.25%) and shipping charges.
  * Optionally allows promotional coupons to grant **Free Shipping**.
* **Coupon Filter Validation**: Coupons are validated against order minimum/maximum totals, weight constraints, quantity ranges, and wholesale customer settings on entry, displaying error feedback for unmet criteria.
* **Dynamic Auto-Rejection**: If cart updates (e.g. quantity reduction or removing items) cause the order subtotal or details to fall outside of coupon constraints, the calculation engine automatically rejects the coupon and clears it from the session.

### 2. Brand or Category Rules
* **Brand or Category Specific Promos**: Evaluates rules specifically targeting a brand or category. Category-level discounts check recursive nested ancestor relations, cascading down to subcategories or nested grandchild categories automatically.
* **Target Brand & Category Live Search**: Selectors in the admin panel are converted into debounced, dynamic Live Search inputs to handle large datasets of brands and categories.

### 3. Buy X Get Y (BOGO)
* **Target Isolation**: Evaluates matches for Buy X Get Y triggers. Once a deal triggers, the target free/discounted item is locked (`is_bogo_target = true` in cart metadata), which automatically blocks manual quantity adjustment in the storefront cart list.
* **BOGO Trigger & Target Live Search**: Product selection fields in BOGO settings utilize debounced Live Searches to dynamically find and select products by title.

### 4. Dynamic Promo Info on Storefront Detail Page
* **Visual Promotions Display**: If checked, custom promotional text entered via the TinyMCE editor will appear on the public product details view under the short description (above the pricing layout).
* **Targeted Matching**: The promotional banner displays dynamically on the storefront only if:
  * The product ID matches the Item-Specific target product.
  * The product is either the trigger item `Buy X` or the discounted target `Get Y` in an active BOGO rule.
  * The product matches the Brand or Category target.
* **Rich TinyMCE Editor**: The edit view integrates a local copy of TinyMCE with custom configurations allowing admins to write fully formatted HTML promo banners for item-specific, BOGO, or brand/category discounts.

### 5. Wholesale Constraints
* Wholesale customer tiers (`role_id = 2`) receive wholesale-specific prices. Depending on storewide configurations, the engine can be set to bypass coupons or standard promotional discounts for wholesale accounts.

---

# Dynamic Email Notifications System

The platform features an admin-managed dynamic transactional email notification engine that overrides default system notifications with custom, responsive HTML layouts designed dynamically inside the administration panel.

### 1. Database & Core Architecture
* **Trigger-Based Classifications (`email_template_types`)**: System triggers are predefined in the database (Order Confirmation, Shipment Notification, Download Reminders, User Registration, Password Reset, Ticket Submitted, Reply Received, Ticket Status Update).
* **Profile Management (`email_templates`)**: Multiple email templates (profiles) can be created per notification type. The engine checks the active template configuration to override default mailings.
* **Unified Template Renderer (`DynamicTemplateMail`)**: Custom transactional mail is generated from a responsive template ([template.blade.php](file:///C:/Sites/laravel-gemini/resources/views/emails/dynamic/template.blade.php)) that merges custom HTML, banner images, user salutations, signatures, disclaimers, and copyright text on the fly.
* **Merge Tag Variable Replacement**: All text and HTML blocks are parsed dynamically to replace custom variables (`{{order_id}}`, `{{customer_name}}`, `{{reset_url}}`, `{{order_items_table}}`, `{{download_links}}`, etc.).

### 2. E-Commerce Order Details & Stats Integration
* **Storefront-Mirrored Summary Box**: The Order Confirmation (`order_confirmation`), Shipment Notification (`order_shipment`), and Download Reminder (`download_reminder`) templates automatically parse the `{{order_items_table}}` variable to inject a detailed summary block mimicking the public checkout success page. This block features:
  * **Order Info Header**: Displays the order invoice number, date, and current fulfillment status.
  * **Formatted Items Table**: Lists all purchased items, quantities, and individual price totals with custom green/blue badges identifying them as physical *Shippable Items* or *Digital Downloads*.
  * **Tax & Shipping Breakdown**: Displays clear breakdowns of the subtotal, promotional discount deductions, 8.25% sales tax, shipping fee, and the final total charged.
  * **Postal Shipping Address**: Formats and displays the customer shipping address for shippable orders.
* **Interactive Download Reminders**: Digital download emails parse the `{{download_links}}` tag to inject stylized, green callout boxes containing direct secure download buttons for each digital item in the order.

### 3. Admin Workspace Features (`/admin/email-templates`)
* **Templates Workspace**: View active status profiles grouped by triggering notification category type.
* **Profile Cloning & Replication**: Fast duplication tool to replicate existing template profiles with a single click.
* **Rich TinyMCE Editors**: Integrates self-hosted TinyMCE rich text editor panels for customizing top headers, main messages, and footers.
* **Live In-Browser Preview**: An interactive Alpine.js preview modal that displays the rendered HTML email output instantly with simulated mock dataset values.
* **Override Logic Controls**: Toggling active state on a template deactivates other profiles of the same type.
* **Fulfillment & Reminder Actions**:
  * **Fulfillment Trigger**: Shipping confirmation emails are sent immediately when an order is flagged as shipped in the details console (via the "Mark Shipped" button or by selecting status `2` in the status dropdown).
  * **Download Reminder Trigger**: Admins can trigger download reminder emails on-demand for digital orders using a **"Send Download Reminder"** button inside the order fulfillment card.

---

# Support & Helpdesk Features

### Helpdesk & Ticket Submission
* **Submit Tickets (`/tickets/create`)**: Subject, category tags, description, and file attachments up to 5MB.
* **Customer Dashboard (`/dashboard`)**: Unified control center for customer roles (retailers and wholesale accounts, role IDs 1 and 2). Features a premium tab-switched workspace (`?tab=tickets`, `?tab=orders`, `?tab=downloads`):
  * **Support Tickets Tab**: View, filter, and track past and active support threads.
  * **Order History Tab**: View completed checkouts. Expandable row accordion shows inline detailed invoices (item breakdowns, price markdowns, tax, shipping, and fulfillment status).
  * **Digital Downloads Tab**: Lists active digital attachments associated with paid orders, detailing expiration dates, remaining download limit counters, and secure links.
  *(Admins and agents landing on `/dashboard` are automatically redirected to the Admin Home Dashboard `/admin/dashboard`)*.
* **Conditional Layout Navigation**: Logged-in customers (roles 1 and 2) see the public storefront header showing only the logo and tabs for *Tickets*, *Orders*, and *Downloads*, with profile controls and a sign-out button. Admins and staff roles (3, 4, and 5) continue utilizing the backoffice administration sidebar.
* **Secure Guest Access (`/tickets/view/{token}`)**: Tokenized links for unauthenticated ticket viewing and reply.

### Staff Operations & Ticket Queue
* **Admin Ticket Console (`/admin/tickets`)**: Search, update status, assign agents, post replies, delete tickets. Paginated at 25.
* **Agent Queue (`/admin/assigned-tickets`)**: Focused view of the current agent's assigned tickets.

### Knowledge Base (KB)
* **Public KB (`/kb`)**: Category structure, search, and helpfulness upvote/downvote ratings.
* **KB Admin**: Article CRUD, sort orders, draft states, and an AI Article Writer (OpenAI).

---

# User Roles & Permissions

| Role | ID | Access |
|---|---|---|
| **Customer** | 1 | Storefront, cart, checkout, ticket submission, ticket history, order history, downloads |
| **Wholesale** | 2 | Same as Customer + wholesale pricing tier |
| **Admin** | 3 | Full admin access. Can manage all tickets, users, and settings. |
| **Order Processor** | 4 | Can view and edit orders in the admin area.. |
| **Ticket Manager** | 5 | Can view and reply to tickets in the admin area. |

### Upgrading from Support Ticketing App
If upgrading from the standalone helpdesk application:
* The migrations automatically convert any existing users with `role_id = 2` (Agent/Team Member) to `role_id = 5` (Ticket Manager) so their administrative ticket management capability is preserved.
* The `role_id = 2` key is now reserved exclusively for the **Wholesale** customer role.
* All references to the obsolete `user_type` field have been removed in favor of `role_id`.

## Dynamic Blade & Livewire Parser

The platform includes a built-in content compilation engine to dynamically parse raw content saved in the database before rendering it on the front-end. This enables the usage of Laravel Blade directives, authorization gates, and Livewire conditions inside CMS pages and product descriptions.

### How It Works
1. **Intermediary Content Parser (`ContentParserService`)**:
   - Compiles database-driven HTML and text strings using `Blade::render()`.
   - Automatically provides session variables, currency contexts, and the authenticated `user` object to the execution scope.
   - Includes full try/catch safety: if a syntax error or malformed Blade code is saved in the database, the parser catches the exception, logs it, and falls back to rendering the raw text (preventing 500 crashes).
2. **CMS Pages Integration**:
   - Parses the main body `content` field of CMS pages.
   - Evaluates dynamic blocks, such as:
     ```html
     @auth
         <p>Welcome back, {{ auth()->user()->name }}!</p>
     @else
         <p>Sign in to unlock wholesale rates.</p>
     @endauth
     ```
3. **Products Integration**:
   - Dynamically compiles both the `short_description` and `long_description` fields of products.
    - Allows inline member benefits, product promotions, and conditional text blocks based on the customer's active role.

---

## Reusable List Menus & Shortcodes

The platform includes a modernized dynamic List Menu builder that allows administrators to create and manage reusable, drag-and-drop ordered lists of links. These lists can be embedded globally across public pages, headers, and footers using simple square-bracket shortcode notation (`[list:{id}]`).

### 1. Database Architecture
The database schema consists of two generic tables:
- **`cms_list_menus`**: Holds list menu records with:
  - `id`: Auto-incrementing identifier (used as the `{id}` in the `[list:{id}]` shortcode).
  - `name`: Human-readable name.
  - `custom_css`: Stylesheet code applied locally only when this list is rendered.
- **`cms_list_menu_items`**: Holds items associated with each menu:
  - `cms_list_menu_id`: Foreign key referencing the parent list menu.
  - `list_item`: Text content containing text, HTML, or shortcodes.
  - `sort_val`: Floating-point sorting rank for drag-and-drop ordering.

### 2. Admin Management Panel
The list builder is located under the **CMS** tab dropdown in the admin navbar and on the left sidebar of all CMS management pages.
- **Access Gating**: Strictly restricted to users with `role_id = 3` (Admin). Standard users or other team members receive a `403 Forbidden` response.
- **Reordering (Drag & Drop)**: Integrates SortableJS using a reactive Alpine.js wrapper. Dragging items by their grab handle instantly updates and saves sorting offsets (`sort_val`) in the database.
- **Inline Saving**: Displays a green inline **Save** button next to each item's text input that becomes visible immediately client-side (using Livewire's `wire:dirty` target detection) when edited. This allows administrators to save specific line item changes independently.
- **Creation Safety Resave**: Adding a new item to the menu triggers a pre-validation and save of all other items to the database first, ensuring no unsaved client-side modifications are lost.
- **Dynamic Link Generator Sidebar**: A slide-out panel that allows live-searching across the database for:
  - **CMS Pages**: Generates `[page:id]` shortcodes.
  - **Products**: Generates `[product:id]` shortcodes.
  - **Categories**: Generates `[category:id]` shortcodes.
  - **Brands**: Generates `[brand:id]` shortcodes.
  *Note: Results are capped to a maximum of 25 total records per query to ensure high-performance database execution on environments with tens of thousands of items.*
- **Drag-and-Drop Text Ingestion**: Generated shortcode badges set draggable HTML5 tags. You can click to copy, or drag them directly and drop them into any list item input box.



### 3. Shortcode Syntax Rules
List items support a combination of raw HTML and specialized shortcodes:
* **CMS Page**: `[page:id]` or `[page:id label="Custom Anchor Text"]`
* **Product Catalog**: `[product:id]` or `[product:id label="Custom Anchor Text"]`
* **Product Category**: `[category:id]` or `[category:id label="Custom Anchor Text"]`
* **Brand Storefront**: `[brand:id]` or `[brand:id label="Custom Anchor Text"]`
* **Standard Plugins**: `[plugin:slug params]` (e.g. `[plugin:brands display=list]`)

### 4. Parsing Pipeline & Execution Order
Shortcode processing is split across **two separate pipelines** that operate independently:

#### Pipeline A — `ProcessShortcodes` Middleware (list menus only)
Runs as an HTTP middleware on all public frontend HTML responses. It handles only the list-menu shortcode family:
1. **`[list:N]` Expansion**: Scans the final HTML response for `[list:(\d+)]` patterns and calls `ListMenuRenderer::render()`.
2. **Internal Item Compilation**: For each matched list menu, the renderer resolves each item's internal shortcodes (`[page:N]`, `[product:N]`, `[category:N]`, `[brand:N]`) into `<a>` tags, wraps them in `<ul>`/`<li>` structure, and prepends the menu's custom CSS block.
3. **Embedded Plugin/Download Shortcodes in Items**: After the `[page/product/category/brand]` pass, `ListMenuRenderer::parseItemContent()` calls `ShortcodeProcessor::process()` — so any `[download:N]` or `[plugin:slug]` shortcodes _embedded inside a list menu item_ are also resolved here.
4. **Post-Expansion Resolve**: Any standalone `[page:N]`, `[product:N]`, `[category:N]`, or `[brand:N]` shortcodes placed _directly_ in page content or the site footer layout (outside a list menu) are resolved in a second pass via `ListMenuRenderer::parseItemContent()`.

> **Important:** `[download:N]` and `[plugin:slug]` shortcodes placed directly in CMS page body content are **not** handled by this middleware. They are resolved exclusively by Pipeline B below.

#### Pipeline B — `ContentParserService::parse()` (CMS page content & product descriptions)
Called explicitly by CMS page view components and product detail pages before rendering content to the browser:
1. **`Blade::render()`**: Compiles any inline Blade directives (`@auth`, `{{ }}`, etc.) in the stored content.
2. **`ShortcodeProcessor::process()`**: Runs immediately after Blade compilation in two ordered passes:
   - **Pass 1 — `[download:N]`**: `processDownloads()` runs first, resolving all CMS download shortcodes to their HTML output (image, video player, audio player, or styled link).
   - **Pass 2 — `[plugin:slug]`**: Plugin shortcodes are expanded after downloads to prevent any ambiguity between numeric IDs and plugin slugs.

### 5. Shortcode Support Matrix

The table below shows which shortcode types work in each content field and which pipeline delivers them. Both pipelines run on every public page request — Pipeline A over the final HTML, Pipeline B at content-parse time — so fields that go through Pipeline B automatically also benefit from Pipeline A.

| Content Field | `[list:N]`<br>`[page:N]`<br>`[product:N]`<br>`[category:N]`<br>`[brand:N]` | `[download:N]` | `[plugin:slug]` | Blade `@auth`<br>`{{ }}` |
|---|:---:|:---:|:---:|:---:|
| **CMS Page — main body** | ✅ Pipeline A | ✅ Pipeline B | ✅ Pipeline B | ✅ Pipeline B |
| **CMS Page — left sidebar** | ✅ Pipeline A | ✅ Pipeline B | ✅ Pipeline B | ✅ Pipeline B |
| **CMS Page — right sidebar** | ✅ Pipeline A | ✅ Pipeline B | ✅ Pipeline B | ✅ Pipeline B |
| **Product short description** | ✅ Pipeline A | ✅ Pipeline B | ✅ Pipeline B | ✅ Pipeline B |
| **Product long description** | ✅ Pipeline A | ✅ Pipeline B | ✅ Pipeline B | ✅ Pipeline B |
| **List menu items** | ✅ Pipeline A | ✅ Pipeline A¹ | ✅ Pipeline A¹ | ❌ |
| **Site footer / header layouts** | ✅ Pipeline A | ❌ | ❌ | ❌ |

> ¹ `[download:N]` and `[plugin:slug]` shortcodes embedded **inside a list menu item** are resolved because `ListMenuRenderer::parseItemContent()` calls `ShortcodeProcessor::process()` internally as part of Pipeline A.

#### How to read this table

- **Pipeline A** (`ProcessShortcodes` middleware) — scans the **final rendered HTML** of every public web response. Works on every field whose output reaches the browser, including sidebars, footers, and headers. Resolves `[list:N]`, `[page:N]`, `[product:N]`, `[category:N]`, and `[brand:N]`.
- **Pipeline B** (`ContentParserService::parse()`) — called explicitly per field, before the field's content is inserted into the page template. Resolves Blade directives, `[download:N]`, and `[plugin:slug]`. Only fields that use a `parsed_*` model accessor go through this pipeline.

#### Model accessors that enable Pipeline B

| Model | Accessor | Raw column |
|---|---|---|
| `CmsPage` | `$page->parsed_content` | `content` |
| `CmsPage` | `$page->parsed_left_col` | `left_col` |
| `CmsPage` | `$page->parsed_right_col` | `right_col` |
| `Product` | `$product->parsed_short_description` | `short_description` |
| `Product` | `$product->parsed_long_description` | `long_description` |

To add Pipeline B support to any new content field, create a matching `getParsed{FieldName}Attribute()` method on the model that returns `ContentParserService::parse($this->field_name)` and use the accessor in the blade template instead of the raw column.

---

## Custom Hashing & Login Security

The application supports an optional custom security layer for authentication:
* **Custom Security Flag (`CUSTOM_LOGIN_SECURITY`)**: When this `.env` variable is defined and not null, standard Laravel password hashing is overridden.
* **Unique API-style Tokens**: New user registrations and admin creations automatically generate two unique random strings (`user_token_1` and `user_token_2`).
* **Ripemd256 HMAC Hashing**: Passwords for users are computed and stored as:
  ```php
  $password = hash_hmac('ripemd256', $password, $user_token_1);
  ```
* **Seamless Authentication Fallback**: Transparently validates credentials inside login and confirmation requests without interrupting standard auth guards or OAuth adapters.

---

# Shipping, Sales Tax & Surcharge Handling

The application implements a generic, highly configurable shipping and tax matrix system:

### 1. Shipping Calculations & Flat-Rate Lists
* **Dynamic Grid Flat-Rate**: Calculates shipping amounts dynamically based on flat-rate ranges mapped to active States/Provinces or Countries. Formats support weight, subtotal, or item counts using the range string syntax: `Min-Max=Price,Other=Default` (e.g. `0-5=5.00,5-10=8.00,Other=15.00`).
* **Custom Flat-Rates Overrides**: Allows administrators to bypass the range matrix grids and configure a flat listing of selectable options (e.g. *Standard Ground*, *Express 2-Day*, *Overnight Air*) dynamically loaded based on domestic or international scopes.
* **Carrier Plugins**: Integrates mock realtime API quote toggles for **UPS**, **FedEx**, and **USPS**, as well as **Local Pickup (Free)** options.
* **Free Shipping Coupons**: Checkout automatically waives all shipping costs if a promotional discount coupon containing the `free_shipping = true` constraint is applied to the cart.

### 2. Tax Configurations & VAT Calculations
* **US Sales Tax & Canadian Provincial VAT**: Supports state-by-state sales tax rates for the US, and provincial VAT calculations for Canada.
* **International Country VAT**: Charges country-level custom VAT rates for international destinations when the `charge_vat` toggle is active.
* **Merchant-Location-Aware VAT Engine**: When the merchant's home country (configured in the Shipping & Taxes console) is a non-US / non-Canadian country, the platform activates **VAT-inclusive pricing** across the entire storefront. All displayed prices include VAT computed from the merchant's home country rate. Cross-border orders (US or Canadian buyers) automatically have the embedded VAT stripped at checkout. See [VAT-Inclusive Pricing & Cross-Border VAT Removal](#vat-inclusive-pricing--cross-border-vat-removal) for full details.
* **Dynamic Tax Label**: The tax line on the order review, checkout success, and email receipts reads *Sales Tax* (US), *GST/HST* (Canada), or *VAT* (all other countries) — determined automatically from the buyer's shipping country.
* **Selective Taxable Subtotal**: Tax is calculated only on the portion of the order that is taxable — not the entire cart subtotal. See [Per-Item Taxability](#3-per-item-taxability-charge_tax) below.
* **Automated Hook**: Includes a clean third-party API plugin structure inside `TaxCalculationService.php` to immediately hook up automated tax calculation engines (e.g. Avalara).

### 3. Per-Item Taxability (`charge_tax`)

Each **product variant** and each **product customization field** carries an independent `charge_tax` flag (stored as `tinyint`, default `1` = taxable). This allows fine-grained control over which items in an order are subject to Sales Tax or VAT.

#### How the OR Rule Works

When a customer adds an item to their cart, the system evaluates taxability using an **OR rule**:

```
item_taxable = 1  if  (variant.charge_tax = 1)  OR  (any product_field.charge_tax = 1 for this product)
```

This means: if *either* the variant itself is marked taxable *or* any of its associated product builder fields is marked taxable, the cart item is taxable. Both must be `0` for the item to be tax-exempt.

#### Tax Calculation with Mixed Carts

During checkout (`OrderReview::calculateTotals()`), the engine builds a **selective taxable subtotal** by summing only the line items where `item_taxable = 1`:

```
taxableSubtotal = Σ (item.final_price × item.qty)  for items where item_taxable = 1
```

The `TaxCalculationService` receives only this taxable portion — not the full cart subtotal. Non-taxable items are included in the order total but contribute **zero** to the tax base.

#### Where `charge_tax` Is Stored and Propagated

| Stage | Table / Field | Notes |
|---|---|---|
| **Product configuration** | `product_variants.charge_tax` | Set by admin in product editor (toggle, default ON) |
| **Product field configuration** | `product_fields.charge_tax` | Set per customization field (toggle, default ON) |
| **Add-to-cart** | `shopping_cart_log.item_taxable` | Computed from OR rule at cart-add time; persists through session |
| **Order placement** | `order_details.item_taxable` | Copied from cart item at checkout; permanently recorded on the order |

#### Admin UI

* **Variant edit / create form**: A green/grey toggle switch labeled **"Sales Tax / VAT"** appears next to the "Requires Shipping" selector. Default is green (Taxable). Toggle off to mark the variant as Tax Exempt.
* **Variants list table**: A **Tax** column shows a color-coded badge (green ● Taxable / grey ● Exempt) for every variant at a glance without entering edit mode.
* **Product customization fields form**: A **"Tax Price Modifier"** toggle appears alongside the Required Field checkbox. Default is ON (taxable). Toggle off if the price modifier should not be taxed.

### 4. Order-Level Handling Charges
* **Trigger Surcharges**: Adds dynamic order-handling surcharges to the billing summary.
* **Criteria Filter Rules**: Automatically triggers fees based on custom combinations of order conditions: minimum/maximum order subtotal, minimum total package weight, or minimum items count.

### 5. Checkout Address Flow Gating & Order Comments
* **Interactive Country & State Selectors**: Country choices default to the United States first, with Canada and the United Kingdom positioned next, followed by an alphabetical list of all active countries. State/province select boxes show conditionally and mandate selection if US or Canada is selected.
* **Social OAuth Account Gating**: Social provider accounts registering with empty profiles are gated at checkout and forced to provide complete shipping address details even for digital-only carts to secure profiles.
* **Order Comments**: Customers can submit order comments/notes during checkout (if enabled globally). Comments are saved to the order, printed on invoice pages, and appended to receipt emails.

---

# Database Seeders & Demo Accounts

Default credentials from the main seeder:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@support.local` | `SampleUser12345#` |
| **Agent / Team Member** | `agent@support.local` | `SampleUser12345#` |
| **Customer** | `user1@example.com` | `SampleUser12345#` |

### DevEcommerceSeeder

> **Not distributed with the repo.** Local development and QA only.

```bash
php artisan db:seed --class=DevEcommerceSeeder
```

| Data | Count |
|---|---|
| Brands | 75 |
| Top-level categories | 80 |
| Subcategories | 30 |
| Products | 500 |
| Variants (1–3 per product) | ~1,000 |
| Image sets | ~1,000 (all `image_url_source = 1`, picsum.photos URLs) |

Safe to re-run — cleans up previous `DEV-` prefixed data before inserting.

---

# Warehouse Stock & Inventory Control

The application features advanced inventory tracking controls, bulk CSV operations, automated webhook integration, and full multi-warehouse (locations) fulfillment support.

### 1. Warehouse Stock Calculations
* **Dual Stock Tracking**: Product variants track both standard store shelf stock (`Available Stock`) and auxiliary external stock (`Warehouse Stock Level`).
* **Optional Calculation Toggle**: An administrator can toggle a checkbox (`use_warehouse_stock = 1`) to include the warehouse level in storefront stock calculations.
* **Calculated Inventory Formula**:
  * **Toggle On**: `Available Stock + Warehouse Stock - Reserved Stock = Current Inventory Number`
  * **Toggle Off**: `Available Stock - Reserved Stock = Current Inventory Number`
* **Real-Time Calculator**: The stock control panel displays dynamic real-time totals on edits before saving to ensure logistics accuracy.

### 2. Bulk CSV Import Updates
* **Format**: Supports comma-separated or pipe-separated imports mapping `SKU|stock_level|warehouse_level|locationid`.
* **Automatic Matching**: Scans and identifies variant entries matching the SKU code, updating the corresponding values:
  - `stock_level` $\rightarrow$ `quantity_available`
  - `warehouse_level` $\rightarrow$ `warehouse_stock_level`
  - `locationid` $\rightarrow$ `location_id`
* **Interface**: Accessed directly inside the Stock Control panel at `/admin/ecommerce/inventory`.

### 3. Automated Inventory Webhook API
* **Endpoint**: `POST /webhooks/inventory-update` (CSRF protection bypassed automatically).
* **Security**: Enforces request authentication via a custom header `X-Inventory-Webhook-Token`, a bearer token, or a query parameter `api_token` matching the environment's `INVENTORY_WEBHOOK_SECRET` value.
* **Payload Structure**:
  ```json
  {
    "sku": "SKU-AAA-123",
    "stock_level": 150,
    "warehouse_level": 80,
    "use_warehouse_stock": true,
    "location_id": 2
  }
  ```
* **Response**: Returns a JSON representation of the updated inventory data and the new calculated total.

### 4. Multi-Warehouse (Locations) Architecture & Fulfillment
* **Locations database (`warehouse_locations`)**: A dedicated table stores warehouse records containing location names, unique codes (e.g., `US-WH-1`), street addresses, state/country regional parameters, status flags, and third-party fulfillment mapping identifiers (like ShipStation carrier IDs).
* **One-to-Many Relationship**: The `ProductVariant` model defines a `HasMany` relationship (`inventories()`) to `ProductInventory`, permitting multiple distinct inventory records to coexist per SKU. Preserves a backwards-compatible `inventory()` `HasOne` fallback wrapper for primary/fallback location metrics.
* **Fulfillment Origin checks**: Checks on the product details page, catalog cards, shopping carts, and slidecarts fetch stock levels dynamically via `$variant->getStockForFulfillment($countryCode, $stateCode)`.
  * Checks if there's an active warehouse location matching the buyer's shipping region (exact state/country code lookup).
  * If found, returns the available stock level specific to that fulfillment origin.
  * If no specific regional match exists, defaults to aggregating (summing) available stock across all active warehouses.
* **Location-based Deduction**: During checkout execution (`OrderReview.php`), stock deduction resolves and decrements quantity from the location closest/matching the customer's shipping address before falling back to the primary store warehouse.
* **Admin Settings Control**: A new **Warehouse Locations** sub-panel is integrated into the Shipping Console (`/admin/ecommerce/shipping`), giving administrators a complete CRUD interface to add, edit, or delete fulfillment warehouses.

---

# Digital Downloads & Storage Settings

### Secure Downloads & Dynamic Version Resolution
* **Secure download links**: `GET /downloads/{orderDetail}/{token}`
* **Order Status Check**: Downloads are permitted for all active paid order statuses (`[1, 2, 5, 7, 8]`).
* **Dynamic Versioning**: Rather than taking historical snapshots when placing orders, the download controller resolves the file location, S3 driver configurations, and CDN URLs dynamically from the active `ProductVariant` relationship. This ensures that any catalog file updates made by administrators are immediately available to existing and past orders.
* **Email Download Buttons**: Transactional confirmation emails and admin-triggered download reminders automatically format direct, secure link buttons to access files directly from mail clients.
* **Access constraints**: Protected by the order's UUID token, 7-day expiration, and a maximum of 5 attempts. Download statistics are logged inside `order_downloads`.

### Admin Upload Guards & Store Safety
* **Upload Validation**: Admin product variant editors enforce that any variant marked as a "Downloadable Item" must have a file uploaded (or a pre-existing location set) before saving.
* **Write Verification**: Filesystem write operations are verified immediately post-store via `Storage::disk()->exists()`. If directory write permissions on local public disks or custom S3 connection configurations fail, the save/update execution halts, the variant creation is rolled back, and the validation error message is output directly in the UI workspace.

### ProductImage URL Resolution
`ProductImage::resolveUrl()` is the single central resolver for all image URLs:
1. If `image_url_source = 1` → return the path as a direct URL immediately.
2. If `cdn_url` is set → prefix the path with the CDN domain.
3. If `image_s3 = 1` → resolve via the S3 disk (global or custom credentials).
4. Otherwise → resolve via the local `public` disk.

---


# CMS Downloads Manager

The **CMS Downloads Manager** is a standalone, site-wide file delivery system that operates independently from the e-commerce order download system. While the product variant download system is tied to a paid order, CMS Downloads are free-standing — they can be embedded on any public page, in any sidebar, or in any CMS body field using a simple shortcode. Common uses include PDF user guides, software installers, firmware packages, audio samples, promotional videos, and any other file that needs to be securely or publicly accessible from page content.

---

### 1. Where It Lives

| Location | Path |
|---|---|
| **Admin Index** | `/admin/cms-downloads` |
| **Create New** | `/admin/cms-downloads/create` |
| **Edit Record** | `/admin/cms-downloads/{id}/edit` |
| **Public Serve Route** | `GET /cms-download/{id}` |
| **CMS Navigation** | Admin nav → **CMS** dropdown → **Downloads** |
| **CMS Sidebar** | Left sidebar of any CMS admin page → **Downloads** link |
| **Settings** | `/admin/settings` → **CMS Downloads** section |

---

### 2. How a Download Is Stored

Each CMS Download record is stored in the `cms_downloads` table. A single record captures everything needed to resolve, serve, and display a file — regardless of where the file is physically located.

#### Key Database Columns

| Column | Type | Purpose |
|---|---|---|
| `internal_name` | `string` | Admin-only label; used in search and the shortcode generator drawer |
| `link_label` | `string` | Default public-facing link text (can be overridden per shortcode) |
| `source_type` | `tinyint` | File location mode: `0` Local, `1` Direct URL, `2` Env S3, `3` Custom S3 |
| `file_path` | `string` | Relative storage path (local uploads) |
| `cdn_url` | `string` | Direct CDN / external URL for Source Type 1 |
| `s3_file_key` | `string` | S3 object key using global `.env` credentials (Source Type 2) |
| `s3_custom_*` | various | Per-record custom S3 bucket, region, access key, secret (Source Type 3) |
| `s3_expiration_seconds` | `int` | TTL for pre-signed S3 URLs (default: 3600 = 1 hour) |
| `poster_image_path` | `string` | Local path or CDN URL for video/audio cover art |
| `video_poster_s3_key` | `string` | S3 key for a poster image when using Env S3 |
| `force_download` | `boolean` | Forces browser download prompt instead of inline display |
| `open_in_new_tab` | `boolean` | Adds `target="_blank"` to the generated `<a>` tag |
| `show_icon` | `tinyint` | Icon position: `0`=None, `1`=Left, `2`=Right, `3`=Top, `4`=Bottom |
| `is_active` | `boolean` | Inactive records render as an HTML comment — never a 404 |
| `expires_at` | `datetime` | Optional hard expiry after which the link is suppressed |
| `custom_css` | `text` | Scoped per-download CSS injected alongside the generated HTML |

---

### 3. File Source Types

The download controller (`CmsDownloadController@serve`) determines how to retrieve and deliver the file based on `source_type`:

#### Source Type 0 — Local Storage
The file was uploaded directly via the edit form and stored on the local `public` disk under `storage/app/public/cms_downloads/`. The controller streams the file using `Storage::disk('public')->response()` with the correct MIME type, honoring the `force_download` flag.

#### Source Type 1 — Direct URL
The `cdn_url` field contains a full public URL (e.g., a CloudFront or Cloudflare R2 URL). The controller issues a `302` redirect to that URL. No server-side streaming occurs — the CDN handles bandwidth directly.

#### Source Type 2 — Env S3 (Global Credentials)
Uses the app-wide S3 credentials from `.env` (`AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`). Generates a **pre-signed temporary URL** via Laravel's `Storage::disk('s3')->temporaryUrl()` with the configured expiry seconds and redirects the browser to it.

#### Source Type 3 — Custom S3 (Per-Record Credentials)
Dynamically registers a new S3 filesystem disk using per-record credentials (`s3_custom_access_key`, `s3_custom_secret_key`, `s3_custom_bucket`, `s3_custom_region`) via `config(["filesystems.disks.{$diskName}" => ...])`, then generates a pre-signed URL through that ephemeral disk. This enables serving files from completely different AWS accounts, regions, or providers on a per-download basis.

> **Security note:** Pre-signed S3 URLs are time-limited (default: 1 hour). They grant temporary, read-only, unauthenticated access to the specific S3 object — no AWS credentials are ever exposed to the browser.

---

### 4. Shortcode System

CMS Downloads are embedded in any page content using the `[download:N]` shortcode.

#### Syntax

```
[download:{id}]
[download:{id} label="Custom Link Text"]
```

The `label` parameter is optional. When omitted, the download's saved `link_label` field is used as the link text. When provided, the inline label overrides the database default for that specific embed only — the record itself is unchanged.

#### Example Usage

```html
Download our user guide:      [download:3]
Get the updated firmware:     [download:7 label="Firmware v2.4.1 (ZIP)"]
Watch the tutorial:           [download:12]
Listen to the audio preview:  [download:15 label="Audio Sample (MP3)"]
```

#### Where Shortcodes Can Be Inserted
- Any CMS Page body (main, left sidebar, right sidebar)
- Any CMS Page custom header content
- Product short or long descriptions (via the dynamic Blade/Livewire parser)
- Email template bodies (via `ContentParserService`)

---

### 5. Shortcode Rendering — Four Output Modes

`ShortcodeProcessor::renderDownload()` auto-detects the file type from the resolved extension and selects one of four output strategies. The `force_download` flag on the record bypasses media rendering for images, video, and audio, always falling through to the download-link branch.

#### Mode A — Inline Image
**Triggered for extensions:** `png · jpg · jpeg · jpe · gif · bmp · webp · svg · svgz · tif · tiff`

Renders the file directly as a responsive `<img>` tag embedded inside the page content:
```html
<img src="/cms-download/3" alt="Label" class="cms-download-image" style="max-width:100%;">
```

#### Mode B — Video.js Player
**Triggered for extensions:** `mp4 · webm · mov · qt`
**Condition:** `isVideo()` returns `true` AND `force_download` is `false`


Injects a fully responsive Video.js player. If a `poster_image` is configured on the download record, it is set as the `poster` attribute so visitors see cover art before pressing play. The correct MIME type (`video/mp4`, `video/webm`, `video/quicktime`) is resolved from the `CmsDownload::mimeType()` helper:
```html
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/…/video-js.min.css">
<script src="https://cdnjs.cloudflare.com/…/video.min.js"></script>
<div class="cms-video-display-container">
    <video id="cms-media-12-84729"
           class="video-js vjs-fluid vjs-default-skin"
           controls preload="auto" width="100%" height="100%"
           poster="https://…/poster.jpg">
        <source src="/cms-download/12" type="video/mp4">
    </video>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof videojs !== "undefined") { videojs("cms-media-12-84729"); }
    });
</script>
```

> **Video.js deduplication:** The CSS and JS `<link>`/`<script>` tags are emitted only once per PHP request via a `static bool $videoJsLoaded` flag on `ShortcodeProcessor`. If multiple video or audio `[download:N]` shortcodes appear on the same page, only the first one outputs the Video.js assets. This prevents duplicate asset loading without requiring any session state.

#### Mode C � Video.js Audio Player
**Triggered for extensions:** `mp3`
**Condition:** `isAudio()` returns `true` AND `force_download` is `false`

Renders an `<audio>` element wrapped in a Video.js player (same CSS/JS assets as video mode � deduplication applies). Poster art is fully supported and recommended; it displays as a thumbnail above the audio controls � ideal for album art or episode cover images:
```html
<div class="cms-audio-display-container">
    <div class="cms-audio-display">
        <audio id="cms-media-15-19283"
               class="video-js vjs-fluid vjs-default-skin"
               controls preload="auto"
               poster="https://�/cover.jpg">
            <source src="/cms-download/15" type="audio/mpeg">
        </audio>
    </div>
</div>
<script>document.addEventListener("DOMContentLoaded",function(){if(typeof videojs!=="undefined"){videojs("cms-media-15-19283");}});</script>
```

> **Force Download override:** If `force_download` is enabled on the record, the audio branch is skipped entirely and the file is served as Mode D (styled download link) regardless of the `.mp3` extension. This applies equally to video files.

#### Mode D � Styled Download Link
**Triggered for:** all other file types (PDF, ZIP, DOCX, EXE, CSV, etc.) � and any media/image type when `force_download` is `true`

Renders a styled `<a>` tag routing through the download controller. The `show_icon` field controls whether a file-type icon appears and at which position relative to the label:

| `show_icon` | Position | Flex Behavior |
|---|---|---|
| `0` | No icon | Plain `<a>` tag only |
| `1` | Icon Left | `inline-flex row`, icon `order:0`, label `order:1` |
| `2` | Icon Right | `inline-flex row`, icon `order:1`, label `order:0` |
| `3` | Icon Top | `flex column`, icon `order:0`, label `order:1` |
| `4` | Icon Bottom | `flex column`, icon `order:1`, label `order:0` |

When an icon position is configured, `ShortcodeProcessor` emits a scoped `<style>` block keyed to the download ID alongside the link HTML � no global CSS class conflicts are possible between multiple different download shortcodes on the same page:
```html
<style id="cms-dl-style-5">
  .cms-link-container-5 { display: inline-flex; flex-direction: row; align-items: center; gap: 6px; }
  .cms-link-icon-5      { order: 0; width: auto; }
  .cms-link-label-5     { order: 1; }
</style>
<a href="/cms-download/5" class="cms-link" target="_blank" rel="noopener noreferrer">
  <div class="cms-link-container-5">
    <div class="cms-link-icon-5">
      <span class="fiv-cla fiv-viv fiv-icon-pdf"
            style="font-size:2em; line-height:1;"
            title=".PDF file"></span>
    </div>
    <div class="cms-link-label-5">Download User Guide (PDF)</div>
  </div>
</a>
```

---
### 6. File Type Icon System

Download link icons use the [file-icon-vectors](https://github.com/dmhendricks/file-icon-vectors) library (v1.0.0 via jsDelivr CDN). Icons are only shown when `show_icon > 0` on a download record.

#### Selecting the Global Icon Pack

The active icon pack is a **global site setting** configured at `/admin/settings` → **CMS Downloads** section. Three packs are available:

| Pack | Setting Value | CSS Class | Description |
|---|---|---|---|
| **Vivid** *(default)* | `vivid` | `fiv-viv` | Bold, colourful filled icons — best on light backgrounds |
| **Classic** | `classic` | `fiv-cla` | Clean monochrome style — timeless and professional |
| **Square** | `square` | `fiv-sqo` | Square outline style — modern and minimal |

The setting is stored as `file_icon_pack` in the `cms_settings` table and read at render time by `ShortcodeProcessor::resolveIconPackClass()`. The resolved CSS class is cached in a static property so the database is queried at most once per HTTP request regardless of how many `[download:N]` shortcodes appear on the page.

**Layout loading behavior:**
- **Admin layout** (`layouts/app.blade.php`): All three pack CSS files are loaded simultaneously so the Settings page live-preview works instantly when toggling between packs — no page reload required.
- **Public layout** (`layouts/public.blade.php`): Only the active pack's single CSS file is loaded, keeping public page weight lean.

**Supported file extension icons** include: `pdf · doc · docx · xls · xlsx · ppt · pptx · zip · rar · exe · mp3 · mp4 · jpg · png · gif · svg · txt · odt` and many more — covering the full file-icon-vectors icon catalogue. Any unrecognised extension renders without an icon gracefully, with no errors.

---

### 7. Admin Interface

#### Downloads Index (`/admin/cms-downloads`)
- Searchable list of all download records, paginated at 25 per page
- Each row displays: **internal name**, **link label**, **live file-type icon** (rendered with the active CSS pack), **icon position badge** (`ICON L` / `ICON R` / `ICON T` / `ICON B`), **source type badge** (Local / Direct URL / Env S3 / Custom S3), **is_active toggle**, **shortcode preview**, **edit** and **delete** actions
- One-click inline active/inactive toggle without a full page reload
- Shortcode shown directly in the list row for fast copy-paste

#### Download Edit Form (`/admin/cms-downloads/create` and `/{id}/edit`)

The form is organized into clearly labelled sections:

**Basic Info**
- `Internal Name` — admin-only label used in search and the shortcode generator drawer; not shown to visitors
- `Link Label` — the public-facing link text used when no `label=` override is supplied in the shortcode
- Shortcode preview badge — displays the exact `[download:{id} label="..."]` string to copy into any TinyMCE editor

**File Source** — mutually exclusive tabbed panel:

| Tab | Description |
|---|---|
| **Local Upload** | Upload a file directly from disk; stored at `storage/app/public/cms_downloads/` |
| **Direct URL** | Paste a full CDN or public URL; the controller issues a 302 redirect to it |
| **Env S3 (App Config)** | Reference a file in the main S3 bucket using `.env` AWS credentials |
| **Custom S3** | Supply independent bucket, region, access key, and secret per download record |

**Video / Audio Poster Image**
Accepts a CDN URL for a still image used as the `poster` attribute in the Video.js player when the file type is a video or audio. The field is displayed alongside the source panel.

**Display Options**
- **File Type Icon** — 5-button radio position picker (None / Left / Right / Top / Bottom) with directional arrow icons and a live `fiv-icon-*` preview rendered below the picker as soon as a file source is configured
- **Open in New Tab** — toggle that adds `target="_blank" rel="noopener noreferrer"` to the generated link
- **Force Download** — forces a browser `Content-Disposition: attachment` prompt on all file types, bypassing inline image/video/audio rendering

**Lifecycle Options**
- **Active** toggle — when off, the shortcode renders a silent HTML comment (`<!-- [download-inactive: N] -->`) rather than a broken link or 404
- **Expiry Date** — optional `datetime-local` picker; once the date passes the link is silently suppressed on the public side

**Advanced**
- **Custom CSS** — raw CSS entered here is scoped to the download ID via `<style id="cms-dl-style-{id}">` and injected inline with the rendered output

---

### 8. Shortcode Generator Drawer Integration

The CMS page editor (`/admin/cms-pages/{id}/edit`) includes a slide-out **Shortcode Generator Drawer** on the right edge of the screen. It provides a live search interface across multiple record types. CMS Downloads are the sixth scope tab:

| Tab Label | Scope | Result Badge Color |
|---|---|---|
| All | All types | Various |
| Pages | CMS Pages | — |
| Prods | Products | — |
| Cats | Categories | — |
| Brnd | Brands | Violet |
| **DLs** | **CMS Downloads (active only)** | **Teal** |

Searching with the **DLs** tab filters `cms_downloads` by matching `internal_name` or `link_label` (only `is_active = true` records are returned). Each result card displays the teal **Download** badge, the internal name as the title, and the full ready-to-use shortcode string (e.g., `[download:3 label="User Guide"]`). Cards can be clicked to copy the shortcode or dragged directly into the TinyMCE editor canvas.

---

### 9. Processing Order in ContentParserService

`ShortcodeProcessor::process()` is called by **Pipeline B** (`ContentParserService::parse()`) and runs two sequential passes over all CMS content strings:

1. **Pass 1 — `[download:N]` shortcodes** — handled first by `processDownloads()`. The regex `/\[download:(\d+)([^\]]*)\]/i` captures the numeric ID and any trailing `label="..."` parameter before the plugin pass runs.
2. **Pass 2 — `[plugin:slug]` shortcodes** — the plugin system runs after downloads are fully resolved.

This ordering guarantees `[download:N]` shortcodes (which use numeric IDs) are never misidentified as plugin slugs, and both types coexist safely on the same page. The same `ShortcodeProcessor::process()` is also called inside `ListMenuRenderer::parseItemContent()` (Pipeline A), meaning `[download:N]` shortcodes embedded _within list menu items_ are equally supported.

---

### 10. Relationship to the Product Order Download System

| Feature | CMS Downloads | Order Downloads |
|---|---|---|
| **Purpose** | Free-standing public/gated content delivery | Paid order fulfilment only |
| **Access control** | Optionally public — no login required | Requires valid order UUID token |
| **Trigger** | Shortcode embedded in page content | Checkout completion; email reminder links |
| **Expiry** | Optional `expires_at` date on the record | Order-level expiry date + max-download counter |
| **Source types** | Local / Direct URL / Env S3 / Custom S3 | Local / S3 (global or per-variant custom creds) |
| **Icon support** | File-icon-vectors (4 positions, 3 packs) | Not applicable |
| **Media playback** | Video.js for video and audio types | Not applicable |
| **Admin location** | `/admin/cms-downloads` | `/admin/ecommerce/orders/{id}` |
| **Public route** | `GET /cms-download/{id}` | `GET /downloads/{orderDetail}/{token}` |
| **Controller** | `CmsDownloadController` | `ProductDownloadController` |
| **Livewire components** | `AdminCmsDownloads`, `AdminCmsDownloadEdit` | Inline on order detail page |

Both systems generate S3 pre-signed URLs dynamically at request time and never store pre-computed URLs in the database, ensuring that any file rotation or key changes on the storage layer take effect immediately across all active links.

---
# Webhook Ingestion & API Endpoints

### 1. Inbound Email Webhook
Receives inbound email replies at: `POST /webhooks/inbound-email`
* Emails matching `reply+{token}@yourdomain.com` are verified and appended to the corresponding ticket thread.
* Supported providers: **Cloudflare Email Workers**, **Mailgun**, **Postmark**.

### 2. Inventory Update Webhook
Receives third-party automated inventory sync requests at: `POST /webhooks/inventory-update`
* Expects `sku`, optional `stock_level`, `warehouse_level`, `use_warehouse_stock`, and `location_id`.
* Requires authorization matching the server's `INVENTORY_WEBHOOK_SECRET` config value.

---

# Routing Directory & Endpoints

### Public Storefront & Cart
| Route | Description |
|---|---|
| `GET /shop` | Paginated catalog with category drill-down and brand filter panels |
| `GET /section/{category_slug}` | Category-filtered catalog |
| `GET /brands/{brand_slug}` | Brand-filtered catalog |
| `GET /items/{seo_link}` | Product detail page |
| `GET /cart` | Shopping cart |
| `GET /checkout` | Checkout form |
| `GET /checkout/review` | Order review |
| `GET /checkout/success/{external_id}` | Confirmation invoice |
| `GET /downloads/{orderDetail}/{token}` | Secure digital download |

### Customer Dashboard & Helpdesk
| Route | Description |
|---|---|
| `GET /dashboard` | Ticket history (Admins and agents redirected to `/admin/dashboard`) |
| `GET /tickets/create` | Submit ticket |
| `GET /tickets/{id}` | Authenticated ticket thread |
| `GET /tickets/view/{token}` | Guest ticket thread |
| `GET /kb` | Knowledge base landing |
| `GET /kb/{seo_link}` | KB article |

### Admin E-Commerce
| Route | Description |
|---|---|
| `GET /admin/pending-orders` | Pending Orders list (non-completed orders, paginated at 25) |
| `GET /admin/ecommerce/products` | Product list (25/page) |
| `GET /admin/ecommerce/products/{id}/edit` | Product editor (TinyMCE, anchor nav, image URL source, Advanced Settings) |
| `GET /admin/ecommerce/categories` | Category tree (25/page, product assignment panel) |
| `GET /admin/ecommerce/brands` | Brand manager (25/page, logo upload, product assignment panel) |
| `GET /admin/ecommerce/orders` | Order list (25/page) |
| `GET /admin/ecommerce/orders/{id}` | Invoice & refund |
| `GET /admin/ecommerce/inventory` | Inventory editor (25/page) |
| `GET /admin/ecommerce/shipping` | Shipping & Taxes Console (Global configs, states/countries, handling fees, custom flat rates) |
| `GET /admin/ecommerce/discounts` | Discounts CRUD list (25/page) |
| `GET /admin/ecommerce/discounts/create` | Create a new store discount/coupon |
| `GET /admin/ecommerce/discounts/{id}/edit` | Edit discounts parameters & parameters |
| `GET /admin/ecommerce/discounts/config` | Storewide discounts settings configuration |
| `GET /admin/cms-pages` | Custom CMS page catalog |
| `GET /admin/cms-pages/create` | Custom CMS page creator |
| `GET /admin/cms-pages/{id}/edit` | Custom CMS page editor, revisions, and restore |
| `POST /webhooks/inventory-update` | Secure API webhook to update variant inventory values |
| `GET /{slug}` | Dynamic fallback catch-all page view |

### Admin Support & Users
| Route | Description |
|---|---|
| `GET /admin/dashboard` | Main admin home dashboard with e-commerce performance reports (KPI cards, SVG revenue trends, conversion funnel, spender rankings, and product sales grid) |
| `GET /admin/tickets` | Ticket queue (25/page) (formerly `/admin/dashboard`) |
| `GET /admin/users` | User list (25/page) |
| `GET /admin/users/{user}` | Customer profile |
| `GET /admin/users/{user}/edit` | Role & profile editor |
| `GET /admin/kb` | KB article manager |
| `GET /admin/kb/categories` | KB category editor |

## Dependent Variants Implementation (Color/Size Combo Selectors)

The platform models variations using a flat inventory schema where each variant is represented by a single `product_variants` row containing an `attributes` JSON field (e.g., `{"Color": "Midnight Blue", "Size": "XXL"}`). This permits maximum database flexibility and performance.

This dependent, multi-step selector experience (e.g., choosing a **Color** first, which dynamically filters and shows only the **Sizes** in stock for that color) is fully implemented out of the box. Below is the codebase implementation pattern used:

### 1. Component State Setup
In your public Livewire component (e.g., `ProductDetails.php`), track the selected state using an array of chosen attributes:
```php
public array $selectedAttributes = [
    'Color' => null,
    'Size'  => null,
];
```

### 2. Extract Available Keys and Options
On component initialization (`mount`), map the unique options available across all of the product's variants:
```php
// Collect all distinct values for each attribute key
$this->availableOptions = [];
foreach ($this->product->variants as $variant) {
    $attrs = json_decode($variant->attributes, true) ?: [];
    foreach ($attrs as $key => $val) {
        $this->availableOptions[$key][] = $val;
    }
}
// Clean up duplicates
foreach ($this->availableOptions as $key => $vals) {
    $this->availableOptions[$key] = array_unique($vals);
}
```

### 3. Progressive Filtering in View
When rendering the selectors in Blade, display options dynamically based on the current selection hierarchy:
* **Step 1 (Color Selection)**: Render all unique colors.
* **Step 2 (Size Selection)**: Filter the sizes to only display those associated with variants matching the selected color.
```html
<!-- Size Select (Progressive Filter) -->
@php
    $selectedColor = $selectedAttributes['Color'];
    $allowedSizesForColor = $product->variants
        ->filter(function($v) use ($selectedColor) {
            $attrs = json_decode($v->attributes, true) ?: [];
            return ($attrs['Color'] ?? null) === $selectedColor;
        })
        ->map(function($v) {
            $attrs = json_decode($v->attributes, true) ?: [];
            return $attrs['Size'] ?? null;
        })
        ->filter()
        ->unique()
        ->toArray();
@endphp

@foreach($availableOptions['Size'] as $size)
    <button 
        wire:click="$set('selectedAttributes.Size', '{{ $size }}')"
        @disabled(!in_array($size, $allowedSizesForColor))
        class="..."
    >
        {{ $size }}
    </button>
@endforeach
```

### 4. Resolve Selection to Variant ID
Once the user has selected values for all required keys, find the matching variant row and update `$selectedVariantId`:
```php
$matchedVariant = $this->product->variants->first(function($v) {
    $attrs = json_decode($v->attributes, true) ?: [];
    return ($attrs['Color'] ?? null) === $this->selectedAttributes['Color'] &&
           ($attrs['Size'] ?? null) === $this->selectedAttributes['Size'];
});

if ($matchedVariant) {
    $matchedVariantId = $matchedVariant->id;
}
```

---

## Customizing Colors & Themes (Light & Dark Mode)

Developers and designers can easily customize the color scheme and design language for both light and dark modes:

### 1. Tailwind Theme Configuration (`tailwind.config.js`)
To customize colors globally, extend or override the Tailwind color palette in your `tailwind.config.js` file:
```js
export default {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Example: Customize primary brand color to emerald instead of indigo
                brand: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                }
            }
        }
    }
}
```
You can then use classes like `bg-brand-600` or `dark:text-brand-500` seamlessly across the Blade layouts and components.

### 2. Custom CSS Overrides & Variables (`resources/css/app.css`)
For layout patterns that use standard CSS classes (such as authentication card backdrops, inline knowledge-base articles, and inputs), modify the overrides in `resources/css/app.css`:
* **Light Mode Colors**: Edit the default styles directly (e.g. `.auth-bg`, `.auth-card`, `.auth-input`).
* **Dark Mode Colors**: Edit the rules under the `.dark` class prefix selector (e.g. `.dark .auth-bg`, `.dark .auth-card`, `.dark .auth-input`).

Example override for a custom dark theme:
```css
/* Change default dark mode body background to a deep forest green tint */
.dark body {
    background-color: #0b130e;
    color: #e6f4ea;
}
```

### 3. Dynamic Branding Theme Customizer (Admin Reskinning)
Administrators can quickly reskin the button and brand color scheme sitewide from the Admin panel:
1. Navigate to **Admin → Settings** (`/admin/settings`).
2. In the **Appearance** panel, configure the custom brand colors:
   - **Primary Color**: Set the primary color used for buttons, links, borders, and active highlights.
   - **Hover Color**: Set the hover background and text color.
   - **Text Color**: Set the button text color (usually `#ffffff`).
   - **Button Shape**: Select the desired corner radius (e.g. Sharp, Rounded MD/XL/2XL, Pill/Full).
3. Click **Save Settings**. 

The settings are saved in the `cms_settings` table and injected dynamically into all frontend and admin layout files using the `<x-site-theme-styles />` component. The default purple/indigo Tailwind colors are automatically overridden globally.

---

# Timezone Configuration

Administrators can override the server's default PHP timezone from within the platform itself, eliminating the need to modify `.env` or server configuration files.

### Where to Configure

Navigate to **Admin → Settings** (`/admin/settings`). A **Timezone** dropdown selector is displayed in the General Settings panel. Select any valid PHP timezone and click **Save Settings**.

### How It Works

* The selected timezone identifier (e.g. `America/Los_Angeles`, `Europe/London`, `Australia/Sydney`) is stored in the `cms_settings` table under the key `timezone`.
* On every application boot, `AppServiceProvider::boot()` reads this setting from the database and calls `date_default_timezone_set()` so that all PHP `date()`, `Carbon`, and Laravel timestamp operations reflect the configured timezone.
* Timezone takes effect immediately on next request after saving — no restart or cache flush required.

### Available Timezone Groups

The dropdown is organized by geographic region and covers the full PHP timezone list, including:

| Region | Examples |
|---|---|
| **United States** | America/New_York, America/Chicago, America/Denver, America/Los_Angeles, America/Anchorage, Pacific/Honolulu |
| **Canada** | America/Toronto, America/Winnipeg, America/Edmonton, America/Vancouver |
| **Europe** | Europe/London, Europe/Dublin, Europe/Paris, Europe/Berlin, Europe/Rome, Europe/Madrid, Europe/Helsinki, Europe/Moscow |
| **Asia / Pacific** | Asia/Tokyo, Asia/Shanghai, Asia/Kolkata, Asia/Singapore, Australia/Sydney, Australia/Melbourne, Pacific/Auckland |
| **Middle East / Africa** | Asia/Dubai, Asia/Riyadh, Africa/Cairo, Africa/Johannesburg |
| **Atlantic / Other** | America/Sao_Paulo, America/Argentina/Buenos_Aires, Pacific/Fiji |

---

# Merchant Location, Currency & VAT Settings

The **Shipping & Taxes** configuration panel (`/admin/ecommerce/shipping`) contains a dedicated **Merchant Location & Currency** section that controls how prices, tax labels, and VAT are presented store-wide.

### Merchant Country (Home Jurisdiction)

* Select the country where the merchant's business is primarily located.
* Defaults to **United States**.
* Countries are sourced from the same `shipping_countries` table used throughout checkout.
* This setting determines whether the store operates under VAT-inclusive pricing rules (see [VAT-Inclusive Pricing](#vat-inclusive-pricing--cross-border-vat-removal) below).

> **Important**: Changing the merchant country to a non-US / non-Canada country activates VAT-inclusive pricing for the entire storefront. An amber notice in the admin UI warns you of this when selecting a non-US/CA country.

### Currency Code

* A three-character ISO 4217 code (e.g. `USD`, `GBP`, `EUR`, `AUD`, `CAD`).
* Defaults to `USD`.
* Displayed in order confirmation emails and invoice pages for reference.

### Currency Symbol

* The symbol that appears before all displayed prices across the storefront, cart, checkout, and email notifications.
* Defaults to `$`.
* Common examples: `£` (British Pound), `€` (Euro), `A$` (Australian Dollar), `C$` (Canadian Dollar), `¥` (Japanese Yen).
* The symbol is always **prefix-positioned** (e.g. `£19.99`).

### Where Currency Symbol Appears

Once saved, the currency symbol propagates automatically to every price-display point in the platform:

| Area | Details |
|---|---|
| **Shop Catalog** (`/shop`) | Grid and list view product prices |
| **Product Details** (`/items/{slug}`) | Main price, sale price, strikethrough, save badge, variant list, selection fees, personalization fee, "You May Also Like" carousel |
| **Shopping Cart** (`/cart`) | Item prices, subtotals, discount lines, order total |
| **Slide-Out Cart Drawer** | All item and summary prices |
| **Order Review** (`/checkout/review`) | Line items, subtotal, tax, shipping, grand total |
| **Checkout Success** (`/checkout/success/{id}`) | Receipt invoice totals |
| **Order Confirmation Email** | All price columns in the HTML order items table |

---

# VAT-Inclusive Pricing & Cross-Border VAT Removal

The platform implements a flexible, merchant-location-aware Value Added Tax (VAT) pricing engine. This feature is activated automatically when the **Merchant Country** (see above) is set to a non-US / non-Canadian country.

### Core Concept

In most countries outside the US and Canada (e.g. UK, EU member states, Australia, New Zealand), prices in a shop are displayed **including VAT**. When a customer in a VAT country shops domestically, the price they see and pay already contains the tax. When a customer from a non-VAT country (US or Canada) makes a cross-border purchase, they should not be charged VAT — so it must be stripped from the displayed price and totals.

### How Prices Are Stored

Product prices in the database (`public_price`, `wholesale_price`, `sale_price`) are stored as **ex-VAT (net) values**. The VAT rate is applied at display-time based on the merchant's configuration. This means:

* The admin always enters and sees net prices in the product editor.
* The storefront multiplies by `(1 + rate/100)` before displaying to customers.
* No price data is corrupted — VAT is a pure display layer.

### Merchant VAT Rate

The VAT rate is looked up from the `shipping_countries` table matching the merchant's configured country code where `charge_vat = 1`. For example, if the merchant is in the UK (`GB`) and the UK row has a `custom_vat_rate` of `20`, the system applies 20% VAT to all displayed prices.

### Domestic VAT Display (Merchant Country = Customer Country)

When the buyer's shipping country is the same jurisdiction as the merchant (e.g. UK merchant, UK buyer):

* All prices on the catalog, product detail, and cart pages are shown **inclusive of VAT** (net price × 1.2 for 20% VAT).
* The order review page shows a VAT breakdown line: **"Includes VAT £X.XX"** — informational, not additive.
* The email confirmation shows the same embedded VAT display.

### Cross-Border VAT Removal (US / CA Buyers)

When the buyer selects **United States** or **Canada** as their shipping country during checkout:

* The `CurrencyService::isCrossBorderExport()` flag returns `true`.
* The order subtotal is divided by `(1 + rate/100)` to strip the embedded VAT.
* No VAT line appears on the order review — the customer pays the net price only.
* Email totals reflect the stripped (ex-VAT) amounts.

> **Example**: A product stored at £16.67 ex-VAT. UK buyer sees £20.00 (incl. 20% VAT). US buyer sees $16.67 (VAT stripped, shown in configured currency symbol).

### Dynamic Tax Labels

The tax line label on the order review and email receipts adapts automatically based on the buyer's shipping country:

| Customer Country | Tax Label |
|---|---|
| United States | **Sales Tax** |
| Canada | **GST/HST** |
| All other countries | **VAT** |

### Service Architecture

The VAT engine is implemented in two service classes:

* **`CurrencyService`** (`app/Services/CurrencyService.php`): Central authority for symbol, code, VAT-inclusive status, merchant country, VAT rate, formatting, and cross-border detection.
* **`TaxCalculationService`** (`app/Services/TaxCalculationService.php`): Handles `extractedVat()` for domestic breakdown and `adjustSubtotalForVat()` for cross-border stripping.
* The config is cached for 60 minutes under the key `currency_config`. Saving new shipping configuration flushes this cache automatically.

---

# Payment Processors

The platform implements a **pluggable payment processor architecture**. The active gateway is selected in the Admin panel (`/admin/ecommerce/checkout/processors`) and resolved at runtime by `PaymentProcessorManager`.

**Stripe, Paddle, and PayPal are built-in defaults** — they are always registered, fully verified, and ready to use. All three support inline checkout forms for a seamless, consistent customer experience. No file edits are required to activate them; simply add credentials to `.env`, and select them as primary in the Admin panel.

### 1. Registered Processors

| Processor ID | Name | Type | Where it lives |
|---|---|---|---|
| `0` | **Test Processor** | Always-on built-in | `app/Services/Payments/Processors/TestProcessor.php` |
| `1` | **Stripe** | Always-on built-in | `app/Services/Payments/Processors/StripeProcessor.php` |
| `2` | **Paddle** | Always-on built-in | `app/Services/Payments/Processors/PaddleProcessor.php` |
| `3` | **PayPal** | Always-on built-in | `app/Services/Payments/Processors/PayPalProcessor.php` |
| `100+` | **Custom Gateway** | Optional / manual | `payment-processors/my-gateway/` (see §8) |

### 2. Architecture Overview

```
app/Services/Payments/
  Contracts/PaymentProcessorInterface.php   ← Interface all drivers implement
  PaymentResult.php                         ← Uniform result DTO
  PaymentProcessorManager.php               ← Resolves active driver + randomize logic
  Processors/
    TestProcessor.php                       ← Built-in test simulator (always active)
    StripeProcessor.php                     ← Built-in Stripe driver (always registered)
    PaddleProcessor.php                     ← Built-in Paddle driver (always registered)
    PayPalProcessor.php                     ← Built-in PayPal driver (always registered)

config/payment_processors.php              ← Registry: maps processor_id → class

payment-processors/
  stripe/
    StripeProcessor.php                     ← Extension TEMPLATE (not active by default)
    README.md                               ← Stripe setup + extension guide
  paddle/
    PaddleProcessor.php                     ← Extension TEMPLATE (not active by default)
    README.md                               ← Paddle setup + extension guide
  paypal/
    PayPalProcessor.php                     ← Extension TEMPLATE (not active by default)
    README.md                               ← PayPal setup + extension guide
  example-gateway/
    ExampleGatewayProcessor.php             ← New processor TEMPLATE (not active)
    README.md                               ← Step-by-step guide for new gateways
```

### 3. Customising Stripe, Paddle or PayPal (Extension Override)

To add or override behaviour without editing the built-in class, create an extension file.
The platform **auto-detects** these files — no changes to `config/payment_processors.php` needed:

| Create this file | Overrides |
|---|---|
| `payment-processors/stripe/StripeProcessorExtension.php` | Built-in `StripeProcessor` (ID=1) |
| `payment-processors/paddle/PaddleProcessorExtension.php` | Built-in `PaddleProcessor` (ID=2) |
| `payment-processors/paypal/PayPalProcessorExtension.php` | Built-in `PayPalProcessor` (ID=3) |

**Extension class requirements:**
- Namespace: `PaymentProcessors\Stripe`, `PaymentProcessors\Paddle` or `PaymentProcessors\PayPal`
- Class name: `StripeProcessorExtension`, `PaddleProcessorExtension` or `PayPalProcessorExtension`
- Must extend the corresponding built-in base class

**Stripe extension skeleton:**
```php
<?php
namespace PaymentProcessors\Stripe;

use App\Services\Payments\Processors\StripeProcessor as Base;

class StripeProcessorExtension extends Base
{
    // Override only the methods you need — everything else is inherited.

    public function createPaymentIntent(float $amount, string $currency = 'usd'): array
    {
        $result = parent::createPaymentIntent($amount, $currency);
        // Add custom logging, metadata, etc.
        return $result;
    }
}
```

Full templates (with all override examples) are in `payment-processors/stripe/StripeProcessor.php` and `payment-processors/paddle/PaddleProcessor.php`.

### 4. Enabling Stripe

**Step 1** — Install the SDK:
```bash
composer require stripe/stripe-php
```

**Step 2** — Add credentials to `.env`:
```ini
# Production
STRIPE_PUBLISHABLE_KEY=pk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_live_xxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx

# Sandbox / Test
STRIPE_SANDBOX_PUBLISHABLE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxx
STRIPE_SANDBOX_SECRET_KEY=sk_test_xxxxxxxxxxxxxxxxxxxx
```

**Step 3** — In Admin → Checkout → Processors, set **Stripe** as Primary and toggle Production on/off.

### 5. Enabling Paddle

**Step 1** — Install the SDK:
```bash
composer require paddlehq/paddle-php-sdk
```

**Step 2** — Add credentials to `.env`:
```ini
# Production (vendors.paddle.com)
PADDLE_API_KEY=your_live_api_key
PADDLE_CLIENT_TOKEN=your_live_client_token
PADDLE_WEBHOOK_SECRET=pdl_ntf_xxxxxxxxxxxxxxxx

# Sandbox (sandbox-vendors.paddle.com)
PADDLE_SANDBOX_API_KEY=your_sandbox_api_key
PADDLE_SANDBOX_CLIENT_TOKEN=your_sandbox_client_token
```

**Step 3** — In Admin → Checkout → Processors, set **Paddle** as Primary and toggle Production on/off.

#### 5.1 Paddle Dynamic & Subscription Pricing (Non-Catalog Fallback)

The platform supports both pre-configured Paddle catalog price IDs and dynamically created non-catalog price items during checkout.

* **Variant Configuration fields** (available in the Admin Product Editor):
  - **Paddle Price (`paddle_price`)**: The base price corresponding to the Paddle catalog price.
  - **Billing Interval (`paddle_interval`)**: Recurring cycle (`day`, `week`, `month`, `year`) or empty for one-time payments.
  - **Billing Frequency (`paddle_frequency`)**: Number of intervals between billings (e.g. `1` for monthly, `3` for quarterly).
  - **Currency Code (`paddle_currency_code`)**: ISO currency code (defaults to `USD`).

* **Dynamic Catalog Match vs. Non-Catalog Creation**:
  - **Catalog Price Match**: If the final checkout price of the variant (after all item-level and order-level discounts, plus selection fees) exactly matches the configured `paddle_price`, the pre-configured `paddle_sandbox_price_id` (or `paddle_live_price_id`) is passed directly to Paddle.
  - **Dynamic Non-Catalog fallback**: If the checkout price does not match `paddle_price` exactly (due to active discounts or customization fees), a new "non-catalog" item is created on Paddle dynamically.
    - If a price ID is configured, the processor fetches the catalog `product_id` from the Paddle API and creates the non-catalog price under that existing product.
    - If no price ID is configured, both the non-catalog product and price are created dynamically on the fly.
  
* **Multiple Items & Cart Constraints**:
  - Multiple items are fully supported in a single Paddle transaction.
  - **Interval Uniformity**: If a cart contains multiple dynamic subscription items, all must share the same billing interval and frequency to be checked out together (validated prior to transaction preparation).

### 5a. Enabling PayPal

No external Composer packages are required for PayPal (uses Laravel's native, lightweight `Http` client).

**Step 1** — Add credentials to `.env`:
```ini
# Production
PAYPAL_CLIENT_ID=your_live_client_id
PAYPAL_CLIENT_SECRET=your_live_client_secret

# Sandbox / Test
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_client_secret
```

**Step 2** — In Admin → Checkout → Processors, set **PayPal** as Primary and toggle Production on/off.

* **PayPal Smart Payment Buttons**: 
  - Integrated using the official PayPal JS SDK loaded with the `buttons` component.
  - Automatically renders all available payment options on the checkout page, including PayPal, Venmo, Credit/Debit Cards, and PayPal Pay Later (based on the buyer's region and eligibility).
  - Handles client-side order creation and server-side capture verification, saving transaction records in `order_payments` with full capture authorization codes.

### 6. Sandbox vs. Production Mode

Each processor row in `order_processors` has a **Production** toggle in the Admin panel:

| Toggle | Credentials Used |
|---|---|
| **OFF** (sandbox) | `STRIPE_SANDBOX_*` / `PADDLE_SANDBOX_*` keys |
| **ON** (live) | `STRIPE_*` / `PADDLE_*` keys |

The sandbox/production flag is read at runtime from the database — no code changes needed to switch environments.

### 6a. Stripe Specific Options

Under the Payment Processors list in the Admin panel, there is a **Stripe Specific Settings** card providing the following option:
* **Stripe Address Requirement Toggle**: 
  - **Disabled (Simple Payment Form)**: Displays Stripe's modern Payment Element with billing address collection set to `never` (displaying only the payment method entry fields).
  - **Enabled (Forced Billing Address Form)**: Integrates Stripe's modern Payment Element alongside a separate Stripe Address Element (`mode: 'billing'`). This forces full billing address verification (left blank on load to avoid shipping address auto-population) with dynamic global country fields and Google Maps autocomplete.

### 7. Processor Selection & Randomize Mode

`PaymentProcessorManager` resolves the active processor using:

1. **Randomize OFF** → use `primary_processor` from `order_checkout_options`.
2. **Randomize ON** → randomly selects from all non-zero configured slots (primary / secondary / tertiary) registered in the registry. Falls back to primary if none are registered.
3. **Fallback** → Test Processor (ID 0).

**Checkout display behavior:**
* Only Test Processor active → mock payment card shown.
* Real processor active (Stripe or Paddle) → that processor's JS widget shown.
* Randomize ON → a random real processor's widget is selected per page load.

### 8. Adding a Brand-New Custom Processor

> See `payment-processors/example-gateway/README.md` for the complete step-by-step guide.

1. Copy `payment-processors/example-gateway/` to `payment-processors/my-gateway/`
2. Rename and implement `MyGatewayProcessor.php` (implement `PaymentProcessorInterface`)
3. Add credentials to `.env`
4. Insert a row into `order_processors` with your chosen `processor_id` (use 100 or higher — IDs 0 to 99 are reserved)
5. Register in `config/payment_processors.php` at the bottom:
   ```php
   require_once base_path('payment-processors/my-gateway/MyGatewayProcessor.php');
   $processors[100] = \PaymentProcessors\MyGateway\MyGatewayProcessor::class;
   ```
6. Add a `case` to `PaymentProcessorManager::activeProcessorType()` for your gateway's frontend JS type string
7. In Admin → Checkout → Processors, set as Primary

> The `payment-processors/` directory is outside `app/` by design — its contents are never overwritten by platform updates.

### 9. Checkout Flow (Two-Step)

1. **`preparePayment()`** (Livewire server call): Scans cart for subscription variants. Then:
   - **Subscription cart (Stripe)**: Calls `StripeProcessor::createSubscription()` — resolves or creates a Stripe Customer + Price, creates the Subscription, returns a `client_secret` for browser confirmation.
   - **Regular cart (Stripe)**: Calls `StripeProcessor::createPaymentIntent()`.
   - **Paddle** (subscription or regular): Calls `PaddleProcessor::createTransaction()`. Passes the variant's configured Paddle Price ID for subscriptions, or a custom-amount item for one-time payments.
   - **PayPal** (regular): Calls `PayPalProcessor::createOrder()` — creates a v2 Order via PayPal Orders API and returns the unique PayPal Order ID and Client ID.
2. **Client-side JS** (Stripe Elements, Paddle.js inline form, or PayPal JS SDK):
   - **Stripe/Paddle**: Load inline checkout forms directly inside the Order Review page.
   - **PayPal**: Loads official PayPal Smart Payment Buttons (PayPal, Pay Later, Cards) inside `#paypal-button-container` and binds approval callbacks.
3. **`placeOrder($gatewayToken)`** (Livewire server call): Verifies payment server-side (capturing the order for PayPal), records `authorization_code` and `transaction_id` in `order_payments`, and places the order.

> **Stripe Customer ID persistence**: When a Stripe Subscription is created, the new Customer ID is automatically saved to `users.stripe_customer_id` for future subscription renewals and webhook linkage.

### 10. Webhook Endpoints

Both gateways push signed webhook events to your server. Webhooks confirm payment for cases where the browser closes after payment succeeds but before `placeOrder()` completes.

All webhook routes are **CSRF-exempt** via the `webhooks/*` wildcard in `bootstrap/app.php`.

#### Stripe Webhook — `POST /webhooks/stripe`

Register in Stripe Dashboard: **Developers → Webhooks → Add endpoint**
```
https://yourdomain.com/webhooks/stripe
```

Required `.env` variable:
```ini
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxx
```

| Event | Action |
|---|---|
| `payment_intent.succeeded` | Finds order by `authorization_code`, ensures status ≥ 1 (Open) |
| `payment_intent.payment_failed` | Logs the failure for review |
| `charge.refunded` | Sets order status to 3 (Refunded) |
| `customer.created` | Stores `stripe_customer_id` on the matching user record |
| `customer.subscription.created` | Logged — extend for subscription entitlement grants |
| `customer.subscription.updated` | Logged — extend for plan changes, downgrades |
| `customer.subscription.deleted` | Logged — extend for access revocation on cancellation |
| `invoice.payment_succeeded` | Logged — extend for recurring renewal confirmations |
| `invoice.payment_failed` | Logged — extend for dunning / payment retry logic |

#### Paddle Webhook — `POST /webhooks/paddle`

Register in Paddle Dashboard: **Developer Tools → Notifications → New Destination**
```
https://yourdomain.com/webhooks/paddle
```

Required `.env` variable:
```ini
PADDLE_WEBHOOK_SECRET=pdl_ntf_xxxxxxxxxxxxxxxx
```

| Event | Action |
|---|---|
| `transaction.completed` | Finds order by `authorization_code`, ensures status ≥ 1 (Open) |
| `transaction.payment_failed` | Logs the failure with error code |
| `customer.created` | Stores `paddle_customer_id` on the matching user record |
| `subscription.created` | Logged — extend for subscription entitlement grants |
| `subscription.updated` | Logged — extend for plan changes and quantity updates |
| `subscription.canceled` | Logged — extend for access revocation |
| `subscription.payment_failed` | Logged — extend for dunning logic |

#### Signature Verification

- **Stripe**: `Stripe\Webhook::constructEvent()` with `Stripe-Signature` header + `STRIPE_WEBHOOK_SECRET`.
- **Paddle**: Parses `Paddle-Signature` header (`ts=<timestamp>;h1=<hex>`), computes `HMAC-SHA256(key=secret, data="ts:payload")`, rejects events older than 5 minutes to prevent replay attacks.

#### Gateway Customer IDs

| Column | Set By |
|---|---|
| `users.stripe_customer_id` | `StripeWebhookController::handleCustomerCreated()` |
| `users.paddle_customer_id` | `PaddleWebhookController::handleCustomerCreated()` |

---


# Subscription Variants & Recurring Billing


The platform fully supports **subscription (recurring) billing** through Stripe and Paddle. Subscriptions are configured at the **product variant level** — any variant that has at least one payment processor price ID set is treated as a subscription variant.

### 1. How a Variant Becomes a Subscription

The `ProductVariant::isSubscriptionVariant()` method determines subscription status:

```php
// Returns true if any gateway price ID is configured OR create_new_stripe_product is enabled
public function isSubscriptionVariant(): bool
{
    return !empty($this->stripe_sandbox_price_id)
        || !empty($this->stripe_live_price_id)
        || !empty($this->paddle_sandbox_price_id)
        || !empty($this->paddle_live_price_id)
        || (int) $this->create_new_stripe_product === 1;
}
```

### 2. Admin Configuration

Navigate to **Admin → Products → Edit Product → Prices &amp; Variants** and open or create a variant. The collapsible **Payment Processor IDs** section contains all subscription configuration fields:

**Paddle:**
- Enter the `pri_sandbox_…` Price ID for test mode and/or the `pri_…` Price ID for live mode.
- Paddle automatically selects the correct ID based on whether the processor is in sandbox or production mode.
- Leave both blank to fall back to custom-amount (one-time) billing via Paddle.

**Stripe:**

| Configuration | When to use |
|---|---|
| Enter existing Price IDs | You have already created a recurring Product + Price in the Stripe Dashboard |
| Enable "Create new Stripe product" | You want the platform to auto-create a Product + recurring Price at checkout time |

When **Create new Stripe product** is ON:
- Select the **Billing Interval**: `Monthly`, `Yearly`, or `Weekly`.
- Optionally enable the **Free Trial** toggle and enter a number of trial days. Subscribers with a full-length trial have zero charge at sign-up; Stripe.js confirmation still runs but no card charge occurs until the trial ends.

When using **existing Stripe Price IDs**, you may still configure the billing interval and trial — these values are passed as metadata context (useful for display or audit purposes; the actual interval is controlled by the pre-created Stripe Price).

### 3. Mixed-Cart Policy

Subscription items and regular (one-time purchase) items **cannot be combined in the same cart**. The platform enforces this rule at cart-add time:

- Adding a subscription item when the cart already contains a regular item → **blocked with an error message**.
- Adding a regular item when the cart already contains a subscription item → **blocked with an error message**.

The customer must remove the conflicting item before they can add the new one. This prevents ambiguous checkout behavior where a cart would need to simultaneously run a one-time PaymentIntent and a Subscription.

### 4. Checkout Routing

At the Order Review step, `OrderReview::preparePayment()` automatically detects whether the cart contains a subscription variant (via `resolveSubscriptionVariant()`) and routes accordingly:

```
Cart scan → subscription variant found?
  YES → Stripe: createSubscription()  |  Paddle: createTransaction(price_id=...)
  NO  → Stripe: createPaymentIntent() |  Paddle: createTransaction(custom amount)
```

**Stripe Subscription creation flow:**
1. Resolves or creates a Stripe **Customer** (using the user's email/name). The Customer ID is stored on `users.stripe_customer_id` for future use.
2. Selects the correct Price ID (sandbox or live based on processor mode). If `create_new_stripe_product = 1`, creates a new **Product + recurring Price** on Stripe on-the-fly.
3. Applies the trial period if configured (`trial_period_days`).
4. Returns a `client_secret` from the subscription's latest invoice's PaymentIntent for browser-side card confirmation via Stripe Elements.

**Paddle Subscription flow:**
1. Resolves the correct Paddle Price ID (sandbox or live).
2. Passes the Price ID to `createTransaction()` — Paddle Billing handles the subscription lifecycle from there.
3. Falls back to custom-amount billing if no Price ID is set.

### 5. Webhook Handling for Subscriptions

Subscription lifecycle events arrive via the payment gateway webhooks:

**Stripe** (`POST /webhooks/stripe`):
| Event | Purpose |
|---|---|
| `customer.subscription.created` | Grant subscription access / update user entitlements |
| `customer.subscription.updated` | Handle plan changes, quantity updates |
| `customer.subscription.deleted` | Revoke access on cancellation |
| `invoice.payment_succeeded` | Confirm each recurring renewal |
| `invoice.payment_failed` | Trigger dunning / retry logic |

**Paddle** (`POST /webhooks/paddle`):
| Event | Purpose |
|---|---|
| `subscription.created` | Grant access |
| `subscription.updated` | Handle plan modifications |
| `subscription.canceled` | Revoke access |
| `subscription.payment_failed` | Dunning |

All subscription events are currently **logged** in the webhook controllers. To grant or revoke product access, add your business logic inside the corresponding `handle*` methods in `StripeWebhookController.php` and `PaddleWebhookController.php`.

### 6. Schema Reference

New columns added to `product_variants`:

| Column | Type | Default | Description |
|---|---|---|---|
| `paddle_sandbox_price_id` | `varchar(100)` | `null` | Paddle Test Price ID |
| `paddle_live_price_id` | `varchar(100)` | `null` | Paddle Live Price ID |
| `stripe_sandbox_price_id` | `varchar(100)` | `null` | Stripe Test Price ID |
| `stripe_live_price_id` | `varchar(100)` | `null` | Stripe Live Price ID |
| `create_new_stripe_product` | `tinyint` | `0` | 1 = auto-create Stripe Product + Price at checkout |
| `stripe_billing_interval` | `varchar(10)` | `month` | `month`, `year`, or `week` |
| `stripe_trial_enabled` | `tinyint` | `0` | 1 = apply free trial period |
| `stripe_trial_days` | `int` | `0` | Number of free trial days |

New column added to `shopping_cart_log`:

| Column | Type | Description |
|---|---|---|
| `variant_id` | `unsignedBigInteger`, nullable | Stores the variant's primary key at cart-add time, enabling fast subscription detection at checkout without string parsing |

---


### . Configure payment processor credentials (if applicable)

Add the relevant block from `.env.example` to your `.env`:
```ini
# Stripe
STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
STRIPE_SANDBOX_PUBLISHABLE_KEY=pk_test_...
STRIPE_SANDBOX_SECRET_KEY=sk_test_...

# Paddle
PADDLE_API_KEY=...
PADDLE_CLIENT_TOKEN=...
PADDLE_SANDBOX_API_KEY=...
PADDLE_SANDBOX_CLIENT_TOKEN=...
PADDLE_WEBHOOK_SECRET=...
```

# Gift Wrapping & Personalization Options

Product variants can be configured to support gift wrapping, engraving, or custom personalization messages with an optional fee. This feature is controlled on a **per-variant basis** from the product editor.

### Admin Configuration (Variant Level)

In the product editor under **Prices & Variants**, each variant has a **Gift Wrapping / Personalization** section with:

| Field | Description |
|---|---|
| **Enable Personalization** | Toggle checkbox to activate the feature for this variant |
| **Personalization Label** | Custom label shown to the customer (e.g. "Add Gift Message", "Engraving Text") |
| **Personalization Fee** | Optional fee added to the unit price when personalization is selected (enter `0` for free) |
| **Wholesale Personalization Fee** | Separate fee level for wholesale customer accounts |
| **Placeholder / Instructions** | Helper text shown inside the personalization text input field |

### Customer-Facing Behavior

When personalization is enabled for the selected variant on the public product details page (`/items/{slug}`):

* A styled checkbox control labeled with the configured personalization label appears below the variant selector.
* If a fee is configured, the fee is displayed next to the label (e.g. `(+£5.00)`).
* Checking the box reveals a text area where the customer enters their personalization message.
* The fee is dynamically added to the line item unit price on cart addition.
* The personalization text and fee are stored in the cart `item_attributes` JSON and carried through to the order details record.

### Order & Cart Behavior

* Personalization text and fee appear in the cart line item, order review page, checkout success invoice, and order confirmation email.
* The admin order fulfillment view displays the personalization text so staff can action it.

---

# Download Expiration & Max Downloads (Variant-Level)

Digital download variants support configurable access limits that are automatically applied to each order when a customer completes checkout.

### Admin Configuration (Variant Level)

In the product editor, variants flagged as **Download Item** have two additional fields:

| Field | Default | Description |
|---|---|---|
| **Download Expiration Date** | 1 year from today | The calendar date after which download access links expire and are rejected |
| **Max Downloads** | 100 | The maximum number of times the download file can be accessed on a single order |

### How It Works

1. **Admin sets limits** on the variant record in the product editor.
2. **At checkout** (`OrderReview::placeOrder()`), when the order details record is written for a digital variant, the current values of `download_expiration` and `max_downloads` from the variant are copied directly into the `order_details` row.
3. **Download controller** (`/downloads/{orderDetail}/{token}`) validates that:
   - The current date is before the stored `download_expiration`.
   - The `download_count` on the order detail has not reached `max_downloads`.
4. If either limit is exceeded, a 403 / expired-access page is returned.

> **Note**: Because limits are captured from the variant **at the time of purchase**, subsequent admin changes to a variant's limits do not retroactively affect existing orders. Each order carries its own independent copy of the access constraints.

### Customer Download Dashboard

The customer dashboard **Downloads** tab (`/dashboard?tab=downloads`) shows:
* Download expiration date per item.
* Remaining download count (max − used).
* Secure download button (disabled if expired or exhausted).

---
---

### City, State & ZIP Formatting

Shipping addresses on the order review page, checkout success page, and email receipts now include the **state abbreviation** (for US orders) between the city and ZIP code:

* **Before**: `San Diego, 92103`
* **After**: `San Diego, CA 92103`

The state code is pulled from the `shipping_statecode` field on the order record when the shipping country is the United States or Canada.

# Dynamic Product Layout Selector

The storefront product details view supports **5 distinct responsive layouts**, which can be selected dynamically by store administrators on a per-product basis. This allows different products (e.g., standard merchandise vs. software licenses vs. video-centric products) to be presented in the most effective visual arrangement.

### Available Layout Options

1. **Right Side Images (Default)**:
   - **Desktop**: A two-column grid where the purchase options, pricing, variants, and customizations are placed in the left column (`lg:col-span-5`), and the image gallery, product description, and video preview are in the right column (`lg:col-span-7`).
   - **Mobile**: Stacks naturally with the gallery appearing at the top (`order-1` for gallery, `order-2` for buy box).
2. **Left Side Images**:
   - **Desktop**: A two-column grid where the image gallery, description, and video are on the left (`lg:col-span-7`), and the buy box options are on the right (`lg:col-span-5`).
3. **Right Side Images With Large Video Player Space Below**:
   - **Desktop**: Layout grid matching Right Side Images, but with the video player pulled out of the visual column and displayed as a full-width space below both columns for maximum visibility.
4. **Centered Layout With Images On Top**:
   - A centered single-column layout optimized for visual products. The image gallery is centered at the top (`max-w-3xl`), with the buy box details, description, and video preview nested below (`max-w-2xl`).
5. **Centered Layout With Large Video Player On Top**:
   - A centered single-column layout optimized for media-heavy or course-style products. The large video player is featured prominently at the top (`max-w-4xl`), with the gallery, buy box details, and description stacked below.

### Admin Configuration

Admins can configure the page layout in the **Product Edit** dashboard:
1. Navigate to `Admin -> Products` and select a product to edit.
2. Scroll to the **Advanced Settings** panel.
3. Locate the **Product Page Layout Option** dropdown.
4. Select one of the 5 options and click **Save Advanced Settings**.

### Technical Architecture (Modular Partials)

To prevent code duplication, the storefront product details view (`product-details.blade.php`) switches templates dynamically and imports the layout components from modular blade partials located in `resources/views/livewire/partials/`:

* **`product-gallery.blade.php`**: Renders the image carousel, hover-to-zoom effect, thumbnail selector, and full-screen lightbox modal using Alpine.js.
* **`product-buy-box.blade.php`**: Renders title, short description, pricing, flat or dependent variant selectors, personalization fields, and the add-to-cart controls.
* **`product-description.blade.php`**: Renders the parsed long description formatted with `@tailwindcss/typography` styling.
* **`product-video-player.blade.php`**: Dynamically inspects the active variant's `video_preview` field and renders an embedded YouTube/Vimeo player or HTML5 `<video>` element.

# FAQ Widget (CMS HTML Widget Library)

The HTML Widget Library drawer in the CMS page editor includes a **FAQ Accordion** snippet that renders a collapsible question/answer section. By default, all FAQ items are rendered in the **closed/collapsed state**. Customers expand individual items by clicking.

### Default Closed Behavior

The FAQ accordion uses Alpine.js `x-data` with `open: false` as the default state for each item:

```html
<div x-data="{ open: false }">
  <button @click="open = !open">Question text here</button>
  <div x-show="open" x-transition>Answer text here</div>
</div>
```

* Setting `open: true` would make an item start expanded.
* Multiple items can be open simultaneously (no auto-close of siblings by default).
* Insert the widget from the **Widget Library** drawer in the CMS editor and customize questions/answers inline in TinyMCE.

---

# Admin Failsafe & Editor Usability Features

To prevent administrative errors, save conflicts, and duplicate emails to customers, the admin dashboard incorporates several built-in failsafe and editor safety protocols:

### 1. CMS Page Version/Revision Locking (Tab Target Refocusing)
- **Problem**: When editing CMS pages, admins may open multiple browser tabs for the same page record. The auto-save revision engine will then record different state versions from different tabs, causing content synchronization conflicts.
- **Solution**: The CMS page editor dynamically sets the tab's `window.name` property to `cms_edit_{id}` on load. When an admin clicks the **Edit Page** floating button from the public website view, it checks if a browser window with that name exists. If it exists, the browser focuses it immediately **without reloading/navigating** it. This prevents duplicate editing sessions and protects unsaved edits from being overwritten by page reloads.

### 2. Double Confirmation Action Gates
- **Problem**: High-consequence email dispatch actions (such as resending order confirmations or sending digital download reminders to customers) can be triggered accidentally on a single click.
- **Solution**: These actions are guarded behind inline double-confirmation gates. Clicking the button changes the state to reveal **Confirm Send** and **Cancel** buttons, ensuring the action is deliberate and preventing duplicate customer emails.

### 3. Sequential Toast Alert Dispatching
- **Problem**: Livewire components perform dynamic DOM updates asynchronously without reloading the page. Traditional session-flash notifications only display once upon initial page load, failing to notify the user if the "Save" action is triggered repeatedly.
- **Solution**: Save actions dispatch browser-level `toast` events directly. An Alpine.js window-level listener catches these events, ensuring success notifications trigger and display reliably on every sequential save click without requiring page refreshes.

---

## Production Checklist

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- [ ] Configure database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
- [ ] Configure mail server (`MAIL_MAILER=smtp` + credentials).
- [ ] Add reCAPTCHA keys (`RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY`).
- [ ] Add OpenAI key (`OPENAI_API_KEY`) if using the AI Article Writer.
- [ ] Set `CDN_URL` if serving images through CloudFront or similar.
- [ ] Configure AWS S3 credentials if using S3 storage.
- [ ] Run `php artisan storage:link` so that `/storage/` symlink resolves (required for inline CMS image uploads).
- [ ] Run `php artisan migrate` to apply all database migrations.
- [ ] Run `npm run build` to compile and minify all Vite/Tailwind assets for production.
- [ ] Do **not** run `DevEcommerceSeeder` in production.

### Payment Processor Checklist (if using Stripe or Paddle)

- [ ] Install the required SDK(s): `composer require stripe/stripe-php` and/or `composer require paddlehq/paddle-php-sdk`.
- [ ] Add live production credentials to `.env` (`STRIPE_PUBLISHABLE_KEY`, `STRIPE_SECRET_KEY`, `STRIPE_WEBHOOK_SECRET`, etc.).
- [ ] Uncomment the relevant processor lines in `config/payment_processors.php`.
- [ ] In Admin → Checkout → Processors, set the desired processor as **Primary** and toggle **Production ON**.
- [ ] Register the webhook endpoint in Stripe Dashboard: `https://yourdomain.com/webhooks/stripe`
- [ ] Register the webhook endpoint in Paddle Dashboard: `https://yourdomain.com/webhooks/paddle`
- [ ] Copy the webhook signing secret from each dashboard into `.env` (`STRIPE_WEBHOOK_SECRET`, `PADDLE_WEBHOOK_SECRET`).
- [ ] Verify the **Production** toggle is **ON** for live mode (OFF = sandbox/test keys used).
- [ ] Test a real end-to-end checkout with a test card / sandbox account before going live.
- [ ] Remove or do not set `STRIPE_SANDBOX_*` / `PADDLE_SANDBOX_*` keys in production to prevent accidental sandbox usage.

---

# Plugin System

<a name="plugin-system-overview"></a>
## Overview

The plugin system is a fully dynamic, database-driven architecture that enables **display elements** (Swiper slideshows, galleries) and **shipping providers** (FedEx, UPS) to be registered, configured, and embedded into any CMS page or product description — without code changes or server restarts.

| Concept | Detail |
|---|---|
| **Discovery** | Built-in classes in `app/Plugins/` auto-boot via `PluginServiceProvider`. Drop-in folders in root `/plugins/` are scanned on every boot. |
| **Configuration** | All settings stored in `plugin_settings` table. Managed via Admin Panel at `/admin/plugins`. |
| **Embedding** | Display plugins use shortcodes: `[plugin:slug]` or `[plugin:slug param=value]`. |
| **Shipping** | Shipping plugins are invoked programmatically via the `PluginManager` singleton. |
| **Activation** | Toggle active/inactive in the Admin Panel. No artisan command needed. |

---

<a name="plugin-database-schema"></a>
## Database Schema

Three tables power the system — created by `2026_07_18_100000_create_plugins_tables.php`.

### `plugins` — Plugin Registry

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `api_id` | varchar 255, unique | Optional external API identifier |
| `name` | text | Human-readable display name |
| `version` | text | Version string |
| `type` | varchar 100 | `display`, `shipping`, `email`, `images` |
| `author` | text | Author or organization name |
| `filename` | varchar 150, unique | Slug used for class resolution |
| `install_type` | tinyint | `0` = drop-in file, `1` = built-in |
| `description` | text | Short description shown in admin |
| `shortcode` | varchar 150, unique | Used in `[plugin:shortcode]` |
| `activation_required` | varchar | `yes` or `no` |
| `activation_instructions` | text | Shown in the Activation tab |
| `activation_failed_msg` | text | |
| `activation_success_msg` | text | |
| `usage_instructions` | text | Shown in the Usage tab |
| `help_info` | text | Info box HTML |
| `help_url` | text | Link to external documentation |
| `activation_date` | dateTime | Timestamp of activation |
| `activation_status` | tinyint | `0` = inactive, `1` = active |
| `activation_key` | text | License/activation key |
| `serial_number` | text | |
| `created_at`, `updated_at` | timestamps | |

### `plugin_options` — Settings Form Schema

Defines the configuration form for each plugin. Fully data-driven — no hardcoded admin forms.

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `plugin_id` | unsignedBigInt | FK → `plugins.id` |
| `field_name` | text | Programmatic key (used in `plugin_settings`) |
| `field_label` | text | Label shown in admin form |
| `field_type` | text | `input`, `textarea`, `checkbox`, `select`, `text-only` |
| `field_data_format` | text | `string`, `float`, `integer`, `date` |
| `field_default_value` | longText | Default value |
| `field_selections` | text | Comma-separated values for `select` type |
| `field_min_value` | text | |
| `field_max_value` | text | |
| `field_editor` | text | `null` = plain textarea, `css` = dark CSS code editor |
| `field_help` | text | Help text shown below the field |
| `field_required` | varchar | `yes` or `no` |
| `field_error_msg` | text | |
| `field_html` | text | |
| `sort_order` | int | Display order in the form |

### `plugin_settings` — Saved Values

Stores the current configured value for each plugin field.

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | |
| `plugin_id` | unsignedBigInt | FK → `plugins.id` |
| `field_name` | text | Matches `plugin_options.field_name` |
| `field_value` | longText | Current saved value |

---

<a name="plugin-file-architecture"></a>
## File Architecture

```
app/
  Plugins/
    Contracts/
      DisplayPlugin.php          ← Interface all display plugins must implement
      ShippingPlugin.php         ← Interface all shipping plugins must implement
    Support/
      PluginManager.php          ← Singleton: discovers, registers, renders plugins
      ShortcodeProcessor.php     ← Parses [plugin:slug] shortcodes from content
      ShippingContext.php        ← Readonly DTO passed to shipping plugin getRates()
    Display/
      SlideshowPlugin.php        ← Built-in: Swiper.js slideshow display plugin
      FeaturedItemsPlugin.php    ← Built-in: Featured products grid/list/slider display
    Shipping/
      FedExPlugin.php            ← Built-in: FedEx REST API v1 shipping plugin
  Http/Controllers/
    PluginApiController.php      ← JSON endpoint: list active display plugins
  Livewire/
    AdminPlugins.php             ← Admin panel Livewire component
  Models/
    Plugin.php                   ← Eloquent model with getSettings/saveSettings helpers
    PluginOption.php             ← Options schema rows
    PluginSetting.php            ← Saved values rows
  Providers/
    PluginServiceProvider.php    ← Registers PluginManager singleton, boots built-ins

plugins/                         ← Drop-in external plugin folder (root level)
  .gitkeep
  my-custom-plugin/              ← Example drop-in plugin
    plugin.json
    MyCustomPlugin.php

resources/views/
  plugins/display/
    slideshow.blade.php          ← Swiper.js slideshow Blade renderer
    featured-items-grid.blade.php   ← Featured items: grid layout
    featured-items-list.blade.php   ← Featured items: list layout
    featured-items-slider.blade.php ← Featured items: Swiper slider layout
  livewire/
    admin-plugins.blade.php      ← Admin plugin manager UI

database/
  migrations/
    2026_07_18_100000_create_plugins_tables.php
  seeders/
    PluginSeeder.php             ← Seeds Slideshow + FedEx with all plugin_options

bootstrap/
  providers.php                  ← PluginServiceProvider registered here
```

---

<a name="shortcode-syntax"></a>
## Shortcode Syntax

Display plugins are embedded in CMS page content or product descriptions using the `[plugin:slug]` shortcode format — similar to WordPress shortcodes.

```
[plugin:slideshow-2026]
[plugin:slideshow-2026 id=3]
[plugin:slideshow-2026 id=2 nav=off paging=off]
```

### How it works

1. `ContentParserService` (which already handles Blade compilation) pipes content through `ShortcodeProcessor::process()` before final output.
2. `ShortcodeProcessor` uses `preg_replace_callback` to match all `[plugin:slug ...]` occurrences.
3. For each match, `PluginManager` looks up the registered `DisplayPlugin` instance by slug.
4. `plugin->render(array $params, Plugin $dbRecord)` is called — `$params` contains all key=value pairs from the shortcode.
5. The returned HTML string replaces the shortcode inline.

### Why `[plugin:slug]` instead of `{{plugin:slug}}`

Curly-brace syntax (`{{...}}`) conflicts with both Laravel Blade and Alpine.js. The square-bracket WordPress-style syntax was chosen to avoid all framework collisions while remaining editor-friendly inside TinyMCE.

---

<a name="built-in-plugins"></a>
## Built-in Plugins

<a name="slideshow-plugin"></a>
### Slideshow Plugin (type: `display`)

| Property | Value |
|---|---|
| **Shortcode** | `[plugin:slideshow-2026]` |
| **File** | `app/Plugins/Display/SlideshowPlugin.php` |
| **View** | `resources/views/plugins/display/slideshow.blade.php` |
| **Engine** | Swiper.js (CDN, loaded with `@once` to prevent conflicts when multiple slideshows appear on one page) |
| **Slide data managed at** | `/admin/slideshows` (the CMS Slideshow Builder) |

**Shortcode parameters:**

| Parameter | Default | Description |
|---|---|---|
| `id` | first active slideshow | Specific slideshow ID to display |
| `nav` | `on` | Show prev/next navigation arrows (`on`/`off`) |
| `paging` | `on` | Show pagination dots (`on`/`off`) |
| `autoplay` | `on` | Enable autoplay (`on`/`off`) |
| `speed` | `4000` | Autoplay delay in milliseconds |

**Admin-configurable settings:**

| Setting | Type | Description |
|---|---|---|
| Live CSS | Dark CSS editor | Editable CSS block applied on every render. Override slideshow appearance. |
| Default CSS | Read-only reference | Shows original default CSS. Cannot be edited. |

---

<a name="fedex-shipping-plugin"></a>
### FedEx Shipping Plugin (type: `shipping`)

| Property | Value |
|---|---|
| **Slug / Shortcode** | `fedex-api` |
| **File** | `app/Plugins/Shipping/FedExPlugin.php` |
| **API** | FedEx REST API v1 — OAuth2 client credentials |
| **Auth Endpoint** | `https://apis.fedex.com/oauth/token` |
| **Rate Endpoint** | `https://apis.fedex.com/rate/v1/rates/quotes` |
| **Activation Required** | Yes |

> ⚠️ **SOAP credentials are NOT compatible.** The old FedEx Web Services SOAP API has been retired. You must register at [developer.fedex.com](https://developer.fedex.com) to obtain REST API credentials.

**Settings (configured in Admin → Plugins → FedEx → Settings tab):**

| Setting Key | Label | Required | Description |
|---|---|---|---|
| `FedEx_Account` | FedEx Account Number | Yes | Your 9-digit FedEx account number |
| `FedEx_Access_ID` | API Client ID | Yes | OAuth2 Client ID from developer.fedex.com |
| `FedEx_Password` | API Client Secret | Yes | OAuth2 Client Secret |
| `FedEx_markup` | Rate Markup / Markdown ($) | No | Flat dollar amount added to every rate. Negative = discount. |
| `FedEx_NorthAmerica` | Enable N. America Rates | No | Toggle checkbox — quote domestic US/CA/MX shipments |
| `FedEx_International` | Enable International Rates | No | Toggle checkbox — quote international shipments |

**Service toggles:**

| Setting Key | Service |
|---|---|
| `FedEx_Ground` | FedEx Ground |
| `FedEx_Ground_Home_Delivery` | FedEx Ground Home Delivery |
| `FedEx_Express_Saver` | FedEx Express Saver (3-day) |
| `FedEx_2_Day_Air` | FedEx 2-Day Air |
| `FedEx_2_Day_Air_AM` | FedEx 2-Day Air A.M. |
| `FedEx_Priority_Overnight` | FedEx Priority Overnight |
| `FedEx_Standard_Overnight` | FedEx Standard Overnight |
| `FedEx_First_Overnight` | FedEx First Overnight |
| `FedEx_International_Priority` | FedEx International Priority |
| `FedEx_International_Economy` | FedEx International Economy |
| `FedEx_International_First` | FedEx International First |
| `FedEx_1_Day_Freight` | FedEx 1-Day Freight |
| `FedEx_2_Day_Freight` | FedEx 2-Day Freight |
| `FedEx_3_Day_Freight` | FedEx 3-Day Freight |

---

<a name="ups-shipping-plugin"></a>
### UPS Shipping Plugin (type: `shipping`)

| Property | Value |
|---|---|
| **Slug / Shortcode** | `ups-api` |
| **File** | `app/Plugins/Shipping/UpsPlugin.php` |
| **API** | UPS REST Rating API v2205 — OAuth2 client credentials |
| **Auth Endpoint** | `https://onlinetools.ups.com/security/v1/oauth/token` |
| **Rate Endpoint** | `https://onlinetools.ups.com/api/rating/v2205/Shop` |
| **Activation Required** | Yes |

> 💡 The UPS REST API uses a `Shop` endpoint that returns **all eligible services in a single request** — there is no per-service round-trip. The plugin then filters by each service's enable/disable toggle before displaying rates to the customer.

**Settings (configured in Admin → Plugins → UPS → Settings tab):**

| Setting Key | Label | Required | Description |
|---|---|---|---|
| `UPS_Client_ID` | UPS Client ID (OAuth2) | Yes | From developer.ups.com → My Apps |
| `UPS_Client_Secret` | UPS Client Secret (OAuth2) | Yes | Keep confidential — never expose in front-end code |
| `UPS_Account_Number` | UPS Account Number | No | Required only for negotiated / discounted rates. Leave blank for retail rates. |
| `UPS_From_Zip` | Origin ZIP Code | Yes | ZIP code your shipments originate from |
| `UPS_From_Country` | Origin Country Code | Yes | 2-letter ISO code (e.g. `US`, `CA`) |
| `UPS_Markup` | Rate Markup / Markdown ($) | No | Flat dollar amount added to every rate. Negative = discount. e.g. `2.50` or `-1.00`. |

**Service toggles (domestic):**

| Setting Key | Service | Notes |
|---|---|---|
| `UPS_Ground` | UPS Ground | 1–5 business days |
| `UPS_Ground_Saver` | UPS Ground Saver | Economy ground |
| `UPS_3_Day_Select` | UPS 3 Day Select | Guaranteed 3-day |
| `UPS_2nd_Day_Air` | UPS 2nd Day Air | 2-day delivery |
| `UPS_2nd_Day_Air_AM` | UPS 2nd Day Air A.M. | Morning delivery guarantee |
| `UPS_Next_Day_Air_Saver` | UPS Next Day Air Saver | Next business day, end of day |
| `UPS_Next_Day_Air` | UPS Next Day Air | Next business day, by 10:30 AM |
| `UPS_Next_Day_Air_Early` | UPS Next Day Air Early | Next business day, by 8:00 AM |

**Service toggles (international):**

| Setting Key | Service |
|---|---|
| `UPS_International_Economy` | UPS Worldwide Economy |
| `UPS_International_Expedited` | UPS Worldwide Expedited |
| `UPS_Worldwide_Express` | UPS Worldwide Express |
| `UPS_Worldwide_Express_Plus` | UPS Worldwide Express Plus |
| `UPS_Worldwide_Saver` | UPS Worldwide Saver |

---

<a name="usps-shipping-plugin"></a>
### USPS Shipping Plugin (type: `shipping`)

| Property | Value |
|---|---|
| **Slug / Shortcode** | `usps-api` |
| **File** | `app/Plugins/Shipping/UspsPlugin.php` |
| **API** | USPS REST API v3 — OAuth2 client credentials |
| **Auth Endpoint** | `https://api.usps.com/oauth2/v3/token` |
| **Domestic Rate Endpoint** | `https://api.usps.com/prices/v3/total-rates/search` |
| **International Rate Endpoint** | `https://api.usps.com/international-prices/v3/total-rates/search` |
| **Activation Required** | Yes |

> ⚠️ **The legacy USPS Web Tools XML/SOAP API was deprecated January 2024.** Old credentials obtained from the original USPS registration are NOT compatible with the v3 REST API. You must create a new account at [developer.usps.com](https://developer.usps.com).

> 💡 Unlike UPS and FedEx, USPS quotes one service at a time. The plugin queries each enabled service in parallel (separate HTTP calls per service). Disabled services are skipped — enable only the services relevant to your operation to minimize API calls and page load time.

**Settings (configured in Admin → Plugins → USPS → Settings tab):**

| Setting Key | Label | Required | Description |
|---|---|---|---|
| `USPS_Client_ID` | USPS Client ID (OAuth2) | Yes | From developer.usps.com → My Apps |
| `USPS_Client_Secret` | USPS Client Secret (OAuth2) | Yes | Keep confidential |
| `USPS_From_Zip` | Origin ZIP Code | Yes | 5-digit US ZIP code your shipments originate from |
| `USPS_Markup` | Rate Markup / Markdown ($) | No | Flat dollar amount added to every rate. Negative = discount. |

**Domestic service toggles:**

| Setting Key | Service | Approx. Transit |
|---|---|---|
| `USPS_Priority_Mail` | Priority Mail | 1–3 days |
| `USPS_Priority_Mail_Express` | Priority Mail Express | Overnight–2 days |
| `USPS_Ground_Advantage` | USPS Ground Advantage | 2–5 days |
| `USPS_First_Class_Package` | First-Class Package Service | 2–3 days (≤ 15.99 oz) |
| `USPS_Parcel_Select` | Parcel Select | 2–9 days (economy) |
| `USPS_Parcel_Select_Lightweight` | Parcel Select Lightweight | 2–9 days (very light) |
| `USPS_Priority_Mail_Cubic` | Priority Mail Cubic | 1–3 days (small dense packages) |

**International service toggles:**

| Setting Key | Service |
|---|---|
| `USPS_Priority_Mail_Express_Intl` | Priority Mail Express International |
| `USPS_Priority_Mail_Intl` | Priority Mail International |
| `USPS_First_Class_Package_Intl` | First-Class Package International |

---

<a name="featured-items-plugin"></a>
### Featured Items Plugin (type: `display`)

| Property | Value |
|---|---|
| **Shortcode** | `[plugin:featured-items]` |
| **File** | `app/Plugins/Display/FeaturedItemsPlugin.php` |
| **Views** | `resources/views/plugins/display/featured-items-{grid,list,slider}.blade.php` |
| **Engine** | Swiper.js (CDN, loaded with `@once` — safe to use alongside the slideshow plugin) |
| **Activation Required** | No (active by default) |
| **Data source** | Products with `featured_item = 1` — toggled per-product in Admin |

The Featured Items plugin renders your hand-picked featured products into any CMS page. Supports three display modes — **grid**, **list**, and **Swiper slider** — all visually consistent with the main `/shop` catalog design.

#### Marking a Product as Featured

1. Go to **Admin → E-Commerce → Products** and open any product.
2. Click **Advanced Settings** in the section nav.
3. Enable the **★ Featured Item** toggle.
4. Click **Save Advanced Settings**.

The product will now appear in every `[plugin:featured-items]` shortcode on the site.

#### Shortcode Parameters

All parameters are optional. Plugin-level defaults (configurable in Admin → Plugins → Featured Items → Settings) apply when a parameter is omitted from the shortcode.

| Parameter | Default | Description |
|---|---|---|
| `display` | `grid` | Display mode: `grid`, `list`, or `slider` |
| `max` | `12` | Maximum number of featured products to show (1–100) |
| `cols` | `4` | Grid columns in grid mode: `2`, `3`, `4`, `5`, or `6` |
| `sort` | `random` | Sort order: `random`, `newest`, or `name` |
| `header` | *(blank)* | Optional section heading displayed above the products |
| `slides` | `4` | Number of visible cards at desktop breakpoint (slider mode only) |
| `nav` | `on` | Show prev/next navigation arrows in slider mode: `on` / `off` |
| `autoplay` | `on` | Enable automatic slide advancement in slider mode: `on` / `off` |
| `speed` | `4000` | Autoplay delay in milliseconds (slider mode only, min 500) |

#### Shortcode Examples

```
[plugin:featured-items]

[plugin:featured-items display=slider slides=4 header="Editor's Picks" autoplay=on speed=5000]

[plugin:featured-items display=grid cols=3 max=6 sort=newest header="New Arrivals"]

[plugin:featured-items display=list max=8 sort=name]

[plugin:featured-items display=grid cols=4 max=12 sort=random nav=off]
```

#### Display Modes

| Mode | Description | Best For |
|---|---|---|
| `grid` | Multi-column card grid with configurable column count | Home pages, landing sections |
| `list` | Horizontal row cards (thumbnail left, info right) | Compact feature sections, sidebars |
| `slider` | Swiper.js horizontal carousel with touch/drag support | Premium hero sections, promotional banners |

All three modes show:
- Product thumbnail (or icon placeholder if no image)
- Product title with link to product detail page
- Truncated short description
- Price (with strikethrough original price when on sale)
- **Buy Now** button (single-variant products) or **View Options** link (multi-variant)
- **★ Featured** amber badge on the product image
- **Sale** badge when `on_sale = 1`

#### Swiper & Slideshow Plugin Coexistence

The slider view uses `@once` to load Swiper CSS and JS from CDN. If the **Slideshow Plugin** is also on the same page, Swiper is only loaded once — there is **no conflict or double-loading**. Each slider instance gets a unique auto-generated ID (`fi_xxxxxxxx`) so multiple Featured Items shortcodes on the same page work independently.

#### Admin-Configurable Settings

Default values for all parameters can be set globally in **Admin → Plugins → Featured Items Display → Settings**:

| Setting Key | Label | Default | Description |
|---|---|---|---|
| `display` | Default Display Mode | `grid` | Applied when `display=` is omitted from the shortcode |
| `max_items` | Max Items to Show | `12` | Applied when `max=` is omitted |
| `sort_order` | Default Sort Order | `random` | Applied when `sort=` is omitted |
| `header_title` | Default Section Header | *(blank)* | Applied when `header=` is omitted |
| `grid_columns` | Grid Columns (default) | `4` | Applied when `cols=` is omitted |
| `slides_visible` | Slider: Visible Slides (Desktop) | `4` | Applied when `slides=` is omitted |
| `show_nav` | Slider: Show Navigation Arrows | `on` | Applied when `nav=` is omitted |
| `autoplay` | Slider: Autoplay | `on` | Applied when `autoplay=` is omitted |
| `autoplay_speed` | Slider: Autoplay Speed (ms) | `4000` | Applied when `speed=` is omitted |

> **Shortcode parameters always override plugin settings.** Plugin settings are only used as fallbacks when a parameter is not present in the shortcode.

---

<a name="shipping-provider-setup-guide"></a>
## Shipping Provider Setup Guide

<a name="how-realtime-rates-work"></a>
### How Realtime Rates Work

When a customer reaches the **Checkout Review** page (`/checkout/review`), the system:

1. Calculates the cart weight (sum of `item_qty × item_weight` for all shippable items)
2. Calls `OrderReview::buildShippingOptions()` which:
   - Fetches **flat-rate / admin-configured options** from `ShippingCalculationService`
   - Fetches **realtime API rates** from every active (activated + enabled) shipping plugin via `PluginManager::getShippingRates()`
3. Merges all options and **sorts them low-to-high by price**
4. Displays the combined list as radio buttons — the customer picks their preferred service
5. The selected option's amount and name are used in `calculateTotals()` and written to the order record at placement

```
Cart Items (weights) ──►  buildShippingOptions()
 Customer Address     ──►    ├── ShippingCalculationService (flat rates)
                             └── PluginManager::getShippingRates(ShippingContext)
                                   ├── FedExPlugin::getRates()  (if activated)
                                   ├── UpsPlugin::getRates()    (if activated)
                                   └── UspsPlugin::getRates()   (if activated)
                                           │
                             Merged + sorted low→high
                                           │
                             Radio buttons on /checkout/review
```

> **Graceful degradation**: If a carrier API is unreachable or returns an error, that plugin silently returns an empty array and logs to `storage/logs/laravel.log`. The customer still sees all other available options — checkout is never blocked by a shipping API failure.

---

<a name="fedex-setup"></a>
### FedEx Setup — Step by Step

#### 1. Create a FedEx Developer Account

1. Go to [developer.fedex.com](https://developer.fedex.com)
2. Click **Sign Up** and create an account (or sign in with your FedEx account)
3. Navigate to **My Projects** → **Create a Project**
4. Select **Shipping** → **Rate API** and complete the project creation
5. Copy your **Client ID** (also called API Key) and **Client Secret** (also called Secret Key)

#### 2. Locate Your FedEx Account Number

Your FedEx Account Number is the 9-digit number associated with your FedEx shipping account. Find it:
- On any FedEx invoice
- In your FedEx profile at [fedex.com](https://www.fedex.com) → Account Management

#### 3. Enter Credentials in the Admin Panel

1. Go to **Admin → Plugins**
2. Find **Shipping Rates - FedEx REST API** and click **Settings**
3. In the **Settings** tab, enter:
   - **FedEx Account Number** — your 9-digit account
   - **API Client ID** — from developer.fedex.com
   - **API Client Secret** — from developer.fedex.com
   - **Origin ZIP Code** — your warehouse/ship-from ZIP (set via FedEx_markup field)
   - **Rate Markup** — `0.00` for pass-through, or a dollar amount to add
4. Enable the service checkboxes you want to offer customers
5. Enable **N. America** and/or **International** rate regions as applicable
6. Click **Save Settings**

#### 4. Activate the Plugin

1. Switch to the **Activation** tab in the Settings panel
2. Click **Activate Plugin**
3. The plugin's activation status will turn green
4. Alternatively, use the toggle switch in the plugin list to enable/disable without full activation

#### 5. Test

- Add a shippable product to your cart and proceed to `/checkout/review`
- FedEx rates should appear in the shipping selector, mixed with any flat rates, sorted low-to-high
- If no rates appear, check `storage/logs/laravel.log` for `FedEx Auth Error` or `FedEx Rate API Error` entries

---

<a name="ups-setup"></a>
### UPS Setup — Step by Step

#### 1. Create a UPS Developer Account

1. Go to [developer.ups.com](https://developer.ups.com)
2. Click **Get Started** → **Sign In / Register** using your UPS.com account (or create one)
3. Navigate to **My Apps** → **Add App**
4. Select **Rating** as the product, give your app a name, and agree to the terms
5. Copy your **Client ID** and **Client Secret** from the app detail page

> 💡 **Negotiated rates**: If you have a UPS account number and want negotiated/discounted rates instead of retail rates, enter your account number in the `UPS_Account_Number` setting. Without an account number, the plugin returns published retail rates.

#### 2. Enter Credentials in the Admin Panel

1. Go to **Admin → Plugins**
2. Find **Shipping Rates - UPS REST API (2026)** and click **Settings**
3. In the **Settings** tab, enter:
   - **UPS Client ID** — from developer.ups.com
   - **UPS Client Secret** — from developer.ups.com
   - **UPS Account Number** — optional, for negotiated rates
   - **Origin ZIP Code** — your warehouse/ship-from ZIP
   - **Origin Country Code** — 2-letter ISO (e.g. `US`)
   - **Rate Markup** — `0.00` for pass-through, or a dollar amount
4. Enable the service checkboxes for the UPS services you want to display
5. Click **Save Settings**

#### 3. Activate the Plugin

1. Switch to the **Activation** tab
2. Click **Activate Plugin**
3. The plugin's status indicator turns green

#### 4. Service Selection Tips

| If your customers are... | Enable these services |
|---|---|
| Mostly domestic / budget-conscious | UPS Ground, UPS Ground Saver |
| Domestic with express options | + 3 Day Select, 2nd Day Air, Next Day Air Saver |
| International | UPS Worldwide Economy, Worldwide Expedited |
| Premium international | + Worldwide Express, Worldwide Express Plus |

#### 5. Test

- Add a shippable product to cart and proceed to `/checkout/review`
- UPS rates should appear interleaved with any other configured rates, sorted low-to-high
- Check `storage/logs/laravel.log` for `UPS Auth Error` or `UPS Rate API Error` if no rates appear

---

<a name="usps-setup"></a>
### USPS Setup — Step by Step

> ⚠️ **Important**: The old USPS Web Tools API (using the `UserId` XML parameter) was deprecated on **January 15, 2024**. This plugin uses the new [USPS APIs REST portal](https://developer.usps.com). Old credentials will **not** work — you must register at the new portal.

#### 1. Create a USPS Developer Account

1. Go to [developer.usps.com](https://developer.usps.com)
2. Click **Sign Up** and complete the registration (a USPS Business Customer Gateway account may be required)
3. Once logged in, click **My Apps** → **Add App**
4. Under **APIs**, select:
   - **Prices** (for domestic rates)
   - **International Prices** (if you ship internationally)
5. Accept the terms and create the app
6. Copy your **Consumer Key** (Client ID) and **Consumer Secret** (Client Secret)

#### 2. Enter Credentials in the Admin Panel

1. Go to **Admin → Plugins**
2. Find **Shipping Rates - USPS REST API v3 (2026)** and click **Settings**
3. In the **Settings** tab, enter:
   - **USPS Client ID** — Consumer Key from developer.usps.com
   - **USPS Client Secret** — Consumer Secret from developer.usps.com
   - **Origin ZIP Code** — your 5-digit US warehouse ZIP (required — USPS only ships from US)
   - **Rate Markup** — `0.00` for pass-through, or a dollar amount
4. Enable the domestic services you want:
   - **Recommended starting set**: Priority Mail + Priority Mail Express + Ground Advantage
   - Enable First-Class Package only for packages under 15.99 oz
   - Enable Parcel Select for high-volume economy shipping
5. Enable international services only if you ship internationally from the US
6. Click **Save Settings**

#### 3. Activate the Plugin

1. Switch to the **Activation** tab
2. Click **Activate Plugin**
3. The plugin status turns green

#### 4. Performance Note

Unlike UPS (which fetches all services in one request), the USPS plugin makes **one API call per enabled service**. For best performance:
- Enable only the services you actually offer
- A typical store needs 2–3 services (e.g. Ground Advantage + Priority Mail + Priority Mail Express)
- Each additional enabled service adds ~200–400ms to the checkout review page load

#### 5. Domestic vs. International

- The plugin automatically detects if the customer's destination country is `US` and uses the domestic endpoint
- All other countries use the international endpoint
- USPS only supports international shipping from US origin — if your origin country is not US, disable this plugin for international customers and use FedEx or UPS instead

#### 6. Test

- Add a shippable product to cart, proceed to `/checkout/review`
- USPS rates should appear alongside other options, sorted low-to-high
- If no rates appear, check `storage/logs/laravel.log` for `USPS Auth Error` or `USPS Plugin Exception` entries
- Common issues: invalid ZIP code in `USPS_From_Zip`, or selecting First-Class for packages over 15.99 oz (the USPS API returns an error for ineligible combinations — the plugin silently skips those)

---

<a name="plugin-admin-panel"></a>
## Admin Panel

**URL:** `/admin/plugins`

### Plugin List Table

Columns: Name + description, Type badge (color-coded), Shortcode, Version, Active toggle, Settings button.

Type badge colors: `display` = indigo, `shipping` = amber, `email` = emerald, other = slate.

The active toggle saves immediately via Livewire — no page reload or save button required.

### Settings Panel

Clicking **Settings** on any row opens a slide-in right panel with three tabs:

**Settings Tab** — data-driven form rendered from `plugin_options` rows:

| `field_type` | `field_editor` | Renders As |
|---|---|---|
| `input` | — | Monospace text input |
| `textarea` | — | Plain resizable textarea |
| `textarea` | `css` | Dark code editor (slate-900 bg, green monospace text) |
| `checkbox` | — | Animated on/off toggle switch |
| `select` | — | Dropdown (values from `field_selections` comma list) |
| `text-only` | — | Read-only `<pre><code>` reference block |

**Usage Tab** — shortcode with one-click copy button, `usage_instructions` HTML rendered, external docs link.

**Activation Tab** — only shown when `activation_required = yes`. Shows activation instructions, key entry field, and activate/deactivate controls.

---

<a name="creating-a-built-in-plugin"></a>
## Creating a New Built-in Plugin

### Step 1 — Create the PHP Class

**Display plugin** (`app/Plugins/Display/MyPlugin.php`):
```php
<?php
namespace App\Plugins\Display;

use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;

class MyPlugin implements DisplayPlugin
{
    public function slug(): string { return 'my-plugin'; }
    public function name(): string { return 'My Custom Plugin'; }

    public function render(array $params, Plugin $plugin): string
    {
        $mySetting = $plugin->getSetting('my_setting', 'default_value');
        return view('plugins.display.my-plugin', compact('params', 'mySetting'))->render();
    }
}
```

**Shipping plugin** (`app/Plugins/Shipping/MyShipper.php`) — implement `getRates()` and return:
```php
return [
    ['label' => 'Standard Rate', 'rate' => 12.50, 'days' => 3, 'code' => 'MY_CODE'],
    ['label' => 'Express Rate',  'rate' => 24.00, 'days' => 1, 'code' => 'MY_EXPRESS'],
];
```

### Step 2 — Register in PluginServiceProvider

In `app/Providers/PluginServiceProvider.php`, add to the `boot()` method:
```php
$manager->register(\App\Plugins\Display\MyPlugin::class);
```

### Step 3 — Seed the Database Record

Add to `PluginSeeder.php` (or create a dedicated seeder):
```php
$plugin = Plugin::updateOrCreate(
    ['filename' => 'my_plugin'],
    [
        'name'                => 'My Custom Plugin',
        'shortcode'           => 'my-plugin',
        'type'                => 'display',
        'author'              => 'Your Name',
        'version'             => '1.0',
        'install_type'        => 1,
        'activation_required' => 'no',
        'activation_status'   => 1,
        'description'         => 'Short description',
    ]
);

PluginOption::create([
    'plugin_id'           => $plugin->id,
    'field_name'          => 'my_setting',
    'field_label'         => 'My Setting Label',
    'field_type'          => 'input',
    'field_required'      => 'no',
    'sort_order'          => 10,
    'field_default_value' => 'default',
]);
```

Then run:
```bash
php artisan db:seed --class=PluginSeeder
```

---

<a name="creating-a-drop-in-plugin"></a>
## Creating a Drop-in External Plugin

Drop-in plugins are placed in the root `/plugins/` directory and discovered automatically on every application boot — no code changes to the platform are required.

### Folder Structure
```
plugins/
  my-custom-plugin/
    plugin.json          ← Required manifest
    MyCustomPlugin.php   ← PHP class (must match "class" value in plugin.json)
```

### `plugin.json` Format
```json
{
  "class": "MyCustomPlugin",
  "name": "My Custom Plugin",
  "version": "1.0",
  "type": "display",
  "shortcode": "my-custom",
  "description": "A drop-in custom plugin",
  "author": "Your Name",
  "options": [
    {
      "field_name": "api_key",
      "field_label": "API Key",
      "field_type": "input",
      "field_required": "yes",
      "sort_order": 10,
      "field_default_value": ""
    }
  ]
}
```

### Discovery Flow (`PluginManager::discoverExternalPlugins()`)
1. Scans all subdirectories of `/plugins/` for `plugin.json`
2. `require_once`s the matching PHP class file
3. Calls `syncExternalPlugin()` — upserts the `plugins` DB record and its `plugin_options` rows
4. Registers the class instance with `PluginManager`

The plugin will appear in the Admin Panel immediately after the next page load.

---

<a name="accessing-plugin-settings-in-code"></a>
## Accessing Plugin Settings in Code

```php
use App\Models\Plugin;

$plugin = Plugin::where('shortcode', 'slideshow-2026')->first();

// Get all settings as ['field_name' => 'field_value'] array
$settings = $plugin->getSettings();

// Get a single setting with fallback default
$css = $plugin->getSetting('live_css', '');

// Save settings (updateOrCreate on plugin_id + field_name)
$plugin->saveSettings(['live_css' => '.wrapper { width: 100%; }']);

// Get the options schema ordered by sort_order
$schema = $plugin->getOptionsSchema(); // Collection of PluginOption models

// Query active plugins by type
$activeDisplay = Plugin::active()->ofType('display')->get();
```

### Using PluginManager directly
```php
use App\Plugins\Support\PluginManager;

$manager = app(PluginManager::class);

// Render a display plugin by slug
$html = $manager->renderDisplay('slideshow-2026', ['id' => '3']);

// Get shipping rates
use App\Plugins\Support\ShippingContext;

$context = new ShippingContext(
    fromZip: '75001',
    toZip: '10001',
    toCountry: 'US',
    weightLbs: 2.5,
    declaredValue: 99.99
);
$rates = $manager->getShippingRates($context); // All active shipping plugins
```

---

<a name="plugin-deployment-checklist"></a>
## Plugin Deployment Checklist

After uploading all plugin files to a new server, run these commands in order:

```bash
# 1. Create the three plugin tables
php artisan migrate

# 2. Seed all built-in plugins (Slideshow, Featured Items, FedEx, UPS, USPS) with options and default settings
php artisan db:seed --class=PluginSeeder

# 3. Clear all caches so PluginServiceProvider is picked up
php artisan optimize:clear
```

> **No other plugin-specific artisan commands are required.** `PluginServiceProvider` is registered in `bootstrap/providers.php` and auto-loads on every request.

> **Re-running the seeder is safe.** All records use `updateOrCreate` — running the seeder on an existing install will update metadata and add any new options without overwriting existing saved settings values.

### Routes Added

| Method | URI | Handler | Name |
|---|---|---|---|
| GET | `/admin/plugins` | `AdminPlugins` Livewire | `admin.plugins.index` |
| GET | `/admin/plugins/list-display` | `PluginApiController@listDisplay` | `admin.plugins.list-display` |

### Credential Storage

All carrier credentials (FedEx Client ID/Secret, UPS Client ID/Secret, USPS Client ID/Secret) are stored in the `plugin_settings` table via the Admin Panel — **not** in `.env`. No environment variables are needed for the plugin system itself.

This means:
- Credentials survive `php artisan config:clear` and cache clears
- Multiple environments (staging, production) can have different credentials stored in their respective databases
- Credentials are never committed to version control

---

## CMS Code / Video Embed Manager

### Overview

The **CMS Code / Video Embed Manager** provides a site-wide library of reusable HTML and video embed snippets that can be inserted into any CMS page, product description, or list menu item using a simple shortcode. The embed code is stored centrally in the database and is **never exposed to the TinyMCE editor** — this solves two critical problems:

1. **TinyMCE Protection** — Rich-text editors routinely reformat, strip attributes from, or entirely remove HTML they do not recognise (iframes, custom scripts, third-party widgets). By storing embed code in a dedicated table and inserting it only at render time via shortcode, the code is completely invisible to the editor and is never modified.

2. **Single-Source Updates** — If the same video or widget is embedded on ten different pages, you only need to update it in one place. Every page that references the shortcode will automatically reflect the change on next load.

---

### 1. Database Table — `cms_embeds`

| Column | Type | Default | Description |
|---|---|---|---|
| `id` | `bigint` (PK, auto) | — | Unique embed ID |
| `name` | `varchar(255)` | — | Internal admin label — never shown publicly |
| `embed_type` | `tinyint unsigned` | `0` | `0` = YouTube · `1` = Vimeo · `2` = Other HTML |
| `code_snippet` | `text` | `null` | Raw HTML/iframe code stored verbatim |
| `is_active` | `boolean` | `true` | Inactive embeds render as an HTML comment |
| `created_at` | `timestamp` | — | — |
| `updated_at` | `timestamp` | — | — |

Migration: `database/migrations/2026_07_19_100000_create_cms_embeds_table.php`

---

### 2. Admin Interface

**Route prefix:** `/admin/cms-embeds`

| URL | Route Name | Description |
|---|---|---|
| `/admin/cms-embeds` | `admin.cms-embeds.index` | List all embed records |
| `/admin/cms-embeds/create` | `admin.cms-embeds.create` | Create a new embed |
| `/admin/cms-embeds/{id}/edit` | `admin.cms-embeds.edit` | Edit an existing embed |

**Access points:**
- **Top nav CMS dropdown** → **Code Embeds** (active-highlighted when on any `admin.cms-embeds.*` route)
- **Responsive/mobile nav** → CMS Sections → **Code Embeds**
- **CMS Admin Hub sidebar** (`/admin/cms`) → **Code Embeds** with `</>` code-bracket icon

#### Index Page (`AdminCmsEmbeds`)
Displays all embed records in a searchable, filterable table:
- **Search** — filters by name
- **Type filter** — All / YouTube / Vimeo / Other HTML
- **Active filter** — All / Active / Inactive
- **Inline active toggle** — enable/disable without entering the edit form
- **Shortcode preview** — displays `[code-embed:{id}]` in monospace for easy copying
- **Type badge** — colour-coded: 🔴 YouTube · 🔵 Vimeo · ⬜ Other HTML
- **Delete** — with confirmation prompt

#### Edit Form (`AdminCmsEmbedEdit`)
Two-panel layout:

**Left panel — Details:**
- `Name` — internal admin label
- `Active` — toggle; inactive embeds render silently as `<!-- [embed-inactive: N] -->`
- `Embed Type` — three radio buttons (YouTube / Vimeo / Other HTML) with contextual help text per type
- **Shortcode badge** (existing records) — shows `[code-embed:{id}]` with a one-click Copy button and the optional label variant `[code-embed:{id} label="..."]`

**Right panel — Code Snippet:**
- Raw **monospace `<textarea>`** — intentionally **not** connected to TinyMCE. Protects the code from reformatting.
- Contextual placeholder and help text changes with the selected type.
- **Live Preview** — renders below the textarea:
  - YouTube/Vimeo → shown inside the responsive 16:9 wrapper
  - Other HTML → shown with a safety notice (admin-only content)

---

### 3. Shortcode Syntax

```
[code-embed:{id}]
[code-embed:{id} label="Optional Label"]
```

**Examples:**

```
[code-embed:1]
[code-embed:3 label="Product Demo Video"]
[code-embed:7]
```

The `label` parameter is reserved for future use (e.g. accessible titles or captions). Currently the snippet renders without a visible label element; label support can be added to `ShortcodeProcessor::renderCodeEmbed()` without a schema change.

---

### 4. Shortcode Processing — Pipeline Integration

`[code-embed:N]` is resolved by **Pipeline B** (`ContentParserService::parse()` → `ShortcodeProcessor::process()`). It runs as **Pass 1a**, before the existing download and plugin passes:

```
Pass 1a → [code-embed:N]    ← NEW
Pass 1b → [download:N]
Pass 2  → [plugin:slug]
```

Because **Pipeline A** (the `ProcessShortcodes` middleware) scans the final HTML of every public page response, `[code-embed:N]` shortcodes placed in any field that goes through Pipeline B are also covered by Pipeline A. The effective shortcode support matrix is:

| Content Field | `[code-embed:N]` |
|---|:---:|
| CMS Page — main body | ✅ Pipeline B |
| CMS Page — left sidebar | ✅ Pipeline B |
| CMS Page — right sidebar | ✅ Pipeline B |
| Product short description | ✅ Pipeline B |
| Product long description | ✅ Pipeline B |

> See the **Shortcode Support Matrix** section for the full cross-shortcode comparison.

---

### 5. Rendering — Three Output Modes

`ShortcodeProcessor::renderCodeEmbed()` selects one of three strategies based on `embed_type`:

#### Mode A — Responsive Video Wrapper (YouTube / Vimeo)
**Triggered when:** `embed_type` is `0` (YouTube) or `1` (Vimeo)

Wraps the stored `code_snippet` in a responsive 16:9 container. This guarantees the iframe scales correctly regardless of the embed provider's default sizing:

```html
<style id="cms-embed-css">
  .cms-embed-video-outer { max-width: 75%; margin: 0 auto; }
  .cms-embed-video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; }
  .cms-embed-video-wrapper iframe,
  .cms-embed-video-wrapper object,
  .cms-embed-video-wrapper embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
  @media (max-width: 1000px) { .cms-embed-video-outer { max-width: 100%; } }
</style>
<div class="cms-embed-video-outer">
  <div class="cms-embed-video-wrapper">
    <iframe src="https://www.youtube.com/embed/..." ...></iframe>
  </div>
</div>
```

> **CSS deduplication:** The `<style id="cms-embed-css">` block is emitted only once per PHP request via a `static bool $embedCssLoaded` flag on `ShortcodeProcessor`. If multiple YouTube/Vimeo embed shortcodes appear on the same page, only the first emits the CSS — identical to the Video.js deduplication pattern used by the CMS Downloads feature.

#### Mode B — Raw HTML (Other)
**Triggered when:** `embed_type` is `2`

Outputs `code_snippet` verbatim with no wrapper or modification. Suitable for any third-party widget, custom HTML block, or code snippet that should not be wrapped in a container.

#### Mode C — Inactive / Empty
- **Inactive record** → `<!-- [embed-inactive: N] -->`
- **Empty snippet** → `<!-- [embed-empty: N] -->`
- **Render error** → `<!-- [embed-error: N] -->` (also logged via `Log::error`)

---

### 6. Model — `App\Models\CmsEmbed`

| Method / Constant | Returns | Description |
|---|---|---|
| `TYPE_YOUTUBE = 0` | `int` | Embed type constant |
| `TYPE_VIMEO = 1` | `int` | Embed type constant |
| `TYPE_OTHER = 2` | `int` | Embed type constant |
| `isVideo(): bool` | `bool` | `true` for YouTube and Vimeo types |
| `typeLabel(): string` | `string` | `'YouTube'`, `'Vimeo'`, or `'Other HTML'` |
| `typeBadgeColor(): string` | `string` | Tailwind CSS classes for the admin type badge |
| `shortcode(): string` | `string` | Returns `[code-embed:{id}]` |

---

### 7. Adding a New Embed — Step-by-Step

1. Go to **Admin → CMS → Code Embeds** (top nav dropdown or CMS sidebar)
2. Click **New Embed**
3. Enter an internal **Name** (e.g. `"Homepage Intro Video"`)
4. Select the **Embed Type** (YouTube, Vimeo, or Other HTML)
5. Paste the full embed code (e.g. the `<iframe>` from YouTube's Share → Embed panel) into the **Code Snippet** textarea
6. Click **Save Embed**
7. Copy the generated shortcode (e.g. `[code-embed:5]`) from the **Shortcode** panel
8. Paste the shortcode into any CMS page body, sidebar column, or product description — it will render the embed when the page loads

---

### 8. Use Cases

| Use Case | Type | Example |
|---|---|---|
| YouTube tutorial video | YouTube | Paste YouTube `<iframe>` — responsive wrapper applied automatically |
| Vimeo product showcase | Vimeo | Paste Vimeo `<iframe>` — responsive wrapper applied automatically |
| Third-party chat widget | Other HTML | Raw `<script>` embed from Intercom, Crisp, etc. |
| Custom HTML code block | Other HTML | Syntax-highlighted `<pre><code>` blocks |
| External booking calendar | Other HTML | Calendly or similar embedded widget |
| Maps embed | Other HTML | Google Maps `<iframe>` (use Other HTML for full control) |


---

## Product Variant Event Details Manager

### Overview

The Event Details feature integrates event scheduling metadata directly into the **product variant** level. Rather than maintaining a separate events table linked to a product, each event date/session is modelled as its own **product variant** — with its own price, SKU, sale price, and inventory count. This design solves the classic pricing-per-date problem: the product itself is the "event series" (e.g. *Digital Marketing Seminar*) and each variant is an individual session (e.g. *Sept 16 — 9 AM*, *Sept 24 — 1 PM*, *Oct 1 — 2 PM*).

The is_event flag on a variant marks it as a calendar item. A companion product_variant_events table (1:1 per variant) stores all event-specific metadata and is used by the front-end calendar renderer.

---

### Database Schema

**product_variants (modified)**

| Column | Type | Notes |
|---|---|---|
| is_event | boolean | Default alse. Marks this variant as a calendar event. |

**product_variant_events (new)**

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | Auto-increment |
| ariant_id | bigint FK | Unique — constrained to product_variants, cascades on delete |
| event_start_date | datetime | Required when is_event is true |
| event_end_date | datetime | Optional end date/time |
| event_label | varchar(255) | Primary calendar display label (required when is_event is true) |
| lternate_label | text | Optional tooltip / secondary label |
| label_background | varchar(50) | Hex colour for calendar display (default #4f46e5) |
| show_date | boolean | Whether to render the date on the front-end calendar (default 	rue) |
| event_location | text | Venue name, address, or online URL |
| event_description | text | Detailed description of this event session |
| event_sort | float | Numeric sort weight for calendar ordering (default  ) |
| created_at / updated_at | timestamps | Standard Laravel timestamps |

> The event record is automatically deleted when is_event is unchecked and saved.

---

### Architecture

- **pp/Models/ProductVariantEvent.php** — New model. elongsTo(ProductVariant), full \, datetime casts on event_start_date and event_end_date.
- **pp/Models/ProductVariant.php** — Updated: is_event added to \ and \ (boolean). New eventDetails() hasOne(ProductVariantEvent) relationship.
- **pp/Livewire/AdminProductEdit.php** — Updated: 10 new public properties for event fields. All variant lifecycle methods updated:
  - esetVariantForm() — resets event fields to defaults
  - startEditVariant() — loads event data from \->eventDetails
  - saveVariant() — creates/updates ProductVariantEvent if is_event, deletes it otherwise
  - updateVariant() — upserts or deletes event record; validates event_start_date and event_label as required when is_event is enabled
  - duplicateVariant() — copies event record to duplicated variant
  - loadProduct() — eager-loads ariants.eventDetails alongside ariants.inventory
- **esources/views/livewire/partials/variant-management.blade.php** — Updated: Event Details sub-section added to both the **Edit Variant** and **Create Variant** forms. Event badge column added to the variant list table.

---

### Admin UI

The **Event Details** sub-section appears between the **Inventory** block and the **Storage, Downloads & Image Uploads** block inside each variant form (edit and create).

**Toggle**
A violet pill-switch labelled *"Mark as Event / Calendar Item"* controls visibility of the event fields. When disabled, a brief hint line is shown. When enabled, a violet *"Event Active"* badge appears alongside the toggle.

**Fields (visible when toggled on)**

| Field | Required | Description |
|---|---|---|
| Event Label | ✅ Yes | Primary text shown on the calendar tile |
| Alternate / Tooltip Label | No | Secondary label, used for tooltips or longer descriptions |
| Event Start Date & Time | ✅ Yes | datetime-local picker |
| Event End Date & Time | No | Optional session end time |
| Calendar Colour | No | Colour swatch + hex input; controls the badge/tile colour (default indigo #4f46e5) |
| Sort Order | No | Float weight for ordering sessions within a calendar view |
| Show Date on Front-End | No | Checkbox — hide/show the date on the public calendar renderer |
| Location / Venue / URL | No | Physical address or online meeting link |
| Event Description | No | Rich text area for detailed session notes |

**Variant List Table**

A new **Event** column displays a coloured calendar badge showing the event start date (e.g. *"Jul 19"*) using the admin-configured calendar colour. Non-event variants show a dash.

---

### Validation Rules

When is_event is 	rue, the following fields become **required** on both create and update:

- event_start_date — must be present
- event_label — must be a string, max 255 characters

All other event fields are nullable regardless of the is_event state.

---

### Behaviour Summary

| Action | Behaviour |
|---|---|
| Save variant with is_event = true | Creates or updates a product_variant_events record |
| Save variant with is_event = false | Deletes any existing product_variant_events record for this variant |
| Duplicate variant (event) | Copies the event record to the new duplicate variant |
| Duplicate variant (non-event) | No event record created for the duplicate |
| Delete variant | product_variant_events record cascade-deleted automatically via FK |

---

### Front-End Calendar Integration

Each variant marked with is_event = true can be queried to populate a calendar view. The key fields for the calendar renderer are:

- is_event — filter flag to identify event variants
- event_start_date / event_end_date — date range for the calendar tile
- event_label — tile display text
- lternate_label — tooltip text
- label_background — tile background/border colour
- show_date — whether to render the date string
- event_location — venue or URL shown below the event label
- event_sort — ordering within a day or date range

Use ProductVariant::where('is_event', true)->with('eventDetails')->orderBy(...) as the base query, filtering by event_start_date range for the visible calendar window.

---

### Shortcode Compatibility

Event variants are standard product variants and support all existing shortcodes:

- [product:N] — embeds the full product (all variants, including event sessions) anywhere a shortcode is accepted
- Products marked as events can be filtered on the front-end using the is_event flag on their variants


---

## Product Cross-Selling Manager

### Overview

The Cross-Selling feature allows admins to associate up to **10 related products** with any product. Each cross-sell entry carries two independent display flags controlling where it surfaces on the public side:

- **Product Page** (display_on_item_view) — shown in a list, grid, or scroller below the product detail view
- **Post Add-to-Cart Page** (display_on_post_cart) — shown on the intermediary confirmation page after the customer adds the item to their cart

Both flags can be toggled independently per entry with a single click.

---

### Database Schema

**product_cross_selling (new)**

| Column | Type | Notes |
|---|---|---|
| id | bigint PK | Auto-increment |
| product_id | bigint FK | Constrained to products, cascades on delete |
| cross_sell_product_id | bigint FK | Constrained to products, cascades on delete |
| sort_order | float | Display ordering weight (default 0) |
| display_on_item_view | boolean | Show on the product detail page (default 	rue) |
| display_on_post_cart | boolean | Show on the post add-to-cart page (default alse) |
| created_at / updated_at | timestamps | Standard Laravel timestamps |

A unique constraint on (product_id, cross_sell_product_id) prevents duplicate pairs.

> Cross-sell records are automatically deleted via cascade when either the parent product or the cross-sold product is deleted.

---

### Architecture

- **pp/Models/ProductCrossSell.php** — New model. product() and crossSellProduct() BelongsTo relationships, full \, boolean casts.
- **pp/Models/Product.php** — Updated: crossSells() HasMany relationship (ordered by sort_order, then id).
- **pp/Livewire/AdminProductEdit.php** — Updated:
  - 3 new public properties: crossSellSearch, crossSellResults, crossSellSearchActive
  - loadProduct() now eager-loads crossSells.crossSellProduct
  - New methods:
    - updatedCrossSellSearch() — live search, debounced, max 25 results, excludes current product and already-added products
    - ddCrossSell(int \) — enforces max 10, determines next sort order, resets search state
    - emoveCrossSell(int \) — scoped to current product for security
    - 	oggleCrossSellItemView(int \) — toggles display_on_item_view
    - 	oggleCrossSellPostCart(int \) — toggles display_on_post_cart
    - updateCrossSellOrder(int \, float \) — updates sort weight
- **esources/views/livewire/partials/cross-selling.blade.php** — New blade partial
- **esources/views/livewire/admin-product-edit.blade.php** — Updated: "Cross-Selling" quick-nav link added; partial included between the Customizations section and the Reviews section

---

### Admin UI

The **Cross-Selling** panel appears on the product edit page, accessible via the quick-jump nav bar at the top of the content column.

**Live Search**
- An auto-complete search input (debounced 400ms) queries products by title or ID.
- Results are displayed in a dropdown (max 25, alphabetically sorted).
- Already-added products and the current product are excluded from results.
- A spinner appears during the search query.
- Clicking a result immediately adds it as a cross-sell and resets the search.

**Item Table**

| Column | Description |
|---|---|
| Sort | Numeric input — change and blur to update sort order |
| Product | Thumbnail, title, and product ID |
| Show on Product Page | Toggle button (green = enabled, grey = disabled) |
| Show on Post-Cart Page | Toggle button (indigo = enabled, grey = disabled) |
| Remove | Removes the cross-sell entry (with confirmation) |

**Limits & Guards**
- Maximum **10** cross-sell items per product — search input is hidden and a warning banner is shown when the limit is reached.
- Each remove action uses wire:confirm for an in-browser confirmation prompt.
- All database operations are scoped to product_id to prevent cross-product tampering.

---

### Front-End Integration (Future)

When building the public-facing display components, query cross-sells using:

`php
// Product detail page — items shown below the product description
 = ProductCrossSell::where('product_id', )
    ->where('display_on_item_view', true)
    ->with('crossSellProduct.variants.inventory')
    ->orderBy('sort_order')
    ->get();

// Post add-to-cart page
 = ProductCrossSell::where('product_id', \)
    ->where('display_on_post_cart', true)
    ->with('crossSellProduct.variants.inventory')
    ->orderBy('sort_order')
    ->get();
`

Display components (list, grid, scroller) are built separately and consume these collections.

---

## Cross-Sell List Plugin (type: `display`)

| Property | Value |
|---|---|
| **Shortcode** | `[plugin:cross-sell-list product_id=X]` |
| **Filename** | `cross_sell_list_2026` |
| **File** | `app/Plugins/Display/CrossSellListPlugin.php` |
| **Widget** | `app/Livewire/Widgets/CrossSellListWidget.php` |
| **Views** | `resources/views/plugins/display/cross-sell-list-{grid,list,slider}.blade.php` |
| **Engine** | Swiper.js (CDN, loaded with `@once` — safe alongside Slideshow and Featured Items plugins) |
| **Activation Required** | No (active by default) |
| **Data source** | `product_cross_sells` table — managed per product in Admin → Edit Product → Cross-Selling |

The Cross-Sell List plugin displays the cross-selling products associated with a specific product on any CMS page, or on the post-cart intermediary page via embedded shortcode. It is a purpose-built sibling to the Featured Items plugin and shares the same three display modes — **grid**, **list**, and **Swiper slider** — with an additional required `product_id` parameter that scopes the output to a single product's cross-sell list.

---

### How Cross-Sells Are Managed

Cross-sell relationships are created and managed per product in the admin:

1. Go to **Admin → E-Commerce → Products** and open any product.
2. Click **Cross-Selling** in the section quick-nav bar.
3. Use the live search input to find and add products.
4. For each entry, configure:
   - **Show on Product Page** — displays the item in the "You may also like" section on the public product detail page.
   - **Show on Post-Cart Page** — triggers the post-cart intermediary page and includes this item in the plugin output rendered there.
   - **Sort Order** — numeric weight controlling display order.
5. A maximum of **10** cross-sell items per product is enforced.

---

### Shortcode Parameters

`product_id` is the only required parameter. All others are optional and fall back to plugin-level defaults configured in **Admin → Plugins → Cross-Sell List Display → Settings**.

| Parameter | Default | Description |
|---|---|---|
| `product_id` | *(required)* | Numeric ID of the product whose cross-sells to display |
| `display` | `grid` | Display mode: `grid`, `list`, or `slider` |
| `max` | `12` | Maximum number of cross-sell products to show (1–100) |
| `cols` | `4` | Grid columns in grid mode: `2`, `3`, `4`, `5`, or `6` |
| `sort` | `sort_order` | Sort order: `sort_order` (admin-defined), `newest`, or `name` |
| `header` | *(blank)* | Optional section heading displayed above the products |
| `slides` | `4` | Number of visible cards at desktop breakpoint (slider mode only) |
| `nav` | `on` | Show prev/next navigation arrows in slider mode: `on` / `off` |
| `autoplay` | `on` | Enable automatic slide advancement in slider mode: `on` / `off` |
| `speed` | `4000` | Autoplay delay in milliseconds (slider mode only, min 500) |

---

### Shortcode Examples

```
[plugin:cross-sell-list product_id=42]

[plugin:cross-sell-list product_id=42 display=slider slides=4 header="You Might Also Like" autoplay=on speed=5000]

[plugin:cross-sell-list product_id=42 display=grid cols=3 max=6 sort=sort_order header="Frequently Bought Together"]

[plugin:cross-sell-list product_id=42 display=list max=8 sort=name]

[plugin:cross-sell-list product_id=42 display=grid cols=4 max=12 nav=off autoplay=off]
```

---

### Display Modes

| Mode | Description | Best For |
|---|---|---|
| `grid` | Multi-column card grid with configurable column count | Product detail pages, CMS landing sections |
| `list` | Horizontal row cards (thumbnail left, info right) | Compact recommendation panels, sidebars |
| `slider` | Swiper.js horizontal carousel with touch/drag support | Post-cart page, promotional recommendation strips |

All three modes show:
- Product thumbnail (or icon placeholder if no image)
- Product title with link to product detail page
- Truncated short description
- Price (with strikethrough original price when on sale)
- **Add to Cart** / **View Options** button depending on variant count
- **Sale** badge when `on_sale = 1`

---

### Post-Cart Intermediary Page

When a product has cross-sells with **Show on Post-Cart Page** enabled, adding that product to the cart suppresses the standard add-to-cart confirmation modal and instead redirects the user to a dedicated post-cart page at:

```
/cart/recommendations/{productId}
```

This page embeds the Cross-Sell List plugin via shortcode scoped to the product just added:

```
[plugin:cross-sell-list product_id={id} display=grid header="Complete Your Order"]
```

The page is rendered by the `PostCartCrossSell` Livewire component and uses the standard public layout.

> **Note:** The post-cart redirect also overrides the **Go Direct to Checkout** advanced setting on the product — cross-sell display always takes priority over both the modal and the direct-checkout bypass.

---

### Using the Plugin on CMS Pages

The plugin can be embedded on any CMS page via shortcode to serve as a recommendation block for a specific product. This is useful for:

- Building dedicated "bundle" or "complete the set" landing pages.
- Embedding product-specific upsells directly inside editorial CMS content.
- Reusing the same cross-sell definitions across multiple page contexts.

```
[plugin:cross-sell-list product_id=15 display=slider header="Bundle & Save"]
```

The `product_id` can also be set as the **Default Product ID** in plugin settings (Admin → Plugins → Cross-Sell List Display → Settings), allowing the shortcode to be used without the parameter on single-purpose pages.

---

### Admin-Configurable Settings

Default values for all parameters are set globally in **Admin → Plugins → Cross-Sell List Display → Settings**:

| Setting Key | Label | Default | Description |
|---|---|---|---|
| `product_id` | Default Product ID | *(blank)* | Fallback product ID when `product_id=` is omitted from shortcode |
| `display` | Default Display Mode | `grid` | Applied when `display=` is omitted |
| `max_items` | Max Items to Show | `12` | Applied when `max=` is omitted |
| `sort_order` | Default Sort Order | `sort_order` | Applied when `sort=` is omitted |
| `header_title` | Default Section Header | *(blank)* | Applied when `header=` is omitted |
| `grid_columns` | Grid Columns (default) | `4` | Applied when `cols=` is omitted |
| `slides_visible` | Slider: Visible Slides (Desktop) | `4` | Applied when `slides=` is omitted |
| `show_nav` | Slider: Show Navigation Arrows | `on` | Applied when `nav=` is omitted |
| `autoplay` | Slider: Autoplay | `on` | Applied when `autoplay=` is omitted |
| `autoplay_speed` | Slider: Autoplay Speed (ms) | `4000` | Applied when `speed=` is omitted |

---

### Swiper & Other Plugin Coexistence

The slider view uses `@once` to load Swiper CSS and JS from CDN. If the **Slideshow Plugin** or **Featured Items Plugin** is also on the same page, Swiper is loaded only once — there is **no conflict or double-loading**. Each slider instance receives a unique auto-generated ID (`cs_xxxxxxxx`) so multiple Cross-Sell List shortcodes on the same page work independently.

---

### Files Reference

| File | Purpose |
|---|---|
| `app/Plugins/Display/CrossSellListPlugin.php` | Plugin class — parses shortcode attributes, queries `product_cross_sells`, renders the correct view |
| `app/Livewire/Widgets/CrossSellListWidget.php` | Livewire widget wrapper for rendering the plugin as an embedded component |
| `app/Livewire/PostCartCrossSell.php` | Livewire component that powers the post-cart intermediary page |
| `resources/views/plugins/display/cross-sell-list-grid.blade.php` | Grid display view |
| `resources/views/plugins/display/cross-sell-list-list.blade.php` | List display view |
| `resources/views/plugins/display/cross-sell-list-slider.blade.php` | Swiper slider display view |
| `resources/views/livewire/post-cart-cross-sell.blade.php` | Post-cart page view |
| `resources/views/livewire/partials/cross-selling.blade.php` | Admin partial — cross-sell management panel on product edit page |
| `database/seeders/PluginSeeder.php` | Seeds the plugin record, options, and default settings |

---

## Demo Store Content

The system includes a full-featured demo store seeder (DemoStoreSeeder.php) that populates your database with sample products, brands, categories, variants, images, events, and cross-selling relationships. This is useful for exploring the platform's capabilities before adding your own real products.

All demo-seeded records are tagged with an is_demo = 1 flag in the database. This flag enables the admin to detect and purge all demo content in a single click when ready to go live.

---

### What's Included in the Demo Store

The demo seeder creates the following data:

#### Brands (5)
| Brand | Description |
|---|---|
| Prestige Design | Main demo brand — used across jewelry and apparel |
| DeMarco | Apparel and gifts brand |
| Old Heritage | Legacy jewelry brand |
| Bella Luna | Fine jewelry brand |
| Excelsior | Watches and accessories |

#### Categories (10)
| Category | Type |
|---|---|
| Custom Jewelry | Top-level |
| → Rings | Sub-category of Custom Jewelry |
| → Bracelets | Sub-category of Custom Jewelry |
| → Necklaces | Sub-category of Custom Jewelry |
| → Earrings | Sub-category of Custom Jewelry |
| Watches | Top-level |
| Downloads & Media | Top-level |
| Gifts & Apparel | Top-level |
| Service Items | Top-level |
| Workshops & Seminars | Top-level |

#### Products (34)
Spanning all major product types the platform supports:

| # | Product | Type | Variants |
|---|---|---|---|
| 1 | 14k\|24k 3 Ct Bracelet | Standard | 1 |
| 2 | Heart Of Sapphire Ring | Standard | 1 |
| 3 | Diamond Mosaic Ring | Size variants | 7 (Size 5–8) |
| 4 | 14K Ring With Cultured Pearl And Diamonds | Personalization + sizes | 7 |
| 5 | Sapphire and Diamond Ring | Standard | 1 |
| 6 | Ruby and Diamond Ring with 14K Band | Standard | 1 |
| 7 | Diamond Wave Bracelet | Standard | 1 |
| 8 | Pinched Style Diamond Bracelet | Standard | 1 |
| 9 | Diamond Heart Bracelet With Your Initials Inscribed | Standard | 1 |
| 10 | 14k Or 24K White Gold 2 Carat Diamond Bracelet | Standard | 1 |
| 11 | 18k Gold 5 Carat GIA Certified Diamond Bracelet | Standard | 1 |
| 12 | Ruby and Diamond Bracelet | Tone variants | 3 (Gold/Silver/Rose) |
| 13 | Sapphire, Ruby And Emerald Bracelet | Standard | 1 |
| 14 | Jewelry Cleaning eBOOK | Download | 1 |
| 15 | Jewelry Repair Webinar Plus eBook | Download | 1 |
| 16 | Jewelry Accessorizing ONLINE Webinar | Download | 1 |
| 17 | Men's Titanium Watch | Standard | 1 |
| 18 | Vintage Pocket Watch | Standard | 1 |
| 19 | Fashion Wrist Watch | Colour variants | 3 (Black/Brown/White) |
| 20 | Premium Office Pens 2 Pack | Standard | 1 |
| 21 | Silver Jewelry Box | Standard | 1 |
| 22 | Modern Pocket Watch | Standard | 1 |
| 23 | Modern Wrist Watch | Standard | 1 |
| 24 | Men's T-Shirt | Colour variants | 6 colours |
| 25 | Women's T-Shirt | Colour variants | 6 colours |
| 26 | Women's Sweatshirt | Colour variants | 3 colours |
| 27 | Men's Titanium Watch (Sweatshirt) | Size+Colour variants | 15 (3 colours × 5 sizes) |
| 28 | Product Builder Example | Session variants | 2 (1hr/2hr) |
| 29 | Donation \| "Make An Offer" Example | Donation tiers | 5 (\–\) |
| 30 | Digital Marketing Seminar | Event + Session variants | 3 sessions |
| 31 | 2-Day Social Media Workshop | Event | 1 session |
| 32 | Inventory Management Seminar - Advanced | Event | 1 |
| 33 | eCommerce Strategies Seminar | Event | 1 |
| 34 | Business Processes Seminar | Event | 1 |

**Also included:**
- ✅ **Inventory records** for every variant (with realistic available/reserved stock quantities)
- ✅ **Event records** for all event-type variants (start/end times, location, colour labels)
- ✅ **Product attributes** (product_fields + product_field_options) for multi-variant products
- ✅ **CDN images** for all products — served from https://d23w3zagfzgqcb.cloudfront.net/demo/
- ✅ **36 cross-selling relationships** across the jewelry and apparel categories

---

### How to Seed the Demo Store

**Prerequisites:** Your database must be fully migrated before seeding.

`ash
# Step 1 — Run all pending migrations (if not already done)
php artisan migrate

# Step 2 — Run the demo store seeder
php artisan db:seed --class=DemoStoreSeeder
`

The seeder is **fully idempotent** — running it multiple times is safe. It skips any product whose seo_slug already exists. If the is_demo flag is missing on existing records (e.g., the seeder was run before the flag migration), it will automatically backfill the flag.

**What the seeder does NOT do:**
- It does not copy image files to your server — images are served via CDN URLs.
- It does not affect orders, users, CMS pages, or any non-product data.
- It does not overwrite products you have already created.

---

### How to Delete Demo Content via the Admin Area

The admin area detects demo content automatically and shows a banner on the **Settings page** only when demo data is present.

**Step-by-step:**

1. Log in to the admin panel.
2. Navigate to **Settings** (Admin → Settings).
3. If the demo store has been seeded, an amber **"Demo Store Content is Active"** banner will appear at the top of the page.
4. Click the **"Purge Demo Content"** button (red, inside the banner).
5. A confirmation modal appears listing everything that will be deleted:
   - All demo products and their metadata
   - All demo product variants and pricing
   - All demo product images (CDN URLs)
   - All demo brands and categories
   - All demo inventory records and events
   - All demo cross-selling relationships
   - All demo product attributes and options
6. Review the warning: **"If you have edited any demo products, those edits will also be deleted."**
7. Click **"Yes, Delete All Demo Content"** to confirm.
8. The purge runs immediately. A success toast confirms completion.
9. The amber banner disappears automatically — confirming all demo content has been removed.

> **Note:** The "Purge Demo Content" button is completely invisible on stores that have never run DemoStoreSeeder. There is no risk of accidentally seeing it on a live store.

---

### How Demo Content is Tracked

Every record created by DemoStoreSeeder is tagged with is_demo = 1 in the database. This flag exists in the following tables:

| Table | Flag Purpose |
|---|---|
| products | Identifies demo products |
| product_brands | Identifies demo brands |
| product_categories | Identifies demo categories |
| product_variants | Identifies demo variants |
| product_images | Identifies demo CDN image records |
| product_cross_selling | Identifies demo cross-selling pairs |

Child records without their own is_demo column (products_inventory, product_variant_events, product_fields, product_field_options, product_categories_assignments) are deleted by JOIN/IN lookups against their demo-flagged parent IDs.

The purge executes deletions in the correct dependency order (children before parents) to avoid foreign key constraint violations.

---

### CDN Images

All demo product images are served from the AWS CloudFront CDN:

`
https://d23w3zagfzgqcb.cloudfront.net/demo/<filename>
`

Images are stored in product_images.cdn_url with image_url_source = 3 (CDN URL mode), which takes priority over local/S3/direct URL sources. No image files are copied to your server.

---

### Re-seeding After Purging

After purging demo content, you can re-seed at any time:

`ash
php artisan db:seed --class=DemoStoreSeeder
`

This will recreate all demo products, brands, categories, images, and cross-sells from scratch.

---

### Affected Database Tables (Summary)

| Table | Action |
|---|---|
| products | 34 demo products created |
| product_brands | 5 demo brands created |
| product_categories | 10 demo categories created (6 top + 4 sub) |
| product_variants | ~100 variants created across all products |
| product_images | CDN image records per variant |
| products_inventory | Stock level records per variant |
| product_variant_events | Event detail records for event-type variants |
| product_fields | Attribute group labels for multi-variant products |
| product_field_options | Attribute option values per variant |
| product_categories_assignments | Category-to-product join records |
| product_categories_assignments | Category-to-product join records |
| product_cross_selling | 36 cross-selling relationships |

---

## Product Image Orientation Setting

### Overview

The **Product Image Orientation** setting lets you choose the aspect ratio used for all product image containers across the storefront and every display plugin. Because this CMS may serve products with square photos or widescreen/landscape photos depending on the install, a single per-install setting ensures images are never cropped incorrectly.

The setting lives in **Admin → Settings → Shop Display** and is persisted in the `cms_settings` table under the key `product_image_orientation`.

### Available Options

| Value | Label | Container | Object-fit | Best for |
|-------|-------|-----------|------------|----------|
| `16:9` | 16:9 Widescreen *(default)* | `aspect-video` | `object-cover` | Landscape/banner product images |
| `1:1` | 1:1 Square | `aspect-square` | `object-contain` | Square product photos |

**16:9 Widescreen** fills the frame at a 16:9 ratio and uses `object-cover` — images are cropped to fill the container width. This is the default and matches the original storefront design.

**1:1 Square** uses a perfect-square container with `object-contain` — images are scaled to fit within the square without any cropping or distortion, preserving the full image regardless of its proportions.

> **Note:** List-view rows switch between `w-28 h-24` (16:9) and `w-24 h-24` (1:1, square) for the thumbnail. The product detail gallery lightbox always uses `object-contain` and is unaffected.

### Where It Applies

The setting is read at render time (cached for 60 minutes via `CmsSetting::allCached()`) and affects **every** product image across the site:

| Location | File |
|----------|------|
| Shop listing page — grid view | `resources/views/livewire/shop-catalog.blade.php` |
| Shop listing page — list view | `resources/views/livewire/shop-catalog.blade.php` |
| Product detail page — main gallery image | `resources/views/livewire/partials/product-gallery.blade.php` |
| Product detail page — "You May Also Like" carousel | `resources/views/livewire/product-details.blade.php` |
| Cross-sell widget — grid | `resources/views/plugins/display/cross-sell-list-widget-grid.blade.php` |
| Cross-sell widget — list | `resources/views/plugins/display/cross-sell-list-widget-list.blade.php` |
| Cross-sell widget — slider | `resources/views/plugins/display/cross-sell-list-widget-slider.blade.php` |
| Featured Items plugin — grid | `resources/views/plugins/display/featured-items-grid.blade.php` |
| Featured Items plugin — list | `resources/views/plugins/display/featured-items-list.blade.php` |
| Featured Items plugin — slider | `resources/views/plugins/display/featured-items-slider.blade.php` |
| Featured Items widget — grid | `resources/views/plugins/display/featured-items-widget-grid.blade.php` |
| Featured Items widget — list | `resources/views/plugins/display/featured-items-widget-list.blade.php` |
| Featured Items widget — slider | `resources/views/plugins/display/featured-items-widget-slider.blade.php` |

### How to Change the Setting

1. Log in to the admin panel.
2. Navigate to **Settings** (the gear icon in the sidebar).
3. Scroll down to the **Shop Display** card.
4. Select either **16:9 Widescreen** or **1:1 Square** using the visual radio selector.
5. Click **Save Settings**.

Changes take effect immediately on the next page load. The settings cache is cleared automatically on save — no server restart or deployment step is needed.

### Technical Implementation

The setting is stored as a plain string (`'16:9'` or `'1:1'`) in the `cms_settings` table. It is read in each blade file via a `@php` block at the top of the template:

```php
@php
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $aspectClass    = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
    $listSizeClass  = $imgOrientation === '1:1' ? 'w-24 h-24' : 'w-28 h-24'; // list views only
@endphp
```

Slider plugin views (which use scoped CSS rather than Tailwind utilities) use the same lookup but emit PHP values directly into an inline `<style>` block:

```php
@php
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $cssAspectRatio = $imgOrientation === '1:1' ? '1/1' : '16/10';
    $cssObjectFit   = $imgOrientation === '1:1' ? 'contain' : 'cover';
@endphp
```

The `CmsSetting::get()` call is backed by a 60-minute cache (`cms_settings_all`) so there is no extra database hit per request. The cache is invalidated automatically whenever settings are saved from the admin panel.

### Adding a New Orientation Option

If you need to add a third orientation (e.g., `4:3`), follow these steps:

1. **AdminSettings.php** — add `'4:3'` to the `in:` validation rule for `product_image_orientation`.
2. **admin-settings.blade.php** — add a new `<label>` radio card in the Shop Display card.
3. **All blade files listed above** — extend the ternary logic to handle the new value.
4. **README.md** — update this section to document the new option.


---

## CMS Form Builder

The Form Builder lets you create unlimited embeddable forms directly from the admin panel. Forms support multiple field types, per-field validation with custom error messages, email notifications, submission logging, and CSV export. Each form is embedded in any CMS page using a simple shortcode.

### Admin Location

Navigate to **Admin ? CMS ? Forms** in the sidebar. The Forms section sits between Downloads and Plugins in the CMS sidebar navigation.

### Creating a Form

1. Click **New Form** on the Forms index page.
2. Fill in the **Form Settings** (left column):
   - **Form Name** � internal label shown in the admin.
   - **Slug** � auto-generated from the name; used in debug comments only (the shortcode uses the ID).
   - **Submit Button Label** � text on the submit button (default: "Submit").
   - **Active** � toggle to enable or disable the form on the frontend.
3. Configure **After Submission** (left column):
   - **Confirmation Message** � plain text or simple HTML shown in place of the form after a successful submit.
   - **Redirect URL** � if set, the visitor is redirected here 2 seconds after submitting.
4. Configure **Email Notification** (left column):
   - **Send To Email** � recipient address for submission notification emails.
   - **Email Subject** � custom subject line (defaults to "New form submission: {Form Name}").
5. Optionally add **Custom CSS** (left column) � scoped to `.cms-form-wrap` within the form container.
6. Build fields in the **Form Fields** panel (right column) � see Field Types below.
7. Click **Save Form**.

### Field Types

| Type | Description |
|---|---|
| **Text Input** | Single-line text field |
| **Textarea** | Multi-line text area |
| **Dropdown** | `<select>` with a list of options |
| **Radio Group** | Single-selection radio buttons |
| **Checkbox** | Single checkbox (yes/no) |
| **Checkbox Group** | Multiple-selection checkboxes |

Each field supports:
- **Label** � the visible field label (required)
- **Instructions** � small help text shown below the label
- **HTML Above** � full HTML/rich content rendered directly above the field (uses TinyMCE)
- **Required** toggle � marks the field as required; exposes:
  - **Validation Rule**: Not Empty, Valid Email, or Numeric Only
  - **Custom Error Message** � per-field message displayed below the field on failure

For Dropdown, Radio Group, and Checkbox Group field types, use the **Options** manager to add/remove the selectable choices.

Fields can be reordered using the ? ? buttons on each field row, or removed with the ? button.

### Embedding a Form (Shortcode)

Once a form is saved, it is assigned a numeric ID. Embed it anywhere in a CMS page using:

```
[cms-form id=5]
```

Replace `5` with the form's actual ID. The shortcode is displayed on the Forms index page and can be clicked to copy it to the clipboard.

The shortcode is expanded server-side by `ShortcodeProcessor` (Pass 0, before all other shortcodes). If the form is inactive or the ID is invalid, the shortcode renders as an empty HTML comment � no broken markup.

Multiple forms can appear on the same page; each gets a unique DOM ID.

### Frontend Behaviour

- **Validation** is performed both client-side (Alpine.js, instant feedback) and server-side (PHP fallback). Per-field errors appear directly below the relevant field.
- On success, the form is replaced with the **Confirmation Message**.
- If a **Redirect URL** is set, the browser redirects there 2 seconds after the confirmation is shown.
- The form uses a `fetch()` POST request � no full page reload.
- **Custom CSS** is injected as a `<style>` block scoped to the individual form container.

### Viewing & Managing Submissions

1. On the Forms index, click the **submissions count badge** for a form, or
2. Open a form's editor and click **View Submissions** at the bottom.

Each submission is displayed as a collapsible card showing the timestamp, IP address, and a preview of the first 3 field values. Expanding a card shows all field values.

**Actions per submission:**
- **Delete** � permanently removes the individual submission record.
- **Export CSV** � downloads all submissions for that form as a `.csv` file with one column per field.

**Search** � filter submissions by IP address or any value in the stored JSON data.

### Email Notifications

When **Send To Email** is configured on a form, a notification email is sent automatically on each successful submission using the application's existing mail configuration (`.env` `MAIL_*` settings). The email includes:

- All field labels and submitted values
- Submission timestamp
- Submitter IP address
- Form name

Email failures are caught silently and logged to `storage/logs/laravel.log` � they do not block the success response shown to the visitor.

### Database Tables

| Table | Purpose |
|---|---|
| `cms_forms` | Form definitions (name, slug, settings) |
| `cms_form_fields` | Field definitions per form (type, label, validation, options) |
| `cms_form_submissions` | Logged submission data as JSON |

All three tables cascade-delete: deleting a form removes all its fields and submissions automatically.

### Public API Route

```
POST /forms/{slug}/submit
```

This route is public (no authentication required). It accepts `{ "values": { "{field_id}": "value" } }` as JSON and returns:

- `200 { "success": true }` on success
- `422 { "errors": { "{field_id}": "message" } }` on validation failure

The `slug` parameter accepts either the form's text slug or its numeric ID.

### Custom CSS Tips

Custom CSS is scoped using `.cms-form-wrap` as the container root. Example:

```css
.cms-form-wrap {
    background: #f8fafc;
    padding: 2rem;
    border-radius: 1rem;
    border: 1px solid #e2e8f0;
}

.cms-form-wrap .cms-field-label {
    color: #4f46e5;
}

.cms-form-wrap .cms-form-submit {
    width: 100%;
}
```

Available CSS hook classes: `.cms-form-wrap`, `.cms-embed-form`, `.cms-form-field`, `.cms-field-html-above`, `.cms-field-label`, `.cms-field-instructions`, `.cms-field-input`, `.cms-field-textarea`, `.cms-field-select`, `.cms-field-radio-group`, `.cms-field-radio-label`, `.cms-field-checkbox-label`, `.cms-field-checkbox-group`, `.cms-field-error`, `.cms-form-submit`, `.cms-form-confirmation`.

### reCAPTCHA v3 Integration

If `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET` are set in your `.env`, **all embedded forms are automatically protected by reCAPTCHA v3** � no extra configuration required.

#### How It Works

1. When the form embed is rendered, it checks `config('services.recaptcha.site_key')`.
2. If a key is present, the reCAPTCHA v3 API script is injected into the page (once per page, even with multiple forms).
3. On submit, `grecaptcha.execute(siteKey, { action: 'cms_form' })` is called to obtain a fresh token.
4. The token is sent alongside the form values in the POST body (`recaptcha_token`).
5. The `CmsFormSubmissionController` verifies the token via `RecaptchaService::verify($token, 'cms_form')`.
6. If verification fails, a `422` response is returned and the error is shown above the submit button.

#### Behaviour Without Keys

When `RECAPTCHA_SITE_KEY` / `RECAPTCHA_SECRET` are not set (local dev, staging without keys), `RecaptchaService::verify()` returns `true` automatically � the form works exactly as if reCAPTCHA were not present. No code changes or feature flags needed.

#### Relevant .env Keys

```
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET=your_secret_key
RECAPTCHA_THRESHOLD=0.5   # optional, default 0.5 (0.0�1.0)
```

The threshold controls how aggressive the bot filter is. `0.5` is Google's recommended default. Lower values are more permissive; higher values are stricter.

---

### Mailing List Auto Opt-in

Each form has an optional **Mailing List Opt-in** feature (Admin ? Forms ? Edit Form ? Mailing List Opt-in card). When enabled, the submitted email address (and name, if tagged) are automatically forwarded to a mailing-list provider on every successful submission � no opt-in checkbox is required on the form itself.

#### Supported Providers

| Provider | .env Keys Required |
|---|---|
| **Mailchimp** | `MAILCHIMP_API_KEY`, `MAILCHIMP_SERVER_PREFIX` (e.g. `us1`) |
| **Constant Contact** | `CONSTANT_CONTACT_API_KEY` |
| **Klaviyo** | `KLAVIYO_API_KEY` |

Provider API keys are read from `.env`. The **List / Audience ID** (not the API key) is configured per-form in the admin editor.

#### Field Roles

The opt-in system needs to know which input field holds the subscriber's email and which holds their name. Rather than guessing by label text, each field carries an explicit **Field Role** marker:

- **Subscriber Email** � the value in this field is sent as the subscriber's email address
- **Subscriber Name** � the value in this field is sent as the subscriber's display name (split into first/last on the first space)
- **None** (default) � the field is not forwarded to the provider

Set roles in the field editor (Admin ? Forms ? Edit Form ? expand a field ? Field Role). Only one field per form should carry each role. If no email-role field is found or the value is not a valid email address, the opt-in is skipped silently.

#### Configuring Auto Opt-in on a Form

1. Open the form in the admin editor.
2. In the left column, scroll to **Mailing List Opt-in** and enable the toggle.
3. Select your provider (Mailchimp, Constant Contact, or Klaviyo).
4. Enter the **List / Audience ID** from your provider's dashboard.
5. In the right column (Fields), expand the email input field and set its **Field Role** to `Subscriber Email`.
6. Expand the name input field and set its **Field Role** to `Subscriber Name`.
7. Click **Save Form**.

#### Failure Handling

Opt-in calls are made after the submission is already saved. Any API failure (network error, bad credentials, invalid list ID) is caught silently, logged to `storage/logs/laravel.log`, and never shown to the visitor. The form confirmation message is always displayed regardless of opt-in outcome.

---

### Sample Seed Data

Running `php artisan db:seed --class=CmsFormSeeder` (or `php artisan migrate:fresh --seed`) installs two ready-to-use forms:

#### Form 1 � Contact Us (`[cms-form id=1]`)

| Field | Type | Role | Required |
|---|---|---|---|
| Your Name | Text Input | `name` | Yes (not empty) |
| Email Address | Text Input | `email` | Yes (valid email) |
| Comments / Message | Textarea | � | Yes (not empty) |
| How did you hear about us? | Dropdown | � | No |

- Auto opt-in is **enabled** (provider and list ID left blank � configure in admin when ready)
- Submit button label: **Send Message**
- Confirmation: "Thank you for reaching out! We'll get back to you within one business day."

#### Form 2 � Email Subscribe (`[cms-form id=2]`)

| Field | Type | Role | Required |
|---|---|---|---|
| Your Name | Text Input | `name` | Yes (not empty) |
| Email Address | Text Input | `email` | Yes (valid email) |

- Auto opt-in is **enabled** (configure provider and list ID in admin)
- Submit button label: **Subscribe**
- Confirmation: "You're subscribed! Thanks for joining our mailing list."

Both forms can be embedded in any CMS page or product description using their shortcodes. The Contact Us shortcode can be added to the existing **Contact** page (slug: `contact`) by editing its content in the CMS page editor and inserting `[cms-form id=1]`.


---

## Checkout Custom Field Manager

### Overview
Admin-only feature (role_id = 3 / Admin) accessible via **Admin → Checkout → Processors & Payments**. Allows injection of fully customizable form fields into two specific positions in the checkout flow without requiring shortcodes.

### Field Positions

| Position | Where It Appears |
|---|---|
| **Checkout (Step 1)** | Below the shipping/customer info form, above the "Continue to Review" button |
| **Billing Page (Step 2)** | Above the Payment Method card on the order review page |

### Field Types Supported

| Type | Description |
|---|---|
| `input` | Single-line text field |
| `textarea` | Multi-line text area |
| `select` | Dropdown list |
| `radio` | Radio button group |
| `checkbox` | Single checkbox |
| `checkbox_group` | Multiple checkboxes |

### Field Options

Each field supports:
- **Label** (required) — visible field label
- **Instructions** — small helper text displayed below the label
- **Required toggle** — mark a field as mandatory before the customer can proceed
- **Validation type** — Not Empty / Valid Email / Numeric
- **Custom error message** — shown below the field on validation failure
- **HTML Above** — optional rich HTML block rendered above the field (supports TinyMCE markup)
- **Active toggle** — disable a field without deleting it
- **Reorder** — ▲ ▼ buttons to change display order

### Billing Page — User Type Filtering

Fields placed on the **Billing Page** position have an additional **Show For** setting:

| Show For | Effect |
|---|---|
| `Both` | Shown to all checkout users |
| `Public Only` | Shown only to regular (non-wholesale) customers |
| `Wholesale Only` | Shown only to users with `role_id = 2` (Wholesale) |

Wholesale detection uses the existing `User::isWholesale()` method.

### Validation Timing

| Position | Validated In |
|---|---|
| Checkout (Step 1) | `Checkout::saveDetailsAndContinue()` — blocks redirect to Step 2 |
| Billing Page (Step 2) | **Top** of `OrderReview::placeOrder()` — before any payment gateway call, ensuring required fields can never be skipped after payment |

### Data Storage

Submitted field values are merged into a JSON column `custom_field_data` on the `orders` table. Values are keyed by their **field label** for human readability (e.g. in future admin order views):

```json
{
  "How did you hear about us?": "Google",
  "PO Number": "PO-2024-1234"
}
```

Step-1 values are carried forward via `session(['checkout_custom_data' => ...])` and merged with Step-2 values when the order is placed. The session keys `checkout_custom_data` and `checkout_opt_in` are cleared immediately after the order is recorded.

### Mailing List Opt-in

The **Checkout Mailing List Opt-in** panel (same page in the admin) configures automatic or manual newsletter subscription on order completion. The customer's **name and email come from their checkout account** — no extra fields are required.

#### Opt-in Modes

| Mode | Behaviour |
|---|---|
| **Off** | No opt-in activity |
| **Auto** | Every completed order silently subscribes the customer |
| **Manual** | A configurable checkbox appears at checkout; only customers who check it are subscribed |

#### Manual Checkbox Settings

When mode is set to **Manual**:
- **Checkbox Label** — customizable text (e.g. "Yes, add me to the mailing list")
- **Checkbox Position** — choose whether the checkbox appears at Step 1 (Checkout) or Step 2 (Billing Page)

#### Supported Providers

| Provider | Required `.env` Keys |
|---|---|
| Mailchimp | `MAILCHIMP_API_KEY`, `MAILCHIMP_SERVER_PREFIX` |
| Constant Contact | `CONSTANT_CONTACT_API_KEY` |
| Klaviyo | `KLAVIYO_API_KEY` |

The List / Audience ID is set in the admin panel (not in `.env`). Opt-in failures are caught silently and logged to `storage/logs/laravel.log` as warnings so the customer experience is never interrupted.

#### CMS Settings Keys

| Key | Values |
|---|---|
| `checkout_optin_mode` | `off` / `auto` / `manual` |
| `checkout_optin_label` | Checkbox display text |
| `checkout_optin_position` | `checkout` / `billing` |
| `checkout_optin_provider` | `mailchimp` / `constant_contact` / `klaviyo` |
| `checkout_optin_list_id` | Provider list / audience ID |

### Admin Access

The field manager and opt-in settings are embedded in the existing **Processors & Payments** admin page and protected by the same `abort_unless(auth()->user()->isAdmin(), 403)` guard. Only users with `role_id = 3` (Admin) can access this page.

### Database Changes

| Table | Change |
|---|---|
| `checkout_custom_fields` | New table — stores all field definitions |
| `orders` | Added `custom_field_data JSON NULL` column |



---

## Dynamic Top Navigation Builder

### Overview
A fully database-driven, admin-configurable top navigation system that replaces the hardcoded public nav bar. Menus are built in the admin, with drag-and-drop ordering, TinyMCE mega-menu HTML editing, 5 preconfigured color schemes, custom CSS per menu, and a developer plugin contract for registering custom nav item types.

**Admin path:** CMS ? Navigation Builder (dmin/nav-builder)
**Access:** Admins only (role_id = 3)

### Key Features
- Build unlimited named menus; designate one as **primary** (rendered in public header)
- Hardcoded nav is preserved as a **graceful fallback** when no primary dynamic menu exists
- **Drag-and-drop** reordering via SortableJS (CDN, no build step)
- **TinyMCE** HTML editor for Mega Menu and Custom HTML Submenu item types
- **Color schemes**: Default (light), Dark, Indigo (gradient), Slate, Transparent, Custom
- **Custom CSS** textarea per menu, injected in a scoped <style> block keyed to the menu's slug
- **2-level nesting**: top-level items + children (sub-menu)
- **Plugin contract** (TopNavigationPlugin) allows developers to register custom item types via the /plugins/ folder

### Database Tables
| Table | Purpose |
|---|---|
| 
av_menus | Menu containers (name, slug, color scheme, custom CSS, sticky, show\_logo) |
| 
av_items | Nav items belonging to a menu (type, label, url, html\_content, cms\_page\_id, visibility, etc.) |

### Nav Item Types (Built-in)
| Type | Description |
|---|---|
| link | Custom URL (internal or external) |
| cms_page | Link to an active CMS page (resolved via cms_page_id) |
| home | Home page |
| shop | Shop landing |
| cart | Shopping cart (shows live item count badge) |
| ccount | My Account / dashboard |
| categories | Category drill-down dropdown (2 levels) |
| rands | Brands dropdown |
| parent | Non-navigable parent with child sub-menu |
| 
o_link | Label only (no anchor) with child sub-menu |
| mega_menu | Full-width mega menu with TinyMCE HTML content |
| html_submenu | Custom HTML sub-menu dropdown (TinyMCE) |
| separator | Visual divider bar |
| plugin | Rendered by a registered TopNavigationPlugin |

### Visibility Options (Per Item)
- ll � Everyone (default)
- guests_only � Not logged in only
- uth_only � Logged-in users only
- wholesale_only � Wholesale users only

### Color Schemes & CSS Variables
Each menu uses CSS custom properties scoped to #top-nav-{slug}:

| Variable | Purpose |
|---|---|
| --nav-bg | Nav bar background (supports gradients, rgba, transparent) |
| --nav-backdrop | ackdrop-filter value |
| --nav-border | Bottom border color |
| --nav-text | Link text color |
| --nav-text-hover | Link hover color |
| --nav-logo-filter | CSS filter on site logo (use rightness(0) invert(1) for white logo on dark) |
| --nav-dropdown-bg | Dropdown background |
| --nav-dropdown-border | Dropdown border |
| --nav-dropdown-shadow | Dropdown box shadow |
| --nav-dropdown-text | Dropdown item text color |
| --nav-dropdown-hover-bg | Dropdown item hover background |
| --nav-mobile-bg | Mobile slide-out panel background |
| --nav-mobile-text | Mobile slide-out panel text |
| --nav-badge-bg | Cart badge background |
| --nav-badge-text | Cart badge text |

Built-in schemes: default, dark, indigo, slate, 	ransparent. Select custom to rely solely on the Custom CSS textarea.

### Developer Plugin Contract
To register a custom nav item type (e.g., a Search Bar or Wishlist icon):

1. Create a class implementing App\Plugins\Contracts\TopNavigationPlugin:

`php
use App\Plugins\Contracts\TopNavigationPlugin;
use App\Models\NavItem;

class SearchBarNavPlugin implements TopNavigationPlugin
{
    public function slug(): string { return 'search_bar'; }
    public function name(): string { return 'Search Bar'; }
    public function renderItem(NavItem , array ): string {
        return '<li class="nav-search-bar">...</li>';
    }
    public function adminFormPartial(): ?string { return null; }
}
`

2. Add a plugin.json manifest in plugins/my-search-bar/:

`json
{
    "type": "top-navigation",
    "class": "SearchBarNavPlugin",
    "filename": "my-search-bar",
    "name": "Search Bar Nav Item",
    "version": "1.0",
    "author": "Developer Name"
}
`

3. Drop the plugin folder into ase_path('plugins'). It is auto-discovered on next request.

Alternatively, register it directly in PluginServiceProvider::register():
`php
->register(SearchBarNavPlugin::class);
`

### New Files Added
| File | Purpose |
|---|---|
| database/migrations/2026_07_21_100000_create_nav_menus_table.php | Nav menus schema |
| database/migrations/2026_07_21_100001_create_nav_items_table.php | Nav items schema |
| pp/Models/NavMenu.php | NavMenu Eloquent model |
| pp/Models/NavItem.php | NavItem model with tree builder |
| pp/Plugins/Contracts/TopNavigationPlugin.php | Plugin contract interface |
| pp/Services/NavItemRenderer.php | Stateless link/sub-menu renderer service |
| pp/Livewire/AdminNavMenus.php | Admin menu list Livewire component |
| pp/Livewire/AdminNavMenuEdit.php | Admin menu editor Livewire component |
| esources/views/livewire/admin-nav-menus.blade.php | Admin list blade |
| esources/views/livewire/admin-nav-menu-edit.blade.php | Admin editor blade |
| esources/views/components/nav-dynamic.blade.php | Public dynamic nav component |
| esources/views/components/nav-item.blade.php | Nav item renderer component |
| esources/views/components/nav-children.blade.php | Dropdown children partial |
| config/nav_schemes.php | Color scheme CSS variable maps |

### Modified Files
| File | Change |
|---|---|
| pp/Plugins/Support/PluginManager.php | Added \ array + register/get methods |
| pp/Livewire/PublicNavigation.php | Loads primary NavMenu and item tree; falls back gracefully |
| esources/views/livewire/public-navigation.blade.php | Adds <x-nav-dynamic> with @else fallback to hardcoded nav |
| esources/views/livewire/layout/navigation.blade.php | Added Navigation Builder link under CMS in admin sidebar |
| outes/web.php | Added dmin/nav-builder and dmin/nav-builder/{menu}/edit routes |

## Variant Image Deduplication by Color, Shade, and Tint

### Feature Overview
When a product has multiple variants (e.g., different size specifications for the same color), e-commerce systems typically associate a duplicate copy of the color's image with each variant. By default, this causes identical images to appear multiple times in the main thumbnail gallery of the product detail page.

This feature groups and flattens the product view page's image gallery so that only one unique image per color, shade, or tint specification is displayed in the thumbnails strip, preventing visual redundancy.

### How It Works
1. **Attribute Extraction**: A custom parser decodes the variant attributes (strictly checking for JSON keys matching `color`, `colour`, `shade`, or `tint` case-insensitively, and falling back to split plain-text strings like `"Black / Large"`).
2. **Deduplication**: The flat-mapped variant images list is filtered using a compound key of `(color_key)|(image_path_key)` to retain only one set of images per color group while preserving multi-angle views of the same color (if present).
3. **Smart Alpine Gallery Interaction**:
   - Swapping sizes/specs of the same color (e.g. from *Black Small* to *Black Medium*) does not disrupt the gallery view or reset the image focus.
   - Clicking a gallery thumbnail transitions the selected variant to the clicked color spec while retaining size selections where possible.
   - When a different variant is selected from the buy-box, Alpine resolves the gallery slide to match the new variant's color group.

### Modified Files
| File | Change |
|---|---|
| [ProductDetails.php](file:///C:/Sites/laravel-gemini/app/Livewire/ProductDetails.php) | Added `getVariantColor` helper; updated event dispatches to propagate color attributes. |
| [product-gallery.blade.php](file:///C:/Sites/laravel-gemini/resources/views/livewire/partials/product-gallery.blade.php) | Mapped, deduplicated, and matched images by color; updated Alpine.js x-data, change listeners, and thumbnail click handlers. |

## Product Management & Gallery Enhancements (Alt Fields, Dashboard Widget, Anchors, and Centered Galleries)

### 1. Recently Edited Products Dashboard Widget
- **Location**: Top of the admin products list page.
- **Description**: A clean dashboard widget displaying the 5 most recently edited products, including their thumbnail, title, price (singular or range), and last modified timestamp.
- **Actions**:
  - **Edit**: Direct link to the product editing interface.
  - **View Site ↗**: Opens the public product view in a new browser tab.
- **Cascaded Touches**: Saving or updating product images or variant specifications automatically propagates timestamp updates up to the parent product, ensuring correct and immediate placement in the recently edited list.

### 2. Image Alt Text and Zoom Description Fields
- **Location**: Image uploads manager in the product variants editing tab.
- **Support**: Universal fields valid across all upload methods (File upload, direct URL mapping, and S3 integrations).
- **Public Display**:
  - The standard lt attribute is loaded dynamically on the public storefront gallery for general accessibility.
  - The zoom image view within the lightbox modal uses the custom zoom description (zoom_label / zoom alt) as the text caption.

### 3. Edit Inventory Scroll Anchor
- **Behavior**: Clicking the **Edit & Inventory** button on any variant in the admin panel variant list scrolls the page immediately to the active variant editor form section using a #section-variants anchor tag, eliminating the need to scroll manually.

### 4. Centered Gallery Thumbnails
- **Location**: Public product detail view page.
- **Aesthetic**: Replaces the left-justified thumbnail layout with a balanced, center-aligned gallery strip directly underneath the primary product image.

### 5. Quick Admin Edit Shortcut
- **Behavior**: Admins with ole_id = 3 see an **Edit Product (Admin)** button next to the breadcrumbs on the public product detail page. Clicking the button opens the admin edit page in a target window/tab.

### 6. Conditional Search Image Rules
- **Rule**: If a product has more than one variant or multiple images set, the **Search Image** toggle allows deselection of an active search image, ensuring that you can easily switch the main search image across different variants.
- **Badges**: The admin variant list displays all uploaded images with corresponding status badges (e.g. `🔍 Search Image`, `👁️ Active`, `🚫 Inactive`).

### 7. Auto-Close Variant Modal Form
- **Behavior**: Creating or editing a variant automatically closes the variant details overlay/form, bringing the updated list of variants back into focus immediately.

### Modified Files
- [ProductVariant.php](file:///C:/Sites/laravel-gemini/app/Models/ProductVariant.php): Added touches to bubble up updates to the parent Product.
- [ProductImage.php](file:///C:/Sites/laravel-gemini/app/Models/ProductImage.php): Added touches to bubble up updates to the parent ProductVariant.
- [Product.php](file:///C:/Sites/laravel-gemini/app/Models/Product.php): Added price_range dynamic accessor.
- [AdminProducts.php](file:///C:/Sites/laravel-gemini/app/Livewire/AdminProducts.php): Added recently edited products query.
- [AdminProductEdit.php](file:///C:/Sites/laravel-gemini/app/Livewire/AdminProductEdit.php): Added logic to manage new Alt and Zoom inputs, search image toggle rules, and auto-close modal state.
- [admin-products.blade.php](file:///C:/Sites/laravel-gemini/resources/views/livewire/admin-products.blade.php): Added Recently Edited Products dashboard widget layout.
- [variant-management.blade.php](file:///C:/Sites/laravel-gemini/resources/views/livewire/partials/variant-management.blade.php): Added alt text inputs in tables and forms, centered layout, badges for search/active images, scroll anchor #section-variants, and button target references.
- [product-gallery.blade.php](file:///C:/Sites/laravel-gemini/resources/views/livewire/partials/product-gallery.blade.php): Centered gallery thumbnails using flex-layout, and mapped zoom descriptions for the modal lightbox.
- [product-details.blade.php](file:///C:/Sites/laravel-gemini/resources/views/livewire/product-details.blade.php): Added conditional breadcrumb admin shortcut link.
