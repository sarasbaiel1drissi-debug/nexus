<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AudienceListMember extends Model
{
    protected $fillable = ['audience_list_id', 'user_id'];

    public function audienceList()
    {
        return $this->belongsTo(AudienceList::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
