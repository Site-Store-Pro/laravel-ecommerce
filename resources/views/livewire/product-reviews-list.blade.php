<div class="space-y-12">
    {{-- Ratings Summary Header --}}
    @if (!$reviews->isEmpty())
        <div class="bg-slate-50 dark:bg-slate-800/40 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="flex items-center gap-3">
                    <span class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($product->reviews_rating, 1) }}
                    </span>
                    <span class="text-slate-400 dark:text-slate-500 font-semibold text-lg">/ 5.0</span>
                </div>
                
                {{-- Visual Clipping Star Tracker --}}
                <div class="flex items-center gap-1">
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $diff = $product->reviews_rating - ($i - 1);
                            $percentage = max(0, min(100, $diff * 100));
                        @endphp
                        <div class="relative w-5 h-5 text-slate-200 dark:text-slate-700">
                            <!-- Background Star -->
                            <svg class="absolute top-0 left-0 w-full h-full fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <!-- Foreground Clipped Star -->
                            <div class="absolute top-0 left-0 h-full overflow-hidden text-amber-400" style="width: {{ $percentage }}%;">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    @endfor
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Based on {{ $reviews->count() }} {{ Str::plural('customer review', $reviews->count()) }}.
                </p>
            </div>

            {{-- Sort Filters --}}
            <div class="flex items-center gap-3">
                <label for="review-sort" class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Sort Reviews</label>
                <select
                    id="review-sort"
                    wire:model.live="sort"
                    class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-bold shadow-sm"
                >
                    <option value="recent">Most Recent</option>
                    <option value="highest">Highest Rated</option>
                    <option value="lowest">Lowest Rated</option>
                </select>
            </div>
        </div>
    @endif

    {{-- Reviews List / Comments --}}
    @if ($reviews->isEmpty())
        <div class="p-12 text-center bg-slate-50/50 dark:bg-slate-800/20 border border-dashed border-slate-200 dark:border-slate-850 rounded-3xl">
            <span class="inline-flex items-center justify-center p-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </span>
            <h3 class="text-sm font-bold text-slate-750 dark:text-white">No reviews yet</h3>
            <p class="text-xs text-slate-450 dark:text-slate-400 mt-1">Be the first to share your thoughts by filling out the form below.</p>
        </div>
    @else
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach ($reviews as $rev)
                <div class="py-6 first:pt-0 last:pb-0 space-y-3">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $rev->name }}</span>
                                @if($rev->location)
                                    <span class="text-xs text-slate-400 dark:text-slate-500">• {{ $rev->location }}</span>
                                @endif
                            </div>
                            
                            {{-- Star Rating --}}
                            <div class="flex items-center gap-0.5 text-amber-400">
                                @for($s = 1; $s <= 5; $s++)
                                    <svg class="w-4 h-4 {{ $s <= $rev->rating ? 'fill-current' : 'text-slate-200 dark:text-slate-700 fill-current' }}" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 whitespace-nowrap">
                            {{ $rev->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if($rev->comments)
                        <p class="text-xs text-slate-650 dark:text-slate-350 leading-relaxed font-medium">
                            {{ $rev->comments }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- Review Submission Form Card --}}
    <div class="bg-white dark:bg-slate-800/30 border border-slate-150 dark:border-slate-850 rounded-3xl p-8 shadow-sm space-y-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Leave a Review</h3>
                <p class="text-xs text-slate-400 mt-0.5">Share your feedback regarding your purchase with other customers.</p>
            </div>

            <form
                x-data="{
                    submitForm() {
                        if (typeof grecaptcha === 'undefined' || !window.recaptchaSiteKey) {
                            $wire.submitReview(); return;
                        }
                        grecaptcha.ready(() => {
                            grecaptcha.execute(window.recaptchaSiteKey, { action: 'product_review' }).then(token => {
                                $wire.recaptchaToken = token;
                                $wire.submitReview();
                            });
                        });
                    }
                }"
                @submit.prevent="submitForm"
                class="space-y-5"
            >
                <input type="hidden" wire:model="recaptchaToken">

                {{-- Interactive Star Selector using Alpine --}}
                <div class="space-y-1.5" x-data="{ hoverRating: 0, currentRating: @entangle('rating') }">
                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Your Rating</label>
                    <div class="flex items-center gap-1.5">
                        @for($s = 1; $s <= 5; $s++)
                            <button
                                type="button"
                                @click="currentRating = {{ $s }}"
                                @mouseenter="hoverRating = {{ $s }}"
                                @mouseleave="hoverRating = 0"
                                class="focus:outline-none transition duration-150 transform hover:scale-110"
                            >
                                <svg class="w-8 h-8 cursor-pointer fill-current transition-colors duration-150"
                                     :class="(hoverRating || currentRating) >= {{ $s }} ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700'"
                                     viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                    @error('rating') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Name (Required) --}}
                    <div class="space-y-1.5">
                        <label for="reviewer-name" class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Your Name (Required)</label>
                        <input
                            type="text"
                            id="reviewer-name"
                            wire:model="name"
                            placeholder="e.g. John Doe"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-750 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                        >
                        @error('name') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Location (Optional) --}}
                    <div class="space-y-1.5">
                        <label for="reviewer-location" class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Location (Optional)</label>
                        <input
                            type="text"
                            id="reviewer-location"
                            wire:model="location"
                            placeholder="e.g. New York, NY"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-750 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                        >
                        @error('location') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Comments (Optional) --}}
                <div class="space-y-1.5">
                    <label for="reviewer-comments" class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Comments (Optional)</label>
                    <textarea
                        id="reviewer-comments"
                        wire:model="comments"
                        rows="4"
                        placeholder="Describe your experience with this product..."
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-750 text-slate-850 dark:text-white rounded-2xl focus:outline-none focus:border-indigo-500 text-xs font-semibold"
                    ></textarea>
                    @error('comments') <p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs shadow-md shadow-indigo-100 dark:shadow-none hover:opacity-90 active:scale-95 transition duration-150"
                    >
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
</div>

@if(config('services.recaptcha.site_key'))
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
        <script>
            if (typeof window.recaptchaSiteKey === 'undefined') {
                window.recaptchaSiteKey = "{{ config('services.recaptcha.site_key') }}";
            }
        </script>
    @endpush
@endif
