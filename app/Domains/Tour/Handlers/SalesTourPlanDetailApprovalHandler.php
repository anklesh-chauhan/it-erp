<?php

namespace App\Domains\Tour\Handlers;

use App\Domains\Approval\Contracts\ApprovalHandler;
use App\Models\Approval;
use App\Models\SalesTourPlanDetail;
use App\Services\Travel\TravelSegmentService;
use Illuminate\Database\Eloquent\Model;
use LogicException;

class SalesTourPlanDetailApprovalHandler implements ApprovalHandler
{
    public function __construct(
        protected TravelSegmentService $travelSegmentService
    ) {}

    public static function supports(): string
    {
        return SalesTourPlanDetail::class;
    }

    public function handle(Model $model, Approval $approval): void
    {
        if (! $model instanceof SalesTourPlanDetail) {
            throw new LogicException('SalesTourPlanDetailApprovalHandler received invalid model: '.$model::class);
        }

        if ($approval->approval_status !== 'approved' || $model->tourPlan === null) {
            return;
        }

        $this->travelSegmentService->generateFromTourPlanDetail($model->tourPlan, $model);
    }
}
