<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoStoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Demo Slideshows
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slideshows` (`slideshow_id`, `slideshow_name`, `slideshow_active`, `sort_order`, `slide_show_alignment`, `created_at`, `updated_at`) VALUES (1, 'Demo Home Page Slide Show', 1, 0, 'middle-center', '2026-08-09 14:35:22', '2026-08-09 14:35:22');
SQL
);

        DB::table('cms_slideshows')->where('slideshow_id', 1)->update(['is_demo' => 1]);

        // Demo Slides
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slides` (`id`, `Title`, `Description`, `SlideURL`, `LargeImage`, `Thumbnail`, `Active`, `ImageSort`, `slide_heading`, `slide_sub_heading`, `slide_content_css`, `slide_heading_css`, `slide_alignment`, `slide_callout_button_label`, `slideshow_id`, `mobile_image`, `cdn_image`, `cdn_mobile_image`, `cdn_thumbnail`, `cdn_image_width`, `cdn_image_height`, `cdn_mobile_image_height`, `cdn_mobile_image_width`, `image_s3`, `image_s3_region`, `image_s3_bucket`, `image_s3_key`, `image_s3_secret`, `cdn_url`, `created_at`, `updated_at`) VALUES (1, 'Shop Now', 'Shop Now', '/shop', NULL, NULL, 1, 1, 'Welcome To The Demo Store', 'Browse the demo products to learn about the app.', NULL, NULL, 'middle-center', 'Shop Now', 1, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-one.webp', 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-1a-mobile-dark.webp', 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-1a-mobile-dark.webp', 1920, 725, 500, 600, 0, NULL, NULL, NULL, NULL, NULL, '2026-08-09 14:43:03', '2026-08-10 22:44:35');
SQL
);

        // Demo Slides
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slides` (`id`, `Title`, `Description`, `SlideURL`, `LargeImage`, `Thumbnail`, `Active`, `ImageSort`, `slide_heading`, `slide_sub_heading`, `slide_content_css`, `slide_heading_css`, `slide_alignment`, `slide_callout_button_label`, `slideshow_id`, `mobile_image`, `cdn_image`, `cdn_mobile_image`, `cdn_thumbnail`, `cdn_image_width`, `cdn_image_height`, `cdn_mobile_image_height`, `cdn_mobile_image_width`, `image_s3`, `image_s3_region`, `image_s3_bucket`, `image_s3_key`, `image_s3_secret`, `cdn_url`, `created_at`, `updated_at`) VALUES (2, 'Rings', 'Rings', '[site_url]/section/rings', NULL, NULL, 1, 2, 'Shop Our Custom Ring Selection', 'Custom rings for any occasion. Engraving and sizing avaialble.', NULL, NULL, 'middle-center', 'Browse Rings', 1, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-two.webp', 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-sample2-mobile-dark.webp', 'https://d23w3zagfzgqcb.cloudfront.net/slides/slide-sample2-mobile-dark.webp', 1920, 725, 500, 600, 0, NULL, NULL, NULL, NULL, NULL, '2026-08-10 16:43:38', '2026-08-10 16:45:40');
SQL
);

        DB::table('cms_slides')->where('slideshow_id', 1)->update(['is_demo' => 1]);

        // Demo Slide Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slide_translations` (`id`, `cms_slide_id`, `language_id`, `slide_heading`, `slide_sub_heading`, `slide_callout_button_label`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (1, 1, 2, 'Bienvenido a la tienda de demostración', 'Explora los productos de demostración para aprender sobre la aplicación.', 'Compra Ahora', 'reviewed', '2026-08-10 16:58:15', '2026-08-10 16:28:29', '2026-08-10 16:58:15');
SQL
);

        // Demo Slide Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slide_translations` (`id`, `cms_slide_id`, `language_id`, `slide_heading`, `slide_sub_heading`, `slide_callout_button_label`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (2, 1, 3, 'Bienvenue au magasin de démonstration', 'Parcourez les produits de démonstration pour en savoir plus sur l\'application.', 'Achetez maintenant', 'ai_translated', '2026-08-10 17:24:10', '2026-08-10 16:58:21', '2026-08-10 17:24:11');
SQL
);

        // Demo Slide Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slide_translations` (`id`, `cms_slide_id`, `language_id`, `slide_heading`, `slide_sub_heading`, `slide_callout_button_label`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (3, 2, 2, 'Compra nuestra selección de anillos personalizados', 'Anillos personalizados para cualquier ocasión. Grabado y ajuste disponibles.', 'Explorar Anillos', 'reviewed', '2026-08-10 16:58:33', '2026-08-10 16:58:33', '2026-08-10 16:58:33');
SQL
);

        // Demo Slide Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_slide_translations` (`id`, `cms_slide_id`, `language_id`, `slide_heading`, `slide_sub_heading`, `slide_callout_button_label`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (4, 2, 3, 'Découvrez notre sélection de bagues personnalisées', 'Bagues personnalisées pour toutes les occasions. Gravure et ajustement disponibles.', 'Parcourir les bagues', 'reviewed', '2026-08-10 16:58:37', '2026-08-10 16:58:37', '2026-08-10 16:58:37');
SQL
);

        // Demo Testimonials
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_testimonials` (`id`, `author_name`, `author_title`, `content`, `avatar_image`, `rating`, `company_name`, `company_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES (1, 'Joan F.', 'Verified Buyer', 'This is a great shopping website! I\'ve ordered twice in the past and will order again in the future. Great price, great items, great customer service!', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80', 5, 'Fashion Weekly', NULL, 1, 1, '2026-07-22 12:49:40', '2026-07-26 22:52:19');
SQL
);

        // Demo Testimonials
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_testimonials` (`id`, `author_name`, `author_title`, `content`, `avatar_image`, `rating`, `company_name`, `company_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES (2, 'Mike P.', 'Regular Customer', 'Always has the best prices and fastest shipping. I highly recommend this company and will continue to use them for future orders.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80', 5, 'Apex Studios', NULL, 1, 2, '2026-07-22 12:49:40', '2026-07-22 12:49:40');
SQL
);

        // Demo Testimonials
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_testimonials` (`id`, `author_name`, `author_title`, `content`, `avatar_image`, `rating`, `company_name`, `company_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES (3, 'Terry Fisk', 'Business Owner', 'This is a great store website. They always have what I need for corporate gifts and seasonal promotions.', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80', 5, 'Aspire Properties', 'https://example.com', 1, 3, '2026-07-22 12:49:40', '2026-07-22 12:49:40');
SQL
);

        // Demo Testimonials
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `cms_testimonials` (`id`, `author_name`, `author_title`, `content`, `avatar_image`, `rating`, `company_name`, `company_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES (4, 'Matt Jones', 'Product Reviewer', 'An outstanding e-commerce platform offering top quality items with flawless customer support.', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80', 5, 'Tech Pulse', NULL, 1, 4, '2026-07-22 12:49:40', '2026-07-22 12:49:40');
SQL
);

        DB::table('cms_testimonials')->update(['is_demo' => 1]);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (1, 1, 2, '¡Este es un gran sitio web de compras! He pedido dos veces en el pasado y volveré a pedir en el futuro. ¡Gran precio, grandes artículos, gran servicio al cliente!', 'Comprador Verificado', NULL, NULL, 'ai_translated', '2026-08-10 16:12:57', '2026-08-05 19:52:43', '2026-08-10 16:12:59');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (2, 2, 2, 'Siempre tiene los mejores precios y el envío más rápido. Recomiendo encarecidamente esta empresa y continuaré usándola para futuros pedidos.', 'Cliente Regular', NULL, NULL, 'ai_translated', '2026-08-10 16:12:59', '2026-08-05 19:52:44', '2026-08-10 16:13:00');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (3, 3, 2, 'Este es un gran sitio web de tienda. Siempre tienen lo que necesito para regalos corporativos y promociones de temporada.', 'Propietario de Negocio', NULL, NULL, 'ai_translated', '2026-08-10 16:13:00', '2026-08-05 19:52:46', '2026-08-10 16:13:02');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (4, 4, 2, 'Una plataforma de comercio electrónico excepcional que ofrece artículos de alta calidad con un soporte al cliente impecable.', 'Revisor de Productos', NULL, NULL, 'ai_translated', '2026-08-10 16:13:02', '2026-08-05 19:52:48', '2026-08-10 16:13:04');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (5, 1, 3, 'C\'est un excellent site de shopping ! J\'ai commandé deux fois dans le passé et je commanderai à nouveau à l\'avenir. Excellent prix, excellents articles, excellent service client !', 'Acheteur Vérifié', NULL, NULL, 'ai_translated', '2026-08-10 17:17:26', '2026-08-06 16:09:10', '2026-08-10 17:17:28');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (6, 2, 3, 'A toujours les meilleurs prix et la livraison la plus rapide. Je recommande vivement cette entreprise et continuerai à l\'utiliser pour mes futures commandes.', 'Client Régulier', NULL, NULL, 'ai_translated', '2026-08-10 17:17:28', '2026-08-06 16:09:12', '2026-08-10 17:17:30');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (7, 3, 3, 'C\'est un excellent site de magasin. Ils ont toujours ce dont j\'ai besoin pour les cadeaux d\'entreprise et les promotions saisonnières.', 'Propriétaire d\'entreprise', NULL, NULL, 'ai_translated', '2026-08-10 17:17:30', '2026-08-06 16:09:13', '2026-08-10 17:17:31');
SQL
);

        // Demo Testimonial Translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `testimonial_translations` (`id`, `testimonial_id`, `language_id`, `content`, `author_title`, `author_name`, `company_name`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (8, 4, 3, 'Une plateforme de commerce électronique exceptionnelle offrant des articles de haute qualité avec un support client irréprochable.', 'Critique de produit', NULL, NULL, 'ai_translated', '2026-08-10 17:17:31', '2026-08-06 16:09:15', '2026-08-10 17:17:33');
SQL
);

        // Table: product_brands
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_brands` (`id`, `name`, `slug`, `description`, `sort_order`, `brand_icon`, `brand_logo_s3`, `brand_logo_cdn_url`, `brand_logo_region`, `brand_logo_bucket_name`, `brand_logo_access_key_id`, `brand_logo_secret_access_key`, `brand_icon_direct_url`, `brand_url`, `created_at`, `updated_at`, `is_demo`, `is_visible_in_menu`, `show_image`) VALUES (1, 'Prestige Design', 'prestige-design', NULL, 5, 'https://d23w3zagfzgqcb.cloudfront.net/prestige-brand.webp', 0, NULL, NULL, NULL, NULL, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/prestige-brand.webp', NULL, '2026-07-20 13:52:46', '2026-08-05 00:08:11', 1, 1, 0);
SQL
);

        // Table: product_brands
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_brands` (`id`, `name`, `slug`, `description`, `sort_order`, `brand_icon`, `brand_logo_s3`, `brand_logo_cdn_url`, `brand_logo_region`, `brand_logo_bucket_name`, `brand_logo_access_key_id`, `brand_logo_secret_access_key`, `brand_icon_direct_url`, `brand_url`, `created_at`, `updated_at`, `is_demo`, `is_visible_in_menu`, `show_image`) VALUES (2, 'DeMarco', 'demarco', NULL, 3, 'https://d23w3zagfzgqcb.cloudfront.net/demarco-brand.webp', 0, NULL, NULL, NULL, NULL, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/demarco-brand.webp', NULL, '2026-07-20 13:52:46', '2026-08-05 00:08:04', 1, 1, 0);
SQL
);

        // Table: product_brands
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_brands` (`id`, `name`, `slug`, `description`, `sort_order`, `brand_icon`, `brand_logo_s3`, `brand_logo_cdn_url`, `brand_logo_region`, `brand_logo_bucket_name`, `brand_logo_access_key_id`, `brand_logo_secret_access_key`, `brand_icon_direct_url`, `brand_url`, `created_at`, `updated_at`, `is_demo`, `is_visible_in_menu`, `show_image`) VALUES (3, 'Old Heritage', 'old-heritage', NULL, 2, 'https://d23w3zagfzgqcb.cloudfront.net/old-heritage-brand.webp', 0, NULL, NULL, NULL, NULL, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/old-heritage-brand.webp', NULL, '2026-07-20 13:52:46', '2026-08-05 00:08:00', 1, 1, 0);
SQL
);

        // Table: product_brands
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_brands` (`id`, `name`, `slug`, `description`, `sort_order`, `brand_icon`, `brand_logo_s3`, `brand_logo_cdn_url`, `brand_logo_region`, `brand_logo_bucket_name`, `brand_logo_access_key_id`, `brand_logo_secret_access_key`, `brand_icon_direct_url`, `brand_url`, `created_at`, `updated_at`, `is_demo`, `is_visible_in_menu`, `show_image`) VALUES (4, 'Bella Luna', 'bella-luna', NULL, 4, 'https://d23w3zagfzgqcb.cloudfront.net/bella-luna-brand.webp', 0, NULL, NULL, NULL, NULL, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/bella-luna-brand.webp', NULL, '2026-07-20 13:52:46', '2026-08-05 00:08:08', 1, 1, 0);
SQL
);

        // Table: product_brands
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_brands` (`id`, `name`, `slug`, `description`, `sort_order`, `brand_icon`, `brand_logo_s3`, `brand_logo_cdn_url`, `brand_logo_region`, `brand_logo_bucket_name`, `brand_logo_access_key_id`, `brand_logo_secret_access_key`, `brand_icon_direct_url`, `brand_url`, `created_at`, `updated_at`, `is_demo`, `is_visible_in_menu`, `show_image`) VALUES (5, 'Excelsior', 'excelsior', 'sample brand description', 1, 'https://d23w3zagfzgqcb.cloudfront.net/excelsior-brand.webp', 0, NULL, NULL, NULL, NULL, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/excelsior-brand.webp', NULL, '2026-07-20 13:52:46', '2026-08-09 19:32:08', 1, 1, 0);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (1, 'Custom Jewelry', 'custom-jewelry', 'Rings, necklaces, earrings and fine jewellery pieces.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2026-07-20 13:52:46', '2026-08-09 18:29:27', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (2, 'Watches', 'watches', 'Men\'s and Women\'s watches and time pieces.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 1, 0, 0, '2026-07-20 13:52:46', '2026-08-09 18:30:09', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (3, 'Downloads & Media', 'downloads-media', 'PDF downloads and on-demand media content.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (4, 'Gifts & Apparel', 'gifts-apparel', 'Sweatshirts, mugs, apparel and gift items.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (5, 'Service Items', 'service-items', 'Service-only items and professional engagements.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (6, 'Workshops & Seminars', 'workshops-seminars', 'In-person and online workshops, seminars and training sessions.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, 0, 0, '2026-07-20 13:52:46', '2026-08-09 18:30:14', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (7, 'Rings', 'rings', 'Fine rings and bands.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (8, 'Bracelets', 'bracelets', 'Diamond, gold and silver bracelets.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `description`, `category_image`, `category_image_s3`, `category_image_cdn_url`, `category_image_region`, `category_image_bucket_name`, `category_image_access_key_id`, `category_image_secret_access_key`, `category_image_direct_url`, `parent_id`, `sort_order`, `is_visible_in_menu`, `display_label_in_plugins`, `display_image_in_plugins`, `created_at`, `updated_at`, `is_demo`) VALUES (10, 'Earrings', 'earrings', 'Diamond and gemstone earrings.', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (1, 1, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (2, 1, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (3, 2, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (50, 2, 7);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (5, 3, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (6, 3, 7);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (7, 4, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (8, 4, 7);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (9, 5, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (10, 5, 7);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (11, 6, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (12, 6, 7);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (13, 7, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (14, 7, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (15, 8, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (16, 8, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (17, 9, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (18, 9, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (19, 10, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (20, 10, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (21, 11, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (22, 11, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (23, 12, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (24, 12, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (25, 13, 1);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (26, 13, 8);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (27, 14, 3);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (28, 15, 3);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (31, 17, 4);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (32, 18, 2);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (33, 19, 2);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (34, 20, 4);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (35, 21, 4);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (36, 22, 2);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (37, 23, 2);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (39, 25, 4);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (42, 28, 5);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (44, 30, 6);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (45, 31, 6);
SQL
);

        // Table: product_categories_assignments
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_categories_assignments` (`id`, `product_id`, `category_id`) VALUES (48, 34, 6);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (1, 1, '14k|24k 3 Ct Bracelet', 'Sample item showing cross selling associations.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>14K White Gold Diamond Bracelet &mdash; Timeless Elegance</h2>\n<p>Elevate any look with this beautiful diamond bracelet crafted in lustrous 14K white gold. Featuring brilliant‑cut diamonds totaling 1/4 carat, the bracelet combines delicate sparkle with a refined, flexible link design for comfort and effortless wear from day to night.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Metal:</strong> 14K white gold with a polished finish</li>\n<li><strong>Diamonds:</strong> Brilliant‑cut diamonds, expertly set, total weight 1/4 carat</li>\n<li><strong>Design:</strong> Flexible link construction for a comfortable, natural fit</li>\n<li><strong>Occasions:</strong> Elegant enough for special events, understated for everyday wear</li>\n</ul>\n<h3>Why You&rsquo;ll Love It</h3>\n<ul>\n<li>Classic white gold and brilliant diamonds create a versatile piece that complements any wardrobe.</li>\n<li>The low‑profile, flexible design sits comfortably on the wrist while still delivering eye‑catching sparkle.</li>\n<li>An ideal gift for anniversaries, birthdays, graduations, or as a meaningful token for any special moment.</li>\n</ul>\n<h3>Styling Tips</h3>\n<ul>\n<li>Wear alone for a refined, minimalist look.</li>\n<li>Stack with other bracelets or a slim watch to create a personalized layered effect.</li>\n<li>Pairs beautifully with both casual and formal ensembles &mdash; from jeans to eveningwear.</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Remove before showering, swimming, or doing household chores to preserve finish and stone brilliance.</li>\n<li>Clean gently with a soft brush and mild soap, then rinse and dry with a lint‑free cloth.</li>\n<li>Store separately from other jewelry to avoid scratches.</li>\n</ul>\n<p>Make a lasting impression with this elegant 14K white gold diamond bracelet &mdash; a timeless addition to any jewelry collection and a thoughtful, stunning gift for someone special.</p>\n</div>\n<p>&nbsp;</p>', '14k|24k 3 Ct Bracelet 14k 24k 3 Ct Bracelet 14k|24k 3 Ct Bracelet Shop our stunning diamond bracelet collection. Fine jewellery crafted in 14K white gold. Sample item showing cross selling associations. 14K White Gold Diamond Bracelet &mdash; Timeless Elegance Elevate any look with this beautiful diamond bracelet crafted in lustrous 14K white gold. Featuring brilliant‑cut diamonds totaling 1/4 carat, the bracelet combines delicate sparkle with a refined, flexible link design for comfort and effortless wear from day to night. Key Features Metal: 14K white gold with a polished finish Diamonds: Brilliant‑cut diamonds, expertly set, total weight 1/4 carat Design: Flexible link construction for a comfortable, natural fit Occasions: Elegant enough for special events, understated for everyday wear Why You&rsquo;ll Love It Classic white gold and brilliant diamonds create a versatile piece that complements any wardrobe. The low‑profile, flexible design sits comfortably on the wrist while still delivering eye‑catching sparkle. An ideal gift for anniversaries, birthdays, graduations, or as a meaningful token for any special moment. Styling Tips Wear alone for a refined, minimalist look. Stack with other bracelets or a slim watch to create a personalized layered effect. Pairs beautifully with both casual and formal ensembles &mdash; from jeans to eveningwear. Care &amp; Maintenance Remove before showering, swimming, or doing household chores to preserve finish and stone brilliance. Clean gently with a soft brush and mild soap, then rinse and dry with a lint‑free cloth. Store separately from other jewelry to avoid scratches. Make a lasting impression with this elegant 14K white gold diamond bracelet &mdash; a timeless addition to any jewelry collection and a thoughtful, stunning gift for someone special. &nbsp; event events ticket tickets experience seminar workshop admission registration Prestige Design', 0, '14k|24k 3 Ct Bracelet', 'Shop our stunning diamond bracelet collection. Fine jewellery crafted in 14K white gold.', '14k-24k-3-Ct Bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 22:45:09', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (2, 1, 'Heart Of Sapphire Ring', 'Elegant heart-of-sapphire ring — a timeless piece for any wardrobe. This sample item is set on sale price to show the difference in pricing display.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Heart of Sapphire Ring</h1>\n<p><strong>Elegant. Romantic. Timeless.</strong> The Heart of Sapphire Ring pairs a graceful heart-shaped sapphire with warm 14K yellow gold for a piece that feels both classic and contemporary. Finely crafted with a secure prong setting, this ring is designed to shine from everyday moments to life&rsquo;s most memorable occasions.</p>\n<h2>Why you&rsquo;ll love it</h2>\n<ul>\n<li><strong>Signature center stone:</strong> A heart-shaped sapphire that captures light and attention with subtle, enduring color.</li>\n<li><strong>Classic craftsmanship:</strong> Set in 14K yellow gold with a secure prong setting for lasting beauty and safety.</li>\n<li><strong>Versatile style:</strong> Elegant enough for evening wear, understated enough for everyday&mdash;pairs beautifully with other rings or worn alone as a statement piece.</li>\n<li><strong>Meaningful gift:</strong> A romantic choice for anniversaries, engagements, birthdays, or any moment you want to mark with love.</li>\n</ul>\n<h2>Product details</h2>\n<ul>\n<li><strong>Metal:</strong> 14K yellow gold</li>\n<li><strong>Gemstone:</strong> Heart-shaped sapphire</li>\n<li><strong>Setting:</strong> Secure prong setting</li>\n<li><strong>Finish:</strong> High-polish</li>\n<li><strong>Craftsmanship:</strong> Hand-finished for refined detail</li>\n</ul>\n<h2>Customization &amp; sizing</h2>\n<p>This ring is available in standard sizes and can be customized on request. Options often include alternate metal choices and personalized engraving&mdash;contact our team to create a tailored piece that perfectly reflects your style and story.</p>\n<h2>Care &amp; maintenance</h2>\n<ul>\n<li>Clean gently with warm water, mild soap, and a soft brush; rinse and dry thoroughly.</li>\n<li>Avoid harsh chemicals, extreme temperatures, and impact to preserve the gemstone and finish.</li>\n<li>Store separately to prevent scratching and maintain luster.</li>\n</ul>\n<h2>Peace of mind</h2>\n<p>Each Heart of Sapphire Ring is inspected to meet our quality standards and comes with dedicated customer support to help with sizing, care, and any questions. For assistance with customization or ordering, our team is happy to help.</p>\n<p><strong>Make it yours:</strong> Add the Heart of Sapphire Ring to your collection today and wear a timeless symbol of love and elegance for years to come.</p>\n</div>\n<p>&nbsp;</p>', 'Heart Of Sapphire Ring Heart Of Sapphire Ring Heart Of Sapphire Ring Elegant 14K gold heart sapphire ring. Shop fine jewellery. Elegant heart-of-sapphire ring — a timeless piece for any wardrobe. This sample item is set on sale price to show the difference in pricing display. Heart of Sapphire Ring Elegant. Romantic. Timeless. The Heart of Sapphire Ring pairs a graceful heart-shaped sapphire with warm 14K yellow gold for a piece that feels both classic and contemporary. Finely crafted with a secure prong setting, this ring is designed to shine from everyday moments to life&rsquo;s most memorable occasions. Why you&rsquo;ll love it Signature center stone: A heart-shaped sapphire that captures light and attention with subtle, enduring color. Classic craftsmanship: Set in 14K yellow gold with a secure prong setting for lasting beauty and safety. Versatile style: Elegant enough for evening wear, understated enough for everyday&mdash;pairs beautifully with other rings or worn alone as a statement piece. Meaningful gift: A romantic choice for anniversaries, engagements, birthdays, or any moment you want to mark with love. Product details Metal: 14K yellow gold Gemstone: Heart-shaped sapphire Setting: Secure prong setting Finish: High-polish Craftsmanship: Hand-finished for refined detail Customization &amp; sizing This ring is available in standard sizes and can be customized on request. Options often include alternate metal choices and personalized engraving&mdash;contact our team to create a tailored piece that perfectly reflects your style and story. Care &amp; maintenance Clean gently with warm water, mild soap, and a soft brush; rinse and dry thoroughly. Avoid harsh chemicals, extreme temperatures, and impact to preserve the gemstone and finish. Store separately to prevent scratching and maintain luster. Peace of mind Each Heart of Sapphire Ring is inspected to meet our quality standards and comes with dedicated customer support to help with sizing, care, and any questions. For assistance with customization or ordering, our team is happy to help. Make it yours: Add the Heart of Sapphire Ring to your collection today and wear a timeless symbol of love and elegance for years to come. &nbsp; event events ticket tickets experience seminar workshop admission registration Prestige Design', 0, 'Heart Of Sapphire Ring', 'Elegant 14K gold heart sapphire ring. Shop fine jewellery.', 'Heart Of Sapphire Ring', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-04 16:01:14', 1, 1, 0.00, 1, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (3, 1, 'Diamond Mosaic Ring', 'Demonstrates sizing selectors plus upsell option.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Product Overview</h2>\n<p>Inspired by timeless bloom motifs and modern precision, the Diamond Mosaic Ring brings a delicate flower to life in a mesmerizing array of diamonds. Meticulously crafted with an intricate mosaic pattern, each small stone catches light from every angle to create a brilliant, scintillating surface that reads like a single floral statement. Perfect as a signature ring, an engagement accent, or an elevated everyday piece.</p>\n<h2>Key Features</h2>\n<ul>\n<li><strong>Design:</strong> Elegant floral mosaic &mdash; an intricate cluster of diamonds arranged to resemble a blossoming flower.</li>\n<li><strong>Metal Options:</strong> Set in 14K white gold (standard). Platinum upgrade available for enhanced durability and a brighter white finish.</li>\n<li><strong>Diamond Setting:</strong> Multiple diamonds are precisely set to maximize sparkle and visual continuity across the motif.</li>\n<li><strong>Craftsmanship:</strong> Hand-finished details and expert setting ensure long-lasting structure and refined appearance.</li>\n<li><strong>Customization:</strong> Available in a range of ring sizes; platinum upgrade and custom requests are welcome. Contact us for special sizing or personalization options.</li>\n</ul>\n<h2>Why You&rsquo;ll Love It</h2>\n<p>The Diamond Mosaic Ring balances romantic charm with contemporary finesse. Its compact yet highly detailed design makes it versatile &mdash; beautiful on its own or paired with other rings. The multi-stone mosaic gives the impression of a larger surface area of light, offering striking presence without a bulky profile.</p>\n<h2>Materials &amp; Care</h2>\n<ul>\n<li><strong>Metals:</strong> 14K white gold (standard). Platinum available upon request.</li>\n<li><strong>Diamonds:</strong> Carefully selected and responsibly sourced to deliver exceptional sparkle and lasting wear.</li>\n<li><strong>Care:</strong> Clean gently with a soft brush and mild soapy water; avoid harsh chemicals and abrasive cleaners. Store separately in a soft pouch or jewelry box to prevent scratches.</li>\n</ul>\n<h2>Sizing, Shipping &amp; Services</h2>\n<ul>\n<li><strong>Sizing:</strong> Available in standard ring sizes. If you need assistance determining your size, contact our customer service for guidance. Resizing services may be available&mdash;please inquire before purchase if you require a precise fit.</li>\n<li><strong>Lead Time:</strong> This piece may be made to order or tailored to your specifications. Production and shipping times vary; contact us for current turnaround estimates.</li>\n<li><strong>Packaging:</strong> Each ring is shipped securely in protective packaging and arrives in a gift-ready jewelry box.</li>\n<li><strong>Custom Requests:</strong> For platinum upgrades, special sizes, engraving, or other personalization, please reach out to our jewelry specialists prior to ordering.</li>\n</ul>\n<h2>Need Help?</h2>\n<p>If you have questions about metal choices, sizing, or custom options (including a platinum upgrade), our team is here to help. Select your preferred metal and size, or contact us for bespoke requests and expert guidance.</p>\n<p><strong>Make it yours:</strong> Choose the Diamond Mosaic Ring for an intricate, luminous piece that celebrates craftsmanship and feminine elegance.</p>\n</div>\n<p>&nbsp;</p>', 'Diamond Mosaic Ring diamond mosaic ring Diamond Mosaic Ring Brilliant diamond mosaic ring. Make a great gift for that special someone! Demonstrates sizing selectors plus upsell option. Product Overview Inspired by timeless bloom motifs and modern precision, the Diamond Mosaic Ring brings a delicate flower to life in a mesmerizing array of diamonds. Meticulously crafted with an intricate mosaic pattern, each small stone catches light from every angle to create a brilliant, scintillating surface that reads like a single floral statement. Perfect as a signature ring, an engagement accent, or an elevated everyday piece. Key Features Design: Elegant floral mosaic &mdash; an intricate cluster of diamonds arranged to resemble a blossoming flower. Metal Options: Set in 14K white gold (standard). Platinum upgrade available for enhanced durability and a brighter white finish. Diamond Setting: Multiple diamonds are precisely set to maximize sparkle and visual continuity across the motif. Craftsmanship: Hand-finished details and expert setting ensure long-lasting structure and refined appearance. Customization: Available in a range of ring sizes; platinum upgrade and custom requests are welcome. Contact us for special sizing or personalization options. Why You&rsquo;ll Love It The Diamond Mosaic Ring balances romantic charm with contemporary finesse. Its compact yet highly detailed design makes it versatile &mdash; beautiful on its own or paired with other rings. The multi-stone mosaic gives the impression of a larger surface area of light, offering striking presence without a bulky profile. Materials &amp; Care Metals: 14K white gold (standard). Platinum available upon request. Diamonds: Carefully selected and responsibly sourced to deliver exceptional sparkle and lasting wear. Care: Clean gently with a soft brush and mild soapy water; avoid harsh chemicals and abrasive cleaners. Store separately in a soft pouch or jewelry box to prevent scratches. Sizing, Shipping &amp; Services Sizing: Available in standard ring sizes. If you need assistance determining your size, contact our customer service for guidance. Resizing services may be available&mdash;please inquire before purchase if you require a precise fit. Lead Time: This piece may be made to order or tailored to your specifications. Production and shipping times vary; contact us for current turnaround estimates. Packaging: Each ring is shipped securely in protective packaging and arrives in a gift-ready jewelry box. Custom Requests: For platinum upgrades, special sizes, engraving, or other personalization, please reach out to our jewelry specialists prior to ordering. Need Help? If you have questions about metal choices, sizing, or custom options (including a platinum upgrade), our team is here to help. Select your preferred metal and size, or contact us for bespoke requests and expert guidance. Make it yours: Choose the Diamond Mosaic Ring for an intricate, luminous piece that celebrates craftsmanship and feminine elegance. &nbsp; event events ticket tickets experience seminar workshop admission registration Prestige Design', 0, 'Diamond Mosaic Ring', 'Brilliant diamond mosaic ring. Make a great gift for that special someone!', 'diamond-mosaic-ring', 0, 1, 0, 0, NULL, NULL, 0, NULL, 1, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 22:55:11', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (4, 4, '14K Ring With Cultured Pearl And Diamonds', 'Demonstrates alternate method of option selection with custom list instead of variants. (When individual option-based inventory levels are not required such as a custom built item.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p><strong>Elevate every moment with timeless elegance.</strong> This 14K gold ring centers a luminous cultured pearl framed by six sparkling diamonds for a refined, feminine look. Handcrafted to catch the light from every angle, it&rsquo;s an ideal choice for everyday sophistication or a memorable gift for a special occasion.</p>\n<h2>Key features</h2>\n<ul>\n<li><strong>Metal:</strong> Solid 14K gold for lasting beauty and durability.</li>\n<li><strong>Centerstone:</strong> Lustrous cultured pearl, chosen for its smooth surface and rich nacre.</li>\n<li><strong>Accent stones:</strong> Six round-cut diamonds that add delicate brilliance around the pearl.</li>\n<li><strong>Finish &amp; setting:</strong> Polished band with secure, expertly crafted settings to protect the pearl and diamonds.</li>\n<li><strong>Design:</strong> Classic, versatile silhouette that pairs well with both casual and formal looks.</li>\n<li><strong>Customization:</strong> Personalization options and gift wrap available to make this piece uniquely yours.</li>\n</ul>\n<h2>Why you&rsquo;ll love it</h2>\n<ul>\n<li>Combines the soft glow of a pearl with the crisp sparkle of diamonds for a balanced, elegant aesthetic.</li>\n<li>Timeless design that transitions effortlessly from day to night.</li>\n<li>Makes a meaningful gift&mdash;ideal for anniversaries, birthdays, bridesmaids, or milestone moments.</li>\n</ul>\n<h2>Product details &amp; care</h2>\n<ul>\n<li><strong>Sizing &amp; personalization:</strong> Available in standard ring sizes; engraving and custom sizing are offered &mdash; please allow additional production time for personalized orders.</li>\n<li><strong>Care instructions:</strong> Avoid exposure to harsh chemicals, perfumes, and chlorinated water. Clean gently with a soft cloth and have settings inspected periodically by a jeweler.</li>\n<li><strong>Storage:</strong> Store separately in a soft pouch or jewelry box to prevent scratching and preserve the pearl&rsquo;s luster.</li>\n</ul>\n<h2>Gift-ready</h2>\n<p>Each ring can be packaged with premium gift wrap and a personalized message upon request&mdash;perfect for gifting straight from our studio to your recipient.</p>\n<p>Choose a piece that blends classic charm with modern craftsmanship. Add personalization or gift wrap at checkout to create a truly special keepsake.</p>\n</div>', '14K Ring With Cultured Pearl And Diamonds 14k ring cultured pearl and diamonds 14K Ring With Cultured Pearl And Diamonds 14K gold ring with cultured pearl and diamond accents. Demonstrates alternate method of option selection with custom list instead of variants. (When individual option-based inventory levels are not required such as a custom built item.) Elevate every moment with timeless elegance. This 14K gold ring centers a luminous cultured pearl framed by six sparkling diamonds for a refined, feminine look. Handcrafted to catch the light from every angle, it&rsquo;s an ideal choice for everyday sophistication or a memorable gift for a special occasion. Key features Metal: Solid 14K gold for lasting beauty and durability. Centerstone: Lustrous cultured pearl, chosen for its smooth surface and rich nacre. Accent stones: Six round-cut diamonds that add delicate brilliance around the pearl. Finish &amp; setting: Polished band with secure, expertly crafted settings to protect the pearl and diamonds. Design: Classic, versatile silhouette that pairs well with both casual and formal looks. Customization: Personalization options and gift wrap available to make this piece uniquely yours. Why you&rsquo;ll love it Combines the soft glow of a pearl with the crisp sparkle of diamonds for a balanced, elegant aesthetic. Timeless design that transitions effortlessly from day to night. Makes a meaningful gift&mdash;ideal for anniversaries, birthdays, bridesmaids, or milestone moments. Product details &amp; care Sizing &amp; personalization: Available in standard ring sizes; engraving and custom sizing are offered &mdash; please allow additional production time for personalized orders. Care instructions: Avoid exposure to harsh chemicals, perfumes, and chlorinated water. Clean gently with a soft cloth and have settings inspected periodically by a jeweler. Storage: Store separately in a soft pouch or jewelry box to prevent scratching and preserve the pearl&rsquo;s luster. Gift-ready Each ring can be packaged with premium gift wrap and a personalized message upon request&mdash;perfect for gifting straight from our studio to your recipient. Choose a piece that blends classic charm with modern craftsmanship. Add personalization or gift wrap at checkout to create a truly special keepsake. event events ticket tickets experience seminar workshop admission registration Bella Luna', 0, '14K Ring With Cultured Pearl And Diamonds', '14K gold ring with cultured pearl and diamond accents.', '14k-ring-cultured-pearl-and-diamonds', 0, 1, 1, 0, NULL, NULL, 0, NULL, 0, 1, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 23:02:59', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (5, 1, 'Sapphire and Diamond Ring', 'Sample item showing an alternate page layout (left side images) plus a gift wrapping option along with sizing options. Inventory levels are also hidden per the setting in advanced product options.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Sapphire and Diamond Ring</h2>\n<p>Elegant and timeless, this sapphire and diamond ring pairs a rich, brilliant-cut sapphire with sparkling diamond accents set in lustrous 14K white gold. The classic prong setting maximizes light return and showcases each stone&rsquo;s fire and depth, creating a refined centerpiece that transitions easily from everyday wear to special occasions.</p>\n<h3>Why you&rsquo;ll love it</h3>\n<ul>\n<li>Classic design: A traditional silhouette that remains stylish for generations.</li>\n<li>Brilliant sparkle: Brilliant-cut stones and open prong settings allow maximum brilliance and day-to-night radiance.</li>\n<li>Durable materials: 14K white gold offers a durable, silvery finish suited for regular wear.</li>\n<li>Versatile styling: Pairs beautifully with wedding bands, other rings, or worn alone as a statement piece.</li>\n</ul>\n<h3>Details &amp; customization</h3>\n<ul>\n<li>Metal: 14K white gold</li>\n<li>Stones: Center sapphire with brilliant-cut diamond accents</li>\n<li>Setting: Classic prong setting to enhance light performance</li>\n<li>Custom options: Available to order with custom sizing and alternate metals (please select options at checkout or contact us for a custom quote)</li>\n<li>Handcrafted: Each ring is carefully finished by skilled jewelers to ensure lasting beauty and quality</li>\n</ul>\n<h3>Care &amp; maintenance</h3>\n<ul>\n<li>Clean periodically with warm soapy water and a soft brush; avoid harsh chemicals and ultrasonic cleaners if the piece contains treated stones.</li>\n<li>Remove during heavy physical work or exposure to abrasive substances to protect the settings and finish.</li>\n<li>Professional inspection and cleaning are recommended annually to maintain setting security and luster.</li>\n</ul>\n<p>This Sapphire and Diamond Ring makes a meaningful gift for anniversaries, birthdays, engagements, or any moment that calls for something special. For certification details, resizing, or bespoke requests, please contact our custom jewelry team &mdash; we&rsquo;re happy to help craft the perfect ring for you.</p>\n</div>', 'Sapphire and Diamond Ring Sapphire and Diamond Ring Sapphire and Diamond Ring Classic sapphire and diamond ring. Shop our fine jewellery collection. Sample item showing an alternate page layout (left side images) plus a gift wrapping option along with sizing options. Inventory levels are also hidden per the setting in advanced product options. Sapphire and Diamond Ring Elegant and timeless, this sapphire and diamond ring pairs a rich, brilliant-cut sapphire with sparkling diamond accents set in lustrous 14K white gold. The classic prong setting maximizes light return and showcases each stone&rsquo;s fire and depth, creating a refined centerpiece that transitions easily from everyday wear to special occasions. Why you&rsquo;ll love it Classic design: A traditional silhouette that remains stylish for generations. Brilliant sparkle: Brilliant-cut stones and open prong settings allow maximum brilliance and day-to-night radiance. Durable materials: 14K white gold offers a durable, silvery finish suited for regular wear. Versatile styling: Pairs beautifully with wedding bands, other rings, or worn alone as a statement piece. Details &amp; customization Metal: 14K white gold Stones: Center sapphire with brilliant-cut diamond accents Setting: Classic prong setting to enhance light performance Custom options: Available to order with custom sizing and alternate metals (please select options at checkout or contact us for a custom quote) Handcrafted: Each ring is carefully finished by skilled jewelers to ensure lasting beauty and quality Care &amp; maintenance Clean periodically with warm soapy water and a soft brush; avoid harsh chemicals and ultrasonic cleaners if the piece contains treated stones. Remove during heavy physical work or exposure to abrasive substances to protect the settings and finish. Professional inspection and cleaning are recommended annually to maintain setting security and luster. This Sapphire and Diamond Ring makes a meaningful gift for anniversaries, birthdays, engagements, or any moment that calls for something special. For certification details, resizing, or bespoke requests, please contact our custom jewelry team &mdash; we&rsquo;re happy to help craft the perfect ring for you. Prestige Design', 0, 'Sapphire and Diamond Ring', 'Classic sapphire and diamond ring. Shop our fine jewellery collection.', 'Sapphire and Diamond Ring', 0, 1, 0, 0, NULL, NULL, 0, NULL, 1, 1, NULL, 2, '2026-07-20 13:52:46', '2026-08-05 23:13:35', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (6, 4, 'Ruby and Diamond Ring with 14K Band - Size 6', 'Example where the item only had one size available but it\'s set as a variant so it appear in the advanced filters search.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Ruby and Diamond Ring with 14K Band</h2>\n<p>Elevate any occasion with this refined ruby and diamond ring, where timeless design meets thoughtful craftsmanship. A vibrant ruby takes center stage in a secure 14K gold four‑prong setting, flanked by channel‑set diamond side stones for added brilliance and a streamlined profile. The result is a classic, versatile piece that reads equally well as an elegant everyday ring or a memorable gift for a milestone moment.</p>\n<ul>\n<li><strong>Center stone:</strong> Vivid ruby set in a traditional four‑prong setting to maximize light and color.</li>\n<li><strong>Accent stones:</strong> Channel‑set diamonds along the shoulders deliver durable sparkle with a low profile for comfortable wear.</li>\n<li><strong>Metal:</strong> 14K gold band with a polished finish for long‑lasting elegance.</li>\n<li><strong>Design:</strong> Classic silhouette that balances bold color with refined detailing &mdash; ideal as a standalone statement or stacked with other rings.</li>\n</ul>\n<h3>Why you&rsquo;ll love it</h3>\n<p>This ring blends the rich warmth of ruby with the crisp fire of diamonds for a look that&rsquo;s both luxurious and wearable. The four‑prong center setting showcases the ruby while protecting it from daily wear, and the channel‑set diamonds provide secure, low‑profile sparkle that won&rsquo;t snag. Thoughtful proportions make this a comfortable piece you&rsquo;ll reach for often.</p>\n<h3>Product details &amp; sizing</h3>\n<ul>\n<li>Metal: 14K gold</li>\n<li>Setting style: Four‑prong center with channel‑set diamond shoulders</li>\n<li>Finish: High polish</li>\n<li>Size: This item is offered in a single size. It is listed as a variant in the catalog to appear in advanced filter searches &mdash; please note the single available size when ordering.</li>\n</ul>\n<p><strong>Need a different size?</strong> Contact us to discuss custom sizing or resizing options. We&rsquo;re happy to accommodate additional sizes or make this ring to your specifications.</p>\n<h3>Care &amp; maintenance</h3>\n<ul>\n<li>To preserve the stones and metal, remove the ring for activities that could cause impact or exposure to harsh chemicals.</li>\n<li>Clean gently with warm water, mild soap, and a soft brush; dry with a lint‑free cloth.</li>\n<li>Have prongs and settings professionally checked periodically to ensure long‑term security.</li>\n</ul>\n<p>Every gemstone is unique, so individual color and character may vary slightly from photos. For questions about this piece, custom requests, or lead times, please contact our customer care team &mdash; we&rsquo;re here to help you make it perfect.</p>\n<p><strong>Add this ruby and diamond ring to your collection for a timeless, elegant statement that will be cherished for years to come.</strong></p>\n</div>\n<p>&nbsp;</p>', 'Ruby and Diamond Ring with 14K Band - Size 6 Ruby and Diamond Ring with 14K Band Ruby and Diamond Ring with 14K Band Elegant ruby and diamond ring in 14K gold. Premium fine jewellery. Example where the item only had one size available but it\'s set as a variant so it appear in the advanced filters search. Ruby and Diamond Ring with 14K Band Elevate any occasion with this refined ruby and diamond ring, where timeless design meets thoughtful craftsmanship. A vibrant ruby takes center stage in a secure 14K gold four‑prong setting, flanked by channel‑set diamond side stones for added brilliance and a streamlined profile. The result is a classic, versatile piece that reads equally well as an elegant everyday ring or a memorable gift for a milestone moment. Center stone: Vivid ruby set in a traditional four‑prong setting to maximize light and color. Accent stones: Channel‑set diamonds along the shoulders deliver durable sparkle with a low profile for comfortable wear. Metal: 14K gold band with a polished finish for long‑lasting elegance. Design: Classic silhouette that balances bold color with refined detailing &mdash; ideal as a standalone statement or stacked with other rings. Why you&rsquo;ll love it This ring blends the rich warmth of ruby with the crisp fire of diamonds for a look that&rsquo;s both luxurious and wearable. The four‑prong center setting showcases the ruby while protecting it from daily wear, and the channel‑set diamonds provide secure, low‑profile sparkle that won&rsquo;t snag. Thoughtful proportions make this a comfortable piece you&rsquo;ll reach for often. Product details &amp; sizing Metal: 14K gold Setting style: Four‑prong center with channel‑set diamond shoulders Finish: High polish Size: This item is offered in a single size. It is listed as a variant in the catalog to appear in advanced filter searches &mdash; please note the single available size when ordering. Need a different size? Contact us to discuss custom sizing or resizing options. We&rsquo;re happy to accommodate additional sizes or make this ring to your specifications. Care &amp; maintenance To preserve the stones and metal, remove the ring for activities that could cause impact or exposure to harsh chemicals. Clean gently with warm water, mild soap, and a soft brush; dry with a lint‑free cloth. Have prongs and settings professionally checked periodically to ensure long‑term security. Every gemstone is unique, so individual color and character may vary slightly from photos. For questions about this piece, custom requests, or lead times, please contact our customer care team &mdash; we&rsquo;re here to help you make it perfect. Add this ruby and diamond ring to your collection for a timeless, elegant statement that will be cherished for years to come. &nbsp; Bella Luna', 0, 'Ruby and Diamond Ring with 14K Band', 'Elegant ruby and diamond ring in 14K gold. Premium fine jewellery.', 'Ruby and Diamond Ring with 14K Band', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 23:20:03', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (7, 3, 'Diamond Wave Bracelet', 'Classic diamond wave bracelet — set in 14K white gold. (Sample Product Showing Centered Layout Option)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Diamond Wave Bracelet &mdash; 14K White Gold</h2>\n<p>Timeless elegance meets modern movement in the Diamond Wave Bracelet. Expertly crafted in 14K white gold, this bracelet features brilliant-cut diamonds arranged in a flowing wave motif that catches the light with every turn of the wrist. Subtle yet striking, it&rsquo;s designed to elevate everyday looks and complete special-occasion ensembles.</p>\n<h3>Product Highlights</h3>\n<ul>\n<li>Metal: 14K white gold for lasting radiance and durability</li>\n<li>Stones: Brilliant-cut diamonds for maximum sparkle and brilliance</li>\n<li>Design: Flowing wave motif that drapes gracefully along the wrist</li>\n<li>Craftsmanship: Carefully hand-set diamonds and a polished finish for a refined look</li>\n<li>Versatility: Elegant enough for evening wear, understated enough for daily wear</li>\n</ul>\n<h3>Why You&rsquo;ll Love It</h3>\n<ul>\n<li>Distinctive silhouette &mdash; the wave design provides movement and visual interest without overpowering your style.</li>\n<li>Reflective sparkle &mdash; brilliant-cut diamonds are positioned to maximize light return for an eye-catching shimmer.</li>\n<li>Comfortable wear &mdash; thoughtfully contoured to sit smoothly on the wrist for all-day comfort.</li>\n<li>Perfect for gifting &mdash; a classic, sophisticated piece for anniversaries, birthdays, or milestone moments.</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<p>To keep your Diamond Wave Bracelet looking its best, clean gently with a soft cloth and mild jewelry cleaner. Remove before swimming, exercising, or handling harsh chemicals. Have your bracelet professionally inspected periodically to ensure settings remain secure.</p>\n<h3>Customization &amp; Services</h3>\n<p>As part of our custom jewelry collection, this bracelet can be tailored to your preferences. Select different lengths or request alternative metal options &mdash; please contact our team for availability and bespoke pricing.</p>\n<p>This Diamond Wave Bracelet blends classic materials with an artful silhouette, making it a versatile, enduring addition to any jewelry wardrobe.</p>\n</div>\n<p>&nbsp;</p>', 'Diamond Wave Bracelet diamond wave bracelet Diamond Wave Bracelet Classic diamond wave bracelet. Fine jewellery for every occasion. Classic diamond wave bracelet — set in 14K white gold. (Sample Product Showing Centered Layout Option) Diamond Wave Bracelet &mdash; 14K White Gold Timeless elegance meets modern movement in the Diamond Wave Bracelet. Expertly crafted in 14K white gold, this bracelet features brilliant-cut diamonds arranged in a flowing wave motif that catches the light with every turn of the wrist. Subtle yet striking, it&rsquo;s designed to elevate everyday looks and complete special-occasion ensembles. Product Highlights Metal: 14K white gold for lasting radiance and durability Stones: Brilliant-cut diamonds for maximum sparkle and brilliance Design: Flowing wave motif that drapes gracefully along the wrist Craftsmanship: Carefully hand-set diamonds and a polished finish for a refined look Versatility: Elegant enough for evening wear, understated enough for daily wear Why You&rsquo;ll Love It Distinctive silhouette &mdash; the wave design provides movement and visual interest without overpowering your style. Reflective sparkle &mdash; brilliant-cut diamonds are positioned to maximize light return for an eye-catching shimmer. Comfortable wear &mdash; thoughtfully contoured to sit smoothly on the wrist for all-day comfort. Perfect for gifting &mdash; a classic, sophisticated piece for anniversaries, birthdays, or milestone moments. Care &amp; Maintenance To keep your Diamond Wave Bracelet looking its best, clean gently with a soft cloth and mild jewelry cleaner. Remove before swimming, exercising, or handling harsh chemicals. Have your bracelet professionally inspected periodically to ensure settings remain secure. Customization &amp; Services As part of our custom jewelry collection, this bracelet can be tailored to your preferences. Select different lengths or request alternative metal options &mdash; please contact our team for availability and bespoke pricing. This Diamond Wave Bracelet blends classic materials with an artful silhouette, making it a versatile, enduring addition to any jewelry wardrobe. &nbsp; Old Heritage', 0, 'Diamond Wave Bracelet', 'Classic diamond wave bracelet. Fine jewellery for every occasion.', 'diamond-wave-bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 4, '2026-07-20 13:52:46', '2026-08-04 16:43:55', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (8, 1, 'Pinched Style Diamond Bracelet', 'Example showing default out of stock message (Currently Unavailable) as well as cross-selling (product recommendations).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Pinched Style Diamond Bracelet</h1>\n<p>Elegant and understated, the Pinched Style Diamond Bracelet pairs delicate craftsmanship with everyday brilliance. Expertly crafted in 14K white gold, its pinched-style links are set with brilliant-cut diamonds that catch the light from every angle&mdash;creating a refined, textured silhouette that&rsquo;s perfect for stacking or wearing solo.</p>\n<h2>Key Features</h2>\n<ul>\n<li><strong>Metal:</strong> Fine 14K white gold with a polished finish.</li>\n<li><strong>Gemstones:</strong> Brilliant-cut diamonds set throughout for continuous sparkle.</li>\n<li><strong>Design:</strong> Pinched-style links deliver subtle texture and enhanced light reflection.</li>\n<li><strong>Wearability:</strong> Delicate, lightweight construction ideal for everyday wear or special occasions.</li>\n<li><strong>Secure fastening:</strong> Finished with a reliable clasp for comfortable, confident wear.</li>\n</ul>\n<h2>Style &amp; Occasion</h2>\n<p>This bracelet&rsquo;s timeless silhouette makes it a versatile addition to any jewelry wardrobe. Wear it alone for a minimalist statement, layer it with thin chains for a contemporary stacked look, or pair it with matching earrings or a pendant for evening elegance. It&rsquo;s an ideal gift for anniversaries, birthdays, graduations, or as a thoughtful &ldquo;just because&rdquo; surprise.</p>\n<h2>Care &amp; Maintenance</h2>\n<ul>\n<li>Avoid exposure to harsh chemicals, perfumes, and lotions to preserve metal and diamond luster.</li>\n<li>Gently clean with a soft brush and mild soapy water; rinse thoroughly and dry with a soft cloth.</li>\n<li>Store separately in a soft pouch or jewelry box to prevent scratches and tangling.</li>\n<li>Periodically inspect settings and clasps; professional cleaning and inspection are recommended for long-term wear.</li>\n</ul>\n<h2>Customization &amp; Ordering</h2>\n<p>Available as part of our Custom Jewelry collection&mdash;please contact us for custom lengths, metal finishes, or special requests. For personalized sizing or bespoke options, our team will work with you to create the perfect piece.</p>\n<p><strong>Ready to make it yours?</strong> Add timeless sparkle to every moment&mdash;contact us for custom requests or to confirm availability and lead times.</p>\n</div>', 'Pinched Style Diamond Bracelet diamond pendant necklace Pinched Style Diamond Bracelet Pinched style diamond bracelet in 14K white gold. Shop our fine jewellery collection. Example showing default out of stock message (Currently Unavailable) as well as cross-selling (product recommendations). Pinched Style Diamond Bracelet Elegant and understated, the Pinched Style Diamond Bracelet pairs delicate craftsmanship with everyday brilliance. Expertly crafted in 14K white gold, its pinched-style links are set with brilliant-cut diamonds that catch the light from every angle&mdash;creating a refined, textured silhouette that&rsquo;s perfect for stacking or wearing solo. Key Features Metal: Fine 14K white gold with a polished finish. Gemstones: Brilliant-cut diamonds set throughout for continuous sparkle. Design: Pinched-style links deliver subtle texture and enhanced light reflection. Wearability: Delicate, lightweight construction ideal for everyday wear or special occasions. Secure fastening: Finished with a reliable clasp for comfortable, confident wear. Style &amp; Occasion This bracelet&rsquo;s timeless silhouette makes it a versatile addition to any jewelry wardrobe. Wear it alone for a minimalist statement, layer it with thin chains for a contemporary stacked look, or pair it with matching earrings or a pendant for evening elegance. It&rsquo;s an ideal gift for anniversaries, birthdays, graduations, or as a thoughtful &ldquo;just because&rdquo; surprise. Care &amp; Maintenance Avoid exposure to harsh chemicals, perfumes, and lotions to preserve metal and diamond luster. Gently clean with a soft brush and mild soapy water; rinse thoroughly and dry with a soft cloth. Store separately in a soft pouch or jewelry box to prevent scratches and tangling. Periodically inspect settings and clasps; professional cleaning and inspection are recommended for long-term wear. Customization &amp; Ordering Available as part of our Custom Jewelry collection&mdash;please contact us for custom lengths, metal finishes, or special requests. For personalized sizing or bespoke options, our team will work with you to create the perfect piece. Ready to make it yours? Add timeless sparkle to every moment&mdash;contact us for custom requests or to confirm availability and lead times. event events ticket tickets experience seminar workshop admission registration Prestige Design', 0, 'Pinched Style Diamond Bracelet', 'Pinched style diamond bracelet in 14K white gold. Shop our fine jewellery collection.', 'diamond-pendant-necklace', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 23:23:02', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (9, 1, 'Diamond Heart Bracelet With Your Initials Inscribed', 'Simple product with the default personalization feature turned on via the variant level.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Diamond Heart Bracelet With Your Initials Inscribed</h2>\n<p>Elevate everyday elegance with this classic heart-design diamond bracelet, expertly crafted in 14K white gold. A delicate row of pav&eacute; diamonds forms a shimmering heart centerpiece that&rsquo;s personalized with your initials for a meaningful, modern keepsake. Total diamond weight: 1/2 carat. Personalization is included at no extra charge.</p>\n<h3>Why you&rsquo;ll love it</h3>\n<ul>\n<li><strong>Timeless design:</strong> A refined heart motif that transitions effortlessly from day to evening.</li>\n<li><strong>Personal and meaningful:</strong> Your initials inscribed directly on the heart for a subtle, sentimental touch.</li>\n<li><strong>Quality materials:</strong> Solid 14K white gold paired with conflict-free diamonds for lasting beauty and ethical sourcing.</li>\n<li><strong>Ready for gifting:</strong> Comes in a luxe jewelry box &mdash; perfect for anniversaries, birthdays, or &ldquo;just because.&rdquo;</li>\n</ul>\n<h3>Product details</h3>\n<ul>\n<li>Metal: 14K white gold</li>\n<li>Diamond total weight: 0.50 carat</li>\n<li>Design: Heart-shaped center motif set with pav&eacute; diamonds</li>\n<li>Finish: High-polish</li>\n<li>Packaging: Complimentary gift box and polishing cloth</li>\n</ul>\n<h3>Personalization (included)</h3>\n<p>Default personalization feature is turned on at the variant level &mdash; personalization is applied by default when you choose a personalized variant. Personalization is included at no additional cost.</p>\n<ol>\n<li>Enter the initials you want engraved in the product options or initials field associated with your selected variant.</li>\n<li>We recommend up to 3 characters (standard initials). Letters only (A&ndash;Z). If you require special characters or a longer inscription, please contact customer service before ordering.</li>\n<li>Engraving is executed in an elegant, legible font optimized for the heart motif. Initials will appear uppercase unless specified otherwise.</li>\n</ol>\n<p><strong>Important:</strong> Please double-check spelling and character order before completing your purchase &mdash; personalized items may be final sale unless there is a manufacturing defect.</p>\n<h3>Fit &amp; sizing</h3>\n<p>The bracelet is available in multiple lengths &mdash; select your preferred size from the variant options. For best fit, measure around the wrist where you wear bracelets and allow 1/2\"&ndash;1\" for comfortable movement. If you&rsquo;re between sizes or need assistance, our customer support team can help you choose the right length.</p>\n<h3>Production &amp; shipping</h3>\n<ul>\n<li>Made to order: Allow 5&ndash;7 business days for personalization and final inspection, plus shipping time.</li>\n<li>Expedited services may be available at checkout &mdash; choose the shipping option that meets your timeline.</li>\n</ul>\n<h3>Care &amp; maintenance</h3>\n<ul>\n<li>Remove before showering, swimming, or using household chemicals.</li>\n<li>Clean gently with a soft, lint-free cloth; professional cleaning and inspection recommended annually.</li>\n<li>Store in the provided box to prevent scratches and tangling.</li>\n</ul>\n<h3>Need help?</h3>\n<p>If you have questions about personalization, sizing, or delivery timelines, contact our customer service team &mdash; we&rsquo;re happy to assist with custom requests or special gift arrangements.</p>\n<p><strong>Add a timeless, personalized touch to your jewelry collection &mdash; order your Diamond Heart Bracelet with your initials inscribed today.</strong></p>\n</div>', 'Diamond Heart Bracelet With Your Initials Inscribed diamond heart bracelet Diamond Heart Bracelet With Your Initials Inscribed Diamond heart bracelet with initials. 14K white gold. Shop fine jewellery. Simple product with the default personalization feature turned on via the variant level. Diamond Heart Bracelet With Your Initials Inscribed Elevate everyday elegance with this classic heart-design diamond bracelet, expertly crafted in 14K white gold. A delicate row of pav&eacute; diamonds forms a shimmering heart centerpiece that&rsquo;s personalized with your initials for a meaningful, modern keepsake. Total diamond weight: 1/2 carat. Personalization is included at no extra charge. Why you&rsquo;ll love it Timeless design: A refined heart motif that transitions effortlessly from day to evening. Personal and meaningful: Your initials inscribed directly on the heart for a subtle, sentimental touch. Quality materials: Solid 14K white gold paired with conflict-free diamonds for lasting beauty and ethical sourcing. Ready for gifting: Comes in a luxe jewelry box &mdash; perfect for anniversaries, birthdays, or &ldquo;just because.&rdquo; Product details Metal: 14K white gold Diamond total weight: 0.50 carat Design: Heart-shaped center motif set with pav&eacute; diamonds Finish: High-polish Packaging: Complimentary gift box and polishing cloth Personalization (included) Default personalization feature is turned on at the variant level &mdash; personalization is applied by default when you choose a personalized variant. Personalization is included at no additional cost. Enter the initials you want engraved in the product options or initials field associated with your selected variant. We recommend up to 3 characters (standard initials). Letters only (A&ndash;Z). If you require special characters or a longer inscription, please contact customer service before ordering. Engraving is executed in an elegant, legible font optimized for the heart motif. Initials will appear uppercase unless specified otherwise. Important: Please double-check spelling and character order before completing your purchase &mdash; personalized items may be final sale unless there is a manufacturing defect. Fit &amp; sizing The bracelet is available in multiple lengths &mdash; select your preferred size from the variant options. For best fit, measure around the wrist where you wear bracelets and allow 1/2\"&ndash;1\" for comfortable movement. If you&rsquo;re between sizes or need assistance, our customer support team can help you choose the right length. Production &amp; shipping Made to order: Allow 5&ndash;7 business days for personalization and final inspection, plus shipping time. Expedited services may be available at checkout &mdash; choose the shipping option that meets your timeline. Care &amp; maintenance Remove before showering, swimming, or using household chemicals. Clean gently with a soft, lint-free cloth; professional cleaning and inspection recommended annually. Store in the provided box to prevent scratches and tangling. Need help? If you have questions about personalization, sizing, or delivery timelines, contact our customer service team &mdash; we&rsquo;re happy to assist with custom requests or special gift arrangements. Add a timeless, personalized touch to your jewelry collection &mdash; order your Diamond Heart Bracelet with your initials inscribed today. event events ticket tickets experience seminar workshop admission registration Prestige Design', 0, 'Diamond Heart Bracelet With Your Initials Inscribed', 'Diamond heart bracelet with initials. 14K white gold. Shop fine jewellery.', 'diamond-heart-bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-05 23:30:17', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (10, 4, '14k Or 24K White Gold 2 Carat Diamond Bracelet', 'Sample item layout with video embed below. Great for showing item features, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>14K or 24K White Gold 2 Carat Diamond Bracelet</h2>\n<p>Elevate any look with this exquisitely crafted 2.00 total carat weight diamond bracelet. Brilliant-cut diamonds are set in a refined, low-profile design that catches light with every movement&mdash;perfect for everyday elegance or special occasions. Choose between a classic 14K white gold setting or a higher-purity 24K gold option to suit your style.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Total Diamond Weight:</strong> 2.00 carats (TW)</li>\n<li><strong>Cut:</strong> Brilliant-cut diamonds for maximum brilliance and sparkle</li>\n<li><strong>Metal Options:</strong> 14K white gold or 24K gold &mdash; see note below about finishes</li>\n<li><strong>Setting:</strong> Secure, precise prong/line settings designed to showcase each stone</li>\n<li><strong>Finish:</strong> Highly polished for a luxurious, reflective surface</li>\n</ul>\n<h3>Craftsmanship &amp; Quality</h3>\n<p>Each bracelet is handcrafted by experienced jewelers using time-tested techniques to ensure longevity and balance. Diamonds are hand-selected for consistent size and optical performance, then set with meticulous attention to alignment and symmetry. The result is a seamless row of scintillating stones that sits comfortably on the wrist.</p>\n<h3>Metal Options &mdash; Important Note</h3>\n<p>You may select 14K white gold for a durable, bright-white finish commonly used in fine jewelry. A 24K option is offered for those seeking higher gold purity; please note that 24K is naturally yellow in color. If you prefer a white finish in a higher-karat piece, we recommend contacting us to discuss rhodium plating or alternative alloy options so we can meet your exact preferences.</p>\n<h3>Sizing &amp; Fit</h3>\n<ul>\n<li>Available in standard bracelet lengths; custom lengths can be made to order for a perfect fit.</li>\n<li>Designed for comfortable, daily wear while maintaining a secure and flattering profile on the wrist.</li>\n<li>Please provide wrist measurement at checkout for custom sizing.</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Store separately to avoid scratches and keep the piece in its protective box when not worn.</li>\n<li>Clean gently with a soft brush and warm soapy water; dry thoroughly with a soft cloth.</li>\n<li>To maintain a white gold finish, rhodium plating may be refreshed periodically.</li>\n</ul>\n<h3>What&rsquo;s Included</h3>\n<ul>\n<li>The 2.00 ct diamond bracelet in your chosen metal</li>\n<li>Premium presentation box</li>\n<li>Care instructions and information on maintenance</li>\n<li>Assistance with certification or appraisal upon request</li>\n</ul>\n<h3>Order &amp; Customization</h3>\n<p>Choose your metal and desired length, or contact our team for custom requests&mdash;including specific diamond quality, clasp styles, or engraving. Our jewelers are available to guide you through selecting the perfect configuration.</p>\n<p><strong>Ready to make it yours?</strong> Select your metal and size, then add to cart to secure this timeless 2-carat diamond bracelet. For custom options or questions, contact our customer care team&mdash;we&rsquo;ll help you create a piece you&rsquo;ll treasure for years to come.</p>\n</div>', '14k Or 24K White Gold 2 Carat Diamond Bracelet 14k 24k white gold 2 carat diamond bracelet 14k Or 24K White Gold 2 Carat Diamond Bracelet 2 carat diamond bracelet in 14K or 24K white gold. Fine jewellery. Sample item layout with video embed below. Great for showing item features, etc. 14K or 24K White Gold 2 Carat Diamond Bracelet Elevate any look with this exquisitely crafted 2.00 total carat weight diamond bracelet. Brilliant-cut diamonds are set in a refined, low-profile design that catches light with every movement&mdash;perfect for everyday elegance or special occasions. Choose between a classic 14K white gold setting or a higher-purity 24K gold option to suit your style. Key Features Total Diamond Weight: 2.00 carats (TW) Cut: Brilliant-cut diamonds for maximum brilliance and sparkle Metal Options: 14K white gold or 24K gold &mdash; see note below about finishes Setting: Secure, precise prong/line settings designed to showcase each stone Finish: Highly polished for a luxurious, reflective surface Craftsmanship &amp; Quality Each bracelet is handcrafted by experienced jewelers using time-tested techniques to ensure longevity and balance. Diamonds are hand-selected for consistent size and optical performance, then set with meticulous attention to alignment and symmetry. The result is a seamless row of scintillating stones that sits comfortably on the wrist. Metal Options &mdash; Important Note You may select 14K white gold for a durable, bright-white finish commonly used in fine jewelry. A 24K option is offered for those seeking higher gold purity; please note that 24K is naturally yellow in color. If you prefer a white finish in a higher-karat piece, we recommend contacting us to discuss rhodium plating or alternative alloy options so we can meet your exact preferences. Sizing &amp; Fit Available in standard bracelet lengths; custom lengths can be made to order for a perfect fit. Designed for comfortable, daily wear while maintaining a secure and flattering profile on the wrist. Please provide wrist measurement at checkout for custom sizing. Care &amp; Maintenance Store separately to avoid scratches and keep the piece in its protective box when not worn. Clean gently with a soft brush and warm soapy water; dry thoroughly with a soft cloth. To maintain a white gold finish, rhodium plating may be refreshed periodically. What&rsquo;s Included The 2.00 ct diamond bracelet in your chosen metal Premium presentation box Care instructions and information on maintenance Assistance with certification or appraisal upon request Order &amp; Customization Choose your metal and desired length, or contact our team for custom requests&mdash;including specific diamond quality, clasp styles, or engraving. Our jewelers are available to guide you through selecting the perfect configuration. Ready to make it yours? Select your metal and size, then add to cart to secure this timeless 2-carat diamond bracelet. For custom options or questions, contact our customer care team&mdash;we&rsquo;ll help you create a piece you&rsquo;ll treasure for years to come. Bella Luna', 0, '14k Or 24K White Gold 2 Carat Diamond Bracelet', '2 carat diamond bracelet in 14K or 24K white gold. Fine jewellery.', '14k-24k-white-gold-2-carat-diamond-bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 3, '2026-07-20 13:52:46', '2026-08-06 00:18:38', 1, 1, 0.00, 0, 0, 0, 'Select Option:', '<div style=\"padding:56.25% 0 0 0;position:relative;\"><iframe src=\"https://player.vimeo.com/video/1216002116?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" frameborder=\"0\" allow=\"autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" title=\"Product Video Example\"></iframe></div><script src=\"https://player.vimeo.com/api/player.js\"></script>', 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (11, 5, '18k Gold 5 Carat GIA Certified Diamond Bracelet', 'Sample with alternate image display (right side) plus a customization option for an upsell.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<div class=\"intro\">\n<h2>18K Gold 5 Carat GIA‑Certified Diamond Bracelet</h2>\n<p>A refined statement bracelet crafted for collectors and connoisseurs. This luxurious 18K gold bracelet showcases a total of 5.00 carats of GIA‑certified brilliant‑cut diamonds &mdash; meticulously matched and set to maximize sparkle, symmetry and wearability.</p>\n</div>\n<div class=\"two-column\">\n<div class=\"left-column\">\n<h3>Why you\'ll love it</h3>\n<ul>\n<li>Timeless design that transitions effortlessly from day to evening.</li>\n<li>Expertly hand-set brilliant-cut diamonds totaling 5.00 carats.</li>\n<li>Crafted in solid 18K gold for lasting strength and luxurious patina.</li>\n<li>GIA certification provides independent verification of the diamonds&rsquo; quality.</li>\n<li>Customizable options allow you to tailor metal, finish and details for a truly personal heirloom.</li>\n</ul>\n<h3>Product details</h3>\n<ul>\n<li>Metal: Solid 18K gold (available in Yellow, White, or Rose upon customization)</li>\n<li>Diamond weight: 5.00 total carat weight (tcw)</li>\n<li>Diamond cut: Brilliant cut (GIA‑certified)</li>\n<li>Clasp: Secure box or lobster clasp with safety latch (selectable)</li>\n<li>Standard lengths: 6.5\", 7\", 7.5\" &mdash; custom lengths available by request</li>\n<li>Finish: High polish (matte or satin finishes available as an upgrade)</li>\n</ul>\n<h3>Certification &amp; provenance</h3>\n<p>All diamonds are accompanied by GIA certification. Certificates or a full appraisal can be provided with purchase or upon request to ensure provenance and retail replacement value for insurance purposes.</p>\n<h3>Customization &amp; Upsell options</h3>\n<p>Make this bracelet uniquely yours. Choose from the options below or click <a class=\"cta\" href=\"#customize\">Customize this piece</a> to begin a private consultation.</p>\n<ul>\n<li><strong>Metal selection:</strong> Upgrade between 18K Yellow, White or Rose gold.</li>\n<li><strong>Diamond enhancements:</strong> Option to upgrade to higher color/clarity tiers or to include larger center stones for a bolder look.</li>\n<li><strong>Finish &amp; clasp:</strong> Satin finish, micro‑pav&eacute; accents, or an upgraded safety clasp for enhanced security.</li>\n<li><strong>Personalization:</strong> Engraving on the clasp or custom length for a perfect fit.</li>\n<li><strong>Presentation &amp; protection:</strong> Add a premium presentation box, a detailed GIA appraisal, and optional extended warranty or insurance valuation service.</li>\n</ul>\n<h3>Care &amp; service</h3>\n<p>To maintain brilliance, clean gently with a soft brush and mild jewelry cleaner. Avoid harsh chemicals and remove before strenuous activity. We offer complimentary lifetime cleaning and inspection when purchased with an extended-care plan.</p>\n<h3>Shipping &amp; returns</h3>\n<p>Secure, insured shipping available worldwide. Due to the custom nature and value of this piece, returns and exchanges are handled on a case-by-case basis &mdash; please review our full return policy or contact our concierge for assistance.</p>\n<p class=\"final-cta\">To customize this bracelet or request GIA documentation and appraisal pricing, click <a class=\"cta\" href=\"#customize\">Customize / Request Appraisal</a> or contact our client services team for a private consultation.</p>\n</div>\n<div class=\"right-column\" aria-label=\"Alternate image display (right side)\">\n<div class=\"image-gallery\"><!-- Replace src values with actual product image URLs --> <img src=\"/images/18k-5ct-bracelet-main.jpg\" alt=\"18K gold bracelet with 5 carat GIA certified diamonds &mdash; main view\"> <img src=\"/images/18k-5ct-bracelet-side.jpg\" alt=\"Side profile showing diamond setting and clasp\"> <img src=\"/images/18k-5ct-bracelet-wrist.jpg\" alt=\"Bracelet on wrist &mdash; scale and wear view\"> <img src=\"/images/18k-5ct-bracelet-box.jpg\" alt=\"Premium presentation box and GIA certificate\"></div>\n<p class=\"image-note\">Alternate image display (right side) &mdash; click images to enlarge.</p>\n</div>\n</div>\n<div class=\"notes\">\n<h4>Important</h4>\n<p>Because each bracelet is handcrafted and may be customized, final diamond arrangement and exact specifications may vary slightly from gallery images. Exact GIA report numbers and appraisal documentation will be provided with each sale.</p>\n</div>\n</div>\n</div>', '18k Gold 5 Carat GIA Certified Diamond Bracelet 18k gold 5 carat gia certified diamond bracelet 18k Gold 5 Carat GIA Certified Diamond Bracelet 18K gold 5 carat GIA certified diamond bracelet. Fine jewellery. Sample with alternate image display (right side) plus a customization option for an upsell. 18K Gold 5 Carat GIA‑Certified Diamond Bracelet A refined statement bracelet crafted for collectors and connoisseurs. This luxurious 18K gold bracelet showcases a total of 5.00 carats of GIA‑certified brilliant‑cut diamonds &mdash; meticulously matched and set to maximize sparkle, symmetry and wearability. Why you\'ll love it Timeless design that transitions effortlessly from day to evening. Expertly hand-set brilliant-cut diamonds totaling 5.00 carats. Crafted in solid 18K gold for lasting strength and luxurious patina. GIA certification provides independent verification of the diamonds&rsquo; quality. Customizable options allow you to tailor metal, finish and details for a truly personal heirloom. Product details Metal: Solid 18K gold (available in Yellow, White, or Rose upon customization) Diamond weight: 5.00 total carat weight (tcw) Diamond cut: Brilliant cut (GIA‑certified) Clasp: Secure box or lobster clasp with safety latch (selectable) Standard lengths: 6.5\", 7\", 7.5\" &mdash; custom lengths available by request Finish: High polish (matte or satin finishes available as an upgrade) Certification &amp; provenance All diamonds are accompanied by GIA certification. Certificates or a full appraisal can be provided with purchase or upon request to ensure provenance and retail replacement value for insurance purposes. Customization &amp; Upsell options Make this bracelet uniquely yours. Choose from the options below or click Customize this piece to begin a private consultation. Metal selection: Upgrade between 18K Yellow, White or Rose gold. Diamond enhancements: Option to upgrade to higher color/clarity tiers or to include larger center stones for a bolder look. Finish &amp; clasp: Satin finish, micro‑pav&eacute; accents, or an upgraded safety clasp for enhanced security. Personalization: Engraving on the clasp or custom length for a perfect fit. Presentation &amp; protection: Add a premium presentation box, a detailed GIA appraisal, and optional extended warranty or insurance valuation service. Care &amp; service To maintain brilliance, clean gently with a soft brush and mild jewelry cleaner. Avoid harsh chemicals and remove before strenuous activity. We offer complimentary lifetime cleaning and inspection when purchased with an extended-care plan. Shipping &amp; returns Secure, insured shipping available worldwide. Due to the custom nature and value of this piece, returns and exchanges are handled on a case-by-case basis &mdash; please review our full return policy or contact our concierge for assistance. To customize this bracelet or request GIA documentation and appraisal pricing, click Customize / Request Appraisal or contact our client services team for a private consultation. Alternate image display (right side) &mdash; click images to enlarge. Important Because each bracelet is handcrafted and may be customized, final diamond arrangement and exact specifications may vary slightly from gallery images. Exact GIA report numbers and appraisal documentation will be provided with each sale. Excelsior', 0, '18k Gold 5 Carat GIA Certified Diamond Bracelet', '18K gold 5 carat GIA certified diamond bracelet. Fine jewellery.', '18k-gold-5-carat-gia-certified-diamond-bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-06 00:25:46', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (12, 4, 'Ruby and Diamond Bracelet', 'Elegant ruby and diamond bracelet in 14k gold, silver and 18k rose gold options. Silver tone has an QTY discount applied. Realtime total display enabled below the add to cart button.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Ruby and Diamond Bracelet &mdash; Timeless Elegance</h2>\n<p>A graceful balance of color and sparkle, this Ruby and Diamond Bracelet pairs vivid ruby stones with brilliant-cut diamond accents for a look that is both luxurious and refined. Thoughtfully designed to dress up everyday wear or finish an evening look, it&rsquo;s available in three metal tones to suit your style: gold tone, silver tone, and rose gold.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Stones:</strong> Vivid ruby stones accented by brilliant-cut diamond accents for radiant contrast.</li>\n<li><strong>Finishes:</strong> Choose from gold tone, silver tone, or warm rose gold tone to match your jewelry collection.</li>\n<li><strong>Craftsmanship:</strong> Carefully set stones and a secure clasp for comfortable, everyday wear.</li>\n<li><strong>Design:</strong> Elegant, versatile silhouette designed to layer beautifully with other bracelets or stand alone as a statement piece.</li>\n<li><strong>Quantity Savings:</strong> The silver tone option includes an automatic quantity discount&mdash;select multiple pieces in your cart to see the applied savings.</li>\n</ul>\n<h3>Why You&rsquo;ll Love It</h3>\n<p>The vivid red of the rubies paired with sparkling diamond accents creates a classic, eye-catching combination that complements both warm and cool skin tones. Lightweight and refined, this bracelet adds instant polish to a business look, cocktail attire, or special-occasion ensemble.</p>\n<h3>Sizing &amp; Fit</h3>\n<p>Available in standard bracelet lengths. Please select your preferred size from the options on the product page. If you&rsquo;re unsure which size to choose, measure your wrist where you would normally wear a bracelet and add 0.5\"&ndash;1\" for a comfortable fit.</p>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Avoid exposure to harsh chemicals, perfumes, and chlorinated water to preserve the finish.</li>\n<li>Wipe with a soft, dry cloth after wear to remove oils and restore shine.</li>\n<li>Store separately in a soft pouch or jewelry box to prevent scratches.</li>\n</ul>\n<h3>Perfect For</h3>\n<p>Birthday or anniversary gifts, bridal or bridesmaid jewelry, milestone celebrations, or simply treating yourself to a refined everyday piece. Each bracelet makes an elegant, thoughtful present and pairs beautifully with matching earrings or a pendant.</p>\n<h3>Additional Information</h3>\n<ul>\n<li>Available finishes: gold tone, silver tone, rose gold.</li>\n<li>Silver tone qualifies for an automatic quantity discount&mdash;add multiple to your cart to receive the reduced price.</li>\n<li>For custom requests or bulk orders, please contact our customer service team.</li>\n</ul>\n<p>Add a touch of timeless luxury to your jewelry collection. Choose your metal tone and size, then click &ldquo;Add to Cart&rdquo; to order.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Ruby and Diamond Bracelet Ruby and Diamond Bracelet Ruby and Diamond Bracelet Ruby and diamond bracelet. Elegant fine jewellery. Elegant ruby and diamond bracelet in 14k gold, silver and 18k rose gold options. Silver tone has an QTY discount applied. Realtime total display enabled below the add to cart button. Ruby and Diamond Bracelet &mdash; Timeless Elegance A graceful balance of color and sparkle, this Ruby and Diamond Bracelet pairs vivid ruby stones with brilliant-cut diamond accents for a look that is both luxurious and refined. Thoughtfully designed to dress up everyday wear or finish an evening look, it&rsquo;s available in three metal tones to suit your style: gold tone, silver tone, and rose gold. Key Features Stones: Vivid ruby stones accented by brilliant-cut diamond accents for radiant contrast. Finishes: Choose from gold tone, silver tone, or warm rose gold tone to match your jewelry collection. Craftsmanship: Carefully set stones and a secure clasp for comfortable, everyday wear. Design: Elegant, versatile silhouette designed to layer beautifully with other bracelets or stand alone as a statement piece. Quantity Savings: The silver tone option includes an automatic quantity discount&mdash;select multiple pieces in your cart to see the applied savings. Why You&rsquo;ll Love It The vivid red of the rubies paired with sparkling diamond accents creates a classic, eye-catching combination that complements both warm and cool skin tones. Lightweight and refined, this bracelet adds instant polish to a business look, cocktail attire, or special-occasion ensemble. Sizing &amp; Fit Available in standard bracelet lengths. Please select your preferred size from the options on the product page. If you&rsquo;re unsure which size to choose, measure your wrist where you would normally wear a bracelet and add 0.5\"&ndash;1\" for a comfortable fit. Care &amp; Maintenance Avoid exposure to harsh chemicals, perfumes, and chlorinated water to preserve the finish. Wipe with a soft, dry cloth after wear to remove oils and restore shine. Store separately in a soft pouch or jewelry box to prevent scratches. Perfect For Birthday or anniversary gifts, bridal or bridesmaid jewelry, milestone celebrations, or simply treating yourself to a refined everyday piece. Each bracelet makes an elegant, thoughtful present and pairs beautifully with matching earrings or a pendant. Additional Information Available finishes: gold tone, silver tone, rose gold. Silver tone qualifies for an automatic quantity discount&mdash;add multiple to your cart to receive the reduced price. For custom requests or bulk orders, please contact our customer service team. Add a touch of timeless luxury to your jewelry collection. Choose your metal tone and size, then click &ldquo;Add to Cart&rdquo; to order. &nbsp; event events ticket tickets experience seminar workshop admission registration Bella Luna', 0, 'Ruby and Diamond Bracelet', 'Ruby and diamond bracelet. Elegant fine jewellery.', 'Ruby and Diamond Bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:46', '2026-08-04 00:11:43', 1, 1, 0.00, 0, 1, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (13, 5, 'Sapphire, Ruby And Emerald Bracelet', 'Demonstrates product customization and personalization.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Sapphire, Ruby and Emerald Bracelet</h2>\n<p>Make a lasting impression with this stunning multistone bracelet, where vivid blue sapphires, deep red rubies, and lush green emeralds are artfully set in fine gold. The rich, contrasting colors create a timeless yet bold statement piece that lifts both day and evening looks.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Precious gemstones:</strong> Vivid sapphires, intense rubies and vibrant emeralds selected for color and brilliance.</li>\n<li><strong>Fine gold setting:</strong> Expertly crafted in fine gold for secure settings and a warm, luxurious finish.</li>\n<li><strong>Handcrafted finish:</strong> Precision stone-setting and polished details for a refined, long-lasting piece.</li>\n<li><strong>Versatile design:</strong> Elegant enough for formal occasions yet bold enough to elevate everyday outfits.</li>\n</ul>\n<h3>Why You\'ll Love It</h3>\n<p>This bracelet blends classic gemstone beauty with contemporary design. The contrasting trio of sapphires, rubies and emeralds creates visual depth and movement&mdash;perfect for anyone who appreciates color, craftsmanship and a piece that can be handed down as an heirloom. It&rsquo;s an ideal choice for anniversaries, milestone celebrations or as a standout addition to your personal jewelry collection.</p>\n<h3>Style Suggestions</h3>\n<ul>\n<li>Wear alone as a focal point with a simple evening dress or tailored blazer.</li>\n<li>Layer with slender gold bangles for a modern stacked look.</li>\n<li>Pair with matching gemstone studs or a delicate pendant for a coordinated set.</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Remove before showering, swimming or using household chemicals.</li>\n<li>Clean gently with a soft, lint-free cloth; avoid harsh abrasives and ultrasonic cleaners for emeralds unless advised by a jeweler.</li>\n<li>Store separately in a soft pouch or the original box to prevent scratches.</li>\n</ul>\n</div>\n<p>&nbsp;</p>', 'Sapphire, Ruby And Emerald Bracelet sapphire bracelet Sapphire, Ruby And Emerald Bracelet Sapphire, ruby and emerald bracelet. Fine jewellery. Demonstrates product customization and personalization. Sapphire, Ruby and Emerald Bracelet Make a lasting impression with this stunning multistone bracelet, where vivid blue sapphires, deep red rubies, and lush green emeralds are artfully set in fine gold. The rich, contrasting colors create a timeless yet bold statement piece that lifts both day and evening looks. Key Features Precious gemstones: Vivid sapphires, intense rubies and vibrant emeralds selected for color and brilliance. Fine gold setting: Expertly crafted in fine gold for secure settings and a warm, luxurious finish. Handcrafted finish: Precision stone-setting and polished details for a refined, long-lasting piece. Versatile design: Elegant enough for formal occasions yet bold enough to elevate everyday outfits. Why You\'ll Love It This bracelet blends classic gemstone beauty with contemporary design. The contrasting trio of sapphires, rubies and emeralds creates visual depth and movement&mdash;perfect for anyone who appreciates color, craftsmanship and a piece that can be handed down as an heirloom. It&rsquo;s an ideal choice for anniversaries, milestone celebrations or as a standout addition to your personal jewelry collection. Style Suggestions Wear alone as a focal point with a simple evening dress or tailored blazer. Layer with slender gold bangles for a modern stacked look. Pair with matching gemstone studs or a delicate pendant for a coordinated set. Care &amp; Maintenance Remove before showering, swimming or using household chemicals. Clean gently with a soft, lint-free cloth; avoid harsh abrasives and ultrasonic cleaners for emeralds unless advised by a jeweler. Store separately in a soft pouch or the original box to prevent scratches. &nbsp; event events ticket tickets experience seminar workshop admission registration Excelsior', 0, 'Sapphire, Ruby And Emerald Bracelet', 'Sapphire, ruby and emerald bracelet. Fine jewellery.', 'sapphire bracelet', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:47', '2026-08-03 23:00:12', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (14, NULL, 'Jewelry Cleaning eBOOK', 'Sample digital download item example. Downloads can be distributed via a secure link to a local secure folder, an s3 expiring download link or an CDN (direct URL).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Jewelry Cleaning eBOOK &mdash; Professional Care, Simple Steps</h2>\n<p>Keep your fine jewelry brilliant and damage-free with this easy-to-follow downloadable eBook. Whether you own diamonds, pearls, gold, silver or mixed-metal pieces, this guide gives clear, practical instructions and preventative care tips so your treasured items look their best for years to come. Instant digital delivery after purchase.</p>\n<h3>Why this eBook?</h3>\n<ul>\n<li>Practical, step-by-step cleaning methods you can do at home without expensive tools</li>\n<li>Safe care guidance for diamonds, colored gemstones, pearls, gold, silver and plated jewelry</li>\n<li>Storage and maintenance routines that prevent tarnish, scratches and wear</li>\n<li>Quick troubleshooting for common issues (tarnish, cloudiness, loose settings)</li>\n<li>Cost-saving tips that reduce unnecessary professional cleanings</li>\n</ul>\n<h3>What&rsquo;s inside</h3>\n<ul>\n<li>Easy-to-follow cleaning procedures for each metal and gemstone type</li>\n<li>Recommended supplies and an affordable tools checklist</li>\n<li>Step-by-step polishing and buffing techniques</li>\n<li>How to clean delicate materials such as pearls and opals</li>\n<li>Storage solutions to prevent knots, scratches and corrosion</li>\n<li>When to seek professional repair or inspection</li>\n<li>A maintenance schedule you can follow (daily, monthly, yearly)</li>\n<li>Common mistakes to avoid and what household products to never use</li>\n</ul>\n<h3>Who should read this eBook?</h3>\n<ul>\n<li>Anyone who owns fine jewelry and wants to preserve its value and appearance</li>\n<li>Gift givers who want to keep heirlooms and special pieces in top condition</li>\n<li>Collectors of vintage or costume pieces needing safe care methods</li>\n<li>Small boutique owners or caretakers looking for reliable cleaning routines</li>\n</ul>\n<h3>Immediate delivery &amp; compatibility</h3>\n<p>After purchase you&rsquo;ll receive instant access to a downloadable eBook file. The file is compatible with most computers, tablets and e-readers. Download instructions and a link are provided in your order confirmation and stored in your account for convenient re-download.</p>\n<h3>Easy to use</h3>\n<ul>\n<li>Clear language and organized sections so you can find answers fast</li>\n<li>Actionable checklists to follow when cleaning or packing jewelry</li>\n<li>No specialist training required &mdash; ideal for beginners and experienced owners alike</li>\n</ul>\n<h3>Support</h3>\n<p>If you have any trouble downloading or opening your eBook, our customer support team is ready to help. Contact details are included with your purchase confirmation.</p>\n<p><strong>Protect your investment and keep every piece shining.</strong> Download the Jewelry Cleaning eBook now and start caring for your jewelry the right way.</p>\n</div>\n<p>&nbsp;</p>', 'Jewelry Cleaning eBOOK jewelry care guide pdf Jewelry Cleaning eBOOK Download our jewellery cleaning eBook. Instant digital delivery. Sample digital download item example. Downloads can be distributed via a secure link to a local secure folder, an s3 expiring download link or an CDN (direct URL). Jewelry Cleaning eBOOK &mdash; Professional Care, Simple Steps Keep your fine jewelry brilliant and damage-free with this easy-to-follow downloadable eBook. Whether you own diamonds, pearls, gold, silver or mixed-metal pieces, this guide gives clear, practical instructions and preventative care tips so your treasured items look their best for years to come. Instant digital delivery after purchase. Why this eBook? Practical, step-by-step cleaning methods you can do at home without expensive tools Safe care guidance for diamonds, colored gemstones, pearls, gold, silver and plated jewelry Storage and maintenance routines that prevent tarnish, scratches and wear Quick troubleshooting for common issues (tarnish, cloudiness, loose settings) Cost-saving tips that reduce unnecessary professional cleanings What&rsquo;s inside Easy-to-follow cleaning procedures for each metal and gemstone type Recommended supplies and an affordable tools checklist Step-by-step polishing and buffing techniques How to clean delicate materials such as pearls and opals Storage solutions to prevent knots, scratches and corrosion When to seek professional repair or inspection A maintenance schedule you can follow (daily, monthly, yearly) Common mistakes to avoid and what household products to never use Who should read this eBook? Anyone who owns fine jewelry and wants to preserve its value and appearance Gift givers who want to keep heirlooms and special pieces in top condition Collectors of vintage or costume pieces needing safe care methods Small boutique owners or caretakers looking for reliable cleaning routines Immediate delivery &amp; compatibility After purchase you&rsquo;ll receive instant access to a downloadable eBook file. The file is compatible with most computers, tablets and e-readers. Download instructions and a link are provided in your order confirmation and stored in your account for convenient re-download. Easy to use Clear language and organized sections so you can find answers fast Actionable checklists to follow when cleaning or packing jewelry No specialist training required &mdash; ideal for beginners and experienced owners alike Support If you have any trouble downloading or opening your eBook, our customer support team is ready to help. Contact details are included with your purchase confirmation. Protect your investment and keep every piece shining. Download the Jewelry Cleaning eBook now and start caring for your jewelry the right way. &nbsp; event events ticket tickets experience seminar workshop admission registration', 0, 'Jewelry Cleaning eBOOK', 'Download our jewellery cleaning eBook. Instant digital delivery.', 'jewelry-care-guide-pdf', 0, 1, 1, 0, NULL, NULL, 0, NULL, 0, 1, NULL, 1, '2026-07-20 13:52:47', '2026-07-25 23:09:25', 1, 1, 0.00, 1, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (15, NULL, 'Jewelry Repair Webinar + Guidebook', 'This sample item demonstrates both the preview video feature (video layout) plus how a video can be displayed after purchase along with any corresponding media such as complimentary PDFs, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Jewelry Repair Webinar + Guidebook</h2>\n<p>This sample product includes a demo, pre-recorded webinar file, a downloadable guide and a preview guide link below. The order download and webinar viewing is controlled via the order security while the download below is a download shortcode via the CMS downloads manager which allows you to add secure download links to any product or site page.</p>\n<h3>What&rsquo;s included</h3>\n<ul>\n<li><strong>Recorded webinar:</strong> Full-length, step-by-step video demonstrations you can stream or download after purchase.</li>\n<li><strong>Comprehensive guidebook (PDF):</strong> Detailed, printable eBook that mirrors the webinar workflow and includes space for your notes.<br>[download:d971912d-cb2e-4790-98ae-8ec53bac2503 label=\"Preview the Guidebook\"]</li>\n<li><strong>Practical checklists &amp; worksheets:</strong> Tool lists, safety reminders, and project checklists to use at the bench.</li>\n<li><strong>Sample media &amp; resources:</strong> Example repair plans and reference diagrams provided as downloadable PDFs.</li>\n</ul>\n<h3>Techniques &amp; skills you&rsquo;ll learn</h3>\n<ul>\n<li>Soldering basics and tips for clean joins on gold and silver</li>\n<li>Chain and clasp repair, cleaning and reconditioning</li>\n<li>Ring resizing fundamentals and sizing best practices</li>\n<li>Prong repair, re-tipping and tightening for secure settings</li>\n<li>Bezel and flush setting adjustments for different gemstones</li>\n<li>Polishing, finishing and surface repair techniques</li>\n<li>Simple troubleshooting and how to avoid common mistakes</li>\n</ul>\n<h3>Who this is for</h3>\n<ul>\n<li>Beginner bench jewelers looking for structured, visual training</li>\n<li>Experienced makers wanting quick refreshers on best practices</li>\n<li>Small business owners and repair-ready retailers</li>\n<li>Anyone who wants to repair personal or sentimental pieces at home</li>\n</ul>\n<h3>How it works</h3>\n<ul>\n<li>Instant digital access after purchase &mdash; no physical product will be shipped.</li>\n<li>Stream the recorded webinar from your account or download the files for offline viewing.</li>\n<li>Follow along with the eBook and use the included worksheets to take your own notes and track progress.</li>\n</ul>\n<h3>Technical details &amp; system requirements</h3>\n<ul>\n<li>Video format: MP4 (streaming and downloadable)</li>\n<li>Guidebook: PDF (printable)</li>\n<li>Compatible with modern browsers, desktop or mobile devices; requires a PDF reader and basic internet connection for streaming or downloading.</li>\n</ul>\n<h3>Why this bundle works</h3>\n<p>This combination of visual demonstration plus a detailed written guide lets you watch repairs being performed in real time, then follow the same steps at your bench with clear, printable instructions. The practical focus means you&rsquo;ll gain usable techniques you can apply immediately&mdash;whether repairing a cherished heirloom or offering repair services to customers.</p>\n<p><strong>Ready to get started?</strong> Purchase now for instant access and begin learning practical jewelry repair techniques today.</p>\n</div>\n<p>&nbsp;</p>', 'Jewelry Repair Webinar + Guidebook jewelry repair webinar guidebook Jewelry Repair Webinar Plus eBook Jewellery repair webinar plus eBook. Digital download bundle. This sample item demonstrates both the preview video feature (video layout) plus how a video can be displayed after purchase along with any corresponding media such as complimentary PDFs, etc. Jewelry Repair Webinar + Guidebook This sample product includes a demo, pre-recorded webinar file, a downloadable guide and a preview guide link below. The order download and webinar viewing is controlled via the order security while the download below is a download shortcode via the CMS downloads manager which allows you to add secure download links to any product or site page. What&rsquo;s included Recorded webinar: Full-length, step-by-step video demonstrations you can stream or download after purchase. Comprehensive guidebook (PDF): Detailed, printable eBook that mirrors the webinar workflow and includes space for your notes. Practical checklists &amp; worksheets: Tool lists, safety reminders, and project checklists to use at the bench. Sample media &amp; resources: Example repair plans and reference diagrams provided as downloadable PDFs. Techniques &amp; skills you&rsquo;ll learn Soldering basics and tips for clean joins on gold and silver Chain and clasp repair, cleaning and reconditioning Ring resizing fundamentals and sizing best practices Prong repair, re-tipping and tightening for secure settings Bezel and flush setting adjustments for different gemstones Polishing, finishing and surface repair techniques Simple troubleshooting and how to avoid common mistakes Who this is for Beginner bench jewelers looking for structured, visual training Experienced makers wanting quick refreshers on best practices Small business owners and repair-ready retailers Anyone who wants to repair personal or sentimental pieces at home How it works Instant digital access after purchase &mdash; no physical product will be shipped. Stream the recorded webinar from your account or download the files for offline viewing. Follow along with the eBook and use the included worksheets to take your own notes and track progress. Technical details &amp; system requirements Video format: MP4 (streaming and downloadable) Guidebook: PDF (printable) Compatible with modern browsers, desktop or mobile devices; requires a PDF reader and basic internet connection for streaming or downloading. Why this bundle works This combination of visual demonstration plus a detailed written guide lets you watch repairs being performed in real time, then follow the same steps at your bench with clear, printable instructions. The practical focus means you&rsquo;ll gain usable techniques you can apply immediately&mdash;whether repairing a cherished heirloom or offering repair services to customers. Ready to get started? Purchase now for instant access and begin learning practical jewelry repair techniques today. &nbsp;', 0, 'Jewelry Repair Webinar Plus eBook', 'Jewellery repair webinar plus eBook. Digital download bundle.', 'jewelry-repair-webinar-guidebook', 0, 1, 0, 0, '[page:13]', 'View Webinar', 0, NULL, 0, 0, NULL, 3, '2026-07-20 13:52:47', '2026-07-26 22:38:18', 1, 1, 0.00, 1, 0, 0, 'Select Option:', '<div style=\"padding:56.25% 0 0 0;position:relative;\"><iframe src=\"https://player.vimeo.com/video/1213077313?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" frameborder=\"0\" allow=\"autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share\" referrerpolicy=\"strict-origin-when-cross-origin\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" title=\"Sample Preview Video For Demo Store\"></iframe></div><script src=\"https://player.vimeo.com/api/player.js\"></script>', 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (17, 2, 'Men\'s Sweatshirt', 'Premium heavyweight sweatshirt with our logo. Sizes S-XXL. (XXL +$5) (Example product using layout with right side images)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">Our premium heavyweight Men\'s Sweatshirt combines classic comfort with clean, everyday style. Built for cool-weather layering and everyday wear, it features our signature logo and is available in three versatile colors: Black, Burgundy, and White.\n<section>\n<h2>Key Features</h2>\n<ul>\n<li>Premium heavyweight construction for warmth and lasting comfort</li>\n<li>Signature logo for a timeless, understated look</li>\n<li>Available in Black, Burgundy, and White</li>\n<li>Sizes: S, M, L, XL, XXL <strong>(XXL +$5)</strong></li>\n<li>Machine washable for easy care</li>\n</ul>\n</section>\n<section>\n<h2>Fit &amp; Sizing&nbsp;</h2>\n<p>Designed for a comfortable, everyday fit that layers easily over tees and under jackets. Choose your usual size. If you prefer a roomier feel, consider sizing up.</p>\n<p><strong>Available sizes:</strong> Small &bull; Medium &bull; Large &bull; XL &bull; XXL (<strong>add $5.00</strong>)</p>\n</section>\n<section>\n<h2>Care Instructions</h2>\n<p>Machine washable for simple upkeep. For best results, wash with like colors and tumble dry on low or air dry to maintain the sweatshirt&rsquo;s finish and fit.</p>\n</section>\n<section>\n<h2>Why You\'ll Love It</h2>\n<ul>\n<li>Reliable heavyweight warmth without bulk &mdash; perfect for cooler days</li>\n<li>Versatile colors that pair easily with jeans, joggers, or layered outfits</li>\n<li>An ideal gift: practical, stylish, and ready to wear</li>\n</ul>\n</section>\n<p><strong>Note:</strong> Select XXL to add $5 to your order. Add this wardrobe staple to your cart today &mdash; a premium sweatshirt designed for comfort, durability, and everyday style.</p>\n</div>\n<p>&nbsp;</p>', 'Men\'s Sweatshirt prestige design sweatshirt Men\'s Sweatshirt Men\'s premium sweatshirt. Multiple colors and sizes. Premium heavyweight sweatshirt with our logo. Sizes S-XXL. (XXL +$5) (Example product using layout with right side images) Our premium heavyweight Men\'s Sweatshirt combines classic comfort with clean, everyday style. Built for cool-weather layering and everyday wear, it features our signature logo and is available in three versatile colors: Black, Burgundy, and White. Key Features Premium heavyweight construction for warmth and lasting comfort Signature logo for a timeless, understated look Available in Black, Burgundy, and White Sizes: S, M, L, XL, XXL (XXL +$5) Machine washable for easy care Fit &amp; Sizing&nbsp; Designed for a comfortable, everyday fit that layers easily over tees and under jackets. Choose your usual size. If you prefer a roomier feel, consider sizing up. Available sizes: Small &bull; Medium &bull; Large &bull; XL &bull; XXL (add $5.00) Care Instructions Machine washable for simple upkeep. For best results, wash with like colors and tumble dry on low or air dry to maintain the sweatshirt&rsquo;s finish and fit. Why You\'ll Love It Reliable heavyweight warmth without bulk &mdash; perfect for cooler days Versatile colors that pair easily with jeans, joggers, or layered outfits An ideal gift: practical, stylish, and ready to wear Note: Select XXL to add $5 to your order. Add this wardrobe staple to your cart today &mdash; a premium sweatshirt designed for comfort, durability, and everyday style. &nbsp; DeMarco', 0, 'Men\'s Sweatshirt', 'Men\'s premium sweatshirt. Multiple colors and sizes.', 'prestige-design-sweatshirt', 0, 1, 0, 0, NULL, NULL, 0, NULL, 1, 0, NULL, 1, '2026-07-20 13:52:47', '2026-08-04 16:49:07', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (18, 5, 'Vintage Pocket Watch', 'Sample item with gift wrapping option enabled.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Vintage Pocket Watch &mdash; Classic Elegance with Intricate Engraving</h2>\n<p>Make every moment memorable with this beautifully crafted Vintage Pocket Watch. Finished in an antique style and detailed with a finely engraved metal casing, this timepiece blends traditional craftsmanship with timeless design. The clear Roman numeral dial and subtle vintage accents make it an outstanding choice for collectors, special occasions, or anyone who appreciates heirloom-quality style.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Intricate engraved casing:</strong> Detailed ornamental engraving gives each watch a distinctive, classic look.</li>\n<li><strong>Roman numeral dial:</strong> Easy-to-read, elegant Roman numerals for a traditional aesthetic.</li>\n<li><strong>Durable construction:</strong> Solid metal case with an antique finish designed to stand up to everyday use while retaining vintage charm.</li>\n<li><strong>Reliable timekeeping:</strong> Built with a precise movement to keep accurate time for daily wear or display.</li>\n<li><strong>Versatile style:</strong> Refined enough for formal events yet rugged enough to wear as an everyday statement piece.</li>\n</ul>\n<h3>Why You&rsquo;ll Love It</h3>\n<p>This Vintage Pocket Watch delivers the look and feel of an authentic heirloom without sacrificing practicality. Its engraved casing and classic face provide an unmistakable sense of history and character, making it a standout accessory for suits, jackets, or display in a collection. It&rsquo;s an ideal gift for vintage enthusiasts, groomsmen, or anyone who favors timeless accessories.</p>\n<h3>Perfect For</h3>\n<ul>\n<li>Collectors seeking a classic addition to their collection</li>\n<li>Gifts for anniversaries, birthdays, weddings, and graduations</li>\n<li>Formal events, reenactments, or themed gatherings</li>\n<li>Everyday wearers who appreciate vintage style</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Wipe with a soft, dry cloth to remove fingerprints and dust.</li>\n<li>Keep away from strong magnetic fields and prolonged exposure to moisture.</li>\n<li>Store in a dry place when not in use to preserve the finish and movement.</li>\n<li>Have the watch serviced by a professional if you notice irregular timekeeping.</li>\n</ul>\n<h3>What&rsquo;s Included</h3>\n<ul>\n<li>Vintage Pocket Watch (engraved casing with Roman numeral dial)</li>\n<li>Instruction card with basic care tips</li>\n</ul>\n<p>This vintage-inspired pocket watch blends form and function to create a lasting impression &mdash; whether added to a collection or given as a meaningful gift. Add it to your cart to own a piece that looks and feels like a cherished classic.</p>\n</div>\n<p>&nbsp;</p>', 'Vintage Pocket Watch vintage pocket watch Vintage Pocket Watch Vintage pocket watch. Classic engraved casing. Sample item with gift wrapping option enabled. Vintage Pocket Watch &mdash; Classic Elegance with Intricate Engraving Make every moment memorable with this beautifully crafted Vintage Pocket Watch. Finished in an antique style and detailed with a finely engraved metal casing, this timepiece blends traditional craftsmanship with timeless design. The clear Roman numeral dial and subtle vintage accents make it an outstanding choice for collectors, special occasions, or anyone who appreciates heirloom-quality style. Key Features Intricate engraved casing: Detailed ornamental engraving gives each watch a distinctive, classic look. Roman numeral dial: Easy-to-read, elegant Roman numerals for a traditional aesthetic. Durable construction: Solid metal case with an antique finish designed to stand up to everyday use while retaining vintage charm. Reliable timekeeping: Built with a precise movement to keep accurate time for daily wear or display. Versatile style: Refined enough for formal events yet rugged enough to wear as an everyday statement piece. Why You&rsquo;ll Love It This Vintage Pocket Watch delivers the look and feel of an authentic heirloom without sacrificing practicality. Its engraved casing and classic face provide an unmistakable sense of history and character, making it a standout accessory for suits, jackets, or display in a collection. It&rsquo;s an ideal gift for vintage enthusiasts, groomsmen, or anyone who favors timeless accessories. Perfect For Collectors seeking a classic addition to their collection Gifts for anniversaries, birthdays, weddings, and graduations Formal events, reenactments, or themed gatherings Everyday wearers who appreciate vintage style Care &amp; Maintenance Wipe with a soft, dry cloth to remove fingerprints and dust. Keep away from strong magnetic fields and prolonged exposure to moisture. Store in a dry place when not in use to preserve the finish and movement. Have the watch serviced by a professional if you notice irregular timekeeping. What&rsquo;s Included Vintage Pocket Watch (engraved casing with Roman numeral dial) Instruction card with basic care tips This vintage-inspired pocket watch blends form and function to create a lasting impression &mdash; whether added to a collection or given as a meaningful gift. Add it to your cart to own a piece that looks and feels like a cherished classic. &nbsp; event events ticket tickets experience seminar workshop admission registration Excelsior', 0, 'Vintage Pocket Watch', 'Vintage pocket watch. Classic engraved casing.', 'vintage-pocket-watch', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:47', '2026-08-03 23:41:35', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (19, 5, 'Fashion Wrist Watch', 'Sample watch with color selectors that are  not pills but variant radio groups. Also uses a non-default out of stock message.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<h2>Fashion Wrist Watch</h2>\n<p>A sleek, modern timepiece crafted for everyday style. The Fashion Wrist Watch pairs a polished stainless‑steel case with interchangeable straps so you can switch between casual and refined looks in seconds. Available with Black and Brown straps &mdash; it&rsquo;s an effortless way to elevate any outfit.</p>\n<h3>Why you&rsquo;ll love it</h3>\n<ul>\n<li><strong>Contemporary design:</strong> Clean dial and slim profile deliver a modern, versatile aesthetic that transitions from day to night.</li>\n<li><strong>Durable construction:</strong> Stainless‑steel case provides strength and a premium finish that holds up to daily wear.</li>\n<li><strong>Interchangeable straps:</strong> Swap straps quickly to customize your look without tools (quick‑release style).</li>\n<li><strong>Classic color options:</strong> Choose Black or Brown to match your personal style or wardrobe.</li>\n</ul>\n<h3>Product details</h3>\n<ul>\n<li>Case material: Stainless steel</li>\n<li>Strap: Interchangeable (included)</li>\n<li>Available colors: Black, Brown, White</li>\n<li>Style: Modern, unisex</li>\n</ul>\n<h3>How to select your color</h3>\n<p>Color options are available as variant radio groups on the product page. Select your preferred color by choosing the corresponding radio button under \"Color.\" (Note: these are presented as radio options rather than pill‑style swatches.)</p>\n<h3>Sizing &amp; fit</h3>\n<p>The adjustable strap fits most wrist sizes. For a tailored fit, remove links or adjust the buckle as needed. If you need precise measurements or help finding the right fit, check our size guide or contact customer support.</p>\n<h3>Care &amp; maintenance</h3>\n<ul>\n<li>Avoid prolonged exposure to moisture and extreme temperatures.</li>\n<li>Wipe the case and strap with a soft, dry cloth to remove dirt and oils.</li>\n<li>Store in a dry place when not in use to preserve finish and longevity.</li>\n</ul>\n<h3>What&rsquo;s included</h3>\n<ul>\n<li>Fashion Wrist Watch (case + primary strap)</li>\n<li>User card with basic care instructions</li>\n</ul>\n<p>Thoughtfully designed for versatility and everyday wear, the Fashion Wrist Watch is a refined accessory that adapts to your style. Select your color via the radio group and create the look you want&mdash;effortlessly.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Fashion Wrist Watch fashion wrist watch Fashion Wrist Watch Fashion wrist watch. Multiple strap options. Sample watch with color selectors that are not pills but variant radio groups. Also uses a non-default out of stock message. Fashion Wrist Watch A sleek, modern timepiece crafted for everyday style. The Fashion Wrist Watch pairs a polished stainless‑steel case with interchangeable straps so you can switch between casual and refined looks in seconds. Available with Black and Brown straps &mdash; it&rsquo;s an effortless way to elevate any outfit. Why you&rsquo;ll love it Contemporary design: Clean dial and slim profile deliver a modern, versatile aesthetic that transitions from day to night. Durable construction: Stainless‑steel case provides strength and a premium finish that holds up to daily wear. Interchangeable straps: Swap straps quickly to customize your look without tools (quick‑release style). Classic color options: Choose Black or Brown to match your personal style or wardrobe. Product details Case material: Stainless steel Strap: Interchangeable (included) Available colors: Black, Brown, White Style: Modern, unisex How to select your color Color options are available as variant radio groups on the product page. Select your preferred color by choosing the corresponding radio button under \"Color.\" (Note: these are presented as radio options rather than pill‑style swatches.) Sizing &amp; fit The adjustable strap fits most wrist sizes. For a tailored fit, remove links or adjust the buckle as needed. If you need precise measurements or help finding the right fit, check our size guide or contact customer support. Care &amp; maintenance Avoid prolonged exposure to moisture and extreme temperatures. Wipe the case and strap with a soft, dry cloth to remove dirt and oils. Store in a dry place when not in use to preserve finish and longevity. What&rsquo;s included Fashion Wrist Watch (case + primary strap) User card with basic care instructions Thoughtfully designed for versatility and everyday wear, the Fashion Wrist Watch is a refined accessory that adapts to your style. Select your color via the radio group and create the look you want&mdash;effortlessly. &nbsp; Excelsior', 0, 'Fashion Wrist Watch', 'Fashion wrist watch. Multiple strap options.', 'fashion-wrist-watch', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, 1, 1, '2026-07-20 13:52:47', '2026-08-04 16:12:14', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (20, 2, 'Premium Office Pens 2 Pack', 'Premium gift-boxed office pens — set of 2 with engraving option. (Sample product with personalization feature enabled.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Premium Office Pens &mdash; 2 Pack (Gift-Boxed, Optional Engraving)</h2>\n<p>Elevate everyday writing with this set of two premium ballpoint pens, presented in an elegant gift-ready box. Designed for smooth, reliable performance and refined presentation, these pens make a thoughtful corporate gift or a personal keepsake when personalized with optional engraving.</p>\n<h3>What&rsquo;s included</h3>\n<ul>\n<li>Set of 2 premium ballpoint pens</li>\n<li>Elegant gift-ready presentation box</li>\n<li>Optional engraving available to personalize each pen</li>\n</ul>\n<h3>Key benefits</h3>\n<ul>\n<li><strong>Smooth, consistent writing:</strong> Dependable ballpoint performance for signatures, notes, and daily use.</li>\n<li><strong>Professional presentation:</strong> Packaged in a gift-ready box&mdash;perfect for client gifts, employee recognition, graduations, and special occasions.</li>\n<li><strong>Personalized touch:</strong> Add optional engraving to create a memorable, one-of-a-kind gift.</li>\n<li><strong>Versatile use:</strong> Ideal for office desks, meeting rooms, and home workspaces.</li>\n</ul>\n<h3>Personalization details</h3>\n<p>Add a meaningful name, date, or short message with the optional engraving service. Select the personalization option when ordering and enter the text you want engraved. Personalized pens are crafted to order&mdash;please review your entry carefully before completing checkout.</p>\n<h3>Perfect for gifting</h3>\n<p>Whether you&rsquo;re recognizing a colleague, thanking a client, or celebrating a milestone, this 2-pack of premium office pens delivers thoughtful style and practical value. The ready-to-gift packaging and engraving option make it simple to create an impressive, memorable present.</p>\n<p><strong>Order now</strong> to secure a professional, gift-ready set&mdash;choose engraving to make your gift uniquely yours.</p>\n</div>\n<p>&nbsp;</p>', 'Premium Office Pens 2 Pack premium office pens 2 pack Premium Office Pens 2 Pack Premium office pens 2 pack. Gift box included. Premium gift-boxed office pens — set of 2 with engraving option. (Sample product with personalization feature enabled.) Premium Office Pens &mdash; 2 Pack (Gift-Boxed, Optional Engraving) Elevate everyday writing with this set of two premium ballpoint pens, presented in an elegant gift-ready box. Designed for smooth, reliable performance and refined presentation, these pens make a thoughtful corporate gift or a personal keepsake when personalized with optional engraving. What&rsquo;s included Set of 2 premium ballpoint pens Elegant gift-ready presentation box Optional engraving available to personalize each pen Key benefits Smooth, consistent writing: Dependable ballpoint performance for signatures, notes, and daily use. Professional presentation: Packaged in a gift-ready box&mdash;perfect for client gifts, employee recognition, graduations, and special occasions. Personalized touch: Add optional engraving to create a memorable, one-of-a-kind gift. Versatile use: Ideal for office desks, meeting rooms, and home workspaces. Personalization details Add a meaningful name, date, or short message with the optional engraving service. Select the personalization option when ordering and enter the text you want engraved. Personalized pens are crafted to order&mdash;please review your entry carefully before completing checkout. Perfect for gifting Whether you&rsquo;re recognizing a colleague, thanking a client, or celebrating a milestone, this 2-pack of premium office pens delivers thoughtful style and practical value. The ready-to-gift packaging and engraving option make it simple to create an impressive, memorable present. Order now to secure a professional, gift-ready set&mdash;choose engraving to make your gift uniquely yours. &nbsp; DeMarco', 0, 'Premium Office Pens 2 Pack', 'Premium office pens 2 pack. Gift box included.', 'premium-office-pens-2-pack', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:47', '2026-07-26 22:06:34', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (21, 2, 'Silver Jewelry Box', 'Sample product with qty discount applied.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p>Keep your most treasured pieces safe, organised and beautifully displayed with the Silver Jewelry Box &mdash; an elegant silver-toned jewellery storage solution finished with a soft velvet interior. Thoughtfully designed for everyday use and special occasions alike, this box combines refined styling with practical storage to protect rings, earrings, bracelets and more.</p>\n<h2>Features</h2>\n<ul>\n<li><strong>Elegant silver-toned exterior</strong> &mdash; a polished finish that complements any bedroom or dressing area.</li>\n<li><strong>Soft velvet lining</strong> &mdash; gentle protection to prevent scratches and tarnish on delicate metals and gemstones.</li>\n<li><strong>Multiple compartments</strong> &mdash; dedicated ring rolls and divided sections to keep earrings, bracelets and small watches organised and tangle-free.</li>\n<li><strong>Removable/adjustable trays</strong> &mdash; create a custom layout to suit your collection and access pieces easily.</li>\n<li><strong>Lockable lid</strong> &mdash; added security and peace of mind when storing valuable items.</li>\n<li><strong>Compact, display-ready design</strong> &mdash; sits neatly on a dresser, vanity or shelf while presenting your jewellery with style.</li>\n</ul>\n<h2>Why you&rsquo;ll love it</h2>\n<ul>\n<li>Protects delicate finishes and gemstones with plush velvet cushioning.</li>\n<li>Saves time by keeping your collection organised and easy to find.</li>\n<li>Stylish enough to double as a decorative accent in your home.</li>\n<li>An ideal, thoughtful gift for anniversaries, birthdays, bridesmaids or anyone who cherishes their jewellery.</li>\n</ul>\n<h2>Good to know</h2>\n<ul>\n<li><strong>Interior:</strong> soft velvet lining</li>\n<li><strong>Exterior:</strong> durable silver-toned finish</li>\n<li><strong>Storage:</strong> ring rolls, divided compartments and removable sections for flexible organization</li>\n<li><strong>Security:</strong> lockable lid for added protection</li>\n</ul>\n<h2>Care &amp; maintenance</h2>\n<ul>\n<li>Wipe the exterior with a soft, dry cloth to remove dust; avoid harsh chemicals or abrasive cleaners.</li>\n<li>Spot-clean the velvet lining with a soft brush or lint roller; for deeper cleaning consult a textile care specialist.</li>\n<li>Store the box in a cool, dry place away from direct sunlight to preserve the finish.</li>\n</ul>\n<h2>Perfect for gifting</h2>\n<p>Presented in a versatile, elegant style, the Silver Jewelry Box makes a thoughtful gift for engagements, weddings, holidays or any milestone. Pair it with a favourite necklace or a new pair of earrings to create a memorable present.</p>\n<p><strong>What&rsquo;s included:</strong> Silver Jewelry Box with velvet lining and internal compartments.</p>\n<p>Organise with elegance &mdash; add the Silver Jewelry Box to your collection today.</p>\n</div>', 'Silver Jewelry Box silver jewelry box Silver Jewelry Box Silver jewelry box with velvet lining. Elegant storage solution. Sample product with qty discount applied. Keep your most treasured pieces safe, organised and beautifully displayed with the Silver Jewelry Box &mdash; an elegant silver-toned jewellery storage solution finished with a soft velvet interior. Thoughtfully designed for everyday use and special occasions alike, this box combines refined styling with practical storage to protect rings, earrings, bracelets and more. Features Elegant silver-toned exterior &mdash; a polished finish that complements any bedroom or dressing area. Soft velvet lining &mdash; gentle protection to prevent scratches and tarnish on delicate metals and gemstones. Multiple compartments &mdash; dedicated ring rolls and divided sections to keep earrings, bracelets and small watches organised and tangle-free. Removable/adjustable trays &mdash; create a custom layout to suit your collection and access pieces easily. Lockable lid &mdash; added security and peace of mind when storing valuable items. Compact, display-ready design &mdash; sits neatly on a dresser, vanity or shelf while presenting your jewellery with style. Why you&rsquo;ll love it Protects delicate finishes and gemstones with plush velvet cushioning. Saves time by keeping your collection organised and easy to find. Stylish enough to double as a decorative accent in your home. An ideal, thoughtful gift for anniversaries, birthdays, bridesmaids or anyone who cherishes their jewellery. Good to know Interior: soft velvet lining Exterior: durable silver-toned finish Storage: ring rolls, divided compartments and removable sections for flexible organization Security: lockable lid for added protection Care &amp; maintenance Wipe the exterior with a soft, dry cloth to remove dust; avoid harsh chemicals or abrasive cleaners. Spot-clean the velvet lining with a soft brush or lint roller; for deeper cleaning consult a textile care specialist. Store the box in a cool, dry place away from direct sunlight to preserve the finish. Perfect for gifting Presented in a versatile, elegant style, the Silver Jewelry Box makes a thoughtful gift for engagements, weddings, holidays or any milestone. Pair it with a favourite necklace or a new pair of earrings to create a memorable present. What&rsquo;s included: Silver Jewelry Box with velvet lining and internal compartments. Organise with elegance &mdash; add the Silver Jewelry Box to your collection today. event events ticket tickets experience seminar workshop admission registration DeMarco', 0, 'Silver Jewelry Box', 'Silver jewelry box with velvet lining. Elegant storage solution.', 'silver-jewelry-box', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 1, '2026-07-20 13:52:47', '2026-08-04 16:53:39', 1, 1, 0.00, 0, 1, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (22, 5, 'Modern Pocket Watch', 'Sample out of stock item with a contact form below the item to request more information.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 14pt;\"><strong>We apologize but this item is currently not available. Please submit the form below to have us contact you when it\'s back in stock.</strong></span></p>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 18pt;\"><strong>[cms-form id=1]</strong></span></p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Modern Pocket Watch modern pocket watch Modern Pocket Watch Modern pocket watch. Sleek minimalist design. Sample out of stock item with a contact form below the item to request more information. We apologize but this item is currently not available. Please submit the form below to have us contact you when it\'s back in stock. &nbsp; Excelsior', 0, 'Modern Pocket Watch', 'Modern pocket watch. Sleek minimalist design.', 'modern-pocket-watch', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, 1, 1, '2026-07-20 13:52:47', '2026-08-05 23:52:44', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (23, 5, 'Modern Wrist Watch', 'Out of stock example item.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Modern Wrist Watch &mdash; Minimalist Design, Everyday Wear</h2>\n<p>A beautifully balanced, modern wrist watch designed for people who appreciate clean lines and effortless style. The slim profile and uncluttered dial make it a versatile accessory for both casual and formal looks. Crafted with a genuine leather strap and precision movement, this unisex timepiece offers lasting comfort and reliable timekeeping.</p>\n<h3>Key Features</h3>\n<ul>\n<li><strong>Minimalist aesthetic:</strong> Clean dial with subtle indices for an understated, contemporary look.</li>\n<li><strong>Slim case:</strong> Low-profile design that sits comfortably under sleeves and jackets.</li>\n<li><strong>Genuine leather strap:</strong> Soft, durable strap that molds to your wrist over time.</li>\n<li><strong>Reliable movement:</strong> Precision quartz movement for accurate timekeeping.</li>\n<li><strong>Everyday durability:</strong> Sapphire-coated mineral crystal resists scratches; water resistant for everyday splashes.</li>\n<li><strong>Unisex sizing:</strong> Designed to suit both men and women with a versatile case size and adjustable strap.</li>\n</ul>\n<h3>Specifications</h3>\n<ul>\n<li>Case diameter: 38 mm (approx.)</li>\n<li>Case thickness: 6&ndash;8 mm (slim profile)</li>\n<li>Movement: Japanese quartz</li>\n<li>Crystal: Sapphire-coated mineral glass</li>\n<li>Strap: Genuine leather with adjustable buckle</li>\n<li>Water resistance: 3 ATM (splash resistant; not suitable for swimming)</li>\n<li>Gender: Unisex</li>\n</ul>\n<h3>What&rsquo;s Included</h3>\n<ul>\n<li>Modern Wrist Watch (displayed strap)</li>\n<li>Premium presentation box</li>\n<li>Instruction manual and warranty card</li>\n</ul>\n<h3>Care &amp; Maintenance</h3>\n<ul>\n<li>Avoid prolonged exposure to moisture; remove the watch before showering or swimming.</li>\n<li>Clean the case and crystal with a soft, dry cloth. Condition the leather strap occasionally with leather care products.</li>\n<li>Have the battery replaced by a qualified watch technician to ensure water-resistance is maintained.</li>\n</ul>\n<h3>Warranty &amp; Support</h3>\n<p>This timepiece is covered by a 24-month limited warranty against manufacturing defects. For warranty service or product support, please contact our customer care team with your order number and warranty card.</p>\n<h3>Availability</h3>\n<p><strong>Out of stock.</strong> We&rsquo;re sorry&mdash;this item is currently unavailable. Click the \"Notify Me\" button on the product page to receive an email as soon as it&rsquo;s back in stock, or contact customer support for help with similar styles and pre-order options.</p>\n<p>Designed to be effortlessly wearable and elegantly restrained, the Modern Wrist Watch is the perfect everyday companion or thoughtful gift for anyone who values timeless, minimalist design.</p>\n</div>\n</div>', 'Modern Wrist Watch modern wrist watch Modern Wrist Watch Modern wrist watch. Minimalist design for men and women. Out of stock example item. Modern Wrist Watch &mdash; Minimalist Design, Everyday Wear A beautifully balanced, modern wrist watch designed for people who appreciate clean lines and effortless style. The slim profile and uncluttered dial make it a versatile accessory for both casual and formal looks. Crafted with a genuine leather strap and precision movement, this unisex timepiece offers lasting comfort and reliable timekeeping. Key Features Minimalist aesthetic: Clean dial with subtle indices for an understated, contemporary look. Slim case: Low-profile design that sits comfortably under sleeves and jackets. Genuine leather strap: Soft, durable strap that molds to your wrist over time. Reliable movement: Precision quartz movement for accurate timekeeping. Everyday durability: Sapphire-coated mineral crystal resists scratches; water resistant for everyday splashes. Unisex sizing: Designed to suit both men and women with a versatile case size and adjustable strap. Specifications Case diameter: 38 mm (approx.) Case thickness: 6&ndash;8 mm (slim profile) Movement: Japanese quartz Crystal: Sapphire-coated mineral glass Strap: Genuine leather with adjustable buckle Water resistance: 3 ATM (splash resistant; not suitable for swimming) Gender: Unisex What&rsquo;s Included Modern Wrist Watch (displayed strap) Premium presentation box Instruction manual and warranty card Care &amp; Maintenance Avoid prolonged exposure to moisture; remove the watch before showering or swimming. Clean the case and crystal with a soft, dry cloth. Condition the leather strap occasionally with leather care products. Have the battery replaced by a qualified watch technician to ensure water-resistance is maintained. Warranty &amp; Support This timepiece is covered by a 24-month limited warranty against manufacturing defects. For warranty service or product support, please contact our customer care team with your order number and warranty card. Availability Out of stock. We&rsquo;re sorry&mdash;this item is currently unavailable. Click the \"Notify Me\" button on the product page to receive an email as soon as it&rsquo;s back in stock, or contact customer support for help with similar styles and pre-order options. Designed to be effortlessly wearable and elegantly restrained, the Modern Wrist Watch is the perfect everyday companion or thoughtful gift for anyone who values timeless, minimalist design. Excelsior', 0, 'Modern Wrist Watch', 'Modern wrist watch. Minimalist design for men and women.', 'modern-wrist-watch', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, 1, 1, '2026-07-20 13:52:47', '2026-08-05 22:39:30', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (25, 2, 'Women\'s T-Shirt', 'Premium women\'s t-shirt. Available in 6 colors.  (Example product using layout with left side images)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Premium Women\'s T-Shirt &mdash; Soft 100% Cotton, Fitted Silhouette</h2>\n<p>Everyday comfort meets effortless style. This premium women\'s t-shirt is crafted from 100% cotton for a soft, breathable feel and a flattering fitted cut that moves with you. Available in six versatile colors and sizes S&ndash;XXL, it&rsquo;s a wardrobe essential that works alone or layered.</p>\n<h3>Key Features</h3>\n<ul>\n<li>Material: 100% cotton for natural breathability and comfort</li>\n<li>Fit: Women\'s fitted silhouette that offers a streamlined, flattering shape</li>\n<li>Color options: Brown, Gray, Green, Navy, Orange, Royal Blue</li>\n<li>Sizes: S, M, L, XL, XXL&nbsp; (+$5.00)</li>\n<li>Construction: Durable stitching for long-lasting wear</li>\n</ul>\n<h3>Fit &amp; Sizing</h3>\n<p>The tee features a fitted cut designed to follow the natural contours of the body. If you prefer a roomier or more relaxed look, consider choosing one size up. For the best fit, consult your usual t-shirt size or compare with a similar favorite tee.</p>\n<h3>Care Instructions</h3>\n<ul>\n<li>Machine wash cold with like colors</li>\n<li>Tumble dry low or hang to dry to preserve shape and color</li>\n<li>Warm iron if needed; do not bleach</li>\n</ul>\n<h3>How to Wear</h3>\n<p>Versatile by design, this tee pairs easily with jeans, skirts, leggings, or tailored trousers. Style tips:</p>\n<ul>\n<li>Casual: Tuck into high-rise jeans with sneakers for an effortless weekend look</li>\n<li>Layered: Wear under a blazer or cardigan for smart-casual office wear</li>\n<li>Active: Combine with joggers and trainers for comfortable athleisure</li>\n</ul>\n<h3>Perfect for Gifting</h3>\n<p>With classic colors and everyday appeal, this t-shirt makes a practical, stylish gift for birthdays, holidays, or as a thoughtful wardrobe upgrade. Available sizes S&ndash;XXL make it easy to find the right fit.</p>\n<p><strong>Add to cart</strong> to bring this premium, go-to women\'s t-shirt into your everyday rotation &mdash; comfort and style in one essential piece.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Women\'s T-Shirt womens t shirt Women\'s T-Shirt Women\'s t-shirt. Premium cotton, multiple colours. Premium women\'s t-shirt. Available in 6 colors. (Example product using layout with left side images) Premium Women\'s T-Shirt &mdash; Soft 100% Cotton, Fitted Silhouette Everyday comfort meets effortless style. This premium women\'s t-shirt is crafted from 100% cotton for a soft, breathable feel and a flattering fitted cut that moves with you. Available in six versatile colors and sizes S&ndash;XXL, it&rsquo;s a wardrobe essential that works alone or layered. Key Features Material: 100% cotton for natural breathability and comfort Fit: Women\'s fitted silhouette that offers a streamlined, flattering shape Color options: Brown, Gray, Green, Navy, Orange, Royal Blue Sizes: S, M, L, XL, XXL&nbsp; (+$5.00) Construction: Durable stitching for long-lasting wear Fit &amp; Sizing The tee features a fitted cut designed to follow the natural contours of the body. If you prefer a roomier or more relaxed look, consider choosing one size up. For the best fit, consult your usual t-shirt size or compare with a similar favorite tee. Care Instructions Machine wash cold with like colors Tumble dry low or hang to dry to preserve shape and color Warm iron if needed; do not bleach How to Wear Versatile by design, this tee pairs easily with jeans, skirts, leggings, or tailored trousers. Style tips: Casual: Tuck into high-rise jeans with sneakers for an effortless weekend look Layered: Wear under a blazer or cardigan for smart-casual office wear Active: Combine with joggers and trainers for comfortable athleisure Perfect for Gifting With classic colors and everyday appeal, this t-shirt makes a practical, stylish gift for birthdays, holidays, or as a thoughtful wardrobe upgrade. Available sizes S&ndash;XXL make it easy to find the right fit. Add to cart to bring this premium, go-to women\'s t-shirt into your everyday rotation &mdash; comfort and style in one essential piece. &nbsp; DeMarco', 0, 'Women\'s T-Shirt', 'Women\'s t-shirt. Premium cotton, multiple colours.', 'womens-t-shirt', 0, 1, 0, 0, NULL, NULL, 0, NULL, 1, 0, NULL, 2, '2026-07-20 13:52:47', '2026-08-04 16:24:10', 1, 1, 0.00, 1, 1, 0, 'Select Color | Size:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (28, NULL, 'Bill Pay Account |  Top Off Example', 'Enter the amount you would like to pay in the space provided below. Min $25 | Max $100', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Donation | Bill Pay Example</h2>\n<p>This product option lets you contribute a set amount or enter a custom amount &mdash; within the allowed range &mdash; and have the funds credited to your account immediately after successful billing. Ideal for account top-ups, one-time donations, or bill payments handled as a service item.</p>\n<h3>How to use</h3>\n<ul>\n<li><strong>Choose a preset amount:</strong> Select one of the predefined values from the list for a fast checkout.</li>\n<li><strong>Or enter a custom amount:</strong> Type any value between <strong>$25</strong> and <strong>$100</strong> to set the exact amount you want to pay.</li>\n<li><strong>Complete checkout:</strong> Proceed to payment. After successful billing, the amount will be credited to your account immediately.</li>\n</ul>\n<h3>Key benefits</h3>\n<ul>\n<li><strong>Flexible options:</strong> Use quick-select amounts or enter a specific value to meet your needs.</li>\n<li><strong>Immediate credit:</strong> Funds are applied to your account as soon as billing completes.</li>\n<li><strong>Secure processing:</strong> Payments are handled through our secure checkout to protect your information.</li>\n<li><strong>Clear records:</strong> You will receive a confirmation and receipt for your transaction.</li>\n</ul>\n<h3>Important details</h3>\n<ul>\n<li>Minimum amount: <strong>$25</strong></li>\n<li>Maximum amount: <strong>$100</strong></li>\n<li>Please ensure the amount you enter falls within the range above; transactions outside this range will not be accepted.</li>\n<li>If you have questions about processing, credits, or receipts, contact our support team for assistance.</li>\n</ul>\n<p><em>Note:</em> This item is provided as an example of bill-pay/donation functionality and demonstrates how an admin can configure fixed or customer-entered payment amounts within a specified min/max range.</p>\n</div>\n<p>&nbsp;</p>', 'Bill Pay Account |  Top Off Example bill pay account credit Bill Pay | \"Make An Offer\" Example This sample item shows how the admin can create a item to accept either an set amount (via a list) or customer inputted amount within a min/max range. Enter the amount you would like to pay in the space provided below. Min $25 | Max $100 Donation | Bill Pay Example This product option lets you contribute a set amount or enter a custom amount &mdash; within the allowed range &mdash; and have the funds credited to your account immediately after successful billing. Ideal for account top-ups, one-time donations, or bill payments handled as a service item. How to use Choose a preset amount: Select one of the predefined values from the list for a fast checkout. Or enter a custom amount: Type any value between $25 and $100 to set the exact amount you want to pay. Complete checkout: Proceed to payment. After successful billing, the amount will be credited to your account immediately. Key benefits Flexible options: Use quick-select amounts or enter a specific value to meet your needs. Immediate credit: Funds are applied to your account as soon as billing completes. Secure processing: Payments are handled through our secure checkout to protect your information. Clear records: You will receive a confirmation and receipt for your transaction. Important details Minimum amount: $25 Maximum amount: $100 Please ensure the amount you enter falls within the range above; transactions outside this range will not be accepted. If you have questions about processing, credits, or receipts, contact our support team for assistance. Note: This item is provided as an example of bill-pay/donation functionality and demonstrates how an admin can configure fixed or customer-entered payment amounts within a specified min/max range. &nbsp;', 0, 'Bill Pay | \"Make An Offer\" Example', 'This sample item shows how the admin can create a item to accept either an set amount (via a list) or customer inputted amount within a min/max range.', 'bill pay account credit ', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 6, '2026-07-20 13:52:47', '2026-07-31 01:11:21', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 1, 1, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (30, NULL, '2-Day Social Media Workshop', 'Intensive 2-day social media workshop.<br><br><strong>4/14-4/15/2027 (9am-5pm each day)</strong>', '', '2-Day Social Media Workshop 2 day social media workshop 2-Day Social Media Workshop 2-day social media workshop. Hands-on training for Instagram, Facebook, LinkedIn. Intensive 2-day social media workshop.4/14-4/15/2027 (9am-5pm each day) event events ticket tickets experience seminar workshop admission registration', 0, '2-Day Social Media Workshop', '2-day social media workshop. Hands-on training for Instagram, Facebook, LinkedIn.', '2-day-social-media-workshop', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 6, '2026-07-20 13:52:47', '2026-08-06 00:46:34', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (31, NULL, 'Inventory Management Seminar - Advanced Course', 'Demonstrates custom event status on sold out events. (Custom Out of Stock Message)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Advanced Inventory Management Seminar &mdash; Optimize Stock, Reduce Costs, Improve Service</h2>\n<p>Take control of your inventory with a practical, hands-on seminar designed for retail and e-commerce teams ready to move beyond basics. This advanced course teaches proven forecasting, demand planning, warehouse optimisation, and automated replenishment strategies that reduce stockouts, lower carrying costs, and improve customer service levels.</p>\n<h3>What you&rsquo;ll gain</h3>\n<ul>\n<li><strong>Actionable forecasting techniques</strong> &mdash; apply time-series methods, seasonality adjustments and causal factors to produce more reliable demand forecasts.</li>\n<li><strong>Smart replenishment strategies</strong> &mdash; design and implement min/max, reorder point, EOQ and safety-stock policies that match your business rhythms and supplier lead times.</li>\n<li><strong>Warehouse optimisation</strong> &mdash; optimise layout, slotting and picking flows to cut handling time and improve throughput.</li>\n<li><strong>Data-driven decision making</strong> &mdash; use segmentation (ABC/XYZ), KPIs and dashboards to focus effort where it moves the needle.</li>\n<li><strong>Automation &amp; systems integration</strong> &mdash; practical guidance for integrating forecasting and replenishment with ERP, WMS and e-commerce platforms.</li>\n<li><strong>Scenario planning &amp; risk management</strong> &mdash; techniques for handling demand volatility, supplier disruption and promotions without overstocking.</li>\n</ul>\n<h3>Who should attend</h3>\n<ul>\n<li>Inventory, supply chain and operations managers</li>\n<li>E-commerce and retail merchandising leaders</li>\n<li>Warehouse supervisors and logistics coordinators</li>\n<li>Procurement professionals responsible for reorder policies</li>\n<li>Business analysts and finance partners focused on inventory costs</li>\n</ul>\n<h3>Course format &amp; practical components</h3>\n<ul>\n<li>Intensive, instructor-led seminar featuring real-world case studies and hands-on exercises</li>\n<li>Interactive workshops where attendees build forecasting models and replenishment rules</li>\n<li>Templates and tools (Excel models, KPI dashboards, SOP checklists) you can adapt immediately</li>\n<li>Q&amp;A and peer discussion to address your specific operational challenges</li>\n</ul>\n<h3>Key outcomes</h3>\n<ul>\n<li>Improved forecast accuracy and a clear process for continuous forecast review</li>\n<li>Replenishment policies aligned with demand profiles and supplier performance</li>\n<li>Practical warehouse changes that increase picking speed and reduce errors</li>\n<li>A roadmap for automating inventory workflows and integrating systems</li>\n<li>Take-home tools and templates to implement improvements right away</li>\n</ul>\n<h3>Prerequisites</h3>\n<ul>\n<li>Familiarity with basic inventory concepts (reorder point, safety stock, lead time)</li>\n<li>Comfort with spreadsheets (Excel/Google Sheets) for workshop exercises</li>\n</ul>\n<h3>Led by experienced practitioners</h3>\n<p>This seminar is delivered by instructors with hands-on experience implementing inventory and replenishment solutions for retail and e-commerce businesses. Sessions focus on practical, repeatable methods you can apply immediately in your operation.</p>\n<p><strong>Seats are limited to maintain an interactive learning environment.</strong> Register now to secure your place and start turning inventory into a competitive advantage.</p>\n</div>\n<p>&nbsp;</p>', 'Inventory Management Seminar - Advanced Course inventory management seminar advanced Inventory Management Seminar - Advanced Course Advanced inventory management seminar. Retail and e-commerce training. Demonstrates custom event status on sold out events. (Custom Out of Stock Message) Advanced Inventory Management Seminar &mdash; Optimize Stock, Reduce Costs, Improve Service Take control of your inventory with a practical, hands-on seminar designed for retail and e-commerce teams ready to move beyond basics. This advanced course teaches proven forecasting, demand planning, warehouse optimisation, and automated replenishment strategies that reduce stockouts, lower carrying costs, and improve customer service levels. What you&rsquo;ll gain Actionable forecasting techniques &mdash; apply time-series methods, seasonality adjustments and causal factors to produce more reliable demand forecasts. Smart replenishment strategies &mdash; design and implement min/max, reorder point, EOQ and safety-stock policies that match your business rhythms and supplier lead times. Warehouse optimisation &mdash; optimise layout, slotting and picking flows to cut handling time and improve throughput. Data-driven decision making &mdash; use segmentation (ABC/XYZ), KPIs and dashboards to focus effort where it moves the needle. Automation &amp; systems integration &mdash; practical guidance for integrating forecasting and replenishment with ERP, WMS and e-commerce platforms. Scenario planning &amp; risk management &mdash; techniques for handling demand volatility, supplier disruption and promotions without overstocking. Who should attend Inventory, supply chain and operations managers E-commerce and retail merchandising leaders Warehouse supervisors and logistics coordinators Procurement professionals responsible for reorder policies Business analysts and finance partners focused on inventory costs Course format &amp; practical components Intensive, instructor-led seminar featuring real-world case studies and hands-on exercises Interactive workshops where attendees build forecasting models and replenishment rules Templates and tools (Excel models, KPI dashboards, SOP checklists) you can adapt immediately Q&amp;A and peer discussion to address your specific operational challenges Key outcomes Improved forecast accuracy and a clear process for continuous forecast review Replenishment policies aligned with demand profiles and supplier performance Practical warehouse changes that increase picking speed and reduce errors A roadmap for automating inventory workflows and integrating systems Take-home tools and templates to implement improvements right away Prerequisites Familiarity with basic inventory concepts (reorder point, safety stock, lead time) Comfort with spreadsheets (Excel/Google Sheets) for workshop exercises Led by experienced practitioners This seminar is delivered by instructors with hands-on experience implementing inventory and replenishment solutions for retail and e-commerce businesses. Sessions focus on practical, repeatable methods you can apply immediately in your operation. Seats are limited to maintain an interactive learning environment. Register now to secure your place and start turning inventory into a competitive advantage. &nbsp; event events ticket tickets experience seminar workshop admission registration', 0, 'Inventory Management Seminar - Advanced Course', 'Advanced inventory management seminar. Retail and e-commerce training.', 'inventory-management-seminar-advanced', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, 5, 6, '2026-07-20 13:52:47', '2026-08-05 22:23:29', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: products
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products` (`id`, `brand_id`, `title`, `short_description`, `long_description`, `product_search_index`, `product_search_index_locked`, `meta_title`, `meta_description`, `seo_slug`, `download_item`, `shipping`, `max_qty`, `checkout_redirect`, `completion_redirect`, `completion_redirect_label`, `standalone_purchase`, `advanced_options`, `dependent_variants`, `hide_inventory_levels`, `inventory_alert_id`, `layout_type`, `created_at`, `updated_at`, `is_demo`, `reviews_enabled`, `reviews_rating`, `featured_item`, `show_item_total`, `show_variant_selector_thumbnail`, `variant_label`, `product_video_embed`, `is_donation_or_bill_pay`, `allow_custom_amount`, `custom_amount_min`, `custom_amount_max`, `custom_amount_options`) VALUES (34, NULL, 'Inventory Management Seminar - Intro Course', 'Introductory inventory management seminar for new business owners. <br><br><strong>February 2, 2027 | 9-10 am</strong>', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Overview</h2>\n<p>This introductory Inventory Management Seminar is designed for new business owners and early-stage operations staff who need clear, practical guidance on controlling stock, reducing waste, and making smarter purchasing decisions. The seminar breaks down core inventory concepts into easy-to-follow steps so you can apply them to your business immediately.</p>\n<h2>What you\'ll learn</h2>\n<ul>\n<li>Fundamental inventory concepts: why inventory matters to cash flow, customer satisfaction, and profitability</li>\n<li>Simple stock counting methods and best practices for accurate inventory records</li>\n<li>How to structure basic purchase orders and streamline supplier communication</li>\n<li>How to calculate and apply straightforward reorder points and safety stock for everyday items</li>\n<li>Practical approaches to reduce stockouts and excess inventory without complex systems</li>\n</ul>\n<h2>Who should attend</h2>\n<ul>\n<li>New business owners and entrepreneurs managing stock for the first time</li>\n<li>Retail managers, cafe and restaurant owners, and small wholesale operators</li>\n<li>E-commerce sellers handling inventory across one or more channels</li>\n<li>Administrative or operations staff responsible for ordering and stock control</li>\n</ul>\n<h2>How the seminar is delivered</h2>\n<p>Delivered in a focused, instructor-led workshop format, the seminar combines short conceptual presentations with real-world examples and interactive Q&amp;A. The pace is beginner-friendly and practical&mdash;no advanced accounting or software experience required.</p>\n<h2>Benefits for your business</h2>\n<ul>\n<li>Make faster, more confident ordering decisions that free up cash and reduce carrying costs</li>\n<li>Improve stock accuracy and avoid costly stockouts during peak demand</li>\n<li>Adopt repeatable counting and ordering routines that save time and reduce errors</li>\n<li>Gain a straightforward method for setting reorder points you can use immediately</li>\n</ul>\n<h2>What to expect after the seminar</h2>\n<p>Participants will leave with a clear, actionable understanding of basic inventory workflows and practical next steps to implement in their business. You&rsquo;ll be able to conduct effective stock counts, place smarter purchase orders, and use simple reorder calculations to keep inventory at the right levels.</p>\n<h2>Prerequisites</h2>\n<p>No prior inventory or accounting knowledge required. Bring examples of your current stock lists or purchasing challenges to make the session more relevant and actionable.</p>\n<p><strong>Ready to get control of your stock?</strong> Join this seminar to build a solid inventory foundation that supports growth, reduces waste, and saves you time. Register today to reserve your spot.</p>\n</div>', 'Inventory Management Seminar - Intro Course inventory management seminar intro Inventory Management Seminar - Intro Course Introductory inventory management seminar. Training for new business owners. Introductory inventory management seminar for new business owners. February 2, 2027 | 9-10 am Overview This introductory Inventory Management Seminar is designed for new business owners and early-stage operations staff who need clear, practical guidance on controlling stock, reducing waste, and making smarter purchasing decisions. The seminar breaks down core inventory concepts into easy-to-follow steps so you can apply them to your business immediately. What you\'ll learn Fundamental inventory concepts: why inventory matters to cash flow, customer satisfaction, and profitability Simple stock counting methods and best practices for accurate inventory records How to structure basic purchase orders and streamline supplier communication How to calculate and apply straightforward reorder points and safety stock for everyday items Practical approaches to reduce stockouts and excess inventory without complex systems Who should attend New business owners and entrepreneurs managing stock for the first time Retail managers, cafe and restaurant owners, and small wholesale operators E-commerce sellers handling inventory across one or more channels Administrative or operations staff responsible for ordering and stock control How the seminar is delivered Delivered in a focused, instructor-led workshop format, the seminar combines short conceptual presentations with real-world examples and interactive Q&amp;A. The pace is beginner-friendly and practical&mdash;no advanced accounting or software experience required. Benefits for your business Make faster, more confident ordering decisions that free up cash and reduce carrying costs Improve stock accuracy and avoid costly stockouts during peak demand Adopt repeatable counting and ordering routines that save time and reduce errors Gain a straightforward method for setting reorder points you can use immediately What to expect after the seminar Participants will leave with a clear, actionable understanding of basic inventory workflows and practical next steps to implement in their business. You&rsquo;ll be able to conduct effective stock counts, place smarter purchase orders, and use simple reorder calculations to keep inventory at the right levels. Prerequisites No prior inventory or accounting knowledge required. Bring examples of your current stock lists or purchasing challenges to make the session more relevant and actionable. Ready to get control of your stock? Join this seminar to build a solid inventory foundation that supports growth, reduces waste, and saves you time. Register today to reserve your spot. event events ticket tickets experience seminar workshop admission registration', 0, 'Inventory Management Seminar - Intro Course', 'Introductory inventory management seminar. Training for new business owners.', 'inventory-management-seminar-intro', 0, 1, 0, 0, NULL, NULL, 0, NULL, 0, 0, NULL, 6, '2026-07-20 13:52:47', '2026-08-05 22:31:01', 1, 1, 0.00, 0, 0, 0, 'Select Option:', NULL, 0, 0, NULL, NULL, NULL);
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (1, 1, 'sample-sku-01', 599.99, 540.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 22:45:09', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (2, 2, 'sample-sku-02', 449.99, 400.00, 1, 419.99, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-04 16:01:14', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (3, 3, 'sample-sku-03-sz5', 299.99, 265.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"5\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 22:49:28', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (10, 4, 'sample-sku-04-sz5', 789.00, 780.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 23:01:14', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (17, 5, 'SDR-size-5', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 1, 25.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"5\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 23:09:50', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping', 'Gift Wrapping', 'Enter what you want to say on the included card or enter \"No Card\" if no card should be included.');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (18, 6, 'sample-sku-06', 2499.99, 2200.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '{\n    \"Ring Size\": \"6\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 23:18:32', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (19, 7, 'sample-sku-07', 349.99, 300.00, 0, NULL, 0.00, 0.00, 0, 0.00, 0, NULL, NULL, 'Standard', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (20, 8, 'sample-sku-08', 499.99, 440.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 23:22:54', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (21, 9, 'sample-sku-09', 799.99, 720.00, 0, 0.00, 0.00, 0.00, 1, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-05 23:30:17', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Initials Inscribing', 'Initials', 'Add Initials to be inscribed on the inside of the bracelet (max 6 characters)');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (22, 10, 'sample-sku-10', 1199.99, 1050.00, 0, NULL, 0.00, 0.00, 0, 0.00, 0, NULL, NULL, 'Standard', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (23, 11, 'sample-sku-11', 899.99, 800.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-06 00:25:46', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (24, 12, 'sample-sku-12-gold', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:46', '2026-08-04 00:04:18', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (27, 13, 'sample-sku-13', 1599.99, 1400.00, 0, 0.00, 0.00, 0.00, 1, 200.00, 1, 0.2, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-03 22:56:46', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Engraving', 'Engraving Entry:', 'Enter initials or message to be engraved on the inside of the bracelet.');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (28, 14, 'sample-sku-14-dl', 4.99, 0.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 1, 0, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/downloads/Jewelry_Cleaning_Guide_Sample.pdf', NULL, NULL, 100, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 21:15:30', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (29, 15, 'sample-sku-16-dl', 29.99, 25.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 1, 0, NULL, 'https://d23w3zagfzgqcb.cloudfront.net/downloads/demo_jewelry_repair_guidebook.pdf', NULL, NULL, 100, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-26 20:02:48', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (31, 17, 'sample-sku-15-black-small', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Black\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-21 14:17:09', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (32, 17, 'sample-sku-15-black-medium', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"Black\",    \"Size\": \"Medium\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (33, 17, 'sample-sku-15-black-large', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Black\",\n    \"Size\": \"Large\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-20 14:38:14', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (34, 17, 'sample-sku-15-black-xl', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Black\",\n    \"Size\": \"XL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-20 14:39:17', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (35, 17, 'sample-sku-15-black-xxl', 49.99, 42.00, 0, 0.00, 5.00, 5.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Black\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:13:19', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (36, 17, 'sample-sku-15-burgundy-small', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Burgungy\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-20 14:46:41', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (37, 17, 'sample-sku-15-burgundy-medium', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"Burgungy\",    \"Size\": \"Medium\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (38, 17, 'sample-sku-15-large-burgundy', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"Burgungy\",    \"Size\": \"Large\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (39, 17, 'sample-sku-15-burgungy-xl', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"Burgungy\",    \"Size\": \"XL\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (40, 17, 'sample-sku-15-burgundy-xxl', 49.99, 42.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"Burgungy\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:13:42', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (41, 17, 'sample-sku-15-white-small', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"White\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-20 14:46:02', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (42, 17, 'sample-sku-15-white-medium', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"White\",    \"Size\": \"Medium\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (43, 17, 'sample-sku-15-white-large', 49.99, 38.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"White\",\n    \"Size\": \"Large\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-20 14:45:21', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (44, 17, 'sample-sku-15-white-xl', 49.99, 38.00, 0, NULL, 0.00, 0.00, 0, 0.00, 1, NULL, NULL, '{    \"Color\": \"White\",    \"Size\": \"XL\"}', 0, 1, NULL, NULL, NULL, NULL, 100, 0, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (45, 17, 'sample-sku-15-white-xxl', 49.99, 42.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0, 'lbs', '{\n    \"Color\": \"White\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:13:29', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (46, 18, 'sample-sku-19', 129.99, 95.00, 0, 0.00, 0.00, 0.00, 1, 15.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-03 23:40:57', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Gift Wrap:', 'Gift Message', 'Enter message for gift card here.');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (48, 19, 'sample-sku-20-brown', 89.99, 55.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-04 16:08:48', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (50, 20, 'sample-sku-21', 24.99, 15.00, 0, 0.00, 0.00, 0.00, 1, 25.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-26 22:06:34', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Engraving Option:', 'Enter optional engraving below:', 'Enter names or initials for the engraving option. Up to two sets of initials (1 for each pen) or 1 line per pen.');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (51, 21, 'sample-sku-22', 70.00, 40.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-04 16:52:56', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (52, 22, 'sample-sku-23', 59.99, 35.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-05 23:51:48', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (53, 23, 'sample-sku-24', 79.99, 45.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-05 22:39:16', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (60, 25, 'sample-sku-26-brown-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-26 21:42:48', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, '', '', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (61, 25, 'sample-sku-26-gray-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Gray\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-25 20:55:42', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, '', '', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (62, 25, 'sample-sku-26-green-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Green\",\n    \"Size\": \"Small\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-26 20:35:28', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (63, 25, 'sample-sku-26-navy-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Small\",\n    \"Color\": \"Navy Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:17:29', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (64, 25, 'sample-sku-26-orange-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Small\",\n    \"Color\": \"Orange\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:20:59', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (65, 25, 'sample-sku-26-royal-small', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Small\",\n    \"Color\": \"Royal Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-07-24 22:27:28', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (79, 30, '4/14-4/15/2027 (9am-5pm each day)', 349.00, 280.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 1, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-05 22:35:21', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (80, 31, '11/09/2026 | 10AM - 2PM', 299.00, 220.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 1, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-05 22:21:39', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (83, 34, 'February 2, 2027 | 9-10 am', 149.00, 100.00, 1, 125.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 1, 0, NULL, NULL, '', '2026-07-20 13:52:47', '2026-08-05 22:29:09', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (85, 25, 'sample-sku-26-brown-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\",\n    \"Size\": \"Medium\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:50:38', '2026-07-24 21:57:00', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (86, 25, 'sample-sku-26-brown-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\",\n    \"Size\": \"Large\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:51:37', '2026-07-24 21:57:20', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (87, 25, 'sample-sku-26-brown-x-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\",\n    \"Size\": \"XL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:52:41', '2026-07-24 22:00:43', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (88, 25, 'sample-sku-26-brown-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Brown\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:53:49', '2026-07-24 21:57:44', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (89, 25, 'sample-sku-26-gray-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Gray\",\n    \"Size\": \"Medium\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:58:11', '2026-07-24 21:58:50', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (90, 25, 'sample-sku-26-gray-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Gray\",\n    \"Size\": \"Large\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:58:59', '2026-07-24 21:59:21', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (91, 25, 'sample-sku-26-gray-x-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Gray\",\n    \"Size\": \"XL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 21:59:25', '2026-07-24 22:01:27', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (92, 25, 'sample-sku-26-gray-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Gray\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:02:21', '2026-07-24 22:02:44', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (93, 25, 'sample-sku-26-green-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Green\",\n    \"Size\": \"Medium\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:06:05', '2026-07-24 22:06:55', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (94, 25, 'sample-sku-26-green-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Green\",\n    \"Size\": \"Large\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:07:29', '2026-07-24 22:07:43', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (95, 25, 'sample-sku-26-green-xl', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Green\",\n    \"Size\": \"XL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:07:48', '2026-08-04 16:24:10', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (96, 25, 'sample-sku-26-green-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Green\",\n    \"Size\": \"XXL\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:08:15', '2026-08-04 16:23:55', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (97, 25, 'sample-sku-26-navy-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Medium\",\n    \"Color\": \"Navy Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:17:37', '2026-07-24 22:18:04', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (98, 25, 'sample-sku-26-navy-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Large\",\n    \"Color\": \"Navy Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:18:14', '2026-07-24 22:18:30', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (99, 25, 'sample-sku-26-navy-x-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XL\",\n    \"Color\": \"Navy Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:18:47', '2026-07-24 22:19:07', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (100, 25, 'sample-sku-26-navy-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XXL\",\n    \"Color\": \"Navy Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:19:10', '2026-07-24 22:19:38', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (101, 25, 'sample-sku-26-orange-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Medium\",\n    \"Color\": \"Orange\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:22:54', '2026-07-24 22:23:58', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (102, 25, 'sample-sku-26-orange-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Large\",\n    \"Color\": \"Orange\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:24:04', '2026-07-24 22:24:26', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (103, 25, 'sample-sku-26-orange-x-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XL\",\n    \"Color\": \"Orange\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:24:32', '2026-07-24 22:24:59', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (104, 25, 'sample-sku-26-orange-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XXL\",\n    \"Color\": \"Orange\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:25:06', '2026-07-24 22:26:17', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (106, 25, 'sample-sku-26-royal-medium', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Medium\",\n    \"Color\": \"Royal Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:29:32', '2026-07-24 22:29:53', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (107, 25, 'sample-sku-26-royal-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"Large\",\n    \"Color\": \"Royal Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:29:56', '2026-07-24 22:30:15', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (108, 25, 'sample-sku-26-royal-x-large', 24.99, 12.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XL\",\n    \"Color\": \"Royal Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:30:18', '2026-07-24 22:30:39', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (109, 25, 'sample-sku-26-royal-xxl', 24.99, 12.00, 0, 0.00, 5.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Size\": \"XXL\",\n    \"Color\": \"Royal Blue\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-24 22:30:44', '2026-07-24 22:31:01', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (125, 28, 'sample-billpay-sku-28', 0.00, 0.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-07-30 22:30:20', '2026-07-30 22:30:20', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (126, 12, 'sample-sku-12-silver', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-04 00:07:23', '2026-08-04 00:07:52', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (127, 12, 'sample-sku-12-18k-rose-gold', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '[]', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-04 00:07:59', '2026-08-04 00:08:50', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (130, 19, 'sample-sku-19-brown', 89.99, 55.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Color\": \"Black\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-04 16:08:32', '2026-08-04 16:11:30', 1, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (132, 31, '03/09/2027  | 10AM - 2PM', 299.00, 220.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 0, 0, 'lbs', '[]', 0, 0, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 1, 0, NULL, NULL, '', '2026-08-05 22:19:43', '2026-08-05 22:21:57', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (133, 3, 'sample-sku-03-sz5.5', 299.99, 265.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"5.5\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 22:48:44', '2026-08-05 22:49:18', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (134, 3, 'sample-sku-03-sz6', 299.99, 265.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"6\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 22:49:36', '2026-08-05 22:49:53', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (135, 3, 'sample-sku-03-sz6.5', 299.99, 265.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"6.5\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 22:50:00', '2026-08-05 22:50:25', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (136, 3, 'sample-sku-03-sz7', 299.99, 265.00, 0, 0.00, 0.00, 0.00, 0, 0.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"7\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 22:52:23', '2026-08-05 22:52:45', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping / Personalization', 'Personalization Details / Gift Message', 'Enter names for engraving, personalization details, or a custom gift message here...');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (137, 5, 'SDR-size-5.5', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 1, 25.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"5.5\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 23:11:15', '2026-08-05 23:11:30', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping', 'Gift Wrapping', 'Enter what you want to say on the included card or enter \"No Card\" if no card should be included.');
SQL
);

        // Table: product_variants
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variants` (`id`, `product_id`, `sku`, `public_price`, `wholesale_price`, `on_sale`, `sale_price`, `variant_fee`, `wholesale_variant_fee`, `personalization_active`, `personalization_fee`, `shipping`, `weight`, `weight_type`, `attributes`, `download_item`, `charge_tax`, `download_location`, `direct_download_url`, `download_label`, `download_expiration`, `downloads_max_allowed`, `download_s3`, `download_s3_region`, `download_s3_bucket_name`, `download_s3_access_key_id`, `download_s3_secret_access_key`, `subscription`, `is_event`, `video_item`, `video_preview`, `video_purchase`, `download_cdn_url`, `created_at`, `updated_at`, `is_demo`, `paddle_sandbox_price_id`, `paddle_live_price_id`, `paddle_price`, `paddle_interval`, `paddle_frequency`, `paddle_currency_code`, `stripe_sandbox_price_id`, `stripe_live_price_id`, `create_new_stripe_product`, `stripe_billing_interval`, `stripe_trial_enabled`, `stripe_trial_days`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`) VALUES (138, 5, 'SDR-size-6.0', 1299.99, 1150.00, 0, 0.00, 0.00, 0.00, 1, 25.00, 1, 0.1, 'lbs', '{\n    \"Ring Size\": \"6\"\n}', 0, 1, NULL, NULL, NULL, NULL, NULL, 0, '', '', '', '', 0, 0, 0, NULL, NULL, '', '2026-08-05 23:11:58', '2026-08-05 23:12:12', 0, NULL, NULL, NULL, NULL, 1, 'USD', NULL, NULL, 0, 'month', 0, 0, 'Add Gift Wrapping', 'Gift Wrapping', 'Enter what you want to say on the included card or enter \"No Card\" if no card should be included.');
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (1, 1, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000001.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000001.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000001.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:46', '2026-08-05 22:45:09', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (2, 2, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_002.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_002.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_002.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:46', '2026-07-26 20:12:23', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (3, 3, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000003.webp', 'Diamond Mosaic Ring', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Size 5', 'Diamond Mosaic Ring', '2026-07-20 13:52:46', '2026-08-05 22:49:28', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (4, 10, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_004.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_004.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_004.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Size 5', '', '2026-07-20 13:52:46', '2026-08-05 23:01:14', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (5, 17, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000005.webp', 'Brilliant Ring', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', 'Brilliant Ring', '2026-07-20 13:52:46', '2026-08-05 23:09:50', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (6, 18, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000006.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000006.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000006.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:46', '2026-08-05 23:18:32', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (7, 19, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000007.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000007.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000007.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', NULL, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (8, 20, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000008.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000008.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000008.webp', 'Bracelet', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', 'Bracelet', '2026-07-20 13:52:46', '2026-08-05 23:22:54', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (9, 21, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000009.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000009.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000009.webp', 'Brilliant Bracelet', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', 'Brilliant Bracelet', '2026-07-20 13:52:46', '2026-08-05 23:30:17', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (10, 22, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000010.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000010.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000010.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', NULL, '2026-07-20 13:52:46', '2026-07-20 13:52:46', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (11, 23, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000011.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000011.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000011.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:46', '2026-08-06 00:25:46', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (12, 24, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000012.webp', 'Ruby and Diamond Bracelet', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Gold Tone', 'Ruby and Diamond Bracelet', '2026-07-20 13:52:46', '2026-08-04 00:04:05', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (13, 27, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000013.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000013.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000013.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-08-03 22:47:46', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (14, 28, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/jewelry-cleaning-pdf-main-thumb.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/jewelry-cleaning-pdf-main-thumb.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/jewerly-cleaning-pdf-zoom.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'PDF Download', '', '2026-07-20 13:52:47', '2026-07-24 17:45:12', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (18, 29, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/webinar.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/webinar.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/webinar.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Webinar + eBook Bundle', '', '2026-07-20 13:52:47', '2026-07-26 19:58:23', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (19, 29, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/webinar2.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/webinar2.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/webinar2.webp', '', NULL, 0, NULL, 2, 0, 1, NULL, NULL, NULL, NULL, 1, 'Jewelry Repair Guide', '', '2026-07-20 13:52:47', '2026-07-26 20:00:11', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (23, 31, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_black_sweatshirt.webp', 'black sweatshirt', NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Black / Small', 'Black Sweatshirt', '2026-07-20 13:52:47', '2026-07-21 14:17:09', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (24, 32, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_black_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Black / Medium', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (25, 33, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_black_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Black / Large', NULL, '2026-07-20 13:52:47', '2026-07-20 14:36:33', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (26, 34, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_black_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Black / XL', NULL, '2026-07-20 13:52:47', '2026-07-20 14:39:17', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (27, 35, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_black_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_black_sweatshirt.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Black / XXL', '', '2026-07-20 13:52:47', '2026-07-24 22:13:19', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (28, 36, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_burgundy sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Burgundy / Small', NULL, '2026-07-20 13:52:47', '2026-07-20 14:46:41', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (29, 37, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_burgundy sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Burgundy / Medium', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (30, 38, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_burgundy sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Burgundy / Large', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (31, 39, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_burgundy sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Burgundy / XL', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (32, 40, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_burgundy sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_burgundy sweatshirt.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Burgundy / XXL', '', '2026-07-20 13:52:47', '2026-07-24 22:13:42', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (33, 41, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_white_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'White / Small', NULL, '2026-07-20 13:52:47', '2026-07-20 14:46:02', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (34, 42, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_white_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'White / Medium', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (35, 43, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_white_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'White / Large', NULL, '2026-07-20 13:52:47', '2026-07-20 14:45:21', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (36, 44, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_white_sweatshirt.webp', NULL, NULL, 0, '', 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'White / XL', NULL, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (37, 45, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_white_sweatshirt.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_white_sweatshirt.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'White / XXL', '', '2026-07-20 13:52:47', '2026-07-24 22:13:29', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (39, 46, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_vintage_sample.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_vintage_sample.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_vintage_sample.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-08-03 23:40:57', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (41, 48, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/watch_brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/watch_brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/watch_brown.webp', 'Brown Strap', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Brown Strap', 'Brown Strap', '2026-07-20 13:52:47', '2026-08-04 16:08:48', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (43, 50, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/sample_pens.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/sample_pens.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/sample_pens.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-07-24 22:36:20', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (44, 51, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_gift_box.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_gift_box.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_gift_box.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-08-04 16:50:23', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (45, 52, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_pocket_modern.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_pocket_modern.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_pocket_modern.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-08-05 23:51:48', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (46, 53, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_modern_watch_sample.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_modern_watch_sample.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_modern_watch_sample.webp', '', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Standard', '', '2026-07-20 13:52:47', '2026-08-05 22:39:16', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (53, 60, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/brown.webp', 'Brown Shirt', NULL, 0, NULL, 1, 1, 1, NULL, NULL, NULL, NULL, 1, 'Brown', 'Brown Shirt', '2026-07-20 13:52:47', '2026-07-26 20:35:40', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (54, 61, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/gray.webp', 'Gray Shirt', NULL, 0, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 1, 'Gray', 'Gray Shirt', '2026-07-20 13:52:47', '2026-07-24 21:58:04', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (55, 62, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/green.webp', 'Green Shirt', NULL, 0, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 1, 'Green', 'Green Shirt', '2026-07-20 13:52:47', '2026-07-26 20:35:28', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (56, 63, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/navy.webp', 'Navy Blue Shirt', NULL, 0, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 1, 'Navy', 'Navy Blue Shirt', '2026-07-20 13:52:47', '2026-07-24 22:17:29', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (57, 64, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/orange.webp', 'Orange Shirt', NULL, 0, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 1, 'Orange', 'Orange Shirt', '2026-07-20 13:52:47', '2026-07-24 22:20:59', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (58, 65, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/royal.webp', 'Royal Blue Shirt', NULL, 0, NULL, 1, 0, 1, NULL, NULL, NULL, NULL, 1, 'Royal Blue', 'Royal Blue Shirt', '2026-07-20 13:52:47', '2026-07-24 22:27:28', 1);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (71, 85, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/brown.webp', 'Brown Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brown Shirt', '2026-07-24 21:50:38', '2026-07-24 21:57:00', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (72, 86, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/brown.webp', 'Brown Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brown Shirt', '2026-07-24 21:51:37', '2026-07-24 21:57:20', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (73, 87, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/brown.webp', 'Brown Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brown Shirt', '2026-07-24 21:52:41', '2026-07-24 21:57:30', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (74, 88, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/brown.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/brown.webp', 'Brown Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brown Shirt', '2026-07-24 21:53:49', '2026-07-24 21:57:44', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (75, 89, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/gray.webp', 'Gray Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Gray Shirt', '2026-07-24 21:58:11', '2026-07-24 21:58:11', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (76, 90, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/gray.webp', 'Gray Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Gray Shirt', '2026-07-24 21:58:59', '2026-07-24 21:58:59', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (77, 91, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/gray.webp', 'Gray Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Gray Shirt', '2026-07-24 21:59:25', '2026-07-24 21:59:25', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (78, 92, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/gray.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/gray.webp', 'Gray Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Gray Shirt', '2026-07-24 22:02:21', '2026-07-24 22:02:21', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (79, 93, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/green.webp', 'Green Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Green Shirt', '2026-07-24 22:06:05', '2026-07-24 22:06:55', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (80, 94, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/green.webp', 'Green Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Green Shirt', '2026-07-24 22:07:29', '2026-07-24 22:07:29', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (81, 95, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/green.webp', 'Green Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Green Shirt', '2026-07-24 22:07:48', '2026-07-24 22:07:48', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (82, 96, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/green.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/green.webp', 'Green Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Green Shirt', '2026-07-24 22:08:15', '2026-07-24 22:08:15', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (83, 97, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/navy.webp', 'Navy Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Navy Blue Shirt', '2026-07-24 22:17:37', '2026-07-24 22:17:37', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (84, 98, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/navy.webp', 'Navy Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Navy Blue Shirt', '2026-07-24 22:18:14', '2026-07-24 22:18:14', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (85, 99, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/navy.webp', 'Navy Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Navy Blue Shirt', '2026-07-24 22:18:47', '2026-07-24 22:18:47', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (86, 100, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/navy.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/navy.webp', 'Navy Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Navy Blue Shirt', '2026-07-24 22:19:10', '2026-07-24 22:19:10', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (87, 101, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/orange.webp', 'Orange Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Orange Shirt', '2026-07-24 22:22:54', '2026-07-24 22:22:54', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (88, 102, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/orange.webp', 'Orange Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Orange Shirt', '2026-07-24 22:24:04', '2026-07-24 22:24:04', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (89, 103, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/orange.webp', 'Orange Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Orange Shirt', '2026-07-24 22:24:32', '2026-07-24 22:24:32', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (90, 104, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/orange.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/orange.webp', 'Orange Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Orange Shirt', '2026-07-24 22:25:06', '2026-07-24 22:25:06', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (92, 106, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/royal.webp', 'Royal Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Royal Blue Shirt', '2026-07-24 22:29:32', '2026-07-24 22:29:32', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (93, 107, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/royal.webp', 'Royal Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Royal Blue Shirt', '2026-07-24 22:29:56', '2026-07-24 22:29:56', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (94, 108, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/royal.webp', 'Royal Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Royal Blue Shirt', '2026-07-24 22:30:18', '2026-07-24 22:30:18', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (95, 109, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/royal.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/royal.webp', 'Royal Blue Shirt', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Royal Blue Shirt', '2026-07-24 22:30:44', '2026-07-24 22:30:44', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (111, 126, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000012.webp', 'Ruby and Diamond Bracelet', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Ruby and Diamond Bracelet', '2026-08-04 00:07:23', '2026-08-04 00:07:23', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (112, 127, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_1000012.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_1000012.webp', 'Ruby and Diamond Bracelet', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Ruby and Diamond Bracelet', '2026-08-04 00:07:59', '2026-08-04 00:07:59', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (115, 130, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/watch_black.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/watch_black.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/watch_black.webp', 'Black Strap', NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Black Strap', '2026-08-04 16:08:32', '2026-08-04 16:11:30', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (116, 133, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000003.webp', 'Diamond Mosaic Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Diamond Mosaic Ring', '2026-08-05 22:48:44', '2026-08-05 22:49:18', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (117, 134, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000003.webp', 'Diamond Mosaic Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Diamond Mosaic Ring', '2026-08-05 22:49:36', '2026-08-05 22:49:36', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (118, 135, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000003.webp', 'Diamond Mosaic Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Diamond Mosaic Ring', '2026-08-05 22:50:00', '2026-08-05 22:50:00', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (119, 136, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000003.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000003.webp', 'Diamond Mosaic Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Diamond Mosaic Ring', '2026-08-05 22:52:23', '2026-08-05 22:52:23', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (120, 137, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000005.webp', 'Brilliant Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brilliant Ring', '2026-08-05 23:11:15', '2026-08-05 23:11:15', 0);
SQL
);

        // Table: product_images
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_images` (`id`, `variant_id`, `thumbnail_path`, `main_path`, `zoom_path`, `image_alt`, `image_url`, `image_s3`, `cdn_url`, `sort_order`, `search_image`, `active`, `image_s3_region`, `image_s3_bucket_name`, `image_s3_access_key_id`, `image_s3_secret_access_key`, `image_url_source`, `alt_label`, `zoom_label`, `created_at`, `updated_at`, `is_demo`) VALUES (121, 138, 'https://d23w3zagfzgqcb.cloudfront.net/images/thumbnails/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/mains/2021_sample_1000005.webp', 'https://d23w3zagfzgqcb.cloudfront.net/images/zooms/2021_sample_1000005.webp', 'Brilliant Ring', NULL, 0, NULL, 0, 1, 1, NULL, NULL, NULL, NULL, 1, NULL, 'Brilliant Ring', '2026-08-05 23:11:58', '2026-08-05 23:11:58', 0);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (1, 1, 12, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (2, 1, 13, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (3, 1, 7, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (4, 1, 9, 4, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (5, 2, 5, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (6, 2, 6, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (7, 2, 13, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (8, 2, 9, 4, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (9, 3, 2, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (10, 3, 1, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (11, 4, 2, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (12, 6, 5, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (13, 6, 4, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (14, 6, 2, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (15, 6, 3, 4, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (16, 8, 7, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (17, 8, 1, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (18, 9, 1, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (19, 9, 7, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (20, 9, 8, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (21, 11, 9, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (22, 11, 1, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (23, 11, 7, 4, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (24, 12, 9, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (25, 12, 1, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (26, 12, 7, 1, 0, 1, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (27, 12, 10, 2, 0, 1, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (28, 13, 7, 1, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (29, 13, 8, 2, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (30, 13, 12, 3, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (31, 13, 10, 1, 0, 1, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (36, 15, 14, 0, 1, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47', 1);
SQL
);

        // Table: product_cross_selling
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_cross_selling` (`id`, `product_id`, `cross_sell_product_id`, `sort_order`, `display_on_item_view`, `display_on_post_cart`, `created_at`, `updated_at`, `is_demo`) VALUES (37, 14, 15, 1, 1, 0, '2026-07-30 22:45:35', '2026-07-30 22:45:35', 0);
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (1, 1, 15, 15, 0, 0, 0, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (2, 2, 20, 20, 0, 0, 0, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (3, 3, 1, 5, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-05 22:51:35');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (10, 10, 1, 3, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-05 23:01:14');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (17, 17, 5, 5, 0, 0, 0, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (18, 18, 1, 0, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-05 23:18:32');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (19, 19, 12, 12, 0, 0, 0, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (20, 20, 0, 0, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-05 23:22:54');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (21, 21, 14, 15, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-10 17:14:54');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (22, 22, 5, 6, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-10 17:14:54');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (23, 23, 8, 8, 0, 0, 0, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (24, 24, 2, 3, 0, 0, 0, '2026-07-20 13:52:46', '2026-08-10 17:14:54');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (27, 27, 5, 4, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 23:41:05');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (28, 28, 999, 999, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (29, 29, 1000, 999, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-26 14:03:12');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (31, 31, 24, 29, 0, 5, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (32, 32, 24, 29, 0, 5, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (33, 33, 24, 29, 0, 5, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (34, 34, 13, 24, 0, 11, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (35, 35, 10, 24, 0, 3, 0, '2026-07-20 13:52:47', '2026-07-20 14:49:49');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (36, 36, 14, 24, 0, 10, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (37, 37, 21, 24, 0, 3, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (38, 38, 24, 24, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (39, 39, 2, 24, 0, 22, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (40, 40, 11, 24, 0, 13, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (41, 41, 24, 24, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (42, 42, 15, 24, 0, 9, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (43, 43, 6, 24, 0, 18, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (44, 44, 20, 24, 0, 4, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (45, 45, 7, 24, 0, 17, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (46, 46, 7, 12, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-21 00:08:23');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (48, 48, 0, 10, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-04 16:06:37');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (50, 50, 48, 50, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 23:57:05');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (51, 51, 25, 25, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (52, 52, 0, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-05 23:51:48');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (53, 53, 0, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-05 22:39:16');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (60, 60, 19, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-10 17:14:54');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (61, 61, 39, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 21:56:23');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (62, 62, 34, 20, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 22:05:35');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (63, 63, 46, 20, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 22:17:29');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (64, 64, 34, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 22:20:59');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (65, 65, 4, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-24 22:27:28');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (79, 79, 3, 3, 0, 0, 0, '2026-07-20 13:52:47', '2026-07-20 13:52:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (80, 80, 15, 0, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-05 22:20:55');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (83, 83, 4, 25, 0, 0, 0, '2026-07-20 13:52:47', '2026-08-05 22:27:44');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (85, 85, 12, 0, 0, 0, 0, '2026-07-24 21:50:38', '2026-07-24 21:51:26');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (86, 86, 31, 0, 0, 0, 0, '2026-07-24 21:51:37', '2026-07-24 21:52:18');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (87, 87, 9, 0, 0, 0, 0, '2026-07-24 21:52:41', '2026-07-24 21:53:17');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (88, 88, 9, 0, 0, 0, 0, '2026-07-24 21:53:49', '2026-07-24 21:53:49');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (89, 89, 0, 0, 0, 0, 0, '2026-07-24 21:58:11', '2026-07-24 21:58:50');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (90, 90, 17, 0, 0, 0, 0, '2026-07-24 21:58:59', '2026-07-24 21:59:21');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (91, 91, 17, 0, 0, 0, 0, '2026-07-24 21:59:25', '2026-07-24 21:59:25');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (92, 92, 0, 0, 0, 0, 0, '2026-07-24 22:02:21', '2026-07-24 22:02:44');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (93, 93, 11, 20, 0, 0, 0, '2026-07-24 22:06:05', '2026-08-02 00:38:24');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (94, 94, 11, 20, 0, 0, 0, '2026-07-24 22:07:29', '2026-08-02 14:07:06');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (95, 95, 6, 0, 0, 0, 0, '2026-07-24 22:07:48', '2026-07-24 22:08:13');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (96, 96, 8, 0, 0, 0, 0, '2026-07-24 22:08:15', '2026-07-24 22:08:39');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (97, 97, 13, 20, 0, 0, 0, '2026-07-24 22:17:37', '2026-07-24 22:18:04');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (98, 98, 7, 20, 0, 0, 0, '2026-07-24 22:18:14', '2026-07-24 22:18:31');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (99, 99, 7, 20, 0, 0, 0, '2026-07-24 22:18:47', '2026-07-24 22:18:47');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (100, 100, 17, 0, 0, 0, 0, '2026-07-24 22:19:10', '2026-07-24 22:19:38');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (101, 101, 23, 0, 0, 0, 0, '2026-07-24 22:22:54', '2026-07-24 22:23:58');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (102, 102, 0, 0, 0, 0, 0, '2026-07-24 22:24:04', '2026-07-24 22:24:26');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (103, 103, 12, 0, 0, 0, 0, '2026-07-24 22:24:32', '2026-07-24 22:24:59');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (104, 104, 12, 0, 0, 0, 0, '2026-07-24 22:25:06', '2026-07-24 22:25:06');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (106, 106, 4, 0, 0, 0, 0, '2026-07-24 22:29:32', '2026-07-24 22:29:32');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (107, 107, 39, 0, 0, 0, 0, '2026-07-24 22:29:56', '2026-07-24 22:30:15');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (108, 108, 2, 0, 0, 0, 0, '2026-07-24 22:30:18', '2026-07-24 22:30:39');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (109, 109, 2, 0, 0, 0, 0, '2026-07-24 22:30:44', '2026-07-24 22:30:44');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (125, 125, 0, 0, 0, 0, 1, '2026-07-30 22:30:20', '2026-07-31 19:14:03');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (126, 126, 13, 3, 0, 0, 0, '2026-08-04 00:07:23', '2026-08-04 00:07:52');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (127, 127, 2, 3, 0, 0, 0, '2026-08-04 00:07:59', '2026-08-04 00:12:10');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (130, 130, 24, 10, 0, 0, 0, '2026-08-04 16:08:32', '2026-08-04 16:09:46');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (132, 132, 0, 0, 0, 0, 0, '2026-08-05 22:19:43', '2026-08-05 22:22:29');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (133, 133, 5, 5, 0, 0, 0, '2026-08-05 22:48:44', '2026-08-05 22:51:45');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (134, 134, 24, 5, 0, 0, 0, '2026-08-05 22:49:36', '2026-08-05 22:52:01');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (135, 135, 0, 0, 0, 0, 0, '2026-08-05 22:50:00', '2026-08-05 22:52:15');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (136, 136, 6, 5, 0, 0, 0, '2026-08-05 22:52:23', '2026-08-05 22:52:45');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (137, 137, 5, 5, 0, 0, 0, '2026-08-05 23:11:15', '2026-08-05 23:11:15');
SQL
);

        // Table: products_inventory
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `products_inventory` (`id`, `variant_id`, `quantity_available`, `warehouse_stock_level`, `use_warehouse_stock`, `reserved_stock`, `location_id`, `created_at`, `updated_at`) VALUES (138, 138, 5, 5, 0, 0, 0, '2026-08-05 23:11:58', '2026-08-05 23:11:58');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (1, 'Temporarily Out Of Stock', 1, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (2, 'Back-Ordered: ETA 2 Weeks', 2, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (3, 'Back-Ordered: ETA 4 Weeks', 3, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (4, 'Item Discontinued', 4, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (5, 'Event Sold-Out', 5, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (6, 'Invoice Paid', 6, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_inventory_alerts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alerts` (`id`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES (7, 'Registration is no longer available for this event.', 7, 1, '2026-08-04 14:30:43', '2026-08-04 14:30:43');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (4, 126, 1, 2, 0.00, 1, '2026-08-04 00:10:27', '2026-08-04 00:10:27');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (5, 126, 2, 4, 130.00, 1, '2026-08-04 00:10:27', '2026-08-04 00:10:27');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (6, 126, 5, 1000000, 250.00, 1, '2026-08-04 00:10:27', '2026-08-04 00:10:27');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (7, 51, 1, 2, 0.00, 1, '2026-08-04 16:52:56', '2026-08-04 16:52:56');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (8, 51, 2, 3, 4.00, 1, '2026-08-04 16:52:56', '2026-08-04 16:52:56');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (9, 51, 3, 5, 8.00, 1, '2026-08-04 16:52:56', '2026-08-04 16:52:56');
SQL
);

        // Table: product_quantity_discounts
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_quantity_discounts` (`id`, `product_variant_id`, `qty_min`, `qty_max`, `discount_value`, `value_type`, `created_at`, `updated_at`) VALUES (10, 51, 5, 1000000, 12.00, 1, '2026-08-04 16:52:56', '2026-08-04 16:52:56');
SQL
);

        // Table: product_variant_events
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_events` (`id`, `variant_id`, `event_start_date`, `event_end_date`, `event_label`, `alternate_label`, `label_background`, `show_date`, `event_location`, `event_description`, `event_sort`, `created_at`, `updated_at`) VALUES (4, 79, '2027-04-14 09:00:00', '2026-04-15 17:00:00', '2-Day Social Media Workshop — 4/14-4/15', '2-Day Social Media Workshop — 4/14-4/15', '#0ea5e9', 1, 'Training Centre, Level 2', NULL, 1, '2026-07-20 13:52:47', '2026-08-05 22:33:07');
SQL
);

        // Table: product_variant_events
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_events` (`id`, `variant_id`, `event_start_date`, `event_end_date`, `event_label`, `alternate_label`, `label_background`, `show_date`, `event_location`, `event_description`, `event_sort`, `created_at`, `updated_at`) VALUES (5, 80, '2026-11-09 10:00:00', '2026-11-09 14:00:00', 'Inventory Management Seminar - Advanced Course', 'Inventory Management Seminar - Advanced Course (10am-2pm)', '#4f46e5', 1, NULL, NULL, 0, '2026-07-26 13:37:46', '2026-07-26 13:37:46');
SQL
);

        // Table: product_variant_events
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_events` (`id`, `variant_id`, `event_start_date`, `event_end_date`, `event_label`, `alternate_label`, `label_background`, `show_date`, `event_location`, `event_description`, `event_sort`, `created_at`, `updated_at`) VALUES (7, 132, '2027-03-09 10:00:00', '2027-03-09 14:00:00', 'Inventory Management Seminar - Advanced Course', 'Inventory Management Seminar - Advanced Course (10am-2pm)', '#4f46e5', 1, NULL, NULL, 0, '2026-08-05 22:19:43', '2026-08-05 22:20:43');
SQL
);

        // Table: product_variant_events
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_events` (`id`, `variant_id`, `event_start_date`, `event_end_date`, `event_label`, `alternate_label`, `label_background`, `show_date`, `event_location`, `event_description`, `event_sort`, `created_at`, `updated_at`) VALUES (8, 83, '2027-02-02 09:00:00', '2027-02-02 10:00:00', 'Inventory Management Seminar - Intro Course 9-10am', 'Inventory Management Seminar - Intro Course 9-10am', '#4f46e5', 1, NULL, NULL, 0, '2026-08-05 22:27:44', '2026-08-05 22:27:44');
SQL
);

        // Table: product_fields
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_fields` (`id`, `product_id`, `label`, `field_type`, `is_required`, `charge_tax`, `sort_order`, `created_at`, `updated_at`) VALUES (2, 4, 'Ring Size', 'select', 1, 1, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_fields
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_fields` (`id`, `product_id`, `label`, `field_type`, `is_required`, `charge_tax`, `sort_order`, `created_at`, `updated_at`) VALUES (12, 13, 'Bracelet Material', 'select', 1, 1, 0, '2026-08-03 22:44:58', '2026-08-03 22:53:52');
SQL
);

        // Table: product_fields
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_fields` (`id`, `product_id`, `label`, `field_type`, `is_required`, `charge_tax`, `sort_order`, `created_at`, `updated_at`) VALUES (13, 3, 'Add Platinum Ring Material', 'checkbox', 0, 1, 0, '2026-08-05 22:54:12', '2026-08-05 22:56:19');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (8, 2, '5', 0.00, 0.00, 1, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (9, 2, '5.5', 0.00, 0.00, 2, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (10, 2, '6', 0.00, 0.00, 3, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (11, 2, '6.5', 0.00, 0.00, 4, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (12, 2, '7', 0.00, 0.00, 5, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (13, 2, '7.5', 0.00, 0.00, 6, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (14, 2, '8', 0.00, 0.00, 7, '2026-07-20 13:52:46', '2026-07-20 13:52:46');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (54, 12, 'Silver', 0.00, 0.00, 0, '2026-08-03 22:44:58', '2026-08-03 22:46:24');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (55, 12, 'White Gold', 0.00, 0.00, 1, '2026-08-03 22:46:24', '2026-08-03 22:46:24');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (56, 12, 'Platinum', 100.00, 100.00, 2, '2026-08-03 22:47:09', '2026-08-03 22:48:31');
SQL
);

        // Table: product_field_options
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_field_options` (`id`, `product_field_id`, `option_value`, `option_price_modifier`, `option_wholesale_price_modifier`, `sort_order`, `created_at`, `updated_at`) VALUES (57, 13, 'Yes', 100.00, 100.00, 0, '2026-08-05 22:54:12', '2026-08-05 22:56:19');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (1, 14, 2, 'eBOOK de Limpieza de Joyas', 'Ejemplo de artículo de descarga digital. Las descargas se pueden distribuir a través de un enlace seguro a una carpeta local segura, un enlace de descarga expirada de s3 o un CDN (URL directa).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>eBOOK de Limpieza de Joyería &mdash; Cuidado Profesional, Pasos Simples</h2>\n<p>Mantén tu joyería fina brillante y libre de daños con este eBook descargable fácil de seguir. Ya sea que poseas diamantes, perlas, oro, plata o piezas de metales mixtos, esta guía ofrece instrucciones claras y prácticas, así como consejos de cuidado preventivo para que tus artículos atesorados luzcan lo mejor posible durante años. Entrega digital instantánea después de la compra.</p>\n<h3>¿Por qué este eBook?</h3>\n<ul>\n<li>Métodos de limpieza prácticos y paso a paso que puedes hacer en casa sin herramientas costosas</li>\n<li>Orientación de cuidado seguro para diamantes, piedras preciosas de colores, perlas, oro, plata y joyería chapada</li>\n<li>Rutinas de almacenamiento y mantenimiento que previenen el deslustre, rayones y desgaste</li>\n<li>Soluciones rápidas para problemas comunes (deslustre, nubosidad, engastes sueltos)</li>\n<li>Consejos para ahorrar costos que reducen limpiezas profesionales innecesarias</li>\n</ul>\n<h3>¿Qué hay dentro?</h3>\n<ul>\n<li>Procedimientos de limpieza fáciles de seguir para cada tipo de metal y piedra preciosa</li>\n<li>Suministros recomendados y una lista de herramientas asequibles</li>\n<li>Técnicas de pulido y abrillantado paso a paso</li>\n<li>Cómo limpiar materiales delicados como perlas y ópalos</li>\n<li>Soluciones de almacenamiento para prevenir nudos, rayones y corrosión</li>\n<li>Cuándo buscar reparación o inspección profesional</li>\n<li>Un calendario de mantenimiento que puedes seguir (diario, mensual, anual)</li>\n<li>Errores comunes a evitar y qué productos del hogar nunca usar</li>\n</ul>\n<h3>¿Quién debería leer este eBook?</h3>\n<ul>\n<li>Cualquiera que posea joyería fina y quiera preservar su valor y apariencia</li>\n<li>Regaladores que desean mantener herencias y piezas especiales en óptimas condiciones</li>\n<li>Coleccionistas de piezas vintage o de fantasía que necesitan métodos de cuidado seguros</li>\n<li>Propietarios de pequeñas boutiques o cuidadores que buscan rutinas de limpieza confiables</li>\n</ul>\n<h3>Entrega inmediata &amp; compatibilidad</h3>\n<p>Después de la compra, recibirás acceso instantáneo a un archivo de eBook descargable. El archivo es compatible con la mayoría de computadoras, tabletas y lectores electrónicos. Las instrucciones de descarga y un enlace se proporcionan en tu confirmación de pedido y se almacenan en tu cuenta para una conveniente re-descarga.</p>\n<h3>Fácil de usar</h3>\n<ul>\n<li>Lenguaje claro y secciones organizadas para que puedas encontrar respuestas rápidamente</li>\n<li>Listas de verificación accionables para seguir al limpiar o empacar joyería</li>\n<li>No se requiere capacitación especializada &mdash; ideal para principiantes y propietarios experimentados por igual</li>\n</ul>\n<h3>Soporte</h3>\n<p>Si tienes algún problema para descargar o abrir tu eBook, nuestro equipo de atención al cliente está listo para ayudar. Los detalles de contacto se incluyen con tu confirmación de compra.</p>\n<p><strong>Protege tu inversión y mantén cada pieza brillante.</strong> Descarga el eBook de Limpieza de Joyería ahora y comienza a cuidar tu joyería de la manera correcta.</p>\n</div>\n<p>&nbsp;</p>', 'eBOOK de Limpieza de Joyas', 'Descarga nuestro eBook de limpieza de joyas. Entrega digital instantánea.', 'ai_translated', '2026-08-10 14:26:26', '2026-07-27 23:11:11', '2026-08-10 14:26:38');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (2, 15, 2, 'Webinar de Reparación de Joyas + Manual', 'Este artículo de muestra demuestra tanto la función de video de vista previa (diseño de video) como cómo se puede mostrar un video después de la compra junto con cualquier medio correspondiente, como PDFs complementarios, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Webinar de Reparación de Joyería + Manual</h2>\n<p>Este producto de muestra incluye un demo, un archivo de webinar pregrabado, un manual descargable y un enlace de vista previa del manual a continuación. La descarga del pedido y la visualización del webinar están controladas a través de la seguridad del pedido, mientras que la descarga a continuación es un shortcode de descarga a través del administrador de descargas de CMS que te permite agregar enlaces de descarga seguros a cualquier producto o página del sitio.</p>\n<h3>Qué incluye</h3>\n<ul>\n<li><strong>Webinar grabado:</strong> Demostraciones en video de larga duración y paso a paso que puedes transmitir o descargar después de la compra.</li>\n<li><strong>Manual completo (PDF):</strong> eBook detallado y imprimible que refleja el flujo de trabajo del webinar e incluye espacio para tus notas.<br>[download:d971912d-cb2e-4790-98ae-8ec53bac2503 label=\"Preview the Guidebook\"]</li>\n<li><strong>Listas de verificación y hojas de trabajo prácticas:</strong> Listas de herramientas, recordatorios de seguridad y listas de verificación de proyectos para usar en el banco.</li>\n<li><strong>Medios y recursos de muestra:</strong> Planes de reparación de ejemplo y diagramas de referencia proporcionados como PDFs descargables.</li>\n</ul>\n<h3>Técnicas y habilidades que aprenderás</h3>\n<ul>\n<li>Fundamentos de soldadura y consejos para uniones limpias en oro y plata</li>\n<li>Reparación de cadenas y cierres, limpieza y reacondicionamiento</li>\n<li>Fundamentos de redimensionamiento de anillos y mejores prácticas de tamaño</li>\n<li>Reparación de garras, re-tipping y ajuste para configuraciones seguras</li>\n<li>Ajustes de engaste de bisel y engaste al ras para diferentes piedras preciosas</li>\n<li>Técnicas de pulido, acabado y reparación de superficies</li>\n<li>Solución de problemas simple y cómo evitar errores comunes</li>\n</ul>\n<h3>Para quién es esto</h3>\n<ul>\n<li>Joyería de banco principiantes que buscan capacitación estructurada y visual</li>\n<li>Fabricantes experimentados que desean refrescar rápidamente las mejores prácticas</li>\n<li>Propietarios de pequeñas empresas y minoristas listos para reparar</li>\n<li>Cualquiera que quiera reparar piezas personales o sentimentales en casa</li>\n</ul>\n<h3>Cómo funciona</h3>\n<ul>\n<li>Acceso digital instantáneo después de la compra — no se enviará ningún producto físico.</li>\n<li>Transmite el webinar grabado desde tu cuenta o descarga los archivos para ver sin conexión.</li>\n<li>Sigue el eBook y utiliza las hojas de trabajo incluidas para tomar tus propias notas y hacer un seguimiento del progreso.</li>\n</ul>\n<h3>Detalles técnicos y requisitos del sistema</h3>\n<ul>\n<li>Formato de video: MP4 (transmisión y descargable)</li>\n<li>Manual: PDF (imprimible)</li>\n<li>Compatible con navegadores modernos, dispositivos de escritorio o móviles; requiere un lector de PDF y conexión a internet básica para transmisión o descarga.</li>\n</ul>\n<h3>Por qué funciona este paquete</h3>\n<p>Esta combinación de demostración visual más una guía escrita detallada te permite ver reparaciones realizadas en tiempo real, y luego seguir los mismos pasos en tu banco con instrucciones claras e imprimibles. El enfoque práctico significa que adquirirás técnicas utilizables que puedes aplicar de inmediato—ya sea reparando un preciado legado o ofreciendo servicios de reparación a los clientes.</p>\n<p><strong>¿Listo para comenzar?</strong> Compra ahora para acceso instantáneo y comienza a aprender técnicas prácticas de reparación de joyería hoy.</p>\n</div>\n<p>&nbsp;</p>', 'Webinar de Reparación de Joyas Más eBook', 'Webinar de reparación de joyas más eBook. Paquete de descarga digital.', 'ai_translated', '2026-08-10 14:26:38', '2026-07-27 23:11:22', '2026-08-10 14:26:51');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (3, 28, 2, 'Cuenta de Pago de Facturas | Ejemplo de Recarga', 'Ingrese la cantidad que le gustaría pagar en el espacio provisto a continuación. Mín $25 | Máx $100', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Donación | Ejemplo de Pago de Factura</h2>\n<p>Esta opción de producto te permite contribuir con una cantidad fija o ingresar una cantidad personalizada &mdash; dentro del rango permitido &mdash; y tener los fondos acreditados a tu cuenta inmediatamente después de una facturación exitosa. Ideal para recargas de cuenta, donaciones únicas o pagos de facturas manejados como un artículo de servicio.</p>\n<h3>Cómo usar</h3>\n<ul>\n<li><strong>Elige una cantidad preestablecida:</strong> Selecciona uno de los valores predefinidos de la lista para un pago rápido.</li>\n<li><strong>O ingresa una cantidad personalizada:</strong> Escribe cualquier valor entre <strong>$25</strong> y <strong>$100</strong> para establecer la cantidad exacta que deseas pagar.</li>\n<li><strong>Completa el pago:</strong> Procede al pago. Después de una facturación exitosa, la cantidad se acreditará a tu cuenta inmediatamente.</li>\n</ul>\n<h3>Beneficios clave</h3>\n<ul>\n<li><strong>Opciones flexibles:</strong> Usa cantidades de selección rápida o ingresa un valor específico para satisfacer tus necesidades.</li>\n<li><strong>Crédito inmediato:</strong> Los fondos se aplican a tu cuenta tan pronto como se completa la facturación.</li>\n<li><strong>Procesamiento seguro:</strong> Los pagos se manejan a través de nuestro pago seguro para proteger tu información.</li>\n<li><strong>Registros claros:</strong> Recibirás una confirmación y un recibo de tu transacción.</li>\n</ul>\n<h3>Detalles importantes</h3>\n<ul>\n<li>Cantidad mínima: <strong>$25</strong></li>\n<li>Cantidad máxima: <strong>$100</strong></li>\n<li>Por favor, asegúrate de que la cantidad que ingresas esté dentro del rango anterior; las transacciones fuera de este rango no serán aceptadas.</li>\n<li>Si tienes preguntas sobre el procesamiento, créditos o recibos, contacta a nuestro equipo de soporte para obtener asistencia.</li>\n</ul>\n<p><em>Nota:</em> Este artículo se proporciona como un ejemplo de la funcionalidad de pago de facturas/donaciones y demuestra cómo un administrador puede configurar cantidades de pago fijas o ingresadas por el cliente dentro de un rango mínimo/máximo especificado.</p>\n</div>\n<p>&nbsp;</p>', 'Pago de Facturas | Ejemplo de \"Hacer una Oferta\"', 'Este artículo de muestra muestra cómo el administrador puede crear un artículo para aceptar ya sea una cantidad establecida (a través de una lista) o una cantidad ingresada por el cliente dentro de un rango mínimo/máximo.', 'ai_translated', '2026-08-10 14:26:51', '2026-07-27 23:11:25', '2026-08-10 14:27:02');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (4, 30, 2, 'Taller de Redes Sociales de 2 Días', 'Taller intensivo de redes sociales de 2 días.<br><br><strong>14/4-15/4/2027 (9am-5pm cada día)</strong>', NULL, 'Taller de Redes Sociales de 2 Días', 'Taller de redes sociales de 2 días. Capacitación práctica para Instagram, Facebook, LinkedIn.', 'ai_translated', '2026-08-10 14:27:02', '2026-07-27 23:11:38', '2026-08-10 14:27:05');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (5, 31, 2, 'Seminario de Gestión de Inventarios - Curso Avanzado', 'Demuestra el estado del evento personalizado en eventos agotados. (Mensaje personalizado de fuera de stock)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Seminario Avanzado de Gestión de Inventarios &mdash; Optimiza el Stock, Reduce Costos, Mejora el Servicio</h2>\n<p>Toma el control de tu inventario con un seminario práctico y hands-on diseñado para equipos de retail y comercio electrónico listos para ir más allá de lo básico. Este curso avanzado enseña pronósticos probados, planificación de la demanda, optimización de almacenes y estrategias de reabastecimiento automatizado que reducen las faltas de stock, disminuyen los costos de almacenamiento y mejoran los niveles de servicio al cliente.</p>\n<h3>Lo que ganarás</h3>\n<ul>\n<li><strong>Técnicas de pronóstico accionables</strong> &mdash; aplica métodos de series temporales, ajustes de estacionalidad y factores causales para producir pronósticos de demanda más fiables.</li>\n<li><strong>Estrategias de reabastecimiento inteligentes</strong> &mdash; diseña e implementa políticas de min/max, punto de reorden, EOQ y stock de seguridad que se ajusten a los ritmos de tu negocio y a los tiempos de entrega de los proveedores.</li>\n<li><strong>Optimización de almacenes</strong> &mdash; optimiza la disposición, la asignación de espacios y los flujos de picking para reducir el tiempo de manipulación y mejorar el rendimiento.</li>\n<li><strong>Toma de decisiones basada en datos</strong> &mdash; utiliza segmentación (ABC/XYZ), KPIs y paneles de control para enfocar esfuerzos donde realmente impactan.</li>\n<li><strong>Automatización &amp; integración de sistemas</strong> &mdash; orientación práctica para integrar pronósticos y reabastecimiento con ERP, WMS y plataformas de comercio electrónico.</li>\n<li><strong>Planificación de escenarios &amp; gestión de riesgos</strong> &mdash; técnicas para manejar la volatilidad de la demanda, interrupciones de proveedores y promociones sin sobrestock.</li>\n</ul>\n<h3>Quién debería asistir</h3>\n<ul>\n<li>Gerentes de inventario, cadena de suministro y operaciones</li>\n<li>Líderes de merchandising en comercio electrónico y retail</li>\n<li>Supervisores de almacén y coordinadores logísticos</li>\n<li>Profesionales de compras responsables de políticas de reorden</li>\n<li>Analistas de negocio y socios financieros enfocados en costos de inventario</li>\n</ul>\n<h3>Formato del curso &amp; componentes prácticos</h3>\n<ul>\n<li>Seminario intensivo dirigido por un instructor que presenta estudios de caso del mundo real y ejercicios prácticos</li>\n<li>Talleres interactivos donde los asistentes construyen modelos de pronóstico y reglas de reabastecimiento</li>\n<li>Plantillas y herramientas (modelos de Excel, paneles de KPIs, listas de verificación de SOP) que puedes adaptar de inmediato</li>\n<li>Preguntas y respuestas y discusión entre pares para abordar tus desafíos operativos específicos</li>\n</ul>\n<h3>Resultados clave</h3>\n<ul>\n<li>Mejora en la precisión de los pronósticos y un proceso claro para la revisión continua de pronósticos</li>\n<li>Políticas de reabastecimiento alineadas con los perfiles de demanda y el rendimiento de los proveedores</li>\n<li>Cambios prácticos en el almacén que aumentan la velocidad de picking y reducen errores</li>\n<li>Una hoja de ruta para automatizar flujos de trabajo de inventario e integrar sistemas</li>\n<li>Herramientas y plantillas para llevar a casa y aplicar mejoras de inmediato</li>\n</ul>\n<h3>Requisitos previos</h3>\n<ul>\n<li>Familiaridad con conceptos básicos de inventario (punto de reorden, stock de seguridad, tiempo de entrega)</li>\n<li>Comodidad con hojas de cálculo (Excel/Google Sheets) para ejercicios del taller</li>\n</ul>\n<h3>Dirigido por profesionales experimentados</h3>\n<p>Este seminario es impartido por instructores con experiencia práctica en la implementación de soluciones de inventario y reabastecimiento para negocios de retail y comercio electrónico. Las sesiones se centran en métodos prácticos y repetibles que puedes aplicar de inmediato en tu operación.</p>\n<p><strong>Los asientos son limitados para mantener un entorno de aprendizaje interactivo.</strong> Regístrate ahora para asegurar tu lugar y comenzar a convertir el inventario en una ventaja competitiva.</p>\n</div>\n<p>&nbsp;</p>', 'Seminario de Gestión de Inventarios - Curso Avanzado', 'Seminario avanzado de gestión de inventarios. Capacitación en retail y comercio electrónico.', 'ai_translated', '2026-08-10 14:27:05', '2026-07-27 23:11:41', '2026-08-10 14:27:18');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (6, 34, 2, 'Seminario de Gestión de Inventarios - Curso Introductorio', 'Seminario introductorio de gestión de inventarios para nuevos propietarios de negocios. <br><br><strong>2 de febrero de 2027 | 9-10 am</strong>', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Descripción general</h2>\n<p>Este seminario introductorio sobre Gestión de Inventarios está diseñado para nuevos propietarios de negocios y personal de operaciones en etapas tempranas que necesitan orientación clara y práctica sobre el control de existencias, la reducción de desperdicios y la toma de decisiones de compra más inteligentes. El seminario desglosa los conceptos básicos de inventario en pasos fáciles de seguir para que puedas aplicarlos a tu negocio de inmediato.</p>\n<h2>Lo que aprenderás</h2>\n<ul>\n<li>Conceptos fundamentales de inventario: por qué el inventario es importante para el flujo de efectivo, la satisfacción del cliente y la rentabilidad</li>\n<li>Métodos simples de conteo de existencias y mejores prácticas para registros de inventario precisos</li>\n<li>Cómo estructurar órdenes de compra básicas y agilizar la comunicación con los proveedores</li>\n<li>Cómo calcular y aplicar puntos de reorden y stock de seguridad sencillos para artículos de uso diario</li>\n<li>Enfoques prácticos para reducir faltantes de stock y exceso de inventario sin sistemas complejos</li>\n</ul>\n<h2>Quién debería asistir</h2>\n<ul>\n<li>Nuevos propietarios de negocios y emprendedores que gestionan inventario por primera vez</li>\n<li>Gerentes de retail, propietarios de cafeterías y restaurantes, y pequeños operadores mayoristas</li>\n<li>Vendedores de comercio electrónico que manejan inventario a través de uno o más canales</li>\n<li>Personal administrativo u operativo responsable de pedidos y control de stock</li>\n</ul>\n<h2>Cómo se entrega el seminario</h2>\n<p>Entregado en un formato de taller enfocado y dirigido por un instructor, el seminario combina presentaciones conceptuales breves con ejemplos del mundo real y una sesión interactiva de preguntas y respuestas. El ritmo es amigable para principiantes y práctico—no se requiere experiencia avanzada en contabilidad o software.</p>\n<h2>Beneficios para tu negocio</h2>\n<ul>\n<li>Toma decisiones de pedido más rápidas y seguras que liberan efectivo y reducen costos de mantenimiento</li>\n<li>Mejora la precisión del stock y evita costosos faltantes durante la demanda máxima</li>\n<li>Adopta rutinas de conteo y pedido repetibles que ahorran tiempo y reducen errores</li>\n<li>Obtén un método sencillo para establecer puntos de reorden que puedes usar de inmediato</li>\n</ul>\n<h2>Qué esperar después del seminario</h2>\n<p>Los participantes saldrán con una comprensión clara y accionable de los flujos de trabajo básicos de inventario y los próximos pasos prácticos para implementar en su negocio. Podrás realizar conteos de stock efectivos, hacer pedidos de compra más inteligentes y utilizar cálculos simples de reorden para mantener el inventario en los niveles adecuados.</p>\n<h2>Requisitos previos</h2>\n<p>No se requiere conocimiento previo de inventario o contabilidad. Trae ejemplos de tus listas de stock actuales o desafíos de compra para hacer la sesión más relevante y accionable.</p>\n<p><strong>¿Listo para tomar el control de tu stock?</strong> Únete a este seminario para construir una base sólida de inventario que apoye el crecimiento, reduzca desperdicios y te ahorre tiempo. Regístrate hoy para reservar tu lugar.</p>\n</div>', 'Seminario de Gestión de Inventarios - Curso Introductorio', 'Seminario introductorio de gestión de inventarios. Capacitación para nuevos propietarios de negocios.', 'ai_translated', '2026-08-10 14:27:18', '2026-07-27 23:11:44', '2026-08-10 14:27:30');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (7, 1, 2, 'Pulsera de 3 Ct 14k|24k', 'Artículo de muestra que muestra asociaciones de venta cruzada.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Pulsera de Diamantes de Oro Blanco de 14K &mdash; Elegancia Atemporal</h2>\n<p>Eleva cualquier look con esta hermosa pulsera de diamantes elaborada en lustroso oro blanco de 14K. Con diamantes de corte brillante que suman un total de 1/4 de quilate, la pulsera combina un delicado brillo con un diseño de eslabones refinado y flexible para comodidad y un uso sin esfuerzo de día a noche.</p>\n<h3>Características Clave</h3>\n<ul>\n<li><strong>Metal:</strong> Oro blanco de 14K con un acabado pulido</li>\n<li><strong>Diamantes:</strong> Diamantes de corte brillante, colocados expertamente, peso total de 1/4 de quilate</li>\n<li><strong>Diseño:</strong> Construcción de eslabones flexibles para un ajuste cómodo y natural</li>\n<li><strong>Ocasiones:</strong> Lo suficientemente elegante para eventos especiales, discreto para el uso diario</li>\n</ul>\n<h3>Por Qué Te Encantará</h3>\n<ul>\n<li>El clásico oro blanco y los brillantes diamantes crean una pieza versátil que complementa cualquier guardarropa.</li>\n<li>El diseño de bajo perfil y flexible se asienta cómodamente en la muñeca mientras sigue ofreciendo un brillo llamativo.</li>\n<li>Un regalo ideal para aniversarios, cumpleaños, graduaciones o como un token significativo para cualquier momento especial.</li>\n</ul>\n<h3>Consejos de Estilo</h3>\n<ul>\n<li>Llévala sola para un look refinado y minimalista.</li>\n<li>Apílala con otras pulseras o un reloj delgado para crear un efecto en capas personalizado.</li>\n<li>Combina maravillosamente con conjuntos tanto casuales como formales &mdash; desde jeans hasta ropa de noche.</li>\n</ul>\n<h3>Cuidado y Mantenimiento</h3>\n<ul>\n<li>Quítatela antes de ducharte, nadar o hacer tareas del hogar para preservar el acabado y el brillo de las piedras.</li>\n<li>Límpiala suavemente con un cepillo suave y jabón suave, luego enjuaga y seca con un paño sin pelusa.</li>\n<li>Guárdala separada de otras joyas para evitar rayones.</li>\n</ul>\n<p>Deja una impresión duradera con esta elegante pulsera de diamantes de oro blanco de 14K &mdash; una adición atemporal a cualquier colección de joyas y un regalo reflexivo y deslumbrante para alguien especial.</p>\n</div>\n<p>&nbsp;</p>', '14k|24k Pulsera de 3 Ct', 'Compra nuestra impresionante colección de pulseras de diamantes. Joyería fina elaborada en oro blanco de 14 quilates.', 'ai_translated', '2026-08-10 14:27:30', '2026-07-27 23:11:49', '2026-08-10 14:27:44');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (8, 2, 2, 'Anillo Corazón de Zafiro', 'Anillo elegante de corazón de zafiro — una pieza atemporal para cualquier guardarropa. Este artículo de muestra está a precio de oferta para mostrar la diferencia en la exhibición de precios.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Anillo Corazón de Zafiro</h1>\n<p><strong>Elegante. Romántico. Atemporal.</strong> El Anillo Corazón de Zafiro combina un elegante zafiro en forma de corazón con cálido oro amarillo de 14K para una pieza que se siente tanto clásica como contemporánea. Finamente elaborado con un engaste de garras seguro, este anillo está diseñado para brillar desde momentos cotidianos hasta las ocasiones más memorables de la vida.</p>\n<h2>Por qué te encantará</h2>\n<ul>\n<li><strong>Piedra central distintiva:</strong> Un zafiro en forma de corazón que captura la luz y la atención con un color sutil y duradero.</li>\n<li><strong>Artesanía clásica:</strong> Engastado en oro amarillo de 14K con un engaste de garras seguro para una belleza y seguridad duraderas.</li>\n<li><strong>Estilo versátil:</strong> Lo suficientemente elegante para la ropa de noche, lo suficientemente discreto para el uso diario&mdash;combina maravillosamente con otros anillos o se usa solo como una pieza de declaración.</li>\n<li><strong>Regalo significativo:</strong> Una elección romántica para aniversarios, compromisos, cumpleaños o cualquier momento que desees marcar con amor.</li>\n</ul>\n<h2>Detalles del producto</h2>\n<ul>\n<li><strong>Metal:</strong> Oro amarillo de 14K</li>\n<li><strong>Piedra preciosa:</strong> Zafiro en forma de corazón</li>\n<li><strong>Engaste:</strong> Engaste de garras seguro</li>\n<li><strong>Acabado:</strong> Pulido brillante</li>\n<li><strong>Artesanía:</strong> Acabado a mano para un detalle refinado</li>\n</ul>\n<h2>Personalización y tamaños</h2>\n<p>Este anillo está disponible en tamaños estándar y se puede personalizar a pedido. Las opciones a menudo incluyen alternativas de metal y grabado personalizado&mdash;contacta a nuestro equipo para crear una pieza a medida que refleje perfectamente tu estilo e historia.</p>\n<h2>Cuidado y mantenimiento</h2>\n<ul>\n<li>Limpia suavemente con agua tibia, jabón suave y un cepillo suave; enjuaga y seca completamente.</li>\n<li>Evita productos químicos agresivos, temperaturas extremas y golpes para preservar la piedra preciosa y el acabado.</li>\n<li>Almacena por separado para evitar rayones y mantener el brillo.</li>\n</ul>\n<h2>Tranquilidad</h2>\n<p>Cada Anillo Corazón de Zafiro es inspeccionado para cumplir con nuestros estándares de calidad y viene con soporte al cliente dedicado para ayudar con el tamaño, cuidado y cualquier pregunta. Para asistencia con la personalización o el pedido, nuestro equipo está encantado de ayudar.</p>\n<p><strong>Hazlo tuyo:</strong> Agrega el Anillo Corazón de Zafiro a tu colección hoy y usa un símbolo atemporal de amor y elegancia durante años.</p>\n</div>\n<p>&nbsp;</p>', 'Anillo Corazón de Zafiro', 'Anillo de zafiro en forma de corazón de oro de 14K. Compra joyería fina.', 'ai_translated', '2026-08-10 14:27:44', '2026-07-27 23:11:59', '2026-08-10 14:28:03');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (9, 3, 2, 'Anillo de mosaico de diamantes', 'Demuestra los selectores de tamaño más la opción de venta adicional.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Descripción del Producto</h2>\n<p>Inspirado en motivos de flores atemporales y precisión moderna, el Anillo de Mosaico de Diamantes da vida a una delicada flor en una deslumbrante variedad de diamantes. Meticulosamente elaborado con un intrincado patrón de mosaico, cada pequeña piedra captura la luz desde todos los ángulos para crear una superficie brillante y centelleante que se lee como una única declaración floral. Perfecto como anillo de firma, acento de compromiso o una pieza elevada para el día a día.</p>\n<h2>Características Clave</h2>\n<ul>\n<li><strong>Diseño:</strong> Mosaico floral elegante &mdash; un intrincado grupo de diamantes dispuestos para parecer una flor en plena floración.</li>\n<li><strong>Opciones de Metal:</strong> Montado en oro blanco de 14K (estándar). Actualización a platino disponible para mayor durabilidad y un acabado blanco más brillante.</li>\n<li><strong>Montaje de Diamantes:</strong> Múltiples diamantes están cuidadosamente montados para maximizar el brillo y la continuidad visual a través del motivo.</li>\n<li><strong>Artesanía:</strong> Detalles acabados a mano y montaje experto aseguran una estructura duradera y una apariencia refinada.</li>\n<li><strong>Personalización:</strong> Disponible en una variedad de tamaños de anillo; se aceptan solicitudes de actualización a platino y personalización. Contáctenos para opciones especiales de tamaño o personalización.</li>\n</ul>\n<h2>Por Qué Te Encantará</h2>\n<p>El Anillo de Mosaico de Diamantes equilibra el encanto romántico con la finura contemporánea. Su diseño compacto pero altamente detallado lo hace versátil &mdash; hermoso por sí solo o combinado con otros anillos. El mosaico de múltiples piedras da la impresión de una mayor superficie de luz, ofreciendo una presencia impactante sin un perfil voluminoso.</p>\n<h2>Materiales &amp; Cuidado</h2>\n<ul>\n<li><strong>Metales:</strong> Oro blanco de 14K (estándar). Platino disponible bajo pedido.</li>\n<li><strong>Diamantes:</strong> Cuidadosamente seleccionados y obtenidos de manera responsable para ofrecer un brillo excepcional y durabilidad.</li>\n<li><strong>Cuidado:</strong> Limpie suavemente con un cepillo suave y agua jabonosa suave; evite productos químicos agresivos y limpiadores abrasivos. Almacene por separado en una bolsa suave o caja de joyería para evitar rayones.</li>\n</ul>\n<h2>Tamaños, Envío &amp; Servicios</h2>\n<ul>\n<li><strong>Tamaños:</strong> Disponible en tamaños de anillo estándar. Si necesita ayuda para determinar su tamaño, contáctenos para orientación. Los servicios de ajuste pueden estar disponibles &mdash; por favor consulte antes de la compra si requiere un ajuste preciso.</li>\n<li><strong>Tiempo de Entrega:</strong> Esta pieza puede ser hecha a pedido o adaptada a sus especificaciones. Los tiempos de producción y envío varían; contáctenos para estimaciones actuales de tiempo de entrega.</li>\n<li><strong>Embalaje:</strong> Cada anillo se envía de manera segura en un embalaje protector y llega en una caja de joyería lista para regalo.</li>\n<li><strong>Solicitudes Personalizadas:</strong> Para actualizaciones a platino, tamaños especiales, grabados u otra personalización, comuníquese con nuestros especialistas en joyería antes de realizar el pedido.</li>\n</ul>\n<h2>¿Necesita Ayuda?</h2>\n<p>Si tiene preguntas sobre las opciones de metal, tamaños o opciones personalizadas (incluida una actualización a platino), nuestro equipo está aquí para ayudar. Seleccione su metal y tamaño preferidos, o contáctenos para solicitudes a medida y orientación experta.</p>\n<p><strong>Hazlo tuyo:</strong> Elige el Anillo de Mosaico de Diamantes para una pieza intrincada y luminosa que celebra la artesanía y la elegancia femenina.</p>\n</div>\n<p>&nbsp;</p>', 'Anillo de mosaico de diamantes', 'Anillo de mosaico de diamantes brillante. ¡Haz un gran regalo para esa persona especial!', 'ai_translated', '2026-08-10 14:28:03', '2026-07-27 23:12:03', '2026-08-10 14:28:46');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (10, 5, 2, 'Anillo de Zafiro y Diamante', 'Artículo de muestra que muestra un diseño de página alternativo (imágenes del lado izquierdo) además de una opción de envoltura de regalo junto con opciones de tamaño. Los niveles de inventario también están ocultos según la configuración en las opciones de producto avanzadas.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Anillo de Zafiro y Diamante</h2>\n<p>Elegante y atemporal, este anillo de zafiro y diamante combina un zafiro de corte brillante rico con acentos de diamante que brillan, todo en un lustroso oro blanco de 14K. El clásico engaste de garras maximiza el retorno de luz y muestra el fuego y la profundidad de cada piedra, creando un centro refinado que transita fácilmente del uso diario a ocasiones especiales.</p>\n<h3>Por qué te encantará</h3>\n<ul>\n<li>Diseño clásico: Una silueta tradicional que se mantiene elegante por generaciones.</li>\n<li>Brillo brillante: Las piedras de corte brillante y los engastes abiertos permiten un brillo máximo y una radiancia de día a noche.</li>\n<li>Materiales duraderos: El oro blanco de 14K ofrece un acabado duradero y plateado adecuado para el uso regular.</li>\n<li>Estilo versátil: Combina maravillosamente con alianzas de boda, otros anillos, o se puede usar solo como una pieza de declaración.</li>\n</ul>\n<h3>Detalles y personalización</h3>\n<ul>\n<li>Metal: Oro blanco de 14K</li>\n<li>Piedras: Zafiro central con acentos de diamante de corte brillante</li>\n<li>Engaste: Clásico engaste de garras para mejorar el rendimiento de la luz</li>\n<li>Opciones personalizadas: Disponible para ordenar con tamaños personalizados y metales alternativos (por favor seleccione opciones al finalizar la compra o contáctenos para un presupuesto personalizado)</li>\n<li>Hecho a mano: Cada anillo es cuidadosamente terminado por joyeros expertos para asegurar una belleza y calidad duraderas</li>\n</ul>\n<h3>Cuidado y mantenimiento</h3>\n<ul>\n<li>Limpie periódicamente con agua tibia jabonosa y un cepillo suave; evite productos químicos agresivos y limpiadores ultrasónicos si la pieza contiene piedras tratadas.</li>\n<li>Retire durante trabajos físicos intensos o exposición a sustancias abrasivas para proteger los engastes y el acabado.</li>\n<li>Se recomienda una inspección y limpieza profesional anualmente para mantener la seguridad del engaste y el brillo.</li>\n</ul>\n<p>Este Anillo de Zafiro y Diamante es un regalo significativo para aniversarios, cumpleaños, compromisos o cualquier momento que requiera algo especial. Para detalles de certificación, redimensionamiento o solicitudes personalizadas, por favor contáctenos con nuestro equipo de joyería personalizada &mdash; estaremos encantados de ayudar a crear el anillo perfecto para ti.</p>\n</div>', 'Anillo de Zafiro y Diamante', 'Anillo clásico de zafiro y diamante. Compra en nuestra colección de joyería fina.', 'ai_translated', '2026-08-10 14:28:46', '2026-07-27 23:12:06', '2026-08-10 14:28:57');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (11, 8, 2, 'Pulsera de Diamante Estilo Pinzado', 'Ejemplo que muestra el mensaje predeterminado de fuera de stock (Actualmente No Disponible) así como la venta cruzada (recomendaciones de productos).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Pulsera de Diamantes Estilo Pinzado</h1>\n<p>Elegante y discreta, la Pulsera de Diamantes Estilo Pinzado combina una delicada artesanía con un brillo diario. Expertamente elaborada en oro blanco de 14K, sus eslabones de estilo pinzado están engastados con diamantes de corte brillante que capturan la luz desde todos los ángulos&mdash;creando una silueta refinada y texturizada que es perfecta para apilar o usar sola.</p>\n<h2>Características Clave</h2>\n<ul>\n<li><strong>Metal:</strong> Oro blanco fino de 14K con un acabado pulido.</li>\n<li><strong>Gemstones:</strong> Diamantes de corte brillante engastados a lo largo para un brillo continuo.</li>\n<li><strong>Diseño:</strong> Eslabones de estilo pinzado que ofrecen una sutil textura y una mejor reflexión de la luz.</li>\n<li><strong>Usabilidad:</strong> Construcción delicada y ligera ideal para el uso diario o ocasiones especiales.</li>\n<li><strong>Cierre seguro:</strong> Terminado con un cierre confiable para un uso cómodo y seguro.</li>\n</ul>\n<h2>Estilo y Ocasión</h2>\n<p>La silueta atemporal de esta pulsera la convierte en una adición versátil a cualquier guardarropa de joyería. Llévala sola para una declaración minimalista, combínala con cadenas delgadas para un look contemporáneo apilado, o emparejala con pendientes a juego o un colgante para una elegancia nocturna. Es un regalo ideal para aniversarios, cumpleaños, graduaciones, o como una sorpresa reflexiva \"solo porque\".</p>\n<h2>Cuidado y Mantenimiento</h2>\n<ul>\n<li>Evita la exposición a productos químicos agresivos, perfumes y lociones para preservar el metal y el brillo de los diamantes.</li>\n<li>Limpia suavemente con un cepillo suave y agua jabonosa suave; enjuaga bien y seca con un paño suave.</li>\n<li>Almacena por separado en una bolsa suave o caja de joyería para prevenir rayones y enredos.</li>\n<li>Inspecciona periódicamente los engastes y cierres; se recomienda limpieza e inspección profesional para un uso a largo plazo.</li>\n</ul>\n<h2>Personalización y Pedido</h2>\n<p>Disponible como parte de nuestra colección de Joyería Personalizada&mdash;por favor contáctanos para longitudes personalizadas, acabados de metal o solicitudes especiales. Para tamaños personalizados o opciones a medida, nuestro equipo trabajará contigo para crear la pieza perfecta.</p>\n<p><strong>¿Listo para hacerlo tuyo?</strong> Agrega un brillo atemporal a cada momento&mdash;contáctanos para solicitudes personalizadas o para confirmar disponibilidad y tiempos de entrega.</p>\n</div>', 'Pulsera de diamantes de estilo pinzado', 'Pulsera de diamantes de estilo pinzado en oro blanco de 14K. Compra en nuestra colección de joyería fina.', 'ai_translated', '2026-08-10 14:28:57', '2026-07-27 23:12:09', '2026-08-10 14:29:07');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (12, 9, 2, 'Pulsera Corazón de Diamante Con Tus Iniciales Grabadas', 'Producto simple con la función de personalización predeterminada activada a nivel de variante.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Pulsera de Corazón de Diamante con tus Iniciales Grabadas</h2>\n<p>Eleva la elegancia cotidiana con esta pulsera de diamantes de diseño clásico en forma de corazón, elaborada con maestría en oro blanco de 14K. Una delicada fila de diamantes pavé forma un deslumbrante centro en forma de corazón que se personaliza con tus iniciales para un recuerdo moderno y significativo. Peso total de diamantes: 1/2 quilate. La personalización está incluida sin costo adicional.</p>\n<h3>Por qué te encantará</h3>\n<ul>\n<li><strong>Diseño atemporal:</strong> Un motivo de corazón refinado que transita sin esfuerzo del día a la noche.</li>\n<li><strong>Personal y significativo:</strong> Tus iniciales grabadas directamente en el corazón para un toque sutil y sentimental.</li>\n<li><strong>Materiales de calidad:</strong> Oro blanco sólido de 14K combinado con diamantes libres de conflictos para una belleza duradera y un abastecimiento ético.</li>\n<li><strong>Listo para regalar:</strong> Viene en una lujosa caja de joyería — perfecto para aniversarios, cumpleaños o “simplemente porque sí.”</li>\n</ul>\n<h3>Detalles del producto</h3>\n<ul>\n<li>Metal: Oro blanco de 14K</li>\n<li>Peso total de diamantes: 0.50 quilate</li>\n<li>Diseño: Motivo central en forma de corazón con diamantes pavé</li>\n<li>Acabado: Pulido brillante</li>\n<li>Embalaje: Caja de regalo y paño de pulido de cortesía</li>\n</ul>\n<h3>Personalización (incluida)</h3>\n<p>La función de personalización predeterminada está activada a nivel de variante — la personalización se aplica por defecto cuando eliges una variante personalizada. La personalización está incluida sin costo adicional.</p>\n<ol>\n<li>Ingresa las iniciales que deseas grabar en las opciones del producto o en el campo de iniciales asociado con tu variante seleccionada.</li>\n<li>Recomendamos hasta 3 caracteres (iniciales estándar). Solo letras (A–Z). Si necesitas caracteres especiales o una inscripción más larga, comunícate con el servicio al cliente antes de realizar el pedido.</li>\n<li>La grabación se ejecuta en una fuente elegante y legible optimizada para el motivo del corazón. Las iniciales aparecerán en mayúsculas a menos que se especifique lo contrario.</li>\n</ol>\n<p><strong>Importante:</strong> Por favor, verifica la ortografía y el orden de los caracteres antes de completar tu compra — los artículos personalizados pueden ser venta final a menos que haya un defecto de fabricación.</p>\n<h3>Ajuste y tallas</h3>\n<p>La pulsera está disponible en múltiples longitudes — selecciona tu tamaño preferido de las opciones de variante. Para un mejor ajuste, mide alrededor de la muñeca donde usas pulseras y permite 1/2\"–1\" para un movimiento cómodo. Si estás entre tamaños o necesitas ayuda, nuestro equipo de atención al cliente puede ayudarte a elegir la longitud correcta.</p>\n<h3>Producción y envío</h3>\n<ul>\n<li>Hecho por encargo: Permite de 5 a 7 días hábiles para la personalización y la inspección final, más el tiempo de envío.</li>\n<li>Servicios exprés pueden estar disponibles en el proceso de pago — elige la opción de envío que se ajuste a tu cronograma.</li>\n</ul>\n<h3>Cuidado y mantenimiento</h3>\n<ul>\n<li>Quítate antes de ducharte, nadar o usar productos químicos del hogar.</li>\n<li>Limpia suavemente con un paño suave y sin pelusa; se recomienda limpieza e inspección profesional anualmente.</li>\n<li>Guarda en la caja proporcionada para evitar rayones y enredos.</li>\n</ul>\n<h3>¿Necesitas ayuda?</h3>\n<p>Si tienes preguntas sobre personalización, tallas o plazos de entrega, contacta a nuestro equipo de servicio al cliente — estamos felices de ayudar con solicitudes personalizadas o arreglos especiales de regalo.</p>\n<p><strong>Agrega un toque atemporal y personalizado a tu colección de joyas — ordena tu Pulsera de Corazón de Diamante con tus iniciales grabadas hoy.</strong></p>\n</div>', 'Pulsera de corazón de diamante con tus iniciales grabadas', 'Pulsera de corazón de diamante con iniciales. Oro blanco de 14 quilates. Compra joyería fina.', 'ai_translated', '2026-08-10 14:29:07', '2026-07-27 23:12:14', '2026-08-10 14:29:21');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (13, 17, 2, 'Sudadera para hombres', 'Sudadera de peso pesado premium con nuestro logo. Tallas S-XXL. (XXL +$5) (Producto de ejemplo utilizando diseño con imágenes en el lado derecho)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">Nuestra sudadera para hombres de peso pesado premium combina la comodidad clásica con un estilo limpio y diario. Diseñada para capas en climas frescos y uso diario, cuenta con nuestro logo distintivo y está disponible en tres colores versátiles: Negro, Burdeos y Blanco.\n<section>\n<h2>Características Clave</h2>\n<ul>\n<li>Construcción de peso pesado premium para calidez y comodidad duradera</li>\n<li>Logo distintivo para un aspecto atemporal y discreto</li>\n<li>Disponible en Negro, Burdeos y Blanco</li>\n<li>Tallas: S, M, L, XL, XXL <strong>(XXL +$5)</strong></li>\n<li>Lavable a máquina para un cuidado fácil</li>\n</ul>\n</section>\n<section>\n<h2>Ajuste y Tallas&nbsp;</h2>\n<p>Diseñada para un ajuste cómodo y diario que se superpone fácilmente sobre camisetas y debajo de chaquetas. Elige tu talla habitual. Si prefieres una sensación más holgada, considera aumentar la talla.</p>\n<p><strong>Tallas disponibles:</strong> Pequeña &bull; Mediana &bull; Grande &bull; XL &bull; XXL (<strong>agregar $5.00</strong>)</p>\n</section>\n<section>\n<h2>Instrucciones de Cuidado</h2>\n<p>Lavable a máquina para un mantenimiento simple. Para obtener los mejores resultados, lavar con colores similares y secar a baja temperatura o al aire para mantener el acabado y ajuste de la sudadera.</p>\n</section>\n<section>\n<h2>Por Qué Te Encantará</h2>\n<ul>\n<li>Calidez confiable de peso pesado sin volumen &mdash; perfecta para días más frescos</li>\n<li>Colores versátiles que combinan fácilmente con jeans, joggers o atuendos en capas</li>\n<li>Un regalo ideal: práctico, elegante y listo para usar</li>\n</ul>\n</section>\n<p><strong>Nota:</strong> Selecciona XXL para agregar $5 a tu pedido. Agrega este básico de armario a tu carrito hoy &mdash; una sudadera premium diseñada para la comodidad, durabilidad y estilo diario.</p>\n</div>\n<p>&nbsp;</p>', 'Sudadera para hombres', 'Sudadera premium para hombres. Múltiples colores y tamaños.', 'ai_translated', '2026-08-10 14:29:21', '2026-07-27 23:12:22', '2026-08-10 14:29:29');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (14, 20, 2, 'Bolígrafos de Oficina Premium Paquete de 2', 'Bolígrafos de oficina en caja de regalo premium — set de 2 con opción de grabado. (Producto de muestra con función de personalización habilitada.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bolígrafos de Oficina Premium &mdash; Paquete de 2 (En Caja de Regalo, Grabado Opcional)</h2>\n<p>Eleva la escritura diaria con este conjunto de dos bolígrafos de bola premium, presentados en una elegante caja lista para regalo. Diseñados para un rendimiento suave y confiable y una presentación refinada, estos bolígrafos son un regalo corporativo considerado o un recuerdo personal cuando se personalizan con grabado opcional.</p>\n<h3>Qué incluye</h3>\n<ul>\n<li>Conjunto de 2 bolígrafos de bola premium</li>\n<li>Elegante caja de presentación lista para regalo</li>\n<li>Grabado opcional disponible para personalizar cada bolígrafo</li>\n</ul>\n<h3>Beneficios clave</h3>\n<ul>\n<li><strong>Escritura suave y consistente:</strong> Rendimiento de bolígrafo confiable para firmas, notas y uso diario.</li>\n<li><strong>Presentación profesional:</strong> Empaquetado en una caja lista para regalo&mdash;perfecto para regalos a clientes, reconocimiento a empleados, graduaciones y ocasiones especiales.</li>\n<li><strong>Toque personalizado:</strong> Agrega un grabado opcional para crear un regalo memorable y único.</li>\n<li><strong>Uso versátil:</strong> Ideal para escritorios de oficina, salas de reuniones y espacios de trabajo en casa.</li>\n</ul>\n<h3>Detalles de personalización</h3>\n<p>Agrega un nombre significativo, fecha o mensaje corto con el servicio de grabado opcional. Selecciona la opción de personalización al realizar el pedido e ingresa el texto que deseas grabar. Los bolígrafos personalizados se fabrican por encargo&mdash;por favor revisa tu entrada cuidadosamente antes de completar la compra.</p>\n<h3>Perfecto para regalar</h3>\n<p>Ya sea que estés reconociendo a un colega, agradeciendo a un cliente o celebrando un hito, este paquete de 2 bolígrafos de oficina premium ofrece un estilo considerado y un valor práctico. El empaque listo para regalo y la opción de grabado hacen que sea simple crear un presente impresionante y memorable.</p>\n<p><strong>Ordena ahora</strong> para asegurar un conjunto profesional listo para regalo&mdash;elige el grabado para hacer tu regalo único.</p>\n</div>\n<p>&nbsp;</p>', 'Bolígrafos de oficina premium 2 unidades', 'Bolígrafos de oficina premium 2 unidades. Caja de regalo incluida.', 'ai_translated', '2026-08-10 14:29:29', '2026-07-27 23:12:29', '2026-08-10 14:29:37');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (15, 21, 2, 'Caja de Joyería de Plata', 'Muestra de producto con descuento por cantidad aplicado.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p>Mantén tus piezas más preciadas seguras, organizadas y bellamente exhibidas con la Caja de Joyería Plateada &mdash; una elegante solución de almacenamiento de joyas en tono plateado terminada con un suave interior de terciopelo. Diseñada cuidadosamente para el uso diario y ocasiones especiales, esta caja combina un estilo refinado con almacenamiento práctico para proteger anillos, aretes, pulseras y más.</p>\n<h2>Características</h2>\n<ul>\n<li><strong>Exterior elegante en tono plateado</strong> &mdash; un acabado pulido que complementa cualquier dormitorio o área de tocador.</li>\n<li><strong>Forro de terciopelo suave</strong> &mdash; protección suave para prevenir rayones y deslustre en metales y piedras preciosas delicadas.</li>\n<li><strong>Múltiples compartimentos</strong> &mdash; rollos de anillos dedicados y secciones divididas para mantener aretes, pulseras y relojes pequeños organizados y sin enredos.</li>\n<li><strong>Bandejas removibles/ajustables</strong> &mdash; crea un diseño personalizado para adaptarse a tu colección y acceder a las piezas fácilmente.</li>\n<li><strong>Tapa con cerradura</strong> &mdash; seguridad adicional y tranquilidad al almacenar objetos valiosos.</li>\n<li><strong>Diseño compacto y listo para exhibir</strong> &mdash; se coloca ordenadamente en una cómoda, tocador o estante mientras presenta tus joyas con estilo.</li>\n</ul>\n<h2>Por qué te encantará</h2>\n<ul>\n<li>Protege acabados delicados y piedras preciosas con un acolchado de terciopelo suave.</li>\n<li>Ahorra tiempo al mantener tu colección organizada y fácil de encontrar.</li>\n<li>Lo suficientemente elegante como para funcionar como un acento decorativo en tu hogar.</li>\n<li>Un regalo ideal y considerado para aniversarios, cumpleaños, damas de honor o cualquier persona que aprecie sus joyas.</li>\n</ul>\n<h2>Bueno saber</h2>\n<ul>\n<li><strong>Interior:</strong> forro de terciopelo suave</li>\n<li><strong>Exterior:</strong> acabado duradero en tono plateado</li>\n<li><strong>Almacenamiento:</strong> rollos de anillos, compartimentos divididos y secciones removibles para una organización flexible</li>\n<li><strong>Seguridad:</strong> tapa con cerradura para mayor protección</li>\n</ul>\n<h2>Cuidado y mantenimiento</h2>\n<ul>\n<li>Limpiar el exterior con un paño suave y seco para eliminar el polvo; evitar productos químicos agresivos o limpiadores abrasivos.</li>\n<li>Limpiar la forro de terciopelo con un cepillo suave o rodillo para pelusa; para una limpieza más profunda, consultar a un especialista en cuidado textil.</li>\n<li>Almacenar la caja en un lugar fresco y seco, alejado de la luz solar directa para preservar el acabado.</li>\n</ul>\n<h2>Perfecto para regalar</h2>\n<p>Presentada en un estilo versátil y elegante, la Caja de Joyería Plateada es un regalo considerado para compromisos, bodas, vacaciones o cualquier hito. Combínala con un collar favorito o un nuevo par de aretes para crear un regalo memorable.</p>\n<p><strong>Qué incluye:</strong> Caja de Joyería Plateada con forro de terciopelo y compartimentos internos.</p>\n<p>Organiza con elegancia &mdash; añade la Caja de Joyería Plateada a tu colección hoy.</p>\n</div>', 'Caja de Joyería de Plata', 'Caja de joyería de plata con forro de terciopelo. Solución de almacenamiento elegante.', 'ai_translated', '2026-08-10 14:29:37', '2026-07-27 23:12:33', '2026-08-10 14:29:49');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (16, 25, 2, 'Camiseta de mujer', 'Camiseta premium para mujeres. Disponible en 6 colores.  (Ejemplo de producto utilizando diseño con imágenes a la izquierda)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Camiseta Premium para Mujer &mdash; Suave 100% Algodón, Silueta Ajustada</h2>\n<p>La comodidad diaria se encuentra con el estilo sin esfuerzo. Esta camiseta premium para mujer está confeccionada con 100% algodón para una sensación suave y transpirable, y un corte ajustado que se mueve contigo. Disponible en seis colores versátiles y tallas S&ndash;XXL, es un básico en el armario que funciona sola o en capas.</p>\n<h3>Características Clave</h3>\n<ul>\n<li>Material: 100% algodón para una transpirabilidad y comodidad natural</li>\n<li>Ajuste: Silueta ajustada para mujer que ofrece una forma estilizada y favorecedora</li>\n<li>Opciones de color: Marrón, Gris, Verde, Marino, Naranja, Azul Real</li>\n<li>Tallas: S, M, L, XL, XXL&nbsp; (+$5.00)</li>\n<li>Construcción: Costuras duraderas para un uso prolongado</li>\n</ul>\n<h3>Ajuste y Tallas</h3>\n<p>La camiseta presenta un corte ajustado diseñado para seguir los contornos naturales del cuerpo. Si prefieres un look más holgado o relajado, considera elegir una talla más. Para el mejor ajuste, consulta tu talla habitual de camiseta o compárala con una camiseta favorita similar.</p>\n<h3>Instrucciones de Cuidado</h3>\n<ul>\n<li>Lavar a máquina en frío con colores similares</li>\n<li>Secar a baja temperatura o colgar para secar para preservar la forma y el color</li>\n<li>Planchar a temperatura tibia si es necesario; no usar blanqueador</li>\n</ul>\n<h3>Cómo Llevarla</h3>\n<p>Versátil por diseño, esta camiseta combina fácilmente con jeans, faldas, leggings o pantalones ajustados. Consejos de estilo:</p>\n<ul>\n<li>Casual: Métela en jeans de tiro alto con zapatillas para un look de fin de semana sin esfuerzo</li>\n<li>En capas: Llévala debajo de un blazer o cárdigan para un atuendo de oficina smart-casual</li>\n<li>Activa: Combínala con joggers y zapatillas para un atuendo cómodo de athleisure</li>\n</ul>\n<h3>Perfecta para Regalar</h3>\n<p>Con colores clásicos y atractivo diario, esta camiseta es un regalo práctico y elegante para cumpleaños, festividades o como una mejora reflexiva del armario. Las tallas disponibles S&ndash;XXL facilitan encontrar el ajuste correcto.</p>\n<p><strong>Añadir al carrito</strong> para llevar esta camiseta premium, imprescindible para mujer, a tu rotación diaria &mdash; comodidad y estilo en una pieza esencial.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Camiseta de mujer', 'Camiseta de mujer. Algodón premium, múltiples colores.', 'ai_translated', '2026-08-10 14:29:49', '2026-07-27 23:12:43', '2026-08-10 14:29:59');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (17, 7, 2, 'Pulsera Onda de Diamante', 'Pulsera clásica de diamantes en forma de ola — montada en oro blanco de 14K. (Ejemplo de producto mostrando la opción de diseño centrado)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Pulsera Diamond Wave &mdash; Oro Blanco de 14K</h2>\n<p>La elegancia atemporal se encuentra con el movimiento moderno en la Pulsera Diamond Wave. Elaborada con maestría en oro blanco de 14K, esta pulsera presenta diamantes de corte brillante dispuestos en un motivo de ola fluida que captura la luz con cada giro de la muñeca. Sutil pero impactante, está diseñada para elevar los looks diarios y completar conjuntos para ocasiones especiales.</p>\n<h3>Aspectos Destacados del Producto</h3>\n<ul>\n<li>Metal: Oro blanco de 14K para una radiancia y durabilidad duraderas</li>\n<li>Piedras: Diamantes de corte brillante para un máximo brillo y destello</li>\n<li>Diseño: Motivo de ola fluida que se drapea con gracia a lo largo de la muñeca</li>\n<li>Artesanía: Diamantes cuidadosamente engastados a mano y un acabado pulido para un aspecto refinado</li>\n<li>Versatilidad: Elegante suficiente para la ropa de noche, discreto suficiente para el uso diario</li>\n</ul>\n<h3>Por Qué Te Encantará</h3>\n<ul>\n<li>Silueta distintiva &mdash; el diseño de ola proporciona movimiento e interés visual sin abrumar tu estilo.</li>\n<li>Brillo reflectante &mdash; los diamantes de corte brillante están posicionados para maximizar el retorno de luz para un destello llamativo.</li>\n<li>Uso cómodo &mdash; cuidadosamente contorneada para sentarse suavemente en la muñeca para comodidad durante todo el día.</li>\n<li>Perfecta para regalar &mdash; una pieza clásica y sofisticada para aniversarios, cumpleaños o momentos importantes.</li>\n</ul>\n<h3>Cuidado y Mantenimiento</h3>\n<p>Para mantener tu Pulsera Diamond Wave luciendo lo mejor posible, limpia suavemente con un paño suave y un limpiador de joyas suave. Retira antes de nadar, hacer ejercicio o manejar productos químicos agresivos. Haz que tu pulsera sea inspeccionada profesionalmente periódicamente para asegurar que los engastes permanezcan seguros.</p>\n<h3>Personalización y Servicios</h3>\n<p>Como parte de nuestra colección de joyería personalizada, esta pulsera puede ser adaptada a tus preferencias. Selecciona diferentes longitudes o solicita opciones de metal alternativas &mdash; por favor contacta a nuestro equipo para disponibilidad y precios personalizados.</p>\n<p>Esta Pulsera Diamond Wave combina materiales clásicos con una silueta artística, convirtiéndola en una adición versátil y duradera a cualquier guardarropa de joyería.</p>\n</div>\n<p>&nbsp;</p>', 'Pulsera Onda de Diamante', 'Pulsera clásica de diamantes en forma de ola. Joyería fina para cada ocasión.', 'ai_translated', '2026-08-10 14:29:59', '2026-07-27 23:12:53', '2026-08-10 14:30:09');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (18, 4, 2, 'Anillo de 14K con Perla Cultivada y Diamantes', 'Demuestra un método alternativo de selección de opciones con una lista personalizada en lugar de variantes. (Cuando no se requieren niveles de inventario basados en opciones individuales, como un artículo hecho a medida.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p><strong>Eleva cada momento con elegancia atemporal.</strong> Este anillo de oro de 14K centra una perla cultivada luminosa enmarcada por seis diamantes brillantes para un aspecto refinado y femenino. Hecho a mano para captar la luz desde todos los ángulos, es una elección ideal para la sofisticación diaria o un regalo memorable para una ocasión especial.</p>\n<h2>Características clave</h2>\n<ul>\n<li><strong>Metal:</strong> Oro sólido de 14K para una belleza y durabilidad duraderas.</li>\n<li><strong>Piedra central:</strong> Perla cultivada lustrosa, elegida por su superficie suave y rica nácar.</li>\n<li><strong>Piedras de acento:</strong> Seis diamantes de corte redondo que añaden un brillo delicado alrededor de la perla.</li>\n<li><strong>Acabado y engaste:</strong> Banda pulida con engastes seguros, elaborados con destreza para proteger la perla y los diamantes.</li>\n<li><strong>Diseño:</strong> Silueta clásica y versátil que combina bien con looks tanto casuales como formales.</li>\n<li><strong>Personalización:</strong> Opciones de personalización y envoltura de regalo disponibles para hacer de esta pieza algo único.</li>\n</ul>\n<h2>Por qué te encantará</h2>\n<ul>\n<li>Combina el suave resplandor de una perla con el brillo nítido de los diamantes para una estética equilibrada y elegante.</li>\n<li>Diseño atemporal que transita sin esfuerzo del día a la noche.</li>\n<li>Hace un regalo significativo—ideal para aniversarios, cumpleaños, damas de honor o momentos importantes.</li>\n</ul>\n<h2>Detalles del producto y cuidado</h2>\n<ul>\n<li><strong>Tamaños y personalización:</strong> Disponible en tamaños de anillo estándar; se ofrecen grabado y tamaños personalizados—por favor, permite tiempo adicional de producción para pedidos personalizados.</li>\n<li><strong>Instrucciones de cuidado:</strong> Evita la exposición a productos químicos agresivos, perfumes y agua clorada. Limpia suavemente con un paño suave y haz que un joyero inspeccione los engastes periódicamente.</li>\n<li><strong>Almacenamiento:</strong> Guarda por separado en una bolsa suave o caja de joyería para evitar rayones y preservar el brillo de la perla.</li>\n</ul>\n<h2>Listo para regalar</h2>\n<p>Cada anillo puede ser empaquetado con envoltura de regalo premium y un mensaje personalizado a pedido—perfecto para regalar directamente desde nuestro estudio a tu destinatario.</p>\n<p>Elige una pieza que combine el encanto clásico con la artesanía moderna. Añade personalización o envoltura de regalo al finalizar la compra para crear un recuerdo verdaderamente especial.</p>\n</div>', 'Anillo de 14K con perla cultivada y diamantes', 'Anillo de oro de 14 quilates con perla cultivada y acentos de diamante.', 'ai_translated', '2026-08-10 14:30:09', '2026-07-27 23:12:57', '2026-08-10 14:30:21');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (19, 6, 2, 'Anillo de Rubí y Diamante con Banda de 14K - Tamaño 6', 'Ejemplo donde el artículo solo tenía un tamaño disponible, pero está configurado como una variante para que aparezca en la búsqueda de filtros avanzados.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Anillo de Rubí y Diamante con Banda de 14K</h2>\n<p>Eleva cualquier ocasión con este refinado anillo de rubí y diamante, donde el diseño atemporal se encuentra con la artesanía cuidadosa. Un vibrante rubí ocupa el centro del escenario en un engaste de cuatro garras de oro de 14K, flanqueado por piedras laterales de diamante engastadas en canal para mayor brillantez y un perfil aerodinámico. El resultado es una pieza clásica y versátil que se lee igualmente bien como un elegante anillo diario o un regalo memorable para un momento importante.</p>\n<ul>\n<li><strong>Piedra central:</strong> Rubí vívido engastado en un tradicional engaste de cuatro garras para maximizar la luz y el color.</li>\n<li><strong>Piedras de acento:</strong> Diamantes engastados en canal a lo largo de los hombros que ofrecen un brillo duradero con un perfil bajo para un uso cómodo.</li>\n<li><strong>Metal:</strong> Banda de oro de 14K con un acabado pulido para una elegancia duradera.</li>\n<li><strong>Diseño:</strong> Silueta clásica que equilibra un color audaz con detalles refinados &mdash; ideal como una declaración independiente o apilada con otros anillos.</li>\n</ul>\n<h3>Por qué te encantará</h3>\n<p>Este anillo combina la rica calidez del rubí con el fuego nítido de los diamantes para un look que es tanto lujoso como usable. El engaste central de cuatro garras muestra el rubí mientras lo protege del desgaste diario, y los diamantes engastados en canal proporcionan un brillo seguro y de bajo perfil que no se enganchará. Proporciones cuidadosas hacen de esta una pieza cómoda que alcanzarás a menudo.</p>\n<h3>Detalles del producto y tallas</h3>\n<ul>\n<li>Metal: Oro de 14K</li>\n<li>Estilo de engaste: Centro de cuatro garras con hombros de diamante engastados en canal</li>\n<li>Acabado: Alto pulido</li>\n<li>Tamaño: Este artículo se ofrece en un solo tamaño. Se lista como una variante en el catálogo para aparecer en búsquedas avanzadas &mdash; por favor, toma nota del único tamaño disponible al realizar el pedido.</li>\n</ul>\n<p><strong>¿Necesitas un tamaño diferente?</strong> Contáctanos para discutir opciones de tamaño personalizado o reajuste. Estamos felices de acomodar tamaños adicionales o hacer este anillo según tus especificaciones.</p>\n<h3>Cuidado y mantenimiento</h3>\n<ul>\n<li>Para preservar las piedras y el metal, quítate el anillo para actividades que puedan causar impacto o exposición a productos químicos agresivos.</li>\n<li>Límpialo suavemente con agua tibia, jabón suave y un cepillo suave; sécalo con un paño sin pelusa.</li>\n<li>Haz que las garras y los engastes sean revisados profesionalmente periódicamente para asegurar la seguridad a largo plazo.</li>\n</ul>\n<p>Cada gema es única, por lo que el color y el carácter individuales pueden variar ligeramente de las fotos. Para preguntas sobre esta pieza, solicitudes personalizadas o tiempos de entrega, por favor contacta a nuestro equipo de atención al cliente &mdash; estamos aquí para ayudarte a hacerlo perfecto.</p>\n<p><strong>Agrega este anillo de rubí y diamante a tu colección para una declaración atemporal y elegante que será apreciada durante años.</strong></p>\n</div>\n<p>&nbsp;</p>', 'Anillo de Rubí y Diamante con Banda de 14K', 'Anillo elegante de rubí y diamante en oro de 14K. Joyería fina de primera calidad.', 'ai_translated', '2026-08-10 14:30:21', '2026-07-27 23:13:00', '2026-08-10 14:30:33');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (20, 10, 2, 'Pulsera de diamantes de 2 quilates en oro blanco de 14k o 24k', 'Diseño de muestra de artículo con video incrustado a continuación. Ideal para mostrar las características del artículo, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Pulsera de Diamantes de 2 Quilates en Oro Blanco de 14K o 24K</h2>\n<p>Eleva cualquier look con esta pulsera de diamantes de 2.00 quilates de peso total, exquisitamente elaborada. Los diamantes de corte brillante están engastados en un diseño refinado y de perfil bajo que captura la luz con cada movimiento&mdash;perfecto para la elegancia diaria o ocasiones especiales. Elige entre un engaste clásico de oro blanco de 14K o una opción de oro de 24K de mayor pureza para adaptarse a tu estilo.</p>\n<h3>Características Clave</h3>\n<ul>\n<li><strong>Peso Total de Diamantes:</strong> 2.00 quilates (TW)</li>\n<li><strong>Corte:</strong> Diamantes de corte brillante para máxima brillantez y destello</li>\n<li><strong>Opciones de Metal:</strong> Oro blanco de 14K o oro de 24K &mdash; consulta la nota a continuación sobre acabados</li>\n<li><strong>Engaste:</strong> Engastes de garra/línea seguros y precisos diseñados para mostrar cada piedra</li>\n<li><strong>Acabado:</strong> Altamente pulido para una superficie lujosa y reflectante</li>\n</ul>\n<h3>Artesanía y Calidad</h3>\n<p>Cada pulsera es hecha a mano por joyeros experimentados utilizando técnicas probadas en el tiempo para asegurar longevidad y equilibrio. Los diamantes son seleccionados a mano por su tamaño y rendimiento óptico consistentes, luego se engastan con meticulosa atención a la alineación y simetría. El resultado es una fila continua de piedras brillantes que se asienta cómodamente en la muñeca.</p>\n<h3>Opciones de Metal &mdash; Nota Importante</h3>\n<p>Puedes seleccionar oro blanco de 14K para un acabado duradero y blanco brillante comúnmente utilizado en joyería fina. Se ofrece una opción de 24K para aquellos que buscan mayor pureza de oro; ten en cuenta que el 24K es naturalmente de color amarillo. Si prefieres un acabado blanco en una pieza de mayor quilate, te recomendamos contactarnos para discutir el chapado en rodio o opciones de aleación alternativas para que podamos satisfacer tus preferencias exactas.</p>\n<h3>Tamaño y Ajuste</h3>\n<ul>\n<li>Disponible en longitudes estándar de pulsera; se pueden hacer longitudes personalizadas por encargo para un ajuste perfecto.</li>\n<li>Diseñada para un uso diario cómodo mientras mantiene un perfil seguro y favorecedor en la muñeca.</li>\n<li>Por favor proporciona la medida de la muñeca al finalizar la compra para un tamaño personalizado.</li>\n</ul>\n<h3>Cuidado y Mantenimiento</h3>\n<ul>\n<li>Almacena por separado para evitar rayones y guarda la pieza en su caja protectora cuando no se use.</li>\n<li>Limpia suavemente con un cepillo suave y agua tibia con jabón; seca completamente con un paño suave.</li>\n<li>Para mantener un acabado de oro blanco, el chapado en rodio puede ser renovado periódicamente.</li>\n</ul>\n<h3>Qué Incluye</h3>\n<ul>\n<li>La pulsera de diamantes de 2.00 ct en el metal que elijas</li>\n<li>Caja de presentación premium</li>\n<li>Instrucciones de cuidado e información sobre mantenimiento</li>\n<li>Asistencia con certificación o tasación a petición</li>\n</ul>\n<h3>Pedido y Personalización</h3>\n<p>Elige tu metal y longitud deseada, o contacta a nuestro equipo para solicitudes personalizadas&mdash;incluyendo calidad específica de diamantes, estilos de broches o grabados. Nuestros joyeros están disponibles para guiarte en la selección de la configuración perfecta.</p>\n<p><strong>¿Listo para hacerlo tuyo?</strong> Selecciona tu metal y tamaño, luego añade al carrito para asegurar esta atemporal pulsera de diamantes de 2 quilates. Para opciones personalizadas o preguntas, contacta a nuestro equipo de atención al cliente&mdash;te ayudaremos a crear una pieza que atesorarás durante años.</p>\n</div>', 'Brazalete de diamantes de 2 quilates en oro blanco de 14k o 24k', 'Pulsera de diamantes de 2 quilates en oro blanco de 14K o 24K. Joyería fina.', 'ai_translated', '2026-08-10 14:30:33', '2026-07-27 23:13:04', '2026-08-10 14:30:48');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (21, 12, 2, 'Pulsera de Rubíes y Diamantes', 'Brazalete elegante de rubíes y diamantes en opciones de oro de 14k, plata y oro rosa de 18k. El tono plateado tiene un descuento por cantidad aplicado. Visualización total en tiempo real habilitada debajo del botón de agregar al carrito.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Pulsera de Rubíes y Diamantes &mdash; Elegancia Atemporal</h2>\n<p>Un equilibrio elegante de color y brillo, esta Pulsera de Rubíes y Diamantes combina piedras de rubí vívidas con acentos de diamantes de corte brillante para un look que es tanto lujoso como refinado. Diseñada cuidadosamente para realzar el uso diario o finalizar un look de noche, está disponible en tres tonos de metal para adaptarse a tu estilo: tono dorado, tono plateado y oro rosa.</p>\n<h3>Características Clave</h3>\n<ul>\n<li><strong>Piedras:</strong> Piedras de rubí vívidas acentuadas por acentos de diamantes de corte brillante para un contraste radiante.</li>\n<li><strong>Acabados:</strong> Elige entre tono dorado, tono plateado o cálido tono oro rosa para combinar con tu colección de joyas.</li>\n<li><strong>Artesanía:</strong> Piedras cuidadosamente engastadas y un cierre seguro para un uso cómodo y diario.</li>\n<li><strong>Diseño:</strong> Silueta elegante y versátil diseñada para superponerse hermosamente con otras pulseras o destacarse como una pieza de declaración.</li>\n<li><strong>Ahorros por Cantidad:</strong> La opción de tono plateado incluye un descuento automático por cantidad&mdash;selecciona múltiples piezas en tu carrito para ver los ahorros aplicados.</li>\n</ul>\n<h3>Por Qué Te Encantará</h3>\n<p>El rojo vívido de los rubíes combinado con los acentos de diamantes brillantes crea una combinación clásica y llamativa que complementa tanto los tonos de piel cálidos como los fríos. Ligera y refinada, esta pulsera añade un toque instantáneo de elegancia a un look de negocios, atuendo de cóctel o conjunto de ocasión especial.</p>\n<h3>Tamaño y Ajuste</h3>\n<p>Disponible en longitudes estándar de pulsera. Por favor selecciona tu tamaño preferido de las opciones en la página del producto. Si no estás seguro de qué tamaño elegir, mide tu muñeca donde normalmente usarías una pulsera y añade 0.5\"&ndash;1\" para un ajuste cómodo.</p>\n<h3>Cuidado y Mantenimiento</h3>\n<ul>\n<li>Evita la exposición a productos químicos agresivos, perfumes y agua clorada para preservar el acabado.</li>\n<li>Limpiar con un paño suave y seco después de usar para eliminar aceites y restaurar el brillo.</li>\n<li>Almacenar por separado en una bolsa suave o caja de joyería para prevenir rayones.</li>\n</ul>\n<h3>Perfecto Para</h3>\n<p>Regalos de cumpleaños o aniversarios, joyería para novias o damas de honor, celebraciones de hitos, o simplemente para darte un capricho con una pieza refinada para el día a día. Cada pulsera es un regalo elegante y considerado que combina maravillosamente con pendientes a juego o un colgante.</p>\n<h3>Información Adicional</h3>\n<ul>\n<li>Acabados disponibles: tono dorado, tono plateado, oro rosa.</li>\n<li>El tono plateado califica para un descuento automático por cantidad&mdash;agrega múltiples a tu carrito para recibir el precio reducido.</li>\n<li>Para solicitudes personalizadas o pedidos al por mayor, por favor contacta a nuestro equipo de servicio al cliente.</li>\n</ul>\n<p>Agrega un toque de lujo atemporal a tu colección de joyas. Elige tu tono de metal y tamaño, luego haz clic en &ldquo;Agregar al Carrito&rdquo; para ordenar.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Pulsera de Rubíes y Diamantes', 'Pulsera de rubíes y diamantes. Joyería fina elegante.', 'ai_translated', '2026-08-10 14:30:48', '2026-07-27 23:13:15', '2026-08-10 14:31:00');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (22, 11, 2, 'Pulsera de diamantes de 5 quilates certificada por GIA en oro de 18k', 'Muestra con visualización de imagen alterna (lado derecho) más una opción de personalización para una venta adicional.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<div class=\"intro\">\n<h2>Pulsera de Diamantes Certificada por GIA de 5 Quilates en Oro de 18K</h2>\n<p>Una pulsera de declaración refinada creada para coleccionistas y conocedores. Esta lujosa pulsera de oro de 18K presenta un total de 5.00 quilates de diamantes de corte brillante certificados por GIA &mdash; meticulosamente emparejados y engastados para maximizar el brillo, la simetría y la usabilidad.</p>\n</div>\n<div class=\"two-column\">\n<div class=\"left-column\">\n<h3>Por qué te encantará</h3>\n<ul>\n<li>Diseño atemporal que transita sin esfuerzo del día a la noche.</li>\n<li>Diamantes de corte brillante engastados a mano que suman un total de 5.00 quilates.</li>\n<li>Fabricada en oro macizo de 18K para una durabilidad duradera y una pátina lujosa.</li>\n<li>La certificación GIA proporciona verificación independiente de la calidad de los diamantes.</li>\n<li>Opciones personalizables que te permiten adaptar el metal, el acabado y los detalles para una herencia verdaderamente personal.</li>\n</ul>\n<h3>Detalles del producto</h3>\n<ul>\n<li>Metal: Oro macizo de 18K (disponible en amarillo, blanco o rosa bajo personalización)</li>\n<li>Peso del diamante: 5.00 peso total en quilates (tcw)</li>\n<li>Corte del diamante: Corte brillante (certificado por GIA)</li>\n<li>Cierre: Cierre de caja seguro o cierre de langosta con pestillo de seguridad (seleccionable)</li>\n<li>Longitudes estándar: 6.5\", 7\", 7.5\" &mdash; longitudes personalizadas disponibles a pedido</li>\n<li>Acabado: Alto pulido (acabados mate o satinado disponibles como mejora)</li>\n</ul>\n<h3>Certificación y procedencia</h3>\n<p>Todos los diamantes vienen acompañados de certificación GIA. Se pueden proporcionar certificados o una tasación completa con la compra o a pedido para asegurar la procedencia y el valor de reemplazo al por menor para fines de seguro.</p>\n<h3>Opciones de personalización y venta adicional</h3>\n<p>Haz que esta pulsera sea única para ti. Elige entre las opciones a continuación o haz clic en <a class=\"cta\" href=\"#customize\">Personaliza esta pieza</a> para comenzar una consulta privada.</p>\n<ul>\n<li><strong>Selección de metal:</strong> Mejora entre oro amarillo, blanco o rosa de 18K.</li>\n<li><strong>Mejoras de diamantes:</strong> Opción de mejorar a niveles superiores de color/claridad o incluir piedras centrales más grandes para un aspecto más audaz.</li>\n<li><strong>Acabado y cierre:</strong> Acabado satinado, acentos de micro-pavé, o un cierre de seguridad mejorado para mayor seguridad.</li>\n<li><strong>Personalización:</strong> Grabado en el cierre o longitud personalizada para un ajuste perfecto.</li>\n<li><strong>Presentación y protección:</strong> Agrega una caja de presentación premium, una tasación detallada de GIA, y un servicio de garantía extendida o valoración de seguro opcional.</li>\n</ul>\n<h3>Cuidado y servicio</h3>\n<p>Para mantener el brillo, limpia suavemente con un cepillo suave y un limpiador de joyas suave. Evita productos químicos agresivos y quítatela antes de realizar actividades extenuantes. Ofrecemos limpieza e inspección de por vida de forma gratuita cuando se compra con un plan de cuidado extendido.</p>\n<h3>Envíos y devoluciones</h3>\n<p>Envío seguro y asegurado disponible a nivel mundial. Debido a la naturaleza personalizada y al valor de esta pieza, las devoluciones e intercambios se manejan caso por caso &mdash; por favor revisa nuestra política de devoluciones completa o contacta a nuestro conserje para asistencia.</p>\n<p class=\"final-cta\">Para personalizar esta pulsera o solicitar documentación y precios de tasación de GIA, haz clic en <a class=\"cta\" href=\"#customize\">Personaliza / Solicita Tasación</a> o contacta a nuestro equipo de servicios al cliente para una consulta privada.</p>\n</div>\n<div class=\"right-column\" aria-label=\"Visualización de imagen alternativa (lado derecho)\">\n<div class=\"image-gallery\"><!-- Replace src values with actual product image URLs --> <img src=\"/images/18k-5ct-bracelet-main.jpg\" alt=\"Pulsera de oro de 18K con diamantes certificados por GIA de 5 quilates &mdash; vista principal\"> <img src=\"/images/18k-5ct-bracelet-side.jpg\" alt=\"Perfil lateral mostrando el engaste de diamantes y el cierre\"> <img src=\"/images/18k-5ct-bracelet-wrist.jpg\" alt=\"Pulsera en la muñeca &mdash; vista de escala y uso\"> <img src=\"/images/18k-5ct-bracelet-box.jpg\" alt=\"Caja de presentación premium y certificado de GIA\"></div>\n<p class=\"image-note\">Visualización de imagen alternativa (lado derecho) &mdash; haz clic en las imágenes para ampliar.</p>\n</div>\n</div>\n<div class=\"notes\">\n<h4>Importante</h4>\n<p>Debido a que cada pulsera está hecha a mano y puede ser personalizada, la disposición final de los diamantes y las especificaciones exactas pueden variar ligeramente de las imágenes de la galería. Los números de informe GIA exactos y la documentación de tasación se proporcionarán con cada venta.</p>\n</div>\n</div>\n</div>', 'Pulsera de diamantes certificada por GIA de 5 quilates en oro de 18k', 'Pulsera de diamantes de 5 quilates certificada por GIA en oro de 18 quilates. Joyería fina.', 'ai_translated', '2026-08-10 14:31:00', '2026-07-27 23:13:19', '2026-08-10 14:31:20');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (23, 13, 2, 'Pulsera de Zafiro, Rubí y Esmeralda', 'Demuestra la personalización y adaptación del producto.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Pulsera de Zafiros, Rubíes y Esmeraldas</h2>\n<p>Deja una impresión duradera con esta impresionante pulsera de múltiples piedras, donde vívidos zafiros azules, profundos rubíes rojos y exuberantes esmeraldas verdes están artísticamente engastados en oro fino. Los ricos colores contrastantes crean una pieza de declaración atemporal pero audaz que eleva tanto los looks de día como de noche.</p>\n<h3>Características Clave</h3>\n<ul>\n<li><strong>Piedras preciosas:</strong> Zafiros vívidos, rubíes intensos y esmeraldas vibrantes seleccionadas por su color y brillo.</li>\n<li><strong>Engaste en oro fino:</strong> Elaborado con maestría en oro fino para engastes seguros y un acabado cálido y lujoso.</li>\n<li><strong>Acabado artesanal:</strong> Engaste de piedras con precisión y detalles pulidos para una pieza refinada y duradera.</li>\n<li><strong>Diseño versátil:</strong> Lo suficientemente elegante para ocasiones formales, pero lo suficientemente audaz para elevar atuendos diarios.</li>\n</ul>\n<h3>Por Qué Te Encantará</h3>\n<p>Esta pulsera combina la belleza clásica de las piedras preciosas con un diseño contemporáneo. El trío contrastante de zafiros, rubíes y esmeraldas crea profundidad visual y movimiento—perfecto para cualquiera que aprecie el color, la artesanía y una pieza que puede ser heredada como un legado. Es una elección ideal para aniversarios, celebraciones de hitos o como una adición destacada a tu colección personal de joyas.</p>\n<h3>Sugerencias de Estilo</h3>\n<ul>\n<li>Llévala sola como un punto focal con un vestido de noche simple o un blazer a medida.</li>\n<li>Superpón con pulseras de oro delgadas para un look apilado moderno.</li>\n<li>Combínala con aretes de piedras preciosas a juego o un colgante delicado para un conjunto coordinado.</li>\n</ul>\n<h3>Cuidado y Mantenimiento</h3>\n<ul>\n<li>Quítatela antes de ducharte, nadar o usar productos químicos del hogar.</li>\n<li>Límpiala suavemente con un paño suave y sin pelusa; evita abrasivos duros y limpiadores ultrasónicos para esmeraldas a menos que lo indique un joyero.</li>\n<li>Guárdala por separado en una bolsa suave o en la caja original para evitar rasguños.</li>\n</ul>\n</div>\n<p>&nbsp;</p>', 'Pulsera de Zafiro, Rubí y Esmeralda', 'Pulsera de zafiro, rubí y esmeralda. Joyería fina.', 'ai_translated', '2026-08-10 14:31:20', '2026-07-27 23:13:22', '2026-08-10 14:31:34');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (24, 18, 2, 'Reloj de bolsillo vintage', 'Artículo de muestra con opción de envoltura de regalo habilitada.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Reloj de bolsillo vintage &mdash; Elegancia clásica con grabado intrincado</h2>\n<p>Haz que cada momento sea memorable con este bellamente elaborado reloj de bolsillo vintage. Terminado en un estilo antiguo y detallado con una caja de metal finamente grabada, esta pieza de tiempo combina la artesanía tradicional con un diseño atemporal. El dial con números romanos claros y los sutiles acentos vintage lo convierten en una opción destacada para coleccionistas, ocasiones especiales o cualquier persona que aprecie un estilo de calidad heredada.</p>\n<h3>Características clave</h3>\n<ul>\n<li><strong>Caja grabada intrincadamente:</strong> El detallado grabado ornamental le da a cada reloj un aspecto clásico y distintivo.</li>\n<li><strong>Dial con números romanos:</strong> Números romanos elegantes y fáciles de leer para una estética tradicional.</li>\n<li><strong>Construcción duradera:</strong> Caja de metal sólida con un acabado antiguo diseñada para resistir el uso diario mientras mantiene su encanto vintage.</li>\n<li><strong>Precisión en el tiempo:</strong> Construido con un movimiento preciso para mantener la hora exacta para el uso diario o la exhibición.</li>\n<li><strong>Estilo versátil:</strong> Lo suficientemente refinado para eventos formales, pero lo suficientemente robusto para usar como una pieza de declaración diaria.</li>\n</ul>\n<h3>Por qué te encantará</h3>\n<p>Este reloj de bolsillo vintage ofrece la apariencia y sensación de una auténtica herencia sin sacrificar la practicidad. Su caja grabada y su cara clásica proporcionan un inconfundible sentido de historia y carácter, convirtiéndolo en un accesorio destacado para trajes, chaquetas o para exhibir en una colección. Es un regalo ideal para entusiastas del vintage, padrinos de boda o cualquier persona que favorezca los accesorios atemporales.</p>\n<h3>Perfecto para</h3>\n<ul>\n<li>Coleccionistas que buscan una adición clásica a su colección</li>\n<li>Regalos para aniversarios, cumpleaños, bodas y graduaciones</li>\n<li>Eventos formales, recreaciones o reuniones temáticas</li>\n<li>Usadores diarios que aprecian el estilo vintage</li>\n</ul>\n<h3>Cuidado y mantenimiento</h3>\n<ul>\n<li>Limpiar con un paño suave y seco para eliminar huellas dactilares y polvo.</li>\n<li>Mantener alejado de campos magnéticos fuertes y exposición prolongada a la humedad.</li>\n<li>Almacenar en un lugar seco cuando no esté en uso para preservar el acabado y el movimiento.</li>\n<li>Hacer que un profesional revise el reloj si notas irregularidades en la precisión del tiempo.</li>\n</ul>\n<h3>Qué incluye</h3>\n<ul>\n<li>Reloj de bolsillo vintage (caja grabada con dial de números romanos)</li>\n<li>Tarjeta de instrucciones con consejos básicos de cuidado</li>\n</ul>\n<p>Este reloj de bolsillo inspirado en lo vintage combina forma y función para crear una impresión duradera &mdash; ya sea añadido a una colección o dado como un regalo significativo. Agrégalo a tu carrito para poseer una pieza que se ve y se siente como un clásico atesorado.</p>\n</div>\n<p>&nbsp;</p>', 'Reloj de bolsillo vintage', 'Reloj de bolsillo vintage. Caja clásica grabada.', 'ai_translated', '2026-08-10 14:31:34', '2026-07-27 23:13:26', '2026-08-10 14:31:46');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (25, 19, 2, 'Reloj de Pulsera de Moda', 'Muestra de reloj con selectores de color que no son píldoras, sino grupos de radio de variante. También utiliza un mensaje de fuera de stock que no es el predeterminado.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<h2>Reloj de Pulsera de Moda</h2>\n<p>Una pieza de tiempo elegante y moderna diseñada para el estilo diario. El Reloj de Pulsera de Moda combina una caja de acero inoxidable pulido con correas intercambiables para que puedas cambiar entre looks casuales y refinados en segundos. Disponible con correas negras y marrones &mdash; es una forma sencilla de elevar cualquier atuendo.</p>\n<h3>Por qué te encantará</h3>\n<ul>\n<li><strong>Diseño contemporáneo:</strong> Esfera limpia y perfil delgado que ofrece una estética moderna y versátil que transita del día a la noche.</li>\n<li><strong>Construcción duradera:</strong> La caja de acero inoxidable proporciona resistencia y un acabado premium que soporta el uso diario.</li>\n<li><strong>Correas intercambiables:</strong> Cambia las correas rápidamente para personalizar tu look sin herramientas (estilo de liberación rápida).</li>\n<li><strong>Opciones de color clásicas:</strong> Elige negro o marrón para combinar con tu estilo personal o guardarropa.</li>\n</ul>\n<h3>Detalles del producto</h3>\n<ul>\n<li>Material de la caja: Acero inoxidable</li>\n<li>Correa: Intercambiable (incluida)</li>\n<li>Colores disponibles: Negro, Marrón, Blanco</li>\n<li>Estilo: Moderno, unisex</li>\n</ul>\n<h3>Cómo seleccionar tu color</h3>\n<p>Las opciones de color están disponibles como grupos de radio de variante en la página del producto. Selecciona tu color preferido eligiendo el botón de radio correspondiente bajo \"Color.\" (Nota: estas se presentan como opciones de radio en lugar de muestras estilo píldora.)</p>\n<h3>Tamaño y ajuste</h3>\n<p>La correa ajustable se adapta a la mayoría de los tamaños de muñeca. Para un ajuste personalizado, quita eslabones o ajusta la hebilla según sea necesario. Si necesitas medidas precisas o ayuda para encontrar el ajuste correcto, consulta nuestra guía de tamaños o contacta al servicio de atención al cliente.</p>\n<h3>Cuidado y mantenimiento</h3>\n<ul>\n<li>Evita la exposición prolongada a la humedad y temperaturas extremas.</li>\n<li>Limpiar la caja y la correa con un paño suave y seco para eliminar suciedad y aceites.</li>\n<li>Almacenar en un lugar seco cuando no esté en uso para preservar el acabado y la longevidad.</li>\n</ul>\n<h3>Qué incluye</h3>\n<ul>\n<li>Reloj de Pulsera de Moda (caja + correa principal)</li>\n<li>Tarjeta de usuario con instrucciones básicas de cuidado</li>\n</ul>\n<p>Diseñado cuidadosamente para versatilidad y uso diario, el Reloj de Pulsera de Moda es un accesorio refinado que se adapta a tu estilo. Selecciona tu color a través del grupo de radio y crea el look que deseas&mdash;sin esfuerzo.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Reloj de Pulsera de Moda', 'Reloj de pulsera de moda. Múltiples opciones de correa.', 'ai_translated', '2026-08-10 14:31:46', '2026-07-27 23:13:29', '2026-08-10 14:31:58');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (26, 22, 2, 'Reloj de bolsillo moderno', 'Artículo fuera de stock con un formulario de contacto debajo del artículo para solicitar más información.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 14pt;\"><strong>Nos disculpamos, pero este artículo no está disponible en este momento. Por favor, envíe el formulario a continuación para que nos pongamos en contacto con usted cuando vuelva a estar en stock.</strong></span></p>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 18pt;\"><strong>[cms-form id=1]</strong></span></p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Reloj de bolsillo moderno', 'Reloj de bolsillo moderno. Diseño minimalista elegante.', 'ai_translated', '2026-08-10 14:31:58', '2026-07-27 23:13:32', '2026-08-10 14:32:03');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (27, 23, 2, 'Reloj de Pulsera Moderno', 'Ejemplo de artículo agotado.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Reloj de Pulsera Moderno &mdash; Diseño Minimalista, Uso Diario</h2>\n<p>Un reloj de pulsera moderno, bellamente equilibrado, diseñado para personas que aprecian las líneas limpias y el estilo sin esfuerzo. El perfil delgado y la esfera despejada lo convierten en un accesorio versátil tanto para looks casuales como formales. Elaborado con una correa de cuero genuino y un movimiento de precisión, esta pieza unisex ofrece comodidad duradera y un cronometraje fiable.</p>\n<h3>Características Clave</h3>\n<ul>\n<li><strong>Estética minimalista:</strong> Esfera limpia con índices sutiles para un aspecto contemporáneo y discreto.</li>\n<li><strong>Caja delgada:</strong> Diseño de bajo perfil que se asienta cómodamente debajo de mangas y chaquetas.</li>\n<li><strong>Correa de cuero genuino:</strong> Correa suave y duradera que se adapta a tu muñeca con el tiempo.</li>\n<li><strong>Movimiento fiable:</strong> Movimiento de cuarzo de precisión para un cronometraje exacto.</li>\n<li><strong>Durabilidad diaria:</strong> Cristal mineral recubierto de zafiro que resiste rayones; resistente al agua para salpicaduras diarias.</li>\n<li><strong>Tamaño unisex:</strong> Diseñado para adaptarse tanto a hombres como a mujeres con un tamaño de caja versátil y correa ajustable.</li>\n</ul>\n<h3>Especificaciones</h3>\n<ul>\n<li>Diámetro de la caja: 38 mm (aprox.)</li>\n<li>Grosor de la caja: 6&ndash;8 mm (perfil delgado)</li>\n<li>Movimiento: Cuarzo japonés</li>\n<li>Cristal: Vidrio mineral recubierto de zafiro</li>\n<li>Correa: Cuero genuino con hebilla ajustable</li>\n<li>Resistencia al agua: 3 ATM (resistente a salpicaduras; no apto para nadar)</li>\n<li>Género: Unisex</li>\n</ul>\n<h3>Qué Incluye</h3>\n<ul>\n<li>Reloj de Pulsera Moderno (correa mostrada)</li>\n<li>Caja de presentación premium</li>\n<li>Manual de instrucciones y tarjeta de garantía</li>\n</ul>\n<h3>Cuidado y Mantenimiento</h3>\n<ul>\n<li>Evita la exposición prolongada a la humedad; quítate el reloj antes de ducharte o nadar.</li>\n<li>Limpia la caja y el cristal con un paño suave y seco. Acondiciona la correa de cuero ocasionalmente con productos para el cuidado del cuero.</li>\n<li>Haz que un técnico de relojería calificado reemplace la batería para asegurar que se mantenga la resistencia al agua.</li>\n</ul>\n<h3>Garantía y Soporte</h3>\n<p>Esta pieza está cubierta por una garantía limitada de 24 meses contra defectos de fabricación. Para servicio de garantía o soporte de producto, por favor contacta a nuestro equipo de atención al cliente con tu número de pedido y tarjeta de garantía.</p>\n<h3>Disponibilidad</h3>\n<p><strong>Agotado.</strong> Lo sentimos&mdash;este artículo no está disponible actualmente. Haz clic en el botón \"Notificarme\" en la página del producto para recibir un correo electrónico tan pronto como esté de nuevo en stock, o contacta al soporte al cliente para obtener ayuda con estilos similares y opciones de preorden.</p>\n<p>Diseñado para ser fácilmente llevable y elegantemente contenido, el Reloj de Pulsera Moderno es el compañero perfecto para el día a día o un regalo considerado para cualquiera que valore el diseño minimalista y atemporal.</p>\n</div>\n</div>', 'Reloj de Pulsera Moderno', 'Reloj de pulsera moderno. Diseño minimalista para hombres y mujeres.', 'ai_translated', '2026-08-10 14:32:03', '2026-07-27 23:13:35', '2026-08-10 14:32:17');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (28, 14, 3, 'eBOOK de Nettoyage de Bijoux', 'Exemple d\'article de téléchargement numérique. Les téléchargements peuvent être distribués via un lien sécurisé vers un dossier local sécurisé, un lien de téléchargement S3 expirant ou un CDN (URL directe).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>eBOOK de nettoyage de bijoux &mdash; Soins professionnels, étapes simples</h2>\n<p>Gardez vos bijoux fins brillants et sans dommages avec cet eBook téléchargeable facile à suivre. Que vous possédiez des diamants, des perles, de l\'or, de l\'argent ou des pièces en métaux mélangés, ce guide fournit des instructions claires et pratiques ainsi que des conseils de prévention pour que vos objets précieux aient fière allure pendant des années. Livraison numérique instantanée après achat.</p>\n<h3>Pourquoi cet eBook ?</h3>\n<ul>\n<li>Méthodes de nettoyage pratiques et étape par étape que vous pouvez réaliser à la maison sans outils coûteux</li>\n<li>Conseils de soins sûrs pour les diamants, les pierres précieuses colorées, les perles, l\'or, l\'argent et les bijoux plaqués</li>\n<li>Routines de stockage et d\'entretien qui préviennent le ternissement, les rayures et l\'usure</li>\n<li>Dépannage rapide pour les problèmes courants (ternissement, nuage, sertissages lâches)</li>\n<li>Conseils d\'économie qui réduisent les nettoyages professionnels inutiles</li>\n</ul>\n<h3>Qu\'est-ce qu\'il y a à l\'intérieur</h3>\n<ul>\n<li>Procédures de nettoyage faciles à suivre pour chaque type de métal et de pierre précieuse</li>\n<li>Fournitures recommandées et liste de contrôle d\'outils abordables</li>\n<li>Techniques de polissage et de lustrage étape par étape</li>\n<li>Comment nettoyer des matériaux délicats tels que les perles et les opales</li>\n<li>Solutions de stockage pour prévenir les nœuds, les rayures et la corrosion</li>\n<li>Quand demander une réparation ou une inspection professionnelle</li>\n<li>Un calendrier d\'entretien que vous pouvez suivre (quotidien, mensuel, annuel)</li>\n<li>Erreurs courantes à éviter et quels produits ménagers ne jamais utiliser</li>\n</ul>\n<h3>Qui devrait lire cet eBook ?</h3>\n<ul>\n<li>Quiconque possède des bijoux fins et souhaite préserver leur valeur et leur apparence</li>\n<li>Les personnes offrant des cadeaux qui souhaitent garder des héritages et des pièces spéciales en parfait état</li>\n<li>Les collectionneurs de pièces vintage ou de costume ayant besoin de méthodes de soins sûres</li>\n<li>Les propriétaires de petites boutiques ou les gardiens à la recherche de routines de nettoyage fiables</li>\n</ul>\n<h3>Livraison immédiate &amp; compatibilité</h3>\n<p>Après l\'achat, vous recevrez un accès instantané à un fichier eBook téléchargeable. Le fichier est compatible avec la plupart des ordinateurs, tablettes et liseuses. Les instructions de téléchargement et un lien sont fournis dans votre confirmation de commande et stockés dans votre compte pour un re-téléchargement pratique.</p>\n<h3>Facile à utiliser</h3>\n<ul>\n<li>Langage clair et sections organisées pour que vous puissiez trouver des réponses rapidement</li>\n<li>Listes de contrôle exploitables à suivre lors du nettoyage ou de l\'emballage des bijoux</li>\n<li>Aucune formation spécialisée requise &mdash; idéal pour les débutants et les propriétaires expérimentés</li>\n</ul>\n<h3>Support</h3>\n<p>Si vous avez des problèmes pour télécharger ou ouvrir votre eBook, notre équipe de support client est prête à vous aider. Les coordonnées sont incluses dans votre confirmation d\'achat.</p>\n<p><strong>Protégez votre investissement et gardez chaque pièce brillante.</strong> Téléchargez l\'eBook de nettoyage de bijoux maintenant et commencez à prendre soin de vos bijoux de la bonne manière.</p>\n</div>\n<p>&nbsp;</p>', 'eBOOK sur le nettoyage des bijoux', 'Téléchargez notre eBook sur le nettoyage des bijoux. Livraison numérique instantanée.', 'ai_translated', '2026-08-10 16:37:18', '2026-08-04 16:30:53', '2026-08-10 16:37:44');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (29, 15, 3, 'Webinaire sur la réparation de bijoux + Guide pratique', 'Cet article d\'échantillon démontre à la fois la fonctionnalité de vidéo de prévisualisation (mise en page vidéo) et comment une vidéo peut être affichée après l\'achat avec tout média correspondant tel que des PDF complémentaires, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Webinaire de réparation de bijoux + Guide</h2>\n<p>Ce produit d\'exemple comprend une démonstration, un fichier de webinaire préenregistré, un guide téléchargeable et un lien vers un guide de prévisualisation ci-dessous. Le téléchargement de la commande et la visualisation du webinaire sont contrôlés via la sécurité de la commande, tandis que le téléchargement ci-dessous est un shortcode de téléchargement via le gestionnaire de téléchargements CMS qui vous permet d\'ajouter des liens de téléchargement sécurisés à tout produit ou page de site.</p>\n<h3>Ce qui est inclus</h3>\n<ul>\n<li><strong>Webinaire enregistré :</strong> Démonstrations vidéo complètes et étape par étape que vous pouvez diffuser ou télécharger après l\'achat.</li>\n<li><strong>Guide complet (PDF) :</strong> eBook détaillé et imprimable qui reflète le flux de travail du webinaire et comprend de l\'espace pour vos notes.<br>[download:d971912d-cb2e-4790-98ae-8ec53bac2503 label=\"Preview the Guidebook\"]</li>\n<li><strong>Listes de contrôle pratiques et fiches de travail :</strong> Listes d\'outils, rappels de sécurité et listes de contrôle de projet à utiliser à l\'atelier.</li>\n<li><strong>Médias et ressources d\'exemple :</strong> Plans de réparation d\'exemple et diagrammes de référence fournis sous forme de PDF téléchargeables.</li>\n</ul>\n<h3>Techniques et compétences que vous apprendrez</h3>\n<ul>\n<li>Notions de base sur le soudage et conseils pour des joints propres sur l\'or et l\'argent</li>\n<li>Réparation de chaînes et de fermoirs, nettoyage et reconditionnement</li>\n<li>Fondamentaux du redimensionnement de bagues et meilleures pratiques de dimensionnement</li>\n<li>Réparation de griffes, réajustement et resserrage pour des sertissages sécurisés</li>\n<li>Ajustements de sertissage en clos et en surface pour différentes pierres précieuses</li>\n<li>Techniques de polissage, de finition et de réparation de surface</li>\n<li>Dépannage simple et comment éviter les erreurs courantes</li>\n</ul>\n<h3>Pour qui est-ce</h3>\n<ul>\n<li>Bijoutiers débutants cherchant une formation structurée et visuelle</li>\n<li>Fabricants expérimentés souhaitant des rappels rapides sur les meilleures pratiques</li>\n<li>Propriétaires de petites entreprises et détaillants prêts à la réparation</li>\n<li>Quiconque souhaitant réparer des pièces personnelles ou sentimentales à la maison</li>\n</ul>\n<h3>Comment cela fonctionne</h3>\n<ul>\n<li>Accès numérique instantané après l\'achat — aucun produit physique ne sera expédié.</li>\n<li>Diffusez le webinaire enregistré depuis votre compte ou téléchargez les fichiers pour une visualisation hors ligne.</li>\n<li>Suivez avec l\'eBook et utilisez les fiches de travail incluses pour prendre vos propres notes et suivre vos progrès.</li>\n</ul>\n<h3>Détails techniques et exigences système</h3>\n<ul>\n<li>Format vidéo : MP4 (streaming et téléchargeable)</li>\n<li>Guide : PDF (imprimable)</li>\n<li>Compatible avec les navigateurs modernes, ordinateurs de bureau ou appareils mobiles ; nécessite un lecteur PDF et une connexion Internet de base pour le streaming ou le téléchargement.</li>\n</ul>\n<h3>Pourquoi ce pack fonctionne</h3>\n<p>Cette combinaison de démonstration visuelle plus un guide écrit détaillé vous permet de regarder les réparations effectuées en temps réel, puis de suivre les mêmes étapes à votre atelier avec des instructions claires et imprimables. L\'accent pratique signifie que vous acquerrez des techniques utilisables que vous pouvez appliquer immédiatement — que ce soit pour réparer un héritage précieux ou offrir des services de réparation à des clients.</p>\n<p><strong>Prêt à commencer ?</strong> Achetez maintenant pour un accès instantané et commencez à apprendre des techniques pratiques de réparation de bijoux dès aujourd\'hui.</p>\n</div>\n<p>&nbsp;</p>', 'Webinaire de réparation de bijoux plus eBook', 'Webinaire de réparation de bijoux plus eBook. Pack de téléchargement numérique.', 'ai_translated', '2026-08-10 16:37:44', '2026-08-04 16:31:09', '2026-08-10 16:37:58');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (30, 28, 3, 'Compte de paiement de factures | Exemple de recharge', 'Entrez le montant que vous souhaitez payer dans l\'espace prévu ci-dessous. Min 25 $ | Max 100 $', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Don | Exemple de Paiement de Facture</h2>\n<p>Cette option de produit vous permet de contribuer un montant fixe ou d\'entrer un montant personnalisé &mdash; dans la plage autorisée &mdash; et d\'avoir les fonds crédités sur votre compte immédiatement après une facturation réussie. Idéal pour les recharges de compte, les dons uniques ou les paiements de factures traités comme un élément de service.</p>\n<h3>Comment utiliser</h3>\n<ul>\n<li><strong>Choisissez un montant prédéfini :</strong> Sélectionnez l\'une des valeurs prédéfinies de la liste pour un paiement rapide.</li>\n<li><strong>Ou entrez un montant personnalisé :</strong> Tapez n\'importe quelle valeur entre <strong>$25</strong> et <strong>$100</strong> pour définir le montant exact que vous souhaitez payer.</li>\n<li><strong>Complétez le paiement :</strong> Procédez au paiement. Après une facturation réussie, le montant sera crédité sur votre compte immédiatement.</li>\n</ul>\n<h3>Avantages clés</h3>\n<ul>\n<li><strong>Options flexibles :</strong> Utilisez des montants à sélection rapide ou entrez une valeur spécifique pour répondre à vos besoins.</li>\n<li><strong>Crédit immédiat :</strong> Les fonds sont appliqués à votre compte dès que la facturation est terminée.</li>\n<li><strong>Traitement sécurisé :</strong> Les paiements sont traités via notre paiement sécurisé pour protéger vos informations.</li>\n<li><strong>Enregistrements clairs :</strong> Vous recevrez une confirmation et un reçu pour votre transaction.</li>\n</ul>\n<h3>Détails importants</h3>\n<ul>\n<li>Montant minimum : <strong>$25</strong></li>\n<li>Montant maximum : <strong>$100</strong></li>\n<li>Veuillez vous assurer que le montant que vous entrez se situe dans la plage ci-dessus ; les transactions en dehors de cette plage ne seront pas acceptées.</li>\n<li>Si vous avez des questions concernant le traitement, les crédits ou les reçus, contactez notre équipe de support pour assistance.</li>\n</ul>\n<p><em>Remarque :</em> Cet article est fourni comme un exemple de fonctionnalité de paiement de facture/don et démontre comment un administrateur peut configurer des montants de paiement fixes ou saisis par le client dans une plage min/max spécifiée.</p>\n</div>\n<p>&nbsp;</p>', 'Paiement de Facture | Exemple \"Faire une Offre\"', 'Cet élément d\'échantillon montre comment l\'administrateur peut créer un élément pour accepter soit un montant fixe (via une liste), soit un montant saisi par le client dans une plage min/max.', 'ai_translated', '2026-08-10 16:37:58', '2026-08-04 16:31:20', '2026-08-10 16:38:07');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (31, 30, 3, 'Atelier de médias sociaux de 2 jours', 'Atelier intensif de médias sociaux de 2 jours.<br><br><strong>14/04-15/04/2027 (9h-17h chaque jour)</strong>', NULL, 'Atelier de médias sociaux de 2 jours', 'Atelier de médias sociaux de 2 jours. Formation pratique pour Instagram, Facebook, LinkedIn.', 'ai_translated', '2026-08-10 16:38:07', '2026-08-04 16:31:36', '2026-08-10 16:38:10');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (32, 31, 3, 'Séminaire de gestion des stocks - Cours avancé', 'Démontre le statut des événements personnalisés sur les événements épuisés. (Message personnalisé de rupture de stock)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Séminaire avancé sur la gestion des stocks &mdash; Optimisez les stocks, réduisez les coûts, améliorez le service</h2>\n<p>Prenez le contrôle de votre inventaire avec un séminaire pratique et concret conçu pour les équipes de vente au détail et de commerce électronique prêtes à aller au-delà des bases. Ce cours avancé enseigne des stratégies éprouvées de prévision, de planification de la demande, d\'optimisation des entrepôts et de réapprovisionnement automatisé qui réduisent les ruptures de stock, abaissent les coûts de stockage et améliorent les niveaux de service client.</p>\n<h3>Ce que vous gagnerez</h3>\n<ul>\n<li><strong>Techniques de prévision exploitables</strong> &mdash; appliquez des méthodes de séries chronologiques, des ajustements de saisonnalité et des facteurs causaux pour produire des prévisions de demande plus fiables.</li>\n<li><strong>Stratégies de réapprovisionnement intelligentes</strong> &mdash; concevez et mettez en œuvre des politiques de min/max, de point de commande, de quantité économique de commande (EOQ) et de stock de sécurité qui correspondent aux rythmes de votre entreprise et aux délais de livraison des fournisseurs.</li>\n<li><strong>Optimisation des entrepôts</strong> &mdash; optimisez la disposition, le placement et les flux de prélèvement pour réduire le temps de manutention et améliorer le débit.</li>\n<li><strong>Prise de décision basée sur les données</strong> &mdash; utilisez la segmentation (ABC/XYZ), les indicateurs clés de performance (KPI) et les tableaux de bord pour concentrer les efforts là où cela fait la différence.</li>\n<li><strong>Automatisation &amp; intégration des systèmes</strong> &mdash; conseils pratiques pour intégrer la prévision et le réapprovisionnement avec les systèmes ERP, WMS et les plateformes de commerce électronique.</li>\n<li><strong>Planification de scénarios &amp; gestion des risques</strong> &mdash; techniques pour gérer la volatilité de la demande, les perturbations des fournisseurs et les promotions sans surstock.</li>\n</ul>\n<h3>Qui devrait assister</h3>\n<ul>\n<li>Gestionnaires des stocks, de la chaîne d\'approvisionnement et des opérations</li>\n<li>Responsables du merchandising en ligne et en magasin</li>\n<li>Superviseurs d\'entrepôt et coordinateurs logistiques</li>\n<li>Professionnels des achats responsables des politiques de réapprovisionnement</li>\n<li>Analystes commerciaux et partenaires financiers axés sur les coûts d\'inventaire</li>\n</ul>\n<h3>Format du cours &amp; composants pratiques</h3>\n<ul>\n<li>Séminaire intensif dirigé par un instructeur, comprenant des études de cas réelles et des exercices pratiques</li>\n<li>Ateliers interactifs où les participants construisent des modèles de prévision et des règles de réapprovisionnement</li>\n<li>Modèles et outils (modèles Excel, tableaux de bord KPI, listes de contrôle SOP) que vous pouvez adapter immédiatement</li>\n<li>Questions &amp; Réponses et discussions entre pairs pour aborder vos défis opérationnels spécifiques</li>\n</ul>\n<h3>Résultats clés</h3>\n<ul>\n<li>Amélioration de la précision des prévisions et un processus clair pour la révision continue des prévisions</li>\n<li>Politiques de réapprovisionnement alignées sur les profils de demande et la performance des fournisseurs</li>\n<li>Changements pratiques dans l\'entrepôt qui augmentent la vitesse de prélèvement et réduisent les erreurs</li>\n<li>Une feuille de route pour automatiser les flux de travail d\'inventaire et intégrer les systèmes</li>\n<li>Outils et modèles à emporter pour mettre en œuvre des améliorations immédiatement</li>\n</ul>\n<h3>Prérequis</h3>\n<ul>\n<li>Familiarité avec les concepts de base des stocks (point de commande, stock de sécurité, délai de livraison)</li>\n<li>Aisance avec les tableurs (Excel/Google Sheets) pour les exercices en atelier</li>\n</ul>\n<h3>Dirigé par des praticiens expérimentés</h3>\n<p>Ce séminaire est animé par des instructeurs ayant une expérience pratique dans la mise en œuvre de solutions de gestion des stocks et de réapprovisionnement pour les entreprises de vente au détail et de commerce électronique. Les sessions se concentrent sur des méthodes pratiques et répétables que vous pouvez appliquer immédiatement dans votre opération.</p>\n<p><strong>Les places sont limitées pour maintenir un environnement d\'apprentissage interactif.</strong> Inscrivez-vous maintenant pour garantir votre place et commencer à transformer l\'inventaire en un avantage concurrentiel.</p>\n</div>\n<p>&nbsp;</p>', 'Séminaire de gestion des stocks - Cours avancé', 'Séminaire avancé sur la gestion des stocks. Formation au détail et au commerce électronique.', 'ai_translated', '2026-08-10 16:38:10', '2026-08-04 16:31:40', '2026-08-10 16:38:24');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (33, 34, 3, 'Séminaire de Gestion des Stocks - Cours d\'Introduction', 'Séminaire d\'introduction à la gestion des stocks pour les nouveaux propriétaires d\'entreprise. <br><br><strong>2 février 2027 | 9-10 h</strong>', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Aperçu</h2>\n<p>Ce séminaire d\'introduction à la gestion des stocks est conçu pour les nouveaux propriétaires d\'entreprise et le personnel opérationnel en phase de démarrage qui ont besoin de conseils clairs et pratiques sur le contrôle des stocks, la réduction des déchets et la prise de décisions d\'achat plus intelligentes. Le séminaire décompose les concepts fondamentaux de l\'inventaire en étapes faciles à suivre afin que vous puissiez les appliquer immédiatement à votre entreprise.</p>\n<h2>Ce que vous apprendrez</h2>\n<ul>\n<li>Concepts fondamentaux de l\'inventaire : pourquoi l\'inventaire est important pour la trésorerie, la satisfaction client et la rentabilité</li>\n<li>Méthodes simples de comptage des stocks et meilleures pratiques pour des enregistrements d\'inventaire précis</li>\n<li>Comment structurer des bons de commande de base et rationaliser la communication avec les fournisseurs</li>\n<li>Comment calculer et appliquer des points de réapprovisionnement simples et des stocks de sécurité pour les articles du quotidien</li>\n<li>Approches pratiques pour réduire les ruptures de stock et l\'excès d\'inventaire sans systèmes complexes</li>\n</ul>\n<h2>Qui devrait assister</h2>\n<ul>\n<li>Nouveaux propriétaires d\'entreprise et entrepreneurs gérant des stocks pour la première fois</li>\n<li>Gestionnaires de détail, propriétaires de cafés et de restaurants, et petits opérateurs de gros</li>\n<li>Vendeurs de commerce électronique gérant des stocks sur un ou plusieurs canaux</li>\n<li>Personnel administratif ou opérationnel responsable des commandes et du contrôle des stocks</li>\n</ul>\n<h2>Comment le séminaire est dispensé</h2>\n<p>Dispensé dans un format d\'atelier dirigé par un instructeur, le séminaire combine de courtes présentations conceptuelles avec des exemples concrets et une session de questions-réponses interactive. Le rythme est adapté aux débutants et pratique—aucune expérience avancée en comptabilité ou en logiciels n\'est requise.</p>\n<h2>Avantages pour votre entreprise</h2>\n<ul>\n<li>Prendre des décisions de commande plus rapides et plus confiantes qui libèrent des liquidités et réduisent les coûts de stockage</li>\n<li>Améliorer la précision des stocks et éviter les ruptures de stock coûteuses pendant les périodes de forte demande</li>\n<li>Adopter des routines de comptage et de commande répétables qui font gagner du temps et réduisent les erreurs</li>\n<li>Acquérir une méthode simple pour définir des points de réapprovisionnement que vous pouvez utiliser immédiatement</li>\n</ul>\n<h2>À quoi s\'attendre après le séminaire</h2>\n<p>Les participants repartiront avec une compréhension claire et concrète des flux de travail de base en matière d\'inventaire et des étapes pratiques à mettre en œuvre dans leur entreprise. Vous serez en mesure de réaliser des comptages de stocks efficaces, de passer des commandes d\'achat plus intelligentes et d\'utiliser des calculs de réapprovisionnement simples pour maintenir l\'inventaire aux bons niveaux.</p>\n<h2>Prérequis</h2>\n<p>Aucune connaissance préalable en inventaire ou en comptabilité n\'est requise. Apportez des exemples de vos listes de stocks actuelles ou de vos défis d\'achat pour rendre la session plus pertinente et actionable.</p>\n<p><strong>Prêt à prendre le contrôle de votre stock ?</strong> Rejoignez ce séminaire pour construire une base solide en gestion des stocks qui soutient la croissance, réduit les déchets et vous fait gagner du temps. Inscrivez-vous aujourd\'hui pour réserver votre place.</p>\n</div>', 'Séminaire de gestion des stocks - Cours d\'introduction', 'Séminaire d\'introduction à la gestion des stocks. Formation pour les nouveaux propriétaires d\'entreprise.', 'ai_translated', '2026-08-10 16:38:24', '2026-08-04 16:31:45', '2026-08-10 16:38:37');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (34, 1, 3, 'Bracelet 3 Ct 14k|24k', 'Échantillon d\'article montrant des associations de vente croisée.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bracelet en Diamants en Or Blanc 14K &mdash; Élégance Intemporelle</h2>\n<p>Élevez n\'importe quel look avec ce magnifique bracelet en diamants fabriqué en or blanc 14K brillant. Doté de diamants taillés en brillant totalisant 1/4 carat, le bracelet combine un éclat délicat avec un design de maillons flexible et raffiné pour un confort et un port sans effort du jour à la nuit.</p>\n<h3>Caractéristiques Clés</h3>\n<ul>\n<li><strong>Métal :</strong> Or blanc 14K avec une finition polie</li>\n<li><strong>Diamants :</strong> Diamants taillés en brillant, habilement sertis, poids total de 1/4 carat</li>\n<li><strong>Design :</strong> Construction de maillons flexibles pour un ajustement confortable et naturel</li>\n<li><strong>Occasions :</strong> Assez élégant pour des événements spéciaux, discret pour un usage quotidien</li>\n</ul>\n<h3>Pourquoi Vous Allez L\'Adorer</h3>\n<ul>\n<li>L\'or blanc classique et les diamants brillants créent une pièce polyvalente qui complète toute garde-robe.</li>\n<li>Le design à profil bas et flexible se pose confortablement sur le poignet tout en offrant un éclat captivant.</li>\n<li>Un cadeau idéal pour les anniversaires, les naissances, les remises de diplômes ou comme un symbole significatif pour tout moment spécial.</li>\n</ul>\n<h3>Conseils de Style</h3>\n<ul>\n<li>Portez-le seul pour un look raffiné et minimaliste.</li>\n<li>Empilez-le avec d\'autres bracelets ou une montre fine pour créer un effet superposé personnalisé.</li>\n<li>S\'associe magnifiquement avec des ensembles décontractés et formels &mdash; des jeans aux tenues de soirée.</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Retirez avant de prendre une douche, de nager ou de faire des tâches ménagères pour préserver la finition et l\'éclat des pierres.</li>\n<li>Nettoyez délicatement avec une brosse douce et du savon doux, puis rincez et séchez avec un chiffon non pelucheux.</li>\n<li>Rangez séparément des autres bijoux pour éviter les rayures.</li>\n</ul>\n<p>Faites une impression durable avec ce bracelet en diamants en or blanc 14K élégant &mdash; un ajout intemporel à toute collection de bijoux et un cadeau réfléchi et éblouissant pour quelqu\'un de spécial.</p>\n</div>\n<p>&nbsp;</p>', 'Bracelet 3 Ct 14k|24k', 'Découvrez notre magnifique collection de bracelets en diamant. Bijoux raffinés en or blanc 14K.', 'ai_translated', '2026-08-10 16:38:37', '2026-08-04 16:31:51', '2026-08-10 16:38:45');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (35, 2, 3, 'Bague Cœur de Saphir', 'Bague élégante en cœur de saphir — un bijou intemporel pour toute garde-robe. Cet article d\'échantillon est proposé à un prix de vente pour montrer la différence dans l\'affichage des prix.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Bague Cœur de Saphir</h1>\n<p><strong>Élégante. Romantique. Intemporelle.</strong> La Bague Cœur de Saphir associe un saphir en forme de cœur gracieux à de l\'or jaune 14K chaud pour une pièce qui se sent à la fois classique et contemporaine. Finement fabriquée avec un sertissage à griffes sécurisé, cette bague est conçue pour briller des moments quotidiens aux occasions les plus mémorables de la vie.</p>\n<h2>Pourquoi vous l’aimerez</h2>\n<ul>\n<li><strong>Pierre centrale emblématique :</strong> Un saphir en forme de cœur qui capture la lumière et l\'attention avec une couleur subtile et durable.</li>\n<li><strong>Artisanat classique :</strong> Sertie en or jaune 14K avec un sertissage à griffes sécurisé pour une beauté et une sécurité durables.</li>\n<li><strong>Style polyvalent :</strong> Assez élégante pour les soirées, assez discrète pour le quotidien—s\'associe magnifiquement avec d\'autres bagues ou se porte seule comme une pièce maîtresse.</li>\n<li><strong>Cadeau significatif :</strong> Un choix romantique pour les anniversaires, les fiançailles, les anniversaires, ou tout moment que vous souhaitez marquer avec amour.</li>\n</ul>\n<h2>Détails du produit</h2>\n<ul>\n<li><strong>Métal :</strong> Or jaune 14K</li>\n<li><strong>Pierre précieuse :</strong> Saphir en forme de cœur</li>\n<li><strong>Sertissage :</strong> Sertissage à griffes sécurisé</li>\n<li><strong>Finition :</strong> Haute brillance</li>\n<li><strong>Artisanat :</strong> Fini à la main pour un détail raffiné</li>\n</ul>\n<h2>Personnalisation &amp; tailles</h2>\n<p>Cette bague est disponible en tailles standard et peut être personnalisée sur demande. Les options incluent souvent des choix de métal alternatifs et une gravure personnalisée—contactez notre équipe pour créer une pièce sur mesure qui reflète parfaitement votre style et votre histoire.</p>\n<h2>Entretien &amp; maintenance</h2>\n<ul>\n<li>Nettoyez délicatement avec de l\'eau tiède, du savon doux et une brosse douce ; rincez et séchez soigneusement.</li>\n<li>Évitez les produits chimiques agressifs, les températures extrêmes et les chocs pour préserver la pierre précieuse et la finition.</li>\n<li>Rangez séparément pour éviter les rayures et maintenir l\'éclat.</li>\n</ul>\n<h2>Tranquillité d\'esprit</h2>\n<p>Chaque Bague Cœur de Saphir est inspectée pour répondre à nos normes de qualité et est accompagnée d\'un support client dédié pour vous aider avec la taille, l\'entretien et toutes vos questions. Pour toute assistance concernant la personnalisation ou la commande, notre équipe est heureuse de vous aider.</p>\n<p><strong>Faites-la vôtre :</strong> Ajoutez la Bague Cœur de Saphir à votre collection aujourd\'hui et portez un symbole intemporel d\'amour et d\'élégance pour les années à venir.</p>\n</div>\n<p>&nbsp;</p>', 'Bague Cœur de Saphir', 'Bague en saphir en forme de cœur en or 14K élégant. Achetez des bijoux de luxe.', 'ai_translated', '2026-08-10 16:38:45', '2026-08-04 16:32:05', '2026-08-10 16:38:56');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (36, 3, 3, 'Bague en mosaïque de diamant', 'Démontre les sélecteurs de taille ainsi que l\'option de vente incitative.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Présentation du produit</h2>\n<p>Inspirée par des motifs floraux intemporels et une précision moderne, la Bague Mosaïque de Diamants donne vie à une délicate fleur dans une fascinante gamme de diamants. Méticuleusement conçue avec un motif mosaïque complexe, chaque petite pierre capte la lumière sous tous les angles pour créer une surface brillante et scintillante qui se lit comme une déclaration florale unique. Parfaite comme bague signature, accent d\'engagement ou pièce quotidienne rehaussée.</p>\n<h2>Caractéristiques clés</h2>\n<ul>\n<li><strong>Design :</strong> Mosaïque florale élégante &mdash; un cluster complexe de diamants agencés pour ressembler à une fleur épanouie.</li>\n<li><strong>Options de métal :</strong> Montée en or blanc 14K (standard). Amélioration en platine disponible pour une durabilité accrue et une finition blanche plus brillante.</li>\n<li><strong>Montage des diamants :</strong> Plusieurs diamants sont précisément montés pour maximiser l\'éclat et la continuité visuelle à travers le motif.</li>\n<li><strong>Artisanat :</strong> Détails finis à la main et montage expert garantissent une structure durable et une apparence raffinée.</li>\n<li><strong>Personnalisation :</strong> Disponible dans une gamme de tailles de bague ; l\'amélioration en platine et les demandes personnalisées sont les bienvenues. Contactez-nous pour des options de taille spéciale ou de personnalisation.</li>\n</ul>\n<h2>Pourquoi vous allez l’aimer</h2>\n<p>La Bague Mosaïque de Diamants équilibre charme romantique et finesse contemporaine. Son design compact mais hautement détaillé la rend polyvalente &mdash; belle seule ou associée à d\'autres bagues. La mosaïque de plusieurs pierres donne l\'impression d\'une plus grande surface de lumière, offrant une présence frappante sans un profil encombrant.</p>\n<h2>Matériaux &amp; Entretien</h2>\n<ul>\n<li><strong>Métaux :</strong> Or blanc 14K (standard). Platine disponible sur demande.</li>\n<li><strong>Diamants :</strong> Soigneusement sélectionnés et sourcés de manière responsable pour offrir un éclat exceptionnel et une durabilité prolongée.</li>\n<li><strong>Entretien :</strong> Nettoyez délicatement avec une brosse douce et de l\'eau savonneuse douce ; évitez les produits chimiques agressifs et les nettoyants abrasifs. Rangez séparément dans une pochette douce ou une boîte à bijoux pour éviter les rayures.</li>\n</ul>\n<h2>Tailles, expédition &amp; services</h2>\n<ul>\n<li><strong>Tailles :</strong> Disponible dans des tailles de bague standard. Si vous avez besoin d\'aide pour déterminer votre taille, contactez notre service client pour des conseils. Des services de redimensionnement peuvent être disponibles &mdash; veuillez demander avant l\'achat si vous avez besoin d\'un ajustement précis.</li>\n<li><strong>Délai de livraison :</strong> Cette pièce peut être fabriquée sur commande ou adaptée à vos spécifications. Les délais de production et d\'expédition varient ; contactez-nous pour des estimations de délai actuelles.</li>\n<li><strong>Emballage :</strong> Chaque bague est expédiée en toute sécurité dans un emballage protecteur et arrive dans une boîte à bijoux prête à offrir.</li>\n<li><strong>Demandes personnalisées :</strong> Pour des améliorations en platine, des tailles spéciales, des gravures ou d\'autres personnalisations, veuillez contacter nos spécialistes en bijoux avant de commander.</li>\n</ul>\n<h2>Besoin d\'aide ?</h2>\n<p>Si vous avez des questions sur les choix de métal, les tailles ou les options personnalisées (y compris une amélioration en platine), notre équipe est là pour vous aider. Sélectionnez votre métal et votre taille préférés, ou contactez-nous pour des demandes sur mesure et des conseils d\'experts.</p>\n<p><strong>Faites-en vôtre :</strong> Choisissez la Bague Mosaïque de Diamants pour une pièce complexe et lumineuse qui célèbre l\'artisanat et l\'élégance féminine.</p>\n</div>\n<p>&nbsp;</p>', 'Bague en mosaïque de diamant', 'Bague en mosaïque de diamants brillants. Faites un excellent cadeau pour cette personne spéciale !', 'ai_translated', '2026-08-10 16:38:56', '2026-08-04 16:32:09', '2026-08-10 16:39:09');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (37, 5, 3, 'Bague en Saphir et Diamant', 'Échantillon d\'article montrant une mise en page alternative (images sur le côté gauche) ainsi qu\'une option d\'emballage cadeau avec des options de taille. Les niveaux de stock sont également cachés selon le paramètre dans les options avancées du produit.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bague en Saphir et Diamant</h2>\n<p>Élégante et intemporelle, cette bague en saphir et diamant associe un saphir taillé brillant riche avec des accents de diamant étincelants sertis dans de l\'or blanc 14K lustré. Le sertissage classique en griffes maximise le retour de lumière et met en valeur le feu et la profondeur de chaque pierre, créant un centre raffiné qui passe facilement d\'une utilisation quotidienne à des occasions spéciales.</p>\n<h3>Pourquoi vous allez l\'aimer</h3>\n<ul>\n<li>Design classique : Une silhouette traditionnelle qui reste élégante pendant des générations.</li>\n<li>Éclat brillant : Des pierres taillées brillantes et des sertissages ouverts permettent un éclat maximal et une radiance du jour à la nuit.</li>\n<li>Matériaux durables : L\'or blanc 14K offre une finition durable et argentée adaptée à un port régulier.</li>\n<li>Style polyvalent : S\'associe magnifiquement avec des alliances, d\'autres bagues, ou se porte seule comme pièce maîtresse.</li>\n</ul>\n<h3>Détails &amp; personnalisation</h3>\n<ul>\n<li>Métal : Or blanc 14K</li>\n<li>Pierres : Saphir central avec accents de diamant taillés brillants</li>\n<li>Sertissage : Sertissage classique en griffes pour améliorer la performance de la lumière</li>\n<li>Options personnalisées : Disponible à la commande avec des tailles personnalisées et des métaux alternatifs (veuillez sélectionner les options lors du paiement ou nous contacter pour un devis personnalisé)</li>\n<li>Fait main : Chaque bague est soigneusement finie par des bijoutiers qualifiés pour garantir une beauté et une qualité durables</li>\n</ul>\n<h3>Entretien &amp; maintenance</h3>\n<ul>\n<li>Nettoyez périodiquement avec de l\'eau tiède savonneuse et une brosse douce ; évitez les produits chimiques agressifs et les nettoyeurs ultrasoniques si la pièce contient des pierres traitées.</li>\n<li>Retirez lors de travaux physiques lourds ou d\'exposition à des substances abrasives pour protéger les sertissages et la finition.</li>\n<li>Une inspection et un nettoyage professionnels sont recommandés annuellement pour maintenir la sécurité du sertissage et le lustre.</li>\n</ul>\n<p>Cette Bague en Saphir et Diamant constitue un cadeau significatif pour les anniversaires, les anniversaires, les fiançailles ou tout moment qui appelle à quelque chose de spécial. Pour des détails sur la certification, le redimensionnement ou des demandes sur mesure, veuillez contacter notre équipe de bijoux personnalisés &mdash; nous sommes heureux de vous aider à créer la bague parfaite pour vous.</p>\n</div>', 'Bague en Saphir et Diamant', 'Bague classique en saphir et diamant. Découvrez notre collection de bijoux de luxe.', 'ai_translated', '2026-08-10 16:39:09', '2026-08-04 16:32:13', '2026-08-10 16:39:20');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (38, 8, 3, 'Bracelet en diamant de style pincé', 'Exemple montrant le message par défaut de produit épuisé (Actuellement indisponible) ainsi que la vente croisée (recommandations de produits).', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h1>Bracelet en Diamant de Style Pincé</h1>\n<p>Élégant et discret, le Bracelet en Diamant de Style Pincé associe un savoir-faire délicat à un éclat quotidien. Fabriqué avec soin en or blanc 14K, ses maillons de style pincé sont sertis de diamants taille brillant qui captent la lumière sous tous les angles&mdash;créant une silhouette raffinée et texturée qui est parfaite pour être empilée ou portée seule.</p>\n<h2>Caractéristiques Principales</h2>\n<ul>\n<li><strong>Métal :</strong> Or blanc 14K fin avec une finition polie.</li>\n<li><strong>Gemmes :</strong> Diamants taille brillant sertis tout au long pour un éclat continu.</li>\n<li><strong>Design :</strong> Les maillons de style pincé offrent une texture subtile et une réflexion de la lumière améliorée.</li>\n<li><strong>Portabilité :</strong> Construction délicate et légère idéale pour un usage quotidien ou des occasions spéciales.</li>\n<li><strong>Fermeture sécurisée :</strong> Fini avec un fermoir fiable pour un port confortable et en toute confiance.</li>\n</ul>\n<h2>Style &amp; Occasion</h2>\n<p>La silhouette intemporelle de ce bracelet en fait un ajout polyvalent à toute garde-robe de bijoux. Portez-le seul pour une déclaration minimaliste, superposez-le avec des chaînes fines pour un look empilé contemporain, ou associez-le à des boucles d\'oreilles ou un pendentif assorti pour une élégance nocturne. C\'est un cadeau idéal pour les anniversaires, les anniversaires de mariage, les remises de diplômes, ou comme une surprise attentionnée &ldquo;juste parce que&rdquo;.</p>\n<h2>Entretien &amp; Maintenance</h2>\n<ul>\n<li>Évitez l\'exposition à des produits chimiques agressifs, des parfums et des lotions pour préserver le métal et l\'éclat des diamants.</li>\n<li>Nettoyez délicatement avec une brosse douce et de l\'eau savonneuse douce ; rincez soigneusement et séchez avec un chiffon doux.</li>\n<li>Rangez séparément dans une pochette douce ou une boîte à bijoux pour éviter les rayures et les enchevêtrements.</li>\n<li>Inspectez périodiquement les sertissages et les fermoirs ; un nettoyage et une inspection professionnels sont recommandés pour un port à long terme.</li>\n</ul>\n<h2>Personnalisation &amp; Commande</h2>\n<p>Disponible dans le cadre de notre collection de Bijoux Personnalisés&mdash;veuillez nous contacter pour des longueurs personnalisées, des finitions métalliques, ou des demandes spéciales. Pour des tailles personnalisées ou des options sur mesure, notre équipe travaillera avec vous pour créer la pièce parfaite.</p>\n<p><strong>Prêt à le rendre à vous ?</strong> Ajoutez un éclat intemporel à chaque moment&mdash;contactez-nous pour des demandes personnalisées ou pour confirmer la disponibilité et les délais.</p>\n</div>', 'Bracelet en diamant style pincé', 'Bracelet en diamant de style pincé en or blanc 14K. Découvrez notre collection de bijoux fins.', 'ai_translated', '2026-08-10 16:39:20', '2026-08-04 16:32:16', '2026-08-10 16:39:31');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (39, 9, 3, 'Bracelet Cœur en Diamant Avec Vos Initiales Gravées', 'Produit simple avec la fonctionnalité de personnalisation par défaut activée au niveau de la variante.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bracelet en Diamant en Forme de Cœur Avec Vos Initiales Gravées</h2>\n<p>Élevez l\'élégance quotidienne avec ce bracelet en diamant au design classique en forme de cœur, habilement fabriqué en or blanc 14K. Une délicate rangée de diamants pavés forme un centre en forme de cœur scintillant qui est personnalisé avec vos initiales pour un souvenir moderne et significatif. Poids total en diamants : 1/2 carat. La personnalisation est incluse sans frais supplémentaires.</p>\n<h3>Pourquoi vous allez l\'aimer</h3>\n<ul>\n<li><strong>Design intemporel :</strong> Un motif de cœur raffiné qui passe sans effort du jour à la nuit.</li>\n<li><strong>Personnel et significatif :</strong> Vos initiales gravées directement sur le cœur pour une touche subtile et sentimentale.</li>\n<li><strong>Matériaux de qualité :</strong> Or blanc 14K solide associé à des diamants sans conflit pour une beauté durable et un approvisionnement éthique.</li>\n<li><strong>Prêt à offrir :</strong> Livré dans une luxueuse boîte à bijoux — parfait pour les anniversaires, les anniversaires ou \"juste parce que\".</li>\n</ul>\n<h3>Détails du produit</h3>\n<ul>\n<li>Métal : or blanc 14K</li>\n<li>Poids total en diamants : 0,50 carat</li>\n<li>Design : Motif central en forme de cœur serti de diamants pavés</li>\n<li>Finition : Haute brillance</li>\n<li>Emballage : Boîte cadeau et chiffon de polissage offerts</li>\n</ul>\n<h3>Personnalisation (incluse)</h3>\n<p>La fonction de personnalisation par défaut est activée au niveau de la variante — la personnalisation est appliquée par défaut lorsque vous choisissez une variante personnalisée. La personnalisation est incluse sans coût supplémentaire.</p>\n<ol>\n<li>Entrez les initiales que vous souhaitez graver dans les options de produit ou le champ d\'initiales associé à votre variante sélectionnée.</li>\n<li>Nous recommandons jusqu\'à 3 caractères (initiales standard). Lettres uniquement (A–Z). Si vous avez besoin de caractères spéciaux ou d\'une inscription plus longue, veuillez contacter le service client avant de commander.</li>\n<li>La gravure est réalisée dans une police élégante et lisible optimisée pour le motif en forme de cœur. Les initiales apparaîtront en majuscules, sauf indication contraire.</li>\n</ol>\n<p><strong>Important :</strong> Veuillez vérifier l\'orthographe et l\'ordre des caractères avant de finaliser votre achat — les articles personnalisés peuvent être en vente finale, sauf en cas de défaut de fabrication.</p>\n<h3>Dimensions &amp; taille</h3>\n<p>Le bracelet est disponible en plusieurs longueurs — sélectionnez votre taille préférée parmi les options de variante. Pour un meilleur ajustement, mesurez autour du poignet où vous portez des bracelets et laissez 1/2\"–1\" pour un mouvement confortable. Si vous êtes entre deux tailles ou avez besoin d\'aide, notre équipe de support client peut vous aider à choisir la bonne longueur.</p>\n<h3>Production &amp; expédition</h3>\n<ul>\n<li>Fabriqué sur commande : Comptez 5–7 jours ouvrables pour la personnalisation et l\'inspection finale, plus le temps d\'expédition.</li>\n<li>Des services accélérés peuvent être disponibles lors du paiement — choisissez l\'option d\'expédition qui correspond à votre calendrier.</li>\n</ul>\n<h3>Entretien &amp; maintenance</h3>\n<ul>\n<li>Retirez avant de prendre une douche, de nager ou d\'utiliser des produits chimiques ménagers.</li>\n<li>Nettoyez délicatement avec un chiffon doux et sans peluches ; un nettoyage et une inspection professionnels sont recommandés annuellement.</li>\n<li>Rangez dans la boîte fournie pour éviter les rayures et les enchevêtrements.</li>\n</ul>\n<h3>Besoin d\'aide ?</h3>\n<p>Si vous avez des questions sur la personnalisation, la taille ou les délais de livraison, contactez notre équipe de service client — nous sommes heureux de vous aider avec des demandes personnalisées ou des arrangements de cadeaux spéciaux.</p>\n<p><strong>Ajoutez une touche intemporelle et personnalisée à votre collection de bijoux — commandez votre Bracelet en Diamant en Forme de Cœur avec vos initiales gravées dès aujourd\'hui.</strong></p>\n</div>', 'Bracelet en cœur de diamant avec vos initiales gravées', 'Bracelet en cœur de diamant avec initiales. Or blanc 14K. Achetez des bijoux de luxe.', 'ai_translated', '2026-08-10 16:39:31', '2026-08-04 16:32:23', '2026-08-10 16:39:47');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (40, 17, 3, 'Sweat-shirt pour hommes', 'Sweatshirt premium en poids lourd avec notre logo. Tailles S-XXL. (XXL +5 $) (Produit d\'exemple utilisant une mise en page avec des images sur le côté droit)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">Notre sweat-shirt pour homme en poids lourd premium allie confort classique et style quotidien épuré. Conçu pour être superposé par temps frais et pour un usage quotidien, il arbore notre logo emblématique et est disponible en trois couleurs polyvalentes : Noir, Bourgogne et Blanc.\n<section>\n<h2>Caractéristiques principales</h2>\n<ul>\n<li>Construction en poids lourd premium pour chaleur et confort durable</li>\n<li>Logo emblématique pour un look intemporel et discret</li>\n<li>Disponible en Noir, Bourgogne et Blanc</li>\n<li>Taille : S, M, L, XL, XXL <strong>(XXL +5 $)</strong></li>\n<li>Lavable en machine pour un entretien facile</li>\n</ul>\n</section>\n<section>\n<h2>Coupe &amp; Tailles&nbsp;</h2>\n<p>Conçu pour une coupe confortable au quotidien qui se superpose facilement sur des t-shirts et sous des vestes. Choisissez votre taille habituelle. Si vous préférez une sensation plus ample, envisagez de prendre une taille au-dessus.</p>\n<p><strong>Taille disponibles :</strong> Petit &bull; Moyen &bull; Grand &bull; XL &bull; XXL (<strong>ajouter 5,00 $</strong>)</p>\n</section>\n<section>\n<h2>Instructions d\'entretien</h2>\n<p>Lavable en machine pour un entretien simple. Pour de meilleurs résultats, lavez avec des couleurs similaires et séchez à basse température ou à l\'air libre pour maintenir la finition et l\'ajustement du sweat-shirt.</p>\n</section>\n<section>\n<h2>Pourquoi vous l\'aimerez</h2>\n<ul>\n<li>Chaleur fiable en poids lourd sans encombrement &mdash; parfait pour les jours plus frais</li>\n<li>Couleurs polyvalentes qui s\'associent facilement avec des jeans, des joggeurs ou des tenues superposées</li>\n<li>Un cadeau idéal : pratique, élégant et prêt à porter</li>\n</ul>\n</section>\n<p><strong>Remarque :</strong> Sélectionnez XXL pour ajouter 5 $ à votre commande. Ajoutez ce basique de garde-robe à votre panier aujourd\'hui &mdash; un sweat-shirt premium conçu pour le confort, la durabilité et le style quotidien.</p>\n</div>\n<p>&nbsp;</p>', 'Sweat-shirt pour hommes', 'Sweatshirt premium pour hommes. Plusieurs couleurs et tailles.', 'ai_translated', '2026-08-10 16:39:47', '2026-08-04 16:32:34', '2026-08-10 16:39:56');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (41, 20, 3, 'Stylos de bureau premium 2 pièces', 'Stylos de bureau premium en boîte cadeau — ensemble de 2 avec option de gravure. (Produit d\'exemple avec fonctionnalité de personnalisation activée.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Stylos de bureau premium &mdash; Pack de 2 (dans une boîte cadeau, gravure optionnelle)</h2>\n<p>Élevez l\'écriture quotidienne avec cet ensemble de deux stylos à bille premium, présentés dans une élégante boîte prête à offrir. Conçus pour une performance fluide et fiable ainsi qu\'une présentation raffinée, ces stylos constituent un cadeau d\'entreprise réfléchi ou un souvenir personnel lorsqu\'ils sont personnalisés avec une gravure optionnelle.</p>\n<h3>Ce qui est inclus</h3>\n<ul>\n<li>Ensemble de 2 stylos à bille premium</li>\n<li>Boîte de présentation élégante prête à offrir</li>\n<li>Gravure optionnelle disponible pour personnaliser chaque stylo</li>\n</ul>\n<h3>Avantages clés</h3>\n<ul>\n<li><strong>Écriture fluide et constante :</strong> Performance de stylo à bille fiable pour les signatures, les notes et une utilisation quotidienne.</li>\n<li><strong>Présentation professionnelle :</strong> Emballé dans une boîte prête à offrir&mdash;parfait pour les cadeaux aux clients, la reconnaissance des employés, les remises de diplômes et les occasions spéciales.</li>\n<li><strong>Touche personnalisée :</strong> Ajoutez une gravure optionnelle pour créer un cadeau mémorable et unique.</li>\n<li><strong>Utilisation polyvalente :</strong> Idéal pour les bureaux, les salles de réunion et les espaces de travail à domicile.</li>\n</ul>\n<h3>Détails de personnalisation</h3>\n<p>Ajoutez un nom significatif, une date ou un court message avec le service de gravure optionnelle. Sélectionnez l\'option de personnalisation lors de la commande et entrez le texte que vous souhaitez faire graver. Les stylos personnalisés sont fabriqués sur commande&mdash;veuillez vérifier votre saisie attentivement avant de finaliser votre commande.</p>\n<h3>Parfait pour offrir</h3>\n<p>Que vous reconnaissiez un collègue, remerciiez un client ou célébriez un jalon, ce pack de 2 stylos de bureau premium offre un style réfléchi et une valeur pratique. L\'emballage prêt à offrir et l\'option de gravure facilitent la création d\'un cadeau impressionnant et mémorable.</p>\n<p><strong>Commandez maintenant</strong> pour sécuriser un ensemble professionnel prêt à offrir&mdash;choisissez la gravure pour rendre votre cadeau unique.</p>\n</div>\n<p>&nbsp;</p>', 'Stylos de bureau premium 2 pièces', 'Stylos de bureau premium 2 pièces. Boîte cadeau incluse.', 'ai_translated', '2026-08-10 16:39:56', '2026-08-04 16:32:44', '2026-08-10 16:40:05');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (42, 21, 3, 'Boîte à bijoux en argent', 'Échantillon de produit avec remise sur la quantité appliquée.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p>Gardez vos pièces les plus précieuses en sécurité, organisées et magnifiquement exposées avec la Boîte à Bijoux en Argent &mdash; une solution de rangement élégante pour bijoux en ton argent, finie avec un intérieur en velours doux. Conçue avec soin pour un usage quotidien et des occasions spéciales, cette boîte allie un style raffiné à un rangement pratique pour protéger les bagues, boucles d\'oreilles, bracelets et plus encore.</p>\n<h2>Caractéristiques</h2>\n<ul>\n<li><strong>Extérieur en ton argent élégant</strong> &mdash; une finition polie qui complète n\'importe quelle chambre ou espace de maquillage.</li>\n<li><strong>Doublure en velours doux</strong> &mdash; protection douce pour éviter les rayures et le ternissement des métaux délicats et des pierres précieuses.</li>\n<li><strong>Multiples compartiments</strong> &mdash; rouleaux pour bagues dédiés et sections divisées pour garder les boucles d\'oreilles, bracelets et petites montres organisés et sans enchevêtrement.</li>\n<li><strong>Plateaux amovibles/réglables</strong> &mdash; créez une disposition personnalisée pour s\'adapter à votre collection et accéder facilement aux pièces.</li>\n<li><strong>Couvercle verrouillable</strong> &mdash; sécurité accrue et tranquillité d\'esprit lors du stockage d\'objets de valeur.</li>\n<li><strong>Design compact, prêt à être exposé</strong> &mdash; se pose élégamment sur une commode, un coiffeuse ou une étagère tout en présentant vos bijoux avec style.</li>\n</ul>\n<h2>Pourquoi vous allez l’adorer</h2>\n<ul>\n<li>Protège les finitions délicates et les pierres précieuses avec un rembourrage en velours moelleux.</li>\n<li>Fait gagner du temps en gardant votre collection organisée et facile à trouver.</li>\n<li>Assez élégant pour faire office d\'accent décoratif dans votre maison.</li>\n<li>Un cadeau idéal et réfléchi pour les anniversaires, les mariages, les demoiselles d\'honneur ou toute personne qui chérit ses bijoux.</li>\n</ul>\n<h2>Bon à savoir</h2>\n<ul>\n<li><strong>Intérieur :</strong> doublure en velours doux</li>\n<li><strong>Extérieur :</strong> finition durable en ton argent</li>\n<li><strong>Rangement :</strong> rouleaux pour bagues, compartiments divisés et sections amovibles pour une organisation flexible</li>\n<li><strong>Sécurité :</strong> couvercle verrouillable pour une protection supplémentaire</li>\n</ul>\n<h2>Entretien &amp; maintenance</h2>\n<ul>\n<li>Essuyez l\'extérieur avec un chiffon doux et sec pour enlever la poussière ; évitez les produits chimiques agressifs ou les nettoyants abrasifs.</li>\n<li>Nettoyez la doublure en velours avec une brosse douce ou un rouleau adhésif ; pour un nettoyage plus approfondi, consultez un spécialiste des soins textiles.</li>\n<li>Rangez la boîte dans un endroit frais et sec, à l\'abri de la lumière directe du soleil pour préserver la finition.</li>\n</ul>\n<h2>Parfait pour offrir</h2>\n<p>Présentée dans un style élégant et polyvalent, la Boîte à Bijoux en Argent constitue un cadeau réfléchi pour des fiançailles, des mariages, des vacances ou tout événement marquant. Associez-la à un collier préféré ou une nouvelle paire de boucles d\'oreilles pour créer un cadeau mémorable.</p>\n<p><strong>Ce qui est inclus :</strong> Boîte à Bijoux en Argent avec doublure en velours et compartiments internes.</p>\n<p>Organisez avec élégance &mdash; ajoutez la Boîte à Bijoux en Argent à votre collection dès aujourd\'hui.</p>\n</div>', 'Boîte à bijoux en argent', 'Boîte à bijoux en argent avec doublure en velours. Solution de rangement élégante.', 'ai_translated', '2026-08-10 16:40:05', '2026-08-04 16:32:47', '2026-08-10 16:40:16');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (43, 25, 3, 'T-shirt pour femmes', 'T-shirt pour femmes de qualité supérieure. Disponible en 6 couleurs.  (Produit exemple utilisant une mise en page avec des images sur le côté gauche)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>T-shirt Femme Premium &mdash; 100% Coton Doux, Silhouette Ajustée</h2>\n<p>Le confort quotidien rencontre le style sans effort. Ce t-shirt femme premium est fabriqué en 100% coton pour une sensation douce et respirante et une coupe ajustée flatteuse qui bouge avec vous. Disponible en six couleurs polyvalentes et en tailles S&ndash;XXL, c\'est un essentiel de garde-robe qui fonctionne seul ou en superposition.</p>\n<h3>Caractéristiques Clés</h3>\n<ul>\n<li>Matériau : 100% coton pour une respirabilité et un confort naturels</li>\n<li>Coupe : Silhouette ajustée pour femme qui offre une forme épurée et flatteuse</li>\n<li>Options de couleur : Marron, Gris, Vert, Marine, Orange, Bleu Royal</li>\n<li>Taille : S, M, L, XL, XXL&nbsp; (+5,00 $)</li>\n<li>Construction : Couture durable pour une utilisation prolongée</li>\n</ul>\n<h3>Ajustement &amp; Tailles</h3>\n<p>Le t-shirt présente une coupe ajustée conçue pour suivre les contours naturels du corps. Si vous préférez un look plus ample ou décontracté, envisagez de choisir une taille au-dessus. Pour le meilleur ajustement, consultez votre taille habituelle de t-shirt ou comparez avec un t-shirt similaire que vous aimez.</p>\n<h3>Instructions d\'Entretien</h3>\n<ul>\n<li>Lavage en machine à froid avec des couleurs similaires</li>\n<li>Séchage à basse température ou suspendre pour sécher afin de préserver la forme et la couleur</li>\n<li>Repassage à chaleur douce si nécessaire ; ne pas blanchir</li>\n</ul>\n<h3>Comment Porter</h3>\n<p>Polyvalent par conception, ce t-shirt se marie facilement avec des jeans, des jupes, des leggings ou des pantalons ajustés. Conseils de style :</p>\n<ul>\n<li>Décontracté : Rentrez-le dans un jean taille haute avec des baskets pour un look de week-end sans effort</li>\n<li>Superposé : Portez-le sous un blazer ou un cardigan pour un look de bureau smart-casual</li>\n<li>Actif : Combinez-le avec des joggers et des chaussures de sport pour un athleisure confortable</li>\n</ul>\n<h3>Parfait pour Offrir</h3>\n<p>Avec des couleurs classiques et un attrait quotidien, ce t-shirt constitue un cadeau pratique et élégant pour les anniversaires, les vacances ou comme une mise à niveau réfléchie de la garde-robe. Les tailles disponibles S&ndash;XXL facilitent la recherche de la bonne taille.</p>\n<p><strong>Ajouter au panier</strong> pour intégrer ce t-shirt femme premium, incontournable, dans votre rotation quotidienne &mdash; confort et style en une pièce essentielle.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'T-shirt pour femmes', 'T-shirt pour femmes. Coton premium, plusieurs couleurs.', 'ai_translated', '2026-08-10 16:40:16', '2026-08-04 16:32:57', '2026-08-10 16:40:26');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (44, 7, 3, 'Bracelet Vague de Diamants', 'Bracelet vague en diamant classique — monté en or blanc 14K. (Exemple de produit montrant l\'option de mise en page centrée)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bracelet Vague de Diamants &mdash; Or Blanc 14K</h2>\n<p>L\'élégance intemporelle rencontre le mouvement moderne dans le Bracelet Vague de Diamants. Habilement fabriqué en or blanc 14K, ce bracelet présente des diamants taillés en brillant disposés dans un motif de vague fluide qui capte la lumière à chaque mouvement du poignet. Subtil mais frappant, il est conçu pour rehausser les looks quotidiens et compléter les ensembles pour des occasions spéciales.</p>\n<h3>Points Forts du Produit</h3>\n<ul>\n<li>Métal : Or blanc 14K pour une radiance et une durabilité durables</li>\n<li>Pierres : Diamants taillés en brillant pour un éclat et une brillance maximaux</li>\n<li>Design : Motif de vague fluide qui drape gracieusement le long du poignet</li>\n<li>Artisanat : Diamants soigneusement sertis à la main et finition polie pour un look raffiné</li>\n<li>Polyvalence : Assez élégant pour les tenues de soirée, assez discret pour un usage quotidien</li>\n</ul>\n<h3>Pourquoi Vous Allez L\'Aimer</h3>\n<ul>\n<li>Silhouette distinctive &mdash; le design en vague apporte mouvement et intérêt visuel sans écraser votre style.</li>\n<li>Éclat réfléchissant &mdash; les diamants taillés en brillant sont positionnés pour maximiser le retour de lumière pour un scintillement accrocheur.</li>\n<li>Confortable à porter &mdash; soigneusement contourné pour s\'asseoir en douceur sur le poignet pour un confort tout au long de la journée.</li>\n<li>Parfait pour offrir &mdash; une pièce classique et sophistiquée pour les anniversaires, les anniversaires ou les moments marquants.</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<p>Pour garder votre Bracelet Vague de Diamants dans son meilleur état, nettoyez-le doucement avec un chiffon doux et un nettoyant pour bijoux doux. Retirez-le avant de nager, de faire de l\'exercice ou de manipuler des produits chimiques agressifs. Faites inspecter votre bracelet par un professionnel périodiquement pour vous assurer que les sertissages restent sécurisés.</p>\n<h3>Personnalisation &amp; Services</h3>\n<p>Dans le cadre de notre collection de bijoux sur mesure, ce bracelet peut être adapté à vos préférences. Sélectionnez différentes longueurs ou demandez des options de métal alternatives &mdash; veuillez contacter notre équipe pour la disponibilité et les prix sur mesure.</p>\n<p>Ce Bracelet Vague de Diamants allie des matériaux classiques à une silhouette artistique, en faisant un ajout polyvalent et durable à toute garde-robe de bijoux.</p>\n</div>\n<p>&nbsp;</p>', 'Bracelet Vague de Diamants', 'Bracelet vague en diamant classique. Bijoux fins pour toutes les occasions.', 'ai_translated', '2026-08-10 16:40:26', '2026-08-04 16:33:09', '2026-08-10 16:40:35');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (45, 4, 3, 'Bague en or 14K avec perle de culture et diamants', 'Démontre une méthode alternative de sélection d\'options avec une liste personnalisée au lieu de variantes. (Lorsque les niveaux de stock basés sur des options individuelles ne sont pas nécessaires, comme pour un article sur mesure.)', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<p><strong>Élevez chaque moment avec une élégance intemporelle.</strong> Cette bague en or 14K met en valeur une perle cultivée lumineuse encadrée par six diamants scintillants pour un look raffiné et féminin. Fabriquée à la main pour capter la lumière sous tous les angles, c\'est un choix idéal pour une sophistication quotidienne ou un cadeau mémorable pour une occasion spéciale.</p>\n<h2>Caractéristiques clés</h2>\n<ul>\n<li><strong>Métal :</strong> Or massif 14K pour une beauté et une durabilité durables.</li>\n<li><strong>Pierre centrale :</strong> Perle cultivée lustrée, choisie pour sa surface lisse et son riche nacre.</li>\n<li><strong>Pierres d\'accent :</strong> Six diamants taillés en rond qui ajoutent une brillance délicate autour de la perle.</li>\n<li><strong>Finition &amp; monture :</strong> Bague polie avec des montures sécurisées, fabriquées avec expertise pour protéger la perle et les diamants.</li>\n<li><strong>Design :</strong> Silhouette classique et polyvalente qui s\'associe bien avec des looks à la fois décontractés et formels.</li>\n<li><strong>Personnalisation :</strong> Options de personnalisation et emballage cadeau disponibles pour rendre cette pièce unique.</li>\n</ul>\n<h2>Pourquoi vous allez l\'aimer</h2>\n<ul>\n<li>Combine l\'éclat doux d\'une perle avec le scintillement net des diamants pour une esthétique équilibrée et élégante.</li>\n<li>Design intemporel qui passe sans effort du jour à la nuit.</li>\n<li>Fait un cadeau significatif—idéal pour les anniversaires, les anniversaires de mariage, les demoiselles d\'honneur ou les moments marquants.</li>\n</ul>\n<h2>Détails du produit &amp; entretien</h2>\n<ul>\n<li><strong>Taille &amp; personnalisation :</strong> Disponible en tailles de bague standard ; gravure et taille sur mesure proposées — veuillez prévoir un délai de production supplémentaire pour les commandes personnalisées.</li>\n<li><strong>Instructions d\'entretien :</strong> Évitez l\'exposition à des produits chimiques agressifs, des parfums et de l\'eau chlorée. Nettoyez délicatement avec un chiffon doux et faites inspecter les montures périodiquement par un bijoutier.</li>\n<li><strong>Stockage :</strong> Conservez séparément dans une pochette douce ou une boîte à bijoux pour éviter les rayures et préserver l\'éclat de la perle.</li>\n</ul>\n<h2>Prêt à offrir</h2>\n<p>Chaque bague peut être emballée avec un emballage cadeau premium et un message personnalisé sur demande—parfait pour offrir directement de notre studio à votre destinataire.</p>\n<p>Choisissez une pièce qui allie charme classique et savoir-faire moderne. Ajoutez une personnalisation ou un emballage cadeau lors du passage à la caisse pour créer un souvenir vraiment spécial.</p>\n</div>', 'Bague 14K avec perle cultivée et diamants', 'Bague en or 14K avec perle de culture et accents en diamant.', 'ai_translated', '2026-08-10 16:40:35', '2026-08-04 16:33:14', '2026-08-10 16:40:46');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (46, 6, 3, 'Bague en rubis et diamants avec bande en or 14K - Taille 6', 'Exemple où l\'article n\'avait qu\'une seule taille disponible mais est défini comme une variante afin d\'apparaître dans la recherche avancée des filtres.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bagues en Rubis et Diamants avec un Anneau en Or 14K</h2>\n<p>Élevez n\'importe quelle occasion avec cette bague raffinée en rubis et diamants, où le design intemporel rencontre un savoir-faire réfléchi. Un rubis vibrant occupe le devant de la scène dans un sertissage sécurisé en or 14K à quatre griffes, flanqué de pierres latérales en diamants serties en channel pour une brillance accrue et un profil épuré. Le résultat est une pièce classique et polyvalente qui se lit aussi bien comme une élégante bague de tous les jours que comme un cadeau mémorable pour un moment marquant.</p>\n<ul>\n<li><strong>Pierre centrale :</strong> Rubis vif serti dans un sertissage traditionnel à quatre griffes pour maximiser la lumière et la couleur.</li>\n<li><strong>Pierres d\'accent :</strong> Diamants sertis en channel le long des épaules offrant une brillance durable avec un profil bas pour un port confortable.</li>\n<li><strong>Métal :</strong> Anneau en or 14K avec une finition polie pour une élégance durable.</li>\n<li><strong>Design :</strong> Silhouette classique qui équilibre une couleur audacieuse avec des détails raffinés &mdash; idéal comme déclaration autonome ou empilé avec d\'autres bagues.</li>\n</ul>\n<h3>Pourquoi vous allez l\'aimer</h3>\n<p>Cette bague mélange la chaleur riche du rubis avec le feu éclatant des diamants pour un look à la fois luxueux et portable. Le sertissage central à quatre griffes met en valeur le rubis tout en le protégeant de l\'usure quotidienne, et les diamants sertis en channel offrent une brillance sécurisée et à profil bas qui ne s\'accroche pas. Des proportions réfléchies font de cette pièce un accessoire confortable que vous porterez souvent.</p>\n<h3>Détails du produit &amp; tailles</h3>\n<ul>\n<li>Métal : Or 14K</li>\n<li>Style de sertissage : Centre à quatre griffes avec épaules en diamants sertis en channel</li>\n<li>Finition : Haute brillance</li>\n<li>Taille : Cet article est proposé dans une seule taille. Il est répertorié comme une variante dans le catalogue pour apparaître dans les recherches filtrées avancées &mdash; veuillez noter la taille unique disponible lors de la commande.</li>\n</ul>\n<p><strong>Besoin d\'une taille différente ?</strong> Contactez-nous pour discuter des options de taille personnalisée ou de redimensionnement. Nous sommes heureux de répondre à des tailles supplémentaires ou de fabriquer cette bague selon vos spécifications.</p>\n<h3>Entretien &amp; maintenance</h3>\n<ul>\n<li>Pour préserver les pierres et le métal, retirez la bague pour des activités qui pourraient causer des impacts ou une exposition à des produits chimiques agressifs.</li>\n<li>Nettoyez délicatement avec de l\'eau tiède, du savon doux et une brosse douce ; séchez avec un chiffon sans peluche.</li>\n<li>Faites vérifier périodiquement les griffes et les sertissages par un professionnel pour garantir la sécurité à long terme.</li>\n</ul>\n<p>Chaque pierre précieuse est unique, donc la couleur et le caractère individuels peuvent varier légèrement par rapport aux photos. Pour des questions concernant cette pièce, des demandes personnalisées ou des délais, veuillez contacter notre équipe de service client &mdash; nous sommes là pour vous aider à la rendre parfaite.</p>\n<p><strong>Ajoutez cette bague en rubis et diamants à votre collection pour une déclaration intemporelle et élégante qui sera chérie pendant des années à venir.</strong></p>\n</div>\n<p>&nbsp;</p>', 'Bague en rubis et diamant avec bande en or 14K', 'Bague élégante en rubis et diamants en or 14 carats. Joaillerie fine de luxe.', 'ai_translated', '2026-08-10 16:40:46', '2026-08-04 16:33:18', '2026-08-10 16:40:59');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (47, 10, 3, 'Bracelet en diamant de 2 carats en or blanc 14k ou 24k', 'Disposition d\'échantillon d\'article avec vidéo intégrée ci-dessous. Idéal pour montrer les caractéristiques de l\'article, etc.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bracelet en diamant de 2 carats en or blanc 14K ou 24K</h2>\n<p>Élevez n\'importe quel look avec ce bracelet en diamant de 2,00 carats au poids total, exquisément conçu. Des diamants taillés en brillant sont sertis dans un design raffiné et discret qui capte la lumière à chaque mouvement&mdash;parfait pour une élégance quotidienne ou des occasions spéciales. Choisissez entre un sertissage classique en or blanc 14K ou une option en or 24K de plus haute pureté pour convenir à votre style.</p>\n<h3>Caractéristiques clés</h3>\n<ul>\n<li><strong>Poids total en diamant :</strong> 2,00 carats (TW)</li>\n<li><strong>Coupe :</strong> Diamants taillés en brillant pour un maximum de brillance et d\'éclat</li>\n<li><strong>Options de métal :</strong> Or blanc 14K ou or 24K &mdash; voir la note ci-dessous concernant les finitions</li>\n<li><strong>Sertissage :</strong> Sertissages à griffes/lignes sécurisés et précis conçus pour mettre en valeur chaque pierre</li>\n<li><strong>Finition :</strong> Hautement poli pour une surface luxueuse et réfléchissante</li>\n</ul>\n<h3>Artisanat &amp; Qualité</h3>\n<p>Chaque bracelet est fabriqué à la main par des bijoutiers expérimentés utilisant des techniques éprouvées pour garantir longévité et équilibre. Les diamants sont soigneusement sélectionnés pour leur taille et leur performance optique constantes, puis sertis avec une attention méticuleuse à l\'alignement et à la symétrie. Le résultat est une rangée homogène de pierres scintillantes qui se pose confortablement sur le poignet.</p>\n<h3>Options de métal &mdash; Remarque importante</h3>\n<p>Vous pouvez choisir l\'or blanc 14K pour une finition durable et blanche brillante couramment utilisée dans les bijoux fins. Une option 24K est proposée pour ceux qui recherchent une pureté en or plus élevée ; veuillez noter que le 24K est naturellement jaune. Si vous préférez une finition blanche dans une pièce de plus haute carat, nous vous recommandons de nous contacter pour discuter du plaquage en rhodium ou d\'autres options d\'alliage afin que nous puissions répondre à vos préférences exactes.</p>\n<h3>Tailles &amp; Ajustement</h3>\n<ul>\n<li>Disponible en longueurs de bracelet standard ; des longueurs personnalisées peuvent être fabriquées sur commande pour un ajustement parfait.</li>\n<li>Conçu pour un port quotidien confortable tout en maintenant un profil sécurisé et flatteur sur le poignet.</li>\n<li>Veuillez fournir la mesure du poignet lors du passage à la caisse pour un ajustement personnalisé.</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Rangez séparément pour éviter les rayures et gardez la pièce dans sa boîte de protection lorsqu\'elle n\'est pas portée.</li>\n<li>Nettoyez délicatement avec une brosse douce et de l\'eau savonneuse tiède ; séchez soigneusement avec un chiffon doux.</li>\n<li>Pour maintenir une finition en or blanc, le plaquage en rhodium peut être rafraîchi périodiquement.</li>\n</ul>\n<h3>Ce qui est inclus</h3>\n<ul>\n<li>Le bracelet en diamant de 2,00 ct dans le métal de votre choix</li>\n<li>Boîte de présentation premium</li>\n<li>Instructions d\'entretien et informations sur la maintenance</li>\n<li>Assistance pour la certification ou l\'évaluation sur demande</li>\n</ul>\n<h3>Commande &amp; Personnalisation</h3>\n<p>Choisissez votre métal et la longueur souhaitée, ou contactez notre équipe pour des demandes personnalisées&mdash;y compris la qualité spécifique des diamants, les styles de fermoir ou le gravage. Nos bijoutiers sont disponibles pour vous guider dans le choix de la configuration parfaite.</p>\n<p><strong>Prêt à le rendre vôtre ?</strong> Sélectionnez votre métal et votre taille, puis ajoutez au panier pour sécuriser ce bracelet en diamant intemporel de 2 carats. Pour des options personnalisées ou des questions, contactez notre équipe de service client&mdash;nous vous aiderons à créer une pièce que vous chérirez pendant des années.</p>\n</div>', 'Bracelet en diamant de 2 carats en or blanc 14k ou 24k', 'Bracelet en diamant de 2 carats en or blanc 14K ou 24K. Bijoux fins.', 'ai_translated', '2026-08-10 16:40:59', '2026-08-04 16:33:22', '2026-08-10 16:41:17');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (48, 12, 3, 'Bracelet en rubis et diamants', 'Bracelet élégant en rubis et diamants en or 14k, avec des options en argent et en or rose 18k. La teinte argentée bénéficie d\'une remise sur la QTY. Affichage du total en temps réel activé en dessous du bouton ajouter au panier.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Bracelet en Rubis et Diamants &mdash; Élégance Intemporelle</h2>\n<p>Un équilibre gracieux de couleur et d\'éclat, ce Bracelet en Rubis et Diamants associe des pierres de rubis vives à des accents de diamants taillés brillants pour un look à la fois luxueux et raffiné. Conçu avec soin pour habiller les tenues quotidiennes ou compléter un look de soirée, il est disponible en trois tons de métal pour s\'adapter à votre style : ton or, ton argent et or rose.</p>\n<h3>Caractéristiques Clés</h3>\n<ul>\n<li><strong>Pierres :</strong> Pierres de rubis vives accentuées par des diamants taillés brillants pour un contraste éclatant.</li>\n<li><strong>Finitions :</strong> Choisissez parmi le ton or, le ton argent ou le ton or rose chaud pour assortir votre collection de bijoux.</li>\n<li><strong>Artisanat :</strong> Pierres soigneusement serties et fermoir sécurisé pour un port confortable au quotidien.</li>\n<li><strong>Design :</strong> Silhouette élégante et polyvalente conçue pour se superposer magnifiquement avec d\'autres bracelets ou se tenir seule comme pièce maîtresse.</li>\n<li><strong>Économies de Quantité :</strong> L\'option ton argent inclut une remise automatique sur la quantité&mdash;sélectionnez plusieurs pièces dans votre panier pour voir les économies appliquées.</li>\n</ul>\n<h3>Pourquoi Vous Allez L\'Adorer</h3>\n<p>Le rouge vif des rubis associé aux accents de diamants scintillants crée une combinaison classique et accrocheuse qui complète à la fois les tons de peau chauds et froids. Léger et raffiné, ce bracelet ajoute une touche de sophistication instantanée à un look professionnel, une tenue de cocktail ou un ensemble pour une occasion spéciale.</p>\n<h3>Tailles &amp; Ajustement</h3>\n<p>Disponible en longueurs de bracelet standard. Veuillez sélectionner votre taille préférée parmi les options sur la page du produit. Si vous n\'êtes pas sûr de la taille à choisir, mesurez votre poignet à l\'endroit où vous porteriez normalement un bracelet et ajoutez 0,5\"&ndash;1\" pour un ajustement confortable.</p>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Évitez l\'exposition à des produits chimiques agressifs, des parfums et de l\'eau chlorée pour préserver la finition.</li>\n<li>Essuyez avec un chiffon doux et sec après utilisation pour enlever les huiles et restaurer l\'éclat.</li>\n<li>Rangez séparément dans une pochette douce ou une boîte à bijoux pour éviter les rayures.</li>\n</ul>\n<h3>Parfait Pour</h3>\n<p>Cadeaux d\'anniversaire ou d\'anniversaire de mariage, bijoux de mariée ou de demoiselle d\'honneur, célébrations marquantes, ou simplement pour se faire plaisir avec une pièce raffinée au quotidien. Chaque bracelet constitue un cadeau élégant et réfléchi et s\'associe magnifiquement avec des boucles d\'oreilles assorties ou un pendentif.</p>\n<h3>Informations Supplémentaires</h3>\n<ul>\n<li>Finitions disponibles : ton or, ton argent, or rose.</li>\n<li>Le ton argent est éligible à une remise automatique sur la quantité&mdash;ajoutez plusieurs à votre panier pour bénéficier du prix réduit.</li>\n<li>Pour des demandes personnalisées ou des commandes en gros, veuillez contacter notre équipe de service client.</li>\n</ul>\n<p>Ajoutez une touche de luxe intemporel à votre collection de bijoux. Choisissez votre ton de métal et votre taille, puis cliquez sur &ldquo;Ajouter au Panier&rdquo; pour commander.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Bracelet en rubis et diamants', 'Bracelet en rubis et diamants. Bijoux fins élégants.', 'ai_translated', '2026-08-10 16:41:17', '2026-08-04 16:33:34', '2026-08-10 16:41:30');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (49, 11, 3, 'Bracelet en diamant certifié GIA de 5 carats en or 18 carats', 'Échantillon avec affichage d\'image alternatif (côté droit) plus une option de personnalisation pour une vente incitative.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<div class=\"intro\">\n<h2>Bracelet en diamant certifié GIA de 5 carats en or 18K</h2>\n<p>Un bracelet d\'affirmation raffiné conçu pour les collectionneurs et les connaisseurs. Ce luxueux bracelet en or 18K présente un total de 5,00 carats de diamants taillés en brillant certifiés GIA &mdash; méticuleusement assortis et sertis pour maximiser l\'éclat, la symétrie et le port.</p>\n</div>\n<div class=\"two-column\">\n<div class=\"left-column\">\n<h3>Pourquoi vous allez l\'aimer</h3>\n<ul>\n<li>Design intemporel qui passe sans effort du jour à la nuit.</li>\n<li>Diamants taillés en brillant, sertis à la main avec un poids total de 5,00 carats.</li>\n<li>Fabriqué en or massif 18K pour une durabilité et une patine luxueuse.</li>\n<li>La certification GIA fournit une vérification indépendante de la qualité des diamants.</li>\n<li>Options personnalisables vous permettant d\'adapter le métal, la finition et les détails pour un héritage véritablement personnel.</li>\n</ul>\n<h3>Détails du produit</h3>\n<ul>\n<li>Métal : Or massif 18K (disponible en jaune, blanc ou rose sur personnalisation)</li>\n<li>Poids des diamants : 5,00 carats au total (tcw)</li>\n<li>Coupe de diamant : Taille brillante (certifiée GIA)</li>\n<li>Fermoir : Fermoir à boîte sécurisé ou fermoir homard avec verrou de sécurité (sélectionnable)</li>\n<li>Longueurs standard : 6,5\", 7\", 7,5\" &mdash; longueurs personnalisées disponibles sur demande</li>\n<li>Finition : Poli brillant (finishes mate ou satin disponibles en option)</li>\n</ul>\n<h3>Certification &amp; provenance</h3>\n<p>Tous les diamants sont accompagnés d\'une certification GIA. Des certificats ou une évaluation complète peuvent être fournis avec l\'achat ou sur demande pour garantir la provenance et la valeur de remplacement au détail à des fins d\'assurance.</p>\n<h3>Options de personnalisation &amp; vente additionnelle</h3>\n<p>Faites de ce bracelet le vôtre. Choisissez parmi les options ci-dessous ou cliquez <a class=\"cta\" href=\"#customize\">Personnaliser ce bijou</a> pour commencer une consultation privée.</p>\n<ul>\n<li><strong>Sélection de métal :</strong> Améliorez entre l\'or 18K jaune, blanc ou rose.</li>\n<li><strong>Améliorations des diamants :</strong> Option d\'améliorer vers des niveaux de couleur/clarité supérieurs ou d\'inclure des pierres centrales plus grandes pour un look plus audacieux.</li>\n<li><strong>Finition &amp; fermoir :</strong> Finition satinée, accents micro-pavé, ou un fermoir de sécurité amélioré pour une sécurité accrue.</li>\n<li><strong>Personnalisation :</strong> Gravure sur le fermoir ou longueur personnalisée pour un ajustement parfait.</li>\n<li><strong>Présentation &amp; protection :</strong> Ajoutez une boîte de présentation premium, une évaluation GIA détaillée, et un service d\'assurance ou de garantie prolongée en option.</li>\n</ul>\n<h3>Entretien &amp; service</h3>\n<p>Pour maintenir l\'éclat, nettoyez délicatement avec une brosse douce et un nettoyant pour bijoux doux. Évitez les produits chimiques agressifs et retirez avant une activité physique intense. Nous offrons un nettoyage et une inspection à vie gratuits lors de l\'achat avec un plan de soins prolongé.</p>\n<h3>Expédition &amp; retours</h3>\n<p>Expédition sécurisée et assurée disponible dans le monde entier. En raison de la nature personnalisée et de la valeur de ce bijou, les retours et échanges sont traités au cas par cas &mdash; veuillez consulter notre politique de retour complète ou contacter notre concierge pour assistance.</p>\n<p class=\"final-cta\">Pour personnaliser ce bracelet ou demander une documentation GIA et des prix d\'évaluation, cliquez <a class=\"cta\" href=\"#customize\">Personnaliser / Demander une évaluation</a> ou contactez notre équipe de services à la clientèle pour une consultation privée.</p>\n</div>\n<div class=\"right-column\" aria-label=\"Affichage d\'image alternatif (côté droit)\">\n<div class=\"image-gallery\"><!-- Replace src values with actual product image URLs --> <img src=\"/images/18k-5ct-bracelet-main.jpg\" alt=\"Bracelet en or 18K avec diamants certifiés GIA de 5 carats &mdash; vue principale\"> <img src=\"/images/18k-5ct-bracelet-side.jpg\" alt=\"Profil latéral montrant le sertissage des diamants et le fermoir\"> <img src=\"/images/18k-5ct-bracelet-wrist.jpg\" alt=\"Bracelet au poignet &mdash; vue d\'échelle et de port\"> <img src=\"/images/18k-5ct-bracelet-box.jpg\" alt=\"Boîte de présentation premium et certificat GIA\"></div>\n<p class=\"image-note\">Affichage d\'image alternatif (côté droit) &mdash; cliquez sur les images pour agrandir.</p>\n</div>\n</div>\n<div class=\"notes\">\n<h4>Important</h4>\n<p>Parce que chaque bracelet est fabriqué à la main et peut être personnalisé, l\'arrangement final des diamants et les spécifications exactes peuvent varier légèrement par rapport aux images de la galerie. Les numéros de rapport GIA exacts et la documentation d\'évaluation seront fournis avec chaque vente.</p>\n</div>\n</div>\n</div>', 'Bracelet en diamant certifié GIA de 5 carats en or 18 carats', 'Bracelet en diamant certifié GIA de 5 carats en or 18K. Joaillerie fine.', 'ai_translated', '2026-08-10 16:41:30', '2026-08-04 16:33:39', '2026-08-10 16:42:01');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (50, 13, 3, 'Bracelet en saphir, rubis et émeraude', 'Démontre la personnalisation et la personnalisation du produit.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Bracelet en Saphir, Rubis et Émeraude</h2>\n<p>Faites une impression durable avec ce magnifique bracelet multistone, où des saphirs bleus vifs, des rubis rouges profonds et des émeraudes vertes luxuriantes sont habilement sertis dans de l\'or fin. Les couleurs riches et contrastées créent une pièce d\'exception intemporelle mais audacieuse qui rehausse les looks de jour comme de nuit.</p>\n<h3>Caractéristiques Clés</h3>\n<ul>\n<li><strong>Gemmes précieuses :</strong> Saphirs vifs, rubis intenses et émeraudes vibrantes sélectionnés pour leur couleur et leur éclat.</li>\n<li><strong>Sertissage en or fin :</strong> Fabriqué avec expertise en or fin pour des sertissages sécurisés et une finition chaude et luxueuse.</li>\n<li><strong>Finition artisanale :</strong> Sertissage de pierres de précision et détails polis pour une pièce raffinée et durable.</li>\n<li><strong>Design polyvalent :</strong> Assez élégant pour des occasions formelles mais assez audacieux pour rehausser les tenues quotidiennes.</li>\n</ul>\n<h3>Pourquoi Vous Allez L\'Adorer</h3>\n<p>Ce bracelet allie la beauté classique des gemmes à un design contemporain. Le trio contrasté de saphirs, rubis et émeraudes crée une profondeur visuelle et un mouvement—parfait pour quiconque apprécie la couleur, le savoir-faire et une pièce qui peut être transmise en héritage. C\'est un choix idéal pour les anniversaires, les célébrations marquantes ou comme ajout remarquable à votre collection de bijoux personnelle.</p>\n<h3>Suggestions de Style</h3>\n<ul>\n<li>Portez-le seul comme point focal avec une simple robe de soirée ou un blazer ajusté.</li>\n<li>Superposez-le avec des bracelets en or fins pour un look empilé moderne.</li>\n<li>Associez-le à des boucles d\'oreilles en gemmes assorties ou à un pendentif délicat pour un ensemble coordonné.</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Retirez-le avant de prendre une douche, de nager ou d\'utiliser des produits chimiques ménagers.</li>\n<li>Nettoyez délicatement avec un chiffon doux et sans peluches ; évitez les abrasifs durs et les nettoyeurs ultrasoniques pour les émeraudes, sauf avis contraire d\'un bijoutier.</li>\n<li>Rangez-le séparément dans une pochette douce ou la boîte d\'origine pour éviter les rayures.</li>\n</ul>\n</div>\n<p>&nbsp;</p>', 'Bracelet en Saphir, Rubis et Émeraude', 'Bracelet en saphir, rubis et émeraude. Bijoux fins.', 'ai_translated', '2026-08-10 16:42:01', '2026-08-04 16:33:49', '2026-08-10 16:42:11');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (51, 18, 3, 'Montre de poche vintage', 'Échantillon d\'article avec option d\'emballage cadeau activée.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<h2>Montre de Poche Vintage &mdash; Élégance Classique avec Gravure Intricate</h2>\n<p>Rendez chaque moment mémorable avec cette magnifique Montre de Poche Vintage. Fini dans un style antique et détaillé avec un boîtier en métal finement gravé, ce garde-temps allie artisanat traditionnel et design intemporel. Le cadran clair avec chiffres romains et les accents vintage subtils en font un choix exceptionnel pour les collectionneurs, les occasions spéciales ou toute personne qui apprécie un style de qualité héritage.</p>\n<h3>Caractéristiques Clés</h3>\n<ul>\n<li><strong>Boîtier gravé complexe :</strong> Une gravure ornementale détaillée donne à chaque montre un look classique distinctif.</li>\n<li><strong>Cadran avec chiffres romains :</strong> Chiffres romains élégants et faciles à lire pour une esthétique traditionnelle.</li>\n<li><strong>Construction durable :</strong> Boîtier en métal solide avec une finition antique conçu pour résister à une utilisation quotidienne tout en conservant son charme vintage.</li>\n<li><strong>Précision de la mesure du temps :</strong> Construit avec un mouvement précis pour garder l\'heure exacte pour un usage quotidien ou une exposition.</li>\n<li><strong>Style polyvalent :</strong> Assez raffiné pour des événements formels mais suffisamment robuste pour être porté comme pièce maîtresse au quotidien.</li>\n</ul>\n<h3>Pourquoi Vous Allez l\'Aimer</h3>\n<p>Cette Montre de Poche Vintage offre l\'apparence et la sensation d\'un véritable héritage sans sacrifier la praticité. Son boîtier gravé et son cadran classique fournissent un sens indéniable d\'histoire et de caractère, en faisant un accessoire remarquable pour des costumes, des vestes ou une exposition dans une collection. C\'est un cadeau idéal pour les passionnés de vintage, les témoins de mariage ou toute personne qui privilégie les accessoires intemporels.</p>\n<h3>Parfait Pour</h3>\n<ul>\n<li>Les collectionneurs à la recherche d\'un ajout classique à leur collection</li>\n<li>Cadeaux pour les anniversaires, les anniversaires de mariage, les mariages et les remises de diplômes</li>\n<li>Événements formels, reconstitutions ou rassemblements thématiques</li>\n<li>Les porteurs quotidiens qui apprécient le style vintage</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Essuyez avec un chiffon doux et sec pour enlever les empreintes digitales et la poussière.</li>\n<li>Éloignez des champs magnétiques forts et d\'une exposition prolongée à l\'humidité.</li>\n<li>Rangez dans un endroit sec lorsqu\'il n\'est pas utilisé pour préserver la finition et le mouvement.</li>\n<li>Faites entretenir la montre par un professionnel si vous remarquez un temps irrégulier.</li>\n</ul>\n<h3>Ce Qui Est Inclus</h3>\n<ul>\n<li>Montre de Poche Vintage (boîtier gravé avec cadran à chiffres romains)</li>\n<li>Carte d\'instructions avec des conseils d\'entretien de base</li>\n</ul>\n<p>Cette montre de poche inspirée du vintage allie forme et fonction pour créer une impression durable &mdash; qu\'elle soit ajoutée à une collection ou offerte comme un cadeau significatif. Ajoutez-la à votre panier pour posséder une pièce qui ressemble et se sent comme un classique chéri.</p>\n</div>\n<p>&nbsp;</p>', 'Montre de poche vintage', 'Montre de poche vintage. Boîtier gravé classique.', 'ai_translated', '2026-08-10 16:42:11', '2026-08-04 16:34:00', '2026-08-10 16:42:22');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (52, 19, 3, 'Montre de poignet tendance', 'Échantillon de montre avec des sélecteurs de couleur qui ne sont pas des pilules mais des groupes radio de variantes. Utilise également un message de rupture de stock non par défaut.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div class=\"product-description\">\n<h2>Montre de poignet tendance</h2>\n<p>Une montre moderne et élégante conçue pour un style quotidien. La montre de poignet tendance associe un boîtier en acier inoxydable poli à des bracelets interchangeables, vous permettant de passer d\'un look décontracté à un look raffiné en quelques secondes. Disponible avec des bracelets noirs et marron &mdash; c\'est un moyen sans effort d\'élever n\'importe quelle tenue.</p>\n<h3>Pourquoi vous allez l\'aimer</h3>\n<ul>\n<li><strong>Design contemporain :</strong> Un cadran épuré et un profil mince offrent une esthétique moderne et polyvalente qui passe du jour à la nuit.</li>\n<li><strong>Construction durable :</strong> Le boîtier en acier inoxydable offre robustesse et finition premium qui résiste à l\'usure quotidienne.</li>\n<li><strong>Bracelets interchangeables :</strong> Changez rapidement de bracelet pour personnaliser votre look sans outils (style à libération rapide).</li>\n<li><strong>Options de couleurs classiques :</strong> Choisissez le noir ou le marron pour correspondre à votre style personnel ou à votre garde-robe.</li>\n</ul>\n<h3>Détails du produit</h3>\n<ul>\n<li>Matériau du boîtier : Acier inoxydable</li>\n<li>Bracelet : Interchangeable (inclus)</li>\n<li>Couleurs disponibles : Noir, Marron, Blanc</li>\n<li>Style : Moderne, unisexe</li>\n</ul>\n<h3>Comment choisir votre couleur</h3>\n<p>Les options de couleur sont disponibles sous forme de groupes de boutons radio sur la page du produit. Sélectionnez votre couleur préférée en choisissant le bouton radio correspondant sous \"Couleur\". (Remarque : celles-ci sont présentées sous forme d\'options radio plutôt que de nuanciers de style pilule.)</p>\n<h3>Tailles &amp; ajustement</h3>\n<p>Le bracelet ajustable convient à la plupart des tailles de poignet. Pour un ajustement sur mesure, retirez des maillons ou ajustez la boucle si nécessaire. Si vous avez besoin de mesures précises ou d\'aide pour trouver la bonne taille, consultez notre guide des tailles ou contactez le service client.</p>\n<h3>Entretien &amp; maintenance</h3>\n<ul>\n<li>Évitez l\'exposition prolongée à l\'humidité et aux températures extrêmes.</li>\n<li>Essuyez le boîtier et le bracelet avec un chiffon doux et sec pour enlever la saleté et les huiles.</li>\n<li>Rangez dans un endroit sec lorsque vous ne l\'utilisez pas pour préserver la finition et la longévité.</li>\n</ul>\n<h3>Ce qui est inclus</h3>\n<ul>\n<li>Montre de poignet tendance (boîtier + bracelet principal)</li>\n<li>Carte utilisateur avec des instructions d\'entretien de base</li>\n</ul>\n<p>Conçue avec soin pour la polyvalence et un usage quotidien, la montre de poignet tendance est un accessoire raffiné qui s\'adapte à votre style. Sélectionnez votre couleur via le groupe de boutons radio et créez le look que vous souhaitez &mdash; sans effort.</p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Montre de poignet tendance', 'Montre-bracelet tendance. Plusieurs options de bracelet.', 'ai_translated', '2026-08-10 16:42:22', '2026-08-04 16:34:11', '2026-08-10 16:42:34');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (53, 22, 3, 'Montre de poche moderne', 'Échantillon d\'article en rupture de stock avec un formulaire de contact en dessous de l\'article pour demander plus d\'informations.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 14pt;\"><strong>Nous nous excusons, mais cet article n\'est actuellement pas disponible. Veuillez soumettre le formulaire ci-dessous pour que nous vous contactions lorsqu\'il sera de nouveau en stock.</strong></span></p>\n<p style=\"line-height: 1.4;\"><span style=\"font-size: 18pt;\"><strong>[cms-form id=1]</strong></span></p>\n</div>\n</div>\n<p>&nbsp;</p>', 'Montre de poche moderne', 'Montre de poche moderne. Design minimaliste élégant.', 'ai_translated', '2026-08-10 16:42:34', '2026-08-04 16:34:16', '2026-08-10 16:42:38');
SQL
);

        // Table: product_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_translations` (`id`, `product_id`, `language_id`, `title`, `short_description`, `long_description`, `meta_title`, `meta_description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (54, 23, 3, 'Montre de poignet moderne', 'Exemple d\'article en rupture de stock.', '<div class=\"prose prose-slate max-w-none\" style=\"max-width: none !important; width: 100%;\">\n<div>\n<h2>Montre de poignet moderne &mdash; Design minimaliste, à porter tous les jours</h2>\n<p>Une montre de poignet moderne, magnifiquement équilibrée, conçue pour les personnes qui apprécient les lignes épurées et le style sans effort. Son profil mince et son cadran dégagé en font un accessoire polyvalent pour des looks à la fois décontractés et formels. Fabriquée avec un bracelet en cuir véritable et un mouvement de précision, cette montre unisexe offre un confort durable et une précision de temps fiable.</p>\n<h3>Caractéristiques clés</h3>\n<ul>\n<li><strong>Esthétique minimaliste :</strong> Cadran épuré avec des indices subtils pour un look contemporain discret.</li>\n<li><strong>Boîtier mince :</strong> Design à profil bas qui se glisse confortablement sous les manches et les vestes.</li>\n<li><strong>Bracelet en cuir véritable :</strong> Bracelet doux et durable qui s\'adapte à votre poignet au fil du temps.</li>\n<li><strong>Mouvement fiable :</strong> Mouvement à quartz japonais pour une précision de temps.</li>\n<li><strong>Durabilité au quotidien :</strong> Verre minéral revêtu de saphir résistant aux rayures ; résistant à l\'eau pour les éclaboussures quotidiennes.</li>\n<li><strong>Taille unisexe :</strong> Conçu pour convenir à la fois aux hommes et aux femmes avec une taille de boîtier polyvalente et un bracelet ajustable.</li>\n</ul>\n<h3>Spécifications</h3>\n<ul>\n<li>Diamètre du boîtier : 38 mm (environ)</li>\n<li>Épaisseur du boîtier : 6&ndash;8 mm (profil mince)</li>\n<li>Mouvement : Quartz japonais</li>\n<li>Verre : Verre minéral revêtu de saphir</li>\n<li>Bracelet : Cuir véritable avec boucle ajustable</li>\n<li>Résistance à l\'eau : 3 ATM (résistant aux éclaboussures ; non adapté à la baignade)</li>\n<li>Genre : Unisexe</li>\n</ul>\n<h3>Ce qui est inclus</h3>\n<ul>\n<li>Montre de poignet moderne (bracelet affiché)</li>\n<li>Boîte de présentation premium</li>\n<li>Manuel d\'instructions et carte de garantie</li>\n</ul>\n<h3>Entretien &amp; Maintenance</h3>\n<ul>\n<li>Évitez une exposition prolongée à l\'humidité ; retirez la montre avant de prendre une douche ou de nager.</li>\n<li>Nettoyez le boîtier et le verre avec un chiffon doux et sec. Entretenez le bracelet en cuir de temps en temps avec des produits d\'entretien pour cuir.</li>\n<li>Faites remplacer la batterie par un technicien horloger qualifié pour garantir que l\'étanchéité est maintenue.</li>\n</ul>\n<h3>Garantie &amp; Support</h3>\n<p>Cette montre est couverte par une garantie limitée de 24 mois contre les défauts de fabrication. Pour un service de garantie ou un support produit, veuillez contacter notre équipe de service client avec votre numéro de commande et votre carte de garantie.</p>\n<h3>Disponibilité</h3>\n<p><strong>Rupture de stock.</strong> Nous sommes désolés&mdash;cet article est actuellement indisponible. Cliquez sur le bouton \"Prévenez-moi\" sur la page du produit pour recevoir un e-mail dès qu\'il est de nouveau en stock, ou contactez le support client pour obtenir de l\'aide avec des styles similaires et des options de précommande.</p>\n<p>Conçue pour être facilement portable et élégamment sobre, la montre de poignet moderne est le compagnon idéal au quotidien ou un cadeau réfléchi pour quiconque apprécie le design intemporel et minimaliste.</p>\n</div>\n</div>', 'Montre-bracelet moderne', 'Montre-bracelet moderne. Design minimaliste pour hommes et femmes.', 'ai_translated', '2026-08-10 16:42:38', '2026-08-04 16:34:19', '2026-08-10 16:42:54');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (1, 1, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:16:13', '2026-08-06 16:18:36');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (2, 2, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:16:15', '2026-08-10 17:25:09');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (3, 3, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"5\":\"5\"}', '2026-08-04 17:16:18', '2026-08-06 16:18:42');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (10, 10, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:16:35', '2026-08-04 17:24:14');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (17, 17, 3, 'Ajouter un emballage cadeau', 'Emballage Cadeau', 'Entrez ce que vous souhaitez dire sur la carte incluse ou entrez \"Pas de carte\" si aucune carte ne doit être incluse.', '{\"Ring Size\":\"Taille de bague\",\"5\":\"5\"}', '2026-08-04 17:16:50', '2026-08-06 16:19:03');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (18, 18, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"6\":\"6\"}', '2026-08-04 17:16:54', '2026-08-10 17:25:42');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (19, 19, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:16:56', '2026-08-10 17:25:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (20, 20, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:16:59', '2026-08-10 17:25:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (21, 21, 3, 'Ajouter l\'inscription des initiales', 'Initiales', 'Ajoutez les initiales à inscrire à l\'intérieur du bracelet (max 6 caractères)', NULL, '2026-08-04 17:17:01', '2026-08-10 14:19:22');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (22, 22, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravure, les détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:03', '2026-08-10 17:25:52');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (23, 23, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:06', '2026-08-06 16:19:25');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (24, 24, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:09', '2026-08-04 17:25:09');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (25, 126, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:10', '2026-08-10 14:19:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (26, 127, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:13', '2026-08-04 17:25:20');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (27, 27, 3, 'Gravure', 'Entrée de gravure :', 'Entrez les initiales ou le message à graver à l\'intérieur du bracelet.', NULL, '2026-08-04 17:17:14', '2026-08-04 17:17:14');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (28, 28, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:16', '2026-08-04 17:17:16');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (29, 29, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:17:18', '2026-08-04 17:17:18');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (30, 31, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:17:22', '2026-08-04 17:25:34');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (31, 32, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:17:28', '2026-08-04 17:17:28');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (32, 33, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:17:32', '2026-08-10 17:26:18');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (33, 34, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:17:37', '2026-08-10 17:26:22');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (34, 35, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:17:42', '2026-08-04 17:26:06');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (35, 36, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Burgungy\":\"Bourgogne\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:17:46', '2026-08-04 17:17:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (36, 37, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Burgungy\":\"Bourgogne\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:17:51', '2026-08-10 17:26:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (37, 38, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Burgungy\":\"Bourgogne\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:17:56', '2026-08-04 17:26:20');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (38, 39, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Burgungy\":\"Bourgogne\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:18:01', '2026-08-10 17:26:42');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (39, 40, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravure, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Burgungy\":\"Bourgogne\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:18:06', '2026-08-10 17:26:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (40, 41, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"White\":\"Blanc\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:18:13', '2026-08-10 17:26:49');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (41, 42, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"White\":\"Blanc\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:18:18', '2026-08-04 17:26:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (42, 43, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"White\":\"Blanc\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:18:22', '2026-08-10 14:20:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (43, 44, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"White\":\"Blanc\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:18:26', '2026-08-10 14:20:51');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (44, 45, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"White\":\"Blanc\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:18:31', '2026-08-04 17:18:31');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (45, 46, 3, 'Emballage cadeau :', 'Message cadeau', 'Entrez le message pour la carte-cadeau ici.', NULL, '2026-08-04 17:18:34', '2026-08-10 14:20:58');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (46, 48, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\"}', '2026-08-04 17:18:38', '2026-08-10 14:21:03');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (47, 130, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Black\":\"Noir\"}', '2026-08-04 17:18:42', '2026-08-10 17:27:14');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (48, 50, 3, 'Option de gravure :', 'Entrez le texte de gravure optionnel ci-dessous :', 'Entrez des noms ou des initiales pour l\'option de gravure. Jusqu\'à deux ensembles d\'initiales (1 pour chaque stylo) ou 1 ligne par stylo.', NULL, '2026-08-04 17:18:44', '2026-08-04 17:18:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (49, 51, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravure, les détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:18:47', '2026-08-10 17:27:18');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (50, 52, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:18:49', '2026-08-04 17:27:22');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (51, 53, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:18:51', '2026-08-06 16:21:29');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (52, 60, 3, NULL, NULL, 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:18:54', '2026-08-04 17:18:54');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (53, 61, 3, NULL, NULL, 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Gray\":\"Gris\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:18:57', '2026-08-04 17:18:57');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (54, 62, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Green\":\"Vert\",\"Size\":\"Taille\",\"Small\":\"Petit\"}', '2026-08-04 17:19:02', '2026-08-04 17:19:02');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (55, 63, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Small\":\"Petit\",\"Color\":\"Couleur\",\"Navy Blue\":\"Bleu marine\"}', '2026-08-04 17:19:06', '2026-08-10 17:27:40');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (56, 64, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Small\":\"Petit\",\"Color\":\"Couleur\",\"Orange\":\"Orange\"}', '2026-08-04 17:19:10', '2026-08-04 17:27:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (57, 65, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Small\":\"Petit\",\"Color\":\"Couleur\",\"Royal Blue\":\"Bleu royal\"}', '2026-08-04 17:19:15', '2026-08-10 17:27:49');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (58, 85, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:19:20', '2026-08-10 17:27:53');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (59, 86, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:19:24', '2026-08-10 17:27:57');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (60, 87, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:19:28', '2026-08-04 17:19:28');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (61, 88, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Brown\":\"Marron\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:19:32', '2026-08-10 14:22:02');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (62, 89, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Gray\":\"Gris\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:19:37', '2026-08-10 14:22:07');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (63, 90, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Gray\":\"Gris\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:19:41', '2026-08-10 17:28:12');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (64, 91, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Gray\":\"Gris\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:19:46', '2026-08-10 17:28:16');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (65, 92, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Gray\":\"Gris\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:19:50', '2026-08-06 16:22:47');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (66, 93, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Green\":\"Vert\",\"Size\":\"Taille\",\"Medium\":\"Moyen\"}', '2026-08-04 17:19:54', '2026-08-06 16:22:52');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (67, 94, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Green\":\"Vert\",\"Size\":\"Taille\",\"Large\":\"Grand\"}', '2026-08-04 17:20:01', '2026-08-04 17:20:01');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (68, 95, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Green\":\"Vert\",\"Size\":\"Taille\",\"XL\":\"XL\"}', '2026-08-04 17:20:05', '2026-08-10 17:28:32');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (69, 96, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Color\":\"Couleur\",\"Green\":\"Vert\",\"Size\":\"Taille\",\"XXL\":\"XXL\"}', '2026-08-04 17:20:11', '2026-08-04 17:20:11');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (70, 97, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Medium\":\"Moyen\",\"Color\":\"Couleur\",\"Navy Blue\":\"Bleu Marine\"}', '2026-08-04 17:20:16', '2026-08-10 14:22:48');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (71, 98, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Large\":\"Grand\",\"Color\":\"Couleur\",\"Navy Blue\":\"Bleu marine\"}', '2026-08-04 17:20:20', '2026-08-10 17:28:45');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (72, 99, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XL\":\"XL\",\"Color\":\"Couleur\",\"Navy Blue\":\"Bleu marine\"}', '2026-08-04 17:20:25', '2026-08-06 16:23:26');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (73, 100, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XXL\":\"XXL\",\"Color\":\"Couleur\",\"Navy Blue\":\"Bleu Marine\"}', '2026-08-04 17:20:30', '2026-08-06 16:23:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (74, 101, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Medium\":\"Moyen\",\"Color\":\"Couleur\",\"Orange\":\"Orange\"}', '2026-08-04 17:20:35', '2026-08-10 17:28:57');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (75, 102, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Large\":\"Grand\",\"Color\":\"Couleur\",\"Orange\":\"Orange\"}', '2026-08-04 17:20:39', '2026-08-04 17:29:19');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (76, 103, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XL\":\"XL\",\"Color\":\"Couleur\",\"Orange\":\"Orange\"}', '2026-08-04 17:20:44', '2026-08-04 17:20:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (77, 104, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XXL\":\"XXL\",\"Color\":\"Couleur\",\"Orange\":\"Orange\"}', '2026-08-04 17:20:48', '2026-08-04 17:20:48');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (78, 106, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Medium\":\"Moyen\",\"Color\":\"Couleur\",\"Royal Blue\":\"Bleu royal\"}', '2026-08-04 17:20:53', '2026-08-04 17:20:53');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (79, 107, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"Large\":\"Grand\",\"Color\":\"Couleur\",\"Royal Blue\":\"Bleu royal\"}', '2026-08-04 17:20:57', '2026-08-04 17:29:37');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (80, 108, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XL\":\"XL\",\"Color\":\"Couleur\",\"Royal Blue\":\"Bleu royal\"}', '2026-08-04 17:21:02', '2026-08-10 14:23:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (81, 109, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Size\":\"Taille\",\"XXL\":\"XXL\",\"Color\":\"Couleur\",\"Royal Blue\":\"Bleu royal\"}', '2026-08-04 17:21:08', '2026-08-04 17:21:08');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (82, 125, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:21:10', '2026-08-04 17:21:10');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (83, 79, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:21:12', '2026-08-10 17:29:29');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (84, 80, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:21:15', '2026-08-04 17:21:15');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (85, 83, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-04 17:21:18', '2026-08-10 14:23:52');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (86, 1, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 18:59:18', '2026-08-10 16:29:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (87, 2, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 18:59:21', '2026-08-10 16:29:35');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (88, 3, 2, 'Añadir envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"5\":\"5\"}', '2026-08-05 18:59:23', '2026-08-10 16:29:38');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (95, 10, 2, 'Añadir envoltura de regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 18:59:38', '2026-08-10 16:29:52');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (102, 17, 2, 'Agregar envoltura de regalo', 'Envoltura de Regalo', 'Ingresa lo que deseas decir en la tarjeta incluida o ingresa \"Sin tarjeta\" si no se debe incluir ninguna tarjeta.', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"5\":\"5\"}', '2026-08-05 18:59:53', '2026-08-10 16:29:56');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (103, 18, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"6\":\"6\"}', '2026-08-05 18:59:55', '2026-08-10 16:30:05');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (104, 19, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 18:59:56', '2026-08-10 16:30:07');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (105, 20, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 18:59:58', '2026-08-10 16:30:09');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (106, 21, 2, 'Agregar Inscripción de Iniciales', 'Iniciales', 'Agrega las iniciales que se inscribirán en el interior de la pulsera (máx. 6 caracteres)', NULL, '2026-08-05 19:00:01', '2026-08-10 16:30:11');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (107, 22, 2, 'Añadir envoltura de regalo / Personalización', 'Detalles de personalización / Mensaje de regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:03', '2026-08-10 16:30:13');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (108, 23, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:05', '2026-08-06 15:20:49');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (109, 24, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:07', '2026-08-10 16:30:17');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (110, 126, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:09', '2026-08-10 16:30:19');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (111, 127, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:11', '2026-08-10 16:30:20');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (112, 27, 2, 'Grabado', 'Entrada de Grabado:', 'Ingrese las iniciales o el mensaje que se grabará en el interior de la pulsera.', NULL, '2026-08-05 19:00:13', '2026-08-05 19:00:13');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (113, 28, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:15', '2026-08-10 16:30:25');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (114, 29, 2, 'Agregar envoltura de regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:00:17', '2026-08-10 16:30:27');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (115, 31, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:00:21', '2026-08-10 16:30:32');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (116, 32, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:00:26', '2026-08-10 16:30:35');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (117, 33, 2, 'Añadir Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:00:30', '2026-08-10 16:30:40');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (118, 34, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:00:35', '2026-08-10 16:30:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (119, 35, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:00:40', '2026-08-10 13:22:42');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (120, 36, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Burgungy\":\"Borgo\\u00f1a\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:00:44', '2026-08-10 16:30:55');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (121, 37, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Burgungy\":\"Borgo\\u00f1a\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:00:48', '2026-08-10 16:30:58');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (122, 38, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Burgungy\":\"Borgo\\u00f1a\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:00:54', '2026-08-10 16:31:02');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (123, 39, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Burgungy\":\"Borgo\\u00f1a\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:00:59', '2026-08-06 15:22:12');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (124, 40, 2, 'Añadir envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Burgungy\":\"Borgo\\u00f1a\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:01:04', '2026-08-10 16:31:11');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (125, 41, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"White\":\"Blanco\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:01:09', '2026-08-10 16:31:17');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (126, 42, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"White\":\"Blanco\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:01:14', '2026-08-10 16:31:21');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (127, 43, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"White\":\"Blanco\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:01:18', '2026-08-10 16:31:25');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (128, 44, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"White\":\"Blanco\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:01:22', '2026-08-10 13:23:23');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (129, 45, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"White\":\"Blanco\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:01:32', '2026-08-10 16:31:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (130, 46, 2, 'Envoltura de regalo:', 'Mensaje de regalo', 'Ingrese el mensaje para la tarjeta de regalo aquí.', NULL, '2026-08-05 19:01:34', '2026-08-06 04:10:34');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (131, 48, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\"}', '2026-08-05 19:01:38', '2026-08-10 16:31:37');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (132, 130, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Black\":\"Negro\"}', '2026-08-05 19:01:41', '2026-08-10 16:31:40');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (133, 50, 2, 'Opción de grabado:', 'Ingrese la grabación opcional a continuación:', 'Ingresa nombres o iniciales para la opción de grabado. Hasta dos conjuntos de iniciales (1 para cada pluma) o 1 línea por pluma.', NULL, '2026-08-05 19:01:43', '2026-08-10 16:31:44');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (134, 51, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:01:45', '2026-08-10 13:23:41');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (135, 52, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:01:47', '2026-08-10 13:23:43');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (136, 53, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:01:49', '2026-08-10 16:31:49');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (137, 60, 2, NULL, NULL, 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:01:53', '2026-08-10 13:23:52');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (138, 61, 2, NULL, NULL, 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Gray\":\"Gris\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:01:56', '2026-08-10 16:31:55');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (139, 62, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabar, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Green\":\"Verde\",\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\"}', '2026-08-05 19:02:00', '2026-08-10 16:31:58');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (140, 63, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\",\"Color\":\"Color\",\"Navy Blue\":\"Azul Marino\"}', '2026-08-05 19:02:07', '2026-08-06 04:11:05');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (141, 64, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\",\"Color\":\"Color\",\"Orange\":\"Naranja\"}', '2026-08-05 19:02:12', '2026-08-06 15:23:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (142, 65, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Small\":\"Peque\\u00f1o\",\"Color\":\"Color\",\"Royal Blue\":\"Azul Real\"}', '2026-08-05 19:02:17', '2026-08-10 16:32:12');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (143, 85, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:02:21', '2026-08-10 16:32:16');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (144, 86, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:02:25', '2026-08-10 16:32:21');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (145, 87, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:02:31', '2026-08-10 16:32:25');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (146, 88, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Brown\":\"Marr\\u00f3n\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:02:35', '2026-08-10 16:32:29');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (147, 89, 2, 'Añadir Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Gray\":\"Gris\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:02:40', '2026-08-10 16:32:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (148, 90, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Gray\":\"Gris\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:02:44', '2026-08-10 16:32:37');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (149, 91, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Gray\":\"Gris\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:02:48', '2026-08-10 16:32:42');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (150, 92, 2, 'Añadir envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Gray\":\"Gris\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:02:53', '2026-08-10 16:32:46');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (151, 93, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Green\":\"Verde\",\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\"}', '2026-08-05 19:02:57', '2026-08-10 16:32:50');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (152, 94, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Green\":\"Verde\",\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\"}', '2026-08-05 19:03:02', '2026-08-10 16:32:55');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (153, 95, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Green\":\"Verde\",\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\"}', '2026-08-05 19:03:07', '2026-08-10 16:33:00');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (154, 96, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Color\":\"Color\",\"Green\":\"Verde\",\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\"}', '2026-08-05 19:03:11', '2026-08-10 16:33:06');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (155, 97, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\",\"Color\":\"Color\",\"Navy Blue\":\"Azul Marino\"}', '2026-08-05 19:03:16', '2026-08-10 16:33:10');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (156, 98, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\",\"Color\":\"Color\",\"Navy Blue\":\"Azul Marino\"}', '2026-08-05 19:03:20', '2026-08-10 16:33:17');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (157, 99, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\",\"Color\":\"Color\",\"Navy Blue\":\"Azul Marino\"}', '2026-08-05 19:03:25', '2026-08-10 16:33:22');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (158, 100, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\",\"Color\":\"Color\",\"Navy Blue\":\"Azul Marino\"}', '2026-08-05 19:03:30', '2026-08-10 13:25:40');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (159, 101, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\",\"Color\":\"Color\",\"Orange\":\"Naranja\"}', '2026-08-05 19:03:34', '2026-08-10 16:33:31');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (160, 102, 2, 'Añadir envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\",\"Color\":\"Color\",\"Orange\":\"Naranja\"}', '2026-08-05 19:03:38', '2026-08-10 16:33:35');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (161, 103, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\",\"Color\":\"Color\",\"Orange\":\"Naranja\"}', '2026-08-05 19:03:43', '2026-08-10 16:33:39');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (162, 104, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\",\"Color\":\"Color\",\"Orange\":\"Naranja\"}', '2026-08-05 19:03:47', '2026-08-10 16:33:43');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (163, 106, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Medium\":\"Mediano\",\"Color\":\"Color\",\"Royal Blue\":\"Azul Real\"}', '2026-08-05 19:03:51', '2026-08-10 16:33:50');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (164, 107, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"Large\":\"Grande\",\"Color\":\"Color\",\"Royal Blue\":\"Azul Real\"}', '2026-08-05 19:03:56', '2026-08-10 13:26:12');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (165, 108, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XL\":\"XL\",\"Color\":\"Color\",\"Royal Blue\":\"Azul Real\"}', '2026-08-05 19:03:59', '2026-08-10 16:33:58');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (166, 109, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingresa nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Size\":\"Tama\\u00f1o\",\"XXL\":\"XXL\",\"Color\":\"Color\",\"Royal Blue\":\"Azul Real\"}', '2026-08-05 19:04:03', '2026-08-10 16:34:01');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (167, 125, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:04:06', '2026-08-10 16:34:03');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (168, 79, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:04:08', '2026-08-10 13:26:27');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (169, 80, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:04:10', '2026-08-10 16:34:06');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (170, 83, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-05 19:04:12', '2026-08-10 13:26:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (171, 133, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"5.5\":\"5.5\"}', '2026-08-06 04:08:28', '2026-08-10 13:21:33');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (172, 134, 2, 'Agregar Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"6\":\"6\"}', '2026-08-06 04:08:31', '2026-08-10 16:29:43');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (173, 135, 2, 'Añadir Envoltura de Regalo / Personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"6.5\":\"6.5\"}', '2026-08-06 04:08:35', '2026-08-10 16:29:48');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (174, 136, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"7\":\"7\"}', '2026-08-06 04:08:38', '2026-08-10 16:29:51');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (175, 137, 2, 'Agregar Envoltura de Regalo', 'Envoltura de regalo', 'Ingresa lo que deseas decir en la tarjeta incluida o ingresa \"Sin tarjeta\" si no se debe incluir ninguna tarjeta.', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"5.5\":\"5.5\"}', '2026-08-06 04:08:46', '2026-08-10 16:29:59');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (176, 138, 2, 'Añadir envoltura de regalo', 'Envoltura de Regalo', 'Escribe lo que deseas decir en la tarjeta incluida o escribe \"Sin Tarjeta\" si no se debe incluir ninguna tarjeta.', '{\"Ring Size\":\"Tama\\u00f1o de anillo\",\"6\":\"6\"}', '2026-08-06 04:08:53', '2026-08-10 16:30:03');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (177, 132, 2, 'Agregar envoltura de regalo / personalización', 'Detalles de Personalización / Mensaje de Regalo', 'Ingrese nombres para grabado, detalles de personalización o un mensaje de regalo personalizado aquí...', NULL, '2026-08-06 04:13:24', '2026-08-10 16:34:08');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (178, 133, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"5.5\":\"5.5\"}', '2026-08-06 16:18:45', '2026-08-10 17:25:15');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (179, 134, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez les noms pour le gravage, les détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"6\":\"6\"}', '2026-08-06 16:18:49', '2026-08-10 14:18:51');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (180, 135, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"6.5\":\"6.5\"}', '2026-08-06 16:18:53', '2026-08-10 14:18:54');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (181, 136, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', '{\"Ring Size\":\"Taille de bague\",\"7\":\"7\"}', '2026-08-06 16:18:57', '2026-08-10 17:25:27');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (182, 137, 3, 'Ajouter un emballage cadeau', 'Emballage cadeau', 'Entrez ce que vous souhaitez dire sur la carte incluse ou entrez \"Pas de carte\" si aucune carte ne doit être incluse.', '{\"Ring Size\":\"Taille de bague\",\"5.5\":\"5.5\"}', '2026-08-06 16:19:07', '2026-08-10 17:25:36');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (183, 138, 3, 'Ajouter un emballage cadeau', 'Emballage Cadeau', 'Entrez ce que vous souhaitez dire sur la carte incluse ou entrez \"Pas de carte\" si aucune carte ne doit être incluse.', '{\"Ring Size\":\"Taille de bague\",\"6\":\"6\"}', '2026-08-06 16:19:11', '2026-08-06 16:19:11');
SQL
);

        // Table: product_variant_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_variant_translations` (`id`, `product_variant_id`, `language_id`, `personalization_label`, `personalization_details_label`, `personalization_placeholder`, `attributes_translated`, `created_at`, `updated_at`) VALUES (184, 132, 3, 'Ajouter un emballage cadeau / Personnalisation', 'Détails de personnalisation / Message cadeau', 'Entrez des noms pour le gravage, des détails de personnalisation ou un message cadeau personnalisé ici...', NULL, '2026-08-06 16:24:19', '2026-08-06 16:24:19');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (1, 1, 2, 'Joyería Personalizada', 'Anillos, collares, pendientes y piezas de joyería fina.', 'ai_translated', '2026-07-29 19:35:15', '2026-07-28 00:54:26', '2026-07-29 19:35:16');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (2, 2, 2, 'Relojes', 'Relojes y piezas de tiempo para hombres y mujeres.', 'ai_translated', '2026-07-29 19:35:16', '2026-07-28 00:54:27', '2026-07-29 19:35:17');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (3, 3, 2, 'Descargas y Medios', 'Descargas de PDF y contenido multimedia bajo demanda.', 'ai_translated', '2026-07-29 19:35:17', '2026-07-28 00:54:28', '2026-07-29 19:35:19');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (4, 4, 2, 'Regalos y Ropa', 'Sudaderas, tazas, ropa y artículos de regalo.', 'ai_translated', '2026-07-29 19:35:19', '2026-07-28 00:54:29', '2026-07-29 19:35:20');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (5, 5, 2, 'Artículos de Servicio', 'Artículos solo de servicio y compromisos profesionales.', 'ai_translated', '2026-07-29 19:35:20', '2026-07-28 00:54:30', '2026-07-29 19:35:21');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (6, 6, 2, 'Talleres y Seminarios', 'Talleres, seminarios y sesiones de capacitación presenciales y en línea.', 'ai_translated', '2026-07-29 19:35:21', '2026-07-28 00:54:32', '2026-07-29 19:35:22');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (7, 7, 2, 'Anillos', 'Anillos y bandas finas.', 'ai_translated', '2026-07-29 19:35:22', '2026-07-28 00:54:32', '2026-07-29 19:35:23');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (8, 8, 2, 'Pulseras', 'Brazaletes de diamante, oro y plata.', 'ai_translated', '2026-07-29 19:35:23', '2026-07-28 00:54:34', '2026-07-29 19:35:24');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (9, 10, 2, 'Pendientes', 'Pendientes de diamantes y piedras preciosas.', 'ai_translated', '2026-07-29 19:35:24', '2026-07-28 00:54:35', '2026-07-29 19:35:25');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (10, 1, 3, 'Bijoux personnalisés', 'Bagues, colliers, boucles d\'oreilles et pièces de joaillerie fine.', 'ai_translated', '2026-08-04 17:14:27', '2026-08-04 17:14:28', '2026-08-04 17:14:28');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (11, 2, 3, 'Montres', 'Montres et horloges pour hommes et femmes.', 'ai_translated', '2026-08-04 17:14:28', '2026-08-04 17:14:30', '2026-08-04 17:14:30');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (12, 3, 3, 'Téléchargements et Médias', 'Téléchargements de PDF et contenu multimédia à la demande.', 'ai_translated', '2026-08-04 17:14:30', '2026-08-04 17:14:31', '2026-08-04 17:14:31');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (13, 4, 3, 'Cadeaux et Vêtements', 'Sweatshirts, tasses, vêtements et articles cadeaux.', 'ai_translated', '2026-08-04 17:14:31', '2026-08-04 17:14:32', '2026-08-04 17:14:32');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (14, 5, 3, 'Articles de service', 'Articles uniquement de service et engagements professionnels.', 'ai_translated', '2026-08-04 17:14:32', '2026-08-04 17:14:34', '2026-08-04 17:14:34');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (15, 6, 3, 'Ateliers et Séminaires', 'Ateliers, séminaires et sessions de formation en personne et en ligne.', 'ai_translated', '2026-08-04 17:14:34', '2026-08-04 17:14:35', '2026-08-04 17:14:35');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (16, 7, 3, 'Bagues', 'Bagues et alliances fines.', 'ai_translated', '2026-08-04 17:14:35', '2026-08-04 17:14:36', '2026-08-04 17:14:36');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (17, 8, 3, 'Bracelets', 'Bracelets en diamant, en or et en argent.', 'ai_translated', '2026-08-04 17:14:36', '2026-08-04 17:14:38', '2026-08-04 17:14:38');
SQL
);

        // Table: category_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `category_translations` (`id`, `category_id`, `language_id`, `name`, `description`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (18, 10, 3, 'Boucles d\'oreilles', 'Boucles d\'oreilles en diamants et en pierres précieuses.', 'ai_translated', '2026-08-04 17:14:38', '2026-08-04 17:14:39', '2026-08-04 17:14:39');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (1, 1, 2, 'Temporalmente Agotado', 'ai_translated', '2026-08-10 16:29:27', '2026-08-06 04:08:09', '2026-08-10 16:29:28');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (2, 2, 2, 'Pedido pendiente: ETA 2 semanas', 'ai_translated', '2026-08-10 16:29:28', '2026-08-06 04:08:09', '2026-08-10 16:29:29');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (3, 3, 2, 'Pedido pendiente: ETA 4 semanas', 'ai_translated', '2026-08-10 16:29:29', '2026-08-06 04:08:10', '2026-08-10 16:29:29');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (4, 4, 2, 'Artículo Descontinuado', 'ai_translated', '2026-08-10 16:29:29', '2026-08-06 04:08:11', '2026-08-10 16:29:30');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (5, 5, 2, 'Evento Agotado', 'ai_translated', '2026-08-10 16:29:30', '2026-08-06 04:08:12', '2026-08-10 16:29:30');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (6, 6, 2, 'Factura Pagada', 'ai_translated', '2026-08-10 16:29:30', '2026-08-06 04:08:12', '2026-08-10 16:29:31');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (7, 7, 2, 'La inscripción ya no está disponible para este evento.', 'ai_translated', '2026-08-10 16:29:31', '2026-08-06 04:08:13', '2026-08-10 16:29:31');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (8, 1, 3, 'Temporairement en rupture de stock', 'ai_translated', '2026-08-10 17:24:59', '2026-08-06 16:18:29', '2026-08-10 17:25:00');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (9, 2, 3, 'En rupture de stock : Délai de livraison 2 semaines', 'ai_translated', '2026-08-10 17:25:00', '2026-08-06 16:18:30', '2026-08-10 17:25:00');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (10, 3, 3, 'En rupture de stock : Délai de livraison 4 semaines', 'ai_translated', '2026-08-10 17:25:00', '2026-08-06 16:18:31', '2026-08-10 17:25:01');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (11, 4, 3, 'Article Discontinué', 'ai_translated', '2026-08-10 17:25:01', '2026-08-06 16:18:31', '2026-08-10 17:25:02');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (12, 5, 3, 'Événement épuisé', 'ai_translated', '2026-08-10 17:25:02', '2026-08-06 16:18:32', '2026-08-10 17:25:02');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (13, 6, 3, 'Facture Payée', 'ai_translated', '2026-08-10 17:25:02', '2026-08-06 16:18:33', '2026-08-10 17:25:03');
SQL
);

        // Table: product_inventory_alert_translations
        DB::unprepared(<<<'SQL'
INSERT IGNORE INTO `product_inventory_alert_translations` (`id`, `product_inventory_alert_id`, `language_id`, `message`, `translation_status`, `translated_at`, `created_at`, `updated_at`) VALUES (14, 7, 3, 'L\'inscription n\'est plus disponible pour cet événement.', 'ai_translated', '2026-08-10 17:25:03', '2026-08-06 16:18:33', '2026-08-10 17:25:03');
SQL
);

        DB::table('product_brands')->update(['is_demo' => 1]);

        DB::table('product_categories')->update(['is_demo' => 1]);

        DB::table('products')->update(['is_demo' => 1]);

        DB::table('product_variants')->update(['is_demo' => 1]);

        DB::table('product_images')->update(['is_demo' => 1]);

        DB::table('product_cross_selling')->update(['is_demo' => 1]);

        // Seed 24 Sample Reviews for Demo Products
        $demoProducts = DB::table('products')->where('is_demo', 1)->pluck('id')->toArray();
        if (!empty($demoProducts)) {
            $reviewerNames = [
                'Sarah Jenkins', 'Michael Chang', 'Emily Watson', 'David Miller', 'Jessica Taylor',
                'James Wilson', 'Amanda Martinez', 'Robert Anderson', 'Lisa Thomas', 'William Jackson',
                'Olivia White', 'Daniel Harris', 'Sophia Martin', 'Christopher Thompson', 'Emma Garcia',
                'Anthony Robinson', 'Isabella Clark', 'Matthew Rodriguez', 'Mia Lewis', 'Ethan Lee',
                'Charlotte Walker', 'Alexander Hall', 'Amelia Allen', 'Benjamin Young'
            ];
            $reviewerLocations = ['New York, NY', 'Austin, TX', 'Seattle, WA', 'Chicago, IL', 'San Francisco, CA', 'Miami, FL', 'Denver, CO', 'Boston, MA', 'Atlanta, GA', 'Portland, OR', 'Los Angeles, CA', 'Dallas, TX'];
            $sampleComments = [
                'Absolutely fantastic quality! Exceeded my expectations in every way. Would definitely order again.',
                'Very pleased with this purchase. Shipping was fast and product works exactly as advertised.',
                'Great value for the price! Customer support was also super helpful when I had a question.',
                'Solid product. Good craftsmanship and nice attention to detail. Overall 4 out of 5.',
                'Love it! Using it daily now. High quality material and smooth experience.',
                'Decent quality for what you pay. Arrived well packaged and on time.',
                'Highly recommend to anyone looking for a reliable product! Will be buying more for friends.',
                'Super convenient and easy to use straight out of the box. 5 stars!',
                'Very good item. Minor cosmetic detail could be improved, but functionally 100% satisfied.',
                'Five stars! The design and performance are top tier. Seamless experience.'
            ];
            $reviewsToInsert = [];
            for ($i = 0; $i < 24; $i++) {
                $prodId = $demoProducts[$i % count($demoProducts)];
                $reviewsToInsert[] = [
                    'product_id' => $prodId,
                    'name' => $reviewerNames[$i],
                    'location' => $reviewerLocations[$i % count($reviewerLocations)],
                    'rating' => rand(3, 5),
                    'comments' => $sampleComments[$i % count($sampleComments)],
                    'approved' => 1,
                    'is_demo' => 1,
                    'created_at' => now()->subDays(rand(1, 45)),
                    'updated_at' => now()->subDays(rand(1, 45)),
                ];
            }
            DB::table('product_reviews')->insert($reviewsToInsert);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
