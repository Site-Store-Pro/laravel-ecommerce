<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class NavItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'position',
        'label',
        'item_type',
        'url',
        'html_content',
        'cms_page_id',
        'is_active',
        'open_in_new_tab',
        'visibility',
        'hide_on_mobile',
        'hide_on_desktop',
        'css_class',
        'aria_label',
        'plugin_slug',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'open_in_new_tab' => 'boolean',
        'hide_on_mobile'  => 'boolean',
        'hide_on_desktop' => 'boolean',
        'position'        => 'float',
    ];

    // ─── Built-in item types ──────────────────────────────────────────────────

    public const BUILT_IN_TYPES = [
        'link'         => 'Custom URL',
        'cms_page'     => 'CMS Page Link',
        'home'         => 'Home Page',
        'shop'         => 'Shop Page',
        'cart'         => 'Shopping Cart',
        'account'      => 'My Account',
        'categories'   => 'Category Drill-Down',
        'brands'       => 'Brands Dropdown',
        'parent'       => 'Parent (with sub-menu)',
        'no_link'      => 'Label Only (no link)',
        'mega_menu'    => 'Mega Menu (full-width)',
        'html_submenu' => 'Custom HTML Sub-menu',
        'separator'    => 'Separator / Divider',
        'plugin'       => 'Plugin Item',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function menu(): BelongsTo
    {
        return $this->belongsTo(NavMenu::class, 'menu_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavItem::class, 'parent_id')->orderBy('position');
    }

    public function cmsPage(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeForMenu($query, int $menuId)
    {
        return $query->where('menu_id', $menuId);
    }

    // ─── Tree builder ─────────────────────────────────────────────────────────

    /**
     * Convert a flat active collection into a nested tree (2 levels).
     * Top-level items have parent_id = null.
     * Children are attached as a 'children' Collection on each parent.
     */
    public static function buildTree(Collection $items): Collection
    {
        $topLevel = $items->whereNull('parent_id')->sortBy('position');
        $childMap = $items->whereNotNull('parent_id')->groupBy('parent_id');

        return $topLevel->map(function (NavItem $item) use ($childMap) {
            $item->setRelation(
                'children',
                ($childMap->get($item->id) ?? collect())->sortBy('position')->values()
            );
            return $item;
        })->values();
    }

    // ─── Visibility helpers ───────────────────────────────────────────────────

    /**
     * Check if this item should be visible for the given user context.
     *
     * @param \App\Models\User|null $user
     */
    public function isVisibleFor(?object $user): bool
    {
        return match ($this->visibility) {
            'guests_only'    => is_null($user),
            'auth_only'      => !is_null($user),
            'wholesale_only' => !is_null($user) && method_exists($user, 'isWholesale') && $user->isWholesale(),
            default          => true, // 'all'
        };
    }
}
