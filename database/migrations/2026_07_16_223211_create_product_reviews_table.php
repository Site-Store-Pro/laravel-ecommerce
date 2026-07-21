<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name');
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comments')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('reviews_enabled')->default(true);
            $table->decimal('reviews_rating', 3, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['reviews_enabled', 'reviews_rating']);
        });

        Schema::dropIfExists('product_reviews');
    }
};
