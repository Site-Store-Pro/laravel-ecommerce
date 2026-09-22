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
        'amazon_category',
        'ebay_category',
        'category_image',
        'header_image',
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
        'header_image_s3',
        'header_image_cdn_url',
        'header_image_region',
        'header_image_bucket_name',
        'header_image_access_key_id',
        'header_image_secret_access_key',
        'header_image_direct_url',
    ];

    protected $casts = [
        'is_visible_in_menu'       => 'boolean',
        'display_label_in_plugins' => 'boolean',
        'display_image_in_plugins' => 'boolean',
        'sort_order'               => 'integer',
        'category_image_s3'        => 'integer',
        'header_image_s3'          => 'integer',
    ];


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the resolved public URL for the category header image.
     */
    public function getHeaderImageUrl(): ?string
    {
        if (!empty($this->header_image_direct_url)) {
            return $this->header_image_direct_url;
        }

        if (empty($this->header_image)) {
            return null;
        }

        if (str_starts_with($this->header_image, 'http://') || str_starts_with($this->header_image, 'https://') || str_starts_with($this->header_image, '/')) {
            return $this->header_image;
        }

        if (!empty($this->header_image_cdn_url)) {
            return rtrim($this->header_image_cdn_url, '/') . '/' . ltrim($this->header_image, '/');
        }

        if ($this->header_image_s3 == 1) {
            return \Illuminate\Support\Facades\Storage::disk('s3')->url($this->header_image);
        }

        return asset('storage/' . ltrim($this->header_image, '/'));
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
     * Precompute cascading distinct product counts for all categories in 1 single fast query.
     * Returns an associative array [category_id => distinct_product_count].
     *
     * @return array<int, int>
     */
    public static function getCascadingProductCountsMap(): array
    {
        $allCategories = static::all(['id', 'parent_id']);
        $childrenByParent = $allCategories->groupBy('parent_id');

        $assignments = \DB::table('product_categories_assignments')
            ->select('category_id', 'product_id')
            ->get();

        $directProductsByCategory = [];
        foreach ($assignments as $row) {
            $directProductsByCategory[$row->category_id][] = $row->product_id;
        }

        $getDescendants = function ($catId) use (&$getDescendants, $childrenByParent) {
            $ids = [$catId];
            if (isset($childrenByParent[$catId])) {
                foreach ($childrenByParent[$catId] as $child) {
                    $ids = array_merge($ids, $getDescendants($child->id));
                }
            }
            return $ids;
        };

        $countsMap = [];
        foreach ($allCategories as $cat) {
            $relevantCatIds = $getDescendants($cat->id);
            $productIds = [];
            foreach ($relevantCatIds as $rId) {
                if (isset($directProductsByCategory[$rId])) {
                    foreach ($directProductsByCategory[$rId] as $pId) {
                        $productIds[$pId] = true;
                    }
                }
            }
            $countsMap[$cat->id] = count($productIds);
        }

        return $countsMap;
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

    /**
     * Fast in-memory resolution of all descendant IDs (including self) without CTE recursive queries.
     *
     * @param int|array $categoryIds
     * @return array<int>
     */
    public static function getDescendantIdsFor(int|array $categoryIds): array
    {
        static $cachedParents = null;
        if ($cachedParents === null) {
            $cachedParents = static::all(['id', 'parent_id'])->groupBy('parent_id');
        }

        $input = is_array($categoryIds) ? array_map('intval', $categoryIds) : [(int)$categoryIds];
        $result = $input;
        $queue = $input;

        while (!empty($queue)) {
            $nextQueue = [];
            foreach ($queue as $pId) {
                if (isset($cachedParents[$pId])) {
                    foreach ($cachedParents[$pId] as $child) {
                        if (!in_array($child->id, $result, true)) {
                            $result[] = $child->id;
                            $nextQueue[] = $child->id;
                        }
                    }
                }
            }
            $queue = $nextQueue;
        }

        return $result;
    }
}
