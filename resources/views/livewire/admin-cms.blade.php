<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="font-extrabold text-2xl text-slate-900 leading-tight">CMS Management</h2>
            <p class="text-sm text-slate-500 mt-0.5">Control the public home page copy, headers, and SEO properties</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Brands
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 hover:bg-slate-50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>

                    <a href="{{ route('admin.ecommerce.cms') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 text-indigo-600 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        CMS Home Page
                    </a>

                    <a href="{{ route('admin.cms-embeds.index') }}" wire:navigate
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm
                              {{ request()->routeIs('admin.cms-embeds.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }}
                              transition duration-150">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.cms-embeds.*') ? 'text-indigo-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        Code Embeds
                    </a>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Status Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3 text-emerald-800 text-sm font-semibold">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Edit CMS Card -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h3 class="text-lg font-bold text-slate-900">Edit Page: Home Page</h3>
                        <p class="text-xs text-slate-400 mt-1">Label identifier: <code>home_page</code></p>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-6">
                        <!-- Hero Title -->
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Hero Banner Title</label>
                            <input type="text" wire:model="title" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500 font-bold">
                            @error('title') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- TinyMCE Content Editor -->
                        <div>
                            <label class="text-xs font-bold text-slate-400 block mb-2 uppercase tracking-wider">Hero Banner Content (TinyMCE Rich Text / HTML)</label>
                            
                            <div wire:ignore x-data x-init="
                                tinymce.init({
                                    selector: '#cms_content',
                                    license_key: 'gpl',
                                    promotion: false,
                                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px } .prose, .prose-slate { max-width: none !important; }',
                                    plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking table emoticons help',
                                    toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                                        'bullist numlist outdent indent | link link link link link | preview fullscreen | ' +
                                        'forecolor backcolor emoticons | help',
                                    setup: function (editor) {
                                        editor.on('change', function () {
                                            $wire.set('content', editor.getContent());
                                        });
                                    }
                                });
                            ">
                                <textarea wire:model="content" id="cms_content" rows="12"
                                          class="mt-1 block w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"></textarea>
                            </div>
                            @error('content') <span class="text-xs text-red-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- SEO Properties -->
                        <div class="p-6 bg-slate-50 border border-slate-200/60 rounded-2xl space-y-4">
                            <h4 class="font-extrabold text-slate-800 text-sm">SEO Configurations</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Title</label>
                                    <input type="text" wire:model="meta_title" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 block mb-1 uppercase tracking-wider">Meta Description</label>
                                    <textarea wire:model="meta_description" rows="2" class="w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-800 rounded-2xl focus:outline-none focus:border-indigo-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex gap-4">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-md hover:opacity-90 transition duration-150">Save Content</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/node_modules/tinymce/tinymce.min.js') }}"></script>
</div>
