<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth'], except: ['index', 'show', 'trending', 'search', 'userPosts']),
        ];
    }

    /**
     * Display a listing of posts (Latest posts)
     */
    public function index()
    {
        $posts = Post::with(['user', 'jaimes.user', 'comments'])
            ->latest()
            ->paginate(12);

        return view('posts.index', compact('posts'));
    }

    /**
     * Trending Posts (last 24h sorted by engagement)
     */
    public function trending()
    {
        $posts = Post::with(['user', 'jaimes.user', 'comments'])
            ->withCount(['jaimes', 'comments'])
            ->where('created_at', '>=', now()->subDay())
            ->orderByDesc('jaimes_count')
            ->orderByDesc('comments_count')
            ->paginate(12);

        return view('posts.trending', compact('posts'));
    }

    /**
     * Search posts
     */
   public function search(Request $request)
{
    $query = $request->input('q', '');

    $posts = Post::with(['user', 'jaimes', 'comments'])
        ->withCount(['jaimes', 'comments'])
        ->where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('body', 'LIKE', "%{$query}%");
        })
        ->latest()
        ->paginate(12);

    // Return JSON for AJAX requests
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'posts' => $posts->items(),
            'total' => $posts->total()
        ]);
    }

    // Return view for normal requests
    return view('posts.search', compact('posts', 'query'));
}

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'body' => ['required', 'min:3', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts_images', 'public');
        }

        Auth::user()->posts()->create([
            'title' => $fields['title'],
            'body' => $fields['body'],
            'image' => $path,
        ]);

        return back()->with('success', 'Post created successfully');
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load(['user', 'jaimes.user', 'comments.user', 'comments.likes']);
        
        $comments = $post->comments()
            ->with(['user', 'likes'])
            ->latest()
            ->get();

        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the post
     */
    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $fields = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'body' => ['required', 'min:3', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg,webp', 'max:2048'],
        ]);

        $path = $post->image;
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            // Store new image
            $path = $request->file('image')->store('posts_images', 'public');
        }

        $post->update([
            'title' => $fields['title'],
            'body' => $fields['body'],
            'image' => $path,
        ]);

        return redirect()->route('dashboard')->with('update', 'Post updated successfully');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        // Delete associated image
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('delete', 'Post deleted successfully');
    }

    /**
     * Toggle Like/Unlike
     */
    public function jaimerPost(Post $post)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to like posts'
            ], 401);
        }

        $userId = auth()->id();
        $jaime = $post->jaimes()->where('user_id', $userId)->first();

        if ($jaime) {
            $jaime->delete();
            $liked = false;
            $message = 'Like removed';
        } else {
            $post->jaimes()->create(['user_id' => $userId]);
            $liked = true;
            $message = 'Post liked';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'likes_count' => $post->jaimes()->count(),
            'liked' => $liked
        ]);
    }

    /**
     * Get posts by specific user
     */
    public function userPosts(User $user)
    {
        $posts = Post::with(['user', 'jaimes.user', 'comments'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('posts.user', compact('posts', 'user'));
    }
}