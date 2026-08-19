<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ProductReviewsList extends Component
{
    public int $productId;
    
    // Form Inputs
    public string $name = '';
    public string $location = '';
    public int $rating = 5;
    public string $comments = '';
    
    // Filters & Sorting
    public string $sort = 'recent'; // recent, highest, lowest

    // reCAPTCHA v3
    public string $recaptchaToken = '';
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string',
        ];
    }
    public function mount(int $productId): void
    {
        $this->productId = $productId;
    }

    public function submitReview(\App\Services\RecaptchaService $recaptcha): void
    {
        // Limit to one review per session
        if (session()->has('submitted_review_' . $this->productId)) {
            $this->addError('name', 'You have already submitted a review for this product.');
            return;
        }

        // reCAPTCHA v3 verification
        if (! $recaptcha->verify($this->recaptchaToken, 'product_review')) {
            $this->addError('name', 'Security check failed. Please refresh the page and try again.');
            return;
        }

        $this->validate();

        ProductReview::create([
            'product_id' => $this->productId,
            'name' => strip_tags(trim($this->name)),
            'location' => strip_tags(trim($this->location)),
            'rating' => $this->rating,
            'comments' => strip_tags(trim($this->comments)),
            'approved' => false,
        ]);

        session()->put('submitted_review_' . $this->productId, true);

        $this->reset(['name', 'location', 'rating', 'comments', 'recaptchaToken']);
        $this->dispatch('toast', message: 'Your review has been submitted and is awaiting approval.', type: 'success');
    }

    public function render(): View
    {
        $product = Product::findOrFail($this->productId);
        $product->recalculateRatingIfZero();

        $query = $product->reviews()->where('approved', true)->withCurrentTranslations();

        if ($this->sort === 'highest') {
            $query->orderBy('rating', 'desc')->orderBy('created_at', 'desc');
        } elseif ($this->sort === 'lowest') {
            $query->orderBy('rating', 'asc')->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->get();

        return view('livewire.product-reviews-list', [
            'product' => $product,
            'reviews' => $reviews,
        ]);
    }
}
