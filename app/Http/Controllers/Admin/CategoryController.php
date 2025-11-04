<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $categories = Category::latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'status' => 'boolean'
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function show(Category $category)
    {
        $products = $category->products()->paginate(12);
        return view('admin.categories.show', compact('category', 'products'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|url',
            'status' => 'boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'status' => $request->has('status')
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        // Special protection for critical categories
        if (in_array(strtolower($category->name), ['uncategorized', 'không phân loại', 'general', 'default'])) {
            return back()->with('error', 'Không thể xóa danh mục mặc định này! Danh mục này cần thiết để hệ thống hoạt động.');
        }

        // Check if category has active products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục này vì còn sản phẩm đang hoạt động! Hãy di chuyển hoặc xóa sản phẩm trước.');
        }

        // Check if category has products in orders (including soft deleted products)
        $productsInOrders = \App\Product::withTrashed()
            ->where('category_id', $category->id)
            ->whereHas('orderItems')
            ->count();

        if ($productsInOrders > 0) {
            // Find default "Uncategorized" category or create one
            $defaultCategory = \App\Category::firstOrCreate(
                ['name' => 'Uncategorized'],
                ['slug' => 'uncategorized', 'description' => 'Sản phẩm chưa phân loại']
            );

            // Move all products (including soft deleted) to default category
            \App\Product::withTrashed()
                ->where('category_id', $category->id)
                ->update(['category_id' => $defaultCategory->id]);

            return back()->with('warning', 'Danh mục có sản phẩm đã được đặt hàng. Các sản phẩm đã được chuyển sang danh mục "Uncategorized". Bạn có thể thử xóa lại.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }
}
