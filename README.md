# Site Store Pro - Modern Laravel eCommerce & CMS Platform

[![Documentation](https://img.shields.io/badge/Documentation-docs.sitestorepro.com-blue?style=for-the-badge&logo=bookstack&logoColor=white)](https://docs.sitestorepro.com)

---

[Site Store Pro Demo Site](https://demo.sitestorepro.com/)

---
---

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](#license)
[![Laravel](https://img.shields.io/badge/Laravel-13-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4.svg)](https://php.net)

Site Store Pro is a production-ready **eCommerce, CMS, and Helpdesk platform** built on **Laravel 13** and **Livewire 3**.

| Capability | Highlights |
| --- | --- |
| **CMS & Site Builder** | Dynamic page management, drag-and-drop section builder, built-in **OpenAI AI Content Generation**. |
| **Multi-Language & AI** | Full localization with manual translation suite and **1-click batch AI auto-translation** (OpenAI API). |
| **eCommerce & Products** | Sell unlimited **Physical Goods**, **Digital Downloads**, and **Event Tickets** with dependent variants and inventory tracking. |
| **Payments & Checkout** | Built-in support for **Stripe**, **Paddle**, and **PayPal** with tax, shipping, and discount code engines. |
| **Support & Tickets** | Complete support ticketing platform, agent queue management, and a self-service **Knowledge Base**. |
| **Administration** | Gated admin control panel with role-based access control. |

---

## Requirements

Before you begin, make sure your environment meets the following requirements:

| Requirement | Details |
| --- | --- |
| **PHP 8.3+** *(PHP 8.5 Recommended)* | Requires standard Laravel extensions: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`. |
| **Composer** | Latest stable version installed globally to manage PHP dependencies. |
| **Node.js & npm** | Node.js (LTS recommended) and npm for compiling frontend assets via Vite. |
| **Database** | MySQL 8.0+ or MariaDB 10.6+ (recommended for production), or SQLite (for local development). |

> [!NOTE]
> The `phpoffice/phpspreadsheet` package is required if you plan to use the bulk CSV/Excel product import and export feature. It is optional — see [Step 8](#8-optional-install-bulk-excel-file-import-support) below.

---

## Installation Steps

### 1. Clone the Repository
Clone the Site Store Pro repository to your local machine and navigate into the project directory:

```bash
git clone <repository-url> laravel-ecommerce
cd laravel-ecommerce
```

### 2. Install Dependencies
Install all backend and frontend dependencies:

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Configure Your Environment
Copy the example environment file and generate your application key:

```bash
cp .env.example .env
php artisan key:generate
```

Open the newly created `.env` file and update the database configuration to match your local setup:

```env
APP_NAME="Site Store Pro"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

> [!NOTE]
> If you are using SQLite for local development, set `DB_CONNECTION=sqlite` and ensure a `database/database.sqlite` file exists:
> ```bash
> touch database/database.sqlite
> ```

### 4. Run Database Migrations and Seed
Run a fresh migration and seed the database with baseline default data:

```bash
php artisan migrate:fresh --seed
```

The seeder creates default roles, core configuration, site label sections, multilingual translations (English, Spanish, French), default CMS pages (Privacy Policy, Terms, Shipping, Returns, About Us, Contact Us), checkout options, email templates, and the default administrator account. A fresh install completes in under 2 seconds.

> [!WARNING]
> `migrate:fresh` will **drop all existing tables** and rebuild from scratch. Never run this command on a production database or any database containing data you need to keep.

### 5. (Optional) Load Developer QA Seed Data
If you want to install a demo storefront populated with sample products, variants, categories, brands, testimonials, slideshows, digital downloads, and 24 sample product reviews:

```bash
php artisan db:seed --class=DemoStoreSeeder
```

### 6. Compile Frontend Assets
Build the frontend assets using Vite:

```bash
# For local development with Hot Module Replacement (HMR)
npm run dev

# For production deployment
npm run build
```

### 7. (Optional) Install Payment Provider SDKs
Site Store Pro supports Stripe and Paddle as built-in payment providers:

```bash
# Stripe Only
composer require stripe/stripe-php

# Paddle Only
composer require paddlehq/paddle-php-sdk

# Both Stripe and Paddle
composer require stripe/stripe-php paddlehq/paddle-php-sdk
```

> [!NOTE]
> Payment provider credentials (API keys, publishable keys, webhook secrets) are configured via your `.env` file or Admin Settings after the respective SDK is installed.

### 8. (Optional) Install Bulk Excel File Import Support
To enable bulk product import and export via Excel/CSV spreadsheet files, install the `phpoffice/phpspreadsheet` package:

```bash
composer require phpoffice/phpspreadsheet
```

### 9. Create Storage Symlink
In production (and recommended for local development too), create the public storage symlink so uploaded images and files are accessible via the browser:

```bash
php artisan storage:link
```

> [!WARNING]
> Without this symlink, product images and other uploaded media will **not** be publicly accessible.

---

## Default Admin Login

After running migrations and seeding, your Site Store Pro installation includes a default administrator account:

| Field | Value |
| --- | --- |
| **URL** | `/admin` |
| **Email** | `admin@support.local` |
| **Password** | `SampleUser12345#` |

> [!WARNING]
> Change the default admin password immediately after your first login, especially before deploying to any publicly accessible environment. Navigate to **Admin → Users** to update the admin account to a secure password.

---

## Quick Reference

<details>
<summary><strong>🚀 Full Installation Command Sequence</strong></summary>

```bash
# 1. Clone and enter the project
git clone <repository-url> laravel-ecommerce
cd laravel-ecommerce

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Run migrations and seed
php artisan migrate:fresh --seed

# 5. Compile production assets
npm run build

# 6. Create storage symlink
php artisan storage:link
```
</details>

<details>
<summary><strong>📦 Optional Extras</strong></summary>

```bash
# Demo Store Seeding
php artisan db:seed --class=DemoStoreSeeder

# Stripe payment SDK
composer require stripe/stripe-php

# Paddle payment SDK
composer require paddlehq/paddle-php-sdk

# Bulk import support
composer require phpoffice/phpspreadsheet
```
</details>

---

## License

The MIT License (MIT)

Copyright (c) 2026 Visperity LLC.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
