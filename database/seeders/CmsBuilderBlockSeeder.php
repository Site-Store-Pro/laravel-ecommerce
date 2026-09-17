<?php

namespace Database\Seeders;

use App\Models\CmsBuilderBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CmsBuilderBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('cms_builder_blocks')) {
            return;
        }

        $blocks = [
            // Header Containers
            [
                'title'             => 'Top Sharing / Announcement Bar',
                'target_element'    => 'top_sharing_container',
                'type'              => 1,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 1,
                'sort_tablet'       => 1,
                'sort_mobile'       => 1,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Main Site Header Bar',
                'target_element'    => 'site_header_container',
                'type'              => 1,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 2,
                'sort_tablet'       => 2,
                'sort_mobile'       => 2,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Top Navigation Menu Row',
                'target_element'    => 'top_nav_container',
                'type'              => 1,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 3,
                'sort_tablet'       => 3,
                'sort_mobile'       => 3,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],

            // Header Elements
            [
                'title'             => 'Header Logo Slot',
                'target_element'    => 'header_logo',
                'type'              => 2,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'content_desktop'   => '<x-site-logo />',
                'sort_desktop'      => 1,
                'sort_tablet'       => 1,
                'sort_mobile'       => 1,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Header Search Bar',
                'target_element'    => 'header_search',
                'type'              => 2,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 2,
                'sort_tablet'       => 2,
                'sort_mobile'       => 2,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Header Column #1 (Left Slot)',
                'target_element'    => 'header_col1',
                'type'              => 2,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 3,
                'sort_tablet'       => 3,
                'sort_mobile'       => 3,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Header Column #2 (Right Slot)',
                'target_element'    => 'header_col2',
                'type'              => 2,
                'section_type'      => 'header',
                'is_placeholder'    => false,
                'sort_desktop'      => 4,
                'sort_tablet'       => 4,
                'sort_mobile'       => 4,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            // Footer Columns & Rows
            [
                'title'             => 'Footer Column #1 (Brand Info)',
                'target_element'    => 'footer_col1',
                'type'              => 5,
                'section_type'      => 'footer',
                'is_placeholder'    => false,
                'content_desktop'   => '<h4 class="font-bold text-base mb-3">About Store</h4><p class="text-xs text-slate-500">Premium store for quality products.</p>',
                'sort_desktop'      => 1,
                'sort_tablet'       => 1,
                'sort_mobile'       => 1,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Footer Column #2 (Quick Links)',
                'target_element'    => 'footer_col2',
                'type'              => 5,
                'section_type'      => 'footer',
                'is_placeholder'    => false,
                'content_desktop'   => '<h4 class="font-bold text-base mb-3">Quick Links</h4><ul class="space-y-1 text-xs"><li><a href="/shop">Shop</a></li><li><a href="/about">About Us</a></li></ul>',
                'sort_desktop'      => 2,
                'sort_tablet'       => 2,
                'sort_mobile'       => 2,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Footer Primary Columns Container',
                'target_element'    => 'site_footer_columns_primary',
                'type'              => 4,
                'section_type'      => 'footer',
                'is_placeholder'    => false,
                'sort_desktop'      => 1,
                'sort_tablet'       => 1,
                'sort_mobile'       => 1,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
            [
                'title'             => 'Footer Copyright Bar',
                'target_element'    => 'footer_row4',
                'type'              => 4,
                'section_type'      => 'footer',
                'is_placeholder'    => false,
                'content_desktop'   => '<div>&copy; ' . date('Y') . ' All rights reserved.</div>',
                'sort_desktop'      => 4,
                'sort_tablet'       => 4,
                'sort_mobile'       => 4,
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ],
        ];

        foreach ($blocks as $data) {
            CmsBuilderBlock::firstOrCreate(
                ['target_element' => $data['target_element'], 'section_type' => $data['section_type']],
                $data
            );
        }
    }
}
