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
        $variantStats = $service->variantTranslationStats($this->languageId);

        $this->stats = [
            'cms_pages'              => $service->translationStats(\App\Models\CmsPage::class, $this->languageId),
            'products'               => $service->translationStats(\App\Models\Product::class, $this->languageId),
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
            'kb_articles'            => $service->translationStats(\App\Models\KbArticle::class, $this->languageId),
            'testimonials'           => $service->translationStats(\App\Models\CmsTestimonial::class, $this->languageId),
            'nav_items'              => $service->translationStats(\App\Models\NavItem::class, $this->languageId),
            'list_menus'             => $service->translationStats(\App\Models\CmsListMenuItem::class, $this->languageId),
            'site_labels'            => $service->translationStats(\App\Models\SiteLabel::class, $this->languageId),
            'product_categories'     => $service->translationStats(\App\Models\Category::class, $this->languageId),
            'cms_categories'         => $service->translationStats(\App\Models\CmsPagesCategory::class, $this->languageId),
            'cms_tags'               => $service->translationStats(\App\Models\CmsPagesTag::class, $this->languageId),
            'kb_categories'          => $service->translationStats(\App\Models\KbCategory::class, $this->languageId),
            'email_templates'        => $service->translationStats(\App\Models\EmailTemplate::class, $this->languageId),
            'modals'                 => $service->translationStats(\App\Models\CmsModal::class, $this->languageId),
            'cms_faqs'               => $service->translationStats(\App\Models\CmsFaq::class, $this->languageId),
            'slideshow_slides'        => $service->translationStats(\App\Models\CmsSlide::class, $this->languageId),
            'builder_blocks'         => $service->translationStats(\App\Models\CmsBuilderBlock::class, $this->languageId),
            'inventory_alerts'       => $service->translationStats(\App\Models\ProductInventoryAlert::class, $this->languageId),
            'product_fields'         => $service->translationStats(\App\Models\ProductField::class,          $this->languageId),
            'product_field_options'  => $service->translationStats(\App\Models\ProductFieldOption::class,    $this->languageId),
            'product_reviews'        => $service->translationStats(\App\Models\ProductReview::class,         $this->languageId),
            'plugins'                => $service->pluginTranslationStats($this->languageId),
        ];
    }

    public function translateType(string $type): void
    {
        if ($type === 'plugins') {
            $plugins = \App\Models\Plugin::all();
            $count = 0;
            foreach ($plugins as $plugin) {
                if (!empty($plugin->getTranslatableFields())) {
                    \App\Jobs\TranslatePluginJob::dispatch($plugin->id, $this->languageId);
                    $count++;
                }
            }
            $this->dispatch('toast',
                message: "{$count} plugin translation jobs queued.",
                type: 'success'
            );
            $this->loadStats();
            return;
        }

        // Variant attribute and personalization translations use the dedicated job.
        if ($type === 'variant_attributes' || $type === 'variant_personalization') {
            $variantIds = \App\Models\ProductVariant::pluck('id');
            foreach ($variantIds as $id) {
                \App\Jobs\TranslateVariantJob::dispatch($id, $this->languageId);
            }
            $this->dispatch('toast',
                message: count($variantIds) . ' variant translation jobs queued.',
                type: 'success'
            );
            $this->loadStats();
            return;
        }

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
            'modals'             => \App\Models\CmsModal::class,
            'cms_faqs'           => \App\Models\CmsFaq::class,
            'slideshow_slides'    => \App\Models\CmsSlide::class,
            'builder_blocks'     => \App\Models\CmsBuilderBlock::class,
            'inventory_alerts'   => \App\Models\ProductInventoryAlert::class,
            'product_fields'        => \App\Models\ProductField::class,
            'product_field_options' => \App\Models\ProductFieldOption::class,
            'product_reviews'       => \App\Models\ProductReview::class,
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
        if ($type === 'plugins') {
            $plugin = \App\Models\Plugin::find($modelId);
            if ($plugin) {
                \App\Jobs\TranslatePluginJob::dispatch($plugin->id, $this->languageId);
                $this->dispatch('toast', message: 'Translation job queued for ' . $plugin->name . '.', type: 'success');
            }
            return;
        }

        // Variant types use the dedicated job.
        if ($type === 'variant_attributes' || $type === 'variant_personalization') {
            \App\Jobs\TranslateVariantJob::dispatch($modelId, $this->languageId);
            $this->dispatch('toast', message: 'Variant translation job queued.', type: 'success');
            return;
        }

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
            'modals'             => \App\Models\CmsModal::class,
            'cms_faqs'           => \App\Models\CmsFaq::class,
            'slideshow_slides'    => \App\Models\CmsSlide::class,
            'builder_blocks'     => \App\Models\CmsBuilderBlock::class,
            'inventory_alerts'   => \App\Models\ProductInventoryAlert::class,
            'product_fields'        => \App\Models\ProductField::class,
            'product_field_options' => \App\Models\ProductFieldOption::class,
            'product_reviews'       => \App\Models\ProductReview::class,
        ];
        $modelClass = $map[$type] ?? null;
        if ($modelClass) {
            \App\Jobs\TranslateContentJob::dispatch($modelClass, $modelId, $this->languageId);
            $this->dispatch('toast', message: 'Translation job queued.', type: 'success');
        }
    }

    public function render(): View
    {
        // Load untranslated items for the active type.
        // Variant types show a list of variants with pending attribute/personalization translations.
        $standardMap = [
            'cms_pages'          => [\App\Models\CmsPage::class,           'cms_page_id',              'title'],
            'products'           => [\App\Models\Product::class,           'product_id',               'title'],
            'kb_articles'        => [\App\Models\KbArticle::class,         'kb_article_id',            'title'],
            'testimonials'       => [\App\Models\CmsTestimonial::class,    'testimonial_id',           'author_title'],
            'nav_items'          => [\App\Models\NavItem::class,           'nav_item_id',              'label'],
            'list_menus'         => [\App\Models\CmsListMenuItem::class,   'cms_list_menu_item_id',    'list_item'],
            'site_labels'        => [\App\Models\SiteLabel::class,         'site_label_id',            'label_key'],
            'product_categories' => [\App\Models\Category::class,          'category_id',              'name'],
            'cms_categories'     => [\App\Models\CmsPagesCategory::class,  'cms_pages_category_id',    'name'],
            'cms_tags'           => [\App\Models\CmsPagesTag::class,       'cms_pages_tag_id',         'name'],
            'kb_categories'      => [\App\Models\KbCategory::class,        'kb_category_id',           'name'],
            'email_templates'    => [\App\Models\EmailTemplate::class,     'email_template_id',        'profile_name'],
            'modals'             => [\App\Models\CmsModal::class,          'cms_modal_id',             'title'],
            'cms_faqs'           => [\App\Models\CmsFaq::class,            'cms_faq_id',               'question'],
            'slideshow_slides'    => [\App\Models\CmsSlide::class,           'cms_slide_id',             'slide_heading'],
            'builder_blocks'     => [\App\Models\CmsBuilderBlock::class,   'cms_builder_block_id',     'title'],
            'inventory_alerts'      => [\App\Models\ProductInventoryAlert::class, 'product_inventory_alert_id', 'message'],
            'product_fields'        => [\App\Models\ProductField::class,          'product_field_id',           'label'],
            'product_field_options' => [\App\Models\ProductFieldOption::class,    'product_field_option_id',    'option_value'],
            'product_reviews'       => [\App\Models\ProductReview::class,        'product_review_id',          'comments'],
        ];

        $items = collect();
        $labelField = 'id';
        $isVariantType = in_array($this->activeType, ['variant_attributes', 'variant_personalization']);

        if ($isVariantType) {
            // For variant types, show variants that have not yet been translated.
            $translatedVariantIds = \App\Models\ProductVariantTranslation::where('language_id', $this->languageId)
                ->when($this->activeType === 'variant_attributes', fn($q) => $q->whereNotNull('attributes_translated'))
                ->when($this->activeType === 'variant_personalization', fn($q) => $q->whereNotNull('personalization_label')->where('personalization_label', '!=', ''))
                ->pluck('product_variant_id');

            $query = \App\Models\ProductVariant::whereNotIn('id', $translatedVariantIds);

            if ($this->activeType === 'variant_personalization') {
                // Only show variants that have personalization enabled.
                $query->where('personalization_active', 1)
                    ->whereNotNull('personalization_label')
                    ->where('personalization_label', '!=', '');
            } else {
                // Only show variants that have attributes to translate.
                $query->whereNotNull('attributes')
                    ->where('attributes', '!=', '{}')
                    ->where('attributes', '!=', '');
            }

            $items = $query->with('product:id,title')->limit(50)->get(['id', 'sku', 'attributes', 'product_id', 'personalization_label']);
            $labelField = 'sku';
        } elseif ($this->activeType === 'plugins') {
            $labelField = 'name';
            $allPlugins = \App\Models\Plugin::all();
            $untranslatedPlugins = collect();
            foreach ($allPlugins as $p) {
                $fields = $p->getTranslatableFields();
                if (empty($fields)) continue;
                $transCount = \App\Models\PluginSettingTranslation::where('plugin_id', $p->id)
                    ->where('language_id', $this->languageId)
                    ->whereIn('field_name', array_keys($fields))
                    ->whereNotNull('field_value')
                    ->where('field_value', '!=', '')
                    ->count();
                if ($transCount < count($fields)) {
                    $untranslatedPlugins->push($p);
                }
            }
            $items = $untranslatedPlugins;
        } elseif (isset($standardMap[$this->activeType])) {
            [$modelClass, $fk, $lf] = $standardMap[$this->activeType];
            $labelField = $lf;
            $translationClassOverrides = ['CmsTestimonial' => 'App\\Models\\TestimonialTranslation'];
            $translationClass = $translationClassOverrides[class_basename($modelClass)]
                ?? ('App\\Models\\' . class_basename($modelClass) . 'Translation');
            $translatedIds = class_exists($translationClass)
                ? $translationClass::where('language_id', $this->languageId)->pluck($fk)
                : collect();
            $items = $modelClass::whereNotIn('id', $translatedIds)->limit(50)->get(['id', $lf]);
        }

        $allTypes = array_merge(
            ['variant_attributes', 'variant_personalization'],
            array_keys($standardMap),
            ['plugins']
        );

        return view('livewire.admin-language-translations', [
            'items'         => $items,
            'labelField'    => $labelField,
            'typeMap'       => $allTypes,
            'isVariantType' => $isVariantType,
        ]);
    }
}
