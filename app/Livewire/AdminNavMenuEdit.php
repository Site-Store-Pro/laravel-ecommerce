<?php

namespace App\Livewire;

use App\Models\NavMenu;
use App\Models\NavItem;
use App\Models\CmsPage;
use App\Plugins\Support\PluginManager;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Edit Navigation Menu')]
class AdminNavMenuEdit extends Component
{
    public NavMenu $menu;

    // ─── Active tab ───────────────────────────────────────────────────────────
    public string $activeTab = 'items';

    // ─── Item form ────────────────────────────────────────────────────────────
    public bool   $showItemForm  = false;
    public ?int   $editingItemId = null;

    // Item form fields
    public ?int    $itemParentId    = null;
    public float   $itemPosition    = 0;
    public string  $itemLabel       = '';
    public string  $itemType        = 'link';
    public string  $itemUrl         = '';
    public string  $itemHtmlContent = '';
    public ?int    $itemCmsPageId   = null;
    public bool    $itemIsActive    = true;
    public bool    $itemNewTab      = false;
    public string  $itemVisibility  = 'all';
    public bool    $itemHideMobile  = false;
    public bool    $itemHideDesktop = false;
    public string  $itemCssClass    = '';
    public string  $itemAriaLabel   = '';
    public string  $itemPluginSlug  = '';

    // CMS page search
    public string $cmsPageSearch = '';

    // ─── Appearance form ──────────────────────────────────────────────────────
    public string $menuName        = '';
    public string $menuColorScheme = 'default';
    public string $menuCustomCss   = '';
    public bool   $menuSticky      = true;
    public bool   $menuShowLogo    = true;

    // ─── Flash ────────────────────────────────────────────────────────────────
    public string $successMessage = '';
    public string $errorMessage   = '';

    // ─── Mount ────────────────────────────────────────────────────────────────
    public function mount(NavMenu $menu): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $this->menu           = $menu;
        $this->menuName       = $menu->name;
        $this->menuColorScheme= $menu->color_scheme;
        $this->menuCustomCss  = $menu->custom_css ?? '';
        $this->menuSticky     = $menu->sticky;
        $this->menuShowLogo   = $menu->show_logo;
    }

    // ─── Computed ─────────────────────────────────────────────────────────────

    public function getItemsProperty()
    {
        $flat = NavItem::where('menu_id', $this->menu->id)
            ->orderBy('position')
            ->get();
        return NavItem::buildTree($flat);
    }

    public function getCmsPagesProperty()
    {
        $q = CmsPage::select('page_id', 'page_title', 'page_seo_link')
            ->where('page_active', 1)
            ->orderBy('page_title');
        if ($this->cmsPageSearch) {
            $q->where('page_title', 'like', '%' . $this->cmsPageSearch . '%');
        }
        return $q->limit(20)->get();
    }

    public function getTopNavPluginsProperty(): array
    {
        return app(PluginManager::class)->allTopNavPlugins();
    }

    public function getParentItemsProperty()
    {
        // Only parent-capable top-level items (not children themselves)
        return NavItem::where('menu_id', $this->menu->id)
            ->whereNull('parent_id')
            ->whereIn('item_type', ['parent', 'no_link'])
            ->orderBy('position')
            ->get();
    }

    // ─── Item CRUD ────────────────────────────────────────────────────────────

    public function openAddItem(?int $parentId = null): void
    {
        $this->resetItemForm();
        $this->itemParentId = $parentId;

        // Auto-assign position as max + 10
        $this->itemPosition = (NavItem::where('menu_id', $this->menu->id)
            ->where('parent_id', $parentId)
            ->max('position') ?? 0) + 10;

        $this->showItemForm  = true;
        $this->editingItemId = null;
    }

    public function editItem(int $id): void
    {
        $item = NavItem::findOrFail($id);

        $this->editingItemId    = $id;
        $this->itemParentId     = $item->parent_id;
        $this->itemPosition     = $item->position;
        $this->itemLabel        = $item->label;
        $this->itemType         = $item->item_type;
        $this->itemUrl          = $item->url ?? '';
        $this->itemHtmlContent  = $item->html_content ?? '';
        $this->itemCmsPageId    = $item->cms_page_id;
        $this->itemIsActive     = $item->is_active;
        $this->itemNewTab       = $item->open_in_new_tab;
        $this->itemVisibility   = $item->visibility;
        $this->itemHideMobile   = $item->hide_on_mobile;
        $this->itemHideDesktop  = $item->hide_on_desktop;
        $this->itemCssClass     = $item->css_class ?? '';
        $this->itemAriaLabel    = $item->aria_label ?? '';
        $this->itemPluginSlug   = $item->plugin_slug ?? '';

        $this->showItemForm = true;
    }

    public function saveItem(): void
    {
        $this->validate([
            'itemLabel'    => 'required|string',
            'itemType'     => 'required|string',
            'itemUrl'      => 'nullable|string|max:2000',
            'itemPosition' => 'required|numeric',
        ]);

        $data = [
            'menu_id'       => $this->menu->id,
            'parent_id'     => $this->itemParentId,
            'position'      => $this->itemPosition,
            'label'         => $this->itemLabel,
            'item_type'     => $this->itemType,
            'url'           => $this->itemUrl ?: null,
            'html_content'  => $this->itemHtmlContent ?: null,
            'cms_page_id'   => $this->itemCmsPageId,
            'is_active'     => $this->itemIsActive,
            'open_in_new_tab' => $this->itemNewTab,
            'visibility'    => $this->itemVisibility,
            'hide_on_mobile'  => $this->itemHideMobile,
            'hide_on_desktop' => $this->itemHideDesktop,
            'css_class'     => $this->itemCssClass ?: null,
            'aria_label'    => $this->itemAriaLabel ?: null,
            'plugin_slug'   => $this->itemPluginSlug ?: null,
        ];

        if ($this->editingItemId) {
            NavItem::where('id', $this->editingItemId)->update($data);
            $this->successMessage = 'Item updated.';
        } else {
            NavItem::create($data);
            $this->successMessage = 'Item added.';
        }

        $this->showItemForm = false;
        $this->resetItemForm();
    }

    public function deleteItem(int $id): void
    {
        // Delete children first
        NavItem::where('parent_id', $id)->delete();
        NavItem::destroy($id);
        $this->successMessage = 'Item deleted.';
    }

    public function toggleItemActive(int $id): void
    {
        $item = NavItem::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);
    }

    /**
     * Called by SortableJS via dispatch — receives the new ordered IDs array
     * and fractional-position-updates each item.
     */
    public function reorderItems(array $orderedIds, ?int $parentId = null): void
    {
        foreach ($orderedIds as $i => $id) {
            NavItem::where('id', $id)
                ->where('menu_id', $this->menu->id)
                ->update(['position' => ($i + 1) * 10, 'parent_id' => $parentId]);
        }
    }

    public function cancelItemForm(): void
    {
        $this->showItemForm = false;
        $this->resetItemForm();
    }

    protected function resetItemForm(): void
    {
        $this->editingItemId   = null;
        $this->itemParentId    = null;
        $this->itemPosition    = 0;
        $this->itemLabel       = '';
        $this->itemType        = 'link';
        $this->itemUrl         = '';
        $this->itemHtmlContent = '';
        $this->itemCmsPageId   = null;
        $this->itemIsActive    = true;
        $this->itemNewTab      = false;
        $this->itemVisibility  = 'all';
        $this->itemHideMobile  = false;
        $this->itemHideDesktop = false;
        $this->itemCssClass    = '';
        $this->itemAriaLabel   = '';
        $this->itemPluginSlug  = '';
        $this->cmsPageSearch   = '';
    }

    // ─── Appearance ───────────────────────────────────────────────────────────

    public function saveAppearance(): void
    {
        $this->validate([
            'menuName'        => 'required|string|max:255',
            'menuColorScheme' => 'required|string',
            'menuCustomCss'   => 'nullable|string',
        ]);

        $this->menu->update([
            'name'         => trim($this->menuName),
            'color_scheme' => $this->menuColorScheme,
            'custom_css'   => $this->menuCustomCss ?: null,
            'sticky'       => $this->menuSticky,
            'show_logo'    => $this->menuShowLogo,
        ]);

        $this->menu->refresh();
        $this->successMessage = 'Appearance saved.';
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin-nav-menu-edit', [
            'menu'           => $this->menu,
            'items'          => $this->items,
            'cmsPages'       => $this->cmsPages,
            'topNavPlugins'  => $this->topNavPlugins,
            'parentItems'    => $this->parentItems,
            'builtInTypes'   => NavItem::BUILT_IN_TYPES,
            'schemeKeys'     => array_keys(config('nav_schemes', [])),
        ]);
    }
}
