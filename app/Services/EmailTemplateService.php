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
        if ($appUrl = config('app.url')) {
            \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        }

        $bodyText = $tpl->getTranslated('body', $languageId);
        $slug = $tpl->type ? $tpl->type->slug : '';
        if (in_array($slug, ['order_confirmation', 'order_shipment', 'download_reminder']) && !str_contains($bodyText, 'order_items_table')) {
            $bodyText .= '<p>{{order_items_table}}</p>';
        }
        if (in_array($slug, ['abandoned_cart_reminder_1', 'abandoned_cart_reminder_2']) && !str_contains($bodyText, 'cart_items_table') && !str_contains($bodyText, 'order_items_table')) {
            $bodyText .= '<p>{{cart_items_table}}</p>';
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
        if ($appUrl = config('app.url')) {
            \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        }

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

    public static function renderCartItemsHtml(string $cartLogSession): string
    {
        $items = \App\Models\ShoppingCartLog::where('cart_log_session', $cartLogSession)
            ->where('order_id', 0)
            ->get();

        if ($items->isEmpty()) {
            return '';
        }

        $html = '<div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 24px;">';
        $html .= '<h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 0; margin-bottom: 16px; letter-spacing: 0.5px;">' . e(siteLabel('email.your_cart_items', 'YOUR CART ITEMS')) . '</h3>';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0">';

        foreach ($items as $item) {
            $attributesText = '';
            if (!empty($item->item_attributes)) {
                $attrs = is_string($item->item_attributes) ? json_decode($item->item_attributes, true) : $item->item_attributes;
                if (is_array($attrs) && !empty($attrs)) {
                    $pairs = [];
                    foreach ($attrs as $k => $v) {
                        if (is_scalar($v)) {
                            $pairs[] = e(ucfirst($k)) . ': ' . e($v);
                        }
                    }
                    $attributesText = implode(', ', $pairs);
                }
            }

            $price = (float)$item->item_price;
            $qty   = (float)$item->item_qty;
            $lineTotal = $price * $qty;

            $html .= '<tr style="border-bottom: 1px solid #f1f5f9;">';
            $html .= '<td style="padding: 12px 0; vertical-align: top;">';
            $html .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . e($item->item_name) . '</strong>';
            if (!empty($attributesText)) {
                $html .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">' . $attributesText . '</span>';
            }
            $html .= '<span style="color: #64748b; font-size: 12px; display: block; margin-top: 2px;">Qty: ' . (int)$qty . ' &times; ' . \App\Services\CurrencyService::format($price) . '</span>';
            $html .= '</td>';
            $html .= '<td style="padding: 12px 0; vertical-align: top;" align="right">';
            $html .= '<strong style="color: #0f172a; font-size: 14px; display: block;">' . \App\Services\CurrencyService::format($lineTotal) . '</strong>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}
