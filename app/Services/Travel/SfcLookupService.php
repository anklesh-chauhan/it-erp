<?php

namespace App\Services\Travel;

use App\Models\StandardFareChart;

class SfcLookupService
{
    public function findDistance(
        int $fromAreaTownId,
        int $toAreaTownId,
        ?int $territoryId = null
    ): ?float {
        [$cityAId, $cityBId] = StandardFareChart::normalizeCityPair($fromAreaTownId, $toAreaTownId);

        $baseQuery = StandardFareChart::query()
            ->where('from_area_town_id', $cityAId)
            ->where('to_area_town_id', $cityBId)
            ->where('is_active', true)
            ->whereNotNull('distance_km')
            ->where('distance_km', '>', 0);

        if ($territoryId !== null) {
            $territoryMatch = (clone $baseQuery)
                ->where('territory_id', $territoryId)
                ->first();

            if ($territoryMatch !== null) {
                return (float) $territoryMatch->distance_km;
            }
        }

        $globalMatch = (clone $baseQuery)
            ->whereNull('territory_id')
            ->first();

        return $globalMatch ? (float) $globalMatch->distance_km : null;
    }
}
