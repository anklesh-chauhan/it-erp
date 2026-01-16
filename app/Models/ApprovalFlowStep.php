<?php

namespace App\Models;

use App\Models\BaseModel;

class ApprovalFlowStep extends BaseModel
{
    protected $fillable = [
        'approval_flow_id',
        'step_order',
        'job_role_id',
        'territory_scope',
        'can_skip',
    ];

    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class);
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }
}
