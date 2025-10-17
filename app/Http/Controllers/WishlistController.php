<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wishlist;
use App\Product;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
                                ->with(['product' => function($query) {
                                    $query->with(['category', 'primaryImage']);
                                }])
                                ->latest()
                                ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Add product to wishlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        $user = auth()->user();

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $user->id)
                         ->where('product_id', $product->id)
                         ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm đã có trong danh sách yêu thích!'
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $product->id
        ]);

        $wishlistCount = $user->wishlists()->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào danh sách yêu thích!',
            'wishlist_count' => $wishlistCount
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = auth()->user();
        $wishlistItem = Wishlist::where('user_id', $user->id)
                               ->where('product_id', $request->product_id)
                               ->first();

        if (!$wishlistItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong danh sách yêu thích!'
            ]);
        }

        $wishlistItem->delete();

        $wishlistCount = $user->wishlists()->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích!',
            'wishlist_count' => $wishlistCount
        ]);
    }

    /**
     * Toggle wishlist status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = auth()->user();
        $wishlistItem = Wishlist::where('user_id', $user->id)
                               ->where('product_id', $request->product_id)
                               ->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            $inWishlist = false;
            $message = 'Đã xóa khỏi danh sách yêu thích!';
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ]);
            $inWishlist = true;
            $message = 'Đã thêm vào danh sách yêu thích!';
        }

        $wishlistCount = $user->wishlists()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'in_wishlist' => $inWishlist,
            'wishlist_count' => $wishlistCount
        ]);
    }

    /**
     * Get wishlist count for current user
     */
    public function count()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $count = auth()->user()->wishlists()->count();

        return response()->json(['count' => $count]);
    }
}
