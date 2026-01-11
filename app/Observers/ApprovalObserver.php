<?php

namespace App\Observers;

use App\Models\Approval;
use App\Events\ApprovalStatusChanged;

class ApprovalObserver
{
    public function updated(Approval $approval): void
    {
        if (! in_array($approval->approval_status, ['approved', 'rejected'])) {
            return;
        }

        event(new ApprovalStatusChanged($approval, $approval->approval_status));
    }
}
