<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->uuid('token')->unique()->index();
            $table->unsignedBigInteger('order_detail_id')->index();
            $table->unsignedInteger('product_id')->index();
            $table->string('redirect_url', 2000);
            $table->string('email', 255);
            $table->timestamp('accessed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('order_detail_id')
                  ->references('id')->on('order_details')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_access_tokens');
    }
};
