<?php

namespace App\Livewire;

use App\Models\NavMenu;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
#[Title('Navigation Builder')]
class AdminNavMenus extends Component
{
    // ─── New menu form ─────────────────────────────────────────────────────────
    public bool $showCreateForm = false;
    public string $newName = '';
    public string $newColorScheme = 'default';

    // ─── Flash ────────────────────────────────────────────────────────────────
    public string $successMessage = '';
    public string $errorMessage   = '';

    // ─── Computed ─────────────────────────────────────────────────────────────
    public function getMenusProperty()
    {
        return NavMenu::withCount('items')->orderByDesc('is_primary')->orderBy('name')->get();
    }

    // ─── Actions ──────────────────────────────────────────────────────────────

    public function createMenu(): void
    {
        $this->validate([
            'newName' => 'required|string|max:255',
        ]);

        // Access guard
        abort_unless(auth()->user()?->isAdmin(), 403);

        $slug = NavMenu::generateSlug($this->newName);
        $base = $slug;
        $n = 1;
        while (NavMenu::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        $menu = NavMenu::create([
            'name'         => trim($this->newName),
            'slug'         => $slug,
            'color_scheme' => $this->newColorScheme,
            'is_active'    => true,
            'is_primary'   => !NavMenu::where('is_primary', true)->exists(),
        ]);

        $this->successMessage = "Menu \"{$menu->name}\" created.";
        $this->reset(['showCreateForm', 'newName', 'newColorScheme']);
    }

    public function setPrimary(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        NavMenu::where('id', '!=', $id)->update(['is_primary' => false]);
        NavMenu::where('id', $id)->update(['is_primary' => true]);

        $this->successMessage = 'Primary menu updated.';
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $menu = NavMenu::findOrFail($id);
        $menu->update(['is_active' => !$menu->is_active]);
    }

    public function duplicateMenu(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $original = NavMenu::with('items')->findOrFail($id);

        $slug = NavMenu::generateSlug($original->name . ' copy');
        $base = $slug;
        $n = 1;
        while (NavMenu::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        $copy = NavMenu::create([
            'name'         => $original->name . ' (Copy)',
            'slug'         => $slug,
            'color_scheme' => $original->color_scheme,
            'custom_css'   => $original->custom_css,
            'is_active'    => false,
            'is_primary'   => false,
            'sticky'       => $original->sticky,
            'show_logo'    => $original->show_logo,
        ]);

        // Deep-copy items preserving parent relationships
        $idMap = [];
        foreach ($original->items->whereNull('parent_id') as $item) {
            $newItem = $copy->items()->create($item->only([
                'parent_id', 'position', 'label', 'item_type', 'url', 'html_content',
                'cms_page_id', 'is_active', 'open_in_new_tab', 'visibility',
                'hide_on_mobile', 'hide_on_desktop', 'css_class', 'aria_label', 'plugin_slug',
            ]));
            $idMap[$item->id] = $newItem->id;
        }
        foreach ($original->items->whereNotNull('parent_id') as $child) {
            $newParentId = $idMap[$child->parent_id] ?? null;
            if ($newParentId) {
                $copy->items()->create(array_merge($child->only([
                    'position', 'label', 'item_type', 'url', 'html_content',
                    'cms_page_id', 'is_active', 'open_in_new_tab', 'visibility',
                    'hide_on_mobile', 'hide_on_desktop', 'css_class', 'aria_label', 'plugin_slug',
                ]), ['parent_id' => $newParentId]));
            }
        }

        $this->successMessage = "Menu duplicated as \"{$copy->name}\".";
    }

    public function deleteMenu(int $id): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $menu = NavMenu::findOrFail($id);
        if ($menu->is_primary) {
            $this->errorMessage = 'Cannot delete the primary menu. Set another menu as primary first.';
            return;
        }
        $name = $menu->name;
        $menu->items()->delete();
        $menu->delete();
        $this->successMessage = "Menu \"{$name}\" deleted.";
    }

    public function render()
    {
        return view('livewire.admin-nav-menus', [
            'menus'   => $this->menus,
            'schemes' => array_keys(config('nav_schemes', [])),
        ]);
    }
}
