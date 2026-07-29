<div id="{{ $swiperId }}_wrapper" class="slideshow-plugin-wrapper" style="position: relative;">
    @if(!empty($defaultCss) || !empty($customCss))
        <style>
            @if(!empty($defaultCss))
                {!! \App\Services\CssMinifierService::minify($defaultCss) !!}
            @endif
            @if(!empty($customCss))
                #{{ $swiperId }}_wrapper {
                    {!! \App\Services\CssMinifierService::minify($customCss) !!}
                }
            @endif
        </style>
    @endif

    <div class="swiper {{ $swiperId }}">
        <div class="swiper-wrapper">
            @foreach($slides as $slide)
                @php
                    $alignMap = [
                        'top-left'      => 'align-items: flex-start; justify-content: flex-start',
                        'top-center'    => 'align-items: flex-start; justify-content: center',
                        'top-right'     => 'align-items: flex-start; justify-content: flex-end',
                        'middle-left'   => 'align-items: center; justify-content: flex-start',
                        'middle-center' => 'align-items: center; justify-content: center',
                        'middle-right'  => 'align-items: center; justify-content: flex-end',
                        'bottom-left'   => 'align-items: flex-end; justify-content: flex-start',
                        'bottom-center' => 'align-items: flex-end; justify-content: center',
                        'bottom-right'  => 'align-items: flex-end; justify-content: flex-end',
                    ];
                    $flexAlign = $alignMap[$slideshow->slide_show_alignment ?? 'middle-center'] ?? 'align-items: center; justify-content: center';
                @endphp

                <div class="swiper-slide slideshow-plugin-slide" style="background-image: url('{{ $slide->desktopImageUrl() }}');">
                    <style>
                        @media (max-width: 768px) {
                            #{{ $swiperId }}_wrapper .swiper-slide:nth-child({{ $loop->iteration }}) {
                                background-image: url('{{ $slide->mobileImageUrl() ?: $slide->desktopImageUrl() }}') !important;
                            }
                        }
                    </style>
                    <div class="slideshow-plugin-overlay" style="{{ $flexAlign }}; pointer-events: none;">
                        <div class="slideshow-plugin-content" style="pointer-events: auto; {{ $slide->slide_content_css }}">
                            @if($slide->Title || $slide->slide_heading)
                                <h2 class="slideshow-plugin-heading" style="{{ $slide->slide_heading_css }}">{{ $slide->slide_heading ?: $slide->Title }}</h2>
                            @endif
                            @if($slide->slide_sub_heading)
                                <p class="slideshow-plugin-subheading">{{ $slide->slide_sub_heading }}</p>
                            @endif
                            @if($slide->SlideURL && $slide->slide_callout_button_label)
                                <a href="{{ $slide->SlideURL }}" class="slideshow-plugin-btn">{{ $slide->slide_callout_button_label }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($nav !== 'off')
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        @endif

        @if($paging !== 'off')
            <div class="swiper-pagination"></div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const swiperOptions = {
        loop: true,
        effect: '{{ $effect }}',
        observeParents: true,
        observer: true,
        @if($autoplay !== 'off')
        autoplay: {
            delay: {{ (int)($speed ?? 5000) }},
            disableOnInteraction: false,
        },
        @endif
        @if($nav !== 'off')
        navigation: {
            nextEl: '#{{ $swiperId }}_wrapper .swiper-button-next',
            prevEl: '#{{ $swiperId }}_wrapper .swiper-button-prev',
        },
        @endif
        @if($paging !== 'off')
        pagination: {
            el: '#{{ $swiperId }}_wrapper .swiper-pagination',
            clickable: true,
        },
        @endif
    };

    new Swiper('.{{ $swiperId }}', swiperOptions);
});
</script>
