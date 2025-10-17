<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Review;
use App\Product;
use App\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a review
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
                              ->where('product_id', $product->id)
                              ->first();

        if ($existingReview) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }

        // Check if this is a verified purchase
        $verifiedPurchase = Review::hasUserPurchasedProduct(auth()->id(), $product->id);

        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'images' => $imagePaths,
            'verified_purchase' => $verifiedPurchase,
            'approved' => false // Pending approval
        ]);

        return back()->with('success', 'Đánh giá của bạn đã được gửi và đang chờ duyệt!');
    }

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns the review
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        $imagePaths = $review->images ?: [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $imagePaths[] = $path;
            }
        }

        // Remove images if requested
        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $removeImage) {
                if (($key = array_search($removeImage, $imagePaths)) !== false) {
                    Storage::disk('public')->delete($removeImage);
                    unset($imagePaths[$key]);
                }
            }
            $imagePaths = array_values($imagePaths);
        }

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'images' => $imagePaths,
            'approved' => false // Reset approval status
        ]);

        return back()->with('success', 'Đánh giá đã được cập nhật!');
    }

    /**
     * Delete a review
     */
    /**
     * Toggle helpful status for a review
     */
    public function toggleHelpful(Review $review)
    {
        $user = auth()->user();

        // Check if user already marked this review as helpful
        $existing = $review->helpfulUsers()->where('user_id', $user->id)->first();

        if ($existing) {
            // Remove helpful mark
            $review->helpfulUsers()->detach($user->id);
            $helpful = false;
        } else {
            // Add helpful mark
            $review->helpfulUsers()->attach($user->id);
            $helpful = true;
        }

        $count = $review->helpfulUsers()->count();

        return response()->json([
            'success' => true,
            'helpful' => $helpful,
            'count' => $count
        ]);
    }

    /**
     * Report a review
     */
    public function report(Review $review)
    {
        // You can implement a reporting system here
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Báo cáo đã được ghi nhận.'
        ]);
    }

    /**
     * Get reviews for a specific product (AJAX)
     */
    public function productReviews(Product $product)
    {
        $reviews = $product->approvedReviews()
                          ->with('user')
                          ->latest()
                          ->paginate(5);

        if (request()->ajax()) {
            return view('components.review-list', compact('reviews'))->render();
        }

        return view('products.reviews', compact('product', 'reviews'));
    }
    /**
     * Get reviews for a product (AJAX)
     */
    public function getProductReviews(Product $product, Request $request)
    {
        $reviews = $product->approvedReviews()
                          ->with('user')
                          ->latest()
                          ->paginate(10);

        if ($request->ajax()) {
            return view('components.review-list', compact('reviews'))->render();
        }

        return response()->json($reviews);
    }
}
