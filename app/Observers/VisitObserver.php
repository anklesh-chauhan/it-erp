<?php

namespace App\Observers;

use App\Models\Visit;
use App\Models\SalesDcr;
use App\Services\Visit\DcrService;
use App\Services\Expense\ExpenseCalculationService;

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

            $oldDcrId = $visit->getOriginal('sales_dcr_id');

            $dcrService = app(DcrService::class);
            $newDcr = $dcrService->getOrCreateForDate($visit->visit_date);

            $visit->sales_dcr_id = $newDcr->id;

            // Update old DCR count
            if ($oldDcrId && $oldDcrId !== $newDcr->id) {
                SalesDcr::find($oldDcrId)?->updateVisitCount();
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Updated
    |--------------------------------------------------------------------------
    */
    public function updated(Visit $visit): void
    {
        // Update current DCR visit count
        $visit->salesDcr?->updateVisitCount();

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
            $service = app(ExpenseCalculationService::class);

            $dcr = $visit->salesDcr;

            if ($dcr !== null) {
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
