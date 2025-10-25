<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Menu;
use App\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::withCount('allItems')->orderBy('order')->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        if (!isset($validatedData['slug']) || empty($validatedData['slug'])) {
            $validatedData['slug'] = Str::slug($validatedData['name']);
        }

        $menu = Menu::create($validatedData);

        return redirect()->route('admin.menus.edit', $menu->id)
            ->with('success', 'Menu đã được tạo thành công. Bây giờ bạn có thể thêm các mục menu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        // If requesting specific menu item data (for AJAX edit modal)
        if ($request->has('item_id')) {
            $item = MenuItem::where('menu_id', $id)
                ->findOrFail($request->item_id);

            return response()->json($item);
        }

        $menu = Menu::with(['allItems' => function($query) {
            $query->orderBy('order');
        }])->findOrFail($id);

        return view('admin.menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer'
        ]);

        $menu->update($validatedData);

        return redirect()->route('admin.menus.edit', $id)
            ->with('success', 'Menu đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu đã được xóa thành công.');
    }

    /**
     * Store a new menu item
     */
    public function storeItem(Request $request, $menuId)
    {
        $validatedData = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'css_class' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $validatedData['menu_id'] = $menuId;

        MenuItem::create($validatedData);

        return redirect()->route('admin.menus.edit', $menuId)
            ->with('success', 'Mục menu đã được thêm thành công.');
    }

    /**
     * Update menu item
     */
    public function updateItem(Request $request, $menuId, $itemId)
    {
        $item = MenuItem::where('menu_id', $menuId)->findOrFail($itemId);

        $validatedData = $request->validate([
            'parent_id' => 'nullable|exists:menu_items,id',
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'target' => 'required|in:_self,_blank',
            'css_class' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean'
        ]);

        $item->update($validatedData);

        return redirect()->route('admin.menus.edit', $menuId)
            ->with('success', 'Mục menu đã được cập nhật thành công.');
    }

    /**
     * Delete menu item
     */
    public function destroyItem($menuId, $itemId)
    {
        $item = MenuItem::where('menu_id', $menuId)->findOrFail($itemId);
        $item->delete();

        return redirect()->route('admin.menus.edit', $menuId)
            ->with('success', 'Mục menu đã được xóa thành công.');
    }

    /**
     * Update menu items order
     */
    public function updateOrder(Request $request, $menuId)
    {
        $items = $request->input('items', []);

        foreach ($items as $itemData) {
            MenuItem::where('id', $itemData['id'])
                ->where('menu_id', $menuId)
                ->update([
                    'order' => $itemData['order']
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Đã cập nhật thứ tự menu']);
    }
}
