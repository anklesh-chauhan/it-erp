<?php

namespace App\Domains\Leave\Handlers;

use App\Models\LeaveApplication;

class LeaveApprovalHandler
{
    public function handle(LeaveApplication $leave, string $status): void
    {
        if ($status === 'approved') {
            $leave->update(['approval_status' => 'approved']);
            $leave->instances()->update(['approval_status' => 'approved']);
        }

        if ($status === 'rejected') {
            $leave->update(['approval_status' => 'rejected']);
            $leave->instances()->update(['approval_status' => 'rejected']);
        }
    }
}
