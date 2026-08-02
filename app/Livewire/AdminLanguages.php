<?php

namespace App\Livewire;

use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminLanguages extends Component
{
    public bool $showAddModal = false;
    public bool $showEditModal = false;
    public ?int $editingId = null;

    // Form fields
    public string $code = '';
    public string $name = '';
    public string $native_name = '';
    public string $flag_emoji = '';
    public bool $is_active = true;
    public bool $show_in_switcher = true;
    public bool $rtl = false;
    
    // Currency fields
    public string $currency_code = '';
    public string $currency_symbol = '';
    public string $currency_position = 'before';
    public string $decimal_separator = '.';
    public string $thousands_separator = ',';
    
    public int $sort_order = 0;

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10', Rule::unique('languages', 'code')->ignore($this->editingId)],
            'name' => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag_emoji' => 'required|string|max:10',
            'currency_position' => 'in:before,after',
            'is_active' => 'boolean',
            'show_in_switcher' => 'boolean',
            'rtl' => 'boolean',
            'currency_code' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'decimal_separator' => 'nullable|string|max:5',
            'thousands_separator' => 'nullable|string|max:5',
            'sort_order' => 'integer',
        ];
    }

    public function openAddModal(): void
    {
        $this->reset(['code', 'name', 'native_name', 'flag_emoji', 'is_active', 'show_in_switcher', 'rtl', 'currency_code', 'currency_symbol', 'currency_position', 'decimal_separator', 'thousands_separator', 'sort_order', 'editingId']);
        $this->showAddModal = true;
        $this->showEditModal = false;
    }

    public function saveLanguage(): void
    {
        $validated = $this->validate();

        Language::create($validated);
        Language::clearCache();

        $this->showAddModal = false;
        $this->dispatch('toast', message: 'Language added successfully.', type: 'success');
    }

    public function editLanguage(int $id): void
    {
        $lang = Language::findOrFail($id);
        
        $this->editingId = $lang->id;
        $this->code = $lang->code;
        $this->name = $lang->name;
        $this->native_name = $lang->native_name;
        $this->flag_emoji = $lang->flag_emoji;
        $this->is_active = (bool) $lang->is_active;
        $this->show_in_switcher = (bool) $lang->show_in_switcher;
        $this->rtl = (bool) $lang->rtl;
        
        $this->currency_code = $lang->currency_code ?? '';
        $this->currency_symbol = $lang->currency_symbol ?? '';
        $this->currency_position = $lang->currency_position ?? 'before';
        $this->decimal_separator = $lang->decimal_separator ?? '.';
        $this->thousands_separator = $lang->thousands_separator ?? ',';
        $this->sort_order = $lang->sort_order;

        $this->showEditModal = true;
        $this->showAddModal = false;
    }

    public function updateLanguage(): void
    {
        $validated = $this->validate();

        $lang = Language::findOrFail($this->editingId);
        $lang->update($validated);
        Language::clearCache();

        $this->showEditModal = false;
        $this->dispatch('toast', message: 'Language updated successfully.', type: 'success');
    }

    public function deleteLanguage(int $id): void
    {
        $lang = Language::findOrFail($id);

        if ($lang->is_default) {
            $this->dispatch('toast', message: 'Cannot delete the default language.', type: 'error');
            return;
        }

        $lang->delete();
        Language::clearCache();

        $this->dispatch('toast', message: 'Language deleted.', type: 'success');
    }

    public function setDefault(int $id): void
    {
        $lang = Language::findOrFail($id);

        Language::where('is_default', true)->update(['is_default' => false]);
        $lang->update(['is_default' => true]);
        Language::clearCache();

        $this->dispatch('toast', message: 'Default language updated.', type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $lang = Language::findOrFail($id);
        $lang->update(['is_active' => !$lang->is_active]);
        Language::clearCache();

        $this->dispatch('toast', message: 'Language status updated.', type: 'success');
    }

    public function toggleSwitcher(int $id): void
    {
        $lang = Language::findOrFail($id);
        $lang->update(['show_in_switcher' => !$lang->show_in_switcher]);
        Language::clearCache();

        $this->dispatch('toast', message: 'Switcher visibility updated.', type: 'success');
    }

    public function bulkTranslate(int $id): void
    {
        $models = [
            \App\Models\CmsPage::class,
            \App\Models\Product::class,
            \App\Models\KbArticle::class,
            \App\Models\CmsTestimonial::class,
            \App\Models\NavItem::class,
            \App\Models\CmsListMenuItem::class,
            \App\Models\SiteLabel::class,
            \App\Models\Category::class,
            \App\Models\CmsPagesCategory::class,
            \App\Models\CmsPagesTag::class,
            \App\Models\KbCategory::class,
            \App\Models\EmailTemplate::class,
        ];

        $count = 0;

        foreach ($models as $modelClass) {
            $ids = $modelClass::pluck('id');
            foreach ($ids as $modelId) {
                \App\Jobs\TranslateContentJob::dispatch($modelClass, $modelId, $id);
                $count++;
            }
        }

        // Dispatch dedicated variant translation jobs for attribute labels
        // and personalization labels (handled by TranslateVariantJob, not the generic job).
        $variantIds = \App\Models\ProductVariant::pluck('id');
        foreach ($variantIds as $variantId) {
            \App\Jobs\TranslateVariantJob::dispatch($variantId, $id);
            $count++;
        }

        $this->dispatch('toast',
            message: "{$count} translation jobs queued — run `php artisan queue:work` on the server to process them.",
            type: 'success'
        );
    }

    public function translationStats(int $languageId): array
    {
        $service = app(TranslationService::class);
        $variantStats = $service->variantTranslationStats($languageId);
        return [
            'cms_pages'              => $service->translationStats(\App\Models\CmsPage::class, $languageId),
            'products'               => $service->translationStats(\App\Models\Product::class, $languageId),
            'variant_attributes'     => [
                'total'      => $variantStats['total_with_attrs'],
                'translated' => $variantStats['translated_attrs'],
                'pending'    => $variantStats['pending_attrs'],
                'reviewed'   => 0,
            ],
            'variant_personalization' => [
                'total'      => $variantStats['total_personalization'],
                'translated' => $variantStats['translated_personalization'],
                'pending'    => $variantStats['pending_personalization'],
                'reviewed'   => 0,
            ],
            'kb_articles'            => $service->translationStats(\App\Models\KbArticle::class, $languageId),
            'testimonials'           => $service->translationStats(\App\Models\CmsTestimonial::class, $languageId),
            'nav_items'              => $service->translationStats(\App\Models\NavItem::class, $languageId),
            'list_menus'             => $service->translationStats(\App\Models\CmsListMenuItem::class, $languageId),
            'site_labels'            => $service->translationStats(\App\Models\SiteLabel::class, $languageId),
            'product_categories'     => $service->translationStats(\App\Models\Category::class, $languageId),
            'cms_categories'         => $service->translationStats(\App\Models\CmsPagesCategory::class, $languageId),
            'cms_tags'               => $service->translationStats(\App\Models\CmsPagesTag::class, $languageId),
            'kb_categories'          => $service->translationStats(\App\Models\KbCategory::class, $languageId),
            'email_templates'        => $service->translationStats(\App\Models\EmailTemplate::class, $languageId),
        ];
    }

    public function render(): View
    {
        $languages = Language::orderBy('sort_order')->orderBy('name')->get();
        return view('livewire.admin-languages', ['languages' => $languages]);
    }
}
