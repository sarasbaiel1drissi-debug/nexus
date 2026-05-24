<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Services\TaskAutoCompleteService;

class PostInteraction extends Component
{
    public Post $post;
    public $commentText = '';
    public $showComments = false;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function toggleLike()
    {
        $existing = Like::where('user_id', auth()->id())
            ->where('post_id', $this->post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->post->forceFill(['likes_count' => $this->post->likes()->count()])->save();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'post_id' => $this->post->id,
            ]);

            $this->post->forceFill(['likes_count' => $this->post->likes()->count()])->save();
            app(TaskAutoCompleteService::class)->check(Auth::user(), 'like');

            // Notify post owner (not self)
            if ($this->post->user_id !== auth()->id()) {
                Notification::create([
                    'user_id' => $this->post->user_id,
                    'type' => 'like',
                    'from_user_id' => auth()->id(),
                    'post_id' => $this->post->id,
                ]);
            }
        }
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function addComment()
    {
        $this->validate([
            'commentText' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $this->post->id,
            'content' => $this->commentText,
        ]);

        $this->post->forceFill(['comments_count' => $this->post->comments()->count()])->save();
        app(TaskAutoCompleteService::class)->check(Auth::user(), 'comment');

        // Notify post owner (not self)
        if ($this->post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $this->post->user_id,
                'type' => 'comment',
                'from_user_id' => auth()->id(),
                'post_id' => $this->post->id,
            ]);
        }

        $this->commentText = '';
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && ($comment->user_id === auth()->id() || $this->post->user_id === auth()->id())) {
            $comment->delete();
            $this->post->forceFill(['comments_count' => $this->post->comments()->count()])->save();
        }
    }

    public function render()
    {
        $this->post->refresh();
        $likesCount = $this->post->likes()->count();
        $isLiked = $this->post->likes()->where('user_id', auth()->id())->exists();
        $comments = $this->post->comments()->with('user')->oldest()->get();
        $commentsCount = $comments->count();

        return view('livewire.post-interaction', [
            'likesCount' => $likesCount,
            'isLiked' => $isLiked,
            'comments' => $comments,
            'commentsCount' => $commentsCount,
        ]);
    }
}
