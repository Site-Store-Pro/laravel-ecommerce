<div style="margin-left: {{ $depth * 1.5 }}rem;" class="py-1">
    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
        <input type="checkbox" wire:model="selectedCategories" value="{{ $node->id }}" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white">
        <span class="@if($depth === 0) font-bold text-slate-900 @else text-slate-600 @endif">{{ $node->name }}</span>
    </label>
</div>

@if($node->children->isNotEmpty())
    @foreach($node->children->sortBy('sort_order') as $child)
        @include('livewire.category-checkbox-node', ['node' => $child, 'depth' => $depth + 1])
    @endforeach
@endif
