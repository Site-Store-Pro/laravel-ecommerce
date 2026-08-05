<?php

namespace App\Livewire;

use App\Models\CmsModal;
use App\Models\CmsModalTranslation;
use App\Models\Language;
use App\Models\Plugin;
use App\Services\TranslationService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminModalEdit extends Component
{
    // ── Core fields ──────────────────────────────────────────────────────────
    public ?int    $modalId              = null;
    public string  $title                = '';
    public string  $body                 = '';
    public string  $position             = 'center';
    public string  $max_width            = '640px';
    public string  $custom_css           = '';
    public string  $cookie_name          = '';
    public int     $cookie_lifetime      = 30;
    public bool    $auto_open            = false;
    public int     $open_delay           = 0;
    public bool    $overlay_dismissible  = true;
    public bool    $show_close_button    = true;
    public string  $trigger_selector     = '';
    public bool    $is_active            = true;

    // ── Translation state ─────────────────────────────────────────────────
    public int     $tlLangId   = 0;
    public array   $tlBuffer   = [];   // ['title' => '...', 'body' => '...']
    public string  $tlStatus   = '';
    public string  $tlTranslatedAt = '';

    // ── Right panel live-search ────────────────────────────────────────────
    public string  $searchProduct  = '';
    public string  $searchBrand    = '';
    public string  $searchCategory = '';
    public string  $searchPage     = '';
    public string  $shortcodeSearchQuery = '';

    // ──────────────────────────────────────────────────────────────────────

    protected function rules(): array
    {
        return [
            'title'               => 'required|string|max:255',
            'body'                => 'nullable|string',
            'position'            => 'required|in:center,left,right,bottom',
            'max_width'           => 'nullable|string|max:50',
            'custom_css'          => 'nullable|string',
            'cookie_name'         => 'nullable|string|max:100',
            'cookie_lifetime'     => 'required|integer|min:0',
            'auto_open'           => 'boolean',
            'open_delay'          => 'required|integer|min:0',
            'overlay_dismissible' => 'boolean',
            'show_close_button'   => 'boolean',
            'trigger_selector'    => 'nullable|string|max:255',
            'is_active'           => 'boolean',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $modal = CmsModal::findOrFail($id);
            $this->modalId             = $modal->id;
            $this->title               = $modal->title;
            $this->body                = $modal->body ?? '';
            $this->position            = $modal->position;
            $this->max_width           = $modal->max_width;
            $this->custom_css          = $modal->custom_css ?? '';
            $this->cookie_name         = $modal->cookie_name ?? '';
            $this->cookie_lifetime     = $modal->cookie_lifetime;
            $this->auto_open           = $modal->auto_open;
            $this->open_delay          = $modal->open_delay;
            $this->overlay_dismissible = $modal->overlay_dismissible;
            $this->show_close_button   = $modal->show_close_button;
            $this->trigger_selector    = $modal->trigger_selector ?? '';
            $this->is_active           = $modal->is_active;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title'               => $this->title,
            'body'                => $this->body,
            'position'            => $this->position,
            'max_width'           => $this->max_width ?: '640px',
            'custom_css'          => $this->custom_css ?: null,
            'cookie_name'         => $this->cookie_name ?: null,
            'cookie_lifetime'     => $this->cookie_lifetime,
            'auto_open'           => $this->auto_open,
            'open_delay'          => $this->open_delay,
            'overlay_dismissible' => $this->overlay_dismissible,
            'show_close_button'   => $this->show_close_button,
            'trigger_selector'    => $this->trigger_selector ?: null,
            'is_active'           => $this->is_active,
        ];

        if ($this->modalId) {
            $modal = CmsModal::findOrFail($this->modalId);
            $modal->update($data);
            $this->dispatch('toast', message: 'Modal saved successfully.', type: 'success');
        } else {
            $modal = CmsModal::create($data);
            $this->modalId = $modal->id;
            $this->redirect(route('admin.modals.edit', $modal->id), navigate: true);
        }
    }

    public function delete(): void
    {
        if ($this->modalId) {
            CmsModal::findOrFail($this->modalId)->delete();
            $this->dispatch('toast', message: 'Modal deleted.', type: 'success');
            $this->redirect(route('admin.modals.index'), navigate: true);
        }
    }

    // ── Translation Methods ────────────────────────────────────────────────

    public function selectTlLang(int $langId): void
    {
        $this->tlLangId = $langId;
        $this->tlBuffer = [];
        $this->tlStatus = '';
        $this->tlTranslatedAt = '';

        if (!$this->modalId) {
            return;
        }

        $existing = CmsModalTranslation::where('cms_modal_id', $this->modalId)
            ->where('language_id', $langId)
            ->first();

        if ($existing) {
            $this->tlBuffer = $existing->only(['title', 'body']);
            $this->tlStatus = $existing->translation_status;
            $this->tlTranslatedAt = $existing->translated_at
                ? $existing->translated_at->format('M d, Y H:i')
                : '';
        }
    }

    public function saveTlModal(): void
    {
        if (!$this->tlLangId || !$this->modalId) {
            return;
        }

        CmsModalTranslation::updateOrCreate(
            ['cms_modal_id' => $this->modalId, 'language_id' => $this->tlLangId],
            array_merge($this->tlBuffer, [
                'translation_status' => 'reviewed',
                'translated_at'      => now(),
            ])
        );

        $this->tlStatus = 'reviewed';
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTlModal(): void
    {
        if (!$this->tlLangId || !$this->modalId) {
            return;
        }

        $lang = Language::findOrFail($this->tlLangId);

        try {
            $svc = app(TranslationService::class);

            if (!empty($this->title)) {
                $this->tlBuffer['title'] = $svc->translateText($this->title, $lang->name, 'Modal popup title');
            }
            if (!empty(strip_tags($this->body))) {
                $this->tlBuffer['body'] = $svc->translateText($this->body, $lang->name, 'Modal popup body content (HTML)');
            }

            $this->dispatch('toast', message: 'AI translation ready — review and save.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        }
    }

    // ── Computed for right panels ─────────────────────────────────────────

    public function getSearchedProductsProperty()
    {
        if (strlen($this->searchProduct) < 2) {
            return collect();
        }
        return \App\Models\Product::where('title', 'like', '%' . $this->searchProduct . '%')
            ->select('id', 'title', 'slug')
            ->limit(8)
            ->get();
    }

    public function getSearchedBrandsProperty()
    {
        if (strlen($this->searchBrand) < 2) {
            return collect();
        }
        return \App\Models\Brand::where('name', 'like', '%' . $this->searchBrand . '%')
            ->select('id', 'name', 'slug')
            ->limit(8)
            ->get();
    }

    public function getSearchedCategoriesProperty()
    {
        if (strlen($this->searchCategory) < 2) {
            return collect();
        }
        return \App\Models\Category::where('name', 'like', '%' . $this->searchCategory . '%')
            ->select('id', 'name', 'slug')
            ->limit(8)
            ->get();
    }

    public function getSearchedPagesProperty()
    {
        if (strlen($this->searchPage) < 2) {
            return collect();
        }
        return \App\Models\CmsPage::where('title', 'like', '%' . $this->searchPage . '%')
            ->where('is_active', true)
            ->select('id', 'title', 'slug')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        $activeLanguages = Language::where('is_active', true)
            ->where('is_default', false)
            ->orderBy('sort_order')
            ->get();

        $displayPlugins = Plugin::where('type', 'display')
            ->where('activation_status', 1)
            ->get();

        return view('livewire.admin-modal-edit', compact('activeLanguages', 'displayPlugins'));
    }
}
