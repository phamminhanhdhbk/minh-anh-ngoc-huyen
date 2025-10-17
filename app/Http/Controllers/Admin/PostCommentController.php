<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PostComment;
use App\Post;

class PostCommentController extends Controller
{
    /**
     * Display a listing of comments
     */
    public function index(Request $request)
    {
        $query = PostComment::with(['post', 'user']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by post
        if ($request->has('post')) {
            $query->where('post_id', $request->post);
        }

        $comments = $query->latest()->paginate(20);
        $posts = Post::orderBy('title')->get();

        // Statistics
        $totalComments = PostComment::count();
        $pendingComments = PostComment::where('status', 'pending')->count();
        $approvedComments = PostComment::where('status', 'approved')->count();
        $rejectedComments = PostComment::where('status', 'rejected')->count();

        return view('admin.post-comments.index', compact('comments', 'posts', 'totalComments', 'pendingComments', 'approvedComments', 'rejectedComments'));
    }

    /**
     * Approve comment
     */
    public function approve($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Bình luận đã được duyệt!');
    }

    /**
     * Reject comment
     */
    public function reject($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Bình luận đã bị từ chối!');
    }

    /**
     * Delete comment
     */
    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa!');
    }
}
