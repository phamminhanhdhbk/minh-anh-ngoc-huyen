<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $themes = Theme::orderBy('order')->get();
        $activeTheme = Theme::getActiveTheme();

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.themes.create');
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
            'slug' => 'required|string|max:255|unique:themes',
            'view_path' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'version' => 'nullable|string|max:20',
            'is_default' => 'boolean',
            'order' => 'integer'
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validatedData['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/themes'), $filename);
            $validatedData['thumbnail'] = 'uploads/themes/' . $filename;
        }

        $theme = Theme::create($validatedData);

        // If marked as default, unset other defaults
        if ($request->is_default) {
            Theme::where('id', '!=', $theme->id)->update(['is_default' => false]);
        }

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $theme = Theme::findOrFail($id);
        return view('admin.themes.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $theme = Theme::findOrFail($id);
        return view('admin.themes.edit', compact('theme'));
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
        $theme = Theme::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes,slug,' . $id,
            'view_path' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'author' => 'nullable|string|max:255',
            'version' => 'nullable|string|max:20',
            'is_default' => 'boolean',
            'order' => 'integer'
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($theme->thumbnail && file_exists(public_path($theme->thumbnail))) {
                unlink(public_path($theme->thumbnail));
            }

            $file = $request->file('thumbnail');
            $filename = time() . '_' . Str::slug($validatedData['name']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/themes'), $filename);
            $validatedData['thumbnail'] = 'uploads/themes/' . $filename;
        }

        $theme->update($validatedData);

        // If marked as default, unset other defaults
        if ($request->is_default) {
            Theme::where('id', '!=', $theme->id)->update(['is_default' => false]);
        }

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $theme = Theme::findOrFail($id);

        // Don't allow deleting active or default theme
        if ($theme->is_active || $theme->is_default) {
            return redirect()->route('admin.themes.index')
                ->with('error', 'Không thể xóa theme đang hoạt động hoặc theme mặc định.');
        }

        // Delete thumbnail
        if ($theme->thumbnail && file_exists(public_path($theme->thumbnail))) {
            unlink(public_path($theme->thumbnail));
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme đã được xóa thành công.');
    }

    /**
     * Activate a theme
     */
    public function activate($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->activate();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Theme "' . $theme->name . '" đã được kích hoạt.');
    }

    /**
     * Preview a theme
     */
    public function preview($id)
    {
        $theme = Theme::findOrFail($id);

        // Store theme preview in session
        session(['preview_theme_id' => $theme->id]);

        return redirect()->route('home')
            ->with('info', 'Đang xem trước theme "' . $theme->name . '". <a href="' . route('admin.themes.clearPreview') . '">Quay lại theme hiện tại</a>');
    }

    /**
     * Clear theme preview
     */
    public function clearPreview()
    {
        session()->forget('preview_theme_id');

        return redirect()->route('home')
            ->with('success', 'Đã quay lại theme hiện tại.');
    }

    /**
     * Update theme settings
     */
    public function updateSettings(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);

        $settings = $request->input('settings', []);
        $theme->update(['settings' => $settings]);

        return redirect()->route('admin.themes.edit', $id)
            ->with('success', 'Cài đặt theme đã được cập nhật.');
    }
}
