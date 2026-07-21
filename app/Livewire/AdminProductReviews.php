<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdminProductReviews extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all'; // all, approved, pending
    public ?int $product_filter = null;

    // Review editing fields
    public bool $isEditingReview = false;
    public ?int $selectedReviewId = null;
    public string $reviewName = '';
    public string $reviewLocation = '';
    public int $reviewRating = 5;
    public string $reviewComments = '';
    public bool $reviewApproved = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'product_filter' => ['except' => null],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingProductFilter(): void
    {
        $this->resetPage();
    }

    public function recalculateProductRating(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $approvedReviews = $product->reviews()->where('approved', true)->get();
        if ($approvedReviews->isEmpty()) {
            $product->update(['reviews_rating' => 0.00]);
        } else {
            $average = $approvedReviews->avg('rating');
            $product->update(['reviews_rating' => round($average, 2)]);
        }
    }

    public function toggleReviewApproval(int $reviewId): void
    {
        $review = ProductReview::findOrFail($reviewId);
        $review->update(['approved' => !$review->approved]);
        $this->recalculateProductRating($review->product_id);
        
        session()->flash('status', 'Review approval status updated.');
    }

    public function deleteReview(int $reviewId): void
    {
        $review = ProductReview::findOrFail($reviewId);
        $productId = $review->product_id;
        $review->delete();
        $this->recalculateProductRating($productId);
        
        session()->flash('status', 'Review deleted successfully.');
    }

    public function editReview(int $reviewId): void
    {
        $review = ProductReview::findOrFail($reviewId);
        $this->selectedReviewId = $review->id;
        $this->reviewName = $review->name;
        $this->reviewLocation = $review->location ?? '';
        $this->reviewRating = $review->rating;
        $this->reviewComments = $review->comments ?? '';
        $this->reviewApproved = (bool) $review->approved;
        $this->isEditingReview = true;
    }

    public function cancelEditReview(): void
    {
        $this->isEditingReview = false;
        $this->resetReviewForm();
    }

    public function saveReview(): void
    {
        $this->validate([
            'reviewName' => 'required|string|max:255',
            'reviewLocation' => 'nullable|string|max:255',
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComments' => 'nullable|string',
            'reviewApproved' => 'boolean',
        ]);

        $review = ProductReview::findOrFail($this->selectedReviewId);
        $review->update([
            'name' => strip_tags(trim($this->reviewName)),
            'location' => strip_tags(trim($this->reviewLocation)),
            'rating' => $this->reviewRating,
            'comments' => strip_tags(trim($this->reviewComments)),
            'approved' => $this->reviewApproved,
        ]);

        $this->recalculateProductRating($review->product_id);
        $this->isEditingReview = false;
        $this->resetReviewForm();
        
        session()->flash('status', 'Review updated successfully.');
    }

    private function resetReviewForm(): void
    {
        $this->selectedReviewId = null;
        $this->reviewName = '';
        $this->reviewLocation = '';
        $this->reviewRating = 5;
        $this->reviewComments = '';
        $this->reviewApproved = false;
    }

    public function render(): View
    {
        $query = ProductReview::with('product');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhere('comments', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status !== 'all') {
            $query->where('approved', $this->status === 'approved');
        }

        if ($this->product_filter) {
            $query->where('product_id', $this->product_filter);
        }

        $reviews = $query->latest()->paginate(15);
        $products = Product::orderBy('title')->get();

        return view('livewire.admin-product-reviews', [
            'reviews' => $reviews,
            'products' => $products,
        ]);
    }
}
