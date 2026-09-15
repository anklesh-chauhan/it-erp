<?php

namespace App\Services\Reports;

use App\Models\SalesTourPlanDetail;
use Illuminate\Database\Eloquent\Builder;

class VisitCoverageQuery
{
    public function builder(): Builder
    {
        return SalesTourPlanDetail::query()
            ->with([
                'tourPlan.user',
                'territory',
                'visitType',
                'patches.companies',
                'visits.visitables',
            ]);
    }
}
