<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    protected $fillable = ['title', 'description', 'xp', 'category', 'auto_complete_rule'];
}
