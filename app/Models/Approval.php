<?php

namespace App\Models;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use App\Traits\HasApprovalWorkflow;

class Approval extends BaseModel
{
    use HasApprovalWorkflow;

    protected $table = 'approvals';

    protected $fillable = ['approvable_type','approvable_id','requested_by','approval_status','completed_at'];

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
        return $this->steps()->where('approval_status', 'draft')->orderBy('level')->first();
    }

    public function isFullyApproved(): bool
    {
        return $this->steps()->where('approval_status', '!=', 'approved')->count() === 0;
    }

    public function scopeDraftForUser(Builder $query): Builder
    {
        $userId = Auth::id();

        return $query->where('approval_status', 'draft')
                     ->whereHas('steps', function (Builder $stepQuery) use ($userId) {
                         // Find a step that is draft and assigned to the current user
                         $stepQuery->where('approval_status', 'draft')
                                   ->where('approver_id', $userId)
                                   ->orderBy('level')
                                   ->limit(1); // Optimize the subquery
                     });
    }

    public static function scopeSortByStatusPriority(Builder $query, string $direction): Builder
    {
        return $query->orderByRaw("FIELD(approval_status, 'draft', 'approved', 'rejected') $direction");
    }
}
