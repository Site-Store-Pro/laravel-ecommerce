<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KbHelpCenterSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $categories = [
            ['id' => 101, 'name' => 'Quick Start & System Requirements', 'slug' => 'quick-start-server-setup', 'description' => 'System prerequisites, Composer dependencies catalog, installation commands, database setup, and initial store configuration.', 'sort_order' => 1, 'is_demo' => 1],
            ['id' => 102, 'name' => 'Architecture & Rendering Pipeline', 'slug' => 'architecture-rendering', 'description' => 'Laravel 13 tech stack, directory structure, Two-Tier Blade view model, layout wrappers, and dynamic CMS routing vs reserved /kb/ namespace.', 'sort_order' => 2, 'is_demo' => 1],
            ['id' => 103, 'name' => 'Product Page & CMS Layout Systems', 'slug' => 'product-page-cms-layout-systems', 'description' => 'The 6 product view layouts, video embeds, full-width CMS page layouts, sidebars, header banners, and per-page background videos.', 'sort_order' => 3, 'is_demo' => 1],
            ['id' => 104, 'name' => 'Site Theme, Header/Footer & Global Settings', 'slug' => 'site-theme-header-footer-builder', 'description' => 'Global admin settings, color palettes, dark mode, typography scale, 5-column footer builder, and dynamic CSS token manager.', 'sort_order' => 4, 'is_demo' => 1],
            ['id' => 105, 'name' => 'Product Catalog, Variants & Inventory', 'slug' => 'product-catalog-variants-inventory', 'description' => 'Product variants, color swatch deduplication, atomic product COPY engine, bulk CSV spreadsheet importer, bill-pay items, product reviews, and multi-warehouse inventory.', 'sort_order' => 5, 'is_demo' => 1],
            ['id' => 106, 'name' => 'Pricing, Taxes, Shipping & Discounts', 'slug' => 'pricing-taxes-shipping-promotions', 'description' => 'US Sales Tax, Canadian GST/PST/HST, International VAT cross-border stripping, all 7 discount types, flat-rate shipping, menu dropdown overrides, and mock real-time shipping providers.', 'sort_order' => 6, 'is_demo' => 1],
            ['id' => 107, 'name' => 'Payment Gateways, Webhooks & Fallbacks', 'slug' => 'payment-gateways-webhooks-plugins', 'description' => 'Built-in payment processors (Stripe, Paddle, PayPal, Test Mode), auto-detected extension overrides, incoming webhooks, primary/secondary fallback routing, and custom payment plugin blueprint.', 'sort_order' => 7, 'is_demo' => 1],
            ['id' => 108, 'name' => 'Support Ticket Manager & User Roles', 'slug' => 'support-ticket-manager-roles', 'description' => 'Customer ticket portal, staff support queue dashboard, inbound email reply parser, attachments, KB cross-linking, and 5-tier role hierarchy.', 'sort_order' => 8, 'is_demo' => 1],
            ['id' => 109, 'name' => 'Digital Downloads & Asset Manager', 'slug' => 'digital-downloads-asset-manager', 'description' => 'Dual download engines: Order-based digital product downloads vs CMS asset downloads manager ([download:ID]), local vs S3 storage, and Video.js streaming.', 'sort_order' => 9, 'is_demo' => 1],
            ['id' => 110, 'name' => 'CMS Embeds, Form Builder & Drawers', 'slug' => 'cms-embeds-form-builder-drawers', 'description' => 'Reusable code embeds ([code-embed:ID]), visual form builder with reCAPTCHA v3 ([cms-form:ID]), navigation list menus ([list-menu:ID]), and 4 editor slide-out drawers.', 'sort_order' => 10, 'is_demo' => 1],
            ['id' => 111, 'name' => 'Search Discovery, Autocomplete & Events', 'slug' => 'search-discovery-events', 'description' => 'Advanced shop search filtering drawer, multi-content live search autocomplete ([plugin:live-search-2026]), events calendar plugin ([plugin:events-calendar-2026]), and collated FULLTEXT search index.', 'sort_order' => 11, 'is_demo' => 1],
            ['id' => 112, 'name' => 'Access Control, Social Logins & Guests', 'slug' => 'access-control-guest-accounts', 'description' => 'Built-in social logins (Google, Facebook, GitHub), user verification matrix, post-order completion redirects, CMS page gating, guest account conversion ([GUEST-USER]), and UUID magic links.', 'sort_order' => 12, 'is_demo' => 1],
            ['id' => 113, 'name' => 'Multi-Language & OpenAI AI Engine', 'slug' => 'multi-language-openai-ai', 'description' => 'Language management, flag switchers, RTL support, 10 email template types, inline OpenAI AI content generation, and 11-table bulk AI translation pipeline.', 'sort_order' => 13, 'is_demo' => 1],
            ['id' => 114, 'name' => 'Browser Queue Monitor & Analytics', 'slug' => 'queue-monitor-analytics', 'description' => 'Queue monitor operations (/admin/languages/queue-monitor), PID background runner, and e-commerce analytics reports (sales volume, product performance, cart conversion).', 'sort_order' => 14, 'is_demo' => 1],
            ['id' => 115, 'name' => 'Display Plugins & Plugin Architecture', 'slug' => 'display-plugins-architecture', 'description' => 'Plugin manager (/admin/plugins), detailed guide to 7 included display plugins, shortcodes, DisplayPlugin and ShippingPlugin interfaces, and custom plugin development blueprint.', 'sort_order' => 15, 'is_demo' => 1],
        ];

        foreach ($categories as $cat) {
            DB::table('kb_categories')->updateOrInsert(
                ['id' => $cat['id']],
                array_merge($cat, ['created_at' => $now, 'updated_at' => $now])
            );
        }

        $articles = $this->getArticlesData();

        $articleIdCounter = 1001;
        foreach ($articles as $art) {
            $content = $this->buildArticleHtml(
                $art['title'],
                $art['summary'],
                $art['admin_route'],
                $art['public_route'],
                $art['details'],
                $art['code_snippet'] ?? null
            );

            DB::table('kb_articles')->updateOrInsert(
                ['id' => $articleIdCounter],
                [
                    'category_id'      => $art['category_id'],
                    'title'            => $art['title'],
                    'seo_link'         => Str::slug($art['title']),
                    'meta_description' => $art['summary'],
                    'article_content'  => $content,
                    'article_active'   => 1,
                    'show_date'        => 1,
                    'date_added'       => $now,
                    'date_modified'    => $now,
                    'sort_order'       => $art['sort_order'],
                    'kb_rating'        => 5.00,
                    'is_demo'          => 1,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );
            $articleIdCounter++;
        }
    }

    private function buildArticleHtml(string $title, string $summary, string $adminRoute, string $publicRoute, array $details, ?string $codeSnippet = null): string
    {
        $detailsHtml = '';
        foreach ($details as $item) {
            $label = e($item[0]);
            $desc = e($item[1]);
            $badge = isset($item[2]) ? '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-indigo-50 text-indigo-700 font-mono">' . e($item[2]) . '</span>' : '';
            $detailsHtml .= "<li class=\"mb-2\"><strong class=\"text-slate-900 font-semibold\">{$label}:</strong> {$desc}{$badge}</li>";
        }

        $snippetHtml = '';
        if ($codeSnippet) {
            $codeEscaped = e($codeSnippet);
            $snippetHtml = "
                <h3 class=\"text-base font-bold text-slate-900 mt-6 mb-3\">Configuration / Syntax Reference</h3>
                <pre class=\"bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono overflow-x-auto mb-6\"><code>{$codeEscaped}</code></pre>
            ";
        }

        return "
            <div class=\"prose prose-slate max-w-none\">
                <div class=\"bg-indigo-50/70 border border-indigo-100 rounded-2xl p-6 mb-6\">
                    <h3 class=\"text-xs font-extrabold text-indigo-900 uppercase tracking-wider mb-2\">Article Overview</h3>
                    <p class=\"text-slate-700 text-sm leading-relaxed mb-0\">" . e($summary) . "</p>
                </div>

                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-4 mb-6\">
                    <div class=\"bg-white border border-slate-200 rounded-xl p-4 shadow-sm\">
                        <h4 class=\"text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1\">Admin Control Location</h4>
                        <code class=\"text-xs font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded\">" . e($adminRoute) . "</code>
                    </div>
                    <div class=\"bg-white border border-slate-200 rounded-xl p-4 shadow-sm\">
                        <h4 class=\"text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1\">Target Storefront Location</h4>
                        <code class=\"text-xs font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-1 rounded\">" . e($publicRoute) . "</code>
                    </div>
                </div>

                <h3 class=\"text-base font-bold text-slate-900 mb-3\">Key Architectural & Operational Concepts</h3>
                <ul class=\"list-disc pl-5 text-sm text-slate-700 space-y-1 mb-6\">
                    {$detailsHtml}
                </ul>

                {$snippetHtml}

                <h3 class=\"text-base font-bold text-slate-900 mb-3\">Step-by-Step Administrative Procedure</h3>
                <ol class=\"list-decimal pl-5 text-sm text-slate-700 space-y-2 mb-6\">
                    <li>Navigate to <code class=\"font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs\">" . e($adminRoute) . "</code> within the store administration workspace.</li>
                    <li>Inspect or modify the operational attributes according to your store policies.</li>
                    <li>Save changes to persist parameters key-value in database configuration tables and flush platform caches.</li>
                    <li>Verify live execution on the public storefront at <code class=\"font-mono bg-slate-100 px-1.5 py-0.5 rounded text-xs\">" . e($publicRoute) . "</code>.</li>
                </ol>

                <div class=\"bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-900 text-xs font-medium\">
                    💡 <strong>Master Reference:</strong> Full developer blueprints and architectural schemas for <em>" . e($title) . "</em> are maintained in <code class=\"font-mono bg-amber-100 px-1 rounded\">docs/online_store_help_center.md</code>.
                </div>
            </div>
        ";
    }

    private function getArticlesData(): array
    {
        return [
            // ── CATEGORY 101: Quick Start & System Requirements (11 articles) ──
            [
                'category_id' => 101, 'sort_order' => 1,
                'title' => 'PHP 8.3 & Required Server Extensions Checklist',
                'summary' => 'Comprehensive server requirements checklist for PHP 8.3+ and mandatory extensions.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/php-83-server-extensions-checklist',
                'details' => [
                    ['pdo_mysql / pdo_sqlite', 'Database drivers for MySQL 8.0, MariaDB, or local SQLite storage.', 'Required Driver'],
                    ['gd / imagick', 'Product thumbnail creation, image cropping, and CAPTCHA rendering.', 'Image Processing'],
                    ['mbstring / ctype', 'Multibyte string processing for multi-language UTF-8 text support.', 'Unicode'],
                    ['curl / openssl', 'Secure HTTP requests for Stripe, Paddle, PayPal, and OpenAI API calls.', 'HTTP Client'],
                    ['zip / xml / bcmath', 'PhpSpreadsheet Excel processing, RSS feeds, and high-precision financial math.', 'Data Processing'],
                ],
                'code_snippet' => "php -m | grep -E 'pdo|gd|mbstring|curl|zip|bcmath|openssl'",
            ],
            [
                'category_id' => 101, 'sort_order' => 2,
                'title' => 'Installing Production & Dev Composer Packages',
                'summary' => 'Complete inventory of production and development Composer dependencies configured in composer.json.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/installing-composer-packages-matrix',
                'details' => [
                    ['phpoffice/phpspreadsheet (^3.7)', 'Powers bulk product and inventory CSV/Excel spreadsheet imports.', 'Spreadsheet Importer'],
                    ['stripe/stripe-php (^16.2)', 'Stripe PaymentIntents API SDK and webhook signature verification.', 'Payment Gateway'],
                    ['paddle/paddle-php-sdk (^1.2)', 'Paddle Billing API v2 SDK integration.', 'Payment Gateway'],
                    ['league/flysystem-aws-s3-v3 (^3.0)', 'AWS S3 storage driver for media uploads and digital downloads.', 'Cloud Storage'],
                    ['openai-php/client (^0.10)', 'OpenAI API client for CMS/Product content generation and multi-language AI translation.', 'AI Engine'],
                    ['livewire/livewire (^3.5)', 'Reactive full-stack component UI framework.', 'Core UI Framework'],
                ],
                'code_snippet' => "composer install --no-dev --optimize-autoloader",
            ],
            [
                'category_id' => 101, 'sort_order' => 3,
                'title' => 'Master Environment (.env) Configuration Blueprint',
                'summary' => 'Exhaustive guide to all environment variables required for cloud storage, payment processors, social logins, and OpenAI.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/master-environment-dotenv-blueprint',
                'details' => [
                    ['AWS_URL', 'CloudFront CDN base distribution URL (e.g. https://d111111abcdef8.cloudfront.net).', 'CDN Acceleration'],
                    ['GOOGLE_CLIENT_ID / SECRET', 'Google OAuth client credentials for 1-click social logins.', 'OAuth Login'],
                    ['OPENAI_API_KEY', 'OpenAI secret API key (sk-proj-...) for content generation and translation.', 'AI Engine'],
                    ['STRIPE_KEY / SECRET', 'Stripe API keys for live or sandbox card processing.', 'Stripe Gateway'],
                ],
                'code_snippet' => "FILESYSTEM_DISK=s3\nAWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE\nAWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY\nAWS_DEFAULT_REGION=us-east-1\nAWS_BUCKET=my-store-assets\nAWS_URL=https://d111111abcdef8.cloudfront.net\nOPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxx",
            ],
            [
                'category_id' => 101, 'sort_order' => 4,
                'title' => 'AWS S3 Bucket & CloudFront CDN Setup Guide',
                'summary' => 'Configuring Amazon S3 object storage and CloudFront CDN asset distribution for storefront media.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/aws-s3-bucket-cloudfront-cdn-setup',
                'details' => [
                    ['S3 Bucket Creation', 'Create an AWS S3 bucket with private or public-read asset permissions.', 'AWS S3'],
                    ['IAM Credentials', 'Generate IAM Access Key ID and Secret Access Key with AmazonS3FullAccess policy.', 'Security'],
                    ['CloudFront Distribution', 'Connect S3 origin to CloudFront CDN for global low-latency media delivery.', 'CDN Delivery'],
                ],
                'code_snippet' => "AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE\nAWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY\nAWS_DEFAULT_REGION=us-east-1\nAWS_BUCKET=my-store-media\nAWS_URL=https://d111111abcdef8.cloudfront.net",
            ],
            [
                'category_id' => 101, 'sort_order' => 5,
                'title' => 'Social OAuth Apps Configuration (Google, Facebook, GitHub)',
                'summary' => 'Setting up OAuth authorization apps in Google Cloud, Facebook Developers, and GitHub Developer settings.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/social-oauth-apps-configuration',
                'details' => [
                    ['Google Console', 'Configure OAuth 2.0 Client ID under Google Cloud API Credentials.', 'Google OAuth'],
                    ['Meta Developers', 'Create a Facebook Login App in Meta for Developers dashboard.', 'Facebook OAuth'],
                    ['GitHub Settings', 'Create an OAuth App under GitHub Developer Settings.', 'GitHub OAuth'],
                    ['Auto Verification', 'Social OAuth accounts are automatically email-verified upon registration.', 'Account Verification'],
                ],
                'code_snippet' => "# Callback Redirect URIs:\nhttps://yourdomain.com/auth/google/callback\nhttps://yourdomain.com/auth/facebook/callback\nhttps://yourdomain.com/auth/github/callback",
            ],
            [
                'category_id' => 101, 'sort_order' => 6,
                'title' => 'Database Migrations & Initial Platform Seeding',
                'summary' => 'Executing database schema creation and seeding initial store defaults or full sample catalog data.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/database-migrations-initial-seeding',
                'details' => [
                    ['php artisan migrate', 'Executes 44+ database migration files to build all tables and FK constraints.', 'Schema Migration'],
                    ['php artisan db:seed', 'Seeds base user roles, order statuses, CMS pages, and core plugins.', 'Core Seeder'],
                    ['php artisan db:seed --class=DemoStoreSeeder', 'Seeds full sample catalog (products, variants, brands, categories, KB articles).', 'Demo Seeder'],
                ],
                'code_snippet' => "php artisan migrate\nphp artisan db:seed\nphp artisan db:seed --class=DemoStoreSeeder",
            ],
            [
                'category_id' => 101, 'sort_order' => 7,
                'title' => 'Pre-Launch Demo Store Data Cleanup Engine',
                'summary' => 'Using the 15-step atomic purge feature in /admin/settings to safely delete demo items before launch.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/pre-launch-demo-store-data-cleanup',
                'details' => [
                    ['$hasDemoContent Detection', 'Detects demo content when products or kb_articles contain is_demo = 1.', 'Admin Detection'],
                    ['Atomic Deletion Order', 'Executes 15-step FK cascade deletion to remove sample items while preserving custom data.', 'Cascade Delete'],
                    ['Purge Target Entities', 'Wipes demo products, variants, images, inventory, fields, brands, categories, and KB articles.', 'Purge Scope'],
                ],
                'code_snippet' => "// AdminSettings.php purge sequence:\nDB::table('products')->where('is_demo', 1)->delete();\nDB::table('kb_articles')->where('is_demo', 1)->delete();\nDB::table('kb_categories')->where('is_demo', 1)->delete();",
            ],
            [
                'category_id' => 101, 'sort_order' => 8,
                'title' => 'SQLite Configuration for Rapid Local Development',
                'summary' => 'Setting up lightweight zero-configuration SQLite database files for rapid local testing.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/sqlite-configuration-local-development',
                'details' => [
                    ['Zero Configuration', 'No local MySQL server installation required for local development.', 'Local Dev'],
                    ['Database File', 'Creates a single portable database.sqlite file inside database/ directory.', 'Storage File'],
                ],
                'code_snippet' => "DB_CONNECTION=sqlite\nDB_DATABASE=C:/Sites/laravel-gemini/database/database.sqlite",
            ],
            [
                'category_id' => 101, 'sort_order' => 9,
                'title' => 'Production MySQL 8.0 & MariaDB Database Optimization',
                'summary' => 'Configuring MySQL 8.0+ production databases, InnoDB buffer pools, and utf8mb4 collation.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/production-mysql-mariadb-optimization',
                'details' => [
                    ['MySQL 8.0+', 'Recommended production database engine supporting native JSON columns and CTEs.', 'Database Engine'],
                    ['utf8mb4_unicode_ci', 'Provides full multibyte Unicode, international script, and emoji character support.', 'Collation'],
                ],
                'code_snippet' => "DB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=online_store_db\nDB_USERNAME=store_user\nDB_PASSWORD=SecurePassword123!\nDB_CHARSET=utf8mb4\nDB_COLLATION=utf8mb4_unicode_ci",
            ],
            [
                'category_id' => 101, 'sort_order' => 10,
                'title' => 'Vite Asset Compilation & Tailwind CSS Build Pipeline',
                'summary' => 'Compiling Tailwind CSS, Alpine.js, and JavaScript bundles using Node.js and Vite.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/vite-asset-compilation-tailwind',
                'details' => [
                    ['Vite Build Pipeline', 'Bundles Tailwind CSS utilities, Alpine.js directives, and JavaScript modules.', 'Asset Bundler'],
                    ['npm run dev', 'Launches local Vite hot module replacement dev server.', 'Development'],
                    ['npm run build', 'Compiles minified production CSS & JS bundles into public/build/.', 'Production'],
                ],
                'code_snippet' => "npm install\nnpm run dev\nnpm run build",
            ],
            [
                'category_id' => 101, 'sort_order' => 11,
                'title' => 'PHP Memory Limits, Upload Max Sizes & Execution Time',
                'summary' => 'Recommended php.ini configuration parameters for high-volume spreadsheet imports and translation queues.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/php-memory-limits-upload-max-sizes',
                'details' => [
                    ['memory_limit = 512M', 'Allocates sufficient memory for bulk PhpSpreadsheet Excel imports.', 'PHP Limits'],
                    ['upload_max_filesize = 100M', 'Allows large digital product files and video asset uploads.', 'Upload Size'],
                    ['max_execution_time = 300', 'Prevents web requests from timing out during bulk AI translation queues.', 'Execution Time'],
                ],
                'code_snippet' => "; php.ini settings:\nmemory_limit = 512M\nupload_max_filesize = 100M\npost_max_size = 100M\nmax_execution_time = 300",
            ],

            // ── CATEGORY 102: Architecture & Rendering Pipeline (10 articles) ──
            [
                'category_id' => 102, 'sort_order' => 1,
                'title' => 'Laravel 13 & Livewire 3 Technology Stack Overview',
                'summary' => 'Overview of the modern stack combining Laravel 13, Livewire 3, Alpine.js, and Tailwind CSS.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/laravel-13-livewire-3-stack-overview',
                'details' => [
                    ['Laravel 13 Core', 'Modern PHP 8.3 web framework providing ORM, routing, auth, and queueing.', 'Backend Framework'],
                    ['Livewire 3', 'Full-stack reactive component UI framework powering dynamic forms and tables.', 'Reactive UI'],
                    ['Alpine.js', 'Micro-interactions, slide-cart drawers, image zoom, and interactive dropdowns.', 'Client JS'],
                    ['Tailwind CSS v3', 'Dynamic utility classes, CSS custom variables token compiler, and responsive grids.', 'Styling'],
                ],
                'code_snippet' => "// Livewire 3 Component Example:\nclass ShopCatalog extends Component {\n    use WithPagination;\n    public string \$search = '';\n}",
            ],
            [
                'category_id' => 102, 'sort_order' => 2,
                'title' => 'Project Directory Structure & Key Namespaces',
                'summary' => 'Navigating app/Http, app/Livewire, app/Services, app/Plugins, resources/views, and payment-processors.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/project-directory-structure-namespaces',
                'details' => [
                    ['app/Livewire/', 'Full-page and component Livewire controllers (AdminSettings, ShopCatalog).', 'Livewire Controllers'],
                    ['app/Services/', 'Domain service engines (TaxService, DiscountService, ContentParserService).', 'Business Logic'],
                    ['app/Plugins/', 'Installed display and shipping extension plugins.', 'Plugin Architecture'],
                    ['payment-processors/', 'Auto-detected third-party payment gateway integration classes.', 'Payment Extensions'],
                ],
                'code_snippet' => "app/\n├── Http/Controllers/\n├── Livewire/\n├── Models/\n├── Plugins/\n└── Services/\npayment-processors/",
            ],
            [
                'category_id' => 102, 'sort_order' => 3,
                'title' => 'Two-Tier Blade View Model & Rendering Architecture',
                'summary' => 'Deep dive into Tier 1 Full-Page Blades versus Tier 2 Livewire Component Partials.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/two-tier-blade-view-model-architecture',
                'details' => [
                    ['Tier 1: Layout Wrappers', 'Full HTML document containers (layouts/public.blade.php & layouts/app.blade.php).', 'Page Shell'],
                    ['Tier 2: Component Partials', 'Focused UI components (livewire/shop-catalog.blade.php, partials/slide-cart.blade.php).', 'UI Partials'],
                ],
                'code_snippet' => "<!-- Tier 1 Public Wrapper -->\n<x-layouts.public>\n    <!-- Tier 2 Livewire Partial -->\n    <livewire:shop-catalog />\n</x-layouts.public>",
            ],
            [
                'category_id' => 102, 'sort_order' => 4,
                'title' => 'Dynamic CMS Site Builder Routing Mechanics (/{slug})',
                'slug' => 'dynamic-cms-site-builder-routing',
                'summary' => 'How GET /{slug} matches dynamic CMS pages including nested sub-directory paths like /blog/article-name.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/blog/article-name',
                'details' => [
                    ['Dynamic Route Catch-All', 'GET /{slug} evaluates cms_pages table to resolve dynamic CMS site builder pages.', 'CMS Routing'],
                    ['Sub-Directory Slugs', 'Supports nested sub-directory slugs (e.g. slug = "blog/article-name" or "company/about").', 'Nested Paths'],
                ],
                'code_snippet' => "Route::get('/{slug}', CmsPageView::class)->where('slug', '.*');",
            ],
            [
                'category_id' => 102, 'sort_order' => 5,
                'title' => 'Reserved Knowledge Base Route Namespace (/kb/{slug})',
                'summary' => 'Why Knowledge Base articles use reserved /kb/ namespace to prevent collision with CMS site builder pages.',
                'admin_route' => '/admin/cms/kb-articles', 'public_route' => '/kb/article-slug',
                'details' => [
                    ['Reserved /kb/ Namespace', 'Knowledge Base articles are explicitly routed under /kb/{slug}.', 'Route Isolation'],
                    ['Collision Prevention', 'Prevents URL conflicts between Help Center documentation and dynamic CMS pages.', 'SEO Safety'],
                ],
                'code_snippet' => "Route::get('/kb/{slug}', KbArticleDetails::class)->name('kb.article');",
            ],
            [
                'category_id' => 102, 'sort_order' => 6,
                'title' => 'Eloquent Adjacency List Trees for Nested Hierarchies',
                'summary' => 'Using staudenmeir/laravel-adjacency-list for recursive category and nav menu hierarchy queries.',
                'admin_route' => '/admin/ecommerce/categories', 'public_route' => '/shop',
                'details' => [
                    ['Recursive CTE Queries', 'Uses staudenmeir/laravel-adjacency-list package for single-query hierarchy fetching.', 'Eloquent CTE'],
                    ['Parent-Child Trees', 'Builds multi-level product categories and dynamic navigation dropdown menus.', 'Tree Structures'],
                ],
                'code_snippet' => "use Staudenmeir\\LaravelAdjacencyList\\Eloquent\\HasRecursiveRelationships;\nclass ProductCategory extends Model {\n    use HasRecursiveRelationships;\n}",
            ],
            [
                'category_id' => 102, 'sort_order' => 7,
                'title' => 'Controller vs Livewire Component Responsibilities',
                'summary' => 'Best practices for choosing between standard HTTP controllers and reactive Livewire components.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/controller-vs-livewire-responsibilities',
                'details' => [
                    ['HTTP Controllers', 'Used for stateless API endpoints, webhooks, file downloads, and OAuth callbacks.', 'Stateless Requests'],
                    ['Livewire Components', 'Used for interactive UI components, admin edit forms, catalog search, and carts.', 'Stateful Components'],
                ],
                'code_snippet' => "// Controller: Handles binary file streaming\nclass DownloadController extends Controller { ... }\n// Livewire: Handles reactive admin form\nclass AdminProductEdit extends Component { ... }",
            ],
            [
                'category_id' => 102, 'sort_order' => 8,
                'title' => 'Request Middleware Pipeline & Execution Order',
                'summary' => 'Execution order of ProcessCmsShortcodes middleware, SetLocale, and EnsureUserIsAdmin.',
                'admin_route' => '/admin/settings', 'public_route' => '/kb/request-middleware-pipeline',
                'details' => [
                    ['ProcessCmsShortcodes', 'Global middleware resolving list menus and navigation shortcodes on public HTML.', 'Global Middleware'],
                    ['SetLocale', 'Resolves user language preferences, flags, and text direction.', 'Localization'],
                    ['EnsureUserIsAdmin', 'Guards /admin route paths against unauthorized access.', 'Security Guard'],
                ],
                'code_snippet' => "->withMiddleware(function (Middleware \$middleware) {\n    \$middleware->web(append: [\n        \\App\\Http\\Middleware\\SetLocale::class,\n        \\App\\Http\\Middleware\\ProcessCmsShortcodes::class,\n    ]);\n})",
            ],
            [
                'category_id' => 102, 'sort_order' => 9,
                'title' => 'Public Storefront Layout Wrapper (layouts.public)',
                'summary' => 'Features of the public wrapper including font loaders, RTL attributes, theme CSS, and public header/footer.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Document Shell', 'Renders public HTML header, Google Fonts CSS imports, and custom JS scripts.', 'HTML Shell'],
                    ['Dynamic Theme Injection', 'Injects root CSS custom variables compiled by HeaderFooterCssManager.', 'CSS Tokens'],
                    ['RTL Text Direction', 'Sets dir="rtl" attribute dynamically when an RTL language is selected.', 'RTL Support'],
                ],
                'code_snippet' => "<html lang=\"{{ str_replace('_', '-', app()->getLocale()) }}\" dir=\"{{ \$rtl ? 'rtl' : 'ltr' }}\">\n<x-site-theme-styles />",
            ],
            [
                'category_id' => 102, 'sort_order' => 10,
                'title' => 'Admin Workspace Layout Wrapper (layouts.app)',
                'summary' => 'Isolated admin layout structure including operations sidebar, navigation topbar, and dark mode toggles.',
                'admin_route' => '/admin/settings', 'public_route' => '/admin',
                'details' => [
                    ['Admin Sidebar Navigation', 'Collapsible admin menu grouping Shop, CMS, Shipping, Languages, and Settings.', 'Admin Sidebar'],
                    ['Dark Slate Theme', 'Supports dark mode toggle specifically for the admin control panel workspace.', 'Admin Dark Mode'],
                ],
                'code_snippet' => "<div class=\"min-h-screen bg-slate-100 dark:bg-slate-900 flex\">\n    <x-admin-sidebar />\n    <main class=\"flex-1 p-6\">{{ \$slot }}</main>\n</div>",
            ],

            // ── CATEGORY 103: Product Page & CMS Layout Systems (10 articles) ──
            [
                'category_id' => 103, 'sort_order' => 1,
                'title' => 'The 6 Product View Layout Options Guide',
                'summary' => 'Comprehensive visual guide to the 6 distinct product detail page layout options.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Layout 1: Right Side Images', 'Standard buy box on left, interactive gallery on right (Default).', 'Layout 1'],
                    ['Layout 2: Left Side Images', 'Buy box on right, gallery on left.', 'Layout 2'],
                    ['Layout 3: Right Side + Video Below', 'Gallery on right with full-width video container below buy box.', 'Layout 3'],
                    ['Layout 4: Centered Image Hero', 'Full-width top hero gallery with centered descriptions below.', 'Layout 4'],
                    ['Layout 5: Centered Video Hero', 'Full-width video player header at top of product view.', 'Layout 5'],
                    ['Layout 6: Minimalist Digital View', 'High-converting minimalist view hiding gallery for digital products.', 'Layout 6'],
                ],
                'code_snippet' => "// Product model layout assignment:\n\$product->layout_type = 1; // 1 to 6",
            ],
            [
                'category_id' => 103, 'sort_order' => 2,
                'title' => 'CMS Page Background Loop Videos & Dark Glass Overlays',
                'summary' => 'Configuring looping background video headers with dark glass overlays for CMS pages.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/custom-landing-page',
                'details' => [
                    ['Background Video Priority', 'Per-page background video settings take highest priority, overriding site defaults.', 'Media Priority'],
                    ['Storage Options', 'Local upload, AWS S3, Custom S3 credentials, or direct MP4/WebM CDN URL.', 'Media Drivers'],
                    ['Glassmorphism Overlay', 'Set overlay tint color and opacity percentage for high text readability.', 'Dark Overlay'],
                ],
                'code_snippet' => "<video autoplay loop muted playsinline class=\"fixed inset-0 w-full h-full object-cover z-0\">\n    <source src=\"{{ \$page->background_video_url }}\" type=\"video/mp4\">\n</video>",
            ],
            [
                'category_id' => 103, 'sort_order' => 3,
                'title' => 'Full-Width Top Header Hero Slideshows on CMS Pages',
                'summary' => 'Assigning full-width hero slideshow banners to the top of CMS pages.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/',
                'details' => [
                    ['Top Header Slideshow', 'Select a slide deck under "Include Top Header Slideshow" on CMS Page Edit.', 'Slideshow Assignment'],
                    ['Full-Width Hero Rendering', 'Renders a responsive hero banner slider above the page body container.', 'Hero Slider'],
                ],
                'code_snippet' => "// CMS Page Editor Field:\n\$page->include_slideshow = \$slideshowId; // Renders [plugin:slideshow-2026 id=X] at top",
            ],
            [
                'category_id' => 103, 'sort_order' => 4,
                'title' => 'CMS Page Layout Modes (Full-Width vs Left/Right Sidebars)',
                'summary' => 'Configuring single-column full-width layouts versus 2-column left or right sidebar layouts.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/blog',
                'details' => [
                    ['Layout 1: Single-Column Full Width', 'Clean 1-column layout without sidebars, ideal for landing pages.', 'Full Width'],
                    ['Layout 2: 2-Column Right Sidebar', 'Body content on left (66%) with sidebar widgets on right (33%).', 'Right Sidebar'],
                    ['Layout 3: 2-Column Left Sidebar', 'Sidebar widgets on left (33%) with body content on right (66%).', 'Left Sidebar'],
                ],
                'code_snippet' => "<!-- Layout Switcher in blade -->\n@if(\$page->layout_type == 2)\n    <div class=\"grid grid-cols-1 lg:grid-cols-3 gap-8\">\n        <div class=\"lg:col-span-2\">{!! \$content !!}</div>\n        <aside>{!! \$page->right_col !!}</aside>\n    </div>\n@endif",
            ],
            [
                'category_id' => 103, 'sort_order' => 5,
                'title' => 'Custom Header Banner Images & Alternate Title Overlays',
                'summary' => 'Setting per-page top header banner images with custom alternate title text overlays.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/about-us',
                'details' => [
                    ['Header Banner Image', 'Upload or link a custom banner background image for the page title bar.', 'Banner Image'],
                    ['Alternate Title Overlay', 'Override standard page title with styled alternate hero text.', 'Title Overlay'],
                ],
                'code_snippet' => "\$page->header_image = 'banners/about-banner.jpg';\n\$page->custom_header_title = 'Our Heritage & Mission';",
            ],
            [
                'category_id' => 103, 'sort_order' => 6,
                'title' => 'CMS Page Background Images & Fixed Wallpaper Styles',
                'summary' => 'Applying unique background images to specific CMS pages.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/promotions',
                'details' => [
                    ['Wallpaper Image', 'Set a full-screen fixed background wallpaper image for individual pages.', 'Background Image'],
                    ['Background Attachment', 'Applies background-attachment: fixed for smooth parallax scrolling.', 'Parallax Style'],
                ],
                'code_snippet' => "\$page->background_image = 'wallpapers/promo-bg.jpg';",
            ],
            [
                'category_id' => 103, 'sort_order' => 7,
                'title' => 'Embedding YouTube, Vimeo & Shortcodes in Product Video Fields',
                'summary' => 'Pasting video URLs or shortcodes into product video embed fields.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Product Video Embed Field', 'Paste YouTube/Vimeo URLs or [code-embed:ID] shortcodes into Video Embed field.', 'Video Field'],
                    ['Responsive 16:9 Wrapper', 'ContentParserService wraps embeds in a responsive 16:9 aspect ratio container.', 'Aspect Ratio'],
                ],
                'code_snippet' => "\$product->video_embed = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';\n// Or shortcode:\n\$product->video_embed = '[code-embed:4]';",
            ],
            [
                'category_id' => 103, 'sort_order' => 8,
                'title' => 'Product Video Preview & Post-Purchase Video Access',
                'summary' => 'Configuring public video previews versus post-purchase exclusive video streams.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Video Preview (<code class="font-mono">video_preview</code>)', 'Public promotional video preview visible to all catalog visitors.', 'Public Preview'],
                    ['Video Purchase (<code class="font-mono">video_purchase</code>)', 'Exclusive full-length video unlocked only after order completion.', 'Post Purchase'],
                ],
                'code_snippet' => "\$variant->video_preview = 'https://cdn.store.com/previews/course-trailer.mp4';\n\$variant->video_purchase = 'https://cdn.store.com/vault/full-course.mp4';",
            ],
            [
                'category_id' => 103, 'sort_order' => 9,
                'title' => 'Configuring Minimum Header Heights for CMS Pages',
                'summary' => 'Setting custom minimum pixel heights for page top header banner containers.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/hero-landing',
                'details' => [
                    ['Minimum Height Control', 'Set min_header_height in pixels (e.g. 350px, 500px, 650px).', 'Header Sizing'],
                    ['Hero Impact', 'Ensures background banner images and videos maintain strong visual presence.', 'Hero Impact'],
                ],
                'code_snippet' => "\$page->min_header_height = '450px';",
            ],
            [
                'category_id' => 103, 'sort_order' => 10,
                'title' => 'Managing Author Display & Date Meta Badges on Pages',
                'summary' => 'Toggling author name cards and published date meta badges on CMS blog posts.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/blog/article-slug',
                'details' => [
                    ['Show Author (<code class="font-mono">show_author</code>)', 'Toggle displaying author avatar and name card.', 'Author Badge'],
                    ['Show Date (<code class="font-mono">show_date</code>)', 'Toggle displaying publication date and modified timestamp.', 'Date Badge'],
                ],
                'code_snippet' => "\$page->show_author = 1;\n\$page->custom_author = 'Lead Product Specialist';\n\$page->show_date = 1;",
            ],

            // ── CATEGORY 104: Site Theme, Header/Footer & Global Settings (10 articles) ──
            [
                'category_id' => 104, 'sort_order' => 1,
                'title' => 'Store Identity & Multi-Driver Logo Storage System',
                'slug' => 'store-identity-multi-driver-logo-storage',
                'summary' => 'Managing store name, logo storage drivers (Local, S3, CDN, URL, Inline SVG), and branding.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Store Name (<code class="font-mono">site_name</code>)', 'Displayed in header navigation, footers, email subjects, and browser titles.', 'Site Name'],
                    ['Logo Type (<code class="font-mono">logo_type</code>)', 'Select logo driver: local, s3, cdn, url, or inline SVG code.', 'Logo Drivers'],
                    ['Inline SVG Support', 'Paste raw inline SVG code to render scalable crisp vector logos without image requests.', 'Vector SVG'],
                ],
                'code_snippet' => "// AdminSettings.php logo driver save:\nCmsSetting::set('site_name', \$this->site_name);\nCmsSetting::set('logo_type', \$this->logo_type);",
            ],
            [
                'category_id' => 104, 'sort_order' => 2,
                'title' => 'Frontend & Admin Dark Mode Theme Tokens',
                'slug' => 'frontend-admin-dark-mode-theme-tokens',
                'summary' => 'Enabling dark mode styling for storefront visitors and admin control panel.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Frontend Dark Mode', 'Enables dark slate theme styling for storefront visitors.', 'Public Theme'],
                    ['Admin Dark Mode', 'Enables dark slate theme styling for admin workspace control panel.', 'Admin Theme'],
                ],
                'code_snippet' => "CmsSetting::set('frontend_dark_mode', 1);\nCmsSetting::set('admin_dark_mode', 1);",
            ],
            [
                'category_id' => 104, 'sort_order' => 3,
                'title' => 'Brand Color Palette & Corner Radius Controls',
                'slug' => 'brand-color-palette-corner-radius',
                'summary' => 'Managing primary brand colors, hover states, text colors, and corner radius tokens.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Primary Color (<code class="font-mono">theme_primary_color</code>)', 'Primary brand hex color (e.g. #4f46e5).', 'Brand Color'],
                    ['Hover Color (<code class="font-mono">theme_hover_color</code>)', 'Interactive hover state hex color (e.g. #4338ca).', 'Hover Color'],
                    ['Corner Radius (<code class="font-mono">theme_border_radius</code>)', 'Button and card corner radius token (0.75rem, 0.5rem, 9999px).', 'Border Radius'],
                ],
                'code_snippet' => ":root {\n  --theme-primary: #4f46e5;\n  --theme-hover: #4338ca;\n  --theme-radius: 0.75rem;\n}",
            ],
            [
                'category_id' => 104, 'sort_order' => 4,
                'title' => 'Back-to-Top Floating Scroll Button Customization',
                'slug' => 'back-to-top-floating-scroll-button',
                'summary' => 'Customizing background color, hover state, and icon colors for the floating back-to-top button.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Background Color', 'Custom background hex color (backtop_bg_color).', 'Background'],
                    ['Hover Color', 'Custom hover state hex color (backtop_hover_bg_color).', 'Hover State'],
                    ['Icon Color', 'Custom arrow icon hex color (backtop_icon_color).', 'Arrow Icon'],
                ],
                'code_snippet' => "CmsSetting::set('backtop_bg_color', '#4f46e5');\nCmsSetting::set('backtop_hover_bg_color', '#4338ca');\nCmsSetting::set('backtop_icon_color', '#ffffff');",
            ],
            [
                'category_id' => 104, 'sort_order' => 5,
                'title' => 'Shop Catalog View Mode & Filter Pill Styling Tokens',
                'slug' => 'shop-catalog-view-mode-filter-pills',
                'summary' => 'Configuring active and inactive colors for grid/list view buttons, category pills, brand pills, and pagination.',
                'admin_route' => '/admin/settings', 'public_route' => '/shop',
                'details' => [
                    ['View Mode Buttons', 'Grid vs list view mode active/inactive background and text colors.', 'View Mode'],
                    ['Category Filter Pills', 'Category filter pill background, text, border, and hover colors.', 'Category Pills'],
                    ['Brand Filter Pills', 'Brand filter pill background, text, border, and hover colors.', 'Brand Pills'],
                    ['Sitewide Pagination', 'Pagination active, inactive, and hover button colors.', 'Pagination'],
                ],
                'code_snippet' => "CmsSetting::set('shop_view_mode_active_bg', '#4f46e5');\nCmsSetting::set('shop_category_pill_bg', '#f1f5f9');",
            ],
            [
                'category_id' => 104, 'sort_order' => 6,
                'title' => 'Typography Scale Manager & Google Fonts Loader',
                'slug' => 'typography-scale-manager-google-fonts',
                'summary' => 'Loading Google Fonts CSS URLs and tuning font family, size, and color for body, P, H1, H2, H3.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Google Fonts Loader', 'Paste Google Fonts CSS import URL (e.g. Inter, Outfit, Roboto).', 'Font Loader'],
                    ['Element Scale', 'Independent font family, font size, and text color controls for body, P, H1, H2, and H3.', 'Font Scale'],
                ],
                'code_snippet' => "CmsSetting::set('google_fonts_url', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');\nCmsSetting::set('theme_body_font_family', 'Inter, sans-serif');",
            ],
            [
                'category_id' => 104, 'sort_order' => 7,
                'title' => 'Content Cards, Panel Containers & Glassmorphism Shadows',
                'slug' => 'content-cards-glassmorphism-shadows',
                'summary' => 'Setting container background colors, card backgrounds, border colors, and box shadow tokens.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Content Background', 'Container background color (theme_content_bg_color).', 'Container'],
                    ['Card Background', 'Glassmorphism card background color (theme_card_bg_color).', 'Card Background'],
                    ['Box Shadow Token', 'Card box shadow class (shadow-sm, shadow-md, shadow-xl).', 'Shadow Token'],
                ],
                'code_snippet' => "CmsSetting::set('theme_card_bg_color', '#ffffff');\nCmsSetting::set('theme_card_border_color', '#e2e8f0');\nCmsSetting::set('theme_card_shadow', 'shadow-md');",
            ],
            [
                'category_id' => 104, 'sort_order' => 8,
                'title' => 'Google Analytics GA4 & Custom JavaScript Loaders',
                'slug' => 'google-analytics-custom-javascript-loaders',
                'summary' => 'Injecting GA4 measurement IDs and custom header JavaScript tracking scripts.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Google Analytics GA4', 'Paste GA4 measurement ID (G-XXXXXXXXXX) for automatic page view tracking.', 'GA4 Tracking'],
                    ['Custom JS Loader', 'Paste custom tracking scripts, live chat widgets, or pixel code inserted before </head>.', 'Custom JS'],
                ],
                'code_snippet' => "CmsSetting::set('google_analytics_id', 'G-ABC123XYZ8');\nCmsSetting::set('custom_js_loader', '<script>console.log(\"Store loaded\");</script>');",
            ],
            [
                'category_id' => 104, 'sort_order' => 9,
                'title' => 'System Timezone & Runtime Configuration Sync',
                'summary' => 'Setting store timezone with instant runtime configuration application.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Global Timezone', 'Select store timezone (e.g. America/New_York, Europe/London, Asia/Tokyo).', 'Timezone'],
                    ['Runtime Application', 'Updating setting immediately syncs runtime configuration (config(["app.timezone" => $timezone])).', 'Runtime Sync'],
                ],
                'code_snippet' => "CmsSetting::set('timezone', 'America/New_York');\nconfig(['app.timezone' => 'America/New_York']);",
            ],
            [
                'category_id' => 104, 'sort_order' => 10,
                'title' => '5-Column Responsive Starter Footer Configuration',
                'summary' => 'Configuring the 5 dynamic footer columns for navigation, support, company info, contact details, and social icons.',
                'admin_route' => '/admin/header-footer-builder', 'public_route' => '/',
                'details' => [
                    ['Column 1: Navigation', 'Primary site navigation and sitemap links.', 'Footer Col 1'],
                    ['Column 2: Support Care', 'Helpdesk portal links (/contact), order tracking, and shipping policies.', 'Footer Col 2'],
                    ['Column 3: Company & Legal', 'Terms of service, privacy policy, and return policy pages.', 'Footer Col 3'],
                    ['Column 4: Contact Info', 'Store street address, phone number, support email, and operating hours.', 'Footer Col 4'],
                    ['Column 5: Social & Newsletter', 'Social media icon bar ([plugin:social-icons-2026]) and newsletter box (OptinService).', 'Footer Col 5'],
                ],
                'code_snippet' => "CmsSetting::set('footer_col1', '<h3>Shop</h3><a href=\"/shop\">All Products</a>');\nCmsSetting::set('footer_col5', '[plugin:social-icons-2026]');",
            ],

            // ── CATEGORY 105: Product Catalog, Variants & Inventory (10 articles) ──
            [
                'category_id' => 105, 'sort_order' => 1,
                'title' => 'Product Management Overview & CRUD Operations (/admin/ecommerce/products)',
                'slug' => 'product-management-overview-crud',
                'summary' => 'Creating, editing, searching, and managing products in the admin panel.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/shop',
                'details' => [
                    ['Product Catalog List', 'Filter products by category, brand, active status, and search keywords.', 'Catalog Queue'],
                    ['Product Fields', 'Title, short description, long description, SEO meta tags, and layout type.', 'Core Fields'],
                ],
                'code_snippet' => "// AdminProductEdit Livewire component:\nclass AdminProductEdit extends Component {\n    public Product \$product;\n}",
            ],
            [
                'category_id' => 105, 'sort_order' => 2,
                'title' => 'Variant Color Swatch Image Gallery Deduplication',
                'slug' => 'variant-color-swatch-image-deduplication',
                'summary' => 'How Alpine.js filters image gallery thumbnails based on selected variant color swatches without reloading.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Color Swatch Matching', 'Image records store color label tags (alt_label) matching variant color swatches.', 'Color Swatches'],
                    ['Alpine.js Gallery Filter', 'Front-end gallery dynamically filters visible thumbnails without full-page refreshes.', 'Alpine Filter'],
                ],
                'code_snippet' => "<div x-data=\"{ activeColor: 'Navy' }\">\n    <template x-for=\"img in images.filter(i => !i.alt || i.alt === activeColor)\">\n        <img :src=\"img.url\" />\n    </template>\n</div>",
            ],
            [
                'category_id' => 105, 'sort_order' => 3,
                'title' => 'Atomic Product COPY & Cloning Engine',
                'summary' => 'Using the admin Copy feature to duplicate products, variants, fields, and images inside DB transactions.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/admin/ecommerce/products',
                'details' => [
                    ['Atomic Copy', 'Duplicates product record, category links, variant SKUs, fields, and cross-sells inside DB::transaction().', 'DB Transaction'],
                    ['SKU Generation', 'Automatically appends -COPY to duplicated variant SKUs to maintain unique SKU constraints.', 'SKU Deduplication'],
                ],
                'code_snippet' => "DB::transaction(function() {\n    \$newProduct = \$product->replicate();\n    \$newProduct->title = \$product->title . ' (Copy)';\n    \$newProduct->save();\n});",
            ],
            [
                'category_id' => 105, 'sort_order' => 4,
                'title' => 'Bulk Product CSV/Excel Import Engine (PhpSpreadsheet)',
                'summary' => 'Importing products via /admin/ecommerce/import using PhpSpreadsheet with column auto-mapping.',
                'admin_route' => '/admin/ecommerce/import', 'public_route' => '/admin/ecommerce/import',
                'details' => [
                    ['PhpSpreadsheet Reader', 'Supports .csv, .xls, and .xlsx file formats.', 'Spreadsheet Engine'],
                    ['Column Auto-Mapping', 'Matches spreadsheet header columns to product fields automatically.', 'Column Mapping'],
                ],
                'code_snippet' => "\$spreadsheet = \\PhpOffice\\PhpSpreadsheet\\IOFactory::load(\$filePath);\n\$rows = \$spreadsheet->getActiveSheet()->toArray();",
            ],
            [
                'category_id' => 105, 'sort_order' => 5,
                'title' => 'Automatic Category Path String Parsing (Apparel > Outerwear)',
                'slug' => 'automatic-category-path-string-parsing',
                'summary' => 'How category strings like Apparel > Outerwear automatically generate parent/child category records.',
                'admin_route' => '/admin/ecommerce/import', 'public_route' => '/shop',
                'details' => [
                    ['Path Delimiter (>)', 'Parses category strings with > delimiters into parent and child category nodes.', 'Category Parser'],
                    ['Auto Creation', 'Creates missing parent or child category database records automatically during import.', 'Auto Seed'],
                ],
                'code_snippet' => "\$categories = explode('>', 'Apparel > Men > Outerwear');\n// Resolves or creates parent_id relationships automatically",
            ],
            [
                'category_id' => 105, 'sort_order' => 6,
                'title' => 'Custom Donation & Bill-Pay Items (Dynamic Customer Pricing)',
                'summary' => 'Marking products as donation/bill-pay items to allow custom customer price inputs or preset amount menus.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/donation-item',
                'details' => [
                    ['Donation Item Toggle', 'Set is_donation_or_bill_pay = 1 on product edit screen.', 'Donation Toggle'],
                    ['Customer Custom Price', 'Allows customer to enter custom dollar amounts or choose preset donation amounts at checkout.', 'Custom Price'],
                ],
                'code_snippet' => "\$product->is_donation_or_bill_pay = 1;\n\$product->preset_amounts = '10,25,50,100';",
            ],
            [
                'category_id' => 105, 'sort_order' => 7,
                'title' => 'Product Review System (Sitewide Toggle vs Per-Item Allow)',
                'summary' => 'Turning reviews ON/OFF globally in settings or managing reviews per product item.',
                'admin_route' => '/admin/settings', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Global Toggle (<code class="font-mono">enable_reviews</code>)', 'Master switch in /admin/settings to enable/disable reviews storewide.', 'Global Switch'],
                    ['Per-Item Toggle (<code class="font-mono">allow_reviews</code>)', 'Product-level toggle to disable review submissions on specific items.', 'Per-Item Switch'],
                ],
                'code_snippet' => "CmsSetting::set('enable_reviews', 1);\n\$product->allow_reviews = 1;",
            ],
            [
                'category_id' => 105, 'sort_order' => 8,
                'title' => 'Admin Review Approval Queue & Rating Recalculation (/admin/ecommerce/reviews)',
                'summary' => 'Moderating pending reviews and automatically updating 1-5 star averages on catalog cards.',
                'admin_route' => '/admin/ecommerce/reviews', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Moderation Queue', 'Customer reviews are submitted in pending status (approved = 0) to prevent spam.', 'Moderation Queue'],
                    ['Rating Recalculation', 'Approving a review recalculates product average star rating (reviews_rating 1.0-5.0).', 'Star Rating'],
                ],
                'code_snippet' => "// AdminProductReviews.php approve method:\n\$review->approved = 1;\n\$review->save();\n\$product->recalculateRating();",
            ],
            [
                'category_id' => 105, 'sort_order' => 9,
                'title' => 'Multi-Tier Stock Levels (Available, Warehouse, Reserved)',
                'summary' => 'Tracking retail available stock, warehouse back-stock, use warehouse toggles, and reserved order stock.',
                'admin_route' => '/admin/ecommerce/inventory', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Available Stock (<code class="font-mono">quantity_available</code>)', 'Active retail stock available for instant checkout.', 'Retail Stock'],
                    ['Warehouse Stock (<code class="font-mono">warehouse_stock_level</code>)', 'Deep back-stock inventory stored in fulfillment centers.', 'Fulfillment Stock'],
                    ['Use Warehouse Toggle', 'Specifies whether storefront draws from warehouse back-stock when retail stock drops to 0.', 'Auto Draw'],
                    ['Reserved Stock (<code class="font-mono">reserved_stock</code>)', 'Stock held by pending customer orders awaiting payment/shipment.', 'Pending Orders'],
                ],
                'code_snippet' => "\$inventory->quantity_available = 25;\n\$inventory->warehouse_stock_level = 100;\n\$inventory->use_warehouse_stock = 1;",
            ],
            [
                'category_id' => 105, 'sort_order' => 10,
                'title' => 'Multi-Warehouse Fulfillment Locations & Location Mapping',
                'summary' => 'Managing physical warehouse records in /admin/shipping and linking variants to specific fulfillment centers.',
                'admin_route' => '/admin/shipping', 'public_route' => '/admin/shipping',
                'details' => [
                    ['Warehouse Locations', 'Manage warehouse records (name, code, street address, city, state, country, ShipStation ID).', 'Warehouse DB'],
                    ['Location Mapping', 'Link variant inventory rows (location_id) to specific physical warehouse fulfillment centers.', 'Variant Mapping'],
                ],
                'code_snippet' => "DB::table('shipping_warehouse_locations')->insert([\n    'name' => 'US East Fulfillment Center',\n    'code' => 'US-EAST-1',\n    'country_code' => 'US',\n]);",
            ],

            // ── CATEGORY 106: Pricing, Taxes, Shipping & Discounts (10 articles) ──
            [
                'category_id' => 106, 'sort_order' => 1,
                'title' => 'US Sales Tax Exclusive Calculation Model',
                'summary' => 'Configuring origin/destination US sales tax rates per state in shipping_states table.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Exclusive Tax Model', 'Catalog prices do NOT include tax. Tax is calculated dynamically on subtotal at checkout.', 'US Exclusive'],
                    ['Per-State Rates', 'Configure state sales tax percentages (sales_tax_rate, e.g. CA 7.25%, NY 8.00%, TX 6.25%).', 'State Rates'],
                ],
                'code_snippet' => "\$tax = TaxService::calculateUsSalesTax(\$subtotal, \$destinationState);",
            ],
            [
                'category_id' => 106, 'sort_order' => 2,
                'title' => 'Canadian GST / PST / HST Tax Structure & Provincial Rules',
                'summary' => 'Configuring federal 5% GST combined with provincial PST or harmonized HST rates across Canadian provinces.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Federal GST (5%)', 'Federal 5% Goods & Services Tax applied across Canada.', 'GST Rate'],
                    ['Provincial PST / RST', 'Charged in non-participating provinces (e.g. BC 7% PST, SK 6% PST, MB 7% RST).', 'PST Rate'],
                    ['Harmonized HST', 'Single combined rate in participating provinces (e.g. ON 13% HST, NS 15% HST).', 'HST Rate'],
                ],
                'code_snippet' => "\$tax = TaxService::calculateCanadianTax(\$subtotal, \$provinceCode);",
            ],
            [
                'category_id' => 106, 'sort_order' => 3,
                'title' => 'International VAT & Inclusive Catalog Pricing Model',
                'summary' => 'How domestic VAT is included in catalog pricing for European/UK buyers.',
                'admin_route' => '/admin/shipping', 'public_route' => '/shop',
                'details' => [
                    ['Inclusive Tax Model', 'Store prices listed in catalog include domestic VAT (e.g. £120.00 includes £20 VAT).', 'Inclusive VAT'],
                    ['Merchant Country', 'Activated for merchants outside US/CA (e.g. UK, EU, Australia).', 'VAT Zone'],
                ],
                'code_snippet' => "CmsSetting::set('vat_inclusive_pricing', 1);",
            ],
            [
                'category_id' => 106, 'sort_order' => 4,
                'title' => 'Dynamic Cross-Border Tax Stripping Engine for Export Orders',
                'summary' => 'Automatically stripping domestic VAT for international buyers during checkout based on delivery address.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Export Tax Stripping', 'When an international buyer enters a delivery address outside merchant VAT zone, domestic VAT is stripped out.', 'Tax Stripping'],
                    ['Tax-Free Export Price', 'Calculates net price dynamically (e.g. £120.00 item drops to £100.00 tax-free export price).', 'Net Pricing'],
                ],
                'code_snippet' => "\$netPrice = TaxService::stripDomesticVat(\$priceWithVat, \$vatRate);",
            ],
            [
                'category_id' => 106, 'sort_order' => 5,
                'title' => 'Quantity Discount Tier /each Price Badges in Buy Box',
                'summary' => 'How /each badges dynamically display unit discount prices in buy boxes as quantity selector changes.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Quantity Tier Badge', 'When quantity discount tier is active (e.g. 5+ items @ 10% off), buy box displays /each unit badge.', 'Unit Badge'],
                    ['Live Recalculation', 'Alpine.js updates displayed unit price live as customer adjusts buy box quantity input.', 'Alpine Sync'],
                ],
                'code_snippet' => "<span class=\"text-xs font-bold text-emerald-600\">\$18.00 /each</span>",
            ],
            [
                'category_id' => 106, 'sort_order' => 6,
                'title' => 'Flat-Rate Shipping Methods & Weight/Subtotal Brackets',
                'summary' => 'Configuring domestic and international flat-rate shipping rules by weight or order subtotal.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Flat-Rate Rules', 'Configure weight or subtotal bracketed shipping rules in shipping_flat_rates table.', 'Flat Rates'],
                    ['Zone Rules', 'Set independent shipping rules for domestic versus international delivery zones.', 'Delivery Zones'],
                ],
                'code_snippet' => "DB::table('shipping_flat_rates')->insert([\n    'name' => 'Standard Ground (3-5 Days)',\n    'rate' => 9.99,\n    'min_subtotal' => 0.00,\n    'max_subtotal' => 99.99,\n]);",
            ],
            [
                'category_id' => 106, 'sort_order' => 7,
                'title' => 'Menu Dropdown Shipping Method Overrides',
                'summary' => 'Configuring checkout shipping display dropdown overrides for flat rates or live carrier rates.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Dropdown Overrides', 'Menu select configuration forcing flat rates, live carrier rates, or custom shipping dropdown menus.', 'Checkout Menu'],
                    ['Customer Select', 'Presents clean dropdown select interface for customers during checkout step.', 'Customer UI'],
                ],
                'code_snippet' => "CmsSetting::set('shipping_menu_override', 'dropdown');",
            ],
            [
                'category_id' => 106, 'sort_order' => 8,
                'title' => 'Real-Time Carrier API Integration (FedEx, UPS, USPS)',
                'summary' => 'Setting up live carrier API drivers and local store pickup options.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Carrier API Drivers', 'Connect live rate quotes from FedEx, UPS, and USPS API endpoints.', 'Carrier Drivers'],
                    ['Origin Zipcode', 'Calculates live quotes based on warehouse origin zipcode and customer delivery address.', 'Origin Zip'],
                ],
                'code_snippet' => "\$rates = ShippingService::fetchLiveCarrierQuotes(\$cart, \$destinationAddress);",
            ],
            [
                'category_id' => 106, 'sort_order' => 9,
                'title' => 'Included Mock Real-Time Shipping Rate Provider',
                'summary' => 'Using the built-in mock shipping rate service for testing live carrier quotes without API keys.',
                'admin_route' => '/admin/shipping', 'public_route' => '/checkout',
                'details' => [
                    ['Mock Rate Driver', 'MockShippingRateProvider / MockShippingService simulates live carrier quotes.', 'Mock Provider'],
                    ['Zero API Keys', 'Generates realistic shipping quotes (Ground $9.99, Express $18.50, Overnight $32.00) without API credentials.', 'Testing Driver'],
                ],
                'code_snippet' => "\$rates = MockShippingRateProvider::getQuotes(\$cartWeight, \$cartSubtotal);",
            ],
            [
                'category_id' => 106, 'sort_order' => 10,
                'title' => '3-Phase Order Calculation Pipeline (Item -> BOGO -> Order Level)',
                'summary' => 'Deterministic sequence executing item-level discounts, BOGO logic, and order-level discounts.',
                'admin_route' => '/admin/ecommerce/discounts', 'public_route' => '/cart',
                'details' => [
                    ['Phase 1: Item Discounts', 'Applies item-specific, brand, and category discounts to individual line items.', 'Phase 1'],
                    ['Phase 2: BOGO Logic', 'Evaluates Buy X Get Y promotional conditions and applies target item discounts.', 'Phase 2'],
                    ['Phase 3: Order Discounts', 'Applies coupon codes, preferred customer, and order subtotal value discounts.', 'Phase 3'],
                ],
                'code_snippet' => "\$cartSubtotal = DiscountService::applyThreePhasePipeline(\$cartItems, \$couponCode, \$user);",
            ],

            // ── CATEGORY 107: Payment Gateways, Webhooks & Fallbacks (10 articles) ──
            [
                'category_id' => 107, 'sort_order' => 1,
                'title' => 'Payment Gateway System Architecture & Processor IDs',
                'summary' => 'Overview of payment gateway integration, sandbox/live modes, and processor ID mapping.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Processor ID 0', 'Test Payment Gateway (Simulated success/failure).', 'Test Gateway'],
                    ['Processor ID 1', 'Stripe PaymentIntents API SDK.', 'Stripe API'],
                    ['Processor ID 2', 'Paddle Billing API v2 SDK.', 'Paddle API'],
                    ['Processor ID 3', 'PayPal Commerce Platform SDK.', 'PayPal API'],
                    ['Processor IDs 100+', 'Custom third-party payment plugins implementing PaymentProcessorInterface.', 'Custom Plugins'],
                ],
                'code_snippet' => "DB::table('order_processors')->get();",
            ],
            [
                'category_id' => 107, 'sort_order' => 2,
                'title' => 'Test Payment Gateway Mode (Processor ID 0)',
                'summary' => 'Simulating instant successful payments for development and user acceptance testing.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Simulated Checkout', 'Allows completing test orders instantly without real credit card charges.', 'Test Mode'],
                    ['Status Testing', 'Simulates successful authorizations, pending checks, or intentional card decline errors.', 'Error Testing'],
                ],
                'code_snippet' => "CmsSetting::set('primary_processor', 0);",
            ],
            [
                'category_id' => 107, 'sort_order' => 3,
                'title' => 'Stripe PaymentIntents & Native Webhook Configuration (Processor ID 1)',
                'summary' => 'Configuring Stripe publishable keys, secret keys, and webhook secret signatures.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Stripe API Keys', 'Configure Stripe Publishable Key (pk_...) and Secret Key (sk_...).', 'Stripe Keys'],
                    ['Webhook Secret', 'Set Webhook Secret Signature (whsec_...) for payment_intent.succeeded verification.', 'Webhook Signature'],
                ],
                'code_snippet' => "STRIPE_KEY=pk_test_51... \nSTRIPE_SECRET=sk_test_51...\nSTRIPE_WEBHOOK_SECRET=whsec_...",
            ],
            [
                'category_id' => 107, 'sort_order' => 4,
                'title' => 'Paddle Billing API v2 SDK & Webhook Setup (Processor ID 2)',
                'summary' => 'Configuring Paddle client tokens, API keys, vendor IDs, and webhook signatures.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Paddle Billing v2', 'Connect Paddle Merchant Account with Client Token and API Key.', 'Paddle Keys'],
                    ['Transaction Webhooks', 'Receives transaction.completed webhooks for automatic order confirmation.', 'Paddle Webhook'],
                ],
                'code_snippet' => "PADDLE_CLIENT_TOKEN=test_...\nPADDLE_API_KEY=pdl_...\nPADDLE_WEBHOOK_SECRET=pdl_whsec_...",
            ],
            [
                'category_id' => 107, 'sort_order' => 5,
                'title' => 'PayPal Commerce Platform Setup (Processor ID 3)',
                'summary' => 'Setting up PayPal Client ID, Secret, and Sandbox/Live API environment modes.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['PayPal Developer App', 'Create a REST API App in PayPal Developer Dashboard.', 'PayPal App'],
                    ['Environment Modes', 'Toggle between Sandbox (testing) and Live production environments.', 'Environment'],
                ],
                'code_snippet' => "PAYPAL_CLIENT_ID=A...\nPAYPAL_CLIENT_SECRET=E...\nPAYPAL_MODE=sandbox",
            ],
            [
                'category_id' => 107, 'sort_order' => 6,
                'title' => 'Primary, Secondary & Tertiary Gateway Fallback Routing',
                'summary' => 'Configuring payment gateway cascading fallback and randomized processor routing.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Cascading Fallback', 'Automatically routes failed charges to secondary or tertiary processors.', 'Fallback Routing'],
                    ['Resilience', 'Ensures high conversion rates during single-gateway service outages.', 'High Availability'],
                ],
                'code_snippet' => "DB::table('order_checkout_options')->update([\n    'primary_processor' => 1,\n    'secondary_processor' => 3,\n]);",
            ],
            [
                'category_id' => 107, 'sort_order' => 7,
                'title' => 'Randomized Gateway Rotation Engine',
                'summary' => 'Randomly distributing incoming checkout transactions across active processors for load balancing.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/checkout',
                'details' => [
                    ['Randomize Processor', 'Set randomize_processor = 1 in checkout options.', 'Random Rotation'],
                    ['Load Distribution', 'Evenly spreads transaction volume across active merchant accounts.', 'Load Balance'],
                ],
                'code_snippet' => "DB::table('order_checkout_options')->update(['randomize_processor' => 1]);",
            ],
            [
                'category_id' => 107, 'sort_order' => 8,
                'title' => 'Incoming Gateway Webhook Endpoints & Payment Verification',
                'summary' => 'Webhook URL routes for asynchronous payment confirmation and signature validation.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/api/webhooks/stripe',
                'details' => [
                    ['Stripe Webhook', '/api/webhooks/stripe (verifies Stripe-Signature header).', 'Stripe Route'],
                    ['Paddle Webhook', '/api/webhooks/paddle (verifies Paddle-Signature header).', 'Paddle Route'],
                    ['PayPal Webhook', '/api/webhooks/paypal (verifies transmission headers).', 'PayPal Route'],
                ],
                'code_snippet' => "Route::post('/api/webhooks/stripe', [WebhookController::class, 'handleStripe']);\nRoute::post('/api/webhooks/paddle', [WebhookController::class, 'handlePaddle']);",
            ],
            [
                'category_id' => 107, 'sort_order' => 9,
                'title' => 'Auto-Detected Extension Class Overrides',
                'summary' => 'Extending built-in gateways by dropping extension classes into payment-processors/ directory.',
                'admin_route' => '/admin/payment-processors', 'public_route' => '/admin/payment-processors',
                'details' => [
                    ['Directory Auto-Scan', 'Scans payment-processors/ directory for custom gateway class files.', 'Auto Scan'],
                    ['Zero Code Edits', 'Instantly registers new payment processors without modifying core framework code.', 'Drop-in Integration'],
                ],
                'code_snippet' => "// File: payment-processors/CustomGatewayProcessor.php\nclass CustomGatewayProcessor implements PaymentProcessorInterface { ... }",
            ],
            [
                'category_id' => 107, 'sort_order' => 10,
                'title' => 'Building Custom Payment Gateway Plugins (PaymentProcessorInterface)',
                'summary' => '9-step blueprint for building third-party payment plugins implementing PaymentProcessorInterface.',
                'admin_route' => '/admin/plugins', 'public_route' => '/checkout',
                'details' => [
                    ['Interface Contract', 'Implement PaymentProcessorInterface (processPayment, capturePayment, refundPayment).', 'PHP Contract'],
                    ['Plugin Manifest', 'Create plugin.json manifest file declaring processor ID and settings schema.', 'Manifest JSON'],
                ],
                'code_snippet' => "interface PaymentProcessorInterface {\n    public function processPayment(array \$orderData): PaymentResponse;\n    public function refundPayment(string \$transactionId, float \$amount): RefundResponse;\n}",
            ],

            // ── CATEGORY 108: Support Ticket Manager & User Roles (10 articles) ──
            [
                'category_id' => 108, 'sort_order' => 1,
                'title' => 'Customer Support Portal & Ticket Submission Workflow (/contact)',
                'summary' => 'How retail, wholesale, and guest customers submit support tickets via /contact or /tickets/create.',
                'admin_route' => '/admin/tickets', 'public_route' => '/contact',
                'details' => [
                    ['Ticket Form', 'Customers submit support tickets with category, subject, description, and file attachments.', 'Public Form'],
                    ['Guest Submissions', 'Unauthenticated guests enter email address and automatically receive ticket tracking token.', 'Guest Access'],
                ],
                'code_snippet' => "Route::get('/contact', ContactForm::class)->name('contact');",
            ],
            [
                'category_id' => 108, 'sort_order' => 2,
                'title' => 'Staff Support Queue Dashboard & Operations (/admin/tickets)',
                'summary' => 'Managing, filtering, and assigning tickets in /admin/tickets with status updates.',
                'admin_route' => '/admin/tickets', 'public_route' => '/admin/tickets',
                'details' => [
                    ['Queue Filtering', 'Filter tickets by status: Open, In Process, Assigned, Resolved, Closed.', 'Ticket Queue'],
                    ['Staff Assignment', 'Assign tickets to specific support agents or admin staff members.', 'Agent Assignment'],
                ],
                'code_snippet' => "// AdminTickets Livewire Component:\nclass AdminTickets extends Component {\n    public string \$statusFilter = 'open';\n}",
            ],
            [
                'category_id' => 108, 'sort_order' => 3,
                'title' => 'Inbound Email Reply Parser Engine (TicketReplyParser)',
                'summary' => 'How TicketReplyParser converts customer email replies into threaded ticket response logs.',
                'admin_route' => '/admin/tickets', 'public_route' => '/admin/tickets',
                'details' => [
                    ['zbateson/mail-mime-parser', 'Parses incoming MIME email bodies from mail server pipe.', 'MIME Parser'],
                    ['Reply Delimiter', 'Strips email quoted history above delimiter line.', 'Text Extractor'],
                    ['Thread Attachment', 'Appends parsed reply text as a new TicketReply database record.', 'Thread Sync'],
                ],
                'code_snippet' => "\$reply = TicketReplyParser::parseInboundEmail(\$rawEmailStream);",
            ],
            [
                'category_id' => 108, 'sort_order' => 4,
                'title' => 'Secure Ticket File Attachments (TicketAttachmentService)',
                'summary' => 'Uploading and inspecting ticket attachment files securely managed by TicketAttachmentService.',
                'admin_route' => '/admin/tickets', 'public_route' => '/account/tickets',
                'details' => [
                    ['Secure Storage', 'Ticket attachments are stored in private disk storage (storage/app/private/tickets/).', 'Private Disk'],
                    ['Access Control', 'Only ticket owner or authorized staff roles can stream attachment downloads.', 'Auth Guard'],
                ],
                'code_snippet' => "TicketAttachmentService::storeAttachment(\$ticket, \$uploadedFile);",
            ],
            [
                'category_id' => 108, 'sort_order' => 5,
                'title' => 'Knowledge Base Article Cross-Linking in Staff Ticket Replies',
                'summary' => 'Searching and embedding KB article links directly into support ticket staff replies.',
                'admin_route' => '/admin/tickets', 'public_route' => '/admin/tickets',
                'details' => [
                    ['KB Article Search', 'Staff can search Knowledge Base articles directly inside ticket reply editor.', 'KB Lookup'],
                    ['1-Click Link Embed', 'Inserts formatted Markdown link [Article Title](/kb/article-slug) into reply body.', 'Shortcode Link'],
                ],
                'code_snippet' => "Please refer to our Knowledge Base guide: [Payment Gateways](/kb/payment-gateway-system-architecture)",
            ],
            [
                'category_id' => 108, 'sort_order' => 6,
                'title' => 'User Role Hierarchy (User, Wholesale, Admin, Order Processor, Ticket Manager)',
                'summary' => 'Understanding permissions across all 5 user role levels in the system.',
                'admin_route' => '/admin/users', 'public_route' => '/admin/users',
                'details' => [
                    ['Role 1: User', 'Regular retail customer. Can submit and view own support tickets.', 'User Role'],
                    ['Role 2: Wholesale', 'Wholesale buyer profile. Receives wholesale tier catalog pricing.', 'Wholesale Role'],
                    ['Role 3: Admin', 'Full system administrator access across all features, settings, and users.', 'Admin Role'],
                    ['Role 4: Order Processor', 'Staff access restricted to viewing and updating shop orders.', 'Order Processor'],
                    ['Role 5: Ticket Manager', 'Staff access restricted to managing support tickets.', 'Ticket Manager'],
                ],
                'code_snippet' => "enum UserRole: int {\n    case User = 1;\n    case Wholesale = 2;\n    case Admin = 3;\n    case OrderProcessor = 4;\n    case TicketManager = 5;\n}",
            ],
            [
                'category_id' => 108, 'sort_order' => 7,
                'title' => 'Customer Account Ticket Portal (/account/tickets)',
                'summary' => 'How logged-in customers track ticket history, reply threads, and attachment downloads.',
                'admin_route' => '/admin/tickets', 'public_route' => '/account/tickets',
                'details' => [
                    ['Customer Dashboard', 'Logged-in customers view history of all open and closed support tickets.', 'Customer History'],
                    ['Interactive Thread', 'Post follow-up replies and upload additional attachments directly from account dashboard.', 'Interactive Portal'],
                ],
                'code_snippet' => "Route::get('/account/tickets', CustomerTickets::class)->middleware('auth');",
            ],
            [
                'category_id' => 108, 'sort_order' => 8,
                'title' => 'Automated Support Ticket Email Notifications',
                'summary' => 'Automated customer emails for ticket submission, staff replies, and status changes.',
                'admin_route' => '/admin/email-templates', 'public_route' => '/contact',
                'details' => [
                    ['Ticket Submitted Email', 'Sent to customer upon initial ticket creation with tracking reference ID.', 'Template 8'],
                    ['Ticket Reply Email', 'Sent to customer when support agent posts a reply.', 'Template 9'],
                    ['Ticket Status Email', 'Sent to customer when ticket status is updated (Resolved, Closed).', 'Template 10'],
                ],
                'code_snippet' => "// Email Template Type IDs: 8 (Submitted), 9 (Reply), 10 (Status)",
            ],
            [
                'category_id' => 108, 'sort_order' => 9,
                'title' => 'Staff Internal Notes on Support Tickets',
                'summary' => 'Adding private internal notes visible only to staff members on ticket threads.',
                'admin_route' => '/admin/tickets', 'public_route' => '/admin/tickets',
                'details' => [
                    ['Internal Note Toggle', 'Toggle "Is Internal Note" when posting a reply in admin ticket workspace.', 'Private Note'],
                    ['Staff Only Visibility', 'Internal notes are highlighted in yellow and hidden from customer portal views.', 'Staff Only'],
                ],
                'code_snippet' => "\$reply->is_internal = 1;\n\$reply->save();",
            ],
            [
                'category_id' => 108, 'sort_order' => 10,
                'title' => 'Ticket Auto-Closure Policies & Resolution Workflows',
                'summary' => 'Configuring ticket auto-closure rules for inactive resolved support tickets.',
                'admin_route' => '/admin/tickets', 'public_route' => '/admin/tickets',
                'details' => [
                    ['Auto Closure', 'Automatically marks resolved tickets as Closed after 7 days of inactivity.', 'Auto Close'],
                    ['Reopening Policy', 'Customers replying to a Closed ticket automatically reopen it to In Process status.', 'Reopen Policy'],
                ],
                'code_snippet' => "// Cron job command:\nphp artisan tickets:close-inactive",
            ],

            // ── CATEGORY 109: Digital Downloads & Asset Manager (10 articles) ──
            [
                'category_id' => 109, 'sort_order' => 1,
                'title' => 'Overview of Dual Digital Download Engines',
                'summary' => 'Distinguishing Engine 1 (Order-Based Product Downloads) from Engine 2 (CMS Asset Downloads Manager).',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/shop',
                'details' => [
                    ['Engine 1: Product Downloads', 'Attached to product variants for post-purchase delivery.', 'Order Downloads'],
                    ['Engine 2: CMS Asset Downloads', 'Uploaded in /admin/cms-downloads and embedded via [download:ID].', 'CMS Assets'],
                ],
                'code_snippet' => "// Engine 1:\n\$variant->download_item = 1;\n// Engine 2:\n[download:1]",
            ],
            [
                'category_id' => 109, 'sort_order' => 2,
                'title' => 'Engine 1 — Order-Based Digital Product Asset Downloads',
                'summary' => 'Attaching digital file assets to product variants for post-purchase customer downloads.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/account/orders',
                'details' => [
                    ['Variant Download Settings', 'Set download_item = 1, upload asset file or enter CDN URL on variant edit.', 'Variant File'],
                    ['Post Checkout Delivery', 'Customer receives secure download button on order confirmation page and account dashboard.', 'Secure Delivery'],
                ],
                'code_snippet' => "\$variant->download_item = 1;\n\$variant->download_location = 'products/ebook.pdf';",
            ],
            [
                'category_id' => 109, 'sort_order' => 3,
                'title' => 'Max Download Limits & Access Link Expiration Rules',
                'summary' => 'Setting maximum allowed download counts and link expiration windows per variant.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/account/orders',
                'details' => [
                    ['Max Downloads Allowed', 'Set downloads_max_allowed (e.g. 5 downloads limit per purchase).', 'Max Downloads'],
                    ['Expiration Window', 'Set download_expiration date or days after purchase limit.', 'Expiry Date'],
                ],
                'code_snippet' => "\$variant->downloads_max_allowed = 10;\n\$variant->download_expiration = now()->addDays(30);",
            ],
            [
                'category_id' => 109, 'sort_order' => 4,
                'title' => 'Resending Digital Download Reminder Emails from Order Details',
                'summary' => 'Resending digital download emails to customers from the Order Details screen.',
                'admin_route' => '/admin/ecommerce/orders/{id}', 'public_route' => '/admin/ecommerce/orders/{id}',
                'details' => [
                    ['Send Download Reminder', 'Click "Send Download Link Reminder" button in Admin Order Details.', 'Admin Action'],
                    ['Magic Token Generation', 'Re-dispatches email_templates (type_id = 3) with fresh 90-day UUID magic download token.', 'Magic UUID'],
                ],
                'code_snippet' => "// AdminOrderDetails.php:\n\$this->sendDownloadReminderEmail();",
            ],
            [
                'category_id' => 109, 'sort_order' => 5,
                'title' => 'Engine 2 — CMS Asset Downloads Manager (/admin/cms-downloads)',
                'summary' => 'Uploading brochures, spec sheets, and manuals in /admin/cms-downloads for shortcode embedding.',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/about-us',
                'details' => [
                    ['CMS Download Asset Record', 'Upload file asset, set title, description, category, and force download toggle.', 'Asset Record'],
                    ['Download Counter', 'Tracks download_count total for each uploaded CMS download file.', 'Download Counter'],
                ],
                'code_snippet' => "Route::get('/admin/cms-downloads', AdminCmsDownloads::class);",
            ],
            [
                'category_id' => 109, 'sort_order' => 6,
                'title' => 'Embedding Asset Download Shortcodes ([download:ID])',
                'summary' => 'Inserting [download:ID] shortcodes to render automatic file format badges, sizes, and tracked download buttons.',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/page-slug',
                'details' => [
                    ['Shortcode Tag', 'Insert [download:ID] anywhere inside CMS page content or product descriptions.', 'Shortcode Tag'],
                    ['File Format Badges', 'Renders file type badge (PDF, DOCX, ZIP, MP4), formatted file size, and download button.', 'UI Badge'],
                ],
                'code_snippet' => "[download:1]\n[download:2 layout=card]",
            ],
            [
                'category_id' => 109, 'sort_order' => 7,
                'title' => 'CMS File Icon Pack Selection (Vivid, Classic, Square)',
                'summary' => 'Selecting download file format icon designs in /admin/settings.',
                'admin_route' => '/admin/settings', 'public_route' => '/page-slug',
                'details' => [
                    ['File Icon Pack', 'Select file_icon_pack setting (vivid, classic, square).', 'Icon Pack'],
                    ['Visual Styling', 'Customizes badge icons rendered next to file shortcodes across storefront.', 'Badge Styling'],
                ],
                'code_snippet' => "CmsSetting::set('file_icon_pack', 'vivid'); // vivid, classic, square",
            ],
            [
                'category_id' => 109, 'sort_order' => 8,
                'title' => 'Storage Drivers: Local Disk vs AWS S3 vs External CDN',
                'summary' => 'Configuring local disk, Amazon S3 bucket storage, or external CDN URL overrides for downloads.',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/download/asset-uuid',
                'details' => [
                    ['Driver 1: Local Storage', 'Files stored on local web server disk (storage/app/downloads/).', 'Local Disk'],
                    ['Driver 2: AWS S3 Storage', 'Files stored in Amazon S3 bucket (download_s3 = 1).', 'AWS S3'],
                    ['Driver 3: External CDN', 'Files served directly from external CDN URL (download_cdn_url).', 'Direct CDN'],
                ],
                'code_snippet' => "\$download->storage_type = 's3';\n\$download->s3_bucket = 'my-assets-bucket';",
            ],
            [
                'category_id' => 109, 'sort_order' => 9,
                'title' => 'Video.js Inline Streaming Media Player Integration',
                'summary' => 'Rendering inline Video.js video and audio players for media assets.',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/page-slug',
                'details' => [
                    ['Force Download OFF', 'When Force Download is toggled OFF, video/audio files automatically render inline Video.js player.', 'Inline Player'],
                    ['HTML5 Streaming', 'Streams MP4, WebM, MP3, and WAV media files directly in browser.', 'Streaming'],
                ],
                'code_snippet' => "<video id=\"my-player\" class=\"video-js vjs-default-skin\" controls preload=\"auto\">\n    <source src=\"{{ \$streamUrl }}\" type=\"video/mp4\">\n</video>",
            ],
            [
                'category_id' => 109, 'sort_order' => 10,
                'title' => 'Secure File Download Controller & Anti-Leech Access Guards',
                'summary' => 'How DownloadController validates active status, expiry, and download limits before streaming bytes.',
                'admin_route' => '/admin/cms-downloads', 'public_route' => '/download/asset-uuid',
                'details' => [
                    ['DownloadController', 'Serves file downloads through authenticated controller routes.', 'Download Route'],
                    ['Anti-Leech Protection', 'Hides actual filesystem paths and prevents direct hotlinking.', 'Hotlink Guard'],
                ],
                'code_snippet' => "Route::get('/download/{uuid}', [DownloadController::class, 'streamFile']);",
            ],

            // ── CATEGORY 110: CMS Embeds, Form Builder & Drawers (10 articles) ──
            [
                'category_id' => 110, 'sort_order' => 1,
                'title' => 'CMS Code & Video Embeds Library (/admin/cms-embeds)',
                'summary' => 'Creating reusable HTML/JS code snippets in /admin/cms-embeds for YouTube videos, charts, and widgets.',
                'admin_route' => '/admin/cms-embeds', 'public_route' => '/page-slug',
                'details' => [
                    ['Code Embed Library', 'Save HTML, iframe, or JS snippets centrally under Admin → CMS → Code Embeds.', 'Central Library'],
                    ['Shortcode Insertion', 'Insert anywhere using shortcode [code-embed:ID].', 'Shortcode Tag'],
                ],
                'code_snippet' => "[code-embed:1]\n[code-embed:2]",
            ],
            [
                'category_id' => 110, 'sort_order' => 2,
                'title' => 'Preventing TinyMCE WYSIWYG Code Stripping with Shortcodes',
                'summary' => 'How [code-embed:ID] shortcodes prevent TinyMCE editor from stripping or corrupting raw JavaScript.',
                'admin_route' => '/admin/cms-embeds', 'public_route' => '/page-slug',
                'details' => [
                    ['Shortcode Placeholder', 'TinyMCE editor only stores clean shortcode string [code-embed:ID].', 'WYSIWYG Safety'],
                    ['Runtime Resolution', 'ContentParserService replaces shortcode tag with raw HTML snippet during HTML rendering.', 'Runtime Render'],
                ],
                'code_snippet' => "\$content = ContentParserService::parseEmbedShortcodes(\$rawHtml);",
            ],
            [
                'category_id' => 110, 'sort_order' => 3,
                'title' => 'Visual Form Builder & Custom Field Creation (/admin/cms/forms)',
                'summary' => 'Designing custom inquiry forms with drag-and-drop fields in /admin/cms/forms.',
                'admin_route' => '/admin/cms/forms', 'public_route' => '/contact',
                'details' => [
                    ['Form Builder UI', 'Build custom inquiry forms under Admin → CMS → Forms.', 'Visual Builder'],
                    ['Field Types', 'Add text, textarea, select, radio, checkbox, and file upload fields.', 'Field Matrix'],
                    ['Form Shortcode', 'Embed forms into any CMS page using shortcode [cms-form:ID].', 'Form Shortcode'],
                ],
                'code_snippet' => "[cms-form:1]",
            ],
            [
                'category_id' => 110, 'sort_order' => 4,
                'title' => 'reCAPTCHA v3 & Form Rate Limiting Security',
                'summary' => 'Integrating invisible reCAPTCHA v3 verification and rate limiting on form submissions.',
                'admin_route' => '/admin/cms/forms', 'public_route' => '/contact',
                'details' => [
                    ['reCAPTCHA v3', 'Invisible Google reCAPTCHA v3 bot verification on form submit.', 'Bot Guard'],
                    ['IP Rate Limiting', 'Prevents submission spam using Laravel ThrottleRequests middleware.', 'Rate Limit'],
                ],
                'code_snippet' => "CmsSetting::set('recaptcha_site_key', '6L... ');\nCmsSetting::set('recaptcha_secret_key', '6L...');",
            ],
            [
                'category_id' => 110, 'sort_order' => 5,
                'title' => 'Form Submission Email Alerts & Opt-in Marketing Lists',
                'summary' => 'Configuring email alerts and automatically adding submitters to marketing opt-in lists.',
                'admin_route' => '/admin/cms/forms', 'public_route' => '/contact',
                'details' => [
                    ['Staff Email Alerts', 'Automatically emails form submission data to designated admin email addresses.', 'Staff Notification'],
                    ['OptinService Sync', 'Adds submitter email address to marketing subscriber list when opt-in checkbox is checked.', 'Optin Sync'],
                ],
                'code_snippet' => "OptinService::subscribe(\$email, \$firstName, \$lastName);",
            ],
            [
                'category_id' => 110, 'sort_order' => 6,
                'title' => 'Relational Top Navigation Menu Builder (/admin/nav-menus)',
                'summary' => 'Creating multi-level relational dropdown navigation menus with hover effects.',
                'admin_route' => '/admin/nav-menus', 'public_route' => '/',
                'details' => [
                    ['Nav Menu Builder', 'Drag-and-drop link builder under Admin → Navigation Builder.', 'Menu Builder'],
                    ['Nested Dropdowns', 'Supports 3 levels of nested dropdown links with hover animations.', 'Nested Menus'],
                ],
                'code_snippet' => "Route::get('/admin/nav-menus', AdminNavMenus::class);",
            ],
            [
                'category_id' => 110, 'sort_order' => 7,
                'title' => 'Structured List Menus ([list-menu:ID])',
                'summary' => 'Building structured link directories in /admin/cms-list-menus embedded via [list-menu:ID].',
                'admin_route' => '/admin/cms-list-menus', 'public_route' => '/sitemap',
                'details' => [
                    ['List Menu Directory', 'Create structured link lists in /admin/cms-list-menus.', 'Link List'],
                    ['Shortcode Tag', 'Embed anywhere using shortcode [list-menu:ID].', 'Shortcode Tag'],
                ],
                'code_snippet' => "[list-menu:1]",
            ],
            [
                'category_id' => 110, 'sort_order' => 8,
                'title' => 'Editor Slide-Out Drawers Architecture Overview',
                'summary' => 'Docked floating sidebar tab container providing quick access to editor tools on page/product screens.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/admin/cms-pages',
                'details' => [
                    ['Floating Tab Bar', '4 docked tabs on right edge of CMS Page and Product edit screens.', 'Editor Drawer'],
                    ['Quick Inserter', 'Allows 1-click insertion of HTML widgets, plugins, shortcodes, and internal links into editor.', 'Quick Insert'],
                ],
                'code_snippet' => "@include('partials.html-widgets-drawer')\n@include('partials.display-plugins-drawer')\n@include('partials.shortcodes-generator-drawer')\n@include('partials.link-generator-drawer')",
            ],
            [
                'category_id' => 110, 'sort_order' => 9,
                'title' => 'Shortcode Generator Drawer (partials.shortcodes-generator-drawer)',
                'summary' => '1-click tool to build shortcodes for pages, downloads, embeds, forms, and plugins.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/admin/cms-pages',
                'details' => [
                    ['Shortcode Builder', 'Visual modal generating valid shortcode syntax with custom parameters.', 'Visual Generator'],
                    ['1-Click Copy', 'Copies shortcode string to clipboard or inserts directly into active TinyMCE instance.', 'Insert Action'],
                ],
                'code_snippet' => "[plugin:slideshow-2026 id=1 auto_play=1 speed=4000]",
            ],
            [
                'category_id' => 110, 'sort_order' => 10,
                'title' => 'Link Generator Autocomplete Drawer (partials.link-generator-drawer)',
                'summary' => 'Real-time autocomplete drawer searching products, brands, categories, and pages to generate formatted HTML links.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/admin/cms-pages',
                'details' => [
                    ['Live Autocomplete', 'Real-time search across products, brands, categories, and CMS pages.', 'Live Search'],
                    ['Link Formatter', 'Generates fully qualified HTML anchor links with target and title attributes.', 'HTML Link'],
                ],
                'code_snippet' => "<a href=\"/items/navy-jacket\" title=\"Navy Outerwear Jacket\">Navy Outerwear Jacket</a>",
            ],

            // ── CATEGORY 111: Search Discovery, Autocomplete & Events (10 articles) ──
            [
                'category_id' => 111, 'sort_order' => 1,
                'title' => 'Advanced Shop Search Filtering Drawer Configuration',
                'summary' => 'Toggling the slide-out catalog drawer on /shop with category trees, brand filters, and price range sliders.',
                'admin_route' => '/admin/settings', 'public_route' => '/shop',
                'details' => [
                    ['Enable Search Drawer', 'Toggle enable_advanced_shop_search in /admin/settings.', 'Drawer Toggle'],
                    ['Filter Controls', 'Slide-out drawer featuring category trees, brand checkboxes, and price sliders.', 'Filter UI'],
                ],
                'code_snippet' => "CmsSetting::set('enable_advanced_shop_search', 1);",
            ],
            [
                'category_id' => 111, 'sort_order' => 2,
                'title' => 'Multi-Content Live Search Autocomplete Plugin ([plugin:live-search-2026])',
                'summary' => 'Real-time JSON live search querying categories, brands, products, pages, KB articles, and testimonials.',
                'admin_route' => '/admin/plugins', 'public_route' => '/search',
                'details' => [
                    ['Live Search Shortcode', 'Embed real-time search bar using [plugin:live-search-2026].', 'Shortcode Tag'],
                    ['6 Search Targets', 'Queries categories, brands, products, pages, KB articles, and testimonials.', 'Multi Target'],
                ],
                'code_snippet' => "[plugin:live-search-2026]\n[plugin:live-search-2026 mode=results]",
            ],
            [
                'category_id' => 111, 'sort_order' => 3,
                'title' => 'Events Calendar Display Plugin ([plugin:events-calendar-2026])',
                'summary' => 'Rendering interactive event calendars with month, agenda, and card grid layouts.',
                'admin_route' => '/admin/plugins', 'public_route' => '/events',
                'details' => [
                    ['Events Plugin', 'Embed interactive event calendars using [plugin:events-calendar-2026].', 'Calendar Tag'],
                    ['Layout Modes', 'Supports month grid, agenda list, and event card grid display modes.', 'Layout Modes'],
                ],
                'code_snippet' => "[plugin:events-calendar-2026]\n[plugin:events-calendar-2026 layout=grid]",
            ],
            [
                'category_id' => 111, 'sort_order' => 4,
                'title' => 'Event Ticket Quotas & Variant Booking Integration',
                'summary' => 'Attaching event dates and ticket quotas to product variants with direct checkout booking modals.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/events',
                'details' => [
                    ['Event Variant Fields', 'Set event_date, event_location, and ticket_quota on product variant edit.', 'Event Fields'],
                    ['Direct Booking', 'Clicking event calendar date opens ticket quantity selector with instant Add to Cart.', 'Direct Booking'],
                ],
                'code_snippet' => "\$variant->event_date = '2026-09-15 10:00:00';\n\$variant->ticket_quota = 50;",
            ],
            [
                'category_id' => 111, 'sort_order' => 5,
                'title' => 'Collated FULLTEXT Search Index Engine (cms_search_index & product_search_index)',
                'summary' => 'How cms_search_index and product_search_index columns combine metadata and shortcode content for high-speed queries.',
                'admin_route' => '/admin/settings', 'public_route' => '/search',
                'details' => [
                    ['Search Index Columns', 'Collates title, meta descriptions, body text, and shortcodes into indexed columns.', 'FULLTEXT Index'],
                    ['High Speed Query', 'Provides sub-10ms search query responses across thousands of catalog records.', 'Fast Lookup'],
                ],
                'code_snippet' => "ALTER TABLE cms_pages ADD FULLTEXT(cms_search_index);",
            ],
            [
                'category_id' => 111, 'sort_order' => 6,
                'title' => 'Admin Keyword Locking (cms_search_index_locked)',
                'summary' => 'Preventing automated index rebuilds from overwriting custom admin promo keywords.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/search',
                'details' => [
                    ['Keyword Lock Toggle', 'Set cms_search_index_locked = 1 on page/product edit screen.', 'Keyword Lock'],
                    ['Custom Keywords', 'Allows staff to append custom promotional keywords without losing them during index rebuilds.', 'Promo Keywords'],
                ],
                'code_snippet' => "\$page->cms_search_index_locked = 1;\n\$page->cms_search_index .= ' sale black-friday promo';",
            ],
            [
                'category_id' => 111, 'sort_order' => 7,
                'title' => 'Artisan CLI Search Index Rebuild (php artisan search:rebuild-index)',
                'summary' => 'Running php artisan search:rebuild-index to re-index all catalog and site content.',
                'admin_route' => '/admin/settings', 'public_route' => '/search',
                'details' => [
                    ['CLI Indexer', 'Re-collates and rebuilds search index columns across all products and CMS pages.', 'CLI Command'],
                    ['Cron Schedule', 'Can be scheduled in Laravel Task Scheduler to run nightly.', 'Cron Task'],
                ],
                'code_snippet' => "php artisan search:rebuild-index",
            ],
            [
                'category_id' => 111, 'sort_order' => 8,
                'title' => 'Category & Brand Filter Multi-Select Logic',
                'summary' => 'How catalog search filters combine multi-selected category and brand checkboxes.',
                'admin_route' => '/admin/ecommerce/categories', 'public_route' => '/shop',
                'details' => [
                    ['Category Filter', 'Filters products matching selected category IDs (including child subcategories).', 'Category Filter'],
                    ['Brand Filter', 'Filters products matching selected brand IDs.', 'Brand Filter'],
                ],
                'code_snippet' => "\$query->whereIn('category_id', \$selectedCats)->whereIn('brand_id', \$selectedBrands);",
            ],
            [
                'category_id' => 111, 'sort_order' => 9,
                'title' => 'Price Range Slider & Min/Max Price Filtering',
                'summary' => 'Configuring catalog price range slider inputs on the shop page.',
                'admin_route' => '/admin/settings', 'public_route' => '/shop',
                'details' => [
                    ['Dynamic Min/Max', 'Auto-detects minimum and maximum product variant prices in active catalog.', 'Price Bounds'],
                    ['Reactive Slider', 'Alpine.js dual-thumb price range slider updating catalog grid live.', 'Price Slider'],
                ],
                'code_snippet' => "\$query->whereBetween('public_price', [\$minPrice, \$maxPrice]);",
            ],
            [
                'category_id' => 111, 'sort_order' => 10,
                'title' => 'Sorting Options (Price Low-High, High-Low, Newest, Best Selling)',
                'summary' => 'Configuring catalog sorting dropdown choices for storefront customers.',
                'admin_route' => '/admin/settings', 'public_route' => '/shop',
                'details' => [
                    ['Newest First', 'Orders by product created_at date descending (Default).', 'Newest Sort'],
                    ['Price Low-High / High-Low', 'Orders by variant public_price ascending or descending.', 'Price Sort'],
                    ['Best Sellers', 'Orders by cumulative order item quantity sold.', 'Best Sellers'],
                ],
                'code_snippet' => "\$query->orderBy('public_price', 'asc');",
            ],

            // ── CATEGORY 112: Access Control, Social Logins & Guests (10 articles) ──
            [
                'category_id' => 112, 'sort_order' => 1,
                'title' => 'Built-in Social OAuth Logins (Google, Facebook, GitHub)',
                'summary' => 'Integrating Socialite OAuth authentication for 1-click social logins.',
                'admin_route' => '/admin/settings', 'public_route' => '/login',
                'details' => [
                    ['1-Click Login', 'Allows customers to log in or register instantly using Google, Facebook, or GitHub.', 'Social Login'],
                    ['Auto Verification', 'Social OAuth users are automatically marked as email-verified.', 'Auto Verify'],
                ],
                'code_snippet' => "Route::get('/auth/{provider}', [SocialController::class, 'redirect']);\nRoute::get('/auth/{provider}/callback', [SocialController::class, 'callback']);",
            ],
            [
                'category_id' => 112, 'sort_order' => 2,
                'title' => 'User Registration, Verification & Access Matrix',
                'summary' => 'Comparing Social OAuth (auto-verified), Standard Password Registration (email verification link required), and Guest Checkout.',
                'admin_route' => '/admin/users', 'public_route' => '/register',
                'details' => [
                    ['Social OAuth User', 'Email auto-verified. Instant full account access.', 'OAuth User'],
                    ['Standard Password User', 'Must click email verification link sent via registration_retail email template.', 'Password User'],
                    ['Guest Checkout User', 'Order confirmed immediately without password setup. Account password setup optional via magic link.', 'Guest Checkout'],
                ],
                'code_snippet' => "// User registration email verification check:\nif (!\$user->hasVerifiedEmail()) {\n    \$user->sendEmailVerificationNotification();\n}",
            ],
            [
                'category_id' => 112, 'sort_order' => 3,
                'title' => 'Post-Order Completion Redirects & Custom Action Links',
                'summary' => 'Configuring custom destination URLs or CMS page shortcodes after checkout completion.',
                'admin_route' => '/admin/ecommerce/products', 'public_route' => '/checkout',
                'details' => [
                    ['Completion Redirect', 'Set completion_redirect URL or page slug on product edit screen.', 'Completion Link'],
                    ['Redirect Label', 'Set completion_redirect_label text (e.g. "Access Your Video Vault").', 'Button Label'],
                ],
                'code_snippet' => "\$product->completion_redirect = 'vault-access-page';\n\$product->completion_redirect_label = 'Access Your Vault';",
            ],
            [
                'category_id' => 112, 'sort_order' => 4,
                'title' => 'Custom Portal Action Buttons in Automated Emails',
                'summary' => 'Injecting violet portal buttons into Order Confirmation, Shipment, and Download emails.',
                'admin_route' => '/admin/email-templates', 'public_route' => '/checkout',
                'details' => [
                    ['Violet Action Button', 'Automated email templates render styled violet CTA buttons linking to completion URLs.', 'Email Button'],
                    ['Guest Token URL', 'Automatically generates single-click UUID magic token links for guest buyers.', 'Magic Token'],
                ],
                'code_snippet' => "<a href=\"{{token_url}}\" style=\"background-color: #7c3aed; color: #ffffff; padding: 10px 20px;\">{{completion_label}}</a>",
            ],
            [
                'category_id' => 112, 'sort_order' => 5,
                'title' => 'CMS Page Access Gating (Purchase Check vs Access Code)',
                'summary' => 'Restricting CMS pages to verified purchasers of specific product IDs or requiring access code passkeys.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/gated-content',
                'details' => [
                    ['Required Product ID', 'Gates CMS page so only customers who purchased product_id X can view content.', 'Product Gate'],
                    ['Access Code Passkey', 'Requires visitors to enter a secret passkey (access_code) to view page.', 'Passkey Gate'],
                ],
                'code_snippet' => "\$page->requires_code = 1;\n\$page->access_code = 'SECRET2026';\n\$page->required_product_id = 42;",
            ],
            [
                'category_id' => 112, 'sort_order' => 6,
                'title' => 'Secure 90-Day UUID Magic Access Tokens (content_access_tokens)',
                'summary' => 'Generating single-click access links for guest purchasers bypassing login screens.',
                'admin_route' => '/admin/ecommerce/orders/{id}', 'public_route' => '/content-access/uuid-token',
                'details' => [
                    ['UUID Token Table', 'Generates 90-day single-click magic access tokens in content_access_tokens table.', 'Magic Token'],
                    ['Guest Access Bypass', 'Allows guest buyers to view gated digital assets without logging into account.', 'Guest Bypass'],
                ],
                'code_snippet' => "\$token = ContentAccessToken::generateOrRefresh(\$orderDetail, \$targetUrl, \$guestEmail);",
            ],
            [
                'category_id' => 112, 'sort_order' => 7,
                'title' => 'Guest Account Conversion Flow ([GUEST-USER])',
                'summary' => 'How guest checkout creates accounts with [GUEST-USER] sentinel password converted via 2-step verification.',
                'admin_route' => '/admin/users', 'public_route' => '/checkout',
                'details' => [
                    ['Sentinel Password', 'Guest checkout creates user profile with sentinel password [GUEST-USER].', 'Guest Profile'],
                    ['Account Conversion', 'Guest clicks "Set Password" link in email to convert profile into full account.', 'Account Upgrade'],
                ],
                'code_snippet' => "\$user->password = Hash::make('[GUEST-USER]');",
            ],
            [
                'category_id' => 112, 'sort_order' => 8,
                'title' => 'Wholesale Account Registration & Approval Workflow',
                'summary' => 'Managing wholesale account registrations and manual admin approval queues.',
                'admin_route' => '/admin/users', 'public_route' => '/register',
                'details' => [
                    ['Wholesale Registration', 'Customers select Wholesale Account registration type on /register.', 'Wholesale Form'],
                    ['Admin Approval', 'Admin receives registration_wholesale notification email and approves wholesale role.', 'Admin Approval'],
                ],
                'code_snippet' => "\$user->role_id = UserRole::Wholesale->value;",
            ],
            [
                'category_id' => 112, 'sort_order' => 9,
                'title' => 'Account Password Reset Workflow & Expiration Rules',
                'summary' => 'How customer password reset emails are dispatched and validated via password_resets tokens.',
                'admin_route' => '/admin/users', 'public_route' => '/password/reset',
                'details' => [
                    ['Reset Password Request', 'Customer requests password reset on /password/reset screen.', 'Reset Request'],
                    ['60-Minute Expiration', 'Dispatches email_templates (type_id = 7) with 60-minute token expiration.', 'Token Expiration'],
                ],
                'code_snippet' => "Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);",
            ],
            [
                'category_id' => 112, 'sort_order' => 10,
                'title' => 'Session Timeout & Cookie Security Standards',
                'summary' => 'Configuring HTTP-only secure cookies and session expiration windows in config/session.php.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Session Lifetime', 'Configures session expiration window (e.g. 120 minutes of inactivity).', 'Session Timeout'],
                    ['Secure Cookies', 'Enforces http_only, secure, and same_site = lax cookie flags.', 'Cookie Security'],
                ],
                'code_snippet' => "'lifetime' => 120,\n'secure' => true,\n'http_only' => true,\n'same_site' => 'lax',",
            ],

            // ── CATEGORY 113: Multi-Language & OpenAI AI Engine (10 articles) ──
            [
                'category_id' => 113, 'sort_order' => 1,
                'title' => 'Language Management, Flag Icons & Country Codes (/admin/languages)',
                'summary' => 'Managing site languages in /admin/languages with flag-icons CSS library.',
                'admin_route' => '/admin/languages', 'public_route' => '/',
                'details' => [
                    ['Language Manager', 'Add, edit, enable, and set default site languages in /admin/languages.', 'Language Manager'],
                    ['Flag Icons', 'Renders country flag badges using flag-icons CSS library.', 'Flag Badges'],
                ],
                'code_snippet' => "DB::table('languages')->insert([\n    'code' => 'es',\n    'name' => 'Spanish',\n    'flag_icon' => 'fi-es',\n]);",
            ],
            [
                'category_id' => 113, 'sort_order' => 2,
                'title' => 'Per-Language Currency Overrides & RTL Text Direction',
                'summary' => 'Setting custom currency symbols and RTL text direction (dir="rtl") per language.',
                'admin_route' => '/admin/languages', 'public_route' => '/',
                'details' => [
                    ['Currency Overrides', 'Set custom currency symbol and positioning per language (e.g. € EUR, ¥ JPY).', 'Currency Overrides'],
                    ['RTL Support', 'Toggle is_rtl = 1 to automatically set dir="rtl" attribute on storefront HTML.', 'RTL Support'],
                ],
                'code_snippet' => "\$language->is_rtl = 1;\n\$language->currency_symbol = '€';",
            ],
            [
                'category_id' => 113, 'sort_order' => 3,
                'title' => 'Detailed Guide to the 10 System Email Templates',
                'summary' => 'Managing order confirmation, shipment, download reminder, registration, password reset, and ticket templates in /admin/email-templates.',
                'admin_route' => '/admin/email-templates', 'public_route' => '/admin/email-templates',
                'details' => [
                    ['Type 1: Order Confirmation', 'Sent upon successful order placement with item receipt table.', 'Type 1'],
                    ['Type 2: Shipment Confirmation', 'Sent when order is marked shipped with tracking number.', 'Type 2'],
                    ['Type 3: Download Reminder', 'Sent to re-dispatch digital download link reminder emails.', 'Type 3'],
                    ['Type 4: Retail Registration', 'Welcome email for new retail password customer accounts.', 'Type 4'],
                    ['Type 5: Wholesale Registration', 'Notification email for wholesale account registrations.', 'Type 5'],
                    ['Type 6: Account Activation', 'Email verification activation link email.', 'Type 6'],
                    ['Type 7: Reset Password', 'Password reset link email with 60-min expiration token.', 'Type 7'],
                    ['Type 8: Ticket Submitted', 'Support ticket creation confirmation email.', 'Type 8'],
                    ['Type 9: Ticket Reply Received', 'Support agent ticket reply notification email.', 'Type 9'],
                    ['Type 10: Ticket Status Updated', 'Support ticket status change notification email.', 'Type 10'],
                ],
                'code_snippet' => "Route::get('/admin/email-templates', AdminEmailTemplates::class);",
            ],
            [
                'category_id' => 113, 'sort_order' => 4,
                'title' => 'Email Profile Management & Active Selection (is_active = 1)',
                'summary' => 'Creating seasonal email template profiles and toggling active status.',
                'admin_route' => '/admin/email-templates', 'public_route' => '/admin/email-templates',
                'details' => [
                    ['Multiple Email Profiles', 'Create multiple email template profiles per type (e.g. "Default", "Holiday Sale").', 'Email Profiles'],
                    ['Active Switch (is_active)', 'Only 1 profile per type_id can be active (is_active = 1) at a time.', 'Active Selection'],
                ],
                'code_snippet' => "DB::table('email_templates')->where('email_type_id', 1)->update(['is_active' => 0]);\n\$template->is_active = 1;\n\$template->save();",
            ],
            [
                'category_id' => 113, 'sort_order' => 5,
                'title' => 'Visual Email Layout Builder & Live Preview Modal',
                'summary' => 'Designing header branding, salutations, body HTML, sign-offs, and inspecting live previews with mock data.',
                'admin_route' => '/admin/email-templates', 'public_route' => '/admin/email-templates',
                'details' => [
                    ['Visual Email Builder', 'Configure header HTML, banner images, salutation, body HTML, sign-off, signature, disclaimer, and copyright.', 'Visual Builder'],
                    ['Live Preview Modal', 'Inspect real-time HTML email rendering with simulated order and customer data.', 'Live Preview'],
                ],
                'code_snippet' => "<div style=\"background-color: #f8fafc; padding: 20px;\">\n    <h2>{{salutation}}</h2>\n    <div>{{body}}</div>\n</div>",
            ],
            [
                'category_id' => 113, 'sort_order' => 6,
                'title' => 'Child-Table Translation Database Pattern',
                'summary' => 'How translatable models store translated strings in dedicated *_translations child tables.',
                'admin_route' => '/admin/languages', 'public_route' => '/',
                'details' => [
                    ['Translation Child Tables', 'Stores translated text in dedicated child tables (e.g. cms_page_translations, product_translations).', 'Child Schemas'],
                    ['HasTranslations Trait', 'Eloquent models use HasTranslations trait to automatically fall back to default language.', 'Trait Sync'],
                ],
                'code_snippet' => "class Product extends Model {\n    use HasTranslations;\n    protected \$translatable = ['title', 'short_description', 'long_description'];\n}",
            ],
            [
                'category_id' => 113, 'sort_order' => 7,
                'title' => 'OpenAI Engine Integration & .env Setup',
                'summary' => 'Configuring OPENAI_API_KEY=sk-... for AI content generation and translation services.',
                'admin_route' => '/admin/settings', 'public_route' => '/admin/settings',
                'details' => [
                    ['OpenAI Key', 'Configure OPENAI_API_KEY=sk-proj-... in .env file.', 'API Key'],
                    ['Model Engine', 'Uses gpt-4o or gpt-4o-mini for high-speed content generation and translation.', 'Model Engine'],
                ],
                'code_snippet' => "OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxxxxxxxxxxxxx",
            ],
            [
                'category_id' => 113, 'sort_order' => 8,
                'title' => 'AI Content Generator in CMS & Product Editors',
                'summary' => 'Generating page content, product descriptions, and meta tags using OpenAI in page/product editors.',
                'admin_route' => '/admin/cms-pages', 'public_route' => '/admin/cms-pages',
                'details' => [
                    ['AI Writer Button', 'Click "Generate with AI" button on CMS page and product edit blades.', 'AI Writer'],
                    ['Prompt Controls', 'Generates body HTML, short descriptions, and SEO meta tags based on title prompt.', 'SEO Writer'],
                ],
                'code_snippet' => "\$aiText = TranslationService::generateAiContent(\$prompt, \$contentType);",
            ],
            [
                'category_id' => 113, 'sort_order' => 9,
                'title' => '11-Table Bulk Multi-Language AI Translation Pipeline',
                'summary' => 'Bulk-translating an entire new language across 11 core database entities with 1 click in /admin/languages.',
                'admin_route' => '/admin/languages', 'public_route' => '/admin/languages',
                'details' => [
                    ['Bulk Translate Button', 'Click "Bulk Translate All" in /admin/languages to translate store into target language.', 'Bulk Action'],
                    ['11 Translatable Tables', 'Processes cms_pages, products, product_variants, kb_articles, testimonials, nav_items, cms_list_menu_items, categories, site_labels, email_templates, and plugin_settings.', '11 Tables'],
                ],
                'code_snippet' => "TranslationService::bulkTranslateLanguage(\$targetLanguageCode);",
            ],
            [
                'category_id' => 113, 'sort_order' => 10,
                'title' => 'Shortcode Protection Engine During AI Translation ({{PLUGIN_0}})',
                'summary' => 'How TranslationService masks shortcode tags with {{PLUGIN_0}} placeholders before sending text to OpenAI.',
                'admin_route' => '/admin/languages', 'public_route' => '/admin/languages',
                'details' => [
                    ['Shortcode Masking', 'Converts bracketed shortcode tags (e.g. [plugin:slideshow-2026]) to {{PLUGIN_0}} placeholders.', 'Masking Engine'],
                    ['Original Syntax Restored', 'Restores original shortcode syntax post-translation to prevent OpenAI from corrupting tags.', 'Unmasking Engine'],
                ],
                'code_snippet' => "// Input: [plugin:slideshow-2026 id=1]\n// Sent to OpenAI: {{PLUGIN_0}}\n// Restored Post-AI: [plugin:slideshow-2026 id=1]",
            ],

            // ── CATEGORY 114: Browser Queue Monitor & Analytics (10 articles) ──
            [
                'category_id' => 114, 'sort_order' => 1,
                'title' => 'Background Process Queue Monitor Dashboard (/admin/languages/queue-monitor)',
                'summary' => 'Monitoring background translation queues, email dispatches, and queue runner status.',
                'admin_route' => '/admin/languages/queue-monitor', 'public_route' => '/admin/languages/queue-monitor',
                'details' => [
                    ['Queue Monitor UI', 'Real-time dashboard in /admin/languages/queue-monitor tracking background jobs.', 'Queue Dashboard'],
                    ['Job Statuses', 'Displays pending, processing, completed, and failed job counts.', 'Job Statuses'],
                ],
                'code_snippet' => "Route::get('/admin/languages/queue-monitor', QueueMonitorDashboard::class);",
            ],
            [
                'category_id' => 114, 'sort_order' => 2,
                'title' => 'Background Process ID (PID) Runner Control',
                'summary' => 'How queue_runner.php tracks background PIDs to ensure single-instance queue processing.',
                'admin_route' => '/admin/languages/queue-monitor', 'public_route' => '/admin/languages/queue-monitor',
                'details' => [
                    ['PID File Tracking', 'Tracks background Process IDs (PIDs) using storage/app/queue_runner.pid.', 'PID Tracking'],
                    ['Browser Process Control', 'Allows staff to start, pause, resume, or terminate queue runner processes from browser.', 'Process Control'],
                ],
                'code_snippet' => "exec('php queue_runner.php > /dev/null 2>&1 & echo $! > storage/app/queue_runner.pid');",
            ],
            [
                'category_id' => 114, 'sort_order' => 3,
                'title' => 'E-Commerce Analytics Reports Dashboard (/admin/reports)',
                'summary' => 'Accessing built-in e-commerce reports for sales volume, product performance, and cart conversion funnels.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['Analytics Dashboard', 'Access sales analytics reports under Admin → Reports & Analytics.', 'Analytics UI'],
                    ['Date Range Selector', 'Filter reporting metrics by today, last 7 days, last 30 days, or custom date ranges.', 'Date Filters'],
                ],
                'code_snippet' => "Route::get('/admin/reports', AdminReports::class);",
            ],
            [
                'category_id' => 114, 'sort_order' => 4,
                'title' => 'Daily & Monthly Revenue & Order Activity Charts',
                'summary' => 'Analyzing daily, weekly, and monthly store revenue and order volume.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['Revenue Metrics', 'Displays gross sales, net sales, taxes collected, and shipping revenue.', 'Revenue Totals'],
                    ['Order Volume Charts', 'Visual bar charts tracking completed, pending, and refunded order counts.', 'Order Volume'],
                ],
                'code_snippet' => "\$monthlyRevenue = Order::where('order_status', 2)->sum('order_total');",
            ],
            [
                'category_id' => 114, 'sort_order' => 5,
                'title' => 'Product Sales Performance & View Conversion Metrics',
                'summary' => 'Identifying top-selling items, view counts, and purchase conversion rates.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['Top Selling Products', 'Ranks catalog products by total units sold and net revenue generated.', 'Best Sellers'],
                    ['Conversion Rate', 'Calculates purchase conversion percentage (Orders ÷ Product Page Views).', 'Conversion Rate'],
                ],
                'code_snippet' => "\$topProducts = OrderDetail::select('item_name', DB::raw('SUM(item_qty) as total_qty'))->groupBy('item_name')->orderByDesc('total_qty')->get();",
            ],
            [
                'category_id' => 114, 'sort_order' => 6,
                'title' => 'Abandoned Cart Funnel Analytics & Conversion Reports',
                'summary' => 'Tracking abandoned shopping cart sessions and conversion drop-offs.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['shopping_cart_log Table', 'Tracks active customer shopping sessions in shopping_cart_log database table.', 'Cart Log'],
                    ['Abandoned Cart Recovery', 'Identifies uncompleted checkout sessions and calculates cart abandonment rate.', 'Cart Recovery'],
                ],
                'code_snippet' => "\$abandonedCarts = DB::table('shopping_cart_log')->where('order_id', 0)->get();",
            ],
            [
                'category_id' => 114, 'sort_order' => 7,
                'title' => 'Customer Lifetime Value (CLV) & Retail vs Wholesale Breakdown',
                'summary' => 'Segmenting store revenue by retail vs wholesale customer accounts.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['Retail Sales', 'Tracks total revenue generated by retail customer accounts (UserRole::User).', 'Retail Sales'],
                    ['Wholesale Sales', 'Tracks total revenue generated by wholesale accounts (UserRole::Wholesale).', 'Wholesale Sales'],
                ],
                'code_snippet' => "\$wholesaleRevenue = Order::whereHas('user', function(\$q) { \$q->where('role_id', 2); })->sum('order_total');",
            ],
            [
                'category_id' => 114, 'sort_order' => 8,
                'title' => 'Exporting Sales & Order Reports to CSV / Excel',
                'summary' => 'Downloading order sales ledgers and inventory stock reports as spreadsheet files.',
                'admin_route' => '/admin/reports', 'public_route' => '/admin/reports',
                'details' => [
                    ['Spreadsheet Export', 'Export sales data, tax ledgers, and inventory levels to .csv or .xlsx files.', 'Excel Export'],
                    ['Filtered Export', 'Applies active date range and order status filters to exported spreadsheet files.', 'Filtered Export'],
                ],
                'code_snippet' => "return Excel::download(new OrderSalesExport(\$startDate, \$endDate), 'sales_report.xlsx');",
            ],
            [
                'category_id' => 114, 'sort_order' => 9,
                'title' => 'Failed Job Logging & Queue Diagnostics',
                'summary' => 'Inspecting failed background queue jobs and viewing exception tracebacks.',
                'admin_route' => '/admin/languages/queue-monitor', 'public_route' => '/admin/languages/queue-monitor',
                'details' => [
                    ['failed_jobs Table', 'Logs failed background queue jobs and exception stack tracebacks.', 'Failed Jobs'],
                    ['Retry Job Action', 'Click "Retry Job" in queue monitor UI to re-dispatch failed tasks.', 'Retry Action'],
                ],
                'code_snippet' => "php artisan queue:retry all",
            ],
            [
                'category_id' => 114, 'sort_order' => 10,
                'title' => 'System Log Tail Viewer & Maintenance Mode Controls',
                'summary' => 'Tailers application logs and toggling maintenance mode for server updates.',
                'admin_route' => '/admin/settings', 'public_route' => '/',
                'details' => [
                    ['Log Tail Viewer', 'Inspect real-time storage/logs/laravel.log entries from admin dashboard.', 'Log Viewer'],
                    ['Maintenance Mode', 'Toggle maintenance mode to display custom maintenance page during server updates.', 'Maintenance Mode'],
                ],
                'code_snippet' => "php artisan down --secret=\"admin-secret-access-token\"\nphp artisan up",
            ],

            // ── CATEGORY 115: Display Plugins & Plugin Architecture (10 articles) ──
            [
                'category_id' => 115, 'sort_order' => 1,
                'title' => 'Plugin Manager Control Panel (/admin/plugins)',
                'summary' => 'Managing, enabling, disabling, and configuring parameters for all installed display and shipping plugins.',
                'admin_route' => '/admin/plugins', 'public_route' => '/admin/plugins',
                'details' => [
                    ['Plugin Control Panel', 'Manage all installed plugins under Admin → Plugin Management.', 'Plugin Manager'],
                    ['Enable / Disable Toggle', 'Enable or disable individual plugins storewide with instant shortcode fallback handling.', 'Plugin Toggle'],
                ],
                'code_snippet' => "Route::get('/admin/plugins', AdminPlugins::class);",
            ],
            [
                'category_id' => 115, 'sort_order' => 2,
                'title' => 'Display Plugin 1 — Slideshow Banner Slider ([plugin:slideshow-2026])',
                'summary' => 'Full-width responsive hero banner slideshow slider display plugin.',
                'admin_route' => '/admin/cms/slideshows', 'public_route' => '/',
                'details' => [
                    ['Slideshow Plugin', 'Embed interactive slideshow sliders using [plugin:slideshow-2026].', 'Slideshow Tag'],
                    ['Parameters', 'id (slide deck ID), auto_play (1/0), speed (milliseconds).', 'Parameters'],
                ],
                'code_snippet' => "[plugin:slideshow-2026 id=1 auto_play=1 speed=5000]",
            ],
            [
                'category_id' => 115, 'sort_order' => 3,
                'title' => 'Display Plugin 2 — Featured Items Grid ([plugin:featured-items])',
                'summary' => 'Product grid/slider display plugin for featured items and best sellers.',
                'admin_route' => '/admin/plugins', 'public_route' => '/',
                'details' => [
                    ['Featured Items Plugin', 'Embed product grids using [plugin:featured-items].', 'Featured Tag'],
                    ['Parameters', 'display (grid/slider), header (custom title text), max (item limit).', 'Parameters'],
                ],
                'code_snippet' => "[plugin:featured-items display=slider header=\"Featured Products\" max=8]",
            ],
            [
                'category_id' => 115, 'sort_order' => 4,
                'title' => 'Display Plugin 3 — Product Cross-Sells ([plugin:cross-sells])',
                'summary' => 'Related product cross-sell recommendations display plugin.',
                'admin_route' => '/admin/plugins', 'public_route' => '/items/product-slug',
                'details' => [
                    ['Cross-Sells Plugin', 'Embed related product recommendations using [plugin:cross-sells].', 'Cross-Sells Tag'],
                    ['Automatic Relation', 'Automatically queries cross-sell assignments (product_cross_selling table).', 'Relational Lookup'],
                ],
                'code_snippet' => "[plugin:cross-sells header=\"Frequently Bought Together\" cols=4]",
            ],
            [
                'category_id' => 115, 'sort_order' => 5,
                'title' => 'Display Plugin 4 — Live Search Autocomplete ([plugin:live-search-2026])',
                'summary' => 'Multi-content real-time JSON search bar display plugin.',
                'admin_route' => '/admin/plugins', 'public_route' => '/search',
                'details' => [
                    ['Live Search Plugin', 'Embed real-time search bar using [plugin:live-search-2026].', 'Live Search Tag'],
                    ['Search Target Overlay', 'Renders drop-down search results overlay as user types.', 'Results Overlay'],
                ],
                'code_snippet' => "[plugin:live-search-2026 placeholder=\"Search products, articles, pages...\"]",
            ],
            [
                'category_id' => 115, 'sort_order' => 6,
                'title' => 'Display Plugin 5 — Events Calendar ([plugin:events-calendar-2026])',
                'summary' => 'Interactive event calendar display plugin with ticket booking integration.',
                'admin_route' => '/admin/plugins', 'public_route' => '/events',
                'details' => [
                    ['Events Calendar Plugin', 'Embed event calendar widget using [plugin:events-calendar-2026].', 'Events Tag'],
                    ['Ticket Booking Integration', 'Displays event dates, locations, and ticket booking buy buttons.', 'Ticket Booking'],
                ],
                'code_snippet' => "[plugin:events-calendar-2026 layout=month]",
            ],
            [
                'category_id' => 115, 'sort_order' => 7,
                'title' => 'Display Plugin 6 — Social Media Icon Bar ([plugin:social-icons-2026])',
                'summary' => 'Responsive social media icon links display plugin.',
                'admin_route' => '/admin/plugins', 'public_route' => '/',
                'details' => [
                    ['Social Icons Plugin', 'Embed social media icon links using [plugin:social-icons-2026].', 'Social Tag'],
                    ['Configured Links', 'Renders Facebook, Twitter/X, Instagram, LinkedIn, and YouTube icons configured in settings.', 'Icon Links'],
                ],
                'code_snippet' => "[plugin:social-icons-2026 style=rounded]",
            ],
            [
                'category_id' => 115, 'sort_order' => 8,
                'title' => 'Display Plugin 7 — Brand Logo Slider ([plugin:brands-2026])',
                'summary' => 'Manufacturing brand logo slider display plugin.',
                'admin_route' => '/admin/plugins', 'public_route' => '/',
                'details' => [
                    ['Brand Slider Plugin', 'Embed brand logo carousel using [plugin:brands-2026].', 'Brand Slider Tag'],
                    ['Logo Query', 'Queries active product brands with uploaded logo images.', 'Brand Logos'],
                ],
                'code_snippet' => "[plugin:brands-2026 display=slider cols=5 autoplay=on]",
            ],
            [
                'category_id' => 115, 'sort_order' => 9,
                'title' => 'DisplayPlugin Interface & Lifecycle Hooks',
                'summary' => 'Understanding the DisplayPlugin contract, render method, shortcode tag registration, and settings schema.',
                'admin_route' => '/admin/plugins', 'public_route' => '/admin/plugins',
                'details' => [
                    ['DisplayPlugin Interface', 'All display plugins implement App\\Contracts\\DisplayPlugin interface.', 'Interface Contract'],
                    ['Lifecycle Methods', 'Requires getShortcodeTag(), render(array $attributes), and getSettingsSchema().', 'Lifecycle Methods'],
                ],
                'code_snippet' => "interface DisplayPlugin {\n    public function getShortcodeTag(): string;\n    public function render(array \$attributes): string;\n}",
            ],
            [
                'category_id' => 115, 'sort_order' => 10,
                'title' => 'Creating Standalone Drop-in External Plugins (plugin.json)',
                'summary' => 'Building standalone drop-in plugins inside plugins/ directory with plugin.json manifest files.',
                'admin_route' => '/admin/plugins', 'public_route' => '/admin/plugins',
                'details' => [
                    ['Drop-in Directory', 'Create custom plugin package inside plugins/{plugin-name}/ directory.', 'Plugin Directory'],
                    ['plugin.json Manifest', 'Declare plugin name, version, author, shortcode tag, and main class file in plugin.json.', 'Manifest JSON'],
                ],
                'code_snippet' => "{\n  \"name\": \"Custom Testimonials Plugin\",\n  \"shortcode\": \"testimonials-2026\",\n  \"class\": \"Plugins\\\\Testimonials\\\\TestimonialsPlugin\"\n}",
            ],
        ];
    }
}
