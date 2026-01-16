<?php

namespace App\Services\Attendance;

use Illuminate\Support\Facades\DB;
use App\Models\LeaveApplication;
use App\Services\Approval\ApprovalService;

class LeaveWorkflowService
{
    /**
     * Start approval workflow for leave application.
     *
     * @param array $ruleResult Result from LeaveRuleEvaluatorService
     */
    public function start(
        LeaveApplication $leave,
        array $ruleResult
    ): void {
        DB::transaction(function () use ($leave, $ruleResult) {

            // Domain state only (NOT approval logic)
            $leave->update([
                'approval_status' => 'draft',
                'applied_at'      => now(),
            ]);

            // Start generic approval workflow
            app(ApprovalService::class)->start(
                approvable: $leave,
                module: 'LeaveApplication',
                territoryId: $leave->employee->territory_id ?? null,
                amount: null
            );

            // ✅ Notifications MUST be rule-driven
            app(LeaveNotificationService::class)
                ->dispatch(
                    event: 'LEAVE_APPLIED',
                    leave: $leave,
                    ruleResult: $ruleResult
                );
        });
    }

    /**
     * Approve via email (signed URL).
     */
    public function approveFromEmail(int $approvalStepId): void
    {
        DB::transaction(function () use ($approvalStepId) {

            $step = \App\Models\ApprovalStep::findOrFail($approvalStepId);
            $approval = $step->approval;

            app(ApprovalService::class)
                ->approve($approval, $step->assigned_user_id);

            // ❌ No notifications here
            // Approval handlers + orchestrators decide notifications
        });
    }

    /**
     * Reject via email (signed URL).
     */
    public function rejectFromEmail(int $approvalStepId): void
    {
        DB::transaction(function () use ($approvalStepId) {

            $step = \App\Models\ApprovalStep::findOrFail($approvalStepId);
            $approval = $step->approval;

            app(ApprovalService::class)
                ->reject($approval, $step->assigned_user_id);

            // ❌ No notifications here
        });
    }
}
