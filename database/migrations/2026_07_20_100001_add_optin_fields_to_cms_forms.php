<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Opt-in toggle + provider config on the form ────────────────────
        Schema::table('cms_forms', function (Blueprint $table) {
            $table->boolean('auto_optin')->default(false)->after('email_subject');
            $table->string('optin_provider')->nullable()->after('auto_optin');    // mailchimp | constant_contact | klaviyo
            $table->string('optin_list_id')->nullable()->after('optin_provider'); // audience / list ID at the provider
        });

        // ── Field role marker so the OptinService knows which input holds ──
        // the subscriber email and which holds the subscriber name.
        // Values: null (no role), 'email', 'name'
        // Only one field per form should carry each role; the service uses the
        // first match if duplicates exist.
        Schema::table('cms_form_fields', function (Blueprint $table) {
            $table->string('field_role')->nullable()->after('sort_order'); // null | 'email' | 'name'
        });
    }

    public function down(): void
    {
        Schema::table('cms_form_fields', function (Blueprint $table) {
            $table->dropColumn('field_role');
        });

        Schema::table('cms_forms', function (Blueprint $table) {
            $table->dropColumn(['auto_optin', 'optin_provider', 'optin_list_id']);
        });
    }
};
