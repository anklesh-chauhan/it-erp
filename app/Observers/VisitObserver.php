<?php

namespace App\Observers;

use App\Models\SalesDcr;
use App\Models\Visit;
use App\Services\Expense\ExpenseCalculationService;
use App\Services\Travel\TravelSegmentService;
use App\Services\Visit\DcrService;

class VisitObserver
{
    /*
    |--------------------------------------------------------------------------
    | Creating
    |--------------------------------------------------------------------------
    */
    public function creating(Visit $visit): void
    {
        if (! $visit->sales_dcr_id && $visit->visit_date !== null) {
            $dcrService = app(DcrService::class);
            $dcr = $dcrService->getOrCreateForDate($visit->visit_date);

            $visit->sales_dcr_id = $dcr->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Created
    |--------------------------------------------------------------------------
    */
    public function created(Visit $visit): void
    {
        $visit->salesDcr?->updateVisitCount();
    }

    /*
    |--------------------------------------------------------------------------
    | Updating
    |--------------------------------------------------------------------------
    */
    public function updating(Visit $visit): void
    {
        /*
        |--------------------------------------------------------------------------
        | Handle Visit Date Change (Move to another DCR)
        |--------------------------------------------------------------------------
        */
        if ($visit->isDirty('visit_date') && $visit->visit_date !== null) {
            $dcrService = app(DcrService::class);
            $newDcr = $dcrService->getOrCreateForDate($visit->visit_date);

            $visit->sales_dcr_id = $newDcr->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Updated
    |--------------------------------------------------------------------------
    */
    public function updated(Visit $visit): void
    {
        $visit->unsetRelation('salesDcr');
        $visit->salesDcr?->updateVisitCount();

        if ($visit->wasChanged('sales_dcr_id')) {
            $previousDcrId = $visit->getPrevious()['sales_dcr_id'] ?? null;

            if ($previousDcrId && (int) $previousDcrId !== (int) $visit->sales_dcr_id) {
                SalesDcr::query()->find($previousDcrId)?->updateVisitCount();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Trigger Expense Auto Calculation
        |--------------------------------------------------------------------------
        */

        if (
            $visit->wasChanged('visit_status') &&
            $visit->visit_status === 'completed' &&
            $visit->sales_dcr_id
        ) {
            $travelSegmentService = app(TravelSegmentService::class);
            $service = app(ExpenseCalculationService::class);

            $dcr = $visit->salesDcr;

            if ($dcr !== null) {
                $travelSegmentService->generateFromActualVisits($dcr);
                $service->autoCalculateDcrExpenses($dcr);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Deleted
    |--------------------------------------------------------------------------
    */
    public function deleted(Visit $visit): void
    {
        $visit->salesDcr?->updateVisitCount();
    }
}
