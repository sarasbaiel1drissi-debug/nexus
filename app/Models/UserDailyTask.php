<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDailyTask extends Model
{
    protected $fillable = ['user_id', 'daily_task_id', 'assigned_date', 'completed'];

    protected $casts = [
        'assigned_date' => 'date',
        'completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dailyTask()
    {
        return $this->belongsTo(DailyTask::class);
    }
}
