<?php

namespace Tests\Feature;

use App\Models\CmsForm;
use App\Models\CmsFormField;
use App\Models\CmsFormFieldTranslation;
use App\Models\CmsFormTranslation;
use App\Models\Language;
use App\Services\LanguageService;
use App\Services\TranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsFormTranslationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_cms_form_and_field_translations_can_be_created(): void
    {
        $spanish = Language::firstOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish', 'native_name' => 'Español', 'is_default' => 0, 'is_active' => 1]
        );

        $form = CmsForm::create([
            'name'                 => 'Contact Us',
            'slug'                 => 'contact-us-test',
            'submit_button_label'  => 'Send Message',
            'confirmation_message' => '<p>Thank you!</p>',
            'is_active'            => 1,
        ]);

        $field = CmsFormField::create([
            'form_id'                => $form->id,
            'type'                   => 'select',
            'label'                  => 'Choose Topic',
            'instructions'           => 'Please pick one topic',
            'is_required'            => 1,
            'required_type'          => 'non_blank',
            'required_error_message' => 'Please select an option.',
            'options'                => ['Support', 'Sales', 'Billing'],
            'sort_order'             => 0,
        ]);

        $formTrans = CmsFormTranslation::create([
            'cms_form_id'          => $form->id,
            'language_id'          => $spanish->id,
            'name'                 => 'Contáctenos',
            'submit_button_label'  => 'Enviar Mensaje',
            'confirmation_message' => '<p>¡Gracias!</p>',
            'translation_status'   => 'ai_translated',
        ]);

        $fieldTrans = CmsFormFieldTranslation::create([
            'cms_form_field_id'      => $field->id,
            'language_id'            => $spanish->id,
            'label'                  => 'Elija el Tema',
            'instructions'           => 'Por favor elija un tema',
            'required_error_message' => 'Por favor seleccione una opción.',
            'options'                => ['Soporte', 'Ventas', 'Facturación'],
            'translation_status'     => 'ai_translated',
        ]);

        $this->assertDatabaseHas('cms_form_translations', [
            'id'                  => $formTrans->id,
            'submit_button_label' => 'Enviar Mensaje',
        ]);

        $this->assertDatabaseHas('cms_form_field_translations', [
            'id'    => $fieldTrans->id,
            'label' => 'Elija el Tema',
        ]);
    }

    public function test_translation_service_reports_form_stats(): void
    {
        $spanish = Language::firstOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish', 'native_name' => 'Español', 'is_default' => 0, 'is_active' => 1]
        );

        $form = CmsForm::create([
            'name'                => 'Quote Request',
            'slug'                => 'quote-request',
            'submit_button_label' => 'Request Quote',
            'is_active'           => 1,
        ]);

        $service = app(TranslationService::class);
        $stats = $service->translationStats(CmsForm::class, $spanish->id);

        $this->assertArrayHasKey('total', $stats);
        $this->assertArrayHasKey('translated', $stats);
        $this->assertArrayHasKey('pending', $stats);
        $this->assertGreaterThanOrEqual(1, $stats['total']);
    }

    public function test_shortcode_processor_renders_translated_form(): void
    {
        $spanish = Language::firstOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish', 'native_name' => 'Español', 'is_default' => 0, 'is_active' => 1]
        );

        $form = CmsForm::create([
            'name'                 => 'Feedback Form',
            'slug'                 => 'feedback-test',
            'submit_button_label'  => 'Submit Feedback',
            'confirmation_message' => '<p>Thank you for your feedback!</p>',
            'is_active'            => 1,
        ]);

        $field = CmsFormField::create([
            'form_id'                => $form->id,
            'type'                   => 'input',
            'label'                  => 'Your Feedback',
            'instructions'           => 'Tell us what you think',
            'is_required'            => 1,
            'required_type'          => 'non_blank',
            'required_error_message' => 'Please enter feedback.',
            'sort_order'             => 0,
        ]);

        CmsFormTranslation::create([
            'cms_form_id'          => $form->id,
            'language_id'          => $spanish->id,
            'name'                 => 'Formulario de Comentarios',
            'submit_button_label'  => 'Enviar Comentarios',
            'confirmation_message' => '<p>¡Gracias por sus comentarios!</p>',
            'translation_status'   => 'ai_translated',
        ]);

        CmsFormFieldTranslation::create([
            'cms_form_field_id'      => $field->id,
            'language_id'            => $spanish->id,
            'label'                  => 'Sus Comentarios',
            'instructions'           => 'Díganos lo que piensa',
            'required_error_message' => 'Por favor ingrese comentarios.',
            'translation_status'     => 'ai_translated',
        ]);

        // Switch to Spanish
        app(LanguageService::class)->setLanguage($spanish->code);

        $processor = app(\App\Plugins\Support\ShortcodeProcessor::class);
        $html = $processor->process("[cms-form id={$form->id}]");

        $this->assertStringContainsString('Enviar Comentarios', $html);
        $this->assertStringContainsString('Sus Comentarios', $html);
        $this->assertStringContainsString('Díganos lo que piensa', $html);
    }
}
