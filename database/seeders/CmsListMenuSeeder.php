<?php

namespace Database\Seeders;

use App\Models\CmsListMenu;
use App\Models\CmsListMenuItem;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class CmsListMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create "Company Info (Footer)" Menu
        $footerMenu = CmsListMenu::create([
            'name' => 'Company Info (Footer)',
            'custom_css' => "/* Custom style for Footer menu */\n#cms-menu-1 {\n    list-style-type: none;\n    padding-left: 0;\n}\n#cms-menu-1 li {\n    margin-bottom: 0.5rem;\n}",
        ]);

        $aboutPage = CmsPage::where('slug', 'about-us')->first();
        if ($aboutPage) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $footerMenu->id,
                'list_item' => '[page:' . $aboutPage->id . ' label="About Our Company"]',
                'sort_val' => 1.0,
            ]);
        }

        $contactPage = CmsPage::where('slug', 'contact')->first();
        if ($contactPage) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $footerMenu->id,
                'list_item' => '[page:' . $contactPage->id . ' label="Get In Touch"]',
                'sort_val' => 2.0,
            ]);
        }

        $privacyPage = CmsPage::where('slug', 'privacy')->first();
        if ($privacyPage) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $footerMenu->id,
                'list_item' => '[page:' . $privacyPage->id . ' label="Privacy Policy"]',
                'sort_val' => 3.0,
            ]);
        }

        // 2. Create "Featured Products List" Menu
        $productsMenu = CmsListMenu::create([
            'name' => 'Featured Products List',
            'custom_css' => "/* Featured Products List */\n.cms-list-menu-item a {\n    font-weight: 600;\n    color: #4f46e5;\n}",
        ]);

        $products = Product::limit(3)->get();
        $sort = 1.0;
        foreach ($products as $product) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $productsMenu->id,
                'list_item' => '[product:' . $product->id . ']',
                'sort_val' => $sort++,
            ]);
        }

        // 3. Create "Top Brands & Plugins" Menu
        $brandsMenu = CmsListMenu::create([
            'name' => 'Top Brands & Plugins',
            'custom_css' => "/* Custom stylesheet for brands list */\n#cms-menu-3 img {\n    max-height: 50px;\n    filter: grayscale(100%);\n}\n#cms-menu-3 img:hover {\n    filter: none;\n}",
        ]);

        $brands = Brand::limit(3)->get();
        $sort = 1.0;
        foreach ($brands as $brand) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $brandsMenu->id,
                'list_item' => '[brand:' . $brand->id . ' label="' . $brand->name . ' Storefront"]',
                'sort_val' => $sort++,
            ]);
        }

        // Add a plugin display list item
        CmsListMenuItem::create([
            'cms_list_menu_id' => $brandsMenu->id,
            'list_item' => '<div class="plugin-wrapper border p-4 rounded-xl">[plugin:brands display=list]</div>',
            'sort_val' => $sort++,
        ]);

        // 4. Create "Shop Categories" Menu
        $categoriesMenu = CmsListMenu::create([
            'name' => 'Shop Categories',
            'custom_css' => "",
        ]);

        $categories = Category::limit(3)->get();
        $sort = 1.0;
        foreach ($categories as $category) {
            CmsListMenuItem::create([
                'cms_list_menu_id' => $categoriesMenu->id,
                'list_item' => '[category:' . $category->id . ']',
                'sort_val' => $sort++,
            ]);
        }
    }
}
