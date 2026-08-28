# Release v1.2.1 — CMS Forms Multi-Language Engine, Storefront Dark Mode Architecture & Email Template Media Uploads

**Release Date:** August 28, 2026  
**Version:** 1.2.1

---

## Table of Contents

- [Overview & Key Highlights](#overview--key-highlights)
- [What's New & Enhancements](#whats-new--enhancements)
  - [1. CMS Forms & Fields Multi-Language Translation Engine](#1-cms-forms--fields-multi-language-translation-engine)
  - [2. Multi-Layer Dark/Light Mode Resolution & Zero-Flicker Architecture](#2-multi-layer-darklight-mode-resolution--zero-flicker-architecture)
  - [3. Customer Portal & Profile Dark Mode UI Contrast Overhaul](#3-customer-portal--profile-dark-mode-ui-contrast-overhaul)
  - [4. Email Template Media Uploads & Image Manager](#4-email-template-media-uploads--image-manager)
  - [5. Cookie Encryption & Middleware Optimization](#5-cookie-encryption--middleware-optimization)
- [Database Schema Changes & Migrations](#database-schema-changes--migrations)
- [Architecture & Key Components](#architecture--key-components)
  - [1. Multi-Language Form Integration](#1-multi-language-form-integration)
  - [2. Public Theme State & Anti-FOUC Pipeline](#2-public-theme-state--anti-fouc-pipeline)
  - [3. Email Template Media Handling](#3-email-template-media-handling)
- [Upgrade & Installation Instructions](#upgrade--installation-instructions)

---

## Overview & Key Highlights

Version **1.2.1** delivers full multi-language translation integration for CMS dynamic forms and form fields (supporting AI automated batch translation via OpenAI for labels, instructions, confirmation messages, and choice options), an anti-flicker multi-layer Dark/Light Mode state resolution engine across all public and CMS storefront templates, comprehensive dark mode readability and contrast improvements for customer dashboards and profile management, and direct media upload capabilities for transactional email templates.

---

## What's New & Enhancements

### 1. CMS Forms & Fields Multi-Language Translation Engine
- **Direct Form Translations Manager:** Added a dedicated **Form Translations** tab directly on the CMS Form Edit screen (`/admin/cms-forms/{id}/edit`). Admins can choose any active system language via language pills, manually edit translated form settings and field labels/instructions/options, and generate instant single-language AI translations via OpenAI with a single click.
- **Language Manager & Coverage Integration:** Integrated **CMS Forms** (`cms_forms`) and **Form Fields** (`cms_form_fields`) into `/admin/languages` (coverage cards, progress bars, bulk translate, and missing translation counts) and `/admin/languages/{id}/translations` (stat cards, item listings, and one-click translation actions).
- **Granular Field Localization:** Translatable attributes include Form Titles, Submit Button Labels, Confirmation Messages (HTML), Field Labels, Helper Instructions, Validation Error Messages, HTML Above Field blocks, and Select/Radio/Checkbox choice options.
- **Automated AI Translation:** Connected to `TranslationService` with OpenAI batch processing (`gpt-4o-mini`) to translate form fields and nested option arrays with a single click.
- **Localized Shortcode Rendering:** Updated `ShortcodeProcessor` (`[cms-form id=N]`) and `form-embed.blade.php` to eager-load `withCurrentTranslations()` so forms render dynamically in the customer's selected language.

### 2. Multi-Layer Dark/Light Mode Resolution & Zero-Flicker Architecture
- **Persistent Theme Memory for Guests & Members:** Fixed guest session theme persistence by establishing a hierarchical theme resolution cascade:
  1. Authenticated User Profile Preference (`theme_preference`)
  2. Active Session State (`session('frontend_theme')`)
  3. Raw Client Cookie (`$_COOKIE['frontend_theme']`)
  4. Global CMS Default Setting (`CmsSetting::isEnabled('frontend_dark_mode')`)
- **Zero-FOUC Head Scripts:** Injected synchronous anti-flicker scripts into the `<head>` of all public templates (`home.blade.php`, `cms.blade.php`, `cms-category.blade.php`, `cms-tag.blade.php`, `public.blade.php`, and `layouts/app.blade.php`) ensuring the `.dark` class is applied to `<html>` prior to stylesheet painting.
- **Admin vs. Customer Theme Separation:** Customer Portal accounts (`role_id` 1 & 2) now inherit storefront theme preferences, while Admin/Staff accounts strictly follow Admin Dark Mode settings.

### 3. Customer Portal & Profile Dark Mode UI Contrast Overhaul
- **Dashboard Table Consistency:** Synchronized table headers (`<thead class="bg-slate-50/50 dark:bg-slate-700/60">`), column labels (`text-slate-400 dark:text-slate-300`), border dividers (`dark:divide-slate-700/60`), and row hover states across **Orders** (`?tab=orders`), **Downloads** (`?tab=downloads`), and **Support Tickets** (`?tab=tickets`).
- **Profile & Form Readability:** Updated `/profile` components (`input-label.blade.php`, `text-input.blade.php`, `secondary-button.blade.php`, `modal.blade.php`) to use high-contrast slate color schemes (`dark:text-slate-100`, `dark:bg-slate-900`, `dark:border-slate-600`), resolving muted or unreadable text in dark mode.

### 4. Email Template Media Uploads & Image Manager
- **Direct Image Uploads:** Enhanced `AdminEmailTemplateEdit.php` with direct file uploading for email header banners, body illustrations, and footer images.
- **S3 & Local Storage Support:** Uploaded assets are processed, named, and stored according to configured storage disks (Local or Amazon S3) and inserted directly into email template attributes.
- **CSRF Token Exemption:** Configured CSRF verification exemptions for CMS and email media upload endpoints to ensure uninterrupted AJAX file handling.

### 5. Cookie Encryption & Middleware Optimization
- **Cookie Exemption Whitelist:** Updated `bootstrap/app.php` with explicit exceptions in `encryptCookies()` for `frontend_theme`, `theme_mode`, `app_theme`, `cart_session_id`, and `app_language`.
- **Client-Side JS Compatibility:** Prevents decryption mismatches when cookies are set or toggled directly via frontend JavaScript handlers.

---

## Database Schema Changes & Migrations

Version 1.2.1 introduces database migration `2026_08_28_000001_create_cms_form_translations_tables.php`:

```php
Schema::create('cms_form_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cms_form_id')->constrained('cms_forms')->onDelete('cascade');
    $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
    $table->string('name')->nullable();
    $table->string('submit_button_label')->nullable();
    $table->text('confirmation_message')->nullable();
    $table->string('translation_status', 20)->default('pending');
    $table->timestamp('translated_at')->nullable();
    $table->timestamps();

    $table->unique(['cms_form_id', 'language_id']);
    $table->index('language_id');
});

Schema::create('cms_form_field_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('cms_form_field_id')->constrained('cms_form_fields')->onDelete('cascade');
    $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
    $table->string('label')->nullable();
    $table->text('instructions')->nullable();
    $table->string('required_error_message')->nullable();
    $table->text('html_above')->nullable();
    $table->json('options')->nullable();
    $table->string('translation_status', 20)->default('pending');
    $table->timestamp('translated_at')->nullable();
    $table->timestamps();

    $table->unique(['cms_form_field_id', 'language_id']);
    $table->index('language_id');
});
```

---

## Architecture & Key Components

### 1. Multi-Language Form Integration
- **`app/Models/CmsFormTranslation.php` & `app/Models/CmsFormFieldTranslation.php`**: Child translation models implementing foreign keys and language relationships.
- **`app/Models/CmsForm.php` & `app/Models/CmsFormField.php`**: Integrated `HasTranslations` trait with transparent fallback to native model attributes when non-default translations are active.
- **`app/Services/TranslationService.php`**: Added translatable field mappings, foreign key lookups, and specialized array translation for dropdown choices, radio buttons, and checkbox groups.
- **`app/Livewire/AdminLanguageTranslations.php`**: Registered `cms_forms` and `cms_form_fields` translation stats, single-item translation queues, and bulk translation triggers.
- **`app/Plugins/Support/ShortcodeProcessor.php`**: Eager-loads form translations with `withCurrentTranslations()` during `[cms-form id=N]` shortcode processing.

### 2. Public Theme State & Anti-FOUC Pipeline
- **`resources/views/pages/cms.blade.php`**, **`cms-category.blade.php`**, **`cms-tag.blade.php`**, **`home.blade.php`**: Standardized multi-layer theme detection and instant head script injection.
- **`resources/views/layouts/app.blade.php`**: Tailored customer account dark mode preferences based on user role (`role_id` 1 & 2).
- **`resources/views/livewire/user-dashboard.blade.php`**: Unified background, borders, text contrast, and table styling for all user account tabs.
- **`resources/views/components/input-label.blade.php`** & **`text-input.blade.php`**: Applied dark theme classes (`dark:text-slate-200`, `dark:bg-slate-900`, `dark:border-slate-600`).

### 3. Email Template Media Handling
- **`app/Livewire/AdminEmailTemplateEdit.php`**: Handles file uploads, image validation, storage routing, and email template URL updates.
- **`bootstrap/app.php`**: Exempts media upload endpoints from CSRF verification and whitelists public theme/language cookies from encryption.

---

## Upgrade & Installation Instructions

To update an existing installation to version **1.2.1**:

1. **Run Database Migrations:**
   ```bash
   php artisan migrate --force
   ```

2. **Clear and Rebuild Caches:**
   ```bash
   php artisan optimize:clear
   php artisan view:clear
   php artisan config:clear
   ```

3. **Verify Translations (Optional):**
   Navigate to **Admin &rarr; Languages &rarr; [Language] &rarr; Translations Dashboard** to translate any existing CMS forms and form fields into your active site languages.
