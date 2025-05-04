<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FollowUpStatus extends Model
{
    protected $fillable = ['name'];

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'follow_up_media_id');
    }
}
