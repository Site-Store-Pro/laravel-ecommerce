<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-sm font-medium text-slate-500 mb-2">
            <a href="{{ route('admin.languages.index') }}" wire:navigate class="hover:text-slate-900 dark:hover:text-white transition">Languages</a>
            <span class="mx-2 text-slate-400">/</span>
            <span class="text-slate-700 dark:text-slate-300">{{ $language->name }}</span>
        </nav>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-3">
                    <span>{{ $language->flag_emoji }}</span>
                    {{ $language->name }}
                    <span class="text-lg font-normal text-slate-400">— Translation Dashboard</span>
                </h1>
            </div>
            <a href="{{ route('admin.languages.index') }}" wire:navigate class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl text-sm font-bold shadow-sm transition flex items-center gap-2">
                &larr; Back to Languages
            </a>
        </div>

        <!-- Flash messages -->
        @if(session()->has('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-100 dark:border-emerald-800 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-sm font-semibold">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            @php
                $labels = [
                    'cms_pages'          => 'CMS Pages',
                    'products'           => 'Products',
                    'kb_articles'        => 'KB Articles',
                    'testimonials'       => 'Testimonials',
                    'nav_items'          => 'Nav Items',
                    'list_menus'         => 'List Menus',
                    'site_labels'        => 'Site Labels',
                    'product_categories' => 'Product Cats',
                    'cms_categories'     => 'CMS Cats',
                    'cms_tags'           => 'CMS Tags',
                    'kb_categories'      => 'KB Categories',
                    'email_templates'    => 'Email Templates',
                ];
            @endphp
            @foreach($typeMap as $type)
                @php 
                    $stat = $stats[$type]; 
                    $pct = $stat['total'] > 0 ? round(($stat['translated'] / $stat['total']) * 100) : 100;
                    $color = $pct >= 90 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-red-500');
                    $badgeClass = $pct >= 90 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($pct >= 50 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400');
                @endphp
                <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-4 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs uppercase tracking-wider">{{ $labels[$type] }}</h3>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $badgeClass }}">{{ $pct }}%</span>
                        </div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mb-2">
                            {{ $stat['translated'] }}<span class="text-sm font-normal text-slate-400">/{{ $stat['total'] }}</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden mb-4">
                            <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @if($stat['translated'] < $stat['total'])
                        <button wire:click="translateType('{{ $type }}')" wire:loading.attr="disabled" class="w-full py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 rounded-lg text-xs font-bold transition flex justify-center items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                            Translate All
                        </button>
                    @else
                        <div class="text-center py-1.5 text-emerald-600 dark:text-emerald-400 text-xs font-bold bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/30">
                            Complete ✓
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Untranslated Items Section -->
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm overflow-hidden">
            <div class="border-b border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex overflow-x-auto">
                @foreach($typeMap as $type)
                    <button wire:click="$set('activeType', '{{ $type }}')" class="px-6 py-4 text-sm font-bold border-b-2 whitespace-nowrap transition {{ $activeType === $type ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50' }}">
                        {{ $labels[$type] }}
                        @if($stats[$type]['total'] - $stats[$type]['translated'] > 0)
                            <span class="ml-2 px-1.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-[10px] text-slate-600 dark:text-slate-300">
                                {{ $stats[$type]['total'] - $stats[$type]['translated'] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
            
            <div class="p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Untranslated {{ $labels[$activeType] }}</h3>
                
                @if($items->isEmpty())
                    <div class="text-center py-12 bg-emerald-50 dark:bg-emerald-900/10 rounded-2xl border border-emerald-100 dark:border-emerald-900/30">
                        <svg class="w-12 h-12 text-emerald-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-emerald-600 dark:text-emerald-400 font-bold">All items translated! ✓</p>
                    </div>
                @else
                    <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-xl">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 font-bold uppercase text-xs tracking-wider">
                                    <th class="py-3 px-4 w-20">ID</th>
                                    <th class="py-3 px-4">Title / Label</th>
                                    <th class="py-3 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($items as $item)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">
                                        <td class="py-3 px-4 text-slate-500 dark:text-slate-400 font-medium">#{{ $item->id }}</td>
                                        <td class="py-3 px-4 text-slate-900 dark:text-white font-semibold">
                                            {{ $item->{$labelField} }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <button wire:click="translateSingle('{{ $activeType }}', {{ $item->id }})" class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 dark:text-indigo-400 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 ml-auto">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                                Translate
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($items->count() === 50)
                        <div class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
                            Showing first 50 untranslated items. Translate these to see more.
                        </div>
                    @endif
                @endif
            </div>
        </div>

    </div>
</div>
