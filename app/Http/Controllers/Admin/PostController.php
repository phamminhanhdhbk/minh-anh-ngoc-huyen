<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Post;
use App\BlogCategory;
use App\PostTag;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('blog_category_id', $request->category);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest()->paginate(20);
        $categories = BlogCategory::active()->get();

        // Statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $archivedPosts = Post::where('status', 'archived')->count();

        return view('admin.posts.index', compact('posts', 'categories', 'totalPosts', 'publishedPosts', 'draftPosts', 'archivedPosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::active()->get();
        $tags = PostTag::orderBy('name')->get();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:post_tags,id'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['slug'] = $request->slug ?: Str::slug($request->title);

        if ($request->status == 'published' && !$request->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $imageName);
            $data['featured_image'] = 'uploads/posts/' . $imageName;
        }

        $post = Post::create($data);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Handle SEO data if exists
        if ($request->has('meta_title')) {
            $post->seoData()->create([
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::with(['tags', 'seoData'])->findOrFail($id);
        $categories = BlogCategory::active()->get();
        $tags = PostTag::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:posts,slug,' . $id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:post_tags,id'
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->title);

        if ($request->status == 'published' && !$post->published_at && !$request->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image && file_exists(public_path($post->featured_image))) {
                unlink(public_path($post->featured_image));
            }

            $image = $request->file('featured_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/posts'), $imageName);
            $data['featured_image'] = 'uploads/posts/' . $imageName;
        }

        $post->update($data);

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        // Handle SEO data
        if ($request->has('meta_title')) {
            $post->seoData()->updateOrCreate(
                ['seoable_id' => $post->id, 'seoable_type' => Post::class],
                [
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keywords' => $request->meta_keywords,
                ]
            );
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Delete featured image
        if ($post->featured_image && file_exists(public_path($post->featured_image))) {
            unlink(public_path($post->featured_image));
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }
}
