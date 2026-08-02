import os

# We will generate all 155 articles across 15 categories with authentic content from docs/online_store_help_center.md

categories = [
    (101, "Quick Start & System Requirements", "quick-start-server-setup", "System prerequisites, Composer dependencies catalog, installation commands, database setup, and initial store configuration.", 1),
    (102, "Architecture & Rendering Pipeline", "architecture-rendering", "Laravel 13 tech stack, directory structure, Two-Tier Blade view model, layout wrappers, and dynamic CMS routing vs reserved /kb/ namespace.", 2),
    (103, "Product Page & CMS Layout Systems", "product-page-cms-layout-systems", "The 6 product view layouts, video embeds, full-width CMS page layouts, sidebars, header banners, and per-page background videos.", 3),
    (104, "Site Theme, Header/Footer & Global Settings", "site-theme-header-footer-builder", "Global admin settings, color palettes, dark mode, typography scale, 5-column footer builder, and dynamic CSS token manager.", 4),
    (105, "Product Catalog, Variants & Inventory", "product-catalog-variants-inventory", "Product variants, color swatch deduplication, atomic product COPY engine, bulk CSV spreadsheet importer, bill-pay items, product reviews, and multi-warehouse inventory.", 5),
    (106, "Pricing, Taxes, Shipping & Discounts", "pricing-taxes-shipping-promotions", "US Sales Tax, Canadian GST/PST/HST, International VAT cross-border stripping, all 7 discount types, flat-rate shipping, menu dropdown overrides, and mock real-time shipping providers.", 6),
    (107, "Payment Gateways, Webhooks & Fallbacks", "payment-gateways-webhooks-plugins", "Built-in payment processors (Stripe, Paddle, PayPal, Test Mode), auto-detected extension overrides, incoming webhooks, primary/secondary fallback routing, and custom payment plugin blueprint.", 7),
    (108, "Support Ticket Manager & User Roles", "support-ticket-manager-roles", "Customer ticket portal, staff support queue dashboard, inbound email reply parser, attachments, KB cross-linking, and 5-tier role hierarchy.", 8),
    (109, "Digital Downloads & Asset Manager", "digital-downloads-asset-manager", "Dual download engines: Order-based digital product downloads vs CMS asset downloads manager ([download:ID]), local vs S3 storage, and Video.js streaming.", 9),
    (110, "CMS Embeds, Form Builder & Drawers", "cms-embeds-form-builder-drawers", "Reusable code embeds ([code-embed:ID]), visual form builder with reCAPTCHA v3 ([cms-form:ID]), navigation list menus ([list-menu:ID]), and 4 editor slide-out drawers.", 10),
    (111, "Search Discovery, Autocomplete & Events", "search-discovery-events", "Advanced shop search filtering drawer, multi-content live search autocomplete ([plugin:live-search-2026]), events calendar plugin ([plugin:events-calendar-2026]), and collated FULLTEXT search index.", 11),
    (112, "Access Control, Social Logins & Guests", "access-control-guest-accounts", "Built-in social logins (Google, Facebook, GitHub), user verification matrix, post-order completion redirects, CMS page gating, guest account conversion ([GUEST-USER]), and UUID magic links.", 12),
    (113, "Multi-Language & OpenAI AI Engine", "multi-language-openai-ai", "Language management, flag switchers, RTL support, 10 email template types, inline OpenAI AI content generation, and 11-table bulk AI translation pipeline.", 13),
    (114, "Browser Queue Monitor & Analytics", "queue-monitor-analytics", "Queue monitor operations (/admin/languages/queue-monitor), PID background runner, and e-commerce analytics reports (sales volume, product performance, cart conversion).", 14),
    (115, "Display Plugins & Plugin Architecture", "display-plugins-architecture", "Plugin manager (/admin/plugins), detailed guide to 7 included display plugins, shortcodes, DisplayPlugin and ShippingPlugin interfaces, and custom plugin development blueprint.", 15),
]

# Definition of 155 distinct articles
all_articles = [
    # Cat 101
    (101, 1, "PHP 8.3 & Required Server Extensions Checklist", "php-83-server-extensions-checklist",
     "Comprehensive server requirements checklist for PHP 8.3+ and mandatory extensions.",
     "/admin/settings", "/kb/php-83-server-extensions-checklist",
     "Verification of PHP 8.3+ runtime environment and mandatory extensions (pdo_mysql, gd, mbstring, curl, zip, xml, bcmath).",
     [
         ("pdo_mysql / pdo_sqlite", "Database driver for MySQL 8.0, MariaDB, or local SQLite storage.", "Required"),
         ("gd / imagick", "Product thumbnail resizing, image optimization, and captcha generation.", "Required"),
         ("mbstring / ctype", "Multibyte string processing for multi-language text & Unicode support.", "Required"),
         ("curl / openssl", "HTTP client requests for Stripe, OpenAI API, and shipping carriers.", "Required"),
         ("zip / xml / bcmath", "PhpSpreadsheet Excel imports, RSS feeds, and high-precision financial math.", "Required"),
     ]),

    (101, 2, "Installing Production & Dev Composer Packages", "installing-composer-packages-matrix",
     "Complete inventory of production and development Composer dependencies configured in composer.json.",
     "/admin/settings", "/kb/installing-composer-packages-matrix",
     "Detailed package matrix covering PhpSpreadsheet, Stripe, Paddle, Flysystem S3, OpenAI Client, Livewire 3, and Mail Mime Parser.",
     [
         ("phpoffice/phpspreadsheet (^3.7)", "Powers bulk product and inventory CSV/Excel spreadsheet imports.", "Production"),
         ("stripe/stripe-php (^16.2)", "Stripe PaymentIntents API and webhook signature verification.", "Production"),
         ("paddle/paddle-php-sdk (^1.2)", "Paddle Billing API v2 SDK integration.", "Production"),
         ("league/flysystem-aws-s3-v3 (^3.0)", "AWS S3 storage driver for media uploads and digital downloads.", "Production"),
         ("openai-php/client (^0.10)", "OpenAI API integration for content generation and translation.", "Production"),
     ]),

    (101, 3, "Master Environment (.env) Configuration Blueprint", "master-environment-dotenv-blueprint",
     "Exhaustive guide to all environment variables required for cloud storage, payment processors, social logins, and OpenAI.",
     "/admin/settings", "/kb/master-environment-dotenv-blueprint",
     "Step-by-step configuration of database credentials, S3 buckets, CloudFront CDNs, OAuth apps, and OpenAI API keys.",
     [
         ("AWS_URL", "CloudFront CDN base URL (e.g. https://d111111abcdef8.cloudfront.net).", "AWS S3"),
         ("GOOGLE_CLIENT_ID", "Google OAuth App client ID.", "Social Login"),
         ("OPENAI_API_KEY", "OpenAI secret API key (sk-proj-...).", "AI Engine"),
         ("STRIPE_SECRET", "Stripe Live/Sandbox API secret key.", "Payment Gateway"),
     ]),

    (101, 4, "AWS S3 Bucket & CloudFront CDN Setup Guide", "aws-s3-bucket-cloudfront-cdn-setup",
     "Configuring Amazon S3 object storage and CloudFront CDN asset distribution for storefront media.",
     "/admin/settings", "/kb/aws-s3-bucket-cloudfront-cdn-setup",
     "Guide to setting up AWS S3 buckets, IAM access keys, CORS rules, and CloudFront distribution domain overrides.",
     [
         ("FILESYSTEM_DISK", "Set to s3 to enable AWS object storage.", "Storage Config"),
         ("AWS_BUCKET", "Target Amazon S3 bucket name.", "AWS S3"),
         ("AWS_URL", "CloudFront CDN URL distribution domain.", "CDN Acceleration"),
     ]),

    (101, 5, "Social OAuth Apps Configuration (Google, Facebook, GitHub)", "social-oauth-apps-configuration",
     "Setting up OAuth authorization apps in Google Cloud, Facebook Developers, and GitHub Developer settings.",
     "/admin/settings", "/kb/social-oauth-apps-configuration",
     "Authorized callback URIs and key management for Google, Facebook, and GitHub login integrations.",
     [
         ("Google Callback", "https://yourdomain.com/auth/google/callback", "Google OAuth"),
         ("Facebook Callback", "https://yourdomain.com/auth/facebook/callback", "Meta OAuth"),
         ("GitHub Callback", "https://yourdomain.com/auth/github/callback", "GitHub OAuth"),
     ]),

    (101, 6, "Database Migrations & Initial Platform Seeding", "database-migrations-initial-seeding",
     "Executing database schema creation and seeding initial store defaults or full sample catalog data.",
     "/admin/settings", "/kb/database-migrations-initial-seeding",
     "Running Artisan migration and seeding commands for base platform setup and demo store populating.",
     [
         ("php artisan migrate", "Creates 44+ database tables and foreign keys.", "CLI Command"),
         ("php artisan db:seed", "Seeds default roles, statuses, and core CMS pages.", "CLI Command"),
         ("php artisan db:seed --class=DemoStoreSeeder", "Seeds full demo catalog, items, variants, and KB docs.", "CLI Command"),
     ]),

    (101, 7, "Pre-Launch Demo Store Data Cleanup Engine", "pre-launch-demo-store-data-cleanup",
     "Using the 15-step atomic purge feature in /admin/settings to safely delete demo items before launch.",
     "/admin/settings", "/kb/pre-launch-demo-store-data-cleanup",
     "Walkthrough of the $hasDemoContent detection banner and the 15-step cascading purge method in AdminSettings.",
     [
         ("Step 1-5", "Deletes cross-sells, variant photos, event rows, inventory, and field options.", "Purge Phase 1"),
         ("Step 6-10", "Deletes product fields, category links, variants, products, and brands.", "Purge Phase 2"),
         ("Step 11-15", "Deletes categories, KB articles, and KB categories with is_demo = 1.", "Purge Phase 3"),
     ]),

    (101, 8, "SQLite Configuration for Rapid Local Development", "sqlite-configuration-local-development",
     "Setting up lightweight zero-configuration SQLite database files for rapid local testing.",
     "/admin/settings", "/kb/sqlite-configuration-local-development",
     "Configuring SQLite connection parameters for zero-install local development and automated testing.",
     [
         ("DB_CONNECTION", "Set to sqlite in .env file.", "Database Config"),
         ("DB_DATABASE", "Absolute path to database.sqlite file.", "File Location"),
     ]),

    (101, 9, "Production MySQL 8.0 & MariaDB Database Optimization", "production-mysql-mariadb-optimization",
     "Configuring MySQL 8.0+ production databases, InnoDB buffer pools, and utf8mb4 collation.",
     "/admin/settings", "/kb/production-mysql-mariadb-optimization",
     "MySQL 8.0 server optimization, InnoDB buffer pool sizing, utf8mb4_unicode_ci collation, and strict mode.",
     [
         ("DB_CHARSET", "utf8mb4 for full Unicode and emoji support.", "Collation"),
         ("max_allowed_packet", "64M minimum for large blob and image binary storage.", "MySQL Server"),
     ]),

    (101, 10, "Vite Asset Compilation & Tailwind CSS Build Pipeline", "vite-asset-compilation-tailwind",
     "Compiling Tailwind CSS, Alpine.js, and JavaScript bundles using Node.js and Vite.",
     "/admin/settings", "/kb/vite-asset-compilation-tailwind",
     "Running Vite development server and compiling production CSS/JS asset bundles.",
     [
         ("npm run dev", "Launches Vite dev server with hot module replacement.", "Dev Workflow"),
         ("npm run build", "Compiles optimized minified assets into public/build/.", "Production Build"),
     ]),

    (101, 11, "PHP Memory Limits, Upload Max Sizes & Execution Time", "php-memory-limits-upload-max-sizes",
     "Recommended php.ini configuration parameters for high-volume spreadsheet imports and translation queues.",
     "/admin/settings", "/kb/php-memory-limits-upload-max-sizes",
     "Tuning php.ini limits for memory_limit, upload_max_filesize, post_max_size, and max_execution_time.",
     [
         ("memory_limit", "512M recommended for PhpSpreadsheet bulk imports.", "PHP Config"),
         ("max_execution_time", "300 seconds for AI translation queues.", "PHP Config"),
     ]),
]

print("Script template ready...")
