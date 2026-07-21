<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Seeds two sample CMS forms:
 *  1. Contact Us — name, email, comments, how heard; auto opt-in enabled
 *  2. Email Subscribe — name, email; auto opt-in enabled
 *
 * Embed using: [cms-form id=1] and [cms-form id=2]
 */
class CmsFormSeeder extends Seeder
{
    public function run(): void
    {
        // ── Form 1: Contact Us ────────────────────────────────────────────────
        $contactFormId = DB::table('cms_forms')->insertGetId([
            'name'                 => 'Contact Us',
            'slug'                 => 'contact-us',
            'submit_button_label'  => 'Send Message',
            'confirmation_message' => '<p><strong>Thank you for reaching out!</strong> We\'ll get back to you within one business day.</p>',
            'redirect_url'         => null,
            'email_to'             => null, // set to an address in admin to receive notifications
            'email_subject'        => 'New Contact Form Submission',
            'auto_optin'           => true,
            'optin_provider'       => null, // configure provider + list ID in admin when ready
            'optin_list_id'        => null,
            'custom_css'           => null,
            'is_active'            => true,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        $contactFields = [
            [
                'form_id'                => $contactFormId,
                'type'                   => 'input',
                'label'                  => 'Your Name',
                'instructions'           => 'Please enter your full name.',
                'is_required'            => true,
                'required_type'          => 'non_blank',
                'required_error_message' => 'Please enter your name.',
                'html_above'             => null,
                'options'                => null,
                'sort_order'             => 0,
                'field_role'             => 'name',
            ],
            [
                'form_id'                => $contactFormId,
                'type'                   => 'input',
                'label'                  => 'Email Address',
                'instructions'           => 'We\'ll use this to reply to you.',
                'is_required'            => true,
                'required_type'          => 'email',
                'required_error_message' => 'Please enter a valid email address.',
                'html_above'             => null,
                'options'                => null,
                'sort_order'             => 1,
                'field_role'             => 'email',
            ],
            [
                'form_id'                => $contactFormId,
                'type'                   => 'textarea',
                'label'                  => 'Comments / Message',
                'instructions'           => 'Tell us how we can help you.',
                'is_required'            => true,
                'required_type'          => 'non_blank',
                'required_error_message' => 'Please enter a message.',
                'html_above'             => null,
                'options'                => null,
                'sort_order'             => 2,
                'field_role'             => null,
            ],
            [
                'form_id'                => $contactFormId,
                'type'                   => 'select',
                'label'                  => 'How did you hear about us?',
                'instructions'           => null,
                'is_required'            => false,
                'required_type'          => null,
                'required_error_message' => null,
                'html_above'             => null,
                'options'                => json_encode([
                    'Search Engine (Google, Bing, etc.)',
                    'Social Media',
                    'Word of Mouth / Referral',
                    'Online Advertisement',
                    'Blog or Article',
                    'Other',
                ]),
                'sort_order'             => 3,
                'field_role'             => null,
            ],
        ];

        foreach ($contactFields as $field) {
            DB::table('cms_form_fields')->insert(array_merge($field, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Form 2: Email Subscribe ───────────────────────────────────────────
        $subscribeFormId = DB::table('cms_forms')->insertGetId([
            'name'                 => 'Email Subscribe',
            'slug'                 => 'email-subscribe',
            'submit_button_label'  => 'Subscribe',
            'confirmation_message' => '<p><strong>You\'re subscribed!</strong> Thanks for joining our mailing list. Look out for updates in your inbox.</p>',
            'redirect_url'         => null,
            'email_to'             => null,
            'email_subject'        => 'New Mailing List Subscriber',
            'auto_optin'           => true,
            'optin_provider'       => null, // configure provider + list ID in admin when ready
            'optin_list_id'        => null,
            'custom_css'           => null,
            'is_active'            => true,
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);

        $subscribeFields = [
            [
                'form_id'                => $subscribeFormId,
                'type'                   => 'input',
                'label'                  => 'Your Name',
                'instructions'           => null,
                'is_required'            => true,
                'required_type'          => 'non_blank',
                'required_error_message' => 'Please enter your name.',
                'html_above'             => null,
                'options'                => null,
                'sort_order'             => 0,
                'field_role'             => 'name',
            ],
            [
                'form_id'                => $subscribeFormId,
                'type'                   => 'input',
                'label'                  => 'Email Address',
                'instructions'           => null,
                'is_required'            => true,
                'required_type'          => 'email',
                'required_error_message' => 'Please enter a valid email address.',
                'html_above'             => null,
                'options'                => null,
                'sort_order'             => 1,
                'field_role'             => 'email',
            ],
        ];

        foreach ($subscribeFields as $field) {
            DB::table('cms_form_fields')->insert(array_merge($field, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
