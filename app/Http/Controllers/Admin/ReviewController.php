<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])
                      ->latest();

        // Filter by status
        if ($request->has('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->where('approved', false);
                    break;
                case 'approved':
                    $query->where('approved', true);
                    break;
                case 'verified':
                    $query->where('verified_purchase', true);
                    break;
            }
        }

        // Filter by rating
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('approved', false)->count(),
            'approved' => Review::where('approved', true)->count(),
            'verified' => Review::where('verified_purchase', true)->count(),
            'with_images' => Review::whereNotNull('images')->count(),
        ];

        // Rating distribution
        $ratingStats = Review::selectRaw('rating, COUNT(*) as count')
                            ->groupBy('rating')
                            ->pluck('count', 'rating')
                            ->toArray();

        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratingStats[$i])) {
                $ratingStats[$i] = 0;
            }
        }
        ksort($ratingStats);

        return view('admin.reviews.index', compact('reviews', 'stats', 'ratingStats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'product']);

        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update(['approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Đã duyệt đánh giá thành công.'
        ]);
    }

    public function reject(Review $review)
    {
        $review->update(['approved' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Đã từ chối đánh giá.'
        ]);
    }

    public function destroy(Review $review)
    {
        // Delete images if exist
        if ($review->images) {
            foreach ($review->image_urls as $imageUrl) {
                $imagePath = str_replace(asset('storage/'), '', $imageUrl);
                if (file_exists(storage_path('app/public/' . $imagePath))) {
                    unlink(storage_path('app/public/' . $imagePath));
                }
            }
        }

        $review->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đánh giá thành công.'
            ]);
        }

        return redirect()->route('admin.reviews.index')
                        ->with('success', 'Đã xóa đánh giá thành công.');
    }
}
