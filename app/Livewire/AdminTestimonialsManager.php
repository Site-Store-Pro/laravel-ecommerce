<?php

namespace App\Livewire;

use App\Models\CmsTestimonial;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminTestimonialsManager extends Component
{
    use WithPagination, WithFileUploads;

    // Search and filter
    public string $search = '';
    public string $statusFilter = 'all'; // 'all', 'active', 'inactive'

    // Form fields
    public ?int $testimonialId = null;
    public string $author_name = '';
    public string $author_title = '';
    public string $content = '';
    public string $avatar_image = '';
    public $avatar_file = null;
    public int $rating = 5;
    public string $company_name = '';
    public string $company_link = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    // Modal state
    public bool $isCreating = false;
    public bool $isEditing = false;

    // Translation state
    public int $tlLangId = 0;
    public array $tlBuffer = [];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403, 'Unauthorized admin access.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    private function resetForm(): void
    {
        $this->testimonialId = null;
        $this->author_name = '';
        $this->author_title = '';
        $this->content = '';
        $this->avatar_image = '';
        $this->avatar_file = null;
        $this->rating = 5;
        $this->company_name = '';
        $this->company_link = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function editTestimonial(int $id): void
    {
        $this->resetForm();
        $item = CmsTestimonial::findOrFail($id);

        $this->testimonialId = $item->id;
        $this->author_name   = $item->author_name;
        $this->author_title  = $item->author_title ?? '';
        $this->content       = $item->content;
        $this->avatar_image  = $item->avatar_image ?? '';
        $this->rating        = $item->rating;
        $this->company_name  = $item->company_name ?? '';
        $this->company_link  = $item->company_link ?? '';
        $this->is_active     = (bool) $item->is_active;
        $this->sort_order    = $item->sort_order;

        $this->isEditing = true;
        $this->loadTlFor($id);
    }

    public function selectTlLang(int $id): void
    {
        $this->tlLangId = $id;
        $this->tlBuffer = [];
        if ($this->testimonialId) {
            $this->loadTlFor($this->testimonialId);
        }
    }

    public function loadTlFor(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        $existing = \App\Models\CmsTestimonialTranslation::where('testimonial_id', $modelId)
            ->where('language_id', $this->tlLangId)
            ->first();
        $this->tlBuffer = $existing ? $existing->only(['author_name', 'author_title', 'content', 'company_name']) : [];
    }

    public function saveTlTestimonial(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        \App\Models\CmsTestimonialTranslation::updateOrCreate(
            ['testimonial_id' => $modelId, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, ['translation_status' => 'reviewed', 'translated_at' => now()])
        );
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTlTestimonial(int $modelId): void
    {
        if ($this->tlLangId === 0) { return; }
        $record = CmsTestimonial::findOrFail($modelId);
        $lang = Language::findOrFail($this->tlLangId);
        try {
            $svc = app(\App\Services\TranslationService::class);
            foreach (['author_name', 'author_title', 'content', 'company_name'] as $field) {
                if (!empty($record->$field)) {
                    $this->tlBuffer[$field] = $svc->translateText($record->$field, $lang->name, 'Testimonial context');
                }
            }
            $this->dispatch('toast', message: 'AI translation ready — review and save.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function saveTestimonial(): void
    {
        $this->validate([
            'author_name'  => 'required|string|max:255',
            'author_title' => 'nullable|string|max:255',
            'content'      => 'required|string',
            'avatar_image' => 'nullable|string|max:2048',
            'avatar_file'  => 'nullable|image|max:4096',
            'rating'       => 'required|integer|min:1|max:5',
            'company_name' => 'nullable|string|max:255',
            'company_link' => 'nullable|url|max:2048',
            'is_active'    => 'required|boolean',
            'sort_order'   => 'required|integer',
        ]);

        $avatarPath = $this->avatar_image;
        if ($this->avatar_file) {
            $path = $this->avatar_file->store('uploads/testimonials', 'public');
            $avatarPath = asset('storage/' . $path);
        }

        $data = [
            'author_name'  => $this->author_name,
            'author_title' => $this->author_title ?: null,
            'content'      => $this->content,
            'avatar_image' => $avatarPath ?: null,
            'rating'       => $this->rating,
            'company_name' => $this->company_name ?: null,
            'company_link' => $this->company_link ?: null,
            'is_active'    => $this->is_active,
            'sort_order'   => $this->sort_order,
        ];

        if ($this->isEditing && $this->testimonialId) {
            CmsTestimonial::where('id', $this->testimonialId)->update($data);
            session()->flash('status', 'Testimonial updated successfully.');
        } else {
            CmsTestimonial::create($data);
            session()->flash('status', 'Testimonial created successfully.');
        }

        $this->resetForm();
    }

    public function toggleActive(int $id): void
    {
        $item = CmsTestimonial::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);
        session()->flash('status', 'Testimonial status toggled.');
    }

    public function deleteTestimonial(int $id): void
    {
        $item = CmsTestimonial::findOrFail($id);
        $item->delete();
        session()->flash('status', 'Testimonial deleted successfully.');
        $this->resetForm();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        $query = CmsTestimonial::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('author_name', 'like', '%' . $this->search . '%')
                  ->orWhere('author_title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%')
                  ->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $testimonials = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(15);

        return view('livewire.admin-testimonials-manager', [
            'testimonials' => $testimonials,
            'activeLanguages' => Language::active()->where('is_default', false)->get(),
        ]);
    }
}
