@php
    /*
     * Build a FLAT list of every active image across ALL variants.
     * Each entry carries its variantId so clicking a thumbnail can
     * also select the correct variant in the Livewire radio group.
     */
    $allAlpineImages = $product->variants->flatMap(function ($variant) {
        $color = $this->getVariantColor($variant);
        return $variant->images->where('active', 1)->values()->map(fn($img) => [
            'id'         => $img->id,
            'variantId'  => $variant->id,
            'thumb'      => $img->thumbnailUrl(),
            'main'       => $img->mainUrl(),
            'zoom'       => $img->zoomUrl() ?? null,
            'alt'        => $img->image_alt ?? $variant->product->title,
            'zoom_label' => $img->zoom_label ?? null,
            'color'      => $color,
            'path'       => $img->main_path,
        ]);
    });

    // Deduplicate the images by color and their physical file/url.
    $uniqueAlpineImages = $allAlpineImages->unique(function ($img) {
        $colorKey = $img['color'] ? strtolower($img['color']) : 'no-color';
        $pathKey = $img['path'] ? strtolower($img['path']) : $img['id'];
        return $colorKey . '|' . $pathKey;
    })->values();

    $totalImages  = $uniqueAlpineImages->count();
    $initialIndex = 0;
    
    if ($selectedImageSet) {
        $found = $uniqueAlpineImages->search(fn($img) => $img['id'] === $selectedImageSet->id);
        if ($found !== false) {
            $initialIndex = $found;
        } else {
            $selectedColor = $selectedVariant ? $this->getVariantColor($selectedVariant) : null;
            if ($selectedColor) {
                $foundColor = $uniqueAlpineImages->search(fn($img) => $img['color'] && strtolower($img['color']) === strtolower($selectedColor));
                if ($foundColor !== false) $initialIndex = $foundColor;
            }
        }
    } else {
        $selectedColor = $selectedVariant ? $this->getVariantColor($selectedVariant) : null;
        if ($selectedColor) {
            $foundColor = $uniqueAlpineImages->search(fn($img) => $img['color'] && strtolower($img['color']) === strtolower($selectedColor));
            if ($foundColor !== false) $initialIndex = $foundColor;
        }
    }

    $alpineImages = $uniqueAlpineImages->toJson(JSON_HEX_APOS | JSON_HEX_QUOT);

    // Image orientation setting
    $imgOrientation = \App\Models\CmsSetting::get('product_image_orientation', '16:9');
    $aspectClass    = $imgOrientation === '1:1' ? 'aspect-square' : 'aspect-video';
    $objectClass    = $imgOrientation === '1:1' ? 'object-contain' : 'object-cover';
@endphp

{{-- ──────────────────────────────────────────────────────────────
     IMAGE GALLERY (Alpine.js — zero Livewire round-trips)
     ────────────────────────────────────────────────────────────── --}}
<div
    x-data="{
        images: {{ $alpineImages }},
        current: {{ $initialIndex }},
        activeVariantId: {{ $selectedVariant ? $selectedVariant->id : 0 }},
        activeColor: '{{ $selectedVariant ? $this->getVariantColor($selectedVariant) : '' }}',
        zooming: false,
        zoomLocked: false,
        zoomX: '50%',
        zoomY: '50%',
        lightbox: false,
        get img() { return this.images[this.current] ?? null; },
        get hasZoom() { return this.img && this.img.zoom; },
        trackMouse(e) {
            // Clear lock on first real mouse movement so zoom can activate naturally.
            if (this.zoomLocked) { this.zoomLocked = false; }
            const rect = e.currentTarget.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width  * 100).toFixed(2);
            const y = ((e.clientY - rect.top)  / rect.height * 100).toFixed(2);
            this.zoomX = x + '%';
            this.zoomY = y + '%';
        },
        openLightbox() { if (this.img) this.lightbox = true; },
        selectThumb(idx) {
            this.current = idx;
            this.zooming = false;
            this.zoomLocked = false; // thumbnail clicks are deliberate — no need to lock
            const img = this.images[idx];
            if (img) {
                const imgColor = img.color || '';
                if (!imgColor || !this.activeColor || imgColor.toLowerCase() !== this.activeColor.toLowerCase()) {
                    const varId = img.variantId;
                    if (varId && varId !== this.activeVariantId) {
                        this.activeVariantId = varId;
                        this.activeColor = imgColor;
                        $wire.set('selectedVariantId', varId);
                    }
                }
            }
        },
    }"
    @keydown.escape.window="lightbox = false"
    @gallery:variant-changed.window="
        const varId = $event.detail.variantId;
        if (varId === activeVariantId) return;
        activeVariantId = varId;
        activeColor = $event.detail.color || '';
        
        let idx = images.findIndex(i => i.variantId === varId);
        if (idx === -1 && $event.detail.color) {
            idx = images.findIndex(i => i.color && i.color.toLowerCase() === $event.detail.color.toLowerCase());
        }
        if (idx !== -1) { current = idx; }
        // Lock zoom: after a variant switch the browser re-fires mouseenter on the
        // newly rendered image element because the cursor is already over it.
        // zoomLocked prevents that phantom mouseenter from triggering zoom.
        // It clears on the next real mousemove so hover-to-zoom works normally.
        zooming = false;
        zoomLocked = true;
    "
>
    {{-- ── Main image area ────────────────────────────────────── --}}
    <div
        class="{{ $aspectClass }} bg-gradient-to-br from-indigo-50/50 to-violet-50/50 rounded-2xl flex items-center justify-center relative overflow-hidden select-none"
        :class="hasZoom ? 'cursor-crosshair' : (img ? 'cursor-zoom-in' : '')"
        @mouseenter="if(hasZoom && !zoomLocked) zooming = true"
        @mouseleave="zooming = false; zoomLocked = false;"
        @mousemove="if(hasZoom) { trackMouse($event); if(!zoomLocked) zooming = true; }"
        @click="openLightbox()"
    >
        {{-- No image fallback (Alpine-driven so it hides after variant swap) --}}
        <div x-show="!img" class="absolute inset-0 flex items-center justify-center">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#4f46e5_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <span class="p-8 rounded-full bg-white shadow-lg text-indigo-600 relative z-10">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </span>
        </div>

        {{-- Main image (shown when NOT zooming) --}}
        <template x-if="img">
            <img
                :src="img?.main"
                :alt="img?.alt"
                class="w-full h-full {{ $objectClass }} transition-opacity duration-200"
                :class="zooming ? 'opacity-0' : 'opacity-100'"
            >
        </template>

        {{-- Zoom lens overlay (background-position tracks mouse) --}}
        <div
            x-show="zooming && hasZoom"
            class="absolute inset-0 transition-opacity duration-150"
            :style="img?.zoom ? `
                background-image: url('` + img.zoom + `');
                background-size: 200%;
                background-repeat: no-repeat;
                background-position: ` + zoomX + ` ` + zoomY + `;
            ` : ''"
        ></div>

        {{-- Zoom hint badge --}}
        <div
            x-show="hasZoom && !zooming"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="absolute bottom-3 right-3 flex items-center gap-1.5 px-2.5 py-1 bg-black/50 backdrop-blur-sm text-white text-[11px] font-semibold rounded-full pointer-events-none"
        >
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
            @label('product.gallery_zoom_hint', 'Hover to zoom · Click to enlarge')
        </div>

        {{-- Click-to-enlarge hint when no zoom image --}}
        <div
            x-show="img && !hasZoom"
            class="absolute bottom-3 right-3 flex items-center gap-1.5 px-2.5 py-1 bg-black/40 backdrop-blur-sm text-white text-[11px] font-semibold rounded-full pointer-events-none opacity-0 group-hover:opacity-100 transition"
        ></div>
    </div>

    {{-- ── Thumbnail strip ───────────────────────────────── --}}
    {{-- Hidden when the product has only one image total across all variants --}}
    <template x-if="images.length > 1">
        <div class="flex flex-wrap items-center justify-center gap-3 mt-4 py-1 px-0.5">
            <template x-for="(timg, idx) in images" :key="idx">
                <button
                    @click="selectThumb(idx)"
                    :class="current === idx
                        ? 'border-indigo-600 ring-2 ring-indigo-600/20 shadow-md'
                        : 'border-slate-200 hover:border-indigo-300'"
                    class="w-16 h-16 rounded-xl overflow-hidden border-2 transition-all duration-150 p-0.5 bg-white shadow-sm flex-shrink-0 focus:outline-none"
                >
                    <img :src="timg.thumb" class="w-full h-full object-cover rounded-lg" :alt="timg.alt">
                </button>
            </template>
        </div>
    </template>

    {{-- ── Lightbox modal ─────────────────────────────────────── --}}
    <template x-teleport="body">
        <div
            x-show="lightbox"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-900/85 backdrop-blur-md"
            @click.self="lightbox = false"
            style="display:none"
        >
            {{-- Close button --}}
            <button
                @click="lightbox = false"
                class="absolute top-5 right-5 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition duration-150"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Prev / Next arrows --}}
            <template x-if="images.length > 1">
                <button
                    @click.stop="current = (current - 1 + images.length) % images.length"
                    class="absolute left-4 p-3 rounded-full bg-white/10 hover:bg-white/25 text-white transition duration-150"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
            </template>
            <template x-if="images.length > 1">
                <button
                    @click.stop="current = (current + 1) % images.length"
                    class="absolute right-4 p-3 rounded-full bg-white/10 hover:bg-white/25 text-white transition duration-150"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </template>

            {{-- Full-res image --}}
            <template x-if="img">
                <div class="max-w-5xl max-h-[85vh] flex flex-col items-center gap-4">
                    <img
                        :src="img?.zoom ?? img?.main"
                        :alt="img?.zoom_label ?? img?.alt"
                        class="max-h-[72vh] max-w-full object-contain rounded-2xl shadow-2xl"
                        @click.stop>
                    {{-- Zoom label (if set) --}}
                    <div x-show="img?.zoom_label" class="text-white text-sm font-semibold tracking-wide bg-white/15 backdrop-blur-md px-4 py-2 rounded-full border border-white/10 shadow-sm" x-text="img?.zoom_label"></div>

                    {{-- Lightbox thumbnail strip --}}
                    <template x-if="images.length > 1">
                        <div class="flex items-center gap-2">
                            <template x-for="(ti, tidx) in images" :key="tidx">
                                <button
                                    @click.stop="current = tidx"
                                    :class="current === tidx ? 'ring-2 ring-white opacity-100' : 'opacity-50 hover:opacity-80'"
                                    class="w-12 h-12 rounded-lg overflow-hidden transition-all duration-150 flex-shrink-0"
                                >
                                    <img :src="ti.thumb" :alt="ti.alt" class="w-full h-full object-cover">
                                </button>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </template>
</div>
