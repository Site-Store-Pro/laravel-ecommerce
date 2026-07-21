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
        $admin = User::factory()->create([
            'name'              => 'Support Admin',
            'email'             => 'admin@support.local',
            'password'          => Hash::make('SampleUser12345#'),
            'role_id'           => UserRole::Admin->value,
            'email_verified_at' => now(),
        ]);

        // Order processor / ticket manager
        $agent = User::factory()->create([
            'name'              => 'Order Processor',
            'email'             => 'orders@support.local',
            'password'          => Hash::make('SampleUser12345#'),
            'role_id'           => UserRole::OrderProcessor->value,
            'email_verified_at' => now(),
        ]);

        // Sample retail customer
        $customer = User::factory()->create([
            'name'              => 'Sample Customer',
            'email'             => 'customer@example.local',
            'password'          => Hash::make('SampleUser12345#'),
            'role_id'           => UserRole::User->value,
            'email_verified_at' => now(),
        ]);

        // Sample wholesale customer
        $wholesale = User::factory()->create([
            'name'              => 'Wholesale Buyer',
            'email'             => 'wholesale@example.local',
            'password'          => Hash::make('SampleUser12345#'),
            'role_id'           => UserRole::Wholesale->value,
            'email_verified_at' => now(),
        ]);

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
                'title'            => 'Welcome',
                'slug'             => 'home',
                'content'          => '<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
                    <div class="space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-indigo-100 bg-indigo-50/50 text-xs font-semibold text-indigo-600">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                            Integrated E-Commerce &amp; Support Platform
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-6xl max-w-4xl mx-auto leading-tight">
                            {{ $heroTitle }}
                        </h1>
                        <div class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto leading-relaxed">
                            {!! $heroContent !!}
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route(\'shop.index\') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-violet-600 px-8 py-4 text-base font-semibold text-white shadow-xl shadow-indigo-100 hover:opacity-95 transition-all hover:-translate-y-0.5 active:translate-y-0">
                            Browse Store
                            <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </a>
                        @auth
                            <a href="{{ route(\'dashboard\') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-8 py-4 text-base font-semibold text-slate-700 hover:bg-slate-50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                                My Support Tickets
                                <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </a>
                        @else
                            <a href="{{ route(\'register\') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-8 py-4 text-base font-semibold text-slate-700 hover:bg-slate-50 transition-all hover:-translate-y-0.5 active:translate-y-0">
                                Create Account
                            </a>
                        @endauth
                    </div>

                    <!-- Feature grid -->
                    <div class="pt-20 grid gap-8 sm:grid-cols-2 lg:grid-cols-4 max-w-6xl mx-auto text-left">
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50/50 transition-colors shadow-sm group">
                            <span class="inline-flex items-center justify-center p-3 rounded-xl bg-indigo-50 text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Integrated Shop</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">Sell physical and digital products with variants, wholesale pricing, inventory tracking, and multi-processor checkout.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50/50 transition-colors shadow-sm group">
                            <span class="inline-flex items-center justify-center p-3 rounded-xl bg-indigo-50 text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Reply via Email</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">Respond to support ticket updates directly from your email client — no login required.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50/50 transition-colors shadow-sm group">
                            <span class="inline-flex items-center justify-center p-3 rounded-xl bg-indigo-50 text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Secure Downloads</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">Deliver digital files securely via local storage, CDN URL, or S3 — with Video.js inline playback and force-download support.</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 hover:bg-slate-50/50 transition-colors shadow-sm group">
                            <span class="inline-flex items-center justify-center p-3 rounded-xl bg-indigo-50 text-indigo-600 mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">Powerful CMS</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">Build pages with a flexible shortcode system, reusable code embeds, list menus, and a full knowledge base — all TinyMCE powered.</p>
                        </div>
                    </div>
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
