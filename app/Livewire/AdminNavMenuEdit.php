<?php

namespace App\Livewire;

use App\Models\NavMenu;
use App\Models\NavItem;
use App\Models\CmsPage;
use App\Models\Language;
use App\Models\NavItemTranslation;
use App\Plugins\Support\PluginManager;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Edit Navigation Menu')]
class AdminNavMenuEdit extends Component
{
    public NavMenu $menu;

    // ─── Translations ─────────────────────────────────────────────────────────
    public int   $translationLanguageId = 0;
    public array $translationBuffer     = [];
    public array $aiTranslating         = []; // tracks per-item AI loading state

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
    public string $menuName         = '';
    public string $menuColorScheme  = 'default';
    public string $menuCustomCss    = '';
    public bool   $menuSticky       = true;
    public string $stickyBodyOffset = '0px';
    public bool   $menuShowLogo     = true;
    public string $menuAlignment    = 'left';

    // ─── Flash ────────────────────────────────────────────────────────────────
    public string $successMessage = '';
    public string $errorMessage   = '';

    // ─── Mount ────────────────────────────────────────────────────────────────
    public function mount(NavMenu $menu): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $this->menu             = $menu;
        $this->menuName         = $menu->name;
        $this->menuColorScheme  = $menu->color_scheme;
        $this->menuCustomCss    = $menu->custom_css ?? '';
        $this->menuSticky       = $menu->sticky;
        $this->stickyBodyOffset = $menu->sticky_body_offset ?? '0px';
        $this->menuShowLogo     = $menu->show_logo;
        $this->menuAlignment    = $menu->alignment ?? 'left';
    }

    public function getDefaultTopNavCssProperty(): string
    {
        return <<<CSS
/* Default Top Navigation Styling Reference */
.top_nav_container {
    background-color: var(--nav-bg, #ffffff);
    color: var(--nav-text, #1e293b);
    border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.2s ease-in-out;
}

.top_nav_container a.nav-link {
    color: var(--nav-text, #475569);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
    transition: color 0.15s ease, background-color 0.15s ease;
}

.top_nav_container a.nav-link:hover {
    color: var(--nav-text-hover, #4f46e5);
    background-color: var(--nav-hover-bg, rgba(79, 70, 229, 0.05));
}

.top_nav_container .dropdown-menu {
    background-color: var(--nav-dropdown-bg, #ffffff);
    border: 1px solid rgba(226, 232, 240, 1);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    border-radius: 0.75rem;
}
CSS;
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
        $q = CmsPage::select('id', 'title', 'slug')
            ->where('is_active', 1)
            ->orderBy('title');
        if ($this->cmsPageSearch) {
            $q->where('title', 'like', '%' . $this->cmsPageSearch . '%');
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
            'menuAlignment'   => 'required|in:left,center,right,even',
        ]);

        $this->menu->update([
            'name'               => trim($this->menuName),
            'color_scheme'       => $this->menuColorScheme,
            'custom_css'         => $this->menuCustomCss ?: null,
            'sticky'             => $this->menuSticky,
            'sticky_body_offset' => $this->stickyBodyOffset ?: '0px',
            'show_logo'          => $this->menuShowLogo,
            'alignment'          => $this->menuAlignment,
        ]);

        $this->menu->refresh();
        $this->successMessage = 'Appearance saved.';
        $this->dispatch('toast', message: 'Appearance settings saved successfully.', type: 'success');
    }

    public function updatedTranslationLanguageId(): void
    {
        $this->translationBuffer = [];
    }

    public function saveNavTranslation(int $navItemId): void
    {
        $label = trim($this->translationBuffer[$navItemId] ?? '');
        if ($this->translationLanguageId === 0) { return; }

        NavItemTranslation::updateOrCreate(
            ['nav_item_id' => $navItemId, 'language_id' => $this->translationLanguageId],
            ['label' => $label ?: null, 'translation_status' => 'reviewed', 'translated_at' => now()]
        );

        unset($this->translationBuffer[$navItemId]);
        session()->flash('success', 'Translation saved.');
    }

    public function clearNavTranslation(int $navItemId): void
    {
        if ($this->translationLanguageId === 0) { return; }
        NavItemTranslation::where('nav_item_id', $navItemId)
            ->where('language_id', $this->translationLanguageId)
            ->delete();
        unset($this->translationBuffer[$navItemId]);
        session()->flash('success', 'Translation cleared.');
    }

    /**
     * AI-translate a single nav item label and pre-fill the translation buffer
     * so the admin can review before saving.
     */
    public function aiTranslateNavItem(int $navItemId): void
    {
        if ($this->translationLanguageId === 0) { return; }

        $item = \App\Models\NavItem::find($navItemId);
        if (!$item) { return; }

        $defaultLabel = strip_tags($item->label ?? '');
        if (empty(trim($defaultLabel))) {
            $this->dispatch('toast', message: 'No default label to translate.', type: 'warning');
            return;
        }

        $language = \App\Models\Language::find($this->translationLanguageId);
        if (!$language) { return; }

        $this->aiTranslating[$navItemId] = true;

        try {
            $translated = app(\App\Services\TranslationService::class)
                ->translateText($defaultLabel, $language->name, 'navigation menu label — keep it short and concise');

            // Pre-fill the buffer so the admin sees the result in the input
            $this->translationBuffer[$navItemId] = $translated;
            $this->dispatch('toast', message: 'AI translation ready — review and click Save.', type: 'success');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[NavTranslation] AI error: ' . $e->getMessage());
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        } finally {
            unset($this->aiTranslating[$navItemId]);
        }
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render()
    {
        $activeLanguages = Language::active()->where('is_default', false)->get();
        $navTranslations = [];
        
        if ($this->translationLanguageId > 0) {
            $navTranslations = NavItemTranslation::where('language_id', $this->translationLanguageId)
                ->whereIn('nav_item_id', $this->menu->items()->pluck('id'))
                ->pluck('label', 'nav_item_id');
        }

        return view('livewire.admin-nav-menu-edit', [
            'menu'            => $this->menu,
            'items'           => $this->items,
            'cmsPages'        => $this->cmsPages,
            'topNavPlugins'   => $this->topNavPlugins,
            'parentItems'     => $this->parentItems,
            'builtInTypes'    => NavItem::BUILT_IN_TYPES,
            'schemeKeys'      => array_keys(config('nav_schemes', [])),
            'activeLanguages' => $activeLanguages,
            'navTranslations' => $navTranslations,
        ]);
    }
}
