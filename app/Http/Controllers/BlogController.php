<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\BlogCategory;
use App\PostTag;
use App\PostComment;

class BlogController extends Controller
{
    /**
     * Display blog home page
     */
    public function index(Request $request)
    {
        $query = Post::with(['category', 'author', 'tags'])
                    ->published()
                    ->orderBy('published_at', 'desc');

        $currentCategory = null;
        
        // Filter by category
        if ($request->has('category')) {
            $currentCategory = BlogCategory::where('slug', $request->category)->firstOrFail();
            $query->where('blog_category_id', $currentCategory->id);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $tag = PostTag::where('slug', $request->tag)->firstOrFail();
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('post_tag_id', $tag->id);
            });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(12);
        $categories = BlogCategory::active()->withCount('publishedPosts')->get();
        $featuredPosts = Post::published()->featured()->take(5)->get();
        $popularPosts = Post::published()->orderBy('views', 'desc')->take(5)->get();
        $tags = PostTag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', compact('posts', 'categories', 'featuredPosts', 'popularPosts', 'tags', 'currentCategory'));
    }

    /**
     * Display single post
     */
    public function show($slug)
    {
        $post = Post::with(['category', 'author', 'tags'])
                    ->where('slug', $slug)
                    ->published()
                    ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Get related posts
        $relatedPosts = Post::published()
                            ->where('blog_category_id', $post->blog_category_id)
                            ->where('id', '!=', $post->id)
                            ->take(4)
                            ->get();

        // Get comments
        $comments = $post->approvedComments()->with(['user', 'replies'])->get();

        $categories = BlogCategory::active()->get();
        $popularPosts = Post::published()->orderBy('views', 'desc')->take(5)->get();
        $tags = PostTag::withCount('posts')->orderBy('name')->get();

        return view('blog.show', compact('post', 'relatedPosts', 'comments', 'categories', 'popularPosts', 'tags'));
    }

    /**
     * Store comment
     */
    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($postId);

        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'email' => $request->email,
            'content' => $request->content,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi và đang chờ duyệt!');
    }
}
