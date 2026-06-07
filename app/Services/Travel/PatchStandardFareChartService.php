<?php

namespace App\Services\Travel;

use App\Models\AccountMaster;
use App\Models\Patch;
use App\Models\StandardFareChart;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class PatchStandardFareChartService
{
    public function ensureForPatch(Patch $patch): int
    {
        $patch->loadMissing([
            'companies.addresses',
            'territory',
        ]);

        $created = 0;

        foreach ($this->collectRoutePairs($patch) as [$fromAreaTownId, $toAreaTownId]) {
            if ($this->ensureRouteExists($fromAreaTownId, $toAreaTownId, $patch)) {
                $created++;
            }
        }

        return $created;
    }

    /**
     * @return Collection<int, array{0: int, 1: int}>
     */
    protected function collectRoutePairs(Patch $patch): Collection
    {
        $hqAreaTownId = $patch->city_pin_code_id;

        $customerAreaTownIds = $patch->companies
            ->map(fn (AccountMaster $account): ?int => $this->resolvePrimaryAreaTownId($account))
            ->filter()
            ->values();

        $pairs = collect();

        if ($hqAreaTownId !== null) {
            foreach ($customerAreaTownIds as $customerAreaTownId) {
                $pairs->push($this->normalizePair((int) $hqAreaTownId, (int) $customerAreaTownId));
            }
        }

        for ($index = 0; $index < $customerAreaTownIds->count() - 1; $index++) {
            $pairs->push($this->normalizePair(
                (int) $customerAreaTownIds[$index],
                (int) $customerAreaTownIds[$index + 1],
            ));
        }

        return $pairs
            ->filter(fn (array $pair): bool => $pair[0] !== $pair[1])
            ->unique(fn (array $pair): string => "{$pair[0]}-{$pair[1]}")
            ->values();
    }

    /**
     * @return array{0: int, 1: int}
     */
    protected function normalizePair(int $fromAreaTownId, int $toAreaTownId): array
    {
        return StandardFareChart::normalizeCityPair($fromAreaTownId, $toAreaTownId);
    }

    protected function ensureRouteExists(int $fromAreaTownId, int $toAreaTownId, Patch $patch): bool
    {
        [$fromId, $toId] = $this->normalizePair($fromAreaTownId, $toAreaTownId);

        $alreadyExists = StandardFareChart::query()
            ->where('from_area_town_id', $fromId)
            ->where('to_area_town_id', $toId)
            ->where('is_active', true)
            ->where(function ($query) use ($patch) {
                if ($patch->territory_id === null) {
                    $query->whereNull('territory_id');

                    return;
                }

                $query->where('territory_id', $patch->territory_id);
            })
            ->exists();

        if ($alreadyExists) {
            return false;
        }

        try {
            StandardFareChart::query()->create([
                'from_area_town_id' => $fromId,
                'to_area_town_id' => $toId,
                'territory_id' => $patch->territory_id,
                'patch_id' => $patch->id,
                'distance_km' => 0,
                'distance_source' => 'google_routes',
                'fare_amount' => 0,
                'is_active' => true,
            ]);
        } catch (ValidationException) {
            return false;
        }

        return true;
    }

    protected function resolvePrimaryAreaTownId(AccountMaster $account): ?int
    {
        $address = $account->addresses->firstWhere('is_primary', true)
            ?? $account->addresses->sortBy('sort')->first();

        return $address?->area_town_id;
    }
}
