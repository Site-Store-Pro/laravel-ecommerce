<x-public-layout>
    @section('title', $page->meta_title ?: $page->title)

    @push('meta')
        @if($page->meta_description)
            <meta name="description" content="{{ $page->meta_description }}">
        @endif
    @endpush

    @push('styles')
        @if($page->custom_css)
            @if(str_contains($page->custom_css, '<style') || str_contains($page->custom_css, '<link'))
                {!! $page->custom_css !!}
            @else
                <style>
                    {!! $page->custom_css !!}
                </style>
            @endif
        @endif
    @endpush

    <div class="min-h-screen pb-12 {{ $page->background_image ? 'bg-fixed bg-cover bg-center' : '' }}"
         style="{{ $page->background_image ? 'background-image: url(\'' . (str_starts_with($page->background_image, 'http') ? $page->background_image : Storage::url($page->background_image)) . '\');' : '' }}">
        
        <!-- Header Section -->
        @if($page->header_image)
            <div class="relative py-24 bg-cover bg-center text-white mb-12 shadow-inner" 
                 style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.6)), url('{{ str_starts_with($page->header_image, 'http') ? $page->header_image : Storage::url($page->header_image) }}');">
                <div class="max-w-5xl mx-auto px-6 relative z-10">
                    @if($page->show_title)
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                            {{ $page->title }}
                        </h1>
                    @endif
                    @if($page->show_author || $page->show_date)
                        <div class="flex items-center gap-3 text-sm text-slate-300 font-medium">
                            @if($page->show_author)
                                <span class="bg-indigo-600/80 px-2.5 py-1 rounded-full text-xs text-white uppercase tracking-wider">Author</span>
                                <span>{{ $page->custom_author ?: ($page->author ? $page->author->name : 'System') }}</span>
                            @endif
                            @if($page->show_author && $page->show_date)
                                <span>•</span>
                            @endif
                            @if($page->show_date)
                                <span>{{ $page->created_at->format('M d, Y') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Standard Clean Header -->
            @if($page->show_title || $page->show_author || $page->show_date)
                <div class="max-w-5xl mx-auto px-6 pt-12 pb-6">
                    <div class="border-b border-slate-200/80 pb-6 mb-8">
                        @if($page->show_title)
                            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 bg-gradient-to-r from-slate-900 to-indigo-950 bg-clip-text text-transparent mb-4">
                                {{ $page->title }}
                            </h1>
                        @endif
                        @if($page->show_author || $page->show_date)
                            <div class="flex items-center gap-3 text-sm text-slate-500">
                                @if($page->show_author)
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wider">Published By</span>
                                    <span class="font-semibold text-slate-700">{{ $page->custom_author ?: ($page->author ? $page->author->name : 'System') }}</span>
                                @endif
                                @if($page->show_author && $page->show_date)
                                    <span>•</span>
                                @endif
                                @if($page->show_date)
                                    <span>{{ $page->created_at->format('M d, Y') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <!-- Main Page Content Content Area -->
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                @if(in_array($page->layout_type, [2, 4]) && !empty($page->left_col))
                    <!-- Left Sidebar -->
                    <div class="w-full lg:w-1/4 space-y-6">
                        <div class="bg-white/95 backdrop-blur-md border border-slate-100 rounded-3xl p-6 shadow-xl shadow-slate-100/40 ">
                            {!! $page->left_col !!}
                        </div>
                    </div>
                @endif

                <!-- Main Column -->
                <div class="w-full lg:flex-1 space-y-6">
                    <div class="bg-white/95 backdrop-blur-md border border-slate-100 rounded-3xl p-8 md:p-12 shadow-xl shadow-slate-100/40 ">
                        {!! $page->content !!}
                    </div>
                </div>

                @if(in_array($page->layout_type, [3, 4]) && !empty($page->right_col))
                    <!-- Right Sidebar -->
                    <div class="w-full lg:w-1/4 space-y-6">
                        <div class="bg-white/95 backdrop-blur-md border border-slate-100 rounded-3xl p-6 shadow-xl shadow-slate-100/40 ">
                            {!! $page->right_col !!}
                        </div>
                    </div>
                @endif
                
            </div>
        </div>
    </div>

    @push('scripts')
        @if($page->custom_js)
            @if(str_contains($page->custom_js, '<script'))
                {!! $page->custom_js !!}
            @else
                <script>
                    {!! $page->custom_js !!}
                </script>
            @endif
        @endif

        <!-- Livewire Dynamic Script Reloader to re-run embedded page script blocks -->
        <script>
            document.addEventListener('livewire:navigated', function () {
                // Find all scripts inside dynamic container and evaluate them
                const container = document.querySelector('.prose');
                if (container) {
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });
                }
            });
        </script>
    @endpush
</x-public-layout>
