<?php

namespace App\Models;

class ApprovalFlowStep extends BaseModel
{
    protected $fillable = [
        'approval_flow_id',
        'step_order',
        'job_role_id',
        'territory_scope',
        'can_skip',
        'sla_hours',
    ];

    protected function casts(): array
    {
        return [
            'can_skip' => 'boolean',
            'sla_hours' => 'integer',
        ];
    }

    public function flow()
    {
        return $this->belongsTo(ApprovalFlow::class);
    }

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class);
    }
}
