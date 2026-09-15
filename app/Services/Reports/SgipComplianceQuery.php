<?php

namespace App\Services\Reports;

use App\Models\SgipViolation;
use Illuminate\Database\Eloquent\Builder;

class SgipComplianceQuery
{
    public function builder(): Builder
    {
        return SgipViolation::query()
            ->with([
                'distribution.doctor',
                'distribution.user',
                'distribution.territory',
                'distribution.marketingCampaign',
                'limit',
            ])
            ->whereHas(
                'distribution',
                fn (Builder $query): Builder => $query->applyVisibility('SgipDistribution')
            );
    }
}
