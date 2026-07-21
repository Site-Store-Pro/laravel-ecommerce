<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Parent form definitions ──────────────────────────────────────────
        Schema::create('cms_forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('submit_button_label')->default('Submit');
            $table->text('custom_css')->nullable();
            $table->text('confirmation_message')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('email_to')->nullable();
            $table->string('email_subject')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Individual form fields ────────────────────────────────────────────
        Schema::create('cms_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('cms_forms')->cascadeOnDelete();
            $table->enum('type', ['input', 'textarea', 'select', 'radio', 'checkbox', 'checkbox_group']);
            $table->string('label');
            $table->text('instructions')->nullable();   // small help text below label
            $table->boolean('is_required')->default(false);
            $table->enum('required_type', ['non_blank', 'email', 'numeric'])->nullable();
            $table->string('required_error_message')->nullable();
            $table->longText('html_above')->nullable();  // TinyMCE HTML rendered above field
            $table->json('options')->nullable();          // option strings for select/radio/checkbox_group
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ── Logged form submissions ───────────────────────────────────────────
        Schema::create('cms_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('cms_forms')->cascadeOnDelete();
            $table->json('data');                        // {field_id: value, ...}
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_form_submissions');
        Schema::dropIfExists('cms_form_fields');
        Schema::dropIfExists('cms_forms');
    }
};
