<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Wrapper Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-3 space-y-2">
                <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700/60 rounded-3xl p-6 shadow-sm space-y-1">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Shop Administration</h2>
                    
                    <a href="{{ route('admin.ecommerce.pending-orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.products') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.ecommerce.import') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 transition duration-150">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import Products
                    </a>

                    <a href="{{ route('admin.ecommerce.categories') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Categories
                    </a>

                    <a href="{{ route('admin.ecommerce.brands') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        Brands
                    </a>

                    <a href="{{ route('admin.ecommerce.orders') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Orders
                    </a>

                    <a href="{{ route('admin.ecommerce.inventory') }}" wire:navigate class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition duration-150">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Inventory
                    </a>
                </div>
            </div>

            <!-- Main Panel Content -->
            <div class="lg:col-span-9 space-y-6">

                <!-- Header Section -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </span>
                            <div>
                                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">Product Bulk Import System</h1>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Import products and variants from CSV (`.csv`) or Excel (`.xlsx`, `.xls`) files.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Template Download Actions -->
                    <div class="flex items-center gap-3 shrink-0">
                        <button wire:click="downloadSampleCsv" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs transition shadow-sm">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Sample CSV
                        </button>
                        <button wire:click="downloadSampleExcel" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs transition shadow-sm">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Sample Excel
                        </button>
                    </div>
                </div>

                <!-- Notifications -->
                @if(session()->has('status'))
                    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session()->has('error'))
                    <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-semibold flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Upload Section -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 p-8 shadow-sm space-y-6">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200">1. Select Import Spreadsheet File</h3>

                    <div class="relative border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl p-8 text-center hover:border-indigo-500 dark:hover:border-indigo-400 transition cursor-pointer bg-slate-50/50 dark:bg-slate-900/40">
                        <input type="file" wire:model="importFile" accept=".csv,.txt,.xlsx,.xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-3">
                            <div class="w-14 h-14 bg-indigo-50 dark:bg-indigo-950/60 rounded-2xl flex items-center justify-center mx-auto text-indigo-600 dark:text-indigo-400">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 0115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Drop your `.csv`, `.xlsx`, or `.xls` file here, or browse</p>
                                <p class="text-xs text-slate-400 mt-1">Supports legacy e-commerce platform exports up to 10MB.</p>
                            </div>
                            @error('importFile') <span class="text-xs text-rose-500 font-semibold block mt-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($importFile)
                        <div class="p-4 bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-800 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 dark:text-slate-100">{{ $importFile->getClientOriginalName() }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ number_format($importFile->getSize() / 1024, 1) }} KB &bull; {{ count($allRows) }} rows detected</p>
                                </div>
                            </div>
                            <button wire:click="resetUpload" type="button" class="text-xs font-bold text-rose-600 hover:underline">Remove File</button>
                        </div>
                    @endif
                </div>

                <!-- Column Mapping & Configuration -->
                @if($fileParsed && !empty($headers))
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200">2. Configure Field Mapping</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Map your spreadsheet headers to standard database fields.</p>
                            </div>
                            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 text-xs font-bold rounded-full">
                                {{ count($headers) }} Columns Found
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($availableStandardKeys as $stdKey => $label)
                                <div class="p-3 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 space-y-1.5">
                                    <label class="text-[11px] font-bold text-slate-600 dark:text-slate-300 block uppercase tracking-wider">{{ $label }}</label>
                                    <select wire:model="columnMapping.{{ $stdKey }}" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-medium text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:border-indigo-500">
                                        <option value="">-- Do Not Import --</option>
                                        @foreach($headers as $h)
                                            <option value="{{ $h }}">{{ $h }}</option>
                                        @endforeach
                                    </select>
                                    @if($stdKey === 'variant_attributes')
                                        <p class="text-[10px] text-slate-400 leading-tight pt-0.5">
                                            Format: <code class="font-mono bg-slate-200 dark:bg-slate-700 px-1 rounded">Color:Black, Size:Large</code>
                                            &mdash; or JSON: <code class="font-mono bg-slate-200 dark:bg-slate-700 px-1 rounded">{"Color":"Black"}</code>
                                        </p>
                                    @elseif($stdKey === 'image_url_source')
                                        <p class="text-[10px] text-slate-400 leading-tight pt-0.5">Value: <code class="font-mono bg-slate-200 dark:bg-slate-700 px-1 rounded">1</code> = Direct URL &nbsp;|&nbsp; <code class="font-mono bg-slate-200 dark:bg-slate-700 px-1 rounded">0</code> = Download &amp; store locally</p>
                                    @elseif($stdKey === 'categories')
                                        <p class="text-[10px] text-slate-400 leading-tight pt-0.5">Supports hierarchy: <code class="font-mono bg-slate-200 dark:bg-slate-700 px-1 rounded">Electronics &gt; Audio</code> &nbsp;or comma-separated</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Live Data Preview Table -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-200">3. Live Import Data Preview</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Previewing first 50 rows of {{ count($allRows) }} items ready to import.</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider">
                                        <th class="py-3 px-4">Row</th>
                                        <th class="py-3 px-4">Title</th>
                                        <th class="py-3 px-4">Category</th>
                                        <th class="py-3 px-4">Brand</th>
                                        <th class="py-3 px-4">Public Price</th>
                                        <th class="py-3 px-4">Wholesale Price</th>
                                        <th class="py-3 px-4">Variant SKU</th>
                                        <th class="py-3 px-4">Variant Name / Label</th>
                                        <th class="py-3 px-4">Variant Attributes</th>
                                        <th class="py-3 px-4">Img Mode</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-300 font-medium">
                                    @foreach($previewRows as $idx => $r)
                                        @php
                                            $mappedTitle = $columnMapping['title'] ?? null;
                                            $mappedCat   = $columnMapping['categories'] ?? null;
                                            $mappedBrand = $columnMapping['brand'] ?? null;
                                            $mappedPub   = $columnMapping['public_price'] ?? null;
                                            $mappedWs    = $columnMapping['wholesale_price'] ?? null;
                                            $mappedSku   = $columnMapping['variant_sku'] ?? null;
                                            $mappedVarName  = $columnMapping['variant_name'] ?? null;
                                            $mappedSpecs = $columnMapping['variant_attributes'] ?? null;
                                            $mappedImgSource = $columnMapping['image_url_source'] ?? null;
                                        @endphp
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/40">
                                            <td class="py-2.5 px-4 font-mono font-bold text-slate-400">#{{ $idx + 1 }}</td>
                                            <td class="py-2.5 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $mappedTitle ? ($r[$mappedTitle] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4 text-indigo-600 dark:text-indigo-400">{{ $mappedCat ? ($r[$mappedCat] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4">{{ $mappedBrand ? ($r[$mappedBrand] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4 font-mono font-bold">{{ $mappedPub ? ($r[$mappedPub] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4 font-mono font-bold text-slate-500">{{ $mappedWs ? ($r[$mappedWs] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4 font-mono text-emerald-600 dark:text-emerald-400">{{ $mappedSku ? ($r[$mappedSku] ?? '') : '' }}</td>
                                            <td class="py-2.5 px-4 text-violet-600 dark:text-violet-400 font-medium">
                                                {{ $mappedVarName ? ($r[$mappedVarName] ?? '') : '' }}
                                            </td>
                                            <td class="py-2.5 px-4 text-slate-500">
                                                @if($mappedSpecs && isset($r[$mappedSpecs]) && $r[$mappedSpecs] !== '')
                                                    @php
                                                        $rawAttr = $r[$mappedSpecs];
                                                        $parsedAttrs = [];
                                                        // Try JSON first
                                                        if (str_starts_with(trim($rawAttr), '{')) {
                                                            $decoded = json_decode($rawAttr, true);
                                                            if (is_array($decoded)) { $parsedAttrs = $decoded; }
                                                        }
                                                        // Fall back to Key:Value, Key:Value parsing
                                                        if (empty($parsedAttrs)) {
                                                            foreach (preg_split('/[,|]+/', $rawAttr) as $pair) {
                                                                $pair = trim($pair);
                                                                if (str_contains($pair, ':')) {
                                                                    [$k, $v] = explode(':', $pair, 2);
                                                                    $parsedAttrs[trim($k)] = trim($v);
                                                                } elseif (str_contains($pair, '=')) {
                                                                    [$k, $v] = explode('=', $pair, 2);
                                                                    $parsedAttrs[trim($k)] = trim($v);
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if(!empty($parsedAttrs))
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($parsedAttrs as $attrKey => $attrVal)
                                                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-[10px] font-semibold">
                                                                    <span class="text-slate-500 dark:text-slate-400">{{ $attrKey }}:</span>
                                                                    <span class="text-indigo-700 dark:text-indigo-300">{{ $attrVal }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-slate-400 italic text-[10px]">{{ $rawAttr }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-300 dark:text-slate-600 text-[10px] italic">—</span>
                                                @endif
                                            </td>
                                            <td class="py-2.5 px-4">
                                                @php $imgSrc = $mappedImgSource ? ($r[$mappedImgSource] ?? '0') : '0'; @endphp
                                                @if((int)$imgSrc === 1)
                                                    <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-bold">Direct URL</span>
                                                @else
                                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-bold">Local Download</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Submit Button -->
                        <div class="pt-4 flex justify-end">
                            <button wire:click="executeImport" wire:loading.attr="disabled" type="button" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-500/30 hover:scale-105 transition-all flex items-center gap-2">
                                <span wire:loading.remove>Execute Bulk Product Import ({{ count($allRows) }} Rows)</span>
                                <span wire:loading class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Processing Product Import...
                                </span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Import Execution Report Stats -->
                @if($importStats)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 p-8 shadow-sm space-y-6 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-2xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-base font-extrabold text-slate-800 dark:text-slate-100">Import Execution Summary</h3>
                                <p class="text-xs text-slate-400">Detailed count of created and updated records.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-2xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $importStats['products_created'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Products Created</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-2xl font-extrabold text-sky-600 dark:text-sky-400">{{ $importStats['products_updated'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Products Updated</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $importStats['variants_created'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Variants Created</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-2xl font-extrabold text-purple-600 dark:text-purple-400">{{ $importStats['categories_created'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Categories Created</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-xl font-extrabold text-violet-600 dark:text-violet-400">{{ $importStats['brands_created'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Brands Auto-Created</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-xl font-extrabold text-amber-600 dark:text-amber-400">{{ $importStats['images_processed'] }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Images Processed</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/60 rounded-2xl border border-slate-200/80 dark:border-slate-700/60 text-center">
                                <span class="block text-xl font-extrabold text-rose-600 dark:text-rose-400">{{ count($importStats['errors']) }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Row Errors</span>
                            </div>
                        </div>

                        @if(!empty($importStats['errors']))
                            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 rounded-2xl space-y-2">
                                <h4 class="text-xs font-bold text-rose-800 dark:text-rose-200 uppercase tracking-wider">Row Execution Errors</h4>
                                <ul class="list-disc pl-5 text-xs text-rose-700 dark:text-rose-300 space-y-1">
                                    @foreach($importStats['errors'] as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
