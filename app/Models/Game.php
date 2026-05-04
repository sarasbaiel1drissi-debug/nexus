<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['name', 'image_url', 'slug'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('rank', 'preferred_role')->withTimestamps();
    }
}
