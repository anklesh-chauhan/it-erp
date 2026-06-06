<?php

namespace App\Services\Expense;

use App\Models\CityPinCode;
use App\Models\SalesDcr;

trait BuildsExpenseContext
{
    protected function buildExpenseContext(SalesDcr $dcr, array $extra = []): array
    {
        $dcr->loadMissing([
            'travelSegments.toAreaTown.city.cityClass',
            'travelSegments.fromAreaTown.city.cityClass',
            'visits',
            'user.employee.positions',
        ]);

        $areaTown = $this->resolveAreaTown($dcr);
        $distanceContext = $this->resolveDistanceContext($dcr);

        return array_merge([
            'visit_count' => $dcr->visits_count ?? $dcr->visits()->count(),
            'distance' => $distanceContext['distance'],
            'distance_source' => $distanceContext['source'],

            'travel_type' => $this->resolveTravelType($dcr),
            'is_hill_station' => $areaTown?->is_hill_station ?? false,
            'city_class' => $areaTown?->cityClass?->name,

            'territory_id' => $dcr->territory_id,
            'joint_work' => $dcr->visits->where('is_joint_work', true)->isNotEmpty(),
        ], $extra);
    }

    protected function resolveAreaTown(SalesDcr $dcr): ?CityPinCode
    {
        $segment = $dcr->travelSegments
            ->whereNotNull('to_area_town_id')
            ->sortByDesc('id')
            ->first();

        if ($segment?->toAreaTown) {
            return $segment->toAreaTown;
        }

        $segment = $dcr->travelSegments
            ->whereNotNull('from_area_town_id')
            ->sortByDesc('id')
            ->first();

        return $segment?->fromAreaTown;
    }

    protected function resolveTravelType(SalesDcr $dcr): string
    {
        $visitTerritoryIds = $dcr->visits
            ->pluck('territory_id')
            ->filter()
            ->unique()
            ->values();

        if ($visitTerritoryIds->isEmpty()) {
            return 'hq';
        }

        $primaryPosition = $dcr->user
            ?->employee
            ?->positions()
            ->wherePivot('is_primary', true)
            ->first();

        if ($primaryPosition?->hq_territory_id &&
            $visitTerritoryIds->every(fn ($id) => (int) $id === $primaryPosition->hq_territory_id)) {
            return 'hq';
        }

        return $dcr->is_overnight ? 'outstation' : 'ex_hq';
    }

    protected function resolveDistanceContext(SalesDcr $dcr): array
    {
        $segments = $dcr->travelSegments;

        if ($segments->isNotEmpty()) {
            $total = (float) $segments->sum('distance_km');

            return [
                'distance' => round($total, 2),
                'source' => 'segments',
            ];
        }

        return [
            'distance' => (float) ($dcr->distance_covered ?? 0),
            'source' => 'fallback',
        ];
    }
}
