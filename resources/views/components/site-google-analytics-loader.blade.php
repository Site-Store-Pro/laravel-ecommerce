@php
    $gaId = \App\Services\GoogleAnalyticsService::getMeasurementId();
@endphp
@if($gaId)
    <!-- Google Analytics (GA4) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');

        // Global Helper for GA4 E-commerce Events
        window.trackGaEvent = function(eventName, eventParams) {
            if (typeof gtag === 'function') {
                gtag('event', eventName, eventParams || {});
            }
        };

        // Listen for Window Dispatch Events
        window.addEventListener('ga-ecommerce-event', function(e) {
            var payload = e.detail || {};
            var evt = payload.event || (Array.isArray(payload) && payload[0] ? payload[0].event : null);
            var data = payload.data || payload.params || payload;
            if (evt) {
                window.trackGaEvent(evt, data);
            }
        });

        // Listen for Livewire Event Dispatch
        document.addEventListener('livewire:init', function() {
            if (window.Livewire) {
                Livewire.on('ga-ecommerce-event', function(data) {
                    if (Array.isArray(data) && data[0]) {
                        data = data[0];
                    }
                    if (data && data.event) {
                        window.trackGaEvent(data.event, data.data || data.params || data);
                    }
                });
            }
        });

        // Automatic Page View tracking on Livewire SPA navigation
        document.addEventListener('livewire:navigated', function() {
            if (typeof gtag === 'function') {
                gtag('event', 'page_view', {
                    page_title: document.title,
                    page_location: window.location.href,
                    page_path: window.location.pathname
                });
            }
        });
    </script>
@endif
