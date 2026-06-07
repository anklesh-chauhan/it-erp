<?php

namespace App\Services\Travel;

use App\Models\CityPinCode;
use App\Models\StandardFareChart;

class SfcDistanceService
{
    public function __construct(
        protected GoogleRoutesService $googleRoutesService
    ) {}

    public function populateChartDistance(StandardFareChart $chart, bool $force = false): bool
    {
        if (
            ! $force
            && filled($chart->distance_km)
            && (float) $chart->distance_km > 0
            && $chart->distance_source === 'manual'
        ) {
            return false;
        }

        $distance = $this->resolveDistance(
            (int) $chart->from_area_town_id,
            (int) $chart->to_area_town_id
        );

        if ($distance === null) {
            return false;
        }

        $chart->forceFill([
            'distance_km' => $distance,
            'distance_source' => 'google_routes',
        ])->saveQuietly();

        return true;
    }

    public function resolveDistance(int $fromAreaTownId, int $toAreaTownId): ?float
    {
        $from = CityPinCode::query()->with('city')->find($fromAreaTownId);
        $to = CityPinCode::query()->with('city')->find($toAreaTownId);

        if ($from === null || $to === null) {
            return null;
        }

        return $this->googleRoutesService->computeDistanceBetweenAreaTowns($from, $to);
    }
}
