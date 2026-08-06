<?php

namespace App\Livewire;

use App\Enums\UserRole;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsBuilderBlock;
use App\Models\CmsListMenu;
use App\Models\CmsPage;
use App\Models\Plugin;
use App\Models\Product;
use App\Services\HeaderFooterCssManager;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminHeaderFooterBuilder extends Component
{
    use WithFileUploads;

    public string $activeTab = 'header'; // 'header', 'footer', 'css_manager', 'full_preview'
    public string $deviceView = 'desktop'; // 'desktop', 'tablet', 'mobile'
    public bool $showLivePreview = true;
    public int $previewNonce = 1;

    // File upload properties
    public $headerBgFile = null;
    public $footerBgFile = null;

    // Drawer Search Properties
    public string $searchProduct = '';
    public string $searchBrand = '';
    public string $categorySearch = '';
    public string $searchCategory = '';
    public string $searchPage = '';
    public string $searchWidget = '';
    public string $searchPlugin = '';
    public string $searchShortcode = '';
    public string $shortcodeSearchQuery = '';
    public string $shortcodeSearchScope = 'all';

    // Block editing state
    public ?int $editingBlockId = null;
    public string $editTitle = '';
    public string $editTargetElement = '';
    public string $editContentDesktop = '';
    public string $editContentTablet = '';
    public string $editContentMobile = '';
    public bool $editIsActiveDesktop = true;
    public bool $editIsActiveTablet = true;
    public bool $editIsActiveMobile = true;

    // Block creation state
    public bool $isCreating = false;
    public string $newTitle = '';
    public string $newTargetElement = '';
    public string $newSectionType = 'footer'; // 'header' or 'footer'
    public int $newType = 5; // 1=header container, 2=header elem, 3=top col, 4=footer row, 5=footer col
    public string $newContentDesktop = '';
    public string $newContentTablet = '';
    public string $newContentMobile = '';
    public bool $newIsActiveDesktop = true;
    public bool $newIsActiveTablet = true;
    public bool $newIsActiveMobile = true;

    // CSS Manager & Layout Config state
    public array $cssVars = [];
    public bool $singleHeaderConfig = false;
    public bool $topNavSticky = true;

    // Shortcode assistant data
    public array $listMenus = [];
    public array $categories = [];
    public array $brands = [];
    public array $plugins = [];

    public function mount(): void
    {
        // Strictly restrict to role_id = 3 (Admin)
        abort_unless(auth()->check() && auth()->user()->role_id === UserRole::Admin, 403, 'Unauthorized access.');

        $this->ensureDefaultBlocksExist();
        $this->loadCssVars();
        $this->loadShortcodeHelpers();
    }

    private function ensureDefaultBlocksExist(): void
    {
        if (\Illuminate\Support\Facades\Schema::hasTable('cms_builder_blocks')) {
            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'site_header_container', 'section_type' => 'header'],
                [
                    'title'             => 'Main Site Header Bar',
                    'type'              => 1,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 1,
                    'sort_tablet'       => 1,
                    'sort_mobile'       => 1,
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'header_top_bar', 'section_type' => 'header'],
                [
                    'title'             => 'Header Top Bar Columns Row',
                    'type'              => 1,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 0,
                    'sort_tablet'       => 0,
                    'sort_mobile'       => 0,
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'header_col1', 'section_type' => 'header'],
                [
                    'title'             => 'Header Column #1 (Left Slot)',
                    'type'              => 2,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 1,
                    'sort_tablet'       => 1,
                    'sort_mobile'       => 1,
                    'content_desktop'   => '',
                    'content_tablet'    => '',
                    'content_mobile'    => '',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'header_col2', 'section_type' => 'header'],
                [
                    'title'             => 'Header Column #2 (Right Slot)',
                    'type'              => 2,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 2,
                    'sort_tablet'       => 2,
                    'sort_mobile'       => 2,
                    'content_desktop'   => '',
                    'content_tablet'    => '',
                    'content_mobile'    => '',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'header_features', 'section_type' => 'header'],
                [
                    'title'             => 'Cart & Account Features',
                    'type'              => 2,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 8,
                    'sort_tablet'       => 8,
                    'sort_mobile'       => 8,
                    'content_desktop'   => '{{Cart & User Account Icons}}',
                    'content_tablet'    => '{{Cart & User Account Icons}}',
                    'content_mobile'    => '{{Cart & User Account Icons}}',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'header_search', 'section_type' => 'header'],
                [
                    'title'             => 'Header & Mobile Search Bar',
                    'type'              => 2,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 99,
                    'sort_tablet'       => 99,
                    'sort_mobile'       => 99,
                    'content_desktop'   => '[plugin:live-search-2026]',
                    'content_tablet'    => '[plugin:live-search-2026]',
                    'content_mobile'    => '[plugin:live-search-2026]',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'copyright_container', 'section_type' => 'footer'],
                [
                    'title'             => 'Copyright & Bottom Links Bar',
                    'type'              => 4,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 99,
                    'sort_tablet'       => 99,
                    'sort_mobile'       => 99,
                    'content_desktop'   => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                    'content_tablet'    => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                    'content_mobile'    => '<div class="w-full text-center text-xs">© ' . date('Y') . ' [site_name]. All rights reserved.</div>',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );

            CmsBuilderBlock::firstOrCreate(
                ['target_element' => 'footer_row4', 'section_type' => 'footer'],
                [
                    'title'             => 'Footer Row #4 (Copyright Bar)',
                    'type'              => 4,
                    'is_placeholder'    => false,
                    'sort_desktop'      => 99,
                    'sort_tablet'       => 99,
                    'sort_mobile'       => 99,
                    'content_desktop'   => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                    'content_tablet'    => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                    'content_mobile'    => '<div class="w-full text-center text-xs">© ' . date('Y') . ' [site_name]. All rights reserved.</div>',
                    'is_active_desktop' => true,
                    'is_active_tablet'  => true,
                    'is_active_mobile'  => true,
                ]
            );
        }
    }

    public function enableCopyrightBlock(): void
    {
        $block = CmsBuilderBlock::whereIn('target_element', ['copyright_container', 'footer_row4'])->first();
        if (!$block) {
            $block = CmsBuilderBlock::create([
                'title'             => 'Copyright & Bottom Links Bar',
                'target_element'    => 'copyright_container',
                'type'              => 4,
                'section_type'      => 'footer',
                'is_placeholder'    => false,
                'sort_desktop'      => 99,
                'sort_tablet'       => 99,
                'sort_mobile'       => 99,
                'content_desktop'   => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                'content_tablet'    => '<div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4 text-xs"><div>© ' . date('Y') . ' [site_name]. All rights reserved.</div><div class="flex items-center gap-4"><a href="/privacy-policy" class="hover:underline">Privacy Policy</a><span class="opacity-40">•</span><a href="/terms" class="hover:underline">Terms of Service</a></div></div>',
                'content_mobile'    => '<div class="w-full text-center text-xs">© ' . date('Y') . ' [site_name]. All rights reserved.</div>',
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ]);
        } else {
            $block->update([
                'is_active_desktop' => true,
                'is_active_tablet'  => true,
                'is_active_mobile'  => true,
            ]);
        }

        $this->editBlock($block->id);
    }

    public function toggleLivePreview(): void
    {
        $this->showLivePreview = !$this->showLivePreview;
    }

    public function refreshLivePreview(): void
    {
        $this->previewNonce++;
    }

    public function openCreateModal(string $section = 'footer'): void
    {
        $this->isCreating           = true;
        $this->newSectionType       = $section;
        $this->newType              = ($section === 'header') ? 2 : 5;
        $this->newTitle             = '';
        $this->newTargetElement     = ($section === 'header') ? 'header_col_custom' : 'footer_col_custom';
        $this->newContentDesktop    = '';
        $this->newContentTablet     = '';
        $this->newContentMobile     = '';
        $this->newIsActiveDesktop  = true;
        $this->newIsActiveTablet   = true;
        $this->newIsActiveMobile   = true;
    }

    public function cancelCreating(): void
    {
        $this->isCreating = false;
    }

    public function saveNewBlock(): void
    {
        $this->validate([
            'newTitle'         => 'required|string|max:255',
            'newTargetElement' => 'nullable|string|max:255',
            'newSectionType'   => 'required|in:header,footer',
        ]);

        $maxSortDesktop = CmsBuilderBlock::where('section_type', $this->newSectionType)->max('sort_desktop') ?? 0;
        $maxSortTablet  = CmsBuilderBlock::where('section_type', $this->newSectionType)->max('sort_tablet') ?? 0;
        $maxSortMobile  = CmsBuilderBlock::where('section_type', $this->newSectionType)->max('sort_mobile') ?? 0;

        CmsBuilderBlock::create([
            'title'             => trim($this->newTitle),
            'target_element'    => trim($this->newTargetElement) ?: null,
            'type'              => $this->newType,
            'section_type'      => $this->newSectionType,
            'is_placeholder'    => false,
            'sort_desktop'      => $maxSortDesktop + 1,
            'sort_tablet'       => $maxSortTablet + 1,
            'sort_mobile'       => $maxSortMobile + 1,
            'content_desktop'   => $this->newContentDesktop,
            'content_tablet'    => $this->newContentTablet,
            'content_mobile'    => $this->newContentMobile,
            'is_active_desktop' => $this->newIsActiveDesktop,
            'is_active_tablet'  => $this->newIsActiveTablet,
            'is_active_mobile'  => $this->newIsActiveMobile,
        ]);

        $this->isCreating = false;
        session()->flash('message', 'New ' . ucfirst($this->newSectionType) . ' block created successfully.');
    }

    public function deleteBlock(int $id): void
    {
        $block = CmsBuilderBlock::findOrFail($id);
        $title = $block->title;
        $block->delete();

        if ($this->editingBlockId === $id) {
            $this->editingBlockId = null;
        }

        session()->flash('message', "Block '{$title}' has been deleted.");
    }

    public function seedDefaultBlocks(): void
    {
        (new \Database\Seeders\CmsBuilderBlockSeeder)->run();
        session()->flash('message', 'Default header & footer layout blocks seeded successfully.');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->editingBlockId = null;
    }

    public function setDeviceView(string $device): void
    {
        $this->deviceView = $device;
    }

    public function editBlock(int $id): void
    {
        $block = CmsBuilderBlock::findOrFail($id);
        $this->editingBlockId       = $block->id;
        $this->editTitle            = $block->title;
        $this->editTargetElement    = $block->target_element ?? '';
        $this->editContentDesktop   = $block->content_desktop ?? '';
        $this->editContentTablet    = $block->content_tablet ?? '';
        $this->editContentMobile    = $block->content_mobile ?? '';
        $this->editIsActiveDesktop = (bool) $block->is_active_desktop;
        $this->editIsActiveTablet  = (bool) $block->is_active_tablet;
        $this->editIsActiveMobile  = (bool) $block->is_active_mobile;
    }

    public function cancelEditing(): void
    {
        $this->editingBlockId = null;
    }

    public function saveBlock(): void
    {
        if (!$this->editingBlockId) {
            return;
        }

        $block = CmsBuilderBlock::findOrFail($this->editingBlockId);

        $contentDesktop = $block->target_element === 'header_logo' ? null : $this->editContentDesktop;
        $contentTablet  = $block->target_element === 'header_logo' ? null : $this->editContentTablet;
        $contentMobile  = $block->target_element === 'header_logo' ? null : $this->editContentMobile;

        $block->update([
            'title'             => trim($this->editTitle),
            'target_element'    => trim($this->editTargetElement) ?: null,
            'content_desktop'   => $contentDesktop,
            'content_tablet'    => $contentTablet,
            'content_mobile'    => $contentMobile,
            'is_active_desktop' => $this->editIsActiveDesktop,
            'is_active_tablet'  => $this->editIsActiveTablet,
            'is_active_mobile'  => $this->editIsActiveMobile,
        ]);

        $this->editingBlockId = null;
        $this->refreshLivePreview();
        session()->flash('message', 'Layout block saved successfully.');
    }

    public function translateBlock(int $id): void
    {
        $block = CmsBuilderBlock::findOrFail($id);

        $languages = \App\Models\Language::where('is_active', true)
            ->where('is_default', false)
            ->get();

        if ($languages->isEmpty()) {
            $this->dispatch('toast', message: 'No active non-default languages found to translate into.', type: 'warning');
            return;
        }

        foreach ($languages as $language) {
            \App\Jobs\TranslateContentJob::dispatch(\App\Models\CmsBuilderBlock::class, $block->id, $language->id);
        }

        $this->dispatch('toast',
            message: $languages->count() . ' translation job(s) queued for "' . e($block->title) . '".',
            type: 'success',
            duration: 6000
        );
    }

    public function toggleActive(int $id): void
    {
        $block = CmsBuilderBlock::find($id);
        if (! $block) {
            return;
        }

        $column = match ($this->deviceView) {
            'tablet' => 'is_active_tablet',
            'mobile' => 'is_active_mobile',
            default  => 'is_active_desktop',
        };

        $block->$column = ! $block->$column;
        $block->save();

        HeaderFooterCssManager::clearCompiledCssCache();
        $this->previewNonce++;
        $this->dispatch('preview-updated');
    }

    public function reorderHeaderRows(int $fromIndex, int $toIndex): void
    {
        $device = in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop';
        $sortCol = match ($device) {
            'tablet' => 'sort_tablet',
            'mobile' => 'sort_mobile',
            default  => 'sort_desktop',
        };

        $rows = CmsBuilderBlock::header()
            ->where('type', 1)
            ->activeForDevice($device)
            ->sortForDevice($device)
            ->get();

        if ($fromIndex < 0 || $fromIndex >= $rows->count() || $toIndex < 0 || $toIndex >= $rows->count()) {
            return;
        }

        $rowsArray = $rows->all();
        $movedItem = array_splice($rowsArray, $fromIndex, 1)[0];
        array_splice($rowsArray, $toIndex, 0, [$movedItem]);

        foreach ($rowsArray as $index => $block) {
            CmsBuilderBlock::where('id', $block->id)->update([
                $sortCol => ($index + 1) * 10,
            ]);
        }

        HeaderFooterCssManager::clearCompiledCssCache();
        $this->previewNonce++;
        $this->dispatch('preview-updated');
    }

    public function moveHeaderRowUp(int $blockId): void
    {
        $device = in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop';
        $rows = CmsBuilderBlock::header()
            ->where('type', 1)
            ->activeForDevice($device)
            ->sortForDevice($device)
            ->get();

        $index = $rows->search(fn($item) => $item->id === $blockId);
        if ($index !== false && $index > 0) {
            $this->reorderHeaderRows($index, $index - 1);
        }
    }

    public function moveHeaderRowDown(int $blockId): void
    {
        $device = in_array($this->deviceView, ['desktop', 'tablet', 'mobile']) ? $this->deviceView : 'desktop';
        $rows = CmsBuilderBlock::header()
            ->where('type', 1)
            ->activeForDevice($device)
            ->sortForDevice($device)
            ->get();

        $index = $rows->search(fn($item) => $item->id === $blockId);
        if ($index !== false && $index < $rows->count() - 1) {
            $this->reorderHeaderRows($index, $index + 1);
        }
    }

    public function openAddModal(string $section = 'footer'): void
    {
        $this->openCreateModal($section);
    }

    public function closeAddModal(): void
    {
        $this->cancelCreating();
    }

    public function createBlock(): void
    {
        $this->saveNewBlock();
    }

    public function cancelEdit(): void
    {
        $this->cancelEditing();
    }

    public function setSectionTab(string $tab): void
    {
        $this->setTab($tab);
    }

    public function toggleEmbedNavigation(): void
    {
        $current = !empty($this->cssVars['nav_inside_main_header']);
        $newValue = $current ? '0' : '1';
        $this->cssVars['nav_inside_main_header'] = $newValue;
        $this->cssVars['nav_placement'] = ($newValue === '1') ? 'main_header' : 'standalone';
        HeaderFooterCssManager::saveVariables($this->cssVars);
        \App\Models\CmsSetting::set('nav_inside_main_header', $newValue);
        \App\Models\CmsSetting::set('nav_placement', $this->cssVars['nav_placement']);
        HeaderFooterCssManager::clearCache();
        $this->refreshLivePreview();
        session()->flash('message', 'Header navigation placement updated successfully.');
    }

    public function setNavPlacement(string $placement): void
    {
        $allowed = ['standalone', 'main_header', 'header_col1', 'header_col2'];
        if (!in_array($placement, $allowed, true)) {
            $placement = 'standalone';
        }

        $this->cssVars['nav_placement'] = $placement;
        $this->cssVars['nav_inside_main_header'] = ($placement !== 'standalone') ? '1' : '0';

        HeaderFooterCssManager::saveVariables($this->cssVars);
        \App\Models\CmsSetting::set('nav_placement', $placement);
        \App\Models\CmsSetting::set('nav_inside_main_header', $this->cssVars['nav_inside_main_header']);
        HeaderFooterCssManager::clearCache();

        $this->refreshLivePreview();
        session()->flash('message', 'Navigation bar placement updated successfully.');
    }

    public function setFeaturesPlacement(string $placement): void
    {
        $allowed = ['main_header', 'header_col1', 'header_col2'];
        if (!in_array($placement, $allowed, true)) {
            $placement = 'main_header';
        }

        $this->cssVars['features_placement'] = $placement;

        HeaderFooterCssManager::saveVariables($this->cssVars);
        \App\Models\CmsSetting::set('features_placement', $placement);
        HeaderFooterCssManager::clearCache();

        $this->refreshLivePreview();
        session()->flash('message', 'Cart features placement updated successfully.');
    }

    public function reorderBlocksArray(array $orderedIds): void
    {
        $sortCol = match ($this->deviceView) {
            'tablet' => 'sort_tablet',
            'mobile' => 'sort_mobile',
            default  => 'sort_desktop',
        };

        foreach ($orderedIds as $index => $id) {
            CmsBuilderBlock::where('id', $id)->update([$sortCol => $index + 1]);
        }

        session()->flash('message', 'Layout order updated via drag-and-drop.');
    }

    public function moveBlockUp(int $id): void
    {
        $this->reorderBlock($id, 'up');
    }

    public function moveBlockDown(int $id): void
    {
        $this->reorderBlock($id, 'down');
    }

    private function reorderBlock(int $id, string $direction): void
    {
        $block = CmsBuilderBlock::findOrFail($id);
        $section = $block->section_type;
        $sortCol = match ($this->deviceView) {
            'tablet' => 'sort_tablet',
            'mobile' => 'sort_mobile',
            default  => 'sort_desktop',
        };

        $blocks = CmsBuilderBlock::where('section_type', $section)
            ->orderBy($sortCol, 'asc')
            ->get();

        $index = $blocks->search(fn($b) => $b->id === $block->id);
        if ($index === false) {
            return;
        }

        $swapIndex = ($direction === 'up') ? $index - 1 : $index + 1;
        if ($swapIndex < 0 || $swapIndex >= $blocks->count()) {
            return;
        }

        $targetBlock = $blocks[$swapIndex];

        $tempSort = $block->$sortCol;
        $block->update([$sortCol => $targetBlock->$sortCol]);
        $targetBlock->update([$sortCol => $tempSort]);

        session()->flash('message', 'Block order updated for ' . ucfirst($this->deviceView) . ' view.');
    }

    public function insertShortcodeToBlock(string $shortcode): void
    {
        match ($this->deviceView) {
            'tablet' => $this->editContentTablet .= ' ' . $shortcode,
            'mobile' => $this->editContentMobile .= ' ' . $shortcode,
            default  => $this->editContentDesktop .= ' ' . $shortcode,
        };
    }

    public function loadCssVars(): void
    {
        $this->cssVars = HeaderFooterCssManager::getActiveVariables();
        $this->singleHeaderConfig = (\App\Models\CmsSetting::get('single_header_config', '0') === '1');
        $this->topNavSticky = in_array(\App\Models\CmsSetting::get('top_nav_sticky', '1'), ['1', 1, true, 'true'], true);
    }

    public function toggleSingleHeaderConfig(): void
    {
        $this->singleHeaderConfig = !$this->singleHeaderConfig;
        \App\Models\CmsSetting::set('single_header_config', $this->singleHeaderConfig ? '1' : '0');
        HeaderFooterCssManager::clearCache();
        $this->refreshLivePreview();
        session()->flash('message', $this->singleHeaderConfig 
            ? 'Single Responsive Header mode enabled. Single layout applies responsively with top navigation on Desktop (≥ 1024px) and hamburger drawer menu on Tablet & Mobile (≤ 1023px).' 
            : 'Multi-Device Header Layout Mode enabled. Customize Desktop, Tablet, and Mobile layouts independently.');
    }

    public function saveCssVars(): void
    {
        if ($this->headerBgFile) {
            $this->validate(['headerBgFile' => 'image|max:4096']);
            $path = $this->headerBgFile->store('uploads/cms_builder', 'public');
            $this->cssVars['header_bg_image_url'] = asset('storage/' . $path);
            $this->headerBgFile = null;
        }

        if ($this->footerBgFile) {
            $this->validate(['footerBgFile' => 'image|max:4096']);
            $path = $this->footerBgFile->store('uploads/cms_builder', 'public');
            $this->cssVars['footer_bg_image_url'] = asset('storage/' . $path);
            $this->footerBgFile = null;
        }

        \App\Models\CmsSetting::set('top_nav_sticky', $this->topNavSticky ? '1' : '0');
        $this->cssVars['top_nav_sticky'] = $this->topNavSticky ? '1' : '0';

        HeaderFooterCssManager::saveVariables($this->cssVars);
        $this->refreshLivePreview();
        session()->flash('message', 'CSS & Theme Manager settings saved successfully.');
    }

    public function resetCssVars(): void
    {
        $this->cssVars = HeaderFooterCssManager::getDefaultVariables();
        HeaderFooterCssManager::saveVariables($this->cssVars);
        session()->flash('message', 'CSS settings reset to default values.');
    }

    private function loadShortcodeHelpers(): void
    {
        try {
            $this->listMenus  = CmsListMenu::select('id', 'name')->get()->toArray();
            $this->categories = Category::select('id', 'name')->get()->toArray();
            $this->brands     = Brand::select('id', 'name')->get()->toArray();
            $this->plugins    = Plugin::active()->select('id', 'name', 'shortcode')->get()->toArray();
        } catch (\Throwable) {
            // Tables might be empty in initial setup
        }
    }

    public function render()
    {
        $sortCol = match ($this->deviceView) {
            'tablet' => 'sort_tablet',
            'mobile' => 'sort_mobile',
            default  => 'sort_desktop',
        };

        $headerBlocks = CmsBuilderBlock::header()->orderBy($sortCol, 'asc')->get();
        $footerBlocks = CmsBuilderBlock::footer()->orderBy($sortCol, 'asc')->get();
        $displayPlugins = Plugin::active()->get();

        $searchedProducts = [];
        if (strlen($this->searchProduct) >= 2) {
            $searchedProducts = Product::where('title', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('sku', 'like', '%' . $this->searchProduct . '%')
                ->limit(25)->get();
        }

        $searchedBrands = [];
        if (strlen($this->searchBrand) >= 2) {
            $searchedBrands = Brand::where('name', 'like', '%' . $this->searchBrand . '%')
                ->limit(25)->get();
        }

        $searchedCategories = [];
        if (strlen($this->categorySearch) >= 2) {
            $searchedCategories = Category::where('name', 'like', '%' . $this->categorySearch . '%')
                ->limit(25)->get();
        }

        $searchedPages = [];
        if (strlen($this->searchPage) >= 2) {
            $searchedPages = CmsPage::where('title', 'like', '%' . $this->searchPage . '%')
                ->orWhere('slug', 'like', '%' . $this->searchPage . '%')
                ->limit(25)->get();
        }

        $shortcodeSearchResults = [];
        if (!empty($this->shortcodeSearchQuery)) {
            $q = '%' . $this->shortcodeSearchQuery . '%';
            $pagesLimit      = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $productsLimit   = ($this->shortcodeSearchScope === 'all') ? 10 : 25;
            $categoriesLimit = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $brandsLimit     = ($this->shortcodeSearchScope === 'all') ? 5 : 25;

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'pages') {
                foreach (CmsPage::where('title', 'like', $q)->limit($pagesLimit)->get() as $p) {
                    $shortcodeSearchResults[] = [
                        'type' => 'Page',
                        'id' => $p->id,
                        'title' => $p->title,
                        'badgeColor' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        'shortcode' => '[page:' . $p->id . ' label="' . e($p->title) . '"]'
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'products') {
                foreach (Product::where('title', 'like', $q)->limit($productsLimit)->get() as $p) {
                    $shortcodeSearchResults[] = [
                        'type' => 'Product',
                        'id' => $p->id,
                        'title' => $p->title,
                        'badgeColor' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'shortcode' => '[product:' . $p->id . ' label="' . e($p->title) . '"]'
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'categories') {
                foreach (Category::where('name', 'like', $q)->limit($categoriesLimit)->get() as $c) {
                    $shortcodeSearchResults[] = [
                        'type' => 'Category',
                        'id' => $c->id,
                        'title' => $c->name,
                        'badgeColor' => 'bg-amber-100 text-amber-800 border-amber-200',
                        'shortcode' => '[category:' . $c->id . ' label="' . e($c->name) . '"]'
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'brands') {
                foreach (Brand::where('name', 'like', $q)->limit($brandsLimit)->get() as $b) {
                    $shortcodeSearchResults[] = [
                        'type' => 'Brand',
                        'id' => $b->id,
                        'title' => $b->name,
                        'badgeColor' => 'bg-violet-100 text-violet-800 border-violet-200',
                        'shortcode' => '[brand:' . $b->id . ' label="' . e($b->name) . '"]'
                    ];
                }
            }
        }

        $activeNavMenu = null;
        $activeNavItems = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('nav_menus')) {
            $activeNavMenu = \App\Models\NavMenu::getPrimary();
            if ($activeNavMenu) {
                $flat = $activeNavMenu->items()->where('is_active', true)->get();
                $activeNavItems = \App\Models\NavItem::buildTree($flat);
            }
        }

        $navPlacement = \App\Models\CmsSetting::get('nav_placement');
        if (empty($navPlacement)) {
            $navInside = !empty($this->cssVars['nav_inside_main_header']) || !empty(\App\Models\CmsSetting::get('nav_inside_main_header'));
            $navPlacement = $navInside ? 'main_header' : 'standalone';
        }
        $featuresPlacement = \App\Models\CmsSetting::get('features_placement', 'main_header');

        return view('livewire.admin-header-footer-builder', [
            'headerBlocks'           => $headerBlocks,
            'footerBlocks'           => $footerBlocks,
            'displayPlugins'         => $displayPlugins,
            'searchedProducts'       => $searchedProducts,
            'searchedBrands'         => $searchedBrands,
            'searchedCategories'     => $searchedCategories,
            'searchedPages'          => $searchedPages,
            'shortcodeSearchResults' => $shortcodeSearchResults,
            'activeNavMenu'          => $activeNavMenu,
            'activeNavItems'         => $activeNavItems,
            'navPlacement'           => $navPlacement,
            'featuresPlacement'      => $featuresPlacement,
        ])->layout('layouts.app');
    }
}
