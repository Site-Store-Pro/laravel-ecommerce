<?php

namespace App\Livewire;

use App\Models\CmsBuilderBlock;
use App\Services\HeaderFooterParserService;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class PublicFooter extends Component
{
    public string $deviceView = 'desktop'; // 'desktop', 'tablet', 'mobile'

    public function render()
    {
        $hasBlocksTable = Schema::hasTable('cms_builder_blocks');
        $device         = in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop';
        $footerBlocks   = $hasBlocksTable ? CmsBuilderBlock::footer()->activeForDevice($device)->sortForDevice($device)->get() : collect();

        $parsedBlocks = [];
        foreach ($footerBlocks as $block) {
            $parsedBlocks[$block->target_element ?? $block->id] = [
                'block'   => $block,
                'content' => HeaderFooterParserService::parse($block->getContentForDevice($device)),
            ];
        }

        return view('livewire.public-footer', [
            'footerBlocks' => $footerBlocks,
            'parsedBlocks' => $parsedBlocks,
            'device'       => $device,
            'deviceView'   => $device,
        ]);
    }
}
