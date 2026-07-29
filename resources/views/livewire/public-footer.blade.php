<footer class="footer_container border-t border-slate-200 dark:border-slate-800">
    
    {{-- Footer Row #1 --}}
    @if(isset($parsedBlocks['footer_row1']) && $parsedBlocks['footer_row1']['block']->is_active_desktop && !empty($parsedBlocks['footer_row1']['content']))
        <div class="footer_row1">
            <div class="max-w-7xl w-full px-4 flex items-center justify-center">
                {!! $parsedBlocks['footer_row1']['content'] !!}
            </div>
        </div>
    @endif

    {{-- Footer Row #2 --}}
    @if(isset($parsedBlocks['footer_row2']) && $parsedBlocks['footer_row2']['block']->is_active_desktop && !empty($parsedBlocks['footer_row2']['content']))
        <div class="footer_row2">
            <div class="max-w-7xl w-full px-4 flex items-center justify-center">
                {!! $parsedBlocks['footer_row2']['content'] !!}
            </div>
        </div>
    @endif

    {{-- Footer Primary Columns Container --}}
    @if(isset($parsedBlocks['site_footer_columns_primary']) && $parsedBlocks['site_footer_columns_primary']['block']->is_active_desktop)
        <div class="footer_contents">
            <ul class="site_footer_columns_primary footer_columns">
                @if(isset($parsedBlocks['footer_col1']) && $parsedBlocks['footer_col1']['block']->is_active_desktop)
                    <li class="footer_col1">{!! $parsedBlocks['footer_col1']['content'] !!}</li>
                @endif
                @if(isset($parsedBlocks['footer_col2']) && $parsedBlocks['footer_col2']['block']->is_active_desktop)
                    <li class="footer_col2">{!! $parsedBlocks['footer_col2']['content'] !!}</li>
                @endif
                @if(isset($parsedBlocks['footer_col3']) && $parsedBlocks['footer_col3']['block']->is_active_desktop)
                    <li class="footer_col3">{!! $parsedBlocks['footer_col3']['content'] !!}</li>
                @endif
                @if(isset($parsedBlocks['footer_col4']) && $parsedBlocks['footer_col4']['block']->is_active_desktop)
                    <li class="footer_col4">{!! $parsedBlocks['footer_col4']['content'] !!}</li>
                @endif
                @if(isset($parsedBlocks['footer_col5']) && $parsedBlocks['footer_col5']['block']->is_active_desktop)
                    <li class="footer_col5">{!! $parsedBlocks['footer_col5']['content'] !!}</li>
                @endif
            </ul>
        </div>
    @endif

    {{-- Footer Row #3 --}}
    @if(isset($parsedBlocks['footer_row3']) && $parsedBlocks['footer_row3']['block']->is_active_desktop && !empty($parsedBlocks['footer_row3']['content']))
        <div class="footer_row3">
            <div class="max-w-7xl w-full px-4 flex items-center justify-center">
                {!! $parsedBlocks['footer_row3']['content'] !!}
            </div>
        </div>
    @endif

    {{-- Footer Row #4 (Copyright / Bottom Bar) --}}
    @if(isset($parsedBlocks['footer_row4']) && $parsedBlocks['footer_row4']['block']->is_active_desktop)
        <div class="footer_row4">
            {!! $parsedBlocks['footer_row4']['content'] !!}
        </div>
    @else
        <div class="footer_row4">
            <div>© {{ date('Y') }} {{ \App\Models\CmsSetting::getSiteName() }}. @label('footer.all_rights_reserved', 'All rights reserved.')</div>
        </div>
    @endif

    {{-- Back to Top Floating Button (Hidden until scrolled down) --}}
    <button id="backtop"
            aria-label="Back to top"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            x-data="{ show: false }"
            x-init="show = (window.pageYOffset || document.documentElement.scrollTop) > 200"
            x-on:scroll.window="show = (window.pageYOffset || document.documentElement.scrollTop) > 200"
            :class="{ 'show': show }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            style="display: none;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
    </button>
</footer>
