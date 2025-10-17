<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class PublicController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::with(['category', 'primaryImage'])
            ->where('status', true)
            ->where('featured', true)
            ->limit(8)
            ->get();

        // Get latest products
        $latestProducts = Product::with(['category', 'primaryImage'])
            ->where('status', true)
            ->latest()
            ->limit(12)
            ->get();

        // Get categories with product count
        $categories = Category::with('products')
            ->where('status', true)
            ->withCount(['products' => function($query) {
                $query->where('status', true);
            }])
            ->having('products_count', '>', 0)
            ->limit(6)
            ->get();

        // Get best selling products
        $bestSellers = Product::with(['category', 'primaryImage', 'orderItems'])
            ->where('status', true)
            ->withCount('orderItems')
            ->having('order_items_count', '>', 0)
            ->orderBy('order_items_count', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('featuredProducts', 'latestProducts', 'categories', 'bestSellers'));
    }
}
