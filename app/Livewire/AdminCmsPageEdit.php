<?php

namespace App\Livewire;

use App\Models\CmsPage;
use App\Models\CmsPageRevision;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminCmsPageEdit extends Component
{
    use WithFileUploads;

    public ?int $pageId = null;
    public ?CmsPage $page = null;

    // Form attributes
    public string $title = '';
    public string $slug = '';
    public bool   $slugTouched = false; // true once the user has manually edited the slug field
    public string $content = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public ?string $expires_at = null;
    public bool $requires_code = false;
    public string $access_code = '';
    public ?int $required_product_id = null;
    public string $custom_css = '';
    public string $custom_js = '';
    public $header_image_upload = null;
    public $background_image_upload = null;
    public ?string $header_image_path = null;
    public ?string $background_image_path = null;
    public bool $is_active = true;
    public bool $exclude_from_search = false;
    public int $layout_type = 1;
    public string $left_col = '';
    public string $right_col = '';
    public string $custom_author = '';
    public bool $show_author = true;
    public bool $show_title = true;
    public bool $show_date = true;

    // Search Index Keywords & Locking
    public string $cms_search_index = '';
    public bool $cms_search_index_locked = false;

    // New Custom Settings
    public string $alternate_page_title = '';
    public string $page_title_alignment = 'middle-center';
    public string $page_title_css = '';
    public string $include_slideshow = '';
    public string $min_header_height = '320px';
    // AI Content Generator
    public string $aiPrompt = '';
    public string $aiResponse = '';

    // Live Search for Link Generator
    public string $searchProduct = '';
    public string $searchBrand = '';
    public string $searchCategory = '';
    public string $searchPage = '';

    // Live Search for Gating Product Selector
    public string $gatingProductSearch = '';

    // Live Search for Shortcode Generator
    public string $shortcodeSearchQuery = '';
    public string $shortcodeSearchScope = 'all';

    // Custom Fields
    public int $page_type = 1;
    public int $page_ranking = 0;
    public bool $hide_page_ranking = true;
    public float $custom_sorting = 0.0;

    // Translation Management
    public string $activeLangCode = '';
    public ?int $activeLangId = null;
    public string $trans_title = '';
    public string $trans_content = '';
    public string $trans_meta_title = '';
    public string $trans_meta_description = '';
    public string $trans_alternate_page_title = '';
    public string $trans_status = 'pending';
    public ?string $trans_translated_at = null;

    // Categories and Tags selection
    public ?int $selected_category_id = null;
    public array $selected_tag_ids = [];

    // Featured Image & custom S3 options
    public $featured_image_upload = null;
    public ?string $featured_image_path = null;
    public int $featured_image_s3 = 0;
    public ?string $featured_image_region = null;
    public ?string $featured_image_bucket_name = null;
    public ?string $featured_image_access_key_id = null;
    public ?string $featured_image_secret_access_key = null;
    public ?string $featured_image_cdn_url = null;

    // Alternate S3 Options for Header & Background Images
    public int $media_image_s3 = 0;
    public ?string $media_image_region = null;
    public ?string $media_image_bucket_name = null;
    public ?string $media_image_access_key_id = null;
    public ?string $media_image_secret_access_key = null;
    public ?string $media_image_cdn_url = null;

    // Per-Page Background Video Settings
    public $background_video_upload = null;
    public ?string $background_video_path = null;
    public ?string $background_video_url = null;
    public string $background_video_type = 'local';
    public int $background_video_s3 = 0;
    public ?string $background_video_region = null;
    public ?string $background_video_bucket_name = null;
    public ?string $background_video_access_key_id = null;
    public ?string $background_video_secret_access_key = null;
    public ?string $background_video_cdn_url = null;

    // Revisions lists
    public $revisionsList = [];
    public ?CmsPageRevision $previewingRevision = null;

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->isEcommerceAdmin(), 403);
        $hasColumn = \Illuminate\Support\Facades\Cache::rememberForever('db_has_col_cms_pages_background_video', function () {
            return \Illuminate\Support\Facades\Schema::hasColumn('cms_pages', 'background_video');
        });
        if (!$hasColumn) {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\Cache::forget('db_has_col_cms_pages_background_video');
        }

        if ($id) {
            $this->pageId = $id;
            $this->page = CmsPage::findOrFail($id);

            $this->title = $this->page->title;
            $this->slug = $this->page->slug;
            $this->content = $this->page->content;
            $this->meta_title = $this->page->meta_title ?? '';
            $this->meta_description = $this->page->meta_description ?? '';
            $this->expires_at = $this->page->expires_at ? $this->page->expires_at->format('Y-m-d\TH:i') : null;
            $this->requires_code = $this->page->requires_code;
            $this->access_code = $this->page->access_code ?? '';
            $this->required_product_id = $this->page->required_product_id;
            $this->custom_css = $this->page->custom_css ?? '';
            $this->custom_js = $this->page->custom_js ?? '';
            $this->header_image_path = $this->page->header_image;
            $this->background_image_path = $this->page->background_image;
            $this->is_active = $this->page->is_active;
            $this->exclude_from_search = (bool) ($this->page->exclude_from_search ?? false);
            $this->cms_search_index = $this->page->cms_search_index ?? '';
            $this->cms_search_index_locked = (bool) ($this->page->cms_search_index_locked ?? false);
            $this->layout_type = $this->page->layout_type ?? 1;
            $this->left_col = $this->page->left_col ?? '';
            $this->right_col = $this->page->right_col ?? '';
            $this->custom_author = $this->page->custom_author ?? '';
            $this->show_author = $this->page->show_author;
            $this->show_title = $this->page->show_title;
            $this->show_date = $this->page->show_date;
            $this->alternate_page_title = $this->page->alternate_page_title ?? '';
            $this->page_title_alignment = $this->page->page_title_alignment ?: 'middle-center';
            $this->page_title_css = $this->page->page_title_css ?? '';
            $this->include_slideshow = $this->page->include_slideshow ?? '';
            $this->min_header_height = $this->page->min_header_height ?: '320px';
            $this->page_type = $this->page->page_type ?? 1;
            $this->page_ranking = $this->page->page_ranking ?? 0;
            $this->hide_page_ranking = (bool)($this->page->hide_page_ranking ?? true);
            $this->custom_sorting = (float)($this->page->custom_sorting ?? 0.0);

            $this->selected_category_id = $this->page->categories()->first()?->id;
            $this->selected_tag_ids = $this->page->tags()->pluck('cms_pages_tags.id')->toArray();

            $this->featured_image_path = $this->page->featured_image;
            $this->featured_image_s3 = $this->page->featured_image_s3 ?? 0;
            $this->featured_image_region = $this->page->featured_image_region;
            $this->featured_image_bucket_name = $this->page->featured_image_bucket_name;
            $this->featured_image_access_key_id = $this->page->featured_image_access_key_id;
            $this->featured_image_secret_access_key = $this->page->featured_image_secret_access_key;
            $this->featured_image_cdn_url = $this->page->featured_image_cdn_url;

            $this->media_image_s3 = $this->page->media_image_s3 ?? 0;
            $this->media_image_region = $this->page->media_image_region;
            $this->media_image_bucket_name = $this->page->media_image_bucket_name;
            $this->media_image_access_key_id = $this->page->media_image_access_key_id;
            $this->media_image_secret_access_key = $this->page->media_image_secret_access_key;
            $this->media_image_cdn_url = $this->page->media_image_cdn_url;

            $this->background_video_path = $this->page->background_video;
            $this->background_video_url = $this->page->background_video_url;
            $this->background_video_type = $this->page->background_video_type ?: 'local';
            $this->background_video_s3 = (int) ($this->page->background_video_s3 ?? 0);
            $this->background_video_region = $this->page->background_video_region;
            $this->background_video_bucket_name = $this->page->background_video_bucket_name;
            $this->background_video_access_key_id = $this->page->background_video_access_key_id;
            $this->background_video_secret_access_key = $this->page->background_video_secret_access_key;
            $this->background_video_cdn_url = $this->page->background_video_cdn_url;

            $this->loadRevisions();
        } else {
            $this->is_active = true;
            $this->layout_type = 1;
            $this->show_author = true;
            $this->show_title = true;
            $this->show_date = true;
            $this->custom_author = '';
            $this->alternate_page_title = '';
            $this->page_title_alignment = 'middle-center';
            $this->page_title_css = '';
            $this->include_slideshow = '';
            $this->min_header_height = '320px';
            $this->page_type = 1;
            $this->page_ranking = 0;
            $this->hide_page_ranking = true;
            $this->custom_sorting = 0.0;
            $this->selected_category_id = null;
            $this->selected_tag_ids     = [];
            $this->slugTouched          = false;

            $this->featured_image_s3 = 0;
            $this->featured_image_path = null;
            $this->featured_image_region = null;
            $this->featured_image_bucket_name = null;
            $this->featured_image_access_key_id = null;
            $this->featured_image_secret_access_key = null;
            $this->featured_image_cdn_url = null;

            $this->media_image_s3 = 0;
            $this->media_image_region = null;
            $this->media_image_bucket_name = null;
            $this->media_image_access_key_id = null;
            $this->media_image_secret_access_key = null;
            $this->media_image_cdn_url = null;
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->pageId && !$this->slugTouched) {
            $this->slug = Str::slug($this->title);
        }
    }

    /** Mark the slug as manually edited so title changes no longer overwrite it. */
    public function updatedSlug(): void
    {
        if (!$this->pageId) {
            // Only flag as touched if the user cleared or typed something different from the auto-slug
            $autoSlug = Str::slug($this->title);
            $this->slugTouched = ($this->slug !== $autoSlug && $this->slug !== '');
        }
    }

    public function loadRevisions(): void
    {
        if ($this->pageId) {
            $this->revisionsList = CmsPageRevision::where('cms_page_id', $this->pageId)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'requires_code' => 'boolean',
            'access_code' => 'nullable|required_if:requires_code,true|string|max:255',
            'required_product_id' => 'nullable|exists:products,id',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'alternate_page_title' => 'nullable|string|max:255',
            'page_title_alignment' => 'nullable|string|max:255',
            'page_title_css' => 'nullable|string',
            'include_slideshow' => 'nullable|string|max:255',
            'min_header_height' => 'nullable|string|max:255',
            'header_image_upload' => 'nullable|image|max:2048',
            'background_image_upload' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
            'layout_type' => 'required|integer|exists:cms_layouts,id',
            'left_col' => 'nullable|string',
            'right_col' => 'nullable|string',
            'custom_author' => 'nullable|string|max:255',
            'show_author' => 'boolean',
            'show_title' => 'boolean',
            'show_date' => 'boolean',
            'page_type' => 'required|integer|in:1,2',
            'hide_page_ranking' => 'boolean',
            'custom_sorting' => 'required|numeric',
            'featured_image_upload' => 'nullable|image|max:2048',
            'featured_image_s3' => 'required|integer|in:0,1,2',
            'featured_image_region' => 'nullable|required_if:featured_image_s3,2|string|max:255',
            'featured_image_bucket_name' => 'nullable|required_if:featured_image_s3,2|string|max:255',
            'featured_image_access_key_id' => 'nullable|required_if:featured_image_s3,2|string|max:255',
            'featured_image_secret_access_key' => 'nullable|required_if:featured_image_s3,2|string|max:255',
            'featured_image_cdn_url' => 'nullable|url|max:255',
            'media_image_s3' => 'required|integer|in:0,1,2',
            'media_image_region' => 'nullable|required_if:media_image_s3,2|string|max:255',
            'media_image_bucket_name' => 'nullable|required_if:media_image_s3,2|string|max:255',
            'media_image_access_key_id' => 'nullable|required_if:media_image_s3,2|string|max:255',
            'media_image_secret_access_key' => 'nullable|required_if:media_image_s3,2|string|max:255',
            'media_image_cdn_url' => 'nullable|url|max:255',
            'background_video_upload' => 'nullable|file|mimes:mp4,webm,ogg,mov|max:51200',
            'background_video_url' => 'nullable|string|max:255',
            'background_video_type' => 'nullable|string|max:50',
            'background_video_s3' => 'required|integer|in:0,1,2',
            'background_video_region' => 'nullable|required_if:background_video_s3,2|string|max:255',
            'background_video_bucket_name' => 'nullable|required_if:background_video_s3,2|string|max:255',
            'background_video_access_key_id' => 'nullable|required_if:background_video_s3,2|string|max:255',
            'background_video_secret_access_key' => 'nullable|required_if:background_video_s3,2|string|max:255',
            'background_video_cdn_url' => 'nullable|string|max:255',
        ]);

        // Custom validation check for unique slug across CMS tables
        $isUnique = \App\Services\UniqueSlugCheck::isUnique($this->slug, 'page', $this->pageId);
        if (!$isUnique) {
            $this->addError('slug', 'This slug is already in use by a page, category, or tag.');
            return;
        }

        // Reserved route-prefix guard — these prefixes are owned by the application router
        $reservedPrefixes = ['kb/', 'shop/', 'items/', 'cart/', 'checkout/', 'login/', 'register/', 'admin/', 'section/'];
        $normalizedSlug   = ltrim($this->slug, '/');
        foreach ($reservedPrefixes as $prefix) {
            if (str_starts_with($normalizedSlug, $prefix) || $normalizedSlug === rtrim($prefix, '/')) {
                $this->addError('slug', "The slug may not start with '/{$prefix}' — that path is reserved by the system.");
                return;
            }
        }

        // Upload files
        if ($this->header_image_upload || $this->background_image_upload) {
            $mediaDisk = 'public';
            if ($this->media_image_s3 == 1) {
                $mediaDisk = 's3';
            } elseif ($this->media_image_s3 == 2) {
                $mediaDisk = 'custom_s3_cms_media_' . ($this->pageId ?: 'new');
                config([
                    "filesystems.disks.{$mediaDisk}" => [
                        'driver' => 's3',
                        'key' => $this->media_image_access_key_id,
                        'secret' => $this->media_image_secret_access_key,
                        'region' => $this->media_image_region,
                        'bucket' => $this->media_image_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            }
            if ($this->header_image_upload) {
                $this->header_image_path = $this->header_image_upload->store('cms', $mediaDisk);
            }
            if ($this->background_image_upload) {
                $this->background_image_path = $this->background_image_upload->store('cms', $mediaDisk);
            }
        }

        if ($this->background_video_upload) {
            $vidDisk = 'public';
            if ($this->background_video_s3 == 1) {
                $vidDisk = 's3';
            } elseif ($this->background_video_s3 == 2) {
                $vidDisk = 'custom_s3_cms_vid_' . ($this->pageId ?: 'new');
                config([
                    "filesystems.disks.{$vidDisk}" => [
                        'driver' => 's3',
                        'key' => $this->background_video_access_key_id,
                        'secret' => $this->background_video_secret_access_key,
                        'region' => $this->background_video_region,
                        'bucket' => $this->background_video_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            }
            $this->background_video_path = $this->background_video_upload->store('cms_videos', $vidDisk);
        }

        if ($this->featured_image_upload) {
            $diskName = 'public';
            if ($this->featured_image_s3 == 1) {
                $diskName = 's3';
            } elseif ($this->featured_image_s3 == 2) {
                $diskName = 'custom_s3_cms_' . ($this->pageId ?: 'new');
                config([
                    "filesystems.disks.{$diskName}" => [
                        'driver' => 's3',
                        'key' => $this->featured_image_access_key_id,
                        'secret' => $this->featured_image_secret_access_key,
                        'region' => $this->featured_image_region,
                        'bucket' => $this->featured_image_bucket_name,
                        'use_path_style_endpoint' => false,
                    ]
                ]);
            }
            $this->featured_image_path = $this->featured_image_upload->store('cms_featured', $diskName);
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'expires_at' => $this->expires_at ?: null,
            'requires_code' => $this->requires_code,
            'access_code' => $this->requires_code ? $this->access_code : null,
            'required_product_id' => $this->required_product_id ?: null,
            'custom_css' => $this->custom_css,
            'custom_js' => $this->custom_js,
            'alternate_page_title' => $this->alternate_page_title ?: null,
            'page_title_alignment' => $this->page_title_alignment ?: 'middle-center',
            'page_title_css' => $this->page_title_css ?: null,
            'include_slideshow' => $this->include_slideshow ?: null,
            'min_header_height' => $this->min_header_height ?: '320px',
            'header_image' => $this->header_image_path,
            'background_image' => $this->background_image_path,
            'media_image_s3' => $this->media_image_s3,
            'media_image_region' => $this->media_image_region,
            'media_image_bucket_name' => $this->media_image_bucket_name,
            'media_image_access_key_id' => $this->media_image_access_key_id,
            'media_image_secret_access_key' => $this->media_image_secret_access_key,
            'media_image_cdn_url' => $this->media_image_cdn_url ?: null,
            'background_video' => $this->background_video_path,
            'background_video_url' => $this->background_video_url ?: null,
            'background_video_type' => $this->background_video_type ?: 'local',
            'background_video_s3' => $this->background_video_s3,
            'background_video_region' => $this->background_video_region ?: null,
            'background_video_bucket_name' => $this->background_video_bucket_name ?: null,
            'background_video_access_key_id' => $this->background_video_access_key_id ?: null,
            'background_video_secret_access_key' => $this->background_video_secret_access_key ?: null,
            'background_video_cdn_url' => $this->background_video_cdn_url ?: null,
            'featured_image' => $this->featured_image_path,
            'featured_image_s3' => $this->featured_image_s3,
            'featured_image_region' => $this->featured_image_region,
            'featured_image_bucket_name' => $this->featured_image_bucket_name,
            'featured_image_access_key_id' => $this->featured_image_access_key_id,
            'featured_image_secret_access_key' => $this->featured_image_secret_access_key,
            'featured_image_cdn_url' => $this->featured_image_cdn_url ?: null,
            'is_active' => $this->is_active,
            'exclude_from_search' => $this->exclude_from_search,
            'cms_search_index' => $this->cms_search_index,
            'cms_search_index_locked' => $this->cms_search_index_locked,
            'layout_type' => $this->layout_type,
            'left_col' => $this->left_col,
            'right_col' => $this->right_col,
            'custom_author' => $this->custom_author ?: null,
            'show_author' => $this->show_author,
            'show_title' => $this->show_title,
            'show_date' => $this->show_date,
            'page_type' => $this->page_type,
            'page_ranking' => $this->page_ranking,
            'hide_page_ranking' => $this->hide_page_ranking ? 1 : 0,
            'custom_sorting' => $this->custom_sorting,
            'author_id' => auth()->id(),
        ];

        if ($this->pageId) {
            $this->page->update($data);
            session()->flash('status', 'Page updated successfully.');
            $this->dispatch('toast', type: 'success', message: 'Page updated successfully.');
        } else {
            $this->page = CmsPage::create($data);
            $this->pageId = $this->page->id;
            session()->flash('status', 'Page created successfully.');
            $this->dispatch('toast', type: 'success', message: 'Page created successfully.');
        }

        // Sync Category
        if ($this->selected_category_id) {
            $this->page->categories()->sync([$this->selected_category_id]);
        } else {
            $this->page->categories()->detach();
        }

        // Sync Tags
        $this->page->tags()->sync($this->selected_tag_ids);

        // Save manual revision
        CmsPageRevision::create([
            'cms_page_id' => $this->pageId,
            'title' => $this->title,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'custom_css' => $this->custom_css,
            'custom_js' => $this->custom_js,
            'header_image' => $this->header_image_path,
            'background_image' => $this->background_image_path,
            'layout_type' => $this->layout_type,
            'left_col' => $this->left_col,
            'right_col' => $this->right_col,
            'custom_author' => $this->custom_author ?: null,
            'show_author' => $this->show_author,
            'show_title' => $this->show_title,
            'show_date' => $this->show_date,
            'revision_type' => 'manual',
            'author_id' => auth()->id(),
        ]);

        $this->loadRevisions();

        // Redirect back to list
        return;
    }

    public function rebuildIndexKeywords(): void
    {
        $dummy = new CmsPage([
            'title' => $this->title,
            'slug' => $this->slug,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'content' => $this->content,
            'left_col' => $this->left_col,
            'right_col' => $this->right_col,
            'cms_search_index_locked' => false,
        ]);
        $this->cms_search_index = $dummy->rebuildSearchIndex(force: true);
        $this->dispatch('toast', type: 'info', message: 'Search index keywords generated from page content.');
    }

    public function saveAutoSaveRevision(): void
    {
        // Only autosave on existing pages with changed/actual content
        if (!$this->pageId || empty($this->title) || empty($this->content)) {
            return;
        }

        // Fetch last revision to compare content and avoid duplicate autosaves of identical data
        $lastRevision = CmsPageRevision::where('cms_page_id', $this->pageId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRevision && 
            $lastRevision->content === $this->content && 
            $lastRevision->title === $this->title && 
            $lastRevision->custom_css === $this->custom_css &&
            $lastRevision->custom_js === $this->custom_js &&
            $lastRevision->layout_type === $this->layout_type &&
            $lastRevision->left_col === $this->left_col &&
            $lastRevision->right_col === $this->right_col &&
            $lastRevision->custom_author === $this->custom_author &&
            $lastRevision->show_author === $this->show_author &&
            $lastRevision->show_title === $this->show_title &&
            $lastRevision->show_date === $this->show_date) {
            return;
        }

        CmsPageRevision::create([
            'cms_page_id' => $this->pageId,
            'title' => $this->title,
            'content' => $this->content,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'custom_css' => $this->custom_css,
            'custom_js' => $this->custom_js,
            'header_image' => $this->header_image_path,
            'background_image' => $this->background_image_path,
            'layout_type' => $this->layout_type,
            'left_col' => $this->left_col,
            'right_col' => $this->right_col,
            'custom_author' => $this->custom_author ?: null,
            'show_author' => $this->show_author,
            'show_title' => $this->show_title,
            'show_date' => $this->show_date,
            'revision_type' => 'autosave',
            'author_id' => auth()->id(),
        ]);

        $this->loadRevisions();
        $this->dispatch('autosave-complete', ['time' => now()->format('H:i:s')]);
    }

    public function previewRevision(int $id): void
    {
        $this->previewingRevision = CmsPageRevision::findOrFail($id);
    }

    public function closePreview(): void
    {
        $this->previewingRevision = null;
    }

    public function restoreRevision(int $id): void
    {
        // 1. Create failsafe backup of the CURRENT page state before restoring
        $page = CmsPage::findOrFail($this->pageId);
        CmsPageRevision::create([
            'cms_page_id' => $page->id,
            'title' => '[Backup before restore] ' . $page->title,
            'content' => $page->content,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'custom_css' => $page->custom_css,
            'custom_js' => $page->custom_js,
            'header_image' => $page->header_image,
            'background_image' => $page->background_image,
            'layout_type' => $page->layout_type,
            'left_col' => $page->left_col,
            'right_col' => $page->right_col,
            'custom_author' => $page->custom_author,
            'show_author' => $page->show_author,
            'show_title' => $page->show_title,
            'show_date' => $page->show_date,
            'revision_type' => 'backup',
            'author_id' => auth()->id(),
        ]);

        // 2. Fetch and restore the selected revision
        $revision = CmsPageRevision::findOrFail($id);
        
        $this->title = $revision->title;
        $this->content = $revision->content;
        $this->meta_title = $revision->meta_title ?? '';
        $this->meta_description = $revision->meta_description ?? '';
        $this->custom_css = $revision->custom_css ?? '';
        $this->custom_js = $revision->custom_js ?? '';
        $this->header_image_path = $revision->header_image;
        $this->background_image_path = $revision->background_image;
        $this->layout_type = $revision->layout_type ?? 1;
        $this->left_col = $revision->left_col ?? '';
        $this->right_col = $revision->right_col ?? '';
        $this->custom_author = $revision->custom_author ?? '';
        $this->show_author = $revision->show_author;
        $this->show_title = $revision->show_title;
        $this->show_date = $revision->show_date;

        $this->previewingRevision = null;
        $this->loadRevisions();
        
        $this->dispatch('content-restored', 
            content: $revision->content,
            left_col: $revision->left_col ?? '',
            right_col: $revision->right_col ?? ''
        );
        session()->flash('status', 'Revision data restored into editor. Save to commit.');
        $this->dispatch('toast', type: 'success', message: 'Revision data restored into editor. Save to commit.');
    }

    public function generateAiContent(): void
    {
        $this->resetErrorBag('ai_content_error');

        $apiKey = config('ai.openai_api_key');
        if (empty($apiKey) || !function_exists('ai_cms_page_content')) {
            return;
        }

        if (blank($this->content)) {
            $this->addError('ai_content_error', 'Please write some content in the page body editor first.');
            return;
        }

        $res = ai_cms_page_content($this->content, $this->aiPrompt);
        if (function_exists('wrap_prose_content')) {
            $res = wrap_prose_content($res);
        }
        $this->aiResponse = $res;
    }

    public function render(): View
    {
        $showAiButton = !empty(config('ai.openai_api_key')) && function_exists('ai_cms_page_content');
        $layouts = \Illuminate\Support\Facades\DB::table('cms_layouts')->orderBy('id', 'asc')->get();
        $pageTypes = \Illuminate\Support\Facades\DB::table('cms_page_types')->orderBy('id', 'asc')->get();
        $categoriesList = \App\Models\CmsPagesCategory::orderBy('name', 'asc')->get();
        $tagsList = \App\Models\CmsPagesTag::orderBy('name', 'asc')->get();
        $displayPlugins = \App\Models\Plugin::active()->ofType('display')->orderBy('name', 'asc')->get();

        $searchedProducts = [];
        if (strlen($this->searchProduct) >= 2) {
            $searchedProducts = Product::where('title', 'like', '%' . $this->searchProduct . '%')
                ->orWhere('seo_slug', 'like', '%' . $this->searchProduct . '%')
                ->limit(25)
                ->get();
        }

        // Gating product live search (max 15 results; also matches direct numeric ID entry)
        $gatingProductResults = collect();
        $q = trim($this->gatingProductSearch);
        if (strlen($q) >= 1) {
            $gatingQuery = Product::orderBy('title');
            if (is_numeric($q)) {
                // Direct ID entry: exact match first, then title fallback
                $gatingQuery->where('id', (int) $q)
                            ->orWhere('title', 'like', '%' . $q . '%');
            } else {
                $gatingQuery->where('title', 'like', '%' . $q . '%')
                            ->orWhere('seo_slug', 'like', '%' . $q . '%');
            }
            $gatingProductResults = $gatingQuery->limit(15)->get();
        }

        // Resolve the currently selected gating product title for display
        $selectedGatingProduct = $this->required_product_id
            ? Product::find($this->required_product_id)
            : null;

        $searchedBrands = [];
        if (strlen($this->searchBrand) >= 2) {
            $searchedBrands = \App\Models\Brand::where('name', 'like', '%' . $this->searchBrand . '%')
                ->orWhere('slug', 'like', '%' . $this->searchBrand . '%')
                ->limit(25)
                ->get();
        }

        $searchedCategories = [];
        if (strlen($this->searchCategory) >= 2) {
            $searchedCategories = \App\Models\Category::where('name', 'like', '%' . $this->searchCategory . '%')
                ->orWhere('slug', 'like', '%' . $this->searchCategory . '%')
                ->limit(25)
                ->get();
        }

        $searchedPages = [];
        if (strlen($this->searchPage) >= 2) {
            $searchedPages = CmsPage::where('title', 'like', '%' . $this->searchPage . '%')
                ->orWhere('slug', 'like', '%' . $this->searchPage . '%')
                ->limit(25)
                ->get();
        }

        $shortcodeSearchResults = [];
        if (!empty($this->shortcodeSearchQuery)) {
            $q = '%' . $this->shortcodeSearchQuery . '%';
            
            $pagesLimit      = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $productsLimit   = ($this->shortcodeSearchScope === 'all') ? 10 : 25;
            $categoriesLimit = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $brandsLimit     = ($this->shortcodeSearchScope === 'all') ? 5 : 25;
            $downloadsLimit  = ($this->shortcodeSearchScope === 'all') ? 5 : 25;

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'pages') {
                $pages = CmsPage::where('title', 'like', $q)->limit($pagesLimit)->get();
                foreach ($pages as $p) {
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
                $productsList = Product::where('title', 'like', $q)->limit($productsLimit)->get();
                foreach ($productsList as $p) {
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
                $categories = \App\Models\Category::where('name', 'like', $q)->limit($categoriesLimit)->get();
                foreach ($categories as $c) {
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
                $brands = \App\Models\Brand::where('name', 'like', $q)->limit($brandsLimit)->get();
                foreach ($brands as $b) {
                    $shortcodeSearchResults[] = [
                        'type'        => 'Brand',
                        'id'          => $b->id,
                        'title'       => $b->name,
                        'badgeColor'  => 'bg-violet-100 text-violet-800 border-violet-200',
                        'shortcode'   => '[brand:' . $b->id . ' label="' . e($b->name) . '"]'
                    ];
                }
            }

            if ($this->shortcodeSearchScope === 'all' || $this->shortcodeSearchScope === 'downloads') {
                $downloads = \App\Models\CmsDownload::where('is_active', true)
                    ->where(function ($q2) use ($q) {
                        $q2->where('internal_name', 'like', $q)
                           ->orWhere('link_label', 'like', $q);
                    })
                    ->limit($downloadsLimit)
                    ->get();
                foreach ($downloads as $d) {
                    $label = $d->link_label ?: $d->internal_name;
                    $shortcodeSearchResults[] = [
                        'type'       => 'Download',
                        'id'         => $d->id,
                        'title'      => $d->internal_name,
                        'badgeColor' => 'bg-teal-100 text-teal-800 border-teal-200',
                        'shortcode'  => '[download:' . $d->uuid . ' label="' . e($label) . '"]'
                    ];
                }
            }

            if (count($shortcodeSearchResults) > 25) {
                $shortcodeSearchResults = array_slice($shortcodeSearchResults, 0, 25);
            }
        }

        $activeLanguages = \App\Models\Language::getAllActive()->where('is_default', false)->values();

        return view('livewire.admin-cms-page-edit', compact(
            'layouts', 'pageTypes', 'categoriesList', 'tagsList', 'displayPlugins',
            'searchedProducts', 'searchedBrands', 'searchedCategories', 'searchedPages', 'shortcodeSearchResults', 'showAiButton',
            'gatingProductResults', 'selectedGatingProduct', 'activeLanguages'
        ));
    }

    // ── Translation Management ─────────────────────────────────────────────────

    public function selectTranslationLang(string $code, int $langId): void
    {
        $this->activeLangCode = $code;
        $this->activeLangId = $langId;
        $this->loadTranslationData();
    }

    protected function loadTranslationData(): void
    {
        if (!$this->pageId || !$this->activeLangId) return;

        $trans = \App\Models\CmsPageTranslation::where('cms_page_id', $this->pageId)
            ->where('language_id', $this->activeLangId)
            ->first();

        $this->trans_title                = $trans?->title ?? '';
        $this->trans_content              = $trans?->content ?? '';
        $this->trans_meta_title           = $trans?->meta_title ?? '';
        $this->trans_meta_description     = $trans?->meta_description ?? '';
        $this->trans_alternate_page_title = $trans?->alternate_page_title ?? '';
        $this->trans_status               = $trans?->translation_status ?? 'pending';
        $this->trans_translated_at        = $trans?->translated_at?->format('M j, Y g:i A');
    }

    public function saveTranslation(): void
    {
        if (!$this->pageId || !$this->activeLangId) return;

        \App\Models\CmsPageTranslation::updateOrCreate(
            ['cms_page_id' => $this->pageId, 'language_id' => $this->activeLangId],
            [
                'title'                 => $this->trans_title ?: null,
                'content'               => $this->trans_content ?: null,
                'meta_title'            => $this->trans_meta_title ?: null,
                'meta_description'      => $this->trans_meta_description ?: null,
                'alternate_page_title'  => $this->trans_alternate_page_title ?: null,
                'translation_status'    => 'reviewed',
                'translated_at'         => now(),
            ]
        );

        $this->trans_status        = 'reviewed';
        $this->trans_translated_at = now()->format('M j, Y g:i A');
        session()->flash('success', 'Translation saved successfully.');
    }

    public function autoTranslatePage(): void
    {
        if (!$this->pageId || !$this->activeLangId) return;

        \App\Jobs\TranslateContentJob::dispatch(
            \App\Models\CmsPage::class,
            $this->pageId,
            $this->activeLangId
        );

        session()->flash('success', 'Translation job queued. Refresh in a moment to see the results.');
    }

    /**
     * Inline AI translation — calls OpenAI synchronously and pre-fills all
     * translation fields so the admin can review before saving.
     * The existing autoTranslatePage() bulk queue method is unchanged.
     */
    public function aiTranslatePageInline(): void
    {
        if (!$this->pageId || !$this->activeLangId) return;

        $page = \App\Models\CmsPage::find($this->pageId);
        $lang = \App\Models\Language::find($this->activeLangId);

        if (!$page || !$lang) return;

        try {
            $svc      = app(\App\Services\TranslationService::class);
            $langName = $lang->name;

            if (!empty($page->title)) {
                $this->trans_title = $svc->translateText($page->title, $langName, 'page title');
            }
            if (!empty($page->alternate_page_title)) {
                $this->trans_alternate_page_title = $svc->translateText($page->alternate_page_title, $langName, 'page heading');
            }
            if (!empty($page->meta_title)) {
                $this->trans_meta_title = $svc->translateText($page->meta_title, $langName, 'SEO meta title');
            }
            if (!empty($page->meta_description)) {
                $this->trans_meta_description = $svc->translateText($page->meta_description, $langName, 'SEO meta description');
            }
            if (!empty($page->content)) {
                $this->trans_content = $svc->translateText($page->content, $langName, 'page content body HTML — preserve all HTML tags and shortcodes');
            }

            $this->trans_status = 'ai_translated';
            $this->dispatch('toast', message: 'AI translation ready — review all fields and click Save Translation.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function clearHeaderImage(): void
    {
        $this->header_image_upload = null;
        $this->header_image_path   = null;
        if ($this->pageId && $this->page) {
            $this->page->header_image = null;
            $this->page->save();
        }
        $this->dispatch('toast', message: 'Header image cleared.', type: 'info');
    }

    public function clearBackgroundImage(): void
    {
        $this->background_image_upload = null;
        $this->background_image_path   = null;
        if ($this->pageId && $this->page) {
            $this->page->background_image = null;
            $this->page->save();
        }
        $this->dispatch('toast', message: 'Background image cleared.', type: 'info');
    }

    public function clearBackgroundVideo(): void
    {
        $this->background_video_upload = null;
        $this->background_video_path = null;
        $this->background_video_url = null;
        if ($this->pageId && $this->page) {
            $this->page->background_video = null;
            $this->page->background_video_url = null;
            $this->page->save();
        }
        $this->dispatch('toast', message: 'Per-page background video settings reset.', type: 'info');
        session()->flash('status', 'Per-page background video settings reset.');
    }
}
