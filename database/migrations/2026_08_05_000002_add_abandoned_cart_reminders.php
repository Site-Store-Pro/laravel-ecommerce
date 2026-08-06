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
        // 1. Add tracking columns to shopping_cart_log table
        if (Schema::hasTable('shopping_cart_log')) {
            Schema::table('shopping_cart_log', function (Blueprint $table) {
                if (!Schema::hasColumn('shopping_cart_log', 'guest_email')) {
                    $table->string('guest_email')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('shopping_cart_log', 'abandoned_reminder_1_sent_at')) {
                    $table->timestamp('abandoned_reminder_1_sent_at')->nullable()->after('guest_email');
                }
                if (!Schema::hasColumn('shopping_cart_log', 'abandoned_reminder_2_sent_at')) {
                    $table->timestamp('abandoned_reminder_2_sent_at')->nullable()->after('abandoned_reminder_1_sent_at');
                }
            });
        }

        // 2. Add email template types for abandoned cart reminders
        $now = now();
        $types = [
            ['id' => 11, 'name' => 'Abandoned Cart Reminder (24 Hours)', 'slug' => 'abandoned_cart_reminder_1', 'ordering' => 13.0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'name' => 'Abandoned Cart Reminder (1 Week)', 'slug' => 'abandoned_cart_reminder_2', 'ordering' => 14.0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($types as $type) {
            $exists = DB::table('email_template_types')->where('slug', $type['slug'])->first();
            if (!$exists) {
                DB::table('email_template_types')->insert($type);
            }
        }

        // 3. Seed default active templates for abandoned cart reminders
        $type1 = DB::table('email_template_types')->where('slug', 'abandoned_cart_reminder_1')->first();
        $type2 = DB::table('email_template_types')->where('slug', 'abandoned_cart_reminder_2')->first();

        if ($type1) {
            $tpl1Exists = DB::table('email_templates')->where('email_type_id', $type1->id)->first();
            if (!$tpl1Exists) {
                DB::table('email_templates')->insert([
                    'email_type_id' => $type1->id,
                    'profile_name' => 'Default 24-Hour Abandoned Cart Reminder',
                    'from_address' => 'support@example.com',
                    'from_name' => 'Online Store',
                    'bcc_address' => '',
                    'subject' => 'Did you leave something behind? Return to your cart!',
                    'header_html' => '',
                    'banner_image_url' => '',
                    'banner_image_link' => '',
                    'show_banner' => 0,
                    'salutation' => 'Dear {{customer_name}},',
                    'include_salutation' => 1,
                    'greeting' => '<p><strong>You left some great items in your shopping cart!</strong></p><p>We saved your cart so you can easily complete your purchase whenever you\'re ready.</p>',
                    'body' => '<p>Below are the items currently in your cart:</p><p>{{cart_items_table}}</p><p><a href="{{checkout_url}}" style="display:inline-block; background-color:#4f46e5; color:#ffffff; font-weight:bold; padding:12px 24px; border-radius:8px; text-decoration:none;">Complete Your Purchase &rarr;</a></p>',
                    'sign_off' => 'Sincerely,',
                    'signature' => 'Sales Department',
                    'disclaimer' => 'If you have any questions or need help with your order, please reply to this email.',
                    'copyright' => 'Copyright 2026',
                    'footer_image_url' => '',
                    'footer_image_link' => '',
                    'show_footer_image' => 0,
                    'footer_html' => '',
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        if ($type2) {
            $tpl2Exists = DB::table('email_templates')->where('email_type_id', $type2->id)->first();
            if (!$tpl2Exists) {
                DB::table('email_templates')->insert([
                    'email_type_id' => $type2->id,
                    'profile_name' => 'Default 7-Day Abandoned Cart Reminder',
                    'from_address' => 'support@example.com',
                    'from_name' => 'Online Store',
                    'bcc_address' => '',
                    'subject' => 'Your cart is waiting for you! Take another look',
                    'header_html' => '',
                    'banner_image_url' => '',
                    'banner_image_link' => '',
                    'show_banner' => 0,
                    'salutation' => 'Dear {{customer_name}},',
                    'include_salutation' => 1,
                    'greeting' => '<p><strong>Items in your cart are still available!</strong></p><p>It\'s been a week since you added items to your cart. Complete your order before items sell out!</p>',
                    'body' => '<p>Here is what is waiting in your cart:</p><p>{{cart_items_table}}</p><p><a href="{{checkout_url}}" style="display:inline-block; background-color:#4f46e5; color:#ffffff; font-weight:bold; padding:12px 24px; border-radius:8px; text-decoration:none;">Return to Cart &rarr;</a></p>',
                    'sign_off' => 'Sincerely,',
                    'signature' => 'Sales Department',
                    'disclaimer' => 'If you have any questions or need help with your order, please reply to this email.',
                    'copyright' => 'Copyright 2026',
                    'footer_image_url' => '',
                    'footer_image_link' => '',
                    'show_footer_image' => 0,
                    'footer_html' => '',
                    'is_active' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shopping_cart_log')) {
            Schema::table('shopping_cart_log', function (Blueprint $table) {
                $table->dropColumn(['guest_email', 'abandoned_reminder_1_sent_at', 'abandoned_reminder_2_sent_at']);
            });
        }

        DB::table('email_template_types')->whereIn('slug', ['abandoned_cart_reminder_1', 'abandoned_cart_reminder_2'])->delete();
    }
};
