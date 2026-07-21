<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Product Reviews & Moderation</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage, search, edit, and approve all product comments and star ratings.</p>
        </div>
    </div>

    {{-- Status Flash Alert --}}
    @if (session()->has('status'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl flex items-center gap-3 text-emerald-700 dark:text-emerald-400 text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search Input --}}
            <div class="md:col-span-2 space-y-1.5">
                <label for="search-input" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Search Comments</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="search-input"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by reviewer, location, or comment text..."
                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                    >
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="space-y-1.5">
                <label for="status-filter" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Filter by Status</label>
                <select
                    id="status-filter"
                    wire:model.live="status"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                >
                    <option value="all">All Reviews</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved</option>
                </select>
            </div>

            {{-- Product Filter --}}
            <div class="space-y-1.5">
                <label for="product-filter" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Filter by Product</label>
                <select
                    id="product-filter"
                    wire:model.live="product_filter"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                >
                    <option value="">All Products</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}">{{ $prod->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Edit Review Modal Form --}}
    @if($isEditingReview)
        <div class="bg-white dark:bg-slate-800 border border-indigo-100 dark:border-slate-700 rounded-3xl p-8 shadow-md space-y-6">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100 dark:border-slate-700">
                <div class="p-2 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">Edit User Review</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Modify fields, adjust the rating, or set approval status below.</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Reviewer Name</label>
                        <input type="text" wire:model="reviewName" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs">
                        @error('reviewName') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Location</label>
                        <input type="text" wire:model="reviewLocation" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs">
                        @error('reviewLocation') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Rating Stars</label>
                        <select wire:model="reviewRating" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs">
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                        @error('reviewRating') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Comments</label>
                    <textarea wire:model="reviewComments" rows="3" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs"></textarea>
                    @error('reviewComments') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" wire:model="reviewApproved" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 bg-white dark:bg-slate-700">
                        <span>Approved</span>
                    </label>
                </div>
                <div class="flex justify-end gap-2.5 pt-2">
                    <button type="button" wire:click="cancelEditReview" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-bold rounded-2xl text-xs transition duration-150">Cancel</button>
                    <button type="button" wire:click="saveReview" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs transition duration-150 shadow-md shadow-indigo-100 dark:shadow-none">Save Changes</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Reviews List Table Card --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl overflow-hidden shadow-sm">
        @if($reviews->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center p-3 bg-slate-100 dark:bg-slate-750 rounded-2xl text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">No reviews found</h3>
                <p class="text-xs text-slate-400 mt-1">Try adjusting your filters or search terms.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-750/30 text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">Product</th>
                            <th class="py-4 px-6">Reviewer</th>
                            <th class="py-4 px-6">Location</th>
                            <th class="py-4 px-6">Rating</th>
                            <th class="py-4 px-6">Comment</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-350">
                        @foreach($reviews as $review)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-750/30 transition duration-150">
                                <td class="py-4 px-6 font-bold text-slate-850 dark:text-white">
                                    @if($review->product)
                                        <a href="{{ route('admin.ecommerce.product-edit', $review->product_id) }}" class="hover:text-indigo-600 transition-colors">
                                            {{ $review->product->title }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">Deleted Product</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-semibold">{{ $review->name }}</td>
                                <td class="py-4 px-6">{{ $review->location ?? '-' }}</td>
                                <td class="py-4 px-6 text-amber-500 font-extrabold whitespace-nowrap">{{ $review->rating }} ★</td>
                                <td class="py-4 px-6 max-w-sm truncate" title="{{ $review->comments }}">{{ $review->comments ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    @if($review->approved)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">Approved</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400">Pending</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right space-x-1.5 whitespace-nowrap">
                                    <button type="button" wire:click="toggleReviewApproval({{ $review->id }})" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-white font-bold transition duration-150">
                                        {{ $review->approved ? 'Unapprove' : 'Approve' }}
                                    </button>
                                    <button type="button" wire:click="editReview({{ $review->id }})" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 font-bold transition duration-150">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="deleteReview({{ $review->id }})" wire:confirm="Are you sure you want to delete this review?" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/40 text-rose-700 dark:text-rose-450 font-bold transition duration-150">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Footer --}}
            @if($reviews->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
        </div>
    </div>
</div>
