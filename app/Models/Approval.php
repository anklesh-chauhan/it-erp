<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\HasApprovalWorkflow;

class Approval extends Model
{
    use HasApprovalWorkflow;

    protected $table = 'approvals';

    protected $fillable = ['approvable_type','approvable_id','requested_by','status','completed_at'];

    public function approvable(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalStep::class);
    }

    public function currentStep()
    {
        return $this->steps()->where('status', 'pending')->orderBy('level')->first();
    }

    public function isFullyApproved(): bool
    {
        return $this->steps()->where('status', '!=', 'approved')->count() === 0;
    }
}
