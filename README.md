# Site Store Pro - Modern Laravel eCommerce & CMS Platform

[![Documentation](https://img.shields.io/badge/Documentation-docs.sitestorepro.com-blue?style=for-the-badge&logo=bookstack&logoColor=white)](https://docs.sitestorepro.com)

---

[Site Store Pro Demo Site](https://demo.sitestorepro.com/)

---
---

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](#license)
[![Laravel](https://img.shields.io/badge/Laravel-13-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4.svg)](https://php.net)

## Table of Contents

- [About Site Store Pro](#about-site-store-pro)
- [Complete Control of Your Online Business](#complete-control-of-your-online-business)
  - [Key Features](#key-features)
- [Requirements](#requirements)
- [Installation Steps](#installation-steps)
  - [1. Clone the Repository](#1-clone-the-repository)
  - [2. Install Dependencies](#2-install-dependencies)
  - [3. Configure Your Environment](#3-configure-your-environment)
  - [4. Run Database Migrations and Seed](#4-run-database-migrations-and-seed)
  - [5. (Optional) Load Developer QA Seed Data](#5-optional-load-developer-qa-seed-data)
  - [6. Compile Frontend Assets](#6-compile-frontend-assets)
  - [7. (Optional) Install Payment Provider SDKs](#7-optional-install-payment-provider-sdks)
  - [8. (Optional) Install Bulk Excel File Import Support](#8-optional-install-bulk-excel-file-import-support)
  - [9. Create Storage Symlink](#9-create-storage-symlink)
- [Default Admin Login](#default-admin-login)
- [Quick Reference](#quick-reference)
- [Local Docker Development Setup Guide](#local-docker-development-setup-guide)
  - [Step 1: Install System Prerequisites](#step-1-install-system-prerequisites)
  - [Step 2: Prepare Your Local Repository](#step-2-prepare-your-local-repository)
  - [Step 3: Add the Configuration Files](#step-3-add-the-configuration-files)
  - [Step 4: Configure Your Local .env](#step-4-configure-your-local-env)
  - [Step 5: Start and Seed the Architecture](#step-5-start-and-seed-the-architecture)
  - [Step 6: Default Admin Login (Docker)](#step-6-default-admin-login)
  - [Step 7: Environment Lifecycle Commands](#step-7-environment-lifecycle-commands)
- [License](#license)

---

## About Site Store Pro

**Site Store Pro** is a production-ready **eCommerce, CMS, and Helpdesk platform** built with **Laravel 13** and **Livewire 3**.

Originally launched as a PHP shopping cart in **2005**, Site Store Pro has evolved through more than **20 years of real-world eCommerce usage powering thousands of online stores worldwide** into a modern, scalable, and fully open-source platform for today's online businesses.

The current Laravel platform modernizes the proven Site Store Pro foundation while preserving the flexibility and advanced functionality developed by [**Kevin Rounsavelle**](https://github.com/kevin-rounsavelle) and the team at [**Visperity**](https://visperity.com). 

The Site Store Pro Laravel platform brings eCommerce, content management, and customer support together in a single, unified application.

## Complete Control of Your Online Business

Site Store Pro is designed to give businesses **complete ownership and control** over every aspect of their online operation. From choosing where and how the platform is hosted to managing customer and business data, configuring the storefront, and selecting payment processors, you remain in control.

Site Store Pro makes it simple to integrate your current branding either through direct theme modification via the web-based admin settings and/or by modifying the Tailwind CSS and site blade files directly. You are not limited to a pre-defined set of design templates and can customize the appearance of the store to match any design requirement.

With Site Store Pro, there are no restrictions tying your business to a specific hosting provider, payment gateway, or proprietary ecosystem. Site Store Pro gives you the flexibility to integrate the exact appearance, infrastructure, and features that best fit your specific business requirements.

### Key Features

- **Full-Featured eCommerce** — Sell physical and digital products through a flexible, scalable storefront.
- **Content Management System** — Create, manage, and publish the content that powers your website.
- **Integrated Helpdesk** — Provide customer support through a built-in ticketing and support system.
- **Laravel 13** — Built on the latest generation of the Laravel framework.
- **Livewire 3** — Deliver modern, interactive interfaces without the complexity of a traditional JavaScript-heavy application.
- **Open Source** — Maintain complete control over your application, data, and platform.

<br>

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
Clone the Site Store Pro repository to your local machine (or online dev server) and navigate into the project directory:

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

Open the newly created `.env` file and update the database configuration to match your db setup:

```env
APP_NAME="Your Online Store Name"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

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
Site Store Pro supports Stripe, Paddle and PayPal as built-in payment providers:

```bash
# Stripe Only
composer require stripe/stripe-php

# Paddle Only
composer require paddlehq/paddle-php-sdk

# Both Stripe and Paddle
composer require stripe/stripe-php paddlehq/paddle-php-sdk

**Paypal is also built-in but does not require a composer installation.**
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
> Change the default admin password immediately after your first login, especially before deploying to any publicly accessible environment. To change the temporary password: Click on Top Right Green Dot (Next To Light/Dark Mode Icon), then click on 'My Profile' to set a secure password.

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

# Local Docker Development Setup Guide

This guide details how to set up and run the Site Store Pro Laravel eCommerce application locally in an isolated multi-container environment using Docker.

---

## Step 1: Install System Prerequisites

### For Windows Users
Docker requires Windows Subsystem for Linux (WSL 2) to run efficiently.
1. Open PowerShell as an Administrator and execute:
   ```bash
   wsl --install
   ```
2. Restart your computer when the process completes.
3. Download and install Docker Desktop for Windows.
4. During installation, verify that the "Use WSL 2 instead of Hyper-V" setting is enabled.

### For macOS Users
Docker runs natively via the macOS Hypervisor framework.
1. Download the correct version of Docker Desktop for Mac:
   * Mac with Apple Silicon (M1, M2, M3, M4 chips)
   * Mac with Intel chip
2. Double-click the downloaded `.dmg` file, drag the Docker icon into your Applications folder, and launch it.
3. Grant the required privileged permissions when prompted by macOS.

---

## Step 2: Prepare Your Local Repository

1. Open your terminal (PowerShell on Windows, or Terminal on macOS) and clone the application:
   ```bash
   git clone https://github.com
   cd laravel-ecommerce
   ```

2. Initialize your local configuration file from the template:
   * Windows (PowerShell): `Copy-Item .env.example .env`
   * macOS (Terminal): `cp .env.example .env`

3. Create the Nginx reverse-proxy configuration folder structure:
   * Windows (PowerShell): `New-Item -Path "docker/nginx" -ItemType "directory" -Force`
   * macOS (Terminal): `mkdir -p docker/nginx`

---

## Step 3: Add the Configuration Files

Create the following three files in your project root folder:

### 1. `Dockerfile`
```dockerfile
# === STAGE 1: Frontend Asset Builder ===
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# === STAGE 2: PHP Dependency Application ===
FROM php:8.3-fpm-alpine AS backend-builder
WORKDIR /var/www
RUN apk add --no-cache git unzip libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev icu-dev curl-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql bcmath gd intl
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json ./
COPY composer.loc[k] ./ 
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts --ignore-platform-reqs
COPY . .

# === STAGE 3: Final Production Image ===
FROM php:8.3-fpm-alpine
WORKDIR /var/www
RUN apk add --no-cache libzip libpng libjpeg-turbo freetype bcmath icu-libs curl \
    && apk add --no-cache --virtual .build-deps libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev icu-dev curl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql bcmath gd intl \
    && apk del .build-deps
COPY --from=backend-builder /var/www /var/www
COPY --from=frontend-builder /app/public/build /var/www/public/build
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
EXPOSE 8080
CMD ["php-fpm"]
```

### 2. `docker-compose.yml`
```yaml
services:
  app:
    build:
      context: .
      target: backend-builder
    container_name: sitestore-app
    restart: unless-stopped
    environment:
      SERVICE_NAME: app
      DB_HOST: mysql
      REDIS_HOST: redis
    volumes:
      - .:/var/www
    networks:
      - sitestore-network

  queue-worker:
    build:
      context: .
      target: backend-builder
    container_name: sitestore-worker
    restart: unless-stopped
    command: php /var/www/artisan queue:work --verbose --tries=3 --timeout=90
    environment:
      SERVICE_NAME: queue-worker
      DB_HOST: mysql
      REDIS_HOST: redis
    volumes:
      - .:/var/www
    depends_on:
      - app
      - mysql
      - redis
    networks:
      - sitestore-network

  webserver:
    image: nginx:alpine
    container_name: sitestore-webserver
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./docker/nginx:/etc/nginx/conf.d/
    depends_on:
      - app
    networks:
      - sitestore-network

  mysql:
    image: mysql:8.0
    container_name: sitestore-db
    restart: unless-stopped
    ports:
      - "3306:3306"
    environment:
      MYSQL_DATABASE: sitestore_db
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: sitestore_user
      MYSQL_PASSWORD: user_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - sitestore-network

  redis:
    image: redis:alpine
    container_name: sitestore-redis
    restart: unless-stopped
    ports:
      - "6379:6379"
    networks:
      - sitestore-network

networks:
  sitestore-network:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

### 3. `docker/nginx/default.conf`
```nginx
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\..+)($);
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

---

## Step 4: Configure Your Local `.env`

Open your local `.env` file and verify that the core connection strings map correctly to Docker's internal container routing network.

### CRITICAL SECURITY WARNING
The values listed below (such as `sitestore_db`, `root_password`, and `user_password`) match the default variables provided in the `docker-compose.yml` file. **These configurations are provided for local development example purposes only.** 

You must change these credentials to unique, secure strings for your specific install. Never use these default passwords in a production environment or any public-facing server.

```ini
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
# Change these values for safety:
DB_DATABASE=sitestore_db
DB_USERNAME=sitestore_user
DB_PASSWORD=user_password

REDIS_HOST=redis
REDIS_PORT=6379

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## Step 5: Start and Seed the Architecture

1. Verify that your Docker Desktop software dashboard status icon shows that the engine is active and running.
2. Clear out any stale, broken, or cached Docker build layers to guarantee a fresh initialization:
   ```bash
   docker builder prune -f
   ```
3. Execute the compilation script in your project root terminal folder:
   ```bash
   docker compose up -d --build
   ```
4. Run the application core encryption algorithms and core database schemas:
   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```
5. **(Optional) Load Developer QA Seed Data**  
   If you want to install a demo storefront populated with sample products, variants, categories, brands, testimonials, slideshows, digital downloads, and 24 sample product reviews, execute the target class seeder inside the running container:
   ```bash
   docker compose exec app php artisan db:seed --class=DemoStoreSeeder
   ```
6. Access your running environment in your local web browser: **`http://localhost:8000`**

---

## Step 6: Default Admin Login

After running migrations and seeding, your Site Store Pro installation includes a default administrator account for initial access:

| Field | Value |
|---|---|
| **URL** | `http://localhost:8000/admin` |
| **Email** | `admin@support.local` |
| **Password** | `SampleUser12345#` |

### Warning
Change the default admin password immediately after your first login, especially before deploying to any publicly accessible or staging environment. 

To change the temporary password:
1. Navigate to the admin dashboard panels.
2. Click on the **Top Right Green Dot** (positioned next to the Light/Dark Mode toggle icon).
3. Click on **'My Profile'** from the dropdown menu to set a secure password.

---

## Step 7: Environment Lifecycle Commands

* **Stop the environment (preserves data):** `docker compose down`
* **Wipe the database volume to start fresh:** `docker compose down -v`
* **Tail active runtime system error logs:** `docker compose logs -f`

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
