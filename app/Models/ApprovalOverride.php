<?php

namespace App\Models;

use App\Models\BaseModel;

class ApprovalOverride extends BaseModel
{
    protected $fillable = [
        'model_type',
        'model_id',
        'user_id',
        'reason',
    ];
}
