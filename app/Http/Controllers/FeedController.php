<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::withAll()
            ->visibleTo(Auth::user())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard', compact('posts'));
    }
}
