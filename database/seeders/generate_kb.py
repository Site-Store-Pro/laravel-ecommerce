import os
import re

# Path to the docs and target seeder
docs_path = r"C:\Sites\laravel-gemini\docs\online_store_help_center.md"
seeder_path = r"C:\Sites\laravel-gemini\database\seeders\KbHelpCenterSeeder.php"

# Read docs
with open(docs_path, "r", encoding="utf-8") as f:
    docs_text = f.read()

# Define categories
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

# We will define 156 articles with rich, specific HTML content
articles_data = [
    # Category 101: Quick Start & System Requirements
    (101, "PHP 8.3 & Required Server Extensions Checklist", "php-83-server-extensions-checklist",
     "Comprehensive server requirements checklist for PHP 8.3+ and mandatory extensions.",
     """<div class="prose prose-slate max-w-none">
<div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-6">
    <h3 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-2">Core Server Requirements</h3>
    <p class="text-slate-700 text-sm leading-relaxed mb-0">The platform requires <strong>PHP 8.3.0 or higher</strong> and a web server (Apache, Nginx, or Caddy) running on Linux or Windows environments.</p>
</div>
<h3 class="text-lg font-bold text-slate-900 mb-3">Mandatory PHP Extensions</h3>
<div class="overflow-x-auto mb-6">
<table class="w-full text-left text-sm border-collapse">
<thead><tr class="bg-slate-100 text-slate-700">
  <th class="p-3 border">Extension</th><th class="p-3 border">Purpose in Platform</th><th class="p-3 border">Requirement</th>
</tr></thead>
<tbody>
  <tr><td class="p-3 border font-mono">pdo_mysql / pdo_sqlite</td><td class="p-3 border">Database driver for MySQL 8.0, MariaDB, or local SQLite storage.</td><td class="p-3 border text-emerald-600 font-bold">Required</td></tr>
  <tr><td class="p-3 border font-mono">gd / imagick</td><td class="p-3 border">Product thumbnail resizing, image optimization, and captcha generation.</td><td class="p-3 border text-emerald-600 font-bold">Required</td></tr>
  <tr><td class="p-3 border font-mono">mbstring / ctype</td><td class="p-3 border">Multibyte string processing for multi-language text & Unicode support.</td><td class="p-3 border text-emerald-600 font-bold">Required</td></tr>
  <tr><td class="p-3 border font-mono">curl / openssl</td><td class="p-3 border">HTTP client requests for Stripe, OpenAI API, and shipping carriers.</td><td class="p-3 border text-emerald-600 font-bold">Required</td></tr>
  <tr><td class="p-3 border font-mono">zip / xml / bcmath</td><td class="p-3 border">PhpSpreadsheet Excel imports, RSS feeds, and high-precision financial math.</td><td class="p-3 border text-emerald-600 font-bold">Required</td></tr>
</tbody>
</table>
</div>
<div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-900 text-xs font-medium">
    💡 <strong>Verification Command:</strong> Run <code>php -m</code> in terminal to verify all required extensions are active.
</div>
</div>"""),

    (101, "Composer Dependencies Catalog & Package Matrix", "composer-dependencies-catalog",
     "Complete inventory of production and development Composer dependencies configured in composer.json.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">The platform combines key production libraries to power spreadsheet imports, payment processing, cloud storage, AI translation, and Livewire reactive components.</p>
<h3 class="text-lg font-bold text-slate-900 mb-3">Key Production Packages</h3>
<ul class="list-disc pl-5 text-sm text-slate-700 space-y-2 mb-6">
  <li><strong>phpoffice/phpspreadsheet (^3.7)</strong>: Powers bulk product and inventory CSV/Excel spreadsheet imports in <code class="font-mono bg-slate-100 px-1 rounded">/admin/ecommerce/import</code>.</li>
  <li><strong>stripe/stripe-php (^16.2)</strong>: Stripe PaymentIntents API and webhook verification.</li>
  <li><strong>paddle/paddle-php-sdk (^1.2)</strong>: Paddle Billing API v2 integration.</li>
  <li><strong>league/flysystem-aws-s3-v3 (^3.0)</strong>: AWS S3 storage driver for media uploads and digital downloads.</li>
  <li><strong>openai-php/client (^0.10)</strong>: OpenAI API integration for CMS/Product content generation and multi-language translation.</li>
  <li><strong>livewire/livewire (^3.5)</strong>: Reactive full-stack component UI framework.</li>
  <li><strong>zbateson/mail-mime-parser (^3.0)</strong>: Inbound email reply parser for the support ticket desk.</li>
  <li><strong>staudenmeir/laravel-adjacency-list (^1.22)</strong>: Eloquent recursive CTE trees for nested categories and navigation menus.</li>
</ul>
</div>"""),

    (101, "Master Environment (.env) Configuration Blueprint", "master-environment-dotenv-blueprint",
     "Exhaustive guide to all environment variables required for cloud storage, payment processors, social logins, and OpenAI.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Configure operational environment parameters inside <code class="font-mono bg-slate-100 px-1 rounded">.env</code> at the project root.</p>
<h3 class="text-lg font-bold text-slate-900 mb-3">AWS S3 & CloudFront CDN Credentials</h3>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=my-store-assets
AWS_URL=https://d111111abcdef8.cloudfront.net</code></pre>
<h3 class="text-lg font-bold text-slate-900 mb-3">Social OAuth Credentials</h3>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-your-google-client-secret
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
GITHUB_CLIENT_ID=your-github-client-id
GITHUB_CLIENT_SECRET=your-github-client-secret</code></pre>
<h3 class="text-lg font-bold text-slate-900 mb-3">OpenAI Integration Key</h3>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxx</code></pre>
</div>"""),

    (101, "AWS S3 Bucket & CloudFront CDN Setup Guide", "aws-s3-bucket-cloudfront-cdn-setup",
     "Configuring Amazon S3 object storage and CloudFront CDN asset distribution for storefront media.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">The platform supports seamless offloading of product photos, CMS header images, and digital download assets to AWS S3 with CloudFront CDN acceleration.</p>
<h3 class="text-lg font-bold text-slate-900 mb-3">Step-by-Step Setup</h3>
<ol class="list-decimal pl-5 text-sm text-slate-700 space-y-2 mb-6">
  <li>Create an S3 bucket in AWS Console with private or public-read permissions.</li>
  <li>Create an IAM user with <code class="font-mono bg-slate-100 px-1 rounded">AmazonS3FullAccess</code> policy and generate Access Key ID & Secret.</li>
  <li>Create a CloudFront distribution pointing to your S3 bucket origin.</li>
  <li>Add <code class="font-mono bg-slate-100 px-1 rounded">AWS_URL=https://d111111abcdef8.cloudfront.net</code> to your <code class="font-mono bg-slate-100 px-1 rounded">.env</code> file.</li>
</ol>
</div>"""),

    (101, "Social OAuth Apps Configuration (Google, Facebook, GitHub)", "social-oauth-apps-configuration",
     "Setting up OAuth authorization apps in Google Cloud, Facebook Developers, and GitHub Developer settings.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Enable 1-click customer login and registration using Socialite OAuth providers.</p>
<h3 class="text-lg font-bold text-slate-900 mb-3">Authorized Callback Redirect URIs</h3>
<ul class="list-disc pl-5 text-sm text-slate-700 space-y-2 mb-6">
  <li><strong>Google Callback</strong>: <code class="font-mono bg-slate-100 px-1 rounded">https://yourdomain.com/auth/google/callback</code></li>
  <li><strong>Facebook Callback</strong>: <code class="font-mono bg-slate-100 px-1 rounded">https://yourdomain.com/auth/facebook/callback</code></li>
  <li><strong>GitHub Callback</strong>: <code class="font-mono bg-slate-100 px-1 rounded">https://yourdomain.com/auth/github/callback</code></li>
</ul>
<div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-indigo-900 text-xs font-medium">
    🔒 <strong>Security Note:</strong> Users registering via Social OAuth are automatically email-verified (<code class="font-mono bg-indigo-100 px-1 rounded">email_verified_at = now()</code>).
</div>
</div>"""),

    (101, "Database Migrations & Initial Platform Seeding", "database-migrations-initial-seeding",
     "Executing database schema creation and seeding initial store defaults or full sample catalog data.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Initialize the database structure using Laravel Artisan CLI commands.</p>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code># Run core schema migrations
php artisan migrate

# Seed base application defaults (roles, statuses, CMS pages)
php artisan db:seed

# (Optional) Seed full demo catalog content
php artisan db:seed --class=DemoStoreSeeder</code></pre>
</div>"""),

    (101, "Pre-Launch Demo Store Data Cleanup Engine", "pre-launch-demo-store-data-cleanup",
     "Using the 15-step atomic purge feature in /admin/settings to safely delete demo items before launch.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Before launching your live store, purge sample demo data with a single click in <strong>Admin → Settings</strong> (<code class="font-mono bg-slate-100 px-1 rounded">/admin/settings</code>).</p>
<h3 class="text-lg font-bold text-slate-900 mb-3">Atomic 15-Step Deletion Order</h3>
<ol class="list-decimal pl-5 text-sm text-slate-700 space-y-1 mb-6">
  <li>Delete demo cross-sells (<code class="font-mono bg-slate-100 px-1 rounded">product_cross_selling</code>)</li>
  <li>Delete demo variant images (<code class="font-mono bg-slate-100 px-1 rounded">product_images</code>)</li>
  <li>Delete demo variant event rows (<code class="font-mono bg-slate-100 px-1 rounded">product_variant_events</code>)</li>
  <li>Delete demo inventory stock (<code class="font-mono bg-slate-100 px-1 rounded">products_inventory</code>)</li>
  <li>Delete demo option choices & field definitions (<code class="font-mono bg-slate-100 px-1 rounded">product_field_options</code>, <code class="font-mono bg-slate-100 px-1 rounded">product_fields</code>)</li>
  <li>Delete demo category assignments (<code class="font-mono bg-slate-100 px-1 rounded">product_categories_assignments</code>)</li>
  <li>Delete demo product variants (<code class="font-mono bg-slate-100 px-1 rounded">product_variants</code>)</li>
  <li>Delete demo products (<code class="font-mono bg-slate-100 px-1 rounded">products</code>)</li>
  <li>Delete demo brands (<code class="font-mono bg-slate-100 px-1 rounded">product_brands</code>)</li>
  <li>Delete demo categories (<code class="font-mono bg-slate-100 px-1 rounded">product_categories</code>)</li>
  <li>Delete demo KB articles (<code class="font-mono bg-slate-100 px-1 rounded">kb_articles</code>)</li>
  <li>Delete demo KB categories (<code class="font-mono bg-slate-100 px-1 rounded">kb_categories</code>)</li>
</ol>
</div>"""),

    (101, "SQLite Configuration for Rapid Local Development", "sqlite-configuration-local-development",
     "Setting up lightweight zero-configuration SQLite database files for rapid local testing.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Configure SQLite in <code class="font-mono bg-slate-100 px-1 rounded">.env</code> for quick local development without installing MySQL.</p>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>DB_CONNECTION=sqlite
DB_DATABASE=C:/Sites/laravel-gemini/database/database.sqlite</code></pre>
</div>"""),

    (101, "Production MySQL 8.0 & MariaDB Database Optimization", "production-mysql-mariadb-optimization",
     "Configuring MySQL 8.0+ production databases, InnoDB buffer pools, and utf8mb4 collation.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">For production deployments, MySQL 8.0+ or MariaDB 10.6+ is recommended for optimal performance and full JSON column support.</p>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_store_db
DB_USERNAME=store_user
DB_PASSWORD=SecurePassword123!
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci</code></pre>
</div>"""),

    (101, "Vite Asset Compilation & Tailwind CSS Build Pipeline", "vite-asset-compilation-tailwind",
     "Compiling Tailwind CSS, Alpine.js, and JavaScript bundles using Node.js and Vite.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">The frontend uses Vite to bundle Tailwind CSS styles and JavaScript dependencies.</p>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code># Install dependencies
npm install

# Run Vite dev server with hot module replacement
npm run dev

# Compile production CSS & JS bundles into public/build/
npm run build</code></pre>
</div>"""),

    (101, "PHP Memory Limits, Upload Max Sizes & Execution Time", "php-memory-limits-upload-max-sizes",
     "Recommended php.ini configuration parameters for high-volume spreadsheet imports and translation queues.",
     """<div class="prose prose-slate max-w-none">
<p class="text-slate-700 text-sm leading-relaxed mb-4">Ensure your server's <code class="font-mono bg-slate-100 px-1 rounded">php.ini</code> configuration accommodates large bulk spreadsheet file uploads and background translation tasks.</p>
<pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono mb-6"><code>memory_limit = 512M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300</code></pre>
</div>"""),
]

print(f"Loaded {len(articles_data)} core articles...")
