<?php

namespace App\Enums;

use LogicException;

enum ApprovalStepStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Skipped = 'skipped';
    case Reassigned = 'reassigned';
    case Cancelled = 'cancelled';

    public function canTransitionTo(self $status): bool
    {
        return match ($this) {
            self::Pending => in_array($status, [
                self::Approved,
                self::Rejected,
                self::Skipped,
                self::Reassigned,
                self::Cancelled,
            ], true),
            default => false,
        };
    }

    public function assertCanTransitionTo(self $status): void
    {
        if (! $this->canTransitionTo($status)) {
            throw new LogicException("Approval step cannot transition from [{$this->value}] to [{$status->value}].");
        }
    }
}
