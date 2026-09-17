<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CategoryMenuWidget extends Component
{
    public ?string $label = null;

    public function mount(?string $label = null): void
    {
        if ($label !== null && trim($label) !== '') {
            $this->label = trim($label);
        }
    }

    public function render(): View
    {
        // Fetch top-level categories visible in menu, including their children recursively
        $categories = Category::whereNull('parent_id')
            ->where('is_visible_in_menu', true)
            ->withCurrentTranslations()
            ->withExists(['products' => fn($q) => $q->active()->showInResults()])
            ->with([
                'children' => function ($query) {
                    $query->withCurrentTranslations()
                        ->where('is_visible_in_menu', true)
                        ->withExists(['products' => fn($q) => $q->active()->showInResults()])
                        ->orderBy('sort_order');
                },
                'children.children' => function ($query) {
                    $query->withCurrentTranslations()
                        ->where('is_visible_in_menu', true)
                        ->withExists(['products' => fn($q) => $q->active()->showInResults()])
                        ->orderBy('sort_order');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        // Recursively filter the tree in-memory to only keep categories with active products
        $filterTree = function ($collection) use (&$filterTree) {
            return $collection->filter(function ($category) use (&$filterTree) {
                if ($category->children->isNotEmpty()) {
                    $category->setRelation('children', $filterTree($category->children));
                }
                return (bool) $category->products_exists || $category->children->isNotEmpty();
            });
        };

        $filteredCategories = $filterTree($categories);

        return view('livewire.category-menu-widget', [
            'categories' => $filteredCategories
        ]);
    }
}
