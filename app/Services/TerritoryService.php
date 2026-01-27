<?php

namespace App\Services;
use App\Models\Territory;

class TerritoryService
{
    public static function getUserTerritoryIds($user): array
    {
        return $user->employee
            ?->employmentDetail
            ?->organizationalUnits
            ?->flatMap(fn ($ou) => $ou->territories)
            ?->pluck('id')
            ?->unique()
            ?->toArray() ?? [];
    }

    public static function fromPinCode(?string $pinCode): ?int
    {
        if (! $pinCode) {
            return null;
        }

        return \App\Models\Territory::whereHas('cityPinCodes', function ($q) use ($pinCode) {
            $q->where('pin_code', $pinCode);
        })->value('id');
    }
}
