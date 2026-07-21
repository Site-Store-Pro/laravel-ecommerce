<?php

namespace App\Livewire;

use App\Models\CmsSlideshow;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminSlideshows extends Component
{
    // Form state
    public ?int $slideshowId = null;
    public string $slideshow_name = '';
    public int $slideshow_active = 1;
    public int $sort_order = 0;
    public string $slide_show_alignment = 'middle-center';

    // UI modes
    public bool $isEditing = false;
    public bool $isCreating = false;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    private function resetForm(): void
    {
        $this->slideshowId = null;
        $this->slideshow_name = '';
        $this->slideshow_active = 1;
        $this->sort_order = 0;
        $this->slide_show_alignment = 'middle-center';
        $this->isEditing = false;
        $this->isCreating = false;
        $this->resetErrorBag();
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function editSlideshow(int $id): void
    {
        $this->resetForm();
        $slideshow = CmsSlideshow::findOrFail($id);

        $this->slideshowId = $slideshow->slideshow_id;
        $this->slideshow_name = $slideshow->slideshow_name ?? '';
        $this->slideshow_active = (int) $slideshow->slideshow_active;
        $this->sort_order = (int) $slideshow->sort_order;
        $this->slide_show_alignment = $slideshow->slide_show_alignment ?? 'middle-center';

        $this->isEditing = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function saveSlideshow(): void
    {
        $this->validate([
            'slideshow_name'       => 'required|string|max:255',
            'slideshow_active'     => 'required|integer|in:0,1',
            'sort_order'           => 'required|integer|min:0',
            'slide_show_alignment' => 'required|string',
        ]);

        $data = [
            'slideshow_name'       => trim($this->slideshow_name),
            'slideshow_active'     => $this->slideshow_active,
            'sort_order'           => $this->sort_order,
            'slide_show_alignment' => $this->slide_show_alignment,
        ];

        if ($this->isEditing && $this->slideshowId) {
            CmsSlideshow::findOrFail($this->slideshowId)->update($data);
            $this->dispatch('toast', message: 'Slideshow updated successfully.', type: 'success');
        } else {
            CmsSlideshow::create($data);
            $this->dispatch('toast', message: 'Slideshow created successfully.', type: 'success');
        }

        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $slideshow = CmsSlideshow::findOrFail($id);
        $slideshow->slideshow_active = $slideshow->slideshow_active ? 0 : 1;
        $slideshow->save();

        $this->dispatch('toast', message: 'Slideshow status updated.', type: 'success');
    }

    public function deleteSlideshow(int $id): void
    {
        $slideshow = CmsSlideshow::findOrFail($id);
        // Also delete child slides and their files
        foreach ($slideshow->slides as $slide) {
            $this->deleteSlideFiles($slide);
            $slide->delete();
        }
        $slideshow->delete();

        $this->resetForm();
        $this->dispatch('toast', message: 'Slideshow and all its slides deleted.', type: 'success');
    }

    private function deleteSlideFiles(\App\Models\CmsSlide $slide): void
    {
        $disk = $slide->getStorageDisk();
        foreach ([$slide->LargeImage, $slide->Thumbnail, $slide->mobile_image] as $path) {
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public function render(): View
    {
        $slideshows = CmsSlideshow::withCount('slides')
            ->orderBy('sort_order')
            ->orderBy('slideshow_id')
            ->get();

        return view('livewire.admin-slideshows', [
            'slideshows'         => $slideshows,
            'alignmentOptions'   => CmsSlideshow::alignmentOptions(),
        ]);
    }
}
