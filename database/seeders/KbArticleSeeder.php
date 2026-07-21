<?php

namespace Database\Seeders;

use App\Models\KbArticle;
use Illuminate\Database\Seeder;

class KbArticleSeeder extends Seeder
{
    public function run(): void
    {
        KbArticle::truncate();

        $nowStr = now()->format('Y-m-d H:i:s');

        // ---------------------------------------------------------------
        // Getting Started (category_id = 1)
        // ---------------------------------------------------------------
        KbArticle::create([
            'title'            => 'Creating Your Account and Logging In',
            'meta_description' => 'How to register for an account, verify your email, and log in to the platform.',
            'seo_link'         => 'creating-your-account',
            'category_id'      => 1,
            'article_content'  => '<p>Getting started is straightforward. Follow the steps below to create your account and access the customer dashboard.</p>
<h2>Step 1 — Register</h2>
<ol>
<li>Click <strong>Create Account</strong> on the home page or navigate to <code>/register</code>.</li>
<li>Enter your name, email address, and a strong password.</li>
<li>Click <strong>Register</strong>.</li>
</ol>
<h2>Step 2 — Verify Your Email</h2>
<p>After registering you will receive a verification email. Click the link inside it to activate your account. Check your spam folder if it does not arrive within a few minutes.</p>
<h2>Step 3 — Log In</h2>
<p>Once verified, navigate to <code>/login</code>, enter your credentials, and you will be taken to your account dashboard where you can view orders, track support tickets, and download purchased digital files.</p>
<blockquote><p><strong>Tip:</strong> If you forget your password, use the <strong>Forgot Password</strong> link on the login page to receive a reset email.</p></blockquote>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        KbArticle::create([
            'title'            => 'Understanding User Roles: Customer vs Wholesale',
            'meta_description' => 'Explains the difference between standard customer accounts and wholesale buyer accounts, and what pricing each sees.',
            'seo_link'         => 'user-roles-customer-vs-wholesale',
            'category_id'      => 1,
            'article_content'  => '<p>This platform supports two customer-facing account types: <strong>Customer</strong> and <strong>Wholesale</strong>. Both can browse the store, place orders, and submit support tickets — but they see different pricing.</p>
<h2>Standard Customer</h2>
<p>A standard customer account sees the public retail price (<code>public_price</code>) on all products. This is the default account type when registering.</p>
<h2>Wholesale Buyer</h2>
<p>Wholesale accounts are assigned by an administrator. Once activated, these accounts see the <code>wholesale_price</code> on all products and variants at checkout.</p>
<h2>Requesting a Wholesale Account</h2>
<p>To request wholesale access, submit a support ticket under the <strong>Account</strong> department, include your business name and expected order volume, and an admin will upgrade your account.</p>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        // ---------------------------------------------------------------
        // Orders & Shop (category_id = 2)
        // ---------------------------------------------------------------
        KbArticle::create([
            'title'            => 'How to Place an Order and Check Out',
            'meta_description' => 'Step-by-step guide to browsing products, adding items to your cart, and completing checkout.',
            'seo_link'         => 'how-to-place-an-order',
            'category_id'      => 2,
            'article_content'  => '<p>Purchasing from the store is quick and straightforward.</p>
<h2>Browse and Add to Cart</h2>
<ol>
<li>Click <strong>Browse Store</strong> from the home page or navigate to <code>/shop</code>.</li>
<li>Select a product and choose any required variants (size, colour, format, etc.).</li>
<li>Click <strong>Add to Cart</strong>.</li>
<li>Continue shopping or click the cart icon to review your order.</li>
</ol>
<h2>Checkout</h2>
<ol>
<li>Click <strong>Checkout</strong> from the cart.</li>
<li>Enter your shipping address (for physical goods) or confirm your email for digital items.</li>
<li>Select a payment method and enter your payment details.</li>
<li>Click <strong>Place Order</strong>.</li>
</ol>
<p>You will receive an order confirmation email immediately. Digital products become available for download in your account dashboard as soon as payment is confirmed.</p>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        KbArticle::create([
            'title'            => 'Downloading Digital Products After Purchase',
            'meta_description' => 'How to access and download digital files, PDFs, and other downloadable products from your account after purchase.',
            'seo_link'         => 'downloading-digital-products',
            'category_id'      => 2,
            'article_content'  => '<p>After purchasing a digital product, your download is available immediately from your account dashboard.</p>
<h2>Accessing Downloads</h2>
<ol>
<li>Log in to your account and go to <strong>My Orders</strong>.</li>
<li>Locate the relevant order and click <strong>View Order</strong>.</li>
<li>Click the <strong>Download</strong> button next to the digital item.</li>
</ol>
<p>Some digital items (video or audio files) will play directly in a media player on the product or CMS page rather than triggering a download — this depends on how the admin has configured the item. If <strong>Force Download</strong> is enabled for a file, your browser will always prompt to save it rather than playing it inline.</p>
<h2>Download Expiry</h2>
<p>Download links may have an expiry date set by the admin. If your link has expired, please open a support ticket referencing your order number and we will regenerate access.</p>
<blockquote><p><strong>Having trouble?</strong> If your download fails or the file appears corrupted, disable any browser extensions and try again, or use a different browser. Still not working? Open a support ticket.</p></blockquote>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        // ---------------------------------------------------------------
        // Support Tickets (category_id = 3)
        // ---------------------------------------------------------------
        KbArticle::create([
            'title'            => 'How to Submit a Support Ticket',
            'meta_description' => 'Step-by-step instructions for opening a new support ticket from your account dashboard.',
            'seo_link'         => 'how-to-submit-a-support-ticket',
            'category_id'      => 3,
            'article_content'  => '<p>Our integrated support ticketing system lets you communicate directly with the support team about orders, account issues, or any other questions.</p>
<h2>Opening a Ticket</h2>
<ol>
<li>Log in and go to your <strong>Dashboard</strong>.</li>
<li>Click <strong>Open New Ticket</strong>.</li>
<li>Select the most relevant <strong>Department</strong> (e.g. Orders, Billing, Technical).</li>
<li>Enter a clear <strong>Subject</strong> and a detailed <strong>Description</strong>.</li>
<li>Attach any relevant screenshots or files (max 10MB each, up to 3 files).</li>
<li>Click <strong>Submit Ticket</strong>.</li>
</ol>
<p>You will receive an email confirmation with your ticket number. A support agent will review and respond as quickly as possible.</p>
<h2>Ticket Statuses</h2>
<ul>
<li><strong>Open</strong> — Submitted, awaiting first agent response.</li>
<li><strong>Assigned</strong> — Assigned to a specific agent.</li>
<li><strong>In Process</strong> — Agent is actively working on it.</li>
<li><strong>Completed</strong> — Issue resolved; ticket will close shortly.</li>
<li><strong>Closed</strong> — Ticket is archived.</li>
</ul>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        KbArticle::create([
            'title'            => 'Replying to Tickets via Email',
            'meta_description' => 'How to respond to support ticket updates directly from your email client without logging in to the dashboard.',
            'seo_link'         => 'replying-to-tickets-via-email',
            'category_id'      => 3,
            'article_content'  => '<p>When a support agent updates your ticket, you will receive an email notification. You can reply directly to that email and your response will be automatically added to the ticket thread — no login required.</p>
<h2>Important Rules for Email Replies</h2>
<h3>1. Do Not Change the Subject Line</h3>
<p>Our parser relies on the unique Ticket ID and Reply Token in the subject line (e.g. <code>[Ticket #1234] [Token: abc123xyz]</code>). Modifying or deleting the subject line will prevent the reply from being associated with your ticket.</p>
<h3>2. Reply from Your Registered Email Address</h3>
<p>Replies from unregistered email addresses are rejected for security reasons. Make sure you reply from the same address associated with your account.</p>
<h3>3. Keep Your Reply Above the Delimiter</h3>
<p>Type your response <strong>above</strong> this line:</p>
<pre><code>--- Reply ABOVE THIS LINE to update your ticket ---</code></pre>
<p>Anything below this marker is ignored by the parser, so email signatures and quoted history will not clutter your ticket.</p>
<blockquote><p>If email replies are not appearing on your ticket after 10 minutes, please log in and post your reply directly through the dashboard instead, then contact us so we can investigate the email delivery issue.</p></blockquote>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        // ---------------------------------------------------------------
        // Admin & CMS (category_id = 4)
        // ---------------------------------------------------------------
        KbArticle::create([
            'title'            => 'Using Shortcodes in CMS Pages and Product Descriptions',
            'meta_description' => 'A reference guide for all supported shortcodes including pages, products, downloads, embeds, and list menus.',
            'seo_link'         => 'using-shortcodes-in-cms',
            'category_id'      => 4,
            'article_content'  => '<p>Shortcodes let you insert dynamic content into CMS pages, product descriptions, and list menu items without writing HTML directly inside the TinyMCE editor.</p>
<h2>Supported Shortcodes</h2>
<table>
<thead><tr><th>Shortcode</th><th>Output</th></tr></thead>
<tbody>
<tr><td><code>[page:N]</code></td><td>Hyperlink to CMS page with ID N</td></tr>
<tr><td><code>[product:N]</code></td><td>Hyperlink to product with ID N</td></tr>
<tr><td><code>[category:N]</code></td><td>Hyperlink to product category with ID N</td></tr>
<tr><td><code>[brand:N]</code></td><td>Hyperlink to brand page with ID N</td></tr>
<tr><td><code>[list:N]</code></td><td>Renders list menu N as a styled &lt;ul&gt; block</td></tr>
<tr><td><code>[download:N]</code></td><td>Renders download link, inline image, or media player</td></tr>
<tr><td><code>[download:N label="..."]</code></td><td>Download with custom link label</td></tr>
<tr><td><code>[code-embed:N]</code></td><td>Renders saved HTML or video embed snippet</td></tr>
<tr><td><code>[plugin:slug]</code></td><td>Renders active plugin output</td></tr>
</tbody>
</table>
<h2>Where Shortcodes Work</h2>
<p>All shortcodes work in CMS page body columns (main, left sidebar, right sidebar) and in product short and long descriptions. The <code>[list:N]</code> shortcode is additionally resolved on every public HTML page by the global middleware pipeline.</p>
<h2>Finding IDs</h2>
<p>The shortcode generator drawer in the CMS page and product edit forms lets you search for records and copy shortcodes with one click — no need to look up IDs manually.</p>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);

        KbArticle::create([
            'title'            => 'Managing File Downloads in the CMS',
            'meta_description' => 'How to add, configure, and publish downloadable files using the CMS Downloads manager.',
            'seo_link'         => 'managing-file-downloads-in-cms',
            'category_id'      => 4,
            'article_content'  => '<p>The CMS Downloads manager lets you publish downloadable or inline files — documents, images, videos, and audio — that can be embedded anywhere via the <code>[download:N]</code> shortcode.</p>
<h2>File Sources</h2>
<ul>
<li><strong>Local</strong> — Upload directly to the server. Served through the secure download controller.</li>
<li><strong>Direct URL / CDN</strong> — Paste a public URL to an externally hosted file.</li>
<li><strong>S3 (Environment)</strong> — Uses the <code>AWS_*</code> credentials in your <code>.env</code> file. Generates a signed pre-signed URL with a configurable expiry.</li>
<li><strong>Custom S3</strong> — Per-download credentials for files stored in a different S3 bucket or account.</li>
</ul>
<h2>Rendering Behaviour</h2>
<ul>
<li><strong>Images</strong> — Rendered as an inline <code>&lt;img&gt;</code> tag.</li>
<li><strong>Video (MP4, WebM, MOV)</strong> — Rendered in a Video.js player (unless Force Download is checked).</li>
<li><strong>Audio (MP3)</strong> — Rendered in a Video.js audio player (unless Force Download is checked).</li>
<li><strong>Everything else</strong> — Rendered as a download link with an optional file-type icon badge.</li>
</ul>
<p>To always force a browser save-dialog instead of inline rendering, check the <strong>Force Download</strong> option when creating or editing the download record.</p>',
            'article_active'   => 1,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);
    }
}
