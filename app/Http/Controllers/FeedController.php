<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index()
    {
        // Get IDs of users the authenticated user follows
        $followingIds = Follow::where('follower_id', Auth::id())
            ->pluck('following_id')
            ->push(Auth::id()) // include own posts
            ->unique();

        // Fetch posts from followed users + self, newest first
        $posts = Post::with(['user', 'media', 'comments.user', 'likes'])
            ->whereIn('user_id', $followingIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('posts'));
    }
}
