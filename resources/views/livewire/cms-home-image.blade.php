<div>
    @if($page)
        @if($page->background_image)
            <style>
                body {
                    background-image: url('{{ str_starts_with($page->background_image, "http") ? $page->background_image : \Storage::url($page->background_image) }}') !important;
                    background-attachment: fixed !important;
                    background-size: cover !important;
                    background-position: center !important;
                }
            </style>
        @endif
        @if($page->header_image)
            <div class="w-full relative py-20 bg-cover bg-center text-white mb-12 shadow-inner" 
                 style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5)), url('{{ str_starts_with($page->header_image, "http") ? $page->header_image : \Storage::url($page->header_image) }}');">
                <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">
                    @if($page->show_title)
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                            {{ $page->title }}
                        </h1>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
