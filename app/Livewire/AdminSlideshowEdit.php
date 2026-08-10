<?php

namespace App\Livewire;

use App\Models\CmsSlide;
use App\Models\CmsSlideshow;
use App\Models\CmsSlideTranslation;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminSlideshowEdit extends Component
{
    use WithFileUploads;

    public int $slideshowId;
    public CmsSlideshow $slideshow;

    // ── Slide form ──────────────────────────────────────────────────────────
    public ?int $slideId = null;
    public string $Title = '';
    public string $Description = '';
    public string $SlideURL = '';
    public string $slide_heading = '';
    public string $slide_sub_heading = '';
    public string $slide_callout_button_label = '';
    public string $slide_content_css = '';
    public string $slide_heading_css = '';
    public string $slide_alignment = 'middle-center';
    public int $Active = 1;
    public float $ImageSort = 0;

    // Storage config (per slide)
    public int $image_s3 = 0;          // 0=local, 1=env S3, 2=custom S3
    public string $image_s3_region = '';
    public string $image_s3_bucket = '';
    public string $image_s3_key = '';
    public string $image_s3_secret = '';
    public string $cdn_url = '';

    // CDN dimension overrides
    public int $cdn_image_width = 1920;
    public int $cdn_image_height = 725;
    public int $cdn_mobile_image_width = 600;
    public int $cdn_mobile_image_height = 500;

    // External URL overrides (take priority over uploads)
    public string $cdn_image = '';
    public string $cdn_mobile_image = '';
    public string $cdn_thumbnail = '';

    // Existing stored image paths (from DB)
    public ?string $existing_large_image = null;
    public ?string $existing_thumbnail = null;
    public ?string $existing_mobile_image = null;

    // File upload bindings
    public $largeImageFile = null;
    public $thumbnailFile = null;
    public $mobileImageFile = null;

    // UI state
    public bool $isEditing = false;
    public bool $isCreating = false;
    public ?int $confirmDeleteId = null;

    // ── Translation state ───────────────────────────────────────────────────
    public ?string $activeLangCode             = null;
    public ?int    $activeLangId               = null;
    public string  $trans_slide_heading        = '';
    public string  $trans_slide_sub_heading    = '';
    public string  $trans_button_label         = '';
    public string  $trans_status               = 'pending';
    public ?string $trans_translated_at        = null;

    public function mount(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
        $this->slideshowId = $id;
        $this->slideshow = CmsSlideshow::findOrFail($id);
    }

    // ── Form helpers ─────────────────────────────────────────────────────────

    private function resetForm(): void
    {
        $this->slideId = null;
        $this->Title = '';
        $this->Description = '';
        $this->SlideURL = '';
        $this->slide_heading = '';
        $this->slide_sub_heading = '';
        $this->slide_callout_button_label = '';
        $this->slide_content_css = '';
        $this->slide_heading_css = '';
        $this->slide_alignment = 'middle-center';
        $this->Active = 1;
        $this->ImageSort = 0;

        $this->image_s3 = 0;
        $this->image_s3_region = '';
        $this->image_s3_bucket = '';
        $this->image_s3_key = '';
        $this->image_s3_secret = '';
        $this->cdn_url = '';

        $this->cdn_image_width = 1920;
        $this->cdn_image_height = 725;
        $this->cdn_mobile_image_width = 600;
        $this->cdn_mobile_image_height = 500;

        $this->cdn_image = '';
        $this->cdn_mobile_image = '';
        $this->cdn_thumbnail = '';

        $this->existing_large_image = null;
        $this->existing_thumbnail = null;
        $this->existing_mobile_image = null;

        $this->largeImageFile = null;
        $this->thumbnailFile = null;
        $this->mobileImageFile = null;

        $this->isEditing = false;
        $this->isCreating = false;
        $this->resetErrorBag();

        $this->activeLangCode         = null;
        $this->activeLangId           = null;
        $this->trans_slide_heading    = '';
        $this->trans_slide_sub_heading = '';
        $this->trans_button_label     = '';
        $this->trans_status           = 'pending';
        $this->trans_translated_at    = null;
    }

    public function startCreate(): void
    {
        $this->resetForm();
        // Default sort to end of list
        $maxSort = CmsSlide::where('slideshow_id', $this->slideshowId)->max('ImageSort') ?? 0;
        $this->ImageSort = $maxSort + 1;
        $this->isCreating = true;
    }

    public function editSlide(int $id): void
    {
        $this->resetForm();
        $slide = CmsSlide::findOrFail($id);

        $this->slideId = $slide->id;
        $this->Title = $slide->Title ?? '';
        $this->Description = $slide->Description ?? '';
        $this->SlideURL = $slide->SlideURL ?? '';
        $this->slide_heading = $slide->slide_heading ?? '';
        $this->slide_sub_heading = $slide->slide_sub_heading ?? '';
        $this->slide_callout_button_label = $slide->slide_callout_button_label ?? '';
        $this->slide_content_css = $slide->slide_content_css ?? '';
        $this->slide_heading_css = $slide->slide_heading_css ?? '';
        $this->slide_alignment = $slide->slide_alignment ?? 'middle-center';
        $this->Active = (int) ($slide->Active ?? 1);
        $this->ImageSort = (float) ($slide->ImageSort ?? 0);

        $this->image_s3 = (int) ($slide->image_s3 ?? 0);
        $this->image_s3_region = $slide->image_s3_region ?? '';
        $this->image_s3_bucket = $slide->image_s3_bucket ?? '';
        $this->image_s3_key = $slide->image_s3_key ?? '';
        $this->image_s3_secret = $slide->image_s3_secret ?? '';
        $this->cdn_url = $slide->cdn_url ?? '';

        $this->cdn_image_width = (int) ($slide->cdn_image_width ?? 1920);
        $this->cdn_image_height = (int) ($slide->cdn_image_height ?? 725);
        $this->cdn_mobile_image_width = (int) ($slide->cdn_mobile_image_width ?? 600);
        $this->cdn_mobile_image_height = (int) ($slide->cdn_mobile_image_height ?? 500);

        $this->cdn_image        = $slide->cdn_image        ?? '';
        $this->cdn_mobile_image = $slide->cdn_mobile_image ?? '';
        $this->cdn_thumbnail    = $slide->cdn_thumbnail    ?? '';

        $this->existing_large_image = $slide->LargeImage;
        $this->existing_thumbnail   = $slide->Thumbnail;
        $this->existing_mobile_image = $slide->mobile_image;

        $this->isEditing = true;

        // Auto-select the first active non-default language for the translation panel
        $activeLangs = \App\Models\Language::where('is_active', true)->where('is_default', false)->get();
        if ($activeLangs->isNotEmpty()) {
            $first = $activeLangs->first();
            $this->selectTranslationLang($first->code, $first->id);
        }
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    // ── Storage helpers ───────────────────────────────────────────────────────

    private function resolveDisk(int $s3Setting, ?int $slideId = null): string
    {
        if ($s3Setting === 1) {
            return 's3';
        }

        if ($s3Setting === 2) {
            $diskName = 'cms_slide_s3_' . ($slideId ?? 'new');
            config([
                "filesystems.disks.{$diskName}" => [
                    'driver'                  => 's3',
                    'key'                     => $this->image_s3_key,
                    'secret'                  => $this->image_s3_secret,
                    'region'                  => $this->image_s3_region,
                    'bucket'                  => $this->image_s3_bucket,
                    'use_path_style_endpoint' => false,
                ],
            ]);
            return $diskName;
        }

        return 'public';
    }

    private function uploadFile($file, string $folder, string $diskName): string
    {
        return $file->store("slideshows/{$this->slideshowId}/{$folder}", $diskName);
    }

    private function deleteFile(?string $path, string $diskName): void
    {
        if ($path && Storage::disk($diskName)->exists($path)) {
            Storage::disk($diskName)->delete($path);
        }
    }

    // ── Save ─────────────────────────────────────────────────────────────────

    public function saveSlide(): void
    {
        $this->validate([
            'Title'                       => 'nullable|string|max:255',
            'Description'                 => 'nullable|string',
            'SlideURL'                    => 'nullable|string|max:2048',
            'slide_heading'               => 'nullable|string|max:500',
            'slide_sub_heading'           => 'nullable|string|max:1000',
            'slide_callout_button_label'  => 'nullable|string|max:255',
            'slide_content_css'           => 'nullable|string',
            'slide_heading_css'          => 'nullable|string',
            'slide_alignment'            => 'required|string',
            'Active'                     => 'required|integer|in:0,1',
            'ImageSort'                  => 'required|numeric',
            'image_s3'                   => 'required|integer|in:0,1,2',
            'image_s3_region'            => 'nullable|string|max:255',
            'image_s3_bucket'            => 'nullable|string|max:255',
            'image_s3_key'               => 'nullable|string|max:255',
            'image_s3_secret'            => 'nullable|string|max:500',
            'cdn_url'                    => 'nullable|string|max:500',
            'cdn_image_width'            => 'nullable|integer',
            'cdn_image_height'           => 'nullable|integer',
            'cdn_mobile_image_width'     => 'nullable|integer',
            'cdn_mobile_image_height'    => 'nullable|integer',
            'cdn_image'                  => 'nullable|url|max:2048',
            'cdn_mobile_image'           => 'nullable|url|max:2048',
            'cdn_thumbnail'              => 'nullable|url|max:2048',
            'largeImageFile'             => 'nullable|image|max:10240',
            'thumbnailFile'              => 'nullable|image|max:5120',
            'mobileImageFile'            => 'nullable|image|max:5120',
        ]);

        // Mobile image requirement check (either upload file, CDN URL, or existing saved image)
        $hasMobileImage = !empty($this->cdn_mobile_image) || $this->mobileImageFile !== null || !empty($this->existing_mobile_image);
        if (!$hasMobileImage) {
            $this->addError('mobileImageFile', 'Mobile Image is required (upload a mobile image file or provide an external mobile image URL).');
            return;
        }

        $slideId = $this->isEditing ? $this->slideId : null;
        $diskName = $this->resolveDisk($this->image_s3, $slideId);

        // Handle existing slide update
        if ($this->isEditing && $this->slideId) {
            $slide = CmsSlide::findOrFail($this->slideId);

            $largePath  = $this->existing_large_image;
            $thumbPath  = $this->existing_thumbnail;
            $mobilePath = $this->existing_mobile_image;

            // If storage type changed, delete from old disk
            $oldDisk = $this->resolveDisk((int) ($slide->image_s3 ?? 0), $slide->id);

            // Only upload a file if no external URL override is set for that slot
            if ($this->largeImageFile && empty($this->cdn_image)) {
                $this->deleteFile($largePath, $oldDisk);
                $largePath = $this->uploadFile($this->largeImageFile, 'desktop', $diskName);
            }
            if ($this->thumbnailFile && empty($this->cdn_thumbnail)) {
                $this->deleteFile($thumbPath, $oldDisk);
                $thumbPath = $this->uploadFile($this->thumbnailFile, 'thumbnail', $diskName);
            }
            if ($this->mobileImageFile && empty($this->cdn_mobile_image)) {
                $this->deleteFile($mobilePath, $oldDisk);
                $mobilePath = $this->uploadFile($this->mobileImageFile, 'mobile', $diskName);
            }

            $slide->update([
                'Title'                      => $this->Title ?: null,
                'Description'                => $this->Description ?: null,
                'SlideURL'                   => $this->SlideURL ?: null,
                'slide_heading'              => $this->slide_heading ?: null,
                'slide_sub_heading'          => $this->slide_sub_heading ?: null,
                'slide_callout_button_label' => $this->slide_callout_button_label ?: null,
                'slide_content_css'          => $this->slide_content_css ?: null,
                'slide_heading_css'          => $this->slide_heading_css ?: null,
                'slide_alignment'            => $this->slide_alignment ?: 'middle-center',
                'Active'                     => $this->Active,
                'ImageSort'                  => $this->ImageSort,
                'LargeImage'                 => $largePath,
                'Thumbnail'                  => $thumbPath,
                'mobile_image'               => $mobilePath,
                // External URL overrides
                'cdn_image'                  => $this->cdn_image ?: null,
                'cdn_mobile_image'           => $this->cdn_mobile_image ?: null,
                'cdn_thumbnail'              => $this->cdn_thumbnail ?: null,
                // Storage config
                'image_s3'                   => $this->image_s3,
                'image_s3_region'            => $this->image_s3_region ?: null,
                'image_s3_bucket'            => $this->image_s3_bucket ?: null,
                'image_s3_key'               => $this->image_s3_key ?: null,
                'image_s3_secret'            => $this->image_s3_secret ?: null,
                'cdn_url'                    => $this->cdn_url ?: null,
                'cdn_image_width'            => $this->cdn_image_width,
                'cdn_image_height'           => $this->cdn_image_height,
                'cdn_mobile_image_width'     => $this->cdn_mobile_image_width,
                'cdn_mobile_image_height'    => $this->cdn_mobile_image_height,
            ]);

            $this->dispatch('toast', message: 'Slide updated successfully.', type: 'success');
        } else {
            // New slide
            $slide = CmsSlide::create([
                'slideshow_id'               => $this->slideshowId,
                'Title'                      => $this->Title ?: null,
                'Description'                => $this->Description ?: null,
                'SlideURL'                   => $this->SlideURL ?: null,
                'slide_heading'              => $this->slide_heading ?: null,
                'slide_sub_heading'          => $this->slide_sub_heading ?: null,
                'slide_callout_button_label' => $this->slide_callout_button_label ?: null,
                'slide_content_css'          => $this->slide_content_css ?: null,
                'slide_heading_css'          => $this->slide_heading_css ?: null,
                'slide_alignment'            => $this->slide_alignment ?: 'middle-center',
                'Active'                     => $this->Active,
                'ImageSort'                  => $this->ImageSort,
                'LargeImage'                 => null,
                'Thumbnail'                  => null,
                'mobile_image'               => null,
                // External URL overrides
                'cdn_image'                  => $this->cdn_image ?: null,
                'cdn_mobile_image'           => $this->cdn_mobile_image ?: null,
                'cdn_thumbnail'              => $this->cdn_thumbnail ?: null,
                // Storage config
                'image_s3'                   => $this->image_s3,
                'image_s3_region'            => $this->image_s3_region ?: null,
                'image_s3_bucket'            => $this->image_s3_bucket ?: null,
                'image_s3_key'               => $this->image_s3_key ?: null,
                'image_s3_secret'            => $this->image_s3_secret ?: null,
                'cdn_url'                    => $this->cdn_url ?: null,
                'cdn_image_width'            => $this->cdn_image_width,
                'cdn_image_height'           => $this->cdn_image_height,
                'cdn_mobile_image_width'     => $this->cdn_mobile_image_width,
                'cdn_mobile_image_height'    => $this->cdn_mobile_image_height,
            ]);

            // Upload files (only if no external URL override is set for that slot)
            $diskName = $this->resolveDisk($this->image_s3, $slide->id);

            $largePath  = ($this->largeImageFile  && empty($this->cdn_image))        ? $this->uploadFile($this->largeImageFile,  'desktop',   $diskName) : null;
            $thumbPath  = ($this->thumbnailFile   && empty($this->cdn_thumbnail))     ? $this->uploadFile($this->thumbnailFile,   'thumbnail', $diskName) : null;
            $mobilePath = ($this->mobileImageFile && empty($this->cdn_mobile_image))  ? $this->uploadFile($this->mobileImageFile, 'mobile',    $diskName) : null;

            $slide->update([
                'LargeImage'   => $largePath,
                'Thumbnail'    => $thumbPath,
                'mobile_image' => $mobilePath,
            ]);

            $this->dispatch('toast', message: 'Slide created successfully.', type: 'success');
        }

        $this->resetForm();
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    public function deleteSlide(int $id): void
    {
        $slide = CmsSlide::findOrFail($id);
        $disk = $slide->getStorageDisk();

        foreach ([$slide->LargeImage, $slide->Thumbnail, $slide->mobile_image] as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }

        $slide->delete();
        $this->resetForm();
        $this->dispatch('toast', message: 'Slide deleted.', type: 'success');
    }

    // ── Toggle active ─────────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $slide = CmsSlide::findOrFail($id);
        $slide->Active = $slide->Active ? 0 : 1;
        $slide->save();
        $this->dispatch('toast', message: 'Slide status updated.', type: 'success');
    }

    // ── Drag-and-drop sort ────────────────────────────────────────────────────

    public function updateSlideOrder(array $order): void
    {
        // $order is an array of slide IDs in the new order, sent by Alpine/Sortable
        foreach ($order as $position => $slideId) {
            CmsSlide::where('id', $slideId)
                ->where('slideshow_id', $this->slideshowId)
                ->update(['ImageSort' => $position + 1]);
        }
        $this->dispatch('toast', message: 'Slide order saved.', type: 'success');
    }

    // ── Remove image individually ─────────────────────────────────────────────

    public function removeDesktopImage(): void
    {
        if (!$this->slideId) return;
        $slide = CmsSlide::findOrFail($this->slideId);
        $disk = $slide->getStorageDisk();
        if ($slide->LargeImage && $disk->exists($slide->LargeImage)) {
            $disk->delete($slide->LargeImage);
        }
        $slide->update(['LargeImage' => null]);
        $this->existing_large_image = null;
        $this->dispatch('toast', message: 'Desktop image removed.', type: 'success');
    }

    public function removeThumbnailImage(): void
    {
        if (!$this->slideId) return;
        $slide = CmsSlide::findOrFail($this->slideId);
        $disk = $slide->getStorageDisk();
        if ($slide->Thumbnail && $disk->exists($slide->Thumbnail)) {
            $disk->delete($slide->Thumbnail);
        }
        $slide->update(['Thumbnail' => null]);
        $this->existing_thumbnail = null;
        $this->dispatch('toast', message: 'Thumbnail removed.', type: 'success');
    }

    public function removeMobileImage(): void
    {
        if (!$this->slideId) return;
        $slide = CmsSlide::findOrFail($this->slideId);
        $disk = $slide->getStorageDisk();
        if ($slide->mobile_image && $disk->exists($slide->mobile_image)) {
            $disk->delete($slide->mobile_image);
        }
        $slide->update(['mobile_image' => null]);
        $this->existing_mobile_image = null;
        $this->dispatch('toast', message: 'Mobile image removed.', type: 'success');
    }

    // ── Translation ───────────────────────────────────────────────────────────

    public function selectTranslationLang(string $code, int $id): void
    {
        $this->activeLangCode = $code;
        $this->activeLangId   = $id;

        if (!$this->slideId) return;

        $t = CmsSlideTranslation::where('cms_slide_id', $this->slideId)
            ->where('language_id', $id)
            ->first();

        if ($t) {
            $this->trans_slide_heading    = $t->slide_heading ?? '';
            $this->trans_slide_sub_heading = $t->slide_sub_heading ?? '';
            $this->trans_button_label     = $t->slide_callout_button_label ?? '';
            $this->trans_status           = $t->translation_status ?? 'pending';
            $this->trans_translated_at    = $t->translated_at ? $t->translated_at->format('M j, Y g:i A') : null;
        } else {
            $this->trans_slide_heading    = '';
            $this->trans_slide_sub_heading = '';
            $this->trans_button_label     = '';
            $this->trans_status           = 'pending';
            $this->trans_translated_at    = null;
        }
    }

    public function saveTranslation(): void
    {
        if (!$this->slideId || !$this->activeLangId) return;

        CmsSlideTranslation::updateOrCreate(
            [
                'cms_slide_id' => $this->slideId,
                'language_id'  => $this->activeLangId,
            ],
            [
                'slide_heading'              => trim($this->trans_slide_heading),
                'slide_sub_heading'          => trim($this->trans_slide_sub_heading),
                'slide_callout_button_label' => trim($this->trans_button_label),
                'translation_status'         => 'reviewed',
                'translated_at'              => now(),
            ]
        );

        $this->trans_status       = 'reviewed';
        $this->trans_translated_at = now()->format('M j, Y g:i A');
        $this->dispatch('toast', message: 'Slide translation saved.', type: 'success');
    }

    public function aiTranslateSlideInline(): void
    {
        if (!$this->slideId || !$this->activeLangId) return;

        $slide = CmsSlide::find($this->slideId);
        $lang  = \App\Models\Language::find($this->activeLangId);

        if (!$slide || !$lang) return;

        try {
            $svc      = app(\App\Services\TranslationService::class);
            $langName = $lang->name;

            if (!empty($slide->slide_heading)) {
                $this->trans_slide_heading = $svc->translateText(
                    $slide->slide_heading,
                    $langName,
                    'slideshow slide heading / title text displayed over the slide image'
                );
            }
            if (!empty($slide->slide_sub_heading)) {
                $this->trans_slide_sub_heading = $svc->translateText(
                    $slide->slide_sub_heading,
                    $langName,
                    'slideshow slide sub-heading / description text'
                );
            }
            if (!empty($slide->slide_callout_button_label)) {
                $this->trans_button_label = $svc->translateText(
                    $slide->slide_callout_button_label,
                    $langName,
                    'slideshow slide call-to-action button label — keep it short and action-oriented'
                );
            }

            $this->trans_status = 'ai_translated';
            $this->dispatch('toast', message: 'AI translation generated — review and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function autoTranslateSlide(): void
    {
        if (!$this->slideId || !$this->activeLangId) return;

        \App\Jobs\TranslateContentJob::dispatch(
            CmsSlide::class,
            $this->slideId,
            $this->activeLangId
        );

        $this->dispatch('toast', message: 'Slide translation job queued for background processing.', type: 'success');
    }

    public function translateAllLanguages(): void
    {
        if (!$this->slideId) return;

        $languages = \App\Models\Language::where('is_active', true)->where('is_default', false)->get();
        if ($languages->isEmpty()) {
            $this->dispatch('toast', message: 'No active non-default languages found.', type: 'warning');
            return;
        }

        foreach ($languages as $lang) {
            \App\Jobs\TranslateContentJob::dispatch(CmsSlide::class, $this->slideId, $lang->id);
        }

        $this->dispatch('toast', message: $languages->count() . ' translation job(s) queued for this slide.', type: 'success');
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): View
    {
        $this->slideshow->refresh();
        $slides = CmsSlide::where('slideshow_id', $this->slideshowId)
            ->orderBy('ImageSort')
            ->orderBy('id')
            ->get();

        return view('livewire.admin-slideshow-edit', [
            'slides'           => $slides,
            'alignmentOptions' => CmsSlideshow::alignmentOptions(),
        ]);
    }
}
