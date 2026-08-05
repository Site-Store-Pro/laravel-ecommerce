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
    public bool $is_visible_in_menu = true;
    public string $brand_icon = '';
    public string $brand_url = '';

    // File Upload properties
    public $logoFile;
    public int $brand_logo_s3 = 0;   // 0=Local, 1=Global S3, 2=Custom S3
    public string $brand_logo_cdn_url = '';
    public string $brand_logo_region = '';
    public string $brand_logo_bucket_name = '';
    public string $brand_logo_access_key_id = '';
    public string $brand_logo_secret_access_key = '';
    public string $brand_icon_direct_url = '';  // Direct URL (highest priority)

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
        $this->is_visible_in_menu = true;
        $this->brand_icon = '';
        $this->brand_url = '';
        $this->logoFile = null;
        $this->brand_logo_s3 = 0;
        $this->brand_logo_cdn_url = '';
        $this->brand_logo_region = '';
        $this->brand_logo_bucket_name = '';
        $this->brand_logo_access_key_id = '';
        $this->brand_logo_secret_access_key = '';
        $this->brand_icon_direct_url = '';
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
        $this->is_visible_in_menu = (bool) $brand->is_visible_in_menu;
        $this->brand_icon = $brand->brand_icon ?? '';
        $this->brand_url = $brand->brand_url ?? '';
        $this->brand_logo_s3 = (int) $brand->brand_logo_s3;
        $this->brand_logo_cdn_url = $brand->brand_logo_cdn_url ?? '';
        $this->brand_logo_region = $brand->brand_logo_region ?? '';
        $this->brand_logo_bucket_name = $brand->brand_logo_bucket_name ?? '';
        $this->brand_logo_access_key_id = $brand->brand_logo_access_key_id ?? '';
        $this->brand_logo_secret_access_key = $brand->brand_logo_secret_access_key ?? '';
        $this->brand_icon_direct_url = $brand->brand_icon_direct_url ?? '';
        
        $this->isEditing = true;
    }

    public function saveBrand(): void
    {
        $rules = [
            'name'                       => 'required|string|max:255',
            'slug'                       => 'required|string|max:255|unique:product_brands,slug,' . ($this->brandId ?? 'NULL') . ',id',
            'description'                => 'nullable|string',
            'sort_order'                 => 'required|integer',
            'is_visible_in_menu'         => 'required|boolean',
            'brand_url'                  => 'nullable|url|max:255',
            'logoFile'                   => 'nullable|image|max:2048',
            'brand_logo_s3'              => 'required|integer',
            'brand_logo_cdn_url'         => 'nullable|url|max:500',
            'brand_icon_direct_url'      => 'nullable|url|max:1000',
            'brand_logo_region'          => 'nullable|string|max:100',
            'brand_logo_bucket_name'     => 'nullable|string|max:255',
            'brand_logo_access_key_id'   => 'nullable|string|max:255',
            'brand_logo_secret_access_key' => 'nullable|string|max:500',
        ];

        $this->validate($rules);

        // ── Resolve final icon path / URL ─────────────────────────────────────
        // Priority: direct URL > file upload > existing value
        $brand_icon_path = $this->brand_icon ?: null;

        if (!empty($this->brand_icon_direct_url)) {
            // Direct URL — store it as-is, no file upload needed
            $brand_icon_path = $this->brand_icon_direct_url;
        } elseif ($this->logoFile) {
            // File upload — pick disk
            if ($this->brand_logo_s3 == 2) {
                $diskName = 'custom_s3_brands_' . ($this->brandId ?: 'new');
                config([
                    "filesystems.disks.{$diskName}" => [
                        'driver' => 's3',
                        'key'    => $this->brand_logo_access_key_id,
                        'secret' => $this->brand_logo_secret_access_key,
                        'region' => $this->brand_logo_region,
                        'bucket' => $this->brand_logo_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            } else {
                $diskName = $this->brand_logo_s3 == 1 ? 's3' : 'public';
            }

            $stored_path = $this->logoFile->store('brands/logos', $diskName);

            // Apply CDN prefix if provided
            if (!empty($this->brand_logo_cdn_url)) {
                $brand_icon_path = rtrim($this->brand_logo_cdn_url, '/') . '/' . ltrim($stored_path, '/');
            } else {
                $brand_icon_path = $stored_path;
            }
        }

        $saveData = [
            'name'                         => $this->name,
            'slug'                         => $this->slug,
            'description'                  => $this->description ?: null,
            'sort_order'                   => $this->sort_order,
            'is_visible_in_menu'           => $this->is_visible_in_menu,
            'brand_icon'                   => $brand_icon_path,
            'brand_url'                    => $this->brand_url ?: null,
            'brand_logo_s3'                => $this->brand_logo_s3,
            'brand_logo_cdn_url'           => $this->brand_logo_cdn_url ?: null,
            'brand_logo_region'            => $this->brand_logo_region ?: null,
            'brand_logo_bucket_name'       => $this->brand_logo_bucket_name ?: null,
            'brand_logo_access_key_id'     => $this->brand_logo_access_key_id ?: null,
            'brand_logo_secret_access_key' => $this->brand_logo_secret_access_key ?: null,
            'brand_icon_direct_url'        => $this->brand_icon_direct_url ?: null,
        ];

        if ($this->isEditing && $this->brandId) {
            Brand::findOrFail($this->brandId)->update($saveData);
            session()->flash('status', 'Brand updated successfully.');
        } else {
            Brand::create($saveData);
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
