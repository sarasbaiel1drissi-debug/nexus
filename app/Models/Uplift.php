<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uplift extends Model
{
    protected $fillable = ['uplifter_id', 'uplifted_id'];

    public function uplifter()
    {
        return $this->belongsTo(User::class, 'uplifter_id');
    }

    public function uplifted()
    {
        return $this->belongsTo(User::class, 'uplifted_id');
    }
}
