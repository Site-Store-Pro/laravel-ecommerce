<?php

namespace App\Plugins\Contracts;

use App\Models\NavItem;

/**
 * Contract for top-navigation plugins.
 *
 * A top-navigation plugin registers a new nav item type
 * (identified by its slug) and handles rendering that item
 * type in the public navigation.
 *
 * To create a custom nav plugin, implement this interface and
 * either register it in PluginServiceProvider or drop a plugin.json
 * + PHP class into the base_path('plugins') external plugins folder.
 *
 * plugin.json minimum fields:
 * {
 *   "type": "top-navigation",
 *   "class": "MyNavPlugin",
 *   "filename": "my-nav-plugin",
 *   "name": "My Nav Plugin",
 *   "version": "1.0"
 * }
 */
interface TopNavigationPlugin
{
    /**
     * Unique item_type key for this plugin's nav item.
     * Must be lowercase, no spaces (e.g. "search_bar", "wishlist_icon").
     * This slug is stored in nav_items.plugin_slug.
     */
    public function slug(): string;

    /**
     * Human-readable name shown in the admin item-type picker.
     */
    public function name(): string;

    /**
     * Render the nav item (and any sub-menu/dropdown) to HTML.
     *
     * @param NavItem $item    The nav item record from the database.
     * @param array   $context Runtime context: ['user' => User|null, 'cartCount' => int, ...]
     * @return string          Raw HTML to inject as the <li> content.
     */
    public function renderItem(NavItem $item, array $context): string;

    /**
     * Optional: return a Blade partial view name for extra fields
     * shown in the admin item editor when this plugin type is selected.
     * Return null if no extra fields are needed.
     *
     * The partial receives $item (NavItem) and $index (int) as variables.
     *
     * Example: 'plugins.nav.my-plugin-fields'
     */
    public function adminFormPartial(): ?string;
}
