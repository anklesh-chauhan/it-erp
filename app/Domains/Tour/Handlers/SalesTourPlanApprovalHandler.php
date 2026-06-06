<?php

namespace App\Domains\Tour\Handlers;

use App\Domains\Approval\Contracts\ApprovalHandler;
use App\Models\Approval;
use App\Models\SalesTourPlan;
use App\Services\Travel\TravelSegmentService;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class SalesTourPlanApprovalHandler implements ApprovalHandler
{
    public function __construct(
        protected TravelSegmentService $travelSegmentService
    ) {}

    public static function supports(): string
    {
        return SalesTourPlan::class;
    }

    public function handle(Model $model, Approval $approval): void
    {
        if (! $model instanceof SalesTourPlan) {
            throw new LogicException('SalesTourPlanApprovalHandler received invalid model: '.$model::class);
        }

        if ($approval->approval_status !== 'approved') {
            return;
        }

        $this->travelSegmentService->regenerateFromTourPlan($model);
    }
}
