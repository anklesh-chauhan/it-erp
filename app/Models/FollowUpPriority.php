<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasApprovalWorkflow;

class FollowUpPriority extends Model
{
    use HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'follow_up_media_id');
    }
}
