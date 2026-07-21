<div class="py-2 border-b border-slate-100 last:border-0" style="margin-left: {{ $depth * 1.5 }}rem;">
    <div class="flex items-center justify-between gap-4 py-1">
        <div class="flex items-center gap-2">
            @if($node->children->isNotEmpty())
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            @else
                <span class="w-4 block"></span>
            @endif
            <span class="font-bold text-slate-800 text-sm">{{ $node->name }}</span>
            <a href="{{ route('shop.category', ['category_slug' => $node->slug]) }}" target="_blank" class="text-slate-400 hover:text-slate-600 transition" title="View Category on Storefront">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <span class="text-xs text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-150 font-mono">{{ $node->slug }}</span>
            <button type="button" wire:click="showProducts({{ $node->id }}, '{{ addslashes($node->name) }}')" class="text-[10px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full border border-indigo-100 font-bold transition focus:outline-none" title="View products in this category">
                {{ $node->getCascadingProductsCount() }} {{ Str::plural('product', $node->getCascadingProductsCount()) }}
            </button>
            @if(!$node->is_visible_in_menu)
                <span class="bg-amber-50 text-amber-700 text-[10px] px-1.5 py-0.5 rounded font-bold border border-amber-100">Hidden from menu</span>
            @endif
        </div>
        <div class="flex items-center gap-1">
            <button wire:click="editCategory({{ $node->id }})" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-lg text-xs font-semibold shadow-sm transition">
                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Edit
            </button>
            <button onclick="confirm('Are you sure you want to delete this category? All its subcategories will be permanently deleted.') || event.stopImmediatePropagation()" wire:click="deleteCategory({{ $node->id }})" class="p-1 bg-red-50 border border-red-150 text-red-600 hover:bg-red-100 rounded-lg transition" title="Delete Category">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
</div>

@if($node->children->isNotEmpty())
    @foreach($node->children->sortBy('sort_order') as $child)
        @include('livewire.category-tree-node', ['node' => $child, 'depth' => $depth + 1])
    @endforeach
@endif
