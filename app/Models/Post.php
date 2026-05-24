<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'content',
        'privacy'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // A post belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A post has many media files (photos/videos)
    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    // A post has many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // A post has many likes
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Helper method to check if a user liked this post
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    // Helper to get post with all relationships
    public function scopeWithAll($query)
    {
        return $query->with(['user', 'media', 'comments.user', 'likes']);
    }

    public function scopeVisibleTo($query, ?User $viewer)
    {
        return $query->where(function ($visibility) use ($viewer) {
            $visibility->where('privacy', 'public');

            if ($viewer) {
                $visibility->orWhere('user_id', $viewer->id)
                    ->orWhere(function ($followersOnly) use ($viewer) {
                        $followersOnly->whereIn('privacy', ['followers', 'friends'])
                            ->whereHas('user.followers', function ($followers) use ($viewer) {
                                $followers->where('follower_id', $viewer->id);
                            });
                    });
            }
        });
    }
}
