<?php

namespace App\Plugins\Display;

use App\Models\Plugin;
use App\Models\ProductVariantEvent;
use App\Plugins\Contracts\DisplayPlugin;
use Illuminate\Support\Facades\Log;

class EventsCalendarPlugin implements DisplayPlugin
{
    public function slug(): string
    {
        return 'events-calendar-2026';
    }

    public function name(): string
    {
        return 'Events Calendar Display (2026)';
    }

    public function render(array $params, Plugin $plugin): string
    {
        try {
            $settings = $plugin->getSettings();

            $header    = $params['header']     ?? $settings['header_title']   ?? 'Upcoming Events Calendar';
            $layout    = strtolower($params['layout']     ?? $settings['default_layout'] ?? 'month');
            $max       = max(1, (int) ($params['max']      ?? $settings['max_events']     ?? 50));
            $defaultCss = $plugin->getSetting('default_css', '');
            $customCss = $params['custom_css'] ?? $settings['custom_css']    ?? '';

            // Query product variant events
            $query = ProductVariantEvent::with([
                'variant.product.brand',
                'variant.product.categories',
                'variant.images',
                'variant.product.variants.images',
            ])
            ->whereHas('variant', function ($q) {
                $q->whereHas('product');
            })
            ->whereNotNull('event_start_date')
            ->orderBy('event_start_date', 'asc');

            if (!empty($params['category'])) {
                $cat = $params['category'];
                $query->whereHas('variant.product.categories', function($cq) use ($cat) {
                    $cq->where('slug', $cat)->orWhere('product_categories.id', $cat);
                });
            }

            $eventRecords = $query->limit($max)->get();

            if ($eventRecords->isEmpty()) {
                return '<!-- [plugin:events-calendar-2026] No event products found -->';
            }

            $eventsPayload = [];
            $currencySymbol = \App\Services\CurrencyService::symbol();

            foreach ($eventRecords as $evt) {
                $variant = $evt->variant;
                $product = $variant ? $variant->product : null;

                if (!$product) continue;

                $startDate = $evt->event_start_date;
                $endDate   = $evt->event_end_date;

                // Price formatting
                $priceVal = $variant->public_price ?? $variant->sale_price ?? 0;
                $priceStr = $priceVal > 0 ? $currencySymbol . number_format($priceVal, 2) : 'Free / TBA';

                // Thumbnail image resolution
                $imgObj = ($variant->images && $variant->images->isNotEmpty()) 
                    ? $variant->images->first() 
                    : ($product->variants ? $product->variants->flatMap->images->first() : null);
                
                $imgUrl = $imgObj ? ($imgObj->thumbnail_url ?: ($imgObj->image_url ?: '')) : '';
                if (!$imgUrl) {
                    $imgUrl = 'https://via.placeholder.com/300x200?text=' . urlencode($product->title);
                }

                $eventsPayload[] = [
                    'id'               => $evt->id,
                    'variant_id'       => $variant->id,
                    'product_id'       => $product->id,
                    'title'            => $product->title,
                    'url'              => route('shop.product', $product->seo_slug),
                    'image'            => $imgUrl,
                    'price'            => $priceStr,
                    'start_date_iso'   => $startDate ? $startDate->toIso8601String() : null,
                    'start_date_ymd'   => $startDate ? $startDate->format('Y-m-d') : '',
                    'start_time_fmt'   => $startDate ? $startDate->format('g:i A') : '',
                    'end_date_iso'     => $endDate ? $endDate->toIso8601String() : null,
                    'end_date_ymd'     => $endDate ? $endDate->format('Y-m-d') : null,
                    'end_time_fmt'     => $endDate ? $endDate->format('g:i A') : null,
                    'date_range_fmt'   => $startDate ? ($startDate->format('M j, Y') . ($endDate ? ' - ' . $endDate->format('M j, Y') : '')) : '',
                    'event_label'      => $evt->event_label ?: ($evt->alternate_label ?: 'Event Ticket'),
                    'label_background' => $evt->label_background ?: '#4f46e5',
                    'event_location'   => $evt->event_location ?: 'Location details provided upon booking',
                    'description'      => $evt->event_description ?: strip_tags($product->short_description ?? ''),
                    'show_date'        => (bool) $evt->show_date,
                ];
            }

            if (empty($eventsPayload)) {
                return '<!-- [plugin:events-calendar-2026] No active product events to display -->';
            }

            $eventsJson = json_encode($eventsPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            $instanceId = 'cal_' . substr(md5(microtime() . rand()), 0, 8);

            return view('plugins.display.events-calendar', [
                'header'      => $header,
                'layout'      => $layout,
                'eventsJson'  => $eventsJson,
                'instanceId'  => $instanceId,
                'defaultCss'  => $defaultCss,
                'customCss'   => $customCss,
                'eventsCount' => count($eventsPayload),
            ])->render();

        } catch (\Throwable $e) {
            Log::error("EventsCalendarPlugin error: " . $e->getMessage());
            return '<!-- [plugin:events-calendar-2026] Render Error: ' . e($e->getMessage()) . ' -->';
        }
    }
}
