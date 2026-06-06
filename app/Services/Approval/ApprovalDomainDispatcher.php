<?php

namespace App\Services\Approval;

use App\Domains\Approval\Contracts\ApprovalHandler;
use App\Domains\Leave\Handlers\LeaveApprovalHandler;
use App\Domains\SalesDocument\Handlers\QuoteApprovalHandler;
use App\Domains\Tour\Handlers\SalesTourPlanApprovalHandler;
use App\Domains\Tour\Handlers\SalesTourPlanDetailApprovalHandler;
use App\Models\Approval;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class ApprovalDomainDispatcher
{
    protected array $handlerMap = [];

    public function __construct()
    {
        $this->discoverHandlers();
    }

    protected function discoverHandlers(): void
    {
        foreach ($this->resolveAllHandlers() as $handlerClass) {
            if (! is_subclass_of($handlerClass, ApprovalHandler::class)) {
                continue;
            }

            $supportedModel = $handlerClass::supports();

            $this->handlerMap[$supportedModel] = $handlerClass;
        }
    }

    protected function resolveAllHandlers(): array
    {
        return [
            LeaveApprovalHandler::class,
            QuoteApprovalHandler::class,
            SalesTourPlanApprovalHandler::class,
            SalesTourPlanDetailApprovalHandler::class,
            // later: scan filesystem or config
        ];
    }

    /**
     * Dispatch approval outcome to domain handler.
     *
     * @throws LogicException
     */
    public function dispatch(Approval $approval, ?string $approvalStatus = null): void
    {
        $approvable = $approval->approvable;

        if (! $approvable instanceof Model) {
            return;
        }

        $handlerClass = $this->handlerMap[$approvable::class] ?? null;

        if (! $handlerClass) {
            throw new LogicException(
                'No ApprovalHandler found for'.$approvable::class
            );
        }

        app($handlerClass)->handle($approvable, $approval);
    }
}
