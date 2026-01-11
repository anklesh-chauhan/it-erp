<?php

namespace App\Listeners;

use App\Events\ApprovalStatusChanged;
use App\Services\Approval\ApprovalDomainDispatcher;

class ApprovalListener
{
    public function handle(ApprovalStatusChanged $event): void
    {
        app(ApprovalDomainDispatcher::class)
            ->dispatch($event->approval, $event->approval_status);
    }
}
