<?php

namespace App\Livewire;

use App\Models\CmsDownload;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminCmsDownloadEdit extends Component
{
    use WithFileUploads;

    // Record identity
    public ?int    $downloadId   = null;
    public ?string $downloadUuid = null;

    // Basic info
    public string $internal_name = '';
    public string $link_label    = '';

    // Status & display
    public bool    $is_active       = true;
    public ?string $expires_at      = null;
    public bool    $force_download  = false;
    public bool    $open_in_new_tab = true;
    public int     $show_icon       = 0;  // 0=none, 1=left, 2=right, 3=top, 4=bottom
    public string  $custom_css      = '';

    // Source type: 0=local, 1=direct URL, 2=env S3, 3=custom S3
    public int $source_type = 0;

    // Local storage
    public string $file_path = '';
    public mixed  $file_upload = null;

    // Direct URL
    public string $cdn_url = '';

    // Env S3
    public string $s3_file_key          = '';
    public int    $s3_expiration_seconds = 600;

    // Custom S3
    public string $s3_custom_key                = '';
    public string $s3_custom_secret             = '';
    public string $s3_custom_region             = '';
    public string $s3_custom_bucket             = '';
    public string $s3_custom_file_key           = '';
    public int    $s3_custom_expiration_seconds = 600;

    // Poster image
    public string $poster_image_path    = '';
    public string $poster_image_cdn_url = '';
    public mixed  $poster_upload        = null;

    protected function rules(): array
    {
        return [
            'internal_name'              => 'required|string|max:255',
            'link_label'                 => 'nullable|string|max:255',
            'is_active'                  => 'boolean',
            'expires_at'                 => 'nullable|date',
            'force_download'             => 'boolean',
            'open_in_new_tab'            => 'boolean',
            'show_icon'                  => 'required|integer|in:0,1,2,3,4',
            'custom_css'                 => 'nullable|string',
            'source_type'                => 'required|integer|in:0,1,2,3',
            'file_upload'                => 'nullable|file|max:102400', // 100 MB
            'cdn_url'                    => 'nullable|url|max:500',
            's3_file_key'               => 'nullable|string|max:500',
            's3_expiration_seconds'      => 'nullable|integer|min:60|max:86400',
            's3_custom_key'              => 'nullable|string|max:255',
            's3_custom_secret'           => 'nullable|string|max:255',
            's3_custom_region'           => 'nullable|string|max:100',
            's3_custom_bucket'           => 'nullable|string|max:255',
            's3_custom_file_key'         => 'nullable|string|max:500',
            's3_custom_expiration_seconds' => 'nullable|integer|min:60|max:86400',
            'poster_upload'              => 'nullable|image|max:10240', // 10 MB
            'poster_image_cdn_url'       => 'nullable|url|max:500',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $download = CmsDownload::findOrFail($id);
            $this->downloadId             = $download->id;
            $this->downloadUuid           = $download->uuid;
            $this->internal_name          = $download->internal_name ?? '';
            $this->link_label             = $download->link_label ?? '';
            $this->is_active              = (bool) $download->is_active;
            $this->expires_at             = $download->expires_at?->format('Y-m-d\TH:i');
            $this->force_download         = (bool) $download->force_download;
            $this->open_in_new_tab        = (bool) $download->open_in_new_tab;
            $this->show_icon              = (int) $download->show_icon;
            $this->custom_css             = $download->custom_css ?? '';
            $this->source_type            = (int) $download->source_type;
            $this->file_path              = $download->file_path ?? '';
            $this->cdn_url                = $download->cdn_url ?? '';
            $this->s3_file_key            = $download->s3_file_key ?? '';
            $this->s3_expiration_seconds  = (int) ($download->s3_expiration_seconds ?: 600);
            $this->s3_custom_key          = $download->s3_custom_key ?? '';
            $this->s3_custom_secret       = $download->s3_custom_secret ?? '';
            $this->s3_custom_region       = $download->s3_custom_region ?? '';
            $this->s3_custom_bucket       = $download->s3_custom_bucket ?? '';
            $this->s3_custom_file_key     = $download->s3_custom_file_key ?? '';
            $this->s3_custom_expiration_seconds = (int) ($download->s3_custom_expiration_seconds ?: 600);
            $this->poster_image_path      = $download->poster_image_path ?? '';
            $this->poster_image_cdn_url   = $download->poster_image_cdn_url ?? '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'internal_name'              => $this->internal_name,
            'link_label'                 => $this->link_label ?: null,
            'is_active'                  => $this->is_active,
            'expires_at'                 => $this->expires_at ?: null,
            'force_download'             => $this->force_download,
            'open_in_new_tab'            => $this->open_in_new_tab,
            'show_icon'                  => $this->show_icon,
            'custom_css'                 => $this->custom_css ?: null,
            'source_type'                => $this->source_type,
            'cdn_url'                    => $this->cdn_url ?: null,
            's3_file_key'               => $this->s3_file_key ?: null,
            's3_expiration_seconds'      => $this->s3_expiration_seconds ?: 600,
            's3_custom_key'              => $this->s3_custom_key ?: null,
            's3_custom_secret'           => $this->s3_custom_secret ?: null,
            's3_custom_region'           => $this->s3_custom_region ?: null,
            's3_custom_bucket'           => $this->s3_custom_bucket ?: null,
            's3_custom_file_key'         => $this->s3_custom_file_key ?: null,
            's3_custom_expiration_seconds' => $this->s3_custom_expiration_seconds ?: 600,
            'poster_image_cdn_url'       => $this->poster_image_cdn_url ?: null,
        ];

        // Handle main file upload (local mode)
        if ($this->file_upload) {
            // Delete old file if replacing
            if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
                Storage::disk('public')->delete($this->file_path);
            }
            $path = $this->file_upload->store('cms_downloads', 'public');
            $data['file_path'] = $path;
            $this->file_path   = $path;
            $this->file_upload = null;
        } else {
            $data['file_path'] = $this->file_path ?: null;
        }

        // Handle poster image upload
        if ($this->poster_upload) {
            if ($this->poster_image_path && Storage::disk('public')->exists($this->poster_image_path)) {
                Storage::disk('public')->delete($this->poster_image_path);
            }
            $posterPath = $this->poster_upload->store('cms_downloads/posters', 'public');
            $data['poster_image_path'] = $posterPath;
            $this->poster_image_path   = $posterPath;
            $this->poster_upload       = null;
        } else {
            $data['poster_image_path'] = $this->poster_image_path ?: null;
        }

        if ($this->downloadId) {
            $download = CmsDownload::findOrFail($this->downloadId);
            $download->update($data);
            session()->flash('status', 'Download updated successfully.');
            $this->dispatch('cms-download-saved', message: 'Download updated successfully.');
        } else {
            $download = CmsDownload::create($data);
            $this->downloadId   = $download->id;
            $this->downloadUuid = $download->uuid;
            session()->flash('status', 'Download created successfully.');
            $this->dispatch('cms-download-saved', message: 'Download created successfully.');
        }
    }

    public function deleteFile(): void
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            Storage::disk('public')->delete($this->file_path);
        }
        if ($this->downloadId) {
            CmsDownload::findOrFail($this->downloadId)->update(['file_path' => null]);
        }
        $this->file_path = '';
        session()->flash('status', 'File removed.');
        $this->dispatch('cms-download-saved', message: 'File removed.');
    }

    public function deletePosterImage(): void
    {
        if ($this->poster_image_path && Storage::disk('public')->exists($this->poster_image_path)) {
            Storage::disk('public')->delete($this->poster_image_path);
        }
        if ($this->downloadId) {
            CmsDownload::findOrFail($this->downloadId)->update(['poster_image_path' => null]);
        }
        $this->poster_image_path = '';
        session()->flash('status', 'Poster image removed.');
    }

    public function render(): View
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
        return view('livewire.admin-cms-download-edit');
    }
}
