<?php

namespace App\Services\Travel;

use App\Models\CityPinCode;
use App\Models\TravelSegment;
use Illuminate\Support\Facades\DB;

class DistanceResolutionService
{
    public function __construct(
        protected SfcLookupService $sfcLookupService
    ) {}

    /**
     * @return array{distance_km: float, distance_source: string}
     */
    public function resolve(TravelSegment $segment): array
    {
        $patchDistance = $this->getPatchDistance($segment->from_account_id, $segment->to_account_id, $segment->patch_id);
        if ($patchDistance !== null) {
            return ['distance_km' => $patchDistance, 'distance_source' => 'patch'];
        }

        if ($segment->from_area_town_id !== null && $segment->to_area_town_id !== null) {
            $directDistance = $this->sfcLookupService->findDistance(
                (int) $segment->from_area_town_id,
                (int) $segment->to_area_town_id,
                $segment->salesDcr?->territory_id
            );

            if ($directDistance !== null) {
                return ['distance_km' => $directDistance, 'distance_source' => 'sfc'];
            }

            $hubDistance = $this->resolveByHubDelta(
                (int) $segment->from_area_town_id,
                (int) $segment->to_area_town_id,
                $segment->salesDcr?->territory_id
            );

            if ($hubDistance !== null) {
                return ['distance_km' => $hubDistance, 'distance_source' => 'sfc_hub'];
            }

            if ((int) $segment->from_area_town_id === (int) $segment->to_area_town_id) {
                return ['distance_km' => 5.0, 'distance_source' => 'local'];
            }
        }

        if ($segment->gps_distance_km !== null) {
            return ['distance_km' => (float) $segment->gps_distance_km, 'distance_source' => 'gps'];
        }

        return ['distance_km' => 0.0, 'distance_source' => 'manual'];
    }

    public function getPatchDistance(?int $fromAccountId, ?int $toAccountId, ?int $patchId): ?float
    {
        if ($fromAccountId === null || $toAccountId === null || $patchId === null) {
            return null;
        }

        $from = DB::table('account_master_patch')
            ->where('patch_id', $patchId)
            ->where('account_master_id', $fromAccountId)
            ->whereNull('deleted_at')
            ->first(['sequence_no', 'distance_km']);

        $to = DB::table('account_master_patch')
            ->where('patch_id', $patchId)
            ->where('account_master_id', $toAccountId)
            ->whereNull('deleted_at')
            ->first(['sequence_no', 'distance_km']);

        if ($from === null || $to === null) {
            return null;
        }

        if ($from->distance_km !== null && (int) $from->sequence_no + 1 === (int) $to->sequence_no) {
            return (float) $from->distance_km;
        }

        if ($to->distance_km !== null && (int) $to->sequence_no + 1 === (int) $from->sequence_no) {
            return (float) $to->distance_km;
        }

        return null;
    }

    protected function resolveByHubDelta(
        int $fromAreaTownId,
        int $toAreaTownId,
        ?int $territoryId
    ): ?float {
        $cities = CityPinCode::query()
            ->whereIn('id', [$fromAreaTownId, $toAreaTownId])
            ->get(['id', 'city_id'])
            ->keyBy('id');

        $fromHubCityId = $cities->get($fromAreaTownId)?->hub_city_id;
        $toHubCityId = $cities->get($toAreaTownId)?->hub_city_id;

        if ($fromHubCityId === null || $toHubCityId === null || (int) $fromHubCityId !== (int) $toHubCityId) {
            return null;
        }

        $fromToHub = $this->sfcLookupService->findDistance($fromAreaTownId, (int) $fromHubCityId, $territoryId);
        $toToHub = $this->sfcLookupService->findDistance($toAreaTownId, (int) $toHubCityId, $territoryId);

        if ($fromToHub === null || $toToHub === null) {
            return null;
        }

        return abs($fromToHub - $toToHub);
    }
}
