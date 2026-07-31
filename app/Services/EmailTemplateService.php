<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailTemplateService
{
    public static function getActiveTemplate(string $slug, ?int $languageId = null): ?EmailTemplate
    {
        $type = EmailTemplateType::where('slug', $slug)->first();
        if (!$type) {
            return null;
        }

        $langService = app(LanguageService::class);
        $langId    = $languageId ?? $langService->currentId();
        $defaultId = $langService->defaultId();
        $ids       = array_unique([$langId, $defaultId]);

        return $type->templates()
            ->where('is_active', true)
            ->with(['translations' => fn ($q) => $q->whereIn('language_id', $ids)])
            ->first();
    }

    public static function replaceVariables(?string $text, array $vars): string
    {
        if (is_null($text)) {
            return '';
        }

        foreach ($vars as $key => $val) {
            $text = str_replace(["{{{$key}}}", "{{ {$key} }}"], (string)$val, $text);
        }

        return $text;
    }

    public static function renderSubject(EmailTemplate $tpl, array $vars, ?int $languageId = null): string
    {
        return self::replaceVariables($tpl->getTranslated('subject', $languageId), $vars);
    }

    public static function renderBody(EmailTemplate $tpl, array $vars, ?int $languageId = null): string
    {
        $bodyText = $tpl->getTranslated('body', $languageId);
        $slug = $tpl->type ? $tpl->type->slug : '';
        if (in_array($slug, ['order_confirmation', 'order_shipment', 'download_reminder']) && !str_contains($bodyText, 'order_items_table')) {
            $bodyText .= '<p>{{order_items_table}}</p>';
        }

        $data = [
            'subject' => self::renderSubject($tpl, $vars, $languageId),
            'header_html' => self::replaceVariables($tpl->getTranslated('header_html', $languageId), $vars),
            'banner_image_url' => $tpl->banner_image_url,
            'banner_image_link' => $tpl->banner_image_link,
            'show_banner' => $tpl->show_banner,
            'salutation' => self::replaceVariables($tpl->getTranslated('salutation', $languageId), $vars),
            'include_salutation' => $tpl->include_salutation,
            'greeting' => self::replaceVariables($tpl->getTranslated('greeting', $languageId), $vars),
            'body' => self::replaceVariables($bodyText, $vars),
            'sign_off' => self::replaceVariables($tpl->getTranslated('sign_off', $languageId), $vars),
            'signature' => self::replaceVariables($tpl->getTranslated('signature', $languageId), $vars),
            'disclaimer' => self::replaceVariables($tpl->getTranslated('disclaimer', $languageId), $vars),
            'copyright' => self::replaceVariables($tpl->getTranslated('copyright', $languageId), $vars),
            'footer_image_url' => $tpl->footer_image_url,
            'footer_image_link' => $tpl->footer_image_link,
            'show_footer_image' => $tpl->show_footer_image,
            'footer_html' => self::replaceVariables($tpl->getTranslated('footer_html', $languageId), $vars),
        ];

        return view('emails.dynamic.template', $data)->render();
    }

    public static function sendEmail(string $slug, string $toEmail, string $toName, array $vars, ?int $languageId = null): bool
    {
        $tpl = self::getActiveTemplate($slug, $languageId);
        if (!$tpl) {
            Log::warning("Dynamic email template with slug '{$slug}' requested but no active template was found.");
            return false;
        }

        try {
            Mail::to($toEmail, $toName)->send(new \App\Mail\DynamicTemplateMail($tpl, $vars, $languageId));
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send dynamic email '{$slug}': " . $e->getMessage());
            return false;
        }
    }
}
