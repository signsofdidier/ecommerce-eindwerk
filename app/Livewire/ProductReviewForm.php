<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductReviewForm extends Component
{
    public Product $product;

    public int $rating = 0;
    public string $title = '';
    public string $body = '';

    public bool $showForm = false;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function showReviewForm()
    {
        if (! $this->hasUserReviewed()) {
            $this->showForm = true;
        }
    }

    public function save()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->hasUserReviewed()) {
            session()->flash('error', 'You have already submitted a review for this product.');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'approved' => false,
        ]);

        $this->reset(['rating', 'title', 'body', 'showForm']);

        //session()->flash('success', 'Review submitted and is pending approval.');
        $this->dispatch('reviewAdded');
    }

    // HEEFT DE USER AL EEN REVIEW OP DIT PRODUCT?
    public function hasUserReviewed(): bool
    {
        return $this->product
            ->reviews()
            ->where('user_id', Auth::id())
            ->exists();
    }

    // REVIEW: USER HEEFT PRODUCT GEKOCHT
    public function getCanReviewProperty()
    {
        return auth()->check() && auth()->user()->hasPurchasedProduct($this->product->id);
    }

    public function render()
    {
        return view('livewire.product-review-form', [
            'canReview' => $this->canReview,
        ]);
    }
}
