<?php

namespace App\Livewire;

use App\Models\CmsBuilderBlock;
use App\Services\HeaderFooterCacheService;
use App\Services\HeaderFooterParserService;
use App\Services\LanguageService;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class PublicFooter extends Component
{
    public string $deviceView = 'desktop'; // 'desktop', 'tablet', 'mobile'

    public function render()
    {
        $device = in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop';

        // Check if static pre-rendered cache is active
        if (HeaderFooterCacheService::isCacheActive()) {
            $langId = app(LanguageService::class)->currentId();
            $cachedHtml = HeaderFooterCacheService::getFooterHtml($device, $langId);
            if ($cachedHtml !== null) {
                return view('livewire.public-footer', [
                    'cachedHtml'   => $cachedHtml,
                    'footerBlocks' => collect(),
                    'parsedBlocks' => [],
                    'device'       => $device,
                    'deviceView'   => $device,
                ]);
            }
        }

        // Dynamic render fallback
        $hasBlocksTable = Schema::hasTable('cms_builder_blocks');
        $footerBlocks   = $hasBlocksTable ? CmsBuilderBlock::footer()->withCurrentTranslations()->activeForDevice($device)->sortForDevice($device)->get() : collect();

        $parsedBlocks = [];
        foreach ($footerBlocks as $block) {
            $parsedBlocks[$block->target_element ?? $block->id] = [
                'block'   => $block,
                'content' => HeaderFooterParserService::parse($block->getContentForDevice($device)),
            ];
        }

        return view('livewire.public-footer', [
            'cachedHtml'   => null,
            'footerBlocks' => $footerBlocks,
            'parsedBlocks' => $parsedBlocks,
            'device'       => $device,
            'deviceView'   => $device,
        ]);
    }
}

