<?php

namespace App\Livewire;

use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class AdminEcommerceBrands extends Component
{
    use WithFileUploads;
    use \Livewire\WithPagination;

    // Brand Form State
    public ?int $brandId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public int $sort_order = 0;
    public string $brand_icon = '';
    public string $brand_url = '';

    // File Upload properties
    public $logoFile;
    public int $brand_logo_s3 = 0;

    // Search / Filter
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // Selected brand for showing assigned products
    public ?int $selectedBrandIdForProducts = null;
    public string $selectedBrandName = '';
    public array $brandProducts = [];

    // Modes
    public bool $isEditing = false;
    public bool $isCreating = false;

    public function mount(): void
    {
        // Brand management is for admins only
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403, 'Unauthorized admin access.');
    }

    public function showProducts(int $brandId, string $brandName): void
    {
        $this->resetForm();
        $this->selectedBrandIdForProducts = $brandId;
        $this->selectedBrandName = $brandName;

        $this->brandProducts = \App\Models\Product::where('brand_id', $brandId)
            ->orderBy('title')
            ->get(['id', 'title'])
            ->toArray();
    }

    public function closeProductsList(): void
    {
        $this->selectedBrandIdForProducts = null;
        $this->selectedBrandName = '';
        $this->brandProducts = [];
    }

    public function updatedName(string $value): void
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($value);
        }
    }

    private function resetForm(): void
    {
        $this->brandId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->brand_icon = '';
        $this->brand_url = '';
        $this->logoFile = null;
        $this->brand_logo_s3 = 0;
        $this->isEditing = false;
        $this->isCreating = false;
        
        $this->selectedBrandIdForProducts = null;
        $this->selectedBrandName = '';
        $this->brandProducts = [];
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function editBrand(int $id): void
    {
        $this->resetForm();
        $brand = Brand::findOrFail($id);
        
        $this->brandId = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->description = $brand->description ?? '';
        $this->sort_order = (int) $brand->sort_order;
        $this->brand_icon = $brand->brand_icon ?? '';
        $this->brand_url = $brand->brand_url ?? '';
        $this->brand_logo_s3 = (int) $brand->brand_logo_s3;
        
        $this->isEditing = true;
    }

    public function saveBrand(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_brands,slug,' . ($this->brandId ?? 'NULL') . ',id',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
            'brand_url' => 'nullable|url|max:255',
            'logoFile' => 'nullable|image|max:2048',
            'brand_logo_s3' => 'required|integer',
        ];

        $this->validate($rules);

        $brand_icon_path = $this->brand_icon ?: null;
        if ($this->logoFile) {
            $disk = $this->brand_logo_s3 == 1 ? 's3' : 'public';
            
            // Delete old file if exists
            if ($this->brand_icon && Storage::disk($disk)->exists($this->brand_icon)) {
                Storage::disk($disk)->delete($this->brand_icon);
            }
            
            $brand_icon_path = $this->logoFile->store('brands/logos', $disk);
        }

        if ($this->isEditing && $this->brandId) {
            $brand = Brand::findOrFail($this->brandId);
            $brand->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
                'brand_icon' => $brand_icon_path,
                'brand_url' => $this->brand_url ?: null,
                'brand_logo_s3' => $this->brand_logo_s3,
            ]);
            session()->flash('status', 'Brand updated successfully.');
        } else {
            Brand::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'sort_order' => $this->sort_order,
                'brand_icon' => $brand_icon_path,
                'brand_url' => $this->brand_url ?: null,
                'brand_logo_s3' => $this->brand_logo_s3,
            ]);
            session()->flash('status', 'Brand created successfully.');
        }

        $this->resetForm();
    }

    public function deleteBrand(int $id): void
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        
        session()->flash('status', 'Brand deleted successfully.');
        $this->resetForm();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function render(): View
    {
        $brands = Brand::query()
            ->withCount('products')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(25);

        return view('livewire.admin-ecommerce-brands', [
            'brands' => $brands,
        ]);
    }
}
