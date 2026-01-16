<?php

namespace App\Services\Approval;

use App\Models\Approval;
use App\Models\ApprovalFlow;
use App\Models\ApprovalFlowStep;
use App\Models\ApprovalStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LogicException;

class ApprovalService
{
    // IMPORTANT:
    // Approval steps are skipped if approver role level
    // is less than or equal to applicant role level.
    // This enforces seniority-based approvals (SAP/Salesforce pattern).

    /**
     * Start approval for any approvable model.
     *
     * @throws LogicException
     */
    public function start(
        Model $approvable,
        string $module,
        ?int $territoryId = null,
        ?float $amount = null
    ): Approval {
        $amount ??= $this->resolveAmount($approvable);

        $flow = $this->resolveApprovalFlow($module, $territoryId, $amount);

        return DB::transaction(function () use ($approvable, $flow, $territoryId) {

            /** -------------------------------------------------
             * Resolve applicant job role level
             * ------------------------------------------------- */
            $applicantUser = Auth::user();

            $applicantRoleLevel = $applicantUser?->employee?->positions()
                ->with('jobRole')
                ->get()
                ->pluck('jobRole.level')
                ->min(); // lowest = most junior position

            // If applicant has no role, assume top-level
            $applicantRoleLevel ??= PHP_INT_MAX;

            /** -------------------------------------------------
             * Create approval record
             * ------------------------------------------------- */
            $approval = $approvable->approval()->create([
                'approval_flow_id' => $flow->id,
                'requested_by'     => Auth::id(),
                'approval_status'  => 'pending',
            ]);

            /** -------------------------------------------------
            * Build approval steps
            * ------------------------------------------------- */
            foreach ($flow->steps as $flowStep) {

                $stepRole = $flowStep->jobRole;

                // 🔑 Skip same or junior roles
                if ($stepRole->level <= $applicantRoleLevel) {
                    continue;
                }

                $user = $this->resolveApprover(
                    $flowStep->job_role_id,
                    $territoryId
                );

                // If no approver found and can_skip = true → skip
                if (! $user && $flowStep->can_skip) {
                    continue;
                }

                ApprovalStep::create([
                    'approval_id'      => $approval->id,
                    'step_order'       => $flowStep->step_order,
                    'job_role_id'      => $flowStep->job_role_id,
                    'assigned_user_id' => $user?->id,
                    'status'           => 'pending',
                ]);
            }

            /** -------------------------------------------------
             * Auto-approve if no steps remain
             * ------------------------------------------------- */
            if ($approval->steps()->count() === 0) {
                $approval->update([
                    'approval_status' => 'approved',
                    'completed_at'    => now(),
                ]);

                app(ApprovalDomainDispatcher::class)
                    ->dispatch($approval);
            }

            return $approval;
        });
    }

    /**
     * Approve a pending step by the logged-in user.
     */
    public function approve(
        Approval $approval,
        int $userId,
        ?string $comments = null
    ): bool {
        return DB::transaction(function () use ($approval, $userId, $comments) {

            $step = $approval->steps()
                ->where('assigned_user_id', $userId)
                ->where('status', 'pending')
                ->orderBy('step_order')
                ->first();

            if (! $step) {
                return false;
            }

            $step->update([
                'status'      => 'approved',
                'comments'    => $comments,
                'actioned_at' => now(),
            ]);

            if ($approval->isFullyApproved()) {
                $approval->update([
                    'approval_status' => 'approved',
                    'completed_at'    => now(),
                ]);

                app(ApprovalDomainDispatcher::class)
                    ->dispatch($approval);
            }

            return true;
        });
    }

    /**
     * Reject a pending approval step.
     */
    public function reject(
        Approval $approval,
        int $userId,
        ?string $comments = null
    ): bool {
        return DB::transaction(function () use ($approval, $userId, $comments) {

            $step = $approval->steps()
                ->where('assigned_user_id', $userId)
                ->where('status', 'pending')
                ->first();

            if (! $step) {
                return false;
            }

            $step->update([
                'status'      => 'rejected',
                'comments'    => $comments,
                'actioned_at' => now(),
            ]);

            $approval->update([
                'approval_status' => 'rejected',
                'completed_at'    => now(),
            ]);

            app(ApprovalDomainDispatcher::class)
                ->dispatch($approval);

            return true;
        });
    }

    /**
     * Cancel approval (optional use case).
     */
    public function cancel(Approval $approval): void
    {
        DB::transaction(function () use ($approval) {
            $approval->steps()->update([
                'status' => 'skipped',
            ]);

            $approval->update([
                'approval_status' => 'draft',
                'completed_at'    => null,
            ]);
        });
    }

    /* =====================================================
     | Internal Helpers
     ===================================================== */

    protected function resolveApprovalFlow(
        string $module,
        ?int $territoryId,
        float $amount
    ): ApprovalFlow {
        $flow = ApprovalFlow ::query()
            ->where('module', $module)
            ->where('active', true)
            ->where(fn ($q) =>
                $q->whereNull('territory_id')
                  ->orWhere('territory_id', $territoryId)
            )
            ->where(fn ($q) =>
                $q->whereNull('min_amount')
                  ->orWhere('min_amount', '<=', $amount)
            )
            ->where(fn ($q) =>
                $q->whereNull('max_amount')
                  ->orWhere('max_amount', '>=', $amount)
            )
            ->with('steps')
            ->orderByDesc('territory_id') // territory-specific wins
            ->first();

        if (! $flow) {
            throw new LogicException(
                "No approval flow found for module [{$module}]"
            );
        }

        return $flow;
    }

    protected function resolveApprover(
        int $jobRoleId,
        ?int $territoryId
        ): ?\App\Models\User {
            $query = \App\Models\User::query()
                ->whereHas('employee.positions', fn ($q) =>
                    $q->where('job_role_id', $jobRoleId)
                );

            // Apply territory filter ONLY if territory exists
            if ($territoryId !== null) {
                $query->whereHas('employee.positions.territories', fn ($t) =>
                    $t->where('territories.id', $territoryId)
                );
            }

            return $query->first();
    }

    protected function resolveAmount(Model $approvable): float
    {
        return match (true) {
            isset($approvable->total)        => (float) $approvable->total,
            isset($approvable->amount)       => (float) $approvable->amount,
            isset($approvable->expected_value) => (float) $approvable->expected_value,
            default                          => 0.0,
        };
    }
}
