<?php

namespace App\Livewire;

use App\Models\CmsListMenu;
use App\Models\CmsListMenuItem;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminCmsListMenuEdit extends Component
{
    public int $menuId;
    public string $menuName = '';
    public string $customCss = '';
    
    // Items data state bound to the form inputs
    public array $itemsData = [];

    // Search state for slide-out panel
    public string $searchQuery = '';
    public string $searchScope = 'all';

    public function mount(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $menu = CmsListMenu::findOrFail($id);
        $this->menuId = $menu->id;
        $this->menuName = $menu->name;
        $this->customCss = $menu->custom_css ?? '';

        $this->loadItemsData();
    }

    public function loadItemsData(): void
    {
        $items = CmsListMenuItem::where('cms_list_menu_id', $this->menuId)
            ->orderBy('sort_val')
            ->get();

        $this->itemsData = [];
        foreach ($items as $item) {
            $this->itemsData[$item->id] = [
                'id' => $item->id,
                'list_item' => $item->list_item ?? '',
            ];
        }
    }

    public function addItem(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        // Validate and save all current items first for safety
        $this->validate([
            'itemsData.*.list_item' => 'nullable|string',
        ]);
        foreach ($this->itemsData as $id => $data) {
            CmsListMenuItem::where('id', $id)
                ->where('cms_list_menu_id', $this->menuId)
                ->update(['list_item' => $data['list_item']]);
        }

        $maxSort = CmsListMenuItem::where('cms_list_menu_id', $this->menuId)->max('sort_val') ?: 0;
        
        CmsListMenuItem::create([
            'cms_list_menu_id' => $this->menuId,
            'list_item' => '',
            'sort_val' => $maxSort + 1.0,
        ]);

        $this->loadItemsData();
        $this->dispatch('toast', message: 'All items saved and new item added.', type: 'success');
    }

    public function removeItem(int $itemId): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        CmsListMenuItem::where('id', $itemId)
            ->where('cms_list_menu_id', $this->menuId)
            ->delete();

        $this->loadItemsData();
        $this->dispatch('toast', message: 'List item deleted.', type: 'success');
    }

    public function saveItem(int $itemId): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            "itemsData.{$itemId}.list_item" => 'nullable|string',
        ]);

        CmsListMenuItem::where('id', $itemId)
            ->where('cms_list_menu_id', $this->menuId)
            ->update(['list_item' => $this->itemsData[$itemId]['list_item']]);

        // Reload data to reset dirty state in UI
        $this->loadItemsData();
        $this->dispatch('toast', message: 'List item saved successfully.', type: 'success');
    }

    public function saveMenu(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $this->validate([
            'menuName' => 'required|string|max:255',
            'customCss' => 'nullable|string',
            'itemsData.*.list_item' => 'nullable|string',
        ]);

        $menu = CmsListMenu::findOrFail($this->menuId);
        $menu->update([
            'name' => trim($this->menuName),
            'custom_css' => $this->customCss,
        ]);

        // Save each item
        foreach ($this->itemsData as $itemId => $data) {
            CmsListMenuItem::where('id', $itemId)
                ->where('cms_list_menu_id', $this->menuId)
                ->update(['list_item' => $data['list_item']]);
        }

        $this->dispatch('toast', message: 'Menu settings and items saved successfully.', type: 'success');
    }

    public function updateItemOrder(array $order): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        foreach ($order as $position => $itemId) {
            CmsListMenuItem::where('id', $itemId)
                ->where('cms_list_menu_id', $this->menuId)
                ->update(['sort_val' => $position + 1.0]);
        }

        // Reload the sorted items
        $this->loadItemsData();
        $this->dispatch('toast', message: 'Items reordered successfully.', type: 'success');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        $searchResults = [];
        if (!empty($this->searchQuery)) {
            $q = '%' . $this->searchQuery . '%';
            
            // Set dynamic query limits depending on chosen scope (never exceeding 25 total records)
            $pagesLimit = ($this->searchScope === 'all') ? 5 : 25;
            $productsLimit = ($this->searchScope === 'all') ? 10 : 25;
            $categoriesLimit = ($this->searchScope === 'all') ? 5 : 25;
            $brandsLimit = ($this->searchScope === 'all') ? 5 : 25;

            if ($this->searchScope === 'all' || $this->searchScope === 'pages') {
                $pages = CmsPage::where('title', 'like', $q)->limit($pagesLimit)->get();
                foreach ($pages as $p) {
                    $searchResults[] = [
                        'type' => 'Page',
                        'id' => $p->id,
                        'title' => $p->title,
                        'badgeColor' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'shortcode' => '[page:' . $p->id . ' label="' . e($p->title) . '"]'
                    ];
                }
            }

            if ($this->searchScope === 'all' || $this->searchScope === 'products') {
                $products = Product::where('title', 'like', $q)->limit($productsLimit)->get();
                foreach ($products as $p) {
                    $searchResults[] = [
                        'type' => 'Product',
                        'id' => $p->id,
                        'title' => $p->title,
                        'badgeColor' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'shortcode' => '[product:' . $p->id . ' label="' . e($p->title) . '"]'
                    ];
                }
            }

            if ($this->searchScope === 'all' || $this->searchScope === 'categories') {
                $categories = Category::where('name', 'like', $q)->limit($categoriesLimit)->get();
                foreach ($categories as $c) {
                    $searchResults[] = [
                        'type' => 'Category',
                        'id' => $c->id,
                        'title' => $c->name,
                        'badgeColor' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'shortcode' => '[category:' . $c->id . ' label="' . e($c->name) . '"]'
                    ];
                }
            }

            if ($this->searchScope === 'all' || $this->searchScope === 'brands') {
                $brands = Brand::where('name', 'like', $q)->limit($brandsLimit)->get();
                foreach ($brands as $b) {
                    $searchResults[] = [
                        'type' => 'Brand',
                        'id' => $b->id,
                        'title' => $b->name,
                        'badgeColor' => 'bg-violet-100 text-violet-800 border-violet-200',
                        'shortcode' => '[brand:' . $b->id . ' label="' . e($b->name) . '"]'
                    ];
                }
            }

            // Cap the final merged results to exactly 25 to guarantee performance
            if (count($searchResults) > 25) {
                $searchResults = array_slice($searchResults, 0, 25);
            }
        }

        return view('livewire.admin-cms-list-menu-edit', [
            'searchResults' => $searchResults
        ]);
    }
}
