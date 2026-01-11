<?php

namespace App\Services\Attendance;

use Illuminate\Support\Facades\DB;
use App\Models\LeaveApplication;
use App\Services\Approval\ApprovalService;

class LeaveWorkflowService
{
    /**
     * Start workflow for leave application
     */
    public function start(LeaveApplication $leave): void
    {
        DB::transaction(function () use ($leave) {

            // Mark leave as draft
            $leave->update([
                'approval_status' => 'draft',
                'applied_at' => now(),
            ]);

            // Start approval workflow
            app(ApprovalService::class)->startFromRules(
                model: $leave,
                module: 'LeaveApplication',
                territoryId: $leave->employee->territory_id ?? null,
                amount: null
            );

            // Notify managers (rule based)
            app(LeaveNotificationService::class)
                ->dispatch('LEAVE_APPLIED', $leave);
        });
    }

    /**
     * Approve via email (signed URL)
     */
    public function approveFromEmail(int $approvalStepId): void
    {
        DB::transaction(function () use ($approvalStepId) {

            $step = \App\Models\ApprovalStep::findOrFail($approvalStepId);
            $approval = $step->approval;
            $leave = $approval->approvable;

            app(ApprovalService::class)
                ->approveStepByUser($approval, $step->approver_id);

            $approval->refresh();

            if ($approval->approval_status === 'approved') {
                $leave->update(['approval_status' => 'approved']);
                $leave->instances()->update(['approval_status' => 'approved']);

                app(LeaveNotificationService::class)
                    ->dispatch('LEAVE_APPROVED', $leave);
            }
        });
    }

    /**
     * Reject via email (signed URL)
     */
    public function rejectFromEmail(int $approvalStepId): void
    {
        DB::transaction(function () use ($approvalStepId) {

            $step = \App\Models\ApprovalStep::findOrFail($approvalStepId);
            $approval = $step->approval;
            $leave = $approval->approvable;

            app(ApprovalService::class)
                ->rejectStepByUser($approval, $step->approver_id);

            $leave->update(['approval_status' => 'rejected']);
            $leave->instances()->update(['approval_status' => 'rejected']);

            app(LeaveNotificationService::class)
                ->dispatch('LEAVE_REJECTED', $leave);
        });
    }
}
