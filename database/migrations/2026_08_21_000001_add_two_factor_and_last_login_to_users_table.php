<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add last_login_at to users table if not exists
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('last_login_at')->nullable()->after('remember_token');
            });
        }

        // 2. Insert Email Template Type for Two-Factor Verification Code
        if (Schema::hasTable('email_template_types')) {
            $existingType = DB::table('email_template_types')
                ->where('slug', 'two_factor_verification')
                ->first();

            if (!$existingType) {
                $typeId = DB::table('email_template_types')->insertGetId([
                    'name'       => 'Two-Factor Verification Code',
                    'slug'       => 'two_factor_verification',
                    'ordering'   => 15,
                    'is_active'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $typeId = $existingType->id;
            }

            // 3. Insert Default Email Template for Two-Factor Verification
            if (Schema::hasTable('email_templates')) {
                $existingTemplate = DB::table('email_templates')
                    ->where('email_type_id', $typeId)
                    ->first();

                if (!$existingTemplate) {
                    $templateId = DB::table('email_templates')->insertGetId([
                        'email_type_id'      => $typeId,
                        'profile_name'       => 'Default Two-Factor Verification',
                        'from_address'       => 'security@support.local',
                        'from_name'          => 'Security Team',
                        'bcc_address'        => null,
                        'subject'            => '{{verification_code}} is your verification code for {{site_name}}',
                        'header_html'        => null,
                        'banner_image_url'   => null,
                        'banner_image_link'  => null,
                        'show_banner'        => 0,
                        'salutation'         => 'Hello {{customer_name}},',
                        'include_salutation' => 1,
                        'greeting'           => '<p>A security verification code was requested for your account on <strong>{{site_name}}</strong>.</p>',
                        'body'               => '<div style="text-align: center; margin: 25px 0;"><div style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4f46e5; background: #eef2ff; border: 1px dashed #6366f1; padding: 12px 24px; border-radius: 8px;">{{verification_code}}</div></div><p style="text-align: center; color: #64748b; font-size: 13px;">This code will expire in {{expires_in_minutes}} minutes. If you did not make this request, you can safely ignore this email.</p>',
                        'sign_off'           => 'Sincerely,',
                        'signature'          => 'The Security Team',
                        'disclaimer'         => 'Never share this verification code with anyone. Our support team will never ask for your code.',
                        'copyright'          => 'Copyright ' . date('Y'),
                        'footer_image_url'   => null,
                        'footer_image_link'  => null,
                        'show_footer_image'  => 0,
                        'footer_html'        => null,
                        'is_active'          => 1,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                    // Seed Spanish (language_id = 2) and French (language_id = 3) translations if languages exist
                    if (Schema::hasTable('email_template_translations')) {
                        // Spanish
                        DB::table('email_template_translations')->insertOrIgnore([
                            'email_template_id'  => $templateId,
                            'language_id'        => 2,
                            'subject'            => '{{verification_code}} es su código de verificación para {{site_name}}',
                            'salutation'         => 'Hola {{customer_name}},',
                            'greeting'           => '<p>Se ha solicitado un código de verificación de seguridad para su cuenta en <strong>{{site_name}}</strong>.</p>',
                            'body'               => '<div style="text-align: center; margin: 25px 0;"><div style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4f46e5; background: #eef2ff; border: 1px dashed #6366f1; padding: 12px 24px; border-radius: 8px;">{{verification_code}}</div></div><p style="text-align: center; color: #64748b; font-size: 13px;">Este código caducará en {{expires_in_minutes}} minutos. Si no realizó esta solicitud, puede ignorar este correo electrónico.</p>',
                            'sign_off'           => 'Atentamente,',
                            'signature'          => 'El Equipo de Seguridad',
                            'disclaimer'         => 'Nunca comparta este código de verificación con nadie.',
                            'copyright'          => 'Derechos de autor ' . date('Y'),
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ]);

                        // French
                        DB::table('email_template_translations')->insertOrIgnore([
                            'email_template_id'  => $templateId,
                            'language_id'        => 3,
                            'subject'            => '{{verification_code}} est votre code de vérification pour {{site_name}}',
                            'salutation'         => 'Bonjour {{customer_name}},',
                            'greeting'           => '<p>Un code de vérification de sécurité a été demandé pour votre compte sur <strong>{{site_name}}</strong>.</p>',
                            'body'               => '<div style="text-align: center; margin: 25px 0;"><div style="display: inline-block; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #4f46e5; background: #eef2ff; border: 1px dashed #6366f1; padding: 12px 24px; border-radius: 8px;">{{verification_code}}</div></div><p style="text-align: center; color: #64748b; font-size: 13px;">Ce code expirera dans {{expires_in_minutes}} minutes. Si vous n\'avez pas fait cette demande, vous pouvez ignorer cet e-mail.</p>',
                            'sign_off'           => 'Cordialement,',
                            'signature'          => 'L\'équipe de Sécurité',
                            'disclaimer'         => 'Ne partagez jamais ce code de vérification avec quiconque.',
                            'copyright'          => 'Droit d\'auteur ' . date('Y'),
                            'created_at'         => now(),
                            'updated_at'         => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'last_login_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('last_login_at');
            });
        }

        $type = DB::table('email_template_types')->where('slug', 'two_factor_verification')->first();
        if ($type) {
            DB::table('email_templates')->where('email_type_id', $type->id)->delete();
            DB::table('email_template_types')->where('id', $type->id)->delete();
        }
    }
};
