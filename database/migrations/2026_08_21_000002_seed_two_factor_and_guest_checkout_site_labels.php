<?php

use App\Models\Language;
use App\Models\SiteLabel;
use App\Models\SiteLabelSection;
use App\Models\SiteLabelTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $checkoutSection = SiteLabelSection::where('slug', 'checkout')->first();
        $checkoutSectionId = $checkoutSection ? $checkoutSection->id : 1;

        $authSection = SiteLabelSection::where('slug', 'auth')->first();
        $authSectionId = $authSection ? $authSection->id : $checkoutSectionId;

        $spanish = Language::where('code', 'es')->first();
        $french  = Language::where('code', 'fr')->first();

        $labels = [
            // Checkout Guest Password requirement labels
            [
                'key'        => 'checkout.field_password_required',
                'section_id' => $checkoutSectionId,
                'desc'       => 'Label for password field when guest checkout is disabled',
                'default'    => 'Create an Account Password',
                'es'         => 'Crear una contraseña de cuenta',
                'fr'         => 'Créer un mot de passe de compte',
            ],
            [
                'key'        => 'checkout.field_password_required_message',
                'section_id' => $checkoutSectionId,
                'desc'       => 'Helper message when guest checkout is disabled',
                'default'    => 'An account password is required to complete your order and track your purchases.',
                'es'         => 'Se requiere una contraseña de cuenta para completar su pedido y realizar el seguimiento de sus compras.',
                'fr'         => 'Un mot de passe de compte est requis pour finaliser votre commande et suivre vos achats.',
            ],
            // 2FA Labels
            [
                'key'        => 'two_factor.checkout_heading',
                'section_id' => $checkoutSectionId,
                'desc'       => 'Heading on 2FA form during checkout',
                'default'    => 'Verify Your Purchase',
                'es'         => 'Verifique su compra',
                'fr'         => 'Vérifiez votre achat',
            ],
            [
                'key'        => 'two_factor.login_heading',
                'section_id' => $authSectionId,
                'desc'       => 'Heading on 2FA form during login',
                'default'    => 'Two-Factor Verification',
                'es'         => 'Verificación de dos factores',
                'fr'         => 'Vérification à deux facteurs',
            ],
            [
                'key'        => 'two_factor.subheading',
                'section_id' => $authSectionId,
                'desc'       => 'Subheading on 2FA verification form',
                'default'    => 'We sent a 6-digit security verification code to',
                'es'         => 'Enviamos un código de verificación de seguridad de 6 dígitos a',
                'fr'         => 'Nous avons envoyé un code de vérification de sécurité à 6 chiffres à',
            ],
            [
                'key'        => 'two_factor.code_label',
                'section_id' => $authSectionId,
                'desc'       => 'Input label for 6-digit verification code',
                'default'    => 'Enter 6-Digit Code',
                'es'         => 'Ingrese el código de 6 dígitos',
                'fr'         => 'Entrez le code à 6 chiffres',
            ],
            [
                'key'        => 'two_factor.continue_checkout_btn',
                'section_id' => $checkoutSectionId,
                'desc'       => 'Submit button for checkout 2FA',
                'default'    => 'Verify & Continue to Payment →',
                'es'         => 'Verificar y continuar al pago →',
                'fr'         => 'Vérifier et continuer vers le paiement →',
            ],
            [
                'key'        => 'two_factor.verify_login_btn',
                'section_id' => $authSectionId,
                'desc'       => 'Submit button for login 2FA',
                'default'    => 'Verify & Sign In →',
                'es'         => 'Verificar e iniciar sesión →',
                'fr'         => 'Vérifier et se connecter →',
            ],
            [
                'key'        => 'two_factor.verifying',
                'section_id' => $authSectionId,
                'desc'       => 'Loading text during verification',
                'default'    => 'Verifying code…',
                'es'         => 'Verificando código…',
                'fr'         => 'Vérification du code…',
            ],
            [
                'key'        => 'two_factor.did_not_receive',
                'section_id' => $authSectionId,
                'desc'       => 'Prompt asking if code was received',
                'default'    => 'Didn\'t receive the code?',
                'es'         => '¿No recibió el código?',
                'fr'         => 'Vous n\'avez pas reçu le code ?',
            ],
            [
                'key'        => 'two_factor.resend_in',
                'section_id' => $authSectionId,
                'desc'       => 'Cooldown prefix text before seconds countdown',
                'default'    => 'Resend code in',
                'es'         => 'Reenviar código en',
                'fr'         => 'Renvoyer le code dans',
            ],
            [
                'key'        => 'two_factor.resend_button',
                'section_id' => $authSectionId,
                'desc'       => 'Link text to resend verification code',
                'default'    => 'Click here to resend code',
                'es'         => 'Haga clic aquí para reenviar el código',
                'fr'         => 'Cliquez ici pour renvoyer le code',
            ],
            [
                'key'        => 'two_factor.sending_code',
                'section_id' => $authSectionId,
                'desc'       => 'Loading text when resending code',
                'default'    => 'Sending new code…',
                'es'         => 'Enviando nuevo código…',
                'fr'         => 'Envoi du nouveau code…',
            ],
            [
                'key'        => 'two_factor.return_to_checkout',
                'section_id' => $checkoutSectionId,
                'desc'       => 'Back link returning to checkout',
                'default'    => 'Return to Checkout',
                'es'         => 'Volver a finalizar compra',
                'fr'         => 'Retour à la commande',
            ],
            [
                'key'        => 'two_factor.return_to_login',
                'section_id' => $authSectionId,
                'desc'       => 'Back link returning to login',
                'default'    => 'Back to Sign In',
                'es'         => 'Volver a iniciar sesión',
                'fr'         => 'Retour à la connexion',
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
            'checkout.field_password_required',
            'checkout.field_password_required_message',
            'two_factor.checkout_heading',
            'two_factor.login_heading',
            'two_factor.subheading',
            'two_factor.code_label',
            'two_factor.continue_checkout_btn',
            'two_factor.verify_login_btn',
            'two_factor.verifying',
            'two_factor.did_not_receive',
            'two_factor.resend_in',
            'two_factor.resend_button',
            'two_factor.sending_code',
            'two_factor.return_to_checkout',
            'two_factor.return_to_login',
        ];

        $labelIds = SiteLabel::whereIn('label_key', $keys)->pluck('id');
        SiteLabelTranslation::whereIn('site_label_id', $labelIds)->delete();
        SiteLabel::whereIn('id', $labelIds)->delete();
    }
};
