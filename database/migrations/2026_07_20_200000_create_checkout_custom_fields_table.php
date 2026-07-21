<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkout_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('type');                         // input | textarea | select | radio | checkbox | checkbox_group
            $table->string('label');
            $table->string('instructions')->nullable();     // small help text under label
            $table->boolean('is_required')->default(false);
            $table->string('required_type')->nullable();    // non_blank | email | numeric
            $table->string('required_error_message')->nullable();
            $table->text('html_above')->nullable();         // rich HTML block above field (TinyMCE)
            $table->json('options')->nullable();            // for select / radio / checkbox_group
            $table->string('position');                     // checkout | billing
            $table->string('show_for')->default('both');    // both | public | wholesale  (billing position only)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_custom_fields');
    }
};
