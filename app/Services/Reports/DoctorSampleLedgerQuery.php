<?php

namespace App\Services\Reports;

use App\Enums\ItemType;
use App\Models\SgipDistributionItem;
use Illuminate\Database\Eloquent\Builder;

class DoctorSampleLedgerQuery
{
    public function builder(): Builder
    {
        return SgipDistributionItem::query()
            ->with([
                'distribution.doctor',
                'distribution.user.employee',
                'distribution.territory',
                'distribution.marketingCampaign',
                'distribution.violations',
                'item',
            ])
            ->whereHas(
                'item',
                fn (Builder $query): Builder => $query->whereIn('item_type', ItemType::cases()),
            )
            ->whereHas(
                'distribution',
                fn (Builder $query): Builder => $query
                    ->whereIn('approval_status', ['submitted', 'approved'])
                    ->applyVisibility('SgipDistribution'),
            );
    }
}
