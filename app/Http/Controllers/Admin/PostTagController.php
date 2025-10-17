<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PostTag;
use Illuminate\Support\Str;

class PostTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = PostTag::withCount('posts')->orderBy('name')->paginate(20);
        return view('admin.post-tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.post-tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:post_tags',
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        PostTag::create($data);

        return redirect()->route('admin.post-tags.index')
            ->with('success', 'Tag đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tag = PostTag::findOrFail($id);
        return view('admin.post-tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tag = PostTag::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:post_tags,slug,' . $id,
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);

        $tag->update($data);

        return redirect()->route('admin.post-tags.index')
            ->with('success', 'Tag đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = PostTag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.post-tags.index')
            ->with('success', 'Tag đã được xóa thành công!');
    }
}
