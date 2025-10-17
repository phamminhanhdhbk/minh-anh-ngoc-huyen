<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\ProductImage;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage']);

        // Search by name or SKU
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category') && $request->get('category') != '') {
            $query->where('category_id', $request->get('category'));
        }

        // Filter by status
        if ($request->has('status') && $request->get('status') !== '' && $request->get('status') !== null) {
            $query->where('status', $request->get('status'));
        }

        $products = $query->latest()->paginate(15);
        $products->appends($request->all());

        $categories = Category::where('status', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'image' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean',
            'featured' => 'boolean'
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price ?: $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku ?: 'SKU-' . time(),
            'image' => $request->image,
            'category_id' => $request->category_id,
            'status' => $request->has('status'),
            'featured' => $request->has('featured')
        ]);

        // Handle multiple images
        $this->handleImageUploads($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images' => function($query) {
            $query->ordered();
        }]);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['images' => function($query) {
            $query->ordered();
        }]);
        $categories = Category::where('status', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'image' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_urls' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean',
            'featured' => 'boolean'
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price ?: $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku ?: 'SKU-' . time(),
            'image' => $request->image,
            'category_id' => $request->category_id,
            'status' => $request->has('status'),
            'featured' => $request->has('featured')
        ]);

        // Handle multiple images
        $this->handleImageUploads($request, $product, true);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update([
            'featured' => !$product->featured
        ]);

        $message = $product->featured ? 'Sản phẩm đã được đánh dấu nổi bật!' : 'Sản phẩm đã được bỏ đánh dấu nổi bật!';

        return back()->with('success', $message);
    }

    public function toggleStatus(Product $product)
    {
        $product->update([
            'status' => !$product->status
        ]);

        $message = $product->status ? 'Sản phẩm đã được kích hoạt!' : 'Sản phẩm đã được tạm ngưng!';

        return back()->with('success', $message);
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(Request $request, Product $product, $isUpdate = false)
    {
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            foreach ($images as $index => $image) {
                if ($image && $image->isValid()) {
                    // Generate safe filename
                    $extension = $image->getClientOriginalExtension();
                    $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = Str::slug($originalName) ?: 'image';
                    $filename = time() . '_' . $index . '_' . $safeName . '.' . $extension;

                    // Store image
                    $path = $image->storeAs('products', $filename, 'public');

                    // Create ProductImage record
                    $productImage = new ProductImage([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'alt_text' => $product->name . ' - Image ' . ($index + 1),
                        'sort_order' => $index,
                        'is_primary' => $index === 0 && !$product->images()->where('is_primary', true)->exists()
                    ]);

                    $productImage->save();
                }
            }
        }

        // Handle image URLs if provided
        if ($request->filled('image_urls')) {
            $urls = array_filter(explode("\n", $request->image_urls));
            $existingCount = $product->images()->count();

            foreach ($urls as $index => $url) {
                $url = trim($url);
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    $productImage = new ProductImage([
                        'product_id' => $product->id,
                        'image_path' => $url,
                        'alt_text' => $product->name . ' - Image ' . ($existingCount + $index + 1),
                        'sort_order' => $existingCount + $index,
                        'is_primary' => $existingCount === 0 && $index === 0 && !$product->images()->where('is_primary', true)->exists()
                    ]);

                    $productImage->save();
                }
            }
        }
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $image)
    {
        // Delete file if it's stored locally
        if (!filter_var($image->image_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasRimary = $image->is_primary;
        $productId = $image->product_id;

        $image->delete();

        // If deleted image was primary, set next image as primary
        if ($wasRimary) {
            $nextImage = ProductImage::where('product_id', $productId)->ordered()->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Set image as primary
     */
    public function setPrimaryImage(ProductImage $image)
    {
        // Remove primary from all images of this product
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Reorder images
     */
    public function reorderImages(Request $request, Product $product)
    {
        $imageIds = $request->input('image_ids', []);

        foreach ($imageIds as $index => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
