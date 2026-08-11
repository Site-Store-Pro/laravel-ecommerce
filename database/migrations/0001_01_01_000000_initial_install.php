<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::unprepared(<<<'SQL'
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_locks_expiration_index`(`expiration` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `category_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `category_translations_category_id_language_id_unique`(`category_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `category_translations_category_id_index`(`category_id` ASC) USING BTREE,
  INDEX `category_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `category_translations_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `checkout_custom_fields`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `required_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `required_error_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `html_above` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `show_for` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'both',
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_builder_block_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_builder_block_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content_desktop` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content_tablet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content_mobile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cbb_trans_unique`(`cms_builder_block_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_builder_block_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_builder_block_translations_cms_builder_block_id_foreign` FOREIGN KEY (`cms_builder_block_id`) REFERENCES `cms_builder_blocks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_builder_block_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_builder_blocks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_element` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` tinyint UNSIGNED NOT NULL DEFAULT 1,
  `section_type` enum('header','footer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'header',
  `is_placeholder` tinyint(1) NOT NULL DEFAULT 0,
  `sort_desktop` double NOT NULL DEFAULT 0,
  `sort_tablet` double NOT NULL DEFAULT 0,
  `sort_mobile` double NOT NULL DEFAULT 0,
  `content_desktop` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content_tablet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content_mobile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active_desktop` tinyint(1) NOT NULL DEFAULT 1,
  `is_active_tablet` tinyint(1) NOT NULL DEFAULT 1,
  `is_active_mobile` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_downloads`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` datetime NULL DEFAULT NULL,
  `force_download` tinyint(1) NOT NULL DEFAULT 0,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT 1,
  `show_icon` tinyint(1) NOT NULL DEFAULT 0,
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `source_type` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_file_key` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_expiration_seconds` int UNSIGNED NOT NULL DEFAULT 600,
  `s3_custom_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_custom_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_custom_region` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_custom_bucket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_custom_file_key` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `s3_custom_expiration_seconds` int UNSIGNED NOT NULL DEFAULT 600,
  `poster_image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `poster_image_cdn_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_downloads_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_embeds`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `embed_type` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `code_snippet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_faq_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_faq_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_faq_translations_cms_faq_id_language_id_unique`(`cms_faq_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_faq_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_faq_translations_cms_faq_id_foreign` FOREIGN KEY (`cms_faq_id`) REFERENCES `cms_faqs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_faq_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_faqs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_form_fields`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` bigint UNSIGNED NOT NULL,
  `type` enum('input','textarea','select','radio','checkbox','checkbox_group') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `required_type` enum('non_blank','email','numeric') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `required_error_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `html_above` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `field_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cms_form_fields_form_id_foreign`(`form_id` ASC) USING BTREE,
  CONSTRAINT `cms_form_fields_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `cms_forms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_form_submissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` bigint UNSIGNED NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cms_form_submissions_form_id_foreign`(`form_id` ASC) USING BTREE,
  CONSTRAINT `cms_form_submissions_form_id_foreign` FOREIGN KEY (`form_id`) REFERENCES `cms_forms` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_forms`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `submit_button_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Submit',
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `confirmation_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `redirect_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `auto_optin` tinyint(1) NOT NULL DEFAULT 0,
  `optin_provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `optin_list_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_forms_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_layouts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_layouts_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_list_menu_item_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_list_menu_item_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `list_item` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `lmi_trans_unique`(`cms_list_menu_item_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_list_menu_item_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_list_menu_item_translations_cms_list_menu_item_id_foreign` FOREIGN KEY (`cms_list_menu_item_id`) REFERENCES `cms_list_menu_items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_list_menu_item_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_list_menu_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_list_menu_id` bigint UNSIGNED NOT NULL,
  `list_item` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_val` double NOT NULL DEFAULT 5000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cms_list_menu_items_cms_list_menu_id_foreign`(`cms_list_menu_id` ASC) USING BTREE,
  CONSTRAINT `cms_list_menu_items_cms_list_menu_id_foreign` FOREIGN KEY (`cms_list_menu_id`) REFERENCES `cms_list_menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_list_menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_modal_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_modal_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_modal_translations_cms_modal_id_language_id_unique`(`cms_modal_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_modal_translations_cms_modal_id_index`(`cms_modal_id` ASC) USING BTREE,
  INDEX `cms_modal_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_modal_translations_cms_modal_id_foreign` FOREIGN KEY (`cms_modal_id`) REFERENCES `cms_modals` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_modal_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_modals`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `position` enum('center','left','right','bottom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'center',
  `max_width` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '640px',
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cookie_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Cookie key; auto-generated as cms_modal_{id} if blank',
  `cookie_lifetime` int NOT NULL DEFAULT 30 COMMENT 'Days; 0 = session only',
  `auto_open` tinyint(1) NOT NULL DEFAULT 0,
  `open_delay` int NOT NULL DEFAULT 0 COMMENT 'Milliseconds before auto-open',
  `overlay_dismissible` tinyint(1) NOT NULL DEFAULT 1,
  `show_close_button` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Show X + Dismiss label',
  `trigger_selector` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'CSS selector to trigger modal on click',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_page_category`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_page_category_cms_page_id_category_id_unique`(`cms_page_id` ASC, `category_id` ASC) USING BTREE,
  INDEX `cms_page_category_category_id_foreign`(`category_id` ASC) USING BTREE,
  CONSTRAINT `cms_page_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `cms_pages_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_page_category_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_page_revisions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_js` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `header_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `revision_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `author_id` bigint UNSIGNED NULL DEFAULT NULL,
  `layout_type` int NOT NULL DEFAULT 1,
  `left_col` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `right_col` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `show_author` tinyint NOT NULL DEFAULT 1,
  `show_title` tinyint NOT NULL DEFAULT 1,
  `show_date` tinyint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cms_page_revisions_cms_page_id_foreign`(`cms_page_id` ASC) USING BTREE,
  INDEX `cms_page_revisions_author_id_foreign`(`author_id` ASC) USING BTREE,
  CONSTRAINT `cms_page_revisions_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `cms_page_revisions_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 235 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_page_tag`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_page_tag_cms_page_id_tag_id_unique`(`cms_page_id` ASC, `tag_id` ASC) USING BTREE,
  INDEX `cms_page_tag_tag_id_foreign`(`tag_id` ASC) USING BTREE,
  CONSTRAINT `cms_page_tag_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_page_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `cms_pages_tags` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_page_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_page_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alternate_page_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_page_translations_cms_page_id_language_id_unique`(`cms_page_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_page_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_page_translations_cms_page_id_foreign` FOREIGN KEY (`cms_page_id`) REFERENCES `cms_pages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_page_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_page_types`  (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_pages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cms_search_index` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cms_search_index_locked` tinyint(1) NOT NULL DEFAULT 0,
  `author_id` bigint UNSIGNED NULL DEFAULT NULL,
  `expires_at` datetime NULL DEFAULT NULL,
  `requires_code` tinyint(1) NOT NULL DEFAULT 0,
  `access_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `required_product_id` bigint UNSIGNED NULL DEFAULT NULL,
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_js` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `header_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `background_video_s3` int NOT NULL DEFAULT 0,
  `background_video_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `background_video_cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `alternate_page_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `page_title_alignment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `page_title_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `include_slideshow` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `min_header_height` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '320px',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `exclude_from_search` tinyint(1) NOT NULL DEFAULT 0,
  `page_type` bigint UNSIGNED NOT NULL DEFAULT 1,
  `page_ranking` int NOT NULL DEFAULT 0,
  `hide_page_ranking` int NOT NULL DEFAULT 1,
  `custom_sorting` double NOT NULL DEFAULT 0,
  `layout_type` bigint UNSIGNED NOT NULL DEFAULT 1,
  `left_col` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `right_col` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `show_author` tinyint NOT NULL DEFAULT 1,
  `show_title` tinyint NOT NULL DEFAULT 1,
  `show_date` tinyint NOT NULL DEFAULT 1,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `featured_image_s3` tinyint NOT NULL DEFAULT 0,
  `featured_image_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `featured_image_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `featured_image_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `featured_image_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `media_image_s3` tinyint NOT NULL DEFAULT 0,
  `media_image_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `media_image_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `media_image_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `media_image_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `featured_image_cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `media_image_cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_pages_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `cms_pages_author_id_foreign`(`author_id` ASC) USING BTREE,
  INDEX `cms_pages_required_product_id_foreign`(`required_product_id` ASC) USING BTREE,
  INDEX `cms_pages_page_type_foreign`(`page_type` ASC) USING BTREE,
  INDEX `cms_pages_layout_type_foreign`(`layout_type` ASC) USING BTREE,
  FULLTEXT INDEX `cms_pages_fulltext_search`(`title`, `cms_search_index`, `content`),
  CONSTRAINT `cms_pages_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `cms_pages_layout_type_foreign` FOREIGN KEY (`layout_type`) REFERENCES `cms_layouts` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cms_pages_page_type_foreign` FOREIGN KEY (`page_type`) REFERENCES `cms_page_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `cms_pages_required_product_id_foreign` FOREIGN KEY (`required_product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_pages_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_pages_categories_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_pages_category_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_pages_category_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uq_cms_pages_cat_trans_cat_lang`(`cms_pages_category_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_pages_category_translations_cms_pages_category_id_index`(`cms_pages_category_id` ASC) USING BTREE,
  INDEX `cms_pages_category_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_pages_category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_cms_pages_cat_trans_cat_id` FOREIGN KEY (`cms_pages_category_id`) REFERENCES `cms_pages_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_pages_tag_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_pages_tag_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_pages_tag_translations_cms_pages_tag_id_language_id_unique`(`cms_pages_tag_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_pages_tag_translations_cms_pages_tag_id_index`(`cms_pages_tag_id` ASC) USING BTREE,
  INDEX `cms_pages_tag_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_pages_tag_translations_cms_pages_tag_id_foreign` FOREIGN KEY (`cms_pages_tag_id`) REFERENCES `cms_pages_tags` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_pages_tag_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_pages_tags`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_pages_tags_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_settings_key_unique`(`key` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 216 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_slide_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cms_slide_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `slide_heading` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_sub_heading` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_callout_button_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cms_slide_translations_cms_slide_id_language_id_unique`(`cms_slide_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `cms_slide_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `cms_slide_translations_cms_slide_id_foreign` FOREIGN KEY (`cms_slide_id`) REFERENCES `cms_slides` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `cms_slide_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_slides`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `Title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `SlideURL` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `LargeImage` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Thumbnail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Active` int NULL DEFAULT NULL,
  `ImageSort` double NULL DEFAULT NULL,
  `slide_heading` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_sub_heading` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_content_css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_heading_css` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slide_alignment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'middle-center',
  `slide_callout_button_label` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `slideshow_id` int NULL DEFAULT 1,
  `mobile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_mobile_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_image_width` int NULL DEFAULT 1920,
  `cdn_image_height` int NULL DEFAULT 725,
  `cdn_mobile_image_height` int NULL DEFAULT 500,
  `cdn_mobile_image_width` int NULL DEFAULT 600,
  `image_s3` int NOT NULL DEFAULT 0,
  `image_s3_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_bucket` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_slideshows`  (
  `slideshow_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slideshow_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slideshow_active` int NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `slide_show_alignment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`slideshow_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `cms_testimonials`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT 5,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `company_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `content_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_detail_id` bigint UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `redirect_url` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accessed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `content_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `content_access_tokens_order_detail_id_index`(`order_detail_id` ASC) USING BTREE,
  INDEX `content_access_tokens_product_id_index`(`product_id` ASC) USING BTREE,
  CONSTRAINT `content_access_tokens_order_detail_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `discount_configuration`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `store_id` int NOT NULL DEFAULT 1,
  `coupon_codes` tinyint NOT NULL DEFAULT 1,
  `preferred_customers` tinyint NOT NULL DEFAULT 1,
  `category_discounts` tinyint NOT NULL DEFAULT 1,
  `quantity_based` tinyint NOT NULL DEFAULT 1,
  `value_based` tinyint NOT NULL DEFAULT 1,
  `new_customer_discount` tinyint NOT NULL DEFAULT 1,
  `item_specific` tinyint NOT NULL DEFAULT 1,
  `allow_multiple_order_discounts` tinyint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `discount_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `discounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `discount_type_id` bigint UNSIGNED NOT NULL,
  `value_type` int NOT NULL DEFAULT 1,
  `order_minimum` double NOT NULL DEFAULT 0,
  `order_maximum` double NOT NULL DEFAULT 100000,
  `order_qty_min` int NOT NULL DEFAULT 1,
  `order_qty_max` int NOT NULL DEFAULT 1000000,
  `product_id` int NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `value` double NOT NULL DEFAULT 0,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `code_type` int NOT NULL DEFAULT 0,
  `times_redeemed` int NOT NULL DEFAULT 0,
  `get_x_free` int NOT NULL DEFAULT 0,
  `free_range1` int NOT NULL DEFAULT 0,
  `free_range2` int NOT NULL DEFAULT 0,
  `free_percent` double NOT NULL DEFAULT 100,
  `show_get_x_free` int NOT NULL DEFAULT 0,
  `show_get_x_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `buy_x_get_y` int NOT NULL DEFAULT 0,
  `product_id_y` int NOT NULL DEFAULT 0,
  `product_y_percent` double NOT NULL DEFAULT 100,
  `start_date` datetime NULL DEFAULT NULL,
  `expiration_date` datetime NULL DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `store_id` int NOT NULL DEFAULT 1,
  `brand_id` int NOT NULL DEFAULT 0,
  `brand_qty_min` int NOT NULL DEFAULT 1,
  `brand_qty_max` int NOT NULL DEFAULT 1000000,
  `brand_subtotal_min` double NOT NULL DEFAULT 0,
  `brand_subtotal_max` double NOT NULL DEFAULT 1000000,
  `category_id` int NOT NULL DEFAULT 0,
  `cat_qty_min` int NOT NULL DEFAULT 1,
  `cat_qty_max` int NOT NULL DEFAULT 1000000,
  `cat_subtotal_min` double NOT NULL DEFAULT 0,
  `cat_subtotal_max` double NOT NULL DEFAULT 1000000,
  `subcat_id` int NOT NULL DEFAULT 0,
  `subcat_qty_min` int NOT NULL DEFAULT 1,
  `subcat_qty_max` int NOT NULL DEFAULT 1000000,
  `subcat_subtotal_min` double NOT NULL DEFAULT 0,
  `subcat_subtotal_max` double NOT NULL DEFAULT 1000000,
  `style_id` int NOT NULL DEFAULT 0,
  `style_qty_min` int NOT NULL DEFAULT 1,
  `style_qty_max` int NOT NULL DEFAULT 1000000,
  `style_subtotal_min` double NOT NULL DEFAULT 0,
  `style_subtotal_max` double NOT NULL DEFAULT 1000000,
  `item_qty_min` int NOT NULL DEFAULT 1,
  `item_qty_max` int NOT NULL DEFAULT 1000000,
  `item_subtotal_min` double NOT NULL DEFAULT 0,
  `item_subtotal_max` double NOT NULL DEFAULT 1000000,
  `bogo_cart_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `free_shipping` tinyint NOT NULL DEFAULT 0,
  `wholesale_only` tinyint NOT NULL DEFAULT 0,
  `order_weight_min` double NOT NULL DEFAULT 0,
  `order_weight_max` double NOT NULL DEFAULT 1000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `discounts_discount_type_id_foreign`(`discount_type_id` ASC) USING BTREE,
  CONSTRAINT `discounts_discount_type_id_foreign` FOREIGN KEY (`discount_type_id`) REFERENCES `discount_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `email_template_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_template_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `header_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `salutation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `greeting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sign_off` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `disclaimer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `copyright` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `footer_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email_template_translations_email_template_id_language_id_unique`(`email_template_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `email_template_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `email_template_translations_email_template_id_foreign` FOREIGN KEY (`email_template_id`) REFERENCES `email_templates` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `email_template_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `email_template_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordering` double NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email_template_types_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `email_templates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email_type_id` bigint UNSIGNED NOT NULL,
  `profile_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bcc_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `subject` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `header_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `banner_image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `banner_image_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `show_banner` tinyint NOT NULL DEFAULT 1,
  `salutation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `include_salutation` tinyint NOT NULL DEFAULT 0,
  `greeting` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sign_off` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `signature` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `disclaimer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `copyright` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `footer_image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `footer_image_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `show_footer_image` tinyint NOT NULL DEFAULT 0,
  `footer_html` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `email_templates_email_type_id_foreign`(`email_type_id` ASC) USING BTREE,
  CONSTRAINT `email_templates_email_type_id_foreign` FOREIGN KEY (`email_type_id`) REFERENCES `email_template_types` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `handling_charges`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `min_subtotal` decimal(10, 2) NULL DEFAULT NULL,
  `max_subtotal` decimal(10, 2) NULL DEFAULT NULL,
  `min_weight` decimal(10, 2) NULL DEFAULT NULL,
  `min_items` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10811 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `kb_article_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kb_article_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `article_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kb_article_translations_kb_article_id_language_id_unique`(`kb_article_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `kb_article_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `kb_article_translations_kb_article_id_foreign` FOREIGN KEY (`kb_article_id`) REFERENCES `kb_articles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `kb_article_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 311 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `kb_articles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `seo_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NULL DEFAULT NULL,
  `article_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_active` int NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `kb_rating` int NOT NULL DEFAULT 0,
  `show_date` tinyint(1) NOT NULL DEFAULT 1,
  `date_added` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `date_modified` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kb_articles_seo_link_unique`(`seo_link` ASC) USING BTREE,
  INDEX `kb_articles_category_id_index`(`category_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1152 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `kb_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kb_categories_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 116 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `kb_category_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `kb_category_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kb_category_translations_kb_category_id_language_id_unique`(`kb_category_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `kb_category_translations_kb_category_id_index`(`kb_category_id` ASC) USING BTREE,
  INDEX `kb_category_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `kb_category_translations_kb_category_id_foreign` FOREIGN KEY (`kb_category_id`) REFERENCES `kb_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `kb_category_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `languages`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_emoji` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '?',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `show_in_switcher` tinyint(1) NOT NULL DEFAULT 1,
  `rtl` tinyint(1) NOT NULL DEFAULT 0,
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `currency_position` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'before',
  `decimal_separator` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '.',
  `thousands_separator` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `languages_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `nav_item_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nav_item_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `html_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nav_item_translations_nav_item_id_language_id_unique`(`nav_item_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `nav_item_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `nav_item_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `nav_item_translations_nav_item_id_foreign` FOREIGN KEY (`nav_item_id`) REFERENCES `nav_items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `nav_items`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `position` double NOT NULL DEFAULT 0,
  `label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'link',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `html_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cms_page_id` int UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT 0,
  `visibility` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `hide_on_mobile` tinyint(1) NOT NULL DEFAULT 0,
  `hide_on_desktop` tinyint(1) NOT NULL DEFAULT 0,
  `css_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `aria_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `plugin_slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `nav_items_menu_id_parent_id_position_index`(`menu_id` ASC, `parent_id` ASC, `position` ASC) USING BTREE,
  CONSTRAINT `nav_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `nav_menus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `nav_menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `color_scheme` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `custom_css` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sticky` tinyint(1) NOT NULL DEFAULT 1,
  `sticky_body_offset` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '0px',
  `show_logo` tinyint(1) NOT NULL DEFAULT 1,
  `alignment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `nav_menus_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_checkout_options`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `primary_processor` int NOT NULL DEFAULT 0,
  `secondary_processor` int NOT NULL DEFAULT 0,
  `tertiary_processor` int NOT NULL DEFAULT 0,
  `randomize_processor` tinyint NOT NULL DEFAULT 0,
  `paypal_express` int NOT NULL DEFAULT 0,
  `retail_minimum` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `wholesale_minimum` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `stripe_address_required` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_details`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `item_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_qty` decimal(10, 3) NOT NULL,
  `final_price` decimal(10, 2) NOT NULL,
  `base_price` decimal(10, 2) NOT NULL,
  `discount_price` decimal(10, 2) NOT NULL,
  `options_fee` decimal(10, 2) NOT NULL,
  `options_list` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `inventory_id` int NOT NULL DEFAULT 0,
  `download_item` int NOT NULL DEFAULT 0,
  `item_taxable` tinyint NOT NULL DEFAULT 1,
  `download_expiration` datetime NULL DEFAULT NULL,
  `downloads_counter` int NULL DEFAULT NULL,
  `downloads_max_allowed` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_details_order_id_foreign`(`order_id` ASC) USING BTREE,
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_downloads`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_details_id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT 0,
  `download_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_downloads_order_details_id_foreign`(`order_details_id` ASC) USING BTREE,
  CONSTRAINT `order_downloads_order_details_id_foreign` FOREIGN KEY (`order_details_id`) REFERENCES `order_details` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_date` datetime NOT NULL,
  `payment_amount` decimal(10, 2) NOT NULL,
  `payment_method` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` int NOT NULL DEFAULT 0,
  `authorization_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `processor_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_payments_order_id_foreign`(`order_id` ASC) USING BTREE,
  CONSTRAINT `order_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_processors`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `processor_id` int NOT NULL,
  `processor_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `production` tinyint NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_refunds`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `refund_date` datetime NOT NULL,
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `authorization_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `processor_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_refunds_order_id_foreign`(`order_id` ASC) USING BTREE,
  CONSTRAINT `order_refunds_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `order_status_list`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `orderstatuscode` int NULL DEFAULT NULL,
  `orderstatus` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sortorder` double NULL DEFAULT NULL,
  `customerdisplay` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `Active` int NOT NULL DEFAULT 0,
  `AdminDisplay` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `orders`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_invoice_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_external_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_user_id` int NOT NULL DEFAULT 0,
  `order_status` int NOT NULL DEFAULT 0,
  `order_date` datetime NOT NULL,
  `order_total` decimal(10, 2) NOT NULL,
  `order_subtotal` decimal(10, 2) NOT NULL,
  `order_taxes` decimal(10, 2) NOT NULL,
  `order_discounts` decimal(10, 2) NOT NULL,
  `order_shipping` int NOT NULL DEFAULT 0,
  `order_shipping_date` datetime NULL DEFAULT NULL,
  `order_shipping_method` int NOT NULL DEFAULT 0,
  `order_shipping_tracking` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order_download` int NOT NULL DEFAULT 0,
  `order_handling` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `order_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `custom_field_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `order_shipping_method_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `orders_order_invoice_no_unique`(`order_invoice_no` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `plugin_options`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_id` bigint UNSIGNED NOT NULL DEFAULT 0,
  `field_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_data_format` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_default_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_selections` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_min_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_max_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_editor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_help` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_required` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `field_error_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_html` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 276 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `plugin_setting_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `field_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pst_unique`(`plugin_id` ASC, `language_id` ASC, `field_name` ASC) USING BTREE,
  INDEX `pst_plugin_lang_idx`(`plugin_id` ASC, `language_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 50 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `plugin_settings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `plugin_id` bigint UNSIGNED NOT NULL DEFAULT 0,
  `field_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `field_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `plugin_settings_plugin_id_index`(`plugin_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 156 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `plugins`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `api_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `version` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `author` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `filename` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `install_type` tinyint NOT NULL DEFAULT 1,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shortcode` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activation_required` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `activation_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `activation_failed_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `activation_success_msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `usage_instructions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `help_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `help_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `activation_date` datetime NULL DEFAULT NULL,
  `activation_status` tinyint NOT NULL DEFAULT 0,
  `activation_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `serial_number` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `plugins_api_id_unique`(`api_id` ASC) USING BTREE,
  UNIQUE INDEX `plugins_filename_unique`(`filename` ASC) USING BTREE,
  UNIQUE INDEX `plugins_shortcode_unique`(`shortcode` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_brands`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `brand_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_logo_s3` int NOT NULL DEFAULT 0,
  `brand_logo_cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_logo_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_logo_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_logo_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_logo_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_icon_direct_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `brand_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  `is_visible_in_menu` tinyint NULL DEFAULT 1,
  `show_image` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_brands_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `category_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_s3` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `category_image_cdn_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_image_direct_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_visible_in_menu` tinyint(1) NOT NULL DEFAULT 1,
  `display_label_in_plugins` tinyint(1) NOT NULL DEFAULT 1,
  `display_image_in_plugins` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_categories_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `product_categories_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  INDEX `product_categories_sort_order_index`(`sort_order` ASC) USING BTREE,
  INDEX `product_categories_is_visible_in_menu_index`(`is_visible_in_menu` ASC) USING BTREE,
  CONSTRAINT `product_categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_categories_assignments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_categories_assignments_product_id_category_id_unique`(`product_id` ASC, `category_id` ASC) USING BTREE,
  INDEX `cat_prod_composite_idx`(`category_id` ASC, `product_id` ASC) USING BTREE,
  CONSTRAINT `product_categories_assignments_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_categories_assignments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_cross_selling`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `cross_sell_product_id` bigint UNSIGNED NOT NULL,
  `sort_order` double NOT NULL DEFAULT 0,
  `display_on_item_view` tinyint(1) NOT NULL DEFAULT 1,
  `display_on_post_cart` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_cross_sell_pair`(`product_id` ASC, `cross_sell_product_id` ASC) USING BTREE,
  INDEX `product_cross_selling_cross_sell_product_id_foreign`(`cross_sell_product_id` ASC) USING BTREE,
  CONSTRAINT `product_cross_selling_cross_sell_product_id_foreign` FOREIGN KEY (`cross_sell_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_cross_selling_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_field_option_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_field_option_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `option_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pfot_option_lang_uq`(`product_field_option_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `product_field_option_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `pfot_option_id_fk` FOREIGN KEY (`product_field_option_id`) REFERENCES `product_field_options` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_field_option_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_field_options`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_field_id` bigint UNSIGNED NOT NULL,
  `option_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_price_modifier` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `option_wholesale_price_modifier` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_field_options_product_field_id_foreign`(`product_field_id` ASC) USING BTREE,
  CONSTRAINT `product_field_options_product_field_id_foreign` FOREIGN KEY (`product_field_id`) REFERENCES `product_fields` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 58 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_field_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_field_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pft_field_lang_uq`(`product_field_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `product_field_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `product_field_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_field_translations_product_field_id_foreign` FOREIGN KEY (`product_field_id`) REFERENCES `product_fields` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_fields`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `charge_tax` tinyint NOT NULL DEFAULT 1,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_fields_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_fields_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_images`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `variant_id` bigint UNSIGNED NOT NULL,
  `thumbnail_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `main_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `zoom_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_alt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image_s3` tinyint NOT NULL DEFAULT 0,
  `cdn_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `search_image` tinyint NOT NULL DEFAULT 0,
  `active` tinyint NOT NULL DEFAULT 1,
  `image_s3_region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_bucket_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_access_key_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_s3_secret_access_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image_url_source` tinyint NOT NULL DEFAULT 0,
  `alt_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `zoom_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_images_variant_id_foreign`(`variant_id` ASC) USING BTREE,
  CONSTRAINT `product_images_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 135 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_inventory_alert_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_inventory_alert_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pia_trans_unique`(`product_inventory_alert_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `fk_pia_trans_lang_id`(`language_id` ASC) USING BTREE,
  CONSTRAINT `fk_pia_trans_alert_id` FOREIGN KEY (`product_inventory_alert_id`) REFERENCES `product_inventory_alerts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_pia_trans_lang_id` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_inventory_alerts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_quantity_discounts`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `qty_min` int NOT NULL DEFAULT 1,
  `qty_max` int NOT NULL DEFAULT 1000000,
  `discount_value` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `value_type` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_quantity_discounts_product_variant_id_foreign`(`product_variant_id` ASC) USING BTREE,
  CONSTRAINT `product_quantity_discounts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_reviews`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_reviews_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `long_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_translations_product_id_language_id_unique`(`product_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `product_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `product_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_translations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 55 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_variant_events`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `variant_id` bigint UNSIGNED NOT NULL,
  `event_start_date` datetime NOT NULL,
  `event_end_date` datetime NULL DEFAULT NULL,
  `event_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `alternate_label` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `label_background` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '#4f46e5',
  `show_date` tinyint(1) NOT NULL DEFAULT 1,
  `event_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `event_sort` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_variant_events_variant_id_unique`(`variant_id` ASC) USING BTREE,
  CONSTRAINT `product_variant_events_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_variant_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `personalization_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `personalization_details_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `personalization_placeholder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `attributes_translated` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pvt_variant_lang_uq`(`product_variant_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `product_variant_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `product_variant_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_variant_translations_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 185 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `product_variants`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `public_price` decimal(10, 2) NOT NULL,
  `wholesale_price` decimal(10, 2) NOT NULL,
  `on_sale` int NOT NULL DEFAULT 0,
  `sale_price` decimal(10, 2) NULL DEFAULT NULL,
  `variant_fee` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `wholesale_variant_fee` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `personalization_active` int NOT NULL DEFAULT 0,
  `personalization_fee` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `shipping` int NOT NULL DEFAULT 0,
  `weight` double NULL DEFAULT NULL,
  `weight_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `attributes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_item` int NOT NULL DEFAULT 0,
  `charge_tax` tinyint NOT NULL DEFAULT 1,
  `download_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `direct_download_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `download_expiration` datetime NULL DEFAULT NULL,
  `downloads_max_allowed` int NULL DEFAULT 100,
  `download_s3` int NOT NULL DEFAULT 0,
  `download_s3_region` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_s3_bucket_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_s3_access_key_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_s3_secret_access_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subscription` int NOT NULL DEFAULT 0,
  `is_event` tinyint(1) NOT NULL DEFAULT 0,
  `video_item` int NOT NULL DEFAULT 0,
  `video_preview` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `video_purchase` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `download_cdn_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  `paddle_sandbox_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paddle_live_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paddle_price` decimal(10, 2) NULL DEFAULT NULL,
  `paddle_interval` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paddle_frequency` int NULL DEFAULT 1,
  `paddle_currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `stripe_sandbox_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stripe_live_price_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_new_stripe_product` tinyint NOT NULL DEFAULT 0,
  `stripe_billing_interval` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
  `stripe_trial_enabled` tinyint NOT NULL DEFAULT 0,
  `stripe_trial_days` int NOT NULL DEFAULT 0,
  `personalization_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Add Gift Wrapping / Personalization',
  `personalization_details_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Personalization Details / Gift Message',
  `personalization_placeholder` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Enter names for engraving, personalization details, or a custom gift message here...',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `product_variants_sku_unique`(`sku` ASC) USING BTREE,
  INDEX `product_variants_product_id_foreign`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 146 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_id` bigint UNSIGNED NULL DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `long_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `product_search_index` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `product_search_index_locked` tinyint(1) NOT NULL DEFAULT 0,
  `meta_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `seo_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `download_item` int NOT NULL DEFAULT 0,
  `shipping` int NOT NULL DEFAULT 1,
  `max_qty` tinyint NOT NULL DEFAULT 0,
  `checkout_redirect` tinyint NOT NULL DEFAULT 0,
  `completion_redirect` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `completion_redirect_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `standalone_purchase` tinyint NOT NULL DEFAULT 0,
  `advanced_options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `dependent_variants` int NOT NULL DEFAULT 0,
  `hide_inventory_levels` int NOT NULL DEFAULT 0,
  `inventory_alert_id` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'FK to product_inventory_alerts; null = use default out-of-stock text',
  `layout_type` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_demo` tinyint NOT NULL DEFAULT 0 COMMENT '1 = seeded by DemoStoreSeeder, eligible for one-click purge',
  `reviews_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `reviews_rating` decimal(3, 2) NOT NULL DEFAULT 0.00,
  `featured_item` tinyint NOT NULL DEFAULT 0 COMMENT '0=not featured, 1=featured — used by the Featured Items Plugin',
  `show_item_total` tinyint NOT NULL DEFAULT 0,
  `show_variant_selector_thumbnail` tinyint NOT NULL DEFAULT 0,
  `variant_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Select Option:',
  `product_video_embed` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_donation_or_bill_pay` tinyint(1) NOT NULL DEFAULT 0,
  `allow_custom_amount` tinyint(1) NOT NULL DEFAULT 0,
  `custom_amount_min` decimal(10, 2) NULL DEFAULT NULL,
  `custom_amount_max` decimal(10, 2) NULL DEFAULT NULL,
  `custom_amount_options` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `products_brand_id_foreign`(`brand_id` ASC) USING BTREE,
  INDEX `products_seo_slug_index`(`seo_slug` ASC) USING BTREE,
  INDEX `products_inventory_alert_id_foreign`(`inventory_alert_id` ASC) USING BTREE,
  FULLTEXT INDEX `products_fulltext_search`(`title`, `product_search_index`, `short_description`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `product_brands` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `products_inventory_alert_id_foreign` FOREIGN KEY (`inventory_alert_id`) REFERENCES `product_inventory_alerts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `products_inventory`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `variant_id` bigint UNSIGNED NOT NULL,
  `quantity_available` int NOT NULL DEFAULT 0,
  `warehouse_stock_level` int NOT NULL DEFAULT 0,
  `use_warehouse_stock` tinyint(1) NOT NULL DEFAULT 0,
  `reserved_stock` int NOT NULL DEFAULT 0,
  `location_id` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `products_inventory_variant_id_foreign`(`variant_id` ASC) USING BTREE,
  CONSTRAINT `products_inventory_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 146 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `shipping_configurations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_ship_options_us` tinyint(1) NOT NULL DEFAULT 0,
  `custom_ship_options_int` tinyint(1) NOT NULL DEFAULT 0,
  `allow_comments` tinyint(1) NOT NULL DEFAULT 0,
  `origin_zipcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `origin_country_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `merchant_country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `currency_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD',
  `currency_symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '$',
  `vat_inclusive_pricing` tinyint(1) NOT NULL DEFAULT 0,
  `realtime_fedex` tinyint(1) NOT NULL DEFAULT 0,
  `realtime_ups` tinyint(1) NOT NULL DEFAULT 0,
  `realtime_usps` tinyint(1) NOT NULL DEFAULT 0,
  `realtime_pickup` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `shipping_countries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort_order` double NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `charge_vat` tinyint(1) NOT NULL DEFAULT 1,
  `custom_vat_rate` double NOT NULL DEFAULT 0,
  `exclude_free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `flat_rate_value_type` int NOT NULL DEFAULT 1,
  `flat_rate_range` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 237 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `shipping_flat_rates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `is_international` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `sort_order` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `shipping_states`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sales_tax_rate` double NOT NULL DEFAULT 0,
  `vat_rate` double NOT NULL DEFAULT 0,
  `exclude_free_shipping` tinyint(1) NOT NULL DEFAULT 0,
  `flat_rate_value_type` int NOT NULL DEFAULT 1,
  `flat_rate_range` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 76 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `shopping_cart_log`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_log_session` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_qty` decimal(10, 3) NOT NULL,
  `item_price` decimal(10, 2) NOT NULL,
  `item_discount_price` decimal(10, 2) NOT NULL,
  `item_attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `item_shippable` int NOT NULL DEFAULT 0,
  `item_weight` decimal(10, 3) NOT NULL,
  `item_taxable` int NOT NULL DEFAULT 0,
  `item_downloadable` int NOT NULL DEFAULT 0,
  `variant_id` bigint UNSIGNED NOT NULL DEFAULT 0,
  `order_id` int NOT NULL DEFAULT 0,
  `user_id` int NOT NULL DEFAULT 0,
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `abandoned_reminder_1_sent_at` timestamp NULL DEFAULT NULL,
  `abandoned_reminder_2_sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `shopping_cart_log_cart_log_session_index`(`cart_log_session`(768) ASC) USING BTREE,
  INDEX `shopping_cart_log_variant_id_index`(`variant_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `site_label_sections`  (
  `id` smallint UNSIGNED NOT NULL,
  `name` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `site_label_sections_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `site_label_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_label_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `label_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `translation_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ai_translated',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `slt_unique`(`site_label_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `site_label_translations_site_label_id_index`(`site_label_id` ASC) USING BTREE,
  INDEX `site_label_translations_language_id_index`(`language_id` ASC) USING BTREE,
  CONSTRAINT `site_label_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `site_label_translations_site_label_id_foreign` FOREIGN KEY (`site_label_id`) REFERENCES `site_labels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 953 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `site_labels`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `label_key` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `section_id` smallint UNSIGNED NOT NULL DEFAULT 0,
  `file_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_default` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label_custom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `site_labels_label_key_unique`(`label_key` ASC) USING BTREE,
  INDEX `site_labels_section_id_index`(`section_id` ASC) USING BTREE,
  INDEX `site_labels_file_name_index`(`file_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 924 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `testimonial_translations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `testimonial_id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `author_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `author_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `translation_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `translated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `testimonial_translations_testimonial_id_language_id_unique`(`testimonial_id` ASC, `language_id` ASC) USING BTREE,
  INDEX `testimonial_translations_language_id_foreign`(`language_id` ASC) USING BTREE,
  CONSTRAINT `testimonial_translations_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `testimonial_translations_testimonial_id_foreign` FOREIGN KEY (`testimonial_id`) REFERENCES `cms_testimonials` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `ticket_attachments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `ticket_reply_id` bigint UNSIGNED NULL DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ticket_attachments_ticket_id_foreign`(`ticket_id` ASC) USING BTREE,
  INDEX `ticket_attachments_ticket_reply_id_foreign`(`ticket_reply_id` ASC) USING BTREE,
  CONSTRAINT `ticket_attachments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ticket_attachments_ticket_reply_id_foreign` FOREIGN KEY (`ticket_reply_id`) REFERENCES `ticket_replies` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `ticket_replies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `via` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web',
  `author_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `author_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ticket_replies_ticket_id_foreign`(`ticket_id` ASC) USING BTREE,
  INDEX `ticket_replies_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `ticket_replies_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `tickets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `assigned_to` bigint UNSIGNED NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `token` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `tickets_token_unique`(`token` ASC) USING BTREE,
  UNIQUE INDEX `tickets_reply_token_unique`(`reply_token` ASC) USING BTREE,
  INDEX `tickets_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `tickets_assigned_to_foreign`(`assigned_to` ASC) USING BTREE,
  INDEX `tickets_status_index`(`status` ASC) USING BTREE,
  INDEX `tickets_assigned_to_index`(`assigned_to` ASC) USING BTREE,
  CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `user_roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_roles_name_unique`(`name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL DEFAULT 1,
  `company` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipping_address1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipping_address2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipping_city` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shopping_postalcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipping_country` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `shipping_countrycode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `rewards_status` int NOT NULL DEFAULT 0,
  `new_user_discount` datetime NULL DEFAULT NULL,
  `preferred_discount_id` bigint UNSIGNED NULL DEFAULT NULL,
  `active` int NOT NULL DEFAULT 1,
  `user_token_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_token_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `shipping_state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `provider_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `stripe_customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paddle_customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_role_id_foreign`(`role_id` ASC) USING BTREE,
  INDEX `users_preferred_discount_id_foreign`(`preferred_discount_id` ASC) USING BTREE,
  CONSTRAINT `users_preferred_discount_id_foreign` FOREIGN KEY (`preferred_discount_id`) REFERENCES `discounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        DB::unprepared(<<<'SQL'
CREATE TABLE `warehouse_locations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `state_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `country_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'US',
  `zipcode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `shipstation_carrier_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `warehouse_locations_code_unique`(`code` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
SQL
);

        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'is_demo')) {
            Schema::table('products', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_variants') && !Schema::hasColumn('product_variants', 'is_demo')) {
            Schema::table('product_variants', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_images') && !Schema::hasColumn('product_images', 'is_demo')) {
            Schema::table('product_images', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_brands') && !Schema::hasColumn('product_brands', 'is_demo')) {
            Schema::table('product_brands', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_categories') && !Schema::hasColumn('product_categories', 'is_demo')) {
            Schema::table('product_categories', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_cross_selling') && !Schema::hasColumn('product_cross_selling', 'is_demo')) {
            Schema::table('product_cross_selling', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('product_reviews') && !Schema::hasColumn('product_reviews', 'is_demo')) {
            Schema::table('product_reviews', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('cms_testimonials') && !Schema::hasColumn('cms_testimonials', 'is_demo')) {
            Schema::table('cms_testimonials', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('cms_slideshows') && !Schema::hasColumn('cms_slideshows', 'is_demo')) {
            Schema::table('cms_slideshows', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('cms_slides') && !Schema::hasColumn('cms_slides', 'is_demo')) {
            Schema::table('cms_slides', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('cms_pages') && !Schema::hasColumn('cms_pages', 'is_demo')) {
            Schema::table('cms_pages', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('cms_downloads') && !Schema::hasColumn('cms_downloads', 'is_demo')) {
            Schema::table('cms_downloads', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('kb_articles') && !Schema::hasColumn('kb_articles', 'is_demo')) {
            Schema::table('kb_articles', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (Schema::hasTable('kb_categories') && !Schema::hasColumn('kb_categories', 'is_demo')) {
            Schema::table('kb_categories', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->tinyInteger('is_demo')->default(0);
            });
        }

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        $tables = Schema::getTableListing();
        foreach ($tables as $table) {
            if ($table !== 'migrations') {
                Schema::dropIfExists($table);
            }
        }
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
};
