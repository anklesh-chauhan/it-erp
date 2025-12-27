<?php

namespace App\Services;

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
}
