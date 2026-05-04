<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'order',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    // This media belongs to a post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Helper to get full URL for the file
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    // Helper for image dimensions
    public function getDimensions()
    {
        if ($this->metadata && isset($this->metadata['width'])) {
            return $this->metadata['width'] . 'x' . $this->metadata['height'];
        }
        return null;
    }
}
