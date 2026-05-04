<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'bio', 'location', 'birthdate', 'gender',
        'profile_picture', 'cover_picture', 'password',
        'playstyle_tags', 'languages', 'region', 'availability',
        'is_online', 'cover_position', 'is_admin', 'xp',
        'theme_color', 'theme_bg', 'theme_text', 'theme_bg_image',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'playstyle_tags' => 'array',
            'is_online' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $relationMethods = [];
            foreach ($relationMethods as $relationMethod) {
                if ($model->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    // ── Accessors ──────────────────────────────────

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture
            ? asset('storage/' . $this->profile_picture)
            : asset('images/default-avatar.png');
    }

    public function getUpliftCountAttribute()
    {
        return Uplift::where('uplifted_id', $this->id)->count();
    }

    public function getUnreadMessageCountAttribute()
    {
        return Message::whereHas('conversation', function ($q) {
            $q->where('user_one_id', $this->id)->orWhere('user_two_id', $this->id);
        })->where('sender_id', '!=', $this->id)
          ->whereNull('read_at')
          ->count();
    }

    // ── Helpers ────────────────────────────────────

    public function isFollowing($userId)
    {
        return Follow::where('follower_id', $this->id)->where('following_id', $userId)->exists();
    }

    public function hasUplifted($userId)
    {
        return Uplift::where('uplifter_id', $this->id)->where('uplifted_id', $userId)->exists();
    }

    // ── Relationships ─────────────────────────────

    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withTimestamps()->withPivot('earned_at', 'expires_at');
    }

    public function dailyTasks()
    {
        return $this->hasMany(UserDailyTask::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function games()
    {
        return $this->belongsToMany(Game::class)->withPivot('rank', 'preferred_role')->withTimestamps();
    }

    public function conversationsAsUserOne()
    {
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function conversationsAsUserTwo()
    {
        return $this->hasMany(Conversation::class, 'user_two_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Follows
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    // Uplifts
    public function upliftsReceived()
    {
        return $this->hasMany(Uplift::class, 'uplifted_id');
    }

    public function upliftsGiven()
    {
        return $this->hasMany(Uplift::class, 'uplifter_id');
    }

    // Notifications
    public function appNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function audienceLists()
    {
        return $this->hasMany(AudienceList::class);
    }
}
