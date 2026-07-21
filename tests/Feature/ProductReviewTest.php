<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\CmsSetting;
use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default settings/values
        CmsSetting::set('enable_reviews', '1');
        CmsSetting::set('third_party_reviews_js', '');

        // Mock RecaptchaService to always pass
        $this->mock(RecaptchaService::class, function ($mock) {
            $mock->shouldReceive('verify')->andReturn(true);
        });
    }

    public function test_reviews_can_be_submitted_and_are_sanitized(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'reviews_enabled' => 1,
            'reviews_rating' => 0.00,
        ]);

        Livewire::test(\App\Livewire\ProductReviewsList::class, ['productId' => $product->id])
            ->set('name', '<script>alert("xss")</script>John Doe')
            ->set('location', '<b>New York</b>')
            ->set('rating', 4)
            ->set('comments', '<i>This product is great! <iframe src="evil.com"></iframe></i>')
            ->call('submitReview');

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'name' => 'alert("xss")John Doe',
            'location' => 'New York',
            'rating' => 4,
            'comments' => 'This product is great! ',
            'approved' => false,
        ]);
    }

    public function test_reviews_require_name_and_rating(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'reviews_enabled' => 1,
            'reviews_rating' => 0.00,
        ]);

        Livewire::test(\App\Livewire\ProductReviewsList::class, ['productId' => $product->id])
            ->set('name', '')
            ->set('rating', 0)
            ->call('submitReview')
            ->assertHasErrors(['name', 'rating']);
    }

    public function test_reviews_are_limited_to_one_per_session(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'reviews_enabled' => 1,
            'reviews_rating' => 0.00,
        ]);

        // Submit first review
        Livewire::test(\App\Livewire\ProductReviewsList::class, ['productId' => $product->id])
            ->set('name', 'Alice')
            ->set('rating', 5)
            ->call('submitReview')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('product_reviews', [
            'product_id' => $product->id,
            'name' => 'Alice',
            'rating' => 5,
        ]);

        // Attempt to submit second review in the same session
        Livewire::test(\App\Livewire\ProductReviewsList::class, ['productId' => $product->id])
            ->set('name', 'Bob')
            ->set('rating', 4)
            ->call('submitReview')
            ->assertHasErrors(['name']);

        // Assert second review was NOT stored
        $this->assertDatabaseMissing('product_reviews', [
            'name' => 'Bob',
        ]);
    }

    public function test_recalculating_product_rating_average(): void
    {
        $product = Product::create([
            'title' => 'Test Product',
            'reviews_enabled' => 1,
            'reviews_rating' => 0.00,
        ]);

        $review1 = ProductReview::create([
            'product_id' => $product->id,
            'name' => 'Alice',
            'rating' => 5,
            'approved' => true,
        ]);

        $review2 = ProductReview::create([
            'product_id' => $product->id,
            'name' => 'Bob',
            'rating' => 4,
            'approved' => false, // not approved yet
        ]);

        // Recalculate average
        $productEditor = new \App\Livewire\AdminProductEdit();
        $productEditor->recalculateProductRating($product->id);

        $product->refresh();
        $this->assertEquals(5.00, $product->reviews_rating);

        // Approve review 2
        $review2->update(['approved' => true]);
        $productEditor->recalculateProductRating($product->id);

        $product->refresh();
        $this->assertEquals(4.50, $product->reviews_rating);
    }
}
