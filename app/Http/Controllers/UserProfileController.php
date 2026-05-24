<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show(string $username): View
    {
        $user = User::with(['games', 'followers.follower', 'following.following', 'upliftsReceived', 'badges'])
            ->where('username', $username)
            ->firstOrFail();

        $posts = Post::withAll()
                    ->visibleTo(Auth::user())
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('profile.show', compact('user', 'posts'));
    }
}
