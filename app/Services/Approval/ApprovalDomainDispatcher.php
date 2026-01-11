<?php

namespace App\Services\Approval;

use App\Models\Approval;
use App\Domains\Leave\Handlers\LeaveApprovalHandler;
use App\Domains\SalesDocument\Handlers\QuoteApprovalHandler;

class ApprovalDomainDispatcher
{
    protected array $handlers = [
        \App\Models\LeaveApplication::class => LeaveApprovalHandler::class,
        \App\Models\Quote::class            => QuoteApprovalHandler::class,
        // \App\Models\Invoice::class          => InvoiceApprovalHandler::class,
    ];

    public function dispatch(Approval $approval, string $approval_status): void
    {
        $approvable = $approval->approvable;

        if (! $approvable) {
            return;
        }

        $handlerClass = $this->handlers[get_class($approvable)] ?? null;

        if (! $handlerClass) {
            return; // silently ignore
        }

        app($handlerClass)->handle($approvable, $approval_status);
    }
}
