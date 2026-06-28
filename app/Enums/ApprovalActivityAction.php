<?php

namespace App\Enums;

enum ApprovalActivityAction: string
{
    case Submitted = 'submitted';
    case Assigned = 'assigned';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Skipped = 'skipped';
    case Reassigned = 'reassigned';
    case Escalated = 'escalated';
    case Cancelled = 'cancelled';
}
