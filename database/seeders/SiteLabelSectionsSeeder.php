<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteLabelSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [1,  'Navigation & Header',         'navigation',      'Main navigation, header, menus, and brand/category widgets', 1],
            [2,  'Footer',                       'footer',          'Footer links and copyright text',                            2],
            [3,  'Shopping Cart',                'shopping-cart',   'Shopping cart and slide cart labels',                        3],
            [4,  'Checkout',                     'checkout',        'Checkout form fields and labels',                            4],
            [5,  'Order Review & Success',        'order-review',    'Order review, payment, and success page labels',             5],
            [6,  'Shop Catalog',                 'shop-catalog',    'Product catalog filters, sorting, and display labels',       6],
            [7,  'Product Details & Buy Box',    'product-details', 'Product page, buy box, gallery, reviews labels',            7],
            [8,  'My Account & Dashboard',       'account',         'Customer account and dashboard labels',                      8],
            [9,  'Support & Tickets',            'support',         'Support ticket labels',                                      9],
            [10, 'Knowledge Base',               'knowledge-base',  'Knowledge base and article labels',                         10],
            [11, 'Auth Pages',                   'auth',            'Login, register, password reset and profile labels',        11],
            [12, 'CMS Pages',                    'cms',             'CMS page, category, tag and access labels',                 12],
            [13, 'Plugin Display Templates',     'plugins',         'Display plugin template labels',                            13],
            [14, 'Live Search Plugin',           'live-search',     'Live Search Plugin',                                        14],
        ];

        $now = now();
        foreach ($sections as [$id, $name, $slug, $desc, $sort]) {
            DB::table('site_label_sections')->updateOrInsert(
                ['id' => $id],
                [
                    'name'        => $name,
                    'slug'        => $slug,
                    'description' => $desc,
                    'sort_order'  => $sort,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]
            );
        }
    }
}
