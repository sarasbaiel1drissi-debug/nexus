<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    
    protected $guarded = [];
    
    protected $casts = [
        'criteria' => 'array',
    ];

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

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps()
                ->withPivot('earned_at', 'expires_at');
    }
}
