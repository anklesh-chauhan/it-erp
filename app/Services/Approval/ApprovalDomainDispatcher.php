<?php

namespace App\Services\Approval;

use App\Models\Approval;
use Illuminate\Database\Eloquent\Model;
use LogicException;
use App\Domains\Approval\Contracts\ApprovalHandler;

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
            \App\Domains\Leave\Handlers\LeaveApprovalHandler::class,
            \App\Domains\SalesDocument\Handlers\QuoteApprovalHandler::class,
            // later: scan filesystem or config
        ];
    }

    /**
     * Dispatch approval outcome to domain handler.
     *
     * @throws LogicException
     */
    public function dispatch(Approval $approval): void
    {
        $approvable = $approval->approvable;

        if (! $approvable instanceof Model) {
            return;
        }

        $handlerClass = $this->handlerMap[$approvable::class] ?? null;

        if (! $handlerClass) {
            throw new LogicException(
                "No ApprovalHandler found for". $approvable::class
            );
        }

        app($handlerClass)->handle($approvable, $approval);
    }
}
