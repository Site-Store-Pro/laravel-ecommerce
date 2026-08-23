<?php

use App\Models\Language;
use App\Models\SiteLabel;
use App\Models\SiteLabelSection;
use App\Models\SiteLabelTranslation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $reviewSection = SiteLabelSection::where('slug', 'review')->first();
        $checkoutSection = SiteLabelSection::where('slug', 'checkout')->first();
        $sectionId = $reviewSection ? $reviewSection->id : ($checkoutSection ? $checkoutSection->id : 1);

        $spanish = Language::where('code', 'es')->first();
        $french  = Language::where('code', 'fr')->first();

        $labels = [
            [
                'key'        => 'review.no_payment_required_title',
                'section_id' => $sectionId,
                'desc'       => 'Title banner when order total is $0.00',
                'default'    => 'No Payment Required',
                'es'         => 'No se requiere pago',
                'fr'         => 'Aucun paiement requis',
            ],
            [
                'key'        => 'review.no_payment_required_subtitle',
                'section_id' => $sectionId,
                'desc'       => 'Subtitle when order total is $0.00',
                'default'    => 'The total for this order is $0.00. No payment or billing information is required.',
                'es'         => 'El total de este pedido es $0.00. No se requiere información de pago ni de facturación.',
                'fr'         => 'Le total de cette commande est de 0,00 $. Aucun renseignement de paiement ou de facturation n\'est requis.',
            ],
            [
                'key'        => 'review.free_order_notice',
                'section_id' => $sectionId,
                'desc'       => 'Notice on button when order total is $0.00',
                'default'    => 'Click Place Order to finalize and receive your order confirmation immediately.',
                'es'         => 'Haga clic en Realizar pedido para finalizar y recibir la confirmación de su pedido de inmediato.',
                'fr'         => 'Cliquez sur Passer la commande pour finaliser et recevoir immédiatement la confirmation de votre commande.',
            ],
        ];

        foreach ($labels as $item) {
            $label = SiteLabel::firstOrCreate(
                ['label_key' => $item['key']],
                [
                    'section_id'        => $item['section_id'],
                    'file_name'         => 'System Auto-Registered',
                    'label_description' => $item['desc'],
                    'label_default'     => $item['default'],
                ]
            );

            if ($spanish) {
                SiteLabelTranslation::updateOrCreate(
                    [
                        'site_label_id' => $label->id,
                        'language_id'   => $spanish->id,
                    ],
                    [
                        'label_value'   => $item['es'],
                    ]
                );
            }

            if ($french) {
                SiteLabelTranslation::updateOrCreate(
                    [
                        'site_label_id' => $label->id,
                        'language_id'   => $french->id,
                    ],
                    [
                        'label_value'   => $item['fr'],
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        $keys = [
            'review.no_payment_required_title',
            'review.no_payment_required_subtitle',
            'review.free_order_notice',
        ];

        $labelIds = SiteLabel::whereIn('label_key', $keys)->pluck('id');
        SiteLabelTranslation::whereIn('site_label_id', $labelIds)->delete();
        SiteLabel::whereIn('id', $labelIds)->delete();
    }
};
