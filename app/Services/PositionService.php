<?php

namespace App\Services;

class PositionService
{
    public static function getTerritoryIdsForUser($user): array
    {
        return $user->employee
            ?->positions
            ?->flatMap(fn ($position) => $position->territories)
            ?->pluck('id')
            ?->unique()
            ?->values()
            ?->toArray() ?? [];
    }
}
