<?php

namespace App\Services\Approval;

use App\Models\Approval;
use App\Models\ApprovalRule;
use App\Models\ApprovalStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class ApprovalService
{
    /**
     * Start approval for an approvable model using rules.
     *
     * @param Model $model The model instance (e.g. Quote)
     * @param string $module module name matching approval_rules.module (e.g. 'quotation')
     * @param int|null $territoryId optional territory id
     * @param float|null $amount optional amount to match thresholds
     * @return Approval
     * @throws Exception
     */
    public function startFromRules(Model $model, string $module, ?int $territoryId = null, ?float $amount = null): Approval
    {
        $amount = $amount ?? ($model->total ?? 0);

        $rules = ApprovalRule::query()
            ->where('module', $module)
            ->where('active', true)
            ->where(function ($q) use ($territoryId) {
                $q->whereNull('territory_id')
                ->orWhere('territory_id', $territoryId);
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('min_amount')
                ->orWhere('min_amount', '<=', $amount);
            })
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')
                ->orWhere('max_amount', '>=', $amount);
            })
            ->orderBy('level')
            ->get();

        if ($rules->isEmpty()) {
            throw new Exception("No approval rules found for module {$module} territory {$territoryId} amount {$amount}");
        }

        return DB::transaction(function () use ($model, $rules) {

            $approval = $model->approval()->create([
                'requested_by' => Auth::id(),
                'approval_status' => 'draft',
            ]);

            foreach ($rules as $rule) {
                ApprovalStep::create([
                    'approval_id' => $approval->id,
                    'approver_id' => $rule->approver_id,
                    'level' => $rule->level,
                ]);
            }

            return $approval;
        });
    }

    /**
     * Approve a step for the given approval and user.
     */
    public function approveStepByUser(
        Approval $approval,
        int $userId,
        ?string $comments = null
    ): bool {
        $step = $approval->steps()
            ->where('approver_id', $userId)
            ->whereIn('approval_status', ['draft', 'pending'])
            ->orderBy('level')
            ->first();

        if (! $step) {
            return false;
        }

        $step->update([
            'approval_status' => 'approved',
            'comments' => $comments,
            'approved_at' => now(),
        ]);

        // ✅ Refresh relationship state
        $approval->refresh();

        if ($approval->isFullyApproved()) {
            $approval->update([
                'approval_status' => 'approved',
                'completed_at' => now(),
            ]);
        }

        return true;
    }


    /**
     * Reject a step by user: mark step rejected & approval rejected.
     */
    public function rejectStepByUser(Approval $approval, int $userId, ?string $comments = null): bool
    {
        $step = $approval->steps()
            ->where('approver_id', $userId)
            ->where('approval_status', 'draft')
            ->orderBy('level')
            ->first();

        if (! $step) {
            return false;
        }

        $step->update([
            'approval_status' => 'rejected',
            'comments' => $comments,
            'approved_at' => now(),
        ]);

        $approval->update(['approval_status' => 'rejected']);
        return true;
    }
}
