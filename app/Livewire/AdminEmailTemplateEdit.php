<?php

namespace App\Livewire;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateType;
use App\Services\EmailTemplateService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class AdminEmailTemplateEdit extends Component
{
    use WithFileUploads;

    public ?int $templateId = null;
    public ?EmailTemplate $template = null;

    // Form fields
    public int $email_type_id = 1;
    public string $profile_name = '';
    public ?string $from_address = null;
    public ?string $from_name = null;
    public ?string $bcc_address = null;
    public string $subject = '';
    public ?string $header_html = null;
    public ?string $banner_image_url = null;
    public ?string $banner_image_link = null;
    public bool $show_banner = false;
    public ?string $salutation = null;
    public bool $include_salutation = false;
    public ?string $greeting = null;
    public ?string $body = null;
    public ?string $sign_off = null;
    public ?string $signature = null;
    public ?string $disclaimer = null;
    public ?string $copyright = null;
    public ?string $footer_image_url = null;
    public ?string $footer_image_link = null;
    public bool $show_footer_image = false;
    public ?string $footer_html = null;
    public bool $is_active = false;

    // Upload state: Banner
    public string $banner_upload_mode = 'url'; // 'url', 'local', 's3', 'custom_s3'
    public $banner_file = null;
    public string $banner_custom_s3_key = '';
    public string $banner_custom_s3_secret = '';
    public string $banner_custom_s3_region = 'us-east-1';
    public string $banner_custom_s3_bucket = '';
    public string $banner_custom_s3_cloudfront = '';
    public string $banner_custom_s3_endpoint = '';

    // Upload state: Footer
    public string $footer_upload_mode = 'url'; // 'url', 'local', 's3', 'custom_s3'
    public $footer_file = null;
    public string $footer_custom_s3_key = '';
    public string $footer_custom_s3_secret = '';
    public string $footer_custom_s3_region = 'us-east-1';
    public string $footer_custom_s3_bucket = '';
    public string $footer_custom_s3_cloudfront = '';
    public string $footer_custom_s3_endpoint = '';

    // Preview
    public bool $showPreviewModal = false;
    public string $previewHtml = '';

    // Translation panel properties
    public ?int $editingLanguageId = null;  // null = default language (English)
    public array $translationData = [];     // [language_id => [field => value, ...]]
    public bool $isTranslating = false;     // AI translation in progress

    protected function rules(): array
    {
        return [
            'email_type_id' => 'required|exists:email_template_types,id',
            'profile_name' => 'required|string|max:255',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'bcc_address' => 'nullable|string|max:255',
            'subject' => 'required|string',
            'header_html' => 'nullable|string',
            'banner_image_url' => 'nullable|string|max:2000',
            'banner_image_link' => 'nullable|string|max:2000',
            'show_banner' => 'boolean',
            'salutation' => 'nullable|string',
            'include_salutation' => 'boolean',
            'greeting' => 'nullable|string',
            'body' => 'nullable|string',
            'sign_off' => 'nullable|string',
            'signature' => 'nullable|string',
            'disclaimer' => 'nullable|string',
            'copyright' => 'nullable|string',
            'footer_image_url' => 'nullable|string|max:2000',
            'footer_image_link' => 'nullable|string|max:2000',
            'show_footer_image' => 'boolean',
            'footer_html' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function mount(?int $id = null, ?int $type_id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        if ($id) {
            $this->templateId = $id;
            $this->template = EmailTemplate::with('translations')->findOrFail($id);
            $this->editingLanguageId = null;

            // Fill form fields
            $this->email_type_id = $this->template->email_type_id;
            $this->profile_name = $this->template->profile_name;
            $this->from_address = $this->template->from_address;
            $this->from_name = $this->template->from_name;
            $this->bcc_address = $this->template->bcc_address;
            $this->subject = $this->template->subject;
            $this->header_html = $this->template->header_html;
            $this->banner_image_url = $this->template->banner_image_url;
            $this->banner_image_link = $this->template->banner_image_link;
            $this->show_banner = $this->template->show_banner;
            $this->salutation = $this->template->salutation;
            $this->include_salutation = $this->template->include_salutation;
            $this->greeting = $this->template->greeting;
            $this->body = $this->template->body;
            $this->sign_off = $this->template->sign_off;
            $this->signature = $this->template->signature;
            $this->disclaimer = $this->template->disclaimer;
            $this->copyright = $this->template->copyright;
            $this->footer_image_url = $this->template->footer_image_url;
            $this->footer_image_link = $this->template->footer_image_link;
            $this->show_footer_image = $this->template->show_footer_image;
            $this->footer_html = $this->template->footer_html;
            $this->is_active = $this->template->is_active;
        } elseif ($type_id) {
            $this->email_type_id = $type_id;
        }
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->is_active) {
            // Deactivate all other templates of this type
            EmailTemplate::where('email_type_id', $this->email_type_id)
                ->when($this->templateId, function($q) {
                    $q->where('id', '!=', $this->templateId);
                })
                ->update(['is_active' => false]);
        } else {
            // If this is the only template of this type, it MUST be active
            $siblingCount = EmailTemplate::where('email_type_id', $this->email_type_id)
                ->when($this->templateId, function($q) {
                    $q->where('id', '!=', $this->templateId);
                })
                ->count();
            if ($siblingCount === 0) {
                $data['is_active'] = true;
                $this->is_active = true;
            }
        }

        if ($this->templateId) {
            $this->template->update($data);
            session()->flash('status', 'Email template updated successfully.');
        } else {
            EmailTemplate::create($data);
            session()->flash('status', 'Email template created successfully.');
        }

        return redirect()->route('admin.email-templates.index');
    }

    public function setEditingLanguage(?int $languageId): void
    {
        $this->editingLanguageId = $languageId;

        if ($languageId !== null && $this->template) {
            $translation = $this->template->translations
                ->firstWhere('language_id', $languageId);

            $this->translationData[$languageId] = [
                'subject'     => $translation?->subject ?? '',
                'header_html' => $translation?->header_html ?? '',
                'salutation'  => $translation?->salutation ?? '',
                'greeting'    => $translation?->greeting ?? '',
                'body'        => $translation?->body ?? '',
                'sign_off'    => $translation?->sign_off ?? '',
                'signature'   => $translation?->signature ?? '',
                'disclaimer'  => $translation?->disclaimer ?? '',
                'copyright'   => $translation?->copyright ?? '',
                'footer_html' => $translation?->footer_html ?? '',
            ];
        }
    }

    public function saveTranslation(): void
    {
        $languageId = $this->editingLanguageId;
        if (!$languageId || !$this->template) {
            return;
        }

        $data = $this->translationData[$languageId] ?? [];

        \App\Models\EmailTemplateTranslation::updateOrCreate(
            [
                'email_template_id' => $this->template->id,
                'language_id'       => $languageId,
            ],
            array_merge($data, [
                'email_template_id'  => $this->template->id,
                'language_id'        => $languageId,
                'translation_status' => 'reviewed',
                'translated_at'      => now(),
            ])
        );

        $this->template->load('translations');
        $this->dispatch('toast', message: 'Translation saved.', type: 'success');
    }

    public function aiTranslateEmail(): void
    {
        $languageId = $this->editingLanguageId;
        if (!$languageId || !$this->template) {
            return;
        }

        $this->isTranslating = true;

        try {
            $language = \App\Models\Language::find($languageId);
            if (!$language) {
                $this->dispatch('toast', message: 'Language not found.', type: 'error');
                return;
            }

            $service = app(\App\Services\TranslationService::class);
            $service->translateRecord($this->template, $language);

            $this->template->load('translations');
            $this->setEditingLanguage($languageId);

            $this->dispatch('toast', message: 'AI translation completed for ' . $language->name . '.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'AI translation failed: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isTranslating = false;
        }
    }

    public function setBannerUploadMode(string $mode): void
    {
        $this->banner_upload_mode = in_array($mode, ['url', 'local', 's3', 'custom_s3']) ? $mode : 'url';
    }

    public function setFooterUploadMode(string $mode): void
    {
        $this->footer_upload_mode = in_array($mode, ['url', 'local', 's3', 'custom_s3']) ? $mode : 'url';
    }

    public function uploadBanner(): void
    {
        $this->validate([
            'banner_file' => 'required|image|max:10240',
        ]);

        try {
            $customConfig = [
                'key'        => $this->banner_custom_s3_key,
                'secret'     => $this->banner_custom_s3_secret,
                'region'     => $this->banner_custom_s3_region ?: 'us-east-1',
                'bucket'     => $this->banner_custom_s3_bucket,
                'cloudfront' => $this->banner_custom_s3_cloudfront,
                'endpoint'   => $this->banner_custom_s3_endpoint,
            ];

            $url = EmailTemplateService::processImageUpload(
                $this->banner_file,
                $this->banner_upload_mode,
                $customConfig,
                'email_templates/banners'
            );

            $this->banner_image_url = $url;
            $this->banner_file = null;
            $this->dispatch('toast', message: 'Banner image uploaded successfully.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'Banner upload failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function clearBannerImage(): void
    {
        $this->banner_image_url = null;
        $this->banner_file = null;
        $this->dispatch('toast', message: 'Banner image cleared.', type: 'success');
    }

    public function uploadFooterImage(): void
    {
        $this->validate([
            'footer_file' => 'required|image|max:10240',
        ]);

        try {
            $customConfig = [
                'key'        => $this->footer_custom_s3_key,
                'secret'     => $this->footer_custom_s3_secret,
                'region'     => $this->footer_custom_s3_region ?: 'us-east-1',
                'bucket'     => $this->footer_custom_s3_bucket,
                'cloudfront' => $this->footer_custom_s3_cloudfront,
                'endpoint'   => $this->footer_custom_s3_endpoint,
            ];

            $url = EmailTemplateService::processImageUpload(
                $this->footer_file,
                $this->footer_upload_mode,
                $customConfig,
                'email_templates/footers'
            );

            $this->footer_image_url = $url;
            $this->footer_file = null;
            $this->dispatch('toast', message: 'Footer image uploaded successfully.', type: 'success');
        } catch (\Throwable $e) {
            $this->dispatch('toast', message: 'Footer image upload failed: ' . $e->getMessage(), type: 'error');
        }
    }

    public function clearFooterImage(): void
    {
        $this->footer_image_url = null;
        $this->footer_file = null;
        $this->dispatch('toast', message: 'Footer image cleared.', type: 'success');
    }

    public function generatePreview(): void
    {
        $mockTemplate = new EmailTemplate([
            'email_type_id' => $this->email_type_id,
            'profile_name' => $this->profile_name,
            'from_address' => $this->from_address,
            'from_name' => $this->from_name,
            'bcc_address' => $this->bcc_address,
            'subject' => $this->subject,
            'header_html' => $this->header_html,
            'banner_image_url' => $this->banner_image_url,
            'banner_image_link' => $this->banner_image_link,
            'show_banner' => $this->show_banner,
            'salutation' => $this->salutation,
            'include_salutation' => $this->include_salutation,
            'greeting' => $this->greeting,
            'body' => $this->body,
            'sign_off' => $this->sign_off,
            'signature' => $this->signature,
            'disclaimer' => $this->disclaimer,
            'copyright' => $this->copyright,
            'footer_image_url' => $this->footer_image_url,
            'footer_image_link' => $this->footer_image_link,
            'show_footer_image' => $this->show_footer_image,
            'footer_html' => $this->footer_html,
        ]);

        $mockVars = [
            'order_id' => '100245',
            'customer_name' => 'John Doe',
            'order_total' => '$150.00',
            'order_subtotal' => '$138.56',
            'order_taxes' => '$11.44',
            'order_shipping' => '$0.00',
            'order_items_table' => '<table width="100%" style="border-collapse: collapse; font-size: 14px; margin-top: 15px;"><tr style="border-bottom: 2px solid #e2e8f0; font-weight: bold;"><th align="left" style="padding: 8px 0;">Item Name</th><th align="center" style="padding: 8px 0;">Qty</th><th align="right" style="padding: 8px 0;">Price</th></tr><tr><td style="padding: 8px 0;">Premium Leather Backpack (BP-01)</td><td align="center" style="padding: 8px 0;">1</td><td align="right" style="padding: 8px 0;">$150.00</td></tr></table>',
            'cart_items_table' => '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;"><h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 0; margin-bottom: 16px; letter-spacing: 0.5px;">YOUR CART ITEMS</h3><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr style="border-bottom: 1px solid #f1f5f9;"><td style="padding: 12px 0; vertical-align: top;"><strong style="color: #0f172a; font-size: 14px; display: block;">Premium Leather Backpack (BP-01)</strong><span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Color: Black</span><span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Qty: 1 &times; $150.00</span></td><td style="padding: 12px 0; vertical-align: top;" align="right"><strong style="color: #0f172a; font-size: 14px; display: block;">$150.00</strong></td></tr></table></div>',
            'checkout_url' => route('shop.cart'),
            'tracking_number' => '1Z999AA10123456784',
            'download_links' => '<a href="#" style="color: #4f46e5; text-decoration: underline;">Download Link 1</a>',
            'activation_url' => 'https://example.com/activate/token123',
            'reset_url' => 'https://example.com/password/reset/token123',
            'ticket_title' => 'Unable to complete checkout',
            'ticket_status' => 'Open',
            'ticket_url' => 'https://example.com/tickets/abc-123',
            'reply_author' => 'Agent Sarah',
            'reply_body' => 'I have reviewed your account and updated your billing details. Please try checking out again.',
            'previous_status' => 'Pending Info',
            'app_name' => config('app.name'),
            'year' => date('Y'),
        ];

        $this->previewHtml = EmailTemplateService::renderBody($mockTemplate, $mockVars);
        $this->showPreviewModal = true;
    }

    public function render(): View
    {
        $types = EmailTemplateType::orderBy('ordering')->get();
        $selectedType = EmailTemplateType::find($this->email_type_id);

        return view('livewire.admin-email-template-edit', [
            'types' => $types,
            'selectedType' => $selectedType,
        ]);
    }
}
