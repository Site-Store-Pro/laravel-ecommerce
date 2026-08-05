<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CmsFormSubmissionController;
use App\Http\Controllers\ContentAccessController;
use App\Http\Controllers\InboundEmailController;
use App\Http\Controllers\TicketAttachmentController;
use App\Livewire\AdminCreateUser;
use App\Livewire\AdminDashboard;
use App\Livewire\AdminEditUser;
use App\Livewire\AdminKbCategories;
use App\Livewire\AdminKbCreate;
use App\Livewire\AdminKbEdit;
use App\Livewire\AdminKbIndex;
use App\Livewire\AdminTicketShow;
use App\Livewire\AdminUsers;
use App\Livewire\CreateTicket;
use App\Livewire\KbArticleShow;
use App\Livewire\KbLanding;
use App\Livewire\PublicTicketView;
use App\Livewire\ShowTicket;
use App\Livewire\PostCartCrossSell;
use App\Livewire\AdminCheckoutProcessors;
use App\Livewire\AdminNavMenus;
use App\Livewire\AdminNavMenuEdit;
use App\Livewire\UserDashboard;
use App\Livewire\AdminPlugins;
use App\Livewire\AdminSiteLabels;

use App\Http\Controllers\PluginApiController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home');

Route::get('tickets/view/{token}', PublicTicketView::class)->name('tickets.public');

// Public form submission — no auth required
Route::post('forms/{slug}/submit', [CmsFormSubmissionController::class, 'submit'])->name('forms.submit');

Route::post('webhooks/inbound-email', InboundEmailController::class)
    ->name('webhooks.inbound-email');

Route::get('kb', KbLanding::class)->name('kb.index');
Route::get('kb/{seo_link}', KbArticleShow::class)->name('kb.show');

Route::get('shop', \App\Livewire\ShopCatalog::class)->name('shop.index');
Route::get('items/{seo_link}', \App\Livewire\ProductDetails::class)->name('shop.product');
Route::get('section/{category_slug}', \App\Livewire\ShopCatalog::class)->name('shop.category');
Route::get('brands/{brand_slug}', \App\Livewire\ShopCatalog::class)->name('shop.brand');
Route::get('cart', \App\Livewire\ShoppingCart::class)->name('shop.cart');
Route::get('/shop/post-cart/{variantId}', PostCartCrossSell::class)->name('shop.post-cart');
Route::get('checkout', \App\Livewire\Checkout::class)->name('shop.checkout');
Route::get('checkout/review', \App\Livewire\OrderReview::class)->name('shop.checkout-review');
Route::get('checkout/success/{external_id}', \App\Livewire\CheckoutSuccess::class)->name('shop.checkout-success');
Route::get('downloads/{orderDetail}/{token}', [\App\Http\Controllers\ProductDownloadController::class, 'download'])->name('products.download');
Route::get('cms-download/{uuid}', [\App\Http\Controllers\CmsDownloadController::class, 'serve'])->name('cms.download')->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::get('api/live-search-api', [PluginApiController::class, 'liveSearchApi'])->name('api.live-search');

// Secure content access token redemption (no auth required — supports guest purchasers)
Route::get('content-access/{token}', [ContentAccessController::class, 'redeem'])->name('content.access');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard bypasses 'verified' so guests reach mount() first (where they are redirected
    // to /account/set-password before the email verification check ever runs).
    Route::get('dashboard', UserDashboard::class)->name('dashboard')->withoutMiddleware('verified');

    // Guest account conversion — set a password (auth but NOT verified required)
    Route::get('account/set-password', \App\Livewire\GuestSetPassword::class)->name('guest.set-password')->withoutMiddleware('verified');

    Route::get('tickets/create', CreateTicket::class)->name('tickets.create');
    Route::get('tickets/{ticket}', ShowTicket::class)->name('tickets.show');
    Route::get('tickets/{ticket}/attachments/{attachment}', [TicketAttachmentController::class, 'download'])
        ->name('tickets.attachments.download');

    // Ticket Manager Group (Admin + Ticket Manager)
    Route::middleware('ticket_manager')->prefix('admin')->name('admin.')->group(function () {
        Route::get('tickets', AdminDashboard::class)->name('tickets');
        Route::get('tickets/{ticket}', AdminTicketShow::class)->name('tickets.show');
        Route::get('assigned-tickets', \App\Livewire\TeamMember\Tickets::class)->name('assigned-tickets');
    });

    // Order Processor Group (Admin + Order Processor)
    Route::middleware('order_processor')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', \App\Livewire\AdminDashboardHome::class)->name('dashboard');
        Route::get('pending-orders', \App\Livewire\AdminPendingOrders::class)->name('ecommerce.pending-orders');
        Route::get('ecommerce/products', \App\Livewire\AdminProducts::class)->name('ecommerce.products');
        Route::get('ecommerce/products/{id}/edit', \App\Livewire\AdminProductEdit::class)->name('ecommerce.product-edit');
        Route::get('ecommerce/orders', \App\Livewire\AdminOrders::class)->name('ecommerce.orders');
        Route::get('ecommerce/orders/{id}', \App\Livewire\AdminOrderDetails::class)->name('ecommerce.order-details');
        Route::get('ecommerce/reviews', \App\Livewire\AdminProductReviews::class)->name('ecommerce.reviews');
        Route::post('cms-pages/upload-image', [\App\Http\Controllers\CmsImageUploadController::class, 'upload'])->name('cms-pages.upload-image');
    });
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('ecommerce/import', \App\Livewire\AdminProductImport::class)->name('ecommerce.import'); // Admin-only: bulk import
        Route::get('users', AdminUsers::class)->name('users');
        Route::get('users/create', AdminCreateUser::class)->name('users.create');
        Route::get('users/{user}', \App\Livewire\AdminUserShow::class)->name('users.show');
        Route::get('users/{user}/edit', AdminEditUser::class)->name('users.edit');

        Route::get('plugins', AdminPlugins::class)->name('plugins.index');
        Route::get('plugins/list-display', [PluginApiController::class, 'listDisplay'])->name('plugins.list-display');

        Route::get('kb', AdminKbIndex::class)->name('kb.index');
        Route::get('kb/categories', AdminKbCategories::class)->name('kb.categories');
        Route::get('kb/create', AdminKbCreate::class)->name('kb.create');
        Route::get('kb/{article}/edit', AdminKbEdit::class)->name('kb.edit');

        // E-commerce Admin-Only Routes
        Route::get('ecommerce/categories', \App\Livewire\AdminEcommerceCategories::class)->name('ecommerce.categories');
        Route::get('ecommerce/brands', \App\Livewire\AdminEcommerceBrands::class)->name('ecommerce.brands');
        Route::get('ecommerce/inventory', \App\Livewire\AdminInventory::class)->name('ecommerce.inventory');
        Route::get('ecommerce/shipping', \App\Livewire\AdminShippingSettings::class)->name('ecommerce.shipping');
        Route::get('cms-pages', \App\Livewire\AdminCmsPages::class)->name('cms-pages.index');
        Route::get('cms-pages/create', \App\Livewire\AdminCmsPageEdit::class)->name('cms-pages.create');
        Route::get('cms-pages/{id}/edit', \App\Livewire\AdminCmsPageEdit::class)->name('cms-pages.edit');
        Route::get('cms-categories', \App\Livewire\AdminCmsCategories::class)->name('cms-categories.index');
        Route::get('cms-tags', \App\Livewire\AdminCmsTags::class)->name('cms-tags.index');
        // CMS Slideshows
        Route::get('cms-slideshows', \App\Livewire\AdminSlideshows::class)->name('cms-slideshows.index');
        Route::get('cms-slideshows/{id}/edit', \App\Livewire\AdminSlideshowEdit::class)->name('cms-slideshows.edit');
        // CMS List Menus
        Route::get('cms-list-menus', \App\Livewire\AdminCmsListMenus::class)->name('cms-list-menus.index');
        Route::get('cms-list-menus/{id}/edit', \App\Livewire\AdminCmsListMenuEdit::class)->name('cms-list-menus.edit');
        // CMS Downloads
        Route::get('cms-downloads', \App\Livewire\AdminCmsDownloads::class)->name('cms-downloads.index');
        Route::get('cms-downloads/create', \App\Livewire\AdminCmsDownloadEdit::class)->name('cms-downloads.create');
        Route::get('cms-downloads/{id}/edit', \App\Livewire\AdminCmsDownloadEdit::class)->name('cms-downloads.edit');
        // CMS Code Embeds
        Route::get('cms-embeds', \App\Livewire\AdminCmsEmbeds::class)->name('cms-embeds.index');
        Route::get('cms-embeds/create', \App\Livewire\AdminCmsEmbedEdit::class)->name('cms-embeds.create');
        Route::get('cms-embeds/{id}/edit', \App\Livewire\AdminCmsEmbedEdit::class)->name('cms-embeds.edit');
        // CMS Forms
        Route::get('cms-forms', \App\Livewire\AdminCmsForms::class)->name('cms-forms.index');
        Route::get('cms-forms/create', \App\Livewire\AdminCmsFormEdit::class)->name('cms-forms.create');
        Route::get('cms-forms/{id}/edit', \App\Livewire\AdminCmsFormEdit::class)->name('cms-forms.edit');
        Route::get('cms-forms/{formId}/submissions', \App\Livewire\AdminCmsFormSubmissions::class)->name('cms-forms.submissions');
        // Discounts
        Route::get('discounts', \App\Livewire\AdminDiscounts::class)->name('discounts.index');
        Route::get('discounts/create', \App\Livewire\AdminDiscountEdit::class)->name('discounts.create');
        Route::get('discounts/{id}/edit', \App\Livewire\AdminDiscountEdit::class)->name('discounts.edit');
        Route::get('discounts/config', \App\Livewire\AdminDiscountConfig::class)->name('discounts.config');

        // Email Templates
        Route::get('email-templates', \App\Livewire\AdminEmailTemplates::class)->name('email-templates.index');
        Route::get('email-templates/create', \App\Livewire\AdminEmailTemplateEdit::class)->name('email-templates.create');
        Route::get('email-templates/{id}/edit', \App\Livewire\AdminEmailTemplateEdit::class)->name('email-templates.edit');

        // Settings
        Route::get('settings', \App\Livewire\AdminSettings::class)->name('settings');
        Route::get('ecommerce/checkout/processors', AdminCheckoutProcessors::class)->name('ecommerce.checkout.processors');

        // Navigation Builder
        Route::get('nav-builder', AdminNavMenus::class)->name('nav-builder.index');
        Route::get('nav-builder/{menu}/edit', AdminNavMenuEdit::class)->name('nav-builder.edit');

        // Header & Footer Layout Builder
        Route::get('cms-header-footer', \App\Livewire\AdminHeaderFooterBuilder::class)->name('cms-header-footer.index');
        Route::get('cms-header-footer/preview', \App\Http\Controllers\Admin\HeaderFooterPreviewController::class)->name('cms-header-footer.preview');

        // Testimonials
        Route::get('testimonials', \App\Livewire\AdminTestimonialsManager::class)->name('testimonials.index');

        // ── Inventory Alert Messages ──────────────────────────────────────────────
        Route::get('inventory-alerts', \App\Livewire\AdminInventoryAlerts::class)->name('inventory-alerts.index');

        // ── FAQ Manager ───────────────────────────────────────────────────────────
        Route::get('faqs', \App\Livewire\AdminFaqs::class)->name('faqs.index');

        // ── Modal Manager ──────────────────────────────────────────────────────────
        Route::get('modals', \App\Livewire\AdminModalIndex::class)->name('modals.index');
        Route::get('modals/create', \App\Livewire\AdminModalEdit::class)->name('modals.create');
        Route::get('modals/{id}/edit', \App\Livewire\AdminModalEdit::class)->name('modals.edit');

        // Site Labels (dynamic text overrides)
        Route::get('site-labels', AdminSiteLabels::class)->name('site-labels.index');

        // ── Language Manager ──────────────────────────────────────────────────────
        Route::get('languages', \App\Livewire\AdminLanguages::class)->name('languages.index');
        Route::get('languages/{languageId}/translations', \App\Livewire\AdminLanguageTranslations::class)->name('languages.translations');
        Route::get('languages/queue-monitor', \App\Livewire\AdminQueueMonitor::class)->name('languages.queue-monitor');

    });
});

Route::get('category/{slug}', [\App\Http\Controllers\CmsCategoryPageController::class, 'show'])->name('cms.category');
Route::get('tag/{slug}', [\App\Http\Controllers\CmsTagPageController::class, 'show'])->name('cms.tag');

// Language switch (handled by Livewire component action, but provide a fallback GET route)
Route::get('/set-language/{code}', function (string $code) {
    app(\App\Services\LanguageService::class)->setLanguage($code);
    return redirect()->back();
})->name('language.switch');

Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});

Route::view('profile', 'user.profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::post('page-unlock/{id}', [\App\Http\Controllers\PageController::class, 'unlock'])->name('page.unlock');
Route::post('webhooks/inventory-update', [\App\Http\Controllers\InventoryWebhookController::class, 'update']);
Route::post('webhooks/stripe',  [\App\Http\Controllers\StripeWebhookController::class,  'handle'])->name('webhooks.stripe');
Route::post('webhooks/paddle',  [\App\Http\Controllers\PaddleWebhookController::class,  'handle'])->name('webhooks.paddle');

Route::get('{slug}', [\App\Http\Controllers\PageController::class, 'show'])->where('slug', '.*')->name('page.show');
