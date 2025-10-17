<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Review;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', true)->with(['category', 'primaryImage']);

        if ($request->has('category')) {
            $category = Category::find($request->category);
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Load product with images and category
        $product->load(['category', 'images' => function($query) {
            $query->ordered();
        }]);

        // Calculate review statistics
        $product->reviews_count = $product->reviews()->approved()->count();
        $product->average_rating = Review::getAverageRating($product->id);
        $product->rating_distribution = Review::getRatingDistribution($product->id);

        // Get related products from same category
        $relatedProducts = Product::with(['category', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
