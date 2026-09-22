<div x-data="{ 
        checkDevice() {
            let w = window.innerWidth;
            let dev = w < 768 ? 'mobile' : (w < 1024 ? 'tablet' : 'desktop');
            if ($wire.deviceView !== dev) {
                $wire.setDeviceView(dev);
            }
        },
        updateCartBadges(count) {
            count = parseInt(count) || 0;
            document.querySelectorAll('.header-cart-badge, .nav-cart-badge').forEach(el => {
                el.textContent = count;
                if (count > 0) {
                    el.classList.remove('hidden');
                    el.style.display = 'flex';
                } else {
                    el.classList.add('hidden');
                    el.style.display = 'none';
                }
            });
        }
     }" 
     x-init="checkDevice(); updateCartBadges({{ (int) ($cartCount ?? 0) }})" 
     @cart-updated.window="
        if ($event.detail && typeof $event.detail.count !== 'undefined') {
            updateCartBadges($event.detail.count);
        }
        $wire.updateCartCount().then(res => {
            updateCartBadges(res ?? $wire.cartCount);
        });
     "
     @resize.window.debounce.150ms="checkDevice()" 
     class="w-full {{ $isSticky ? 'sticky top-0 z-[999] shadow-md' : 'relative z-40' }}">
    @if(!empty($cachedHtml))
        {!! $cachedHtml !!}
    @elseif($useFallback)
        <livewire:public-navigation />
    @else
        @include('livewire.public-header-inner')
    @endif
</div>

