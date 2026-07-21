@if($selectedVariant && $selectedVariant->video_preview)
    <div class="w-full">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Video Preview</h2>
        @php
            $videoUrl = $selectedVariant->video_preview;
            $embedUrl = null;
            if (str_contains($videoUrl, 'youtube.com') || str_contains($videoUrl, 'youtu.be')) {
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match);
                $youtubeId = $match[1] ?? null;
                $embedUrl = $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}?rel=0" : null;
            } elseif (str_contains($videoUrl, 'vimeo.com')) {
                preg_match('%vimeo\.com/(?:channels/(?:\w+/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|/|\?)%i', $videoUrl, $match);
                $vimeoId = $match[3] ?? null;
                $embedUrl = $vimeoId ? "https://player.vimeo.com/video/{$vimeoId}" : null;
            }
        @endphp

        @if($embedUrl)
            <div class="aspect-video w-full rounded-3xl overflow-hidden shadow-sm border border-slate-100 bg-black">
                <iframe src="{{ $embedUrl }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        @elseif(str_starts_with($videoUrl, 'http') || str_starts_with($videoUrl, '/'))
            <video src="{{ $videoUrl }}" controls class="w-full rounded-3xl shadow-sm border border-slate-100 bg-black aspect-video object-contain"></video>
        @else
            <div class="aspect-video w-full rounded-3xl overflow-hidden shadow-sm border border-slate-100 bg-slate-950 flex items-center justify-center text-white p-4">
                <span class="text-slate-400">Watch video: <a href="{{ $videoUrl }}" target="_blank" class="text-indigo-400 hover:underline ml-1 font-semibold">{{ $videoUrl }}</a></span>
            </div>
        @endif
    </div>
@endif
