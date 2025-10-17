<?php

use Illuminate\Support\Facades\Route;
use App\Product;

Route::get('/test-search', function() {
    $search = request('search', '');

    echo "<h3>Test Search Results</h3>";
    echo "<p>Search term: '$search'</p>";

    $query = Product::with('category');

    if (!empty($search)) {
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('sku', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    $products = $query->latest()->paginate(15);

    echo "<p>Total found: " . $products->total() . "</p>";
    echo "<p>Current page count: " . count($products->items()) . "</p>";

    echo "<h4>Products:</h4>";
    echo "<ul>";
    foreach ($products as $product) {
        echo "<li>{$product->id}: {$product->name} - {$product->sku}</li>";
    }
    echo "</ul>";

    echo "<h4>Request Info:</h4>";
    echo "<pre>";
    print_r(request()->all());
    echo "</pre>";
});
