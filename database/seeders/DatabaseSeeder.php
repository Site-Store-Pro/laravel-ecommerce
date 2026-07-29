<?php

namespace Database\Seeders;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------
        // Users
        // Admin
        // ---------------------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@support.local'],
            [
                'name'              => 'Support Admin',
                'password'          => Hash::make('SampleUser12345#'),
                'role_id'           => UserRole::Admin->value,
                'email_verified_at' => now(),
            ]
        );

        // Order processor / ticket manager
        $agent = User::firstOrCreate(
            ['email' => 'orders@support.local'],
            [
                'name'              => 'Order Processor',
                'password'          => Hash::make('SampleUser12345#'),
                'role_id'           => UserRole::OrderProcessor->value,
                'email_verified_at' => now(),
            ]
        );

        // Sample retail customer
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.local'],
            [
                'name'              => 'Sample Customer',
                'password'          => Hash::make('SampleUser12345#'),
                'role_id'           => UserRole::User->value,
                'email_verified_at' => now(),
            ]
        );

        // Sample wholesale customer
        $wholesale = User::firstOrCreate(
            ['email' => 'wholesale@example.local'],
            [
                'name'              => 'Wholesale Buyer',
                'password'          => Hash::make('SampleUser12345#'),
                'role_id'           => UserRole::Wholesale->value,
                'email_verified_at' => now(),
            ]
        );

        // ---------------------------------------------------------------
        // Sample support tickets (attached to sample customer)
        // ---------------------------------------------------------------
        Ticket::factory()->create([
            'user_id'     => $customer->id,
            'title'       => 'Cannot reset my password',
            'description' => "I've tried resetting my password three times but never receive the email. Can you help?",
            'status'      => TicketStatus::Open,
            'assigned_to' => null,
        ]);

        Ticket::factory()->create([
            'user_id'     => $customer->id,
            'title'       => 'Order not appearing in my account',
            'description' => 'I completed checkout and received a confirmation email but the order is not visible in my account dashboard.',
            'status'      => TicketStatus::InProcess,
            'assigned_to' => $agent->id,
        ]);

        Ticket::factory()->create([
            'user_id'     => $wholesale->id,
            'title'       => 'Wholesale pricing not applied at checkout',
            'description' => 'My wholesale account is active but I am seeing retail prices during checkout. Please advise.',
            'status'      => TicketStatus::Assigned,
            'assigned_to' => $admin->id,
        ]);

        TicketReply::factory()->create([
            'ticket_id' => Ticket::first()->id,
            'user_id'   => $admin->id,
            'via'       => 'web',
        ]);

        // ---------------------------------------------------------------
        // Subseeders
        // ---------------------------------------------------------------
        $this->call([
            KbCategorySeeder::class,
            KbArticleSeeder::class,
            CmsSettingsSeeder::class,
            ShippingSeeder::class,
            PluginSeeder::class,
            CmsFormSeeder::class,
            CmsBuilderBlockSeeder::class,
            TestimonialSeeder::class,
        ]);

        // ---------------------------------------------------------------
        // Checkout processors (required config — no orders seeded)
        // ---------------------------------------------------------------
        DB::table('order_checkout_options')->insert([
            'primary_processor'   => 0,
            'secondary_processor' => 1,
            'tertiary_processor'  => 2,
            'randomize_processor' => 0,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        DB::table('order_processors')->insert([
            ['processor_id' => 0, 'processor_name' => 'Test Payment Gateway (Success/Failure)', 'production' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['processor_id' => 1, 'processor_name' => 'Stripe Payments',                        'production' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['processor_id' => 2, 'processor_name' => 'Paddle Billing',                         'production' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['processor_id' => 3, 'processor_name' => 'PayPal Payments',                        'production' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ---------------------------------------------------------------
        // Core CMS pages (static / legal)
        // ---------------------------------------------------------------
        DB::table('cms_pages')->insert([
            [
                'id'               => 1,
                'title'            => 'Home',
                'slug'             => 'home',
                'content'          => '[plugin:slideshow-2026]

<div class="py-12 bg-white dark:bg-slate-800/80 border-y border-slate-100 dark:border-slate-700/60 shadow-sm mb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-indigo-100 dark:border-indigo-900 bg-indigo-50/80 dark:bg-indigo-950/50 text-xs font-bold text-indigo-600 dark:text-indigo-400">
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
            Premier E-Commerce Storefront &amp; Customer Support Platform
        </div>
        
        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white max-w-4xl mx-auto leading-tight">
            Discover Quality Products &amp; Unmatched Customer Care
        </h1>
        
        <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg max-w-3xl mx-auto leading-relaxed">
            Welcome to our digital storefront! Explore our curated catalog of physical goods, digital downloads, exclusive wholesale pricing, and instant multi-channel customer assistance.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 pt-2">
            <a href="/shop" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">
                <span>Explore Catalog</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="#categories" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-extrabold text-sm transition-all">
                <span>Browse Categories</span>
            </a>
        </div>

        <div class="pt-8 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-5xl mx-auto text-left">
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v1a2 2 0 01-2 2M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">Fast Shipping</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Reliable fulfillment</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">100% Quality</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Guaranteed products</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">Instant Access</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Digital downloads</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 dark:bg-violet-900/50 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-slate-100">Dedicated Support</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">24/7 Ticketing Help</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    [plugin:brands-2026 display=slider header="Featured Manufacturing Brands" cols=5 autoplay=on]
</div>

<div id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
    [plugin:categories-2026 display=grid header="Shop By Top Categories" cols=4]
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    [plugin:featured-items display=slider header="Featured Products &amp; Best Sellers" max=8]
</div>',
                'meta_title'       => 'Welcome',
                'meta_description' => 'An integrated e-commerce and customer support platform with secure downloads, a CMS, shortcodes, and a knowledge base.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 0,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 2,
                'title'            => 'About Us',
                'slug'             => 'about-us',
                'content'          => '<h2>About This Platform</h2><p>This is a fully integrated e-commerce and customer support platform built with Laravel, Livewire, and Alpine.js. It combines a feature-rich online store with a ticketing system, knowledge base, and a flexible CMS — all managed from a single admin interface.</p><p>The platform supports physical and digital products, wholesale pricing, multi-processor checkout, secure file downloads, reusable embed shortcodes, and email-based ticket replies — all out of the box.</p>',
                'meta_title'       => 'About This Platform',
                'meta_description' => 'Learn about this integrated e-commerce and support platform built with Laravel and Livewire.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 1,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 3,
                'title'            => 'Contact Us',
                'slug'             => 'contact',
                'content'          => '<h2>Get In Touch</h2><p>Have questions about your order or need assistance? We are here to help.</p><p>Submit a support ticket by logging in to your account dashboard. Our team typically responds within one business day. You can also reply to ticket notification emails directly — no need to log back in.</p>',
                'meta_title'       => 'Contact Support',
                'meta_description' => 'Get in touch with our customer service team or submit a support ticket.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 1,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 4,
                'title'            => 'Privacy Policy',
                'slug'             => 'privacy',
                'content'          => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This policy describes how we collect, use, and protect your personal information when you use our services.</p><p>We use your email address and profile details solely to handle authentication, process orders, and send support ticket notifications. We do not sell or share your personal data with third parties except as required to fulfil your orders (e.g. payment processors).</p>',
                'meta_title'       => 'Privacy Policy',
                'meta_description' => 'Our privacy policy covering user data, order processing, and ticket notifications.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 1,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 5,
                'title'            => 'Terms of Service',
                'slug'             => 'terms',
                'content'          => '<h2>Terms of Service</h2><p>By accessing or using this platform you agree to comply with and be bound by these terms.</p><p>Physical goods may be returned within 30 days of purchase if unused and in original condition. Digital products and downloadable files are delivered immediately upon order confirmation and are non-refundable unless a technical delivery failure occurred. Please open a support ticket if you experience any download issues.</p>',
                'meta_title'       => 'Terms of Service',
                'meta_description' => 'Terms of service governing purchases, digital downloads, and support ticket policies.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 1,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 6,
                'title'            => 'Refund Policy',
                'slug'             => 'refunds',
                'content'          => '<h2>Refund Policy</h2><p>We stand behind the quality of everything we sell. Physical goods can be returned within 30 days of delivery if the item is unused and in its original packaging — simply open a support ticket to start the process.</p><p>For digital goods and downloadable files, refunds are evaluated on a case-by-case basis. If you experience a technical issue preventing download or playback, please open a support ticket immediately and we will resolve it promptly.</p>',
                'meta_title'       => 'Refund &amp; Return Policy',
                'meta_description' => 'Our 30-day return policy for physical goods and digital download support procedures.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 1,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'id'               => 7,
                'title'            => 'Search Results',
                'slug'             => 'search',
                'content'          => '[plugin:live-search-2026 mode=results]',
                'meta_title'       => 'Search Results',
                'meta_description' => 'Multi-content search results across products, CMS pages, knowledge base articles, and customer testimonials.',
                'author_id'        => 1,
                'layout_type'      => 1,
                'show_title'       => 0,
                'show_author'      => 0,
                'show_date'        => 0,
                'is_active'        => 1,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ]);

        // ---------------------------------------------------------------
        // CMS Blog category + tags
        // ---------------------------------------------------------------
        $blogCategoryId = DB::table('cms_pages_categories')->insertGetId([
            'name'       => 'Blog',
            'slug'       => 'blog',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $laravelTagId = DB::table('cms_pages_tags')->insertGetId([
            'name' => 'Laravel', 'slug' => 'laravel', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $livewireTagId = DB::table('cms_pages_tags')->insertGetId([
            'name' => 'Livewire', 'slug' => 'livewire', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $tutorialTagId = DB::table('cms_pages_tags')->insertGetId([
            'name' => 'Tutorial', 'slug' => 'tutorial', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $newsTagId = DB::table('cms_pages_tags')->insertGetId([
            'name' => 'News', 'slug' => 'news', 'created_at' => now(), 'updated_at' => now(),
        ]);
        $ecomTagId = DB::table('cms_pages_tags')->insertGetId([
            'name' => 'E-Commerce', 'slug' => 'ecommerce', 'created_at' => now(), 'updated_at' => now(),
        ]);

        // ---------------------------------------------------------------
        // Sample blog posts — app-relevant content
        // ---------------------------------------------------------------
        $postsData = [
            [
                'title'          => 'Platform Overview: What This Application Does',
                'slug'           => 'blog/platform-overview',
                'content'        => '<h2>A complete e-commerce and support solution</h2><p>This platform combines a fully featured online store with an integrated customer support ticketing system, a knowledge base, and a powerful CMS — all in one Laravel application.</p><p>Admins manage products, variants, inventory, orders, shipping, discounts, and customer accounts from a unified admin panel. The CMS handles blog posts, static pages, downloads, reusable code embeds, and list menus — all with shortcode support so content stays clean inside the TinyMCE editor.</p>',
                'meta_title'     => 'Platform Overview: What This Application Does',
                'meta_description' => 'A complete overview of the integrated e-commerce and customer support platform.',
                'created_at'     => now()->subMonths(10),
                'custom_sorting' => 5.0,
                'tags'           => [$newsTagId, $laravelTagId],
            ],
            [
                'title'          => 'CMS Shortcodes: How Pages and Products Share Content',
                'slug'           => 'blog/cms-shortcodes-explained',
                'content'        => '<h2>Shortcodes make content reusable and safe</h2><p>The platform supports a rich shortcode system that lets editors insert dynamic content — pages, products, categories, brands, list menus, file downloads, and reusable code/video embeds — using simple tags like <code>[page:2]</code>, <code>[download:1]</code>, or <code>[code-embed:3]</code>.</p><p>Shortcodes are processed by a two-pipeline architecture: Pipeline A (global middleware) resolves list menus and navigation on every public HTML response, while Pipeline B (ContentParserService) resolves downloads, embeds, and plugins inside specific content fields. This means TinyMCE never sees the raw HTML — it only stores the shortcode string.</p>',
                'meta_title'     => 'CMS Shortcodes: How Pages and Products Share Content',
                'meta_description' => 'An explanation of the two-pipeline shortcode system used for CMS pages, products, downloads, and embeds.',
                'created_at'     => now()->subMonths(7),
                'custom_sorting' => 4.0,
                'tags'           => [$laravelTagId, $tutorialTagId],
            ],
            [
                'title'          => 'Secure File Downloads with Local, CDN, and S3 Support',
                'slug'           => 'blog/secure-file-downloads',
                'content'        => '<h2>Four delivery modes, one download shortcode</h2><p>The CMS Downloads system supports four file source types: local disk storage, direct CDN URL, S3 via environment credentials, and custom per-file S3 credentials. Files are served securely through the download controller, and active/expiry checks prevent unauthorized access.</p><p>For video and audio files, the platform automatically renders a Video.js player when Force Download is not enabled. PDF and image files can be displayed inline or downloaded. A configurable file-type icon system (Vivid, Classic, or Square style) shows file format badges on download links.</p>',
                'meta_title'     => 'Secure File Downloads: Local, CDN, and S3',
                'meta_description' => 'How the CMS Downloads feature delivers files securely via local storage, CDN, or S3 with inline video/audio playback.',
                'created_at'     => now()->subMonths(4),
                'custom_sorting' => 3.0,
                'tags'           => [$tutorialTagId, $ecomTagId],
            ],
            [
                'title'          => 'Email-Based Ticket Replies: How It Works',
                'slug'           => 'blog/email-ticket-replies',
                'content'        => '<h2>Reply to tickets directly from your inbox</h2><p>When a support agent updates a ticket, the customer receives an email notification containing a unique reply token embedded in the subject line. The customer can reply directly to that email — no login required — and the response is automatically parsed and appended to the correct ticket thread.</p><p>The reply parser extracts text above the reply-delimiter line, validates the token against the ticket, strips email signatures, and stores the reply as a standard TicketReply record. Agents can also reply via email and have their responses attributed correctly.</p>',
                'meta_title'     => 'Email-Based Ticket Replies: How It Works',
                'meta_description' => 'How the platform parses inbound email replies and appends them to the correct support ticket thread.',
                'created_at'     => now()->subMonths(1),
                'custom_sorting' => 2.0,
                'tags'           => [$tutorialTagId],
            ],
            [
                'title'          => 'Reusable Code & Video Embeds in the CMS',
                'slug'           => 'blog/reusable-code-video-embeds',
                'content'        => '<h2>Store embed code once, use it everywhere</h2><p>The Code & Video Embed Manager lets admins save HTML or video embed snippets (YouTube, Vimeo, or any raw HTML) in a central library. Each embed is assigned an ID and can be inserted anywhere using the shortcode <code>[code-embed:{id}]</code>.</p><p>YouTube and Vimeo embeds are automatically wrapped in a responsive 16:9 container so they scale correctly on all devices. Raw HTML embeds are output verbatim. Because the embed code is stored in the database and rendered at request time, the TinyMCE editor never sees or modifies it — solving the common problem of editors stripping or reformatting iframes and custom widgets.</p>',
                'meta_title'     => 'Reusable Code & Video Embeds in the CMS',
                'meta_description' => 'How the Code Embed Manager stores reusable HTML and video snippets and inserts them via shortcode without TinyMCE corruption.',
                'created_at'     => now()->subWeeks(2),
                'custom_sorting' => 1.0,
                'tags'           => [$newsTagId, $livewireTagId],
            ],
        ];

        foreach ($postsData as $p) {
            $pId = DB::table('cms_pages')->insertGetId([
                'title'            => $p['title'],
                'slug'             => $p['slug'],
                'content'          => $p['content'],
                'meta_title'       => $p['meta_title'],
                'meta_description' => $p['meta_description'],
                'author_id'        => 1,
                'custom_author'    => 'Site Manager',
                'show_author'      => 1,
                'show_date'        => 1,
                'layout_type'      => 1,
                'page_type'        => 2, // Post type
                'hide_page_ranking' => 0,
                'page_ranking'     => 0,
                'custom_sorting'   => $p['custom_sorting'],
                'is_active'        => 1,
                'created_at'       => $p['created_at'],
                'updated_at'       => now(),
            ]);

            DB::table('cms_page_category')->insert([
                'cms_page_id' => $pId,
                'category_id' => $blogCategoryId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            foreach ($p['tags'] as $tId) {
                DB::table('cms_page_tag')->insert([
                    'cms_page_id' => $pId,
                    'tag_id'      => $tId,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
