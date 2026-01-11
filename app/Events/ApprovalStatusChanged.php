<?php

namespace App\Events;

use App\Models\Approval;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApprovalStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Approval $approval,
        public string $approval_status
    ) {}
}
