<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class NavMenu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_primary',
        'is_active',
        'color_scheme',
        'custom_css',
        'sticky',
        'sticky_body_offset',
        'show_logo',
        'alignment',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active'  => 'boolean',
        'sticky'     => 'boolean',
        'show_logo'  => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function items(): HasMany
    {
        return $this->hasMany(NavItem::class, 'menu_id')->orderBy('position');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Static helpers ───────────────────────────────────────────────────────

    /**
     * Get the primary active menu, or fallback to first active or first menu if none configured.
     */
    public static function getPrimary(): ?NavMenu
    {
        return static::where('is_primary', true)->where('is_active', true)->first()
            ?? static::where('is_active', true)->first()
            ?? static::first();
    }

    /**
     * Set this menu as the only primary menu (clears all others).
     */
    public function setAsPrimary(): void
    {
        static::where('id', '!=', $this->id)->update(['is_primary' => false]);
        $this->update(['is_primary' => true]);
    }

    /**
     * Load top-level items with their children as a nested collection.
     */
    public function getItemTree(): Collection
    {
        return NavItem::buildTree(
            $this->items()->where('is_active', true)->get()
        );
    }

    /**
     * Return the CSS custom-property map for the selected color scheme.
     */
    public function colorSchemeVars(): array
    {
        $schemes = config('nav_schemes', []);
        return $schemes[$this->color_scheme] ?? $schemes['default'] ?? [];
    }

    /**
     * Generate a URL-safe slug from a name string.
     */
    public static function generateSlug(string $name): string
    {
        return preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($name)));
    }
}
