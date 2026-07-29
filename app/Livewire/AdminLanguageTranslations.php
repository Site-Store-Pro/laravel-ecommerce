<?php

namespace App\Livewire;

use App\Models\Language;
use App\Services\TranslationService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AdminLanguageTranslations extends Component
{
    public int $languageId = 0;
    public ?Language $language = null;
    public array $stats = [];
    public string $activeType = 'cms_pages';

    public function mount(int $languageId): void
    {
        $this->languageId = $languageId;
        $this->language = Language::findOrFail($languageId);
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $service = app(TranslationService::class);
        $this->stats = [
            'cms_pages'          => $service->translationStats(\App\Models\CmsPage::class, $this->languageId),
            'products'           => $service->translationStats(\App\Models\Product::class, $this->languageId),
            'kb_articles'        => $service->translationStats(\App\Models\KbArticle::class, $this->languageId),
            'testimonials'       => $service->translationStats(\App\Models\CmsTestimonial::class, $this->languageId),
            'nav_items'          => $service->translationStats(\App\Models\NavItem::class, $this->languageId),
            'list_menus'         => $service->translationStats(\App\Models\CmsListMenuItem::class, $this->languageId),
            'site_labels'        => $service->translationStats(\App\Models\SiteLabel::class, $this->languageId),
            'product_categories' => $service->translationStats(\App\Models\Category::class, $this->languageId),
            'cms_categories'     => $service->translationStats(\App\Models\CmsPagesCategory::class, $this->languageId),
            'cms_tags'           => $service->translationStats(\App\Models\CmsPagesTag::class, $this->languageId),
            'kb_categories'      => $service->translationStats(\App\Models\KbCategory::class, $this->languageId),
            'email_templates'    => $service->translationStats(\App\Models\EmailTemplate::class, $this->languageId),
        ];
    }

    public function translateType(string $type): void
    {
        $map = [
            'cms_pages'          => \App\Models\CmsPage::class,
            'products'           => \App\Models\Product::class,
            'kb_articles'        => \App\Models\KbArticle::class,
            'testimonials'       => \App\Models\CmsTestimonial::class,
            'nav_items'          => \App\Models\NavItem::class,
            'list_menus'         => \App\Models\CmsListMenuItem::class,
            'site_labels'        => \App\Models\SiteLabel::class,
            'product_categories' => \App\Models\Category::class,
            'cms_categories'     => \App\Models\CmsPagesCategory::class,
            'cms_tags'           => \App\Models\CmsPagesTag::class,
            'kb_categories'      => \App\Models\KbCategory::class,
            'email_templates'    => \App\Models\EmailTemplate::class,
        ];

        $modelClass = $map[$type] ?? null;
        if (!$modelClass) return;

        $ids = $modelClass::pluck('id');
        foreach ($ids as $id) {
            \App\Jobs\TranslateContentJob::dispatch($modelClass, $id, $this->languageId);
        }

        $this->dispatch('toast',
            message: count($ids) . ' ' . str_replace('_', ' ', $type) . ' translation jobs queued.',
            type: 'success'
        );
        $this->loadStats();
    }

    public function translateSingle(string $type, int $modelId): void
    {
        // Same map as above
        $map = [
            'cms_pages'          => \App\Models\CmsPage::class,
            'products'           => \App\Models\Product::class,
            'kb_articles'        => \App\Models\KbArticle::class,
            'testimonials'       => \App\Models\CmsTestimonial::class,
            'nav_items'          => \App\Models\NavItem::class,
            'list_menus'         => \App\Models\CmsListMenuItem::class,
            'site_labels'        => \App\Models\SiteLabel::class,
            'product_categories' => \App\Models\Category::class,
            'cms_categories'     => \App\Models\CmsPagesCategory::class,
            'cms_tags'           => \App\Models\CmsPagesTag::class,
            'kb_categories'      => \App\Models\KbCategory::class,
            'email_templates'    => \App\Models\EmailTemplate::class,
        ];
        $modelClass = $map[$type] ?? null;
        if ($modelClass) {
            \App\Jobs\TranslateContentJob::dispatch($modelClass, $modelId, $this->languageId);
            $this->dispatch('toast', message: 'Translation job queued.', type: 'success');
        }
    }

    public function render(): View
    {
        // Load untranslated items for the active type
        $map = [
            'cms_pages'          => [\App\Models\CmsPage::class,        'cms_page_id',              'title'],
            'products'           => [\App\Models\Product::class,         'product_id',               'title'],
            'kb_articles'        => [\App\Models\KbArticle::class,       'kb_article_id',            'title'],
            'testimonials'       => [\App\Models\CmsTestimonial::class,  'testimonial_id',           'author_name'],
            'nav_items'          => [\App\Models\NavItem::class,         'nav_item_id',              'label'],
            'list_menus'         => [\App\Models\CmsListMenuItem::class, 'cms_list_menu_item_id',    'list_item'],
            'site_labels'        => [\App\Models\SiteLabel::class,       'site_label_id',            'label_key'],
            'product_categories' => [\App\Models\Category::class,        'category_id',              'name'],
            'cms_categories'     => [\App\Models\CmsPagesCategory::class,'cms_pages_category_id',    'name'],
            'cms_tags'           => [\App\Models\CmsPagesTag::class,     'cms_pages_tag_id',         'name'],
            'kb_categories'      => [\App\Models\KbCategory::class,      'kb_category_id',           'name'],
            'email_templates'    => [\App\Models\EmailTemplate::class,   'email_template_id',        'profile_name'],
        ];

        $items = collect();
        if (isset($map[$this->activeType])) {
            [$modelClass, $fk, $labelField] = $map[$this->activeType];
            $translationClass = 'App\\Models\\' . class_basename($modelClass) . 'Translation';
            $translatedIds = class_exists($translationClass)
                ? $translationClass::where('language_id', $this->languageId)->pluck($fk)
                : collect();
            $items = $modelClass::whereNotIn('id', $translatedIds)->limit(50)->get(['id', $labelField]);
        }

        return view('livewire.admin-language-translations', [
            'items'     => $items,
            'labelField'=> $map[$this->activeType][2] ?? 'id',
            'typeMap'   => array_keys($map),
        ]);
    }
}
