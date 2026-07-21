<?php

namespace Database\Seeders;

use App\Models\KbCategory;
use Illuminate\Database\Seeder;

class KbCategorySeeder extends Seeder
{
    public function run(): void
    {
        KbCategory::create([
            'id'          => 1,
            'name'        => 'Getting Started',
            'slug'        => 'getting-started',
            'description' => 'Platform setup, first steps, and account basics.',
            'sort_order'  => 10,
        ]);

        KbCategory::create([
            'id'          => 2,
            'name'        => 'Orders & Shop',
            'slug'        => 'orders-shop',
            'description' => 'Placing orders, checkout, digital downloads, and refunds.',
            'sort_order'  => 20,
        ]);

        KbCategory::create([
            'id'          => 3,
            'name'        => 'Support Tickets',
            'slug'        => 'support-tickets',
            'description' => 'Submitting tickets, email replies, and public ticket links.',
            'sort_order'  => 30,
        ]);

        KbCategory::create([
            'id'          => 4,
            'name'        => 'Admin & CMS',
            'slug'        => 'admin-cms',
            'description' => 'Managing pages, products, downloads, embeds, and settings.',
            'sort_order'  => 40,
        ]);
    }
}
