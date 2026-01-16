<?php

namespace App\Domains\Leave\Handlers;

use App\Models\Approval;
use App\Models\LeaveApplication;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use App\Domains\Approval\Contracts\ApprovalHandler;
use App\Services\Attendance\LeaveBalanceCalculator;
use Illuminate\Support\Facades\Cache;

class LeaveApprovalHandler implements ApprovalHandler
{
    public static function supports(): string
    {
        return LeaveApplication::class;
    }

    /**
     * Handle approval outcome for LeaveApplication.
     */
    public function handle(Model $model, Approval $approval): void
    {
        if (! $model instanceof LeaveApplication) {
            throw new LogicException(
                'LeaveApprovalHandler received invalid model: ' . $model::class
            );
        }

        match ($approval->approval_status) {
            'approved' => $this->approve($model),
            'rejected' => $this->reject($model),
            default    => null, // draft / pending → no action
        };
    }

    protected function approve(LeaveApplication $leave): void
    {
        // 🛑 Idempotency guard
        if ($leave->approval_status === 'approved') {
            return;
        }

        // 1️⃣ Update domain state
        $leave->update([
            'approval_status' => 'approved',
        ]);

        $leave->instances()->update([
            'approval_status' => 'approved',
        ]);

        // 2️⃣ OPTIONAL: warm / invalidate balance cache
        Cache::forget(
            "leave_balance:{$leave->employee_id}:{$leave->leave_type_id}"
        );

        // 3️⃣ OPTIONAL: eager recompute (read-only)
        app(LeaveBalanceCalculator::class)->calculate(
            employeeId: $leave->employee_id,
            leaveTypeId: $leave->leave_type_id,
            asOnDate: now()
        );
    }

    protected function reject(LeaveApplication $leave): void
    {
        // 🛑 Idempotency guard
        if ($leave->approval_status === 'rejected') {
            return;
        }

        $leave->update([
            'approval_status' => 'rejected',
        ]);

        $leave->instances()->update([
            'approval_status' => 'rejected',
        ]);

        // Clear cache if any pending impact was shown
        Cache::forget(
            "leave_balance:{$leave->employee_id}:{$leave->leave_type_id}"
        );
    }
}
