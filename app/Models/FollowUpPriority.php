<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasApprovalWorkflow;

class FollowUpPriority extends BaseModel
{
    use HasApprovalWorkflow;

    protected $fillable = ['name'];

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class, 'follow_up_media_id');
    }
}
