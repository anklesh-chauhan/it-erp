<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

    public function scopePendingForUser(Builder $query): Builder
    {
        $userId = Auth::id();

        return $query->where('status', 'pending')
                     ->whereHas('steps', function (Builder $stepQuery) use ($userId) {
                         // Find a step that is pending and assigned to the current user
                         $stepQuery->where('status', 'pending')
                                   ->where('approver_id', $userId)
                                   ->orderBy('level')
                                   ->limit(1); // Optimize the subquery
                     });
    }

    public static function scopeSortByStatusPriority(Builder $query, string $direction): Builder
    {
        return $query->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected') $direction");
    }
}
