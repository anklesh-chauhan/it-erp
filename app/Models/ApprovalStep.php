<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\HasApprovalWorkflow;

class ApprovalStep extends BaseModel
{
    use HasApprovalWorkflow;

    protected $table = 'approval_steps';

    protected $fillable = ['approval_id','approver_id','level','status','comments','approved_at'];

    public function approval(): BelongsTo
    {
        return $this->belongsTo(Approval::class);
    }


    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }
}
