<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use App\Traits\HasTranslations;

class Category extends Model
{
    use HasRecursiveRelationships, HasTranslations;

    protected $table = 'product_categories';

    protected array $translatable = ['name', 'description'];

    protected function translationForeignKey(): string
    {
        return 'category_id';
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_image',
        'parent_id',
        'sort_order',
        'is_visible_in_menu',
        'display_label_in_plugins',
        'display_image_in_plugins',
        'category_image_s3',
        'category_image_cdn_url',
        'category_image_region',
        'category_image_bucket_name',
        'category_image_access_key_id',
        'category_image_secret_access_key',
        'category_image_direct_url',
    ];

    protected $casts = [
        'is_visible_in_menu'       => 'boolean',
        'display_label_in_plugins' => 'boolean',
        'display_image_in_plugins' => 'boolean',
        'sort_order'               => 'integer',
        'category_image_s3'        => 'integer',
    ];


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Return ancestor categories starting from root down to self.
     * e.g. [RootCategory, SubCategory, SubSubCategory]
     */
    public function getBreadcrumbChain(): array
    {
        $chain = [];
        $visited = [];
        $curr = $this;

        while ($curr && !in_array($curr->id, $visited)) {
            $visited[] = $curr->id;
            array_unshift($chain, $curr);
            $curr = $curr->parent;
        }

        return $chain;
    }

    /**
     * Relationship: Products belonging to this category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories_assignments', 'category_id', 'product_id');
    }

    /**
     * Get distinct products count in this category and all its descendants.
     */
    public function getCascadingProductsCount(): int
    {
        $categoryIds = $this->descendantsAndSelf()->pluck('id');
        return \DB::table('product_categories_assignments')
            ->whereIn('category_id', $categoryIds)
            ->distinct()
            ->count('product_id');
    }

    /**
     * Check if this category or any descendant has products.
     * Uses loaded relations if available to avoid extra DB queries.
     */
    public function hasActiveProducts(): bool
    {
        if ($this->relationLoaded('products')) {
            if ($this->products->isNotEmpty()) {
                return true;
            }
        } else {
            if ($this->products()->exists()) {
                return true;
            }
        }

        if ($this->relationLoaded('children')) {
            foreach ($this->children as $child) {
                if ($child->hasActiveProducts()) {
                    return true;
                }
            }
        } else {
            foreach ($this->children()->get() as $child) {
                if ($child->hasActiveProducts()) {
                    return true;
                }
            }
        }

        return false;
    }
}
