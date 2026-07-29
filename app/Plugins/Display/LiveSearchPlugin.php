<?php

namespace App\Plugins\Display;

use App\Models\Language;
use App\Models\Plugin;
use App\Plugins\Contracts\DisplayPlugin;
use App\Services\LiveSearchService;
use Illuminate\Support\Facades\Log;

class LiveSearchPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'live-search-2026';
    }

    public function name(): string
    {
        return 'Live Search 2026';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            // Resolve active and default language.
            // The SetLocale middleware binds the current Language in 'current.language'.
            $currentLang   = app()->bound('current.language') ? app('current.language') : null;
            $defaultLang   = Language::getDefault();
            $langId        = $currentLang?->id ?? $defaultLang->id;
            $defaultLangId = $defaultLang->id;
            $isDefault     = ($langId === $defaultLangId);

            // Merge per-language setting overrides (button label, placeholder) when not
            // on the default language. Falls back gracefully to base settings.
            $settings = $isDefault
                ? $plugin->getSettings()
                : $plugin->getSettingsForLanguage($langId);

            $mode        = strtolower($params['mode']        ?? $settings['default_mode'] ?? 'input');
            $layout      = strtolower($params['layout']      ?? $settings['layout']       ?? 'list');
            $placeholder = $params['placeholder']            ?? $settings['placeholder']  ?? null;
            $btnLabel    = $params['button_label']           ?? $settings['button_label'] ?? null;
            $defaultCss  = $plugin->getSetting('default_css', '');
            $customCss   = $params['custom_css']             ?? $settings['custom_css']   ?? '';
            $customJs    = $params['custom_js']              ?? $settings['custom_js']    ?? '';

            if ($mode === 'results') {
                return $this->renderResultsView($params, $layout, $langId, $defaultLangId);
            }

            return $this->renderInputWidget($placeholder, $btnLabel, $defaultCss, $customCss, $customJs);

        } catch (\Throwable $e) {
            Log::error('[LiveSearchPlugin] Render error: ' . $e->getMessage());
            return '<!-- [plugin-error: live-search-2026] ' . e($e->getMessage()) . ' -->';
        }
    }

    /**
     * Render the Live Search input box widget with instant autocomplete & JS overrides.
     */
    private function renderInputWidget(?string $placeholder, ?string $btnLabel, string $defaultCss = '', string $customCss = '', string $customJs = ''): string
    {
        $placeholder = $placeholder ?? siteLabel('live_search.placeholder', 'Search products, pages, articles...');
        $btnLabel    = $btnLabel    ?? siteLabel('live_search.button_label', 'Search');

        $uniqueId = 'live_search_' . substr(md5(microtime()), 0, 8);
        $searchUrl = url('/search');

        $html = '';

        if (!empty($defaultCss) || !empty($customCss)) {
            $html .= "<style>\n";
            if (!empty($defaultCss)) {
                $html .= \App\Services\CssMinifierService::minify($defaultCss) . "\n";
            }
            if (!empty($customCss)) {
                $html .= \App\Services\CssMinifierService::minify($customCss) . "\n";
            }
            $html .= "</style>";
        }

        $html .= '
        <div id="' . $uniqueId . '" class="live-search-2026-wrapper relative w-full max-w-2xl mx-auto"
             x-data="{
                 query: \'\',
                 results: [],
                 open: false,
                 loading: false,
                 fetchResults() {
                     if (this.query.trim().length < 2) {
                         this.results = [];
                         this.open = false;
                         return;
                     }
                     this.loading = true;
                     fetch(\'/api/live-search-api?q=\' + encodeURIComponent(this.query))
                         .then(res => res.json())
                         .then(data => {
                             this.results = Array.isArray(data) ? data.slice(0, 15) : [];
                             this.loading = false;
                             this.open = true;
                         })
                         .catch(() => {
                             this.loading = false;
                         });
                 }
             }"
             @click.away="open = false">

            <form action="' . e($searchUrl) . '" method="GET" class="live-search-form relative flex items-stretch w-full shadow-sm rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 transition-all focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 overflow-hidden">
                <input type="text" name="q" 
                       x-model="query" 
                       @input.debounce.300ms="fetchResults()"
                       @focus="if (results.length > 0) open = true"
                       placeholder="' . e($placeholder) . '" 
                       class="w-full max-w-[250px] py-3 px-4 bg-transparent text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none font-medium"
                       autocomplete="off" />
                
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm transition-colors duration-150 flex items-center justify-center shrink-0 border-l border-indigo-700 rounded-r-xl rounded-l-none !rounded-tl-none !rounded-bl-none">
                    <span>' . e($btnLabel) . '</span>
                </button>
            </form>

            <div x-show="open" x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute left-0 right-0 top-full mt-2 z-50 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden max-h-96 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/50">
                
                <template x-if="loading">
                    <div class="p-4 text-center text-xs font-semibold text-slate-400 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>' . e(siteLabel('live_search.loading_message', 'Searching catalog & articles...')) . '</span>
                    </div>
                </template>

                <template x-if="!loading && results.length === 0">
                    <div class="p-6 text-center text-xs text-slate-500">
                        ' . e(siteLabel('live_search.no_results_inline', 'No active results found for')) . ' &ldquo;<span x-text="query" class="font-bold text-slate-700 dark:text-slate-300"></span>&rdquo;.
                    </div>
                </template>

                <template x-for="item in results" :key="item.type + \'_\' + item.id">
                    <a :href="item.url" class="flex items-center gap-2.5 px-3 py-2 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 transition group border-b border-slate-100/60 dark:border-slate-700/40 last:border-0">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200 dark:border-slate-600">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.title" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <span x-html="item.icon_svg" class="text-slate-400 group-hover:text-indigo-600 transition-colors w-4 h-4"></span>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 leading-tight">
                                <span :class="item.badge_class" class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider shrink-0" x-text="item.type_label"></span>
                                <h4 class="text-[12px] font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate" x-text="item.title"></h4>
                            </div>
                            <p class="text-[11px] text-slate-400 dark:text-slate-400 truncate mt-0.5" style="font-size: 11px !important; line-height: 1.25;" x-text="item.snippet"></p>
                        </div>
                        <div class="text-slate-300 group-hover:text-indigo-600 transition-colors shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                </template>
            </div>
        </div>';

        if (!empty($customJs)) {
            $html .= "\n<script>\n(function(){\n" . $customJs . "\n})();\n</script>\n";
        }

        return $html;
    }

    /**
     * Render the Multi-Content Search Results View.
     * All search and translation logic is delegated to LiveSearchService.
     */
    private function renderResultsView(array $params, string $layout, int $langId = 0, int $defaultLangId = 0): string
    {
        $queryStr = trim($params['query'] ?? request()->query('q') ?? request()->query('search') ?? '');

        // Resolve language IDs if called without them (e.g. direct shortcode without render())
        if ($langId === 0 || $defaultLangId === 0) {
            $currentLang   = app()->bound('current.language') ? app('current.language') : null;
            $defaultLang   = Language::getDefault();
            $langId        = $currentLang?->id ?? $defaultLang->id;
            $defaultLangId = $defaultLang->id;
        }

        $results = collect();

        if (!empty($queryStr)) {
            $results = collect((new LiveSearchService())->search($queryStr, $langId, $defaultLangId));
        }

        $html = '<div class="w-full max-w-6xl mx-auto space-y-6 py-6 px-4 sm:px-0">';

        // Header search box
        $html .= '<div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-4">';
        $html .= '<div class="flex items-center justify-between flex-wrap gap-4">';
        $html .= '<div><h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">' . e(siteLabel('live_search.results_heading', 'Catalog & Content Search')) . '</h2>';
        if (!empty($queryStr)) {
            $html .= '<p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">' . e(siteLabel('live_search.results_showing', 'Showing result(s) for')) . ' ' . $results->count() . ' &ldquo;<strong class="text-indigo-600 dark:text-indigo-400">' . e($queryStr) . '</strong>&rdquo;</p>';
        } else {
            $html .= '<p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">' . e(siteLabel('live_search.results_subtitle', 'Enter a keyword below to search across products, CMS pages, documentation, and customer reviews.')) . '</p>';
        }
        $html .= '</div>';
        $html .= '</div>';

        // Search Input Bar
        // Pass null so renderInputWidget() uses siteLabel() fallbacks (respects active language)
        $html .= $this->renderInputWidget(null, null, '', '');
        $html .= '</div>';

        if (empty($queryStr)) {
            $html .= '<div class="p-12 text-center bg-slate-50 dark:bg-slate-800/40 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 text-slate-500">';
            $html .= '<svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>';
            $html .= '<h3 class="text-base font-bold text-slate-800 dark:text-slate-200">' . e(siteLabel('live_search.start_title', 'Start Your Search')) . '</h3>';
            $html .= '<p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">' . e(siteLabel('live_search.start_subtitle', 'Type a product title, page name, or article keyword in the search box above.')) . '</p>';
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }

        if ($results->isEmpty()) {
            $html .= '<div class="p-12 text-center bg-slate-50 dark:bg-slate-800/40 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 text-slate-500 space-y-3">';
            $html .= '<svg class="w-12 h-12 mx-auto text-rose-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            $html .= '<h3 class="text-base font-bold text-slate-800 dark:text-slate-200">' . e(siteLabel('live_search.no_results_title', 'No Matching Results Found')) . '</h3>';
            $html .= '<p class="text-xs text-slate-500 max-w-sm mx-auto">' . e(siteLabel('live_search.no_results_body', "We couldn't find anything matching your search. Try broader terms or check spelling.")) . '</p>';
            $html .= '<div class="pt-2"><a href="' . url('/shop') . '" class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">' . e(siteLabel('live_search.browse_catalog', 'Browse Full Catalog')) . ' &rarr;</a></div>';
            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }

        // Results Container (Grid or List)
        if ($layout === 'grid') {
            $html .= '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
            foreach ($results as $item) {
                $html .= '<a href="' . e($item['url']) . '" class="group p-5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl shadow-sm hover:shadow-md hover:border-indigo-500 transition flex flex-col justify-between space-y-4">';
                $html .= '<div class="space-y-3">';
                $html .= '<div class="flex items-center justify-between gap-2">';
                $html .= '<span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold uppercase tracking-wider ' . $item['badge_class'] . '">' . e($item['type_label']) . '</span>';
                if ($item['image']) {
                    $html .= '<img src="' . e($item['image']) . '" alt="' . e($item['title']) . '" class="w-10 h-10 object-cover rounded-xl border border-slate-200 dark:border-slate-700">';
                } else {
                    $html .= '<div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors">' . $item['icon_svg'] . '</div>';
                }
                $html .= '</div>';
                $html .= '<h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">' . e($item['title']) . '</h3>';
                $html .= '<p class="text-[13px] text-slate-500 dark:text-slate-400 line-clamp-3 leading-snug">' . e($item['snippet']) . '</p>';
                $html .= '</div>';
                $html .= '<div class="pt-2 border-t border-slate-100 dark:border-slate-700/50 flex items-center justify-between text-xs font-bold text-indigo-600 dark:text-indigo-400">';
                $html .= '<span>' . e(siteLabel('live_search.view_details', 'View Details')) . '</span>';
                $html .= '<svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>';
                $html .= '</div>';
                $html .= '</a>';
            }
            $html .= '</div>';
        } else {
            // List view
            $html .= '<div class="space-y-4">';
            foreach ($results as $item) {
                $html .= '<a href="' . e($item['url']) . '" class="group p-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-500 transition flex items-center gap-4">';
                $html .= '<div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 overflow-hidden border border-slate-200 dark:border-slate-600">';
                if ($item['image']) {
                    $html .= '<img src="' . e($item['image']) . '" alt="' . e($item['title']) . '" class="w-full h-full object-cover">';
                } else {
                    $html .= '<span class="text-slate-400 group-hover:text-indigo-600 transition-colors">' . $item['icon_svg'] . '</span>';
                }
                $html .= '</div>';
                $html .= '<div class="flex-1 min-w-0 space-y-0.5">';
                $html .= '<div class="flex items-center gap-2">';
                $html .= '<span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider ' . $item['badge_class'] . '">' . e($item['type_label']) . '</span>';
                $html .= '<h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">' . e($item['title']) . '</h3>';
                $html .= '</div>';
                $html .= '<p class="text-[13px] text-slate-500 dark:text-slate-400 truncate leading-snug">' . e($item['snippet']) . '</p>';
                $html .= '</div>';
                $html .= '<div class="text-slate-300 group-hover:text-indigo-600 transition-colors shrink-0 pr-2">';
                $html .= '<svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
                $html .= '</div>';
                $html .= '</a>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
