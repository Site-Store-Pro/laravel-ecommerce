<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateType;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailTemplateService
{
    public static function getActiveTemplate(string $slug): ?EmailTemplate
    {
        $type = EmailTemplateType::where('slug', $slug)->first();
        if (!$type) {
            return null;
        }
        return $type->activeTemplate();
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

    public static function renderSubject(EmailTemplate $tpl, array $vars): string
    {
        return self::replaceVariables($tpl->subject, $vars);
    }

    public static function renderBody(EmailTemplate $tpl, array $vars): string
    {
        $bodyText = $tpl->body;
        $slug = $tpl->type ? $tpl->type->slug : '';
        if (in_array($slug, ['order_confirmation', 'order_shipment', 'download_reminder']) && !str_contains($bodyText, 'order_items_table')) {
            $bodyText .= '<p>{{order_items_table}}</p>';
        }

        $data = [
            'subject' => self::renderSubject($tpl, $vars),
            'header_html' => self::replaceVariables($tpl->header_html, $vars),
            'banner_image_url' => $tpl->banner_image_url,
            'banner_image_link' => $tpl->banner_image_link,
            'show_banner' => $tpl->show_banner,
            'salutation' => self::replaceVariables($tpl->salutation, $vars),
            'include_salutation' => $tpl->include_salutation,
            'greeting' => self::replaceVariables($tpl->greeting, $vars),
            'body' => self::replaceVariables($bodyText, $vars),
            'sign_off' => self::replaceVariables($tpl->sign_off, $vars),
            'signature' => self::replaceVariables($tpl->signature, $vars),
            'disclaimer' => self::replaceVariables($tpl->disclaimer, $vars),
            'copyright' => self::replaceVariables($tpl->copyright, $vars),
            'footer_image_url' => $tpl->footer_image_url,
            'footer_image_link' => $tpl->footer_image_link,
            'show_footer_image' => $tpl->show_footer_image,
            'footer_html' => self::replaceVariables($tpl->footer_html, $vars),
        ];

        return view('emails.dynamic.template', $data)->render();
    }

    public static function sendEmail(string $slug, string $toEmail, string $toName, array $vars): bool
    {
        $tpl = self::getActiveTemplate($slug);
        if (!$tpl) {
            Log::warning("Dynamic email template with slug '{$slug}' requested but no active template was found.");
            return false;
        }

        try {
            Mail::to($toEmail, $toName)->send(new \App\Mail\DynamicTemplateMail($tpl, $vars));
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send dynamic email '{$slug}': " . $e->getMessage());
            return false;
        }
    }
}
