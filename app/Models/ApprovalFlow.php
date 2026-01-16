<?php

namespace App\Models;

use App\Models\BaseModel;

class ApprovalFlow extends BaseModel
{
    protected $fillable = [
        'module',
        'territory_id',
        'min_amount',
        'max_amount',
        'active',
    ];

    public function steps()
    {
        return $this->hasMany(ApprovalFlowStep::class)
            ->orderBy('step_order');
    }

    public function territory()
    {
        return $this->belongsTo(Territory::class);
    }
}
